<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme de l'Église</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="<?php echo e($AppParametres->logo ? Storage::url($AppParametres->logo) :  ''); ?>" type="image/png" />

    <style>
        .puzzle-piece {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }

        .slider-track {
            background: linear-gradient(to right, #e5e7eb 0%, #3b82f6 0%);
            background-size: 0% 100%;
            transition: background-size 0.3s ease;
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
            <a href="<?php echo e(route('public.accueil')); ?>" class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full mb-4 shadow-lg">
                <img class="aspect-square w-[80px] rounded-full object-cover ring-2 ring-blue-500" src="<?php echo e($AppParametres->logo ? Storage::url($AppParametres->logo) :  ''); ?>" alt="Logo église" />
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Méthodiste Côte d'Ivoire</h1>
            <p class="text-gray-600" id="subtitle">Connectez-vous à votre espace membre</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <!-- Navigation des sections -->
            <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
                <button onclick="showSection('login')" id="loginTab" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                </button>
                <button onclick="showSection('forgot')" id="forgotTab" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-key mr-2"></i>Récupération
                </button>
            </div>

            <!-- Messages d'erreur Laravel -->
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <div>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-red-700 text-sm"><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Messages d'erreur globaux -->
            <div id="errorMessages" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <div id="errorText"></div>
                </div>
            </div>

            <!-- Message de succès Laravel -->
            <?php if(session('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-green-700 text-sm"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Message d'information sur les mots de passe temporaires -->
            <?php if(session('reset_token')): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <div>
                            <p class="text-blue-700 text-sm font-medium">Lien de réinitialisation généré</p>
                            <p class="text-blue-600 text-sm">Vérifiez votre email  <code class="bg-blue-100 px-2 py-1 rounded"><?php echo e(session('email')); ?></code></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Message d'erreur spécifique Bcrypt -->
            <?php if(session('bcrypt_error')): ?>
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                        <div>
                            <p class="text-orange-700 text-sm font-medium">Problème de mot de passe détecté</p>
                            <p class="text-orange-600 text-sm">Votre mot de passe doit être réinitialisé pour des raisons de sécurité. Utilisez "Mot de passe oublié".</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Message de succès JS -->
            <div id="successMessages" class="hidden bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <p id="successText" class="text-green-700 text-sm"></p>
                </div>
            </div>

            <!-- SECTION CONNEXION -->
            <div id="loginSection" class="section">
                <!-- Options de connexion -->
                <div class="flex mb-4 bg-gray-50 rounded-lg p-1">
                    <button onclick="toggleLoginType('email')" id="emailOption" class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </button>
                    <button onclick="toggleLoginType('phone')" id="phoneOption" class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-phone mr-2"></i>Téléphone
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('security.login')); ?>" id="loginForm" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Champ Email/Téléphone -->
                    <div>
                        <label id="loginLabel" class="block text-sm font-medium text-gray-700 mb-2">
                            <i id="loginIcon" class="fas fa-envelope text-gray-400 mr-2"></i>
                            <span id="loginLabelText">Adresse email</span>
                        </label>
                        <input
                            id="loginInput"
                            type="email"
                            name="login"
                            value="<?php echo e(old('login')); ?>"
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="exemple@email.com"
                        >
                        <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>Mot de passe
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="••••••••"
                            >
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i id="passwordToggleIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                value="1"
                                <?php echo e(old('remember') ? 'checked' : ''); ?>

                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Se souvenir de moi
                            </label>
                        </div>

                        <button type="button" onclick="showSection('forgot')" class="text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                            Mot de passe oublié ?
                        </button>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="button" onclick="showCaptchaModal()" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </button>
                </form>
            </div>

            <!-- SECTION MOT DE PASSE OUBLIÉ -->
            <div id="forgotSection" class="section hidden">
                <div class="text-center mb-6">
                    <i class="fas fa-key text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Récupération de mot de passe</h3>
                    <p class="text-gray-600 text-sm">Entrez votre email ou téléphone pour recevoir un lien de récupération</p>
                </div>

                <!-- Options de récupération -->
                <div class="flex mb-4 bg-gray-50 rounded-lg p-1">
                    <button onclick="toggleRecoveryType('email')" id="emailRecovery" class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </button>
                    <button onclick="toggleRecoveryType('phone')" id="phoneRecovery" class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-phone mr-2"></i>SMS
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('security.request')); ?>" id="forgotForm" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Champ Email/Téléphone -->
                    <div>
                        <label id="recoveryLabel" class="block text-sm font-medium text-gray-700 mb-2">
                            <i id="recoveryIcon" class="fas fa-envelope text-gray-400 mr-2"></i>
                            <span id="recoveryLabelText">Adresse email</span>
                        </label>
                        <input
                            id="recoveryInput"
                            type="email"
                            name="recovery"
                            value="<?php echo e(old('recovery')); ?>"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['recovery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="exemple@email.com"
                        >
                        <?php $__errorArgs = ['recovery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Bouton d'envoi -->
                    <button type="button" onclick="showCaptchaModalForRecovery()" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-orange-700 hover:to-red-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer le lien de récupération
                    </button>
                </form>

                <!-- Retour à la connexion -->
                <div class="mt-6 text-center">
                    <button type="button" onclick="showSection('login')" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à la connexion
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <!-- Footer -->
<div class="mt-8 text-center">
    <p class="text-gray-500 text-sm">
        © <?php echo e(date('Y')); ?> <?php echo e($AppParametres->nom_eglise ?? "Église Méthodiste Côte d'Ivoire"); ?>. Tous droits réservés.
    </p>
    <p class="text-gray-400 text-xs mt-2">
        Développé par
        <a href="https://wa.me/+2250708948093" target="_blank" rel="noopener noreferrer"
           class="text-blue-500 hover:text-blue-600 font-medium transition duration-200">
            BarriServices
        </a>
    </p>
    <div class="mt-3 space-x-4">
        <a href="#" class="text-gray-400 hover:text-gray-600 text-sm transition duration-200">
            Conditions d'utilisation
        </a>
        <a href="#" class="text-gray-400 hover:text-gray-600 text-sm transition duration-200">
            Politique de confidentialité
        </a>
    </div>
</div>
    </div>

    <!-- MODAL CAPTCHA PUZZLE SLIDER -->
    <div id="captchaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 captcha-modal">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
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
            <div id="puzzleContainer" class="relative w-full h-48 bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 rounded-xl overflow-hidden mb-6 border-2 border-gray-200 shadow-inner">
                <!-- Motif de fond -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%">
                        <defs>
                            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="10" cy="10" r="1.5" fill="#3b82f6" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                <!-- Emplacement cible du puzzle (vide) -->
                <div id="puzzleTarget" class="absolute top-1/2 -translate-y-1/2 transition-all duration-300" style="width: 60px; height: 60px;">
                    <svg viewBox="0 0 60 60" class="w-full h-full opacity-30">
                        <defs>
                            <filter id="shadow">
                                <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.3"/>
                            </filter>
                        </defs>
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
                                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
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
                    <div id="sliderProgress" class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full transition-all duration-300" style="width: 0%;"></div>
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
        // Variables globales
        let currentSection = 'login';
        let currentLoginType = 'email';
        let currentRecoveryType = 'email';
        let currentFormType = 'login'; // 'login' ou 'recovery'

        // Variables CAPTCHA
        let isDragging = false;
        let sliderPosition = 0;
        let puzzlePosition = 0;
        let isVerified = false;
        const PUZZLE_SIZE = 60;
        const TOLERANCE = 15; // Tolérance en pixels

        // Initialisation du CAPTCHA au chargement
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

            // Mettre à jour la position de la pièce
            document.getElementById('puzzlePiece').style.left = newPosition + 'px';

            // Mettre à jour la poignée du slider
            const sliderMaxPosition = document.getElementById('sliderHandle').parentElement.clientWidth - 48;
            const sliderHandlePosition = (newPosition / maxPosition) * sliderMaxPosition;
            document.getElementById('sliderHandle').style.left = sliderHandlePosition + 'px';

            // Mettre à jour la progression
            const progress = (newPosition / maxPosition) * 100;
            document.getElementById('sliderProgress').style.width = progress + '%';
        }

        function stopDragging() {
            if (!isDragging) return;
            isDragging = false;

            const difference = Math.abs(sliderPosition - puzzlePosition);

            if (difference <= TOLERANCE) {
                // Succès
                isVerified = true;
                document.getElementById('verifiedMessage').classList.remove('hidden');
                document.getElementById('captchaError').classList.add('hidden');
                document.getElementById('submitCaptcha').disabled = false;
                document.getElementById('submitCaptcha').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('sliderText').innerHTML = '<i class="fas fa-check-circle mr-2"></i>Vérifié !';

                // Animer la pièce vers la position exacte
                document.getElementById('puzzlePiece').style.left = puzzlePosition + 'px';
            } else {
                // Échec
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

        // Afficher la modal CAPTCHA pour la connexion
        function showCaptchaModal() {
            currentFormType = 'login';

            // Valider le formulaire avant d'afficher le CAPTCHA
            const loginInput = document.getElementById('loginInput');
            const passwordInput = document.getElementById('password');

            if (!loginInput.value.trim()) {
                showError('Veuillez entrer votre email ou téléphone.');
                loginInput.focus();
                return;
            }

            if (!passwordInput.value.trim()) {
                showError('Veuillez entrer votre mot de passe.');
                passwordInput.focus();
                return;
            }

            // Validation email/téléphone
            if (currentLoginType === 'email' && !validateEmail(loginInput.value)) {
                showError('Veuillez entrer une adresse email valide.');
                loginInput.focus();
                return;
            }

            if (currentLoginType === 'phone' && !validatePhone(loginInput.value)) {
                showError('Veuillez entrer un numéro de téléphone valide.');
                loginInput.focus();
                return;
            }

            // Afficher la modal
            document.getElementById('captchaModal').classList.remove('hidden');
            document.getElementById('captchaModal').classList.add('flex');

            // Générer le puzzle après l'affichage pour que les dimensions soient correctes
            setTimeout(() => {
                generateNewPuzzle();
            }, 100);
        }

        // Afficher la modal CAPTCHA pour la récupération
        function showCaptchaModalForRecovery() {
            currentFormType = 'recovery';

            // Valider le formulaire avant d'afficher le CAPTCHA
            const recoveryInput = document.getElementById('recoveryInput');

            if (!recoveryInput.value.trim()) {
                showError('Veuillez entrer votre email ou téléphone.');
                recoveryInput.focus();
                return;
            }

            // Validation email/téléphone
            if (currentRecoveryType === 'email' && !validateEmail(recoveryInput.value)) {
                showError('Veuillez entrer une adresse email valide.');
                recoveryInput.focus();
                return;
            }

            if (currentRecoveryType === 'phone' && !validatePhone(recoveryInput.value)) {
                showError('Veuillez entrer un numéro de téléphone valide.');
                recoveryInput.focus();
                return;
            }

            // Afficher la modal
            document.getElementById('captchaModal').classList.remove('hidden');
            document.getElementById('captchaModal').classList.add('flex');

            // Générer le puzzle après l'affichage pour que les dimensions soient correctes
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

                // Soumettre le formulaire approprié
                if (currentFormType === 'login') {
                    document.getElementById('loginForm').submit();
                } else if (currentFormType === 'recovery') {
                    document.getElementById('forgotForm').submit();
                }
            }
        });

        // Fonction pour afficher/masquer les sections
        function showSection(section) {
            // Masquer toutes les sections
            document.querySelectorAll('.section').forEach(sec => {
                sec.classList.add('hidden');
            });

            // Réinitialiser les onglets
            document.querySelectorAll('[id$="Tab"]').forEach(tab => {
                tab.className = 'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800';
            });

            // Afficher la section demandée
            document.getElementById(section + 'Section').classList.remove('hidden');

            // Activer l'onglet correspondant
            const activeTab = document.getElementById(section + 'Tab');
            if (activeTab) {
                activeTab.className = 'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm';
            }

            // Mettre à jour le sous-titre
            const subtitles = {
                'login': 'Connectez-vous à votre espace membre',
                'forgot': 'Récupérez votre mot de passe'
            };
            document.getElementById('subtitle').textContent = subtitles[section];

            currentSection = section;
        }

        // Fonction pour basculer entre email et téléphone (connexion)
        function toggleLoginType(type) {
            currentLoginType = type;

            const emailBtn = document.getElementById('emailOption');
            const phoneBtn = document.getElementById('phoneOption');
            const input = document.getElementById('loginInput');
            const label = document.getElementById('loginLabelText');
            const icon = document.getElementById('loginIcon');

            // Réinitialiser les boutons
            emailBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800';
            phoneBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800';

            if (type === 'email') {
                emailBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm';
                input.type = 'email';
                input.placeholder = 'exemple@email.com';
                input.name = 'login';
                input.value = '';
                label.textContent = 'Adresse email';
                icon.className = 'fas fa-envelope text-gray-400 mr-2';
            } else {
                phoneBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm';
                input.type = 'tel';
                input.placeholder = '+225 07 12 34 56 78';
                input.name = 'login';
                input.value = '';
                label.textContent = 'Numéro de téléphone';
                icon.className = 'fas fa-phone text-gray-400 mr-2';
            }
        }

        // Fonction pour basculer entre email et téléphone (récupération)
        function toggleRecoveryType(type) {
            currentRecoveryType = type;

            const emailBtn = document.getElementById('emailRecovery');
            const phoneBtn = document.getElementById('phoneRecovery');
            const input = document.getElementById('recoveryInput');
            const label = document.getElementById('recoveryLabelText');
            const icon = document.getElementById('recoveryIcon');

            // Réinitialiser les boutons
            emailBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800';
            phoneBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-800';

            if (type === 'email') {
                emailBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm';
                input.type = 'email';
                input.placeholder = 'exemple@email.com';
                input.name = 'email';
                label.textContent = 'Adresse email';
                icon.className = 'fas fa-envelope text-gray-400 mr-2';
            } else {
                phoneBtn.className = 'flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200 bg-white text-blue-600 shadow-sm';
                input.type = 'tel';
                input.placeholder = '+225 07 12 34 56 78';
                input.name = 'phone';
                label.textContent = 'Numéro de téléphone';
                icon.className = 'fas fa-phone text-gray-400 mr-2';
            }
        }

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

        // Fonction pour afficher les messages d'erreur
        function showError(message) {
            const errorDiv = document.getElementById('errorMessages');
            const errorText = document.getElementById('errorText');
            errorText.innerHTML = '<p class="text-red-700 text-sm">' + message + '</p>';
            errorDiv.classList.remove('hidden');

            // Masquer après 5 secondes
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
        }

        // Fonction pour afficher les messages de succès
        function showSuccess(message) {
            const successDiv = document.getElementById('successMessages');
            const successText = document.getElementById('successText');
            successText.textContent = message;
            successDiv.classList.remove('hidden');

            // Masquer après 5 secondes
            setTimeout(() => {
                successDiv.classList.add('hidden');
            }, 5000);
        }

        // Validation email en temps réel
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Validation téléphone en temps réel
        function validatePhone(phone) {
            const re = /^[\+]?[0-9\s\-\(\)]{10,}$/;
            return re.test(phone);
        }

        // Gestionnaires d'événements pour la validation en temps réel
        document.addEventListener('input', function(e) {
            if (e.target.type === 'email') {
                if (e.target.value && !validateEmail(e.target.value)) {
                    e.target.classList.add('border-red-500');
                } else {
                    e.target.classList.remove('border-red-500');
                }
            }

            if (e.target.type === 'tel') {
                if (e.target.value && !validatePhone(e.target.value)) {
                    e.target.classList.add('border-red-500');
                } else {
                    e.target.classList.remove('border-red-500');
                }
            }
        });

        // Empêcher la soumission directe des formulaires
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });

        document.getElementById('forgotForm').addEventListener('submit', function(e) {
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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/auth/login.blade.php ENDPATH**/ ?>