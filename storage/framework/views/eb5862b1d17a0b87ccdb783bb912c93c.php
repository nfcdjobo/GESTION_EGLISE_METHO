<?php $__env->startSection('title', 'Gestion des Rapports de Réunions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Rapports de Réunions</h1>
        <p class="text-slate-500 mt-1">Suivi et gestion des rapports de réunions - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\RapportReunion::class)): ?>
                        <a href="<?php echo e(route('private.rapports-reunions.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Rapport
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.rapports-reunions.statistiques')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    <a href="<?php echo e(route('private.rapports-reunions.a-valider')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-check mr-2"></i> À Valider
                    </a>
                    <a href="<?php echo e(route('private.rapports-reunions.mes-rapports')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user mr-2"></i> Rapports
                    </a>

                    <button type="button" onclick="exporterSelection()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.rapports-reunions.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Titre, résumé, réunion..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <?php $__currentLoopData = \App\Models\RapportReunion::STATUTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(request('statut') == $value ? 'selected' : ''); ?>>
                                <?php switch($value):
                                    case ('brouillon'): ?> Brouillon <?php break; ?>
                                    <?php case ('en_revision'): ?> En Révision <?php break; ?>
                                    <?php case ('valide'): ?> Validé <?php break; ?>
                                    <?php case ('publie'): ?> Publié <?php break; ?>
                                <?php endswitch; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de Rapport</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = \App\Models\RapportReunion::TYPES_RAPPORT; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(request('type') == $value ? 'selected' : ''); ?>>
                                <?php switch($value):
                                    case ('proces_verbal'): ?> Procès-verbal <?php break; ?>
                                    <?php case ('compte_rendu'): ?> Compte-rendu <?php break; ?>
                                    <?php case ('rapport_activite'): ?> Rapport d'activité <?php break; ?>
                                    <?php case ('rapport_financier'): ?> Rapport financier <?php break; ?>
                                <?php endswitch; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rédacteur</label>
                    <select name="redacteur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les rédacteurs</option>
                        <?php $__currentLoopData = \App\Models\User::whereHas('rapportsRediges')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $redacteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($redacteur->id); ?>" <?php echo e(request('redacteur_id') == $redacteur->id ? 'selected' : ''); ?>><?php echo e($redacteur->nom); ?> <?php echo e($redacteur->prenom); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.rapports-reunions.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['total'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total des rapports</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['en_revision'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">En révision</p>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['publies'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Publiés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['satisfaction_moyenne'] ?? 0, 1)); ?></p>
                    <p class="text-sm text-slate-500">Satisfaction moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Rapports (<?php echo e($rapports->total()); ?>)
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>Date de création</option>
                        <option value="titre_rapport" <?php echo e(request('sort_by') == 'titre_rapport' ? 'selected' : ''); ?>>Titre</option>
                        <option value="statut" <?php echo e(request('sort_by') == 'statut' ? 'selected' : ''); ?>>Statut</option>
                        <option value="type_rapport" <?php echo e(request('sort_by') == 'type_rapport' ? 'selected' : ''); ?>>Type</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" <?php echo e(request('sort_direction') == 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                        <option value="asc" <?php echo e(request('sort_direction') == 'asc' ? 'selected' : ''); ?>>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if($rapports->count() > 0): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $rapports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rapport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo e($rapport->titre_rapport); ?></h3>
                                    <p class="text-sm text-slate-600"><?php echo e($rapport->type_rapport_traduit); ?></p>
                                    <?php if($rapport->reunion): ?>
                                        <p class="text-xs text-slate-500 mt-1"><?php echo e($rapport->reunion->titre); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <?php
                                        $statutColors = [
                                            'brouillon' => 'bg-gray-100 text-gray-800',
                                            'en_revision' => 'bg-yellow-100 text-yellow-800',
                                            'valide' => 'bg-blue-100 text-blue-800',
                                            'publie' => 'bg-green-100 text-green-800'
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$rapport->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e($rapport->statut_traduit); ?>

                                    </span>
                                    <div class="text-xs text-slate-500 text-right">
                                        Complété à <?php echo e($rapport->pourcentage_completion); ?>%
                                    </div>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span><?php echo e($rapport->created_at->format('d/m/Y à H:i')); ?></span>
                                </div>

                                <?php if($rapport->redacteur): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-edit w-4 mr-2"></i>
                                        <span><?php echo e($rapport->redacteur->nom); ?> <?php echo e($rapport->redacteur->prenom); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($rapport->nombre_presents): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-users w-4 mr-2"></i>
                                        <span><?php echo e($rapport->nombre_presents); ?> présents</span>
                                    </div>
                                <?php endif; ?>

                                <?php if($rapport->note_satisfaction): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-star w-4 mr-2"></i>
                                        <span><?php echo e($rapport->note_satisfaction); ?>/5</span>
                                    </div>
                                <?php endif; ?>

                                <?php if($rapport->resume): ?>
                                    <div class="text-sm text-slate-600">
                                        <p class="truncate">
                                            <?php echo e(Str::limit(strip_tags($rapport->resume), 80)); ?>

                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.read')): ?>
                                        <a href="<?php echo e(route('private.rapports-reunions.show', $rapport)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.update')): ?>
                                        <a href="<?php echo e(route('private.rapports-reunions.edit', $rapport)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.revision')): ?>
                                    <?php if($rapport->statut === 'brouillon'): ?>
                                        <button type="button" onclick="changerStatut('<?php echo e($rapport->id); ?>', 'en_revision')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Passer en révision">
                                            <i class="fas fa-arrow-right text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.validate')): ?>
                                    <?php if($rapport->statut === 'en_revision'): ?>
                                        <button type="button" onclick="openValidationModal('<?php echo e($rapport->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Valider">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.publish')): ?>
                                    <?php if($rapport->statut === 'valide'): ?>
                                        <button type="button" onclick="changerStatut('<?php echo e($rapport->id); ?>', 'publie')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Publier">
                                            <i class="fas fa-share text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.delete')): ?>
                                    <?php if($rapport->statut !== 'publie'): ?>
                                        <button type="button" onclick="supprimerRapport('<?php echo e($rapport->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium"><?php echo e($rapports->firstItem()); ?></span> à <span class="font-medium"><?php echo e($rapports->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($rapports->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($rapports->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun rapport trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'statut', 'type', 'redacteur_id'])): ?>
                            Aucun rapport ne correspond à vos critères de recherche.
                        <?php else: ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.create')): ?>
                            Commencez par créer votre premier rapport de réunion.
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.create')): ?>
                        <a href="<?php echo e(route('private.rapports-reunions.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un rapport
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal validation -->
<div id="validationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Valider le rapport</h3>
            <form id="validationForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="rapport_id" name="rapport_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaires (optionnel)</label>
                    <div class="has-error-container">
                        <textarea name="commentaires" id="commentaires" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeValidationModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.validate')): ?>
            <button type="button" onclick="validerRapport()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                Valider
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
// Gestion du tri
document.getElementById('sortBy').addEventListener('change', function() {
    updateSort();
});

document.getElementById('sortOrder').addEventListener('change', function() {
    updateSort();
});

function updateSort() {
    const sortBy = document.getElementById('sortBy').value;
    const sortOrder = document.getElementById('sortOrder').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_direction', sortOrder);
    window.location.href = url.toString();
}

// Modal validation
function openValidationModal(rapportId) {
    document.getElementById('rapport_id').value = rapportId;
    document.getElementById('validationModal').classList.remove('hidden');

    // Initialiser CKEditor
    setTimeout(() => {
        if (document.getElementById('commentaires') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#commentaires + .ck-editor')) {
                initializeCKEditor('#commentaires', 'simple', {
                    placeholder: 'Commentaires sur la validation...'
                });
            }
        }
    }, 100);
}


let selectedRapports = [];

function exporterSelection() {
    const checkboxes = document.querySelectorAll('input[name="rapport_ids[]"]:checked');
    const rapportIds = Array.from(checkboxes).map(cb => cb.value);

    if (rapportIds.length === 0) {
        alert('Veuillez sélectionner au moins un rapport');
        return;
    }

    const url = new URL('<?php echo e(route("private.rapports-reunions.export")); ?>');
    url.searchParams.set('format', 'pdf');
    rapportIds.forEach(id => url.searchParams.append('rapport_ids[]', id));

    window.open(url.toString(), '_blank');
}

// Ajouter des checkboxes dans chaque carte de rapport
function ajouterCheckboxRapport() {
    // Ajouter cette checkbox dans chaque carte de rapport
    return `
        <div class="absolute top-2 left-2">
            <input type="checkbox" name="rapport_ids[]" value="${rapportId}"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
        </div>
    `;
}



function closeValidationModal() {
    // Nettoyer l'instance CKEditor
    const editorContainer = document.querySelector('#commentaires + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#commentaires']) {
        window.CKEditorInstances['#commentaires'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#commentaires'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('validationModal').classList.add('hidden');
    document.getElementById('validationForm').reset();
}

function validerRapport() {
    // Synchroniser CKEditor
    if (window.CKEditorInstances && window.CKEditorInstances['#commentaires']) {
        const editor = window.CKEditorInstances['#commentaires'];
        const textarea = document.getElementById('commentaires');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('validationForm');
    const formData = new FormData(form);
    const rapportId = document.getElementById('rapport_id').value;

    fetch(`<?php echo e(route('private.rapports-reunions.valider', ':rapportid')); ?>`.replace(':rapportid', rapportId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Changer statut
function changerStatut(rapportId, nouveauStatut) {
    let url;
    switch(nouveauStatut) {
        case 'en_revision':
            url = `<?php echo e(route('private.rapports-reunions.revision', ':rapportid')); ?>`.replace(':rapportid', rapportId);
            break;
        case 'publie':
            url = `<?php echo e(route('private.rapports-reunions.publier', ':rapportid')); ?>`.replace(':rapportid', rapportId);
            break;
        default:
            return;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Suppression
function supprimerRapport(rapportId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
        fetch(`<?php echo e(route('private.rapports-reunions.destroy', ':rapportid')); ?>`.replace(':rapportid', rapportId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('validationModal').addEventListener('click', function(e) {
    if (e.target === this) closeValidationModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/rapportsreunions/index.blade.php ENDPATH**/ ?>