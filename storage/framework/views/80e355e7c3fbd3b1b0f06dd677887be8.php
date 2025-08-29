<?php $__env->startSection('title', 'Hiérarchie des Rôles'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Hiérarchie des Rôles</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.roles.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Rôles
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Hiérarchie</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions et filtres -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-sitemap text-purple-600 mr-2"></i>
                    Vue d'ensemble
                </h2>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('private.roles.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                    <button type="button" onclick="toggleView()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-exchange-alt mr-2"></i> <span id="view-toggle-text">Vue Graphique</span>
                    </button>
                    <button type="button" onclick="exportHierarchy()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Affichage</label>
                    <select id="display-mode" onchange="changeDisplayMode()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="all">Tous les niveaux</option>
                        <option value="non-empty">Niveaux avec rôles uniquement</option>
                        <option value="system">Rôles système uniquement</option>
                        <option value="custom">Rôles personnalisés uniquement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Niveau minimum</label>
                    <input type="number" id="min-level" value="0" min="0" max="100" onchange="filterByLevel()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Niveau maximum</label>
                    <input type="number" id="max-level" value="100" min="0" max="100" onchange="filterByLevel()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Options</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="expandAll()" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-expand mr-1"></i> Ouvrir
                        </button>
                        <button type="button" onclick="collapseAll()" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-compress mr-1"></i> Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                Statistiques de la Hiérarchie
            </h2>
        </div>
        <div class="p-6">
            <?php
                $totalRoles = collect($hierarchy)->flatten()->count();
                $totalUsers = collect($hierarchy)->flatten()->sum(function($role) { return $role->users()->count(); });
                $systemRoles = collect($hierarchy)->flatten()->where('is_system_role', true)->count();
                $customRoles = collect($hierarchy)->flatten()->where('is_system_role', false)->count();
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold"><?php echo e($totalRoles); ?></p>
                            <p class="text-blue-100">Total des rôles</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-500 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold"><?php echo e($totalUsers); ?></p>
                            <p class="text-green-100">Utilisateurs assignés</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold"><?php echo e($systemRoles); ?></p>
                            <p class="text-yellow-100">Rôles système</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-lock text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold"><?php echo e($customRoles); ?></p>
                            <p class="text-purple-100">Rôles personnalisés</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-cogs text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue hiérarchique par niveaux -->
    <div id="hierarchy-view" class="space-y-6">
        <?php $__currentLoopData = $hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelName => $levelRoles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $levelRange = '';
                if (strpos($levelName, '100') !== false) $levelRange = '100';
                elseif (strpos($levelName, '80-99') !== false) $levelRange = '80-99';
                elseif (strpos($levelName, '60-79') !== false) $levelRange = '60-79';
                elseif (strpos($levelName, '40-59') !== false) $levelRange = '40-59';
                elseif (strpos($levelName, '20-39') !== false) $levelRange = '20-39';
                elseif (strpos($levelName, '10-19') !== false) $levelRange = '10-19';
                else $levelRange = '0-9';
            ?>
            <div class="level-section bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300" data-level-range="<?php echo e($levelRange); ?>">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                <?php if(strpos($levelName, '100') !== false): ?> bg-red-100 text-red-800
                                <?php elseif(strpos($levelName, '80-99') !== false): ?> bg-yellow-100 text-yellow-800
                                <?php elseif(strpos($levelName, '60-79') !== false): ?> bg-blue-100 text-blue-800
                                <?php elseif(strpos($levelName, '40-59') !== false): ?> bg-purple-100 text-purple-800
                                <?php elseif(strpos($levelName, '20-39') !== false): ?> bg-green-100 text-green-800
                                <?php elseif(strpos($levelName, '10-19') !== false): ?> bg-gray-100 text-gray-800
                                <?php else: ?> bg-slate-100 text-slate-800
                                <?php endif; ?>">
                                <?php echo e($levelRange); ?>

                            </span>
                            <h2 class="text-xl font-bold text-slate-800"><?php echo e($levelName); ?></h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                <?php echo e($levelRoles->count()); ?> rôle(s)
                            </span>
                        </div>
                        <button type="button" onclick="toggleLevelSection('<?php echo e($levelRange); ?>')" id="toggle-<?php echo e($levelRange); ?>" class="p-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="level-content p-6" id="level-<?php echo e($levelRange); ?>">
                    <?php if($levelRoles->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $levelRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="role-item group" data-system="<?php echo e($role->is_system_role ? 'true' : 'false'); ?>" data-level="<?php echo e($role->level); ?>">
                                    <div class="h-full bg-white border-2 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group-hover:border-blue-300 <?php echo e($role->is_system_role ? 'border-yellow-200 bg-yellow-50' : 'border-slate-200'); ?>">
                                        <!-- En-tête du rôle -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-start space-x-2 mb-2">
                                                    <?php if($role->is_system_role): ?>
                                                        <i class="fas fa-lock text-yellow-500 mt-1"></i>
                                                    <?php endif; ?>
                                                    <h3 class="text-lg font-bold text-slate-900"><?php echo e($role->name); ?></h3>
                                                </div>
                                                <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded"><?php echo e($role->slug); ?></code>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($role->level >= 100): ?> bg-red-100 text-red-800
                                                <?php elseif($role->level >= 80): ?> bg-yellow-100 text-yellow-800
                                                <?php elseif($role->level >= 60): ?> bg-blue-100 text-blue-800
                                                <?php elseif($role->level >= 40): ?> bg-purple-100 text-purple-800
                                                <?php elseif($role->level >= 20): ?> bg-green-100 text-green-800
                                                <?php elseif($role->level >= 10): ?> bg-gray-100 text-gray-800
                                                <?php else: ?> bg-slate-100 text-slate-800
                                                <?php endif; ?>">
                                                <?php echo e($role->level); ?>

                                            </span>
                                        </div>

                                        <!-- Description -->
                                        <?php if($role->description): ?>
                                            <div class="mb-4">
                                                <p class="text-sm text-slate-600"><?php echo e(Str::limit($role->description, 100)); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Statistiques -->
                                        <div class="grid grid-cols-3 gap-4 mb-4">
                                            <div class="text-center">
                                                <div class="text-xl font-bold text-blue-600"><?php echo e($role->users()->count()); ?></div>
                                                <div class="text-xs text-slate-500">Utilisateurs</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl font-bold text-purple-600"><?php echo e($role->permissions()->count()); ?></div>
                                                <div class="text-xs text-slate-500">Permissions</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl font-bold text-green-600"><?php echo e($role->users()->wherePivot('actif', true)->count()); ?></div>
                                                <div class="text-xs text-slate-500">Actifs</div>
                                            </div>
                                        </div>

                                        <!-- Indicateurs de statut -->
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <?php if($role->is_system_role): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-lock mr-1"></i> Système
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-cog mr-1"></i> Personnalisé
                                                </span>
                                            <?php endif; ?>

                                            <?php if($role->users()->wherePivot('actif', true)->count() > 0): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">En utilisation</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-800">Inutilisé</span>
                                            <?php endif; ?>

                                            <?php if($role->permissions()->count() === 0): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">Sans permissions</span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-2 mb-4">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.read')): ?>
                                                <a href="<?php echo e(route('private.roles.show', $role)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-cyan-100 text-cyan-700 text-sm font-medium rounded-lg hover:bg-cyan-200 transition-colors">
                                                    <i class="fas fa-eye mr-1"></i> Voir
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.update')): ?>
                                                <a href="<?php echo e(route('private.roles.edit', $role)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-200 transition-colors">
                                                    <i class="fas fa-edit mr-1"></i> Modifier
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.manage')): ?>
                                                <a href="<?php echo e(route('private.roles.permissions', $role)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                                    <i class="fas fa-key mr-1"></i> Permissions
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Utilisateurs récents -->
                                        <?php if($role->users()->count() > 0): ?>
                                            <div class="border-t border-slate-200 pt-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-sm font-medium text-slate-700">Utilisateurs récents:</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <?php $__currentLoopData = $role->users()->take(3)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="relative group">
                                                            <?php if($user->avatar): ?>
                                                                <img src="<?php echo e($user->avatar); ?>" class="w-8 h-8 rounded-full object-cover ring-2 ring-blue-500" alt="<?php echo e($user->nom_complet); ?>">
                                                            <?php else: ?>
                                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold ring-2 ring-blue-500">
                                                                    <?php echo e(strtoupper(substr($user->nom_complet, 0, 1))); ?>

                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                                                <?php echo e($user->nom_complet); ?>

                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($role->users()->count() > 3): ?>
                                                        <span class="text-xs text-slate-500">+<?php echo e($role->users()->count() - 3); ?> autres</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun rôle dans ce niveau hiérarchique</h3>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                                <a href="<?php echo e(route('private.roles.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-plus mr-2"></i> Créer un rôle
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Vue graphique -->
    <div id="graph-view" class="hidden">
        <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-project-diagram text-indigo-600 mr-2"></i>
                    Vue Graphique de la Hiérarchie
                </h2>
                <p class="text-slate-500 mt-1">Représentation visuelle de l'organisation hiérarchique des rôles</p>
            </div>
            <div class="p-6">
                <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl p-4">
                    <svg id="hierarchy-svg" width="100%" height="600" class="w-full">
                        <!-- Le graphique sera généré dynamiquement -->
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Guide des niveaux -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-layer-group text-green-600 mr-2"></i>
                Guide des Niveaux Hiérarchiques
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 mr-3">100</span>
                        <h3 class="font-bold text-slate-900">Super Admin</h3>
                    </div>
                    <p class="text-sm text-slate-600">Accès complet au système, peut tout modifier, y compris les autres super admins.</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mr-3">80-99</span>
                        <h3 class="font-bold text-slate-900">Administration</h3>
                    </div>
                    <p class="text-sm text-slate-600">Gestion administrative du système, configuration, utilisateurs et rôles.</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-3">60-79</span>
                        <h3 class="font-bold text-slate-900">Direction</h3>
                    </div>
                    <p class="text-sm text-slate-600">Direction et supervision, accès aux rapports et tableaux de bord.</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mr-3">40-59</span>
                        <h3 class="font-bold text-slate-900">Responsables</h3>
                    </div>
                    <p class="text-sm text-slate-600">Responsabilités de gestion, supervision d'équipes ou de départements.</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 mr-3">20-39</span>
                        <h3 class="font-bold text-slate-900">Membres Actifs</h3>
                    </div>
                    <p class="text-sm text-slate-600">Membres engagés avec responsabilités spécifiques et permissions étendues.</p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-slate-50 border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 mr-3">10-19</span>
                        <h3 class="font-bold text-slate-900">Membres</h3>
                    </div>
                    <p class="text-sm text-slate-600">Membres standard avec accès aux fonctionnalités de base.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-center">
                <div class="bg-gradient-to-br from-slate-50 to-gray-50 border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all duration-300 max-w-md">
                    <div class="flex items-center justify-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-slate-100 text-slate-800 mr-3">0-9</span>
                        <h3 class="font-bold text-slate-900">Visiteurs</h3>
                    </div>
                    <p class="text-sm text-slate-600 text-center">Accès limité, généralement en lecture seule ou pour les nouveaux utilisateurs.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
let currentView = 'hierarchy';

// Basculer entre les vues
function toggleView() {
    const hierarchyView = document.getElementById('hierarchy-view');
    const graphView = document.getElementById('graph-view');
    const toggleText = document.getElementById('view-toggle-text');

    if (currentView === 'hierarchy') {
        hierarchyView.classList.add('hidden');
        graphView.classList.remove('hidden');
        toggleText.textContent = 'Vue Hiérarchique';
        currentView = 'graph';
        generateHierarchyGraph();
    } else {
        hierarchyView.classList.remove('hidden');
        graphView.classList.add('hidden');
        toggleText.textContent = 'Vue Graphique';
        currentView = 'hierarchy';
    }
}

// Changer le mode d'affichage
function changeDisplayMode() {
    const mode = document.getElementById('display-mode').value;
    const sections = document.querySelectorAll('.level-section');

    sections.forEach(section => {
        const roles = section.querySelectorAll('.role-item');
        let showSection = false;

        roles.forEach(role => {
            const isSystem = role.dataset.system === 'true';
            let showRole = true;

            switch(mode) {
                case 'system':
                    showRole = isSystem;
                    break;
                case 'custom':
                    showRole = !isSystem;
                    break;
                case 'non-empty':
                    showRole = true;
                    break;
                case 'all':
                default:
                    showRole = true;
                    break;
            }

            role.style.display = showRole ? 'block' : 'none';
            if (showRole) showSection = true;
        });

        if (mode === 'non-empty') {
            const visibleRoles = section.querySelectorAll('.role-item:not([style*="display: none"])');
            showSection = visibleRoles.length > 0;
        }

        section.style.display = showSection ? 'block' : 'none';
    });
}

// Filtrer par niveau
function filterByLevel() {
    const minLevel = parseInt(document.getElementById('min-level').value) || 0;
    const maxLevel = parseInt(document.getElementById('max-level').value) || 100;

    document.querySelectorAll('.role-item').forEach(role => {
        const level = parseInt(role.dataset.level);
        const show = level >= minLevel && level <= maxLevel;
        role.style.display = show ? 'block' : 'none';
    });

    document.querySelectorAll('.level-section').forEach(section => {
        const visibleRoles = section.querySelectorAll('.role-item:not([style*="display: none"])');
        section.style.display = visibleRoles.length > 0 ? 'block' : 'none';
    });
}

// Basculer une section de niveau
function toggleLevelSection(levelRange) {
    const content = document.getElementById(`level-${levelRange}`);
    const button = document.getElementById(`toggle-${levelRange}`);
    const icon = button.querySelector('i');

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.className = 'fas fa-chevron-down';
    } else {
        content.style.display = 'none';
        icon.className = 'fas fa-chevron-right';
    }
}

// Ouvrir toutes les sections
function expandAll() {
    document.querySelectorAll('.level-content').forEach(content => {
        content.style.display = 'block';
    });
    document.querySelectorAll('[id^="toggle-"] i').forEach(icon => {
        icon.className = 'fas fa-chevron-down';
    });
}

// Fermer toutes les sections
function collapseAll() {
    document.querySelectorAll('.level-content').forEach(content => {
        content.style.display = 'none';
    });
    document.querySelectorAll('[id^="toggle-"] i').forEach(icon => {
        icon.className = 'fas fa-chevron-right';
    });
}

// Générer le graphique hiérarchique
function generateHierarchyGraph() {
    const svg = document.getElementById('hierarchy-svg');
    svg.innerHTML = '';

    const width = svg.clientWidth;
    const height = 600;
    const levels = 7;
    const levelHeight = height / (levels + 1);

    const hierarchyData = <?php echo json_encode($hierarchy, 15, 512) ?>;
    const levelData = [];

    Object.keys(hierarchyData).forEach((levelName, index) => {
        const roles = hierarchyData[levelName];
        if (roles.length > 0) {
            levelData.push({
                name: levelName,
                roles: roles,
                y: (levels - index) * levelHeight,
                color: getLevelColor(levelName)
            });
        }
    });

    levelData.forEach((level, index) => {
        const y = level.y;
        const rolesCount = level.roles.length;
        const roleWidth = Math.min(120, (width - 100) / Math.max(rolesCount, 1));
        const startX = (width - (rolesCount * roleWidth)) / 2;

        // Ligne de niveau
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', 50);
        line.setAttribute('y1', y);
        line.setAttribute('x2', width - 50);
        line.setAttribute('y2', y);
        line.setAttribute('stroke', '#e2e8f0');
        line.setAttribute('stroke-width', 2);
        svg.appendChild(line);

        // Label du niveau
        const label = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        label.setAttribute('x', 10);
        label.setAttribute('y', y + 5);
        label.setAttribute('font-size', '12');
        label.setAttribute('font-weight', 'bold');
        label.setAttribute('fill', level.color);
        label.textContent = level.name.split(' ')[0];
        svg.appendChild(label);

        // Dessiner les rôles
        level.roles.forEach((role, roleIndex) => {
            const x = startX + (roleIndex * roleWidth);

            // Rectangle du rôle
            const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            rect.setAttribute('x', x);
            rect.setAttribute('y', y - 20);
            rect.setAttribute('width', roleWidth - 10);
            rect.setAttribute('height', 40);
            rect.setAttribute('fill', role.is_system_role ? '#fbbf24' : level.color);
            rect.setAttribute('stroke', '#334155');
            rect.setAttribute('stroke-width', 1);
            rect.setAttribute('rx', 8);
            rect.style.cursor = 'pointer';
            rect.style.transition = 'all 0.2s';

            rect.addEventListener('mouseenter', () => {
                rect.setAttribute('stroke-width', 2);
                rect.style.filter = 'brightness(110%)';
            });

            rect.addEventListener('mouseleave', () => {
                rect.setAttribute('stroke-width', 1);
                rect.style.filter = 'brightness(100%)';
            });

            rect.addEventListener('click', () => {
                window.location.href = `/admin/roles/${role.id}`;
            });

            svg.appendChild(rect);

            // Texte du rôle
            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', x + (roleWidth - 10) / 2);
            text.setAttribute('y', y + 5);
            text.setAttribute('font-size', '10');
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('fill', '#1e293b');
            text.setAttribute('font-weight', 'bold');
            text.textContent = role.name.length > 12 ? role.name.substring(0, 12) + '...' : role.name;
            text.style.cursor = 'pointer';

            text.addEventListener('click', () => {
                window.location.href = `/admin/roles/${role.id}`;
            });

            svg.appendChild(text);

            // Nombre d'utilisateurs
            const userCount = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            userCount.setAttribute('x', x + (roleWidth - 10) / 2);
            userCount.setAttribute('y', y + 15);
            userCount.setAttribute('font-size', '8');
            userCount.setAttribute('text-anchor', 'middle');
            userCount.setAttribute('fill', '#64748b');
            userCount.textContent = `${role.users_count || 0} utilisateurs`;
            svg.appendChild(userCount);
        });
    });
}

// Obtenir la couleur d'un niveau
function getLevelColor(levelName) {
    if (levelName.includes('100')) return '#dc2626';
    if (levelName.includes('80-99')) return '#d97706';
    if (levelName.includes('60-79')) return '#2563eb';
    if (levelName.includes('40-59')) return '#7c3aed';
    if (levelName.includes('20-39')) return '#059669';
    if (levelName.includes('10-19')) return '#6b7280';
    return '#374151';
}

// Exporter la hiérarchie
function exportHierarchy() {
    const hierarchyData = <?php echo json_encode($hierarchy, 15, 512) ?>;

    let csv = 'Niveau,Nom,Slug,Type,Utilisateurs,Permissions,Description\n';
console.log("*************************************", hierarchyData);
    Object.keys(hierarchyData).forEach(levelName => {

        hierarchyData[levelName].forEach(role => {
            console.log("*************************************", levelName);
            csv += `"${levelName}","${role.name}","${role.slug}","${role.is_system_role ? 'Système' : 'Personnalisé'}","${role.users_count || 0}","${role.permissions_count || 0}","${role.description || ''}"\n`;
        });
    });

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `hierarchie_roles_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    changeDisplayMode();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/roles/hierarchy.blade.php ENDPATH**/ ?>