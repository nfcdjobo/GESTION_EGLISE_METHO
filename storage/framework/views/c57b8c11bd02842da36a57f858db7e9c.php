<?php $__env->startSection('title', 'Modifier le Rapport'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Rapport</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.rapports-reunions.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Rapports de Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="<?php echo e(route('private.rapports-reunions.show', $rapport)); ?>" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors"><?php echo e($rapport->titre_rapport); ?></a>
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

    <form action="<?php echo e(route('private.rapports-reunions.update', $rapport)); ?>" method="POST" id="rapportForm" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
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
                        <div>
                            <label for="reunion_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Réunion concernée <span class="text-red-500">*</span>
                            </label>
                            <select id="reunion_id" name="reunion_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['reunion_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php if($rapport->reunion): ?>
                                    <option value="<?php echo e($rapport->reunion->id); ?>" selected>
                                        <?php echo e($rapport->reunion->titre); ?> - <?php echo e(\Carbon\Carbon::parse($rapport->reunion->date_reunion)->format('d/m/Y')); ?>

                                    </option>
                                <?php endif; ?>
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
                            <p class="mt-1 text-sm text-slate-500">La réunion associée ne peut pas être modifiée</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titre_rapport" class="block text-sm font-medium text-slate-700 mb-2">
                                    Titre du rapport <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre_rapport" name="titre_rapport" value="<?php echo e(old('titre_rapport', $rapport->titre_rapport)); ?>" required maxlength="200" placeholder="Ex: Rapport de la réunion mensuelle"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['titre_rapport'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['titre_rapport'];
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
                                <label for="type_rapport" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de rapport <span class="text-red-500">*</span>
                                </label>
                                <select id="type_rapport" name="type_rapport" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['type_rapport'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner le type</option>
                                    <?php $__currentLoopData = \App\Models\RapportReunion::TYPES_RAPPORT; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(old('type_rapport', $rapport->type_rapport) == $value ? 'selected' : ''); ?>>
                                            <?php switch($value):
                                                case ('proces_verbal'): ?> Procès-verbal <?php break; ?>
                                                <?php case ('compte_rendu'): ?> Compte-rendu <?php break; ?>
                                                <?php case ('rapport_activite'): ?> Rapport d'activité <?php break; ?>
                                                <?php case ('rapport_financier'): ?> Rapport financier <?php break; ?>
                                            <?php endswitch; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['type_rapport'];
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
                                <label for="redacteur_id" class="block text-sm font-medium text-slate-700 mb-2">Rédacteur</label>
                                <select id="redacteur_id" name="redacteur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['redacteur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un rédacteur</option>
                                    <?php $__currentLoopData = $redacteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $redacteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($redacteur->id); ?>" <?php echo e(old('redacteur_id', $rapport->redacteur_id) == $redacteur->id ? 'selected' : ''); ?>>
                                            <?php echo e($redacteur->nom); ?> <?php echo e($redacteur->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['redacteur_id'];
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
                                <label for="validateur_id" class="block text-sm font-medium text-slate-700 mb-2">Validateur</label>
                                <select id="validateur_id" name="validateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['validateur_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Sélectionner un validateur</option>
                                    <?php $__currentLoopData = $validateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $validateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($validateur->id); ?>" <?php echo e(old('validateur_id', $rapport->validateur_id) == $validateur->id ? 'selected' : ''); ?>>
                                            <?php echo e($validateur->nom); ?> <?php echo e($validateur->prenom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['validateur_id'];
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
                            <label for="resume" class="block text-sm font-medium text-slate-700 mb-2">Résumé</label>
                            <div class="<?php $__errorArgs = ['resume'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="resume" name="resume" rows="4" placeholder="Résumé du rapport"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('resume', $rapport->resume)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['resume'];
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

                <!-- Contenu du rapport -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-edit text-green-600 mr-2"></i>
                            Contenu du Rapport
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="decisions_prises" class="block text-sm font-medium text-slate-700 mb-2">Décisions prises</label>
                            <div class="<?php $__errorArgs = ['decisions_prises'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="decisions_prises" name="decisions_prises" rows="4" placeholder="Décisions importantes prises lors de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('decisions_prises', $rapport->decisions_prises)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['decisions_prises'];
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
                            <label for="actions_decidees" class="block text-sm font-medium text-slate-700 mb-2">Actions décidées</label>
                            <div class="<?php $__errorArgs = ['actions_decidees'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="actions_decidees" name="actions_decidees" rows="4" placeholder="Actions décidées et à mettre en œuvre"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('actions_decidees', $rapport->actions_decidees)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['actions_decidees'];
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
                            <label for="recommandations" class="block text-sm font-medium text-slate-700 mb-2">Recommandations</label>
                            <div class="<?php $__errorArgs = ['recommandations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="recommandations" name="recommandations" rows="4" placeholder="Recommandations pour l'avenir"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('recommandations', $rapport->recommandations)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['recommandations'];
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
                            <label for="commentaires" class="block text-sm font-medium text-slate-700 mb-2">Commentaires généraux</label>
                            <div class="<?php $__errorArgs = ['commentaires'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <textarea id="commentaires" name="commentaires" rows="3" placeholder="Commentaires généraux sur la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo e(old('commentaires', $rapport->commentaires)); ?></textarea>
                            </div>
                            <?php $__errorArgs = ['commentaires'];
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

                <!-- Points traités -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            Points Traités
                        </h2>
                    </div>
                    <div class="p-6">
                        <div id="points-traites-container">
                            <div class="space-y-4" id="points-list">
                                <?php if($rapport->points_traites && count($rapport->points_traites) > 0): ?>
                                    <?php $__currentLoopData = $rapport->points_traites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="points_traites[]" value="<?php echo e(is_array($point) ? $point['titre'] ?? $point : $point); ?>" placeholder="Titre du point traité"
                                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <?php if($index > 0): ?>
                                                <button type="button" onclick="supprimerPoint(this)" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" onclick="ajouterPoint()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="points_traites[]" placeholder="Titre du point traité"
                                            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <button type="button" onclick="ajouterPoint()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Modifiez ou ajoutez les différents points traités lors de la réunion</p>
                    </div>
                </div>

                <!-- Présences -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-cyan-600 mr-2"></i>
                            Présences et Statistiques
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombre_presents" class="block text-sm font-medium text-slate-700 mb-2">Nombre de présents</label>
                                <input type="number" id="nombre_presents" name="nombre_presents" value="<?php echo e(old('nombre_presents', $rapport->nombre_presents)); ?>" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nombre_presents'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['nombre_presents'];
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
                                <label for="montant_collecte" class="block text-sm font-medium text-slate-700 mb-2">Montant collecté (€)</label>
                                <input type="number" id="montant_collecte" name="montant_collecte" value="<?php echo e(old('montant_collecte', $rapport->montant_collecte)); ?>" min="0" step="0.01"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['montant_collecte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['montant_collecte'];
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

                        <!-- Gestion des présences individuelles -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-slate-700">Liste des présences (<?php echo e($rapport->presences ? count($rapport->presences) : 0); ?>)</label>
                                <button type="button" onclick="ouvrirModalPresences()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-users mr-2"></i> Gérer les présences
                                </button>
                            </div>

                            <?php if($rapport->presences && count($rapport->presences) > 0): ?>
                                <div class="bg-slate-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        <?php $__currentLoopData = $rapport->presences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-2 bg-white rounded border">
                                                <div>
                                                    <p class="font-medium text-sm"><?php echo e(is_array($presence) ? $presence['nom'] : $presence); ?></p>
                                                    <?php if(is_array($presence) && isset($presence['role'])): ?>
                                                        <p class="text-xs text-slate-500"><?php echo e($presence['role']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <button type="button" onclick="supprimerPresence('<?php echo e(is_array($presence) && isset($presence['user_id']) ? $presence['user_id'] : uniqid()); ?>')" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-6 bg-slate-50 rounded-lg">
                                    <i class="fas fa-users text-2xl text-slate-400 mb-2"></i>
                                    <p class="text-slate-500">Aucune présence enregistrée</p>
                                    <button type="button" onclick="ouvrirModalPresences()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ajouter des présences
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
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
                                'brouillon' => 'bg-gray-100 text-gray-800',
                                'en_revision' => 'bg-yellow-100 text-yellow-800',
                                'valide' => 'bg-blue-100 text-blue-800',
                                'publie' => 'bg-green-100 text-green-800'
                            ];
                        ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium <?php echo e($statutColors[$rapport->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($rapport->statut_traduit); ?>

                        </span>
                        <p class="text-sm text-slate-500 mt-2">Créé le <?php echo e($rapport->created_at->format('d/m/Y à H:i')); ?></p>
                        <p class="text-sm text-slate-500">Modifié le <?php echo e($rapport->updated_at->format('d/m/Y à H:i')); ?></p>
                        <?php if($rapport->valide_le): ?>
                            <p class="text-sm text-slate-500">Validé le <?php echo e($rapport->valide_le->format('d/m/Y à H:i')); ?></p>
                        <?php endif; ?>
                        <?php if($rapport->publie_le): ?>
                            <p class="text-sm text-slate-500">Publié le <?php echo e($rapport->publie_le->format('d/m/Y à H:i')); ?></p>
                        <?php endif; ?>

                        <?php if(!$rapport->est_modifiable): ?>
                            <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                <p class="text-sm text-orange-700">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Ce rapport ne peut plus être modifié car il est <?php echo e($rapport->statut_traduit); ?>.
                                </p>
                            </div>
                        <?php endif; ?>
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
                            <span id="preview-titre" class="text-sm text-slate-900 font-semibold"><?php echo e($rapport->titre_rapport); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="text-sm text-slate-600"><?php echo e($rapport->type_rapport_traduit); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Réunion:</span>
                            <span id="preview-reunion" class="text-sm text-slate-600"><?php echo e($rapport->reunion->titre ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Présents:</span>
                            <span id="preview-presents" class="text-sm text-slate-600"><?php echo e($rapport->nombre_presents); ?></span>
                        </div>
                        <div class="pt-2 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Complété à:</span>
                                <div class="flex items-center">
                                    <div class="w-16 h-2 bg-slate-200 rounded-full mr-2">
                                        <div id="completion-bar" class="h-2 bg-gradient-to-r from-blue-500 to-green-500 rounded-full" style="width: <?php echo e($rapport->pourcentage_completion); ?>%"></div>
                                    </div>
                                    <span id="completion-text" class="text-sm text-slate-900 font-semibold"><?php echo e($rapport->pourcentage_completion); ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Évaluation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-star text-yellow-600 mr-2"></i>
                            Évaluation
                        </h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="note_satisfaction" class="block text-sm font-medium text-slate-700 mb-2">Note de satisfaction (1-5)</label>
                            <select id="note_satisfaction" name="note_satisfaction"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['note_satisfaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 focus:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Non évaluée</option>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('note_satisfaction', $rapport->note_satisfaction) == $i ? 'selected' : ''); ?>><?php echo e($i); ?> - <?php echo e(['Très insatisfaisante', 'Insatisfaisante', 'Correcte', 'Satisfaisante', 'Excellente'][$i-1]); ?></option>
                                <?php endfor; ?>
                            </select>
                            <?php $__errorArgs = ['note_satisfaction'];
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

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?php echo e(route('private.rapports-reunions.show', $rapport)); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i> Voir les détails
                        </a>

                        <button type="button" onclick="genererResumeAuto()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-magic mr-2"></i> Générer résumé auto
                        </button>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $rapport)): ?>
                            <?php if($rapport->statut !== 'publie'): ?>
                                <button type="button" onclick="supprimerRapport()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
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
                    <a href="<?php echo e(route('private.rapports-reunions.show', $rapport)); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal gestion présences -->
<div id="presencesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Gérer les présences</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex gap-4">
                    <input type="text" id="nouveau_nom" placeholder="Nom et prénom" class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <input type="text" id="nouveau_role" placeholder="Rôle (optionnel)" class="w-32 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" onclick="ajouterNouvellePresence()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter
                    </button>
                </div>

                <div id="presences-modal-list" class="space-y-2">
                    <?php if($rapport->presences && count($rapport->presences) > 0): ?>
                        <?php $__currentLoopData = $rapport->presences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $presence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg" data-presence-index="<?php echo e($index); ?>">
                                <div>
                                    <p class="font-medium"><?php echo e(is_array($presence) ? $presence['nom'] : $presence); ?></p>
                                    <?php if(is_array($presence) && isset($presence['role'])): ?>
                                        <p class="text-sm text-slate-500"><?php echo e($presence['role']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <button type="button" onclick="retirerPresenceModal(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="fermerModalPresences()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Fermer
            </button>
        </div>
    </div>
</div>

<?php echo $__env->make('partials.ckeditor-resources', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
let pointsCount = <?php echo e($rapport->points_traites ? count($rapport->points_traites) : 1); ?>;
let presencesData = <?php echo json_encode($rapport->presences ?? [], 15, 512) ?>;

// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre_rapport').value || '<?php echo e($rapport->titre_rapport); ?>';
    const typeSelect = document.getElementById('type_rapport');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '<?php echo e($rapport->type_rapport_traduit); ?>';
    const presents = document.getElementById('nombre_presents').value || '<?php echo e($rapport->nombre_presents); ?>';

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-presents').textContent = presents;

    // Calculer le pourcentage de completion (approximatif)
    updateCompletionPercentage();
}

function updateCompletionPercentage() {
    const champsObligatoires = ['titre_rapport', 'resume', 'decisions_prises', 'actions_decidees'];
    let champsRemplis = 0;

    champsObligatoires.forEach(champ => {
        const element = document.getElementById(champ);
        if (element && element.value.trim()) {
            champsRemplis++;
        }
    });

    const pourcentage = Math.round((champsRemplis / champsObligatoires.length) * 100);
    document.getElementById('completion-bar').style.width = pourcentage + '%';
    document.getElementById('completion-text').textContent = pourcentage + '%';
}

// Ajouter un point traité
function ajouterPoint() {
    const container = document.getElementById('points-list');
    const newPoint = document.createElement('div');
    newPoint.className = 'flex items-center gap-2';
    newPoint.innerHTML = `
        <input type="text" name="points_traites[]" placeholder="Titre du point traité"
            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <button type="button" onclick="supprimerPoint(this)" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newPoint);
    pointsCount++;
}

function supprimerPoint(button) {
    button.closest('.flex').remove();
}

// Gestion des présences
function ouvrirModalPresences() {
    document.getElementById('presencesModal').classList.remove('hidden');
}

function fermerModalPresences() {
    document.getElementById('presencesModal').classList.add('hidden');
}

function ajouterNouvellePresence() {
    const nom = document.getElementById('nouveau_nom').value.trim();
    const role = document.getElementById('nouveau_role').value.trim();

    if (!nom) {
        alert('Veuillez saisir un nom');
        return;
    }

    const container = document.getElementById('presences-modal-list');
    const newPresence = document.createElement('div');
    newPresence.className = 'flex items-center justify-between p-3 bg-slate-50 rounded-lg';
    newPresence.innerHTML = `
        <div>
            <p class="font-medium">${nom}</p>
            ${role ? `<p class="text-sm text-slate-500">${role}</p>` : ''}
        </div>
        <button type="button" onclick="retirerPresenceModal(this)" class="text-red-600 hover:text-red-800">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(newPresence);

    // Ajouter aux données
    presencesData.push({ nom: nom, role: role || null });

    // Réinitialiser les champs
    document.getElementById('nouveau_nom').value = '';
    document.getElementById('nouveau_role').value = '';

    // Mettre à jour le nombre de présents
    document.getElementById('nombre_presents').value = presencesData.length;
    updatePreview();
}

function retirerPresenceModal(button) {
    const item = button.closest('.flex');
    const index = Array.from(item.parentNode.children).indexOf(item);

    // Retirer des données
    presencesData.splice(index, 1);

    // Retirer de l'affichage
    item.remove();

    // Mettre à jour le nombre de présents
    document.getElementById('nombre_presents').value = presencesData.length;
    updatePreview();
}

function supprimerPresence(userId) {
    // Fonction appelée depuis les éléments existants
    fetch(`<?php echo e(route('private.rapports-reunions.presences.supprimer', $rapport->id)); ?>`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Générer résumé automatique
function genererResumeAuto() {
    const reunion = document.querySelector('#reunion_id option:checked')?.text || '';
    const type = document.querySelector('#type_rapport option:checked')?.text || '';
    const presents = document.getElementById('nombre_presents').value || '0';
    const pointsInputs = document.querySelectorAll('input[name="points_traites[]"]');
    const nombrePoints = Array.from(pointsInputs).filter(input => input.value.trim()).length;

    let resume = '';
    if (reunion) {
        resume += `${type} pour ${reunion}. `;
    }
    if (presents > 0) {
        resume += `${presents} participants présents. `;
    }
    if (nombrePoints > 0) {
        resume += `${nombrePoints} points traités. `;
    }

    if (resume) {
        document.getElementById('resume').value = resume;

        // Si CKEditor est initialisé sur ce champ
        if (window.CKEditorInstances && window.CKEditorInstances['#resume']) {
            window.CKEditorInstances['#resume'].setData(resume);
        }

        updatePreview();
    }
}

function supprimerRapport() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
        fetch(`<?php echo e(route('private.rapports-reunions.destroy', $rapport->id)); ?>`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?php echo e(route("private.rapports-reunions.index")); ?>';
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

// Événements pour la mise à jour de l'aperçu
['titre_rapport', 'type_rapport', 'nombre_presents'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

// Événements pour le calcul de completion
['titre_rapport', 'resume', 'decisions_prises', 'actions_decidees'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updateCompletionPercentage);
    }
});

// Validation du formulaire
document.getElementById('rapportForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    // Ajouter les présences mises à jour
    if (presencesData.length > 0) {
        presencesData.forEach((presence, index) => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `presences[${index}][nom]`;
            hiddenInput.value = presence.nom;
            this.appendChild(hiddenInput);

            if (presence.role) {
                const hiddenRoleInput = document.createElement('input');
                hiddenRoleInput.type = 'hidden';
                hiddenRoleInput.name = `presences[${index}][role]`;
                hiddenRoleInput.value = presence.role;
                this.appendChild(hiddenRoleInput);
            }
        });
    }

    const titre = document.getElementById('titre_rapport').value.trim();
    const type = document.getElementById('type_rapport').value;

    if (!titre || !type) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
});

// Fermer le modal en cliquant à l'extérieur
document.getElementById('presencesModal').addEventListener('click', function(e) {
    if (e.target === this) fermerModalPresences();
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    updateCompletionPercentage();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/rapportsreunions/edit.blade.php ENDPATH**/ ?>