<?php $__env->startSection('title', 'Comparaison de Rôles'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Comparaison de Rôles</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo e(route('private.roles.index')); ?>"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-users mr-2"></i>
                            Rôles
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Comparaison</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="<?php echo e(route('private.roles.index')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                <button type="button" onclick="exportComparison()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>
                <button type="button" onclick="printComparison()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>

        <!-- Résumé des rôles comparés -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-balance-scale text-purple-600 mr-2"></i>
                    Rôles Comparés (<?php echo e($roles->count()); ?>)
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-<?php echo e(min($roles->count(), 4)); ?> gap-6">
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            class="bg-gradient-to-br from-<?php echo e($loop->index % 2 == 0 ? 'slate' : 'blue'); ?>-50 to-<?php echo e($loop->index % 2 == 0 ? 'blue' : 'purple'); ?>-50 rounded-xl p-6 border border-<?php echo e($loop->index % 2 == 0 ? 'slate' : 'blue'); ?>-200 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="mb-4">
                                    <?php if($role->is_system_role): ?>
                                        <i class="fas fa-lock text-yellow-500 text-lg mb-2"></i>
                                    <?php endif; ?>
                                    <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo e($role->name); ?></h3>
                                    <code
                                        class="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded"><?php echo e($role->slug); ?></code>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        <?php if($role->level >= 100): ?> bg-red-100 text-red-800
                                        <?php elseif($role->level >= 80): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($role->level >= 60): ?> bg-blue-100 text-blue-800
                                        <?php elseif($role->level >= 40): ?> bg-purple-100 text-purple-800
                                        <?php elseif($role->level >= 20): ?> bg-green-100 text-green-800
                                        <?php elseif($role->level >= 10): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-slate-100 text-slate-800 <?php endif; ?>">
                                            Niveau <?php echo e($role->level); ?>

                                        </span>
                                    </div>

                                    <div>
                                        <?php if($role->is_system_role): ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-lock mr-1"></i> Système
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-cog mr-1"></i> Personnalisé
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-blue-600"><?php echo e($role->permissions->count()); ?>

                                            </div>
                                            <div class="text-xs text-slate-500">Permissions</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-green-600"><?php echo e($role->users->count()); ?></div>
                                            <div class="text-xs text-slate-500">Utilisateurs</div>
                                        </div>
                                    </div>
                                </div>

                                <?php if($role->description): ?>
                                    <div class="mt-4">
                                        <p class="text-sm text-slate-600"><?php echo e(Str::limit($role->description, 100)); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-4">
                                    <a href="<?php echo e(route('private.roles.show', $role)); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-white/50 text-blue-600 text-sm font-medium rounded-lg hover:bg-white/80 transition-colors border border-blue-200">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Statistiques de comparaison -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-double text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e(collect($comparison)->filter(function ($item) {return collect($item['roles'])->contains(true);})->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">Permissions communes</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i><?php echo e($roles->count()); ?>

                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e(collect($comparison)->filter(function ($item) use ($roles) {
                                    $roleCounts = collect($item['roles'])->filter()->count();
                                    return $roleCounts > 0 && $roleCounts < $roles->count();
                                })->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">Permissions partielles</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-list text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(count($comparison)); ?></p>
                        <p class="text-sm text-slate-500">Total permissions</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-equals text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e(collect($comparison)->filter(function ($item) use ($roles) {return collect($item['roles'])->filter()->count() === $roles->count();})->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">Permissions identiques</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et options -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-amber-600 mr-2"></i>
                    Filtres et Options
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" id="search-permissions" placeholder="Nom ou slug de permission..."
                                onkeyup="filterComparison()"
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                        <select id="filter-category" onchange="filterComparison()"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les catégories</option>
                            <?php $__currentLoopData = collect($comparison)->pluck('permission.category')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category); ?>"><?php echo e(ucfirst($category)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                        <select id="filter-status" onchange="filterComparison()"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les statuts</option>
                            <option value="all">Tous les rôles</option>
                            <option value="none">Aucun rôle</option>
                            <option value="partial">Quelques rôles</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Actions</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="expandAll()"
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-expand mr-1"></i> Ouvrir
                            </button>
                            <button type="button" onclick="collapseAll()"
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-compress mr-1"></i> Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrice de comparaison -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-table text-indigo-600 mr-2"></i>
                    Matrice de Comparaison
                </h2>
                <p class="text-slate-500 mt-1">Comparaison détaillée des permissions par rôle</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full comparison-table" id="comparison-table">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                                <th class="px-4 py-4 text-left permission-column" style="min-width: 300px;">
                                    <div class="flex items-center">
                                        <span class="font-semibold">Permission</span>
                                        <button type="button" onclick="sortTable()"
                                            class="ml-2 p-1 text-slate-300 hover:text-white transition-colors rounded">
                                            <i class="fas fa-sort text-sm"></i>
                                        </button>
                                    </div>
                                </th>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-4 py-4 text-center role-column" style="min-width: 120px;">
                                        <div class="role-header">
                                            <div class="font-semibold text-sm mb-1">
                                                <?php echo e($role->name); ?>

                                                <?php if($role->is_system_role): ?>
                                                    <i class="fas fa-lock text-yellow-300 ml-1"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                <?php if($role->level >= 100): ?> bg-red-200 text-red-800
                                                <?php elseif($role->level >= 80): ?> bg-yellow-200 text-yellow-800
                                                <?php elseif($role->level >= 60): ?> bg-blue-200 text-blue-800
                                                <?php elseif($role->level >= 40): ?> bg-purple-200 text-purple-800
                                                <?php elseif($role->level >= 20): ?> bg-green-200 text-green-800
                                                <?php elseif($role->level >= 10): ?> bg-gray-200 text-gray-800
                                                <?php else: ?> bg-slate-200 text-slate-800 <?php endif; ?>">
                                                    <?php echo e($role->level); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="px-4 py-4 text-center" style="min-width: 100px;">
                                    <span class="font-semibold">Total</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php
                                $currentCategory = null;
                            ?>
                            <?php $__currentLoopData = $comparison; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($currentCategory !== $item['permission']->category): ?>
                                    <?php $currentCategory = $item['permission']->category; ?>
                                    <tr class="category-separator bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                                        <td colspan="<?php echo e($roles->count() + 2); ?>" class="px-4 py-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-folder mr-2"></i>
                                                    <span
                                                        class="font-semibold category-name"><?php echo e(ucfirst($currentCategory)); ?></span>
                                                </div>
                                                <button type="button" onclick="toggleCategory('<?php echo e($currentCategory); ?>')"
                                                    id="toggle-<?php echo e($currentCategory); ?>"
                                                    class="p-1 text-blue-200 hover:text-white transition-colors rounded">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr class="permission-row category-<?php echo e($currentCategory); ?> hover:bg-slate-50 transition-colors"
                                    data-permission-name="<?php echo e(strtolower($item['permission']->name)); ?>"
                                    data-permission-slug="<?php echo e(strtolower($item['permission']->slug)); ?>"
                                    data-category="<?php echo e($currentCategory); ?>"
                                    data-role-count="<?php echo e(collect($item['roles'])->filter()->count()); ?>">
                                    <td class="px-4 py-4 permission-info">
                                        <div class="permission-details">
                                            <div class="font-semibold text-slate-900 mb-1"><?php echo e($item['permission']->name); ?>

                                            </div>
                                            <?php if($item['permission']->description): ?>
                                                <div class="text-sm text-slate-600 mb-2">
                                                    <?php echo e($item['permission']->description); ?></div>
                                            <?php endif; ?>
                                            <code
                                                class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded"><?php echo e($item['permission']->slug); ?></code>
                                        </div>
                                    </td>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-4 py-4 text-center role-permission">
                                            <?php if($item['roles'][$role->id]): ?>
                                                <div
                                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                                                    <i class="fas fa-check text-green-600 text-lg"></i>
                                                </div>
                                            <?php else: ?>
                                                <div
                                                    class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                                                    <i class="fas fa-times text-red-600"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="px-4 py-4 text-center total-column">
                                        <?php $totalCount = collect($item['roles'])->filter()->count(); ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($totalCount === $roles->count()): ?> bg-green-100 text-green-800
                                        <?php elseif($totalCount === 0): ?> bg-red-100 text-red-800
                                        <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                            <?php echo e($totalCount); ?>/<?php echo e($roles->count()); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Légende
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Permissions</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <span class="text-slate-700">Permission accordée au rôle</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                                <span class="text-slate-700">Permission non accordée au rôle</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Badges de total</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-3">X/X</span>
                                <span class="text-slate-700">Tous les rôles ont cette permission</span>
                            </li>
                            <li class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-3">X/X</span>
                                <span class="text-slate-700">Quelques rôles ont cette permission</span>
                            </li>
                            <li class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-3">0/X</span>
                                <span class="text-slate-700">Aucun rôle n'a cette permission</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Filtrage de la comparaison
            function filterComparison() {
                const searchTerm = document.getElementById('search-permissions').value.toLowerCase();
                const categoryFilter = document.getElementById('filter-category').value;
                const statusFilter = document.getElementById('filter-status').value;

                document.querySelectorAll('.permission-row').forEach(row => {
                    const name = row.dataset.permissionName;
                    const slug = row.dataset.permissionSlug;
                    const category = row.dataset.category;
                    const roleCount = parseInt(row.dataset.roleCount);
                    const totalRoles = <?php echo e($roles->count()); ?>;

                    let show = true;

                    // Filtre de recherche
                    if (searchTerm && !name.includes(searchTerm) && !slug.includes(searchTerm)) {
                        show = false;
                    }

                    // Filtre de catégorie
                    if (categoryFilter && category !== categoryFilter) {
                        show = false;
                    }

                    // Filtre de statut
                    if (statusFilter === 'all' && roleCount !== totalRoles) {
                        show = false;
                    } else if (statusFilter === 'none' && roleCount !== 0) {
                        show = false;
                    } else if (statusFilter === 'partial' && (roleCount === 0 || roleCount === totalRoles)) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';
                });

                // Masquer les en-têtes de catégorie vides
                document.querySelectorAll('.category-separator').forEach(separator => {
                    const categoryName = separator.querySelector('.category-name').textContent.trim().toLowerCase();
                    const visibleRows = document.querySelectorAll(
                        `.category-${categoryName.replace(/\s+/g, '')}:not([style*="display: none"])`);
                    separator.style.display = visibleRows.length > 0 ? '' : 'none';
                });
            }

            // Basculer l'affichage d'une catégorie
            function toggleCategory(category) {
                const rows = document.querySelectorAll(`.category-${category}`);
                const button = document.getElementById(`toggle-${category}`);
                const icon = button.querySelector('i');

                const isHidden = rows[0] && rows[0].style.display === 'none';

                rows.forEach(row => {
                    row.style.display = isHidden ? '' : 'none';
                });

                icon.className = isHidden ? 'fas fa-chevron-down' : 'fas fa-chevron-right';
            }

            // Ouvrir toutes les catégories
            function expandAll() {
                document.querySelectorAll('.permission-row').forEach(row => {
                    row.style.display = '';
                });
                document.querySelectorAll('[id^="toggle-"] i').forEach(icon => {
                    icon.className = 'fas fa-chevron-down';
                });
            }

            // Fermer toutes les catégories
            function collapseAll() {
                document.querySelectorAll('.permission-row').forEach(row => {
                    row.style.display = 'none';
                });
                document.querySelectorAll('[id^="toggle-"] i').forEach(icon => {
                    icon.className = 'fas fa-chevron-right';
                });
            }

            // Trier le tableau
            function sortTable() {

                const table = document.getElementById('comparison-table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('.permission-row'));

                // Alterner entre tri par nom et par nombre de rôles
                const currentSort = table.dataset.sort || 'name';
                const newSort = currentSort === 'name' ? 'count' : 'name';

                rows.sort((a, b) => {
                    if (newSort === 'name') {
                        return a.dataset.permissionName.localeCompare(b.dataset.permissionName);
                    } else {
                        return parseInt(b.dataset.roleCount) - parseInt(a.dataset.roleCount);
                    }
                });

                // Reconstruire le tbody
                tbody.innerHTML = '';
                let currentCategory = null;

                rows.forEach(row => {
                    const category = row.dataset.category;
                    if (currentCategory !== category) {
                        currentCategory = category;
                        // Ajouter l'en-tête de catégorie
                        const separator = document.createElement('tr');
                        separator.className =
                            'category-separator bg-gradient-to-r from-blue-600 to-purple-600 text-white';
                        separator.innerHTML = `
                <td colspan="<?php echo e($roles->count() + 2); ?>" class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-folder mr-2"></i>
                            <span class="font-semibold category-name">${category.charAt(0).toUpperCase() + category.slice(1)}</span>
                        </div>
                        <button type="button" onclick="toggleCategory('${category}')" id="toggle-${category}" class="p-1 text-blue-200 hover:text-white transition-colors rounded">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </td>
            `;
                        tbody.appendChild(separator);
                    }
                    tbody.appendChild(row);
                });

                table.dataset.sort = newSort;
            }

            // Exporter la comparaison
            function exportComparison() {
                const roles = <?php echo json_encode($roles->pluck('name'), 15, 512) ?>;
                const comparison = <?php echo json_encode($comparison, 15, 512) ?>;

                let csv = 'Permission,Catégorie,Description,Slug,' + roles.join(',') + ',Total\n';

                comparison.forEach(item => {
                    const permission = item.permission;
                    const roleValues = roles.map(roleName => {
                        const rolesData = <?php echo json_encode($roles, 15, 512) ?>;
                        const role = rolesData.find(r => r.name === roleName);
                        return item.roles[role.id] ? 'Oui' : 'Non';
                    });
                    const total = Object.values(item.roles).filter(v => v).length;

                    csv +=
                        `"${permission.name}","${permission.category}","${permission.description || ''}","${permission.slug}",${roleValues.join(',')},${total}\n`;
                });

                const blob = new Blob([csv], {
                    type: 'text/csv'
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `comparaison_roles_${new Date().toISOString().split('T')[0]}.csv`;
                a.click();
            }

            // Imprimer la comparaison
            function printComparison() {
                window.print();
            }

            // CSS pour l'impression
            const printStyles = `
    @media print {
        .space-y-8 > *:not(:has(.comparison-table)) { display: none !important; }
        .comparison-table { font-size: 10px !important; }
        .permission-column { max-width: 200px !important; }
        .role-column { max-width: 80px !important; }
        .sticky { position: static !important; }
    }
`;

            // Ajouter les styles d'impression
            const styleSheet = document.createElement('style');
            styleSheet.textContent = printStyles;
            document.head.appendChild(styleSheet);
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/roles/compare.blade.php ENDPATH**/ ?>