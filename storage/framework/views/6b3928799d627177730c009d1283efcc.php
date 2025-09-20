<?php $__env->startSection('title', $classe->nom); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="<?php echo e(route('private.classes.index')); ?>"
                   class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <!-- Image de la classe -->
                    <div class="flex-shrink-0">
                        <?php if($classe->image_classe): ?>
                            <img src="<?php echo e(asset('storage/' . $classe->image_classe)); ?>" alt="<?php echo e($classe->nom); ?>"
                                class="w-24 h-24 object-cover rounded-2xl shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-chalkboard-teacher text-3xl text-white"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            <?php echo e($classe->nom); ?>

                        </h1>

                        <div class="flex items-center space-x-4 mt-2">
                            <?php if($classe->tranche_age): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <?php echo e($classe->tranche_age); ?>

                                </span>
                            <?php endif; ?>

                            <?php if($classe->responsables && count($classe->responsables) > 0): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> En attente
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if($classe->description): ?>
                            <p class="text-slate-600 mt-3 max-w-2xl"><?php echo e($classe->description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                        <a href="<?php echo e(route('private.classes.edit', $classe)); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.export')): ?>
                        <div class="relative">
                            <button type="button" onclick="toggleExportMenu()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-download mr-2"></i> Exporter
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-10">
                                <div class="py-2">
                                    <a href="<?php echo e(route('private.classes.export', ['classe' => $classe, 'format' => 'csv'])); ?>"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors">
                                        <i class="fas fa-file-csv mr-2"></i> CSV
                                    </a>
                                    <a href="<?php echo e(route('private.classes.export', ['classe' => $classe, 'format' => 'excel'])); ?>"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors">
                                        <i class="fas fa-file-excel mr-2"></i> Excel
                                    </a>
                                    <a href="<?php echo e(route('private.classes.export', ['classe' => $classe, 'format' => 'pdf'])); ?>"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors">
                                        <i class="fas fa-file-pdf mr-2"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.duplicate')): ?>
                        <form action="<?php echo e(route('private.classes.duplicate', $classe)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" onclick="return confirm('Voulez-vous dupliquer cette classe ?')"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-sm font-medium rounded-xl hover:from-cyan-600 hover:to-blue-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-copy mr-2"></i> Dupliquer
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($classe->nombre_inscrits); ?></p>
                        <p class="text-sm text-slate-500">Membres inscrits</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-tie text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(count($classe->responsables ?? [])); ?></p>
                        <p class="text-sm text-slate-500">Responsables</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-bold text-slate-800">
                            <?php if($classe->age_minimum && $classe->age_maximum): ?>
                                <?php echo e($classe->age_minimum); ?>-<?php echo e($classe->age_maximum); ?> ans
                            <?php elseif($classe->age_minimum): ?>
                                <?php echo e($classe->age_minimum); ?>+ ans
                            <?php elseif($classe->age_maximum): ?>
                                Jusqu'à <?php echo e($classe->age_maximum); ?> ans
                            <?php else: ?>
                                Tous âges
                            <?php endif; ?>
                        </p>
                        <p class="text-sm text-slate-500">Tranche d'âge</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(count($classe->programme ?? [])); ?></p>
                        <p class="text-sm text-slate-500">Éléments du programme</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-user-tie text-green-600 mr-2"></i>
                                Responsables de la classe
                            </h2>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                <button onclick="showManageResponsablesModal()"
                                    class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm">
                                    <i class="fas fa-plus mr-2"></i> Gérer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <?php if($classe->responsables && count($classe->responsables) > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $classe->responsables_collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                <?php echo e(substr($responsable->prenom, 0, 1)); ?><?php echo e(substr($responsable->nom, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-slate-900"><?php echo e($responsable->prenom); ?> <?php echo e($responsable->nom); ?></h3>

                                                <div class="flex items-center space-x-3 text-sm">





                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                                        <?php echo e(ucfirst($responsable->responsabilite)); ?>

                                                    </span>
                                                    <?php if($responsable->superieur): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-crown mr-1"></i> Supérieur
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-sm text-slate-500"><?php echo e($responsable->email); ?></p>
                                            </div>
                                        </div>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                            <button onclick="removeResponsable('<?php echo e($responsable->id); ?>')"
                                                class="text-red-600 hover:text-red-800 p-2">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-user-tie text-3xl text-slate-400 mb-3"></i>
                                <p class="text-slate-500">Aucun responsable assigné à cette classe</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                    <button onclick="showManageResponsablesModal()"
                                        class="mt-3 inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-plus mr-2"></i> Ajouter un responsable
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Membres de la classe -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-users text-blue-600 mr-2"></i>
                                Membres de la classe (<?php echo e($classe->nombre_inscrits); ?>)
                            </h2>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                <div class="flex items-center space-x-2">
                                    <button onclick="showAddMembersModal()"
                                        class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                        <i class="fas fa-user-plus mr-2"></i> Ajouter
                                    </button>
                                    <button onclick="showMembersListModal()"
                                        class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm">
                                        <i class="fas fa-list mr-2"></i> Voir tout
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <?php if($classe->membres->count() > 0): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $classe->membres->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            <?php echo e(substr($membre->prenom, 0, 1)); ?><?php echo e(substr($membre->nom, 0, 1)); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-slate-900 truncate"><?php echo e($membre->prenom); ?> <?php echo e($membre->nom); ?></p>
                                            <p class="text-sm text-slate-500 truncate"><?php echo e($membre->email); ?></p>
                                        </div>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                            <button onclick="removeMember('<?php echo e($membre->id); ?>')"
                                                class="text-red-600 hover:text-red-800 p-1">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php if($classe->membres->count() > 8): ?>
                                <div class="mt-4 text-center">
                                    <button onclick="showMembersListModal()"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir les <?php echo e($classe->membres->count() - 8); ?> autres membres
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-users text-3xl text-slate-400 mb-3"></i>
                                <p class="text-slate-500 mb-4">Aucun membre inscrit dans cette classe</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                    <button onclick="showAddMembersModal()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-user-plus mr-2"></i> Ajouter des membres
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Colonne secondaire -->
            <div class="space-y-8">
                <!-- Programme -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-book text-purple-600 mr-2"></i>
                            Programme
                        </h2>
                    </div>

                    <div class="p-6">
                        <?php if($classe->programme && count($classe->programme) > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $classe->programme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-start space-x-3">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-600 rounded-full text-xs font-medium mt-0.5">
                                            <?php echo e($index + 1); ?>

                                        </span>
                                        <p class="text-slate-700 flex-1"><?php echo e($element); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-6">
                                <i class="fas fa-book-open text-2xl text-slate-400 mb-2"></i>
                                <p class="text-slate-500">Aucun programme défini</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                            Informations
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Date de création:</span>
                            <span class="font-medium text-slate-900"><?php echo e($classe->created_at->format('d/m/Y')); ?></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-600">Dernière modification:</span>
                            <span class="font-medium text-slate-900"><?php echo e($classe->updated_at->format('d/m/Y à H:i')); ?></span>
                        </div>

                        <?php if($classe->age_minimum || $classe->age_maximum): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Âge requis:</span>
                                <span class="font-medium text-slate-900">
                                    <?php if($classe->age_minimum && $classe->age_maximum): ?>
                                        <?php echo e($classe->age_minimum); ?> - <?php echo e($classe->age_maximum); ?> ans
                                    <?php elseif($classe->age_minimum): ?>
                                        <?php echo e($classe->age_minimum); ?>+ ans
                                    <?php else: ?>
                                        Jusqu'à <?php echo e($classe->age_maximum); ?> ans
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="flex justify-between">
                            <span class="text-slate-600">Capacité:</span>
                            <span class="font-medium text-green-600">Illimitée</span>
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-slate-600">Statut:</span>
                                <?php if($classe->responsables && count($classe->responsables) > 0): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> En attente
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions rapides
                        </h2>
                    </div>

                    <div class="p-6 space-y-3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                            <a href="<?php echo e(route('private.classes.edit', $classe)); ?>"
                                class="block w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors text-center">
                                <i class="fas fa-edit mr-2"></i> Modifier la classe
                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                            <button onclick="showAddMembersModal()"
                                class="block w-full px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors">
                                <i class="fas fa-user-plus mr-2"></i> Ajouter des membres
                            </button>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.export')): ?>
                            <button onclick="toggleExportMenu()"
                                class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i> Exporter les données
                            </button>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.archive')): ?>
                            <form action="<?php echo e(route('private.classes.archive', $classe)); ?>" method="POST" class="block">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir archiver cette classe ?')"
                                    class="w-full px-4 py-3 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-colors">
                                    <i class="fas fa-archive mr-2"></i> Archiver la classe
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
                            <?php if($classe->nombre_inscrits == 0): ?>
                                <button onclick="deleteClasse()"
                                    class="block w-full px-4 py-3 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-2"></i> Supprimer la classe
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal de gestion des responsables -->
    <div id="manageResponsablesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Gérer les responsables</h3>
                    <button type="button" onclick="closeManageResponsablesModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="manageResponsablesContent" class="p-6">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de membres -->
    <div id="addMembersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Ajouter des membres</h3>
                    <button type="button" onclick="closeAddMembersModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="addMembersContent" class="p-6">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <!-- Modal de liste des membres -->
    <div id="membersListModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Liste complète des membres</h3>
                    <button type="button" onclick="closeMembersListModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="membersListContent" class="p-6">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // Toggle du menu d'export
        function toggleExportMenu() {
            const menu = document.getElementById('exportMenu');
            menu.classList.toggle('hidden');
        }

        // Fermer le menu d'export si on clique ailleurs
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('exportMenu');
            const button = e.target.closest('button');

            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleExportMenu') === -1) {
                menu.classList.add('hidden');
            }
        });

        // Gestion des modals
        function showManageResponsablesModal() {
            document.getElementById('manageResponsablesModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            loadManageResponsablesContent();
        }

        function closeManageResponsablesModal() {
            document.getElementById('manageResponsablesModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showAddMembersModal() {
            document.getElementById('addMembersModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            loadAddMembersContent();
        }

        function closeAddMembersModal() {
            document.getElementById('addMembersModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showMembersListModal() {
            document.getElementById('membersListModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            loadMembersListContent();
        }

        function closeMembersListModal() {
            document.getElementById('membersListModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Chargement du contenu des modals
        // function loadManageResponsablesContent() {
        //     const content = document.getElementById('manageResponsablesContent');
        //     content.innerHTML = `
        //         <div class="text-center py-8">
        //             <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        //             <p class="mt-2 text-slate-600">Chargement...</p>
        //         </div>
        //     `;
        //     // Ici vous pourriez charger le contenu via AJAX
        // }

        // Chargement du contenu des modals
function loadManageResponsablesContent() {
    const content = document.getElementById('manageResponsablesContent');
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="mt-2 text-slate-600">Chargement des membres...</p>
        </div>
    `;

    // Charger les membres pour la gestion des responsables
    fetch(`<?php echo e(route('private.classes.getMembresForResponsables', $classe)); ?>`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            content.innerHTML = generateManageResponsablesContent(data.data);
        } else {
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                    <p class="text-red-600">Erreur lors du chargement</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                <p class="text-red-600">Erreur lors du chargement</p>
            </div>
        `;
    });
}


// Générer le contenu pour la gestion des responsables
function generateManageResponsablesContent(data) {
    const { membres, types_responsabilite } = data;
    const membersList = Array.isArray(membres) ? membres : membres.data;

    if (!membersList || membersList.length === 0) {
        return `
            <div class="text-center py-8">
                <i class="fas fa-users text-3xl text-slate-400 mb-3"></i>
                <p class="text-slate-500">Aucun membre dans cette classe</p>
            </div>
        `;
    }

    let html = `
        <!-- Barre de recherche -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="searchMembres" placeholder="Rechercher un membre..." onkeyup="searchMembres()"
                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- Liste des membres -->
        <div class="space-y-3 max-h-96 overflow-y-auto" id="membresList">
    `;

    membersList.forEach(membre => {
        const typesOptions = types_responsabilite.map(type =>
            `<option value="${type}" ${membre.responsabilite === type ? 'selected' : ''}>${type.charAt(0).toUpperCase() + type.slice(1)}</option>`
        ).join('');

        html += `
            <div class="membre-item flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors" data-membre-nom="${membre.prenom.toLowerCase()} ${membre.nom.toLowerCase()}" data-membre-email="${membre?.email?.toLowerCase()}">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        ${membre.prenom.charAt(0)}${membre.nom.charAt(0)}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">${membre.prenom} ${membre.nom}</p>
                        <p class="text-sm text-slate-500">${membre.email ?? 'Aucun email disponible'}</p>
                        ${membre.is_responsable ? `
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800 mt-1">
                                <i class="fas fa-user-tie mr-1"></i> ${membre.responsabilite}
                                ${membre.superieur ? ' (Supérieur)' : ''}
                            </span>
                        ` : ''}
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    ${membre.is_responsable ? `
                        <!-- Membre responsable : options de modification -->
                        <select onchange="updateResponsabilite('${membre.id}', this.value, ${membre.superieur})"
                            class="text-sm border border-slate-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            ${typesOptions}
                        </select>

                        <label class="flex items-center">
                            <input type="checkbox" ${membre.superieur ? 'checked' : ''}
                                onchange="toggleSuperieur('${membre.id}', '${membre.responsabilite}', this.checked)"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
                            <span class="text-xs text-slate-600">Supérieur</span>
                        </label>

                        <button onclick="removeResponsabilite('${membre.id}')"
                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                            title="Retirer la responsabilité">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    ` : `
                        <!-- Membre normal : option d'ajout -->
                        <select id="responsabilite_${membre.id}"
                            class="text-sm border border-slate-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Choisir une responsabilité</option>
                            ${types_responsabilite.map(type => `<option value="${type}">${type.charAt(0).toUpperCase() + type.slice(1)}</option>`).join('')}
                        </select>

                        <label class="flex items-center">
                            <input type="checkbox" id="superieur_${membre.id}"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
                            <span class="text-xs text-slate-600">Supérieur</span>
                        </label>

                        <button onclick="addResponsabilite('${membre.id}')"
                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                            title="Ajouter une responsabilité">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    `}
                </div>
            </div>
        `;
    });

    html += '</div>';

    // Ajouter la pagination si nécessaire
    if (!Array.isArray(membres) && membres.links) {
        html += `
            <div class="mt-4 flex justify-center">
                <!-- Pagination sera ajoutée ici si nécessaire -->
            </div>
        `;
    }

    return html;
}



// Fonction de recherche des membres
function searchMembres() {
    const searchTerm = document.getElementById('searchMembres').value.toLowerCase();
    const membreItems = document.querySelectorAll('.membre-item');

    membreItems.forEach(item => {
        const membreNom = item.getAttribute('data-membre-nom');
        const membreEmail = item.getAttribute('data-membre-email');

        if (membreNom.includes(searchTerm) || membreEmail.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Ajouter une responsabilité
function addResponsabilite(userId) {
    const responsabiliteSelect = document.getElementById(`responsabilite_${userId}`);
    const superieurCheckbox = document.getElementById(`superieur_${userId}`);

    const responsabilite = responsabiliteSelect.value;
    const superieur = superieurCheckbox.checked;

    if (!responsabilite) {
        showErrorMessage('Veuillez sélectionner une responsabilité');
        return;
    }

    updateResponsabiliteApi(userId, 'add', responsabilite, superieur);
}


// Mettre à jour une responsabilité
function updateResponsabilite(userId, responsabilite, superieur) {
    updateResponsabiliteApi(userId, 'update', responsabilite, superieur);
}


// Basculer le statut supérieur
function toggleSuperieur(userId, responsabilite, superieur) {
    updateResponsabiliteApi(userId, 'update', responsabilite, superieur);
}

// Retirer une responsabilité
function removeResponsabilite(userId) {
    if (!confirm('Êtes-vous sûr de vouloir retirer cette responsabilité ?')) {
        return;
    }

    updateResponsabiliteApi(userId, 'remove');
}


// API pour mettre à jour les responsabilités
function updateResponsabiliteApi(userId, action, responsabilite = null, superieur = false) {
    const payload = {
        user_id: userId,
        action: action
    };

    if (responsabilite) {
        payload.responsabilite = responsabilite;
    }

    if (superieur !== undefined) {
        payload.superieur = superieur;
    }

    fetch(`<?php echo e(route('private.classes.updateResponsabilite', $classe)); ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message || 'Responsabilité mise à jour avec succès');
            // Recharger le contenu de la modal
            loadManageResponsablesContent();
            // Optionnellement recharger la page après un délai
            setTimeout(() => location.reload(), 1500);
        } else {
            showErrorMessage(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showErrorMessage('Une erreur est survenue');
    });
}


function loadAddMembersContent() {
    const content = document.getElementById('addMembersContent');
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="mt-2 text-slate-600">Chargement des utilisateurs disponibles...</p>
        </div>
    `;

    // Charger les utilisateurs disponibles
    fetch(`<?php echo e(route('private.classes.getUtilisateursDisponibles', $classe)); ?>`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            content.innerHTML = generateAddMembersContent(data.data.utilisateurs);
            // Initialiser le compteur après le chargement du contenu
            setTimeout(() => {
                updateSelectedCount();
                updateSelectAllState();
            }, 100);
        } else {
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                    <p class="text-red-600">Erreur lors du chargement</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                <p class="text-red-600">Erreur lors du chargement</p>
            </div>
        `;
    });
}

        function loadMembersListContent() {
            const content = document.getElementById('membersListContent');
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <p class="mt-2 text-slate-600">Chargement des membres...</p>
                </div>
            `;

            // Charger la liste complète des membres
            fetch(`<?php echo e(route('private.classes.members', $classe)); ?>`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    content.innerHTML = generateMembersListContent(data.data.membres);
                } else {
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                            <p class="text-red-600">Erreur lors du chargement</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                        <p class="text-red-600">Erreur lors du chargement</p>
                    </div>
                `;
            });
        }

        // Générer le contenu pour l'ajout de membres
        // function generateAddMembersContent(utilisateurs) {
        //     if (!utilisateurs || (Array.isArray(utilisateurs) ? utilisateurs.length === 0 : utilisateurs.data.length === 0)) {
        //         return `
        //             <div class="text-center py-8">
        //                 <i class="fas fa-users text-3xl text-slate-400 mb-3"></i>
        //                 <p class="text-slate-500">Aucun utilisateur disponible pour inscription</p>
        //             </div>
        //         `;
        //     }

        //     const users = Array.isArray(utilisateurs) ? utilisateurs : utilisateurs.data;
        //     let html = `
        //         <form id="addMembersForm" onsubmit="submitAddMembers(event)">
        //             <div class="mb-4">
        //                 <label class="block text-sm font-medium text-slate-700 mb-2">
        //                     Sélectionner les utilisateurs à ajouter:
        //                 </label>
        //                 <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-lg">
        //                     <div class="p-3 border-b border-slate-200 bg-slate-50">
        //                         <label class="flex items-center">
        //                             <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"
        //                                 class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-3">
        //                             <span class="font-medium text-slate-700">Sélectionner tout</span>
        //                         </label>
        //                     </div>
        //     `;

        //     users.forEach(user => {
        //         html += `
        //             <div class="p-3 border-b border-slate-100 hover:bg-slate-50">
        //                 <label class="flex items-center">
        //                     <input type="checkbox" name="user_ids[]" value="${user.id}"
        //                         class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-3 user-checkbox">
        //                     <div class="flex items-center space-x-3 flex-1">
        //                         <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
        //                             ${user.prenom.charAt(0)}${user.nom.charAt(0)}
        //                         </div>
        //                         <div>
        //                             <p class="font-medium text-slate-900">${user.prenom} ${user.nom}</p>
        //                             <p class="text-sm text-slate-500">${user.email ?? 'Aucun email disponible'}</p>
        //                             ${user.age ? `<p class="text-xs text-slate-400">${user.age} ans</p>` : ''}
        //                         </div>
        //                     </div>
        //                     ${!user.age_compatible ? '<span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded">Âge incompatible</span>' : ''}
        //                 </label>
        //             </div>
        //         `;
        //     });

        //     html += `
        //                 </div>
        //             </div>
        //             <div class="flex items-center justify-between">
        //                 <label class="flex items-center">
        //                     <input type="checkbox" name="force_age_check" value="1"
        //                         class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
        //                     <span class="text-sm text-slate-600">Forcer l'ajout même si l'âge est incompatible</span>
        //                 </label>
        //                 <button type="submit"
        //                     class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        //                     Ajouter les membres sélectionnés
        //                 </button>
        //             </div>
        //         </form>
        //     `;

        //     return html;
        // }

        // Générer le contenu pour l'ajout de membres
function generateAddMembersContent(utilisateurs) {
    if (!utilisateurs || (Array.isArray(utilisateurs) ? utilisateurs.length === 0 : utilisateurs.data.length === 0)) {
        return `
            <div class="text-center py-8">
                <i class="fas fa-users text-3xl text-slate-400 mb-3"></i>
                <p class="text-slate-500">Aucun utilisateur disponible pour inscription</p>
            </div>
        `;
    }

    const users = Array.isArray(utilisateurs) ? utilisateurs : utilisateurs.data;
    let html = `
        <form id="addMembersForm" onsubmit="submitAddMembers(event)">
            <!-- Barre de recherche -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="searchUtilisateurs" placeholder="Rechercher un utilisateur..." onkeyup="searchUtilisateurs()"
                        class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Sélectionner les utilisateurs à ajouter:
                </label>
                <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-lg">
                    <div class="p-3 border-b border-slate-200 bg-slate-50">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-3">
                            <span class="font-medium text-slate-700">Sélectionner tout</span>
                        </label>
                    </div>
                    <div id="utilisateursList">
    `;

    users.forEach(user => {
        html += `
            <div class="utilisateur-item p-3 border-b border-slate-100 hover:bg-slate-50"
                 data-user-nom="${user.prenom.toLowerCase()} ${user.nom.toLowerCase()}"
                 data-user-email="${user?.email?.toLowerCase()}">
                <label class="flex items-center">
                    <input type="checkbox" name="user_ids[]" value="${user.id}"
                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-3 user-checkbox">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                            ${user.prenom.charAt(0)}${user.nom.charAt(0)}
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">${user.prenom} ${user.nom}</p>
                            <p class="text-sm text-slate-500">${user.email?? 'Aucun email disponible'}</p>
                            ${user.age ? `<p class="text-xs text-slate-400">${user.age} ans</p>` : ''}
                        </div>
                    </div>
                    ${!user.age_compatible ? '<span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded">Âge incompatible</span>' : ''}
                </label>
            </div>
        `;
    });

    html += `
                    </div>
                </div>
            </div>

            <!-- Filtres supplémentaires -->
            <div class="mb-4 p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" id="filterAgeCompatible" onchange="filterByAgeCompatibility()"
                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
                        <span class="text-slate-600">Afficher uniquement les âges compatibles</span>
                    </label>
                    <span id="selectedCount" class="text-slate-500">0 sélectionné(s)</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="force_age_check" value="1"
                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
                    <span class="text-sm text-slate-600">Forcer l'ajout même si l'âge est incompatible</span>
                </label>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Ajouter les membres sélectionnés
                </button>
            </div>
        </form>
    `;

    return html;
}



// Fonction de recherche des utilisateurs
function searchUtilisateurs() {
    const searchTerm = document.getElementById('searchUtilisateurs').value.toLowerCase();
    const utilisateurItems = document.querySelectorAll('.utilisateur-item');
    let visibleCount = 0;

    utilisateurItems.forEach(item => {
        const userNom = item.getAttribute('data-user-nom');
        const userEmail = item.getAttribute('data-user-email');

        if (userNom.includes(searchTerm) || userEmail.includes(searchTerm)) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
            // Désélectionner les éléments cachés
            const checkbox = item.querySelector('.user-checkbox');
            if (checkbox) {
                checkbox.checked = false;
            }
        }
    });

    // Mettre à jour le compteur
    updateSelectedCount();

    // Mettre à jour l'état du "Sélectionner tout"
    updateSelectAllState();
}

// Filtrer par compatibilité d'âge
function filterByAgeCompatibility() {
    const filterCheckbox = document.getElementById('filterAgeCompatible');
    const utilisateurItems = document.querySelectorAll('.utilisateur-item');

    utilisateurItems.forEach(item => {
        const ageIncompatibleBadge = item.querySelector('span.text-amber-600');
        const hasAgeIncompatibility = ageIncompatibleBadge !== null;

        if (filterCheckbox.checked) {
            // Afficher seulement les âges compatibles
            if (hasAgeIncompatibility) {
                item.style.display = 'none';
                // Désélectionner les éléments cachés
                const checkbox = item.querySelector('.user-checkbox');
                if (checkbox) {
                    checkbox.checked = false;
                }
            } else {
                item.style.display = 'block';
            }
        } else {
            // Afficher tous les utilisateurs (mais respecter la recherche)
            const searchTerm = document.getElementById('searchUtilisateurs').value.toLowerCase();
            const userNom = item.getAttribute('data-user-nom');
            const userEmail = item.getAttribute('data-user-email');

            if (userNom.includes(searchTerm) || userEmail.includes(searchTerm)) {
                item.style.display = 'block';
            }
        }
    });

    // Mettre à jour le compteur
    updateSelectedCount();

    // Mettre à jour l'état du "Sélectionner tout"
    updateSelectAllState();
}


// Mettre à jour le compteur de sélection
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const countElement = document.getElementById('selectedCount');

    if (countElement) {
        countElement.textContent = `${selectedCheckboxes.length} sélectionné(s)`;
    }
}

// Mettre à jour l'état du bouton "Sélectionner tout"
function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const visibleCheckboxes = Array.from(document.querySelectorAll('.user-checkbox')).filter(cb => {
        return cb.closest('.utilisateur-item').style.display !== 'none';
    });
    const visibleCheckedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);

    if (selectAllCheckbox) {
        if (visibleCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (visibleCheckedCheckboxes.length === visibleCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (visibleCheckedCheckboxes.length > 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }
}


// Mise à jour de la fonction toggleSelectAll existante
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const visibleCheckboxes = Array.from(document.querySelectorAll('.user-checkbox')).filter(cb => {
        return cb.closest('.utilisateur-item').style.display !== 'none';
    });

    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    updateSelectedCount();
}

// Ajouter des écouteurs d'événements pour les checkboxes
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('user-checkbox')) {
        updateSelectedCount();
        updateSelectAllState();
    }
});



        // Générer le contenu pour la liste des membres
        function generateMembersListContent(membres) {
            const membersList = Array.isArray(membres) ? membres : membres.data;

            if (!membersList || membersList.length === 0) {
                return `
                    <div class="text-center py-8">
                        <i class="fas fa-users text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucun membre inscrit dans cette classe</p>
                    </div>
                `;
            }

            let html = '<div class="space-y-3">';
            membersList.forEach(membre => {
                html += `
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                ${membre.prenom.charAt(0)}${membre.nom.charAt(0)}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">${membre.prenom} ${membre.nom}</p>
                                <p class="text-sm text-slate-500">${membre.email ?? 'Aucun email disponible'}</p>
                                ${membre.telephone_1 ? `<p class="text-sm text-slate-400">${membre.telephone_1}</p>` : ''}
                            </div>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                            <button onclick="removeMember('${membre.id}')"
                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                title="Retirer de la classe">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                `;
            });
            html += '</div>';

            return html;
        }

        // Fonctions utilitaires
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function submitAddMembers(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Afficher un indicateur de chargement
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Ajout en cours...';
            submitButton.disabled = true;

            fetch(`<?php echo e(route('private.classes.ajouter-membres', $classe)); ?>`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;

                if (data.success) {
                    showSuccessMessage(data.message || 'Membres ajoutés avec succès');
                    closeAddMembersModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showErrorMessage(data.message || 'Erreur lors de l\'ajout des membres');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                showErrorMessage('Une erreur est survenue lors de l\'ajout des membres');
            });
        }

        // Retirer un membre
        function removeMember(userId) {
            if (!confirm('Êtes-vous sûr de vouloir retirer ce membre de la classe ?')) {
                return;
            }

            fetch(`<?php echo e(route('private.classes.desinscrireUtilisateur', $classe)); ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || 'Membre retiré avec succès');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showErrorMessage(data.message || 'Erreur lors de la suppression du membre');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorMessage('Une erreur est survenue');
            });
        }

        // Retirer un responsable
        function removeResponsable(userId) {
            if (!confirm('Êtes-vous sûr de vouloir retirer ce responsable ?')) {
                return;
            }

            fetch(`<?php echo e(route('private.classes.retirerResponsable', $classe)); ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || 'Responsable retiré avec succès');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showErrorMessage(data.message || 'Erreur lors de la suppression du responsable');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorMessage('Une erreur est survenue');
            });
        }

        // Supprimer la classe
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
        function deleteClasse() {
            if (confirm('Êtes-vous sûr de vouloir supprimer définitivement cette classe ? Cette action est irréversible.')) {
                fetch(`<?php echo e(route('private.classes.destroy', $classe)); ?>`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message || 'Classe supprimée avec succès');
                        setTimeout(() => {
                            window.location.href = '<?php echo e(route("private.classes.index")); ?>';
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showErrorMessage('Une erreur est survenue lors de la suppression');
                });
            }
        }
        <?php endif; ?>

        // Fonctions d'affichage des messages
        function showSuccessMessage(message) {
            showMessage(message, 'success');
        }

        function showErrorMessage(message) {
            showMessage(message, 'error');
        }

        function showMessage(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(alertDiv);

            // Animation d'entrée
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-full');
                alertDiv.classList.add('translate-x-0');
            }, 100);

            // Animation de sortie et suppression
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-0');
                alertDiv.classList.add('translate-x-full');
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }

        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('manageResponsablesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeManageResponsablesModal();
            }
        });

        document.getElementById('addMembersModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddMembersModal();
            }
        });

        document.getElementById('membersListModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMembersListModal();
            }
        });

        // Fermer les modals avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeManageResponsablesModal();
                closeAddMembersModal();
                closeMembersListModal();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/show.blade.php ENDPATH**/ ?>