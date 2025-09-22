<?php $__env->startSection('title', 'Créer un membres'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Membres</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo e(route('private.users.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Membres
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-users text-slate-500">Ajouter un nouveau membre à votre communauté - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- En-tête avec gradient -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 px-6 sm:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Créer un nouvel membres</h2>
                        <p class="text-indigo-100 mt-2 text-sm sm:text-base">Remplissez les informations ci-dessous pour créer un compte</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?php echo e(route('private.users.index')); ?>"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10border border-white/20 rounded-xl font-medium text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="<?php echo e(route('private.users.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?php echo csrf_field(); ?>

            <!-- Section : Informations personnelles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        Informations personnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="prenom" class="block text-sm font-medium text-slate-700">Prénom *</label>
                            <input type="text" name="prenom" id="prenom" value="<?php echo e(old('prenom')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="nom" class="block text-sm font-medium text-slate-700">Nom *</label>
                            <input type="text" name="nom" id="nom" value="<?php echo e(old('nom')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="date_naissance" class="block text-sm font-medium text-slate-700">Date de naissance</label>
                            <input type="date" name="date_naissance" id="date_naissance" value="<?php echo e(old('date_naissance')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['date_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['date_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="sexe" class="block text-sm font-medium text-slate-700">Sexe *</label>
                            <select name="sexe" id="sexe" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['sexe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Sélectionner...</option>
                                <option value="masculin" <?php echo e(old('sexe') === 'masculin' ? 'selected' : ''); ?>>Masculin</option>
                                <option value="feminin" <?php echo e(old('sexe') === 'feminin' ? 'selected' : ''); ?>>Féminin</option>
                            </select>
                            <?php $__errorArgs = ['sexe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="statut_matrimonial" class="block text-sm font-medium text-slate-700">Statut matrimonial</label>
                            <select name="statut_matrimonial" id="statut_matrimonial"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="">Sélectionner...</option>
                                <option value="celibataire" <?php echo e(old('statut_matrimonial') === 'celibataire' ? 'selected' : ''); ?>>Célibataire</option>
                                <option value="marie" <?php echo e(old('statut_matrimonial') === 'marie' ? 'selected' : ''); ?>>Marié(e)</option>
                                <option value="divorce" <?php echo e(old('statut_matrimonial') === 'divorce' ? 'selected' : ''); ?>>Divorcé(e)</option>
                                <option value="veuf" <?php echo e(old('statut_matrimonial') === 'veuf' ? 'selected' : ''); ?>>Veuf/Veuve</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="nombre_enfants" class="block text-sm font-medium text-slate-700">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfants" id="nombre_enfants" value="<?php echo e(old('nombre_enfants', 0)); ?>" min="0"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="photo_profil" class="block text-sm font-medium text-slate-700">Photo de profil</label>
                            <input type="file" name="photo_profil" id="photo_profil" accept="image/*"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                            <p class="mt-1 text-sm text-slate-500">Formats acceptés: JPG, PNG. Taille max: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Contact -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        Informations de contact
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="telephone_1" class="block text-sm font-medium text-slate-700">Téléphone principal *</label>
                            <input type="tel" name="telephone_1" id="telephone_1" value="<?php echo e(old('telephone_1')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['telephone_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['telephone_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="telephone_2" class="block text-sm font-medium text-slate-700">Téléphone secondaire</label>
                            <input type="tel" name="telephone_2" id="telephone_2" value="<?php echo e(old('telephone_2')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="email" class="block text-sm font-medium text-slate-700">Email *</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Adresse -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        Adresse
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label for="adresse_ligne_1" class="block text-sm font-medium text-slate-700">Adresse ligne 1 *</label>
                            <input type="text" name="adresse_ligne_1" id="adresse_ligne_1" value="<?php echo e(old('adresse_ligne_1')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['adresse_ligne_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['adresse_ligne_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="adresse_ligne_2" class="block text-sm font-medium text-slate-700">Adresse ligne 2</label>
                            <input type="text" name="adresse_ligne_2" id="adresse_ligne_2" value="<?php echo e(old('adresse_ligne_2')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="ville" class="block text-sm font-medium text-slate-700">Ville *</label>
                            <input type="text" name="ville" id="ville" value="<?php echo e(old('ville')); ?>" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="code_postal" class="block text-sm font-medium text-slate-700">Code postal</label>
                            <input type="text" name="code_postal" id="code_postal" value="<?php echo e(old('code_postal')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="region" class="block text-sm font-medium text-slate-700">Région</label>
                            <input type="text" name="region" id="region" value="<?php echo e(old('region')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="pays" class="block text-sm font-medium text-slate-700">Pays</label>
                            <select name="pays" id="pays"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="CI" <?php echo e(old('pays', 'CI') === 'CI' ? 'selected' : ''); ?>>Côte d'Ivoire</option>
                                <option value="BF" <?php echo e(old('pays') === 'BF' ? 'selected' : ''); ?>>Burkina Faso</option>
                                <option value="ML" <?php echo e(old('pays') === 'ML' ? 'selected' : ''); ?>>Mali</option>
                                <option value="GH" <?php echo e(old('pays') === 'GH' ? 'selected' : ''); ?>>Ghana</option>
                                <option value="SN" <?php echo e(old('pays') === 'SN' ? 'selected' : ''); ?>>Sénégal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Informations professionnelles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-briefcase text-white"></i>
                        </div>
                        Informations professionnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="profession" class="block text-sm font-medium text-slate-700">Profession</label>
                            <input type="text" name="profession" id="profession" value="<?php echo e(old('profession')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="employeur" class="block text-sm font-medium text-slate-700">Employeur</label>
                            <input type="text" name="employeur" id="employeur" value="<?php echo e(old('employeur')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Informations d'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-church text-white"></i>
                        </div>
                        Informations d'église
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="classe_id" class="block text-sm font-medium text-slate-700">Classe</label>
                            <select name="classe_id" id="classe_id"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="" >Aucune classe</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($classe->id); ?>" <?php echo e(old('classe_id') == $classe->id ? 'selected' : ''); ?>>
                                    <?php echo e($classe->nom); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['classe_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="date_adhesion" class="block text-sm font-medium text-slate-700">Date d'adhésion</label>
                            <input type="date" name="date_adhesion" id="date_adhesion" value="<?php echo e(old('date_adhesion')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                            <?php $__errorArgs = ['date_adhesion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="statut_membre" class="block text-sm font-medium text-slate-700">Statut membre *</label>
                            <select name="statut_membre" id="statut_membre" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['statut_membre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Sélectionner...</option>
                                <option value="actif" <?php echo e(old('statut_membre') === 'actif' ? 'selected' : ''); ?>>Actif</option>
                                <option value="inactif" <?php echo e(old('statut_membre') === 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                                <option value="visiteur" <?php echo e(old('statut_membre') === 'visiteur' ? 'selected' : ''); ?>>Visiteur</option>
                                <option value="nouveau_converti" <?php echo e(old('statut_membre') === 'nouveau_converti' ? 'selected' : ''); ?>>Nouveau converti</option>
                            </select>
                            <?php $__errorArgs = ['statut_membre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="statut_bapteme" class="block text-sm font-medium text-slate-700">Statut baptême *</label>
                            <select name="statut_bapteme" id="statut_bapteme" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['statut_bapteme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Sélectionner...</option>
                                <option value="non_baptise" <?php echo e(old('statut_bapteme') === 'non_baptise' ? 'selected' : ''); ?>>Non baptisé</option>
                                <option value="baptise" <?php echo e(old('statut_bapteme') === 'baptise' ? 'selected' : ''); ?>>Baptisé</option>
                                <option value="confirme" <?php echo e(old('statut_bapteme') === 'confirme' ? 'selected' : ''); ?>>Confirmé</option>
                            </select>
                            <?php $__errorArgs = ['statut_bapteme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div id="date_bapteme_container" style="display: none;" class="space-y-2">
                            <label for="date_bapteme" class="block text-sm font-medium text-slate-700">Date de baptême</label>
                            <input type="date" name="date_bapteme" id="date_bapteme" value="<?php echo e(old('date_bapteme')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="eglise_precedente" class="block text-sm font-medium text-slate-700">Église précédente</label>
                            <input type="text" name="eglise_precedente" id="eglise_precedente" value="<?php echo e(old('eglise_precedente')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Contact d'urgence -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        Contact d'urgence
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="contact_urgence_nom" class="block text-sm font-medium text-slate-700">Nom du contact</label>
                            <input type="text" name="contact_urgence_nom" id="contact_urgence_nom" value="<?php echo e(old('contact_urgence_nom')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="contact_urgence_telephone" class="block text-sm font-medium text-slate-700">Téléphone du contact</label>
                            <input type="tel" name="contact_urgence_telephone" id="contact_urgence_telephone" value="<?php echo e(old('contact_urgence_telephone')); ?>"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="contact_urgence_relation" class="block text-sm font-medium text-slate-700">Relation</label>
                            <input type="text" name="contact_urgence_relation" id="contact_urgence_relation" value="<?php echo e(old('contact_urgence_relation')); ?>"
                                   placeholder="Ex: Époux/Épouse, Parent, Ami..."
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Compte et rôles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        Compte et rôles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <p class="mt-1 text-sm text-slate-500">
                                Le mot de passe doit contenir au moins 8 caractères, incluant des majuscules, minuscules et chiffres.
                            </p>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-4">Rôles</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-4 border-2 border-slate-200 rounded-xl hover:bg-slate-50 hover:border-indigo-300 transition-all duration-200 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="<?php echo e($role->id); ?>"
                                           <?php echo e(in_array($role->id, old('roles', [])) ? 'checked' : ''); ?>

                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($role->name); ?></div>
                                        <div class="text-sm text-slate-500"><?php echo e($role->description); ?></div>
                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <p class="mt-2 text-sm text-slate-500">
                                Si aucun rôle n'est sélectionné, un rôle par défaut sera attribué selon le statut membre.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="<?php echo e(route('private.users.index')); ?>" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Créer l'membres
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statutBapteme = document.getElementById('statut_bapteme');
    const dateBaptemeContainer = document.getElementById('date_bapteme_container');

    function toggleDateBapteme() {
        const value = statutBapteme.value;
        if (value === 'baptise' || value === 'confirme') {
            dateBaptemeContainer.style.display = 'block';
            dateBaptemeContainer.classList.add('animate-fade-in');
            document.getElementById('date_bapteme').required = true;
        } else {
            dateBaptemeContainer.style.display = 'none';
            dateBaptemeContainer.classList.remove('animate-fade-in');
            document.getElementById('date_bapteme').required = false;
        }
    }

    statutBapteme.addEventListener('change', toggleDateBapteme);
    toggleDateBapteme(); // Check initial state

    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation de focus sur les champs
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.space-y-2').classList.add('scale-102');
        });

        input.addEventListener('blur', function() {
            this.closest('.space-y-2').classList.remove('scale-102');
        });
    });

    // Validation en temps réel pour le mot de passe
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            const parent = this.closest('.space-y-2');
            let strengthIndicator = parent.querySelector('.password-strength');

            if (!strengthIndicator && value.length > 0) {
                strengthIndicator = document.createElement('div');
                strengthIndicator.className = 'password-strength mt-2 text-xs';
                parent.appendChild(strengthIndicator);
            }

            if (value.length === 0) {
                if (strengthIndicator) strengthIndicator.remove();
                return;
            }

            let strength = 0;
            let messages = [];

            if (value.length >= 8) strength++; else messages.push('8 caractères min');
            if (/[A-Z]/.test(value)) strength++; else messages.push('1 majuscule');
            if (/[a-z]/.test(value)) strength++; else messages.push('1 minuscule');
            if (/[0-9]/.test(value)) strength++; else messages.push('1 chiffre');

            const colors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500'];
            const labels = ['Faible', 'Moyen', 'Bon', 'Fort'];

            strengthIndicator.className = `password-strength mt-2 text-xs ${colors[strength - 1] || 'text-red-500'}`;
            strengthIndicator.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">Force: ${labels[strength - 1] || 'Très faible'}</span>
                    ${messages.length > 0 ? `<span class="text-slate-500">Manque: ${messages.join(', ')}</span>` : ''}
                </div>
            `;
        });
    }
});

// Style CSS inline pour les animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    .scale-102 {
        transform: scale(1.02);
        transition: transform 0.2s ease;
    }
`;
document.head.appendChild(style);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/users/create.blade.php ENDPATH**/ ?>