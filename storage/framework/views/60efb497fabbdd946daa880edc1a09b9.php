<?php $__env->startSection('title', $multimedia->titre); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Navigation et actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('private.multimedia.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la galerie
                </a>
                <div class="text-sm text-slate-500">
                    <i class="fas fa-calendar mr-1"></i>
                    Ajouté le <?php echo e($multimedia->created_at->format('d/m/Y à H:i')); ?>

                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Actions principales -->
                <a href="<?php echo e(route('private.multimedia.download', $multimedia)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Télécharger
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $multimedia)): ?>
                    <a href="<?php echo e(route('private.multimedia.edit', $multimedia)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
                    <?php if($multimedia->statut_moderation == 'en_attente'): ?>
                        <button type="button" onclick="moderateMedia('approve')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-check mr-2"></i> Approuver
                        </button>
                        <button type="button" onclick="moderateMedia('reject')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times mr-2"></i> Rejeter
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('feature_media')): ?>
                    <button type="button" onclick="toggleFeatured()" class="inline-flex items-center px-4 py-2 <?php echo e($multimedia->est_featured ? 'bg-gradient-to-r from-yellow-600 to-amber-600' : 'bg-slate-400'); ?> text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-amber-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-star mr-2"></i> <?php echo e($multimedia->est_featured ? 'Retirer de la une' : 'Mettre à la une'); ?>

                    </button>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $multimedia)): ?>
                    <button type="button" onclick="deleteMedia()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white text-sm font-medium rounded-xl hover:from-gray-700 hover:to-slate-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Colonne principale : Aperçu du média -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Aperçu du média -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 relative">
                    <?php if($multimedia->est_image): ?>
                        <img src="<?php echo e($multimedia->url_complete); ?>" alt="<?php echo e($multimedia->alt_text ?? $multimedia->titre); ?>"
                             class="w-full h-full object-contain bg-black">
                    <?php elseif($multimedia->est_video): ?>
                        <video controls class="w-full h-full bg-black">
                            <source src="<?php echo e($multimedia->url_complete); ?>" type="<?php echo e($multimedia->type_mime); ?>">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    <?php elseif($multimedia->est_audio): ?>
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <i class="fas fa-music text-6xl text-slate-400 mb-4"></i>
                                <audio controls class="mb-4">
                                    <source src="<?php echo e($multimedia->url_complete); ?>" type="<?php echo e($multimedia->type_mime); ?>">
                                    Votre navigateur ne supporte pas la lecture audio.
                                </audio>
                                <p class="text-lg font-medium text-slate-700"><?php echo e($multimedia->titre); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <?php if($multimedia->type_media == 'document'): ?>
                                    <i class="fas fa-file-alt text-6xl text-blue-400 mb-4"></i>
                                <?php elseif($multimedia->type_media == 'presentation'): ?>
                                    <i class="fas fa-file-powerpoint text-6xl text-red-400 mb-4"></i>
                                <?php elseif($multimedia->type_media == 'archive'): ?>
                                    <i class="fas fa-file-archive text-6xl text-yellow-400 mb-4"></i>
                                <?php else: ?>
                                    <i class="fas fa-file text-6xl text-slate-400 mb-4"></i>
                                <?php endif; ?>
                                <h3 class="text-lg font-medium text-slate-700 mb-2"><?php echo e($multimedia->titre); ?></h3>
                                <p class="text-sm text-slate-500 uppercase"><?php echo e($multimedia->extension); ?> • <?php echo e($multimedia->taille_formatee); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Badges d'overlay -->
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        <?php if($multimedia->est_featured): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-star mr-1"></i> À la une
                            </span>
                        <?php endif; ?>
                        <?php if($multimedia->contenu_sensible): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-orange-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Sensible
                            </span>
                        <?php endif; ?>
                        <span class="inline-flex items-center px-3 py-1 bg-slate-800/80 text-white text-sm font-medium rounded-full shadow-lg">
                            <?php echo e($multimedia->type_media_label); ?>

                        </span>
                    </div>

                    <!-- Badge de statut -->
                    <div class="absolute top-4 right-4">
                        <?php if($multimedia->statut_moderation == 'en_attente'): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-orange-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-clock mr-1"></i> En attente
                            </span>
                        <?php elseif($multimedia->statut_moderation == 'approuve'): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-check mr-1"></i> Approuvé
                            </span>
                        <?php elseif($multimedia->statut_moderation == 'rejete'): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-times mr-1"></i> Rejeté
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800"><?php echo e($multimedia->titre); ?></h2>
                    <p class="text-slate-600 mt-1 capitalize"><?php echo e($multimedia->categorie_label); ?></p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Description -->
                    <?php if($multimedia->description): ?>
                        <div>
                            <h3 class="font-medium text-slate-900 mb-2">Description</h3>

                            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $multimedia,'field' => 'description','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($multimedia),'field' => 'description','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
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
                    <?php endif; ?>

                    <!-- Légende -->
                    <?php if($multimedia->legende): ?>
                        <div>
                            <h3 class="font-medium text-slate-900 mb-2">Légende</h3>
                            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $multimedia,'field' => 'legende','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($multimedia),'field' => 'legende','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
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
                    <?php endif; ?>

                    <!-- Tags -->
                    <?php if($multimedia->tags && count($multimedia->tags) > 0): ?>
                        <div>
                            <h3 class="font-medium text-slate-900 mb-2">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $multimedia->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($tag); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Événement associé -->
                    <?php if($multimedia->evenement_parent): ?>
                        <div>
                            <h3 class="font-medium text-slate-900 mb-2">Événement associé</h3>
                            <div class="bg-slate-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <?php if($multimedia->type_evenement_parent == 'culte'): ?>
                                            <i class="fas fa-church text-blue-600"></i>
                                        <?php elseif($multimedia->type_evenement_parent == 'evenement'): ?>
                                            <i class="fas fa-calendar-alt text-green-600"></i>
                                        <?php elseif($multimedia->type_evenement_parent == 'intervention'): ?>
                                            <i class="fas fa-microphone text-purple-600"></i>
                                        <?php else: ?>
                                            <i class="fas fa-users text-orange-600"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($multimedia->nom_evenement_parent); ?></p>
                                        <p class="text-sm text-slate-500 capitalize"><?php echo e(str_replace('_', ' ', $multimedia->type_evenement_parent)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Avertissement pour contenu sensible -->
                    <?php if($multimedia->contenu_sensible && $multimedia->avertissement): ?>
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-exclamation-triangle text-orange-600 mt-0.5"></i>
                                <div>
                                    <h3 class="font-medium text-orange-900 mb-1">Contenu sensible</h3>
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $multimedia,'field' => 'avertissement','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($multimedia),'field' => 'avertissement','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
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
                    <?php endif; ?>

                    <!-- Restrictions d'usage -->
                    <?php if($multimedia->restrictions_usage): ?>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                                <div>
                                    <h3 class="font-medium text-amber-900 mb-1">Restrictions d'usage</h3>
                                    
                                    <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $multimedia,'field' => 'restrictions_usage','showMeta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($multimedia),'field' => 'restrictions_usage','show-meta' => 'true','class' => 'bg-slate-50 p-4 rounded-lg']); ?>
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
                    <?php endif; ?>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
                <?php if($multimedia->statut_moderation == 'rejete' && $multimedia->commentaire_moderation): ?>
                    <!-- Commentaire de modération -->
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-times-circle text-red-600 mt-0.5"></i>
                            <div class="flex-1">
                                <h3 class="font-medium text-red-900 mb-2">Motif de rejet</h3>
                                <p class="text-sm text-red-800 mb-3"><?php echo e($multimedia->commentaire_moderation); ?></p>
                                <?php if($multimedia->moderator): ?>
                                    <p class="text-xs text-red-700">
                                        Rejeté par <?php echo e($multimedia->moderator->name); ?> le <?php echo e($multimedia->modere_le->format('d/m/Y à H:i')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar : Métadonnées -->
        <div class="space-y-6">
            <!-- Informations techniques -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations techniques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500">Type</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->type_media_label); ?></p>
                        </div>
                        <div>
                            <span class="text-slate-500">Format</span>
                            <p class="font-medium text-slate-900 uppercase"><?php echo e($multimedia->extension); ?></p>
                        </div>
                        <div>
                            <span class="text-slate-500">Taille</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->taille_formatee); ?></p>
                        </div>
                        <div>
                            <span class="text-slate-500">Qualité</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->qualites[$multimedia->qualite] ?? 'Standard'); ?></p>
                        </div>
                    </div>

                    <?php if($multimedia->largeur && $multimedia->hauteur): ?>
                        <div>
                            <span class="text-slate-500 text-sm">Dimensions</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->dimensions_formatee); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($multimedia->duree_formatee): ?>
                        <div>
                            <span class="text-slate-500 text-sm">Durée</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->duree_formatee); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($multimedia->bitrate): ?>
                        <div>
                            <span class="text-slate-500 text-sm">Bitrate</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->bitrate); ?> kbps</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Métadonnées de capture -->
            <?php if($multimedia->date_prise || $multimedia->lieu_prise || $multimedia->photographe || $multimedia->appareil): ?>
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="font-bold text-slate-800 flex items-center">
                            <i class="fas fa-camera text-purple-600 mr-2"></i>
                            Métadonnées de capture
                        </h3>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <?php if($multimedia->date_prise): ?>
                            <div>
                                <span class="text-slate-500">Date de capture</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->date_prise->format('d/m/Y à H:i')); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($multimedia->lieu_prise): ?>
                            <div>
                                <span class="text-slate-500">Lieu</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->lieu_prise); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($multimedia->photographe): ?>
                            <div>
                                <span class="text-slate-500">Auteur</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->photographe); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($multimedia->appareil): ?>
                            <div>
                                <span class="text-slate-500">Appareil</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->appareil); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Permissions et accès -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>
                        Permissions
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-slate-500 text-sm">Niveau d'accès</span>
                        <p class="font-medium text-slate-900"><?php echo e($multimedia->niveau_acces_label); ?></p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-medium text-slate-900 text-sm">Autorisations d'usage</h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Usage public</span>
                                <span class="text-<?php echo e($multimedia->usage_public ? 'green' : 'red'); ?>-600">
                                    <i class="fas fa-<?php echo e($multimedia->usage_public ? 'check' : 'times'); ?>"></i>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Site web</span>
                                <span class="text-<?php echo e($multimedia->usage_site_web ? 'green' : 'red'); ?>-600">
                                    <i class="fas fa-<?php echo e($multimedia->usage_site_web ? 'check' : 'times'); ?>"></i>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Réseaux sociaux</span>
                                <span class="text-<?php echo e($multimedia->usage_reseaux_sociaux ? 'green' : 'red'); ?>-600">
                                    <i class="fas fa-<?php echo e($multimedia->usage_reseaux_sociaux ? 'check' : 'times'); ?>"></i>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Usage commercial</span>
                                <span class="text-<?php echo e($multimedia->usage_commercial ? 'green' : 'red'); ?>-600">
                                    <i class="fas fa-<?php echo e($multimedia->usage_commercial ? 'check' : 'times'); ?>"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center p-3 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-slate-900"><?php echo e($multimedia->nombre_vues); ?></div>
                            <div class="text-slate-500">Vues</div>
                        </div>
                        <div class="text-center p-3 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-slate-900"><?php echo e($multimedia->nombre_telechargements); ?></div>
                            <div class="text-slate-500">Téléchargements</div>
                        </div>
                        <div class="text-center p-3 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-slate-900"><?php echo e($multimedia->nombre_likes); ?></div>
                            <div class="text-slate-500">Likes</div>
                        </div>
                        <div class="text-center p-3 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-slate-900"><?php echo e($multimedia->nombre_partages); ?></div>
                            <div class="text-slate-500">Partages</div>
                        </div>
                    </div>

                    <?php if($multimedia->derniere_vue): ?>
                        <div class="pt-3 border-t border-slate-200">
                            <span class="text-slate-500 text-sm">Dernière vue</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->derniere_vue->diffForHumans()); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations de suivi -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Suivi
                    </h3>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div>
                        <span class="text-slate-500">Créé par</span>
                        <p class="font-medium text-slate-900">
                            <?php if($multimedia->uploadedBy): ?>
                                <?php echo e($multimedia->uploadedBy->name); ?>

                            <?php else: ?>
                                Utilisateur supprimé
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-500">Date de création</span>
                        <p class="font-medium text-slate-900"><?php echo e($multimedia->created_at->format('d/m/Y à H:i')); ?></p>
                    </div>

                    <?php if($multimedia->updated_at != $multimedia->created_at): ?>
                        <div>
                            <span class="text-slate-500">Dernière modification</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->updated_at->format('d/m/Y à H:i')); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($multimedia->modere_le && $multimedia->moderator): ?>
                        <div>
                            <span class="text-slate-500">Modéré par</span>
                            <p class="font-medium text-slate-900"><?php echo e($multimedia->moderator->name); ?></p>
                            <p class="text-slate-500 text-xs"><?php echo e($multimedia->modere_le->format('d/m/Y à H:i')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
    <!-- Modal de modération -->
    <div id="moderationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-gavel text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Modération du média</h3>
                </div>
                <p class="text-slate-600 mb-4" id="moderationMessage"></p>
                <div id="commentSection" class="hidden mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire (requis pour le rejet)</label>
                    <textarea id="moderationComment" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Raison du rejet ou commentaire..."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeModerationModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmModeration" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $multimedia)): ?>
    <!-- Modal de suppression -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-trash text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Supprimer le média</h3>
                </div>
                <p class="text-slate-600 mb-4">Êtes-vous sûr de vouloir supprimer définitivement ce média ? Cette action est irréversible.</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-red-700">⚠️ Le fichier sera définitivement supprimé du serveur.</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
// Modération
function moderateMedia(action) {
    const modal = document.getElementById('moderationModal');
    const message = document.getElementById('moderationMessage');
    const commentSection = document.getElementById('commentSection');
    const confirmBtn = document.getElementById('confirmModeration');

    if (action === 'approve') {
        message.textContent = 'Êtes-vous sûr de vouloir approuver ce média ?';
        commentSection.classList.add('hidden');
        confirmBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors';
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

        fetch(`<?php echo e(route('private.multimedia.approve', $multimedia)); ?>`.replace('approve', action), {
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
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('feature_media')): ?>
// Toggle featured
function toggleFeatured() {
    fetch('<?php echo e(route("private.multimedia.toggle-featured", $multimedia)); ?>', {
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

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $multimedia)): ?>
// Suppression
function deleteMedia() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    fetch('<?php echo e(route("private.multimedia.destroy", $multimedia)); ?>', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?php echo e(route("private.multimedia.index")); ?>';
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    })
    .finally(() => {
        closeDeleteModal();
    });
}
<?php endif; ?>

// Fermeture des modals en cliquant à l'extérieur
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
document.getElementById('moderationModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModerationModal();
});
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $multimedia)): ?>
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/multimedia/show.blade.php ENDPATH**/ ?>