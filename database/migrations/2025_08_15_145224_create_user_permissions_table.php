<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            // Clé primaire UUID
            $table->uuid('id')->primary();

            // Relations principales
            $table->uuid('user_id')->comment('Référence vers l\'membres');
            $table->uuid('permission_id')->comment('Référence vers la permission');

            // État de la permission
            $table->boolean('is_granted')->default(true)->comment('Permission accordée ou révoquée');

            // Informations d'attribution
            $table->uuid('granted_by')->nullable()->comment('Membres qui a accordé la permission');
            $table->timestamp('granted_at')->useCurrent()->comment('Date d\'attribution de la permission');

            // Gestion de l'expiration
            $table->timestamp('expires_at')->nullable()->comment('Date d\'expiration (null = permanente)');
            $table->boolean('is_expired')->default(false)->comment('Flag calculé pour les permissions expirées');

            // Métadonnées
            $table->text('reason')->nullable()->comment('Raison de l\'attribution/révocation');
            $table->json('metadata')->nullable()->comment('Données supplémentaires en JSON');

            // Audit et traçabilité
            $table->uuid('revoked_by')->nullable()->comment('Membres qui a révoqué la permission');
            $table->timestamp('revoked_at')->nullable()->comment('Date de révocation');
            $table->text('revocation_reason')->nullable()->comment('Raison de la révocation');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes de clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('granted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('revoked_by')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances (sans CONCURRENTLY)
            $table->index(['user_id', 'is_granted'], 'idx_user_permissions_user_granted');
            $table->index(['permission_id', 'is_granted'], 'idx_user_permissions_permission_granted');
            $table->index(['user_id', 'permission_id', 'is_granted'], 'idx_user_permissions_user_permission_granted');
            $table->index(['expires_at', 'is_granted'], 'idx_user_permissions_expires_granted');
            $table->index(['granted_at', 'is_granted'], 'idx_user_permissions_granted_at');
            $table->index(['user_id', 'expires_at', 'is_granted'], 'idx_user_permissions_user_expires_granted');

            // Index pour l'audit
            $table->index(['granted_by', 'granted_at'], 'idx_user_permissions_granted_audit');
            $table->index(['revoked_by', 'revoked_at'], 'idx_user_permissions_revoked_audit');

            // Index partiel pour les permissions actives
            $table->index(['user_id', 'permission_id'], 'idx_user_permissions_active_only');

            // Contrainte unique pour éviter les doublons actifs
            $table->unique(['user_id', 'permission_id'], 'unique_user_permission_active');
        });

        // Fonction PostgreSQL pour mettre à jour is_expired
        DB::statement("
            CREATE OR REPLACE FUNCTION update_user_permission_expired()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.is_expired = CASE
                    WHEN NEW.expires_at IS NOT NULL AND NEW.expires_at <= NOW() THEN true
                    ELSE false
                END;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Trigger PostgreSQL pour mettre à jour is_expired automatiquement
        DB::statement("
            CREATE TRIGGER trigger_update_user_permissions_expired
                BEFORE INSERT OR UPDATE ON user_permissions
                FOR EACH ROW
                EXECUTE FUNCTION update_user_permission_expired();
        ");

        // Vue pour les permissions actives (facilite les requêtes)
        DB::statement("
            CREATE VIEW active_user_permissions AS
            SELECT up.*, p.name as permission_name, p.resource, p.action
            FROM user_permissions up
            JOIN permissions p ON up.permission_id = p.id
            WHERE up.is_granted = true
              AND up.deleted_at IS NULL
              AND (up.expires_at IS NULL OR up.expires_at > NOW())
              AND p.is_active = true
              AND p.deleted_at IS NULL
        ");

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE user_permissions IS 'Table de liaison entre membres et permissions avec gestion des expirations et audit'");

        // Commentaires sur les colonnes principales
        DB::statement("COMMENT ON COLUMN user_permissions.is_expired IS 'Flag calculé automatiquement par trigger'");
        DB::statement("COMMENT ON COLUMN user_permissions.metadata IS 'Données supplémentaires en JSON (contexte, conditions, etc.)'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la vue
        DB::statement("DROP VIEW IF EXISTS active_user_permissions");

        // Supprimer le trigger
        DB::statement("DROP TRIGGER IF EXISTS trigger_update_user_permissions_expired ON user_permissions");

        // Supprimer la fonction
        DB::statement("DROP FUNCTION IF EXISTS update_user_permission_expired()");

        Schema::dropIfExists('user_permissions');
    }
};
