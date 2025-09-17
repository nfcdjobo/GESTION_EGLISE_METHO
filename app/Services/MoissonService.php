<?php

namespace App\Services;

use App\Models\Moisson;
use App\Models\PassageMoisson;
use App\Models\VenteMoisson;
use App\Models\EngagementMoisson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MoissonService
{
    /**
     * Crée une nouvelle moisson avec ses composants
     */
    public function creerMoissonComplete(array $donneesMoisson, array $composants = []): Moisson
    {
        DB::beginTransaction();
        try {


            // Créer la moisson principale
            $moisson = Moisson::create($donneesMoisson);

            // Ajouter les passages si fournis
            if (!empty($composants['passages'])) {

                foreach ($composants['passages'] as $key => $passage) {
                    if(str_contains($key, 'vente')){
                        continue;
                    }
                    $passage['moisson_id'] = $moisson->id;
                    $passage['collecter_par'] = auth()->user()->id;
                    $passage['creer_par'] = auth()->user()->id;
                    PassageMoisson::create($passage);
                }
            }

            // Ajouter les ventes si fournies
            if (!empty($composants['ventes'])) {

                foreach ($composants['ventes'] as $key => $vente) {

                    if(str_contains($key, 'passage')){
                        continue;
                    }

                    $vente['moisson_id'] = $moisson->id;
                    $vente['collecter_par'] = auth()->user()->id;
                    $vente['creer_par'] = auth()->user()->id;
                    VenteMoisson::create($vente);
                }
            }

            // Ajouter les engagements si fournis
            if (!empty($composants['engagements'])) {
                foreach ($composants['engagements'] as $key => $engagement) {
                    $engagement['moisson_id'] = $moisson->id;
                    $engagement['collecter_par'] = auth()->user()->id;
                    $engagement['creer_par'] = auth()->user()->id;
                    EngagementMoisson::create($engagement);
                }
            }

            // Recalculer les totaux
            $moisson->recalculerTotaux();

            DB::commit();
            return $moisson->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtient le tableau de bord complet d'une moisson
     */
    public function obtenirTableauDeBord(string $moissonId): array
    {

        $rapport = DB::select('SELECT * FROM get_rapport_moisson(?)', [$moissonId]);

        if (empty($rapport)) {
            throw new \InvalidArgumentException("Moisson non trouvée: {$moissonId}");
        }

        $donnees = $rapport[0];

        return [
            'moisson' => [
                'id' => $donnees->moisson_id,
                'theme' => $donnees->theme,
                'date' => $donnees->date_moisson,
                'objectif' => $donnees->objectif,
                'total_collecte' => $donnees->total_collecte,
                'reste' => $donnees->reste_a_collecter,
                'supplement' => $donnees->supplement,
                'pourcentage' => $donnees->pourcentage_realise,
                'statut' => $donnees->statut_global
            ],
            'passages' => json_decode($donnees->passages_json, true),
            'ventes' => json_decode($donnees->ventes_json, true),
            'engagements' => json_decode($donnees->engagements_json, true),
            'totaux' => [
                'passages' => $donnees->total_passages,
                'ventes' => $donnees->total_ventes,
                'engagements' => $donnees->total_engagements,
                'engagements_en_attente' => $donnees->engagements_en_attente
            ]
        ];
    }

    /**
     * Obtient les statistiques globales du système
     */
    public function obtenirStatistiquesGlobales(Carbon $dateDebut = null, Carbon $dateFin = null): array
    {
        $stats = DB::select('SELECT * FROM get_moisson_global_stats(?, ?)', [
            $dateDebut?->toDateString(),
            $dateFin?->toDateString()
        ]);

        if (empty($stats)) {
            return $this->getStatistiquesVides();
        }

        $donnees = $stats[0];

        return [
            'periode' => [
                'date_debut' => $dateDebut?->toDateString(),
                'date_fin' => $dateFin?->toDateString(),
                'libelle' => $this->genererLibellePeriode($dateDebut, $dateFin)
            ],
            'moissons' => [
                'nombre_total' => $donnees->nombre_moissons,
                'objectif_atteint' => $donnees->moissons_objectif_atteint,
                'taux_reussite' => $donnees->nombre_moissons > 0 ?
                    round(($donnees->moissons_objectif_atteint * 100) / $donnees->nombre_moissons, 2) : 0
            ],
            'financier' => [
                'objectif_total' => $donnees->objectif_total,
                'collecte_totale' => $donnees->montant_collecte_total,
                'reste_total' => $donnees->reste_total,
                'supplement_total' => $donnees->supplement_total,
                'pourcentage_realisation' => $donnees->pourcentage_realisation
            ],
            'activites' => [
                'nombre_passages' => $donnees->nombre_passages_total,
                'nombre_ventes' => $donnees->nombre_ventes_total,
                'nombre_engagements' => $donnees->nombre_engagements_total,
                'engagements_en_retard' => $donnees->nombre_engagements_en_retard
            ]
        ];
    }

    /**
     * Gère les rappels automatiques pour les engagements
     */
    public function gererRappelsEngagements(): array
    {
        $rappelsDuJour = EngagementMoisson::getRappelsDuJour();
        $rappelsTraites = [];

        foreach ($rappelsDuJour as $engagement) {
            $rappelsTraites[] = [
                'engagement_id' => $engagement->id,
                'donateur' => $engagement->nom_donateur,
                'montant_restant' => $engagement->reste,
                'telephone' => $engagement->telephone,
                'email' => $engagement->email,
                'jours_jusqu_echeance' => $engagement->date_echeance ?
                    now()->diffInDays($engagement->date_echeance, false) : null,
                'moisson' => $engagement->moisson->theme ?? 'Non précisé'
            ];
        }

        return $rappelsTraites;
    }

    /**
     * Exporte les données d'une moisson pour reporting
     */
    public function exporterDonneesMoisson(string $moissonId, string $format = 'array'): array
    {
        $moisson = Moisson::with([
            'passageMoissons' => fn($q) => $q->with(['classe', 'collecteur']),
            'venteMoissons' => fn($q) => $q->with(['collecteur']),
            'engagementMoissons' => fn($q) => $q->with(['donateur', 'collecteur']),
            'culte',
            'createur'
        ])->findOrFail($moissonId);

        $donnees = [
            'informations_generales' => [
                'id' => $moisson->id,
                'theme' => $moisson->theme,
                'date' => $moisson->date->format('d/m/Y'),
                'culte' => $moisson->culte->titre ?? 'Non précisé',
                'createur' => $moisson->createur->nom_complet ?? 'Non précisé',
                'statut' => $moisson->status ? 'Actif' : 'Inactif',
                'date_creation' => $moisson->created_at->format('d/m/Y H:i'),
                'derniere_modification' => $moisson->updated_at->format('d/m/Y H:i')
            ],
            'objectifs_et_realisations' => [
                'objectif_initial' => $moisson->cible,
                'montant_collecte' => $moisson->montant_solde,
                'reste_a_collecter' => $moisson->reste,
                'montant_supplementaire' => $moisson->montant_supplementaire,
                'pourcentage_realisation' => $moisson->pourcentage_realise,
                'statut_progression' => $moisson->statut_progression
            ],
            'passages_bibliques' => $moisson->passages_bibliques ?? [],
            'detail_passages' => $this->formaterPassages($moisson->passageMoissons),
            'detail_ventes' => $this->formaterVentes($moisson->venteMoissons),
            'detail_engagements' => $this->formaterEngagements($moisson->engagementMoissons)
        ];

        return $donnees;
    }

    /**
     * Analyse la performance d'une moisson par rapport aux moyennes
     */
    public function analyserPerformance(string $moissonId): array
    {
        $moisson = Moisson::findOrFail($moissonId);

        // Statistiques comparatives
        $moyennes = Moisson::selectRaw('
            AVG(cible) as cible_moyenne,
            AVG(montant_solde) as collecte_moyenne,
            AVG(montant_solde * 100.0 / NULLIF(cible, 0)) as pourcentage_moyen,
            COUNT(CASE WHEN montant_solde >= cible THEN 1 END) * 100.0 / COUNT(*) as taux_reussite
        ')->where('status', true)->first();

        $position = Moisson::where('status', true)
            ->where('montant_solde', '>', $moisson->montant_solde)
            ->count() + 1;

        $totalMoissons = Moisson::where('status', true)->count();

        return [
            'moisson' => [
                'theme' => $moisson->theme,
                'pourcentage' => $moisson->pourcentage_realise,
                'montant_collecte' => $moisson->montant_solde,
                'objectif' => $moisson->cible
            ],
            'comparaisons' => [
                'vs_objectif_moyen' => [
                    'objectif_moyen' => $moyennes->cible_moyenne ?? 0,
                    'ecart_pourcentage' => $moyennes->cible_moyenne > 0 ?
                        round((($moisson->cible - $moyennes->cible_moyenne) * 100) / $moyennes->cible_moyenne, 2) : 0
                ],
                'vs_collecte_moyenne' => [
                    'collecte_moyenne' => $moyennes->collecte_moyenne ?? 0,
                    'ecart_pourcentage' => $moyennes->collecte_moyenne > 0 ?
                        round((($moisson->montant_solde - $moyennes->collecte_moyenne) * 100) / $moyennes->collecte_moyenne, 2) : 0
                ],
                'vs_performance_moyenne' => [
                    'pourcentage_moyen' => $moyennes->pourcentage_moyen ?? 0,
                    'ecart_points' => ($moisson->pourcentage_realise ?? 0) - ($moyennes->pourcentage_moyen ?? 0)
                ]
            ],
            'classement' => [
                'position' => $position,
                'total' => $totalMoissons,
                'percentile' => $totalMoissons > 0 ? round((($totalMoissons - $position + 1) * 100) / $totalMoissons, 1) : 0
            ],
            'recommandations' => $this->genererRecommandations($moisson, $moyennes)
        ];
    }

    /**
     * Planifie les rappels pour tous les engagements d'une moisson
     */
    public function planifierRappelsMoisson(string $moissonId, array $parametres = []): array
    {
        $engagements = EngagementMoisson::where('moisson_id', $moissonId)
            ->where('status', true)
            ->where('reste', '>', 0)
            ->whereNotNull('date_echeance')
            ->get();

        $rappelsPlanifies = [];
        $joursAvantEcheance = $parametres['jours_avant_echeance'] ?? 7;

        foreach ($engagements as $engagement) {
            $dateRappel = $engagement->date_echeance->subDays($joursAvantEcheance);

            if ($dateRappel->isFuture()) {
                $engagement->planifierRappel($dateRappel);
                $rappelsPlanifies[] = [
                    'engagement_id' => $engagement->id,
                    'donateur' => $engagement->nom_donateur,
                    'date_rappel' => $dateRappel->format('d/m/Y'),
                    'date_echeance' => $engagement->date_echeance->format('d/m/Y'),
                    'montant_restant' => $engagement->reste
                ];
            }
        }

        return $rappelsPlanifies;
    }

    /**
     * Gère le processus de clôture d'une moisson
     */
    public function cloturerMoisson(string $moissonId, string $userId, array $options = []): array
    {
        DB::beginTransaction();
        try {
            $moisson = Moisson::findOrFail($moissonId);

            // Vérifications préalables
            $verifications = $this->verifierConditionsCloture($moisson);
            if (!$verifications['peut_cloturer']) {
                return [
                    'succes' => false,
                    'message' => 'La moisson ne peut pas être clôturée',
                    'erreurs' => $verifications['erreurs']
                ];
            }

            // Finaliser tous les composants
            $this->finaliserComposants($moisson, $userId);

            // Marquer la moisson comme terminée
            $moisson->status = false;
            $moisson->ajouterEditeur($userId, 'cloture', [
                'date_cloture' => now()->toISOString(),
                'motif' => $options['motif'] ?? 'Clôture normale',
                'notes' => $options['notes'] ?? null
            ]);
            $moisson->save();

            // Générer le rapport final
            $rapportFinal = $this->genererRapportCloture($moisson);

            DB::commit();

            return [
                'succes' => true,
                'message' => 'Moisson clôturée avec succès',
                'rapport_final' => $rapportFinal
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Recalcule tous les totaux du système (maintenance)
     */
    public function recalculerTousLesTotaux(): array
    {
        try {
            $nombreMisAJour = DB::select('SELECT recalculate_all_moisson_totals() as count')[0]->count;

            // Rafraîchir aussi la vue matérialisée
            DB::select('SELECT refresh_moisson_statistics()');

            return [
                'succes' => true,
                'nombre_moissons_maj' => $nombreMisAJour,
                'timestamp' => now()->toISOString()
            ];
        } catch (\Exception $e) {
            return [
                'succes' => false,
                'erreur' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ];
        }
    }

    /**
     * Génère un rapport de performance pour une période donnée
     */
    public function genererRapportPerformance(Carbon $dateDebut, Carbon $dateFin): array
    {
        $moissons = Moisson::whereBetween('date', [$dateDebut, $dateFin])
            ->with(['passageMoissons', 'venteMoissons', 'engagementMoissons'])
            ->get();

        $rapportGlobal = [
            'periode' => [
                'debut' => $dateDebut->format('d/m/Y'),
                'fin' => $dateFin->format('d/m/Y'),
                'duree_jours' => $dateDebut->diffInDays($dateFin)
            ],
            'resume' => [
                'nombre_moissons' => $moissons->count(),
                'objectif_total' => $moissons->sum('cible'),
                'collecte_totale' => $moissons->sum('montant_solde'),
                'taux_realisation_global' => $moissons->sum('cible') > 0 ?
                    round(($moissons->sum('montant_solde') * 100) / $moissons->sum('cible'), 2) : 0,
                'moissons_reussies' => $moissons->where('montant_solde', '>=', function($m) { return $m->cible; })->count()
            ],
            'details_par_moisson' => $moissons->map(function($moisson) {
                return [
                    'theme' => $moisson->theme,
                    'date' => $moisson->date->format('d/m/Y'),
                    'objectif' => $moisson->cible,
                    'collecte' => $moisson->montant_solde,
                    'pourcentage' => $moisson->pourcentage_realise,
                    'statut' => $moisson->statut_progression,
                    'nb_passages' => $moisson->passageMoissons->count(),
                    'nb_ventes' => $moisson->venteMoissons->count(),
                    'nb_engagements' => $moisson->engagementMoissons->count()
                ];
            })->toArray(),
            'analyses' => [
                'meilleure_performance' => $moissons->sortByDesc('pourcentage_realise')->first()?->theme,
                'plus_grosse_collecte' => $moissons->sortByDesc('montant_solde')->first()?->theme,
                'objectif_le_plus_ambitieux' => $moissons->sortByDesc('cible')->first()?->theme
            ]
        ];

        return $rapportGlobal;
    }

    // Méthodes privées d'assistance

    private function getStatistiquesVides(): array
    {
        return [
            'periode' => null,
            'moissons' => ['nombre_total' => 0, 'objectif_atteint' => 0, 'taux_reussite' => 0],
            'financier' => ['objectif_total' => 0, 'collecte_totale' => 0, 'reste_total' => 0, 'supplement_total' => 0, 'pourcentage_realisation' => 0],
            'activites' => ['nombre_passages' => 0, 'nombre_ventes' => 0, 'nombre_engagements' => 0, 'engagements_en_retard' => 0]
        ];
    }

    private function genererLibellePeriode(Carbon $debut = null, Carbon $fin = null): string
    {
        if (!$debut && !$fin) return 'Toutes périodes';
        if (!$fin) return 'Depuis le ' . $debut->format('d/m/Y');
        if (!$debut) return 'Jusqu\'au ' . $fin->format('d/m/Y');
        return 'Du ' . $debut->format('d/m/Y') . ' au ' . $fin->format('d/m/Y');
    }

    private function formaterPassages(Collection $passages): array
    {
        return $passages->map(function ($passage) {
            return [
                'categorie' => $passage->categorie_libelle,
                'classe' => $passage->classe->nom ?? null,
                'objectif' => $passage->cible,
                'collecte' => $passage->montant_solde,
                'reste' => $passage->reste,
                'pourcentage' => $passage->pourcentage_realise,
                'collecteur' => $passage->collecteur->nom_complet ?? 'Non précisé',
                'date_collecte' => $passage->collecte_le?->format('d/m/Y H:i'),
                'statut' => $passage->status ? 'Validé' : 'En attente'
            ];
        })->toArray();
    }

    private function formaterVentes(Collection $ventes): array
    {
        return $ventes->map(function ($vente) {
            return [
                'categorie' => $vente->categorie_libelle,
                'description' => $vente->description,
                'objectif' => $vente->cible,
                'collecte' => $vente->montant_solde,
                'reste' => $vente->reste,
                'pourcentage' => $vente->pourcentage_realise,
                'collecteur' => $vente->collecteur->nom_complet ?? 'Non précisé',
                'date_collecte' => $vente->collecte_le?->format('d/m/Y H:i'),
                'statut' => $vente->status ? 'Validé' : 'En attente'
            ];
        })->toArray();
    }

    private function formaterEngagements(Collection $engagements): array
    {

        return $engagements->map(function ($engagement) {
            //  dd($engagement->categorie_libelle, $engagement->donateur, $engagement->nom_entite);
            return [
                'categorie' => $engagement->categorie_libelle,
                'donateur' => $engagement->donateur_id ?  $engagement->donateur->nom_complet : $engagement->nom_entite,
                'objectif' => $engagement->cible,
                'collecte' => $engagement->montant_solde,
                'reste' => $engagement->reste,
                'pourcentage' => $engagement->pourcentage_realise,
                'telephone' => $engagement->telephone,
                'email' => $engagement->email,
                'date_echeance' => $engagement->date_echeance?->format('d/m/Y'),
                'en_retard' => $engagement->est_en_retard,
                'jours_retard' => $engagement->jours_retard,
                'niveau_urgence' => $engagement->niveau_urgence_libelle,
                'collecteur' => $engagement->collecteur->nom_complet ?? 'Non précisé',
                'date_collecte' => $engagement->collecter_le?->format('d/m/Y H:i'),
                'statut' => $engagement->status ? 'Validé' : 'En attente'
            ];
        })->toArray();
    }

    private function genererRecommandations($moisson, $moyennes): array
    {
        $recommandations = [];
        $pourcentage = $moisson->pourcentage_realise ?? 0;

        if ($pourcentage < 50) {
            $recommandations[] = [
                'type' => 'critique',
                'message' => 'Performance très faible. Intensifier les efforts de collecte.',
                'actions' => ['Réviser la stratégie', 'Mobiliser plus d\'acteurs', 'Revoir l\'objectif']
            ];
        } elseif ($pourcentage < 80) {
            $recommandations[] = [
                'type' => 'attention',
                'message' => 'Performance modérée. Des améliorations sont possibles.',
                'actions' => ['Relancer les engagements', 'Organiser des ventes supplémentaires']
            ];
        } elseif ($pourcentage >= 100) {
            $recommandations[] = [
                'type' => 'succes',
                'message' => 'Objectif atteint ! Excellente performance.',
                'actions' => ['Capitaliser sur cette réussite', 'Partager les bonnes pratiques']
            ];
        }

        if ($moisson->engagementMoissons()->enRetard()->count() > 0) {
            $recommandations[] = [
                'type' => 'urgence',
                'message' => 'Des engagements sont en retard.',
                'actions' => ['Effectuer des relances', 'Planifier des rencontres', 'Renégocier les échéances']
            ];
        }

        return $recommandations;
    }

    private function verifierConditionsCloture(Moisson $moisson): array
    {
        $erreurs = [];

        // Vérifier les engagements en attente
        $engagementsEnAttente = $moisson->engagementMoissons()
            ->where('status', true)
            ->where('reste', '>', 0)
            ->count();

        if ($engagementsEnAttente > 0) {
            $erreurs[] = "Il reste {$engagementsEnAttente} engagement(s) non soldé(s)";
        }

        // Vérifier les éléments non validés
        $elementsNonValides = $moisson->passageMoissons()->where('status', false)->count() +
                             $moisson->venteMoissons()->where('status', false)->count() +
                             $moisson->engagementMoissons()->where('status', false)->count();

        if ($elementsNonValides > 0) {
            $erreurs[] = "Il reste {$elementsNonValides} élément(s) non validé(s)";
        }

        return [
            'peut_cloturer' => empty($erreurs),
            'erreurs' => $erreurs
        ];
    }

    private function finaliserComposants(Moisson $moisson, string $userId): void
    {
        // Valider tous les passages non validés
        $moisson->passageMoissons()->where('status', false)->update(['status' => true]);

        // Valider toutes les ventes non validées
        $moisson->venteMoissons()->where('status', false)->update(['status' => true]);

        // Marquer les engagements non soldés comme "clôturés"
        $engagementsNonSoldes = $moisson->engagementMoissons()
            ->where('reste', '>', 0)
            ->get();

        foreach ($engagementsNonSoldes as $engagement) {
            $engagement->ajouterEditeur($userId, 'cloture_avec_reste', [
                'reste_non_collecte' => $engagement->reste
            ]);
        }
    }

    private function genererRapportCloture(Moisson $moisson): array
    {
        return [
            'moisson' => [
                'theme' => $moisson->theme,
                'date' => $moisson->date->format('d/m/Y'),
                'objectif_initial' => $moisson->cible,
                'montant_final' => $moisson->montant_solde,
                'pourcentage_final' => $moisson->pourcentage_realise,
                'statut_final' => $moisson->statut_progression
            ],
            'synthese' => [
                'objectif_atteint' => $moisson->objectif_atteint,
                'montant_supplement' => $moisson->montant_supplementaire,
                'reste_non_collecte' => $moisson->reste
            ],
            'activites' => [
                'nombre_passages' => $moisson->passageMoissons->count(),
                'nombre_ventes' => $moisson->venteMoissons->count(),
                'nombre_engagements' => $moisson->engagementMoissons->count(),
                'engagements_non_soldes' => $moisson->engagementMoissons()->where('reste', '>', 0)->count()
            ],
            'date_cloture' => now()->format('d/m/Y H:i')
        ];
    }
}
