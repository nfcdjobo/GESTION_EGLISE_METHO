@extends('layouts.private.main')
@section('title', 'Modifier la Participation')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Modifier la Participation
                </h1>
                <p class="text-slate-500 mt-1">{{ $participation->participant->nom }} {{ $participation->participant->prenom }} - {{ $participation->culte->titre }}</p>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.participantscultes.index') }}"
                                class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-users mr-2"></i>
                                Participations
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.participantscultes.show', [$participation->participant_id, $participation->culte_id]) }}"
                                    class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $participation->participant->nom }} {{ $participation->participant->prenom }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Modifier</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Actions rapides -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('private.participantscultes.show', [$participation->participant_id, $participation->culte_id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Détails de la Participation
                    </h2>
                </div>
                <div class="p-6">
                    <form id="editForm" method="POST" action="{{ route('private.participantscultes.update', [$participation->participant_id, $participation->culte_id]) }}">
                        @csrf
                        @method('PUT')

                        <!-- Informations de base -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut de présence</label>
                                    <select name="statut_presence" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="present" {{ $participation->statut_presence == 'present' ? 'selected' : '' }}>Présent</option>
                                        <option value="present_partiel" {{ $participation->statut_presence == 'present_partiel' ? 'selected' : '' }}>Présent Partiel</option>
                                        <option value="en_retard" {{ $participation->statut_presence == 'en_retard' ? 'selected' : '' }}>En Retard</option>
                                        <option value="parti_tot" {{ $participation->statut_presence == 'parti_tot' ? 'selected' : '' }}>Parti Tôt</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de participation</label>
                                    <select name="type_participation" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="physique" {{ $participation->type_participation == 'physique' ? 'selected' : '' }}>Physique</option>
                                        <option value="en_ligne" {{ $participation->type_participation == 'en_ligne' ? 'selected' : '' }}>En Ligne</option>
                                        <option value="hybride" {{ $participation->type_participation == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Heure d'arrivée</label>
                                    <input type="time" name="heure_arrivee"
                                        value="{{ $participation->heure_arrivee ? \Carbon\Carbon::parse($participation->heure_arrivee)->format('H:i') : '' }}"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Heure de départ</label>
                                    <input type="time" name="heure_depart"
                                        value="{{ $participation->heure_depart ? \Carbon\Carbon::parse($participation->heure_depart)->format('H:i') : '' }}"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Rôle dans le culte</label>
                                <select name="role_culte" required
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="participant" {{ $participation->role_culte == 'participant' ? 'selected' : '' }}>Participant</option>
                                    <option value="equipe_technique" {{ $participation->role_culte == 'equipe_technique' ? 'selected' : '' }}>Équipe Technique</option>
                                    <option value="equipe_louange" {{ $participation->role_culte == 'equipe_louange' ? 'selected' : '' }}>Équipe Louange</option>
                                    <option value="equipe_accueil" {{ $participation->role_culte == 'equipe_accueil' ? 'selected' : '' }}>Équipe Accueil</option>
                                    <option value="orateur" {{ $participation->role_culte == 'orateur' ? 'selected' : '' }}>Orateur</option>
                                    <option value="dirigeant" {{ $participation->role_culte == 'dirigeant' ? 'selected' : '' }}>Dirigeant</option>
                                    <option value="diacre_service" {{ $participation->role_culte == 'diacre_service' ? 'selected' : '' }}>Diacre de Service</option>
                                    <option value="collecteur_offrande" {{ $participation->role_culte == 'collecteur_offrande' ? 'selected' : '' }}>Collecteur Offrande</option>
                                    <option value="invite_special" {{ $participation->role_culte == 'invite_special' ? 'selected' : '' }}>Invité Spécial</option>
                                    <option value="nouveau_visiteur" {{ $participation->role_culte == 'nouveau_visiteur' ? 'selected' : '' }}>Nouveau Visiteur</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Accompagné par</label>
                                <select name="accompagne_par"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Aucun accompagnateur</option>
                                    @if(isset($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $participation->accompagne_par == $user->id ? 'selected' : '' }}>
                                                {{ $user->nom }} {{ $user->prenom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Options de confirmation -->
                            <div class="bg-slate-50 p-4 rounded-xl">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Confirmation et validation</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="presence_confirmee" value="1"
                                                {{ $participation->presence_confirmee ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-slate-700">Présence confirmée</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirmé par</label>
                                        <select name="confirme_par"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="">Sélectionner...</option>
                                            @if(isset($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $participation->confirme_par == $user->id ? 'selected' : '' }}>
                                                        {{ $user->nom }} {{ $user->prenom }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Besoins de suivi -->
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Besoins de suivi pastoral</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="premiere_visite" value="1"
                                            {{ $participation->premiere_visite ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Première visite</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="demande_contact_pastoral" value="1"
                                            {{ $participation->demande_contact_pastoral ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Demande contact pastoral</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="interesse_bapteme" value="1"
                                            {{ $participation->interesse_bapteme ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Intéressé par le baptême</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="souhaite_devenir_membre" value="1"
                                            {{ $participation->souhaite_devenir_membre ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Souhaite devenir membre</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Notes et commentaires -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes du responsable</label>
                                    <textarea name="notes_responsable" rows="4"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                        placeholder="Notes et observations du responsable...">{{ $participation->notes_responsable }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaires du participant</label>
                                    <textarea name="commentaires_participant" rows="4"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                        placeholder="Commentaires ou témoignage du participant...">{{ $participation->commentaires_participant }}</textarea>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                                <div class="flex items-center space-x-3">
                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                                    </button>
                                    <button type="button" onclick="resetForm()"
                                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-undo mr-2"></i> Réinitialiser
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('private.participantscultes.show', [$participation->participant_id, $participation->culte_id]) }}"
                                       class="inline-flex items-center px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                                        <i class="fas fa-times mr-2"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations du participant -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user text-purple-600 mr-2"></i>
                        Participant
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $participation->participant->nom }} {{ $participation->participant->prenom }}</h3>
                        <p class="text-sm text-slate-600">{{ ucfirst($participation->participant->statut_membre ?? 'Visiteur') }}</p>
                    </div>

                    <div class="space-y-3 text-sm">
                        @if($participation->participant->email)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Email:</span>
                                <a href="mailto:{{ $participation->participant->email }}" class="text-blue-600 hover:underline">{{ $participation->participant->email }}</a>
                            </div>
                        @endif
                        @if($participation->participant->telephone_1)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Téléphone:</span>
                                <a href="tel:{{ $participation->participant->telephone_1 }}" class="text-blue-600 hover:underline">{{ $participation->participant->telephone_1 }}</a>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Sexe:</span>
                            <span class="text-slate-900">{{ ucfirst($participation->participant->sexe ?? 'Non spécifié') }}</span>
                        </div>
                        @if($participation->participant->date_naissance)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Âge:</span>
                                <span class="text-slate-900">{{ \Carbon\Carbon::parse($participation->participant->date_naissance)->age }} ans</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations du culte -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-green-600 mr-2"></i>
                        Culte
                    </h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Titre:</span>
                        <span class="text-slate-900 font-medium">{{ $participation->culte->titre }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Date:</span>
                        <span class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($participation->culte->date_culte)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Heure:</span>
                        <span class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($participation->culte->heure_debut)->format('H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Type:</span>
                        <span class="text-slate-900 font-medium">{{ $participation->culte->type_culte_libelle }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Lieu:</span>
                        <span class="text-slate-900 font-medium">{{ $participation->culte->lieu }}</span>
                    </div>
                </div>
                <div class="p-6 pt-0">
                    <a href="{{ route('private.cultes.show', $participation->culte) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                        <i class="fas fa-eye mr-2"></i> Voir le culte complet
                    </a>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if(!$participation->presence_confirmee)
                        <button type="button" onclick="confirmerPresence()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-check mr-2"></i> Confirmer la présence
                        </button>
                    @endif

                    <button type="button" onclick="duplicateParticipation()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        <i class="fas fa-copy mr-2"></i> Dupliquer pour autre culte
                    </button>

                    <button type="button" onclick="printParticipation()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>

                    <button type="button" onclick="deleteParticipation()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </div>
            </div>

            <!-- Historique des modifications -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Historique
                    </h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Créé le:</span>
                        <span class="text-slate-900 font-medium">{{ $participation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Modifié le:</span>
                        <span class="text-slate-900 font-medium">{{ $participation->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($participation->enregistreur)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Enregistré par:</span>
                            <span class="text-slate-900 font-medium">{{ $participation->enregistreur->nom }} {{ $participation->enregistreur->prenom }}</span>
                        </div>
                    @endif
                    @if($participation->confirme_le && $participation->confirmateur)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Confirmé le:</span>
                            <span class="text-slate-900 font-medium">{{ $participation->confirme_le->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Confirmé par:</span>
                            <span class="text-slate-900 font-medium">{{ $participation->confirmateur->nom }} {{ $participation->confirmateur->prenom }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Sauvegarde automatique
let autoSaveTimer;
const form = document.getElementById('editForm');

// Détecter les changements et programmer une sauvegarde automatique
form.addEventListener('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(autoSave, 5000); // Sauvegarde après 5 secondes d'inactivité
});

function autoSave() {
    const formData = new FormData(form);
    formData.append('auto_save', '1');

    fetch(form.action, {
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
            showNotification('Sauvegarde automatique effectuée', 'success');
        }
    })
    .catch(error => {
        console.error('Erreur de sauvegarde automatique:', error);
    });
}

// Soumission du formulaire principal
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
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
            showNotification('Participation mise à jour avec succès', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("private.participantscultes.show", [$participation->participant_id, $participation->culte_id]) }}';
            }, 1500);
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
});

function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les modifications non sauvegardées seront perdues.')) {
        form.reset();
        location.reload();
    }
}

function confirmerPresence() {
    fetch('{{ route("private.participantscultes.confirmer-presence", [$participation->participant_id, $participation->culte_id]) }}', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Présence confirmée avec succès', 'success');
            // Mettre à jour le checkbox
            document.querySelector('input[name="presence_confirmee"]').checked = true;
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function duplicateParticipation() {
    // Modal ou redirection pour dupliquer vers un autre culte
    alert('Fonctionnalité de duplication à implémenter');
}

function printParticipation() {
    window.print();
}

function deleteParticipation() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette participation ?')) {
        fetch('{{ route("private.participantscultes.destroy", [$participation->participant_id, $participation->culte_id]) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Participation supprimée avec succès', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("private.participantscultes.index") }}';
                }, 1500);
            } else {
                showNotification(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    }
}

function showNotification(message, type = 'info') {
    // Créer une notification toast
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Validation en temps réel
document.querySelector('input[name="heure_depart"]').addEventListener('change', function() {
    const heureArrivee = document.querySelector('input[name="heure_arrivee"]').value;
    const heureDepart = this.value;

    if (heureArrivee && heureDepart && heureDepart <= heureArrivee) {
        this.setCustomValidity('L\'heure de départ doit être postérieure à l\'heure d\'arrivée');
        showNotification('L\'heure de départ doit être postérieure à l\'heure d\'arrivée', 'error');
    } else {
        this.setCustomValidity('');
    }
});

// Prévenir la perte de données
window.addEventListener('beforeunload', function(e) {
    if (form.checkValidity() && form.dataset.changed) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Marquer le formulaire comme modifié
form.addEventListener('input', function() {
    this.dataset.changed = 'true';
});

form.addEventListener('submit', function() {
    this.dataset.changed = 'false';
});
</script>
@endpush
@endsection
