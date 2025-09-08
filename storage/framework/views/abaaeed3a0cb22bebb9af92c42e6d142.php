<?php $__env->startSection('title', 'Détails de l\'Annonce'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent"><?php echo e($annonce->titre); ?></h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.annonces.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Annonces
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500"><?php echo e(Str::limit($annonce->titre, 30)); ?></span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $annonce)): ?>
                            <a href="<?php echo e(route('private.annonces.edit', $annonce)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('publish', $annonce)): ?>
                            <?php if($annonce->statut === 'brouillon'): ?>
                                <button onclick="publierAnnonce('<?php echo e($annonce->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-paper-plane mr-2"></i> Publier
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('archive', $annonce)): ?>
                            <?php if($annonce->statut === 'publiee'): ?>
                                <button onclick="archiverAnnonce('<?php echo e($annonce->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-archive mr-2"></i> Archiver
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('duplicate', $annonce)): ?>
                            <button onclick="dupliquerAnnonce('<?php echo e($annonce->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-copy mr-2"></i> Dupliquer
                            </button>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $annonce)): ?>
                            <button onclick="supprimerAnnonce('<?php echo e($annonce->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('private.annonces.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenu de l'annonce -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 mb-2"><?php echo e($annonce->titre); ?></h2>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($annonce->badge_statut); ?>">
                                    <?php echo e(\App\Models\Annonce::getStatuts()[$annonce->statut] ?? $annonce->statut); ?>

                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($annonce->badge_priorite); ?>">
                                    <?php echo e(\App\Models\Annonce::getNiveauxPriorite()[$annonce->niveau_priorite] ?? $annonce->niveau_priorite); ?>

                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                    <?php echo e(\App\Models\Annonce::getTypesAnnonces()[$annonce->type_annonce] ?? $annonce->type_annonce); ?>

                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500">Audience cible</p>
                            <p class="font-semibold text-slate-700"><?php echo e(\App\Models\Annonce::getAudiencesCibles()[$annonce->audience_cible] ?? $annonce->audience_cible); ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-slate-700">
                        <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $annonce,'field' => 'contenu','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($annonce),'field' => 'contenu','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Détails de l'événement (si applicable) -->
            <?php if($annonce->type_annonce === 'evenement' && ($annonce->date_evenement || $annonce->lieu_evenement)): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-event text-green-600 mr-2"></i>
                            Détails de l'Événement
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php if($annonce->date_evenement): ?>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-slate-400 w-5 mr-3"></i>
                                <div>
                                    <p class="font-semibold text-slate-800">Date</p>
                                    <p class="text-slate-600"><?php echo e($annonce->date_evenement->format('l d F Y')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($annonce->lieu_evenement): ?>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-slate-400 w-5 mr-3"></i>
                                <div>
                                    <p class="font-semibold text-slate-800">Lieu</p>
                                    <p class="text-slate-600"><?php echo e($annonce->lieu_evenement); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Historique des modifications -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Historique
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold text-slate-800">Création</p>
                                <p class="text-sm text-slate-600"><?php echo e($annonce->created_at->format('d/m/Y à H:i')); ?></p>
                            </div>
                        </div>
                        <?php if($annonce->auteur): ?>
                            <span class="text-sm text-slate-600"><?php echo e($annonce->auteur->nom); ?> <?php echo e($annonce->auteur->prenom); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if($annonce->publie_le): ?>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="font-semibold text-slate-800">Publication</p>
                                    <p class="text-sm text-slate-600"><?php echo e($annonce->publie_le->format('d/m/Y à H:i')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($annonce->updated_at && $annonce->updated_at != $annonce->created_at): ?>
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="font-semibold text-slate-800">Dernière modification</p>
                                    <p class="text-sm text-slate-600"><?php echo e($annonce->updated_at->format('d/m/Y à H:i')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar - Informations et contact -->
        <div class="space-y-6">
            <!-- Informations de diffusion -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-broadcast-tower text-purple-600 mr-2"></i>
                        Diffusion
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Site web</span>
                        <span class="inline-flex items-center">
                            <?php if($annonce->afficher_site_web): ?>
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span class="text-sm text-green-600">Activé</span>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-red-500 mr-1"></i>
                                <span class="text-sm text-red-600">Désactivé</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Culte</span>
                        <span class="inline-flex items-center">
                            <?php if($annonce->annoncer_culte): ?>
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span class="text-sm text-green-600">Activé</span>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-red-500 mr-1"></i>
                                <span class="text-sm text-red-600">Désactivé</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <?php if($annonce->expire_le): ?>
                        <div class="pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Expire le</span>
                                <span class="text-sm text-slate-600"><?php echo e($annonce->expire_le->format('d/m/Y')); ?></span>
                            </div>
                            <?php if($annonce->jours_restants !== null): ?>
                                <div class="mt-2">
                                    <?php if($annonce->jours_restants > 0): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($annonce->jours_restants <= 3 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'); ?>">
                                            <?php echo e($annonce->jours_restants); ?> jour<?php echo e($annonce->jours_restants > 1 ? 's' : ''); ?> restant<?php echo e($annonce->jours_restants > 1 ? 's' : ''); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expirée
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact principal -->
            <?php if($annonce->contactPrincipal): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-circle text-green-600 mr-2"></i>
                            Contact Principal
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo e(substr($annonce->contactPrincipal->prenom, 0, 1)); ?><?php echo e(substr($annonce->contactPrincipal->nom, 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-semibold text-slate-800"><?php echo e($annonce->contactPrincipal->nom); ?> <?php echo e($annonce->contactPrincipal->prenom); ?></p>
                                <div class="space-y-1">
                                    <a href="mailto:<?php echo e($annonce->contactPrincipal->email); ?>" class="flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-envelope w-4 mr-2"></i>
                                        <?php echo e($annonce->contactPrincipal->email); ?>

                                    </a>
                                    <?php if($annonce->contactPrincipal->telephone): ?>
                                        <a href="tel:<?php echo e($annonce->contactPrincipal->telephone); ?>" class="flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                            <i class="fas fa-phone w-4 mr-2"></i>
                                            <?php echo e($annonce->contactPrincipal->telephone); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Statistiques -->
            <?php if($annonce->statut === 'publiee'): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                            Statistiques
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-800"><?php echo e($annonce->created_at->diffForHumans()); ?></div>
                            <div class="text-sm text-slate-600">Créée</div>
                        </div>

                        <?php if($annonce->publie_le): ?>
                            <div class="text-center pt-3 border-t border-slate-100">
                                <div class="text-2xl font-bold text-green-600"><?php echo e($annonce->publie_le->diffForHumans()); ?></div>
                                <div class="text-sm text-slate-600">Publiée</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions rapides (mobile) -->
            <div class="block lg:hidden">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Actions</h3>
                        <div class="flex flex-col space-y-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $annonce)): ?>
                                <a href="<?php echo e(route('private.annonces.edit', $annonce->id)); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i> Modifier
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('publish', $annonce)): ?>
                                <?php if($annonce->statut === 'brouillon'): ?>
                                    <button onclick="publierAnnonce('<?php echo e($annonce->id); ?>')" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                        <i class="fas fa-paper-plane mr-2"></i> Publier
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
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
                window.location.href = '<?php echo e(route("private.annonces.index")); ?>';
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/annonces/show.blade.php ENDPATH**/ ?>