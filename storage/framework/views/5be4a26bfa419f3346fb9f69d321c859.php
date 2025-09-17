<?php $__env->startSection('title', $title ?? 'Erreur'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 p-4">
    <div class="max-w-3xl w-full">
        <!-- Carte principale d'erreur -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <!-- En-tête avec dégradé dynamique -->
            <div class="bg-gradient-to-r <?php if($color === 'red'): ?> from-red-500 to-pink-500 <?php elseif($color === 'orange'): ?> from-orange-500 to-red-500 <?php elseif($color === 'yellow'): ?> from-yellow-500 to-orange-500 <?php elseif($color === 'blue'): ?> from-blue-500 to-cyan-500 <?php elseif($color === 'purple'): ?> from-purple-500 to-pink-500 <?php else: ?> from-slate-500 to-slate-600 <?php endif; ?> p-8 text-center relative overflow-hidden">
                <!-- Éléments décoratifs -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-4 left-4 w-8 h-8 border-2 border-white rounded-full"></div>
                    <div class="absolute bottom-6 right-8 w-6 h-6 border-2 border-white rounded-lg rotate-45"></div>
                    <div class="absolute top-1/2 right-4 w-4 h-4 bg-white rounded-full"></div>
                </div>

                <h1 class="text-4xl font-bold text-white mb-2"><?php echo e($statusCode); ?></h1>
                <p class="<?php if($color === 'red'): ?> text-red-100 <?php elseif($color === 'orange'): ?> text-orange-100 <?php elseif($color === 'yellow'): ?> text-yellow-100 <?php elseif($color === 'blue'): ?> text-blue-100 <?php elseif($color === 'purple'): ?> text-purple-100 <?php else: ?> text-slate-100 <?php endif; ?> text-xl"><?php echo e($title); ?></p>
            </div>

            <!-- Contenu principal -->
            <div class="p-8">
                <!-- Message d'erreur avec style moderne -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r <?php if($color === 'red'): ?> from-red-100 to-pink-100 text-red-800 <?php elseif($color === 'orange'): ?> from-orange-100 to-red-100 text-orange-800 <?php elseif($color === 'yellow'): ?> from-yellow-100 to-orange-100 text-yellow-800 <?php elseif($color === 'blue'): ?> from-blue-100 to-cyan-100 text-blue-800 <?php elseif($color === 'purple'): ?> from-purple-100 to-pink-100 text-purple-800 <?php else: ?> from-slate-100 to-gray-100 text-slate-800 <?php endif; ?> rounded-xl mb-6 shadow-md">
                        <i class="fas <?php echo e($icon); ?> mr-3 text-xl"></i>
                        <span class="font-semibold text-lg"><?php echo e($message); ?></span>
                    </div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-4">
                        <?php switch($statusCode):
                            case (400): ?>
                                Oops ! Requête incorrecte
                                <?php break; ?>
                            <?php case (401): ?>
                                Authentification requise
                                <?php break; ?>
                            <?php case (403): ?>
                                Oops ! Vous n'êtes pas autorisé
                                <?php break; ?>
                            <?php case (404): ?>
                                Oops ! Cette page n'existe pas
                                <?php break; ?>
                            <?php case (405): ?>
                                Méthode non autorisée
                                <?php break; ?>
                            <?php case (419): ?>
                                Session expirée
                                <?php break; ?>
                            <?php case (422): ?>
                                Données invalides
                                <?php break; ?>
                            <?php case (429): ?>
                                Trop de requêtes
                                <?php break; ?>
                            <?php case (500): ?>
                                Erreur interne du serveur
                                <?php break; ?>
                            <?php case (502): ?>
                                Passerelle défectueuse
                                <?php break; ?>
                            <?php case (503): ?>
                                Service indisponible
                                <?php break; ?>
                            <?php case (504): ?>
                                Timeout de la passerelle
                                <?php break; ?>
                            <?php default: ?>
                                Une erreur s'est produite
                        <?php endswitch; ?>
                    </h2>
                    <?php if(isset($description)): ?>
                        <p class="text-slate-600 text-lg leading-relaxed max-w-2xl mx-auto">
                            <?php echo e($description); ?>

                        </p>
                    <?php endif; ?>
                </div>

                <!-- Informations spécifiques selon le type d'erreur -->
                <?php if($statusCode === 419 && isset($refresh_required)): ?>
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 mb-8 border border-blue-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Action requise
                        </h3>
                        <p class="text-slate-600 mb-4">
                            Votre session a expiré pour des raisons de sécurité. Cliquez sur le bouton ci-dessous pour actualiser la page.
                        </p>
                        <button onclick="window.location.reload()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Actualiser la page
                        </button>
                    </div>
                <?php endif; ?>

                <?php if($statusCode === 429 && isset($retry_after)): ?>
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 mb-8 border border-orange-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-clock text-orange-500 mr-2"></i>
                            Limitation de débit
                        </h3>
                        <p class="text-slate-600 mb-4">
                            Vous avez effectué trop de requêtes. Veuillez patienter <?php echo e($retry_after); ?> secondes avant de réessayer.
                        </p>
                        <div id="countdown" class="text-2xl font-bold text-orange-600"></div>
                    </div>
                <?php endif; ?>

                <?php if($statusCode === 500 && isset($support_info)): ?>
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 mb-8 border border-red-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-life-ring text-red-500 mr-2"></i>
                            Aide et support
                        </h3>
                        <p class="text-slate-600 mb-4">
                            Nos équipes techniques ont été automatiquement notifiées de cette erreur.
                            Si le problème persiste, contactez-nous :
                        </p>
                        <a href="mailto:<?php echo e($support_info); ?>"
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Contacter le support
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Sections spéciales pour 404 -->
                <?php if($statusCode === 404): ?>
                    <?php if(isset($similar_pages) && count($similar_pages) > 0): ?>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 mb-8 border border-purple-200">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-lightbulb text-purple-500 mr-2"></i>
                                Peut-être cherchiez-vous :
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php $__currentLoopData = array_slice($similar_pages, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($page['url']); ?>"
                                       class="flex items-center p-3 bg-white/70 rounded-lg hover:bg-white/90 transition-all duration-200">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-link text-purple-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800"><?php echo e($page['path']); ?></p>
                                            <p class="text-sm text-slate-600">Similarité: <?php echo e($page['similarity']); ?>%</p>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($popular_pages) && count($popular_pages) > 0): ?>
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-8 border border-blue-200">
                            <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-3"></i>
                                Pages les plus visitées
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $popular_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($page['url']); ?>"
                                       class="flex items-center p-3 bg-white/70 rounded-lg hover:bg-white/90 transition-all duration-200 hover:shadow-md group">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                            <i class="<?php echo e($page['icon']); ?> text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800"><?php echo e($page['name']); ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Boutons d'action principaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('private.dashboard') ?? '/'); ?>"
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold text-lg rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-home mr-3"></i>
                        Retour à l'accueil
                    </a>

                    <button onclick="history.back()"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-semibold text-lg rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Page précédente
                    </button>

                   
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Animations personnalisées */
@keyframes bounce-slow {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée en cascade
    const cards = document.querySelectorAll('[class*="hover:shadow-lg"]');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Effet parallaxe léger pour les éléments décoratifs
    window.addEventListener('mousemove', function(e) {
        const decorativeElements = document.querySelectorAll('.absolute');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;

        decorativeElements.forEach((element, index) => {
            const speed = (index + 1) * 0.5;
            const x = (mouseX - 0.5) * speed;
            const y = (mouseY - 0.5) * speed;

            element.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Countdown pour l'erreur 429
    <?php if($statusCode === 429 && isset($retry_after)): ?>
    let retryAfter = <?php echo e($retry_after); ?>;
    const countdownElement = document.getElementById('countdown');

    const updateCountdown = () => {
        if (retryAfter > 0) {
            countdownElement.textContent = `${retryAfter}s`;
            retryAfter--;
            setTimeout(updateCountdown, 1000);
        } else {
            countdownElement.textContent = 'Vous pouvez maintenant réessayer';
            countdownElement.className += ' text-green-600';
        }
    };
    updateCountdown();
    <?php endif; ?>

    // Auto-refresh pour erreur 419 après 5 secondes
    <?php if($statusCode === 419 && isset($refresh_required)): ?>
    setTimeout(() => {
        const refreshButton = document.querySelector('button[onclick="window.location.reload()"]');
        if (refreshButton) {
            refreshButton.textContent = 'Actualisation automatique...';
            refreshButton.disabled = true;
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }, 5000);
    <?php endif; ?>
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/errors/generic.blade.php ENDPATH**/ ?>