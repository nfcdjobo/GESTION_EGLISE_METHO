<?php $__env->startSection('title', 'Modifier ' . $typeReunion->nom); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier <?php echo e($typeReunion->nom); ?></h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.types-reunions.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Types de Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="<?php echo e(route('private.types-reunions.show', $typeReunion)); ?>" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors"><?php echo e($typeReunion->code); ?></a>
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

    <form action="<?php echo e(route('private.types-reunions.update', $typeReunion)); ?>" method="POST" id="typeReunionForm" enctype="multipart/form-data" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col p-6 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <a href="<?php echo e(route('private.types-reunions.show', $typeReunion)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i> Voir
                            </a>
                            <button type="button" onclick="duplicateType('<?php echo e($typeReunion->id); ?>')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-copy mr-2"></i> Dupliquer
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du type <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" value="<?php echo e(old('nom', $typeReunion->nom)); ?>" required maxlength="150"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Ex: Culte dominical">
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
                            </div>

                            <div>
                                <label for="code" class="block text-sm font-medium text-slate-700 mb-2">
                                    Code unique <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="code" name="code" value="<?php echo e(old('code', $typeReunion->code)); ?>" required maxlength="50"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Ex: culte-dominical">
                                <?php $__errorArgs = ['code'];
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
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Description détaillée du type de réunion..."><?php echo e(old('description', $typeReunion->description)); ?></textarea>
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
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('categorie', $typeReunion->categorie) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
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

                            <div>
                                <label for="niveau_acces" class="block text-sm font-medium text-slate-700 mb-2">
                                    Niveau d'accès <span class="text-red-500">*</span>
                                </label>
                                <select id="niveau_acces" name="niveau_acces" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['niveau_acces'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le niveau</option>
                                    <?php $__currentLoopData = $niveauxAcces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('niveau_acces', $typeReunion->niveau_acces) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
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
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="icone" class="block text-sm font-medium text-slate-700 mb-2">Icône FontAwesome</label>
                                <input type="text" id="icone" name="icone" value="<?php echo e(old('icone', $typeReunion->icone)); ?>" maxlength="100"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['icone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="calendar-alt">
                                <p class="text-xs text-slate-500 mt-1">Nom de l'icône sans le préfixe "fa-"</p>
                                <?php $__errorArgs = ['icone'];
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
                                <label for="couleur" class="block text-sm font-medium text-slate-700 mb-2">Couleur</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" id="couleur" name="couleur" value="<?php echo e(old('couleur', $typeReunion->couleur ?? '#3498db')); ?>"
                                        class="w-12 h-12 border border-slate-300 rounded-xl <?php $__errorArgs = ['couleur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="text" id="couleur_text" value="<?php echo e(old('couleur', $typeReunion->couleur ?? '#3498db')); ?>"
                                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="#3498db">
                                </div>
                                <?php $__errorArgs = ['couleur'];
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
                                <label for="priorite" class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                <select id="priorite" name="priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <?php for($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(old('priorite', $typeReunion->priorite) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                                <p class="text-xs text-slate-500 mt-1">1 = Priorité maximale, 10 = Priorité minimale</p>
                            </div>
                        </div>

                        <!-- Alerte si le type est utilisé -->
                        <?php if($typeReunion->nombre_utilisations > 0): ?>
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-amber-600 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-amber-800">Type de réunion utilisé</h4>
                                        <p class="text-sm text-amber-700 mt-1">Ce type a été utilisé <?php echo e($typeReunion->nombre_utilisations); ?> fois. Les modifications importantes peuvent affecter les réunions existantes.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Configuration temporelle -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-clock text-green-600 mr-2"></i>
                            Configuration Temporelle
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="frequence_type" class="block text-sm font-medium text-slate-700 mb-2">
                                Fréquence type <span class="text-red-500">*</span>
                            </label>
                            <select id="frequence_type" name="frequence_type" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['frequence_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Sélectionner la fréquence</option>
                                <?php $__currentLoopData = $frequences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('frequence_type', $typeReunion->frequence_type) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['frequence_type'];
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="duree_standard" class="block text-sm font-medium text-slate-700 mb-2">Durée standard</label>
                                <input type="time" id="duree_standard" name="duree_standard" value="<?php echo e(old('duree_standard', $typeReunion->duree_standard?->format('H:i'))); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['duree_standard'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['duree_standard'];
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
                                <label for="duree_min" class="block text-sm font-medium text-slate-700 mb-2">Durée minimale</label>
                                <input type="time" id="duree_min" name="duree_min" value="<?php echo e(old('duree_min', $typeReunion->duree_min?->format('H:i'))); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['duree_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['duree_min'];
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
                                <label for="duree_max" class="block text-sm font-medium text-slate-700 mb-2">Durée maximale</label>
                                <input type="time" id="duree_max" name="duree_max" value="<?php echo e(old('duree_max', $typeReunion->duree_max?->format('H:i'))); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['duree_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['duree_max'];
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

                <!-- Paramètres de configuration -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-purple-600 mr-2"></i>
                            Paramètres de Configuration
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-slate-700 mb-3">Organisation</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="necessite_preparation" value="1" <?php echo e(old('necessite_preparation', $typeReunion->necessite_preparation) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Nécessite une préparation spéciale</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="necessite_inscription" value="1" <?php echo e(old('necessite_inscription', $typeReunion->necessite_inscription) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inscription obligatoire</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="a_limite_participants" value="1" <?php echo e(old('a_limite_participants', $typeReunion->a_limite_participants) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_limite">
                                        <span class="ml-2 text-sm text-slate-700">Nombre de participants limité</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permet_enfants" value="1" <?php echo e(old('permet_enfants', $typeReunion->permet_enfants) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_enfants">
                                        <span class="ml-2 text-sm text-slate-700">Enfants autorisés</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-slate-700 mb-3">Contenu spirituel</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_louange" value="1" <?php echo e(old('inclut_louange', $typeReunion->inclut_louange) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un temps de louange</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_message" value="1" <?php echo e(old('inclut_message', $typeReunion->inclut_message) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un message/enseignement</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_priere" value="1" <?php echo e(old('inclut_priere', $typeReunion->inclut_priere) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un temps de prière</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_communion" value="1" <?php echo e(old('inclut_communion', $typeReunion->inclut_communion) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Peut inclure la communion</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permet_temoignages" value="1" <?php echo e(old('permet_temoignages', $typeReunion->permet_temoignages) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Permet les témoignages</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="section_limite_participants" class="<?php echo e($typeReunion->a_limite_participants ? '' : 'hidden'); ?>">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="limite_participants" class="block text-sm font-medium text-slate-700 mb-2">Limite de participants</label>
                                    <input type="number" id="limite_participants" name="limite_participants" value="<?php echo e(old('limite_participants', $typeReunion->limite_participants)); ?>" min="1"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <div id="section_age_enfants" class="<?php echo e($typeReunion->permet_enfants ? '' : 'hidden'); ?>">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">Âge minimum</label>
                                <input type="number" id="age_minimum" name="age_minimum" value="<?php echo e(old('age_minimum', $typeReunion->age_minimum)); ?>" min="0" max="99"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="text-xs text-slate-500 mt-1">Laissez vide si aucun âge minimum requis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion financière -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-money-bill text-amber-600 mr-2"></i>
                            Gestion Financière
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="collecte_offrandes" value="1" <?php echo e(old('collecte_offrandes', $typeReunion->collecte_offrandes) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Collecte d'offrandes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="a_frais_participation" value="1" <?php echo e(old('a_frais_participation', $typeReunion->a_frais_participation) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_frais">
                                    <span class="ml-2 text-sm text-slate-700">Frais de participation</span>
                                </label>
                            </div>

                            <div id="section_frais_participation" class="<?php echo e($typeReunion->a_frais_participation ? '' : 'hidden'); ?>">
                                <div>
                                    <label for="frais_standard" class="block text-sm font-medium text-slate-700 mb-2">Frais standard (XOF)</label>
                                    <input type="number" id="frais_standard" name="frais_standard" value="<?php echo e(old('frais_standard', $typeReunion->frais_standard)); ?>" min="0" step="0.01"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options d'affichage -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-cyan-600 mr-2"></i>
                            Options d'Affichage
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="afficher_calendrier_public" value="1" <?php echo e(old('afficher_calendrier_public', $typeReunion->afficher_calendrier_public) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Afficher sur le calendrier public</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="afficher_site_web" value="1" <?php echo e(old('afficher_site_web', $typeReunion->afficher_site_web) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Afficher sur le site web</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="actif" value="1" <?php echo e(old('actif', $typeReunion->actif) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Type de réunion actif</span>
                                </label>
                            </div>

                            <div>
                                <label for="ordre_affichage" class="block text-sm font-medium text-slate-700 mb-2">Ordre d'affichage</label>
                                <input type="number" id="ordre_affichage" name="ordre_affichage" value="<?php echo e(old('ordre_affichage', $typeReunion->ordre_affichage)); ?>" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="text-xs text-slate-500 mt-1">0 = Première position</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Aperçu et informations -->
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
                        <div class="text-center mb-4">
                            <div id="preview-icon" class="w-16 h-16 rounded-xl flex items-center justify-center text-white shadow-lg mx-auto mb-2" style="background-color: <?php echo e($typeReunion->couleur ?? '#3498db'); ?>">
                                <i class="fas fa-<?php echo e($typeReunion->icone ?? 'calendar-alt'); ?> text-2xl"></i>
                            </div>
                            <h3 id="preview-name" class="font-semibold text-slate-800"><?php echo e($typeReunion->nom); ?></h3>
                            <p id="preview-code" class="text-sm text-slate-500"><?php echo e($typeReunion->code); ?></p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Catégorie:</span>
                                <span id="preview-categorie" class="text-slate-600"><?php echo e(ucfirst($typeReunion->categorie)); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Accès:</span>
                                <span id="preview-acces" class="text-slate-600"><?php echo e(ucfirst(str_replace('_', ' ', $typeReunion->niveau_acces))); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Fréquence:</span>
                                <span id="preview-frequence" class="text-slate-600"><?php echo e(ucfirst(str_replace('_', ' ', $typeReunion->frequence_type))); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Durée:</span>
                                <span id="preview-duree" class="text-slate-600"><?php echo e($typeReunion->duree_standard?->format('H:i') ?? '-'); ?></span>
                            </div>
                        </div>

                        <div id="preview-badges" class="flex flex-wrap gap-1 mt-4">
                            <!-- Badges dynamiques -->
                        </div>
                    </div>
                </div>

                <!-- Responsable -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-tie text-green-600 mr-2"></i>
                            Responsable
                        </h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="responsable_type_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable par défaut</label>
                            <select id="responsable_type_id" name="responsable_type_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Aucun responsable défini</option>
                                <!-- Les options seront ajoutées dynamiquement -->
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Responsable par défaut pour ce type de réunion</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques actuelles -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                            Statistiques Actuelles
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Utilisations:</span>
                            <span class="font-medium text-slate-800"><?php echo e($typeReunion->nombre_utilisations); ?></span>
                        </div>
                        <?php if($typeReunion->derniere_utilisation): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Dernière utilisation:</span>
                                <span class="font-medium text-slate-800"><?php echo e($typeReunion->derniere_utilisation->format('d/m/Y')); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Créé le:</span>
                            <span class="font-medium text-slate-800"><?php echo e($typeReunion->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <?php if($typeReunion->updated_at->gt($typeReunion->created_at)): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Modifié le:</span>
                                <span class="font-medium text-slate-800"><?php echo e($typeReunion->updated_at->format('d/m/Y')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les Modifications
                    </button>
                    <a href="<?php echo e(route('private.types-reunions.show', $typeReunion)); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Gestion de l'aperçu en temps réel
function updatePreview() {
    const nom = document.getElementById('nom').value || '<?php echo e($typeReunion->nom); ?>';
    const code = document.getElementById('code').value || '<?php echo e($typeReunion->code); ?>';
    const icone = document.getElementById('icone').value || '<?php echo e($typeReunion->icone ?? 'calendar-alt'); ?>';
    const couleur = document.getElementById('couleur').value || '<?php echo e($typeReunion->couleur ?? '#3498db'); ?>';
    const categorieSelect = document.getElementById('categorie');
    const accesSelect = document.getElementById('niveau_acces');
    const frequenceSelect = document.getElementById('frequence_type');
    const dureeStandard = document.getElementById('duree_standard').value;

    // Mise à jour des éléments
    document.getElementById('preview-name').textContent = nom;
    document.getElementById('preview-code').textContent = code;
    document.getElementById('preview-icon').style.backgroundColor = couleur;
    document.getElementById('preview-icon').innerHTML = `<i class="fas fa-${icone} text-2xl"></i>`;

    // Mise à jour des informations
    document.getElementById('preview-categorie').textContent = categorieSelect.options[categorieSelect.selectedIndex]?.text || '<?php echo e(ucfirst($typeReunion->categorie)); ?>';
    document.getElementById('preview-acces').textContent = accesSelect.options[accesSelect.selectedIndex]?.text || '<?php echo e(ucfirst(str_replace("_", " ", $typeReunion->niveau_acces))); ?>';
    document.getElementById('preview-frequence').textContent = frequenceSelect.options[frequenceSelect.selectedIndex]?.text || '<?php echo e(ucfirst(str_replace("_", " ", $typeReunion->frequence_type))); ?>';
    document.getElementById('preview-duree').textContent = dureeStandard || '<?php echo e($typeReunion->duree_standard?->format("H:i") ?? "-"); ?>';

    // Mise à jour des badges
    updatePreviewBadges();
}

function updatePreviewBadges() {
    const badgesContainer = document.getElementById('preview-badges');
    badgesContainer.innerHTML = '';

    const badges = [
        { checkbox: 'necessite_inscription', text: 'Inscription', color: 'orange' },
        { checkbox: 'inclut_louange', text: 'Louange', color: 'purple' },
        { checkbox: 'inclut_message', text: 'Message', color: 'blue' },
        { checkbox: 'permet_enfants', text: 'Enfants', color: 'green' },
        { checkbox: 'collecte_offrandes', text: 'Offrandes', color: 'yellow' },
    ];

    badges.forEach(badge => {
        const checkbox = document.getElementById(badge.checkbox) || document.querySelector(`input[name="${badge.checkbox}"]`);
        if (checkbox && checkbox.checked) {
            const badgeElement = document.createElement('span');
            badgeElement.className = `inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-${badge.color}-100 text-${badge.color}-800`;
            badgeElement.textContent = badge.text;
            badgesContainer.appendChild(badgeElement);
        }
    });
}

// Synchronisation des champs couleur
function syncColorInputs() {
    const colorPicker = document.getElementById('couleur');
    const colorText = document.getElementById('couleur_text');

    colorPicker.addEventListener('change', function() {
        colorText.value = this.value;
        updatePreview();
    });

    colorText.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
            updatePreview();
        }
    });
}

// Gestion des sections conditionnelles
function setupConditionalSections() {
    // Limite de participants
    const limitCheckbox = document.getElementById('checkbox_limite');
    const limitSection = document.getElementById('section_limite_participants');

    if (limitCheckbox && limitSection) {
        limitCheckbox.addEventListener('change', function() {
            limitSection.classList.toggle('hidden', !this.checked);
            document.getElementById('limite_participants').required = this.checked;
        });
    }

    // Âge minimum pour enfants
    const enfantsCheckbox = document.getElementById('checkbox_enfants');
    const ageSection = document.getElementById('section_age_enfants');

    if (enfantsCheckbox && ageSection) {
        enfantsCheckbox.addEventListener('change', function() {
            ageSection.classList.toggle('hidden', !this.checked);
        });
    }

    // Frais de participation
    const fraisCheckbox = document.getElementById('checkbox_frais');
    const fraisSection = document.getElementById('section_frais_participation');

    if (fraisCheckbox && fraisSection) {
        fraisCheckbox.addEventListener('change', function() {
            fraisSection.classList.toggle('hidden', !this.checked);
            document.getElementById('frais_standard').required = this.checked;
        });
    }
}

// Dupliquer un type
function duplicateType(typeId) {
    if (confirm('Dupliquer ce type de réunion ?')) {
        fetch(`<?php echo e(route('private.types-reunions.dupliquer', ':type')); ?>`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data && data.data.id) {
                    window.location.href = `<?php echo e(route('private.types-reunions.show', ':id')); ?>`.replace(':id', data.data.id);
                } else {
                    location.reload();
                }
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

// Validation du formulaire
function setupFormValidation() {
    const form = document.getElementById('typeReunionForm');

    form.addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const code = document.getElementById('code').value.trim();
        const categorie = document.getElementById('categorie').value;
        const niveauAcces = document.getElementById('niveau_acces').value;
        const frequence = document.getElementById('frequence_type').value;

        if (!nom || !code || !categorie || !niveauAcces || !frequence) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }

        // Validation du code
        if (!/^[a-z0-9\-]+$/.test(code)) {
            e.preventDefault();
            alert('Le code ne peut contenir que des lettres minuscules, des chiffres et des tirets.');
            return false;
        }

        // Validation de la couleur
        const couleur = document.getElementById('couleur').value;
        if (!/^#[0-9A-Fa-f]{6}$/.test(couleur)) {
            e.preventDefault();
            alert('La couleur doit être au format hexadécimal (#123456).');
            return false;
        }
    });
}

// Événements pour la mise à jour de l'aperçu
function setupPreviewEvents() {
    const elementsToWatch = [
        'nom', 'code', 'icone', 'couleur', 'categorie', 'niveau_acces',
        'frequence_type', 'duree_standard'
    ];

    elementsToWatch.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });

    // Checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePreview);
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    syncColorInputs();
    setupConditionalSections();
    setupFormValidation();
    setupPreviewEvents();
    updatePreview();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/typesreunions/edit.blade.php ENDPATH**/ ?>