<?php $__env->startSection('title', 'Gestion des Dons'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Dons</h1>
        <p class="text-slate-500 mt-1">Administration des dons - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('donation.create')): ?>
                        <a href="<?php echo e(route('private.dons.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Don
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dons.export')): ?>
                        <a href="<?php echo e(route('private.dons.export')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dons.statistics')): ?>
                        <a href="<?php echo e(route('private.dons.statistiques')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dons.dashboard')): ?>
                        <a href="<?php echo e(route('private.dons.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.dons.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nom, prénom, téléphone..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Paramètre</label>
                    <select name="parametre" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous paramètres</option>
                        <?php if(isset($parametres)): ?>
                            <?php $__currentLoopData = $parametres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($param->id); ?>" <?php echo e(request('parametre') == $param->id ? 'selected' : ''); ?>>
                                    <?php echo e($param->operateur); ?> - <?php echo e($param->type_libelle); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                    <select name="devise" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes devises</option>
                        <?php $__currentLoopData = \App\Models\Don::DEVISES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $libelle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e(request('devise') == $code ? 'selected' : ''); ?>><?php echo e($libelle); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toute période</option>
                        <option value="aujourd_hui" <?php echo e(request('periode') == 'aujourd_hui' ? 'selected' : ''); ?>>Aujourd'hui</option>
                        <option value="cette_semaine" <?php echo e(request('periode') == 'cette_semaine' ? 'selected' : ''); ?>>Cette semaine</option>
                        <option value="ce_mois" <?php echo e(request('periode') == 'ce_mois' ? 'selected' : ''); ?>>Ce mois</option>
                        <option value="cette_annee" <?php echo e(request('periode') == 'cette_annee' ? 'selected' : ''); ?>>Cette année</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Montant min</label>
                    <input type="number" name="montant_min" value="<?php echo e(request('montant_min')); ?>" placeholder="0" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.dons.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <?php if(isset($statistiques)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['total_dons'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total dons</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['montant_total'] ?? 0, 0, ',', ' ')); ?></p>
                    <p class="text-sm text-slate-500">Montant total</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-day text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['dons_aujourd_hui'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Dons aujourd'hui</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['dons_ce_mois'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Dons ce mois</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liste des dons -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Dons (<?php echo e($dons->total()); ?>)
                </h2>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dons.manage')): ?>
                    <button type="button" onclick="showBulkActions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-cogs mr-2"></i> Actions en lot
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="p-6">
            <?php if($dons->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Donateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Téléphone</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Devise</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Opérateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Preuve</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php $__currentLoopData = $dons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $don): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="selected_dons[]" value="<?php echo e($don->id); ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 don-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-medium">
                                                    <?php echo e(substr($don->prenom_donateur, 0, 1)); ?><?php echo e(substr($don->nom_donateur, 0, 1)); ?>

                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900"><?php echo e($don->nom_complet); ?></div>
                                                <div class="text-sm text-slate-500">Don #<?php echo e($don->id); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div>
                                            <div class="font-medium text-slate-900"><?php echo e($don->telephone_1); ?></div>
                                            <?php if($don->telephone_2): ?>
                                                <div class="text-sm text-slate-500"><?php echo e($don->telephone_2); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-lg font-bold text-green-600"><?php echo e(number_format($don->montant, 2, ',', ' ')); ?></span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php switch($don->devise):
                                                case ('XOF'): ?> bg-orange-100 text-orange-800 <?php break; ?>
                                                <?php case ('EUR'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                                <?php case ('USD'): ?> bg-green-100 text-green-800 <?php break; ?>
                                                <?php default: ?> bg-gray-100 text-gray-800 <?php break; ?>
                                            <?php endswitch; ?>">
                                            <?php echo e($don->devise); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if($don->parametreDon): ?>
                                            <div>
                                                <div class="font-medium text-slate-900"><?php echo e($don->parametreDon->operateur); ?></div>
                                                <div class="text-sm text-slate-500"><?php echo e($don->parametreDon->type_libelle); ?></div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-900"><?php echo e($don->created_at->format('d/m/Y')); ?></div>
                                        <div class="text-sm text-slate-500"><?php echo e($don->created_at->format('H:i')); ?></div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if($don->aUnePreuve()): ?>
                                            <a href="<?php echo e(route('private.dons.telechargerPreuve', $don)); ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                                <i class="fas fa-download mr-1"></i> Télécharger
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i> Aucune
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('donation.read')): ?>
                                                <a href="<?php echo e(route('private.dons.show', $don)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
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
                        Affichage de <span class="font-medium"><?php echo e($dons->firstItem()); ?></span> à <span class="font-medium"><?php echo e($dons->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($dons->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($dons->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun don trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'parametre', 'devise', 'periode', 'montant_min'])): ?>
                            Aucun don ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Commencez par enregistrer votre premier don.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dons.create')): ?>
                        <a href="<?php echo e(route('private.dons.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Enregistrer un don
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
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce don ?</p>
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

<!-- Modal Actions en lot -->
<div id="bulkActionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-cogs text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Actions en lot</h3>
            </div>
            <div class="space-y-4">
                <button type="button" onclick="bulkAction('export')" class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Exporter sélectionnés
                </button>
                
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeBulkActionsModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
        </div>
    </div>
</div>

<script>
// Sélection multiple
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.don-checkbox');
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

function showBulkActions() {
    const selected = document.querySelectorAll('.don-checkbox:checked');
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un don');
        return;
    }
    document.getElementById('bulkActionsModal').classList.remove('hidden');
}

function closeBulkActionsModal() {
    document.getElementById('bulkActionsModal').classList.add('hidden');
}

// Suppression d'un don
function deleteDon(donId) {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`<?php echo e(route('private.dons.destroy', ':donId')); ?>`.replace(':donId', donId), {
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

// Dupliquer un don
function duplicateDon(donId) {
    if (confirm('Voulez-vous dupliquer ce don ?')) {
        window.location.href = "<?php echo e(route('private.dons.dupliquer', ':donId')); ?>".replace(':donId', donId);
    }
}

// Actions en lot
function bulkAction(action) {
    const selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
                         .map(cb => cb.value);

    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un don');
        return;
    }

    if (!confirm(`Êtes-vous sûr de vouloir ${action} ${selected.length} don(s) ?`)) {
        return;
    }

    // Traitement selon l'action
    if (action === 'export') {
        // Export CSV des éléments sélectionnés
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "<?php echo e(route('private.dons.export')); ?>";

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrfInput);

        selected.forEach(donId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = donId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
}

// Close modals when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('bulkActionsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkActionsModal();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/dons/index.blade.php ENDPATH**/ ?>