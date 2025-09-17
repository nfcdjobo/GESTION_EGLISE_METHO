@extends('layouts.private.main')
@section('title', 'Ajouter des Participants - ' . $culte->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Ajouter des Participants
                </h1>
                <p class="text-slate-500 mt-1">{{ $culte->titre }} - {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }}</p>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.cultes.index') }}"
                                class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-church mr-2"></i>
                                Cultes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.cultes.show', $culte) }}"
                                    class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $culte->titre }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Participants</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Actions rapides -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('private.cultes.show', $culte) }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour au culte
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du culte -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informations du Culte
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-xl">
                    <p class="text-sm text-blue-600 font-medium">Date et heure</p>
                    <p class="text-lg font-bold text-blue-800">
                        {{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}
                    </p>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl">
                    <p class="text-sm text-green-600 font-medium">Type de culte</p>
                    <p class="text-lg font-bold text-green-800">{{ $culte->type_culte_libelle }}</p>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl">
                    <p class="text-sm text-purple-600 font-medium">Lieu</p>
                    <p class="text-lg font-bold text-purple-800">{{ $culte->lieu }}</p>
                </div>
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 rounded-xl">
                    <p class="text-sm text-amber-600 font-medium">Participants actuels</p>
                    <p class="text-lg font-bold text-amber-800">{{ $participantsActuels ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Méthodes d'ajout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Ajout individuel -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>
                    Ajout Individuel
                </h2>
                <p class="text-sm text-slate-500 mt-1">Ajouter un participant existant ou créer un nouveau profil</p>
            </div>
            <div class="p-6">
                <form id="individualForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="culte_id" value="{{ $culte->id }}">

                    <!-- Recherche de participant existant -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rechercher un participant existant</label>
                        <div class="relative">
                            <input type="text" id="searchParticipant" placeholder="Nom, prénom, email ou téléphone..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                        <div id="searchResults" class="hidden mt-2 bg-white border border-slate-200 rounded-xl shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>

                    <div class="text-center">
                        <span class="text-sm text-slate-500">OU</span>
                    </div>

                    <!-- Création d'un nouveau participant -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Prénom *</label>
                            <input type="text" name="prenom" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nom *</label>
                            <input type="text" name="nom" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Sexe *</label>
                            <select name="sexe" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner...</option>
                                <option value="masculin">Masculin</option>
                                <option value="feminin">Féminin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone *</label>
                            <input type="tel" name="telephone_1" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="email"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Informations de participation -->
                    <div class="pt-4 border-t border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Détails de la participation</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Statut de présence</label>
                                <select name="statut_presence"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="present">Présent</option>
                                    <option value="present_partiel">Présent Partiel</option>
                                    <option value="en_retard">En Retard</option>
                                    <option value="parti_tot">Parti Tôt</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Type de participation</label>
                                <select name="type_participation"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="physique">Physique</option>
                                    <option value="en_ligne">En Ligne</option>
                                    <option value="hybride">Hybride</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Rôle dans le culte</label>
                                <select name="role_culte"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="participant">Participant</option>
                                    <option value="equipe_technique">Équipe Technique</option>
                                    <option value="equipe_louange">Équipe Louange</option>
                                    <option value="equipe_accueil">Équipe Accueil</option>
                                    <option value="orateur">Orateur</option>
                                    <option value="dirigeant">Dirigeant</option>
                                    <option value="diacre_service">Diacre de Service</option>
                                    <option value="collecteur_offrande">Collecteur Offrande</option>
                                    <option value="invite_special">Invité Spécial</option>
                                    <option value="nouveau_visiteur">Nouveau Visiteur</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Accompagné par</label>
                                <select name="accompagne_par"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Aucun accompagnateur</option>
                                    <!-- Options seront remplies dynamiquement -->
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Heure d'arrivée</label>
                                <input type="time" name="heure_arrivee"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Heure de départ</label>
                                <input type="time" name="heure_depart"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <!-- Options de suivi -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Besoins de suivi</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="premiere_visite" value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Première visite</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="demande_contact_pastoral" value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Demande contact pastoral</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="interesse_bapteme" value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Intéressé par le baptême</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="souhaite_devenir_membre" value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Souhaite devenir membre</span>
                                </label>
                            </div>
                        </div>

                        <!-- Commentaires -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Commentaires</label>
                            <textarea name="commentaires_participant" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Commentaires ou notes particulières..."></textarea>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-user-plus mr-2"></i> Ajouter le participant
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ajout en masse -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-purple-600 mr-2"></i>
                    Ajout en Masse
                </h2>
                <p class="text-sm text-slate-500 mt-1">Ajouter plusieurs participants rapidement</p>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Import depuis un fichier -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Import depuis un fichier</h3>
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                            <input type="file" id="fileImport" accept=".csv,.xlsx,.xls" class="hidden">
                            <label for="fileImport" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-4"></i>
                                <p class="text-slate-600 mb-2">Cliquez pour sélectionner un fichier</p>
                                <p class="text-sm text-slate-500">CSV, Excel (.xlsx, .xls)</p>
                            </label>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:underline text-sm">
                                <i class="fas fa-download mr-1"></i> Télécharger le modèle de fichier
                            </a>
                        </div>
                    </div>

                    <!-- Ajout rapide par liste -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Ajout rapide par liste</h3>
                        <form id="bulkForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="culte_id" value="{{ $culte->id }}">

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Liste des participants (un par ligne)
                                </label>
                                <textarea id="bulkParticipants" name="participants_list" rows="8"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                    placeholder="Format: Prénom Nom | Téléphone | Email
Exemple:
Jean Dupont | 0123456789 | jean@email.com
Marie Martin | 0987654321 | marie@email.com"></textarea>
                                <p class="text-sm text-slate-500 mt-1">
                                    Format : Prénom Nom | Téléphone | Email (séparés par |)
                                </p>
                            </div>

                            <!-- Options par défaut pour l'ajout en masse -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut par défaut</label>
                                    <select name="default_statut_presence"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="present">Présent</option>
                                        <option value="present_partiel">Présent Partiel</option>
                                        <option value="en_retard">En Retard</option>
                                        <option value="parti_tot">Parti Tôt</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Type par défaut</label>
                                    <select name="default_type_participation"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="physique">Physique</option>
                                        <option value="en_ligne">En Ligne</option>
                                        <option value="hybride">Hybride</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="default_premiere_visite" value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Marquer comme première visite</span>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                <i class="fas fa-users mr-2"></i> Ajouter en masse
                            </button>
                        </form>
                    </div>

                    <!-- Sélection depuis la liste des membres -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Sélection depuis les membres</h3>
                        <button type="button" onclick="openMembersModal()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                            <i class="fas fa-list mr-2"></i> Sélectionner depuis les membres existants
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des participants actuels -->
    @if(isset($participantsExistants) && count($participantsExistants) > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Participants Déjà Inscrits ({{ count($participantsExistants) }})
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($participantsExistants as $participant)
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $participant->participant->nom }} {{ $participant->participant->prenom }}</p>
                                    <p class="text-sm text-slate-600">{{ $participant->role_culte_libelle }}</p>
                                    <p class="text-xs text-slate-500">{{ $participant->statut_presence_libelle }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('private.participantscultes.show', [$participant->participant_id, $participant->culte_id]) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                       title="Voir détails">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <button type="button" onclick="removeParticipant('{{ $participant->participant_id }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                            title="Retirer">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal de sélection des membres -->
<div id="membersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Sélectionner des membres</h3>
            <button type="button" onclick="closeMembersModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <input type="text" id="searchMembers" placeholder="Rechercher un membre..."
                    class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div id="membersList" class="max-h-96 overflow-y-auto space-y-2">
                <!-- Liste des membres sera chargée dynamiquement -->
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeMembersModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="addSelectedMembers()"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Ajouter les sélectionnés
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedMembers = [];

// Recherche de participants existants
document.getElementById('searchParticipant').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 2) {
        document.getElementById('searchResults').classList.add('hidden');
        return;
    }

    // Simulation de la recherche - à remplacer par un appel API
    setTimeout(() => {
        showSearchResults([
            {id: '1', nom: 'Dupont', prenom: 'Jean', email: 'jean@email.com', telephone: '0123456789'},
            {id: '2', nom: 'Martin', prenom: 'Marie', email: 'marie@email.com', telephone: '0987654321'}
        ]);
    }, 300);
});

function showSearchResults(results) {
    const container = document.getElementById('searchResults');
    if (results.length === 0) {
        container.classList.add('hidden');
        return;
    }

    container.innerHTML = results.map(user => `
        <div class="p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-b-0"
             onclick="selectExistingUser('${user.id}', '${user.prenom}', '${user.nom}', '${user.email}', '${user.telephone}')">
            <p class="font-medium text-slate-900">${user.prenom} ${user.nom}</p>
            <p class="text-sm text-slate-600">${user.email} | ${user.telephone}</p>
        </div>
    `).join('');

    container.classList.remove('hidden');
}

function selectExistingUser(id, prenom, nom, email, telephone) {
    // Remplir le formulaire avec les données de l'membres sélectionné
    document.querySelector('input[name="participant_id"]')?.remove();
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'participant_id';
    hiddenInput.value = id;
    document.getElementById('individualForm').appendChild(hiddenInput);

    // Vider les champs de création
    document.querySelector('input[name="prenom"]').value = '';
    document.querySelector('input[name="nom"]').value = '';
    document.querySelector('input[name="telephone_1"]').value = '';
    document.querySelector('input[name="email"]').value = '';
    document.querySelector('select[name="sexe"]').value = '';

    // Afficher le nom sélectionné
    document.getElementById('searchParticipant').value = `${prenom} ${nom}`;
    document.getElementById('searchResults').classList.add('hidden');
}

// Soumission du formulaire individuel
document.getElementById('individualForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("private.participantscultes.store-with-user-creation") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Participant ajouté avec succès !');
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Soumission du formulaire en masse
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("private.participantscultes.bulk-with-user-creation") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${data.data.participations_creees} participants ajoutés avec succès !`);
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Gestion du modal des membres
function openMembersModal() {
    document.getElementById('membersModal').classList.remove('hidden');
    loadMembers();
}

function closeMembersModal() {
    document.getElementById('membersModal').classList.add('hidden');
    selectedMembers = [];
}

function loadMembers() {
    // Simulation du chargement des membres - à remplacer par un appel API
    const members = [
        {id: '1', nom: 'Dupont', prenom: 'Jean', email: 'jean@email.com'},
        {id: '2', nom: 'Martin', prenom: 'Marie', email: 'marie@email.com'},
        {id: '3', nom: 'Bernard', prenom: 'Paul', email: 'paul@email.com'}
    ];

    const container = document.getElementById('membersList');
    container.innerHTML = members.map(member => `
        <label class="flex items-center p-3 hover:bg-slate-50 rounded-lg cursor-pointer">
            <input type="checkbox" value="${member.id}"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-3">
            <div class="flex-1">
                <p class="font-medium text-slate-900">${member.prenom} ${member.nom}</p>
                <p class="text-sm text-slate-600">${member.email}</p>
            </div>
        </label>
    `).join('');
}

function addSelectedMembers() {
    const checkboxes = document.querySelectorAll('#membersList input[type="checkbox"]:checked');
    if (checkboxes.length === 0) {
        alert('Veuillez sélectionner au moins un membre');
        return;
    }

    const memberIds = Array.from(checkboxes).map(cb => cb.value);

    // Logique d'ajout des membres sélectionnés
    console.log('Ajout des membres:', memberIds);
    alert(`${memberIds.length} membre(s) sélectionné(s) - Fonctionnalité à implémenter`);
    closeMembersModal();
}

function removeParticipant(participantId) {
    if (confirm('Retirer ce participant du culte ?')) {
        fetch(`{{route('private.participantscultes.destroy', [':participant', ':culte'])}}`.replace(':participant', participantId).replace(':culte', culteId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('membersModal').addEventListener('click', function(e) {
    if (e.target === this) closeMembersModal();
});

// Gestion de l'import de fichier
document.getElementById('fileImport').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Logique d'import de fichier
        console.log('Fichier sélectionné:', file.name);
        alert('Import de fichier - Fonctionnalité à implémenter');
    }
});
</script>
@endpush
@endsection
