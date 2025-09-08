@extends('layouts.private.main')
@section('title', 'Nouvelle Souscription')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouvelle Souscription</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Souscriptions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Créer</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('private.subscriptions.store') }}" method="POST" id="souscriptionForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire principal -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Souscription
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Sélection FIMECO -->
                        <div>
                            <label for="fimeco_id" class="block text-sm font-medium text-slate-700 mb-2">
                                FIMECO <span class="text-red-500">*</span>
                            </label>
                            {{-- @if($fimecoActive) --}}
                                <input type="hidden" name="fimeco_id" value="{{ $fimecoActive->id }}">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <h3 class="font-semibold text-blue-900">{{ $fimecoActive->nom }}</h3>
                                    <p class="text-sm text-blue-700 mt-1">{{ $fimecoActive->description }}</p>
                                    <div class="flex items-center gap-4 mt-3 text-sm text-blue-600">
                                        <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($fimecoActive->debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fimecoActive->fin)->format('d/m/Y') }}</span>
                                        <span><i class="fas fa-target mr-1"></i>{{ number_format($fimecoActive->cible, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                            {{-- @else
                                <select id="fimeco_id" name="fimeco_id" required onchange="updateFimecoInfo()"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fimeco_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une FIMECO</option>
                                    @foreach($fimecoActives as $fimeco)
                                        <option value="{{ $fimeco->id }}" data-nom="{{ $fimeco->nom }}" data-description="{{ $fimeco->description }}"
                                                data-debut="{{ $fimeco->debut }}" data-fin="{{ $fimeco->fin }}" data-cible="{{ $fimeco->cible }}"
                                                {{ old('fimeco_id') == $fimeco->id ? 'selected' : '' }}>
                                            {{ $fimeco->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fimeco_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror


                                <!-- Zone d'affichage des infos FIMECO -->
                                <div id="fimeco-info" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <h3 id="fimeco-nom" class="font-semibold text-blue-900"></h3>
                                    <p id="fimeco-description" class="text-sm text-blue-700 mt-1"></p>
                                    <div class="flex items-center gap-4 mt-3 text-sm text-blue-600">
                                        <span id="fimeco-periode"><i class="fas fa-calendar mr-1"></i></span>
                                        <span id="fimeco-cible"><i class="fas fa-target mr-1"></i></span>
                                    </div>
                                </div>
                            @endif --}}
                        </div>

                        <div>
                            <label for="souscripteur_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Souscripteur <span class="text-red-500">*</span>
                            </label>
                            <select id="souscripteur_id" name="souscripteur_id" required onchange="() => fetchUsers()"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('souscripteur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le souscripteur</option>
                                    @foreach($utilisateursDisponibles as $user)
                                        <option value="{{ $user->id }}" data-nom="{{ $user->nom }}" data-telephone="{{ $user->telephone_1 }}"
                                                data-name="{{ $user->nom }}" data-prenom="{{ $user->prenom }}" data-email="{{ $user->email }}"
                                                {{ old('souscripteur_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom. ' '. $user->prenom. ' ('. $user->telephone_1. ')'  }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- Montant de souscription -->
                        <div>
                            <label for="montant_souscrit" class="block text-sm font-medium text-slate-700 mb-2">
                                Montant de souscription (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="montant_souscrit" name="montant_souscrit" value="{{ old('montant_souscrit') }}"
                                   required min="10" step="0.01" placeholder="50000" onchange="updatePreview()"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant_souscrit') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('montant_souscrit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Montant minimum : 10 FCFA</p>
                        </div>



                        <!-- Commentaire -->
                        <div>
                            <label for="commentaire" class="block text-sm font-medium text-slate-700 mb-2">Commentaire (optionnel)</label>
                            <textarea id="commentaire" name="commentaire" rows="3" placeholder="Motivation, objectifs personnels, etc."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('commentaire') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu de la souscription -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">FIMECO:</span>
                            <span id="preview-fimeco" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Montant:</span>
                            <span id="preview-montant" class="text-sm text-green-600 font-semibold">-</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut initial:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Information importante -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-amber-600 mr-2"></i>
                            Informations Importantes
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">Engagement</p>
                                    <p class="mt-1">En souscrivant, vous vous engagez à verser le montant indiqué selon les modalités de la FIMECO.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-check-circle text-green-400 mt-0.5 mr-3"></i>
                                <div class="text-sm text-green-800">
                                    <p class="font-medium">Flexibilité</p>
                                    <p class="mt-1">Vous pouvez effectuer des paiements partiels et suivre votre progression.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-clock text-orange-400 mt-0.5 mr-3"></i>
                                <div class="text-sm text-orange-800">
                                    <p class="font-medium">Suivi</p>
                                    <p class="mt-1">Vos paiements doivent être validés par les responsables de la FIMECO.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guide de souscription -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-map text-green-600 mr-2"></i>
                            Guide de Souscription
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-blue-600 font-bold text-sm">1</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900">Choisir la FIMECO</h3>
                                <p class="text-sm text-slate-600">Sélectionnez une FIMECO active correspondant à vos objectifs</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-purple-600 font-bold text-sm">2</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900">Fixer le montant</h3>
                                <p class="text-sm text-slate-600">Déterminez un montant réaliste selon vos capacités</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-green-600 font-bold text-sm">3</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900">Valider</h3>
                                <p class="text-sm text-slate-600">Confirmez votre souscription et commencez les paiements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la Souscription
                    </button>
                    <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Mise à jour des informations FIMECO
function updateFimecoInfo() {
    const select = document.getElementById('fimeco_id');
    const infoDiv = document.getElementById('fimeco-info');

    if (select?.value) {
        const option = select.options[select.selectedIndex];
        const nom = option.getAttribute('data-nom');
        const description = option.getAttribute('data-description');
        const debut = option.getAttribute('data-debut');
        const fin = option.getAttribute('data-fin');
        const cible = option.getAttribute('data-cible');

        document.getElementById('fimeco-nom').textContent = nom;
        document.getElementById('fimeco-description').textContent = description || 'Aucune description';
        document.getElementById('fimeco-periode').innerHTML = `<i class="fas fa-calendar mr-1"></i>${debut} - ${fin}`;
        document.getElementById('fimeco-cible').innerHTML = `<i class="fas fa-target mr-1"></i>${new Intl.NumberFormat('fr-FR').format(cible)} FCFA`;

        infoDiv.classList.remove('hidden');
    } else {
        infoDiv?.classList.add('hidden');
    }

    updatePreview();
}

// Mise à jour de l'aperçu
function updatePreview() {
    const fimecoSelect = document.getElementById('fimeco_id');
    const montant = document.getElementById('montant_souscrit').value;

    // FIMECO
    if (fimecoSelect?.value) {
        const fimecoNom = fimecoSelect.options[fimecoSelect.selectedIndex].text;
        document.getElementById('preview-fimeco').textContent = fimecoNom;
    } else {
        document.getElementById('preview-fimeco').textContent = '-';
    }

    // Montant
    if (montant) {
        const formatted = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
        document.getElementById('preview-montant').textContent = formatted;
    } else {
        document.getElementById('preview-montant').textContent = '-';
    }


}

// Validation du formulaire
document.getElementById('souscriptionForm').addEventListener('submit', function(e) {
    const fimecoId = document.getElementById('fimeco_id').value;
    const montant = parseFloat(document.getElementById('montant_souscrit').value);

    if (!fimecoId) {
        e.preventDefault();
        alert('Veuillez sélectionner une FIMECO.');
        return false;
    }

    if (!montant || montant < 10) {
        e.preventDefault();
        alert('Le montant de souscription doit être d\'au moins 10 FCFA.');
        return false;
    }



    // Confirmation
    const confirmation = confirm(
        `Confirmez-vous votre souscription de ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA ?`
    );

    if (!confirmation) {
        e.preventDefault();
        return false;
    }
});

const fetchUsers = () => {
    alert()
    const target = this.target
    alert();
    fetch("{{route('private.subscriptions.user-disponibles', ':fimeco')}}".replace(':fimeco', target.value), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            target.content = '';
            target.value = '';
            data.data.forEach((user, key) => {
                if($key == 0){
                    target.innerHTML = `<option value="">Sélectionner le souscripteur</option>`;
                }else{
                    target.innerHTML += `<option value="${user.id}" data-nom="${user.nom}" data-telephone="${user.telephone_1}" data-prenom="${user.prenom}" data-email="${user.email}">
                                             ${user.nom}  ${user.prenom} ( ${user.telephone_1} )
                                        </option>`
                }
            })

        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Événements
document.getElementById('montant_souscrit').addEventListener('input', updatePreview);

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateFimecoInfo();
    updatePreview();
});
</script>
@endpush
@endsection
