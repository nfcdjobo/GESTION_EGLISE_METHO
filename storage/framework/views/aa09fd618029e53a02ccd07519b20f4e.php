<?php $__env->startSection('title', 'Gestion des Classes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Gestion des Classes
            </h1>
            <p class="text-slate-500 mt-1">
                Administration des classes et groupes - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?>

            </p>
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
                            <a href="<?php echo e(route('private.classes.create')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouvelle Classe
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('private.classes.statistiques')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques
                        </a>
                    </div>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('private.classes.index')); ?>"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                        <!-- Recherche -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Nom, description ou tranche d'âge..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>

                        <!-- Tranche d'âge -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tranche d'âge</label>
                            <select name="tranche_age"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes les tranches</option>
                                <option value="0-3 ans" <?php echo e(request('tranche_age') == '0-3 ans' ? 'selected' : ''); ?>>0-3 ans</option>
                                <option value="4-6 ans" <?php echo e(request('tranche_age') == '4-6 ans' ? 'selected' : ''); ?>>4-6 ans</option>
                                <option value="7-9 ans" <?php echo e(request('tranche_age') == '7-9 ans' ? 'selected' : ''); ?>>7-9 ans</option>
                                <option value="10-12 ans" <?php echo e(request('tranche_age') == '10-12 ans' ? 'selected' : ''); ?>>10-12 ans</option>
                                <option value="13-15 ans" <?php echo e(request('tranche_age') == '13-15 ans' ? 'selected' : ''); ?>>13-15 ans</option>
                                <option value="16-18 ans" <?php echo e(request('tranche_age') == '16-18 ans' ? 'selected' : ''); ?>>16-18 ans</option>
                                <option value="Adultes" <?php echo e(request('tranche_age') == 'Adultes' ? 'selected' : ''); ?>>Adultes</option>
                                <option value="Tous âges" <?php echo e(request('tranche_age') == 'Tous âges' ? 'selected' : ''); ?>>Tous âges</option>
                            </select>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="actives_seulement"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes</option>
                                <option value="1" <?php echo e(request('actives_seulement') == '1' ? 'selected' : ''); ?>>Actives uniquement</option>
                            </select>
                        </div>

                        <!-- Tri -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tri</label>
                            <select name="sort"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="nom" <?php echo e(request('sort') == 'nom' ? 'selected' : ''); ?>>Nom</option>
                                <option value="nombre_inscrits" <?php echo e(request('sort') == 'nombre_inscrits' ? 'selected' : ''); ?>>Nb Inscrits</option>
                                <option value="tranche_age" <?php echo e(request('sort') == 'tranche_age' ? 'selected' : ''); ?>>Tranche d'âge</option>
                                <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Date création</option>
                            </select>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="lg:col-span-5 flex gap-2 pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Rechercher
                            </button>
                            <a href="<?php echo e(route('private.classes.index')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($classes->filter(function($classe) { return !empty($classe->responsables); })->count()); ?>

                        </p>
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
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($classes->count() > 0 ? number_format($classes->avg('nombre_inscrits'), 1) : 0); ?>

                        </p>
                        <p class="text-sm text-slate-500">Moyenne par classe</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des classes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <!-- En-tête avec contrôles d'affichage -->
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Classes (<?php echo e($classes->total()); ?>)
                    </h2>

                    <div class="flex items-center gap-3">
                        <!-- Sélecteur de vue -->
                        <div class="flex items-center bg-slate-100 rounded-lg p-1">
                            <button type="button" id="listViewBtn" onclick="switchView('list')"
                                class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md bg-white text-slate-900 shadow-sm">
                                <i class="fas fa-list mr-2"></i>
                                Liste
                            </button>
                            <button type="button" id="gridViewBtn" onclick="switchView('grid')"
                                class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md text-slate-600 hover:text-slate-900 hover:bg-white">
                                <i class="fas fa-th-large mr-2"></i>
                                Grille
                            </button>
                        </div>

                        <!-- Actions groupées -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.bulk-actions')): ?>
                            <button type="button" onclick="showBulkActions()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-tasks mr-2"></i> Actions groupées
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Contenu des classes -->
            <div class="p-6">
                <?php if($classes->count() > 0): ?>
                    <!-- Vue en liste (par défaut) -->
                    <div id="listView" class="space-y-4">
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <!-- Checkbox de sélection -->
                                            <input type="checkbox" name="selected_classes[]" value="<?php echo e($classe->id); ?>"
                                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 classe-checkbox">

                                            <!-- Image/Icône -->
                                            <div class="flex-shrink-0">
                                                <?php if($classe->image_classe): ?>
                                                    <img src="<?php echo e(asset('storage/' . $classe->image_classe)); ?>" alt="<?php echo e($classe->nom); ?>"
                                                        class="w-16 h-16 object-cover rounded-lg">
                                                <?php else: ?>
                                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Informations principales -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="text-lg font-semibold text-slate-900 truncate"><?php echo e($classe->nom); ?></h3>
                                                    <?php if(!empty($classe->responsables)): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i> Active
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i> En attente
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($classe->tranche_age): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?php echo e($classe->tranche_age); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if($classe->description): ?>
                                                    <p class="text-sm text-slate-600 mb-2 line-clamp-1"><?php echo e($classe->description); ?></p>
                                                <?php endif; ?>

                                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                                    <?php if($classe->responsables_collection && $classe->responsables_collection->count() > 0): ?>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-user-tie text-green-500 mr-1"></i>
                                                            <?php echo e($classe->responsables_collection->count()); ?> responsable(s)
                                                        </span>
                                                        <?php
                                                            $superieur = $classe->responsables_collection->where('superieur', true)->first();
                                                        ?>
                                                        <?php if($superieur): ?>
                                                            <span class="flex items-center">
                                                                <i class="fas fa-crown text-yellow-500 mr-1"></i>
                                                                <?php echo e($superieur->prenom); ?> <?php echo e($superieur->nom); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="flex items-center text-amber-600">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Aucun responsable
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statistiques et actions -->
                                        <div class="flex items-center space-x-6">
                                            <!-- Statistiques -->
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-slate-900"><?php echo e($classe->nombre_inscrits + $classe->responsables_collection->count()); ?></div>
                                                <div class="text-xs text-slate-500">Inscrits</div>
                                                <div class="text-xs text-green-600 font-medium mt-1">Capacité illimitée</div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center space-x-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
                                                    <a href="<?php echo e(route('private.classes.show', $classe)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                        title="Voir">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                                    <a href="<?php echo e(route('private.classes.edit', $classe)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                        title="Modifier">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                                    <a href="<?php echo e(route('private.classes.getUtilisateursDisponibles', $classe)); ?>"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                                        title="Gérer les membres">
                                                        <i class="fas fa-users text-sm"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
                                                    <?php if($classe->nombre_inscrits == 0): ?>
                                                        <button type="button" onclick="deleteClasse('<?php echo e($classe->id); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Vue en grille (cachée par défaut) -->
                    <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
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
                                        <input type="checkbox" name="selected_classes[]" value="<?php echo e($classe->id); ?>"
                                            class="w-4 h-4 text-blue-600 bg-white/80 border-gray-300 rounded focus:ring-blue-500 classe-checkbox">
                                    </div>
                                    <?php if(!empty($classe->responsables)): ?>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Active
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> En attente
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
                                    <div class="grid gap-4 mb-4">
                                        <div class="text-center p-3 bg-slate-50 rounded-lg">
                                            <div class="text-lg font-bold text-slate-900"><?php echo e($classe->nombre_inscrits); ?></div>
                                            <div class="text-xs text-slate-500">Inscrits</div>
                                            <div class="text-xs text-green-600 font-medium">Capacité illimitée</div>
                                        </div>
                                    </div>

                                    <!-- Responsables -->
                                    <div class="mb-4 space-y-2">
                                        <?php if($classe->responsables_collection && $classe->responsables_collection->count() > 0): ?>
                                            <?php
                                                $superieur = $classe->responsables_collection->where('superieur', true)->first();
                                            ?>
                                            <?php if($superieur): ?>
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-crown text-yellow-500 mr-2"></i>
                                                    <span class="font-medium">Responsable:</span>
                                                    <span class="ml-1"><?php echo e($superieur->prenom); ?> <?php echo e($superieur->nom); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="text-xs text-slate-500">
                                                <?php echo e($classe->responsables_collection->count()); ?> responsable(s) total
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center text-sm text-amber-600">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <span class="font-medium">Aucun responsable</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.read')): ?>
                                                <a href="<?php echo e(route('private.classes.show', $classe)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                    title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.update')): ?>
                                                <a href="<?php echo e(route('private.classes.edit', $classe)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.manage-members')): ?>
                                                <a href="<?php echo e(route('private.classes.getUtilisateursDisponibles', $classe)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                                    title="Gérer les membres">
                                                    <i class="fas fa-users text-sm"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.delete')): ?>
                                            <?php if($classe->nombre_inscrits == 0): ?>
                                                <button type="button" onclick="deleteClasse('<?php echo e($classe->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                    title="Supprimer">
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
                    <!-- État vide -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chalkboard-teacher text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune classe trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            <?php if(request()->hasAny(['search', 'tranche_age', 'actives_seulement'])): ?>
                                Aucune classe ne correspond à vos critères de recherche.
                            <?php else: ?>
                                Commencez par créer votre première classe.
                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.create')): ?>
                            <a href="<?php echo e(route('private.classes.create')); ?>"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
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
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmDelete"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Modal d'actions groupées -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Actions groupées</h3>
                    <button type="button" onclick="closeBulkActionsModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-600 mt-2">
                    <span id="selectedCount">0</span> classe(s) sélectionnée(s)
                </p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.bulk-actions')): ?>
                        <button type="button" onclick="executeBulkAction('archive')"
                            class="w-full flex items-center px-4 py-3 text-left bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600 transition-colors">
                                <i class="fas fa-archive text-white"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Archiver les classes</div>
                                <div class="text-sm text-slate-600">Déplacer vers les archives</div>
                            </div>
                        </button>

                        <button type="button" onclick="executeBulkAction('delete')"
                            class="w-full flex items-center px-4 py-3 text-left bg-red-50 hover:bg-red-100 rounded-xl transition-colors group">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash text-white"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">Supprimer les classes</div>
                                <div class="text-sm text-slate-600">Suppression définitive (sans membres uniquement)</div>
                            </div>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation d'action groupée -->
    <div id="bulkConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="confirmIcon" class="w-12 h-12 rounded-full flex items-center justify-center mr-4">
                        <!-- Icône dynamique -->
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Confirmer l'action</h3>
                </div>
                <p id="confirmMessage" class="text-slate-600 mb-4">
                    <!-- Message dynamique -->
                </p>
                <div id="warningMessage" class="hidden p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                        <span class="text-amber-800 text-sm font-medium">Cette action est irréversible</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeBulkConfirmModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmBulkAction"
                    class="px-4 py-2 text-white rounded-xl transition-colors">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- Styles CSS -->
    <style>
        .view-btn {
            transition: all 0.2s ease-in-out;
        }

        .view-active {
            background-color: white !important;
            color: #1e293b !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .classes-container {
            transition: opacity 0.3s ease-in-out;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <!-- Scripts JavaScript -->
    <script>
        // Variables globales
        let currentView = 'list';

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurer la vue préférée depuis localStorage
            const savedView = localStorage.getItem('classesView') || 'list';
            switchView(savedView);
        });

        // Fonction pour changer de vue
        function switchView(view) {
            currentView = view;

            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');

            // Masquer toutes les vues
            listView.classList.add('hidden');
            gridView.classList.add('hidden');

            // Réinitialiser les styles des boutons
            listBtn.classList.remove('view-active');
            gridBtn.classList.remove('view-active');
            listBtn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white');
            gridBtn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white');

            // Afficher la vue sélectionnée
            if (view === 'list') {
                gridBtn.classList.remove('bg-white');
                listView.classList.remove('hidden');
                listBtn.classList.add('view-active');
                listBtn.classList.remove('text-slate-600', 'bg-white', 'hover:text-slate-900', 'hover:bg-white');
            } else {
                listBtn.classList.remove('bg-white');
                gridView.classList.remove('hidden');
                gridBtn.classList.add('view-active');
                gridBtn.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white');
            }

            // Sauvegarder la préférence
            localStorage.setItem('classesView', view);
        }

        // Gestion des modals
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showBulkActionsModal() {
            document.getElementById('bulkActionsModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeBulkActionsModal() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showBulkConfirmModal() {
            document.getElementById('bulkConfirmModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeBulkConfirmModal() {
            document.getElementById('bulkConfirmModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
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
                        showSuccessMessage(data.message || 'Classe supprimée avec succès');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showErrorMessage(data.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    closeDeleteModal();
                    showErrorMessage('Une erreur est survenue lors de la suppression');
                });
            };
        }
        <?php endif; ?>

        // Actions groupées
        function showBulkActions() {
            const selected = Array.from(document.querySelectorAll('.classe-checkbox:checked'));

            if (selected.length === 0) {
                showErrorMessage('Veuillez sélectionner au moins une classe');
                return;
            }

            // Mettre à jour le compteur dans le modal
            document.getElementById('selectedCount').textContent = selected.length;

            // Afficher le modal des actions groupées
            showBulkActionsModal();
        }

        // Exécuter une action groupée
        function executeBulkAction(action) {
            const selected = Array.from(document.querySelectorAll('.classe-checkbox:checked'));
            const selectedIds = selected.map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                showErrorMessage('Aucune classe sélectionnée');
                return;
            }

            // Fermer le modal des actions
            closeBulkActionsModal();

            // Configurer le modal de confirmation
            const confirmIcon = document.getElementById('confirmIcon');
            const confirmMessage = document.getElementById('confirmMessage');
            const warningMessage = document.getElementById('warningMessage');
            const confirmButton = document.getElementById('confirmBulkAction');

            // Réinitialiser les styles
            confirmIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center mr-4';
            warningMessage.classList.add('hidden');

            let actionText = '';
            let buttonClass = '';
            let iconClass = '';
            let iconBg = '';

            switch (action) {
                case 'archive':
                    actionText = 'archiver';
                    buttonClass = 'bg-blue-600 hover:bg-blue-700';
                    iconClass = 'fas fa-archive';
                    iconBg = 'bg-blue-100';
                    confirmIcon.innerHTML = `<i class="${iconClass} text-blue-600 text-xl"></i>`;
                    break;
                case 'delete':
                    actionText = 'supprimer définitivement';
                    buttonClass = 'bg-red-600 hover:bg-red-700';
                    iconClass = 'fas fa-trash';
                    iconBg = 'bg-red-100';
                    confirmIcon.innerHTML = `<i class="${iconClass} text-red-600 text-xl"></i>`;
                    warningMessage.classList.remove('hidden');
                    break;
            }

            confirmIcon.classList.add(iconBg);
            confirmMessage.textContent = `Êtes-vous sûr de vouloir ${actionText} ${selectedIds.length} classe(s) ?`;
            confirmButton.className = `px-4 py-2 text-white rounded-xl transition-colors ${buttonClass}`;
            confirmButton.textContent = actionText.charAt(0).toUpperCase() + actionText.slice(1);

            // Gestionnaire de confirmation
            confirmButton.onclick = function() {
                performBulkAction(action, selectedIds);
            };

            // Afficher le modal de confirmation
            showBulkConfirmModal();
        }

        // Effectuer l'action groupée
        function performBulkAction(action, classeIds) {
            // Fermer le modal de confirmation
            closeBulkConfirmModal();

            // Afficher un indicateur de chargement
            showLoadingMessage('Traitement en cours...');

            fetch('<?php echo e(route("private.classes.bulk-actions")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    action: action,
                    classe_ids: classeIds
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingMessage();

                if (data.success) {
                    showSuccessMessage(data.message);

                    // Décocher toutes les cases
                    document.querySelectorAll('.classe-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Recharger la page après un délai
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showErrorMessage(data.message || 'Erreur lors de l\'action groupée');
                }
            })
            .catch(error => {
                hideLoadingMessage();
                console.error('Erreur:', error);
                showErrorMessage('Une erreur est survenue lors de l\'action groupée');
            });
        }

        // Afficher un message de chargement
        function showLoadingMessage(message) {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingMessage';
            loadingDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-xl shadow-lg z-50';
            loadingDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-3"></div>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(loadingDiv);
        }

        // Masquer le message de chargement
        function hideLoadingMessage() {
            const loadingDiv = document.getElementById('loadingMessage');
            if (loadingDiv) {
                loadingDiv.remove();
            }
        }

        // Fonctions d'affichage des messages
        function showSuccessMessage(message) {
            showMessage(message, 'success');
        }

        function showErrorMessage(message) {
            showMessage(message, 'error');
        }

        function showMessage(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(alertDiv);

            // Animation d'entrée
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-full');
                alertDiv.classList.add('translate-x-0');
            }, 100);

            // Animation de sortie et suppression
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-0');
                alertDiv.classList.add('translate-x-full');
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }

        // Fermer les modals en cliquant à l'extérieur
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

        document.getElementById('bulkConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBulkConfirmModal();
            }
        });

        // Fermer les modals avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeBulkActionsModal();
                closeBulkConfirmModal();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/index.blade.php ENDPATH**/ ?>