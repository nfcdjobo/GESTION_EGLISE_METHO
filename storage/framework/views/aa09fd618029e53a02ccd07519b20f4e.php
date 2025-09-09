<?php $__env->startSection('title', 'Gestion des Classes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Classes</h1>
        <p class="text-slate-500 mt-1">Administration des classes et groupes - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.create')): ?>
                        <a href="<?php echo e(route('private.classes.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle Classe
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('private.classes.statistiques')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.classes.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nom, description ou tranche d'âge..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tranche d'âge</label>
                    <select name="tranche_age" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les tranches</option>
                        <option value="0-3 ans" <?php echo e(request('tranche_age') == '0-3 ans' ? 'selected' : ''); ?>>0-3 ans</option>
                        <option value="4-6 ans" <?php echo e(request('tranche_age') == '4-6 ans' ? 'selected' : ''); ?>>4-6 ans</option>
                        <option value="7-9 ans" <?php echo e(request('tranche_age') == '7-9 ans' ? 'selected' : ''); ?>>7-9 ans</option>
                        <option value="10-12 ans" <?php echo e(request('tranche_age') == '10-12 ans' ? 'selected' : ''); ?>>10-12 ans</option>
                        <option value="Adultes" <?php echo e(request('tranche_age') == 'Adultes' ? 'selected' : ''); ?>>Adultes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="actives_seulement" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes</option>
                        <option value="1" <?php echo e(request('actives_seulement') == '1' ? 'selected' : ''); ?>>Actives uniquement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Places disponibles</label>
                    <select name="avec_places" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes</option>
                        <option value="1" <?php echo e(request('avec_places') == '1' ? 'selected' : ''); ?>>Avec places disponibles</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tri</label>
                    <select name="sort" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="nom" <?php echo e(request('sort') == 'nom' ? 'selected' : ''); ?>>Nom</option>
                        <option value="nombre_inscrits" <?php echo e(request('sort') == 'nombre_inscrits' ? 'selected' : ''); ?>>Nb Inscrits</option>
                        <option value="tranche_age" <?php echo e(request('sort') == 'tranche_age' ? 'selected' : ''); ?>>Tranche d'âge</option>
                        <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Date création</option>
                    </select>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.classes.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($classes->total()); ?></p>
                    <p class="text-sm text-slate-500">Total des classes</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($classes->where('responsable_id', '!=', null)->count()); ?></p>
                    <p class="text-sm text-slate-500">Classes actives</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($classes->sum('nombre_inscrits')); ?></p>
                    <p class="text-sm text-slate-500">Total inscrits</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($classes->avg('nombre_inscrits'), 1)); ?></p>
                    <p class="text-sm text-slate-500">Moyenne par classe</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des classes -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Classes (<?php echo e($classes->total()); ?>)
                </h2>
                <div class="flex gap-2">
                    <button type="button" onclick="showBulkActions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-tasks mr-2"></i> Actions groupées
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if($classes->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-slate-200 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                            <!-- Image de la classe -->
                            <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500">
                                <?php if($classe->image_classe): ?>
                                    <img src="<?php echo e(asset('storage/' . $classe->image_classe)); ?>" alt="<?php echo e($classe->nom); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-chalkboard-teacher text-6xl text-white/80"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-3 right-3">
                                    <input type="checkbox" name="selected_classes[]" value="<?php echo e($classe->id); ?>" class="w-4 h-4 text-blue-600 bg-white/80 border-gray-300 rounded focus:ring-blue-500 classe-checkbox">
                                </div>
                                <?php if($classe->responsable_id): ?>
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Active
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Contenu de la carte -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-slate-900 line-clamp-1"><?php echo e($classe->nom); ?></h3>
                                    <div class="flex items-center space-x-1">
                                        <?php if($classe->tranche_age): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($classe->tranche_age); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($classe->description): ?>
                                    <p class="text-sm text-slate-600 mb-4 line-clamp-2"><?php echo e($classe->description); ?></p>
                                <?php endif; ?>

                                <!-- Statistiques -->
                                <div class="grid  gap-4 mb-4">
                                    <div class="text-center p-3 bg-slate-50 rounded-lg">
                                        <div class="text-lg font-bold text-slate-900"><?php echo e($classe->nombre_inscrits); ?></div>
                                        <div class="text-xs text-slate-500">Inscrits</div>
                                    </div>
                                </div>

                                <!-- Responsables -->
                                <?php if($classe->responsable || $classe->enseignantPrincipal): ?>
                                    <div class="mb-4 space-y-2">
                                        <?php if($classe->responsable): ?>
                                            <div class="flex items-center text-sm text-slate-600">
                                                <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                                                <span class="font-medium">Responsable:</span>
                                                <span class="ml-1"><?php echo e($classe->responsable->nom_complet); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($classe->enseignantPrincipal): ?>
                                            <div class="flex items-center text-sm text-slate-600">
                                                <i class="fas fa-chalkboard-teacher text-green-500 mr-2"></i>
                                                <span class="font-medium">Enseignant:</span>
                                                <span class="ml-1"><?php echo e($classe->enseignantPrincipal->nom_complet); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Barre de progression -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-slate-600 mb-1">
                                        <span>Taux de remplissage</span>
                                        <span><?php echo e($classe->pourcentage_remplissage); ?>%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo e(min($classe->pourcentage_remplissage, 100)); ?>%"></div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
                                            <a href="<?php echo e(route('private.classes.show', $classe)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                            <a href="<?php echo e(route('private.classes.edit', $classe)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                            <button type="button" onclick="showMemberModal('<?php echo e($classe->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Gérer les membres">
                                                <i class="fas fa-users text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
                                        <?php if($classe->nombre_inscrits == 0): ?>
                                            <button type="button" onclick="deleteClasse('<?php echo e($classe->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium"><?php echo e($classes->firstItem()); ?></span> à <span class="font-medium"><?php echo e($classes->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($classes->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($classes->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune classe trouvée</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'tranche_age', 'actives_seulement', 'avec_places'])): ?>
                            Aucune classe ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Commencez par créer votre première classe.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.create')): ?>
                        <a href="<?php echo e(route('private.classes.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer une classe
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
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cette classe ?</p>
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

<!-- Modal de gestion des membres -->
<div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Gérer les membres de la classe</h3>
        </div>
        <div id="memberModalContent" class="p-6">
            <!-- Contenu chargé dynamiquement -->
        </div>
    </div>
</div>

<script>
// Sélection multiple
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.classe-checkbox');
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

function showMemberModal(classeId) {
    document.getElementById('memberModal').classList.remove('hidden');
    // Charger le contenu des membres via AJAX
    loadMemberContent(classeId);
}

function closeMemberModal() {
    document.getElementById('memberModal').classList.add('hidden');
}

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
// Suppression d'une classe
function deleteClasse(classeId) {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`<?php echo e(route('private.classes.destroy', ':classe')); ?>`.replace(':classe', classeId), {
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
<?php endif; ?>

// Charger le contenu des membres
function loadMemberContent(classeId) {
    const content = document.getElementById('memberModalContent');
    content.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i></div>';

    fetch("<?php echo e(route('private.classes.members', ':classeid')); ?>".replace(':classeid', classeId), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher les membres
            const members = data.data.membres.data
            content.innerHTML = generateMemberContent(members);
        } else {
            content.innerHTML = '<p class="text-red-600">Erreur lors du chargement des membres</p>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        content.innerHTML = '<p class="text-red-600">Erreur lors du chargement</p>';
    });
}

// Générer le contenu des membres
function generateMemberContent(membres) {
    if (membres.length === 0) {
        return '<p class="text-slate-500 text-center py-4">Aucun membre inscrit dans cette classe</p>';
    }

    let html = '<div class="space-y-3">';
    membres.forEach(membre => {
        html += `
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                        ${membre.prenom.charAt(0)}${membre.nom.charAt(0)}
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">${membre.prenom} ${membre.nom}</p>
                        <p class="text-sm text-slate-500">${membre.email}</p>
                    </div>
                </div>
                <button onclick="removeMember('${membre.id}')" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    });
    html += '</div>';

    return html;
}

// Actions groupées
function showBulkActions() {
    const selected = Array.from(document.querySelectorAll('.classe-checkbox:checked'));

    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins une classe');
        return;
    }

    const actions = [
        'Exporter les classes sélectionnées',
        'Archiver les classes sélectionnées',
        'Envoyer une notification aux classes'
    ];

    // Ici vous pouvez implémenter un modal ou menu pour les actions groupées
    console.log(`${selected.length} classes sélectionnées`);
}

// Close modals when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('memberModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemberModal();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/index.blade.php ENDPATH**/ ?>