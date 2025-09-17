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
        Schema::create('user_roles', function (Blueprint $table) {
            // Relations principales (table pivot)
            $table->uuid('user_id')->comment('ID de l\'membres');
            $table->uuid('role_id')->comment('ID du rôle');

            // Métadonnées d'attribution (optionnelles)
            $table->uuid('attribue_par')->nullable()->comment('Qui a attribué ce rôle');
            $table->timestamp('attribue_le')->useCurrent()->comment('Quand le rôle a été attribué');
            $table->timestamp('expire_le')->nullable()->comment('Date d\'expiration (optionnel pour rôles temporaires)');

            // État simple
            $table->boolean('actif')->default(true)->comment('Rôle actif ou non');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Clé primaire composée
            $table->primary(['user_id', 'role_id']);

            // Contraintes foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('attribue_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['user_id', 'actif'], 'idx_user_roles_user_actif');
            $table->index(['role_id', 'actif'], 'idx_user_roles_role_actif');
            $table->index('attribue_par', 'idx_user_roles_attribue_par');
            $table->index('expire_le', 'idx_user_roles_expiration');
        });

        // Commentaire sur la table
        // DB::statement("ALTER TABLE user_roles COMMENT = 'Table pivot : un membres peut avoir plusieurs rôles'");
         DB::statement("COMMENT ON TABLE user_roles IS 'Table pivot : un membres peut avoir plusieurs rôles';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
