@extends('layouts.private.main')
@section('title', 'Modifier mon profil')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier mon profil</h1>
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
                        <span class="text-sm font-medium text-slate-500">Modifier</span>
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

    <form action="{{ route('private.profil.update.informations') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Photo de profil -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-camera text-blue-600 mr-2"></i>
                    Photo de Profil
                </h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Aperçu de la photo actuelle -->
                    <div class="flex-shrink-0">
                        @if($user->photo_profil)
                            <img id="photoPreview" src="{{ Storage::url($user->photo_profil) }}" alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-lg">
                        @else
                            <div id="photoPreview" class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white text-4xl font-bold">
                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Upload et actions -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Changer de photo</label>
                        <div class="flex flex-wrap gap-3">
                            <label class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors cursor-pointer">
                                <i class="fas fa-upload mr-2"></i> Choisir une photo
                                <input type="file" name="photo_profil" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                            </label>
                            @if($user->photo_profil)
                                <a href="{{ route('private.profil.delete.photo') }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </a>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Format accepté : JPG, PNG, GIF (max 2 Mo)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user text-purple-600 mr-2"></i>
                    Informations Personnelles
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('prenom') border-red-500 @enderror">
                        @error('prenom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 @enderror">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_naissance') border-red-500 @enderror">
                        @error('date_naissance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Sexe <span class="text-red-500">*</span></label>
                        <select name="sexe" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('sexe') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            <option value="masculin" {{ old('sexe', $user->sexe) === 'masculin' ? 'selected' : '' }}>Masculin</option>
                            <option value="feminin" {{ old('sexe', $user->sexe) === 'feminin' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut matrimonial <span class="text-red-500">*</span></label>
                        <select name="statut_matrimonial" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut_matrimonial') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            <option value="celibataire" {{ old('statut_matrimonial', $user->statut_matrimonial) === 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                            <option value="marie" {{ old('statut_matrimonial', $user->statut_matrimonial) === 'marie' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="divorce" {{ old('statut_matrimonial', $user->statut_matrimonial) === 'divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="veuf" {{ old('statut_matrimonial', $user->statut_matrimonial) === 'veuf' ? 'selected' : '' }}>Veuf(ve)</option>
                        </select>
                        @error('statut_matrimonial')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nombre d'enfants <span class="text-red-500">*</span></label>
                        <input type="number" name="nombre_enfants" value="{{ old('nombre_enfants', $user->nombre_enfants) }}" min="0" max="20" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_enfants') border-red-500 @enderror">
                        @error('nombre_enfants')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-address-book text-green-600 mr-2"></i>
                    Coordonnées
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone principal <span class="text-red-500">*</span></label>
                        <input type="tel" name="telephone_1" value="{{ old('telephone_1', $user->telephone_1) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_1') border-red-500 @enderror">
                        @error('telephone_1')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone secondaire</label>
                        <input type="tel" name="telephone_2" value="{{ old('telephone_2', $user->telephone_2) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_2') border-red-500 @enderror">
                        @error('telephone_2')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                    Adresse
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Adresse ligne 1</label>
                    <input type="text" name="adresse_ligne_1" value="{{ old('adresse_ligne_1', $user->adresse_ligne_1) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('adresse_ligne_1') border-red-500 @enderror">
                    @error('adresse_ligne_1')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Adresse ligne 2</label>
                    <input type="text" name="adresse_ligne_2" value="{{ old('adresse_ligne_2', $user->adresse_ligne_2) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('adresse_ligne_2') border-red-500 @enderror">
                    @error('adresse_ligne_2')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Code postal</label>
                        <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code_postal') border-red-500 @enderror">
                        @error('code_postal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('ville') border-red-500 @enderror">
                        @error('ville')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Région</label>
                        <input type="text" name="region" value="{{ old('region', $user->region) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('region') border-red-500 @enderror">
                        @error('region')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pays</label>
                    <input type="text" name="pays" value="{{ old('pays', $user->pays) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pays') border-red-500 @enderror">
                    @error('pays')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-briefcase text-amber-600 mr-2"></i>
                    Informations Professionnelles
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession', $user->profession) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('profession') border-red-500 @enderror">
                        @error('profession')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Employeur</label>
                        <input type="text" name="employeur" value="{{ old('employeur', $user->employeur) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('employeur') border-red-500 @enderror">
                        @error('employeur')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-phone-volume text-cyan-600 mr-2"></i>
                    Contact d'Urgence
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                        <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_urgence_nom') border-red-500 @enderror">
                        @error('contact_urgence_nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone</label>
                        <input type="tel" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_urgence_telephone') border-red-500 @enderror">
                        @error('contact_urgence_telephone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Relation</label>
                        <input type="text" name="contact_urgence_relation" value="{{ old('contact_urgence_relation', $user->contact_urgence_relation) }}" placeholder="Ex: Époux, Parent, Ami..." class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_urgence_relation') border-red-500 @enderror">
                        @error('contact_urgence_relation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('private.profil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
// Prévisualisation de la photo
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-lg">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>

@endsection
