<?php $__env->startSection('title', 'Modifier le Projet'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Projet</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.projets.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Projets
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="<?php echo e(route('private.projets.show', $projet)); ?>" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors"><?php echo e($projet->nom_projet); ?></a>
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

    <form action="<?php echo e(route('private.projets.update', $projet)); ?>" method="POST" enctype="multipart/form-data" id="projetForm" class="space-y-8">
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
                                <label for="nom_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du projet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom_projet" name="nom_projet" value="<?php echo e(old('nom_projet', $projet->nom_projet)); ?>" required maxlength="200" placeholder="Ex: Construction nouveau sanctuaire"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom_projet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['nom_projet'];
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
                                <label for="code_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Code du projet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="code_projet" name="code_projet" readonly disabled value="<?php echo e(old('code_projet', $projet->code_projet)); ?>" required maxlength="50" placeholder="Ex: CONST2024001"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['code_projet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['code_projet'];
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
                                <textarea id="description" name="description" rows="4" placeholder="Description détaillée du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('description', $projet->description)); ?></textarea>
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



                        <div>
                            <label for="objectif" class="block text-sm font-medium text-slate-700 mb-2">Objectifs</label>
                            <div class="<?php $__errorArgs = ['objectif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="objectif" name="objectif" rows="4" placeholder="Objectifs du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('objectif', $projet->objectif)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['objectif'];
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
                            <label for="contexte" class="block text-sm font-medium text-slate-700 mb-2">contextes</label>
                            <div class="<?php $__errorArgs = ['contexte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="contexte" name="contexte" rows="4" placeholder="Contextes du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('contexte', $projet->contexte)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['contexte'];
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
                                <label for="type_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de projet <span class="text-red-500">*</span>
                                </label>
                                <select id="type_projet" name="type_projet" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['type_projet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le type</option>
                                    <?php $__currentLoopData = $options['types_projet']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('type_projet', $projet->type_projet) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['type_projet'];
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
                                    <?php $__currentLoopData = $options['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('categorie', $projet->categorie) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
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
                                <label for="priorite" class="block text-sm font-medium text-slate-700 mb-2">
                                    Priorité
                                </label>
                                <select id="priorite" name="priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['priorite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__currentLoopData = $options['priorites']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('priorite', $projet->priorite) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['priorite'];
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

                <!-- Budget et financement -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-coins text-green-600 mr-2"></i>
                            Budget et Financement
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="budget_prevu" class="block text-sm font-medium text-slate-700 mb-2">Budget prévu</label>
                                <input type="number" id="budget_prevu" name="budget_prevu" value="<?php echo e(old('budget_prevu', $projet->budget_prevu)); ?>" min="0" step="0.01" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['budget_prevu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['budget_prevu'];
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
                                <label for="budget_minimum" class="block text-sm font-medium text-slate-700 mb-2">Budget minimum</label>
                                <input type="number" id="budget_minimum" name="budget_minimum" value="<?php echo e(old('budget_minimum', $projet->budget_minimum)); ?>" min="0" step="0.01" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['budget_minimum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['budget_minimum'];
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
                                <label for="devise" class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                                <select id="devise" name="devise"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['devise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="XOF" <?php echo e(old('devise', $projet->devise) == 'XOF' ? 'selected' : ''); ?>>Franc CFA (XOF)</option>
                                    <option value="EUR" <?php echo e(old('devise', $projet->devise) == 'EUR' ? 'selected' : ''); ?>>Euro (EUR)</option>
                                    <option value="USD" <?php echo e(old('devise', $projet->devise) == 'USD' ? 'selected' : ''); ?>>Dollar US (USD)</option>
                                </select>
                                <?php $__errorArgs = ['devise'];
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

                        <?php if($projet->budget_collecte > 0 || $projet->budget_depense > 0): ?>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <h4 class="font-medium text-slate-800 mb-3">Suivi financier</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-slate-600">Collecté:</span>
                                    <span class="font-semibold text-green-600"><?php echo e(number_format($projet->budget_collecte, 0, ',', ' ')); ?> <?php echo e($projet->devise); ?></span>
                                </div>
                                <div>
                                    <span class="text-slate-600">Dépensé:</span>
                                    <span class="font-semibold text-red-600"><?php echo e(number_format($projet->budget_depense, 0, ',', ' ')); ?> <?php echo e($projet->devise); ?></span>
                                </div>
                                <div>
                                    <span class="text-slate-600">Financement:</span>
                                    <span class="font-semibold text-blue-600"><?php echo e($projet->pourcentage_financement); ?>%</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Planification temporelle -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                            Planification Temporelle
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                                <input type="date" id="date_debut" name="date_debut" value="<?php echo e(old('date_debut', $projet->date_debut?->format('Y-m-d'))); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['date_debut'];
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
                                <label for="date_fin_prevue" class="block text-sm font-medium text-slate-700 mb-2">Date de fin prévue</label>
                                <input type="date" id="date_fin_prevue" name="date_fin_prevue" value="<?php echo e(old('date_fin_prevue', $projet->date_fin_prevue?->format('Y-m-d'))); ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_fin_prevue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['date_fin_prevue'];
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
                                <label for="duree_prevue_jours" class="block text-sm font-medium text-slate-700 mb-2">Durée prévue (jours)</label>
                                <input type="number" id="duree_prevue_jours" name="duree_prevue_jours" value="<?php echo e(old('duree_prevue_jours', $projet->duree_prevue_jours)); ?>" min="1" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['duree_prevue_jours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['duree_prevue_jours'];
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

                        <?php if($projet->statut === 'en_cours'): ?>
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h4 class="font-medium text-blue-800 mb-2">Progression du projet</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm text-blue-700">
                                    <span>Avancement</span>
                                    <span><?php echo e($projet->pourcentage_completion); ?>%</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($projet->pourcentage_completion); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Responsables et équipe -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-amber-600 mr-2"></i>
                            Responsables et Équipe
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable principal</label>
                                <select id="responsable_id" name="responsable_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['responsable_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un responsable</option>
                                    <?php $__currentLoopData = $options['responsables']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($responsable['id']); ?>" <?php echo e(old('responsable_id', $projet->responsable_id) == $responsable['id'] ? 'selected' : ''); ?>><?php echo e($responsable['nom_complet']); ?></option>
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
                                <label for="coordinateur_id" class="block text-sm font-medium text-slate-700 mb-2">Coordinateur</label>
                                <select id="coordinateur_id" name="coordinateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['coordinateur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un coordinateur</option>
                                    <?php $__currentLoopData = $options['responsables']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($responsable['id']); ?>" <?php echo e(old('coordinateur_id', $projet->coordinateur_id) == $responsable['id'] ? 'selected' : ''); ?>><?php echo e($responsable['nom_complet']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['coordinateur_id'];
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
                                <label for="chef_projet_id" class="block text-sm font-medium text-slate-700 mb-2">Chef de projet</label>
                                <select id="chef_projet_id" name="chef_projet_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['chef_projet_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un chef de projet</option>
                                    <?php $__currentLoopData = $options['responsables']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($responsable['id']); ?>" <?php echo e(old('chef_projet_id', $projet->chef_projet_id) == $responsable['id'] ? 'selected' : ''); ?>><?php echo e($responsable['nom_complet']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['chef_projet_id'];
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

                <!-- Localisation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Localisation
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="localisation" class="block text-sm font-medium text-slate-700 mb-2">Localisation</label>
                                <input type="text" id="localisation" name="localisation" value="<?php echo e(old('localisation', $projet->localisation)); ?>" maxlength="200" placeholder="Ex: Site de l'église"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['localisation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['localisation'];
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
                                <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                                <input type="text" id="ville" name="ville" value="<?php echo e(old('ville', $projet->ville)); ?>" maxlength="100" placeholder="Ex: Abidjan"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['ville'];
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
                                <label for="region" class="block text-sm font-medium text-slate-700 mb-2">Région</label>
                                <input type="text" id="region" name="region" value="<?php echo e(old('region', $projet->region)); ?>" maxlength="100" placeholder="Ex: District Autonome d'Abidjan"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['region'];
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
                            <label for="adresse_complete" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète</label>
                            <div class="<?php $__errorArgs = ['adresse_complete'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="adresse_complete" name="adresse_complete" rows="2" placeholder="Adresse complète du lieu du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('adresse_complete', $projet->adresse_complete)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['adresse_complete'];
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

                <!-- Options et paramètres -->
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
                                    <input type="checkbox" id="visible_public" name="visible_public" value="1" <?php echo e(old('visible_public', $projet->visible_public) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="visible_public" class="ml-2 text-sm font-medium text-slate-700">
                                        Visible au public
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="ouvert_aux_dons" name="ouvert_aux_dons" value="1" <?php echo e(old('ouvert_aux_dons', $projet->ouvert_aux_dons) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="ouvert_aux_dons" class="ml-2 text-sm font-medium text-slate-700">
                                        Ouvert aux dons
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="necessite_suivi" name="necessite_suivi" value="1" <?php echo e(old('necessite_suivi', $projet->necessite_suivi) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="necessite_suivi" class="ml-2 text-sm font-medium text-slate-700">
                                        Nécessite un suivi post-projet
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="projet_recurrent" name="projet_recurrent" value="1" <?php echo e(old('projet_recurrent', $projet->projet_recurrent) ? 'checked' : ''); ?>

                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="projet_recurrent" class="ml-2 text-sm font-medium text-slate-700">
                                        Projet récurrent
                                    </label>
                                </div>
                            </div>

                            <div id="recurrence_section" class="space-y-4 <?php echo e($projet->projet_recurrent ? '' : 'hidden'); ?>">
                                <div>
                                    <label for="frequence_recurrence" class="block text-sm font-medium text-slate-700 mb-2">Fréquence de récurrence</label>
                                    <select id="frequence_recurrence" name="frequence_recurrence"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <?php $__currentLoopData = $options['frequences_recurrence']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e(old('frequence_recurrence', $projet->frequence_recurrence) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="site_web" class="block text-sm font-medium text-slate-700 mb-2">Site web du projet</label>
                            <input type="url" id="site_web" name="site_web" value="<?php echo e(old('site_web', $projet->site_web)); ?>" placeholder="https://..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['site_web'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['site_web'];
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

            <!-- Sidebar - Statut et informations -->
            <div class="space-y-6">
                <!-- Statut actuel -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Statut Actuel
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <?php
                                $statutColors = [
                                    'conception' => 'bg-gray-100 text-gray-800',
                                    'planification' => 'bg-blue-100 text-blue-800',
                                    'recherche_financement' => 'bg-yellow-100 text-yellow-800',
                                    'en_attente' => 'bg-orange-100 text-orange-800',
                                    'en_cours' => 'bg-green-100 text-green-800',
                                    'suspendu' => 'bg-red-100 text-red-800',
                                    'termine' => 'bg-emerald-100 text-emerald-800',
                                    'annule' => 'bg-red-100 text-red-800',
                                    'archive' => 'bg-slate-100 text-slate-800'
                                ];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statutColors[$projet->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($projet->statut_libelle); ?>

                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Créé le:</span>
                            <span class="text-sm text-slate-600"><?php echo e($projet->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <?php if($projet->approuve_le): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Approuvé le:</span>
                            <span class="text-sm text-slate-600"><?php echo e($projet->approuve_le->format('d/m/Y')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($projet->date_debut): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Débuté le:</span>
                            <span class="text-sm text-slate-600"><?php echo e($projet->date_debut->format('d/m/Y')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightning-bolt text-yellow-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
<?php if($projet->peutEtreApprouve()): ?>
    <button type="button" onclick="approveProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
        <i class="fas fa-check mr-2"></i> Approuver
    </button>
<?php endif; ?>

<?php if($projet->peutEtrePlanifie()): ?>
    <button type="button" onclick="planifyProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
        <i class="fas fa-calendar-check mr-2"></i> Planifier
    </button>
<?php endif; ?>


<?php if($projet->peutEtreEnRechercheFinancement()): ?>
    <button type="button" onclick="searchFunding()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 transition-colors">
        <i class="fas fa-search-dollar mr-2"></i> Rechercher financement
    </button>
<?php endif; ?>

<?php if($projet->peutEtreDemarre()): ?>
    <button type="button" onclick="startProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
        <i class="fas fa-play mr-2"></i> Démarrer
    </button>
<?php endif; ?>

                        <?php if($projet->peutEtreSuspendu()): ?>
                            <button type="button" onclick="suspendProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 transition-colors">
                                <i class="fas fa-pause mr-2"></i> Suspendre
                            </button>
                        <?php endif; ?>

                        <?php if($projet->peutEtreRepris()): ?>
                            <button type="button" onclick="resumeProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-play mr-2"></i> Reprendre
                            </button>
                        <?php endif; ?>

                        <?php if($projet->peutEtreTermine()): ?>
                            <button type="button" onclick="completeProject()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-flag-checkered mr-2"></i> Terminer
                            </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('private.projets.show', $projet)); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i> Voir détails
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Mettre à jour le Projet
                    </button>
                    <a href="<?php echo e(route('private.projets.show', $projet)); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>


<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Gestion de la récurrence
function toggleRecurrenceSection() {
    const recurrentCheckbox = document.getElementById('projet_recurrent');
    const recurrenceSection = document.getElementById('recurrence_section');

    if (recurrentCheckbox.checked) {
        recurrenceSection.classList.remove('hidden');
    } else {
        recurrenceSection.classList.add('hidden');
    }
}

// Calcul automatique de la durée
function calculateDuration() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin_prevue').value;

    if (dateDebut && dateFin) {
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        const diff = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24));

        if (diff > 0) {
            document.getElementById('duree_prevue_jours').value = diff;
        }
    }
}

// Actions rapides
function approveProject() {
    if (confirm('Êtes-vous sûr de vouloir approuver ce projet ?')) {
        fetch(`<?php echo e(route('private.projets.approuver', $projet)); ?>`, {
            method: 'POST',
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
}

function startProject() {
    if (confirm('Êtes-vous sûr de vouloir démarrer ce projet ?')) {
        fetch(`<?php echo e(route('private.projets.demarrer', $projet)); ?>`, {
            method: 'POST',
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
        });
    }
}

function suspendProject() {
    const motif = prompt('Motif de suspension:');
    if (motif) {
        fetch(`<?php echo e(route('private.projets.suspendre', $projet)); ?>`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ motif: motif })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        });
    }
}

function resumeProject() {
    if (confirm('Êtes-vous sûr de vouloir reprendre ce projet ?')) {
        fetch(`<?php echo e(route('private.projets.reprendre', $projet)); ?>`, {
            method: 'POST',
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
        });
    }
}

function completeProject() {
    if (confirm('Êtes-vous sûr de vouloir terminer ce projet ?')) {
        fetch(`<?php echo e(route('private.projets.terminer', $projet)); ?>`, {
            method: 'POST',
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
        });
    }
}


function planifyProject() {
    if (confirm('Êtes-vous sûr de vouloir planifier ce projet ? Il passera du statut "conception" à "planification".')) {
        fetch(`<?php echo e(route('private.projets.planifier', $projet)); ?>`, {
            method: 'POST',
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
}


// Événement pour la récurrence
document.getElementById('projet_recurrent').addEventListener('change', toggleRecurrenceSection);

// Événements pour le calcul de durée
document.getElementById('date_debut').addEventListener('change', calculateDuration);
document.getElementById('date_fin_prevue').addEventListener('change', calculateDuration);

// Validation du formulaire avec synchronisation CKEditor
document.getElementById('projetForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const nom = document.getElementById('nom_projet').value.trim();
    const code = document.getElementById('code_projet').value.trim();
    const type = document.getElementById('type_projet').value;
    const categorie = document.getElementById('categorie').value;

    if (!nom || !code || !type || !categorie) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Vérifier que les dates sont cohérentes
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin_prevue').value;

    if (dateDebut && dateFin && new Date(dateDebut) > new Date(dateFin)) {
        e.preventDefault();
        alert('La date de début ne peut pas être postérieure à la date de fin prévue.');
        return false;
    }

    // Vérifier que le budget minimum n'est pas supérieur au budget prévu
    const budgetPrevu = parseFloat(document.getElementById('budget_prevu').value) || 0;
    const budgetMinimum = parseFloat(document.getElementById('budget_minimum').value) || 0;

    if (budgetMinimum > budgetPrevu && budgetPrevu > 0) {
        e.preventDefault();
        alert('Le budget minimum ne peut pas être supérieur au budget prévu.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleRecurrenceSection();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/projets/edit.blade.php ENDPATH**/ ?>