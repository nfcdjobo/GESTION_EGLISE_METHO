<?php

// Migration: 2025_08_17_000000_create_permission_audit_logs_table.php

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
        Schema::create('permission_audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Action effectuée
            $table->string('action', 50); // created, updated, deleted, restored, granted, revoked, etc.

            // Modèle affecté
            $table->string('model_type', 100); // Permission, Role, UserPermission, UserRole, etc.
            $table->uuid('model_id'); // ID du modèle affecté

            // Utilisateur qui a effectué l'action
            $table->uuid('user_id')->nullable();
            $table->uuid('target_user_id')->nullable(); // Utilisateur cible (pour les attributions)

            // Changements
            $table->json('changes')->nullable(); // Nouvelles valeurs
            $table->json('original')->nullable(); // Valeurs originales

            // Contexte
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->uuid('session_id')->nullable();
            $table->json('context')->nullable(); // Contexte additionnel

            // Timestamps
            $table->timestamps();

            // Index
            $table->index('action');
            $table->index('model_type');
            $table->index('model_id');
            $table->index('user_id');
            $table->index('target_user_id');
            $table->index('created_at');
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_audit_logs');
    }
};

// Modèle: app/Models/PermissionAuditLog.php



