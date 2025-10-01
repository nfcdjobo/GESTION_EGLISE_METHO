<?php $__env->startSection('title', 'Paramètres de l\'Église'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Paramètres de l'Église</h1>
        <p class="text-slate-500 mt-1">Configuration générale de l'église - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parametres.update')): ?>
                    <a href="<?php echo e(route('private.parametres.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier les paramètres
                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('private.parametres.show')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-eye mr-2"></i> Vue détaillée
                </a>

                <button type="button" onclick="gererProgrammes()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-calendar-alt mr-2"></i> Gérer les programmes
                </button>

                <a href="<?php echo e(route('private.parametresdons.index')); ?>"  class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-dove mr-2"></i> Paramètres Donations
                </a>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Identité de l'église -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-church text-blue-600 mr-2"></i>
                    Identité de l'Église
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <?php if($parametres->logo_url): ?>
                        <img src="<?php echo e($parametres->logo_url); ?>" alt="Logo" class="w-16 h-16 object-cover rounded-xl shadow-md">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-church text-white text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900"><?php echo e($parametres->nom_eglise); ?></h3>
                        <?php if($parametres->date_fondation): ?>
                            <p class="text-sm text-slate-500">Fondée en <?php echo e($parametres->date_fondation->format('Y')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($parametres->description_eglise): ?>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <p class="text-slate-700"><?php echo e(Str::limit($parametres->description_eglise, 200)); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($parametres->verset_biblique): ?>
                    <div class="p-4 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                        <p class="text-blue-800 italic">"<?php echo e($parametres->verset_biblique); ?>"</p>
                        <?php if($parametres->reference_verset): ?>
                            <p class="text-blue-600 text-sm mt-2 font-medium">- <?php echo e($parametres->reference_verset); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-address-book text-green-600 mr-2"></i>
                    Informations de Contact
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Téléphone principal</p>
                            <p class="font-medium text-slate-900"><?php echo e($parametres->telephone_1 ?: 'Non défini'); ?></p>
                        </div>
                    </div>

                    <?php if($parametres->telephone_2): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Téléphone secondaire</p>
                                <p class="font-medium text-slate-900"><?php echo e($parametres->telephone_2); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Email principal</p>
                        <p class="font-medium text-slate-900"><?php echo e($parametres->email_principal); ?></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Adresse</p>
                        <p class="font-medium text-slate-900"><?php echo e($parametres->getAdresseComplete()); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($parametres->nombre_membres ?: '0'); ?></p>
                    <p class="text-sm text-slate-500">Membres</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($parametres->date_fondation ? $parametres->date_fondation->diffInYears() : '0'); ?></p>
                    <p class="text-sm text-slate-500">Années d'existence</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-globe text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(count(array_filter([$parametres->facebook_url, $parametres->instagram_url, $parametres->youtube_url, $parametres->twitter_url]))); ?></p>
                    <p class="text-sm text-slate-500">Réseaux sociaux</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($parametres->getProgrammes() ? count($parametres->getProgrammes()) : '0'); ?></p>
                    <p class="text-sm text-slate-500">Programmes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision et Mission -->
    <?php if($parametres->mission_statement || $parametres->vision): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php if($parametres->mission_statement): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bullseye text-orange-600 mr-2"></i>
                            Notre Mission
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-700 leading-relaxed"><?php echo e($parametres->mission_statement); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($parametres->vision): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-indigo-600 mr-2"></i>
                            Notre Vision
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-700 leading-relaxed"><?php echo e($parametres->vision); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Réseaux sociaux et programmes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Réseaux sociaux -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                    Réseaux Sociaux
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <?php if($parametres->facebook_url): ?>
                        <a href="<?php echo e($parametres->facebook_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <i class="fab fa-facebook text-blue-600 text-xl"></i>
                            <span class="text-blue-800 font-medium">Facebook</span>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->instagram_url): ?>
                        <a href="<?php echo e($parametres->instagram_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors">
                            <i class="fab fa-instagram text-pink-600 text-xl"></i>
                            <span class="text-pink-800 font-medium">Instagram</span>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->youtube_url): ?>
                        <a href="<?php echo e($parametres->youtube_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fab fa-youtube text-red-600 text-xl"></i>
                            <span class="text-red-800 font-medium">YouTube</span>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->twitter_url): ?>
                        <a href="<?php echo e($parametres->twitter_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors">
                            <i class="fab fa-twitter text-sky-600 text-xl"></i>
                            <span class="text-sky-800 font-medium">Twitter</span>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if(!$parametres->facebook_url && !$parametres->instagram_url && !$parametres->youtube_url && !$parametres->twitter_url): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-share-alt text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucun réseau social configuré</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Programmes de l'église -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-alt text-amber-600 mr-2"></i>
                    Programmes de l'Église
                </h2>
            </div>
            <div class="p-6">
                <?php if($parametres->getProgrammesPublics() && count($parametres->getProgrammesPublics()) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $parametres->getProgrammesPublics(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <i class="<?php echo e($programme['icone'] ?? 'fas fa-calendar-day'); ?> text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($programme['titre'] ?? 'N/A'); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($programme['type_horaire'] ?? 'regulier'); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-slate-900"><?php echo e($programme['horaire_texte'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <button type="button" onclick="gererProgrammes()" class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-cog mr-2"></i> Gérer tous les programmes
                        </button>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-alt text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 mb-4">Aucun programme configuré</p>
                        <button type="button" onclick="gererProgrammes()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter des programmes
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function exportParametres() {
    // Créer un objet avec les données à exporter
    const data = {
        nom_eglise: "<?php echo e($parametres->nom_eglise); ?>",
        contact: {
            telephone_1: "<?php echo e($parametres->telephone_1); ?>",
            telephone_2: "<?php echo e($parametres->telephone_2); ?>",
            email_principal: "<?php echo e($parametres->email_principal); ?>",
            adresse: "<?php echo e($parametres->getAdresseComplete()); ?>"
        },
        programmes: <?php echo json_encode($parametres->getProgrammes(), 15, 512) ?>,
        // Ajouter d'autres données selon les besoins
    };

    // Créer et télécharger le fichier JSON
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'parametres_eglise.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function gererProgrammes() {
    // Rediriger vers la page de gestion des programmes ou ouvrir un modal
    // Pour l'instant, on peut rediriger vers la page d'édition
    window.location.href = "<?php echo e(route('private.parametres.edit')); ?>#programmes";
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/parametres/index.blade.php ENDPATH**/ ?>