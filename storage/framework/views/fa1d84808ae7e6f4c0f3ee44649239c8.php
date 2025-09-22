<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme de l'Église</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link rel="icon" href="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" type="image/png" />
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo et Titre -->
        <div class="text-center mb-8">
            <a href="<?php echo e(route('public.accueil')); ?>" class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full mb-4 shadow-lg">
                <img class="aspect-square w-[80px] rounded-full object-cover ring-2 ring-blue-500" src="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" alt="Logo église" />
                
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
                            <p class="text-blue-600 text-sm">Vérifiez votre email ou utilisez ce token : <code class="bg-blue-100 px-2 py-1 rounded"><?php echo e(session('reset_token')); ?></code></p>
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

                <form method="POST" action="<?php echo e(route('security.login')); ?>" class="space-y-6">
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
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
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

                <form method="POST" action="<?php echo e(route('security.request')); ?>" class="space-y-6">
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
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-orange-700 hover:to-red-700 transform hover:scale-[1.02] transition duration-200 shadow-lg">
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
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                © 2024 Plateforme de l'Église. Tous droits réservés.
            </p>
            <div class="mt-2 space-x-4">
                <a href="#" class="text-gray-400 hover:text-gray-600 text-sm transition duration-200">
                    Conditions d'utilisation
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-600 text-sm transition duration-200">
                    Politique de confidentialité
                </a>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentSection = 'login';
        let currentLoginType = 'email';
        let currentRecoveryType = 'email';


        // Méthode 1 : Propriété checked
        const rememberCheckbox = document.getElementById('remember');
        const isChecked = rememberCheckbox.checked; // true ou false

        // Méthode 2 : Avec querySelector
        const isChecked2 = document.querySelector('#remember').checked;

        // Méthode 3 : Event listener pour détecter les changements
        document.getElementById('remember').addEventListener('change', function(e) {
            console.log('Checkbox state:', e.target.checked); // true ou false
        });

        // Méthode 4 : Fonction utilitaire
        function getRememberValue() {
            return document.getElementById('remember').checked;
        }

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
                // 'register': 'Créez votre compte membre',
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
            // input.name = 'email';
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

        // Validation des formulaires
        document.addEventListener('submit', function(e) {
            const form = e.target;

            if (currentSection === 'register') {
                const password = document.getElementById('registerPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    showError('Les mots de passe ne correspondent pas.');
                    return;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    showError('Le mot de passe doit contenir au moins 8 caractères.');
                    return;
                }
            }

            // Validation générale pour tous les formulaires
            const requiredFields = form.querySelectorAll('[required]');
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    e.preventDefault();
                    showError('Veuillez remplir tous les champs requis.');
                    return;
                }
            }
        });

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
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/auth/login.blade.php ENDPATH**/ ?>