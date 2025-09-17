<?php

// =================================================================
// 2025_08_29_043000_create_subscription_payment_logs_table.php
// Table d'audit pour traçabilité complète

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payment_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subscription_id');
            $table->uuid('payment_id')->nullable(); // Si lié à un paiement spécifique
            $table->uuid('user_id')->nullable(); // Qui a fait l'action

            $table->enum('action', [
                'souscription_creee',
                'paiement_ajoute',
                'paiement_valide',
                'paiement_refuse',
                'paiement_annule',
                'souscription_modifiee',
                'souscription_annulee'
            ]);

            $table->json('donnees_avant')->nullable(); // État avant modification
            $table->json('donnees_apres')->nullable();  // État après modification

            $table->decimal('ancien_montant_paye', 15, 2)->nullable();
            $table->decimal('nouveau_montant_paye', 15, 2)->nullable();

            $table->text('commentaire')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            // Index
            $table->index(['subscription_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payment_logs');
    }
};
