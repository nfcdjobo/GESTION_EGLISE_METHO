<?php

// =================================================================
// 2025_08_29_042717_create_subscription_payments_table.php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');

            $table->decimal('montant', 15, 2);
            $table->decimal('ancien_reste', 15, 2); // Pour traçabilité
            $table->decimal('nouveau_reste', 15, 2);

            $table->enum('type_paiement', [
                'especes',
                'cheque',
                'virement',
                'carte',
                'mobile_money'
            ]);

            $table->string('reference_paiement', 100)->nullable(); // N° chèque, ref virement, etc.

            $table->enum('statut', [
                'en_attente',
                'valide',
                'refuse',
                'annule'
            ])->default('en_attente');

            $table->dateTime('date_paiement');
            $table->uuid('validateur_id')->nullable(); // Qui a validé le paiement
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            $table->dateTime('date_validation')->nullable();

            $table->text('commentaire')->nullable();

            // Version de la souscription au moment du paiement (pour détection de concurrence)
            $table->bigInteger('subscription_version_at_payment');

            $table->timestamps();
            $table->softDeletes();

            // Contraintes
            // $table->check('montant > 0');
            // $table->check('ancien_reste >= nouveau_reste');

            // Index pour performance
            $table->index(['subscription_id', 'date_paiement']);
            $table->index(['statut', 'date_paiement']);
            $table->index('reference_paiement');
        });

        // Ajout des contraintes CHECK après la création de la table
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT check_montant CHECK (montant > 0)');
        // DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT check_reste CHECK (ancien_reste >= nouveau_reste)');

    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
