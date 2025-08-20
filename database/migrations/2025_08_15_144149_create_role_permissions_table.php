<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            // Relations principales (table pivot)
            $table->uuid('role_id')->comment('ID du rôle');
            $table->uuid('permission_id')->comment('ID de la permission');

            // Métadonnées d'attribution (optionnelles)
            $table->uuid('attribue_par')->nullable()->comment('Qui a attribué cette permission au rôle');
            $table->timestamp('attribue_le')->useCurrent()->comment('Quand la permission a été attribuée');
            $table->timestamp('expire_le')->nullable()->comment('Date d\'expiration (optionnel pour permissions temporaires)');

            // État simple
            $table->boolean('actif')->default(true)->comment('Permission active pour ce rôle');

            // Conditions spécifiques (optionnel)
            $table->json('conditions')->nullable()->comment('Conditions spécifiques pour cette attribution');
            $table->text('notes')->nullable()->comment('Notes sur l\'attribution de cette permission');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Clé primaire composée
            $table->primary(['role_id', 'permission_id']);

            // Contraintes foreign key
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('attribue_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['role_id', 'actif'], 'idx_role_permissions_role_actif');
            $table->index(['permission_id', 'actif'], 'idx_role_permissions_perm_actif');
            $table->index('attribue_par', 'idx_role_permissions_attribue_par');
            $table->index('expire_le', 'idx_role_permissions_expiration');
        });

        // Commentaire sur la table
        // DB::statement("ALTER TABLE role_permissions COMMENT = 'Table pivot : un rôle peut avoir plusieurs permissions'");
        DB::statement("COMMENT ON TABLE role_permissions IS 'Table pivot : un rôle peut avoir plusieurs permissions';");

        // Vue pour les permissions actives par rôle
        DB::statement("
            CREATE VIEW role_permissions_actifs AS
            SELECT
                rp.role_id,
                rp.permission_id,
                rp.attribue_le,
                rp.expire_le,
                r.name as nom_role,
                r.slug as code_role,
                r.level as niveau_role,
                p.name as nom_permission,
                p.slug as code_permission,
                p.resource,
                p.action,
                p.category as categorie_permission
            FROM role_permissions rp
            INNER JOIN roles r ON rp.role_id = r.id
            INNER JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.actif = true
                AND rp.deleted_at IS NULL
                AND r.deleted_at IS NULL
                AND p.is_active = true
                AND p.deleted_at IS NULL
                AND (rp.expire_le IS NULL OR rp.expire_le > NOW())
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression de la vue
        DB::statement("DROP VIEW IF EXISTS role_permissions_actifs");

        // Suppression de la table
        Schema::dropIfExists('role_permissions');
    }
};
