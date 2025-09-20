<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fimeco extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'fimecos';

    protected $fillable = [
        'responsable_id',
        'nom',
        'description',
        'debut',
        'fin',
        'cible',
        'statut',
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'progression' => 'decimal:2',
        'statut_global' => 'string',
        'statut' => 'string',
    ];

    protected $attributes = [
        'montant_solde' => 0,
        'reste' => 0,
        'montant_supplementaire' => 0,
        'progression' => 0,
        'statut_global' => 'tres_faible',
        'statut' => 'active',
    ];

    // Relations

    /**
     * Relation avec l'utilisateur responsable
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relation avec les souscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id');
    }

    /**
     * Relation avec les souscriptions actives uniquement
     */
    public function subscriptionsActives(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id')->where('statut', '!=', 'inactive');
    }

    /**
     * Relation avec les souscriptions complètement payées
     */
    public function subscriptionsCompletes(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id')->where('statut', 'completement_payee');
    }

    // Scopes

    /**
     * Scope pour les FIMECO actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope pour les FIMECO avec objectif atteint
     */
    public function scopeObjectifAtteint($query)
    {
        return $query->where('statut_global', 'objectif_atteint');
    }

    /**
     * Scope pour les FIMECO en cours
     */
    public function scopeEnCours($query)
    {
        return $query->whereIn('statut_global', ['en_cours', 'presque_atteint']);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopePeriode($query, $debut = null, $fin = null)
    {
        if ($debut) {
            $query->where('debut', '>=', $debut);
        }
        if ($fin) {
            $query->where('fin', '<=', $fin);
        }
        return $query;
    }

    /**
     * Scope pour recherche textuelle
     */
    public function scopeRecherche($query, $terme)
    {
        return $query->where('nom', 'ILIKE', "%{$terme}%")->orWhere('description', 'ILIKE', "%{$terme}%");
    }

    // Accesseurs

    /**
     * Retourne le pourcentage de progression formaté
     */
    public function getProgressionFormatteeAttribute(): string
    {
        return number_format($this->progression, 2) . '%';
    }

    /**
     * Vérifie si l'objectif est atteint
     */
    public function getObjectifAtteintAttribute(): bool
    {
        return $this->statut_global === 'objectif_atteint';
    }

    /**
     * Retourne le nombre de jours restants
     */
    public function getJoursRestantsAttribute(): int
    {
        return max(0, now()->diffInDays($this->fin, false));
    }

    /**
     * Vérifie si le FIMECO est en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->fin < now() && $this->statut_global !== 'objectif_atteint';
    }

    // Méthodes métier

/**
 * Calcule les statistiques complètes du FIMECO - VERSION ENRICHIE
 */
public function getStatistiques(): array
{
    $subscriptions = $this->subscriptions()->get();
    $subscriptionsAvecSupplements = $subscriptions->where('montant_supplementaire', '>', 0);

    $statistiquesBase = [
        'nb_souscriptions_total' => $subscriptions->count(),
        'nb_souscriptions_actives' => $subscriptions->where('statut', '!=', 'inactive')->count(),
        'nb_souscriptions_completes' => $subscriptions->where('statut', 'completement_payee')->count(),
        'nb_souscriptions_partielles' => $subscriptions->where('statut', 'partiellement_payee')->count(),
        'nb_souscriptions_inactives' => $subscriptions->where('statut', 'inactive')->count(),
        'montant_total_souscrit' => $subscriptions->sum('montant_souscrit'),
        'montant_total_paye' => $subscriptions->sum('montant_paye'),
        'progression_moyenne_souscriptions' => $subscriptions->avg('progression') ?? 0,
        'nb_souscriptions_en_retard' => $subscriptions->filter(function ($s) {
            return $s->date_echeance && $s->date_echeance < now() && $s->statut !== 'completement_payee';
        })->count(),
    ];

    // Statistiques enrichies pour les paiements supplémentaires
    $statistiquesSupplementaires = [
        'nb_souscriptions_avec_supplements' => $subscriptionsAvecSupplements->count(),
        'montant_total_supplementaire' => $subscriptionsAvecSupplements->sum('montant_supplementaire'),
        'montant_moyen_supplement' => $subscriptionsAvecSupplements->count() > 0 ?
            $subscriptionsAvecSupplements->avg('montant_supplementaire') : 0,
        'taux_souscripteurs_genereux' => $subscriptions->count() > 0 ?
            round(($subscriptionsAvecSupplements->count() / $subscriptions->count()) * 100, 2) : 0,
        'contribution_supplements_vs_objectif' => $this->taux_depassement,
        'montant_collecte_au_dela_objectif' => $this->montant_supplementaire,
        'souscripteur_plus_genereux' => $this->getSouscripteurPlusGenereux(),
        'repartition_supplements' => $this->getRepartitionSupplements(),
    ];

    return array_merge($statistiquesBase, $statistiquesSupplementaires);
}




/**
 * Retourne le souscripteur le plus généreux (plus de suppléments)
 */
private function getSouscripteurPlusGenereux(): ?array
{
    $subscriptionPlusGenereuse = $this->subscriptions()
        ->with('souscripteur:id,nom,prenom')
        ->where('montant_supplementaire', '>', 0)
        ->orderBy('montant_supplementaire', 'desc')
        ->first();

    if (!$subscriptionPlusGenereuse) {
        return null;
    }

    return [
        'nom' => $subscriptionPlusGenereuse->souscripteur->nom . ' ' .
                ($subscriptionPlusGenereuse->souscripteur->prenom ?? ''),
        'montant_supplementaire' => $subscriptionPlusGenereuse->montant_supplementaire,
        'pourcentage_de_sa_souscription' => $subscriptionPlusGenereuse->montant_souscrit > 0 ?
            round(($subscriptionPlusGenereuse->montant_supplementaire / $subscriptionPlusGenereuse->montant_souscrit) * 100, 2) : 0,
    ];
}



/**
 * Retourne la répartition des montants supplémentaires par tranches
 */
private function getRepartitionSupplements(): array
{
    $subscriptionsAvecSupplements = $this->subscriptions()
        ->where('montant_supplementaire', '>', 0)
        ->get();

    if ($subscriptionsAvecSupplements->isEmpty()) {
        return [];
    }

    $tranches = [
        '1-10k' => ['min' => 1, 'max' => 10000, 'count' => 0, 'total' => 0],
        '10k-50k' => ['min' => 10001, 'max' => 50000, 'count' => 0, 'total' => 0],
        '50k-100k' => ['min' => 50001, 'max' => 100000, 'count' => 0, 'total' => 0],
        '100k+' => ['min' => 100001, 'max' => PHP_INT_MAX, 'count' => 0, 'total' => 0],
    ];

    foreach ($subscriptionsAvecSupplements as $subscription) {
        $montant = $subscription->montant_supplementaire;

        foreach ($tranches as $nom => &$tranche) {
            if ($montant >= $tranche['min'] && $montant <= $tranche['max']) {
                $tranche['count']++;
                $tranche['total'] += $montant;
                break;
            }
        }
    }

    return $tranches;
}



/**
 * Vérifie si une nouvelle souscription peut être créée - VERSION MODIFIÉE
 */
public function peutAccepterNouvellesSouscriptions(): bool
{
    // Même si l'objectif est atteint, on peut encore accepter des souscriptions
    // pour permettre des contributions supplémentaires
    return $this->statut === 'active' && $this->fin >= now();
}

/**
 * Retourne le montant encore disponible pour les souscriptions - VERSION MODIFIÉE
 */
public function getMontantDisponible(): float
{
    // Pour les FIMECO, on peut théoriquement dépasser la cible
    // Mais on peut quand même indiquer ce qui reste pour atteindre l'objectif de base
    return max(0, $this->cible - $this->subscriptions()->sum('montant_souscrit'));
}

    /**
     * Retourne les paiements en attente pour ce FIMECO
     */
    public function getPaiementsEnAttente()
    {
        return SubscriptionPayment::whereHas('subscription', function ($query) {
            $query->where('fimeco_id', $this->id);
        })->where('statut', 'en_attente')->get();
    }

    // Événements du modèle

    protected static function booted()
    {
        // Validation avant sauvegarde
        static::saving(function ($fimeco) {
            if ($fimeco->fin < $fimeco->debut) {
                throw new \InvalidArgumentException('La date de fin ne peut pas être antérieure à la date de début');
            }
            if ($fimeco->cible <= 0) {
                throw new \InvalidArgumentException('La cible doit être supérieure à zéro');
            }
        });
    }



/**
 * Relation avec les souscriptions ayant des paiements supplémentaires
 */
public function subscriptionsAvecSupplements(): HasMany
{
    return $this->hasMany(Subscription::class, 'fimeco_id')
                ->where('montant_supplementaire', '>', 0);
}


/**
 * Vérifie si le FIMECO a des paiements supplémentaires
 */
public function getAPaiementsSupplementairesAttribute(): bool
{
    return $this->montant_supplementaire > 0;
}

/**
 * Retourne le taux de dépassement de l'objectif
 */
public function getTauxDepassementAttribute(): float
{
    if ($this->cible <= 0) {
        return 0;
    }
    return $this->montant_supplementaire > 0 ?
        round(($this->montant_supplementaire / $this->cible) * 100, 2) : 0;
}


/**
 * Vérifie si l'objectif est largement dépassé (plus de 50% de supplément)
 */
public function getObjectifLargementDepasseAttribute(): bool
{
    return $this->taux_depassement > 50;
}


/**
 * Nouveau: Calcule le potentiel de collecte supplémentaire
 */
public function getPotentielCollecteSupplementaire(): array
{
    $subscriptionsCompletes = $this->subscriptions()
        ->where('statut', 'completement_payee')
        ->get();

    if ($subscriptionsCompletes->isEmpty()) {
        return [
            'estimation_conservative' => 0,
            'estimation_optimiste' => 0,
            'nb_souscripteurs_cibles' => 0,
        ];
    }

    $tauxGenerositeActuel = $subscriptionsCompletes->where('montant_supplementaire', '>', 0)->count() /
                          $subscriptionsCompletes->count();

    $supplementMoyenActuel = $subscriptionsCompletes->avg('montant_supplementaire') ?? 0;

    $subscriptionsNonCompletes = $this->subscriptions()
        ->where('statut', '!=', 'completement_payee')
        ->where('statut', '!=', 'inactive')
        ->count();

    return [
        'estimation_conservative' => round($subscriptionsNonCompletes * $supplementMoyenActuel * 0.3, 2),
        'estimation_optimiste' => round($subscriptionsNonCompletes * $supplementMoyenActuel * $tauxGenerositeActuel, 2),
        'nb_souscripteurs_cibles' => $subscriptionsNonCompletes,
        'taux_generosite_actuel' => round($tauxGenerositeActuel * 100, 2),
        'supplement_moyen_actuel' => round($supplementMoyenActuel, 2),
    ];
}




/**
 * Retourne les alertes spécifiques aux paiements supplémentaires
 */
public function getAlertesSupplementaires(): array
{
    $alertes = [];

    if ($this->taux_depassement > 100) {
        $alertes[] = [
            'type' => 'success',
            'message' => "Objectif largement dépassé ! Collecte supplémentaire de " .
                        number_format($this->montant_supplementaire, 0, ',', ' ') . " FCFA",
            'pourcentage' => $this->taux_depassement,
        ];
    } elseif ($this->taux_depassement > 20) {
        $alertes[] = [
            'type' => 'info',
            'message' => "Bel élan de générosité ! " .
                        number_format($this->montant_supplementaire, 0, ',', ' ') . " FCFA collectés en plus",
            'pourcentage' => $this->taux_depassement,
        ];
    }

    $nbSouscripteursGenereux = $this->subscriptionsAvecSupplements()->count();
    $totalSouscripteurs = $this->subscriptions()->count();

    if ($totalSouscripteurs > 0) {
        $tauxGenerosité = ($nbSouscripteursGenereux / $totalSouscripteurs) * 100;

        if ($tauxGenerosité > 50) {
            $alertes[] = [
                'type' => 'success',
                'message' => "Communauté très généreuse ! {$nbSouscripteursGenereux} souscripteurs sur {$totalSouscripteurs} ont donné au-delà de leur engagement",
                'taux' => round($tauxGenerosité, 1),
            ];
        } elseif ($tauxGenerosité > 25) {
            $alertes[] = [
                'type' => 'info',
                'message' => "Belle générosité ! {$nbSouscripteursGenereux} souscripteurs ont donné au-delà de leur engagement",
                'taux' => round($tauxGenerosité, 1),
            ];
        }
    }

    return $alertes;
}



/**
 * Retourne un rapport détaillé des paiements supplémentaires
 */
public function getRapportPaiementsSupplementaires(): array
{
    $subscriptionsAvecSupplements = $this->subscriptionsAvecSupplements()
        ->with(['souscripteur:id,nom,prenom', 'payments' => function($query) {
            $query->where('statut', 'valide')->orderBy('date_paiement', 'desc');
        }])
        ->get();

    if ($subscriptionsAvecSupplements->isEmpty()) {
        return [
            'message' => 'Aucun paiement supplémentaire pour ce FIMECO',
            'details' => []
        ];
    }

    $details = $subscriptionsAvecSupplements->map(function ($subscription) {
        $paiementsSupplementaires = $subscription->payments->filter(function ($payment) {
            return $payment->est_paiement_supplementaire;
        });

        return [
            'souscripteur' => [
                'nom' => trim($subscription->souscripteur->nom . ' ' . ($subscription->souscripteur->prenom ?? '')),
                'id' => $subscription->souscripteur->id,
            ],
            'souscription' => [
                'montant_initial' => $subscription->montant_souscrit,
                'montant_total_paye' => $subscription->montant_paye,
                'montant_supplementaire' => $subscription->montant_supplementaire,
                'taux_depassement' => $subscription->montant_souscrit > 0 ?
                    round(($subscription->montant_supplementaire / $subscription->montant_souscrit) * 100, 2) : 0,
            ],
            'paiements_supplementaires' => $paiementsSupplementaires->map(function ($payment) {
                return [
                    'date' => $payment->date_paiement->format('d/m/Y'),
                    'montant' => $payment->montant,
                    'montant_supplementaire' => $payment->montant_supplementaire_du_paiement,
                    'type' => $payment->getTypePaiementLibelle(),
                    'reference' => $payment->reference_paiement,
                ];
            })->toArray(),
            'nb_paiements_supplementaires' => $paiementsSupplementaires->count(),
            'premier_paiement_supplementaire' => $paiementsSupplementaires->min('date_paiement'),
            'dernier_paiement_supplementaire' => $paiementsSupplementaires->max('date_paiement'),
        ];
    })->sortByDesc('souscription.montant_supplementaire')->values()->toArray();

    return [
        'resume' => [
            'nb_souscripteurs_genereux' => $subscriptionsAvecSupplements->count(),
            'montant_total_supplementaire' => $subscriptionsAvecSupplements->sum('montant_supplementaire'),
            'montant_moyen_par_souscripteur' => $subscriptionsAvecSupplements->avg('montant_supplementaire'),
            'taux_participants_genereux' => $this->subscriptions()->count() > 0 ?
                round(($subscriptionsAvecSupplements->count() / $this->subscriptions()->count()) * 100, 2) : 0,
        ],
        'details' => $details,
        'top_3_plus_genereux' => array_slice($details, 0, 3),
    ];
}


/**
 * Calcule l'impact des paiements supplémentaires sur la progression
 */
public function getImpactSupplementsSurProgression(): array
{
    $progressionSansSupplements = $this->cible > 0 ?
        round((($this->montant_solde - $this->montant_supplementaire) / $this->cible) * 100, 2) : 0;

    $progressionAvecSupplements = $this->progression;

    return [
        'progression_objectif_base' => $progressionSansSupplements,
        'progression_avec_supplements' => $progressionAvecSupplements,
        'gain_progression' => $progressionAvecSupplements - $progressionSansSupplements,
        'montant_supplements' => $this->montant_supplementaire,
        'contribution_supplements_pourcentage' => $this->taux_depassement,
        'objectif_base_atteint' => $progressionSansSupplements >= 100,
        'message' => $this->genererMessageProgression($progressionSansSupplements, $progressionAvecSupplements),
    ];
}



/**
 * Génère un message explicatif sur la progression
 */
private function genererMessageProgression(float $progressionBase, float $progressionTotale): string
{
    if ($progressionBase >= 100) {
        $surplus = $progressionTotale - 100;
        return "Objectif atteint à 100% ! Grâce à la générosité des souscripteurs, " .
               "vous avez collecté {$surplus}% supplémentaires.";
    } elseif ($progressionBase < 100 && $progressionTotale >= 100) {
        return "Objectif atteint grâce aux paiements supplémentaires ! " .
               "L'objectif de base était à {$progressionBase}%, " .
               "mais les contributions supplémentaires ont permis d'atteindre {$progressionTotale}%.";
    } else {
        $gainSupplements = $progressionTotale - $progressionBase;
        if ($gainSupplements > 0) {
            return "Progression boostée par les paiements supplémentaires ! " .
                   "Objectif de base : {$progressionBase}%, avec suppléments : {$progressionTotale}% " .
                   "(+{$gainSupplements}% grâce à la générosité).";
        } else {
            return "Progression actuelle : {$progressionTotale}% de l'objectif.";
        }
    }
}



/**
 * Propose des stratégies pour encourager les paiements supplémentaires
 */
public function getStrategiesEncouragementSupplements(): array
{
    $statistiques = $this->getStatistiques();
    $strategies = [];

    // Stratégie basée sur la progression actuelle
    if ($this->progression < 50) {
        $strategies[] = [
            'type' => 'motivation',
            'titre' => 'Campagne de sensibilisation',
            'description' => 'Organiser une campagne pour expliquer l\'importance du projet et encourager la participation',
            'priorite' => 'haute'
        ];
    } elseif ($this->progression >= 80 && $this->progression < 100) {
        $strategies[] = [
            'type' => 'derniere_ligne_droite',
            'titre' => 'Dernière ligne droite',
            'description' => 'Mobiliser pour les derniers efforts, montrer qu\'on est proche du but',
            'priorite' => 'moyenne'
        ];
    } elseif ($this->progression >= 100) {
        $strategies[] = [
            'type' => 'depassement',
            'titre' => 'Projet bonus',
            'description' => 'Proposer des objectifs bonus ou des améliorations supplémentaires au projet',
            'priorite' => 'basse'
        ];
    }

    // Stratégie basée sur le taux de générosité actuel
    $tauxGenereux = $statistiques['taux_souscripteurs_genereux'];
    if ($tauxGenereux < 10) {
        $strategies[] = [
            'type' => 'education',
            'titre' => 'Sensibilisation aux dons supplémentaires',
            'description' => 'Expliquer la possibilité et l\'impact des contributions au-delà de l\'engagement initial',
            'priorite' => 'haute'
        ];
    } elseif ($tauxGenereux > 30) {
        $strategies[] = [
            'type' => 'reconnaissance',
            'titre' => 'Valorisation des contributeurs généreux',
            'description' => 'Mettre en avant les contributeurs généreux pour encourager l\'émulation positive',
            'priorite' => 'moyenne'
        ];
    }

    // Stratégie basée sur les montants
    if ($statistiques['montant_moyen_supplement'] < 10000) {
        $strategies[] = [
            'type' => 'micro_dons',
            'titre' => 'Faciliter les micro-contributions',
            'description' => 'Proposer des montants suggérés accessibles pour encourager les petites contributions',
            'priorite' => 'moyenne'
        ];
    }

    return $strategies;
}



}
