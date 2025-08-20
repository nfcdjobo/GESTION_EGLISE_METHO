<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom_eglise',
        'denomination',
        'description_courte',
        'mission_vision',
        'type_contact',
        'telephone_principal',
        'telephone_secondaire',
        'telephone_urgence',
        'fax',
        'whatsapp',
        'email_principal',
        'email_administratif',
        'email_pastoral',
        'email_info',
        'email_presse',
        'adresse_complete',
        'rue',
        'quartier',
        'ville',
        'commune',
        'code_postal',
        'region',
        'pays',
        'latitude',
        'longitude',
        'indications_acces',
        'points_repere',
        'facebook_url',
        'facebook_handle',
        'instagram_url',
        'instagram_handle',
        'tiktok_url',
        'tiktok_handle',
        'youtube_url',
        'youtube_handle',
        'twitter_url',
        'twitter_handle',
        'linkedin_url',
        'telegram_url',
        'site_web_principal',
        'site_web_secondaire',
        'blog_url',
        'app_mobile_android',
        'app_mobile_ios',
        'podcast_url',
        'youtube_live_url',
        'facebook_live_url',
        'zoom_meeting_id',
        'google_meet_url',
        'radio_frequency',
        'tv_channel',
        'horaires_bureau',
        'horaires_cultes',
        'horaires_speciaux',
        'disponible_24h',
        'numero_siret',
        'numero_rna',
        'code_ape',
        'numero_tva',
        'date_creation',
        'statut_juridique',
        'iban_dons',
        'bic_swift',
        'nom_banque',
        'titulaire_compte',
        'mobile_money_orange',
        'mobile_money_mtn',
        'mobile_money_moov',
        'pasteur_principal',
        'telephone_pasteur',
        'email_pasteur',
        'secretaire_general',
        'telephone_secretaire',
        'tresorier',
        'telephone_tresorier',
        'logo_url',
        'photo_eglise_url',
        'photos_galleries',
        'video_presentation_url',
        'langues_parlees',
        'accessibilite_handicap',
        'services_speciaux',
        'equipements_disponibles',
        'visible_public',
        'afficher_site_web',
        'afficher_app_mobile',
        'partage_autorise',
        'qr_code_contact',
        'qr_code_wifi',
        'code_court_sms',
        'hashtag_officiel',
        'contact_urgence_medical',
        'contact_police',
        'contact_pompiers',
        'procedures_urgence',
        'capacite_accueil',
        'nombre_membres',
        'derniere_mise_a_jour',
        'notes_complementaires',
        'responsable_contact_id',
        'cree_par',
        'modifie_par',
        'verifie',
        'derniere_verification',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_creation' => 'date',
        'derniere_mise_a_jour' => 'date',
        'derniere_verification' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacite_accueil' => 'integer',
        'nombre_membres' => 'integer',
        'disponible_24h' => 'boolean',
        'accessibilite_handicap' => 'boolean',
        'visible_public' => 'boolean',
        'afficher_site_web' => 'boolean',
        'afficher_app_mobile' => 'boolean',
        'partage_autorise' => 'boolean',
        'verifie' => 'boolean',
        'horaires_bureau' => 'array',
        'horaires_cultes' => 'array',
        'photos_galleries' => 'array',
        'langues_parlees' => 'array',
    ];

    /**
     * Relation avec le responsable du contact
     */
    public function responsableContact()
    {
        return $this->belongsTo(User::class, 'responsable_contact_id');
    }

    /**
     * Utilisateur qui a créé le contact
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié le contact
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scope pour les contacts publics
     */
    public function scopePublics($query)
    {
        return $query->where('visible_public', true);
    }

    /**
     * Scope pour les contacts vérifiés
     */
    public function scopeVerifies($query)
    {
        return $query->where('verifie', true);
    }

    /**
     * Scope pour filtrer par type de contact
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_contact', $type);
    }

    /**
     * Scope pour filtrer par ville
     */
    public function scopeParVille($query, $ville)
    {
        return $query->where('ville', $ville);
    }

    /**
     * Scope pour les contacts avec géolocalisation
     */
    public function scopeAvecGeo($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope pour les contacts avec réseaux sociaux
     */
    public function scopeAvecReseauxSociaux($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('facebook_url')
              ->orWhereNotNull('instagram_url')
              ->orWhereNotNull('youtube_url')
              ->orWhereNotNull('twitter_url');
        });
    }

    /**
     * Obtenir l'adresse complète formatée
     */
    public function getAdresseFormateeAttribute()
    {
        $elements = array_filter([
            $this->rue,
            $this->quartier,
            $this->ville,
            $this->commune,
            $this->code_postal,
            $this->region,
            $this->pays
        ]);

        return implode(', ', $elements);
    }

    /**
     * Obtenir le téléphone principal formaté
     */
    public function getTelephonePrincipalFormate()
    {
        if (!$this->telephone_principal) {
            return null;
        }

        // Format ivoirien standard
        $tel = preg_replace('/[^\d]/', '', $this->telephone_principal);

        if (strlen($tel) === 10 && str_starts_with($tel, '0')) {
            return '+225 ' . substr($tel, 0, 2) . ' ' . substr($tel, 2, 2) . ' ' . substr($tel, 4, 2) . ' ' . substr($tel, 6, 2) . ' ' . substr($tel, 8, 2);
        }

        return $this->telephone_principal;
    }

    /**
     * Obtenir tous les réseaux sociaux
     */
    public function getReseauxSociauxAttribute()
    {
        return [
            'facebook' => [
                'url' => $this->facebook_url,
                'handle' => $this->facebook_handle,
                'actif' => !empty($this->facebook_url),
            ],
            'instagram' => [
                'url' => $this->instagram_url,
                'handle' => $this->instagram_handle,
                'actif' => !empty($this->instagram_url),
            ],
            'youtube' => [
                'url' => $this->youtube_url,
                'handle' => $this->youtube_handle,
                'actif' => !empty($this->youtube_url),
            ],
            'twitter' => [
                'url' => $this->twitter_url,
                'handle' => $this->twitter_handle,
                'actif' => !empty($this->twitter_url),
            ],
            'tiktok' => [
                'url' => $this->tiktok_url,
                'handle' => $this->tiktok_handle,
                'actif' => !empty($this->tiktok_url),
            ],
            'linkedin' => [
                'url' => $this->linkedin_url,
                'actif' => !empty($this->linkedin_url),
            ],
            'telegram' => [
                'url' => $this->telegram_url,
                'actif' => !empty($this->telegram_url),
            ],
        ];
    }

    /**
     * Obtenir tous les moyens de paiement mobile
     */
    public function getMobileMoneyAttribute()
    {
        return [
            'orange' => $this->mobile_money_orange,
            'mtn' => $this->mobile_money_mtn,
            'moov' => $this->mobile_money_moov,
        ];
    }

    /**
     * Vérifier si le contact est complet
     */
    public function isComplet()
    {
        $champsRequis = [
            'nom_eglise',
            'telephone_principal',
            'email_principal',
            'adresse_complete',
            'ville',
        ];

        foreach ($champsRequis as $champ) {
            if (empty($this->$champ)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Marquer comme vérifié
     */
    public function marquerVerifie($userId = null)
    {
        $this->update([
            'verifie' => true,
            'derniere_verification' => now(),
            'modifie_par' => $userId,
        ]);
    }

    /**
     * Mettre à jour la date de dernière modification
     */
    public function mettreAJour()
    {
        $this->update(['derniere_mise_a_jour' => now()->toDateString()]);
    }

    /**
     * Générer un QR code pour les informations de contact
     */
    public function genererQRCodeContact()
    {
        $infos = [
            'nom' => $this->nom_eglise,
            'tel' => $this->telephone_principal,
            'email' => $this->email_principal,
            'adresse' => $this->adresse_complete,
            'site' => $this->site_web_principal,
        ];

        // Vous pouvez utiliser une librairie comme SimpleSoftwareIO/simple-qrcode
        // pour générer le QR code
        return $infos;
    }

    /**
     * Obtenir les horaires d'ouverture pour aujourd'hui
     */
    public function getHorairesAujourdhui()
    {
        if (!$this->horaires_bureau) {
            return null;
        }

        $jourSemaine = now()->dayOfWeek; // 0 = dimanche, 1 = lundi, etc.
        $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

        return $this->horaires_bureau[$jours[$jourSemaine]] ?? null;
    }

    /**
     * Vérifier si l'église est ouverte maintenant
     */
    public function isOuvertMaintenant()
    {
        if ($this->disponible_24h) {
            return true;
        }

        $horaires = $this->getHorairesAujourdhui();
        if (!$horaires) {
            return false;
        }

        $heureActuelle = now()->format('H:i');
        return $heureActuelle >= $horaires['ouverture'] && $heureActuelle <= $horaires['fermeture'];
    }

    /**
     * Calculer la distance depuis un point
     */
    public function calculerDistance($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Formule de haversine pour calculer la distance
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($latitude);
        $lon2 = deg2rad($longitude);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Obtenir les informations pour les cartes (Google Maps, etc.)
     */
    public function getInfosCarte()
    {
        return [
            'nom' => $this->nom_eglise,
            'adresse' => $this->adresse_formatee,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'telephone' => $this->telephone_principal,
            'site_web' => $this->site_web_principal,
            'horaires' => $this->horaires_cultes,
        ];
    }
}
