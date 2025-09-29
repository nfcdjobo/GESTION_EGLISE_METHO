@extends('layouts.private.main')
@section('title', 'Nouveau Paramètre de Don')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouveau Paramètre de Don</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.parametresdons.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Paramètres de Don
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Nouveau</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Formulaire -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                Informations du Paramètre
            </h2>
        </div>

        <form method="POST" action="{{ route('private.parametresdons.store') }}" class="p-6" enctype="multipart/form-data" id="parametreForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Opérateur -->
                <div>
                    <label for="operateur" class="block text-sm font-medium text-slate-700 mb-2">
                        Opérateur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="operateur" name="operateur" value="{{ old('operateur') }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('operateur') border-red-500 @enderror"
                           placeholder="Nom de l'opérateur (ex: Orange Money, MTN, etc.)" required>
                    @error('operateur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
                        Type de Paiement <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type') border-red-500 @enderror">
                        <option value="">Sélectionnez un type</option>
                        <option value="virement_bancaire" {{ old('type') == 'virement_bancaire' ? 'selected' : '' }}>Virement Bancaire</option>
                        <option value="carte_bancaire" {{ old('type') == 'carte_bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                        <option value="mobile_money" {{ old('type') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numéro de compte -->
                <div>
                    <label for="numero_compte" class="block text-sm font-medium text-slate-700 mb-2">
                        Numéro de Compte <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="numero_compte" name="numero_compte" value="{{ old('numero_compte') }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('numero_compte') border-red-500 @enderror"
                           placeholder="Numéro de compte ou téléphone" required>
                    @error('numero_compte')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo de l'opérateur -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-slate-700 mb-2">
                        Logo de l'Opérateur (optionnel)
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('logo') border-red-500 @enderror"
                                   onchange="previewLogo(event)">
                            <p class="mt-1 text-xs text-slate-500">
                                Formats acceptés: JPG, PNG, SVG, WebP (max 2 Mo)
                            </p>
                        </div>
                        <!-- Prévisualisation du logo -->
                        <div id="logoPreview" class="hidden w-20 h-20 border-2 border-slate-300 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                            <img id="logoPreviewImg" src="" alt="Aperçu du logo" class="w-full h-full object-contain">
                        </div>
                    </div>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- QR Code -->
                <div>
                    <label for="qrcode" class="block text-sm font-medium text-slate-700 mb-2">
                        Code QR (optionnel)
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="file" id="qrcode" name="qrcode" accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('qrcode') border-red-500 @enderror"
                                   onchange="previewQRCode(event)">
                            <p class="mt-1 text-xs text-slate-500">
                                Formats acceptés: JPG, PNG, SVG (max 2 Mo)
                            </p>
                        </div>
                        <!-- Prévisualisation du QR Code -->
                        <div id="qrcodePreview" class="hidden w-20 h-20 border-2 border-slate-300 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                            <img id="qrcodePreviewImg" src="" alt="Aperçu du QR Code" class="w-full h-full object-contain">
                        </div>
                    </div>
                    @error('qrcode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Options -->
            <div class="mt-8 space-y-4">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-cog text-purple-600 mr-2"></i>
                    Options
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Statut -->
                    <div class="flex items-center space-x-3 p-4 bg-slate-50 rounded-xl">
                        <input type="checkbox" id="statut" name="statut" value="1" {{ old('statut') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="statut" class="text-sm font-medium text-slate-700">
                            Activer ce paramètre
                        </label>
                    </div>

                    <!-- Publication -->
                    <div class="flex items-center space-x-3 p-4 bg-slate-50 rounded-xl">
                        <input type="checkbox" id="publier" name="publier" value="1" {{ old('publier') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="publier" class="text-sm font-medium text-slate-700">
                            Publier pour les dons publics
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('private.parametresdons.index') }}" class="px-6 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Prévisualisation du logo
    function previewLogo(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('logoPreview');
        const previewImg = document.getElementById('logoPreviewImg');

        if (file) {
            // Vérifier la taille du fichier (2 Mo max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale : 2 Mo');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }

            // Vérifier le type de fichier
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format de fichier non accepté. Utilisez JPG, PNG, SVG ou WebP');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }

    // Prévisualisation du QR Code
    function previewQRCode(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('qrcodePreview');
        const previewImg = document.getElementById('qrcodePreviewImg');

        if (file) {
            // Vérifier la taille du fichier (2 Mo max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale : 2 Mo');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }

            // Vérifier le type de fichier
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
            if (!validTypes.includes(file.type)) {
                alert('Format de fichier non accepté. Utilisez JPG, PNG ou SVG');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endpush

@endsection
