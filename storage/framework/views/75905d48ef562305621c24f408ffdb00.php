<?php $__env->startSection('title', 'Modifier ' . $multimedia->titre); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Média</h1>
                    <p class="text-slate-500 mt-1"><?php echo e($multimedia->titre); ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-slate-500">
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            Créé le <?php echo e($multimedia->created_at->format('d/m/Y')); ?>

                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            <?php echo e($multimedia->uploadedBy->nom. ' '.$multimedia->uploadedBy->prenom ?? 'Inconnu'); ?>

                        </span>
                        <span class="flex items-center capitalize">
                            <i class="fas fa-tag mr-1"></i>
                            <?php echo e($multimedia->categorie_label); ?>

                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(route('private.multimedia.show', $multimedia)); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir
                    </a>
                    <a href="<?php echo e(route('private.multimedia.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Aperçu du fichier actuel -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-file-image text-blue-600 mr-2"></i>
                Fichier Actuel
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-slate-50 rounded-xl p-6">
                <div class="flex items-center space-x-6">
                    <!-- Aperçu -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl overflow-hidden">
                            <?php if($multimedia->est_image && $multimedia->url_miniature): ?>
                                <img src="<?php echo e($multimedia->url_miniature); ?>" alt="<?php echo e($multimedia->titre); ?>" class="w-full h-full object-cover">
                            <?php elseif($multimedia->est_image && $multimedia->url_complete): ?>
                                <img src="<?php echo e($multimedia->url_complete); ?>" alt="<?php echo e($multimedia->titre); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <?php if($multimedia->type_media == 'video'): ?>
                                        <i class="fas fa-video text-2xl text-red-500"></i>
                                    <?php elseif($multimedia->type_media == 'audio'): ?>
                                        <i class="fas fa-music text-2xl text-purple-500"></i>
                                    <?php elseif($multimedia->type_media == 'document'): ?>
                                        <i class="fas fa-file-alt text-2xl text-blue-500"></i>
                                    <?php else: ?>
                                        <i class="fas fa-file text-2xl text-slate-400"></i>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informations du fichier -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-2"><?php echo e($multimedia->nom_fichier_original); ?></h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-slate-500">Type</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->type_media_label); ?></p>
                            </div>
                            <div>
                                <span class="text-slate-500">Taille</span>
                                <p class="font-medium text-slate-900"><?php echo e($multimedia->taille_formatee); ?></p>
                            </div>
                            <?php if($multimedia->dimensions_formatee): ?>
                                <div>
                                    <span class="text-slate-500">Dimensions</span>
                                    <p class="font-medium text-slate-900"><?php echo e($multimedia->dimensions_formatee); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if($multimedia->duree_formatee): ?>
                                <div>
                                    <span class="text-slate-500">Durée</span>
                                    <p class="font-medium text-slate-900"><?php echo e($multimedia->duree_formatee); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action de téléchargement -->
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('private.multimedia.download', $multimedia)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i> Télécharger
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <form action="<?php echo e(route('private.multimedia.update', $multimedia)); ?>" method="POST" id="editForm" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Section: Informations de base -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Informations de Base
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titre" id="titre" value="<?php echo e(old('titre', $multimedia->titre)); ?>" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Titre du média">
                        <?php $__errorArgs = ['titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="categorie" id="categorie" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('categorie', $multimedia->categorie) == $key ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Description détaillée du média"><?php echo e(old('description', $multimedia->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Légende -->
                <div>
                    <label for="legende" class="block text-sm font-medium text-slate-700 mb-2">Légende</label>
                    <textarea name="legende" id="legende" rows="2"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Légende courte pour l'affichage"><?php echo e(old('legende', $multimedia->legende)); ?></textarea>
                    <?php $__errorArgs = ['legende'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


            </div>
        </div>

        <!-- Section: Association à un événement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Association à un Événement
                    <span class="text-sm font-normal text-red-500 ml-2">(au moins un requis)</span>
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Culte -->
                    <div>
                        <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                        <select name="culte_id" id="culte_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucun culte sélectionné</option>
                            <?php $__currentLoopData = $cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($culte->id); ?>" <?php echo e(old('culte_id', $multimedia->culte_id) == $culte->id ? 'selected' : ''); ?>>
                                    <?php echo e($culte->titre); ?> - <?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['culte_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Événement -->
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-slate-700 mb-2">Événement</label>
                        <select name="event_id" id="event_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucun événement sélectionné</option>
                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($event->id); ?>" <?php echo e(old('event_id', $multimedia->event_id) == $event->id ? 'selected' : ''); ?>>
                                    <?php echo e($event->titre); ?> - <?php echo e(\Carbon\Carbon::parse($event->date_debut)->format('d/m/Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['event_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Intervention -->
                    <div>
                        <label for="intervention_id" class="block text-sm font-medium text-slate-700 mb-2">Intervention</label>
                        <select name="intervention_id" id="intervention_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucune intervention sélectionnée</option>
                            <?php $__currentLoopData = $interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($intervention->id); ?>" <?php echo e(old('intervention_id', $multimedia->intervention_id) == $intervention->id ? 'selected' : ''); ?>>
                                    <?php echo e($intervention->titre); ?>

                                    <?php if($intervention->culte): ?>
                                        (<?php echo e($intervention->culte->titre); ?>)
                                    <?php elseif($intervention->reunion): ?>
                                        (<?php echo e($intervention->reunion->titre); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['intervention_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Réunion -->
                    <div>
                        <label for="reunion_id" class="block text-sm font-medium text-slate-700 mb-2">Réunion</label>
                        <select name="reunion_id" id="reunion_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucune réunion sélectionnée</option>
                            <?php $__currentLoopData = $reunions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reunion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($reunion->id); ?>" <?php echo e(old('reunion_id', $multimedia->reunion_id) == $reunion->id ? 'selected' : ''); ?>>
                                    <?php echo e($reunion->titre); ?> - <?php echo e(\Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['reunion_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <?php $__errorArgs = ['evenement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Section: Métadonnées de capture -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-camera text-purple-600 mr-2"></i>
                    Métadonnées de Capture
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Date de prise -->
                    <div>
                        <label for="date_prise" class="block text-sm font-medium text-slate-700 mb-2">Date de prise</label>
                        <input type="datetime-local" name="date_prise" id="date_prise"
                               value="<?php echo e(old('date_prise', $multimedia->date_prise ? $multimedia->date_prise->format('Y-m-d\TH:i') : '')); ?>"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <?php $__errorArgs = ['date_prise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Lieu de prise -->
                    <div>
                        <label for="lieu_prise" class="block text-sm font-medium text-slate-700 mb-2">Lieu de prise</label>
                        <input type="text" name="lieu_prise" id="lieu_prise" value="<?php echo e(old('lieu_prise', $multimedia->lieu_prise)); ?>"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Lieu où le média a été capturé">
                        <?php $__errorArgs = ['lieu_prise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Photographe -->
                    <div>
                        <label for="photographe" class="block text-sm font-medium text-slate-700 mb-2">Photographe/Créateur</label>
                        <input type="text" name="photographe" id="photographe" value="<?php echo e(old('photographe', $multimedia->photographe)); ?>"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Nom du photographe ou créateur">
                        <?php $__errorArgs = ['photographe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Appareil -->
                    <div>
                        <label for="appareil" class="block text-sm font-medium text-slate-700 mb-2">Appareil utilisé</label>
                        <input type="text" name="appareil" id="appareil" value="<?php echo e(old('appareil', $multimedia->appareil)); ?>"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Modèle d'appareil photo/caméra">
                        <?php $__errorArgs = ['appareil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Qualité -->
                <div>
                    <label for="qualite" class="block text-sm font-medium text-slate-700 mb-2">Niveau de qualité</label>
                    <select name="qualite" id="qualite"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <?php $__currentLoopData = $qualites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('qualite', $multimedia->qualite) == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['qualite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Section: Permissions et accès -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>
                    Permissions et Accès
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Niveau d'accès -->
                <div>
                    <label for="niveau_acces" class="block text-sm font-medium text-slate-700 mb-2">
                        Niveau d'accès <span class="text-red-500">*</span>
                    </label>
                    <select name="niveau_acces" id="niveau_acces" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <?php $__currentLoopData = $niveaux_acces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('niveau_acces', $multimedia->niveau_acces) == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['niveau_acces'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Options d'usage -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="font-medium text-slate-900">Autorisations d'usage</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="hidden" name="usage_public" value="0">
                                <input type="checkbox" name="usage_public" value="1" <?php echo e(old('usage_public', $multimedia->usage_public) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Usage public autorisé</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_site_web" value="0">
                                <input type="checkbox" name="usage_site_web" value="1" <?php echo e(old('usage_site_web', $multimedia->usage_site_web) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Publication sur le site web</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_reseaux_sociaux" value="0">
                                <input type="checkbox" name="usage_reseaux_sociaux" value="1" <?php echo e(old('usage_reseaux_sociaux', $multimedia->usage_reseaux_sociaux) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Partage sur réseaux sociaux</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_commercial" value="0">
                                <input type="checkbox" name="usage_commercial" value="1" <?php echo e(old('usage_commercial', $multimedia->usage_commercial) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Usage commercial autorisé</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-medium text-slate-900">Options spéciales</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="hidden" name="contenu_sensible" value="0">
                                <input type="checkbox" name="contenu_sensible" value="1" <?php echo e(old('contenu_sensible', $multimedia->contenu_sensible) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">Contenu sensible</span>
                            </label>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('feature_media')): ?>
                                <label class="flex items-center">
                                    <input type="hidden" name="est_featured" value="0">
                                    <input type="checkbox" name="est_featured" value="1" <?php echo e(old('est_featured', $multimedia->est_featured) ? 'checked' : ''); ?>

                                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-sm text-slate-700">Mettre à la une</span>
                                </label>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
                                <label class="flex items-center">
                                    <input type="hidden" name="est_visible" value="0">
                                    <input type="checkbox" name="est_visible" value="1" <?php echo e(old('est_visible', $multimedia->est_visible) ? 'checked' : ''); ?>

                                           class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm text-slate-700">Visible</span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Restrictions d'usage -->
                <div>
                    <label for="restrictions_usage" class="block text-sm font-medium text-slate-700 mb-2">Restrictions d'usage spécifiques</label>
                    <textarea name="restrictions_usage" id="restrictions_usage" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Précisez les restrictions particulières d'usage de ce média"><?php echo e(old('restrictions_usage', $multimedia->restrictions_usage)); ?></textarea>
                    <?php $__errorArgs = ['restrictions_usage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Avertissement pour contenu sensible -->
                <div id="warningSection" class="<?php echo e($multimedia->contenu_sensible ? '' : 'hidden'); ?>">
                    <label for="avertissement" class="block text-sm font-medium text-slate-700 mb-2">Avertissement pour contenu sensible</label>
                    <textarea name="avertissement" id="avertissement" rows="2"
                              class="w-full px-4 py-3 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                              placeholder="Décrivez pourquoi ce contenu est sensible"><?php echo e(old('avertissement', $multimedia->avertissement)); ?></textarea>
                </div>
            </div>
        </div>

       

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
            <?php if($multimedia->statut_moderation != 'approuve'): ?>
                <!-- Section: Statut de modération (pour les modérateurs) -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-orange/20">
                    <div class="p-6 border-b border-orange-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-gavel text-orange-600 mr-2"></i>
                            Modération
                            <span class="ml-3 px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full">
                                <?php echo e($multimedia->statut_moderation_label); ?>

                            </span>
                        </h2>
                    </div>
                    <div class="p-6 bg-orange-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <?php if($multimedia->statut_moderation == 'rejete' && $multimedia->commentaire_moderation): ?>
                                    <div class="mb-4 p-4 bg-red-100 border border-red-200 rounded-lg">
                                        <h4 class="font-medium text-red-900 mb-2">Motif de rejet précédent :</h4>
                                        <p class="text-sm text-red-800"><?php echo e($multimedia->commentaire_moderation); ?></p>
                                        <?php if($multimedia->moderator): ?>
                                            <p class="text-xs text-red-700 mt-2">
                                                Par <?php echo e($multimedia->moderator->nom. ' '.$multimedia->moderator->prenom); ?> le <?php echo e($multimedia->modere_le->format('d/m/Y à H:i')); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <p class="text-orange-800 text-sm">
                                    Ce média nécessite une modération. Les modifications seront soumises à approbation.
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="quickApprove()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i> Approuver
                                </button>
                                <button type="button" onclick="quickReject()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i> Rejeter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4 pt-6">
            <a href="<?php echo e(route('private.multimedia.show', $multimedia)); ?>" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Annuler
            </a>
            <div class="flex items-center gap-4">
                <button type="submit" name="action" value="save" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>


<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contenuSensibleCheckbox = document.querySelector('input[name="contenu_sensible"]');
    const warningSection = document.getElementById('warningSection');





    // Gestion du contenu sensible
    contenuSensibleCheckbox.addEventListener('change', function() {
        if (this.checked) {
            warningSection.classList.remove('hidden');
        } else {
            warningSection.classList.add('hidden');
        }
    });

});

// Validation avant soumission
document.getElementById('editForm').addEventListener('submit', function(e) {
    // Vérifier qu'au moins un événement est sélectionné
    const culte = document.getElementById('culte_id').value;
    const event = document.getElementById('event_id').value;
    const intervention = document.getElementById('intervention_id').value;
    const reunion = document.getElementById('reunion_id').value;

    if (!culte && !event && !intervention && !reunion) {
        e.preventDefault();
        alert('Veuillez associer ce média à au moins un événement (culte, événement, intervention ou réunion).');
        return false;
    }

    // Vérifier le contenu sensible
    const contenuSensible = document.querySelector('input[name="contenu_sensible"]').checked;
    const avertissement = document.getElementById('avertissement').value.trim();

    if (contenuSensible && !avertissement) {
        e.preventDefault();
        alert('Veuillez fournir un avertissement pour le contenu sensible.');
        document.getElementById('avertissement').focus();
        return false;
    }

});

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moderate_media')): ?>
// Actions rapides de modération
function quickApprove() {
    if (confirm('Approuver ce média après sauvegarde ?')) {
        // Ajouter un champ caché pour indiquer l'approbation
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'quick_action';
        input.value = 'approve';
        document.getElementById('editForm').appendChild(input);

        document.getElementById('editForm').submit();
    }
}

function quickReject() {
    const reason = prompt('Motif de rejet :');
    if (reason && reason.trim()) {
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'quick_action';
        actionInput.value = 'reject';
        document.getElementById('editForm').appendChild(actionInput);

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reject_reason';
        reasonInput.value = reason.trim();
        document.getElementById('editForm').appendChild(reasonInput);

        document.getElementById('editForm').submit();
    }
}
<?php endif; ?>

// Auto-génération du titre SEO et alt text
document.getElementById('titre').addEventListener('input', function() {
    const titre = this.value;
    const titreSeo = document.getElementById('titre_seo');
    const altText = document.getElementById('alt_text');

    // Ne remplir automatiquement que si les champs sont vides
    if (!titreSeo.value.trim()) {
        titreSeo.value = titre;
    }

    if (!altText.value.trim()) {
        altText.value = titre;
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/multimedia/edit.blade.php ENDPATH**/ ?>