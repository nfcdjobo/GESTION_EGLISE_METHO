<?php $__env->startSection('title', 'Créer un FIMECO'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('private.fimecos.index')); ?>"
                    class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <i class="fas fa-arrow-left text-slate-600"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Créer un nouveau FIMECO
                    </h1>
                    <p class="text-slate-500 mt-1">Financement et Mobilisation Collective</p>
                </div>
            </div>
        </div>

        <!-- Formulaire principal -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                    Informations du FIMECO
                </h2>
                <p class="text-slate-500 text-sm mt-1">Remplissez les informations nécessaires pour créer le FIMECO</p>
            </div>

            <form action="<?php echo e(route('private.fimecos.store')); ?>" method="POST" class="p-6 space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Informations de base -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nom du FIMECO -->
                    <div class="lg:col-span-2">
                        <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                            Nom du FIMECO <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" value="<?php echo e(old('nom')); ?>" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Ex: Construction du nouveau sanctuaire">
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

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Décrivez l'objectif et les détails du FIMECO..."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
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

                    <!-- Responsable -->
                    <div>
                        <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                            Responsable
                        </label>
                        <select id="responsable_id" name="responsable_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['responsable_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Sélectionner un responsable</option>
                            <?php $__currentLoopData = $responsables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($responsable->id); ?>" <?php echo e(old('responsable_id') == $responsable->id ? 'selected' : ''); ?>>
                                    <?php echo e($responsable->nom); ?> (<?php echo e($responsable->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['responsable_id'];
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

                    <!-- Montant cible -->
                    <div>
                        <label for="cible" class="block text-sm font-medium text-slate-700 mb-2">
                            Montant cible (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="cible" name="cible" value="<?php echo e(old('cible')); ?>" required min="1"
                                class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['cible'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="1000000">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-slate-500 text-sm">FCFA</span>
                            </div>
                        </div>
                        <?php $__errorArgs = ['cible'];
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

                <!-- Période -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="debut" class="block text-sm font-medium text-slate-700 mb-2">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="debut" name="debut" value="<?php echo e(old('debut', date('Y-m-d'))); ?>" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['debut'];
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

                    <!-- Date de fin -->
                    <div>
                        <label for="fin" class="block text-sm font-medium text-slate-700 mb-2">
                            Date de fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="fin" name="fin" value="<?php echo e(old('fin')); ?>" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['fin'];
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

                <!-- Aperçu des calculs -->
                <div id="apercu-calculs" class="hidden bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-calculator text-blue-600 mr-2"></i>
                        Aperçu des calculs
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-slate-600">Durée</div>
                            <div id="duree-jours" class="text-xl font-bold text-blue-600">-</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-slate-600">Cible formatée</div>
                            <div id="cible-formatee" class="text-xl font-bold text-green-600">-</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-slate-600">Moyenne par jour</div>
                            <div id="moyenne-jour" class="text-xl font-bold text-purple-600">-</div>
                        </div>
                    </div>
                    <div id="avertissements" class="mt-4 space-y-2"></div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <button type="button" onclick="validerDonnees()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        Valider les données
                    </button>
                    <button type="submit" id="btn-submit" disabled
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>
                        Créer le FIMECO
                    </button>
                    <a href="<?php echo e(route('private.fimecos.index')); ?>"
                        class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        <!-- Aide et conseils -->
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl shadow-lg border border-amber-200 p-6">
            <h3 class="text-lg font-semibold text-amber-800 mb-4 flex items-center">
                <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                Conseils pour créer un FIMECO efficace
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Nom claire et descriptif</div>
                            <div class="text-sm text-slate-600">Choisissez un nom qui explique clairement l'objectif</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Cible réaliste</div>
                            <div class="text-sm text-slate-600">Fixez un montant atteignable selon votre communauté</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Période adaptée</div>
                            <div class="text-sm text-slate-600">Laissez suffisamment de temps pour atteindre l'objectif</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Responsable engagé</div>
                            <div class="text-sm text-slate-600">Désignez quelqu'un de motivé pour suivre le projet</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            let validationData = null;

            function calculerApercu() {
                const debut = document.getElementById('debut').value;
                const fin = document.getElementById('fin').value;
                const cible = parseFloat(document.getElementById('cible').value) || 0;

                if (debut && fin && cible > 0) {
                    const dateDebut = new Date(debut);
                    const dateFin = new Date(fin);
                    const diffTime = dateFin - dateDebut;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 0) {
                        document.getElementById('duree-jours').textContent = `${diffDays} jours`;
                        document.getElementById('cible-formatee').textContent = new Intl.NumberFormat('fr-FR').format(cible) + ' FCFA';
                        document.getElementById('moyenne-jour').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(cible / diffDays)) + ' FCFA';

                        // Avertissements
                        const avertissements = document.getElementById('avertissements');
                        avertissements.innerHTML = '';

                        if (diffDays < 30) {
                            avertissements.innerHTML += `
                                <div class="flex items-center bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="text-sm">Durée très courte (moins de 30 jours)</span>
                                </div>
                            `;
                        }

                        if (diffDays > 365) {
                            avertissements.innerHTML += `
                                <div class="flex items-center bg-orange-100 text-orange-800 p-3 rounded-lg">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span class="text-sm">Durée très longue (plus d'un an)</span>
                                </div>
                            `;
                        }

                        if (cible < 10000) {
                            avertissements.innerHTML += `
                                <div class="flex items-center bg-blue-100 text-blue-800 p-3 rounded-lg">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    <span class="text-sm">Pour une cible faible, considérez une durée plus courte</span>
                                </div>
                            `;
                        }

                        if (cible > 1000000) {
                            avertissements.innerHTML += `
                                <div class="flex items-center bg-purple-100 text-purple-800 p-3 rounded-lg">
                                    <i class="fas fa-bullhorn mr-2"></i>
                                    <span class="text-sm">Pour une cible élevée, assurez-vous d'avoir un plan de communication robuste</span>
                                </div>
                            `;
                        }

                        document.getElementById('apercu-calculs').classList.remove('hidden');
                    }
                } else {
                    document.getElementById('apercu-calculs').classList.add('hidden');
                }
            }

            function validerDonnees() {
                const formData = new FormData();
                formData.append('nom', document.getElementById('nom').value);
                formData.append('cible', document.getElementById('cible').value);
                formData.append('debut', document.getElementById('debut').value);
                formData.append('fin', document.getElementById('fin').value);

                fetch('<?php echo e(route("private.fimecos.validateFimecoData")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        validationData = data;
                        document.getElementById('btn-submit').disabled = false;

                        // Afficher les avertissements et suggestions
                        const avertissements = document.getElementById('avertissements');

                        if (data.warnings && data.warnings.length > 0) {
                            data.warnings.forEach(warning => {
                                avertissements.innerHTML += `
                                    <div class="flex items-center bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span class="text-sm">${warning}</span>
                                    </div>
                                `;
                            });
                        }

                        if (data.suggestions && data.suggestions.length > 0) {
                            data.suggestions.forEach(suggestion => {
                                avertissements.innerHTML += `
                                    <div class="flex items-center bg-blue-100 text-blue-800 p-3 rounded-lg">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        <span class="text-sm">${suggestion}</span>
                                    </div>
                                `;
                            });
                        }

                        alert('Données validées avec succès ! Vous pouvez maintenant créer le FIMECO.');
                    } else {
                        let errorMessage = 'Erreurs de validation :\n';
                        Object.values(data.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '- ' + error + '\n';
                            });
                        });
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la validation');
                });
            }

            // Event listeners
            document.addEventListener('DOMContentLoaded', function() {
                ['debut', 'fin', 'cible'].forEach(id => {
                    document.getElementById(id).addEventListener('change', calculerApercu);
                });

                setTimeout(() => {
                    form.style.transition = 'all 0.5s ease';
                    form.style.opacity = '1';
                    // form.style.transform = 'translateY(0)';
                }, 100);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fimecos/create.blade.php ENDPATH**/ ?>