<?php $__env->startSection('title', 'Gestion des Rôles'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Rôles</h1>
        <p class="text-slate-500 mt-1">Administration des rôles et permissions - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                        <a href="<?php echo e(route('private.roles.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Rôle
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export-data', 'roles')): ?>
                        <a href="<?php echo e(route('private.roles.export')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.roles.hierarchy')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-sitemap mr-2"></i> Hiérarchie
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.roles.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nom, slug ou description..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="system" <?php echo e(request('type') == 'system' ? 'selected' : ''); ?>>Système</option>
                        <option value="custom" <?php echo e(request('type') == 'custom' ? 'selected' : ''); ?>>Personnalisé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Niveau Min</label>
                    <input type="number" name="min_level" value="<?php echo e(request('min_level')); ?>" min="0" max="100" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Niveau Max</label>
                    <input type="number" name="max_level" value="<?php echo e(request('max_level')); ?>" min="0" max="100" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tri</label>
                    <select name="sort" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="level" <?php echo e(request('sort') == 'level' ? 'selected' : ''); ?>>Niveau</option>
                        <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Nom</option>
                        <option value="users_count" <?php echo e(request('sort') == 'users_count' ? 'selected' : ''); ?>>Nb Utilisateurs</option>
                        <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Date création</option>
                    </select>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.roles.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80  rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($roles->total()); ?></p>
                    <p class="text-sm text-slate-500">Total des rôles</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80  rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($roles->where('is_system_role', true)->count()); ?></p>
                    <p class="text-sm text-slate-500">Rôles système</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80  rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($roles->where('is_system_role', false)->count()); ?></p>
                    <p class="text-sm text-slate-500">Rôles personnalisés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80  rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-friends text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($roles->sum('users_count')); ?></p>
                    <p class="text-sm text-slate-500">Utilisateurs assignés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rôles -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Rôles (<?php echo e($roles->total()); ?>)
                </h2>
                <button type="button" onclick="compareRoles()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-balance-scale mr-2"></i> Comparer
                </button>
            </div>
        </div>
        <div class="p-6">
            <?php if($roles->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nom</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Slug</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Niveau</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Utilisateurs</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Permissions</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="selected_roles[]" value="<?php echo e($role->id); ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 role-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <?php if($role->is_system_role): ?>
                                                <i class="fas fa-lock text-yellow-500" title="Rôle système"></i>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-semibold text-slate-900"><?php echo e($role->name); ?></div>
                                                <?php if($role->description): ?>
                                                    <div class="text-sm text-slate-500"><?php echo e(Str::limit($role->description, 50)); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <code class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded"><?php echo e($role->slug); ?></code>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($role->level >= 80): ?> bg-red-100 text-red-800
                                            <?php elseif($role->level >= 60): ?> bg-yellow-100 text-yellow-800
                                            <?php elseif($role->level >= 40): ?> bg-blue-100 text-blue-800
                                            <?php elseif($role->level >= 20): ?> bg-purple-100 text-purple-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e($role->level); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if($role->is_system_role): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-lock mr-1"></i> Système
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-cog mr-1"></i> Personnalisé
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?php echo e($role->users_count); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <?php echo e($role->permissions_count); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if($role->deleted_at): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-trash mr-1"></i> Supprimé
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Actif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.read')): ?>
                                                <a href="<?php echo e(route('private.roles.show', $role)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.update')): ?>
                                                <a href="<?php echo e(route('private.roles.edit', $role)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.manage')): ?>
                                                <a href="<?php echo e(route('private.roles.permissions', $role)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Permissions">
                                                    <i class="fas fa-key text-sm"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                                                <button type="button" onclick="cloneRole(<?php echo e($role->id); ?>)" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Cloner">
                                                    <i class="fas fa-copy text-sm"></i>
                                                </button>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.delete')): ?>
                                                <?php if($role->canBeDeleted()): ?>
                                                    <button type="button" onclick="deleteRole(<?php echo e($role->id); ?>)" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium"><?php echo e($roles->firstItem()); ?></span> à <span class="font-medium"><?php echo e($roles->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($roles->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($roles->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun rôle trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'type', 'min_level', 'max_level'])): ?>
                            Aucun rôle ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Commencez par créer votre premier rôle.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                        <a href="<?php echo e(route('private.roles.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un rôle
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce rôle ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible.</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>


<script>


// Sélection multiple
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.role-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Modal functions
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Suppression d'un rôle
function deleteRole(roleId) {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`<?php echo e(route('private.roles.destroy', ':role')); ?>`.replace(':role', roleId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
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
    };
}

// Clonage d'un rôle
function cloneRole(roleId) {
    if (confirm('Voulez-vous cloner ce rôle ?')) {
        window.location.href = `/admin/roles/${roleId}/clone`;
    }
}

// Comparaison de rôles
function compareRoles() {
    const selected = Array.from(document.querySelectorAll('.role-checkbox:checked'))
                         .map(cb => cb.value);

    if (selected.length < 2) {
        alert('Veuillez sélectionner au moins 2 rôles à comparer');
        return;
    }

    if (selected.length > 5) {
        alert('Vous ne pouvez comparer que 5 rôles maximum');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route("private.roles.compare")); ?>';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '<?php echo e(csrf_token()); ?>';
    form.appendChild(csrfToken);

    selected.forEach(roleId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'role_ids[]';
        input.value = roleId;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/roles/index.blade.php ENDPATH**/ ?>