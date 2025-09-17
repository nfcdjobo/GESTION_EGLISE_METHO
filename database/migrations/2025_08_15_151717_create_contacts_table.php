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
        Schema::create('contacts', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base de l'église
            $table->string('nom_eglise', 200)->comment('Nom officiel de l-église');
            $table->string('denomination', 100)->nullable()->comment('Dénomination religieuse');
            $table->text('description_courte')->nullable()->comment('Description courte de l-église');
            $table->text('mission_vision')->nullable()->comment('Mission et vision de l-église');

            // Type de contact (permet plusieurs entrées)
            $table->enum('type_contact', [
                'principal',           // Contact principal
                'administratif',       // Contact administratif
                'pastoral',           // Contact pastoral
                'urgence',            // Contact d'urgence
                'jeunesse',           // Contact jeunesse
                'femmes',             // Contact ministère femmes
                'hommes',             // Contact ministère hommes
                'enfants',            // Contact école du dimanche
                'technique',          // Contact technique
                'media',              // Contact médias
                'finance',            // Contact finances
                'social'              // Contact œuvres sociales
            ])->default('principal')->comment('Type de contact');

            // Coordonnées téléphoniques
            $table->string('telephone_principal', 20)->nullable()->comment('Téléphone principal');
            $table->string('telephone_secondaire', 20)->nullable()->comment('Téléphone secondaire');
            $table->string('telephone_urgence', 20)->nullable()->comment('Téléphone d-urgence');
            $table->string('fax', 20)->nullable()->comment('Numéro de fax');
            $table->string('whatsapp', 20)->nullable()->comment('Numéro WhatsApp');

            // Coordonnées emails
            $table->string('email_principal')->nullable()->comment('Email principal');
            $table->string('email_administratif')->nullable()->comment('Email administratif');
            $table->string('email_pastoral')->nullable()->comment('Email pastoral');
            $table->string('email_info')->nullable()->comment('Email d-information');
            $table->string('email_presse')->nullable()->comment('Email presse/médias');

            // Adresse physique principale (siège)
            $table->text('adresse_complete')->nullable()->comment('Adresse complète du siège');
            $table->string('rue', 200)->nullable()->comment('Rue et numéro');
            $table->string('quartier', 100)->nullable()->comment('Quartier');
            $table->string('ville', 100)->nullable()->comment('Ville');
            $table->string('commune', 100)->nullable()->comment('Commune');
            $table->string('code_postal', 10)->nullable()->comment('Code postal');
            $table->string('region', 100)->nullable()->comment('Région');
            $table->string('pays', 100)->default('CI')->comment('Pays');

            // Géolocalisation
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude GPS');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude GPS');
            $table->text('indications_acces')->nullable()->comment('Indications pour accès');
            $table->text('points_repere')->nullable()->comment('Points de repère');

            // Réseaux sociaux
            $table->string('facebook_url')->nullable()->comment('Page Facebook');
            $table->string('facebook_handle')->nullable()->comment('@nom Facebook');
            $table->string('instagram_url')->nullable()->comment('Compte Instagram');
            $table->string('instagram_handle')->nullable()->comment('@nom Instagram');
            $table->string('tiktok_url')->nullable()->comment('Compte TikTok');
            $table->string('tiktok_handle')->nullable()->comment('@nom TikTok');
            $table->string('youtube_url')->nullable()->comment('Chaîne YouTube');
            $table->string('youtube_handle')->nullable()->comment('@nom YouTube');
            $table->string('twitter_url')->nullable()->comment('Compte Twitter/X');
            $table->string('twitter_handle')->nullable()->comment('@nom Twitter');
            $table->string('linkedin_url')->nullable()->comment('Page LinkedIn');
            $table->string('telegram_url')->nullable()->comment('Canal Telegram');

            // Site web et plateformes digitales
            $table->string('site_web_principal')->nullable()->comment('Site web principal');
            $table->string('site_web_secondaire')->nullable()->comment('Site web secondaire');
            $table->string('blog_url')->nullable()->comment('Blog officiel');
            $table->string('app_mobile_android')->nullable()->comment('App Android (Play Store)');
            $table->string('app_mobile_ios')->nullable()->comment('App iOS (App Store)');
            $table->string('podcast_url')->nullable()->comment('Podcast officiel');

            // Plateformes de streaming/diffusion
            $table->string('youtube_live_url')->nullable()->comment('Canal YouTube Live');
            $table->string('facebook_live_url')->nullable()->comment('Facebook Live');
            $table->string('zoom_meeting_id')->nullable()->comment('ID réunion Zoom récurrente');
            $table->string('google_meet_url')->nullable()->comment('Lien Google Meet');
            $table->string('radio_frequency')->nullable()->comment('Fréquence radio (si applicable)');
            $table->string('tv_channel')->nullable()->comment('Chaîne TV (si applicable)');

            // Horaires d'ouverture/disponibilité
            $table->json('horaires_bureau')->nullable()->comment('Horaires du bureau (JSON)');
            $table->json('horaires_cultes')->nullable()->comment('Horaires des cultes (JSON)');
            $table->text('horaires_speciaux')->nullable()->comment('Horaires spéciaux/fêtes');
            $table->boolean('disponible_24h')->default(false)->comment('Disponible 24h/24');

            // Informations légales et administratives
            $table->string('numero_siret')->nullable()->comment('Numéro SIRET/registre');
            $table->string('numero_rna')->nullable()->comment('Numéro RNA (associations)');
            $table->string('code_ape')->nullable()->comment('Code APE/secteur d-activité');
            $table->string('numero_tva')->nullable()->comment('Numéro TVA intracommunautaire');
            $table->date('date_creation')->nullable()->comment('Date de création de l-église');
            $table->string('statut_juridique')->nullable()->comment('Statut juridique');

            // Informations bancaires (publiques)
            $table->string('iban_dons')->nullable()->comment('IBAN pour les dons');
            $table->string('bic_swift')->nullable()->comment('Code BIC/SWIFT');
            $table->string('nom_banque')->nullable()->comment('Nom de la banque');
            $table->string('titulaire_compte')->nullable()->comment('Titulaire du compte');
            $table->string('mobile_money_orange')->nullable()->comment('Numéro Orange Money');
            $table->string('mobile_money_mtn')->nullable()->comment('Numéro MTN Money');
            $table->string('mobile_money_moov')->nullable()->comment('Numéro Moov Money');

            // Contacts de leadership
            $table->string('pasteur_principal')->nullable()->comment('Nom du pasteur principal');
            $table->string('telephone_pasteur')->nullable()->comment('Téléphone pasteur');
            $table->string('email_pasteur')->nullable()->comment('Email pasteur');
            $table->string('secretaire_general')->nullable()->comment('Nom du secrétaire général');
            $table->string('telephone_secretaire')->nullable()->comment('Téléphone secrétaire');
            $table->string('tresorier')->nullable()->comment('Nom du trésorier');
            $table->string('telephone_tresorier')->nullable()->comment('Téléphone trésorier');

            // Médias et ressources
            $table->string('logo_url')->nullable()->comment('URL du logo officiel');
            $table->string('photo_eglise_url')->nullable()->comment('Photo principale de l-église');
            $table->json('photos_galleries')->nullable()->comment('Galerie de photos (JSON)');
            $table->string('video_presentation_url')->nullable()->comment('Vidéo de présentation');

            // Langues et accessibilité
            $table->json('langues_parlees')->nullable()->comment('Langues parlées/services (JSON)');
            $table->boolean('accessibilite_handicap')->default(false)->comment('Accessible aux handicapés');
            $table->text('services_speciaux')->nullable()->comment('Services spéciaux proposés');
            $table->text('equipements_disponibles')->nullable()->comment('Équipements disponibles');

            // Paramètres de visibilité
            $table->boolean('visible_public')->default(true)->comment('Visible au public');
            $table->boolean('afficher_site_web')->default(true)->comment('Afficher sur le site web');
            $table->boolean('afficher_app_mobile')->default(true)->comment('Afficher sur l-app mobile');
            $table->boolean('partage_autorise')->default(true)->comment('Partage autorisé');

            // QR Codes et codes de contact
            $table->string('qr_code_contact')->nullable()->comment('QR code avec infos contact');
            $table->string('qr_code_wifi')->nullable()->comment('QR code WiFi');
            $table->string('code_court_sms')->nullable()->comment('Code court SMS');
            $table->string('hashtag_officiel')->nullable()->comment('Hashtag officiel (#)');

            // Urgence et secours
            $table->string('contact_urgence_medical')->nullable()->comment('Contact urgence médicale');
            $table->string('contact_police')->nullable()->comment('Contact police locale');
            $table->string('contact_pompiers')->nullable()->comment('Contact pompiers');
            $table->text('procedures_urgence')->nullable()->comment('Procédures d-urgence');

            // Informations supplémentaires
            $table->integer('capacite_accueil')->nullable()->comment('Capacité d-accueil totale');
            $table->integer('nombre_membres')->nullable()->comment('Nombre approximatif de membres');
            $table->date('derniere_mise_a_jour')->nullable()->comment('Dernière mise à jour des infos');
            $table->text('notes_complementaires')->nullable()->comment('Notes complémentaires');

            // Audit et gestion
            $table->uuid('responsable_contact_id')->nullable()->comment('Responsable de ces informations');
            $table->uuid('cree_par')->nullable()->comment('Membres qui a créé');
            $table->uuid('modifie_par')->nullable()->comment('Dernier membres ayant modifié');
            $table->boolean('verifie')->default(false)->comment('Informations vérifiées');
            $table->timestamp('derniere_verification')->nullable()->comment('Dernière vérification');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('responsable_contact_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les recherches
            $table->index('type_contact', 'idx_contacts_type');
            $table->index(['visible_public', 'type_contact'], 'idx_contacts_public');
            $table->index('ville', 'idx_contacts_ville');
            $table->index('telephone_principal', 'idx_contacts_tel');
            $table->index('email_principal', 'idx_contacts_email');
            $table->index(['latitude', 'longitude'], 'idx_contacts_geo');
            $table->index('derniere_verification', 'idx_contacts_verification');
        });

        // Commentaire sur la table (PostgreSQL syntax)
        DB::statement("COMMENT ON TABLE contacts IS 'Informations de contact et coordonnées de l''église'");

        // Vue pour les contacts publics
        DB::statement("
            CREATE VIEW contacts_publics AS
            SELECT
                c.id,
                c.nom_eglise,
                c.type_contact,
                c.telephone_principal,
                c.email_principal,
                c.adresse_complete,
                c.ville,
                c.site_web_principal,
                c.facebook_url,
                c.instagram_url,
                c.horaires_cultes,
                c.latitude,
                c.longitude,
                CONCAT(resp.prenom, ' ', resp.nom) as nom_responsable
            FROM contacts c
            LEFT JOIN users resp ON c.responsable_contact_id = resp.id
            WHERE c.visible_public = true
                AND c.deleted_at IS NULL
            ORDER BY c.type_contact, c.nom_eglise
        ");

        // Vue pour les réseaux sociaux
        DB::statement("
            CREATE VIEW reseaux_sociaux_eglise AS
            SELECT
                c.id,
                c.nom_eglise,
                c.facebook_url,
                c.facebook_handle,
                c.instagram_url,
                c.instagram_handle,
                c.tiktok_url,
                c.tiktok_handle,
                c.youtube_url,
                c.youtube_handle,
                c.twitter_url,
                c.twitter_handle,
                c.site_web_principal,
                c.hashtag_officiel
            FROM contacts c
            WHERE c.visible_public = true
                AND c.deleted_at IS NULL
                AND (
                    c.facebook_url IS NOT NULL
                    OR c.instagram_url IS NOT NULL
                    OR c.tiktok_url IS NOT NULL
                    OR c.youtube_url IS NOT NULL
                )
        ");

        // Vue pour les contacts d'urgence
        DB::statement("
            CREATE VIEW contacts_urgence AS
            SELECT
                c.id,
                c.nom_eglise,
                c.telephone_urgence,
                c.email_principal,
                c.contact_urgence_medical,
                c.contact_police,
                c.contact_pompiers,
                c.procedures_urgence,
                c.adresse_complete,
                c.latitude,
                c.longitude
            FROM contacts c
            WHERE c.visible_public = true
                AND c.deleted_at IS NULL
                AND (
                    c.telephone_urgence IS NOT NULL
                    OR c.contact_urgence_medical IS NOT NULL
                    OR c.procedures_urgence IS NOT NULL
                )
            ORDER BY c.type_contact
        ");

        // Vue pour les informations de dons
        DB::statement("
            CREATE VIEW informations_dons AS
            SELECT
                c.id,
                c.nom_eglise,
                c.iban_dons,
                c.bic_swift,
                c.nom_banque,
                c.titulaire_compte,
                c.mobile_money_orange,
                c.mobile_money_mtn,
                c.mobile_money_moov,
                c.email_principal,
                c.telephone_principal
            FROM contacts c
            WHERE c.visible_public = true
                AND c.deleted_at IS NULL
                AND (
                    c.iban_dons IS NOT NULL
                    OR c.mobile_money_orange IS NOT NULL
                    OR c.mobile_money_mtn IS NOT NULL
                    OR c.mobile_money_moov IS NOT NULL
                )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS informations_dons");
        DB::statement("DROP VIEW IF EXISTS contacts_urgence");
        DB::statement("DROP VIEW IF EXISTS reseaux_sociaux_eglise");
        DB::statement("DROP VIEW IF EXISTS contacts_publics");

        // Suppression de la table
        Schema::dropIfExists('contacts');
    }
};
