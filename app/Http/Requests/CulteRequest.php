<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CulteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vous pouvez personnaliser cette logique selon vos besoins d'autorisation
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Champs obligatoires de base
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'titre' => ['required', 'string', 'max:200'],
            'date_culte' => ['required', 'date', 'after_or_equal:1900-01-01'],
            'heure_debut' => ['required', 'date_format:H:i'],

            // Champs optionnels de base
            'description' => ['nullable', 'string'],
            'heure_fin' => ['nullable', 'date_format:H:i', 'after:heure_debut'],
            'heure_debut_reelle' => ['nullable', 'date_format:H:i'],
            'heure_fin_reelle' => ['nullable', 'date_format:H:i', 'after:heure_debut_reelle'],

            // Type et catégorie (énumérations)
            'type_culte' => [
                'required',
                Rule::in([
                    'dimanche_matin', 'dimanche_soir', 'mercredi', 'vendredi',
                    'samedi_jeunes', 'special', 'conference', 'seminaire',
                    'retraite', 'mariage', 'funerailles', 'bapteme',
                    'communion', 'noel', 'paques', 'nouvel_an'
                ])
            ],
            'categorie' => [
                'required',
                Rule::in(['regulier', 'special', 'ceremonial', 'formation', 'evangelisation'])
            ],

            // Lieu et logistique
            'lieu' => ['nullable', 'string', 'max:200'],
            'adresse_lieu' => ['nullable', 'string'],
            'capacite_prevue' => ['nullable', 'integer', 'min:1', 'max:100000'],

            // Responsables et intervenants (UUIDs optionnels)
            'pasteur_principal_id' => ['nullable', 'uuid', 'exists:users,id'],
            'predicateur_id' => ['nullable', 'uuid', 'exists:users,id'],
            'responsable_culte_id' => ['nullable', 'uuid', 'exists:users,id'],
            'dirigeant_louange_id' => ['nullable', 'uuid', 'exists:users,id'],
            'responsable_finances_id' => ['nullable', 'uuid', 'exists:users,id'],

            // Équipe du culte (JSON)
            'equipe_culte' => ['nullable', 'array'],
            'equipe_culte.*.role' => ['required_with:equipe_culte', 'string', 'max:100'],
            'equipe_culte.*.user_id' => ['required_with:equipe_culte', 'uuid', 'exists:users,id'],
            'equipe_culte.*.nom' => ['required_with:equipe_culte', 'string', 'max:200'],

            // Message et prédication
            'titre_message' => ['nullable', 'string', 'max:300'],
            'resume_message' => ['nullable', 'string'],
            'passage_biblique' => ['nullable', 'string', 'max:500'],
            'plan_message' => ['nullable', 'string'],

            // Versets clés (JSON array)
            'versets_cles' => ['nullable', 'array'],
            'versets_cles.*' => ['string', 'max:500'],

            // Programme et ordre de service
            'ordre_service' => ['nullable', 'array'],
            'cantiques_chantes' => ['nullable', 'array'],
            'cantiques_chantes.*.titre' => ['required_with:cantiques_chantes', 'string', 'max:200'],
            'cantiques_chantes.*.numero' => ['nullable', 'string', 'max:10'],
            'cantiques_chantes.*.auteur' => ['nullable', 'string', 'max:100'],

            // Durées
            'duree_louange' => ['nullable', 'date_format:H:i'],
            'duree_message' => ['nullable', 'date_format:H:i'],
            'duree_priere' => ['nullable', 'date_format:H:i'],

            // Statistiques et données
            'nombre_participants' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'nombre_adultes' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'nombre_enfants' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'nombre_jeunes' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'nombre_nouveaux' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'nombre_conversions' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'nombre_baptemes' => ['nullable', 'integer', 'min:0', 'max:1000'],

            // Offrandes et finances
            'detail_offrandes' => ['nullable', 'array'],
            'detail_offrandes.offrandes_ordinnaires' => ['required_with:detail_offrandes', 'array'],
            'detail_offrandes.offrandes_speciales' => ['required_with:detail_offrandes', 'array'],
            'detail_offrandes.offrandes_speciales.*.titre' => ['required', 'string', 'max:200'],
            'detail_offrandes.offrandes_speciales.*.montant' => ['required', 'numeric', 'min:0'],

            'offrande_totale' => ['nullable', 'numeric', 'min:0', 'max:999999999999999.99'],
            'dime_totale' => ['nullable', 'numeric', 'min:0', 'max:999999999999999.99'],

            // Médias et enregistrements
            'est_enregistre' => ['nullable', 'boolean'],
            'lien_enregistrement_audio' => ['nullable', 'url', 'max:2048'],
            'lien_enregistrement_video' => ['nullable', 'url', 'max:2048'],
            'lien_diffusion_live' => ['nullable', 'url', 'max:2048'],
            'diffusion_en_ligne' => ['nullable', 'boolean'],

            // Photos (JSON array d'URLs)
            'photos_culte' => ['nullable', 'array'],
            'photos_culte.*' => ['url', 'max:2048'],

            // État et statut
            'statut' => [
                'nullable',
                Rule::in(['planifie', 'en_preparation', 'en_cours', 'termine', 'annule', 'reporte'])
            ],
            'est_public' => ['nullable', 'boolean'],
            'necessite_invitation' => ['nullable', 'boolean'],

            // Météo et contexte
            'meteo' => ['nullable', 'string', 'max:100'],
            'atmosphere' => [
                'nullable',
                Rule::in(['excellent', 'tres_bon', 'bon', 'moyen', 'difficile'])
            ],

            // Notes et commentaires
            'notes_pasteur' => ['nullable', 'string'],
            'notes_organisateur' => ['nullable', 'string'],
            'temoignages' => ['nullable', 'string'],
            'points_forts' => ['nullable', 'string'],
            'points_amelioration' => ['nullable', 'string'],
            'demandes_priere' => ['nullable', 'string'],

            // Suivi et évaluation
            'note_globale' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_louange' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_message' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_organisation' => ['nullable', 'numeric', 'min:1', 'max:10'],
        ];

        // Règles spécifiques selon le contexte (création vs modification)
        if ($this->isMethod('POST')) {
            // Règles pour la création
            $rules = array_merge($rules, $this->getCreationRules());
        } else {
            // Règles pour la modification
            $rules = array_merge($rules, $this->getUpdateRules());
        }

        return $rules;
    }

    /**
     * Règles spécifiques à la création
     */
    protected function getCreationRules(): array
    {
        return [
            'statut' => ['nullable', 'in:planifie,en_preparation'], // Statuts autorisés à la création
        ];
    }

    /**
     * Règles spécifiques à la modification
     */
    protected function getUpdateRules(): array
    {
        return [
            // Lors de la modification, on peut changer vers tous les statuts
            'statut' => [
                'nullable',
                Rule::in(['planifie', 'en_preparation', 'en_cours', 'termine', 'annule', 'reporte'])
            ],
        ];
    }

    /**
     * Règles de validation personnalisées
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validation que est_public et necessite_invitation ne sont pas tous les deux true
            if ($this->est_public && $this->necessite_invitation) {
                $validator->errors()->add('necessite_invitation',
                    'Un culte ne peut pas être public ET nécessiter une invitation.');
            }

            // Validation de la cohérence des participants
            if ($this->isParticipantsDataInconsistent()) {
                $validator->errors()->add('nombre_participants',
                    'Le nombre total de participants doit être supérieur ou égal à la somme des adultes, enfants et jeunes.');
            }

            // Validation du statut "termine" avec données obligatoires
            if ($this->statut === 'termine') {
                if (!$this->heure_debut_reelle) {
                    $validator->errors()->add('heure_debut_reelle',
                        'L\'heure de début réelle est obligatoire pour un culte terminé.');
                }
                if (!$this->nombre_participants) {
                    $validator->errors()->add('nombre_participants',
                        'Le nombre de participants est obligatoire pour un culte terminé.');
                }
            }

            // Validation des URLs spécifiques
            $this->validateUrls($validator);

            // Validation des offrandes spéciales dans detail_offrandes
            $this->validateDetailOffrandes($validator);
        });
    }

    /**
     * Vérifier l'incohérence des données de participants
     */
    protected function isParticipantsDataInconsistent(): bool
    {
        if (!$this->nombre_participants) {
            return false;
        }

        $somme = ($this->nombre_adultes ?? 0) +
                ($this->nombre_enfants ?? 0) +
                ($this->nombre_jeunes ?? 0);

        return $this->nombre_participants < $somme;
    }

    /**
     * Valider les URLs
     */
    protected function validateUrls($validator)
    {
        $urlFields = [
            'lien_enregistrement_audio',
            'lien_enregistrement_video',
            'lien_diffusion_live'
        ];

        foreach ($urlFields as $field) {
            if ($this->$field && !filter_var($this->$field, FILTER_VALIDATE_URL)) {
                $validator->errors()->add($field, "Le champ $field doit être une URL valide.");
            }
        }
    }

    /**
     * Valider la structure detail_offrandes
     */
    protected function validateDetailOffrandes($validator)
    {
        if (!$this->detail_offrandes) {
            return;
        }

        // Vérifier que offrandes_ordinnaires contient des UUIDs valides comme clés
        $offrandes_ord = $this->detail_offrandes['offrandes_ordinnaires'] ?? [];
        foreach ($offrandes_ord as $classe_uuid => $montant) {
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $classe_uuid)) {
                $validator->errors()->add('detail_offrandes.offrandes_ordinnaires',
                    'Les clés des offrandes ordinaires doivent être des UUIDs valides.');
                break;
            }
            if (!is_numeric($montant) || $montant < 0) {
                $validator->errors()->add('detail_offrandes.offrandes_ordinnaires',
                    'Les montants des offrandes ordinaires doivent être des nombres positifs.');
                break;
            }
        }
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'programme_id.required' => 'Le programme est obligatoire.',
            'programme_id.exists' => 'Le programme sélectionné n\'existe pas.',
            'titre.required' => 'Le titre du culte est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 200 caractères.',
            'date_culte.required' => 'La date du culte est obligatoire.',
            'date_culte.date' => 'La date du culte doit être une date valide.',
            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM:SS.',
            'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'type_culte.required' => 'Le type de culte est obligatoire.',
            'type_culte.in' => 'Le type de culte sélectionné n\'est pas valide.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'capacite_prevue.integer' => 'La capacité prévue doit être un nombre entier.',
            'capacite_prevue.min' => 'La capacité prévue doit être d\'au moins 1.',
            'nombre_participants.integer' => 'Le nombre de participants doit être un nombre entier.',
            'nombre_participants.min' => 'Le nombre de participants ne peut pas être négatif.',
            'offrande_totale.numeric' => 'Le total des offrandes doit être un nombre.',
            'offrande_totale.min' => 'Le total des offrandes ne peut pas être négatif.',
            'note_globale.min' => 'La note globale doit être entre 1 et 10.',
            'note_globale.max' => 'La note globale doit être entre 1 et 10.',
            'lien_enregistrement_audio.url' => 'Le lien d\'enregistrement audio doit être une URL valide.',
            'lien_enregistrement_video.url' => 'Le lien d\'enregistrement vidéo doit être une URL valide.',
            'lien_diffusion_live.url' => 'Le lien de diffusion en direct doit être une URL valide.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'atmosphere.in' => 'L\'atmosphère sélectionnée n\'est pas valide.',
        ];
    }

    /**
     * Noms d'attributs personnalisés
     */
    public function attributes(): array
    {
        return [
            'programme_id' => 'programme',
            'titre' => 'titre du culte',
            'date_culte' => 'date du culte',
            'heure_debut' => 'heure de début',
            'heure_fin' => 'heure de fin',
            'type_culte' => 'type de culte',
            'categorie' => 'catégorie',
            'pasteur_principal_id' => 'pasteur principal',
            'predicateur_id' => 'prédicateur',
            'responsable_culte_id' => 'responsable du culte',
            'dirigeant_louange_id' => 'dirigeant de louange',
            'nombre_participants' => 'nombre de participants',
            'offrande_totale' => 'total des offrandes',
            'note_globale' => 'note globale',
            'est_enregistre' => 'enregistrement',
            'diffusion_en_ligne' => 'diffusion en ligne',
        ];
    }
}
