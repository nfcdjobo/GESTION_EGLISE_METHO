<?php $__env->startSection('title', 'Modifier - ' . $moisson->theme); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="<?php echo e(route('private.moissons.index')); ?>" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo e(route('private.moissons.show', $moisson)); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo e(Str::limit($moisson->theme, 25)); ?>

                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Modifier</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Modifier la moisson
                    </h1>
                    <p class="text-slate-500 mt-1 flex items-center gap-4">
                        <span><i class="fas fa-calendar mr-1"></i><?php echo e($moisson->date->format('d F Y')); ?></span>
                        <span><i class="fas fa-coins mr-1"></i><?php echo e(number_format($moisson->cible, 0, ',', ' ')); ?> FCFA</span>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($moisson->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($moisson->status ? 'Active' : 'Inactive'); ?>

                        </span>
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="<?php echo e(route('private.moissons.show', $moisson)); ?>"
                        class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir
                    </a>
                    <a href="<?php echo e(route('private.moissons.index')); ?>"
                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Alertes de validation -->
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form id="moisson-edit-form" action="<?php echo e(route('private.moissons.update', $moisson)); ?>" method="POST" class="space-y-8">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations générales
                        </h3>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <i class="fas fa-user text-xs"></i>
                            Créé par <?php echo e($moisson->createur->nom_complet ?? 'N/A'); ?>

                            <span class="mx-2">•</span>
                            <i class="fas fa-clock text-xs"></i>
                            <?php echo e($moisson->created_at->format('d/m/Y à H:i')); ?>

                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Thème -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Thème de la prédication <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="theme" id="theme" required
                                value="<?php echo e(old('theme', $moisson->theme)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <?php $__errorArgs = ['theme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Date de célébration <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" required
                                value="<?php echo e(old('date', $moisson->date->format('Y-m-d'))); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Culte -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Culte <span class="text-red-500">*</span>
                            </label>
                            <select name="culte_id" id="culte_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un culte</option>
                                <?php $__currentLoopData = $cultes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($culte->id); ?>" <?php echo e(old('culte_id', $moisson->culte_id) == $culte->id ? 'selected' : ''); ?>>
                                        <?php echo e($culte->titre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['culte_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Cible financière -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Objectif financier (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                value="<?php echo e(old('cible', $moisson->cible)); ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <?php $__errorArgs = ['cible'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1" <?php echo e(old('status', $moisson->status) == '1' ? 'checked' : ''); ?>

                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0" <?php echo e(old('status', $moisson->status) == '0' ? 'checked' : ''); ?>

                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de collecte (lecture seule) -->
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <h4 class="font-medium text-slate-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                            Informations de collecte (automatiques)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-slate-50 rounded-lg p-4">
                                <p class="text-xs text-slate-500 mb-1">Montant collecté</p>
                                <p class="font-semibold text-green-600"><?php echo e(number_format($moisson->montant_solde, 0, ',', ' ')); ?> FCFA</p>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-4">
                                <p class="text-xs text-slate-500 mb-1">
                                    <?php if($moisson->reste > 0): ?> Reste à collecter <?php else: ?> Supplément <?php endif; ?>
                                </p>
                                <p class="font-semibold <?php echo e($moisson->reste > 0 ? 'text-orange-600' : 'text-green-600'); ?>">
                                    <?php echo e(number_format($moisson->reste > 0 ? $moisson->reste : $moisson->montant_supplementaire, 0, ',', ' ')); ?> FCFA
                                </p>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-4">
                                <p class="text-xs text-slate-500 mb-1">Performance</p>
                                <p class="font-semibold text-blue-600"><?php echo e(number_format($moisson->pourcentage_realise, 1)); ?>%</p>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-4">
                                <p class="text-xs text-slate-500 mb-1">Statut progression</p>
                                <p class="font-semibold text-slate-700"><?php echo e($moisson->statut_progression); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passages bibliques -->
            

            <!-- Passages bibliques -->
<div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-book text-blue-600 mr-2"></i>
            Passages bibliques
        </h3>
        <p class="text-sm text-slate-600 mt-1">Modifiez les références bibliques liées à cette moisson</p>
    </div>
    <div class="p-6">
        <div id="passages-container" class="space-y-4">
            <?php if($moisson->passages_bibliques && count($moisson->passages_bibliques) > 0): ?>
                <?php $__currentLoopData = $moisson->passages_bibliques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $passage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="passage-item flex items-center gap-4">
                        <input type="text" name="passages_bibliques[]"
                               value="<?php echo e(is_array($passage) ? ($passage['reference'] ?? $passage['livre'] . ' ' . $passage['chapitre'] . ':' . $passage['verset_debut'] . (isset($passage['verset_fin']) ? '-' . $passage['verset_fin'] : '')) : $passage); ?>"
                               placeholder="Ex: Matthieu 9:37-38"
                               class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <button type="button" onclick="removePassage(this)"
                                class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors <?php echo e($index === 0 && count($moisson->passages_bibliques) === 1 ? 'opacity-50 cursor-not-allowed' : ''); ?>">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="passage-item flex items-center gap-4">
                    <input type="text" name="passages_bibliques[]" placeholder="Ex: Matthieu 9:37-38"
                           class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" onclick="removePassage(this)"
                            class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors opacity-50 cursor-not-allowed">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <button type="button" onclick="addPassage()"
                class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Ajouter un passage
        </button>

        <!-- Message d'aide -->
        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">Format des références :</p>
                    <ul class="text-xs space-y-1 ml-4 list-disc">
                        <li>Un seul verset : <code class="bg-white px-1 rounded">Jean 3:16</code></li>
                        <li>Plusieurs versets : <code class="bg-white px-1 rounded">Luc 14:2-10</code></li>
                        <li>Livres avec chiffres : <code class="bg-white px-1 rounded">1 Corinthiens 13:1-13</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


            <!-- Composants de collecte (résumé) -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                <i class="fas fa-layer-group text-blue-600 mr-2"></i>
                                Composants de collecte
                            </h3>
                            <p class="text-sm text-slate-600 mt-1">Gérez les passages, ventes et engagements depuis la vue détaillée</p>
                        </div>
                        <a href="<?php echo e(route('private.moissons.show', $moisson)); ?>"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-cogs mr-2"></i> Gérer les composants
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Passages -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-2xl text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-slate-800 mb-2">Passages de collecte</h4>
                            <p class="text-3xl font-bold text-blue-600 mb-1"><?php echo e($moisson->passageMoissons->count()); ?></p>
                            <p class="text-sm text-slate-500">Total: <?php echo e(number_format($moisson->passageMoissons->sum('montant_solde'), 0, ',', ' ')); ?> FCFA</p>
                        </div>

                        <!-- Ventes -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-store text-2xl text-green-600"></i>
                            </div>
                            <h4 class="font-medium text-slate-800 mb-2">Ventes</h4>
                            <p class="text-3xl font-bold text-green-600 mb-1"><?php echo e($moisson->venteMoissons->count()); ?></p>
                            <p class="text-sm text-slate-500">Total: <?php echo e(number_format($moisson->venteMoissons->sum('montant_solde'), 0, ',', ' ')); ?> FCFA</p>
                        </div>

                        <!-- Engagements -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-handshake text-2xl text-purple-600"></i>
                            </div>
                            <h4 class="font-medium text-slate-800 mb-2">Engagements</h4>
                            <p class="text-3xl font-bold text-purple-600 mb-1"><?php echo e($moisson->engagementMoissons->count()); ?></p>
                            <p class="text-sm text-slate-500">Total: <?php echo e(number_format($moisson->engagementMoissons->sum('montant_solde'), 0, ',', ' ')); ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des modifications -->
            <?php if($moisson->editeurs && count($moisson->editeurs) > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-blue-600 mr-2"></i>
                            Historique des modifications
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            <?php $__currentLoopData = array_reverse($moisson->editeurs); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center space-x-3 text-sm">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <span class="font-medium"><?php echo e($edit['action'] ?? 'modification'); ?></span>
                                        <?php if(isset($edit['user_id'])): ?>
                                            par <span class="text-blue-600"><?php echo e(\App\Models\User::find($edit['user_id'])->nom_complet ?? 'Utilisateur inconnu'); ?></span>
                                        <?php endif; ?>
                                        <span class="text-slate-500">
                                            le <?php echo e(\Carbon\Carbon::parse($edit['date'])->format('d/m/Y à H:i')); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6">
                <div class="flex gap-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moissons.recalculate')): ?>
                        <button type="button" onclick="recalculerTotaux()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-calculator mr-2"></i> Recalculer totaux
                        </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('moissons.close')): ?>
                        <?php if($moisson->status && !$moisson->est_cloturee): ?>
                            <button type="button" onclick="cloturerMoisson()"
                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 transition-colors">
                                <i class="fas fa-lock mr-2"></i> Clôturer
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="flex gap-4">
                    <a href="<?php echo e(route('private.moissons.show', $moisson)); ?>"
                        class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Gestion des passages bibliques
            function addPassage() {
                const container = document.getElementById('passages-container');
                const newPassage = document.createElement('div');
                newPassage.className = 'passage-item flex items-center gap-4';
                newPassage.innerHTML = `
                    <input type="text" name="passages_bibliques[]" placeholder="Ex: Jean 4:35"
                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" onclick="removePassage(this)"
                        class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-minus"></i>
                    </button>
                `;
                container.appendChild(newPassage);
                updateRemoveButtons();
            }

            function removePassage(button) {
                const container = document.getElementById('passages-container');
                if (container.children.length > 1) {
                    button.closest('.passage-item').remove();
                    updateRemoveButtons();
                }
            }

            function updateRemoveButtons() {
                const container = document.getElementById('passages-container');
                const removeButtons = container.querySelectorAll('.passage-item button');
                removeButtons.forEach((button, index) => {
                    if (container.children.length === 1) {
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                        button.disabled = true;
                    } else {
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.disabled = false;
                    }
                });
            }

            function recalculerTotaux() {
                if (confirm('Recalculer les totaux de cette moisson ?')) {
                    fetch("<?php echo e(route('private.moissons.recalculer-totaux', $moisson)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors du recalcul');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors du recalcul');
                    });
                }
            }

            function cloturerMoisson() {
                const motif = prompt('Motif de clôture (optionnel):');
                if (motif !== null) { // L'utilisateur n'a pas annulé
                    fetch("<?php echo e(route('private.moissons.cloturer', $moisson)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            motif: motif
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Moisson clôturée avec succès');
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la clôture');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la clôture');
                    });
                }
            }

            // Validation du formulaire
            document.getElementById('moisson-edit-form').addEventListener('submit', function(e) {
                const theme = document.getElementById('theme').value.trim();
                const date = document.getElementById('date').value;
                const cible = document.getElementById('cible').value;
                const culte = document.getElementById('culte_id').value;

                if (!theme || !date || !cible || !culte) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return;
                }

                if (parseInt(cible) < 1) {
                    e.preventDefault();
                    alert('L\'objectif financier doit être supérieur à 0.');
                    return;
                }

                // Validation des passages bibliques (au moins un passage non vide)
                const passages = document.querySelectorAll('input[name="passages_bibliques[]"]');
                let hasValidPassage = false;
                passages.forEach(input => {
                    if (input.value.trim() !== '') {
                        hasValidPassage = true;
                    }
                });

                if (!hasValidPassage) {
                    e.preventDefault();
                    alert('Veuillez saisir au moins un passage biblique.');
                    return;
                }
            });

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                updateRemoveButtons();

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

                // Avertissement pour les modifications importantes
                const originalCible = <?php echo e($moisson->cible); ?>;
                const cibleInput = document.getElementById('cible');
                cibleInput.addEventListener('blur', function() {
                    const newCible = parseInt(this.value);
                    if (newCible !== originalCible && <?php echo e($moisson->montant_solde); ?> > 0) {
                        alert('Attention: Modifier l\'objectif alors que des montants sont déjà collectés peut affecter les calculs.');
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/moissons/edit.blade.php ENDPATH**/ ?>