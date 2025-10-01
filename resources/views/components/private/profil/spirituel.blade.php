@extends('layouts.private.main')
@section('title', 'Informations Spirituelles')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Informations Spirituelles</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.profil.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-user mr-2"></i>
                        Mon Profil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Spirituel</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start space-x-3">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start space-x-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Erreurs de validation</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire -->
        <div class="lg:col-span-2">
            <form action="{{ route('private.profil.update.spirituel') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Témoignage -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-book-bible text-purple-600 mr-2"></i>
                            Mon Témoignage
                        </h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Partagez votre témoignage de conversion ou votre parcours spirituel
                        </label>
                        <textarea name="temoignage" rows="8" maxlength="5000" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('temoignage') border-red-500 @enderror" placeholder="Racontez comment vous avez rencontré le Seigneur...">{{ old('temoignage', $user->temoignage) }}</textarea>
                        @error('temoignage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-slate-500">Maximum 5000 caractères</p>
                            <span class="text-xs text-slate-500" id="temoignage-count">{{ strlen($user->temoignage ?? '') }}/5000</span>
                        </div>
                    </div>
                </div>

                <!-- Dons spirituels -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-hands-praying text-amber-600 mr-2"></i>
                            Dons Spirituels
                        </h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Quels sont vos dons spirituels ou ministères ?
                        </label>
                        <textarea name="dons_spirituels" rows="6" maxlength="2000" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('dons_spirituels') border-red-500 @enderror" placeholder="Ex: Enseignement, prière, intercession, louange, évangélisation...">{{ old('dons_spirituels', $user->dons_spirituels) }}</textarea>
                        @error('dons_spirituels')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-slate-500">Maximum 2000 caractères</p>
                            <span class="text-xs text-slate-500" id="dons-count">{{ strlen($user->dons_spirituels ?? '') }}/2000</span>
                        </div>
                    </div>
                </div>

                <!-- Demandes de prière -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-praying-hands text-green-600 mr-2"></i>
                            Demandes de Prière
                        </h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Partagez vos sujets de prière avec la communauté
                        </label>
                        <textarea name="demandes_priere" rows="6" maxlength="2000" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('demandes_priere') border-red-500 @enderror" placeholder="Vos demandes de prière...">{{ old('demandes_priere', $user->demandes_priere) }}</textarea>
                        @error('demandes_priere')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-slate-500">Maximum 2000 caractères</p>
                            <span class="text-xs text-slate-500" id="priere-count">{{ strlen($user->demandes_priere ?? '') }}/2000</span>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('private.profil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations d'église (lecture seule) -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-blue-600 mr-2"></i>
                        Parcours Spirituel
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Statut de membre</label>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($user->statut_membre)
                                    @case('actif') bg-green-100 text-green-800 @break
                                    @case('inactif') bg-red-100 text-red-800 @break
                                    @case('visiteur') bg-yellow-100 text-yellow-800 @break
                                    @case('nouveau_converti') bg-purple-100 text-purple-800 @break
                                @endswitch">
                                {{ ucfirst(str_replace('_', ' ', $user->statut_membre)) }}
                            </span>
                        </div>
                    </div>

                    @if($user->date_adhesion)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date d'adhésion</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-900">{{ $user->date_adhesion->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $user->date_adhesion->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Statut de baptême</label>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->statut_bapteme === 'baptise' || $user->statut_bapteme === 'confirme')
                                    bg-cyan-100 text-cyan-800
                                @else
                                    bg-slate-100 text-slate-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->statut_bapteme)) }}
                            </span>
                        </div>
                    </div>

                    @if($user->date_bapteme)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date de baptême</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-900">{{ $user->date_bapteme->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($user->eglise_precedente)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Église précédente</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-900">{{ $user->eglise_precedente }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verset d'encouragement -->
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-100 p-6">
                <div class="text-center">
                    <i class="fas fa-bible text-3xl text-blue-600 mb-4"></i>
                    <p class="text-sm text-slate-700 italic mb-2">"Car je connais les projets que j'ai formés sur vous, dit l'Éternel, projets de paix et non de malheur, afin de vous donner un avenir et de l'espérance."</p>
                    <p class="text-xs font-semibold text-blue-600">Jérémie 29:11</p>
                </div>
            </div>

            <!-- Conseils -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Conseils
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p class="text-xs text-slate-600">Soyez authentique dans votre témoignage</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p class="text-xs text-slate-600">Partagez vos dons pour aider la communauté à vous connaître</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p class="text-xs text-slate-600">Les demandes de prière permettent l'intercession</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <p class="text-xs text-slate-600">Vous pouvez modifier ces informations à tout moment</p>
                    </div>
                </div>
            </div>

            <!-- Note de confidentialité -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg border border-amber-100 p-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-lock text-amber-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900 mb-2">Confidentialité</h3>
                        <p class="text-xs text-amber-700">Ces informations sont partagées uniquement avec les responsables de l'église pour mieux vous accompagner dans votre cheminement spirituel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Compteur de caractères pour le témoignage
document.querySelector('textarea[name="temoignage"]')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('temoignage-count').textContent = count + '/5000';
});

// Compteur de caractères pour les dons spirituels
document.querySelector('textarea[name="dons_spirituels"]')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('dons-count').textContent = count + '/2000';
});

// Compteur de caractères pour les demandes de prière
document.querySelector('textarea[name="demandes_priere"]')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('priere-count').textContent = count + '/2000';
});
</script>

@endsection
