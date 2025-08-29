<?php $__env->startSection('title', 'Télécharger un Média'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Télécharger un Média</h1>
                    <p class="text-slate-500 mt-1">Ajoutez un nouveau média à votre médiathèque</p>
                </div>
                <a href="<?php echo e(route('private.multimedia.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la galerie
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire d'upload -->
    <form action="<?php echo e(route('private.multimedia.store')); ?>" method="POST" enctype="multipart/form-data" id="uploadForm" class="space-y-8">
        <?php echo csrf_field(); ?>

        <!-- Section: Fichier et informations de base -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-upload text-blue-600 mr-2"></i>
                    Fichier et Informations de Base
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Upload de fichier -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Fichier <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-blue-400 transition-colors" id="dropZone">
                        <div class="space-y-1 text-center">
                            <div class="mx-auto h-12 w-12 text-slate-400">
                                <i class="fas fa-cloud-upload-alt text-4xl"></i>
                            </div>
                            <div class="flex text-sm text-slate-600">
                                <label for="fichier" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Téléchargez un fichier</span>
                                    <input id="fichier" name="fichier" type="file" class="sr-only" required accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.ppt,.pptx">
                                </label>
                                <p class="pl-1">ou glissez-déposez</p>
                            </div>
                            <p class="text-xs text-slate-500">
                                Images, Vidéos, Audios, Documents jusqu'à 2GB
                            </p>
                        </div>
                    </div>
                    <?php $__errorArgs = ['fichier'];
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

                <!-- Aperçu du fichier -->
                <div id="filePreview" class="hidden">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-center space-x-4">
                            <div id="previewIcon" class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-2xl text-slate-400"></i>
                            </div>
                            <div class="flex-1">
                                <p id="fileName" class="font-medium text-slate-900"></p>
                                <p id="fileSize" class="text-sm text-slate-500"></p>
                                <p id="fileType" class="text-sm text-slate-500"></p>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-600 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titre" id="titre" value="<?php echo e(old('titre')); ?>" required
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
                        <select name="categorie" id="categorie" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('categorie') == $key ? 'selected' : ''); ?>>
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
                    <div class="<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <textarea name="description" id="description" rows="5" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Description détaillée du média"><?php echo e(old('description')); ?></textarea>
                    </div>
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
                    <div class="<?php $__errorArgs = ['legende'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <textarea name="legende" id="legende" rows="2" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Légende courte pour l'affichage"><?php echo e(old('legende')); ?></textarea>
                    </div>
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
                                <option value="<?php echo e($culte->id); ?>" <?php echo e(old('culte_id') == $culte->id ? 'selected' : ''); ?>>
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
                                <option value="<?php echo e($event->id); ?>" <?php echo e(old('event_id') == $event->id ? 'selected' : ''); ?>>
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
                                <option value="<?php echo e($intervention->id); ?>" <?php echo e(old('intervention_id') == $intervention->id ? 'selected' : ''); ?>>
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
                                <option value="<?php echo e($reunion->id); ?>" <?php echo e(old('reunion_id') == $reunion->id ? 'selected' : ''); ?>>
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
                        <input type="datetime-local" name="date_prise" id="date_prise" value="<?php echo e(old('date_prise')); ?>"
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
                        <input type="text" name="lieu_prise" id="lieu_prise" value="<?php echo e(old('lieu_prise')); ?>"
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
                        <input type="text" name="photographe" id="photographe" value="<?php echo e(old('photographe')); ?>"
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
                        <input type="text" name="appareil" id="appareil" value="<?php echo e(old('appareil')); ?>"
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
                            <option value="<?php echo e($key); ?>" <?php echo e(old('qualite', 'standard') == $key ? 'selected' : ''); ?>>
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
                            <option value="<?php echo e($key); ?>" <?php echo e(old('niveau_acces', 'public') == $key ? 'selected' : ''); ?>>
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
                                <input type="checkbox" name="usage_public" value="1" <?php echo e(old('usage_public', true) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Usage public autorisé</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_site_web" value="0">
                                <input type="checkbox" name="usage_site_web" value="1" <?php echo e(old('usage_site_web', true) ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Publication sur le site web</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_reseaux_sociaux" value="0">
                                <input type="checkbox" name="usage_reseaux_sociaux" value="1" <?php echo e(old('usage_reseaux_sociaux') ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Partage sur réseaux sociaux</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_commercial" value="0">
                                <input type="checkbox" name="usage_commercial" value="1" <?php echo e(old('usage_commercial') ? 'checked' : ''); ?>

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
                                <input type="checkbox" name="contenu_sensible" value="1" <?php echo e(old('contenu_sensible') ? 'checked' : ''); ?>

                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">Contenu sensible</span>
                            </label>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('feature_media')): ?>
                                <label class="flex items-center">
                                    <input type="hidden" name="est_featured" value="0">
                                    <input type="checkbox" name="est_featured" value="1" <?php echo e(old('est_featured') ? 'checked' : ''); ?>

                                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-sm text-slate-700">Mettre à la une</span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Restrictions d'usage -->
                <div>
                    <label for="restrictions_usage" class="block text-sm font-medium text-slate-700 mb-2">Restrictions d'usage spécifiques</label>
                    <div class="<?php $__errorArgs = ['restrictions_usage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <textarea name="restrictions_usage" id="restrictions_usage" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Précisez les restrictions particulières d'usage de ce média"><?php echo e(old('restrictions_usage')); ?></textarea>
                    </div>
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
                <div id="warningSection" class="hidden">
                    <label for="avertissement" class="block text-sm font-medium text-slate-700 mb-2">Avertissement pour contenu sensible</label>
                    <div class="<?php $__errorArgs = ['avertissement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <textarea name="avertissement" id="avertissement" rows="2"
                              class="w-full px-4 py-3 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                              placeholder="Décrivez pourquoi ce contenu est sensible"><?php echo e(old('avertissement')); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

       

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4 pt-6">
            <a href="<?php echo e(route('private.multimedia.index')); ?>" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Annuler
            </a>
            <div class="flex items-center gap-4">
                <button type="submit" name="action" value="draft" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-medium rounded-xl hover:bg-orange-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Enregistrer en brouillon
                </button>
                <button type="submit" name="action" value="publish" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-upload mr-2"></i> Télécharger et publier
                </button>
            </div>
        </div>
    </form>
</div>


<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
// Gestion de l'upload de fichier
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fichier');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');

    const contenuSensibleCheckbox = document.querySelector('input[name="contenu_sensible"]');
    const warningSection = document.getElementById('warningSection');

    // Gestion du drag & drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // Validation de taille
        const maxSize = 2 * 1024 * 1024 * 1024; // 2GB
        if (file.size > maxSize) {
            alert('Le fichier est trop volumineux. Taille maximum: 2GB');
            clearFile();
            return;
        }

        // Mise à jour de l'aperçu
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('fileType').textContent = file.type || 'Type inconnu';

        // Icône selon le type
        const icon = document.getElementById('previewIcon').querySelector('i');
        if (file.type.startsWith('image/')) {
            icon.className = 'fas fa-image text-2xl text-green-500';
        } else if (file.type.startsWith('video/')) {
            icon.className = 'fas fa-video text-2xl text-red-500';
        } else if (file.type.startsWith('audio/')) {
            icon.className = 'fas fa-music text-2xl text-purple-500';
        } else {
            icon.className = 'fas fa-file text-2xl text-blue-500';
        }

        // Auto-remplissage du titre si vide
        const titreInput = document.getElementById('titre');
        if (!titreInput.value) {
            const baseName = file.name.replace(/\.[^/.]+$/, "");
            titreInput.value = baseName.replace(/[_-]/g, ' ');
        }

        filePreview.classList.remove('hidden');
        dropZone.style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }





    // Gestion du contenu sensible
    contenuSensibleCheckbox.addEventListener('change', function() {
        if (this.checked) {
            warningSection.classList.remove('hidden');
        } else {
            warningSection.classList.add('hidden');
        }
    });
});

function clearFile() {
    document.getElementById('fichier').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('dropZone').style.display = 'block';
}

// Validation avant soumission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
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

// Auto-génération du titre SEO et alt text
document.getElementById('titre').addEventListener('input', function() {
    const titre = this.value;
    const titreSeo = document.getElementById('titre_seo');
    const altText = document.getElementById('alt_text');

    if (!titreSeo.value) {
        titreSeo.value = titre;
    }

    if (!altText.value) {
        altText.value = titre;
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/multimedia/create.blade.php ENDPATH**/ ?>