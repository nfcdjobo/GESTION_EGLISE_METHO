<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public const SET_NULL = 'set null';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametres_dons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('operateur', 50); // Augmenté pour plus de flexibilité
            $table->enum('type', ['virement_bancaire', 'carte_bancaire', 'mobile_money']);
            $table->string('numero_compte', 100); // Spécifié une longueur appropriée
            $table->string('logo')->nullable();
            $table->string('qrcode')->nullable(); // Changé en text pour les QR codes longs
            $table->boolean('statut')->default(false)->comment('Actif/Inactif');
            $table->boolean('publier')->default(false)->comment('Publié/Non publié');

            // Clés étrangères avec contraintes nullables appropriées
            $table->uuid('publier_par')->nullable();
            $table->foreign('publier_par')->references('id')->on('users')->onDelete(self::SET_NULL);

            $table->uuid('creer_par')->nullable();
            $table->foreign('creer_par')->references('id')->on('users')->onDelete(self::SET_NULL);

            $table->uuid('modifier_par')->nullable();
            $table->foreign('modifier_par')->references('id')->on('users')->onDelete(self::SET_NULL);

            $table->uuid('supprimer_par')->nullable();
            $table->foreign('supprimer_par')->references('id')->on('users')->onDelete(self::SET_NULL);

            $table->timestamps();
            $table->softDeletes();

            // Index pour améliorer les performances
            $table->index(['statut', 'publier']);
            $table->index('type');
            $table->index('operateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres_dons');
    }
};
