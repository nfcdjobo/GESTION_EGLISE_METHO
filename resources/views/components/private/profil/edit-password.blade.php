@extends('layouts.private.main')
@section('title', 'Changer mon mot de passe')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Changer mon mot de passe</h1>
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
                        <span class="text-sm font-medium text-slate-500">Mot de passe</span>
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
        <!-- Formulaire de changement de mot de passe -->
        <div class="lg:col-span-2">
            <form action="{{ route('private.profil.update.password') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-key text-amber-600 mr-2"></i>
                            Modifier le Mot de Passe
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Mot de passe actuel -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Mot de passe actuel <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('current_password') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Nouveau mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror" oninput="checkPasswordStrength()">
                                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Indicateur de force du mot de passe -->
                            <div class="mt-2">
                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <span id="strength-text" class="text-xs font-medium text-slate-500"></span>
                                </div>
                                <p class="text-xs text-slate-500">Le mot de passe doit contenir au moins 8 caractères, incluant majuscules, minuscules, chiffres et symboles.</p>
                            </div>
                        </div>

                        <!-- Confirmation du nouveau mot de passe -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password_confirmation') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('private.profil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-key mr-2"></i> Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>

        <!-- Conseils de sécurité -->
        <div class="space-y-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                        Conseils de Sécurité
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Utilisez un mot de passe fort</h3>
                            <p class="text-xs text-slate-600">Minimum 8 caractères avec majuscules, minuscules, chiffres et symboles.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Évitez les mots de passe évidents</h3>
                            <p class="text-xs text-slate-600">N'utilisez pas votre nom, date de naissance ou mots courants.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-amber-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Mot de passe unique</h3>
                            <p class="text-xs text-slate-600">N'utilisez pas le même mot de passe sur plusieurs sites.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-purple-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Changez régulièrement</h3>
                            <p class="text-xs text-slate-600">Modifiez votre mot de passe tous les 3-6 mois.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl shadow-lg border border-red-100 p-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-900 mb-2">Important</h3>
                        <p class="text-xs text-red-700">Ne partagez jamais votre mot de passe avec qui que ce soit. Si vous pensez que votre compte a été compromis, changez immédiatement votre mot de passe.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle visibility des mots de passe
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Vérification de la force du mot de passe
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    let strength = 0;
    let strengthLabel = '';
    let color = '';

    // Critères de vérification
    if (password.length >= 8) strength += 20;
    if (password.length >= 12) strength += 10;
    if (/[a-z]/.test(password)) strength += 20;
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[0-9]/.test(password)) strength += 15;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 15;

    // Définir le label et la couleur
    if (strength === 0) {
        strengthLabel = '';
        color = '';
    } else if (strength < 40) {
        strengthLabel = 'Faible';
        color = '#ef4444';
    } else if (strength < 60) {
        strengthLabel = 'Moyen';
        color = '#f59e0b';
    } else if (strength < 80) {
        strengthLabel = 'Bon';
        color = '#3b82f6';
    } else {
        strengthLabel = 'Fort';
        color = '#10b981';
    }

    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = color;
    strengthText.textContent = strengthLabel;
    strengthText.style.color = color;
}
</script>

@endsection
