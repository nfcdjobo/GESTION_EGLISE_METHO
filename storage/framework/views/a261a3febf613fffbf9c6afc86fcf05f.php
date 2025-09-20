<?php $__env->startSection('title', 'Modifier le Culte'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Culte</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.cultes.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-church mr-2"></i>
                        Cultes
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="<?php echo e(route('private.cultes.show', $culte)); ?>" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors"><?php echo e($culte->titre); ?></a>
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

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.update')): ?>
    <form action="<?php echo e(route('private.cultes.update', $culte)); ?>" method="POST" enctype="multipart/form-data" id="culteForm" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                    Titre du culte <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre" name="titre" value="<?php echo e(old('titre', $culte->titre)); ?>" required maxlength="200" placeholder="Ex: Culte du dimanche matin"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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

                            <div>
                                <label for="programme_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Programme <span class="text-red-500">*</span>
                                </label>
                                <select id="programme_id" name="programme_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['programme_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un programme</option>
                                    <?php $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($programme->id); ?>" <?php echo e(old('programme_id', $culte->programme_id) == $programme->id ? 'selected' : ''); ?>>
                                            <?php echo e($programme->nom_programme); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['programme_id'];
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
                            <div class="<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="description" name="description" rows="3" placeholder="Description du culte"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('description', $culte->description)); ?></textarea>
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="type_culte" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de culte <span class="text-red-500">*</span>
                                </label>
                                <select id="type_culte" name="type_culte" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['type_culte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le type</option>
                                    <?php $__currentLoopData = \App\Models\Culte::TYPE_CULTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('type_culte', $culte->type_culte) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['type_culte'];
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
                                    <?php $__currentLoopData = \App\Models\Culte::CATEGORIE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('categorie', $culte->categorie) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
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
                                <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <select id="statut" name="statut" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__currentLoopData = \App\Models\Culte::STATUT; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('statut', $culte->statut) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['statut'];
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

                <!-- Date, heure et lieu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Date, Heure et Lieu
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date_culte" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date du culte <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_culte" name="date_culte" value="<?php echo e(old('date_culte', $culte->date_culte->format('Y-m-d'))); ?>" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_culte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['date_culte'];
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
                                <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Heure de début <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="heure_debut" name="heure_debut" value="<?php echo e(old('heure_debut', $culte->heure_debut ? \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') : '')); ?>" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['heure_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['heure_debut'];
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
                                <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin (prévue)</label>
                                <input type="time" id="heure_fin" name="heure_fin" value="<?php echo e(old('heure_fin', $culte->heure_fin ? \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') : '')); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['heure_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['heure_fin'];
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

                        <?php if($culte->statut === 'termine' && ($culte->heure_debut_reelle || $culte->heure_fin_reelle)): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-green-50 rounded-xl border border-green-200">
                                <div>
                                    <label for="heure_debut_reelle" class="block text-sm font-medium text-slate-700 mb-2">Heure de début réelle</label>
                                    <input type="time" id="heure_debut_reelle" name="heure_debut_reelle" value="<?php echo e(old('heure_debut_reelle', $culte->heure_debut_reelle ? \Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i') : '')); ?>"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>

                                <div>
                                    <label for="heure_fin_reelle" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin réelle</label>
                                    <input type="time" id="heure_fin_reelle" name="heure_fin_reelle" value="<?php echo e(old('heure_fin_reelle', $culte->heure_fin_reelle ? \Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i') : '')); ?>"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lieu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lieu" name="lieu" value="<?php echo e(old('lieu', $culte->lieu)); ?>" required maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['lieu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['lieu'];
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
                                <label for="capacite_prevue" class="block text-sm font-medium text-slate-700 mb-2">Capacité prévue</label>
                                <input type="number" id="capacite_prevue" name="capacite_prevue" value="<?php echo e(old('capacite_prevue', $culte->capacite_prevue)); ?>" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['capacite_prevue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['capacite_prevue'];
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
                            <label for="adresse_lieu" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète (si lieu externe)</label>
                            <div class="<?php $__errorArgs = ['adresse_lieu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="adresse_lieu" name="adresse_lieu" rows="2" placeholder="Adresse complète du lieu si différent de l'église"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('adresse_lieu', $culte->adresse_lieu)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['adresse_lieu'];
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

                <!-- Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Responsables et Intervenants
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pasteur_principal_id" class="block text-sm font-medium text-slate-700 mb-2">Pasteur principal</label>
                                <select id="pasteur_principal_id" name="pasteur_principal_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['pasteur_principal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un pasteur</option>
                                    <?php $__currentLoopData = $pasteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pasteur->id); ?>" <?php echo e(old('pasteur_principal_id', $culte->pasteur_principal_id) == $pasteur->id ? 'selected' : ''); ?>>
                                            <?php echo e($pasteur->nom); ?> <?php echo e($pasteur->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['pasteur_principal_id'];
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
                                <label for="predicateur_id" class="block text-sm font-medium text-slate-700 mb-2">Prédicateur</label>
                                <select id="predicateur_id" name="predicateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['predicateur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un prédicateur</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(old('predicateur_id', $culte->predicateur_id) == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->nom); ?> <?php echo e($user->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['predicateur_id'];
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_culte_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable du culte</label>
                                <select id="responsable_culte_id" name="responsable_culte_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['responsable_culte_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un responsable</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(old('responsable_culte_id', $culte->responsable_culte_id) == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->nom); ?> <?php echo e($user->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['responsable_culte_id'];
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
                                <label for="dirigeant_louange_id" class="block text-sm font-medium text-slate-700 mb-2">Dirigeant de louange</label>
                                <select id="dirigeant_louange_id" name="dirigeant_louange_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['dirigeant_louange_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un dirigeant</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(old('dirigeant_louange_id', $culte->dirigeant_louange_id) == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->nom); ?> <?php echo e($user->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['dirigeant_louange_id'];
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

                <!-- Message et prédication -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bible text-amber-600 mr-2"></i>
                            Message et Prédication
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="titre_message" class="block text-sm font-medium text-slate-700 mb-2">Titre du message</label>
                            <input type="text" id="titre_message" name="titre_message" value="<?php echo e(old('titre_message', $culte->titre_message)); ?>" maxlength="300" placeholder="Titre de la prédication"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['titre_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['titre_message'];
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
                            <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage biblique principal</label>
                            <input type="text" id="passage_biblique" name="passage_biblique" value="<?php echo e(old('passage_biblique', $culte->passage_biblique)); ?>" maxlength="500" placeholder="Ex: Jean 3:16-17"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['passage_biblique'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['passage_biblique'];
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
                            <label for="resume_message" class="block text-sm font-medium text-slate-700 mb-2">Résumé du message</label>
                            <div class="<?php $__errorArgs = ['resume_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="resume_message" name="resume_message" rows="4" placeholder="Résumé de la prédication"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('resume_message', $culte->resume_message)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['resume_message'];
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
                            <label for="plan_message" class="block text-sm font-medium text-slate-700 mb-2">Plan du message</label>
                            <div class="<?php $__errorArgs = ['plan_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="plan_message" name="plan_message" rows="4" placeholder="Plan détaillé du message"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('plan_message', $culte->plan_message)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['plan_message'];
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

                <!-- Participation et statistiques (si culte terminé) -->
                <?php if($culte->statut === 'termine' || $culte->nombre_participants): ?>
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                Participation et Statistiques
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label for="nombre_participants" class="block text-sm font-medium text-slate-700 mb-2">Participants total</label>
                                    <input type="number" id="nombre_participants" name="nombre_participants" value="<?php echo e(old('nombre_participants', $culte->nombre_participants)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_participants'];
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
                                    <label for="nombre_adultes" class="block text-sm font-medium text-slate-700 mb-2">Adultes</label>
                                    <input type="number" id="nombre_adultes" name="nombre_adultes" value="<?php echo e(old('nombre_adultes', $culte->nombre_adultes)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_adultes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_adultes'];
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
                                    <label for="nombre_jeunes" class="block text-sm font-medium text-slate-700 mb-2">Jeunes</label>
                                    <input type="number" id="nombre_jeunes" name="nombre_jeunes" value="<?php echo e(old('nombre_jeunes', $culte->nombre_jeunes)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_jeunes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_jeunes'];
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
                                    <label for="nombre_enfants" class="block text-sm font-medium text-slate-700 mb-2">Enfants</label>
                                    <input type="number" id="nombre_enfants" name="nombre_enfants" value="<?php echo e(old('nombre_enfants', $culte->nombre_enfants)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_enfants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_enfants'];
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
                                    <label for="nombre_nouveaux" class="block text-sm font-medium text-slate-700 mb-2">Nouveaux visiteurs</label>
                                    <input type="number" id="nombre_nouveaux" name="nombre_nouveaux" value="<?php echo e(old('nombre_nouveaux', $culte->nombre_nouveaux)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_nouveaux'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_nouveaux'];
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
                                    <label for="nombre_conversions" class="block text-sm font-medium text-slate-700 mb-2">Conversions</label>
                                    <input type="number" id="nombre_conversions" name="nombre_conversions" value="<?php echo e(old('nombre_conversions', $culte->nombre_conversions)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_conversions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_conversions'];
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
                                    <label for="nombre_baptemes" class="block text-sm font-medium text-slate-700 mb-2">Baptêmes</label>
                                    <input type="number" id="nombre_baptemes" name="nombre_baptemes" value="<?php echo e(old('nombre_baptemes', $culte->nombre_baptemes)); ?>" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_baptemes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['nombre_baptemes'];
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
                <?php endif; ?>

                <!-- Options avancées -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-cyan-600 mr-2"></i>
                            Options et Paramètres
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="est_public" name="est_public" value="1" <?php echo e(old('est_public', $culte->est_public) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_public" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte ouvert au public
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="necessite_invitation" name="necessite_invitation" value="1" <?php echo e(old('necessite_invitation', $culte->necessite_invitation) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="necessite_invitation" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte sur invitation uniquement
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" <?php echo e(old('diffusion_en_ligne', $culte->diffusion_en_ligne) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                        Diffusion en ligne
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_enregistre" name="est_enregistre" value="1" <?php echo e(old('est_enregistre', $culte->est_enregistre) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_enregistre" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte enregistré (audio/vidéo)
                                    </label>
                                </div>
                            </div>

                            <div id="liens_section" class="space-y-4 <?php echo e(!$culte->diffusion_en_ligne && !$culte->est_enregistre ? 'hidden' : ''); ?>">
                                <div>
                                    <label for="lien_diffusion_live" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion en direct</label>
                                    <input type="url" id="lien_diffusion_live" name="lien_diffusion_live" value="<?php echo e(old('lien_diffusion_live', $culte->lien_diffusion_live)); ?>" placeholder="https://..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['lien_diffusion_live'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['lien_diffusion_live'];
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
                                    <label for="lien_enregistrement_video" class="block text-sm font-medium text-slate-700 mb-2">Lien enregistrement vidéo</label>
                                    <input type="url" id="lien_enregistrement_video" name="lien_enregistrement_video" value="<?php echo e(old('lien_enregistrement_video', $culte->lien_enregistrement_video)); ?>" placeholder="https://..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['lien_enregistrement_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['lien_enregistrement_video'];
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
                                    <label for="lien_enregistrement_audio" class="block text-sm font-medium text-slate-700 mb-2">Lien enregistrement audio</label>
                                    <input type="url" id="lien_enregistrement_audio" name="lien_enregistrement_audio" value="<?php echo e(old('lien_enregistrement_audio', $culte->lien_enregistrement_audio)); ?>" placeholder="https://..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['lien_enregistrement_audio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['lien_enregistrement_audio'];
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
                </div>

                <!-- Notes et commentaires -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-comment-alt text-indigo-600 mr-2"></i>
                            Notes et Commentaires
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
    <label for="notes_pasteur" class="block text-sm font-medium text-slate-700 mb-2">Notes du pasteur</label>
    <div class="<?php $__errorArgs = ['notes_pasteur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <textarea id="notes_pasteur" name="notes_pasteur" rows="3" placeholder="Notes et observations du pasteur"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('notes_pasteur', $culte->notes_pasteur)); ?></textarea>
    </div>
    <?php $__errorArgs = ['notes_pasteur'];
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
    <label for="notes_organisateur" class="block text-sm font-medium text-slate-700 mb-2">Notes de l'organisateur</label>
    <div class="<?php $__errorArgs = ['notes_organisateur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <textarea id="notes_organisateur" name="notes_organisateur" rows="3" placeholder="Notes organisationnelles"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('notes_organisateur', $culte->notes_organisateur)); ?></textarea>
    </div>
    <?php $__errorArgs = ['notes_organisateur'];
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
    <label for="points_forts" class="block text-sm font-medium text-slate-700 mb-2">Points forts</label>
    <div class="<?php $__errorArgs = ['points_forts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <textarea id="points_forts" name="points_forts" rows="3" placeholder="Points positifs du culte"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('points_forts', $culte->points_forts)); ?></textarea>
    </div>
    <?php $__errorArgs = ['points_forts'];
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
    <label for="points_amelioration" class="block text-sm font-medium text-slate-700 mb-2">Points d'amélioration</label>
    <div class="<?php $__errorArgs = ['points_amelioration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <textarea id="points_amelioration" name="points_amelioration" rows="3" placeholder="Points à améliorer"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('points_amelioration', $culte->points_amelioration)); ?></textarea>
    </div>
    <?php $__errorArgs = ['points_amelioration'];
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
    <label for="temoignages" class="block text-sm font-medium text-slate-700 mb-2">Témoignages</label>
    <div class="<?php $__errorArgs = ['temoignages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <textarea id="temoignages" name="temoignages" rows="3" placeholder="Témoignages recueillis pendant le culte"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('temoignages', $culte->temoignages)); ?></textarea>
    </div>
    <?php $__errorArgs = ['temoignages'];
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

            <!-- Sidebar - Aperçu et aide -->
            <div class="space-y-6">
                <!-- Statut actuel -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Statut Actuel
                        </h2>
                    </div>
                    <div class="p-6 text-center">
                        <?php
                            $statutColors = [
                                'planifie' => 'bg-blue-100 text-blue-800',
                                'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                'en_cours' => 'bg-orange-100 text-orange-800',
                                'termine' => 'bg-green-100 text-green-800',
                                'annule' => 'bg-red-100 text-red-800',
                                'reporte' => 'bg-purple-100 text-purple-800'
                            ];
                        ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium <?php echo e($statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($culte->statut_libelle); ?>

                        </span>
                        <p class="text-sm text-slate-500 mt-2">Créé le <?php echo e($culte->created_at->format('d/m/Y à H:i')); ?></p>
                        <p class="text-sm text-slate-500">Modifié le <?php echo e($culte->updated_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                </div>

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
                            <span class="text-sm font-medium text-slate-700">Titre:</span>
                            <span id="preview-titre" class="text-sm text-slate-900 font-semibold"><?php echo e($culte->titre); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="text-sm text-slate-600"><?php echo e($culte->type_culte_libelle); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Date:</span>
                            <span id="preview-date" class="text-sm text-slate-600"><?php echo e($culte->date_culte->format('d/m/Y')); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Heure:</span>
                            <span id="preview-heure" class="text-sm text-slate-600"><?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?><?php echo e($culte->heure_fin ? ' - ' . \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') : ''); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Lieu:</span>
                            <span id="preview-lieu" class="text-sm text-slate-600"><?php echo e($culte->lieu); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span id="preview-statut" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800'); ?>"><?php echo e($culte->statut_libelle); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?php echo e(route('private.cultes.show', $culte)); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i> Voir les détails
                        </a>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                            <button type="button" onclick="openDuplicateModal('<?php echo e($culte->id); ?>')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                <i class="fas fa-copy mr-2"></i> Dupliquer
                            </button>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.delete')): ?>
                            <?php if($culte->statut !== 'en_cours'): ?>
                                <button type="button" onclick="deleteCulte('<?php echo e($culte->id); ?>')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </button>
                            <?php endif; ?>
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
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                    <a href="<?php echo e(route('private.cultes.show', $culte)); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- Modal duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le culte</h3>
            <form id="duplicateForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="duplicate_culte_id" name="culte_id" value="<?php echo e($culte->id); ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                        <input type="date" name="nouvelle_date" id="nouvelle_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle heure</label>
                        <input type="time" name="nouvelle_heure" id="nouvelle_heure" value="<?php echo e($culte->heure_debut ? \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') : ''); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau titre (optionnel)</label>
                        <input type="text" name="nouveau_titre" id="nouveau_titre" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Laisser vide pour ajouter (Copie)">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.duplicate')): ?>
            <button type="button" onclick="duplicateCulte()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre').value || '<?php echo e($culte->titre); ?>';
    const typeSelect = document.getElementById('type_culte');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '<?php echo e($culte->type_culte_libelle); ?>';
    const date = document.getElementById('date_culte').value || '<?php echo e($culte->date_culte->format('Y-m-d')); ?>';
    const heureDebut = document.getElementById('heure_debut').value || '';
    const heureFin = document.getElementById('heure_fin').value || '';
    const lieu = document.getElementById('lieu').value || '<?php echo e($culte->lieu); ?>';
    const statutSelect = document.getElementById('statut');
    const statut = statutSelect.options[statutSelect.selectedIndex]?.text || '<?php echo e($culte->statut_libelle); ?>';

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-date').textContent = date !== '' ? new Date(date).toLocaleDateString('fr-FR') : '';
    document.getElementById('preview-heure').textContent = heureDebut + (heureFin ? ' - ' + heureFin : '');
    document.getElementById('preview-lieu').textContent = lieu;
    document.getElementById('preview-statut').textContent = statut;
}

// Gestion des liens de diffusion
function toggleLinksSection() {
    const diffusionCheckbox = document.getElementById('diffusion_en_ligne');
    const enregistreCheckbox = document.getElementById('est_enregistre');
    const linksSection = document.getElementById('liens_section');

    if (diffusionCheckbox.checked || enregistreCheckbox.checked) {
        linksSection.classList.remove('hidden');
    } else {
        linksSection.classList.add('hidden');
    }
}

// Gestion des options exclusives
document.getElementById('est_public').addEventListener('change', function() {
    const invitationCheckbox = document.getElementById('necessite_invitation');
    if (this.checked) {
        invitationCheckbox.checked = false;
    }
});

document.getElementById('necessite_invitation').addEventListener('change', function() {
    const publicCheckbox = document.getElementById('est_public');
    if (this.checked) {
        publicCheckbox.checked = false;
    }
});

// Événements pour la mise à jour de l'aperçu
['titre', 'type_culte', 'date_culte', 'heure_debut', 'heure_fin', 'lieu', 'statut'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

// Événements pour les liens
document.getElementById('diffusion_en_ligne').addEventListener('change', toggleLinksSection);
document.getElementById('est_enregistre').addEventListener('change', toggleLinksSection);

// Modal duplication
function openDuplicateModal(culteId) {
    document.getElementById('duplicate_culte_id').value = culteId;
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
}

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.duplicate')): ?>
function duplicateCulte() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const culteId = document.getElementById('duplicate_culte_id').value;

    fetch(`<?php echo e(route('private.cultes.dupliquer', ':culteid')); ?>`.replace(':culteid', culteId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `<?php echo e(route('private.cultes.show', ':culteid')); ?>`.replace(':culteid', data.data.id);
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

// Suppression
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.delete')): ?>
function deleteCulte(culteId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce culte ?')) {
        fetch(`<?php echo e(route('private.cultes.destroy', ':culteid')); ?>`.replace(':culteid', culteId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?php echo e(route("private.cultes.index")); ?>';
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

// Fermer le modal en cliquant à l'extérieur
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDuplicateModal();
});

// Validation du formulaire
document.getElementById('culteForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const titre = document.getElementById('titre').value.trim();
    const date = document.getElementById('date_culte').value;
    const heure = document.getElementById('heure_debut').value;
    const lieu = document.getElementById('lieu').value.trim();
    const programme = document.getElementById('programme_id').value;

    if (!titre || !date || !heure || !lieu || !programme) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleLinksSection();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/cultes/edit.blade.php ENDPATH**/ ?>