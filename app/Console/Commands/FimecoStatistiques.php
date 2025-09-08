<?php

// =================================================================
// app/Console/Commands/FimecoStatistiques.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fimeco;
use App\Services\FimecoService;

class FimecoStatistiques extends Command
{
    protected $signature = 'fimeco:statistiques {--fimeco= : ID de la FIMECO spécifique}';

    protected $description = 'Afficher les statistiques des FIMECO';

    public function __construct(private FimecoService $fimecoService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $fimecoId = $this->option('fimeco');

        if ($fimecoId) {
            $this->afficherStatistiquesFimeco($fimecoId);
        } else {
            $this->afficherToutesStatistiques();
        }

        return Command::SUCCESS;
    }

    private function afficherStatistiquesFimeco(string $fimecoId): void
    {
        try {
            $stats = $this->fimecoService->obtenirStatistiquesFimeco($fimecoId);
            $fimeco = Fimeco::findOrFail($fimecoId);

            $this->info("=== Statistiques FIMECO: {$fimeco->nom} ===");
            $this->table([
                'Métrique', 'Valeur'
            ], [
                ['Total souscriptions', number_format($stats['total_souscriptions'], 2) . ' FCFA'],
                ['Total payé', number_format($stats['total_paye'], 2) . ' FCFA'],
                ['Reste à collecter', number_format($stats['reste_a_collecter'], 2) . ' FCFA'],
                ['% de réalisation', $stats['pourcentage_realisation'] . '%'],
                ['Nombre souscripteurs', $stats['nombre_souscripteurs']],
                ['Montant moyen', number_format($stats['montant_moyen_souscription'], 2) . ' FCFA']
            ]);

        } catch (\Exception $e) {
            $this->error("Erreur: " . $e->getMessage());
        }
    }

    private function afficherToutesStatistiques(): void
    {
        $fimecos = Fimeco::with('subscriptions')->get();

        $data = $fimecos->map(function ($fimeco) {
            return [
                $fimeco->nom,
                $fimeco->statut,
                $fimeco->debut->format('d/m/Y'),
                $fimeco->fin->format('d/m/Y'),
                number_format($fimeco->cible, 2) . ' FCFA',
                number_format($fimeco->total_paye, 2) . ' FCFA',
                $fimeco->pourcentage_realisation . '%'
            ];
        });

        $this->table([
            'FIMECO', 'Statut', 'Début', 'Fin', 'Objectif', 'Collecté', '% Réalisé'
        ], $data);
    }
}
