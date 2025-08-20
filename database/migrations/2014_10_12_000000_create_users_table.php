<?php

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
        Schema::create('users', function (Blueprint $table) {
            // Identifiant unique UUID
            $table->uuid('id')->primary();
            $table->uuid('classe_id')->nullable();


            // Informations personnelles de base
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['masculin', 'feminin']);

            // Informations de contact
            $table->string('telephone_1');
            $table->string('telephone_2')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable()->after('email');

            // Adresse
            $table->string('adresse_ligne_1');
            $table->string('adresse_ligne_2')->nullable();
            $table->string('ville');
            $table->string('code_postal')->nullable();
            $table->string('region')->nullable();
            $table->string('pays')->default('CI'); // Côte d'Ivoire par défaut

            // Informations familiales
            $table->enum('statut_matrimonial', ['celibataire', 'marie', 'divorce', 'veuf'])->default('celibataire');

            $table->integer('nombre_enfants')->default(0);

            // Informations professionnelles
            $table->string('profession')->nullable();
            $table->string('employeur')->nullable();

            // Informations d'église
            $table->date('date_adhesion')->nullable(); // Date d'adhésion
            $table->enum('statut_membre', ['actif', 'inactif', 'visiteur', 'nouveau_converti'])->default('visiteur');
            $table->enum('statut_bapteme', ['non_baptise', 'baptise', 'confirme'])->default('non_baptise');
            $table->date('date_bapteme')->nullable();
            $table->string('eglise_precedente')->nullable(); // Église précédente

            // Contact d'urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->string('contact_urgence_relation')->nullable();

            // Informations spirituelles
            $table->text('temoignage')->nullable(); // Témoignage de conversion
            $table->text('dons_spirituels')->nullable(); // Dons spirituels
            $table->text('demandes_priere')->nullable(); // Demandes de prière



            // Informations système
            $table->string('password');
            $table->boolean('actif')->default(true);
            $table->rememberToken()->after('password');

            // Photo de profil
            $table->string('photo_profil')->nullable();

            // Notes administratives
            $table->text('notes_admin')->nullable();

            // Timestamps et soft delete
            $table->timestamps();
            $table->softDeletes(); // Pour le soft delete

            $table->foreign('classe_id')->references('id')->on('classes');




            // Index pour optimiser les recherches
            $table->index(['statut_membre', 'actif']);
            $table->index(['prenom', 'nom']);
            $table->index('date_adhesion');
        });

        // Ajouter la contrainte de clé étrangère après la création de la table
        Schema::table('classes', function (Blueprint $table) {
            // Clés étrangères - CORRIGÉES
            $table->foreign('enseignant_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null'); // ← AJOUTÉ

            // Index pour optimiser les recherches
            $table->index('responsable_id');
            $table->index('enseignant_principal_id'); // ← AJOUTÉ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
