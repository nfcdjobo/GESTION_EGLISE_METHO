<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - Plateforme de l'Église</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{$AppParametres->logo ? Storage::url($AppParametres->logo) :  ''}}" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .puzzle-piece {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }

        .captcha-modal {
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
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

            <!-- Messages d'erreur JS -->
            <div id="errorMessages" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <div id="errorText"></div>
                </div>
            </div>

            <!-- Formulaire de réinitialisation -->
            <form method="POST" action="{{ route('security.password.update') }}" id="resetPasswordForm" class="space-y-6">
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
                <button type="button" onclick="showCaptchaModal()" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-orange-700 hover:to-red-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
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
        <!-- Footer Page de Réinitialisation -->
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} {{$AppParametres->nom_eglise ?? "Église Méthodiste Côte d'Ivoire"}}. Tous droits réservés.
            </p>
            <p class="text-gray-400 text-xs mt-2">
                Développé par
                <a href="https://wa.me/+2250708948093" target="_blank" rel="noopener noreferrer"
                class="text-orange-500 hover:text-orange-600 font-medium transition duration-200">
                    BarriServices
                </a>
            </p>
        </div>
    </div>

    <!-- MODAL CAPTCHA PUZZLE SLIDER -->
    <div id="captchaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 captcha-modal">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-shield-alt text-orange-600 mr-2"></i>
                        Vérification de sécurité
                    </h2>
                    <button onclick="closeCaptchaModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <p class="text-gray-600 text-sm">
                    Faites glisser la pièce du puzzle à la position correcte pour continuer
                </p>
            </div>

            <!-- Zone du puzzle -->
            <div id="puzzleContainer" class="relative w-full h-48 bg-gradient-to-br from-orange-100 via-red-100 to-pink-100 rounded-xl overflow-hidden mb-6 border-2 border-gray-200 shadow-inner">
                <!-- Motif de fond -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%">
                        <defs>
                            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="10" cy="10" r="1.5" fill="#ea580c" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                <!-- Emplacement cible du puzzle (vide) -->
                <div id="puzzleTarget" class="absolute top-1/2 -translate-y-1/2 transition-all duration-300" style="width: 60px; height: 60px;">
                    <svg viewBox="0 0 60 60" class="w-full h-full opacity-30">
                        <path
                            d="M 10,5 L 28,5 C 28,5 32,5 32,9 C 32,13 28,13 28,13 L 45,13 L 45,28 C 45,28 45,32 49,32 C 53,32 53,28 53,28 L 53,45 L 36,45 C 36,45 32,45 32,49 C 32,53 36,53 36,53 L 10,53 L 10,36 C 10,36 10,32 6,32 C 2,32 2,36 2,36 L 2,10 C 2,10 2,5 10,5 Z"
                            fill="white"
                            stroke="#94a3b8"
                            stroke-width="2"
                            stroke-dasharray="5,5"
                        />
                    </svg>
                </div>

                <!-- Pièce du puzzle mobile -->
                <div id="puzzlePiece" class="absolute top-1/2 -translate-y-1/2 left-0 transition-all duration-100 cursor-grab active:cursor-grabbing puzzle-piece" style="width: 60px; height: 60px;">
                    <svg viewBox="0 0 60 60" class="w-full h-full">
                        <defs>
                            <linearGradient id="pieceGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#ea580c;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#dc2626;stop-opacity:1" />
                            </linearGradient>
                            <filter id="pieceShadow">
                                <feDropShadow dx="0" dy="4" stdDeviation="4" flood-opacity="0.4"/>
                            </filter>
                        </defs>
                        <path
                            d="M 10,5 L 28,5 C 28,5 32,5 32,9 C 32,13 28,13 28,13 L 45,13 L 45,28 C 45,28 45,32 49,32 C 53,32 53,28 53,28 L 53,45 L 36,45 C 36,45 32,45 32,49 C 32,53 36,53 36,53 L 10,53 L 10,36 C 10,36 10,32 6,32 C 2,32 2,36 2,36 L 2,10 C 2,10 2,5 10,5 Z"
                            fill="url(#pieceGradient)"
                            filter="url(#pieceShadow)"
                        />
                    </svg>
                </div>

                <!-- Message de vérification -->
                <div id="verifiedMessage" class="hidden absolute inset-0 bg-green-500 bg-opacity-90 flex items-center justify-center rounded-xl">
                    <div class="text-center text-white">
                        <i class="fas fa-check-circle text-6xl mb-2"></i>
                        <p class="text-xl font-bold">Vérifié avec succès !</p>
                    </div>
                </div>
            </div>

            <!-- Barre de glissement -->
            <div class="mb-6">
                <div class="relative h-12 bg-gray-200 rounded-full overflow-hidden">
                    <div id="sliderProgress" class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-500 rounded-full transition-all duration-300" style="width: 0%;"></div>
                    <div id="sliderHandle" class="absolute left-0 top-0 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center cursor-grab active:cursor-grabbing transition-all duration-100">
                        <i class="fas fa-grip-lines-vertical text-gray-400"></i>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span id="sliderText" class="text-gray-600 font-medium text-sm">
                            <i class="fas fa-arrow-right mr-2"></i>Glissez pour vérifier
                        </span>
                    </div>
                </div>
            </div>

            <!-- Message d'erreur -->
            <div id="captchaError" class="hidden bg-red-50 border-l-4 border-red-500 p-3 rounded-lg mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="text-red-700 text-sm">Position incorrecte. Réessayez !</p>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3">
                <button onclick="refreshCaptcha()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i>
                    Nouveau puzzle
                </button>
                <button id="submitCaptcha" disabled class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 opacity-50 cursor-not-allowed flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i>
                    Valider
                </button>
            </div>
        </div>
    </div>

    <script>
        // Variables CAPTCHA
        let isDragging = false;
        let sliderPosition = 0;
        let puzzlePosition = 0;
        let isVerified = false;
        const PUZZLE_SIZE = 60;
        const TOLERANCE = 15;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            setupSliderEvents();

            // Animation d'entrée
            const card = document.querySelector('.bg-white.rounded-2xl');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Générer une nouvelle position de puzzle
        function generateNewPuzzle() {
            const container = document.getElementById('puzzleContainer');
            const maxPosition = container.clientWidth - PUZZLE_SIZE - 20;
            puzzlePosition = Math.random() * maxPosition + 10;

            document.getElementById('puzzleTarget').style.left = puzzlePosition + 'px';
            document.getElementById('puzzlePiece').style.left = '0px';
            document.getElementById('sliderHandle').style.left = '0px';
            document.getElementById('sliderProgress').style.width = '0%';

            sliderPosition = 0;
            isVerified = false;

            document.getElementById('verifiedMessage').classList.add('hidden');
            document.getElementById('captchaError').classList.add('hidden');
            document.getElementById('submitCaptcha').disabled = true;
            document.getElementById('submitCaptcha').classList.add('opacity-50', 'cursor-not-allowed');
            document.getElementById('puzzleContainer').classList.remove('shake');
        }

        // Configuration des événements de glissement
        function setupSliderEvents() {
            const handle = document.getElementById('sliderHandle');

            handle.addEventListener('mousedown', startDragging);
            handle.addEventListener('touchstart', startDragging);

            document.addEventListener('mousemove', drag);
            document.addEventListener('touchmove', drag);

            document.addEventListener('mouseup', stopDragging);
            document.addEventListener('touchend', stopDragging);
        }

        function startDragging(e) {
            if (isVerified) return;
            isDragging = true;
            e.preventDefault();
        }

        function drag(e) {
            if (!isDragging || isVerified) return;

            const container = document.getElementById('puzzleContainer');
            const rect = container.getBoundingClientRect();
            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;

            let newPosition = clientX - rect.left;
            const maxPosition = rect.width - PUZZLE_SIZE;

            newPosition = Math.max(0, Math.min(newPosition, maxPosition));
            sliderPosition = newPosition;

            document.getElementById('puzzlePiece').style.left = newPosition + 'px';

            const sliderMaxPosition = document.getElementById('sliderHandle').parentElement.clientWidth - 48;
            const sliderHandlePosition = (newPosition / maxPosition) * sliderMaxPosition;
            document.getElementById('sliderHandle').style.left = sliderHandlePosition + 'px';

            const progress = (newPosition / maxPosition) * 100;
            document.getElementById('sliderProgress').style.width = progress + '%';
        }

        function stopDragging() {
            if (!isDragging) return;
            isDragging = false;

            const difference = Math.abs(sliderPosition - puzzlePosition);

            if (difference <= TOLERANCE) {
                isVerified = true;
                document.getElementById('verifiedMessage').classList.remove('hidden');
                document.getElementById('captchaError').classList.add('hidden');
                document.getElementById('submitCaptcha').disabled = false;
                document.getElementById('submitCaptcha').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('sliderText').innerHTML = '<i class="fas fa-check-circle mr-2"></i>Vérifié !';
                document.getElementById('puzzlePiece').style.left = puzzlePosition + 'px';
            } else {
                document.getElementById('captchaError').classList.remove('hidden');
                document.getElementById('puzzleContainer').classList.add('shake');

                setTimeout(() => {
                    sliderPosition = 0;
                    document.getElementById('puzzlePiece').style.left = '0px';
                    document.getElementById('sliderHandle').style.left = '0px';
                    document.getElementById('sliderProgress').style.width = '0%';
                    document.getElementById('captchaError').classList.add('hidden');
                    document.getElementById('puzzleContainer').classList.remove('shake');
                }, 1000);
            }
        }

        // Afficher la modal CAPTCHA
        function showCaptchaModal() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            // Validations
            if (!emailInput.value.trim()) {
                showError('Veuillez entrer votre adresse email.');
                emailInput.focus();
                return;
            }

            if (!validateEmail(emailInput.value)) {
                showError('Veuillez entrer une adresse email valide.');
                emailInput.focus();
                return;
            }

            if (!passwordInput.value.trim()) {
                showError('Veuillez entrer un nouveau mot de passe.');
                passwordInput.focus();
                return;
            }

            if (passwordInput.value.length < 8) {
                showError('Le mot de passe doit contenir au moins 8 caractères.');
                passwordInput.focus();
                return;
            }

            if (!passwordConfirmInput.value.trim()) {
                showError('Veuillez confirmer votre mot de passe.');
                passwordConfirmInput.focus();
                return;
            }

            if (passwordInput.value !== passwordConfirmInput.value) {
                showError('Les mots de passe ne correspondent pas.');
                passwordConfirmInput.focus();
                return;
            }

            // Afficher la modal
            document.getElementById('captchaModal').classList.remove('hidden');
            document.getElementById('captchaModal').classList.add('flex');

            setTimeout(() => {
                generateNewPuzzle();
            }, 100);
        }

        // Fermer la modal CAPTCHA
        function closeCaptchaModal() {
            document.getElementById('captchaModal').classList.add('hidden');
            document.getElementById('captchaModal').classList.remove('flex');
            generateNewPuzzle();
        }

        // Rafraîchir le CAPTCHA
        function refreshCaptcha() {
            generateNewPuzzle();
        }

        // Soumettre le formulaire après validation CAPTCHA
        document.getElementById('submitCaptcha').addEventListener('click', function() {
            if (isVerified) {
                closeCaptchaModal();
                document.getElementById('resetPasswordForm').submit();
            }
        });

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

            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
            if (/\d/.test(password)) strength += 1;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;

            const strengthColors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const strengthTexts = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
            const strengthTextColors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-blue-600', 'text-green-600'];

            strengthBar.className = 'h-2 rounded-full transition-all duration-300';
            strengthText.className = 'text-xs mt-1';

            strengthBar.classList.add(strengthColors[strength]);
            strengthText.classList.add(strengthTextColors[strength]);
            strengthBar.style.width = ((strength + 1) * 20) + '%';
            strengthText.textContent = strengthTexts[strength];
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function(e) {
            checkPasswordStrength(e.target.value);
        });

        // Validation email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Afficher les messages d'erreur
        function showError(message) {
            const errorDiv = document.getElementById('errorMessages');
            const errorText = document.getElementById('errorText');
            errorText.innerHTML = '<p class="text-red-700 text-sm">' + message + '</p>';
            errorDiv.classList.remove('hidden');

            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
        }

        // Empêcher la soumission directe du formulaire
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('captchaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCaptchaModal();
            }
        });

        // Support du clavier (Escape pour fermer)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('captchaModal');
                if (!modal.classList.contains('hidden')) {
                    closeCaptchaModal();
                }
            }
        });
    </script>
</body>
</html>
