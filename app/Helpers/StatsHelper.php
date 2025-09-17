<?php

namespace App\Helpers;

use App\Models\Moisson;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsHelper
{
    /**
     * Calcule les statistiques de base pour les moissons
     */
    public static function getStatistiquesBase($dateDebut = null, $dateFin = null): array
    {
        $query = Moisson::query();

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        }

        $totalMoissons = $query->count();
        $totalCollecte = $query->sum('montant_solde');
        $totalCible = $query->sum('cible');
        $objectifsAtteints = $query->whereRaw('montant_solde >= cible')->count();

        $pourcentageMoyen = $totalCible > 0 ?
            round(($totalCollecte * 100) / $totalCible, 2) : 0;

        $tauxReussite = $totalMoissons > 0 ?
            round(($objectifsAtteints * 100) / $totalMoissons, 2) : 0;

        return [
            'total_moissons' => $totalMoissons,
            'total_collecte' => $totalCollecte,
            'total_cible' => $totalCible,
            'objectifs_atteints' => $objectifsAtteints,
            'pourcentage_moyen' => $pourcentageMoyen,
            'taux_reussite' => $tauxReussite
        ];
    }

    /**
     * Calcule les statistiques par composant
     */
    public static function getStatistiquesComposants($dateDebut = null, $dateFin = null): array
    {
        $baseCondition = "";
        $params = [];

        if ($dateDebut && $dateFin) {
            $baseCondition = "JOIN moissons m ON {table}.moisson_id = m.id WHERE m.date BETWEEN ? AND ? AND m.deleted_at IS NULL";
            $params = [$dateDebut, $dateFin];
        }

        // Passages
        $passagesQuery = str_replace('{table}', 'pm', $baseCondition);
        $passages = DB::selectOne("
            SELECT
                COUNT(*) as nombre,
                COALESCE(SUM(montant_solde), 0) as total
            FROM passage_moissons pm
            {$passagesQuery}
        ", $params);

        // Ventes
        $ventesQuery = str_replace('{table}', 'vm', $baseCondition);
        $ventes = DB::selectOne("
            SELECT
                COUNT(*) as nombre,
                COALESCE(SUM(montant_solde), 0) as total
            FROM vente_moissons vm
            {$ventesQuery}
        ", $params);

        // Engagements
        $engagementsQuery = str_replace('{table}', 'em', $baseCondition);
        $engagements = DB::selectOne("
            SELECT
                COUNT(*) as nombre,
                COALESCE(SUM(montant_solde), 0) as total
            FROM engagement_moissons em
            {$engagementsQuery}
        ", $params);

        $totalGeneral = $passages->total + $ventes->total + $engagements->total;

        return [
            'passages' => [
                'nombre' => $passages->nombre,
                'total' => $passages->total,
                'pourcentage' => $totalGeneral > 0 ? ($passages->total * 100) / $totalGeneral : 0
            ],
            'ventes' => [
                'nombre' => $ventes->nombre,
                'total' => $ventes->total,
                'pourcentage' => $totalGeneral > 0 ? ($ventes->total * 100) / $totalGeneral : 0
            ],
            'engagements' => [
                'nombre' => $engagements->nombre,
                'total' => $engagements->total,
                'pourcentage' => $totalGeneral > 0 ? ($engagements->total * 100) / $totalGeneral : 0
            ]
        ];
    }

    /**
     * Calcule l'évolution par rapport à une période précédente
     */
    public static function getEvolutionPeriode($dateDebut, $dateFin): array
    {
        $duree = $dateDebut->diffInDays($dateFin);
        $debutPrec = $dateDebut->copy()->subDays($duree);
        $finPrec = $dateFin->copy()->subDays($duree);

        $statsPeriode = self::getStatistiquesBase($dateDebut, $dateFin);
        $statsPrec = self::getStatistiquesBase($debutPrec, $finPrec);

        $evolutionCollecte = $statsPrec['total_collecte'] > 0 ?
            round((($statsPeriode['total_collecte'] - $statsPrec['total_collecte']) * 100) / $statsPrec['total_collecte'], 1) : 0;

        $evolutionNombre = $statsPrec['total_moissons'] > 0 ?
            round((($statsPeriode['total_moissons'] - $statsPrec['total_moissons']) * 100) / $statsPrec['total_moissons'], 1) : 0;

        return [
            'evolution_collecte' => $evolutionCollecte,
            'evolution_nombre' => $evolutionNombre,
            'periode_precedente' => [
                'debut' => $debutPrec->format('d/m/Y'),
                'fin' => $finPrec->format('d/m/Y'),
                'stats' => $statsPrec
            ]
        ];
    }

    /**
     * Formatte les données pour les graphiques
     */
    public static function formatDataForChart($type, $data): array
    {
        switch ($type) {
            case 'evolution_mensuelle':
                return [
                    'labels' => $data->map(fn($d) => Carbon::parse($d->mois)->format('M Y'))->toArray(),
                    'data' => $data->pluck('total')->toArray()
                ];

            case 'repartition':
                return [
                    'labels' => ['Passages', 'Ventes', 'Engagements'],
                    'data' => array_values($data)
                ];

            default:
                return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Valide et normalise une période
     */
    public static function normaliserPeriode($periode): array
    {
        $periodesValides = ['7', '30', '90', '365', 'all'];

        if (!in_array($periode, $periodesValides)) {
            $periode = '30'; // Valeur par défaut
        }

        $dateFin = now();
        $dateDebut = null;

        switch ($periode) {
            case '7':
                $dateDebut = now()->subDays(7);
                break;
            case '30':
                $dateDebut = now()->subDays(30);
                break;
            case '90':
                $dateDebut = now()->subDays(90);
                break;
            case '365':
                $dateDebut = now()->subDays(365);
                break;
            case 'all':
                // Pas de filtre de date
                $dateDebut = null;
                $dateFin = null;
                break;
        }

        return [
            'periode' => $periode,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'libelle' => self::genererLibellePeriode($dateDebut, $dateFin)
        ];
    }

    private static function genererLibellePeriode($dateDebut, $dateFin): string
    {
        if (!$dateDebut && !$dateFin) {
            return 'Toute la période';
        }

        if (!$dateDebut) {
            return 'Jusqu\'au ' . $dateFin->format('d/m/Y');
        }

        if (!$dateFin) {
            return 'Depuis le ' . $dateDebut->format('d/m/Y');
        }

        return 'Du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y');
    }
}
