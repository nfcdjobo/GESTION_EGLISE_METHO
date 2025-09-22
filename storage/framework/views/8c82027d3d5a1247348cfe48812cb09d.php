<?php $__env->startSection('title', 'Détails Moisson - ' . $moisson->theme); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="<?php echo e(route('private.moissons.index')); ?>" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium"><?php echo e(Str::limit($moisson->theme, 30)); ?></span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <?php echo e($moisson->theme); ?>

                    </h1>
                    <p class="text-slate-500 mt-1 flex items-center gap-4">
                        <span><i class="fas fa-calendar mr-1"></i><?php echo e($moisson->date->format('d F Y')); ?></span>
                        <span><i class="fas fa-church mr-1"></i><?php echo e($moisson->culte->titre ?? 'N/A'); ?></span>
                        <span><i class="fas fa-user mr-1"></i><?php echo e($moisson->createur->nom_complet ?? 'N/A'); ?></span>
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moissons.update')): ?>
                        <a href="<?php echo e(route('private.moissons.edit', $moisson)); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moissons.recalculate')): ?>
                        <button onclick="recalculerTotaux()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md">
                            <i class="fas fa-calculator mr-2"></i> Recalculer
                        </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moissons.export')): ?>
                        <button onclick="exporterMoissons()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Indicateurs de performance -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Objectif cible</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($moisson->cible, 0, ',', ' ')); ?></p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bullseye text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Montant collecté</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($moisson->montant_solde, 0, ',', ' ')); ?></p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">
                            <?php if($moisson->reste > 0): ?> Reste à collecter <?php else: ?> Supplément <?php endif; ?>
                        </p>
                        <p class="text-2xl font-bold <?php echo e($moisson->reste > 0 ? 'text-orange-600' : 'text-green-600'); ?>">
                            <?php echo e(number_format($moisson->reste > 0 ? $moisson->reste : $moisson->montant_supplementaire, 0, ',', ' ')); ?>

                        </p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br <?php echo e($moisson->reste > 0 ? 'from-orange-500 to-red-500' : 'from-purple-500 to-pink-500'); ?> rounded-xl flex items-center justify-center">
                        <i class="fas <?php echo e($moisson->reste > 0 ? 'fa-exclamation-triangle' : 'fa-plus-circle'); ?> text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Performance</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($moisson->pourcentage_realise, 1)); ?>%</p>
                        <p class="text-xs <?php echo e($moisson->pourcentage_realise >= 100 ? 'text-green-600' : ($moisson->pourcentage_realise >= 70 ? 'text-blue-600' : 'text-orange-600')); ?>">
                            <?php echo e($moisson->statut_progression); ?>

                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 bg-slate-200 rounded-full h-2">
                            <div class="h-2 rounded-full <?php echo e($moisson->pourcentage_realise >= 100 ? 'bg-green-500' : ($moisson->pourcentage_realise >= 70 ? 'bg-blue-500' : ($moisson->pourcentage_realise >= 50 ? 'bg-yellow-500' : 'bg-red-500'))); ?>"
                                 style="width: <?php echo e(min($moisson->pourcentage_realise, 100)); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Passages bibliques -->
        <?php if($moisson->passages_bibliques && count($moisson->passages_bibliques) > 0): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center mb-4">
                        <i class="fas fa-book text-blue-600 mr-2"></i>
                        Passages Bibliques
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $moisson->passages_bibliques_formatted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-bookmark mr-1 text-xs"></i>
                                <?php echo e($passage); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Onglets des composants -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="border-b border-slate-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="switchTab('passages')" id="tab-passages"
                        class="tab-button border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-sm font-medium">
                        <i class="fas fa-users mr-2"></i>
                        Passages (<?php echo e($moisson->passageMoissons->count()); ?>)
                    </button>
                    <button onclick="switchTab('ventes')" id="tab-ventes"
                        class="tab-button border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 py-4 px-1 text-sm font-medium">
                        <i class="fas fa-store mr-2"></i>
                        Ventes (<?php echo e($moisson->venteMoissons->count()); ?>)
                    </button>
                    <button onclick="switchTab('engagements')" id="tab-engagements"
                        class="tab-button border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 py-4 px-1 text-sm font-medium">
                        <i class="fas fa-handshake mr-2"></i>
                        Engagements (<?php echo e($moisson->engagementMoissons->count()); ?>)
                    </button>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="p-6">
                <!-- Onglet Passages -->
                <div id="content-passages" class="tab-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800">Passages de collecte</h3>

                        <div class="flex space-x-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('passages.read')): ?>
                                <a href="<?php echo e(route('private.moissons.passages.index', $moisson)); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-list mr-2"></i> Passages
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('passages.create')): ?>
                                <button onclick="ajouterPassage()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Ajouter passage
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>


                    <?php if($moisson->passageMoissons->count() > 0): ?>
                        <div class="grid gap-4">
                            <?php $__currentLoopData = $moisson->passageMoissons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-slate-200 rounded-xl p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($passage->categorie_libelle); ?>

                                            </span>
                                            <?php if($passage->classe): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <?php echo e($passage->classe->nom); ?>

                                                </span>
                                            <?php endif; ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($passage->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($passage->status ? 'Validé' : 'En attente'); ?>

                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('passages.read')): ?>
                                                <button onclick="detailPassage('<?php echo e($passage->id); ?>')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                     title="Voir détail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('passages.update')): ?>
                                                <button onclick="modifierPassage('<?php echo e($passage->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('passages.delete')): ?>
                                                <button onclick="supprimerPassage('<?php echo e($passage->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-xs text-slate-500">Cible</p>
                                            <p class="font-medium"><?php echo e(number_format($passage->cible, 0, ',', ' ')); ?> FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Collecté</p>
                                            <p class="font-medium text-green-600"><?php echo e(number_format($passage->montant_solde, 0, ',', ' ')); ?> FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Reste/Supplément</p>
                                            <p class="font-medium <?php echo e($passage->reste > 0 ? 'text-orange-600' : 'text-green-600'); ?>">
                                                <?php echo e(number_format($passage->reste > 0 ? $passage->reste : $passage->montant_supplementaire, 0, ',', ' ')); ?> FCFA
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Performance</p>
                                            <p class="font-medium"><?php echo e(number_format($passage->pourcentage_realise, 1)); ?>%</p>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-1">
                                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo e(min($passage->pourcentage_realise, 100)); ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-users text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucun passage de collecte enregistré</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Onglet Ventes -->
                <div id="content-ventes" class="tab-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800">Ventes de moisson</h3>


                        <div class="flex space-x-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.read')): ?>
                                <a href="<?php echo e(route('private.moissons.ventes.index', $moisson)); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-list mr-2"></i> Ventes
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.create')): ?>
                                <button onclick="ajouterVente()"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Ajouter vente
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($moisson->venteMoissons->count() > 0): ?>
                        <div class="grid gap-4">
                            <?php $__currentLoopData = $moisson->venteMoissons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-slate-200 rounded-xl p-4 hover:border-green-300 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?php echo e($vente->categorie_libelle); ?>

                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($vente->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($vente->status ? 'Validé' : 'En attente'); ?>

                                            </span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.read')): ?>
                                                <button onclick="detailVente('<?php echo e($vente->id); ?>')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                     title="Voir détail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.update')): ?>
                                                <button onclick="modifierVente('<?php echo e($vente->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.delete')): ?>
                                                <button onclick="supprimerVente('<?php echo e($vente->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if($vente->description): ?>
                                        <p class="text-sm text-slate-600 mb-3"><?php echo e($vente->description); ?></p>
                                    <?php endif; ?>

                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        <div>
                                            <p class="text-xs text-slate-500">Cible</p>
                                            <p class="font-medium"><?php echo e(number_format($vente->cible, 0, ',', ' ')); ?> FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Collecté</p>
                                            <p class="font-medium text-green-600"><?php echo e(number_format($vente->montant_solde, 0, ',', ' ')); ?> FCFA</p>
                                        </div>

                                        <div>
                                            <p class="text-xs text-slate-500">Performance</p>
                                            <p class="font-medium"><?php echo e(number_format($vente->pourcentage_realise, 1)); ?>%</p>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-1">
                                                <div class="bg-green-600 h-1.5 rounded-full" style="width: <?php echo e(min($vente->pourcentage_realise, 100)); ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-store text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucune vente enregistrée</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Onglet Engagements -->
                <div id="content-engagements" class="tab-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800">Engagements de moisson</h3>


                        <div class="flex space-x-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ventes.read')): ?>
                                <a href="<?php echo e(route('private.moissons.engagements.index', $moisson)); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-list mr-2"></i> Engagements
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('engagements.create')): ?>
                            <button onclick="ajouterEngagement()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter engagement
                            </button>
                        <?php endif; ?>
                        </div>
                    </div>

                    <?php if($moisson->engagementMoissons->count() > 0): ?>
                        <div class="grid gap-4">
                            <?php $__currentLoopData = $moisson->engagementMoissons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engagement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-slate-200 rounded-xl p-4 hover:border-purple-300 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?php echo e($engagement->categorie_libelle); ?>

                                            </span>
                                            <?php if($engagement->est_en_retard): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    En retard (<?php echo e($engagement->jours_retard); ?>j)
                                                </span>
                                            <?php endif; ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($engagement->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($engagement->status ? 'Validé' : 'En attente'); ?>

                                            </span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('engagements.read')): ?>
                                                <button onclick="detailEngagement('<?php echo e($engagement->id); ?>')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                     title="Voir détail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('engagements.update')): ?>
                                                <button onclick="modifierEngagement('<?php echo e($engagement->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('engagements.delete')): ?>
                                                <button onclick="supprimerEngagement('<?php echo e($engagement->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>



                                    </div>

                                    <div class="mb-3">
                                        <p class="font-medium text-slate-800"><?php echo e($engagement->nom_donateur); ?></p>
                                        <?php if($engagement->description): ?>
                                            <p class="text-sm text-slate-600"><?php echo e($engagement->description); ?></p>
                                        <?php endif; ?>
                                        <?php if($engagement->date_echeance): ?>
                                            <p class="text-xs text-slate-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                Échéance: <?php echo e($engagement->date_echeance->format('d/m/Y')); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-xs text-slate-500">Engagement</p>
                                            <p class="font-medium"><?php echo e(number_format($engagement->cible, 0, ',', ' ')); ?> FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Versé</p>
                                            <p class="font-medium text-green-600"><?php echo e(number_format($engagement->montant_solde, 0, ',', ' ')); ?> FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Reste</p>
                                            <p class="font-medium <?php echo e($engagement->reste > 0 ? 'text-orange-600' : 'text-green-600'); ?>">
                                                <?php echo e(number_format($engagement->reste, 0, ',', ' ')); ?> FCFA
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Progression</p>
                                            <p class="font-medium"><?php echo e(number_format($engagement->pourcentage_realise, 1)); ?>%</p>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-1">
                                                <div class="bg-purple-600 h-1.5 rounded-full" style="width: <?php echo e(min($engagement->pourcentage_realise, 100)); ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-handshake text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucun engagement enregistré</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

                            <!-- Modal d'export avec Tailwind CSS -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-download text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Choisir le format d'export</h3>
            <p class="text-slate-500 text-sm mt-1">Sélectionnez le format qui vous convient</p>
        </div>

        <div class="space-y-3 mb-6">
            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('json')">
                <input type="radio" name="format" value="json" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-code text-orange-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">JSON</div>
                    <div class="text-sm text-slate-500">Données structurées pour intégration</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>

            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('excel')">
                <input type="radio" name="format" value="excel" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-excel text-green-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">Excel</div>
                    <div class="text-sm text-slate-500">Tableau de bord avec calculs</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>

            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('pdf')">
                <input type="radio" name="format" value="pdf" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">PDF</div>
                    <div class="text-sm text-slate-500">Rapport complet imprimable</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>
        </div>

        <div class="flex gap-3">
            <button onclick="closeExportModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                Annuler
            </button>
            <button onclick="confirmExport()" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                Exporter
            </button>
        </div>
    </div>
</div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Gestion des onglets
            function switchTab(tabName) {
                // Masquer tous les contenus
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Réinitialiser tous les boutons d'onglet
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-slate-500');
                });

                // Afficher le contenu sélectionné
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Activer le bouton d'onglet sélectionné
                const activeTab = document.getElementById('tab-' + tabName);
                activeTab.classList.remove('border-transparent', 'text-slate-500');
                activeTab.classList.add('border-blue-500', 'text-blue-600');
            }

            function recalculerTotaux() {
                if (confirm('Recalculer les totaux de cette moisson ?')) {
                    fetch("<?php echo e(route('private.moissons.recalculer-totaux', $moisson)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors du recalcul');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors du recalcul');
                    });
                }
            }




            // Remplacer votre fonction existante par ceci :
let selectedFormat = '';

function exporterMoissons() {
    document.getElementById('exportModal').classList.remove('hidden');
    selectedFormat = '';
    document.querySelectorAll('input[name="format"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.format-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
    });
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function selectFormat(format) {
    selectedFormat = format;
    document.querySelectorAll('.format-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
    });
    event.currentTarget.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
    document.querySelector(`input[value="${format}"]`).checked = true;
}

function confirmExport() {
    if (selectedFormat) {
        window.location.href = `<?php echo e(route('private.moissons.export.liste')); ?>?format=${selectedFormat}`;
        closeExportModal();
    } else {
        alert('Veuillez sélectionner un format d\'export');
    }
}

// Fermer le modal si on clique sur l'overlay
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exportModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeExportModal();
        }
    });
});


















            // Fonctions pour les passages
            function ajouterPassage() {
                // Ouvrir modal d'ajout de passage
                window.location.href = `<?php echo e(route('private.moissons.passages.create', $moisson->id)); ?>`;
            }

            function modifierPassage(passageId) {
                window.location.href = `<?php echo e(route('private.moissons.passages.edit', [$moisson->id,':id'])); ?>`.replace(':id', passageId);
            }

            function detailPassage(passageId){
                window.location.href = `<?php echo e(route('private.moissons.passages.show', [$moisson,':id'])); ?>`.replace(':id', passageId);
            }

            function supprimerPassage(passageId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce passage ?')) {
                    fetch(`<?php echo e(route('private.moissons.passages.destroy', [$moisson->id,':id'])); ?>`.replace(':id', passageId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                }
            }


            // Fonctions pour les ventes
            function ajouterVente() {
                window.location.href = `<?php echo e(route('private.moissons.ventes.create', $moisson->id)); ?>`;
            }

            function detailVente(venteId){
                window.location.href = `<?php echo e(route('private.moissons.ventes.show', [$moisson,':id'])); ?>`.replace(':id', venteId);
            }

            function modifierVente(venteId) {
                window.location.href = `<?php echo e(route('private.moissons.ventes.edit', [$moisson->id, ':id'])); ?>`.replace(':id', venteId);
            }

            function supprimerVente(venteId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')) {
                    fetch(`<?php echo e(route('private.moissons.ventes.destroy', [$moisson->id, ':id'])); ?>`.replace(':id', venteId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                }
            }



            // Fonctions pour les engagements
            function ajouterEngagement() {
                window.location.href = `<?php echo e(route('private.moissons.engagements.create', $moisson->id)); ?>`;
            }

            function modifierEngagement(engagementId) {
                window.location.href = `<?php echo e(route('private.moissons.engagements.edit', [$moisson->id, ':id'])); ?>`.replace(':id', engagementId);
            }

            function detailEngagement(engagementId){
                window.location.href = `<?php echo e(route('private.moissons.engagements.show', [$moisson,':id'])); ?>`.replace(':id', engagementId);
            }

            function supprimerEngagement(engagementId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cet engagement ?')) {
                    fetch(`<?php echo e(route('private.moissons.engagements.destroy', [$moisson->id, ':id'])); ?>`.replace(':id', engagementId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                }
            }

            // Animation au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/moissons/show.blade.php ENDPATH**/ ?>