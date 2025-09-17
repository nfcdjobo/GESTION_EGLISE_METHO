<?php $__env->startSection('title', 'Détails du Rôle: ' . $role->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-2">
            <?php if($role->is_system_role): ?>
                <i class="fas fa-lock text-yellow-500 text-xl"></i>
            <?php endif; ?>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent"><?php echo e($role->name); ?></h1>
        </div>
        <nav class="flex" aria-label="Breadcrumb">
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
                        <span class="text-sm font-medium text-slate-500"><?php echo e($role->name); ?></span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions principales -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 p-6">
        <div class="flex flex-wrap justify-center gap-3">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.update')): ?>
                <a href="<?php echo e(route('private.roles.edit', $role)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.manage')): ?>
                <a href="<?php echo e(route('private.roles.permissions', $role)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-key mr-2"></i> Gérer les Permissions
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                <button type="button" onclick="cloneRole()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i> Cloner
                </button>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
                <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i> Attribuer à un membres
                </button>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.delete')): ?>
                <?php if($role->canBeDeleted()): ?>
                    <button type="button" onclick="deleteRole()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations du rôle et Statistiques -->
        <div class="space-y-6">
            <!-- Informations du rôle -->
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations du Rôle
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-sm font-medium text-slate-700">Nom:</span>
                        <div class="mt-1 flex items-center space-x-2">
                            <?php if($role->is_system_role): ?>
                                <i class="fas fa-lock text-yellow-500" title="Rôle système"></i>
                            <?php endif; ?>
                            <span class="font-semibold text-slate-900"><?php echo e($role->name); ?></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Slug:</span>
                        <div class="mt-1">
                            <code class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded"><?php echo e($role->slug); ?></code>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Description:</span>
                        <div class="mt-1 text-slate-600">
                            <?php echo e($role->description ?: 'Aucune description'); ?>

                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Niveau hiérarchique:</span>
                        <div class="mt-1 flex items-center space-x-2">
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
                            <span class="text-sm text-slate-600">
                                <?php if($role->level >= 100): ?>
                                    Super Admin
                                <?php elseif($role->level >= 80): ?>
                                    Administration
                                <?php elseif($role->level >= 60): ?>
                                    Direction
                                <?php elseif($role->level >= 40): ?>
                                    Responsable
                                <?php elseif($role->level >= 20): ?>
                                    Membre Actif
                                <?php elseif($role->level >= 10): ?>
                                    Membre
                                <?php else: ?>
                                    Visiteur
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Type:</span>
                        <div class="mt-1">
                            <?php if($role->is_system_role): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-lock mr-1"></i> Rôle Système
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-cog mr-1"></i> Rôle Personnalisé
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Créé le:</span>
                        <div class="mt-1 text-sm text-slate-600">
                            <?php echo e($role->created_at->format('d/m/Y à H:i')); ?>

                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Dernière modification:</span>
                        <div class="mt-1 text-sm text-slate-600">
                            <?php echo e($role->updated_at->format('d/m/Y à H:i')); ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-blue-800"><?php echo e($stats['total_users']); ?></p>
                                <p class="text-sm text-blue-600">Membress Total</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-green-800"><?php echo e($stats['active_users']); ?></p>
                                <p class="text-sm text-green-600">Membress Actifs</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-purple-800"><?php echo e($stats['total_permissions']); ?></p>
                                <p class="text-sm text-purple-600">Permissions</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-key text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <?php if($stats['expiring_soon'] > 0): ?>
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-xl border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-yellow-800"><?php echo e($stats['expiring_soon']); ?></p>
                                    <p class="text-sm text-yellow-700">Expire sous 7 jours</p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="lg:col-span-2">
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-key text-amber-600 mr-2"></i>
                            Permissions (<?php echo e($stats['total_permissions']); ?>)
                        </h2>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.manage')): ?>
                            <a href="<?php echo e(route('private.roles.permissions', $role)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Gérer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-6">
                    <?php if($permissions->count() > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="permission-category border border-slate-200 rounded-xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-4 border-b border-slate-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="text-lg font-semibold text-slate-800"><?php echo e(ucfirst($category)); ?></h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                                    <?php echo e($categoryPermissions->count()); ?>

                                                </span>
                                            </div>
                                            <button type="button" onclick='toggleCategoryVisibility("<?php echo e($category); ?>")' class="p-2 text-slate-600 hover:text-slate-800 hover:bg-white/50 rounded-lg transition-colors">
                                                <i class="fas fa-chevron-down transition-transform duration-200" id="icon-<?php echo e($category); ?>"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="category-permissions p-4" id="permissions-<?php echo e($category); ?>">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <?php $__currentLoopData = $categoryPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-4 border border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                                            <i class="fas fa-check text-green-600 text-xs"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <h4 class="font-semibold text-slate-900"><?php echo e($permission->name); ?></h4>
                                                            <?php if($permission->description): ?>
                                                                <p class="text-sm text-slate-600 mt-1"><?php echo e($permission->description); ?></p>
                                                            <?php endif; ?>
                                                            <code class="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded mt-2 inline-block"><?php echo e($permission->slug); ?></code>
                                                            <?php if($permission->pivot->expire_le): ?>
                                                                <div class="mt-2 flex items-center text-xs text-yellow-600">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    Expire le <?php echo e(\Carbon\Carbon::parse($permission->pivot->expire_le)->format('d/m/Y')); ?>

                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-key text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune permission attribuée</h3>
                            <p class="text-slate-500 mb-6">Ce rôle n'a aucune permission pour le moment.</p>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.manage')): ?>
                                <a href="<?php echo e(route('private.roles.permissions', $role)); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-plus mr-2"></i> Attribuer des permissions
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Membress récents -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-indigo-600 mr-2"></i>
                    Membress Récents
                </h2>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
                    <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Attribuer
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="p-6">
            <?php if($recentUsers->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Membres</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Attribué le</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Attribué par</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Expire le</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $attribue_par = \App\Models\User::find($user->pivot->attribue_par)?->id ?? null;
                                    $authUserId = Auth::id();
                                ?>

                                <?php if($user->isSuperAdmin() && $attribue_par === $authUserId): ?>
                                    <?php continue; ?>
                                <?php endif; ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <?php if($user->avatar): ?>
                                                <img src="<?php echo e($user->avatar); ?>" class="w-10 h-10 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                    <?php echo e(strtoupper(substr($user->nom_complet, 0, 1))); ?>

                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-semibold text-slate-900 flex items-center">
                                                    <?php echo e($user->nom_complet); ?>

                                                    <?php if($user->pivot->actif && (!$user->pivot->expire_le || $user->pivot->expire_le > now())): ?>
                                                        <i class="fas fa-check-circle text-green-500 ml-2" title="Actif"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600"><?php echo e($user->email); ?></td>
                                    <td class="px-4 py-4 text-sm text-slate-600"><?php echo e(\Carbon\Carbon::parse($user->pivot->attribue_le)->format('d/m/Y H:i')); ?></td>
                                    <td class="px-4 py-4 text-sm text-slate-600">
                                        <?php if($user->pivot->attribue_par): ?>
                                            <?php echo e(\App\Models\User::find($user->pivot->attribue_par)?->nom_complet ?? 'N/A'); ?>

                                        <?php else: ?>
                                            <span class="text-slate-400">Système</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <?php if($user->pivot->expire_le): ?>
                                            <?php
                                                $expireDate = \Carbon\Carbon::parse($user->pivot->expire_le);
                                                $isExpiringSoon = $expireDate->diffInDays(now()) <= 7 && $expireDate->isFuture();
                                                $isExpired = $expireDate->isPast();
                                            ?>
                                            <span class="
                                                <?php if($isExpired): ?> text-red-600
                                                <?php elseif($isExpiringSoon): ?> text-yellow-600
                                                <?php else: ?> text-slate-600
                                                <?php endif; ?>">
                                                <?php echo e($expireDate->format('d/m/Y')); ?>

                                                <?php if($isExpiringSoon): ?>
                                                    <i class="fas fa-exclamation-triangle ml-1"></i>
                                                <?php elseif($isExpired): ?>
                                                    <i class="fas fa-times-circle ml-1"></i>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400">Permanent</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if($user->pivot->actif): ?>
                                            <?php if(!$user->pivot->expire_le || $user->pivot->expire_le > now()): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Expiré</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
                                            <button type="button" onclick="removeUserRole('<?php echo e($user->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun membres</h3>
                    <p class="text-slate-500 mb-6">Ce rôle n'est attribué à aucun membres pour le moment.</p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
                        <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i> Attribuer à un membres
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'attribution d'membres -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
<div id="assignUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Attribuer le rôle à un membres</h3>
                <button type="button" onclick="closeAssignUserModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="assignUserForm" class="p-6 space-y-4">
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">Membres</label>
                <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Sélectionnez un membres...</option>
                </select>
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-slate-700 mb-2">Date d'expiration (optionnel)</label>
                <input type="datetime-local" id="expires_at" name="expires_at" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <p class="mt-1 text-sm text-slate-500">Laissez vide pour une attribution permanente</p>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-slate-700 mb-2">Raison (optionnel)</label>
                <textarea id="reason" name="reason" rows="3" placeholder="Motif de l'attribution..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <button type="button" onclick="closeAssignUserModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Attribuer
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
// Basculer la visibilité des catégories de permissions
function toggleCategoryVisibility(category) {
    const element = document.getElementById(`permissions-${category}`);
    const icon = document.getElementById(`icon-${category}`);

    if (element.style.display === 'none') {
        element.style.display = 'block';
        icon.style.transform = 'rotate(0deg)';
    } else {
        element.style.display = 'none';
        icon.style.transform = 'rotate(-90deg)';
    }
}

// Cloner le rôle
function cloneRole() {
    if (confirm('Voulez-vous cloner ce rôle ?')) {
        window.location.href = `<?php echo e(route('private.roles.clone', $role)); ?>`;
    }
}

// Supprimer le rôle
function deleteRole() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?\nCette action est irréversible.')) {
        fetch(`<?php echo e(route('private.roles.destroy', $role)); ?>`, {
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
                window.location.href = '<?php echo e(route("private.roles.index")); ?>';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.remove')): ?>
// Retirer le rôle d'un membres
function removeUserRole(userId) {
    if (confirm('Voulez-vous retirer ce rôle de cet membres ?')) {
        fetch(`<?php echo e(route('private.roles.remove.user', $role)); ?>`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.assign')): ?>
// Modal functions
function showAssignUserModal() {
    document.getElementById('assignUserModal').classList.remove('hidden');
    loadUsers();
}

function closeAssignUserModal() {
    document.getElementById('assignUserModal').classList.add('hidden');
    document.getElementById('assignUserForm').reset();
}

// Charger la liste des membres
function loadUsers() {
    fetch("<?php echo e(route('private.users.search')); ?>", {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(users => {
        const select = document.getElementById('user_id');

        select.innerHTML = '<option value="">Sélectionnez un membres...</option>';

        users.forEach(user => {
            // Exclure les membres qui ont déjà ce rôle
            const hasRole = <?php echo json_encode($role->users->pluck('id'), 15, 512) ?>.includes(user.id);
            if (!hasRole) {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.text}`;
                select.appendChild(option);
            }
        });
    })
    .catch(error => {
        console.error('Erreur lors du chargement des membres:', error);
    });
}

// Soumission du formulaire d'attribution
document.getElementById('assignUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch(`<?php echo e(route('private.roles.assign.user', $role)); ?>`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAssignUserModal();
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Close modal when clicking outside
document.getElementById('assignUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignUserModal();
    }
});
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/roles/show.blade.php ENDPATH**/ ?>