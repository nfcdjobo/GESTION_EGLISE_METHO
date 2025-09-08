<?php $__env->startSection('title', 'Gestion des Réunions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Réunions</h1>
        <p class="text-slate-500 mt-1">Organisation et suivi des réunions - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.create')): ?>
                        <a href="<?php echo e(route('private.reunions.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle Réunion
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.reunions.calendrier')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar mr-2"></i> Calendrier
                    </a>
                    <a href="<?php echo e(route('private.reunions.statistiques')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.reunions.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Titre, lieu, description..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="planifiee" <?php echo e(request('statut') == 'planifiee' ? 'selected' : ''); ?>>Planifiée</option>
                        <option value="confirmee" <?php echo e(request('statut') == 'confirmee' ? 'selected' : ''); ?>>Confirmée</option>
                        <option value="planifie" <?php echo e(request('statut') == 'planifie' ? 'selected' : ''); ?>>En préparation</option>
                        <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="terminee" <?php echo e(request('statut') == 'terminee' ? 'selected' : ''); ?>>Terminée</option>
                        <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                        <option value="reportee" <?php echo e(request('statut') == 'reportee' ? 'selected' : ''); ?>>Reportée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de réunion</label>
                    <select name="type_reunion_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = $typesReunions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(request('type_reunion_id') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Organisateur</label>
                    <select name="organisateur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les organisateurs</option>
                        <!-- Populate avec les organisateurs -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                    <select name="niveau_priorite" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes priorités</option>
                        <option value="faible" <?php echo e(request('niveau_priorite') == 'faible' ? 'selected' : ''); ?>>Faible</option>
                        <option value="normale" <?php echo e(request('niveau_priorite') == 'normale' ? 'selected' : ''); ?>>Normale</option>
                        <option value="haute" <?php echo e(request('niveau_priorite') == 'haute' ? 'selected' : ''); ?>>Haute</option>
                        <option value="urgente" <?php echo e(request('niveau_priorite') == 'urgente' ? 'selected' : ''); ?>>Urgente</option>
                        <option value="critique" <?php echo e(request('niveau_priorite') == 'critique' ? 'selected' : ''); ?>>Critique</option>
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
                                <input type="checkbox" name="diffusion_en_ligne" value="1" <?php echo e(request('diffusion_en_ligne') ? 'checked' : ''); ?> id="diffusion_en_ligne" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="diffusion_en_ligne" class="ml-2 text-sm text-slate-700">Avec diffusion en ligne</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="recurrentes" value="1" <?php echo e(request('recurrentes') ? 'checked' : ''); ?> id="recurrentes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="recurrentes" class="ml-2 text-sm text-slate-700">Récurrentes seulement</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.reunions.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($reunions->total()); ?></p>
                    <p class="text-sm text-slate-500">Total réunions</p>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($reunions->where('statut', 'confirmee')->count()); ?></p>
                    <p class="text-sm text-slate-500">Confirmées</p>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($reunions->sum('nombre_participants_reel') ?: '0'); ?></p>
                    <p class="text-sm text-slate-500">Total participants</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($reunions->whereNotNull('note_globale')->avg('note_globale') ?: 0, 1)); ?>/10</p>
                    <p class="text-sm text-slate-500">Note moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des réunions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Réunions (<?php echo e($reunions->total()); ?>)
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="date_reunion" <?php echo e(request('sort_by') == 'date_reunion' ? 'selected' : ''); ?>>Date</option>
                        <option value="titre" <?php echo e(request('sort_by') == 'titre' ? 'selected' : ''); ?>>Titre</option>
                        <option value="statut" <?php echo e(request('sort_by') == 'statut' ? 'selected' : ''); ?>>Statut</option>
                        <option value="niveau_priorite" <?php echo e(request('sort_by') == 'niveau_priorite' ? 'selected' : ''); ?>>Priorité</option>
                        <option value="nombre_participants_reel" <?php echo e(request('sort_by') == 'nombre_participants_reel' ? 'selected' : ''); ?>>Participants</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                        <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if($reunions->count() > 0): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $reunions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reunion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo e($reunion->titre); ?></h3>
                                    <p class="text-sm text-slate-600"><?php echo e($reunion->typeReunion->nom ?? 'Non défini'); ?></p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <?php
                                        $statutColors = [
                                            'planifiee' => 'bg-blue-100 text-blue-800',
                                            'confirmee' => 'bg-green-100 text-green-800',
                                            'planifie' => 'bg-yellow-100 text-yellow-800',
                                            'en_cours' => 'bg-orange-100 text-orange-800',
                                            'terminee' => 'bg-emerald-100 text-emerald-800',
                                            'annulee' => 'bg-red-100 text-red-800',
                                            'reportee' => 'bg-purple-100 text-purple-800',
                                            'suspendue' => 'bg-gray-100 text-gray-800'
                                        ];

                                        $prioriteColors = [
                                            'faible' => 'bg-gray-100 text-gray-800',
                                            'normale' => 'bg-blue-100 text-blue-800',
                                            'haute' => 'bg-yellow-100 text-yellow-800',
                                            'urgente' => 'bg-orange-100 text-orange-800',
                                            'critique' => 'bg-red-100 text-red-800'
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$reunion->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e(ucfirst($reunion->statut)); ?>

                                    </span>
                                    <?php if($reunion->niveau_priorite !== 'normale'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($prioriteColors[$reunion->niveau_priorite] ?? 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e(ucfirst($reunion->niveau_priorite)); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($reunion->diffusion_en_ligne): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-video mr-1"></i> Live
                                        </span>
                                    <?php endif; ?>
                                    <?php if($reunion->est_recurrente): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-repeat mr-1"></i> Récurrente
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y')); ?></span>
                                    <i class="fas fa-clock w-4 ml-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($reunion->heure_debut_prevue)->format('H:i')); ?></span>
                                </div>

                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span><?php echo e($reunion->lieu); ?></span>
                                </div>

                                <?php if($reunion->organisateurPrincipal): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-tie w-4 mr-2"></i>
                                        <span><?php echo e($reunion->organisateurPrincipal->nom); ?> <?php echo e($reunion->organisateurPrincipal->prenom); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if($reunion->nombre_inscrits > 0): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-users w-4 mr-2"></i>
                                        <span><?php echo e($reunion->nombre_inscrits); ?> inscrit(s)</span>
                                        <?php if($reunion->nombre_participants_reel): ?>
                                            <span class="ml-2 text-green-600">(<?php echo e($reunion->nombre_participants_reel); ?> présent(s))</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if($reunion->note_globale): ?>
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-star w-4 mr-2 text-yellow-500"></i>
                                        <span><?php echo e($reunion->note_globale); ?>/10</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.read')): ?>
                                        <a href="<?php echo e(route('private.reunions.show', $reunion)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.update')): ?>
                                        <a href="<?php echo e(route('private.reunions.edit', $reunion)); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if($reunion->peutCommencer()): ?>
                                        <button type="button" onclick="changerStatut('<?php echo e($reunion->id); ?>', 'commencer')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Commencer">
                                            <i class="fas fa-play text-sm"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if($reunion->peutEtreTerminee()): ?>
                                        <button type="button" onclick="changerStatut('<?php echo e($reunion->id); ?>', 'terminer')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Terminer">
                                            <i class="fas fa-stop text-sm"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.create')): ?>
                                        <button type="button" onclick="openDuplicateModal('<?php echo e($reunion->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Dupliquer">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <?php if($reunion->peutEtreAnnulee()): ?>
                                        <button type="button" onclick="openAnnulerModal('<?php echo e($reunion->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Annuler">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.delete')): ?>
                                        <?php if(in_array($reunion->statut, ['planifiee', 'confirmee'])): ?>
                                            <button type="button" onclick="supprimerReunion('<?php echo e($reunion->id); ?>')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
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
                        Affichage de <span class="font-medium"><?php echo e($reunions->firstItem()); ?></span> à <span class="font-medium"><?php echo e($reunions->lastItem()); ?></span>
                        sur <span class="font-medium"><?php echo e($reunions->total()); ?></span> résultats
                    </div>
                    <div>
                        <?php echo e($reunions->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune réunion trouvée</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['search', 'statut', 'type_reunion_id', 'organisateur_id'])): ?>
                            Aucune réunion ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Commencez par planifier votre première réunion.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reunions.create')): ?>
                        <a href="<?php echo e(route('private.reunions.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Planifier une réunion
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer la réunion</h3>
            <form id="duplicateForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="duplicate_reunion_id" name="reunion_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                        <input type="date" name="nouvelle_date" id="nouvelle_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="copier_participants" id="copier_participants" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="copier_participants" class="ml-2 text-sm text-slate-700">Copier les participants</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="dupliquerReunion()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
        </div>
    </div>
</div>

<!-- Modal Annulation -->
<div id="annulerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Annuler la réunion</h3>
            <form id="annulerForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="annuler_reunion_id" name="reunion_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Motif d'annulation</label>
                        <textarea name="motif_annulation" id="motif_annulation" rows="3" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Raison de l'annulation..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Message aux participants</label>
                        <textarea name="message_participants" id="message_participants" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Message à envoyer aux participants..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeAnnulerModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="annulerReunion()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Confirmer l'annulation
            </button>
        </div>
    </div>
</div>

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

// Modal duplication
function openDuplicateModal(reunionId) {
    document.getElementById('duplicate_reunion_id').value = reunionId;
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
}

function dupliquerReunion() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('duplicate_reunion_id').value;

    fetch(`<?php echo e(route('private.reunions.dupliquer', ':reunion')); ?>`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
            window.location.href = `/private/reunions/${data.data?.id || reunionId}`;
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Modal annulation
function openAnnulerModal(reunionId) {
    document.getElementById('annuler_reunion_id').value = reunionId;
    document.getElementById('annulerModal').classList.remove('hidden');
}

function closeAnnulerModal() {
    document.getElementById('annulerModal').classList.add('hidden');
    document.getElementById('annulerForm').reset();
}

function annulerReunion() {
    const form = document.getElementById('annulerForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('annuler_reunion_id').value;

    fetch(`<?php echo e(route('private.reunions.annuler', ':reunion')); ?>`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
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

// Changement de statut
function changerStatut(reunionId, action) {
    const actions = {
        'commencer': 'commencer',
        'terminer': 'terminer'
    };

    if (!actions[action]) return;
    let route = `<?php echo e(route('private.reunions.confirmer', ':reunion')); ?>`.replace(':reunion', reunionId);
    route = route.replace('activer', actions[action]);
    alert(route)
    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
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
function supprimerReunion(reunionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?')) {
        fetch(`<?php echo e(route('private.reunions.destroy', ':reunion')); ?>`.replace(':reunion', reunionId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message?.includes('succès')) {
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
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDuplicateModal();
});

document.getElementById('annulerModal').addEventListener('click', function(e) {
    if (e.target === this) closeAnnulerModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/index.blade.php ENDPATH**/ ?>