<?php $__env->startSection('title', 'Modifier les Paramètres'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier les Paramètres</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo e(route('private.parametres.index')); ?>"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
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

        <form action="<?php echo e(route('private.parametres.update')); ?>" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>

                        <a href="<?php echo e(route('private.parametres.index')); ?>"
                            class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>

                        <button type="button" onclick="resetForm()"
                            class="inline-flex items-center px-4 py-3 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-undo mr-2"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations de base -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-blue-600 mr-2"></i>
                        Informations de Base
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom_eglise" class="block text-sm font-medium text-slate-700 mb-2">Nom de l'Église
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nom_eglise" id="nom_eglise"
                                value="<?php echo e(old('nom_eglise', $parametres->nom_eglise)); ?>" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom_eglise'];
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
                            <label for="date_fondation" class="block text-sm font-medium text-slate-700 mb-2">Date de
                                Fondation</label>
                            <input type="date" name="date_fondation" id="date_fondation"
                                value="<?php echo e(old('date_fondation', $parametres->date_fondation?->format('Y-m-d'))); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_fondation'];
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
                        <label for="description_eglise"
                            class="block text-sm font-medium text-slate-700 mb-2">Mot d'accueil et de salutation</label>
                        <input type="text" name="description_eglise" id="description_eglise" required value="<?php echo e(old('description_eglise', $parametres->description_eglise)); ?>" max="50"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['description_eglise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Fourmule de bienvenue..."/>
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
                        <label for="vision" class="block text-sm font-medium text-slate-700 mb-2">Phrase introductive</label>
                        <input name="vision" type="text" id="vision" max="100" required value="<?php echo e(old('vision', $parametres->vision)); ?>"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['vision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Phrase courte descriptive..."/>
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


                </div>
            </div>

            <!-- Contact -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-address-book text-green-600 mr-2"></i>
                        Informations de Contact
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone_1" class="block text-sm font-medium text-slate-700 mb-2">Téléphone
                                Principal <span class="text-red-500">*</span></label>
                            <input type="tel" name="telephone_1" id="telephone_1"
                                value="<?php echo e(old('telephone_1', $parametres->telephone_1)); ?>" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['telephone_1'];
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
                            <label for="telephone_2" class="block text-sm font-medium text-slate-700 mb-2">Téléphone
                                Secondaire</label>
                            <input type="tel" name="telephone_2" id="telephone_2"
                                value="<?php echo e(old('telephone_2', $parametres->telephone_2)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['telephone_2'];
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
                            <label for="email_principal" class="block text-sm font-medium text-slate-700 mb-2">Email
                                Principal <span class="text-red-500">*</span></label>
                            <input type="email" name="email_principal" id="email_principal"
                                value="<?php echo e(old('email_principal', $parametres->email_principal)); ?>" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['email_principal'];
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
                            <label for="email_secondaire" class="block text-sm font-medium text-slate-700 mb-2">Email
                                Secondaire</label>
                            <input type="email" name="email_secondaire" id="email_secondaire"
                                value="<?php echo e(old('email_secondaire', $parametres->email_secondaire)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['email_secondaire'];
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
                        <label for="adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="adresse" id="adresse" rows="3" required value="<?php echo e(old('adresse', $parametres->adresse)); ?>" max="50"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Adresse complète de l'église"/>
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
                            <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="ville" id="ville" value="<?php echo e(old('ville', $parametres->ville)); ?>"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['ville'];
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
                            <input type="text" name="commune" id="commune"
                                value="<?php echo e(old('commune', $parametres->commune)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['commune'];
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
                            <label for="code_postal" class="block text-sm font-medium text-slate-700 mb-2">Code
                                Postal</label>
                            <input type="text" name="code_postal" id="code_postal"
                                value="<?php echo e(old('code_postal', $parametres->code_postal)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['code_postal'];
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
                        <label for="pays" class="block text-sm font-medium text-slate-700 mb-2">Pays <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="pays" id="pays" value="<?php echo e(old('pays', $parametres->pays)); ?>" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['pays'];
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
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-images text-purple-600 mr-2"></i>
                        Médias
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    

                    <div class="p-6 space-y-8">
                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-church text-blue-600 mr-1"></i> Logo de l'Église
                            </label>
                            <div class="flex items-center space-x-6">
                                <?php if($parametres->logo_url): ?>
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo e($parametres->logo_url); ?>" alt="Logo actuel"
                                            class="w-24 h-24 object-contain rounded-xl shadow-md border-2 border-slate-200 p-2 bg-white">
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <input type="file" name="logo" id="logo" accept="image/*"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <p class="text-sm text-slate-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Format: JPG, PNG, SVG | Taille max: 2MB | Recommandé: 512x512px (format carré)
                                    </p>
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

                        <!-- Séparateur -->
                        <div class="border-t border-slate-200"></div>

                        <!-- Images Hero Slider -->
                        <div id="images-hero-section">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <label class="block text-lg font-semibold text-slate-800 mb-1">
                                            <i class="fas fa-sliders-h text-purple-600 mr-2"></i>
                                            Carrousel Hero (Page d'Accueil)
                                        </label>
                                        <p class="text-sm text-slate-600">
                                            Images affichées en slider sur la bannière principale de votre site
                                        </p>
                                    </div>
                                    <button type="button" onclick="addImageHeroSlide()"
                                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-plus-circle mr-2"></i> Ajouter un Slide
                                    </button>
                                </div>

                                <!-- Recommendations -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">
                                        <i class="fas fa-info-circle mr-1"></i> Recommandations pour les slides Hero
                                    </h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li><i class="fas fa-check text-green-600 mr-2"></i><strong>Dimensions:</strong> 1920x1080px (Full HD) ou ratio 16:9</li>
                                        <li><i class="fas fa-check text-green-600 mr-2"></i><strong>Format:</strong> JPG (pour photos), PNG (avec transparence), WebP (optimisé)</li>
                                        <li><i class="fas fa-check text-green-600 mr-2"></i><strong>Poids:</strong> Maximum 5MB par image (idéalement 500KB-1MB pour de bonnes performances)</li>
                                        <li><i class="fas fa-check text-green-600 mr-2"></i><strong>Nombre:</strong> 3-5 slides recommandés pour un bon équilibre</li>
                                        <li><i class="fas fa-check text-green-600 mr-2"></i><strong>Contenu:</strong> Images de haute qualité montrant l'église, la communauté, les événements</li>
                                    </ul>
                                </div>
                            </div>

                            <?php
                                $imagesHero = old('images_hero_data', $parametres->images_hero ?: []);
                            ?>

                            <div id="images-hero-container" class="space-y-4">
                                <?php if($imagesHero && count($imagesHero) > 0): ?>
                                    <?php $__currentLoopData = $imagesHero; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="hero-slide-item bg-gradient-to-r from-slate-50 to-purple-50 rounded-xl border-2 border-purple-200 p-5 shadow-sm hover:shadow-lg transition-all duration-200"
                                            data-slide-index="<?php echo e($index); ?>">
                                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                                                <!-- Aperçu & Contrôles -->
                                                <div class="lg:col-span-4">
                                                    <div class="space-y-3">
                                                        <!-- Badge Ordre -->
                                                        <div class="flex items-center justify-between">
                                                            <span class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm font-bold rounded-full shadow-md">
                                                                <i class="fas fa-grip-vertical mr-2 cursor-move drag-handle"></i>
                                                                Slide #<span class="slide-number"><?php echo e($index + 1); ?></span>
                                                            </span>
                                                            <div class="flex items-center gap-2">
                                                                <label class="text-xs text-slate-600 font-medium">Ordre:</label>
                                                                <input type="number"
                                                                    name="images_hero_data[<?php echo e($index); ?>][ordre]"
                                                                    value="<?php echo e($image['ordre'] ?? ($index + 1)); ?>"
                                                                    min="1"
                                                                    class="w-16 px-2 py-1 text-sm text-center font-semibold border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                                            </div>
                                                        </div>

                                                        <!-- Aperçu Image -->
                                                        <?php if(isset($image['url'])): ?>
                                                            <div class="relative group rounded-xl overflow-hidden shadow-xl border-2 border-purple-300">
                                                                <div class="aspect-video bg-slate-200">
                                                                    <img src="<?php echo e(asset('storage/' . $image['url'])); ?>"
                                                                        alt="<?php echo e($image['titre'] ?? 'Slide ' . ($index + 1)); ?>"
                                                                        class="w-full h-full object-cover">
                                                                </div>
                                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                                    <div class="absolute bottom-3 left-3 right-3">
                                                                        <p class="text-white text-sm font-medium line-clamp-2">
                                                                            <i class="fas fa-image mr-1"></i><?php echo e($image['titre'] ?? 'Sans titre'); ?>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <!-- Badge statut -->
                                                                <div class="absolute top-3 right-3">
                                                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full shadow-lg <?php echo e(($image['active'] ?? true) ? 'bg-green-500 text-white' : 'bg-gray-600 text-white'); ?>">
                                                                        <i class="fas fa-<?php echo e(($image['active'] ?? true) ? 'eye' : 'eye-slash'); ?> mr-1"></i>
                                                                        <?php echo e(($image['active'] ?? true) ? 'Visible' : 'Masqué'); ?>

                                                                    </span>
                                                                </div>
                                                                <!-- Dimensions info -->
                                                                <div class="absolute top-3 left-3">
                                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-black/70 text-white rounded backdrop-blur-sm">
                                                                        <i class="fas fa-expand-arrows-alt mr-1"></i>16:9
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="aspect-video bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center border-2 border-dashed border-purple-400">
                                                                <div class="text-center text-purple-600">
                                                                    <i class="fas fa-image text-5xl mb-2 opacity-50"></i>
                                                                    <p class="text-sm font-medium">Nouveau slide</p>
                                                                    <p class="text-xs text-purple-500">1920x1080px recommandé</p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Informations du Slide -->
                                                <div class="lg:col-span-8">
                                                    <div class="space-y-4">
                                                        <!-- Titre du Slide -->
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                                <i class="fas fa-heading text-purple-600 mr-1"></i>
                                                                Titre du Slide <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                name="images_hero_data[<?php echo e($index); ?>][titre]"
                                                                value="<?php echo e($image['titre'] ?? ''); ?>"
                                                                placeholder="Ex: Bienvenue dans notre communauté de foi"
                                                                required
                                                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-medium <?php $__errorArgs = ['images_hero_data.' . $index . '.titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                            <p class="text-xs text-slate-500 mt-1">
                                                                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                                                                Ce titre peut être affiché en superposition sur l'image
                                                            </p>
                                                            <?php $__errorArgs = ['images_hero_data.' . $index . '.titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i><?php echo e($message); ?></p>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>

                                                        <!-- Description (optionnel pour texte overlay) -->
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                                <i class="fas fa-align-left text-purple-600 mr-1"></i>
                                                                Description / Texte d'accompagnement
                                                                <span class="text-xs font-normal text-slate-500">(Optionnel)</span>
                                                            </label>
                                                            <textarea
                                                                name="images_hero_data[<?php echo e($index); ?>][description]"
                                                                rows="2"
                                                                placeholder="Texte descriptif qui pourra être affiché sous le titre du slide..."
                                                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 <?php $__errorArgs = ['images_hero_data.' . $index . '.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e($image['description'] ?? ''); ?></textarea>
                                                            <p class="text-xs text-slate-500 mt-1">
                                                                Maximum 150 caractères pour une bonne lisibilité
                                                            </p>
                                                            <?php $__errorArgs = ['images_hero_data.' . $index . '.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i><?php echo e($message); ?></p>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>

                                                        <!-- Upload Image -->
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                                <i class="fas fa-cloud-upload-alt text-purple-600 mr-1"></i>
                                                                <?php echo e(isset($image['url']) ? 'Remplacer l\'image' : 'Image du Slide'); ?>

                                                                <?php if(!isset($image['url'])): ?> <span class="text-red-500">*</span> <?php endif; ?>
                                                            </label>
                                                            <input type="file"
                                                                name="images_hero_files[<?php echo e($index); ?>]"
                                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                                <?php echo e(isset($image['url']) ? '' : 'required'); ?>

                                                                onchange="previewSlideImage(this, <?php echo e($index); ?>)"
                                                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 <?php $__errorArgs = ['images_hero_files.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                            <div class="mt-2 text-xs text-slate-600 space-y-1">
                                                                <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Format recommandé:</strong> JPG ou WebP (meilleure compression)</p>
                                                                <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Dimensions optimales:</strong> 1920x1080px (Full HD, ratio 16:9)</p>
                                                                <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Poids max:</strong> 5MB | <strong>Recommandé:</strong> 500KB-1MB</p>
                                                            </div>
                                                            <?php $__errorArgs = ['images_hero_files.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <p class="text-red-500 text-xs mt-2"><i class="fas fa-exclamation-circle mr-1"></i><?php echo e($message); ?></p>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>

                                                        <!-- Options & Actions -->
                                                        <div class="pt-4 border-t-2 border-slate-200">
                                                            <div class="flex items-center justify-between flex-wrap gap-4">
                                                                <!-- Statut Actif -->
                                                                <label class="flex items-center cursor-pointer group">
                                                                    <div class="relative">
                                                                        <input type="checkbox"
                                                                            name="images_hero_data[<?php echo e($index); ?>][active]"
                                                                            value="1"
                                                                            <?php echo e(($image['active'] ?? true) ? 'checked' : ''); ?>

                                                                            class="sr-only peer">
                                                                        <div class="w-11 h-6 bg-gray-400 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                                                    </div>
                                                                    <span class="ml-3 text-sm font-semibold text-slate-700 group-hover:text-purple-600 transition-colors">
                                                                        <i class="fas fa-eye mr-1"></i>
                                                                        Slide visible sur le site
                                                                    </span>
                                                                </label>

                                                                <!-- Actions -->
                                                                <div class="flex items-center gap-2">
                                                                    <button type="button"
                                                                        onclick="duplicateSlide(this)"
                                                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                                                                        title="Dupliquer ce slide">
                                                                        <i class="fas fa-copy mr-1"></i> Dupliquer
                                                                    </button>
                                                                    <button type="button"
                                                                        onclick="removeSlide(this)"
                                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md"
                                                                        title="Supprimer ce slide">
                                                                        <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Champs cachés -->
                                                        <input type="hidden" name="images_hero_data[<?php echo e($index); ?>][id]" value="<?php echo e($image['id'] ?? ''); ?>">
                                                        <?php if(isset($image['url'])): ?>
                                                            <input type="hidden" name="images_hero_data[<?php echo e($index); ?>][url]" value="<?php echo e($image['url']); ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div id="no-slides-message" class="text-center py-12 bg-gradient-to-br from-slate-50 to-purple-50 rounded-xl border-2 border-dashed border-purple-300">
                                        <i class="fas fa-images text-6xl text-purple-300 mb-4"></i>
                                        <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucun slide configuré</h3>
                                        <p class="text-sm text-slate-600 mb-4">Créez votre premier slide pour le carrousel de la page d'accueil</p>
                                        <button type="button" onclick="addImageHeroSlide()"
                                            class="inline-flex items-center px-5 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                                            <i class="fas fa-plus-circle mr-2"></i> Créer le premier slide
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Footer avec compteur et actions -->
                            <div class="mt-6 pt-5 border-t-2 border-slate-200">
                                <div class="flex items-center justify-between flex-wrap gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="text-sm font-medium text-slate-700">
                                            <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                                            <span id="slides-count" class="font-bold text-lg text-purple-600"><?php echo e(count($imagesHero)); ?></span>
                                            <span class="text-slate-600">slide(s) configuré(s)</span>
                                        </div>
                                        <?php if(count($imagesHero) > 1): ?>
                                            <button type="button" onclick="sortSlidesByOrder()"
                                                class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                                <i class="fas fa-sort-amount-down mr-1"></i> Réorganiser par ordre
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(count($imagesHero) > 0): ?>
                                        <div class="text-xs text-slate-500">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Glissez-déposez les slides pour les réordonner
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                     </div>


                </div>
            </div>

            <!-- Contenu Spirituel -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bible text-amber-600 mr-2"></i>
                        Contenu Spirituel
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="verset_biblique" class="block text-sm font-medium text-slate-700 mb-2">Verset
                                Biblique</label>
                            <textarea name="verset_biblique" id="verset_biblique" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['verset_biblique'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Verset inspirant pour votre église..."><?php echo e(old('verset_biblique', $parametres->verset_biblique)); ?></textarea>
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
                            <label for="reference_verset"
                                class="block text-sm font-medium text-slate-700 mb-2">Référence</label>
                            <input type="text" name="reference_verset" id="reference_verset"
                                value="<?php echo e(old('reference_verset', $parametres->reference_verset)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['reference_verset'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="ex: Jean 3:16">
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






                </div>
            </div>

            <!-- Réseaux Sociaux -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                            <input type="url" name="facebook_url" id="facebook_url"
                                value="<?php echo e(old('facebook_url', $parametres->facebook_url)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="https://facebook.com/votre-eglise">
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
                            <input type="url" name="instagram_url" id="instagram_url"
                                value="<?php echo e(old('instagram_url', $parametres->instagram_url)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="https://instagram.com/votre-eglise">
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
                            <input type="url" name="youtube_url" id="youtube_url"
                                value="<?php echo e(old('youtube_url', $parametres->youtube_url)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="https://youtube.com/c/votre-eglise">
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
                            <input type="url" name="twitter_url" id="twitter_url"
                                value="<?php echo e(old('twitter_url', $parametres->twitter_url)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['twitter_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="https://twitter.com/votre-eglise">
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
                        <input type="url" name="website_url" id="website_url"
                            value="<?php echo e(old('website_url', $parametres->website_url)); ?>"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['website_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="https://www.votre-eglise.com">
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



            <!-- Programmes de l'église -->
            <div id="programmes"
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-amber-600 mr-2"></i>
                        Programmes de l'Église
                    </h2>
                </div>
                <div class="p-6">
                    <div id="programmes-container" class="space-y-4">
                        <?php
                            // Récupérer les données old() ou les données du modèle
                            $programmesData = old('programmes', $parametres->getProgrammes() ?: []);
                        ?>

                        <?php if($programmesData && count($programmesData) > 0): ?>
                            <?php $__currentLoopData = $programmesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $programme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="programme-item p-6 bg-slate-50 rounded-xl border border-slate-200"
                                    data-programme-id="<?php echo e($programme['id'] ?? ''); ?>">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <!-- Titre et Description -->
                                        <div class="md:col-span-6">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Titre du Programme
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="text" name="programmes[<?php echo e($index); ?>][titre]"
                                                        value="<?php echo e($programme['titre'] ?? ''); ?>" placeholder="Ex: Cultes Dominicaux"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        required>
                                                    <?php $__errorArgs = ['programmes.' . $index . '.titre'];
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
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                                    <textarea name="programmes[<?php echo e($index); ?>][description]" rows="2"
                                                        placeholder="Description du programme..."
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e($programme['description'] ?? ''); ?></textarea>
                                                    <?php $__errorArgs = ['programmes.' . $index . '.description'];
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

                                        <!-- Icône et Type -->
                                        <div class="md:col-span-3">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Icône
                                                        FontAwesome</label>
                                                    <input type="text" name="programmes[<?php echo e($index); ?>][icone]"
                                                        value="<?php echo e($programme['icone'] ?? 'fas fa-calendar'); ?>"
                                                        placeholder="fas fa-praying-hands"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.icone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                    <?php $__errorArgs = ['programmes.' . $index . '.icone'];
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
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type
                                                        d'horaire</label>
                                                    <select name="programmes[<?php echo e($index); ?>][type_horaire]"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.type_horaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <option value="regulier" <?php echo e(($programme['type_horaire'] ?? '') == 'regulier' ? 'selected' : ''); ?>>Régulier</option>
                                                        <option value="sur_rendez_vous" <?php echo e(($programme['type_horaire'] ?? '') == 'sur_rendez_vous' ? 'selected' : ''); ?>>Sur rendez-vous</option>
                                                        <option value="permanent" <?php echo e(($programme['type_horaire'] ?? '') == 'permanent' ? 'selected' : ''); ?>>Permanent</option>
                                                        <option value="ponctuel" <?php echo e(($programme['type_horaire'] ?? '') == 'ponctuel' ? 'selected' : ''); ?>>Ponctuel</option>
                                                    </select>
                                                    <?php $__errorArgs = ['programmes.' . $index . '.type_horaire'];
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

                                        <!-- Horaires -->
                                        <div class="md:col-span-3">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                                                    <select name="programmes[<?php echo e($index); ?>][jour]"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.jour'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <option value="">-- Sélectionner --</option>
                                                        <?php $__currentLoopData = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($jour); ?>" <?php echo e(($programme['jour'] ?? '') == $jour ? 'selected' : ''); ?>><?php echo e($jour); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['programmes.' . $index . '.jour'];
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
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Début</label>
                                                        <input type="time" name="programmes[<?php echo e($index); ?>][heure_debut]"
                                                            value="<?php echo e($programme['heure_debut'] ?? ''); ?>"
                                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.heure_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <?php $__errorArgs = ['programmes.' . $index . '.heure_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Fin</label>
                                                        <input type="time" name="programmes[<?php echo e($index); ?>][heure_fin]"
                                                            value="<?php echo e($programme['heure_fin'] ?? ''); ?>"
                                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.heure_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <?php $__errorArgs = ['programmes.' . $index . '.heure_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">Texte
                                                        d'affichage</label>
                                                    <input type="text" name="programmes[<?php echo e($index); ?>][horaire_texte]"
                                                        value="<?php echo e($programme['horaire_texte'] ?? ''); ?>"
                                                        placeholder="Ex: Dimanche : 9h00 - 11h30"
                                                        class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['programmes.' . $index . '.horaire_texte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                    <?php $__errorArgs = ['programmes.' . $index . '.horaire_texte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Options et Actions -->
                                        <div class="md:col-span-12">
                                            <div class="flex items-center justify-between pt-4 border-t border-slate-300">
                                                <div class="flex items-center space-x-4">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="programmes[<?php echo e($index); ?>][est_public]" value="1"
                                                            <?php echo e(($programme['est_public'] ?? true) ? 'checked' : ''); ?>

                                                            class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-slate-700">Public</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="programmes[<?php echo e($index); ?>][est_actif]" value="1"
                                                            <?php echo e(($programme['est_actif'] ?? true) ? 'checked' : ''); ?>

                                                            class="rounded border-slate-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-slate-700">Actif</span>
                                                    </label>
                                                    <div class="text-sm text-slate-500">
                                                        Ordre: <input type="number" name="programmes[<?php echo e($index); ?>][ordre]"
                                                            value="<?php echo e($programme['ordre'] ?? ($index + 1)); ?>" min="1"
                                                            class="w-16 px-2 py-1 border border-slate-300 rounded text-xs <?php $__errorArgs = ['programmes.' . $index . '.ordre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <?php $__errorArgs = ['programmes.' . $index . '.ordre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <button type="button" onclick="duplicateProgramme(this)"
                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                        <i class="fas fa-copy mr-1"></i> Dupliquer
                                                    </button>
                                                    <button type="button" onclick="removeProgramme(this)"
                                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Champs cachés pour l'ID -->
                                        <input type="hidden" name="programmes[<?php echo e($index); ?>][id]"
                                            value="<?php echo e($programme['id'] ?? ''); ?>">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-200">
                        <button type="button" onclick="addProgramme()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter un programme
                        </button>

                        <div class="text-sm text-slate-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="programmes-count"><?php echo e(count($programmesData)); ?></span> programme(s) configuré(s)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paramètres Système -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                            <select name="devise" id="devise"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['devise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="EUR" <?php echo e(old('devise', $parametres->devise) == 'EUR' ? 'selected' : ''); ?>>Euro
                                    (EUR)</option>
                                <option value="USD" <?php echo e(old('devise', $parametres->devise) == 'USD' ? 'selected' : ''); ?>>Dollar
                                    US (USD)</option>
                                <option value="XOF" <?php echo e(old('devise', $parametres->devise) == 'XOF' ? 'selected' : ''); ?>>Franc
                                    CFA (XOF)</option>
                                <option value="XAF" <?php echo e(old('devise', $parametres->devise) == 'XAF' ? 'selected' : ''); ?>>Franc
                                    CFA Central (XAF)</option>
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
                            <select name="langue" id="langue"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['langue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="fr" <?php echo e(old('langue', $parametres->langue) == 'fr' ? 'selected' : ''); ?>>Français
                                </option>
                                <option value="en" <?php echo e(old('langue', $parametres->langue) == 'en' ? 'selected' : ''); ?>>Anglais
                                </option>
                                <option value="es" <?php echo e(old('langue', $parametres->langue) == 'es' ? 'selected' : ''); ?>>Espagnol
                                </option>
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
                            <label for="fuseau_horaire" class="block text-sm font-medium text-slate-700 mb-2">Fuseau
                                Horaire</label>
                            <select name="fuseau_horaire" id="fuseau_horaire"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['fuseau_horaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="Europe/Paris" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'Europe/Paris' ? 'selected' : ''); ?>>Europe/Paris</option>
                                <option value="Africa/Abidjan" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'Africa/Abidjan' ? 'selected' : ''); ?>>Africa/Abidjan
                                </option>
                                <option value="America/New_York" <?php echo e(old('fuseau_horaire', $parametres->fuseau_horaire) == 'America/New_York' ? 'selected' : ''); ?>>America/New_York
                                </option>
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
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>

                        <a href="<?php echo e(route('private.parametres.index')); ?>"
                            class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour sans sauvegarder
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
        $programmesData = old('programmes', $parametres->getProgrammes() ?: []);
    ?>


<script>
// ============= VALIDATION AVANCÉE DES IMAGES HERO =============

// Configuration des contraintes de validation (doit correspondre au controller)
const IMAGE_HERO_VALIDATION = {
    formats: ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
    maxSize: 5 * 1024 * 1024, // 5MB
    minWidth: 1280,
    minHeight: 720,
    recommendedWidth: 1920,
    recommendedHeight: 1080,
    recommendedRatio: 16/9,
    ratioTolerance: 0.1
};

// Stockage des validations d'images
const imageValidations = new Map();

// Fonction de validation d'une image
async function validateImageFile(file, index) {
    return new Promise((resolve) => {
        // Vérifier le format
        if (!IMAGE_HERO_VALIDATION.formats.includes(file.type)) {
            resolve({
                valid: false,
                errors: [`Format non accepté. Utilisez: JPG, PNG ou WebP`],
                warnings: []
            });
            return;
        }

        // Vérifier la taille
        if (file.size > IMAGE_HERO_VALIDATION.maxSize) {
            resolve({
                valid: false,
                errors: [`Fichier trop volumineux (${(file.size / 1024 / 1024).toFixed(2)} MB). Maximum: 5 MB`],
                warnings: []
            });
            return;
        }

        // Vérifier les dimensions
        const img = new Image();
        const objectUrl = URL.createObjectURL(file);

        img.onload = function() {
            URL.revokeObjectURL(objectUrl);

            const errors = [];
            const warnings = [];

            // Vérifier dimensions minimales
            if (this.width < IMAGE_HERO_VALIDATION.minWidth || this.height < IMAGE_HERO_VALIDATION.minHeight) {
                errors.push(`Dimensions trop petites (${this.width}x${this.height}px). Minimum requis: ${IMAGE_HERO_VALIDATION.minWidth}x${IMAGE_HERO_VALIDATION.minHeight}px`);
            }

            // Vérifier le ratio 16:9
            const ratio = this.width / this.height;
            const expectedRatio = IMAGE_HERO_VALIDATION.recommendedRatio;
            const ratioDiff = Math.abs(ratio - expectedRatio);

            if (ratioDiff > IMAGE_HERO_VALIDATION.ratioTolerance) {
                warnings.push(`Ratio ${ratio.toFixed(2)}:1 détecté. Recommandé: 16:9 (1.78:1). L'image pourrait être déformée.`);
            }

            // Vérifier dimensions recommandées
            if (this.width < IMAGE_HERO_VALIDATION.recommendedWidth || this.height < IMAGE_HERO_VALIDATION.recommendedHeight) {
                warnings.push(`Dimensions ${this.width}x${this.height}px. Recommandé: ${IMAGE_HERO_VALIDATION.recommendedWidth}x${IMAGE_HERO_VALIDATION.recommendedHeight}px pour une meilleure qualité.`);
            }

            // Vérifier le poids (recommandation)
            if (file.size > 1 * 1024 * 1024 && file.size <= IMAGE_HERO_VALIDATION.maxSize) {
                warnings.push(`Fichier de ${(file.size / 1024 / 1024).toFixed(2)} MB. Recommandé: 500KB-1MB pour de meilleures performances.`);
            }

            resolve({
                valid: errors.length === 0,
                errors: errors,
                warnings: warnings,
                dimensions: { width: this.width, height: this.height },
                size: file.size,
                ratio: ratio
            });
        };

        img.onerror = function() {
            URL.revokeObjectURL(objectUrl);
            resolve({
                valid: false,
                errors: ['Impossible de lire l\'image. Fichier corrompu ou format invalide.'],
                warnings: []
            });
        };

        img.src = objectUrl;
    });
}

// Afficher les erreurs et avertissements de validation
function displayValidationMessages(input, validation, index) {
    // Supprimer les anciens messages
    const existingMessages = input.parentElement.querySelectorAll('.validation-message');
    existingMessages.forEach(msg => msg.remove());

    if (!validation.valid && validation.errors.length > 0) {
        // Afficher les erreurs (bloquantes)
        const errorContainer = document.createElement('div');
        errorContainer.className = 'validation-message mt-2 p-3 bg-red-50 border-2 border-red-300 rounded-lg';
        errorContainer.innerHTML = `
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-800 mb-1">❌ Image non valide - Erreurs bloquantes:</p>
                    <ul class="text-xs text-red-700 space-y-1">
                        ${validation.errors.map(err => `<li>• ${err}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        input.parentElement.appendChild(errorContainer);
        input.classList.add('border-red-500', 'border-2');
        input.classList.remove('border-slate-300');
    } else if (validation.warnings.length > 0) {
        // Afficher les avertissements (non bloquants)
        const warningContainer = document.createElement('div');
        warningContainer.className = 'validation-message mt-2 p-3 bg-yellow-50 border border-yellow-300 rounded-lg';
        warningContainer.innerHTML = `
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-800 mb-1">⚠️ Avertissements (non bloquants):</p>
                    <ul class="text-xs text-yellow-700 space-y-1">
                        ${validation.warnings.map(warn => `<li>• ${warn}</li>`).join('')}
                    </ul>
                    <p class="text-xs text-yellow-600 mt-2 italic">Vous pouvez continuer, mais l'image pourrait ne pas s'afficher de manière optimale.</p>
                </div>
            </div>
        `;
        input.parentElement.appendChild(warningContainer);
        input.classList.add('border-yellow-400', 'border-2');
        input.classList.remove('border-slate-300', 'border-red-500');
    } else {
        // Image valide
        const successContainer = document.createElement('div');
        successContainer.className = 'validation-message mt-2 p-2 bg-green-50 border border-green-300 rounded-lg';
        successContainer.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <p class="text-xs font-medium text-green-700">
                    ✓ Image valide (${validation.dimensions.width}x${validation.dimensions.height}px, ${(validation.size / 1024 / 1024).toFixed(2)} MB)
                </p>
            </div>
        `;
        input.parentElement.appendChild(successContainer);
        input.classList.remove('border-red-500', 'border-yellow-400');
        input.classList.add('border-green-400', 'border-2');

        // Retirer le message de succès après 5 secondes
        setTimeout(() => {
            successContainer.remove();
            input.classList.remove('border-green-400', 'border-2');
            input.classList.add('border-slate-300');
        }, 5000);
    }
}

// Validation lors de la sélection d'image
document.addEventListener('change', async function(e) {
    if (e.target.name && e.target.name.includes('images_hero_files[')) {
        const input = e.target;
        const file = input.files[0];

        if (!file) {
            imageValidations.delete(input.name);
            return;
        }

        // Afficher un indicateur de chargement
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'validation-message mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg';
        loadingMsg.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                <p class="text-xs text-blue-700">Validation de l'image en cours...</p>
            </div>
        `;
        input.parentElement.appendChild(loadingMsg);

        // Extraire l'index
        const match = input.name.match(/images_hero_files\[(\d+)\]/);
        const index = match ? parseInt(match[1]) : 0;

        // Valider l'image
        const validation = await validateImageFile(file, index);

        // Supprimer le message de chargement
        loadingMsg.remove();

        // Stocker le résultat
        imageValidations.set(input.name, validation);

        // Afficher les messages
        displayValidationMessages(input, validation, index);
    }
});

// Validation globale avant soumission du formulaire
document.querySelector('form').addEventListener('submit', function(e) {
    let hasBlockingErrors = false;
    const errorMessages = [];

    // Vérifier toutes les images hero
    const imageInputs = document.querySelectorAll('input[name*="images_hero_files["]');

    imageInputs.forEach((input, idx) => {
        const file = input.files[0];
        const isRequired = input.hasAttribute('required');

        if (isRequired && !file) {
            hasBlockingErrors = true;
            errorMessages.push(`Slide #${idx + 1}: Image obligatoire manquante`);
            input.classList.add('border-red-500', 'border-2');
        } else if (file) {
            const validation = imageValidations.get(input.name);

            if (!validation) {
                hasBlockingErrors = true;
                errorMessages.push(`Slide #${idx + 1}: Image non validée (veuillez attendre la fin de la validation)`);
            } else if (!validation.valid) {
                hasBlockingErrors = true;
                errorMessages.push(`Slide #${idx + 1}: ${validation.errors.join(', ')}`);
            }
        }
    });

    // Vérifier les titres des slides
    const slideData = document.querySelectorAll('input[name*="images_hero_data["][name*="][titre]"]');
    slideData.forEach((input, idx) => {
        if (!input.value.trim()) {
            hasBlockingErrors = true;
            errorMessages.push(`Slide #${idx + 1}: Titre obligatoire manquant`);
            input.classList.add('border-red-500', 'border-2');
        }
    });

    if (hasBlockingErrors) {
        e.preventDefault();
        e.stopPropagation();

        // Créer et afficher une modal d'erreur
        const errorModal = document.createElement('div');
        errorModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        errorModal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="p-6 border-b border-red-200 bg-red-50">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-900">Impossible de soumettre le formulaire</h3>
                            <p class="text-sm text-red-700">Des erreurs de validation bloquent l'enregistrement</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm font-semibold text-slate-700 mb-3">
                        ${errorMessages.length} erreur(s) détectée(s):
                    </p>
                    <ul class="space-y-2 mb-6">
                        ${errorMessages.map(msg => `
                            <li class="flex items-start gap-2 text-sm text-slate-700 bg-red-50 p-3 rounded-lg">
                                <i class="fas fa-times-circle text-red-600 mt-0.5"></i>
                                <span>${msg}</span>
                            </li>
                        `).join('')}
                    </ul>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Rappel des exigences:
                        </p>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>✓ Format: JPG, PNG ou WebP uniquement</li>
                            <li>✓ Dimensions minimales: ${IMAGE_HERO_VALIDATION.minWidth}x${IMAGE_HERO_VALIDATION.minHeight}px</li>
                            <li>✓ Dimensions recommandées: ${IMAGE_HERO_VALIDATION.recommendedWidth}x${IMAGE_HERO_VALIDATION.recommendedHeight}px (ratio 16:9)</li>
                            <li>✓ Poids maximum: 5 MB</li>
                            <li>✓ Poids recommandé: 500 KB - 1 MB</li>
                        </ul>
                    </div>

                    <button onclick="this.closest('.fixed').remove()"
                            class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg">
                        <i class="fas fa-times mr-2"></i>Fermer et corriger les erreurs
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(errorModal);

        // Scroll vers le premier champ en erreur
        const firstError = document.querySelector('.border-red-500');
        if (firstError) {
            setTimeout(() => {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }, 300);
        }

        return false;
    }

    // Tout est valide, afficher un indicateur de chargement
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement en cours...';
    });
});

// Améliorer la fonction previewSlideImage existante
const originalPreviewSlideImage = window.previewSlideImage;
window.previewSlideImage = async function(input, index) {
    // Appeler la fonction originale si elle existe
    if (originalPreviewSlideImage) {
        originalPreviewSlideImage(input, index);
    }

    // Ajouter la validation
    const file = input.files[0];
    if (file) {
        const validation = await validateImageFile(file, index);
        imageValidations.set(input.name, validation);
        displayValidationMessages(input, validation, index);
    }
};

console.log('✅ Validation avancée des images hero activée');
</script>


    <script>




        let slideIndex = <?php echo e(count($imagesHero)); ?>;

        function addImageHeroSlide() {
            // Supprimer le message "aucun slide"
            const noSlidesMessage = document.getElementById('no-slides-message');
            if (noSlidesMessage) {
                noSlidesMessage.remove();
            }

            const container = document.getElementById('images-hero-container');
            const slideHtml = `
                <div class="hero-slide-item bg-gradient-to-r from-slate-50 to-purple-50 rounded-xl border-2 border-purple-200 p-5 shadow-sm hover:shadow-lg transition-all duration-200"
                    data-slide-index="${slideIndex}">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                        <!-- Aperçu -->
                        <div class="lg:col-span-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm font-bold rounded-full shadow-md">
                                        <i class="fas fa-grip-vertical mr-2 cursor-move drag-handle"></i>
                                        Slide #<span class="slide-number">${slideIndex + 1}</span>
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-slate-600 font-medium">Ordre:</label>
                                        <input type="number"
                                            name="images_hero_data[${slideIndex}][ordre]"
                                            value="${slideIndex + 1}"
                                            min="1"
                                            class="w-16 px-2 py-1 text-sm text-center font-semibold border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                    </div>
                                </div>

                                <div class="aspect-video bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center border-2 border-dashed border-purple-400" id="preview-${slideIndex}">
                                    <div class="text-center text-purple-600">
                                        <i class="fas fa-image text-5xl mb-2 opacity-50"></i>
                                        <p class="text-sm font-medium">Nouveau slide</p>
                                        <p class="text-xs text-purple-500">1920x1080px recommandé</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations -->
                        <div class="lg:col-span-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-heading text-purple-600 mr-1"></i>
                                        Titre du Slide <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        name="images_hero_data[${slideIndex}][titre]"
                                        placeholder="Ex: Bienvenue dans notre communauté de foi"
                                        required
                                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-medium">
                                    <p class="text-xs text-slate-500 mt-1">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                                        Ce titre peut être affiché en superposition sur l'image
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-align-left text-purple-600 mr-1"></i>
                                        Description / Texte d'accompagnement
                                        <span class="text-xs font-normal text-slate-500">(Optionnel)</span>
                                    </label>
                                    <textarea
                                        name="images_hero_data[${slideIndex}][description]"
                                        rows="2"
                                        placeholder="Texte descriptif qui pourra être affiché sous le titre du slide..."
                                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Maximum 150 caractères pour une bonne lisibilité
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-cloud-upload-alt text-purple-600 mr-1"></i>
                                        Image du Slide <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file"
                                        name="images_hero_files[${slideIndex}]"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        required
                                        onchange="previewSlideImage(this, ${slideIndex})"
                                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <div class="mt-2 text-xs text-slate-600 space-y-1">
                                        <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Format recommandé:</strong> JPG ou WebP (meilleure compression)</p>
                                        <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Dimensions optimales:</strong> 1920x1080px (Full HD, ratio 16:9)</p>
                                        <p><i class="fas fa-check-circle text-green-600 mr-1"></i><strong>Poids max:</strong> 5MB | <strong>Recommandé:</strong> 500KB-1MB</p>
                                    </div>
                                </div>

                                <div class="pt-4 border-t-2 border-slate-200">
                                    <div class="flex items-center justify-between flex-wrap gap-4">
                                        <label class="flex items-center cursor-pointer group">
                                            <div class="relative">
                                                <input type="checkbox"
                                                    name="images_hero_data[${slideIndex}][active]"
                                                    value="1"
                                                    checked
                                                    class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-400 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                            </div>
                                            <span class="ml-3 text-sm font-semibold text-slate-700 group-hover:text-purple-600 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Slide visible sur le site
                                            </span>
                                        </label>

                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                onclick="duplicateSlide(this)"
                                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                                                <i class="fas fa-copy mr-1"></i> Dupliquer
                                            </button>
                                            <button type="button"
                                                onclick="removeSlide(this)"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md">
                                                <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="images_hero_data[${slideIndex}][id]" value="">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', slideHtml);
            slideIndex++;
            updateSlidesCount();

            // Scroll vers le nouveau slide
            setTimeout(() => {
                container.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        function removeSlide(button) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce slide du carrousel ?')) {
                const slideItem = button.closest('.hero-slide-item');
                slideItem.style.opacity = '0';
                slideItem.style.transform = 'scale(0.95)';

                            setTimeout(() => {
                    slideItem.remove();
                    updateSlidesCount();
                    reindexSlides();
                    updateSlideNumbers();

                    // Afficher le message si aucun slide
                    const container = document.getElementById('images-hero-container');
                    if (container.children.length === 0) {
                        container.innerHTML = `
                            <div id="no-slides-message" class="text-center py-12 bg-gradient-to-br from-slate-50 to-purple-50 rounded-xl border-2 border-dashed border-purple-300">
                                <i class="fas fa-images text-6xl text-purple-300 mb-4"></i>
                                <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucun slide configuré</h3>
                                <p class="text-sm text-slate-600 mb-4">Créez votre premier slide pour le carrousel de la page d'accueil</p>
                                <button type="button" onclick="addImageHeroSlide()"
                                    class="inline-flex items-center px-5 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                                    <i class="fas fa-plus-circle mr-2"></i> Créer le premier slide
                                </button>
                            </div>
                        `;
                    }
                }, 300);
            }
        }

        function duplicateSlide(button) {
            const slideItem = button.closest('.hero-slide-item');
            const clonedSlide = slideItem.cloneNode(true);

            // Mettre à jour les indices
            const inputs = clonedSlide.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.name && input.name.includes('images_hero_data[')) {
                    input.name = input.name.replace(/images_hero_data\[\d+\]/, `images_hero_data[${slideIndex}]`);
                }
                if (input.name && input.name.includes('images_hero_files[')) {
                    input.name = input.name.replace(/images_hero_files\[\d+\]/, `images_hero_files[${slideIndex}]`);
                    input.required = true; // Nouvelle image requise pour la copie
                }
                // Vider l'ID pour créer un nouveau slide
                if (input.name && input.name.includes('[id]')) {
                    input.value = '';
                }
                // Vider l'URL existante
                if (input.name && input.name.includes('[url]')) {
                    input.remove();
                }
            });

            // Ajouter "Copie de" au titre
            const titreInput = clonedSlide.querySelector('input[name*="[titre]"]');
            if (titreInput && titreInput.value) {
                titreInput.value = 'Copie de ' + titreInput.value;
            }

            // Mettre à jour le numéro du slide
            clonedSlide.querySelector('.slide-number').textContent = slideIndex + 1;
            clonedSlide.dataset.slideIndex = slideIndex;

            slideItem.parentNode.insertBefore(clonedSlide, slideItem.nextSibling);
            slideIndex++;
            updateSlidesCount();

            // Scroll vers le slide dupliqué
            setTimeout(() => {
                clonedSlide.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        function reindexSlides() {
            const slides = document.querySelectorAll('.hero-slide-item');
            slides.forEach((slide, index) => {
                slide.dataset.slideIndex = index;
                const inputs = slide.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name && input.name.includes('images_hero_data[')) {
                        input.name = input.name.replace(/images_hero_data\[\d+\]/, `images_hero_data[${index}]`);
                    }
                    if (input.name && input.name.includes('images_hero_files[')) {
                        input.name = input.name.replace(/images_hero_files\[\d+\]/, `images_hero_files[${index}]`);
                    }
                });
            });
            slideIndex = slides.length;
        }

        function updateSlideNumbers() {
            const slides = document.querySelectorAll('.hero-slide-item');
            slides.forEach((slide, index) => {
                const numberSpan = slide.querySelector('.slide-number');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
        }

        function updateSlidesCount() {
            const count = document.querySelectorAll('.hero-slide-item').length;
            const countElement = document.getElementById('slides-count');
            if (countElement) {
                countElement.textContent = count;
            }
        }

        function sortSlidesByOrder() {
            const container = document.getElementById('images-hero-container');
            const slides = Array.from(container.querySelectorAll('.hero-slide-item'));

            slides.sort((a, b) => {
                const ordreA = parseInt(a.querySelector('input[name*="[ordre]"]').value) || 0;
                const ordreB = parseInt(b.querySelector('input[name*="[ordre]"]').value) || 0;
                return ordreA - ordreB;
            });

            container.innerHTML = '';
            slides.forEach(slide => container.appendChild(slide));
            reindexSlides();
            updateSlideNumbers();

            // Animation de confirmation
            slides.forEach((slide, index) => {
                setTimeout(() => {
                    slide.style.opacity = '0';
                    slide.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        slide.style.transition = 'all 0.3s ease';
                        slide.style.opacity = '1';
                        slide.style.transform = 'translateX(0)';
                    }, 50);
                }, index * 50);
            });
        }

        function previewSlideImage(input, index) {
            const file = input.files[0];
            if (file) {
                // Vérifier la taille
                if (file.size > 5 * 1024 * 1024) {
                    alert('⚠️ Attention: L\'image fait plus de 5MB. Pour de meilleures performances, il est recommandé de compresser l\'image à moins de 1MB.');
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById(`preview-${index}`) ||
                        input.closest('.hero-slide-item').querySelector('.aspect-video');

                    if (previewContainer) {
                        // Créer l'aperçu avec badge "Nouveau"
                        previewContainer.innerHTML = `
                            <div class="relative w-full h-full rounded-xl overflow-hidden">
                                <img src="${e.target.result}"
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                                    <div class="absolute bottom-2 left-2 right-2 text-white text-xs">
                                        <i class="fas fa-image mr-1"></i>Nouvelle image sélectionnée
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-bold bg-green-500 text-white rounded-full shadow-lg">
                                        <i class="fas fa-check-circle mr-1"></i>Nouveau
                                    </span>
                                </div>
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-black/70 text-white rounded backdrop-blur-sm">
                                        <i class="fas fa-file-image mr-1"></i>${(file.size / 1024 / 1024).toFixed(2)} MB
                                    </span>
                                </div>
                            </div>
                        `;
                    }

                    // Vérifier les dimensions de l'image
                    const img = new Image();
                    img.onload = function() {
                        const ratio = (this.width / this.height).toFixed(2);
                        const isOptimal = this.width >= 1920 && this.height >= 1080;
                        const is16_9 = Math.abs(ratio - 1.78) < 0.1; // 16:9 = 1.78

                        if (!isOptimal || !is16_9) {
                            const warning = document.createElement('div');
                            warning.className = 'mt-2 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-xs';
                            warning.innerHTML = `
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                                    <div class="text-yellow-800">
                                        <strong>Dimensions détectées:</strong> ${this.width}x${this.height}px (ratio ${ratio}:1)
                                        ${!is16_9 ? '<br>⚠️ L\'image n\'est pas au format 16:9. Elle pourrait être déformée ou recadrée.' : ''}
                                        ${!isOptimal ? '<br>⚠️ Dimensions inférieures à 1920x1080px. Qualité réduite sur grands écrans.' : ''}
                                    </div>
                                </div>
                            `;
                            input.parentElement.appendChild(warning);

                            // Supprimer l'avertissement après 10 secondes
                            setTimeout(() => warning.remove(), 10000);
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-mise à jour de l'ordre lors de la modification
        document.addEventListener('change', function(e) {
            if (e.target.name && e.target.name.includes('[ordre]')) {
                updateSlideNumbers();
            }
        });

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            updateSlidesCount();
            updateSlideNumbers();
        });
    </script>




    <script>

        let programmeIndex = <?php echo e(count($programmesData)); ?>;
        // let programmeIndex = <?php echo e($parametres->getProgrammes() ? count($parametres->getProgrammes()) : 0); ?>;

        function addProgramme() {
            const container = document.getElementById('programmes-container');
            const programmeHtml = `
                <div class="programme-item p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Titre et Description -->
                        <div class="md:col-span-6">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Titre du Programme <span class="text-red-500">*</span></label>
                                    <input type="text" name="programmes[${programmeIndex}][titre]" placeholder="Ex: Cultes Dominicaux" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                    <textarea name="programmes[${programmeIndex}][description]" rows="2" placeholder="Description du programme..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Icône et Type -->
                        <div class="md:col-span-3">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Icône FontAwesome</label>
                                    <input type="text" name="programmes[${programmeIndex}][icone]" value="fas fa-calendar" placeholder="fas fa-praying-hands" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type d'horaire</label>
                                    <select name="programmes[${programmeIndex}][type_horaire]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="regulier">Régulier</option>
                                        <option value="sur_rendez_vous">Sur rendez-vous</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="ponctuel">Ponctuel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Horaires -->
                        <div class="md:col-span-3">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                                    <select name="programmes[${programmeIndex}][jour]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Lundi">Lundi</option>
                                        <option value="Mardi">Mardi</option>
                                        <option value="Mercredi">Mercredi</option>
                                        <option value="Jeudi">Jeudi</option>
                                        <option value="Vendredi">Vendredi</option>
                                        <option value="Samedi">Samedi</option>
                                        <option value="Dimanche">Dimanche</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">Début</label>
                                        <input type="time" name="programmes[${programmeIndex}][heure_debut]" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">Fin</label>
                                        <input type="time" name="programmes[${programmeIndex}][heure_fin]" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Texte d'affichage</label>
                                    <input type="text" name="programmes[${programmeIndex}][horaire_texte]" placeholder="Ex: Dimanche : 9h00 - 11h30" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Options et Actions -->
                        <div class="md:col-span-12">
                            <div class="flex items-center justify-between pt-4 border-t border-slate-300">
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="programmes[${programmeIndex}][est_public]" value="1" checked class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-slate-700">Public</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="programmes[${programmeIndex}][est_actif]" value="1" checked class="rounded border-slate-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-slate-700">Actif</span>
                                    </label>
                                    <div class="text-sm text-slate-500">
                                        Ordre: <input type="number" name="programmes[${programmeIndex}][ordre]" value="${programmeIndex + 1}" min="1" class="w-16 px-2 py-1 border border-slate-300 rounded text-xs">
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="duplicateProgramme(this)" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-copy mr-1"></i> Dupliquer
                                    </button>
                                    <button type="button" onclick="removeProgramme(this)" class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Champ caché pour l'ID (nouveau programme) -->
                        <input type="hidden" name="programmes[${programmeIndex}][id]" value="">
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', programmeHtml);
            programmeIndex++;
            updateProgrammesCount();
        }

        function removeProgramme(button) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')) {
                button.closest('.programme-item').remove();
                updateProgrammesCount();
                reindexProgrammes();
            }
        }

        function duplicateProgramme(button) {
            const programmeItem = button.closest('.programme-item');
            const clonedItem = programmeItem.cloneNode(true);

            // Mettre à jour les names avec le nouvel index
            const inputs = clonedItem.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.name && input.name.includes('programmes[')) {
                    input.name = input.name.replace(/programmes\[\d+\]/, `programmes[${programmeIndex}]`);
                }
                // Vider l'ID pour en créer un nouveau
                if (input.name && input.name.includes('[id]')) {
                    input.value = '';
                }
            });

            // Ajouter "Copie de" au titre
            const titreInput = clonedItem.querySelector('input[name*="[titre]"]');
            if (titreInput && titreInput.value) {
                titreInput.value = 'Copie de ' + titreInput.value;
            }

            programmeItem.parentNode.insertBefore(clonedItem, programmeItem.nextSibling);
            programmeIndex++;
            updateProgrammesCount();
        }

        function reindexProgrammes() {
            const programmeItems = document.querySelectorAll('.programme-item');
            programmeItems.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name && input.name.includes('programmes[')) {
                        input.name = input.name.replace(/programmes\[\d+\]/, `programmes[${index}]`);
                    }
                });
            });
            programmeIndex = programmeItems.length;
        }

        function updateProgrammesCount() {
            const count = document.querySelectorAll('.programme-item').length;
            document.getElementById('programmes-count').textContent = count;
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
        document.querySelector('form').addEventListener('submit', function (e) {
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

            // Vérifier qu'au moins un programme a un titre
            const programmeTitres = document.querySelectorAll('input[name*="[titre]"]');
            let hasValidProgramme = false;
            programmeTitres.forEach(input => {
                if (input.value.trim()) {
                    hasValidProgramme = true;
                }
            });

            if (hasErrors) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                document.querySelector('.border-red-500').scrollIntoView({ behavior: 'smooth' });
            }
        });

        // Prévisualisation des images
        document.getElementById('logo').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
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

        // Validation des URLs
        document.querySelectorAll('input[type="url"]').forEach(input => {
            input.addEventListener('blur', function () {
                if (this.value && !this.value.match(/^https?:\/\/.+/)) {
                    this.setCustomValidity('Veuillez entrer une URL valide commençant par http:// ou https://');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        // Auto-génération du texte d'affichage
        document.addEventListener('change', function (e) {
            if (e.target.name && (e.target.name.includes('[jour]') || e.target.name.includes('[heure_debut]') || e.target.name.includes('[heure_fin]'))) {
                const programmeContainer = e.target.closest('.programme-item');
                const jour = programmeContainer.querySelector('select[name*="[jour]"]').value;
                const heureDebut = programmeContainer.querySelector('input[name*="[heure_debut]"]').value;
                const heureFin = programmeContainer.querySelector('input[name*="[heure_fin]"]').value;
                const horaireTexte = programmeContainer.querySelector('input[name*="[horaire_texte]"]');

                if (jour && heureDebut && heureFin && !horaireTexte.value) {
                    horaireTexte.value = `${jour} : ${heureDebut} - ${heureFin}`;
                }
            }
        });

        // Gestion du type d'horaire
        document.addEventListener('change', function (e) {
            if (e.target.name && e.target.name.includes('[type_horaire]')) {
                const programmeContainer = e.target.closest('.programme-item');
                const typeHoraire = e.target.value;
                const horaireTexte = programmeContainer.querySelector('input[name*="[horaire_texte]"]');

                if (typeHoraire === 'sur_rendez_vous' && !horaireTexte.value) {
                    horaireTexte.value = 'Sur rendez-vous';
                } else if (typeHoraire === 'permanent' && !horaireTexte.value) {
                    horaireTexte.value = 'Actions permanentes';
                }
            }
        });

        // Auto-sauvegarde en brouillon (optionnel)
        let autoSaveTimeout;
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function () {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    const formData = new FormData(document.querySelector('form'));
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    localStorage.setItem('parametres_draft', JSON.stringify(data));
                }, 2000);
            });
        });

        // Nettoyer le brouillon après soumission réussie
        document.querySelector('form').addEventListener('submit', function () {
            setTimeout(() => {
                localStorage.removeItem('parametres_draft');
            }, 1000);
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/parametres/edit.blade.php ENDPATH**/ ?>