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
            $table->string('code_postal', 20)->nullable();

            // Médias
            $table->string('logo')->nullable(); // chemin vers le fichier logo
            $table->json('images_hero')->nullable(); /** Donnée json contenant un tableau d'objet dont les propriétés sont les suivantes: id, titre, url, active, ordre */



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

            // Programmes de l'église (remplace horaires_cultes)
            $table->json('programmes')->nullable(); // format JSON pour tous les programmes

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
            'nom_eglise' => 'CANAAN Belle Ville',
            'telephone_1' => '',
            'email_principal' => 'contact@eglise.com',
            'adresse' => 'Abobo belle ville après...',
            'ville' => 'Abidjzn',
            'pays' => 'Côte d\'Ivoire',
            'verset_biblique' => "Car Dieu a tant aimé le monde qu'il a donné son Fils unique, afin que quiconque croit en lui ne périsse point, mais qu'il ait la vie éternelle.",
            'reference_verset' => 'Jean 3:16',
            'programmes' => json_encode([
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440001',
                    'titre' => 'Cultes Dominicaux',
                    'description' => 'Rejoignez-nous chaque dimanche pour des moments de louange, de prière et d\'enseignement biblique enrichissant.',
                    'icone' => 'fas fa-praying-hands',
                    'type_horaire' => 'regulier',
                    'jour' => 'Dimanche',
                    'heure_debut' => '09:00',
                    'heure_fin' => '11:30',
                    'horaire_texte' => 'Dimanche : 9h00 - 11h30',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 1
                ],
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440002',
                    'titre' => 'Étude Biblique',
                    'description' => 'Approfondissez votre connaissance de la Parole de Dieu à travers nos études bibliques interactives.',
                    'icone' => 'fas fa-book-open',
                    'type_horaire' => 'regulier',
                    'jour' => 'Mercredi',
                    'heure_debut' => '18:00',
                    'heure_fin' => '19:30',
                    'horaire_texte' => 'Mercredi : 18h00 - 19h30',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 2
                ],
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440003',
                    'titre' => 'École du Dimanche',
                    'description' => 'Enseignement adapté aux enfants et adolescents pour grandir dans la foi chrétienne.',
                    'icone' => 'fas fa-child',
                    'type_horaire' => 'regulier',
                    'jour' => 'Dimanche',
                    'heure_debut' => '08:00',
                    'heure_fin' => '09:00',
                    'horaire_texte' => 'Dimanche : 8h00 - 9h00',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 3
                ],
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440004',
                    'titre' => 'Œuvres Sociales',
                    'description' => 'Actions communautaires, aide aux plus démunis et projets de développement local.',
                    'icone' => 'fas fa-heart',
                    'type_horaire' => 'permanent',
                    'jour' => null,
                    'heure_debut' => null,
                    'heure_fin' => null,
                    'horaire_texte' => 'Actions permanentes',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 4
                ],
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440005',
                    'titre' => 'Mariages & Baptêmes',
                    'description' => 'Célébration des moments importants de la vie chrétienne dans la joie et la communion.',
                    'icone' => 'fas fa-ring',
                    'type_horaire' => 'sur_rendez_vous',
                    'jour' => null,
                    'heure_debut' => null,
                    'heure_fin' => null,
                    'horaire_texte' => 'Sur rendez-vous',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 5
                ],
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440006',
                    'titre' => 'Chœur & Musique',
                    'description' => 'Groupes de louange et chorales pour magnifier le Seigneur par la musique.',
                    'icone' => 'fas fa-music',
                    'type_horaire' => 'regulier',
                    'jour' => 'Samedi',
                    'heure_debut' => '15:00',
                    'heure_fin' => '17:00',
                    'horaire_texte' => 'Samedi : 15h00 - 17h00',
                    'est_public' => true,
                    'est_actif' => true,
                    'ordre' => 6
                ]
            ]),
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
