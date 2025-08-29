<?php $__env->startSection('title', 'Statistiques Multimédia'); ?>



<?php $__env->startSection('content'); ?>

<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques Multimédia</h1>
                    <p class="text-slate-500 mt-1">Analyse détaillée de votre médiathèque - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(route('private.multimedia.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la galerie
                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_media')): ?>
                        <button type="button" onclick="exportStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                        <button type="button" onclick="refreshStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-sync mr-2"></i> Actualiser
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        <!-- Total médias -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-photo-video text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($generales['total_medias'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total médias</p>
                </div>
            </div>
        </div>

        <!-- Total taille -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hdd text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(formatBytes($generales['total_taille'] ?? 0)); ?></p>
                    <p class="text-sm text-slate-500">Espace utilisé</p>
                </div>
            </div>
        </div>

        <!-- Total vues -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($generales['total_vues'] ?? 0)); ?></p>
                    <p class="text-sm text-slate-500">Total vues</p>
                </div>
            </div>
        </div>

        <!-- Médias à la une -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($generales['medias_featured'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">À la une</p>
                </div>
            </div>
        </div>

        <!-- En attente modération -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($generales['en_attente_moderation'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">En modération</p>
                </div>
            </div>
        </div>

        <!-- Moyenne vues -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">
                        <?php echo e($generales['total_medias'] > 0 ? round(($generales['total_vues'] ?? 0) / $generales['total_medias'], 1) : 0); ?>

                    </p>
                    <p class="text-sm text-slate-500">Vues moyennes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__currentLoopData = $statistiques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $total = $statistiques->sum('nombre_total');
                            $percentage = $total > 0 ? round(($stat->nombre_total / $total) * 100, 1) : 0;
                            $colors = [
                                'image' => 'bg-green-500',
                                'video' => 'bg-red-500',
                                'audio' => 'bg-purple-500',
                                'document' => 'bg-blue-500',
                                'presentation' => 'bg-orange-500',
                                'archive' => 'bg-gray-500'
                            ];
                            $color = $colors[$stat->type_media] ?? 'bg-slate-500';
                        ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 <?php echo e($color); ?> rounded-full"></div>
                                <span class="font-medium text-slate-900 capitalize"><?php echo e(str_replace('_', ' ', $stat->type_media)); ?></span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-slate-900"><?php echo e($stat->nombre_total); ?></div>
                                    <div class="text-xs text-slate-500"><?php echo e($percentage); ?>%</div>
                                </div>
                                <div class="w-20 bg-slate-200 rounded-full h-2">
                                    <div class="<?php echo e($color); ?> h-2 rounded-full transition-all duration-1000" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Canvas pour graphique -->
                <div class="mt-6">
                    <canvas id="typeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par catégorie -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                    Top Catégories
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__currentLoopData = $statistiques->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $maxTotal = $statistiques->max('nombre_total');
                            $percentage = $maxTotal > 0 ? round(($stat->nombre_total / $maxTotal) * 100, 1) : 0;
                            $colors = [
                                'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-red-500',
                                'bg-yellow-500', 'bg-indigo-500', 'bg-pink-500', 'bg-teal-500'
                            ];
                            $color = $colors[$index % count($colors)];
                        ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <div class="w-3 h-3 <?php echo e($color); ?> rounded-full flex-shrink-0"></div>
                                <span class="font-medium text-slate-900 truncate capitalize">
                                    <?php echo e(str_replace('_', ' ', $stat->categorie)); ?>

                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-semibold text-slate-900"><?php echo e($stat->nombre_total); ?></span>
                                <div class="w-16 bg-slate-200 rounded-full h-2">
                                    <div class="<?php echo e($color); ?> h-2 rounded-full transition-all duration-1000" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Canvas pour graphique -->
                <div class="mt-6">
                    <canvas id="categoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Médias populaires et récents -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Médias les plus vus -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-fire text-orange-600 mr-2"></i>
                    Médias Populaires
                </h2>
            </div>
            <div class="p-6">
                <?php if($generales['plus_vues']): ?>
                    <div class="space-y-4">
                        <?php
                            // Simuler une liste des médias les plus vus
                            $popularMedia = collect([
                                $generales['plus_vues'],
                                ...$generales['plus_recents']->take(4)
                            ])->sortByDesc('nombre_vues')->take(5);
                        ?>
                        <?php $__currentLoopData = $popularMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-4 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-bold rounded-full">
                                        <?php echo e($index + 1); ?>

                                    </span>
                                </div>
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg overflow-hidden">
                                    <?php if($media->type_media == 'image' && $media->url_miniature): ?>
                                        <img src="<?php echo e($media->url_miniature); ?>" alt="<?php echo e($media->titre); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <?php if($media->type_media == 'video'): ?>
                                                <i class="fas fa-video text-red-500"></i>
                                            <?php elseif($media->type_media == 'audio'): ?>
                                                <i class="fas fa-music text-purple-500"></i>
                                            <?php else: ?>
                                                <i class="fas fa-file text-blue-500"></i>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-slate-900 truncate"><?php echo e($media->titre); ?></h3>
                                    <div class="flex items-center space-x-4 text-sm text-slate-500 mt-1">
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            <?php echo e(number_format($media->nombre_vues)); ?>

                                        </span>
                                        <span class="capitalize"><?php echo e($media->type_media); ?></span>
                                        <span><?php echo e($media->created_at->format('d/m/Y')); ?></span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="<?php echo e(route('private.multimedia.show', $media)); ?>" class="text-blue-600 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée de popularité disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Médias récents -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-clock text-green-600 mr-2"></i>
                    Ajouts Récents
                </h2>
            </div>
            <div class="p-6">
                <?php if($generales['plus_recents'] && $generales['plus_recents']->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $generales['plus_recents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-4 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg overflow-hidden">
                                    <?php if($media->type_media == 'image' && $media->url_miniature): ?>
                                        <img src="<?php echo e($media->url_miniature); ?>" alt="<?php echo e($media->titre); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <?php if($media->type_media == 'video'): ?>
                                                <i class="fas fa-video text-red-500"></i>
                                            <?php elseif($media->type_media == 'audio'): ?>
                                                <i class="fas fa-music text-purple-500"></i>
                                            <?php else: ?>
                                                <i class="fas fa-file text-blue-500"></i>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-slate-900 truncate"><?php echo e($media->titre); ?></h3>
                                    <div class="flex items-center space-x-4 text-sm text-slate-500 mt-1">
                                        <span class="capitalize"><?php echo e($media->type_media); ?></span>
                                        <span><?php echo e($media->created_at->diffForHumans()); ?></span>
                                        <span class="px-2 py-0.5 bg-<?php echo e($media->statut_moderation == 'approuve' ? 'green' : 'orange'); ?>-100 text-<?php echo e($media->statut_moderation == 'approuve' ? 'green' : 'orange'); ?>-800 text-xs rounded-full">
                                            <?php echo e(ucfirst($media->statut_moderation)); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="<?php echo e(route('private.multimedia.show', $media)); ?>" class="text-blue-600 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucun média récent trouvé</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   

    <!-- Métriques de modération -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-gavel text-purple-600 mr-2"></i>
                    Modération et Qualité
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Temps de modération moyen -->
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-stopwatch text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">2.3h</div>
                        <div class="text-sm text-slate-600">Temps de modération moyen</div>
                    </div>

                    <!-- Taux d'approbation -->
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-slate-800"><?php echo e($generales['total_medias'] > 0 ? round((($generales['total_medias'] - $generales['en_attente_moderation']) / $generales['total_medias']) * 100, 1) : 0); ?>%</div>
                        <div class="text-sm text-slate-600">Taux d'approbation</div>
                    </div>

                    <!-- Note qualité moyenne -->
                    <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-200">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">8.5/10</div>
                        <div class="text-sm text-slate-600">Note qualité moyenne</div>
                    </div>
                </div>

                <!-- Répartition des statuts -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 mb-4">Répartition des Statuts</h3>
                    <?php
                        $totalMod = $generales['total_medias'];
                        $approves = $totalMod - $generales['en_attente_moderation'];
                        $moderation_stats = [
                            ['status' => 'Approuvés', 'count' => $approves, 'color' => 'bg-green-500'],
                            ['status' => 'En attente', 'count' => $generales['en_attente_moderation'], 'color' => 'bg-orange-500'],
                            ['status' => 'Rejetés', 'count' => 0, 'color' => 'bg-red-500']
                        ];
                    ?>

                    <?php $__currentLoopData = $moderation_stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $percentage = $totalMod > 0 ? round(($stat['count'] / $totalMod) * 100, 1) : 0; ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 <?php echo e($stat['color']); ?> rounded-full"></div>
                                <span class="font-medium text-slate-900"><?php echo e($stat['status']); ?></span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-slate-900"><?php echo e($stat['count']); ?></div>
                                    <div class="text-xs text-slate-500"><?php echo e($percentage); ?>%</div>
                                </div>
                                <div class="w-20 bg-slate-200 rounded-full h-2">
                                    <div class="<?php echo e($stat['color']); ?> h-2 rounded-full transition-all duration-1000" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Actions d'administration -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_media')): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-cogs text-gray-600 mr-2"></i>
                    Maintenance et Optimisation
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <button type="button" onclick="cleanupOrphanFiles()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-red-600 to-pink-600 text-white font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-broom mr-2"></i>
                        Nettoyer les fichiers orphelins
                    </button>

                    <button type="button" onclick="optimizeStorage()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-compress-arrows-alt mr-2"></i>
                        Optimiser le stockage
                    </button>

                    <button type="button" onclick="regenerateThumbnails()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-image mr-2"></i>
                        Régénérer les miniatures
                    </button>

                    <button type="button" onclick="recalculateHashes()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-fingerprint mr-2"></i>
                        Recalculer les hashs
                    </button>

                    <button type="button" onclick="migrateStorage()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Migration stockage
                    </button>

                    <button type="button" onclick="generateReport()"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-medium rounded-xl hover:from-teal-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-export mr-2"></i>
                        Rapport détaillé
                    </button>
                </div>

                <!-- Alertes système -->
                <div class="mt-8 space-y-3">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-yellow-900">Espace de stockage</h4>
                                <p class="text-sm text-yellow-800"><?php echo e(formatBytes($generales['total_taille'] ?? 0)); ?> utilisés sur 100 GB disponibles</p>
                            </div>
                        </div>
                    </div>

                    <?php if(($generales['en_attente_moderation'] ?? 0) > 10): ?>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-clock text-orange-600 mt-0.5 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-orange-900">File de modération</h4>
                                    <p class="text-sm text-orange-800"><?php echo e($generales['en_attente_moderation']); ?> médias en attente de modération</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des couleurs
    const colors = {
        primary: '#3B82F6',
        secondary: '#8B5CF6',
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
        info: '#06B6D4'
    };

    // Données pour les graphiques
    const statistiques = <?php echo json_encode($statistiques, 15, 512) ?>;
    const typeData = {};
    const categoryData = {};

    // Préparation des données
    statistiques.forEach(item => {
        if (!typeData[item.type_media]) {
            typeData[item.type_media] = 0;
        }
        typeData[item.type_media] += item.nombre_total;

        if (!categoryData[item.categorie]) {
            categoryData[item.categorie] = 0;
        }
        categoryData[item.categorie] += item.nombre_total;
    });

    // Graphique par type
    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(typeData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
                datasets: [{
                    data: Object.values(typeData),
                    backgroundColor: [
                        colors.success,
                        colors.danger,
                        colors.secondary,
                        colors.primary,
                        colors.warning,
                        '#6B7280'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Graphique par catégorie
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        const topCategories = Object.entries(categoryData)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 8);

        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: topCategories.map(([key]) => key.replace('_', ' ').charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')),
                datasets: [{
                    data: topCategories.map(([,value]) => value),
                    backgroundColor: [
                        colors.primary, colors.success, colors.secondary, colors.danger,
                        colors.warning, colors.info, '#EC4899', '#14B8A6'
                    ],
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F1F5F9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique d'évolution
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        // Données simulées pour l'évolution
        const evolutionData = generateEvolutionData(30);

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: evolutionData.labels,
                datasets: [{
                    label: 'Uploads',
                    data: evolutionData.data,
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F1F5F9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
});

// Génération de données d'évolution simulées
function generateEvolutionData(days) {
    const labels = [];
    const data = [];
    const now = new Date();

    for (let i = days - 1; i >= 0; i--) {
        const date = new Date(now);
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('fr-FR', { month: 'short', day: 'numeric' }));
        data.push(Math.floor(Math.random() * 10) + 1); // Données simulées
    }

    return { labels, data };
}

// Fonctions d'administration
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_media')): ?>
function cleanupOrphanFiles() {
    if (confirm('Êtes-vous sûr de vouloir nettoyer les fichiers orphelins ? Cette action est irréversible.')) {
        fetch("<?php echo e(route('private.multimedia.admin.storage.cleanup')); ?>", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Nettoyage effectué avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors du nettoyage', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    }
}

function optimizeStorage() {
    if (confirm('Optimiser le stockage des médias ? Cette opération peut prendre quelques minutes.')) {
        fetch('<?php echo e(route("private.multimedia.admin.storage.optimize")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Optimisation effectuée avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors de l\'optimisation', 'error');
            }
        });
    }
}

function regenerateThumbnails() {
    if (confirm('Régénérer toutes les miniatures ? Cette opération peut prendre du temps.')) {
        fetch('<?php echo e(route("private.multimedia.maintenance.regenerate.thumbnails")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Miniatures régénérées avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors de la régénération', 'error');
            }
        });
    }
}

function recalculateHashes() {
    if (confirm('Recalculer tous les hashs des fichiers ?')) {
        fetch('<?php echo e(route("private.multimedia.maintenance.recalculate.hashes")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Hashs recalculés avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors du recalcul', 'error');
            }
        });
    }
}

function migrateStorage() {
    if (confirm('Lancer la migration de stockage ? Cette opération est irréversible.')) {
        fetch('<?php echo e(route("private.multimedia.maintenance.migrate.storage")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Migration effectuée avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors de la migration', 'error');
            }
        });
    }
}

function generateReport() {
    window.open('<?php echo e(route("private.multimedia.statistiques")); ?>?export=pdf', '_blank');
}
<?php endif; ?>

function exportStatistics() {
    window.open('<?php echo e(route("private.multimedia.statistiques")); ?>?export=csv', '_blank');
}

function refreshStatistics() {
    location.reload();
}

// Système de notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 translate-x-full`;

    const bgColor = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    }[type] || 'bg-blue-500';

    const icon = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';

    notification.className += ` ${bgColor}`;
    notification.innerHTML = `<i class="${icon} mr-2"></i>${message}`;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto-suppression
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}


</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/multimedia/statistiques.blade.php ENDPATH**/ ?>