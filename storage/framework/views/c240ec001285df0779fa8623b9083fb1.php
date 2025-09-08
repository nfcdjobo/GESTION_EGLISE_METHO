<?php $__env->startSection('title', 'Modifier la Classe - ' . $classe->nom); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier la Classe</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.classes.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Classes
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="<?php echo e(route('private.classes.show', $classe)); ?>" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors"><?php echo e($classe->nom); ?></a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Modifier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="<?php echo e(route('private.classes.update', $classe)); ?>" method="POST" enctype="multipart/form-data" id="classeForm" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom de la classe <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" value="<?php echo e(old('nom', $classe->nom)); ?>" required maxlength="255" placeholder="Ex: École du Dimanche - Enfants"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-slate-500">Nom d'affichage de la classe (255 caractères max)</p>
                            </div>

                            <div>
                                <label for="tranche_age" class="block text-sm font-medium text-slate-700 mb-2">
                                    Tranche d'âge
                                </label>
                                <select id="tranche_age" name="tranche_age" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['tranche_age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner une tranche</option>
                                    <?php $__currentLoopData = $tranches_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tranche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tranche); ?>" <?php echo e(old('tranche_age', $classe->tranche_age) == $tranche ? 'selected' : ''); ?>><?php echo e($tranche); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['tranche_age'];
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

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description de la classe, objectifs et activités"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $classe->description)); ?></textarea>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge minimum
                                </label>
                                <input type="number" id="age_minimum" name="age_minimum" value="<?php echo e(old('age_minimum', $classe->age_minimum)); ?>" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['age_minimum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['age_minimum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-slate-500">Âge minimum requis (optionnel)</p>
                            </div>

                            <div>
                                <label for="age_maximum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge maximum
                                </label>
                                <input type="number" id="age_maximum" name="age_maximum" value="<?php echo e(old('age_maximum', $classe->age_maximum)); ?>" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['age_maximum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['age_maximum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-slate-500">Âge maximum autorisé (optionnel)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-green-600 mr-2"></i>
                            Responsables
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Responsable de classe
                                </label>
                                <select id="responsable_id" name="responsable_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['responsable_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucun responsable</option>
                                    <?php $__currentLoopData = $utilisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utilisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($utilisateur->id); ?>" <?php echo e(old('responsable_id', $classe->responsable_id) == $utilisateur->id ? 'selected' : ''); ?>>
                                            <?php echo e($utilisateur->nom_complet); ?> (<?php echo e($utilisateur->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['responsable_id'];
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

                            <div>
                                <label for="enseignant_principal_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Enseignant principal
                                </label>
                                <select id="enseignant_principal_id" name="enseignant_principal_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['enseignant_principal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucun enseignant</option>
                                    <?php $__currentLoopData = $utilisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utilisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($utilisateur->id); ?>" <?php echo e(old('enseignant_principal_id', $classe->enseignant_principal_id) == $utilisateur->id ? 'selected' : ''); ?>>
                                            <?php echo e($utilisateur->nom_complet); ?> (<?php echo e($utilisateur->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['enseignant_principal_id'];
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

                        <!-- Alerte si changement de responsable/enseignant avec membres -->
                        <?php if($classe->membres->count() > 0): ?>
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-amber-800">Attention</h4>
                                        <p class="text-sm text-amber-700 mt-1">
                                            Cette classe a <?php echo e($classe->membres->count()); ?> membre(s) inscrit(s).
                                            Le changement de responsable ou d'enseignant sera notifié à tous les membres.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Image et médias -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-image text-purple-600 mr-2"></i>
                            Image et Médias
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Image actuelle -->
                        <?php if($classe->image_classe): ?>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Image actuelle</label>
                                <div class="relative inline-block">
                                    <img src="<?php echo e(asset('storage/' . $classe->image_classe)); ?>" alt="<?php echo e($classe->nom); ?>" class="w-32 h-32 object-cover rounded-xl border border-slate-300">
                                    <button type="button" onclick="removeCurrentImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="remove_image" name="remove_image" value="0">
                            </div>
                        <?php endif; ?>

                        <div>
                            <label for="image_classe" class="block text-sm font-medium text-slate-700 mb-2">
                                <?php echo e($classe->image_classe ? 'Changer l\'image' : 'Image de la classe'); ?>

                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600">
                                        <label for="image_classe" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Télécharger un fichier</span>
                                            <input id="image_classe" name="image_classe" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                </div>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" src="" alt="Aperçu" class="max-w-full h-32 object-cover rounded-lg mx-auto">
                            </div>
                            <?php $__errorArgs = ['image_classe'];
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
            </div>

            <!-- Aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Nom:</span>
                            <span id="preview-nom" class="text-sm text-slate-900 font-semibold"><?php echo e($classe->nom); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Tranche d'âge:</span>
                            <span id="preview-tranche" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><?php echo e($classe->tranche_age ?: '-'); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Âges:</span>
                            <span id="preview-ages" class="text-sm text-slate-600">
                                <?php if($classe->age_minimum && $classe->age_maximum): ?>
                                    <?php echo e($classe->age_minimum); ?>-<?php echo e($classe->age_maximum); ?> ans
                                <?php elseif($classe->age_minimum): ?>
                                    <?php echo e($classe->age_minimum); ?>+ ans
                                <?php elseif($classe->age_maximum): ?>
                                    Jusqu'à <?php echo e($classe->age_maximum); ?> ans
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Responsable:</span>
                            <span id="preview-responsable" class="text-sm text-slate-600"><?php echo e($classe->responsable ? $classe->responsable->nom_complet : '-'); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Enseignant:</span>
                            <span id="preview-enseignant" class="text-sm text-slate-600"><?php echo e($classe->enseignantPrincipal ? $classe->enseignantPrincipal->nom_complet : '-'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>
                            Statistiques Actuelles
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($classe->nombre_inscrits); ?></div>
                            <div class="text-sm text-slate-500">Membres inscrits</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($classe->places_disponibles); ?></div>
                            <div class="text-sm text-slate-500">Places disponibles</div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-medium text-slate-700 mb-2">
                                <span>Taux de remplissage</span>
                                <span><?php echo e($classe->pourcentage_remplissage); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: <?php echo e(min($classe->pourcentage_remplissage, 100)); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique des modifications -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-amber-600 mr-2"></i>
                            Historique
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Créé le:</span>
                            <span class="font-medium"><?php echo e($classe->created_at->format('d/m/Y à H:i')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Dernière modification:</span>
                            <span class="font-medium"><?php echo e($classe->updated_at->format('d/m/Y à H:i')); ?></span>
                        </div>
                        <?php if($classe->updated_at != $classe->created_at): ?>
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <p class="text-blue-700 text-xs">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Cette classe a été modifiée <?php echo e($classe->updated_at->diffForHumans()); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programme de la classe -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-book text-amber-600 mr-2"></i>
                    Programme de la Classe
                </h2>
                <p class="text-slate-500 mt-1">Modifiez le programme et les objectifs de la classe</p>
            </div>
            <div class="p-6">
                <div id="programme-container">
                    <?php if($classe->programme && is_array($classe->programme) && count($classe->programme) > 0): ?>
                        <?php $__currentLoopData = $classe->programme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lecon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="programme-item border border-slate-200 rounded-xl p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Titre de la leçon</label>
                                        <input type="text" name="programme[<?php echo e($index); ?>][titre]" value="<?php echo e($lecon['titre'] ?? ''); ?>" placeholder="Ex: L'amour de Dieu" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Durée (minutes)</label>
                                        <input type="number" name="programme[<?php echo e($index); ?>][duree]" value="<?php echo e($lecon['duree'] ?? ''); ?>" placeholder="45" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeProgrammeItem(this)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash mr-2"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                                    <textarea name="programme[<?php echo e($index); ?>][description]" rows="2" placeholder="Description détaillée de la leçon..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"><?php echo e($lecon['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="programme-item border border-slate-200 rounded-xl p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Titre de la leçon</label>
                                    <input type="text" name="programme[0][titre]" placeholder="Ex: L'amour de Dieu" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Durée (minutes)</label>
                                    <input type="number" name="programme[0][duree]" placeholder="45" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeProgrammeItem(this)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-2"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                                <textarea name="programme[0][description]" rows="2" placeholder="Description détaillée de la leçon..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" onclick="addProgrammeItem()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Ajouter une leçon
                </button>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les Modifications
                    </button>
                    <a href="<?php echo e(route('private.classes.show', $classe)); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir la Classe
                    </a>
                    <a href="<?php echo e(route('private.classes.index')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-400 text-white font-medium rounded-xl hover:bg-slate-500 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let programmeItemCount = <?php echo e($classe->programme && is_array($classe->programme) ? count($classe->programme) : 1); ?>;

// Mise à jour de l'aperçu
function updatePreview() {
    const nom = document.getElementById('nom').value || '<?php echo e($classe->nom); ?>';
    const tranche = document.getElementById('tranche_age').value || '-';
    const ageMin = document.getElementById('age_minimum').value;
    const ageMax = document.getElementById('age_maximum').value;
    const responsableSelect = document.getElementById('responsable_id');
    const enseignantSelect = document.getElementById('enseignant_principal_id');

    document.getElementById('preview-nom').textContent = nom;

    if (tranche !== '-') {
        document.getElementById('preview-tranche').textContent = tranche;
        document.getElementById('preview-tranche').className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    } else {
        document.getElementById('preview-tranche').textContent = '-';
        document.getElementById('preview-tranche').className = 'text-sm text-slate-600';
    }

    // Gestion des âges
    let agesText = '-';
    if (ageMin && ageMax) {
        agesText = `${ageMin}-${ageMax} ans`;
    } else if (ageMin) {
        agesText = `${ageMin}+ ans`;
    } else if (ageMax) {
        agesText = `Jusqu'à ${ageMax} ans`;
    }
    document.getElementById('preview-ages').textContent = agesText;

    // Responsable
    const responsableText = responsableSelect.selectedIndex > 0 ?
        responsableSelect.options[responsableSelect.selectedIndex].text.split(' (')[0] : '-';
    document.getElementById('preview-responsable').textContent = responsableText;

    // Enseignant
    const enseignantText = enseignantSelect.selectedIndex > 0 ?
        enseignantSelect.options[enseignantSelect.selectedIndex].text.split(' (')[0] : '-';
    document.getElementById('preview-enseignant').textContent = enseignantText;
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('nom').addEventListener('input', updatePreview);
document.getElementById('tranche_age').addEventListener('change', updatePreview);
document.getElementById('age_minimum').addEventListener('input', updatePreview);
document.getElementById('age_maximum').addEventListener('input', updatePreview);
document.getElementById('responsable_id').addEventListener('change', updatePreview);
document.getElementById('enseignant_principal_id').addEventListener('change', updatePreview);

// Aperçu de l'image
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

// Supprimer l'image actuelle
function removeCurrentImage() {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'image actuelle ?')) {
        document.getElementById('remove_image').value = '1';
        document.querySelector('.relative.inline-block').style.display = 'none';
    }
}

// Gestion du programme
function addProgrammeItem() {
    const container = document.getElementById('programme-container');
    const itemHtml = `
        <div class="programme-item border border-slate-200 rounded-xl p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Titre de la leçon</label>
                    <input type="text" name="programme[${programmeItemCount}][titre]" placeholder="Ex: L'amour de Dieu" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Durée (minutes)</label>
                    <input type="number" name="programme[${programmeItemCount}][duree]" placeholder="45" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeProgrammeItem(this)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea name="programme[${programmeItemCount}][description]" rows="2" placeholder="Description détaillée de la leçon..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    programmeItemCount++;
}

function removeProgrammeItem(button) {
    const item = button.closest('.programme-item');
    const container = document.getElementById('programme-container');

    if (container.children.length > 1) {
        item.remove();
    } else {
        alert('Au moins une leçon doit être définie dans le programme');
    }
}

// Validation du formulaire
document.getElementById('classeForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom').value.trim();
    const ageMin = document.getElementById('age_minimum').value;
    const ageMax = document.getElementById('age_maximum').value;

    if (!nom) {
        e.preventDefault();
        alert('Veuillez saisir le nom de la classe.');
        return false;
    }

    if (ageMin && ageMax && parseInt(ageMin) > parseInt(ageMax)) {
        e.preventDefault();
        alert('L\'âge minimum ne peut pas être supérieur à l\'âge maximum.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/edit.blade.php ENDPATH**/ ?>