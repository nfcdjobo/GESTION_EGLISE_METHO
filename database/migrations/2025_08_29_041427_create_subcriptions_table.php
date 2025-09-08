<?php

// =================================================================
// 2025_08_29_041427_create_subscriptions_table.php (nom corrigé)

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('souscripteur_id')->nullable();
            $table->foreign('souscripteur_id')->references('id')->on('users')->onDelete('set null');

            $table->uuid('fimeco_id')->nullable();
            $table->foreign('fimeco_id')->references('id')->on('fimecos')->onDelete('cascade');

            // Montants avec precision monétaire appropriée
            $table->decimal('montant_souscrit', 15, 2);
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->decimal('reste_a_payer', 15, 2);

            $table->enum('statut', [
                'active',
                'partiellement_payee',
                'completement_payee',
                'annulee',
                'suspendue'
            ])->default('active');

            $table->date('date_souscription');
            $table->date('date_echeance')->nullable();

            // Version pour gestion optimiste de la concurrence
            $table->bigInteger('version')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['souscripteur_id', 'fimeco_id'], 'unique_souscription_par_fimeco');
            $table->index(['statut', 'date_souscription']);
            $table->index('fimeco_id');
        });

        // Ajout des contraintes CHECK
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT check_montant_souscrit CHECK (montant_souscrit > 0)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT check_montant_paye CHECK (montant_paye >= 0)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT check_reste_a_payer CHECK (reste_a_payer >= 0)');
        // DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT check_coherence_montant CHECK (montant_paye <= montant_souscrit)');
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
