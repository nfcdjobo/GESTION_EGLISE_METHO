<?php $__env->startSection('title', 'Gestion des Annonces'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion
                des Annonces</h1>
            <p class="text-slate-500 mt-1">Communication et annonces pour la communauté -
                <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
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
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.create')): ?>
                            <a href="<?php echo e(route('private.annonces.create')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouvelle Annonce
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.publish')): ?>
                            <a href="<?php echo e(route('private.annonces.actives')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i> Publiques
                            </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.statistics')): ?>
                            <button onclick="loadStatistiques()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Statistiques
                            </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('private.annonces.export-liste-pdf', request()->query())); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
<i class="fas fa-file-pdf"></i> Exporter la liste en PDF
</a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="<?php echo e(route('private.annonces.index')); ?>"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo e($filtres['search'] ?? ''); ?>"
                                placeholder="Titre, contenu..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                        <select name="statut"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les statuts</option>
                            <?php $__currentLoopData = $statuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e(($filtres['statut'] ?? '') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                        <select name="type_annonce"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les types</option>
                            <?php $__currentLoopData = $typesAnnonces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e(($filtres['type_annonce'] ?? '') == $key ? 'selected' : ''); ?>><?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                        <select name="niveau_priorite"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes priorités</option>
                            <?php $__currentLoopData = $niveauxPriorite; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e(($filtres['niveau_priorite'] ?? '') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Audience</label>
                        <select name="audience_cible"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes audiences</option>
                            <?php $__currentLoopData = $audiencesCibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e(($filtres['audience_cible'] ?? '') == $key ? 'selected' : ''); ?>><?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="lg:col-span-6 flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                        <a href="<?php echo e(route('private.annonces.index')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-bullhorn text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($annonces->total()); ?></p>
                        <p class="text-sm text-slate-500">Total annonces</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($annonces->where('statut', 'publiee')->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">Publiées</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($annonces->where('statut', 'brouillon')->count()); ?>

                        </p>
                        <p class="text-sm text-slate-500">Brouillons</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            <?php echo e($annonces->where('niveau_priorite', 'urgent')->count()); ?></p>
                        <p class="text-sm text-slate-500">Urgentes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des annonces -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Annonces (<?php echo e($annonces->total()); ?>)
                    </h2>
                </div>
            </div>
            <div class="p-6">
                <?php if($annonces->count() > 0): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $annonces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $annonce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 mb-1"><?php echo e($annonce->titre); ?></h3>
                                        <p class="text-sm text-slate-600">
                                            <?php echo e($typesAnnonces[$annonce->type_annonce] ?? $annonce->type_annonce); ?></p>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($annonce->badge_statut); ?>">
                                            <?php echo e($statuts[$annonce->statut] ?? $annonce->statut); ?>

                                        </span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($annonce->badge_priorite); ?>">
                                            <?php echo e($niveauxPriorite[$annonce->niveau_priorite] ?? $annonce->niveau_priorite); ?>

                                        </span>
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="space-y-3 mb-4">
                                    <?php if($annonce->date_evenement): ?>
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                            <span><?php echo e($annonce->date_evenement->format('d/m/Y')); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($annonce->lieu_evenement): ?>
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                            <span><?php echo e($annonce->lieu_evenement); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($annonce->contactPrincipal): ?>
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-user w-4 mr-2"></i>
                                            <span><?php echo e($annonce->contactPrincipal->nom); ?>

                                                <?php echo e($annonce->contactPrincipal->prenom); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($annonce->expire_le): ?>
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-clock w-4 mr-2"></i>
                                            <span>Expire le <?php echo e($annonce->expire_le->format('d/m/Y')); ?></span>
                                            <?php if($annonce->jours_restants !== null && $annonce->jours_restants <= 3): ?>
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <?php echo e($annonce->jours_restants); ?> jours
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Badges d'options -->
                                    <div class="flex flex-wrap gap-1">
                                        <?php if($annonce->afficher_site_web): ?>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-globe mr-1"></i> Site web
                                            </span>
                                        <?php endif; ?>
                                        <?php if($annonce->annoncer_culte): ?>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-church mr-1"></i> Culte
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Aperçu du contenu -->
                                    <div class="text-sm text-slate-600">
                                        <p class="line-clamp-2">
                                            <?php echo e(Str::limit(strip_tags($annonce->contenu), 100)); ?>

                                        </p>
                                    </div>
                                </div>

                                <!-- Footer avec actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                    <div class="flex items-center space-x-2">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.read')): ?>
                                            <a href="<?php echo e(route('private.annonces.show', $annonce)); ?>"
                                                class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.update')): ?>
                                            <a href="<?php echo e(route('private.annonces.edit', $annonce)); ?>"
                                                class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                title="Modifier">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.publish')): ?>
                                            <?php if($annonce->statut === 'brouillon'): ?>
                                                <button type="button" onclick="publierAnnonce('<?php echo e($annonce->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                    title="Publier">
                                                    <i class="fas fa-paper-plane text-sm"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.archive')): ?>
                                            <?php if($annonce->statut === 'publiee'): ?>
                                                <button type="button" onclick="archiverAnnonce('<?php echo e($annonce->id); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors"
                                                    title="Archiver">
                                                    <i class="fas fa-archive text-sm"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.duplicate')): ?>
                                            <button type="button" onclick="dupliquerAnnonce('<?php echo e($annonce->id); ?>')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors"
                                                title="Dupliquer">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.delete')): ?>
                                        <button type="button" onclick="supprimerAnnonce('<?php echo e($annonce->id); ?>')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                            title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Informations de création -->
                                <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                                    <?php if($annonce->auteur): ?>
                                        Par <?php echo e($annonce->auteur->nom); ?> <?php echo e($annonce->auteur->prenom); ?>

                                    <?php endif; ?>
                                    <?php if($annonce->publie_le): ?>
                                        • Publié le <?php echo e($annonce->publie_le->format('d/m/Y à H:i')); ?>

                                    <?php else: ?>
                                        • Créé le <?php echo e($annonce->created_at->format('d/m/Y à H:i')); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium"><?php echo e($annonces->firstItem()); ?></span> à <span
                                class="font-medium"><?php echo e($annonces->lastItem()); ?></span>
                            sur <span class="font-medium"><?php echo e($annonces->total()); ?></span> résultats
                        </div>
                        <div>
                            <?php echo e($annonces->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullhorn text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune annonce trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            <?php if(request()->hasAny(['search', 'statut', 'type_annonce', 'niveau_priorite'])): ?>
                                Aucune annonce ne correspond à vos critères de recherche.
                            <?php else: ?>
                                Commencez par créer votre première annonce.
                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.create')): ?>
                            <a href="<?php echo e(route('private.annonces.create')); ?>"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Créer une annonce
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.publish')): ?>
                function publierAnnonce(annonceId) {
                    if (confirm('Êtes-vous sûr de vouloir publier cette annonce ?')) {
                        fetch(`<?php echo e(route('private.annonces.publier', ':annonce')); ?>`.replace(':annonce', annonceId), {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Accept': 'application/json',
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
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.archive')): ?>
            function archiverAnnonce(annonceId) {
                if (confirm('Êtes-vous sûr de vouloir archiver cette annonce ?')) {
                    fetch(`<?php echo e(route('private.annonces.archiver', ':annonce')); ?>`.replace(':annonce', annonceId), {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json',
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
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.duplicate')): ?>
            function dupliquerAnnonce(annonceId) {
                if (confirm('Voulez-vous créer une copie de cette annonce ?')) {
                    fetch(`<?php echo e(route('private.annonces.dupliquer', ':annonce')); ?>`.replace(':annonce', annonceId), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.redirect) {
                                window.location.href = data.redirect;
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
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.delete')): ?>
            function supprimerAnnonce(annonceId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.')) {
                    fetch(`<?php echo e(route('private.annonces.destroy', ':annonce')); ?>`.replace(':annonce', annonceId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json',
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
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('annonces.statistics')): ?>
            function loadStatistiques() {
                fetch("<?php echo e(route('private.annonces.statistiques')); ?>")
                    .then(response => response.json())
                    .then(data => {
                        console.log('Statistiques:', data);
                        // Ici vous pouvez afficher les statistiques dans une modale
                        // alert('Statistiques chargées - voir la console pour les détails');
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors du chargement des statistiques');
                    });
            }
            <?php endif; ?>
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/annonces/index.blade.php ENDPATH**/ ?>