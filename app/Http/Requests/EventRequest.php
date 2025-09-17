<?php

namespace App\Http\Requests;


use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class EventRequest extends FormRequest
{
    /**
     * Détermine si l'membres est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Règles de validation qui s'appliquent à la requête.
     */
    public function rules(): array
    {
        $eventId = $this->route('event') ? $this->route('event')->id : null;

        return [
            // Informations de base
            'titre' => ['required', 'string', 'max:200'],
            'sous_titre' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'resume_court' => ['nullable', 'string', 'max:500'],
            'slug' => [
                'nullable',
                'string',
                'max:250',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('events', 'slug')->ignore($eventId)
            ],

            // Type et catégorie
            'type_evenement' => [
                'required',
                Rule::in([
                    'conference', 'seminaire', 'atelier', 'camps', 'formation',
                    'celebration', 'festival', 'concert', 'spectacle', 'exposition',
                    'competition', 'ceremonie', 'rencontre', 'sortie', 'pelerinage',
                    'retraite', 'jeune_priere', 'evangelisation', 'social', 'caritatif',
                    'culturel', 'sportif', 'anniversaire', 'inauguration', 'autre'
                ])
            ],
            'categorie' => [
                'required',
                Rule::in([
                    'spirituel', 'educatif', 'social', 'culturel', 'sportif',
                    'caritatif', 'administratif', 'technique', 'formation', 'divertissement'
                ])
            ],

            // Planification temporelle
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            // 'heure_debut' => ['required', 'date_format:H:i'],
            // 'heure_fin' => ['nullable', 'date_format:H:i', 'after:heure_debut'],
            'heure_debut' => ['required', 'date_format:H:i'],
        'heure_fin' => ['nullable', 'date_format:H:i'],

        'datetime_debut' => ['required', 'date', 'after_or_equal:now'],
        'datetime_fin' => ['nullable', 'date', 'after:datetime_debut'],

            'evenement_multi_jours' => ['nullable', 'boolean'],
            'horaires_detailles' => ['nullable', 'json'],
            'fuseau_horaire' => ['nullable', 'string', 'max:50'],

            // Lieu et logistique
            'lieu_nom' => ['required', 'string', 'max:200'],
            'lieu_adresse' => ['nullable', 'string'],
            'lieu_ville' => ['nullable', 'string', 'max:100'],
            'lieu_pays' => ['nullable', 'string', 'max:100'],
            'instructions_acces' => ['nullable', 'string'],
            'transport_organise' => ['nullable', 'string'],

            // Capacité et participation
            'capacite_totale' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'places_reservees' => ['nullable', 'integer', 'min:0'],
            'places_disponibles' => ['nullable', 'integer', 'min:0'],
            'nombre_participants' => ['nullable', 'integer', 'min:0'],
            'liste_attente' => ['nullable', 'boolean'],

            // Audience et ciblage
            'audience_cible' => [
                'nullable',
                Rule::in([
                    'tous', 'membres', 'jeunes', 'adultes', 'enfants', 'familles',
                    'femmes', 'hommes', 'couples', 'celibataires', 'nouveaux_membres',
                    'leadership', 'invite_seulement', 'public_externe'
                ])
            ],
            'age_minimum' => ['nullable', 'integer', 'min:0', 'max:120'],
            'age_maximum' => ['nullable', 'integer', 'min:0', 'max:120', 'gte:age_minimum'],
            'ouvert_public' => ['nullable', 'boolean'],
            'necessite_invitation' => ['nullable', 'boolean'],

            // Inscription et tarification
            'inscription_requise' => ['nullable', 'boolean'],
            'date_ouverture_inscription' => [
                'nullable',
                'date',
                'required_if:inscription_requise,true',
                'before_or_equal:date_debut'
            ],
            'date_fermeture_inscription' => [
                'nullable',
                'date',
                'after_or_equal:date_ouverture_inscription',
                'before_or_equal:date_debut'
            ],
            'inscription_payante' => ['nullable', 'boolean'],
            'prix_inscription' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99',
                'required_if:inscription_payante,true'
            ],
            'tarifs_categories' => ['nullable', 'json'],
            'conditions_inscription' => ['nullable', 'string'],

            // Responsables et organisation
            'organisateur_principal_id' => ['nullable', 'uuid', 'exists:users,id'],
            'coordinateur_id' => ['nullable', 'uuid', 'exists:users,id'],
            'responsable_logistique_id' => ['nullable', 'uuid', 'exists:users,id'],
            'responsable_communication_id' => ['nullable', 'uuid', 'exists:users,id'],
            'equipe_organisation' => ['nullable', 'json'],
            'partenaires' => ['nullable', 'json'],
            'sponsors' => ['nullable', 'json'],

            // Programme et contenu
            'programme_detaille' => ['nullable', 'json'],
            'intervenants' => ['nullable', 'json'],
            'objectifs' => ['nullable', 'string'],
            'programme_enfants' => ['nullable', 'string'],
            'activites_annexes' => ['nullable', 'json'],

            // Statut et workflow
            'statut' => [
                'nullable',
                Rule::in([
                    'brouillon', 'planifie', 'en_promotion', 'ouvert_inscription',
                    'complet', 'en_cours', 'termine', 'annule', 'reporte', 'archive'
                ])
            ],
            'priorite' => [
                'nullable',
                Rule::in(['faible', 'normale', 'haute', 'urgente'])
            ],

            // Communication et promotion
            'message_promotion' => ['nullable', 'string'],
            'hashtag_officiel' => ['nullable', 'string', 'max:100', 'regex:/^#[a-zA-Z0-9_]+$/'],
            'canaux_communication' => ['nullable', 'json'],
            'publication_site_web' => ['nullable', 'boolean'],
            'publication_reseaux_sociaux' => ['nullable', 'boolean'],
            'envoi_newsletter' => ['nullable', 'boolean'],

            // Médias et ressources
            'image_principale' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'galerie_images' => ['nullable', 'json'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'video_presentation' => ['nullable', 'url'],
            'documents_joints' => ['nullable', 'json'],
            'site_web_evenement' => ['nullable', 'url'],

            // Diffusion et enregistrement
            'diffusion_en_ligne' => ['nullable', 'boolean'],
            'lien_diffusion' => [
                'nullable',
                'url',
                'required_if:diffusion_en_ligne,true'
            ],
            'enregistrement_autorise' => ['nullable', 'boolean'],
            'lien_enregistrement' => ['nullable', 'url'],
            'photos_autorisees' => ['nullable', 'boolean'],

            // Budget et finances
            'budget_prevu' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'cout_realise' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'recettes_inscriptions' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'recettes_sponsors' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'detail_budget' => ['nullable', 'json'],
            'responsable_finances' => ['nullable', 'string', 'max:255'],

            // Évaluation et feedback
            'note_globale' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_organisation' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_contenu' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'note_lieu' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'taux_satisfaction' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'feedback_participants' => ['nullable', 'string'],
            'points_positifs' => ['nullable', 'string'],
            'points_amelioration' => ['nullable', 'string'],

            // Récurrence et série
            'evenement_recurrent' => ['nullable', 'boolean'],
            'frequence_recurrence' => [
                'nullable',
                Rule::in(['hebdomadaire', 'mensuelle', 'trimestrielle', 'semestrielle', 'annuelle']),
                'required_if:evenement_recurrent,true'
            ],
            'prochaine_occurrence' => [
                'nullable',
                'date',
                'after:date_debut',
                'required_if:evenement_recurrent,true'
            ],
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'événement est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 200 caractères.',
            'sous_titre.max' => 'Le sous-titre ne peut pas dépasser 200 caractères.',
            'resume_court.max' => 'Le résumé court ne peut pas dépasser 500 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé par un autre événement.',
            'slug.regex' => 'Le slug doit contenir uniquement des lettres minuscules, des chiffres et des tirets.',

            'type_evenement.required' => 'Le type d\'événement est obligatoire.',
            'type_evenement.in' => 'Le type d\'événement sélectionné n\'est pas valide.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',

            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',

            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',

            'lieu_nom.required' => 'Le nom du lieu est obligatoire.',
            'lieu_nom.max' => 'Le nom du lieu ne peut pas dépasser 200 caractères.',
            'lieu_ville.max' => 'Le nom de la ville ne peut pas dépasser 100 caractères.',
            'lieu_pays.max' => 'Le nom du pays ne peut pas dépasser 100 caractères.',

            'capacite_totale.integer' => 'La capacité totale doit être un nombre entier.',
            'capacite_totale.min' => 'La capacité totale doit être au moins de 1.',
            'capacite_totale.max' => 'La capacité totale ne peut pas dépasser 100 000.',
            'places_reservees.integer' => 'Le nombre de places réservées doit être un nombre entier.',
            'places_reservees.min' => 'Le nombre de places réservées ne peut pas être négatif.',
            'places_disponibles.integer' => 'Le nombre de places disponibles doit être un nombre entier.',
            'places_disponibles.min' => 'Le nombre de places disponibles ne peut pas être négatif.',

            'age_minimum.integer' => 'L\'âge minimum doit être un nombre entier.',
            'age_minimum.min' => 'L\'âge minimum ne peut pas être négatif.',
            'age_minimum.max' => 'L\'âge minimum ne peut pas dépasser 120 ans.',
            'age_maximum.integer' => 'L\'âge maximum doit être un nombre entier.',
            'age_maximum.min' => 'L\'âge maximum ne peut pas être négatif.',
            'age_maximum.max' => 'L\'âge maximum ne peut pas dépasser 120 ans.',
            'age_maximum.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',

            'date_ouverture_inscription.required_if' => 'La date d\'ouverture des inscriptions est obligatoire si l\'inscription est requise.',
            'date_ouverture_inscription.before_or_equal' => 'La date d\'ouverture des inscriptions doit être avant ou le jour de l\'événement.',
            'date_fermeture_inscription.after_or_equal' => 'La date de fermeture des inscriptions doit être après l\'ouverture des inscriptions.',
            'date_fermeture_inscription.before_or_equal' => 'La date de fermeture des inscriptions doit être avant ou le jour de l\'événement.',

            'prix_inscription.required_if' => 'Le prix d\'inscription est obligatoire si l\'inscription est payante.',
            'prix_inscription.numeric' => 'Le prix d\'inscription doit être un nombre.',
            'prix_inscription.min' => 'Le prix d\'inscription ne peut pas être négatif.',
            'prix_inscription.max' => 'Le prix d\'inscription ne peut pas dépasser 999 999,99.',

            'organisateur_principal_id.exists' => 'L\'organisateur principal sélectionné n\'existe pas.',
            'coordinateur_id.exists' => 'Le coordinateur sélectionné n\'existe pas.',
            'responsable_logistique_id.exists' => 'Le responsable logistique sélectionné n\'existe pas.',
            'responsable_communication_id.exists' => 'Le responsable communication sélectionné n\'existe pas.',

            'hashtag_officiel.regex' => 'Le hashtag doit commencer par # et contenir uniquement des lettres, chiffres et underscores.',

            'image_principale.image' => 'L\'image principale doit être un fichier image.',
            'image_principale.mimes' => 'L\'image principale doit être au format JPEG, JPG, PNG ou WebP.',
            'image_principale.max' => 'L\'image principale ne peut pas dépasser 2 Mo.',
            'images.array' => 'Les images doivent être envoyées sous forme de tableau.',
            'images.max' => 'Vous ne pouvez pas envoyer plus de 10 images.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Chaque image doit être au format JPEG, JPG, PNG ou WebP.',
            'images.*.max' => 'Chaque image ne peut pas dépasser 2 Mo.',

            'video_presentation.url' => 'Le lien de la vidéo de présentation doit être une URL valide.',
            'site_web_evenement.url' => 'Le lien du site web de l\'événement doit être une URL valide.',
            'lien_diffusion.required_if' => 'Le lien de diffusion est obligatoire si la diffusion en ligne est activée.',
            'lien_diffusion.url' => 'Le lien de diffusion doit être une URL valide.',
            'lien_enregistrement.url' => 'Le lien d\'enregistrement doit être une URL valide.',

            'budget_prevu.numeric' => 'Le budget prévu doit être un nombre.',
            'budget_prevu.min' => 'Le budget prévu ne peut pas être négatif.',
            'cout_realise.numeric' => 'Le coût réalisé doit être un nombre.',
            'cout_realise.min' => 'Le coût réalisé ne peut pas être négatif.',
            'recettes_inscriptions.numeric' => 'Les recettes des inscriptions doivent être un nombre.',
            'recettes_inscriptions.min' => 'Les recettes des inscriptions ne peuvent pas être négatives.',
            'recettes_sponsors.numeric' => 'Les recettes des sponsors doivent être un nombre.',
            'recettes_sponsors.min' => 'Les recettes des sponsors ne peuvent pas être négatives.',

            'note_globale.numeric' => 'La note globale doit être un nombre.',
            'note_globale.min' => 'La note globale doit être au minimum 1.',
            'note_globale.max' => 'La note globale doit être au maximum 10.',
            'note_organisation.numeric' => 'La note d\'organisation doit être un nombre.',
            'note_organisation.min' => 'La note d\'organisation doit être au minimum 1.',
            'note_organisation.max' => 'La note d\'organisation doit être au maximum 10.',
            'note_contenu.numeric' => 'La note du contenu doit être un nombre.',
            'note_contenu.min' => 'La note du contenu doit être au minimum 1.',
            'note_contenu.max' => 'La note du contenu doit être au maximum 10.',
            'note_lieu.numeric' => 'La note du lieu doit être un nombre.',
            'note_lieu.min' => 'La note du lieu doit être au minimum 1.',
            'note_lieu.max' => 'La note du lieu doit être au maximum 10.',
            'taux_satisfaction.numeric' => 'Le taux de satisfaction doit être un nombre.',
            'taux_satisfaction.min' => 'Le taux de satisfaction doit être au minimum 0%.',
            'taux_satisfaction.max' => 'Le taux de satisfaction doit être au maximum 100%.',

            'frequence_recurrence.required_if' => 'La fréquence de récurrence est obligatoire si l\'événement est récurrent.',
            'frequence_recurrence.in' => 'La fréquence de récurrence sélectionnée n\'est pas valide.',
            'prochaine_occurrence.required_if' => 'La prochaine occurrence est obligatoire si l\'événement est récurrent.',
            'prochaine_occurrence.after' => 'La prochaine occurrence doit être après la date de l\'événement.',
        ];
    }






    /**
     * Configuration des attributs pour les messages d'erreur
     */
    public function attributes(): array
    {
        return [
            'titre' => 'titre',
            'sous_titre' => 'sous-titre',
            'resume_court' => 'résumé court',
            'type_evenement' => 'type d\'événement',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'heure_debut' => 'heure de début',
            'heure_fin' => 'heure de fin',
            'lieu_nom' => 'nom du lieu',
            'lieu_ville' => 'ville',
            'lieu_pays' => 'pays',
            'capacite_totale' => 'capacité totale',
            'places_reservees' => 'places réservées',
            'places_disponibles' => 'places disponibles',
            'age_minimum' => 'âge minimum',
            'age_maximum' => 'âge maximum',
            'organisateur_principal_id' => 'organisateur principal',
            'coordinateur_id' => 'coordinateur',
            'responsable_logistique_id' => 'responsable logistique',
            'responsable_communication_id' => 'responsable communication',
            'prix_inscription' => 'prix d\'inscription',
            'budget_prevu' => 'budget prévu',
            'cout_realise' => 'coût réalisé',
            'note_globale' => 'note globale',
            'taux_satisfaction' => 'taux de satisfaction',
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Générer automatiquement le slug si pas fourni
        if (!$this->has('slug') && $this->has('titre')) {
            $this->merge([
                'slug' => Str::slug($this->input('titre'))
            ]);
        }

        if ($this->date_debut && $this->heure_debut) {
            $this->merge([
                'datetime_debut' => $this->date_debut.' '.$this->heure_debut,
            ]);
        }

        if ($this->date_fin && $this->heure_fin) {
            $this->merge([
                'datetime_fin' => $this->date_fin.' '.$this->heure_fin,
            ]);
        }

        // Convertir les booléens
        $this->merge([
            'evenement_multi_jours' => $this->boolean('evenement_multi_jours'),
            'ouvert_public' => $this->boolean('ouvert_public'),
            'necessite_invitation' => $this->boolean('necessite_invitation'),
            'inscription_requise' => $this->boolean('inscription_requise'),
            'inscription_payante' => $this->boolean('inscription_payante'),
            'liste_attente' => $this->boolean('liste_attente'),
            'publication_site_web' => $this->boolean('publication_site_web'),
            'publication_reseaux_sociaux' => $this->boolean('publication_reseaux_sociaux'),
            'envoi_newsletter' => $this->boolean('envoi_newsletter'),
            'diffusion_en_ligne' => $this->boolean('diffusion_en_ligne'),
            'enregistrement_autorise' => $this->boolean('enregistrement_autorise'),
            'photos_autorisees' => $this->boolean('photos_autorisees'),
            'evenement_recurrent' => $this->boolean('evenement_recurrent'),
        ]);

        // Assigner l'membres authentifié comme organisateur principal si pas fourni
        if (!$this->has('organisateur_principal_id') && auth()->check()) {
            $this->merge([
                'organisateur_principal_id' => auth()->id()
            ]);
        }
    }

    /**
     * Validation après les règles de base
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Vérifier que les places disponibles ne dépassent pas la capacité totale
            if ($this->filled(['capacite_totale', 'places_disponibles'])) {
                if ($this->input('places_disponibles') > $this->input('capacite_totale')) {
                    $validator->errors()->add(
                        'places_disponibles',
                        'Le nombre de places disponibles ne peut pas dépasser la capacité totale.'
                    );
                }
            }

            // Vérifier que les places réservées ne dépassent pas la capacité totale
            if ($this->filled(['capacite_totale', 'places_reservees'])) {
                if ($this->input('places_reservees') > $this->input('capacite_totale')) {
                    $validator->errors()->add(
                        'places_reservees',
                        'Le nombre de places réservées ne peut pas dépasser la capacité totale.'
                    );
                }
            }

            // Vérifier la cohérence des dates pour événement multi-jours
            if ($this->boolean('evenement_multi_jours') && !$this->filled('date_fin')) {
                $validator->errors()->add(
                    'date_fin',
                    'La date de fin est obligatoire pour un événement multi-jours.'
                );
            }

            // Vérifier la cohérence inscription payante/prix
            if ($this->boolean('inscription_payante') && !$this->filled('prix_inscription')) {
                $validator->errors()->add(
                    'prix_inscription',
                    'Le prix d\'inscription est obligatoire si l\'inscription est payante.'
                );
            }
        });
    }
}
