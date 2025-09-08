<?php $__env->startSection('title', 'Classe - ' . $classe->nom); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent"><?php echo e($classe->nom); ?></h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo e(route('private.classes.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Classes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500"><?php echo e($classe->nom); ?></span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                    <a href="<?php echo e(route('private.classes.edit', $classe)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.export')): ?>
                    <div class="relative">
                        <button onclick="toggleExportMenu()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-10">
                            <div class="py-2">
                                <a href="<?php echo e(route('private.classes.export', $classe->id)); ?>?format=csv" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                                </a>
                                <a href="<?php echo e(route('private.classes.export', $classe->id)); ?>?format=excel" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                                </a>
                                <a href="<?php echo e(route('private.classes.export', $classe->id)); ?>?format=pdf" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Carte principale -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Image de couverture -->
                <div class="relative h-64 bg-gradient-to-br from-blue-400 to-purple-500">
                    <?php if($classe->image_classe): ?>
                        <img src="<?php echo e(Storage::url($classe->image_classe)); ?>" alt="<?php echo e($classe->nom); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-8xl text-white/80"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="flex items-end justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-white"><?php echo e($classe->nom); ?></h2>
                                <?php if($classe->tranche_age): ?>
                                    <p class="text-white/90 mt-1"><?php echo e($classe->tranche_age); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($classe->responsable_id): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                                    <i class="fas fa-check mr-1"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500 text-white">
                                    <i class="fas fa-clock mr-1"></i> En attente
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                    <?php if($classe->description): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2 flex items-center">
                                <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                Description
                            </h3>
                            <p class="text-slate-700 leading-relaxed"><?php echo e($classe->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Informations détaillées -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <?php if($classe->age_minimum || $classe->age_maximum): ?>
                                <div>
                                    <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Tranche d'âge</h4>
                                    <p class="text-lg font-semibold text-slate-900">
                                        <?php if($classe->age_minimum && $classe->age_maximum): ?>
                                            <?php echo e($classe->age_minimum); ?> - <?php echo e($classe->age_maximum); ?> ans
                                        <?php elseif($classe->age_minimum): ?>
                                            <?php echo e($classe->age_minimum); ?>+ ans
                                        <?php elseif($classe->age_maximum): ?>
                                            Jusqu'à <?php echo e($classe->age_maximum); ?> ans
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div>
                                <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Date de création</h4>
                                <p class="text-lg font-semibold text-slate-900"><?php echo e($classe->created_at->format('d/m/Y')); ?></p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Capacité</h4>
                                <p class="text-lg font-semibold text-slate-900">50 personnes max</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Dernière mise à jour</h4>
                                <p class="text-lg font-semibold text-slate-900"><?php echo e($classe->updated_at->format('d/m/Y à H:i')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsables -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-green-600 mr-2"></i>
                        Équipe de Direction
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Responsable -->
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <?php if($classe->responsable): ?>
                                        <?php echo e(substr($classe->responsable->prenom, 0, 1)); ?><?php echo e(substr($classe->responsable->nom, 0, 1)); ?>

                                    <?php else: ?>
                                        <i class="fas fa-user-plus"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-slate-900">Responsable</h4>
                                    <?php if($classe->responsable): ?>
                                        <p class="text-slate-700"><?php echo e($classe->responsable->nom_complet); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($classe->responsable->email); ?></p>
                                    <?php else: ?>
                                        <p class="text-slate-500">Aucun responsable assigné</p>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.assign-leaders')): ?>
                                            <button onclick="assignResponsable()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Assigner un responsable
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Enseignant -->
                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <?php if($classe->enseignantPrincipal): ?>
                                        <?php echo e(substr($classe->enseignantPrincipal->prenom, 0, 1)); ?><?php echo e(substr($classe->enseignantPrincipal->nom, 0, 1)); ?>

                                    <?php else: ?>
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-slate-900">Enseignant Principal</h4>
                                    <?php if($classe->enseignantPrincipal): ?>
                                        <p class="text-slate-700"><?php echo e($classe->enseignantPrincipal->nom_complet); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($classe->enseignantPrincipal->email); ?></p>
                                    <?php else: ?>
                                        <p class="text-slate-500">Aucun enseignant assigné</p>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.assign-leaders')): ?>
                                            <button onclick="assignEnseignant()" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                Assigner un enseignant
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membres de la classe -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-friends text-purple-600 mr-2"></i>
                            Membres (<?php echo e($classe->membres->count()); ?>)
                        </h3>
                        <div class="flex items-center space-x-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                <button onclick="showAddMemberModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                                    <i class="fas fa-user-plus mr-2"></i> Ajouter un membre
                                </button>
                                <button onclick="showBulkAddModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                                    <i class="fas fa-users mr-2"></i> Ajout groupé
                                </button>
                            <?php endif; ?>
                            <?php if($classe->membres->count() > 6): ?>
                                <a href="<?php echo e(route('private.classes.membres', $classe)); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-all duration-200">
                                    <i class="fas fa-list mr-2"></i> Voir tous
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <?php if($classe->membres->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $classe->membres->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-medium">
                                            <?php echo e(substr($membre->prenom, 0, 1)); ?><?php echo e(substr($membre->nom, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900"><?php echo e($membre->nom_complet); ?></p>
                                            <p class="text-sm text-slate-500"><?php echo e($membre->email); ?></p>
                                            <?php if($membre->telephone_1): ?>
                                                <p class="text-sm text-slate-500"><?php echo e($membre->telephone_1); ?></p>
                                            <?php endif; ?>
                                            <?php if($membre->date_naissance): ?>
                                                <p class="text-xs text-slate-400"><?php echo e($membre->date_naissance->diffInYears(now())); ?> ans</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                        <button onclick="removeMember('<?php echo e($membre->id); ?>')" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($classe->membres->count() > 6): ?>
                            <div class="mt-4 text-center">
                                <p class="text-sm text-slate-500">Affichage des 6 premiers membres</p>
                                <a href="<?php echo e(route('private.classes.membres', $classe)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir tous les <?php echo e($classe->membres->count()); ?> membres
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-friends text-2xl text-slate-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 mb-2">Aucun membre inscrit</h4>
                            <p class="text-slate-500 mb-4">Cette classe n'a pas encore de membres inscrits.</p>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                <div class="flex items-center justify-center space-x-3">
                                    <button onclick="showAddMemberModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-user-plus mr-2"></i> Ajouter le premier membre
                                    </button>
                                    <button onclick="showBulkAddModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                                        <i class="fas fa-users mr-2"></i> Ajout groupé
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Programme -->
            <?php if($classe->programme && is_array($classe->programme) && count($classe->programme) > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-book text-amber-600 mr-2"></i>
                            Programme de la Classe
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php $__currentLoopData = $classe->programme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lecon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-slate-900 flex items-center">
                                                <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3"><?php echo e($index + 1); ?></span>
                                                <?php echo e($lecon['titre'] ?? 'Leçon sans titre'); ?>

                                            </h4>
                                            <?php if(isset($lecon['description'])): ?>
                                                <p class="text-slate-600 mt-2 ml-9"><?php echo e($lecon['description']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if(isset($lecon['duree'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-4">
                                                <i class="fas fa-clock mr-1"></i> <?php echo e($lecon['duree']); ?> min
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Inscrits -->
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?php echo e($classe->nombre_inscrits); ?></div>
                        <div class="text-sm text-slate-500 uppercase tracking-wider">Membres inscrits</div>
                    </div>

                    <!-- Places disponibles -->
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600"><?php echo e($classe->places_disponibles ?? (50 - $classe->nombre_inscrits)); ?></div>
                        <div class="text-sm text-slate-500 uppercase tracking-wider">Places disponibles</div>
                    </div>

                    <!-- Taux de remplissage -->
                    <div>
                        <?php
                            $pourcentage = round(($classe->nombre_inscrits / 50) * 100, 1);
                        ?>
                        <div class="flex justify-between text-sm font-medium text-slate-700 mb-2">
                            <span>Taux de remplissage</span>
                            <span><?php echo e($pourcentage); ?>%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300" style="width: <?php echo e(min($pourcentage, 100)); ?>%"></div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="text-center p-3 rounded-xl <?php echo e($classe->nombre_inscrits >= 50 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'); ?>">
                        <?php if($classe->nombre_inscrits >= 50): ?>
                            <i class="fas fa-exclamation-circle text-red-600 text-2xl mb-2"></i>
                            <div class="text-red-800 font-semibold">Classe complète</div>
                        <?php else: ?>
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <div class="text-green-800 font-semibold">Places disponibles</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-lightning-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                        <button onclick="showAddMemberModal()" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i> Ajouter un membre
                        </button>
                        <button onclick="showBulkAddModal()" class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-users mr-2"></i> Ajout groupé
                        </button>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
                        <a href="<?php echo e(route('private.classes.membres', $classe)); ?>" class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-list mr-2"></i> Voir tous les membres
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.attendance')): ?>
                        <a href="#" class="w-full inline-flex items-center justify-center px-4 py-3 bg-amber-600 text-white text-sm font-medium rounded-xl hover:bg-amber-700 transition-colors">
                            <i class="fas fa-check-square mr-2"></i> Marquer présences
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.communicate')): ?>
                        <button onclick="showCommunicationModal()" class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i> Envoyer message
                        </button>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.reports')): ?>
                        <a href="#" class="w-full inline-flex items-center justify-center px-4 py-3 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i> Voir rapport
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-slate-600 mr-2"></i>
                        Informations
                    </h3>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Créé le:</span>
                        <span class="font-medium"><?php echo e($classe->created_at->format('d/m/Y à H:i')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Modifié le:</span>
                        <span class="font-medium"><?php echo e($classe->updated_at->format('d/m/Y à H:i')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">ID:</span>
                        <code class="text-xs bg-slate-100 px-2 py-1 rounded"><?php echo e($classe->id); ?></code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de membre unique -->
<div id="addMemberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter un membre à la classe</h3>
        </div>
        <div class="p-6">
            <form id="addMemberForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sélectionner un utilisateur</label>
                    <select id="memberSelect" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Chargement...</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Seuls les utilisateurs sans classe sont affichés</p>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeAddMemberModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajout groupé -->
<div id="bulkAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Ajout groupé de membres</h3>
            <p class="text-sm text-slate-600 mt-1">Sélectionnez plusieurs utilisateurs à ajouter à la classe</p>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form id="bulkAddForm">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-slate-700">Utilisateurs disponibles</label>
                        <div class="flex items-center space-x-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="filterCompatible" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-600">Âge compatible uniquement</span>
                            </label>
                            <input type="text" id="searchUsers" placeholder="Rechercher..." class="px-3 py-1 text-sm border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div id="availableUsersList" class="space-y-2 max-h-64 overflow-y-auto border border-slate-200 rounded-lg p-3">
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Chargement des utilisateurs...</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm">
                        <span id="selectedCount" class="text-slate-600">0 utilisateur(s) sélectionné(s)</span>
                        <button type="button" onclick="selectAllVisible()" class="text-blue-600 hover:text-blue-800">Tout sélectionner</button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="forceAgeCheck" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-slate-700">Forcer l'ajout même si l'âge n'est pas compatible</span>
                    </label>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeBulkAddModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-users mr-2"></i> Ajouter les membres
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let availableUsers = [];
let filteredUsers = [];

// Menu d'export
function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');
}

// Fermer le menu d'export si on clique ailleurs
document.addEventListener('click', function(e) {
    const menu = document.getElementById('exportMenu');
    const button = e.target.closest('button');
    if (!button || button.onclick !== toggleExportMenu) {
        menu.classList.add('hidden');
    }
});

// Modal d'ajout de membre unique
function showAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
    loadAvailableMembers();
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
}

// Modal d'ajout groupé
function showBulkAddModal() {
    document.getElementById('bulkAddModal').classList.remove('hidden');
    loadAvailableUsersForBulk();
}

function closeBulkAddModal() {
    document.getElementById('bulkAddModal').classList.add('hidden');
    // Reset form
    document.getElementById('availableUsersList').innerHTML = '';
    availableUsers = [];
    filteredUsers = [];
    updateSelectedCount();
}

// Charger les membres disponibles pour le modal simple
function loadAvailableMembers() {
    const select = document.getElementById('memberSelect');
    select.innerHTML = '<option value="">Chargement...</option>';

    fetch("<?php echo e(route('private.classes.utilisateurs-disponibles', $classe->id)); ?>", {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            select.innerHTML = '<option value="">Sélectionner un utilisateur</option>';
            data.data.membres.data.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                const ageInfo = user.age ? ` (${user.age} ans)` : '';
                const compatibleInfo = user.age_compatible ? '' : ' - ⚠️ Âge incompatible';
                option.textContent = `${user.prenom} ${user.nom}${ageInfo}${compatibleInfo}`;
                if (!user.age_compatible) {
                    option.style.color = '#dc2626';
                }
                select.appendChild(option);
            });
        } else {
            select.innerHTML = '<option value="">Erreur lors du chargement</option>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        select.innerHTML = '<option value="">Erreur lors du chargement</option>';
    });
}

// Charger les utilisateurs pour l'ajout groupé
function loadAvailableUsersForBulk() {
    const container = document.getElementById('availableUsersList');
    container.innerHTML = `
        <div class="text-center py-8 text-slate-500">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Chargement des utilisateurs...</p>
        </div>
    `;

    fetch("<?php echo e(route('private.classes.utilisateurs-disponibles', $classe->id)); ?>", {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            availableUsers = data.data.membres.data;
            filteredUsers = [...availableUsers];
            renderUsersList();
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        container.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erreur lors du chargement</p>
            </div>
        `;
    });
}

// Rendu de la liste des utilisateurs
function renderUsersList() {
    const container = document.getElementById('availableUsersList');

    if (filteredUsers.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-users text-2xl mb-2"></i>
                <p>Aucun utilisateur disponible</p>
            </div>
        `;
        return;
    }

    container.innerHTML = filteredUsers.map(user => `
        <label class="flex items-center p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
            <input type="checkbox" name="user_ids[]" value="${user.id}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-3" onchange="updateSelectedCount()">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-slate-900">${user.prenom} ${user.nom}</span>
                    <div class="flex items-center space-x-2">
                        ${user.age ? `<span class="text-sm text-slate-500">${user.age} ans</span>` : ''}
                        ${user.age_compatible ?
                            '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Compatible</span>' :
                            '<span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">⚠️ Âge incompatible</span>'
                        }
                    </div>
                </div>
                <p class="text-sm text-slate-500">${user.email}</p>
            </div>
        </label>
    `).join('');
}

// Filtrage des utilisateurs
function filterUsers() {
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    const compatibleOnly = document.getElementById('filterCompatible').checked;

    filteredUsers = availableUsers.filter(user => {
        const matchesSearch = !searchTerm ||
            user.prenom.toLowerCase().includes(searchTerm) ||
            user.nom.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm);

        const matchesCompatible = !compatibleOnly || user.age_compatible;

        return matchesSearch && matchesCompatible;
    });

    renderUsersList();
    updateSelectedCount();
}

// Événements de filtrage
document.getElementById('searchUsers').addEventListener('input', filterUsers);
document.getElementById('filterCompatible').addEventListener('change', filterUsers);

// Sélectionner tous les utilisateurs visibles
function selectAllVisible() {
    const checkboxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });

    updateSelectedCount();
}

// Mettre à jour le compteur de sélection
function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]:checked');
    const count = checkedBoxes.length;
    document.getElementById('selectedCount').textContent = `${count} utilisateur(s) sélectionné(s)`;
}

// Ajouter un membre unique
document.getElementById('addMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const userId = document.getElementById('memberSelect').value;
    if (!userId) {
        alert('Veuillez sélectionner un utilisateur');
        return;
    }

    fetch("<?php echo e(route('private.classes.inscrire', $classe->id)); ?>", {
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
            closeAddMemberModal();
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.message || 'Erreur lors de l\'ajout du membre');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Ajout groupé
document.getElementById('bulkAddForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const checkedBoxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]:checked');
    const userIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (userIds.length === 0) {
        alert('Veuillez sélectionner au moins un utilisateur');
        return;
    }

    const forceAgeCheck = document.getElementById('forceAgeCheck').checked;

    // Confirmation
    if (!confirm(`Voulez-vous ajouter ${userIds.length} utilisateur(s) à la classe ?`)) {
        return;
    }

    fetch("<?php echo e(route('private.classes.ajouter-membres', $classe->id)); ?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            user_ids: userIds,
            force_age_check: forceAgeCheck
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBulkAddModal();
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            alert(data.message || 'Erreur lors de l\'ajout des membres');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Retirer un membre
function removeMember(userId) {
    if (!confirm('Êtes-vous sûr de vouloir retirer ce membre de la classe ?')) {
        return;
    }

    fetch("<?php echo e(route('private.classes.desinscrire', $classe->id)); ?>", {
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
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.message || 'Erreur lors de la suppression du membre');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Fonction d'affichage des messages de succès
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Assigner responsable/enseignant (placeholders)
function assignResponsable() {
    alert('Fonctionnalité d\'assignation du responsable à implémenter');
}

function assignEnseignant() {
    alert('Fonctionnalité d\'assignation de l\'enseignant à implémenter');
}

function showCommunicationModal() {
    alert('Fonctionnalité de communication à implémenter');
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('addMemberModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddMemberModal();
    }
});

document.getElementById('bulkAddModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkAddModal();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/show.blade.php ENDPATH**/ ?>