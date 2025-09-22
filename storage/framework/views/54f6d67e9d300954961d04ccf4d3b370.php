<?php $__env->startSection('title', 'Modifier les Paramètres'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier les Paramètres</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.parametres.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-cogs mr-2"></i>
                        Paramètres
                    </a>
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

    <form action="<?php echo e(route('private.parametres.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>

                    <a href="<?php echo e(route('private.parametres.index')); ?>" class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>

                    <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-3 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-undo mr-2"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Informations de base -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-church text-blue-600 mr-2"></i>
                    Informations de Base
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom_eglise" class="block text-sm font-medium text-slate-700 mb-2">Nom de l'Église <span class="text-red-500">*</span></label>
                        <input type="text" name="nom_eglise" id="nom_eglise" value="<?php echo e(old('nom_eglise', $parametres->nom_eglise)); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['nom_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="date_fondation" class="block text-sm font-medium text-slate-700 mb-2">Date de Fondation</label>
                        <input type="date" name="date_fondation" id="date_fondation" value="<?php echo e(old('date_fondation', $parametres->date_fondation?->format('Y-m-d'))); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_fondation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['date_fondation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="description_eglise" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description_eglise" id="description_eglise" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['description_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Décrivez votre église..."><?php echo e(old('description_eglise', $parametres->description_eglise)); ?></textarea>
                    <?php $__errorArgs = ['description_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="nombre_membres" class="block text-sm font-medium text-slate-700 mb-2">Nombre de Membres</label>
                    <input type="number" name="nombre_membres" id="nombre_membres" value="<?php echo e(old('nombre_membres', $parametres->nombre_membres)); ?>" min="0" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_membres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['nombre_membres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-address-book text-green-600 mr-2"></i>
                    Informations de Contact
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="telephone_1" class="block text-sm font-medium text-slate-700 mb-2">Téléphone Principal <span class="text-red-500">*</span></label>
                        <input type="tel" name="telephone_1" id="telephone_1" value="<?php echo e(old('telephone_1', $parametres->telephone_1)); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['telephone_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['telephone_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="telephone_2" class="block text-sm font-medium text-slate-700 mb-2">Téléphone Secondaire</label>
                        <input type="tel" name="telephone_2" id="telephone_2" value="<?php echo e(old('telephone_2', $parametres->telephone_2)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['telephone_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['telephone_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email_principal" class="block text-sm font-medium text-slate-700 mb-2">Email Principal <span class="text-red-500">*</span></label>
                        <input type="email" name="email_principal" id="email_principal" value="<?php echo e(old('email_principal', $parametres->email_principal)); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['email_principal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email_principal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email_secondaire" class="block text-sm font-medium text-slate-700 mb-2">Email Secondaire</label>
                        <input type="email" name="email_secondaire" id="email_secondaire" value="<?php echo e(old('email_secondaire', $parametres->email_secondaire)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['email_secondaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email_secondaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse <span class="text-red-500">*</span></label>
                    <textarea name="adresse" id="adresse" rows="3" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Adresse complète de l'église"><?php echo e(old('adresse', $parametres->adresse)); ?></textarea>
                    <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville <span class="text-red-500">*</span></label>
                        <input type="text" name="ville" id="ville" value="<?php echo e(old('ville', $parametres->ville)); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="commune" class="block text-sm font-medium text-slate-700 mb-2">Commune</label>
                        <input type="text" name="commune" id="commune" value="<?php echo e(old('commune', $parametres->commune)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['commune'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['commune'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-slate-700 mb-2">Code Postal</label>
                        <input type="text" name="code_postal" id="code_postal" value="<?php echo e(old('code_postal', $parametres->code_postal)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['code_postal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['code_postal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="pays" class="block text-sm font-medium text-slate-700 mb-2">Pays <span class="text-red-500">*</span></label>
                    <input type="text" name="pays" id="pays" value="<?php echo e(old('pays', $parametres->pays)); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['pays'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['pays'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Médias -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-images text-purple-600 mr-2"></i>
                    Médias
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-slate-700 mb-2">Logo de l'Église</label>
                    <div class="flex items-center space-x-6">
                        <?php if($parametres->logo_url): ?>
                            <div class="flex-shrink-0">
                                <img src="<?php echo e($parametres->logo_url); ?>" alt="Logo actuel" class="w-20 h-20 object-cover rounded-xl shadow-md">
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" name="logo" id="logo" accept="image/*" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <p class="text-sm text-slate-500 mt-1">Format accepté: JPG, PNG, SVG. Taille max: 2MB</p>
                        </div>
                    </div>
                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Images Hero -->
                <div>
                    <label for="images_hero" class="block text-sm font-medium text-slate-700 mb-2">Images pour la Page d'Accueil</label>

                    <?php if($parametres->images_hero_urls && count($parametres->images_hero_urls) > 0): ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <?php $__currentLoopData = $parametres->images_hero_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group">
                                    <img src="<?php echo e($imageUrl); ?>" alt="Image hero <?php echo e($index + 1); ?>" class="w-full h-24 object-cover rounded-xl shadow-md">
                                    <button type="button" onclick="removeHeroImage(<?php echo e($index); ?>)" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <input type="file" name="images_hero[]" id="images_hero" multiple accept="image/*" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['images_hero.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <p class="text-sm text-slate-500 mt-1">Vous pouvez sélectionner plusieurs images. Format accepté: JPG, PNG. Taille max: 5MB par image</p>
                    <?php $__errorArgs = ['images_hero.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Contenu Spirituel -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bible text-amber-600 mr-2"></i>
                    Contenu Spirituel
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="verset_biblique" class="block text-sm font-medium text-slate-700 mb-2">Verset Biblique</label>
                        <textarea name="verset_biblique" id="verset_biblique" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['verset_biblique'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Verset inspirant pour votre église..."><?php echo e(old('verset_biblique', $parametres->verset_biblique)); ?></textarea>
                        <?php $__errorArgs = ['verset_biblique'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="reference_verset" class="block text-sm font-medium text-slate-700 mb-2">Référence</label>
                        <input type="text" name="reference_verset" id="reference_verset" value="<?php echo e(old('reference_verset', $parametres->reference_verset)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['reference_verset'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ex: Jean 3:16">
                        <?php $__errorArgs = ['reference_verset'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="mission_statement" class="block text-sm font-medium text-slate-700 mb-2">Mission</label>
                    <textarea name="mission_statement" id="mission_statement" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['mission_statement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Quelle est la mission de votre église ?"><?php echo e(old('mission_statement', $parametres->mission_statement)); ?></textarea>
                    <?php $__errorArgs = ['mission_statement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="vision" class="block text-sm font-medium text-slate-700 mb-2">Vision</label>
                    <textarea name="vision" id="vision" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['vision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Quelle est la vision de votre église ?"><?php echo e(old('vision', $parametres->vision)); ?></textarea>
                    <?php $__errorArgs = ['vision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="histoire_eglise" class="block text-sm font-medium text-slate-700 mb-2">Histoire de l'Église</label>
                    <textarea name="histoire_eglise" id="histoire_eglise" rows="5" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['histoire_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Racontez l'histoire de votre église..."><?php echo e(old('histoire_eglise', $parametres->histoire_eglise)); ?></textarea>
                    <?php $__errorArgs = ['histoire_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Réseaux Sociaux -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                    Réseaux Sociaux
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                        </label>
                        <input type="url" name="facebook_url" id="facebook_url" value="<?php echo e(old('facebook_url', $parametres->facebook_url)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://facebook.com/votre-eglise">
                        <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram
                        </label>
                        <input type="url" name="instagram_url" id="instagram_url" value="<?php echo e(old('instagram_url', $parametres->instagram_url)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://instagram.com/votre-eglise">
                        <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fab fa-youtube text-red-600 mr-1"></i> YouTube
                        </label>
                        <input type="url" name="youtube_url" id="youtube_url" value="<?php echo e(old('youtube_url', $parametres->youtube_url)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://youtube.com/c/votre-eglise">
                        <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fab fa-twitter text-sky-600 mr-1"></i> Twitter
                        </label>
                        <input type="url" name="twitter_url" id="twitter_url" value="<?php echo e(old('twitter_url', $parametres->twitter_url)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['twitter_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://twitter.com/votre-eglise">
                        <?php $__errorArgs = ['twitter_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="website_url" class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-globe text-blue-600 mr-1"></i> Site Web
                    </label>
                    <input type="url" name="website_url" id="website_url" value="<?php echo e(old('website_url', $parametres->website_url)); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['website_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://www.votre-eglise.com">
                    <?php $__errorArgs = ['website_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Horaires de Culte -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-clock text-amber-600 mr-2"></i>
                    Horaires de Culte
                </h2>
            </div>
            <div class="p-6">
                <div id="horaires-container" class="space-y-4">
                    <?php if($parametres->horaires_cultes && count($parametres->horaires_cultes) > 0): ?>
                        <?php $__currentLoopData = $parametres->horaires_cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $horaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="horaire-item grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                                    <select name="horaires_cultes[<?php echo e($index); ?>][jour]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="dimanche" <?php echo e(($horaire['jour'] ?? '') == 'dimanche' ? 'selected' : ''); ?>>Dimanche</option>
                                        <option value="lundi" <?php echo e(($horaire['jour'] ?? '') == 'lundi' ? 'selected' : ''); ?>>Lundi</option>
                                        <option value="mardi" <?php echo e(($horaire['jour'] ?? '') == 'mardi' ? 'selected' : ''); ?>>Mardi</option>
                                        <option value="mercredi" <?php echo e(($horaire['jour'] ?? '') == 'mercredi' ? 'selected' : ''); ?>>Mercredi</option>
                                        <option value="jeudi" <?php echo e(($horaire['jour'] ?? '') == 'jeudi' ? 'selected' : ''); ?>>Jeudi</option>
                                        <option value="vendredi" <?php echo e(($horaire['jour'] ?? '') == 'vendredi' ? 'selected' : ''); ?>>Vendredi</option>
                                        <option value="samedi" <?php echo e(($horaire['jour'] ?? '') == 'samedi' ? 'selected' : ''); ?>>Samedi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Heure</label>
                                    <input type="time" name="horaires_cultes[<?php echo e($index); ?>][heure]" value="<?php echo e($horaire['heure'] ?? ''); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                                    <input type="text" name="horaires_cultes[<?php echo e($index); ?>][type]" value="<?php echo e($horaire['type'] ?? ''); ?>" placeholder="Culte, Prière..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeHoraire(this)" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <button type="button" onclick="addHoraire()" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Ajouter un horaire
                </button>
            </div>
        </div>

        <!-- Paramètres Système -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-cogs text-slate-600 mr-2"></i>
                    Paramètres Système
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="devise" class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                        <select name="devise" id="devise" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['devise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="EUR" <?php echo e(old('devise', $parametres->devise) == 'EUR' ? 'selected' : ''); ?>>Euro (EUR)</option>
                            <option value="USD" <?php echo e(old('devise', $parametres->devise) == 'USD' ? 'selected' : ''); ?>>Dollar US (USD)</option>
                            <option value="XOF" <?php echo e(old('devise', $parametres->devise) == 'XOF' ? 'selected' : ''); ?>>Franc CFA (XOF)</option>
                            <option value="XAF" <?php echo e(old('devise', $parametres->devise) == 'XAF' ? 'selected' : ''); ?>>Franc CFA Central (XAF)</option>
                        </select>
                        <?php $__errorArgs = ['devise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="langue" class="block text-sm font-medium text-slate-700 mb-2">Langue</label>
                        <select name="langue" id="langue" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['langue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="fr" <?php echo e(old('langue', $parametres->langue) == 'fr' ? 'selected' : ''); ?>>Français</option>
                            <option value="en" <?php echo e(old('langue', $parametres->langue) == 'en' ? 'selected' : ''); ?>>Anglais</option>
                            <option value="es" <?php echo e(old('langue', $parametres->langue) == 'es' ? 'selected' : ''); ?>>Espagnol</option>
                        </select>
                        <?php $__errorArgs = ['langue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="fuseau_horaire" class="block text-sm font-medium text-slate-700 mb-2">Fuseau Horaire</label>
                        <select name="fuseau_horaire" id="fuseau_horaire" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['fuseau_horaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="Europe/Paris" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'Europe/Paris' ? 'selected' : ''); ?>>Europe/Paris</option>
                            <option value="Africa/Abidjan" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'Africa/Abidjan' ? 'selected' : ''); ?>>Africa/Abidjan</option>
                            <option value="America/New_York" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'America/New_York' ? 'selected' : ''); ?>>America/New_York</option>
                        </select>
                        <?php $__errorArgs = ['fuseau_horaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions finales -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>

                    <a href="<?php echo e(route('private.parametres.index')); ?>" class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour sans sauvegarder
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let horaireIndex = <?php echo e($parametres->horaires_cultes ? count($parametres->horaires_cultes) : 0); ?>;

function addHoraire() {
    const container = document.getElementById('horaires-container');
    const horaireHtml = `
        <div class="horaire-item grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                <select name="horaires_cultes[${horaireIndex}][jour]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="dimanche">Dimanche</option>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Heure</label>
                <input type="time" name="horaires_cultes[${horaireIndex}][heure]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                <input type="text" name="horaires_cultes[${horaireIndex}][type]" placeholder="Culte, Prière..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeHoraire(this)" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-1"></i> Supprimer
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', horaireHtml);
    horaireIndex++;
}

function removeHoraire(button) {
    button.closest('.horaire-item').remove();
}

function removeHeroImage(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        // Ici vous pouvez ajouter la logique pour supprimer l'image du serveur
        // Pour l'instant, on cache juste l'élément
        event.target.closest('.group').style.display = 'none';
    }
}

function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les modifications non sauvegardées seront perdues.')) {
        document.querySelector('form').reset();
        location.reload();
    }
}

// Validation côté client
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = [
        'nom_eglise',
        'telephone_1',
        'email_principal',
        'adresse',
        'ville',
        'pays'
    ];

    let hasErrors = false;

    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            hasErrors = true;
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (hasErrors) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        document.querySelector('.border-red-500').scrollIntoView({ behavior: 'smooth' });
    }
});

// Prévisualisation des images
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Créer une prévisualisation si elle n'existe pas
            let preview = document.querySelector('#logo-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'logo-preview';
                preview.className = 'w-20 h-20 object-cover rounded-xl shadow-md mt-2';
                document.getElementById('logo').parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Prévisualisation des images hero
document.getElementById('images_hero').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
        // Créer un conteneur pour les prévisualisations
        let previewContainer = document.querySelector('#hero-preview-container');
        if (!previewContainer) {
            previewContainer = document.createElement('div');
            previewContainer.id = 'hero-preview-container';
            previewContainer.className = 'grid grid-cols-2 md:grid-cols-4 gap-4 mt-4';
            e.target.parentNode.appendChild(previewContainer);
        }

        // Vider le conteneur
        previewContainer.innerHTML = '';

        // Ajouter les prévisualisations
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Prévisualisation ${index + 1}" class="w-full h-24 object-cover rounded-xl shadow-md">
                        <div class="absolute top-2 right-2 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-xs"></i>
                        </div>
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Validation des URLs
document.querySelectorAll('input[type="url"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && !this.value.match(/^https?:\/\/.+/)) {
            this.setCustomValidity('Veuillez entrer une URL valide commençant par http:// ou https://');
        } else {
            this.setCustomValidity('');
        }
    });
});

// Validation des emails
document.querySelectorAll('input[type="email"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && !this.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            this.setCustomValidity('Veuillez entrer une adresse email valide');
        } else {
            this.setCustomValidity('');
        }
    });
});

// Auto-sauvegarde en brouillon (optionnel)
let autoSaveTimeout;
document.querySelectorAll('input, textarea, select').forEach(field => {
    field.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Sauvegarder en localStorage pour récupération en cas de problème
            const formData = new FormData(document.querySelector('form'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem('parametres_draft', JSON.stringify(data));
        }, 2000); // Sauvegarde après 2 secondes d'inactivité
    });
});

// Récupérer le brouillon au chargement de la page
window.addEventListener('load', function() {
    const draft = localStorage.getItem('parametres_draft');
    if (draft && confirm('Un brouillon a été trouvé. Voulez-vous le restaurer ?')) {
        const data = JSON.parse(draft);
        Object.keys(data).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field && field.type !== 'file') {
                field.value = data[key];
            }
        });
    }
});

// Nettoyer le brouillon après soumission réussie
document.querySelector('form').addEventListener('submit', function() {
    setTimeout(() => {
        localStorage.removeItem('parametres_draft');
    }, 1000);
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/parametres/edit.blade.php ENDPATH**/ ?>