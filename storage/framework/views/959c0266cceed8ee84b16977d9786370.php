<?php $__env->startSection('title', 'Galerie Multimédia'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Galerie Multimédia</h1>
                <p class="text-slate-500 mt-1">Gestion de la médiathèque de la communauté -
                    <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
            </div>
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
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.create')): ?>
                        <a href="<?php echo e(route('private.multimedia.create')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Télécharger un Média
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
                            <a href="<?php echo e(route('private.multimedia.moderation.queue')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-gavel mr-2"></i> File de Modération
                                <?php if($multimedia->where('statut_moderation', 'en_attente')->count() > 0): ?>
                                    <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">
                                        <?php echo e($multimedia->where('statut_moderation', 'en_attente')->count()); ?>

                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('private.multimedia.galerie')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i> Vue Publique
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.statistics')): ?>
                            <a href="<?php echo e(route('private.multimedia.statistiques')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Statistiques
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('private.multimedia.index')); ?>" class="space-y-6" id="filterForm">
                    <!-- Première ligne : Recherche et événements -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e($currentFilters['search'] ?? ''); ?>"
                                    placeholder="Titre, description, photographe, lieu..."
                                    class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                            <select name="culte_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les cultes</option>
                                <?php $__currentLoopData = $cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($culte->id); ?>"
                                        <?php echo e(($currentFilters['culte_id'] ?? '') == $culte->id ? 'selected' : ''); ?>>
                                        <?php echo e($culte->titre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Événement</label>
                            <select name="event_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les événements</option>
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($event->id); ?>"
                                        <?php echo e(($currentFilters['event_id'] ?? '') == $event->id ? 'selected' : ''); ?>>
                                        <?php echo e($event->titre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Deuxième ligne : Filtres de contenu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type de média</label>
                            <select name="type_media"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les types</option>
                                <?php $__currentLoopData = $filters['types_media']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e(($currentFilters['type_media'] ?? '') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                            <select name="categorie"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes les catégories</option>
                                <?php $__currentLoopData = $filters['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e(($currentFilters['categorie'] ?? '') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut Modération</label>
                            <select name="statut_moderation"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                <?php $__currentLoopData = $filters['statuts_moderation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e(($currentFilters['statut_moderation'] ?? '') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Niveau d'Accès</label>
                            <select name="niveau_acces"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les niveaux</option>
                                <?php $__currentLoopData = $filters['niveaux_acces']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e(($currentFilters['niveau_acces'] ?? '') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléchargé par</label>
                            <select name="telecharge_par"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les utilisateurs</option>
                                <?php $__currentLoopData = $uploaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uploader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($uploader->id); ?>"
                                        <?php echo e(($currentFilters['telecharge_par'] ?? '') == $uploader->id ? 'selected' : ''); ?>>
                                        <?php echo e($uploader->nom . ' ' . $uploader->prenom); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Options</label>
                            <div class="space-y-2 pt-1">
                                <label class="flex items-center">
                                    <input type="checkbox" name="featured_only" value="1"
                                        <?php echo e(request('featured_only') ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">À la une uniquement</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="visible_only" value="1"
                                        <?php echo e(request('visible_only') ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Visibles uniquement</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions de filtre -->
                    <div class="flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Filtrer
                        </button>
                        <a href="<?php echo e(route('private.multimedia.index')); ?>"
                            class="inline-flex items-center px-6 py-3 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                        <button type="button" onclick="toggleAdvancedFilters()"
                            class="inline-flex items-center px-4 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <i class="fas fa-cog mr-2"></i> Avancé
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-photo-video text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($multimedia->total()); ?></p>
                        <p class="text-sm text-slate-500">Total médias</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-images text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($multimedia->where('type_media', 'image')->count()); ?></p>
                        <p class="text-sm text-slate-500">Images</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-video text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($multimedia->where('type_media', 'video')->count()); ?></p>
                        <p class="text-sm text-slate-500">Vidéos</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-music text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($multimedia->where('type_media', 'audio')->count()); ?></p>
                        <p class="text-sm text-slate-500">Audios</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($multimedia->where('est_featured', true)->count()); ?></p>
                        <p class="text-sm text-slate-500">À la une</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions en lot (pour les modérateurs) -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
            <div id="bulkActions" class="hidden bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-amber-800">
                            <span id="selectedCount">0</span> média(s) sélectionné(s)
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.approve')): ?>
                            <button type="button" onclick="bulkAction('approve')"
                                class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-1"></i> Approuver
                            </button>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.reject')): ?>
                            <button type="button" onclick="bulkAction('reject')"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-times mr-1"></i> Rejeter
                            </button>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.delete')): ?>
                            <button type="button" onclick="bulkAction('delete')"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i> Supprimer
                            </button>
                        <?php endif; ?>
                        <button type="button" onclick="clearSelection()"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Grille des médias -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-images text-purple-600 mr-2"></i>
                        Médiathèque (<?php echo e($multimedia->total()); ?>)
                    </h2>
                    <div class="flex items-center space-x-4">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Tout sélectionner</span>
                            </label>
                        <?php endif; ?>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="toggleView('grid')"
                                class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="gridViewBtn">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" onclick="toggleView('list')"
                                class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="listViewBtn">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <?php if($multimedia->count() > 0): ?>
                    <!-- Vue grille (par défaut) -->
                    <div id="gridView"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        <?php $__currentLoopData = $multimedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="group relative bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
                                    <div class="absolute top-2 left-2 z-10">
                                        <input type="checkbox" name="selected_media[]" value="<?php echo e($media->id); ?>"
                                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 media-checkbox">
                                    </div>
                                <?php endif; ?>

                                <!-- Aperçu du média -->
                                <div
                                    class="aspect-square relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                                    <?php if($media->est_image && $media->url_miniature): ?>
                                        <img src="<?php echo e(asset($media->url_miniature)); ?>"
                                            alt="<?php echo e($media->alt_text ?? $media->titre); ?>"
                                            class="w-full h-full object-cover">
                                    <?php elseif($media->est_image && $media->url_complete): ?>
                                        <img src="<?php echo e(asset($media->url_complete)); ?>"
                                            alt="<?php echo e($media->alt_text ?? $media->titre); ?>"
                                            class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <div class="text-center">
                                                <?php if($media->type_media == 'video'): ?>
                                                    <i class="fas fa-video text-4xl text-slate-400 mb-2"></i>
                                                <?php elseif($media->type_media == 'audio'): ?>
                                                    <i class="fas fa-music text-4xl text-slate-400 mb-2"></i>
                                                <?php elseif($media->type_media == 'document'): ?>
                                                    <i class="fas fa-file-alt text-4xl text-slate-400 mb-2"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-file text-4xl text-slate-400 mb-2"></i>
                                                <?php endif; ?>
                                                <p class="text-xs text-slate-500 uppercase font-medium">
                                                    <?php echo e($media->extension); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Overlay avec badges -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-2 left-2 right-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex flex-wrap gap-1">
                                                    <?php if($media->est_featured): ?>
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-yellow-500 text-white text-xs rounded">
                                                            <i class="fas fa-star mr-1"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($media->statut_moderation == 'en_attente'): ?>
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-orange-500 text-white text-xs rounded">
                                                            <i class="fas fa-clock mr-1"></i>
                                                        </span>
                                                    <?php elseif($media->statut_moderation == 'approuve'): ?>
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-green-500 text-white text-xs rounded">
                                                            <i class="fas fa-check mr-1"></i>
                                                        </span>
                                                    <?php elseif($media->statut_moderation == 'rejete'): ?>
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-red-500 text-white text-xs rounded">
                                                            <i class="fas fa-times mr-1"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-white text-xs bg-black/20 px-1.5 py-0.5 rounded">
                                                    <?php echo e($media->taille_formatee); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions overlay -->
                                    <div
                                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex flex-col space-y-1">
                                            <a href="<?php echo e(route('private.multimedia.show', $media)); ?>"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.update')): ?>
                                                <a href="<?php echo e(route('private.multimedia.edit', $media)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.download')): ?>
                                                <a href="<?php echo e(route('private.multimedia.download', $media)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                    title="Télécharger">
                                                    <i class="fas fa-download text-sm"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations du média -->
                                <div class="p-4">
                                    <div class="mb-2">
                                        <h3 class="font-semibold text-slate-900 text-sm line-clamp-2 mb-1">
                                            <?php echo e($media->titre); ?></h3>
                                        <p class="text-xs text-slate-500 capitalize"><?php echo e($media->categorie_label); ?></p>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <div class="flex items-center space-x-2">
                                            <?php if($media->largeur && $media->hauteur): ?>
                                                <span><?php echo e($media->dimensions_formatee); ?></span>
                                            <?php elseif($media->duree_formatee): ?>
                                                <span><?php echo e($media->duree_formatee); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <i class="fas fa-eye text-slate-400"></i>
                                            <span><?php echo e($media->nombre_vues); ?></span>
                                        </div>
                                    </div>

                                    <!-- Actions rapides -->
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="text-xs text-slate-500">
                                            <?php echo e($media->created_at->diffForHumans()); ?>

                                        </div>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(['multimedia.approve', 'multimedia.reject', 'multimedia.toggle-featured'])): ?>
                                            <div class="flex items-center space-x-1">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(['multimedia.approve', 'multimedia.reject'])): ?>
                                                    <?php if($media->statut_moderation == 'en_attente'): ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.approve')): ?>
                                                            <button type="button"
                                                                onclick="moderateMedia('<?php echo e($media->id); ?>', 'approve')"
                                                                class="text-green-600 hover:text-green-700 transition-colors"
                                                                title="Approuver">
                                                                <i class="fas fa-check text-sm"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.reject')): ?>
                                                            <button type="button"
                                                                onclick="moderateMedia('<?php echo e($media->id); ?>', 'reject')"
                                                                class="text-red-600 hover:text-red-700 transition-colors"
                                                                title="Rejeter">
                                                                <i class="fas fa-times text-sm"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.toggle-featured')): ?>
                                                    <button type="button" onclick="toggleFeatured('<?php echo e($media->id); ?>')"
                                                        class="text-yellow-600 hover:text-yellow-700 transition-colors <?php echo e($media->est_featured ? 'opacity-100' : 'opacity-50'); ?>"
                                                        title="Mettre à la une">
                                                        <i class="fas fa-star text-sm"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Vue liste (cachée par défaut) -->
                    <div id="listView" class="hidden space-y-4">
                        <?php $__currentLoopData = $multimedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="flex items-center space-x-4 p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all duration-300">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
                                    <div class="flex-shrink-0">
                                        <input type="checkbox" name="selected_media[]" value="<?php echo e($media->id); ?>"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 media-checkbox">
                                    </div>
                                <?php endif; ?>

                                <!-- Miniature -->
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg overflow-hidden">
                                    <?php if($media->est_image && $media->url_miniature): ?>
                                        <img src="<?php echo e(asset($media->url_miniature)); ?>" alt="<?php echo e($media->titre); ?>"
                                            class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <?php if($media->type_media == 'video'): ?>
                                                <i class="fas fa-video text-slate-400"></i>
                                            <?php elseif($media->type_media == 'audio'): ?>
                                                <i class="fas fa-music text-slate-400"></i>
                                            <?php else: ?>
                                                <i class="fas fa-file text-slate-400"></i>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Informations -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-slate-900 truncate"><?php echo e($media->titre); ?></h3>
                                            <div class="flex items-center space-x-4 text-sm text-slate-500 mt-1">
                                                <span class="capitalize"><?php echo e($media->type_media_label); ?></span>
                                                <span class="capitalize"><?php echo e($media->categorie_label); ?></span>
                                                <span><?php echo e($media->taille_formatee); ?></span>
                                                <?php if($media->uploadedBy): ?>
                                                    <span>par <?php echo e($media->uploadedBy->name); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <!-- Badges de statut -->
                                            <?php if($media->est_featured): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                    <i class="fas fa-star mr-1"></i> À la une
                                                </span>
                                            <?php endif; ?>
                                            <?php if($media->statut_moderation == 'en_attente'): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">
                                                    <i class="fas fa-clock mr-1"></i> En attente
                                                </span>
                                            <?php elseif($media->statut_moderation == 'approuve'): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                    <i class="fas fa-check mr-1"></i> Approuvé
                                                </span>
                                            <?php elseif($media->statut_moderation == 'rejete'): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                                    <i class="fas fa-times mr-1"></i> Rejeté
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="<?php echo e(route('private.multimedia.show', $media)); ?>"
                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                        title="Voir">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.update')): ?>
                                        <a href="<?php echo e(route('private.multimedia.edit', $media)); ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                            title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.download')): ?>
                                    <a href="<?php echo e(route('private.multimedia.download', $media)); ?>"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                        title="Télécharger">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(['multimedia.approve', 'multimedia.reject'])): ?>
                                        <?php if($media->statut_moderation == 'en_attente'): ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.approve')): ?>
                                            <button type="button" onclick="moderateMedia('<?php echo e($media->id); ?>', 'approve')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                title="Approuver">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.reject')): ?>
                                            <button type="button" onclick="moderateMedia('<?php echo e($media->id); ?>', 'reject')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                title="Rejeter">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-8 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium"><?php echo e($multimedia->firstItem()); ?></span> à <span
                                class="font-medium"><?php echo e($multimedia->lastItem()); ?></span>
                            sur <span class="font-medium"><?php echo e($multimedia->total()); ?></span> résultats
                        </div>
                        <div>
                            <?php echo e($multimedia->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-photo-video text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun média trouvé</h3>
                        <p class="text-slate-500 mb-6">
                            <?php if(request()->hasAny(['search', 'culte_id', 'event_id', 'type_media', 'categorie'])): ?>
                                Aucun média ne correspond à vos critères de recherche.
                            <?php else: ?>
                                Votre médiathèque est vide. Commencez par télécharger votre premier média.
                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.download')): ?>
                        <a href="<?php echo e(route('private.multimedia.create')); ?>"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Télécharger un média
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
        <!-- Modal de modération -->
        <div id="moderationModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-gavel text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Modération du média</h3>
                    </div>
                    <p class="text-slate-600 mb-4" id="moderationMessage">Êtes-vous sûr de vouloir effectuer cette action ?
                    </p>
                    <div id="commentSection" class="hidden mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire (requis pour le rejet)</label>
                        <textarea id="moderationComment" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Raison du rejet ou commentaire..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeModerationModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" id="confirmModeration"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal d'action en lot -->
        <div id="bulkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-list-check text-amber-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Action en lot</h3>
                    </div>
                    <p class="text-slate-600 mb-4" id="bulkMessage">Confirmer l'action sur les médias sélectionnés ?</p>
                    <div id="bulkCommentSection" class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                        <textarea id="bulkComment" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Commentaire optionnel..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeBulkModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" id="confirmBulk"
                        class="px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-colors">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        let currentView = 'grid';

        // Basculer entre les vues grille/liste
        function toggleView(view) {
            currentView = view;
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                if (gridView) {
                    gridView.classList.remove('hidden');
                }

                if (listView) {
                    listView.classList.add('hidden');
                }

                if (gridBtn) {
                    gridBtn.classList.add('text-blue-600');
                    gridBtn.classList.remove('text-slate-600');
                }

                if (listBtn) {
                    listBtn.classList.add('text-slate-600');
                    listBtn.classList.remove('text-blue-600');
                }

            } else {
                if (gridView) {
                    gridView.classList.add('hidden');
                }

                if (listView) {
                    listView.classList.remove('hidden');
                }

                if (listBtn) {
                    listBtn.classList.add('text-blue-600');
                    listBtn.classList.remove('text-slate-600');
                }

                if (gridBtn) {
                    gridBtn.classList.add('text-slate-600');
                    gridBtn.classList.remove('text-blue-600');
                }
            }

            // Sauvegarder la préférence
            localStorage.setItem('multimedia_view', view);
        }

        // Charger la préférence de vue au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('multimedia_view') || 'grid';
            toggleView(savedView);
        });

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
            // Gestion de la sélection
            const selectAllCheckbox = document.getElementById('selectAll');
            const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            selectAllCheckbox?.addEventListener('change', function() {
                mediaCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            mediaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            function updateBulkActions() {
                const checked = document.querySelectorAll('.media-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActions?.classList.remove('hidden');
                    selectedCount.textContent = count;
                } else {
                    bulkActions?.classList.add('hidden');
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                }
            }

            function clearSelection() {
                mediaCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateBulkActions();
            }

            // Modération individuelle
            function moderateMedia(mediaId, action) {
                const modal = document.getElementById('moderationModal');
                const message = document.getElementById('moderationMessage');
                const commentSection = document.getElementById('commentSection');
                const confirmBtn = document.getElementById('confirmModeration');

                if (action === 'approve') {
                    message.textContent = 'Êtes-vous sûr de vouloir approuver ce média ?';
                    commentSection.classList.add('hidden');
                    confirmBtn.className =
                        'px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors';
                    confirmBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Approuver';
                } else if (action === 'reject') {
                    message.textContent = 'Pourquoi voulez-vous rejeter ce média ?';
                    commentSection.classList.remove('hidden');
                    confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors';
                    confirmBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Rejeter';
                }

                confirmBtn.onclick = function() {
                    const comment = document.getElementById('moderationComment').value;
                    if (action === 'reject' && !comment.trim()) {
                        alert('Un commentaire est requis pour rejeter un média.');
                        return;
                    }

                    const formData = new FormData();
                    if (comment) formData.append('commentaire', comment);
                    if (action === 'reject') formData.append('raison', comment);

                    fetch(`<?php echo e(route('private.multimedia.index')); ?>/${mediaId}/${action}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json'
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
                        })
                        .finally(() => {
                            closeModerationModal();
                        });
                };

                modal.classList.remove('hidden');
            }

            function closeModerationModal() {
                const modal = document.getElementById('moderationModal');
                modal.classList.add('hidden');
                document.getElementById('moderationComment').value = '';
            }

            // Actions en lot
            function bulkAction(action) {
                const selected = Array.from(document.querySelectorAll('.media-checkbox:checked'))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('Veuillez sélectionner au moins un média');
                    return;
                }

                const modal = document.getElementById('bulkModal');
                const message = document.getElementById('bulkMessage');
                const confirmBtn = document.getElementById('confirmBulk');

                const actions = {
                    approve: 'approuver',
                    reject: 'rejeter',
                    delete: 'supprimer'
                };

                message.textContent = `Êtes-vous sûr de vouloir ${actions[action]} ${selected.length} média(s) ?`;

                confirmBtn.onclick = function() {
                    const comment = document.getElementById('bulkComment').value;

                    if (action === 'reject' && !comment.trim()) {
                        alert('Un commentaire est requis pour rejeter des médias.');
                        return;
                    }

                    fetch("<?php echo e(route('private.multimedia.bulk-moderate')); ?>", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                media_ids: selected,
                                action: action,
                                commentaire: comment
                            })
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
                        })
                        .finally(() => {
                            closeBulkModal();
                        });
                };

                modal.classList.remove('hidden');
            }

            function closeBulkModal() {
                const modal = document.getElementById('bulkModal');
                modal.classList.add('hidden');
                document.getElementById('bulkComment').value = '';
            }
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.toggle-featured')): ?>
            // Basculer featured
            function toggleFeatured(mediaId) {
                fetch(`<?php echo e(route('private.multimedia.toggle-featured', ':multimedia')); ?>`.replace(':multimedia', mediaId), {
                        method: 'PATCH',
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
        <?php endif; ?>

        // Fermeture des modals en cliquant à l'extérieur
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('multimedia.moderate')): ?>
            document.getElementById('moderationModal')?.addEventListener('click', function(e) {
                if (e.target === this) closeModerationModal();
            });

            document.getElementById('bulkModal')?.addEventListener('click', function(e) {
                if (e.target === this) closeBulkModal();
            });
        <?php endif; ?>

        // Filtres avancés (placeholder pour extension future)
        function toggleAdvancedFilters() {
            // Logique pour afficher/masquer des filtres avancés
            console.log('Filtres avancés à implémenter');
        }

        // Auto-submit des filtres au changement
        document.getElementById('filterForm').addEventListener('change', function(e) {
            if (e.target.type !== 'text') {
                this.submit();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/multimedia/index.blade.php ENDPATH**/ ?>