<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Informations de base de l'église
            $table->string('nom_eglise');
            $table->string('telephone_1', 20);
            $table->string('telephone_2', 20)->nullable();
            $table->string('email_principal')->unique();
            $table->string('email_secondaire')->nullable();

            // Adresse complète
            $table->text('adresse');
            $table->string('ville');
            $table->string('commune')->nullable();
            $table->string('pays');
            $table->string('code_postal', 10)->nullable();

            // Médias
            $table->string('logo')->nullable(); // chemin vers le fichier logo
            $table->json('images_hero')->nullable(); // array d'images pour la page d'accueil

            // Contenu spirituel
            $table->text('verset_biblique')->nullable();
            $table->string('reference_verset')->nullable(); // ex: "Jean 3:16"
            $table->text('mission_statement')->nullable();
            $table->text('vision')->nullable();
            $table->text('description_eglise')->nullable();

            // Réseaux sociaux
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('website_url')->nullable();

            // Horaires de culte
            $table->json('horaires_cultes')->nullable(); // format JSON pour flexibilité

            // Informations supplémentaires de l'église
            $table->date('date_fondation')->nullable();
            $table->integer('nombre_membres')->nullable();
            $table->text('histoire_eglise')->nullable();

            // Autres paramètres
            $table->string('devise')->default('EUR');
            $table->string('langue')->default('fr');
            $table->string('fuseau_horaire')->default('Europe/Paris');
            $table->boolean('actif')->default(true);

            // Colonne pour la contrainte singleton
            $table->boolean('singleton')->default(true);

            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index('actif');
        });

        // Ajouter une contrainte unique sur la colonne singleton pour garantir une seule ligne
        DB::statement('ALTER TABLE parametres ADD CONSTRAINT parametres_singleton_unique UNIQUE (singleton)');

        // Insérer automatiquement la première ligne avec des valeurs par défaut
        DB::table('parametres')->insert([
            'nom_eglise' => 'Nom de votre église',
            'telephone_1' => '',
            'email_principal' => 'contact@eglise.com',
            'adresse' => '',
            'ville' => '',
            'pays' => 'France',
            'verset_biblique' => "Car Dieu a tant aimé le monde qu''il a donné son Fils unique, afin que quiconque croit en lui ne périsse point, mais qu''il ait la vie éternelle.",
            'reference_verset' => 'Jean 3:16',
            'actif' => true,
            'singleton' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
