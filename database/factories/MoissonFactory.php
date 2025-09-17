<?php

namespace Database\Factories;

use App\Models\Moisson;
use App\Models\User;
use App\Models\Culte;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class MoissonFactory extends Factory
{
    protected $model = Moisson::class;

    public function definition(): array
    {
        return [
            'theme' => $this->faker->sentence(4),
            'date' => $this->faker->dateTimeBetween('-1 year', '+3 months')->format('Y-m-d'),
            'cible' => $this->faker->numberBetween(100000, 1000000),
            'passages_bibliques' => $this->genererPassagesBibliques(),
            'culte_id' => User::factory(), // À remplacer par Culte::factory() si le modèle existe
            'creer_par' => User::factory(),
            'status' => $this->faker->boolean(80) // 80% de chance d'être actif
        ];
    }

    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function avecObjectifEleve(): static
    {
        return $this->state(fn (array $attributes) => [
            'cible' => $this->faker->numberBetween(1000000, 5000000),
        ]);
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ]);
    }

    public function passe(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d'),
        ]);
    }

    public function futur(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('+1 week', '+6 months')->format('Y-m-d'),
        ]);
    }

    private function genererPassagesBibliques(): array
    {
        $livres = [
            'Genèse', 'Exode', 'Lévitique', 'Nombres', 'Deutéronome',
            'Matthieu', 'Marc', 'Luc', 'Jean', 'Actes',
            'Psaumes', 'Proverbes', 'Ecclésiaste', 'Cantique des cantiques',
            'Romains', '1 Corinthiens', '2 Corinthiens', 'Galates', 'Éphésiens'
        ];

        $passages = [];
        $nombrePassages = $this->faker->numberBetween(1, 3);

        for ($i = 0; $i < $nombrePassages; $i++) {
            $livre = $this->faker->randomElement($livres);
            $chapitre = $this->faker->numberBetween(1, 50);
            $versetDebut = $this->faker->numberBetween(1, 20);
            $versetFin = $this->faker->optional(0.7)->numberBetween($versetDebut + 1, $versetDebut + 10);

            $passages[] = [
                'livre' => $livre,
                'chapitre' => $chapitre,
                'verset_debut' => $versetDebut,
                'verset_fin' => $versetFin,
                'reference' => $versetFin ?
                    "{$livre} {$chapitre}:{$versetDebut}-{$versetFin}" :
                    "{$livre} {$chapitre}:{$versetDebut}"
            ];
        }

        return $passages;
    }
}

// Factory pour PassageMoisson
class PassageMoissonFactory extends Factory
{
    protected $model = \App\Models\PassageMoisson::class;

    public function definition(): array
    {
        $categories = array_keys(\App\Models\PassageMoisson::CATEGORIES);

        return [
            'moisson_id' => Moisson::factory(),
            'categorie' => $this->faker->randomElement($categories),
            'cible' => $this->faker->numberBetween(10000, 200000),
            'collecter_par' => User::factory(),
            'creer_par' => User::factory(),
            'collecte_le' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->boolean(70)
        ];
    }

    public function classeCommunautaire(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'passage_classe_communautaire',
            'classe_id' => User::factory(), // À remplacer par Classe::factory()
        ]);
    }

    public function avecMontant(float $montant = null): static
    {
        return $this->state(fn (array $attributes) => [
            'montant_solde' => $montant ?? $this->faker->numberBetween(
                0,
                $attributes['cible'] ?? 50000
            ),
        ]);
    }
}

// Factory pour VenteMoisson
class VenteMoissonFactory extends Factory
{
    protected $model = \App\Models\VenteMoisson::class;

    public function definition(): array
    {
        $categories = array_keys(\App\Models\VenteMoisson::CATEGORIES);

        return [
            'moisson_id' => Moisson::factory(),
            'categorie' => $this->faker->randomElement($categories),
            'cible' => $this->faker->numberBetween(5000, 100000),
            'collecter_par' => User::factory(),
            'creer_par' => User::factory(),
            'collecte_le' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'description' => $this->faker->sentence(8),
            'status' => $this->faker->boolean(75)
        ];
    }

   

    public function aliments(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'aliments',
            'description' => 'Vente de produits alimentaires - ' . $this->faker->words(3, true)
        ]);
    }

    public function arbresVie(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'arbres_vie',
            'description' => 'Vente d\'arbres de vie - ' . $this->faker->words(3, true)
        ]);
    }
}

// Factory pour EngagementMoisson
class EngagementMoissonFactory extends Factory
{
    protected $model = \App\Models\EngagementMoisson::class;

    public function definition(): array
    {
        $categories = array_keys(\App\Models\EngagementMoisson::CATEGORIES);
        $categorie = $this->faker->randomElement($categories);

        return [
            'moisson_id' => Moisson::factory(),
            'categorie' => $categorie,
            'donateur_id' => $categorie === 'entite_physique' ? User::factory() : null,
            'nom_entite' => $categorie === 'entite_morale' ? $this->faker->company : null,
            'cible' => $this->faker->numberBetween(20000, 500000),
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'adresse' => $this->faker->address,
            'description' => $this->faker->paragraph,
            'collecter_par' => User::factory(),
            'creer_par' => User::factory(),
            'collecter_le' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'date_echeance' => $this->faker->dateTimeBetween('now', '+6 months'),
            'status' => $this->faker->boolean(80)
        ];
    }

    public function entiteMorale(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'entite_morale',
            'donateur_id' => null,
            'nom_entite' => $this->faker->company,
        ]);
    }

    public function entitePhysique(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'entite_physique',
            'donateur_id' => User::factory(),
            'nom_entite' => null,
        ]);
    }

    public function enRetard(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_echeance' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'reste' => $this->faker->numberBetween(1000, $attributes['cible'] ?? 50000),
            'status' => true
        ]);
    }

    public function avecRappel(): static
    {
        return $this->state(function (array $attributes) {
            $echeance = Carbon::parse($attributes['date_echeance'] ?? now()->addMonth());

            return [
                'date_rappel' => $echeance->copy()->subDays($this->faker->numberBetween(3, 10)),
            ];
        });
    }

    public function partiellementSolde(): static
    {
        return $this->state(function (array $attributes) {
            $cible = $attributes['cible'] ?? 100000;
            $montantSolde = $this->faker->numberBetween($cible * 0.3, $cible * 0.8);

            return [
                'montant_solde' => $montantSolde,
                'reste' => $cible - $montantSolde,
                'montant_supplementaire' => 0
            ];
        });
    }

    public function completementSolde(): static
    {
        return $this->state(function (array $attributes) {
            $cible = $attributes['cible'] ?? 100000;
            $supplement = $this->faker->boolean(30) ? $this->faker->numberBetween(0, $cible * 0.2) : 0;

            return [
                'montant_solde' => $cible + $supplement,
                'reste' => 0,
                'montant_supplementaire' => $supplement
            ];
        });
    }
}
