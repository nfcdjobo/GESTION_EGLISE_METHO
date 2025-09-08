<?php $__env->startSection('title', 'Gestion des Cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Cultes</h1>
        <p class="text-slate-500 mt-1">Organisation et suivi des services religieux - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                        <a href="<?php echo e(route('private.cultes.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Culte
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.cultes.planning')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar mr-2"></i> Planning
                    </a>
                    <a href="<?php echo e(route('private.cultes.statistiques')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    <a href="<?php echo e(route('private.cultes.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.cultes.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Titre, lieu, message..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" <?php echo e(request('statut') == 'planifie' ? 'selected' : ''); ?>>Planifié</option>
                        <option value="en_preparation" <?php echo e(request('statut') == 'en_preparation' ? 'selected' : ''); ?>>En Préparation</option>
                        <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En Cours</option>
                        <option value="termine" <?php echo e(request('statut') == 'termine' ? 'selected' : ''); ?>>Terminé</option>
                        <option value="annule" <?php echo e(request('statut') == 'annule' ? 'selected' : ''); ?>>Annulé</option>
                        <option value="reporte" <?php echo e(request('statut') == 'reporte' ? 'selected' : ''); ?>>Reporté</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de Culte</label>
                    <select name="type_culte" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = \App\Models\Culte::TYPE_CULTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('type_culte') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Programme</label>
                    <select name="programme_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les programmes</option>
                        <?php $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($programme->id); ?>" <?php echo e(request('programme_id') == $programme->id ? 'selected' : ''); ?>><?php echo e($programme->nom); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pasteur</label>
                    <select name="pasteur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les pasteurs</option>
                        <?php $__currentLoopData = $pasteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pasteur->id); ?>" <?php echo e(request('pasteur_id') == $pasteur->id ? 'selected' : ''); ?>><?php echo e($pasteur->nom); ?> <?php echo e($pasteur->prenom); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                        <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                        <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="a_venir" value="1" <?php echo e(request('a_venir') ? 'checked' : ''); ?> id="a_venir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="a_venir" class="ml-2 text-sm text-slate-700">Cultes à venir</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="publics_seulement" value="1" <?php echo e(request('publics_seulement') ? 'checked' : ''); ?> id="publics_seulement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="publics_seulement" class="ml-2 text-sm text-slate-700">Publics seulement</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.cultes.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <i class="fas fa-church text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($cultes->total()); ?></p>
                    <p class="text-sm text-slate-500">Total des cultes</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($cultes->where('statut', 'planifie')->count()); ?></p>
                    <p class="text-sm text-slate-500">Cultes planifiés</p>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($cultes->sum('nombre_participants') ?: '0'); ?></p>
                    <p class="text-sm text-slate-500">Total participants</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($cultes->sum('offrande_totale') ?: 0)); ?> FCFA</p>
                    <p class="text-sm text-slate-500">Total offrandes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des cultes -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Cultes (<?php echo e($cultes->total()); ?>)
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="date_culte" <?php echo e(request('sort_by') == 'date_culte' ? 'selected' : ''); ?>>Date</option>
                        <option value="titre" <?php echo e(request('sort_by') == 'titre' ? 'selected' : ''); ?>>Titre</option>
                        <option value="type_culte" <?php echo e(request('sort_by') == 'type_culte' ? 'selected' : ''); ?>>Type</option>
                        <option value="statut" <?php echo e(request('sort_by') == 'statut' ? 'selected' : ''); ?>>Statut</option>
                        <option value="nombre_participants" <?php echo e(request('sort_by') == 'nombre_participants' ? 'selected' : ''); ?>>Participants</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                        <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if($cultes->count() > 0): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo e($culte->titre); ?></h3>
                                    <p class="text-sm text-slate-600"><?php echo e($culte->type_culte_libelle); ?></p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <?php
                                        $statutColors = [
                                            'planifie' => 'bg-blue-100 text-blue-800',
                                            'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                            'en_cours' => 'bg-orange-100 text-orange-800',
                                            'termine' => 'bg-green-100 text-green-800',
                                            'annule' => 'bg-red-100 text-red-800',
                                            'reporte' => 'bg-purple-100 text-purple-800'
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e($culte->statut_libelle); ?>

                                    </span>
                                    <?php if($culte->est_public): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-globe mr-1"></i> Public
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y')); ?></span>
                                    <i class="fas fa-clock w-4 ml-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?></span>
                                </div>

                                <?php if($culte->lieu !== 'Église principale'): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                        <span><?php echo e($culte->lieu); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->pasteurPrincipal): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user w-4 mr-2"></i>
                                        <span><?php echo e($culte->pasteurPrincipal->nom); ?> <?php echo e($culte->pasteurPrincipal->prenom); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($culte->nombre_participants): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-users w-4 mr-2"></i>
                                        <span><?php echo e($culte->nombre_participants); ?> participants</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.read')): ?>
                                        <a href="<?php echo e(route('private.cultes.show', $culte)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
                                        <a href="<?php echo e(route('private.cultes.edit', $culte)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if($culte->statut !== 'termine'): ?>
                                        <button type="button" onclick="openStatusModal('<?php echo e($culte->id); ?>', '<?php echo e($culte->statut); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Changer statut">
                                            <i class="fas fa-exchange-alt text-sm"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                                        <button type="button" onclick="openDuplicateModal('<?php echo e($culte->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Dupliquer">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.delete')): ?>
                                    <?php if($culte->statut !== 'en_cours'): ?>
                                        <button type="button" onclick="deleteCulte('<?php echo e($culte->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
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
                        Affichage de <span class="font-medium"><?php echo e($cultes->firstItem()); ?></span> à <span class="font-medium"><?php echo e($cultes->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($cultes->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($cultes->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-church text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun culte trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'statut', 'type_culte', 'programme_id'])): ?>
                            Aucun culte ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Commencez par planifier votre premier culte.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                        <a href="<?php echo e(route('private.cultes.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Planifier un culte
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal changement de statut -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Changer le statut du culte</h3>
            <form id="statusForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="culte_id" name="culte_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                    <select id="nouveau_statut" name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="planifie">Planifié</option>
                        <option value="en_preparation">En Préparation</option>
                        <option value="en_cours">En Cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                        <option value="reporte">Reporté</option>
                    </select>
                </div>
                <div id="raisonDiv" class="mb-4 hidden">
    <label class="block text-sm font-medium text-slate-700 mb-2">Raison</label>
    <div class="has-error-container">
        <textarea name="raison" id="raison" rows="3"
            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
            placeholder="Raison de l'annulation ou du report..."></textarea>
    </div>
</div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="updateStatus()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Changer le statut
            </button>
        </div>
    </div>
</div>

<!-- Modal duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le culte</h3>
            <form id="duplicateForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="duplicate_culte_id" name="culte_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                        <input type="date" name="nouvelle_date" id="nouvelle_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle heure</label>
                        <input type="time" name="nouvelle_heure" id="nouvelle_heure" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau titre (optionnel)</label>
                        <input type="text" name="nouveau_titre" id="nouveau_titre" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Laisser vide pour ajouter (Copie)">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="duplicateCulte()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
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
    url.searchParams.set('sort_order', sortOrder);
    window.location.href = url.toString();
}

// Modal statut
function openStatusModal(culteId, currentStatus) {
    document.getElementById('culte_id').value = culteId;
    document.getElementById('nouveau_statut').value = currentStatus;
    toggleRaisonField();
    document.getElementById('statusModal').classList.remove('hidden');

    // Initialiser CKEditor sur le textarea raison après un court délai
    setTimeout(() => {
        if (document.getElementById('raison') && typeof ClassicEditor !== 'undefined') {
            // Vérifier si CKEditor n'est pas déjà initialisé sur cet élément
            if (!document.querySelector('#raison + .ck-editor')) {
                initializeCKEditor('#raison', 'simple', {
                    placeholder: 'Raison de l\'annulation ou du report...'
                });
            }
        }
    }, 100);
}

function closeStatusModal() {
    // Nettoyer l'instance CKEditor si elle existe
    const editorContainer = document.querySelector('#raison + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#raison']) {
        window.CKEditorInstances['#raison'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#raison'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusForm').reset();
}

function toggleRaisonField() {
    const statut = document.getElementById('nouveau_statut').value;
    const raisonDiv = document.getElementById('raisonDiv');
    if (statut === 'annule' || statut === 'reporte') {
        raisonDiv.classList.remove('hidden');
        document.getElementById('raison').required = true;
    } else {
        raisonDiv.classList.add('hidden');
        document.getElementById('raison').required = false;
    }
}

document.getElementById('nouveau_statut').addEventListener('change', toggleRaisonField);

function updateStatus() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances && window.CKEditorInstances['#raison']) {
        const editor = window.CKEditorInstances['#raison'];
        const textarea = document.getElementById('raison');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('statusForm');

    const formData = new FormData(form);
    const culteId = document.getElementById('culte_id').value;

    fetch(`<?php echo e(route('private.cultes.statut', ':culteid')); ?>`.replace(':culteid', culteId), {
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

// Modal duplication
function openDuplicateModal(culteId) {
    document.getElementById('duplicate_culte_id').value = culteId;
    // Définir la date de demain par défaut
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
}

function duplicateCulte() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const culteId = document.getElementById('duplicate_culte_id').value;

    fetch(`<?php echo e(route('private.cultes.dupliquer', ':culteid')); ?>`.replace(':culteid', culteId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `<?php echo e(route('private.cultes.show', ':culteid')); ?>`.replace(':culteid', data.data.id);
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
function deleteCulte(culteId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce culte ?')) {
        fetch(`<?php echo e(route('private.cultes.destroy', ':culteid')); ?>`.replace(':culteid', culteId), {
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
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDuplicateModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/cultes/index.blade.php ENDPATH**/ ?>