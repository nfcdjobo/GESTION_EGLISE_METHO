<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - Plateforme de l'Église</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{$AppParametres->logo ? Storage::url($AppParametres->logo) :  ''}}" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo et Titre -->
        <div class="text-center mb-8">
            <a href="{{route('public.accueil')}}" class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-orange-600 to-red-600 rounded-full mb-4 shadow-lg">
                <img class="aspect-square w-[80px] rounded-full object-cover ring-2 ring-blue-500" src="{{$AppParametres->logo ? Storage::url($AppParametres->logo) :  ''}}" alt="Logo église" />
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Nouveau mot de passe</h1>
            <p class="text-gray-600">Créez votre nouveau mot de passe</p>
        </div>

        <!-- Carte de réinitialisation -->
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire de réinitialisation -->
            <form method="POST" action="{{ route('security.password.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>Adresse email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', request()->email) }}"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                        placeholder="exemple@email.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>Nouveau mot de passe
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i id="passwordToggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                            placeholder="••••••••"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i id="passwordConfirmationToggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Indicateur de force du mot de passe -->
                <div id="passwordStrength" class="hidden">
                    <div class="text-sm font-medium text-gray-700 mb-2">Force du mot de passe :</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="passwordStrengthBar" class="h-2 rounded-full transition-all duration-300"></div>
                    </div>
                    <p id="passwordStrengthText" class="text-xs mt-1"></p>
                </div>

                <!-- Bouton de réinitialisation -->
                <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-orange-700 hover:to-red-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Définir le nouveau mot de passe
                </button>
            </form>

            <!-- Retour à la connexion -->
            <div class="mt-6 text-center">
                <a href="{{ route('security.login') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la connexion
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} Plateforme de l'Église. Tous droits réservés.
            </p>
        </div>
    </div>

    <script>
        // Fonction pour afficher/masquer le mot de passe
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + 'ToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Vérificateur de force du mot de passe
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            const strengthContainer = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthContainer.classList.add('hidden');
                return;
            }

            strengthContainer.classList.remove('hidden');

            // Longueur
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;

            // Contient des minuscules et majuscules
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;

            // Contient des chiffres
            if (/\d/.test(password)) strength += 1;

            // Contient des caractères spéciaux
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;

            // Mise à jour de l'indicateur
            const strengthColors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const strengthTexts = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
            const strengthTextColors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-blue-600', 'text-green-600'];

            // Reset classes
            strengthBar.className = 'h-2 rounded-full transition-all duration-300';
            strengthText.className = 'text-xs mt-1';

            // Apply new classes
            strengthBar.classList.add(strengthColors[strength]);
            strengthText.classList.add(strengthTextColors[strength]);
            strengthBar.style.width = ((strength + 1) * 20) + '%';
            strengthText.textContent = strengthTexts[strength];
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function(e) {
            checkPasswordStrength(e.target.value);
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.bg-white.rounded-2xl');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
