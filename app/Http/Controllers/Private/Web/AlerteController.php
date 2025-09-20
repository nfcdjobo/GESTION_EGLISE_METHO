<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AlerteController extends Controller
{
    /**
     * Filtrer les utilisateurs avec une assiduité faible aux cultes
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function membresAssiduiteFaible(Request $request)
    {
        // Validation des paramètres
        $validated = $request->validate([
            'type_alerte' => 'sometimes|string|in:dimanches_successifs,cultes_mensuels,both',
            'periode_mois' => 'sometimes|integer|min:1|max:12',
            'format' => 'sometimes|string|in:json,html'
        ]);

        $typeAlerte = $validated['type_alerte'] ?? 'both';
        $periodeMois = $validated['periode_mois'] ?? 1;

        // Déterminer le format de réponse selon l'en-tête Accept
        if ($request->expectsJson()) {
            return $this->getResponseJson($typeAlerte, $periodeMois);
        }

        return $this->getResponseBlade($typeAlerte, $periodeMois);
    }

    /**
     * Générer la réponse JSON
     */
    private function getResponseJson(string $typeAlerte, int $periodeMois): JsonResponse
    {
        $data = $this->getMembresFaibleAssiduite($typeAlerte, $periodeMois);

        return response()->json([
            'success' => true,
            'message' => 'Analyse d\'assiduité terminée',
            'data' => [
                'criteres' => [
                    'type_alerte' => $typeAlerte,
                    'periode_analyse' => $periodeMois,
                    'date_analyse' => Carbon::now()->toISOString()
                ],
                'statistiques' => [
                    'total_membres_analyses' => $data['total_membres'],
                    'membres_dimanches_successifs' => count($data['dimanches_successifs'] ?? []),
                    'membres_cultes_mensuels' => count($data['cultes_mensuels'] ?? []),
                    'membres_critique' => count($data['critique'] ?? [])
                ],
                'resultats' => $data['resultats']
            ],
            'meta' => [
                'generated_at' => Carbon::now()->toISOString(),
                'version' => '1.0'
            ]
        ]);
    }

    /**
     * Générer la réponse Blade
     */
    private function getResponseBlade(string $typeAlerte, int $periodeMois): View
    {
        $data = $this->getMembresFaibleAssiduite($typeAlerte, $periodeMois);

        return view('components.private.alertes.assiduite-faible', [
            'membres' => $data['resultats'],
            'statistiques' => $data,
            'typeAlerte' => $typeAlerte,
            'periodeMois' => $periodeMois,
            'dateAnalyse' => Carbon::now()
        ]);
    }

    /**
     * Logique principale pour identifier les membres à faible assiduité
     */
    private function getMembresFaibleAssiduite(string $typeAlerte, int $periodeMois): array
    {
        // Pour le mois : seulement le mois précédent complet
        $debutMoisPrecedent = Carbon::now()->startOfMonth()->subMonth();
        $finMoisPrecedent = Carbon::now()->startOfMonth()->subDay();

        // Pour les dimanches : les 2 derniers dimanches passés uniquement
        $dimanchesRecents = $this->getDerniersDimanches(2);

        $resultats = [];
        $statistics = [
            'total_membres' => 0,
            'dimanches_successifs' => [],
            'cultes_mensuels' => [],
            'critique' => []
        ];

        // Récupérer tous les membres actifs
        $membresActifs = DB::table('users')
            ->where('deleted_at', null)
            ->where('actif', true)
            ->where('statut_membre', 'actif')
            ->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'date_adhesion')
            ->get();

        $statistics['total_membres'] = $membresActifs->count();

        foreach ($membresActifs as $membre) {
            $alertes = [];
            $severite = 'normale';

            // Vérification 1: Absence aux 2 derniers dimanches passés
            if (in_array($typeAlerte, ['dimanches_successifs', 'both'])) {
                $absenceDimanches = $this->verifierAbsenceDerniersDimanches($membre->id, $dimanchesRecents);
                if ($absenceDimanches['absent']) {
                    $alertes[] = [
                        'type' => 'dimanches_successifs',
                        'description' => "Absent aux {$absenceDimanches['nombre_dimanches_manques']} derniers dimanches",
                        'details' => $absenceDimanches,
                        'severite' => $absenceDimanches['nombre_dimanches_manques'] >= 2 ? 'critique' : 'attention'
                    ];
                    $statistics['dimanches_successifs'][] = $membre->id;

                    if ($absenceDimanches['nombre_dimanches_manques'] >= 2) {
                        $severite = 'critique';
                    }
                }
            }

            // Vérification 2: Participation au mois précédent uniquement
            if (in_array($typeAlerte, ['cultes_mensuels', 'both'])) {
                $participationMoisPrecedent = $this->verifierParticipationMoisPrecedent($membre->id, $debutMoisPrecedent, $finMoisPrecedent);
                if ($participationMoisPrecedent['participation_faible']) {
                    $alertes[] = [
                        'type' => 'cultes_mensuels',
                        'description' => "Seulement {$participationMoisPrecedent['nombre_cultes']} culte(s) le mois dernier",
                        'details' => $participationMoisPrecedent,
                        'severite' => $participationMoisPrecedent['nombre_cultes'] === 0 ? 'critique' : 'attention'
                    ];
                    $statistics['cultes_mensuels'][] = $membre->id;

                    if ($participationMoisPrecedent['nombre_cultes'] === 0) {
                        $severite = 'critique';
                    }
                }
            }

            // Si des alertes ont été trouvées, ajouter le membre aux résultats
            if (!empty($alertes)) {
                $dernierCulteInfo = $this->getDernierCulteInfo($membre->id);

                $membreAlerte = [
                    'membre' => [
                        'id' => $membre->id,
                        'nom_complet' => $membre->prenom . ' ' . $membre->nom,
                        'prenom' => $membre->prenom,
                        'nom' => $membre->nom,
                        'email' => $membre->email,
                        'telephone' => $membre->telephone_1,
                        'date_adhesion' => $membre->date_adhesion
                    ],
                    'alertes' => $alertes,
                    'severite' => $severite,
                    'dernier_culte' => $dernierCulteInfo,
                    'score_assiduite' => $this->calculerScoreAssiduitePrecedent($membre->id, $debutMoisPrecedent, $finMoisPrecedent),
                    'date_analyse' => Carbon::now()->toDateString()
                ];

                $resultats[] = $membreAlerte;

                if ($severite === 'critique') {
                    $statistics['critique'][] = $membre->id;
                }
            }
        }

        // Trier par sévérité puis par score d'assiduité
        usort($resultats, function ($a, $b) {
            $severityOrder = ['critique' => 3, 'attention' => 2, 'normale' => 1];

            if ($severityOrder[$a['severite']] !== $severityOrder[$b['severite']]) {
                return $severityOrder[$b['severite']] - $severityOrder[$a['severite']];
            }

            return $a['score_assiduite'] - $b['score_assiduite'];
        });

        return [
            'resultats' => $resultats,
            'total_membres' => $statistics['total_membres'],
            'dimanches_successifs' => $statistics['dimanches_successifs'],
            'cultes_mensuels' => $statistics['cultes_mensuels'],
            'critique' => $statistics['critique']
        ];
    }

    /**
     * Obtenir les N derniers dimanches passés avec leurs cultes
     */
    private function getDerniersDimanches(int $nombre = 2): array
    {
        $dimanches = [];
        $date = Carbon::now();

        // Trouver les derniers dimanches passés (vérifier que c'est bien un dimanche)
        while (count($dimanches) < $nombre) {
            // Aller au dimanche précédent
            if ($date->dayOfWeek === Carbon::SUNDAY && $date->isPast()) {
                $dimanches[] = $date->copy();
            }
            $date->subDay();

            // Sécurité : ne pas aller plus de 60 jours dans le passé
            if ($date->diffInDays(Carbon::now()) > 60) {
                break;
            }
        }

        // Récupérer les cultes de ces dimanches (vérifier que date_culte est un dimanche)
        $dimanchesAvecCultes = [];
        foreach ($dimanches as $dimanche) {
            $culte = DB::table('cultes')
                ->where('date_culte', $dimanche->toDateString())
                ->where('type_culte', 'dimanche_matin')
                ->where('statut', 'termine')
                ->where('deleted_at', null)
                // Vérifier que la date_culte correspond bien à un dimanche
                ->whereRaw('EXTRACT(DOW FROM date_culte) = 0') // 0 = Dimanche en PostgreSQL
                ->first();

            if ($culte) {
                $dimanchesAvecCultes[] = [
                    'date' => $dimanche,
                    'culte' => $culte
                ];
            }
        }

        return $dimanchesAvecCultes;
    }

    /**
     * Vérifier l'absence aux derniers dimanches spécifiques
     */
    private function verifierAbsenceDerniersDimanches(string $membreId, array $dimanchesRecents): array
    {
        if (empty($dimanchesRecents)) {
            return [
                'absent' => false,
                'nombre_dimanches_manques' => 0,
                'total_dimanches_analyses' => 0
            ];
        }

        // Récupérer la date d'adhésion du membre
        $dateAdhesion = DB::table('users')
            ->where('id', $membreId)
            ->value('date_adhesion');

        // Filtrer les dimanches selon la date d'adhésion
        $dimanchesApresAdhesion = [];
        foreach ($dimanchesRecents as $dimanche) {
            // Si pas de date d'adhésion ou dimanche après adhésion
            if (!$dateAdhesion || Carbon::parse($dateAdhesion)->lte($dimanche['date'])) {
                $dimanchesApresAdhesion[] = $dimanche;
            }
        }

        if (empty($dimanchesApresAdhesion)) {
            return [
                'absent' => false,
                'nombre_dimanches_manques' => 0,
                'total_dimanches_analyses' => 0,
                'message' => 'Membre adhéré après les dimanches analysés'
            ];
        }

        $cultesIds = array_column(array_column($dimanchesApresAdhesion, 'culte'), 'id');

        // Récupérer les participations du membre à ces cultes spécifiques
        $participations = DB::table('participant_cultes')
            ->whereIn('culte_id', $cultesIds)
            ->where('participant_id', $membreId)
            ->where('deleted_at', null)
            ->pluck('culte_id')
            ->toArray();

        $dimanchesManques = count($cultesIds) - count($participations);

        return [
            'absent' => $dimanchesManques >= 1, // Au moins 1 dimanche manqué = alerte
            'nombre_dimanches_manques' => $dimanchesManques,
            'total_dimanches_analyses' => count($cultesIds),
            'participations_enregistrees' => count($participations),
            'date_adhesion' => $dateAdhesion,
            'dimanches_details' => array_map(function($dimanche) use ($participations) {
                return [
                    'date' => $dimanche['date']->format('Y-m-d'),
                    'culte_id' => $dimanche['culte']->id,
                    'present' => in_array($dimanche['culte']->id, $participations)
                ];
            }, $dimanchesApresAdhesion)
        ];
    }

    /**
     * Vérifier la participation au mois précédent uniquement
     */
    private function verifierParticipationMoisPrecedent(string $membreId, Carbon $debutMois, Carbon $finMois): array
    {
        // Récupérer la date d'adhésion du membre
        $dateAdhesion = DB::table('users')
            ->where('id', $membreId)
            ->value('date_adhesion');

        // Ajuster la période d'analyse selon la date d'adhésion
        $dateDebutAnalyse = $debutMois;
        if ($dateAdhesion && Carbon::parse($dateAdhesion)->gt($debutMois)) {
            $dateDebutAnalyse = Carbon::parse($dateAdhesion);

            // Si adhésion après la fin du mois analysé, pas d'analyse possible
            if ($dateDebutAnalyse->gt($finMois)) {
                return [
                    'participation_faible' => false,
                    'nombre_cultes' => 0,
                    'total_cultes_mois' => 0,
                    'seuil_minimum' => 0,
                    'mois_analyse' => $debutMois->format('F Y'),
                    'pourcentage_assiduite' => 0,
                    'message' => 'Membre adhéré après le mois analysé',
                    'periode' => [
                        'debut' => $debutMois->format('Y-m-d'),
                        'fin' => $finMois->format('Y-m-d'),
                        'adhesion' => $dateAdhesion
                    ]
                ];
            }
        }

        $nombreCultes = DB::table('participant_cultes')
            ->join('cultes', 'participant_cultes.culte_id', '=', 'cultes.id')
            ->where('participant_cultes.participant_id', $membreId)
            ->where('cultes.date_culte', '>=', $dateDebutAnalyse->toDateString())
            ->where('cultes.date_culte', '<=', $finMois->toDateString())
            ->where('cultes.statut', 'termine')
            ->where('participant_cultes.deleted_at', null)
            ->where('cultes.deleted_at', null)
            ->count();

        // Compter les cultes disponibles dans la période réelle d'analyse
        $totalCultesMois = DB::table('cultes')
            ->where('date_culte', '>=', $dateDebutAnalyse->toDateString())
            ->where('date_culte', '<=', $finMois->toDateString())
            ->where('statut', 'termine')
            ->where('deleted_at', null)
            ->count();

        $seuil = max(1, intval($totalCultesMois * 0.5)); // Au moins 50% des cultes disponibles

        return [
            'participation_faible' => $nombreCultes < $seuil && $totalCultesMois > 0,
            'nombre_cultes' => $nombreCultes,
            'total_cultes_mois' => $totalCultesMois,
            'seuil_minimum' => $seuil,
            'mois_analyse' => $debutMois->format('F Y'),
            'pourcentage_assiduite' => $totalCultesMois > 0 ? round(($nombreCultes / $totalCultesMois) * 100, 1) : 0,
            'date_adhesion' => $dateAdhesion,
            'periode' => [
                'debut' => $dateDebutAnalyse->format('Y-m-d'),
                'fin' => $finMois->format('Y-m-d'),
                'debut_theorique' => $debutMois->format('Y-m-d')
            ]
        ];
    }

    /**
     * Obtenir les informations du dernier culte auquel le membre a participé
     */
    private function getDernierCulteInfo(string $membreId): ?array
    {
        $dernierCulte = DB::table('participant_cultes')
            ->join('cultes', 'participant_cultes.culte_id', '=', 'cultes.id')
            ->where('participant_cultes.participant_id', $membreId)
            ->where('participant_cultes.deleted_at', null)
            ->where('cultes.deleted_at', null)
            ->orderBy('cultes.date_culte', 'desc')
            ->select('cultes.titre', 'cultes.date_culte', 'cultes.type_culte', 'participant_cultes.statut_presence')
            ->first();

        if (!$dernierCulte) {
            return null;
        }

        return [
            'titre' => $dernierCulte->titre,
            'date' => $dernierCulte->date_culte,
            'type' => $dernierCulte->type_culte,
            'statut_presence' => $dernierCulte->statut_presence,
            'jours_depuis' => Carbon::parse($dernierCulte->date_culte)->diffInDays(Carbon::now())
        ];
    }

    /**
     * Calculer un score d'assiduité pour le mois précédent (0-100)
     */
    private function calculerScoreAssiduitePrecedent(string $membreId, Carbon $debutMois, Carbon $finMois): int
    {
        // Récupérer la date d'adhésion du membre
        $dateAdhesion = DB::table('users')
            ->where('id', $membreId)
            ->value('date_adhesion');

        // Ajuster la période d'analyse selon la date d'adhésion
        $dateDebutAnalyse = $debutMois;
        if ($dateAdhesion && Carbon::parse($dateAdhesion)->gt($debutMois)) {
            $dateDebutAnalyse = Carbon::parse($dateAdhesion);

            // Si adhésion après la fin du mois analysé, score parfait par défaut
            if ($dateDebutAnalyse->gt($finMois)) {
                return 100;
            }
        }

        $totalCultes = DB::table('cultes')
            ->where('date_culte', '>=', $dateDebutAnalyse->toDateString())
            ->where('date_culte', '<=', $finMois->toDateString())
            ->where('statut', 'termine')
            ->where('deleted_at', null)
            ->count();

        if ($totalCultes === 0) {
            return 100;
        }

        $participations = DB::table('participant_cultes')
            ->join('cultes', 'participant_cultes.culte_id', '=', 'cultes.id')
            ->where('participant_cultes.participant_id', $membreId)
            ->where('cultes.date_culte', '>=', $dateDebutAnalyse->toDateString())
            ->where('cultes.date_culte', '<=', $finMois->toDateString())
            ->where('cultes.statut', 'termine')
            ->where('participant_cultes.deleted_at', null)
            ->where('cultes.deleted_at', null)
            ->count();

        return (int) round(($participations / $totalCultes) * 100);
    }

    /**
     * API pour obtenir uniquement les statistiques
     */
    public function statistiquesAssiduite(Request $request): JsonResponse
    {
        // Utiliser la logique standard mais avec les périodes précises
        $data = $this->getMembresFaibleAssiduite('both', 1); // 1 pour le mois précédent

        return response()->json([
            'statistiques' => [
                'total_membres_actifs' => $data['total_membres'],
                'membres_alerte_dimanches' => count($data['dimanches_successifs']),
                'membres_alerte_mensuelle' => count($data['cultes_mensuels']),
                'membres_critiques' => count($data['critique']),
                'pourcentage_problematique' => $data['total_membres'] > 0 ?
                    round((count($data['resultats']) / $data['total_membres']) * 100, 1) : 0
            ],
            'periode_analyse' => [
                'mois_precedent' => Carbon::now()->startOfMonth()->subMonth()->format('F Y'),
                'derniers_dimanches' => '2 derniers dimanches passés'
            ],
            'date_analyse' => Carbon::now()->toISOString()
        ]);
    }
}
