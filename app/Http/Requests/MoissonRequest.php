<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoissonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $moissonId = $this->route('moisson')?->id ?? $this->input('id');

        return [
            'theme' => ['required', 'string', 'max:250'],
            'date' => [
                'required',
                'date',
                'after_or_equal:' . now()->subYear()->toDateString(),
                'before_or_equal:' . now()->addYear()->toDateString(),
                Rule::unique('moissons', 'date')
                    ->ignore($moissonId)
                    ->whereNull('deleted_at')
            ],
            'cible' => ['required', 'numeric', 'min:1', 'max:99999999999999.99'],
            'culte_id' => ['required', 'uuid', 'exists:cultes,id'],
            'passages_bibliques' => ['nullable', 'array', 'max:10'],
            'passages_bibliques.*.livre' => ['required_with:passages_bibliques', 'string', 'max:50'],
            'passages_bibliques.*.chapitre' => ['required_with:passages_bibliques', 'integer', 'min:1', 'max:150'],
            'passages_bibliques.*.verset_debut' => ['required_with:passages_bibliques', 'integer', 'min:1'],
            'passages_bibliques.*.verset_fin' => ['nullable', 'integer', 'gte:passages_bibliques.*.verset_debut'],
            'status' => ['sometimes', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'theme.required' => 'Le thème de la moisson est obligatoire.',
            'theme.max' => 'Le thème ne peut pas dépasser 250 caractères.',
            'date.required' => 'La date de la moisson est obligatoire.',
            'date.unique' => 'Une moisson existe déjà pour cette date.',
            'date.after_or_equal' => 'La date ne peut pas être antérieure à l\'année dernière.',
            'date.before_or_equal' => 'La date ne peut pas dépasser l\'année prochaine.',
            'cible.required' => 'L\'objectif financier est obligatoire.',
            'cible.min' => 'L\'objectif doit être supérieur à 0.',
            'cible.max' => 'L\'objectif dépasse la limite autorisée.',
            'culte_id.required' => 'Le culte est obligatoire.',
            'culte_id.exists' => 'Le culte sélectionné n\'existe pas.',
            'passages_bibliques.max' => 'Vous ne pouvez pas ajouter plus de 10 passages bibliques.',
            'passages_bibliques.*.livre.required_with' => 'Le nom du livre biblique est obligatoire.',
            'passages_bibliques.*.chapitre.min' => 'Le numéro de chapitre doit être positif.',
            'passages_bibliques.*.verset_debut.min' => 'Le numéro de verset doit être positif.',
            'passages_bibliques.*.verset_fin.gte' => 'Le verset de fin doit être supérieur ou égal au verset de début.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Parser les passages bibliques depuis le format string vers le format structuré
        if ($this->has('passages_bibliques') && is_array($this->passages_bibliques)) {
            $passages = collect($this->passages_bibliques)
                ->filter(function ($passage) {
                    return !empty($passage);
                })
                ->map(function ($passage) {
                    // Si c'est déjà un tableau structuré, on le garde tel quel
                    if (is_array($passage)) {
                        return $passage;
                    }

                    // Sinon, on parse la chaîne de caractères
                    return $this->parsePassageBiblique($passage);
                })
                ->filter(function ($passage) {
                    // Garder seulement les passages valides
                    return isset($passage['livre']) && isset($passage['chapitre']) && isset($passage['verset_debut']);
                })
                ->map(function ($passage) {
                    // Générer automatiquement la référence
                    $reference = $passage['livre'] . ' ' . $passage['chapitre'] . ':' . $passage['verset_debut'];
                    if (!empty($passage['verset_fin'])) {
                        $reference .= '-' . $passage['verset_fin'];
                    }
                    $passage['reference'] = $reference;
                    return $passage;
                })
                ->values()
                ->toArray();

            $this->merge(['passages_bibliques' => $passages]);
        }

        // S'assurer que la cible est un nombre
        if ($this->has('cible')) {
            $this->merge(['cible' => floatval(str_replace([' ', ','], ['', '.'], $this->cible))]);
        }
    }

    /**
     * Parse une référence biblique au format "Livre chapitre:verset" ou "Livre chapitre:verset-verset"
     *
     * @param string $passage
     * @return array|null
     */
    private function parsePassageBiblique(string $passage): ?array
    {
        // Nettoyer la chaîne
        $passage = trim($passage);

        // Pattern pour matcher: "Livre chapitre:verset" ou "Livre chapitre:verset-verset"
        // Exemples: "Jean 12:10", "Luc 14:2-3", "1 Corinthiens 13:1-13"
        $pattern = '/^(.+?)\s+(\d+):(\d+)(?:-(\d+))?$/';

        if (preg_match($pattern, $passage, $matches)) {
            $result = [
                'livre' => trim($matches[1]),
                'chapitre' => (int)$matches[2],
                'verset_debut' => (int)$matches[3],
            ];

            // Si un verset de fin est spécifié
            if (!empty($matches[4])) {
                $result['verset_fin'] = (int)$matches[4];
            } else {
                $result['verset_fin'] = null;
            }

            return $result;
        }

        return null;
    }
}
