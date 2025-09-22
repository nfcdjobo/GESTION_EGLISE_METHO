<?php $__env->startSection('title', 'Détails des Paramètres'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Détails des Paramètres</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.parametres.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-cogs mr-2"></i>
                        Paramètres
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Détails</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parametres.update')): ?>
                    <a href="<?php echo e(route('private.parametres.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                <?php endif; ?>

                <button type="button" onclick="exportParametres()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>

                <button type="button" onclick="printParametres()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>

                <a href="<?php echo e(route('private.parametres.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Identité de l'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-blue-600 mr-2"></i>
                        Identité de l'Église
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex items-center space-x-6">
                        <?php if($parametres->logo_url): ?>
                            <img src="<?php echo e($parametres->logo_url); ?>" alt="Logo" class="w-24 h-24 object-cover rounded-xl shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-church text-white text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900"><?php echo e($parametres->nom_eglise); ?></h3>
                            <?php if($parametres->date_fondation): ?>
                                <p class="text-slate-600 mt-1">Fondée le <?php echo e($parametres->date_fondation->format('d/m/Y')); ?></p>
                                <p class="text-sm text-slate-500"><?php echo e($parametres->date_fondation->diffInYears()); ?> années d'existence</p>
                            <?php endif; ?>
                            <?php if($parametres->nombre_membres): ?>
                                <p class="text-sm text-slate-500 mt-1"><?php echo e(number_format($parametres->nombre_membres)); ?> membres</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($parametres->description_eglise): ?>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <h4 class="font-semibold text-slate-900 mb-2">Description</h4>
                            <p class="text-slate-700 leading-relaxed"><?php echo e($parametres->description_eglise); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($parametres->histoire_eglise): ?>
                        <div class="p-4 bg-blue-50 rounded-xl">
                            <h4 class="font-semibold text-blue-900 mb-2">Histoire</h4>
                            <p class="text-blue-800 leading-relaxed"><?php echo e($parametres->histoire_eglise); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contenu spirituel -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bible text-amber-600 mr-2"></i>
                        Contenu Spirituel
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <?php if($parametres->verset_biblique): ?>
                        <div class="p-6 bg-amber-50 rounded-xl border-l-4 border-amber-500">
                            <h4 class="font-semibold text-amber-900 mb-3">Verset de l'Église</h4>
                            <blockquote class="text-amber-800 italic text-lg leading-relaxed">
                                "<?php echo e($parametres->verset_biblique); ?>"
                            </blockquote>
                            <?php if($parametres->reference_verset): ?>
                                <p class="text-amber-700 font-medium mt-3">- <?php echo e($parametres->reference_verset); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($parametres->mission_statement): ?>
                        <div class="p-4 bg-green-50 rounded-xl">
                            <h4 class="font-semibold text-green-900 mb-2 flex items-center">
                                <i class="fas fa-bullseye text-green-600 mr-2"></i>
                                Mission
                            </h4>
                            <p class="text-green-800 leading-relaxed"><?php echo e($parametres->mission_statement); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($parametres->vision): ?>
                        <div class="p-4 bg-purple-50 rounded-xl">
                            <h4 class="font-semibold text-purple-900 mb-2 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Vision
                            </h4>
                            <p class="text-purple-800 leading-relaxed"><?php echo e($parametres->vision); ?></p>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Téléphone principal</p>
                                    <p class="font-medium text-slate-900"><?php echo e($parametres->telephone_1); ?></p>
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

                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Email principal</p>
                                    <p class="font-medium text-slate-900"><?php echo e($parametres->email_principal); ?></p>
                                </div>
                            </div>

                            <?php if($parametres->email_secondaire): ?>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope-open text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Email secondaire</p>
                                        <p class="font-medium text-slate-900"><?php echo e($parametres->email_secondaire); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-900 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Adresse
                        </h4>
                        <p class="text-slate-700"><?php echo e($parametres->getAdresseComplete()); ?></p>
                    </div>
                </div>
            </div>

            <!-- Médias -->
            <?php if($parametres->images_hero_urls && count($parametres->images_hero_urls) > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-images text-purple-600 mr-2"></i>
                            Galerie d'Images
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <?php $__currentLoopData = $parametres->images_hero_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="group relative">
                                    <img src="<?php echo e($imageUrl); ?>" alt="Image de l'église" class="w-full h-32 object-cover rounded-xl shadow-md group-hover:shadow-lg transition-shadow">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition-opacity cursor-pointer" onclick="openImageModal('<?php echo e($imageUrl); ?>')">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-expand text-white text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Résumé -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                        Résumé
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Membres:</span>
                        <span class="text-lg font-bold text-slate-900"><?php echo e(number_format($parametres->nombre_membres ?: 0)); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Fondation:</span>
                        <span class="text-lg font-bold text-slate-900"><?php echo e($parametres->date_fondation ? $parametres->date_fondation->format('Y') : 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Années:</span>
                        <span class="text-lg font-bold text-slate-900"><?php echo e($parametres->date_fondation ? $parametres->date_fondation->diffInYears() : '0'); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Pays:</span>
                        <span class="text-lg font-bold text-slate-900"><?php echo e($parametres->pays); ?></span>
                    </div>
                </div>
            </div>

            <!-- Réseaux sociaux -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                        Réseaux Sociaux
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <?php if($parametres->facebook_url): ?>
                        <a href="<?php echo e($parametres->facebook_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <i class="fab fa-facebook text-blue-600 text-xl"></i>
                            <span class="text-blue-800 font-medium">Facebook</span>
                            <i class="fas fa-external-link-alt text-blue-500 text-sm ml-auto"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->instagram_url): ?>
                        <a href="<?php echo e($parametres->instagram_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors">
                            <i class="fab fa-instagram text-pink-600 text-xl"></i>
                            <span class="text-pink-800 font-medium">Instagram</span>
                            <i class="fas fa-external-link-alt text-pink-500 text-sm ml-auto"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->youtube_url): ?>
                        <a href="<?php echo e($parametres->youtube_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fab fa-youtube text-red-600 text-xl"></i>
                            <span class="text-red-800 font-medium">YouTube</span>
                            <i class="fas fa-external-link-alt text-red-500 text-sm ml-auto"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->twitter_url): ?>
                        <a href="<?php echo e($parametres->twitter_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors">
                            <i class="fab fa-twitter text-sky-600 text-xl"></i>
                            <span class="text-sky-800 font-medium">Twitter</span>
                            <i class="fas fa-external-link-alt text-sky-500 text-sm ml-auto"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($parametres->website_url): ?>
                        <a href="<?php echo e($parametres->website_url); ?>" target="_blank" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <i class="fas fa-globe text-gray-600 text-xl"></i>
                            <span class="text-gray-800 font-medium">Site Web</span>
                            <i class="fas fa-external-link-alt text-gray-500 text-sm ml-auto"></i>
                        </a>
                    <?php endif; ?>

                    <?php if(!$parametres->facebook_url && !$parametres->instagram_url && !$parametres->youtube_url && !$parametres->twitter_url && !$parametres->website_url): ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-share-alt text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500">Aucun réseau social configuré</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Horaires de culte -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-amber-600 mr-2"></i>
                        Horaires de Culte
                    </h2>
                </div>
                <div class="p-6">
                    <?php if($parametres->horaires_cultes && count($parametres->horaires_cultes) > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $parametres->horaires_cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar-day text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900"><?php echo e(ucfirst($horaire['jour'] ?? 'N/A')); ?></p>
                                            <p class="text-sm text-slate-500"><?php echo e($horaire['type'] ?? 'Culte'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-slate-900"><?php echo e($horaire['heure'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clock text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500">Aucun horaire configuré</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paramètres système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-slate-600 mr-2"></i>
                        Paramètres Système
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Devise</span>
                                <span class="text-sm font-bold text-slate-900"><?php echo e($parametres->devise); ?></span>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Langue</span>
                                <span class="text-sm font-bold text-slate-900">
                                    <?php switch($parametres->langue):
                                        case ('fr'): ?> Français <?php break; ?>
                                        <?php case ('en'): ?> English <?php break; ?>
                                        <?php case ('es'): ?> Español <?php break; ?>
                                        <?php default: ?> <?php echo e(ucfirst($parametres->langue)); ?>

                                    <?php endswitch; ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Fuseau horaire</span>
                                <span class="text-sm font-bold text-slate-900"><?php echo e($parametres->fuseau_horaire); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Créé le</span>
                            <span class="text-xs text-slate-600"><?php echo e($parametres->created_at->format('d/m/Y à H:i')); ?></span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Modifié le</span>
                            <span class="text-xs text-slate-600"><?php echo e($parametres->updated_at->format('d/m/Y à H:i')); ?></span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Statut</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Actif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides sidebar -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-tools text-orange-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parametres.update')): ?>
                        <a href="<?php echo e(route('private.parametres.edit')); ?>" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit text-blue-600"></i>
                            <span class="text-blue-800 font-medium">Modifier les paramètres</span>
                        </a>
                    <?php endif; ?>

                    <button type="button" onclick="shareParametres()" class="w-full flex items-center space-x-3 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                        <i class="fas fa-share text-green-600"></i>
                        <span class="text-green-800 font-medium">Partager</span>
                    </button>

                    <button type="button" onclick="copyToClipboard()" class="w-full flex items-center space-x-3 p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                        <i class="fas fa-copy text-purple-600"></i>
                        <span class="text-purple-800 font-medium">Copier les infos</span>
                    </button>

                    <button type="button" onclick="generateQRCode()" class="w-full flex items-center space-x-3 p-3 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-qrcode text-indigo-600"></i>
                        <span class="text-indigo-800 font-medium">QR Code</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les images en grand -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button type="button" onclick="closeImageModal()" class="absolute top-4 right-4 w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-colors z-10">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img id="modalImage" src="" alt="Image agrandie" class="max-w-full max-h-full object-contain rounded-xl">
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button type="button" onclick="downloadImage()" class="px-3 py-1 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-white text-sm transition-colors">
                <i class="fas fa-download mr-1"></i> Télécharger
            </button>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">QR Code - Informations Église</h3>
                <button type="button" onclick="closeQRModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6 text-center">
            <div id="qrCodeContainer" class="mb-4"></div>
            <p class="text-sm text-slate-600 mb-4">Scannez ce QR code pour accéder aux informations de l'église</p>
            <button type="button" onclick="downloadQRCode()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Télécharger QR Code
            </button>
        </div>
    </div>
</div>

<script>
function exportParametres() {
    const data = {
        nom_eglise: "<?php echo e($parametres->nom_eglise); ?>",
        date_fondation: "<?php echo e($parametres->date_fondation?->format('Y-m-d')); ?>",
        nombre_membres: <?php echo e($parametres->nombre_membres ?: 0); ?>,
        contact: {
            telephone_1: "<?php echo e($parametres->telephone_1); ?>",
            telephone_2: "<?php echo e($parametres->telephone_2); ?>",
            email_principal: "<?php echo e($parametres->email_principal); ?>",
            email_secondaire: "<?php echo e($parametres->email_secondaire); ?>",
            adresse: "<?php echo e($parametres->getAdresseComplete()); ?>"
        },
        spirituel: {
            verset_biblique: `<?php echo e($parametres->verset_biblique); ?>`,
            reference_verset: "<?php echo e($parametres->reference_verset); ?>",
            mission: `<?php echo e($parametres->mission_statement); ?>`,
            vision: `<?php echo e($parametres->vision); ?>`,
            histoire: `<?php echo e($parametres->histoire_eglise); ?>`
        },
        reseaux_sociaux: {
            facebook: "<?php echo e($parametres->facebook_url); ?>",
            instagram: "<?php echo e($parametres->instagram_url); ?>",
            youtube: "<?php echo e($parametres->youtube_url); ?>",
            twitter: "<?php echo e($parametres->twitter_url); ?>",
            website: "<?php echo e($parametres->website_url); ?>"
        },
        horaires_cultes: <?php echo json_encode($parametres->horaires_cultes, 15, 512) ?>,
        parametres_systeme: {
            devise: "<?php echo e($parametres->devise); ?>",
            langue: "<?php echo e($parametres->langue); ?>",
            fuseau_horaire: "<?php echo e($parametres->fuseau_horaire); ?>"
        },
        export_date: "<?php echo e(now()->format('Y-m-d H:i:s')); ?>"
    };

    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'parametres_eglise_<?php echo e(now()->format("Y-m-d")); ?>.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function printParametres() {
    window.print();
}

function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function downloadImage() {
    const img = document.getElementById('modalImage');
    const a = document.createElement('a');
    a.href = img.src;
    a.download = 'image_eglise_' + Date.now() + '.jpg';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function shareParametres() {
    if (navigator.share) {
        navigator.share({
            title: "<?php echo e($parametres->nom_eglise); ?>",
            text: "Découvrez les informations de <?php echo e($parametres->nom_eglise); ?>",
            url: window.location.href
        }).catch(console.error);
    } else {
        copyToClipboard();
    }
}

function copyToClipboard() {
    const text = `<?php echo e($parametres->nom_eglise); ?>

Contact: <?php echo e($parametres->telephone_1); ?> | <?php echo e($parametres->email_principal); ?>

Adresse: <?php echo e($parametres->getAdresseComplete()); ?>

<?php if($parametres->website_url): ?>Site web: <?php echo e($parametres->website_url); ?><?php endif; ?>`;

    navigator.clipboard.writeText(text).then(() => {
        showNotification('Informations copiées dans le presse-papier!', 'success');
    }).catch(() => {
        showNotification('Erreur lors de la copie', 'error');
    });
}

function generateQRCode() {
    const qrData = JSON.stringify({
        nom: "<?php echo e($parametres->nom_eglise); ?>",
        telephone: "<?php echo e($parametres->telephone_1); ?>",
        email: "<?php echo e($parametres->email_principal); ?>",
        adresse: "<?php echo e($parametres->getAdresseComplete()); ?>",
        <?php if($parametres->website_url): ?>
            website: "<?php echo e($parametres->website_url); ?>

        <?php endif; ?>
    });


    document.getElementById('qrCodeContainer').innerHTML = `
        <div class="w-48 h-48 bg-slate-100 rounded-xl flex items-center justify-center mx-auto">
            <div class="text-center">
                <i class="fas fa-qrcode text-4xl text-slate-400 mb-2"></i>
                <p class="text-sm text-slate-500">QR Code généré</p>
                <p class="text-xs text-slate-400">Intégration QR requise</p>
            </div>
        </div>`;

    document.getElementById('qrModal').classList.remove('hidden');
}

function closeQRModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

function downloadQRCode() {
    showNotification('Fonctionnalité à implémenter avec une bibliothèque QR Code', 'info');
}

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

// Fermer les modals en cliquant en dehors
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});

// Fermer les modals avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeQRModal();
    }
});

// Styles d'impression
const printStyles = `
    @media print {
        .no-print { display: none !important; }
        .print-break { page-break-before: always; }
        body { font-size: 12pt; }
        h1 { font-size: 18pt; }
        h2 { font-size: 16pt; }
        h3 { font-size: 14pt; }
        .bg-gradient-to-r { background: none !important; color: #000 !important; }
        .shadow-lg, .shadow-md { box-shadow: none !important; }
        .rounded-2xl, .rounded-xl { border-radius: 8px !important; }
        .bg-white\/80 { background: white !important; }
        .border-white\/20 { border-color: #e5e7eb !important; }
    }
`;

// Ajouter les styles d'impression
const styleSheet = document.createElement('style');
styleSheet.textContent = printStyles;
document.head.appendChild(styleSheet);
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/parametres/show.blade.php ENDPATH**/ ?>