<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $titre Titre de l'annonce
 * @property string $contenu Contenu principal de l'annonce
 * @property string $type_annonce Type d'annonce
 * @property string $niveau_priorite Niveau de priorité
 * @property string $audience_cible Audience ciblée
 * @property \Illuminate\Support\Carbon|null $publie_le Date/heure de publication
 * @property \Illuminate\Support\Carbon|null $expire_le Date/heure d'expiration
 * @property \Illuminate\Support\Carbon|null $date_evenement Date de l'événement
 * @property bool $afficher_site_web Afficher sur le site web
 * @property bool $annoncer_culte Annoncer pendant le culte
 * @property string|null $contact_principal_id Contact principal
 * @property string|null $lieu_evenement Lieu de l'événement
 * @property string $statut Statut de l'annonce
 * @property string|null $cree_par Utilisateur créateur
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $auteur
 * @property-read \App\Models\User|null $contactPrincipal
 * @property-read string $badge_priorite
 * @property-read string $badge_statut
 * @property-read array $content_overview
 * @property-read bool $est_active
 * @property-read bool $est_expire
 * @property-read int|null $jours_restants
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce actives()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce parAudience(string $audience)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce parType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce pourCulte()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce pourSiteWeb()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce query()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce trieesParPriorite()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce urgentes()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereAfficherSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereAnnoncerCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereAudienceCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereContactPrincipalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereDateEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereExpireLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereLieuEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereNiveauPriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce wherePublieLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereTypeAnnonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Annonce withoutTrashed()
 */
	class Annonce extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nom
 * @property string|null $description
 * @property string|null $tranche_age
 * @property int|null $age_minimum
 * @property int|null $age_maximum
 * @property int $nombre_inscrits
 * @property array|null $responsables
 * @property array|null $programme
 * @property string|null $image_classe
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $est_complete
 * @property-read mixed $nom_complet
 * @property-read mixed $places_disponibles
 * @property-read mixed $pourcentage_remplissage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $membres
 * @property-read int|null $membres_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $membresActifs
 * @property-read int|null $membres_actifs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Classe actives()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe ageMaximum($age)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe ageMinimum($age)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe avecPlacesDisponibles($capaciteMax = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe parTrancheAge($tranche)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereAgeMaximum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereAgeMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereImageClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereNombreInscrits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereResponsables($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereTrancheAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classe withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Classe withoutTrashed()
 */
	class Classe extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nom_eglise Nom officiel de l-église
 * @property string|null $denomination Dénomination religieuse
 * @property string|null $description_courte Description courte de l-église
 * @property string|null $mission_vision Mission et vision de l-église
 * @property string $type_contact Type de contact
 * @property string|null $telephone_principal Téléphone principal
 * @property string|null $telephone_secondaire Téléphone secondaire
 * @property string|null $telephone_urgence Téléphone d-urgence
 * @property string|null $fax Numéro de fax
 * @property string|null $whatsapp Numéro WhatsApp
 * @property string|null $email_principal Email principal
 * @property string|null $email_administratif Email administratif
 * @property string|null $email_pastoral Email pastoral
 * @property string|null $email_info Email d-information
 * @property string|null $email_presse Email presse/médias
 * @property string|null $adresse_complete Adresse complète du siège
 * @property string|null $rue Rue et numéro
 * @property string|null $quartier Quartier
 * @property string|null $ville Ville
 * @property string|null $commune Commune
 * @property string|null $code_postal Code postal
 * @property string|null $region Région
 * @property string $pays Pays
 * @property string|null $latitude Latitude GPS
 * @property string|null $longitude Longitude GPS
 * @property string|null $indications_acces Indications pour accès
 * @property string|null $points_repere Points de repère
 * @property string|null $facebook_url Page Facebook
 * @property string|null $facebook_handle @nom Facebook
 * @property string|null $instagram_url Compte Instagram
 * @property string|null $instagram_handle @nom Instagram
 * @property string|null $tiktok_url Compte TikTok
 * @property string|null $tiktok_handle @nom TikTok
 * @property string|null $youtube_url Chaîne YouTube
 * @property string|null $youtube_handle @nom YouTube
 * @property string|null $twitter_url Compte Twitter/X
 * @property string|null $twitter_handle @nom Twitter
 * @property string|null $linkedin_url Page LinkedIn
 * @property string|null $telegram_url Canal Telegram
 * @property string|null $site_web_principal Site web principal
 * @property string|null $site_web_secondaire Site web secondaire
 * @property string|null $blog_url Blog officiel
 * @property string|null $app_mobile_android App Android (Play Store)
 * @property string|null $app_mobile_ios App iOS (App Store)
 * @property string|null $podcast_url Podcast officiel
 * @property string|null $youtube_live_url Canal YouTube Live
 * @property string|null $facebook_live_url Facebook Live
 * @property string|null $zoom_meeting_id ID réunion Zoom récurrente
 * @property string|null $google_meet_url Lien Google Meet
 * @property string|null $radio_frequency Fréquence radio (si applicable)
 * @property string|null $tv_channel Chaîne TV (si applicable)
 * @property array|null $horaires_bureau Horaires du bureau (JSON)
 * @property array|null $horaires_cultes Horaires des cultes (JSON)
 * @property string|null $horaires_speciaux Horaires spéciaux/fêtes
 * @property bool $disponible_24h Disponible 24h/24
 * @property string|null $numero_siret Numéro SIRET/registre
 * @property string|null $numero_rna Numéro RNA (associations)
 * @property string|null $code_ape Code APE/secteur d-activité
 * @property string|null $numero_tva Numéro TVA intracommunautaire
 * @property \Illuminate\Support\Carbon|null $date_creation Date de création de l-église
 * @property string|null $statut_juridique Statut juridique
 * @property string|null $iban_dons IBAN pour les dons
 * @property string|null $bic_swift Code BIC/SWIFT
 * @property string|null $nom_banque Nom de la banque
 * @property string|null $titulaire_compte Titulaire du compte
 * @property string|null $mobile_money_orange Numéro Orange Money
 * @property string|null $mobile_money_mtn Numéro MTN Money
 * @property string|null $mobile_money_moov Numéro Moov Money
 * @property string|null $pasteur_principal Nom du pasteur principal
 * @property string|null $telephone_pasteur Téléphone pasteur
 * @property string|null $email_pasteur Email pasteur
 * @property string|null $secretaire_general Nom du secrétaire général
 * @property string|null $telephone_secretaire Téléphone secrétaire
 * @property string|null $tresorier Nom du trésorier
 * @property string|null $telephone_tresorier Téléphone trésorier
 * @property string|null $logo_url URL du logo officiel
 * @property string|null $photo_eglise_url Photo principale de l-église
 * @property array|null $photos_galleries Galerie de photos (JSON)
 * @property string|null $video_presentation_url Vidéo de présentation
 * @property array|null $langues_parlees Langues parlées/services (JSON)
 * @property bool $accessibilite_handicap Accessible aux handicapés
 * @property string|null $services_speciaux Services spéciaux proposés
 * @property string|null $equipements_disponibles Équipements disponibles
 * @property bool $visible_public Visible au public
 * @property bool $afficher_site_web Afficher sur le site web
 * @property bool $afficher_app_mobile Afficher sur l-app mobile
 * @property bool $partage_autorise Partage autorisé
 * @property string|null $qr_code_contact QR code avec infos contact
 * @property string|null $qr_code_wifi QR code WiFi
 * @property string|null $code_court_sms Code court SMS
 * @property string|null $hashtag_officiel Hashtag officiel (#)
 * @property string|null $contact_urgence_medical Contact urgence médicale
 * @property string|null $contact_police Contact police locale
 * @property string|null $contact_pompiers Contact pompiers
 * @property string|null $procedures_urgence Procédures d-urgence
 * @property int|null $capacite_accueil Capacité d-accueil totale
 * @property int|null $nombre_membres Nombre approximatif de membres
 * @property \Illuminate\Support\Carbon|null $derniere_mise_a_jour Dernière mise à jour des infos
 * @property string|null $notes_complementaires Notes complémentaires
 * @property string|null $responsable_contact_id Responsable de ces informations
 * @property string|null $cree_par Utilisateur qui a créé
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property bool $verifie Informations vérifiées
 * @property \Illuminate\Support\Carbon|null $derniere_verification Dernière vérification
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createur
 * @property-read mixed $adresse_formatee
 * @property-read mixed $mobile_money
 * @property-read mixed $reseaux_sociaux
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $responsableContact
 * @method static \Illuminate\Database\Eloquent\Builder|Contact avecGeo()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact avecReseauxSociaux()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact parVille($ville)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact publics()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact verifies()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAccessibiliteHandicap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAdresseComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAfficherAppMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAfficherSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAppMobileAndroid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAppMobileIos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereBicSwift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereBlogUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCapaciteAccueil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCodeApe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCodeCourtSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCommune($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactPolice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactPompiers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactUrgenceMedical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDenomination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDerniereMiseAJour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDerniereVerification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDescriptionCourte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDisponible24h($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailAdministratif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailPasteur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailPastoral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailPresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmailPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEquipementsDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFacebookHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFacebookLiveUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereGoogleMeetUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereHashtagOfficiel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereHorairesBureau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereHorairesCultes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereHorairesSpeciaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIbanDons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIndicationsAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereInstagramHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLanguesParlees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLinkedinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMissionVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMobileMoneyMoov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMobileMoneyMtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMobileMoneyOrange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNomBanque($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNomEglise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNombreMembres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNotesComplementaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNumeroRna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNumeroSiret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereNumeroTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePartageAutorise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePasteurPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhotoEgliseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhotosGalleries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePodcastUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePointsRepere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereProceduresUrgence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereQrCodeContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereQrCodeWifi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereRadioFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereResponsableContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereRue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereSecretaireGeneral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereServicesSpeciaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereSiteWebPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereSiteWebSecondaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereStatutJuridique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelegramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephonePasteur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephonePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephoneSecondaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephoneSecretaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephoneTresorier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTelephoneUrgence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTiktokHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTiktokUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTitulaireCompte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTresorier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTvChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTwitterHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTypeContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereVerifie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereVideoPresentationUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereVisiblePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereYoutubeHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereYoutubeLiveUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereYoutubeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereZoomMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $programme_id
 * @property string $titre Titre/Thème du culte
 * @property string|null $description Description détaillée du culte
 * @property \Illuminate\Support\Carbon $date_culte Date du culte
 * @property \Illuminate\Support\Carbon $heure_debut Heure de début prévue
 * @property \Illuminate\Support\Carbon|null $heure_fin Heure de fin prévue
 * @property \Illuminate\Support\Carbon|null $heure_debut_reelle Heure de début réelle
 * @property \Illuminate\Support\Carbon|null $heure_fin_reelle Heure de fin réelle
 * @property string $type_culte Type de culte
 * @property string $categorie Catégorie du culte
 * @property string $lieu Lieu du culte
 * @property string|null $adresse_lieu Adresse complète si lieu externe
 * @property int|null $capacite_prevue Capacité prévue de participants
 * @property string|null $pasteur_principal_id Pasteur principal du culte
 * @property string|null $predicateur_id Prédicateur/Orateur principal
 * @property string|null $responsable_culte_id Responsable de l'organisation
 * @property string|null $dirigeant_louange_id Dirigeant de louange
 * @property array|null $equipe_culte Équipe du culte (JSON: rôles et personnes)
 * @property string|null $titre_message Titre du message/prédication
 * @property string|null $resume_message Résumé du message
 * @property string|null $passage_biblique Passage biblique principal
 * @property array|null $versets_cles Versets clés (JSON array)
 * @property string|null $plan_message Plan détaillé du message
 * @property array|null $ordre_service Ordre de service détaillé (JSON)
 * @property array|null $cantiques_chantes Liste des cantiques/chants (JSON)
 * @property \Illuminate\Support\Carbon|null $duree_louange Durée de la louange
 * @property \Illuminate\Support\Carbon|null $duree_message Durée du message
 * @property \Illuminate\Support\Carbon|null $duree_priere Durée des prières
 * @property int|null $nombre_participants Nombre total de participants
 * @property int|null $nombre_adultes Nombre d'adultes
 * @property int|null $nombre_enfants Nombre d'enfants
 * @property int|null $nombre_jeunes Nombre de jeunes
 * @property int|null $nombre_nouveaux Nombre de nouveaux visiteurs
 * @property int $nombre_conversions Nombre de conversions
 * @property int $nombre_baptemes Nombre de baptêmes
 * @property array|null $detail_offrandes Détail des offrandes par type (JSON). Tout types d'offrande et leur montant respectif: offrande ordinaire sont obligatoire cette offrande qui contient les offrandes de chaque classe communautaire et les offrandes de culte d'enfant, les spéciales eux se situent dans un cadre imprévu il peut ne pas avoir d'offrande spéciale et il peut en avoir plusieurs
 * @property string|null $offrande_totale Total des offrandes
 * @property string|null $dime_totale Total des dîmes
 * @property string|null $responsable_finances_id Responsable du comptage
 * @property bool $est_enregistre Culte enregistré (audio/vidéo)
 * @property string|null $lien_enregistrement_audio Lien vers l'enregistrement audio
 * @property string|null $lien_enregistrement_video Lien vers l'enregistrement vidéo
 * @property string|null $lien_diffusion_live Lien de diffusion en direct
 * @property array|null $photos_culte Photos du culte (JSON array de liens)
 * @property bool $diffusion_en_ligne Diffusé en ligne
 * @property string $statut Statut du culte
 * @property bool $est_public Culte ouvert au public
 * @property bool $necessite_invitation Culte sur invitation uniquement
 * @property string|null $meteo Conditions météorologiques
 * @property string|null $atmosphere Atmosphère spirituelle ressentie
 * @property string|null $notes_pasteur Notes du pasteur
 * @property string|null $notes_organisateur Notes de l'organisateur
 * @property string|null $temoignages Témoignages recueillis
 * @property string|null $points_forts Points forts du culte
 * @property string|null $points_amelioration Points à améliorer
 * @property string|null $demandes_priere Demandes de prière exprimées
 * @property string|null $note_globale Note globale du culte (1-10)
 * @property string|null $note_louange Note de la louange (1-10)
 * @property string|null $note_message Note du message (1-10)
 * @property string|null $note_organisation Note de l'organisation (1-10)
 * @property string|null $cree_par Utilisateur qui a créé l'enregistrement
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createur
 * @property-read \App\Models\User|null $dirigeantLouange
 * @property-read string|null $atmosphere_libelle
 * @property-read string $categorie_libelle
 * @property-read array $content_overview
 * @property-read mixed $description_formatted
 * @property-read string|null $duree_totale
 * @property-read bool $is_a_venir
 * @property-read bool $is_termine
 * @property-read mixed $notes_formatted
 * @property-read mixed $plan_message_formatted
 * @property-read mixed $points_formatted
 * @property-read mixed $resume_message_formatted
 * @property-read string $statut_libelle
 * @property-read mixed $temoignages_formatted
 * @property-read string $type_culte_libelle
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $pasteurPrincipal
 * @property-read \App\Models\User|null $predicateur
 * @property-read \App\Models\Programme $programme
 * @property-read \App\Models\User|null $responsableCulte
 * @property-read \App\Models\User|null $responsableFinances
 * @method static \Illuminate\Database\Eloquent\Builder|Culte aVenir()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte parDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte public()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte query()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte searchContent($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte termines()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereAdresseLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereAtmosphere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereCantiquesChantes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereCapacitePrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDateCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDemandesPriere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDetailOffrandes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDiffusionEnLigne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDimeTotale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDirigeantLouangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDureeLouange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDureeMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereDureePriere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereEquipeCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereEstEnregistre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereEstPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereHeureDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereHeureDebutReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereHeureFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereHeureFinReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereLienDiffusionLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereLienEnregistrementAudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereLienEnregistrementVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereMeteo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNecessiteInvitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreAdultes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreBaptemes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreEnfants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreJeunes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreNouveaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNombreParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNoteGlobale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNoteLouange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNoteMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNoteOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNotesOrganisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereNotesPasteur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereOffrandeTotale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereOrdreService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePassageBiblique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePasteurPrincipalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePhotosCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePlanMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePointsAmelioration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePointsForts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte wherePredicateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereResponsableCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereResponsableFinancesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereResumeMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereTemoignages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereTitreMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereTypeCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte whereVersetsCles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Culte withContent()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Culte withoutTrashed()
 */
	class Culte extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $moisson_id
 * @property string $categorie Type d'engagement
 * @property string|null $donateur_id Existe si catégorie est une personne physique
 * @property string|null $nom_entite Nom de l'entité morale si applicable
 * @property string|null $description Description de l'engagement
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $adresse
 * @property string $cible C'est l'objectif à atteindre mais on peut aller au-delà
 * @property string $montant_solde L'ensemble des montants déjà collectés
 * @property string $reste Reste à solder
 * @property string $montant_supplementaire Existe lorsque montant_solde > cible
 * @property string $collecter_par
 * @property \Illuminate\Support\Carbon $collecter_le
 * @property string $creer_par
 * @property \Illuminate\Support\Carbon|null $date_echeance Date limite pour honorer l'engagement
 * @property \Illuminate\Support\Carbon|null $date_rappel Date de rappel automatique
 * @property array|null $editeurs Historique des modifications en JSONB
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $categorie_libelle
 * @property-read \App\Models\User $collecteur
 * @property-read \App\Models\User $createur
 * @property-read mixed $doit_etre_rappele
 * @property-read \App\Models\User|null $donateur
 * @property-read mixed $est_en_retard
 * @property-read mixed $est_entite_morale
 * @property-read mixed $jours_retard
 * @property-read \App\Models\Moisson $moisson
 * @property-read mixed $niveau_urgence
 * @property-read mixed $niveau_urgence_libelle
 * @property-read mixed $nom_donateur
 * @property-read mixed $objectif_atteint
 * @property-read mixed $pourcentage_realise
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson aRappeler(?\Carbon\Carbon $date = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson actif()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson avecMoisson()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson enRetard()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson entiteMorale()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson entitePhysique()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson objectifAtteint()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson parCategorie(string $categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson parNiveauUrgence(string $niveau)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson query()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson rechercheTexte(string $terme)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCollecterLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCollecterPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereCreerPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereDateEcheance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereDateRappel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereDonateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereEditeurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereMoissonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereNomEntite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EngagementMoisson withoutTrashed()
 */
	class EngagementMoisson extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string|null $country_name
 * @property-read string $formatted_user_agent
 * @property-read string $short_url
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log byPath(string $path)
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log inLastDays(int $days)
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log notFromBots()
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log today()
 * @method static \Illuminate\Database\Eloquent\Builder|Error404Log unresolved()
 */
	class Error404Log extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $titre Titre de lévénement
 * @property string|null $sous_titre Sous-titre de lévénement
 * @property string|null $description Description détaillée
 * @property string|null $resume_court Résumé court pour aperçu
 * @property string $slug URL slug pour lévénement
 * @property string $type_evenement Type d événement
 * @property string $categorie Catégorie de lévénement
 * @property \Illuminate\Support\Carbon $date_debut Date de début
 * @property \Illuminate\Support\Carbon|null $date_fin Date de fin
 * @property \Illuminate\Support\Carbon $heure_debut Heure de début
 * @property \Illuminate\Support\Carbon|null $heure_fin Heure de fin
 * @property bool $evenement_multi_jours Événement sur plusieurs jours
 * @property array|null $horaires_detailles Horaires détaillés (JSON)
 * @property string $fuseau_horaire Fuseau horaire
 * @property string $lieu_nom Nom du lieu
 * @property string|null $lieu_adresse Adresse complète du lieu
 * @property string|null $lieu_ville Ville
 * @property string $lieu_pays Pays
 * @property string|null $instructions_acces Instructions d accès
 * @property string|null $transport_organise Transport organisé
 * @property int|null $capacite_totale Capacité totale
 * @property int $places_reservees Places réservées
 * @property int|null $places_disponibles Places disponibles
 * @property int $nombre_inscrits Nombre d inscrits
 * @property int|null $nombre_participants Nombre réel de participants
 * @property bool $liste_attente Liste d attente activée
 * @property string $audience_cible Audience ciblée
 * @property int|null $age_minimum Âge minimum
 * @property int|null $age_maximum Âge maximum
 * @property bool $ouvert_public Ouvert au public
 * @property bool $necessite_invitation Nécessite une invitation
 * @property bool $inscription_requise Inscription obligatoire
 * @property \Illuminate\Support\Carbon|null $date_ouverture_inscription Date d ouverture des inscriptions
 * @property \Illuminate\Support\Carbon|null $date_fermeture_inscription Date de fermeture des inscriptions
 * @property bool $inscription_payante Inscription payante
 * @property string|null $prix_inscription Prix d inscription
 * @property array|null $tarifs_categories Tarifs par catégorie (JSON)
 * @property string|null $conditions_inscription Conditions d inscription
 * @property string|null $organisateur_principal_id Organisateur principal
 * @property string|null $coordinateur_id Coordinateur
 * @property string|null $responsable_logistique_id Responsable logistique
 * @property string|null $responsable_communication_id Responsable communication
 * @property array|null $equipe_organisation Équipe d organisation (JSON)
 * @property array|null $partenaires Partenaires (JSON)
 * @property array|null $sponsors Sponsors (JSON)
 * @property array|null $programme_detaille Programme détaillé (JSON)
 * @property array|null $intervenants Intervenants (JSON)
 * @property string|null $objectifs Objectifs de lévénement
 * @property string|null $programme_enfants Programme spécial enfants
 * @property array|null $activites_annexes Activités annexes (JSON)
 * @property string $statut Statut de lévénement
 * @property string $priorite Niveau de priorité
 * @property string|null $annule_par Qui a annulé lévénement
 * @property \Illuminate\Support\Carbon|null $annule_le Date d annulation
 * @property string|null $motif_annulation Motif d annulation
 * @property \Illuminate\Support\Carbon|null $nouvelle_date Nouvelle date si reporté
 * @property string|null $message_promotion Message promotionnel
 * @property string|null $hashtag_officiel Hashtag officiel
 * @property array|null $canaux_communication Canaux de communication (JSON)
 * @property bool $publication_site_web Publier sur le site web
 * @property bool $publication_reseaux_sociaux Publier sur réseaux sociaux
 * @property bool $envoi_newsletter Envoyer par newsletter
 * @property string|null $image_principale Image principale
 * @property array|null $galerie_images Galerie d images (JSON)
 * @property string|null $video_presentation Vidéo de présentation
 * @property array|null $documents_joints Documents joints (JSON)
 * @property string|null $site_web_evenement Site web dédié
 * @property bool $diffusion_en_ligne Diffusion en ligne
 * @property string|null $lien_diffusion Lien de diffusion
 * @property bool $enregistrement_autorise Enregistrement autorisé
 * @property string|null $lien_enregistrement Lien vers lenregistrement
 * @property bool $photos_autorisees Photos autorisées
 * @property string|null $budget_prevu Budget prévisionnel
 * @property string|null $cout_realise Coût réalisé
 * @property string|null $recettes_inscriptions Recettes des inscriptions
 * @property string|null $recettes_sponsors Recettes des sponsors
 * @property array|null $detail_budget Détail du budget (JSON)
 * @property string|null $responsable_finances Responsable des finances
 * @property string|null $note_globale Note globale (1-10)
 * @property string|null $note_organisation Note organisation
 * @property string|null $note_contenu Note contenu
 * @property string|null $note_lieu Note lieu
 * @property string|null $taux_satisfaction Taux de satisfaction (%)
 * @property string|null $feedback_participants Feedback des participants
 * @property string|null $points_positifs Points positifs
 * @property string|null $points_amelioration Points à améliorer
 * @property bool $evenement_recurrent Événement récurrent
 * @property string|null $frequence_recurrence Fréquence de récurrence
 * @property \Illuminate\Support\Carbon|null $prochaine_occurrence Prochaine occurrence
 * @property string|null $cree_par Utilisateur qui a créé lévénement
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property \Illuminate\Support\Carbon|null $derniere_activite Dernière activité
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $annulePar
 * @property-read \App\Models\User|null $coordinateur
 * @property-read \App\Models\User|null $createur
 * @property-read mixed $duree
 * @property-read mixed $jours_restants
 * @property-read mixed $pourcentage_remplissage
 * @property-read mixed $statut_inscription
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $organisateurPrincipal
 * @property-read \App\Models\User|null $responsableCommunication
 * @property-read \App\Models\User|null $responsableLogistique
 * @method static \Illuminate\Database\Eloquent\Builder|Event aVenir()
 * @method static \Illuminate\Database\Eloquent\Builder|Event enCours()
 * @method static \Illuminate\Database\Eloquent\Builder|Event inscriptionsOuvertes()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event parCategorie($categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|Event parLieu($ville)
 * @method static \Illuminate\Database\Eloquent\Builder|Event parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Event planifies()
 * @method static \Illuminate\Database\Eloquent\Builder|Event publics()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event termines()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereActivitesAnnexes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAgeMaximum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAgeMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAnnuleLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAnnulePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAudienceCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereBudgetPrevu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCanauxCommunication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCapaciteTotale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereConditionsInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCoordinateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCoutRealise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDateFermetureInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDateOuvertureInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDerniereActivite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDetailBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDiffusionEnLigne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDocumentsJoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEnregistrementAutorise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEnvoiNewsletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEquipeOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEvenementMultiJours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEvenementRecurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFeedbackParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFrequenceRecurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFuseauHoraire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereGalerieImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereHashtagOfficiel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereHeureDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereHeureFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereHorairesDetailles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImagePrincipale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereInscriptionPayante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereInscriptionRequise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereInstructionsAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIntervenants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLienDiffusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLienEnregistrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLieuAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLieuNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLieuPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLieuVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereListeAttente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereMessagePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereMotifAnnulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNecessiteInvitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNombreInscrits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNombreParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNoteContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNoteGlobale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNoteLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNoteOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNouvelleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereObjectifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganisateurPrincipalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOuvertPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePartenaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePhotosAutorisees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePlacesDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePlacesReservees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePointsAmelioration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePointsPositifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePrixInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereProchaineOccurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereProgrammeDetaille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereProgrammeEnfants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePublicationReseauxSociaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePublicationSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRecettesInscriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRecettesSponsors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereResponsableCommunicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereResponsableFinances($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereResponsableLogistiqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereResumeCourt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSiteWebEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSousTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSponsors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTarifsCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTauxSatisfaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTransportOrganise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTypeEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereVideoPresentation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id Ici on a l'identifiant de la fimeco. Aussi dans une même période on ne peut créer une seule fimeco. Deux ou plisueur ne doivent pas être enregistrées lorsqu'il y'a une fimeco qui n'est pas encore cloturée
 * @property string|null $responsable_id
 * @property string $nom C'est le nom de la fimeco qui doit respecter cette nommenclature FIMECO-ANNEEFIN-CANAAN-BELLEVILLE  Ici ANNEEFIN est l'année de la date de fin (la colonne fin)
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $debut Date à laquelle la fimeco commence
 * @property \Illuminate\Support\Carbon $fin La date à laquelle la fimeco prend fin.
 * @property string $cible Le montant cible: c'est un montant prévu. En un mot c'est l'objectif à atteindre et cet objectif doit forcement être atteint avant la cloture de la fimeco. Tnat que le montant_solde n'est as égale ou superieur à la cible dont ne doit pas pouvoir cloturer la fimeco
 * @property string $montant_solde Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par les souscripteurs et ce montant vient de la table subscriptions. Mise à jour automatique
 * @property string $reste C'est l'ensemble des montants non soldés. Mise à jour automatique
 * @property string $montant_supplementaire Le montant supplémentaire rentre en jeu lorsque montant_solde est supérieur à la cible. Mise à jour automatique
 * @property string $progression Progression ou évolution de montant soldé en %. Et ça peut aller au dela des 100% puisque le montant_solde peut etre superieur à la cible
 * @property string $statut_global Mise à jour automatique: tres_faible <=25%, 25% < en_cours <= 75%, 75% < presque_atteint <= 99,99% et objectif_atteint >= 100%
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read bool $a_paiements_supplementaires
 * @property-read bool $en_retard
 * @property-read int $jours_restants
 * @property-read bool $objectif_atteint
 * @property-read bool $objectif_largement_depasse
 * @property-read string $progression_formattee
 * @property-read float $taux_depassement
 * @property-read \App\Models\User|null $responsable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptionsActives
 * @property-read int|null $subscriptions_actives_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptionsAvecSupplements
 * @property-read int|null $subscriptions_avec_supplements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptionsCompletes
 * @property-read int|null $subscriptions_completes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco actifs()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco enCours()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco objectifAtteint()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco periode($debut = null, $fin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco recherche($terme)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereProgression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereStatutGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Fimeco withoutTrashed()
 */
	class Fimeco extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $culte_id Culte associé à la transaction
 * @property string|null $donateur_id Membre qui a fait le don
 * @property string|null $collecteur_id Personne qui a collecté
 * @property string|null $validateur_id Personne qui a validé
 * @property string $numero_transaction Numéro unique de transaction
 * @property \Illuminate\Support\Carbon $date_transaction Date de la transaction
 * @property \Illuminate\Support\Carbon|null $heure_transaction Heure de la transaction
 * @property string $montant Montant de la transaction
 * @property string $devise Devise
 * @property string $type_transaction Type de transaction
 * @property string $categorie Catégorie de transaction
 * @property string|null $nom_donateur_anonyme Nom si donateur non membre
 * @property string|null $contact_donateur Contact du donateur (tél/email)
 * @property bool $est_anonyme Don anonyme
 * @property bool $est_membre Donateur est membre de l église
 * @property string $mode_paiement Mode de paiement
 * @property string|null $reference_paiement Référence de paiement
 * @property array|null $details_paiement Détails supplémentaires (JSON)
 * @property string|null $description_don_nature Description du don en nature
 * @property string|null $valeur_estimee Valeur estimée du don en nature
 * @property string|null $destination Destination ou projet bénéficiaire
 * @property string|null $projet_id Projet spécifique bénéficiaire
 * @property bool $est_flechee Offrande fléchée pour un usage spécifique
 * @property string|null $instructions_donateur Instructions particulières du donateur
 * @property string $statut Statut de la transaction
 * @property \Illuminate\Support\Carbon|null $validee_le Date et heure de validation
 * @property string|null $motif_annulation Motif d annulation/remboursement
 * @property string|null $notes_validation Notes de validation
 * @property string|null $numero_recu Numéro de reçu fiscal
 * @property bool $recu_demande Reçu fiscal demandé
 * @property bool $recu_emis Reçu fiscal émis
 * @property \Illuminate\Support\Carbon|null $date_emission_recu Date d émission du reçu
 * @property string|null $fichier_recu Chemin vers le fichier reçu
 * @property bool $est_recurrente Transaction récurrente
 * @property string|null $frequence_recurrence Fréquence de récurrence
 * @property \Illuminate\Support\Carbon|null $prochaine_echeance Prochaine échéance
 * @property string|null $transaction_parent_id Transaction parent si récurrente
 * @property string|null $occasion_speciale Occasion spéciale (Noël, Pâques, etc.)
 * @property string|null $lieu_collecte Lieu de collecte (église principale, annexe, etc.)
 * @property string|null $cree_par Utilisateur créateur
 * @property string|null $modifie_par Dernier modificateur
 * @property \Illuminate\Support\Carbon|null $derniere_verification Dernière vérification comptable
 * @property string|null $verifie_par Vérificateur comptable
 * @property string|null $notes_comptable Notes du responsable financier
 * @property bool $deductible_impots Don déductible des impôts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $collecteur
 * @property-read \App\Models\User|null $createur
 * @property-read \App\Models\Culte|null $culte
 * @property-read \App\Models\User|null $donateur
 * @property-read mixed $contact_donateur_complet
 * @property-read mixed $est_don_nature
 * @property-read mixed $jours_depuis_transaction
 * @property-read mixed $jours_depuis_validation
 * @property-read mixed $mode_paiement_libelle
 * @property-read mixed $montant_format
 * @property-read mixed $nom_donateur
 * @property-read mixed $statut_libelle
 * @property-read mixed $type_transaction_libelle
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\Projet|null $projet
 * @property-read Fonds|null $transactionParent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Fonds> $transactionsEnfants
 * @property-read int|null $transactions_enfants_count
 * @property-read \App\Models\User|null $validateur
 * @property-read \App\Models\User|null $verificateur
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds annulees()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds anonymes()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds avecRecu()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds dimes()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds dons()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds especes()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds flechees()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds mobileMoney()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds offrandes()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds parCulte($culteId)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds parDonateur($donateurId)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds parMois($annee, $mois)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds recurrentes()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds recusEmis()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds validees()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereCollecteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereContactDonateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDateEmissionRecu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDateTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDeductibleImpots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDerniereVerification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDescriptionDonNature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDetailsPaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDevise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereDonateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereEstAnonyme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereEstFlechee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereEstMembre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereEstRecurrente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereFichierRecu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereFrequenceRecurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereHeureTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereInstructionsDonateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereLieuCollecte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereModePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereMotifAnnulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereNomDonateurAnonyme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereNotesComptable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereNotesValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereNumeroRecu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereNumeroTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereOccasionSpeciale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereProchaineEcheance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereProjetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereRecuDemande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereRecuEmis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereReferencePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereTransactionParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereTypeTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereValeurEstimee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereValidateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereValideeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds whereVerifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Fonds withoutTrashed()
 */
	class Fonds extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $inscrit_id ID de l'utilisateur inscrit
 * @property string $event_id ID de l'événement
 * @property string|null $cree_par ID de l'utilisateur qui a créé l'inscription
 * @property \Illuminate\Support\Carbon|null $cree_le Date et heure de création
 * @property string|null $modifie_par ID du dernier utilisateur ayant modifié
 * @property string|null $supprimer_par ID de l'utilisateur qui a supprimé
 * @property string|null $annule_par ID de l'utilisateur qui a annulé
 * @property \Illuminate\Support\Carbon|null $annule_le Date et heure d'annulation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $annulateur
 * @property-read \App\Models\User|null $createur
 * @property-read \App\Models\Event $event
 * @property-read mixed $date_inscription
 * @property-read mixed $est_active
 * @property-read mixed $est_annulee
 * @property-read mixed $est_auto_inscription
 * @property-read mixed $est_inscription_administrative
 * @property-read mixed $est_supprimee
 * @property-read mixed $statut
 * @property-read mixed $temps_depuis_annulation
 * @property-read mixed $temps_depuis_inscription
 * @property-read \App\Models\User $inscrit
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $suppresseur
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent actives()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent annulees()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent autoInscriptions()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent inscriptionsAdministratives()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent parCreateur($createurId)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent parEvent($eventId)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent parInscrit($inscritId)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent recentes($jours = 7)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent supprimees()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereAnnuleLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereAnnulePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereCreeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereInscritId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereSupprimerPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InscriptionEvent withoutTrashed()
 */
	class InscriptionEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $culte_id Culte concerné
 * @property string|null $reunion_id Réunion concernée
 * @property string $intervenant_id Personne qui intervient
 * @property string $titre Titre de l'intervention
 * @property string $type_intervention Type d'intervention
 * @property \Illuminate\Support\Carbon|null $heure_debut Heure de début
 * @property int $duree_minutes Durée en minutes
 * @property int|null $ordre_passage Ordre dans le programme
 * @property string|null $description Description de l'intervention
 * @property string|null $passage_biblique Passage biblique de référence
 * @property string $statut Statut de l'intervention
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Culte|null $culte
 * @property-read mixed $heure_fin
 * @property-read mixed $statut_label
 * @property-read mixed $type_intervention_label
 * @property-read \App\Models\User $intervenant
 * @property-read \App\Models\Reunion|null $reunion
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention ordonneesParPassage()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention parIntervenant($intervenantId)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention pourCulte($culteId)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention pourReunion($reunionId)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention query()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention statut($statut)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention type($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereDureeMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereHeureDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereIntervenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereOrdrePassage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention wherePassageBiblique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereReunionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereTypeIntervention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Intervention withoutTrashed()
 */
	class Intervention extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 */
	class Module extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id ID généré automatiquement
 * @property string $theme Le thème de la prédication
 * @property \Illuminate\Support\Carbon $date La date de la célébration de la moisson
 * @property string $cible Le montant cible: c'est un montant prévu.
 * @property string $montant_solde Le montant soldé: c'est l'ensemble de tous les fonds collectés et ces fonds viennent des tables passages moissons, vente_moissons et engagement_moissons. Le montant total peut aller au-delà du cible, en dessous du cible ou égal au cible. Mise à jour automatique
 * @property string $reste C'est l'ensemble des montants non soldés. Mise à jour automatique
 * @property string $montant_supplementaire Le montant supplémentaire existe si cible inférieur au montant_total. Mise à jour automatique
 * @property array|null $passages_bibliques Les passages bibliques en JSONB contenant le livre chapitre allant de x à y mais le y est optionnel
 * @property string $culte_id Le culte
 * @property string $creer_par Celui qui fait l'enregistrement
 * @property array|null $editeurs Historique des modifications en JSONB avec l'identifiant de celui qui a effectué la mise à jour, date de mise à jour
 * @property bool $status Statut de la moisson
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $createur
 * @property-read \App\Models\Culte $culte
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EngagementMoisson> $engagementMoissons
 * @property-read int|null $engagement_moissons_count
 * @property-read array $passages_bibliques_formatted
 * @property-read mixed $objectif_atteint
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PassageMoisson> $passageMoissons
 * @property-read int|null $passage_moissons_count
 * @property-read mixed $pourcentage_realise
 * @property-read mixed $statut_progression
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VenteMoisson> $venteMoissons
 * @property-read int|null $vente_moissons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson actif()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson avecStatistiques()
 * @method static \Database\Factories\MoissonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson objectifAtteint()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson parDate($dateDebut = null, $dateFin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson parStatutProgression($statut)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereCreerPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereEditeurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson wherePassagesBibliques($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Moisson withoutTrashed()
 */
	class Moisson extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $culte_id Culte associé
 * @property string|null $event_id Événement associé
 * @property string|null $intervention_id Intervention associée
 * @property string|null $reunion_id Réunion associée
 * @property string $titre Titre du média
 * @property string|null $description Description du média
 * @property string|null $legende Légende/caption du média
 * @property array|null $tags Tags/mots-clés (JSON array)
 * @property string $type_media Type de média
 * @property string $categorie Catégorie du média
 * @property string $nom_fichier_original Nom original du fichier
 * @property string $nom_fichier_stockage Nom du fichier en stockage
 * @property string $chemin_fichier Chemin complet vers le fichier
 * @property string|null $url_publique URL publique d'accès
 * @property string|null $miniature Chemin vers la miniature
 * @property string $type_mime Type MIME du fichier
 * @property string $extension Extension du fichier
 * @property int $taille_fichier Taille en octets
 * @property string|null $hash_fichier Hash SHA-256 du fichier
 * @property array|null $metadonnees_exif Métadonnées EXIF (pour images)
 * @property int|null $largeur Largeur en pixels
 * @property int|null $hauteur Hauteur en pixels
 * @property string|null $orientation Orientation de l'image
 * @property int|null $duree_secondes Durée en secondes
 * @property int|null $bitrate Bitrate en kbps
 * @property string|null $codec Codec utilisé
 * @property string|null $resolution Résolution (ex: 1920x1080)
 * @property int|null $fps Images par seconde (vidéo)
 * @property \Illuminate\Support\Carbon|null $date_prise Date de prise/création du média
 * @property string|null $lieu_prise Lieu de prise
 * @property string|null $photographe Photographe/créateur
 * @property string|null $appareil Appareil utilisé
 * @property array|null $parametres_capture Paramètres de capture (JSON)
 * @property string $licence Type de licence
 * @property bool $usage_public Autorisé pour usage public
 * @property bool $usage_site_web Autorisé sur le site web
 * @property bool $usage_reseaux_sociaux Autorisé sur réseaux sociaux
 * @property bool $usage_commercial Usage commercial autorisé
 * @property string|null $restrictions_usage Restrictions d'usage spécifiques
 * @property string $statut_moderation Statut de modération
 * @property string|null $modere_par Modérateur
 * @property \Illuminate\Support\Carbon|null $modere_le Date de modération
 * @property string|null $commentaire_moderation Commentaire du modérateur
 * @property bool $est_visible Média visible
 * @property bool $est_featured Média mis en avant
 * @property bool $est_archive Média archivé
 * @property \Illuminate\Support\Carbon|null $date_publication Date de publication
 * @property \Illuminate\Support\Carbon|null $date_expiration Date d'expiration
 * @property string $niveau_acces Niveau d'accès requis
 * @property bool $necessite_connexion Connexion requise
 * @property array|null $groupes_autorises Groupes autorisés (JSON array)
 * @property int $nombre_vues Nombre de vues
 * @property int $nombre_telechargements Nombre de téléchargements
 * @property int $nombre_partages Nombre de partages
 * @property int $nombre_likes Nombre de likes
 * @property int $nombre_commentaires Nombre de commentaires
 * @property \Illuminate\Support\Carbon|null $derniere_vue Dernière vue
 * @property string $service_stockage Service de stockage utilisé
 * @property array|null $emplacements_backup Emplacements de backup (JSON)
 * @property bool $backup_automatique Backup automatique activé
 * @property \Illuminate\Support\Carbon|null $derniere_sauvegarde Dernière sauvegarde
 * @property string|null $alt_text Texte alternatif pour SEO
 * @property string|null $titre_seo Titre SEO
 * @property string|null $description_seo Description SEO
 * @property string|null $slug Slug pour URL
 * @property string $statut_traitement Statut de traitement
 * @property array|null $versions_disponibles Versions disponibles (JSON)
 * @property bool $generer_miniatures Générer miniatures automatiquement
 * @property array|null $formats_convertis Formats convertis disponibles
 * @property string $qualite Niveau de qualité
 * @property string|null $note_qualite Note de qualité (1-10)
 * @property bool $contenu_sensible Contenu sensible
 * @property string|null $avertissement Avertissement si contenu sensible
 * @property string $telecharge_par Utilisateur qui a téléchargé
 * @property string|null $cree_par Utilisateur créateur
 * @property string|null $modifie_par Dernier utilisateur modificateur
 * @property array|null $historique_modifications Historique des modifications
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Culte|null $culte
 * @property-read \App\Models\Event|null $event
 * @property-read mixed $categorie_label
 * @property-read array $content_overview
 * @property-read mixed $dimensions_formatee
 * @property-read mixed $duree_formatee
 * @property-read mixed $est_audio
 * @property-read mixed $est_image
 * @property-read mixed $est_video
 * @property-read mixed $evenement_parent
 * @property-read mixed $niveau_acces_label
 * @property-read mixed $nom_evenement_parent
 * @property-read mixed $statut_moderation_label
 * @property-read mixed $taille_formatee
 * @property-read mixed $type_evenement_parent
 * @property-read mixed $type_media_label
 * @property-read mixed $url_complete
 * @property-read mixed $url_miniature
 * @property-read \App\Models\Intervention|null $intervention
 * @property-read \App\Models\User|null $moderator
 * @property-read \App\Models\Reunion|null $reunion
 * @property-read \App\Models\User $uploadedBy
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia approuve()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia featured()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia forEvent($eventType, $eventId)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia ofCategory($category)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia ofType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia popular()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia public()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia recent($days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia visible()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereAppareil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereAvertissement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereBackupAutomatique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereBitrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCheminFichier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCodec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCommentaireModeration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereContenuSensible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDateExpiration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDatePrise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDatePublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDerniereSauvegarde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDerniereVue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDescriptionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereDureeSecondes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereEmplacementsBackup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereEstArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereEstFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereEstVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereFormatsConvertis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereFps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereGenererMiniatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereGroupesAutorises($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereHashFichier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereHauteur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereHistoriqueModifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereInterventionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereLargeur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereLegende($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereLicence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereLieuPrise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereMetadonneesExif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereMiniature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereModereLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereModerePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNecessiteConnexion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNiveauAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNomFichierOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNomFichierStockage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNombreCommentaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNombreLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNombrePartages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNombreTelechargements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNombreVues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereNoteQualite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereParametresCapture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia wherePhotographe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereQualite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereRestrictionsUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereReunionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereServiceStockage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereStatutModeration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereStatutTraitement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTailleFichier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTelechargePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTitreSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTypeMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereTypeMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUrlPublique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUsageCommercial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUsagePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUsageReseauxSociaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereUsageSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia whereVersionsDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Multimedia withoutTrashed()
 */
	class Multimedia extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $participant_id ID de l'utilisateur participant
 * @property string $culte_id ID du culte
 * @property string $statut_presence Type de présence du participant
 * @property string $type_participation Mode de participation
 * @property string|null $heure_arrivee Heure réelle d'arrivée
 * @property string|null $heure_depart Heure réelle de départ
 * @property string $role_culte Rôle du participant dans ce culte
 * @property bool $presence_confirmee Présence confirmée par un responsable
 * @property string|null $confirme_par ID de la personne qui a confirmé la présence
 * @property \Illuminate\Support\Carbon|null $confirme_le Date et heure de confirmation
 * @property bool $premiere_visite Est-ce la première visite de cette personne?
 * @property string|null $accompagne_par ID du membre accompagnateur
 * @property bool $demande_contact_pastoral Demande un contact pastoral
 * @property bool $interesse_bapteme Intéressé par le baptême
 * @property bool $souhaite_devenir_membre Souhaite devenir membre
 * @property string|null $notes_responsable Notes du responsable sur la participation
 * @property string|null $commentaires_participant Commentaires du participant
 * @property string|null $enregistre_par ID de la personne qui a enregistré
 * @property \Illuminate\Support\Carbon|null $enregistre_le Date et heure d'enregistrement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $accompagnateur
 * @property-read \App\Models\User|null $confirmateur
 * @property-read \App\Models\Culte $culte
 * @property-read \App\Models\User|null $enregistreur
 * @property-read string|null $duree_participation
 * @property-read string|null $heure_arrivee_formattee
 * @property-read string|null $heure_depart_formattee
 * @property-read bool $is_participation_complete
 * @property-read bool $necessite_suivi
 * @property-read string $nom_participant
 * @property-read string $role_culte_libelle
 * @property-read string $statut_presence_libelle
 * @property-read string $titre_culte
 * @property-read string $type_participation_libelle
 * @property-read \App\Models\User $participant
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte confirmees()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte enLigne()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte necessitantSuivi()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parCulte(string $culteId)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parParticipant(string $participantId)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parRole(string $role)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parStatut(string $statut)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte parType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte physique()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte premiereVisite()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte recentes(int $jours = 30)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereAccompagnePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereCommentairesParticipant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereConfirmeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereConfirmePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereCulteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereDemandeContactPastoral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereEnregistreLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereEnregistrePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereHeureArrivee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereHeureDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereInteresseBapteme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereNotesResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte wherePremiereVisite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte wherePresenceConfirmee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereRoleCulte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereSouhaiteDevenirMembre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereStatutPresence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereTypeParticipation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantCulte withoutTrashed()
 */
	class ParticipantCulte extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $moisson_id
 * @property string $categorie Catégorie de passage
 * @property string|null $classe_id Si le type est le passage de la classe communautaire alors classe_id prend l'identifiant de la classe sinon null
 * @property string $cible C'est l'objectif à atteindre mais on peut aller au-delà
 * @property string $montant_solde L'ensemble des montants déjà collectés
 * @property string $reste Reste à solder
 * @property string $montant_supplementaire Existe lorsque montant_solde > cible
 * @property string $collecter_par
 * @property \Illuminate\Support\Carbon $collecte_le
 * @property string $creer_par
 * @property array|null $editeurs Historique des modifications en JSONB
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $categorie_libelle
 * @property-read \App\Models\Classe|null $classe
 * @property-read \App\Models\User $collecteur
 * @property-read \App\Models\User $createur
 * @property-read mixed $est_classe_communautaire
 * @property-read \App\Models\Moisson $moisson
 * @property-read mixed $objectif_atteint
 * @property-read mixed $pourcentage_realise
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson actif()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson avecMoisson()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson classeCommunautaire()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson enRetard()
 * @method static \Database\Factories\PassageMoissonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson objectifAtteint()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson parCategorie(string $categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson parPeriode($dateDebut = null, $dateFin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson query()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereClasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCollecteLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCollecterPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereCreerPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereEditeurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereMoissonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PassageMoisson withoutTrashed()
 */
	class PassageMoisson extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon $attempted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereAttemptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordChangeAttempt withoutTrashed()
 */
	class PasswordChangeAttempt extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name Nom affiché de la permission
 * @property string $slug Identifiant unique pour la permission
 * @property string|null $description Description détaillée de la permission
 * @property string|null $resource Entité/ressource concernée (ex: users, posts)
 * @property string $action Action autorisée sur la ressource
 * @property string $guard_name Guard utilisé (web, api, etc.)
 * @property string|null $category Catégorie de permission pour groupement
 * @property int $priority Priorité de la permission (0-255)
 * @property bool $is_active Permission active/inactive
 * @property bool $is_system Permission système (non modifiable)
 * @property array|null $conditions Conditions supplémentaires en JSON
 * @property string|null $created_by Utilisateur qui a créé la permission
 * @property string|null $updated_by Dernier utilisateur ayant modifié
 * @property \Illuminate\Support\Carbon|null $last_used_at Dernière utilisation de la permission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createur
 * @property-read mixed $nom_complet
 * @property-read \App\Models\User|null $modificateur
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission actives()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission parAction($action)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission parCategorie($category)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission parGuard($guard)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission parRessource($resource)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission systeme()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutTrashed()
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $action_name
 * @property-read mixed $description
 * @property-read mixed $formatted_changes
 * @property-read mixed $model
 * @property-read \App\Models\User|null $targetUser
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog byAction($action)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog byModelType($modelType)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog byTargetUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog byUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog inPeriod($startDate, $endDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionAuditLog recent($days = 7)
 */
	class PermissionAuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nom_programme Nom du programme
 * @property string|null $description Description du programme
 * @property string $code_programme Code unique du programme
 * @property string $type_programme Type de programme
 * @property string $frequence Fréquence du programme
 * @property \Illuminate\Support\Carbon|null $date_debut Date de début du programme
 * @property \Illuminate\Support\Carbon|null $date_fin Date de fin du programme
 * @property \Illuminate\Support\Carbon|null $heure_debut Heure de début
 * @property \Illuminate\Support\Carbon|null $heure_fin Heure de fin
 * @property array|null $jours_semaine Jours de la semaine [1,2,3,4,5,6,7]
 * @property string|null $lieu_principal Lieu principal du programme
 * @property string|null $responsable_principal_id Responsable principal du programme
 * @property string $audience_cible Audience ciblée
 * @property string $statut Statut du programme
 * @property string|null $notes Notes supplémentaires
 * @property string|null $cree_par Utilisateur créateur
 * @property string|null $modifie_par Dernier modificateur
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createurMembres
 * @property-read mixed $horaires
 * @property-read mixed $jours_semaine_texte
 * @property-read mixed $nom_complet
 * @property-read string $statut_badge
 * @property-read \App\Models\User|null $modificateurMembres
 * @property-read \App\Models\User|null $responsablePrincipal
 * @method static \Illuminate\Database\Eloquent\Builder|Programme actifs()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme enCours()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme parAudience($audience)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme parFrequence($frequence)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme query()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereAudienceCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereCodeProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereFrequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereHeureDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereHeureFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereJoursSemaine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereLieuPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereNomProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereResponsablePrincipalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereTypeProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Programme withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Programme withoutTrashed()
 */
	class Programme extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nom_projet Nom du projet
 * @property string $code_projet Code unique du projet
 * @property string|null $description Description détaillée du projet
 * @property string|null $objectif Objectifs du projet
 * @property string|null $contexte Contexte et justification
 * @property string $type_projet Type de projet
 * @property string $categorie Catégorie du projet
 * @property string|null $budget_prevu Budget prévisionnel total
 * @property string $budget_collecte Montant déjà collecté
 * @property string $budget_depense Montant déjà dépensé
 * @property string|null $budget_minimum Budget minimum pour démarrer
 * @property string $devise Devise du budget
 * @property array|null $detail_budget Détail du budget par poste (JSON)
 * @property array|null $sources_financement Sources de financement (JSON)
 * @property \Illuminate\Support\Carbon|null $date_creation Date de création du projet
 * @property \Illuminate\Support\Carbon|null $date_debut Date de début du projet
 * @property \Illuminate\Support\Carbon|null $date_fin_prevue Date de fin prévue
 * @property \Illuminate\Support\Carbon|null $date_fin_reelle Date de fin réelle
 * @property int|null $duree_prevue_jours Durée prévue en jours
 * @property int|null $duree_reelle_jours Durée réelle en jours
 * @property string|null $responsable_id Responsable principal du projet
 * @property string|null $coordinateur_id Coordinateur du projet
 * @property string|null $chef_projet_id Chef de projet
 * @property array|null $equipe_projet Équipe du projet (JSON)
 * @property array|null $partenaires Partenaires du projet (JSON)
 * @property array|null $beneficiaires Bénéficiaires du projet (JSON)
 * @property string|null $localisation Localisation du projet
 * @property string|null $adresse_complete Adresse complète
 * @property string|null $ville Ville
 * @property string|null $region Région
 * @property string $pays Pays
 * @property string|null $latitude Latitude GPS
 * @property string|null $longitude Longitude GPS
 * @property string $statut Statut du projet
 * @property string $priorite Niveau de priorité
 * @property string $pourcentage_completion Pourcentage davancement
 * @property string|null $derniere_activite Dernière activité enregistrée
 * @property \Illuminate\Support\Carbon|null $derniere_mise_a_jour Date de dernière mise à jour
 * @property string|null $approuve_par Qui a approuvé le projet
 * @property \Illuminate\Support\Carbon|null $approuve_le Date dapprobation
 * @property string|null $commentaires_approbation Commentaires dapprobation
 * @property bool $necessite_approbation Nécessite une approbation
 * @property array|null $objectifs_mesurables Objectifs mesurables (JSON)
 * @property array|null $indicateurs_succes Indicateurs de succès (JSON)
 * @property array|null $risques_identifies Risques identifiés (JSON)
 * @property array|null $mesures_mitigation Mesures de mitigation (JSON)
 * @property array|null $documents_joints Documents joints (JSON)
 * @property array|null $photos_projet Photos du projet (JSON)
 * @property string|null $site_web Site web du projet
 * @property array|null $liens_utiles Liens utiles (JSON)
 * @property string|null $manuel_procedure Manuel de procédure
 * @property bool $visible_public Visible au public
 * @property bool $ouvert_aux_dons Ouvert aux dons
 * @property string|null $message_promotion Message promotionnel
 * @property string|null $image_principale Image principale du projet
 * @property array|null $canaux_communication Canaux de communication (JSON)
 * @property string|null $resultats_obtenus Résultats obtenus
 * @property string|null $impact_communaute Impact sur la communauté
 * @property string|null $lecons_apprises Leçons apprises
 * @property string|null $recommandations Recommandations
 * @property string|null $note_satisfaction Note de satisfaction (1-10)
 * @property string|null $feedback_beneficiaires Feedback des bénéficiaires
 * @property bool $necessite_suivi Nécessite un suivi post-projet
 * @property \Illuminate\Support\Carbon|null $prochaine_evaluation Date de prochaine évaluation
 * @property string|null $plan_suivi Plan de suivi
 * @property array|null $projet_lie Projets liés (JSON)
 * @property bool $conforme_reglementation Conforme à la réglementation
 * @property string|null $autorisations_requises Autorisations requises
 * @property bool $audit_requis Audit requis
 * @property string|null $observations_audit Observations daudit
 * @property bool $projet_recurrent Projet récurrent
 * @property string|null $frequence_recurrence Fréquence de récurrence
 * @property string|null $projet_parent_id Projet parent si récurrent
 * @property array|null $metadonnees Métadonnées supplémentaires (JSON)
 * @property string|null $reference_externe Référence externe
 * @property string|null $integration_systemes Intégration avec dautres systèmes
 * @property string|null $notes_responsable Notes du responsable
 * @property string|null $notes_admin Notes administratives
 * @property string|null $historique_modifications Historique des modifications
 * @property string|null $cree_par Utilisateur qui a créé le projet
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property \Illuminate\Support\Carbon|null $derniere_activite_date Date de dernière activité
 * @property string|null $derniere_activite_par Auteur de la dernière activité
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approbateur
 * @property-read \App\Models\User|null $chefProjet
 * @property-read \App\Models\User|null $coordinateur
 * @property-read \App\Models\User|null $createur
 * @property-read \App\Models\User|null $derniereActivitePar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fonds> $fonds
 * @property-read int|null $fonds_count
 * @property-read mixed $budget_format
 * @property-read array $content_overview
 * @property-read mixed $description_formatted
 * @property-read mixed $equipe_projet_noms
 * @property-read mixed $est_approuve
 * @property-read mixed $est_en_retard
 * @property-read mixed $est_finance
 * @property-read mixed $jours_restants
 * @property-read mixed $montant_restant
 * @property-read mixed $nom_complet
 * @property-read mixed $notes_formatted
 * @property-read mixed $plan_message_formatted
 * @property-read mixed $points_formatted
 * @property-read mixed $pourcentage_financement
 * @property-read mixed $priorite_libelle
 * @property-read mixed $resume_message_formatted
 * @property-read mixed $statut_libelle
 * @property-read mixed $temoignages_formatted
 * @property-read mixed $type_projet_libelle
 * @property-read \App\Models\User|null $modificateur
 * @property-read Projet|null $projetParent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Projet> $projetsEnfants
 * @property-read int|null $projets_enfants_count
 * @property-read \App\Models\User|null $responsable
 * @method static \Illuminate\Database\Eloquent\Builder|Projet actifs()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet annules()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet approuves()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enAttenteApprobation()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enAttenteDisponibles()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enCours()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enPlanification()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enRechercheFinancement()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet enRetard()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet necessitantSuivi()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet ouvertsAuxDons()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parCategorie($categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parPriorite($priorite)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parRegion($region)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parResponsable($responsableId)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet parVille($ville)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet recurrents()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet searchContent($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet suspendus()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet termines()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet visiblesPublic()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereAdresseComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereApprouveLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereApprouvePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereAuditRequis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereAutorisationsRequises($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereBeneficiaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereBudgetCollecte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereBudgetDepense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereBudgetMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereBudgetPrevu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCanauxCommunication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereChefProjetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCodeProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCommentairesApprobation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereConformeReglementation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereContexte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCoordinateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDateFinPrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDateFinReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDerniereActivite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDerniereActiviteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDerniereActivitePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDerniereMiseAJour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDetailBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDevise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDocumentsJoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDureePrevueJours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereDureeReelleJours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereEquipeProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereFeedbackBeneficiaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereFrequenceRecurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereHistoriqueModifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereImagePrincipale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereImpactCommunaute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereIndicateursSucces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereIntegrationSystemes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereLeconsApprises($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereLiensUtiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereLocalisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereManuelProcedure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereMessagePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereMesuresMitigation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereMetadonnees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNecessiteApprobation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNecessiteSuivi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNomProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNoteSatisfaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereNotesResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereObjectif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereObjectifsMesurables($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereObservationsAudit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereOuvertAuxDons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePartenaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePhotosProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePlanSuivi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePourcentageCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet wherePriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereProchaineEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereProjetLie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereProjetParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereProjetRecurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereRecommandations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereReferenceExterne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereResultatsObtenus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereRisquesIdentifies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereSourcesFinancement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereTypeProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet whereVisiblePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Projet withContent()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Projet withoutTrashed()
 */
	class Projet extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $reunion_id Réunion concernée par ce rapport
 * @property string $titre_rapport Titre du rapport
 * @property string $type_rapport Type de rapport
 * @property string|null $redacteur_id Rédacteur du rapport
 * @property string|null $validateur_id Personne qui valide le rapport
 * @property string|null $cree_par Personne qui valide le rapport
 * @property string|null $modifie_par Personne qui valide le rapport
 * @property string $statut Statut du rapport
 * @property \Illuminate\Support\Carbon|null $valide_le Date de validation
 * @property \Illuminate\Support\Carbon|null $publie_le Date de publication
 * @property string|null $resume Résumé du rapport
 * @property array|null $points_traites Points traités (JSON)
 * @property string|null $decisions_prises Décisions prises
 * @property string|null $actions_decidees Actions décidées
 * @property array|null $presences Liste des présences (JSON)
 * @property int $nombre_presents Nombre de présents
 * @property string|null $montant_collecte Montant collecté
 * @property array|null $actions_suivre Actions à suivre (JSON)
 * @property string|null $recommandations Recommandations
 * @property int|null $note_satisfaction Note de satisfaction (1-5)
 * @property string|null $commentaires Commentaires généraux
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createur
 * @property-read array $content_overview
 * @property-read bool $est_modifiable
 * @property-read int $jours_depuis_creation
 * @property-read int $pourcentage_completion
 * @property-read string $statut_traduit
 * @property-read string $titre_format
 * @property-read string $type_rapport_traduit
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $redacteur
 * @property-read \App\Models\Reunion $reunion
 * @property-read \App\Models\User|null $validateur
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion avecActionsSuivre()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion brouillons()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion enRevision()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion parRedacteur(string $redacteurId)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion parStatut(string $statut)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion parType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion publies()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion query()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion recents(int $jours = 30)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion satisfactionElevee(int $noteMin = 4)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion valides()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereActionsDecidees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereActionsSuivre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereCommentaires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereDecisionsPrises($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereMontantCollecte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereNombrePresents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereNoteSatisfaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion wherePointsTraites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion wherePresences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion wherePublieLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereRecommandations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereRedacteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereReunionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereTitreRapport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereTypeRapport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereValidateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion whereValideLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RapportReunion withoutTrashed()
 */
	class RapportReunion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $type_reunion_id Type/modèle de réunion utilisé
 * @property string $titre Titre spécifique de cette réunion
 * @property string|null $description Description spécifique
 * @property string|null $objectifs Objectifs de cette réunion
 * @property \Illuminate\Support\Carbon $date_reunion Date de la réunion
 * @property \Illuminate\Support\Carbon $heure_debut_prevue Heure de début prévue
 * @property \Illuminate\Support\Carbon|null $heure_fin_prevue Heure de fin prévue
 * @property \Illuminate\Support\Carbon|null $heure_debut_reelle Heure de début réelle
 * @property \Illuminate\Support\Carbon|null $heure_fin_reelle Heure de fin réelle
 * @property string|null $duree_prevue Durée prévue
 * @property string|null $duree_reelle Durée réelle
 * @property string $lieu Lieu de la réunion
 * @property string|null $adresse_complete Adresse complète du lieu
 * @property string|null $salle Salle spécifique
 * @property int|null $capacite_salle Capacité de la salle
 * @property string|null $latitude Latitude GPS
 * @property string|null $longitude Longitude GPS
 * @property string|null $organisateur_principal_id Organisateur principal
 * @property string|null $animateur_id Animateur/facilitateur principal
 * @property string|null $responsable_technique_id Responsable technique
 * @property string|null $responsable_accueil_id Responsable accueil
 * @property array|null $equipe_organisation Équipe d'organisation (JSON)
 * @property array|null $intervenants Liste des intervenants (JSON)
 * @property int|null $nombre_places_disponibles Places disponibles
 * @property int $nombre_inscrits Nombre d'inscrits
 * @property int|null $nombre_participants_reel Nombre réel de participants
 * @property int|null $nombre_adultes Nombre d'adultes présents
 * @property int|null $nombre_enfants Nombre d'enfants présents
 * @property int|null $nombre_nouveaux Nombre de nouveaux participants
 * @property \Illuminate\Support\Carbon|null $limite_inscription Date limite d'inscription
 * @property bool $liste_attente_activee Liste d'attente activée
 * @property array|null $ordre_du_jour Ordre du jour détaillé (JSON)
 * @property string|null $message_principal Message ou enseignement principal
 * @property string|null $passage_biblique Passage biblique de référence
 * @property array|null $documents_annexes Documents fournis (JSON)
 * @property string|null $materiel_fourni Matériel fourni aux participants
 * @property string|null $materiel_apporter Matériel à apporter
 * @property string $statut Statut de la réunion
 * @property string $niveau_priorite Niveau de priorité
 * @property string|null $frais_inscription Frais d'inscription
 * @property string|null $budget_prevu Budget prévu
 * @property string|null $cout_reel Coût réel
 * @property array|null $detail_couts Détail des coûts (JSON)
 * @property string|null $recettes_totales Recettes totales
 * @property bool $diffusion_en_ligne Diffusion en ligne
 * @property string|null $lien_diffusion Lien de diffusion
 * @property bool $enregistrement_autorise Enregistrement autorisé
 * @property string|null $lien_enregistrement Lien vers l'enregistrement
 * @property array|null $photos_reunion Photos de la réunion (JSON)
 * @property string|null $notes_communication Notes de communication
 * @property string|null $preparation_necessaire Préparation nécessaire
 * @property array|null $checklist_preparation Checklist de préparation (JSON)
 * @property bool $preparation_terminee Préparation terminée
 * @property string|null $instructions_participants Instructions pour les participants
 * @property string|null $note_globale Note globale (1-10)
 * @property string|null $note_contenu Note du contenu
 * @property string|null $note_organisation Note de l'organisation
 * @property string|null $note_lieu Note du lieu
 * @property string|null $taux_satisfaction Taux de satisfaction en %
 * @property string|null $points_positifs Points positifs relevés
 * @property string|null $points_amelioration Points à améliorer
 * @property string|null $feedback_participants Feedback des participants
 * @property int $nombre_decisions Nombre de décisions spirituelles
 * @property int $nombre_recommitments Nombre de re-engagements
 * @property int $nombre_guerisons Nombre de guérisons rapportées
 * @property string|null $temoignages_recueillis Témoignages recueillis
 * @property string|null $demandes_priere Demandes de prière
 * @property string|null $conditions_meteo Conditions météorologiques
 * @property string|null $contexte_particulier Contexte particulier
 * @property string|null $defis_rencontres Défis rencontrés
 * @property string|null $solutions_apportees Solutions apportées
 * @property string|null $reunion_parent_id Réunion parent si récurrente
 * @property bool $est_recurrente Fait partie d'une série récurrente
 * @property \Illuminate\Support\Carbon|null $prochaine_occurrence Prochaine occurrence si récurrente
 * @property string|null $reunion_suivante_id Réunion suivante prévue
 * @property string|null $annulee_par Qui a annulé la réunion
 * @property \Illuminate\Support\Carbon|null $annulee_le Date d'annulation
 * @property string|null $motif_annulation Motif d'annulation
 * @property \Illuminate\Support\Carbon|null $nouvelle_date Nouvelle date si reportée
 * @property string|null $message_participants Message envoyé aux participants
 * @property bool $rappel_1_jour_envoye Rappel J-1 envoyé
 * @property bool $rappel_1_semaine_envoye Rappel J-7 envoyé
 * @property \Illuminate\Support\Carbon|null $dernier_rappel_envoye Dernier rappel envoyé
 * @property int $nombre_rappels_envoyes Nombre de rappels envoyés
 * @property string|null $cree_par Utilisateur qui a créé la réunion
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property string|null $validee_par Qui a validé la réunion
 * @property \Illuminate\Support\Carbon|null $validee_le Date de validation
 * @property string|null $notes_organisateur Notes privées de l'organisateur
 * @property string|null $notes_admin Notes administratives
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $animateur
 * @property-read \App\Models\User|null $annuleePar
 * @property-read \App\Models\User|null $createur
 * @property-read array $content_overview
 * @property-read mixed $description_formatted
 * @property-read mixed $duree_prevue_en_minutes
 * @property-read mixed $duree_reelle_en_minutes
 * @property-read mixed $jours_restants
 * @property-read mixed $notes_formatted
 * @property-read mixed $plan_message_formatted
 * @property-read mixed $points_formatted
 * @property-read mixed $resume_message_formatted
 * @property-read mixed $statut_inscription
 * @property-read mixed $temoignages_formatted
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $organisateurPrincipal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RapportReunion> $rapports
 * @property-read int|null $rapports_count
 * @property-read \App\Models\User|null $responsableAccueil
 * @property-read \App\Models\User|null $responsableTechnique
 * @property-read Reunion|null $reunionParent
 * @property-read Reunion|null $reunionSuivante
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reunion> $reunionsEnfants
 * @property-read int|null $reunions_enfants_count
 * @property-read \App\Models\TypeReunion $typeReunion
 * @property-read \App\Models\User|null $validateur
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion aVenir()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion avecDiffusionEnLigne()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion duJour()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion parLieu($lieu)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion parOrganisateur($organisateurId)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion parStatut($statut)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion recurrentes()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion searchContent($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion searchInCKEditorFields(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereAdresseComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereAnimateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereAnnuleeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereAnnuleePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereBudgetPrevu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereCapaciteSalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereChecklistPreparation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereConditionsMeteo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereContexteParticulier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereCoutReel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDateReunion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDefisRencontres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDemandesPriere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDernierRappelEnvoye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDetailCouts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDiffusionEnLigne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDocumentsAnnexes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDureePrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereDureeReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereEnregistrementAutorise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereEquipeOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereEstRecurrente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereFeedbackParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereFraisInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereHeureDebutPrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereHeureDebutReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereHeureFinPrevue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereHeureFinReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereInstructionsParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereIntervenants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLienDiffusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLienEnregistrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLimiteInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereListeAttenteActivee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereMaterielApporter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereMaterielFourni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereMessageParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereMessagePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereMotifAnnulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNiveauPriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreAdultes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreDecisions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreEnfants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreGuerisons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreInscrits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreNouveaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreParticipantsReel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombrePlacesDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreRappelsEnvoyes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNombreRecommitments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNoteContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNoteGlobale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNoteLieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNoteOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNotesCommunication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNotesOrganisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereNouvelleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereObjectifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereOrdreDuJour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereOrganisateurPrincipalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePassageBiblique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePhotosReunion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePointsAmelioration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePointsPositifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePreparationNecessaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion wherePreparationTerminee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereProchaineOccurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereRappel1JourEnvoye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereRappel1SemaineEnvoye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereRecettesTotales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereResponsableAccueilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereResponsableTechniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereReunionParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereReunionSuivanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereSalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereSolutionsApportees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereTauxSatisfaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereTemoignagesRecueillis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereTypeReunionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereValideeLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion whereValideePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion withContent()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Reunion withoutTrashed()
 */
	class Reunion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $level
 * @property bool $is_system_role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $nombre_membress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role niveauInferieur($level)
 * @method static \Illuminate\Database\Eloquent\Builder|Role niveauSuperieurOuEgal($level)
 * @method static \Illuminate\Database\Eloquent\Builder|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role parNiveau($level)
 * @method static \Illuminate\Database\Eloquent\Builder|Role personnalises()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role systeme()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereIsSystemRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $role_id ID du rôle
 * @property string $permission_id ID de la permission
 * @property string|null $attribue_par Qui a attribué cette permission au rôle
 * @property \Illuminate\Support\Carbon $attribue_le Quand la permission a été attribuée
 * @property \Illuminate\Support\Carbon|null $expire_le Date d'expiration (optionnel pour permissions temporaires)
 * @property bool $actif Permission active pour ce rôle
 * @property array|null $conditions Conditions spécifiques pour cette attribution
 * @property string|null $notes Notes sur l'attribution de cette permission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $attribuePar
 * @property-read mixed $duree
 * @property-read mixed $jours_restants
 * @property-read mixed $nom_permission
 * @property-read mixed $nom_role
 * @property-read mixed $statut
 * @property-read \App\Models\Permission $permission
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission actives()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission expirees()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission expirentBientot($jours = 7)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission nonExpirees()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission parPermission($permissionId)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission parRole($roleId)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission valides()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereActif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereAttribueLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereAttribuePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereExpireLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission withoutTrashed()
 */
	class RolePermission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $souscripteur_id
 * @property string|null $fimeco_id
 * @property string $montant_souscrit
 * @property string $montant_paye
 * @property string $reste_a_payer
 * @property string $cible Copie de la cible du FIMECO pour éviter les jointures coûteuses
 * @property string $montant_solde Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par le souscription et ce montant vient de la table paiement_souscriptions. Mise à jour automatique
 * @property string $reste C'est l'ensemble le montant non soldé. Mise à jour automatique
 * @property string $montant_supplementaire Le montant supplémentaire existe si cible inférieur au cible. Ce montant est une valeur positive, c'est la différence entre montant_solde et cible lorsque le montant_solde est superieur cible c'est-à-dire le souscripteur va au dela de sa cible prévue. Mise à jour automatique
 * @property string $progression Progression ou évolution de montant soldé en % et peut aller au dela de 100% puis que le souscripteur peut aller au dela de sa cible
 * @property string $statut_global Mise à jour automatique: tres_faible <=25%, 25% < en_cours <= 75%, 75% < presque_atteint <= 99,99% et objectif_atteint >= 100%
 * @property string $statut Mise à jour automatique en fonction du reste à payer
 * @property \Illuminate\Support\Carbon $date_souscription
 * @property \Illuminate\Support\Carbon|null $date_echeance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Fimeco|null $fimeco
 * @property-read bool $a_paiements_supplementaires
 * @property-read \App\Models\SubscriptionPayment|null $dernier_paiement
 * @property-read bool $en_retard
 * @property-read bool $est_complete
 * @property-read int $jours_restants
 * @property-read int $jours_retard
 * @property-read float $montant_base_restant
 * @property-read float $montant_total_paye
 * @property-read int $nombre_paiements
 * @property-read string $progression_formattee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionPayment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionPayment> $paymentsEnAttente
 * @property-read int|null $payments_en_attente_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionPayment> $paymentsSupplementaires
 * @property-read int|null $payments_supplementaires_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionPayment> $paymentsValides
 * @property-read int|null $payments_valides_count
 * @property-read \App\Models\User|null $souscripteur
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription actives()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription avecPaiementsSupplementaires()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription completes()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription echeanceProche($jours = 30)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription enRetard()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription partielles()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription periodeSouscription($debut = null, $fin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription pourFimeco($fimecoId)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription pourSouscripteur($souscripteurId)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDateEcheance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDateSouscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereFimecoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereMontantPaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereMontantSouscrit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereProgression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereResteAPayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSouscripteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatutGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription withoutTrashed()
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $subscription_id
 * @property string $montant
 * @property string $ancien_reste
 * @property string $nouveau_reste
 * @property string $type_paiement
 * @property string|null $reference_paiement
 * @property string $statut
 * @property \Illuminate\Support\Carbon $date_paiement
 * @property string|null $validateur_id
 * @property \Illuminate\Support\Carbon|null $date_validation
 * @property string|null $commentaire
 * @property int $subscription_version_at_payment
 * @property string|null $payment_hash
 * @property \Illuminate\Support\Carbon|null $date_paiement_only
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read int $age_jours
 * @property-read int|null $delai_validation_heures
 * @property-read bool $est_en_attente
 * @property-read bool $est_rejete
 * @property-read bool $est_valide
 * @property-read string $montant_formatte
 * @property-read bool $necessite_reference
 * @property-read \App\Models\Subscription|null $subscription
 * @property-read \App\Models\User|null $validateur
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment aujourdhui()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment avecReference()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment parValidateur($validateurId)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment periode($debut = null, $fin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment rejetes()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment valides()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereAncienReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereCommentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereDatePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereDatePaiementOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereDateValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereNouveauReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment wherePaymentHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereReferencePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereSubscriptionVersionAtPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereTypePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment whereValidateurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPayment withoutTrashed()
 */
	class SubscriptionPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $subscription_id
 * @property string|null $payment_id
 * @property string|null $user_id
 * @property string $action
 * @property array|null $donnees_avant
 * @property array|null $donnees_apres
 * @property string|null $ancien_montant_paye
 * @property string|null $nouveau_montant_paye
 * @property string|null $commentaire
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $a_eu_impact_financier
 * @property-read float|null $difference_monetaire
 * @property-read \App\Models\SubscriptionPayment|null $payment
 * @property-read \App\Models\Subscription|null $subscription
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog parAction(string $action)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog parMembres($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereAncienMontantPaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereCommentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereDonneesApres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereDonneesAvant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereNouveauMontantPaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPaymentLog whereUserId($value)
 */
	class SubscriptionPaymentLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $collecteur
 * @property-read \App\Models\User|null $createur
 * @property-read \App\Models\Culte|null $culte
 * @property-read \App\Models\User|null $donateur
 * @property-read mixed $nom_donateur
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\Projet|null $projet
 * @property-read TransactionSpirituelle|null $transactionParent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionSpirituelle> $transactionsEnfants
 * @property-read int|null $transactions_enfants_count
 * @property-read \App\Models\User|null $validateur
 * @property-read \App\Models\User|null $verificateur
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle dimes()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle enAttente()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle offrandes()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle parDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle parPeriode($dateDebut, $dateFin)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle parType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle recurrentes()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle validees()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSpirituelle withoutTrashed()
 */
	class TransactionSpirituelle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nom Nom du type de réunion
 * @property string $code Code unique du type de réunion
 * @property string|null $description Description détaillée du type de réunion
 * @property string|null $icone Nom de l'icône (FontAwesome, etc.)
 * @property string $couleur Couleur associée (code hex)
 * @property string $categorie Catégorie de la réunion
 * @property string $niveau_acces Niveau d'accès requis
 * @property string $frequence_type Fréquence type par défaut
 * @property \Illuminate\Support\Carbon|null $duree_standard Durée standard prévue
 * @property \Illuminate\Support\Carbon|null $duree_min Durée minimale
 * @property \Illuminate\Support\Carbon|null $duree_max Durée maximale
 * @property bool $necessite_preparation Nécessite une préparation spéciale
 * @property bool $necessite_inscription Inscription obligatoire
 * @property bool $a_limite_participants Nombre de participants limité
 * @property int|null $limite_participants Limite de participants si applicable
 * @property bool $permet_enfants Enfants autorisés
 * @property int|null $age_minimum Âge minimum requis
 * @property array|null $equipements_requis Équipements nécessaires (JSON array)
 * @property array|null $roles_requis Rôles/responsables requis (JSON)
 * @property string|null $materiel_necessaire Matériel nécessaire
 * @property string|null $preparation_requise Préparation requise
 * @property bool $inclut_louange Inclut un temps de louange
 * @property bool $inclut_message Inclut un message/enseignement
 * @property bool $inclut_priere Inclut un temps de prière
 * @property bool $inclut_communion Peut inclure la communion
 * @property bool $permet_temoignages Permet les témoignages
 * @property bool $collecte_offrandes Collecte d'offrandes
 * @property bool $a_frais_participation Frais de participation
 * @property string|null $frais_standard Frais standard si applicable
 * @property string|null $details_frais Détails des frais
 * @property bool $permet_enregistrement Enregistrement autorisé
 * @property bool $permet_diffusion_live Diffusion en direct autorisée
 * @property bool $necessite_promotion Nécessite une promotion/annonce
 * @property int|null $delai_annonce_jours Délai d'annonce en jours
 * @property array|null $modele_ordre_service Modèle d'ordre de service (JSON)
 * @property string|null $instructions_organisateur Instructions pour l'organisateur
 * @property string|null $modele_invitation Modèle d'invitation
 * @property string|null $modele_programme Modèle de programme
 * @property bool $necessite_evaluation Évaluation post-réunion requise
 * @property bool $necessite_rapport Rapport obligatoire
 * @property array|null $criteres_evaluation Critères d'évaluation (JSON)
 * @property string|null $questions_feedback Questions pour le feedback
 * @property array|null $metriques_importantes Métriques à suivre (JSON)
 * @property bool $compte_conversions Compter les conversions
 * @property bool $compte_baptemes Compter les baptêmes
 * @property bool $compte_nouveaux Compter les nouveaux visiteurs
 * @property bool $afficher_calendrier_public Afficher sur calendrier public
 * @property bool $afficher_site_web Afficher sur le site web
 * @property string|null $nom_affichage_public Nom pour affichage public
 * @property string|null $description_publique Description pour le public
 * @property bool $actif Type de réunion actif
 * @property bool $est_archive Type archivé
 * @property int $ordre_affichage Ordre d'affichage
 * @property int $priorite Priorité (1-10)
 * @property string|null $regles_annulation Règles d'annulation
 * @property string|null $politique_remboursement Politique de remboursement
 * @property string|null $conditions_participation Conditions de participation
 * @property string|null $code_vestimentaire Code vestimentaire
 * @property string|null $responsable_type_id Responsable par défaut de ce type
 * @property string|null $cree_par Utilisateur qui a créé le type
 * @property string|null $modifie_par Dernier utilisateur ayant modifié
 * @property \Illuminate\Support\Carbon|null $derniere_utilisation Dernière utilisation de ce type
 * @property int $nombre_utilisations Nombre d'utilisations total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createurType
 * @property-read mixed $description_affichage
 * @property-read mixed $nom_affichage
 * @property-read \App\Models\User|null $modificateur
 * @property-read \App\Models\User|null $responsableType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reunion> $reunions
 * @property-read int|null $reunions_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion actif()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion parCategorie($categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion parNiveauAcces($niveau)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion public()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereAFraisParticipation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereALimiteParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereActif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereAfficherCalendrierPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereAfficherSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereAgeMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCodeVestimentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCollecteOffrandes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCompteBaptemes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCompteConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCompteNouveaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereConditionsParticipation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCouleur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCreePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereCriteresEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDelaiAnnonceJours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDerniereUtilisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDescriptionPublique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDetailsFrais($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDureeMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDureeMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereDureeStandard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereEquipementsRequis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereEstArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereFraisStandard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereFrequenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereIcone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereInclutCommunion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereInclutLouange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereInclutMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereInclutPriere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereInstructionsOrganisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereLimiteParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereMaterielNecessaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereMetriquesImportantes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereModeleInvitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereModeleOrdreService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereModeleProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereModifiePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNecessiteEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNecessiteInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNecessitePreparation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNecessitePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNecessiteRapport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNiveauAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNomAffichagePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereNombreUtilisations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereOrdreAffichage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePermetDiffusionLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePermetEnfants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePermetEnregistrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePermetTemoignages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePolitiqueRemboursement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePreparationRequise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion wherePriorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereQuestionsFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereReglesAnnulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereResponsableTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereRolesRequis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeReunion withoutTrashed()
 */
	class TypeReunion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $classe_id
 * @property string $prenom
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $date_naissance
 * @property string $sexe
 * @property string $telephone_1
 * @property string|null $telephone_2
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $adresse_ligne_1
 * @property string|null $adresse_ligne_2
 * @property string|null $ville
 * @property string|null $code_postal
 * @property string|null $region
 * @property string $pays
 * @property string $statut_matrimonial
 * @property int $nombre_enfants
 * @property string|null $profession
 * @property string|null $employeur
 * @property \Illuminate\Support\Carbon|null $date_adhesion
 * @property string $statut_membre
 * @property string $statut_bapteme
 * @property \Illuminate\Support\Carbon|null $date_bapteme
 * @property string|null $eglise_precedente
 * @property string|null $contact_urgence_nom
 * @property string|null $contact_urgence_telephone
 * @property string|null $contact_urgence_relation
 * @property string|null $temoignage
 * @property string|null $dons_spirituels
 * @property string|null $demandes_priere
 * @property mixed $password
 * @property bool $actif
 * @property string|null $remember_token
 * @property string|null $photo_profil
 * @property string|null $notes_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Annonce> $annonces
 * @property-read int|null $annonces_count
 * @property-read \App\Models\Classe|null $classe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Classe> $classesEnseignant
 * @property-read int|null $classes_enseignant_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Classe> $classesResponsables
 * @property-read int|null $classes_responsables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Culte> $cultesPasteur
 * @property-read int|null $cultes_pasteur_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Culte> $cultesPredicateur
 * @property-read int|null $cultes_predicateur_count
 * @property-read mixed $nom_complet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Intervention> $interventions
 * @property-read int|null $interventions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Programme> $programmesResponsable
 * @property-read int|null $programmes_responsable_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RapportReunion> $rapportsCrees
 * @property-read int|null $rapports_crees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RapportReunion> $rapportsModifies
 * @property-read int|null $rapports_modifies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RapportReunion> $rapportsRediges
 * @property-read int|null $rapports_rediges_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RapportReunion> $rapportsValides
 * @property-read int|null $rapports_valides_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reunion> $reunionsOrganisees
 * @property-read int|null $reunions_organisees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-write mixed $mot_de_passe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fonds> $transactionsCollecteur
 * @property-read int|null $transactions_collecteur_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fonds> $transactionsDonateur
 * @property-read int|null $transactions_donateur_count
 * @method static \Illuminate\Database\Eloquent\Builder|User actifs()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User membres()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdresseLigne1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdresseLigne2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereClasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactUrgenceNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactUrgenceRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactUrgenceTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateAdhesion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateBapteme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDemandesPriere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDonsSpirituels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEglisePrecedente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmployeur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNombreEnfants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhotoProfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatutBapteme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatutMatrimonial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatutMembre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelephone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelephone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTemoignage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder|UserClasse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserClasse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserClasse query()
 */
	class UserClasse extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id Référence vers l'utilisateur
 * @property string $permission_id Référence vers la permission
 * @property bool $is_granted Permission accordée ou révoquée
 * @property string|null $granted_by Utilisateur qui a accordé la permission
 * @property \Illuminate\Support\Carbon $granted_at Date d'attribution de la permission
 * @property \Illuminate\Support\Carbon|null $expires_at Date d'expiration (null = permanente)
 * @property bool $is_expired Flag calculé automatiquement par trigger
 * @property string|null $reason Raison de l'attribution/révocation
 * @property array|null $metadata Données supplémentaires en JSON (contexte, conditions, etc.)
 * @property string|null $revoked_by Utilisateur qui a révoqué la permission
 * @property \Illuminate\Support\Carbon|null $revoked_at Date de révocation
 * @property string|null $revocation_reason Raison de la révocation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $days_remaining
 * @property-read mixed $status
 * @property-read mixed $total_duration
 * @property-read \App\Models\User|null $grantedBy
 * @property-read \App\Models\Permission $permission
 * @property-read \App\Models\User|null $revokedBy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission active()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission expired()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission expiringSoon($days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission granted()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission revoked()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereGrantedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereGrantedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereIsExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereIsGranted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereRevocationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereRevokedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereRevokedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission withoutTrashed()
 */
	class UserPermission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $user_id ID de l'utilisateur
 * @property string $role_id ID du rôle
 * @property string|null $attribue_par Qui a attribué ce rôle
 * @property \Illuminate\Support\Carbon $attribue_le Quand le rôle a été attribué
 * @property \Illuminate\Support\Carbon|null $expire_le Date d'expiration (optionnel pour rôles temporaires)
 * @property bool $actif Rôle actif ou non
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $attribuePar
 * @property-read mixed $duree
 * @property-read mixed $jours_restants
 * @property-read mixed $statut
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole actives()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole expirees()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole nonExpirees()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole valides()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereActif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereAttribueLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereAttribuePar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereExpireLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole withoutTrashed()
 */
	class UserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $moisson_id
 * @property string $categorie Type de vente pour la moisson
 * @property string $cible C'est l'objectif à atteindre mais on peut aller au-delà
 * @property string $montant_solde L'ensemble des montants déjà collectés
 * @property string $reste Reste à solder
 * @property string $montant_supplementaire Existe lorsque montant_solde > cible
 * @property string $collecter_par
 * @property \Illuminate\Support\Carbon $collecte_le
 * @property string $creer_par
 * @property int|null $quantite Quantité vendue si applicable
 * @property string|null $prix_unitaire Prix unitaire si applicable
 * @property string|null $description Description détaillée de la vente
 * @property array|null $editeurs Historique des modifications en JSONB
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $categorie_libelle
 * @property-read \App\Models\User $collecteur
 * @property-read \App\Models\User $createur
 * @property-read \App\Models\Moisson $moisson
 * @property-read mixed $objectif_atteint
 * @property-read mixed $pourcentage_realise
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson actif()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson avecMoisson()
 * @method static \Database\Factories\VenteMoissonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson objectifAtteint()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson parCategorie(string $categorie)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson parPeriode($dateDebut = null, $dateFin = null)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson rechercheDescription(string $terme)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCollecteLe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCollecterPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereCreerPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereEditeurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereMoissonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereMontantSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereMontantSupplementaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereReste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VenteMoisson withoutTrashed()
 */
	class VenteMoisson extends \Eloquent {}
}

