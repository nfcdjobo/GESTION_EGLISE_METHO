<?php $__env->startSection('title', 'Modifier la Souscription'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Modifier la Souscription
                </h1>
                <p class="text-slate-500 mt-1">
                    <?php echo e($subscription['souscripteur']['nom'] ?? 'Souscripteur'); ?> - <?php echo e($subscription['fimeco']['nom'] ?? 'FIMECO'); ?>

                </p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('private.subscriptions.show', $subscription['id'])); ?>"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
                </a>
            </div>
        </div>

        <!-- Alert d'information sur les modifications -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <?php if(!$peut_modifier_montant): ?>
                                <li>Le montant souscrit ne peut plus être modifié car des paiements ont été effectués.</li>
                            <?php endif; ?>
                            <li>La modification de l'échéance peut affecter les alertes et rappels.</li>
                            <li>Toutes les modifications sont tracées et journalisées.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('private.subscriptions.update', $subscription['id'])); ?>"
              class="space-y-8"
              id="subscriptionEditForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Informations non modifiables -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-lock text-slate-500 mr-2"></i>
                        Informations non modifiables
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Ces informations ne peuvent pas être changées après création
                    </p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Souscripteur -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Souscripteur</label>
                            <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-xl">
                                <?php if($subscription['souscripteur']['photo_profil'] ?? false): ?>
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="<?php echo e(asset('storage/' . $subscription['souscripteur']['photo_profil'])); ?>"
                                         alt="<?php echo e($subscription['souscripteur']['nom']); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            <?php echo e(strtoupper(substr($subscription['souscripteur']['nom'] ?? 'U', 0, 1))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="font-medium text-slate-900"><?php echo e($subscription['souscripteur']['nom'] ?? 'N/A'); ?></div>
                                    <div class="text-sm text-slate-500"><?php echo e($subscription['souscripteur']['email'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- FIMECO -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">FIMECO</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <div class="font-medium text-slate-900"><?php echo e($subscription['fimeco']['nom'] ?? 'N/A'); ?></div>
                                <div class="text-sm text-slate-500 mt-1">
                                    Progression: <?php echo e(number_format($subscription['fimeco']['progression'] ?? 0, 1)); ?>%
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                    <div class="h-1.5 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"
                                         style="width: <?php echo e(min($subscription['fimeco']['progression'] ?? 0, 100)); ?>%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Date de souscription -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date de souscription</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <div class="text-slate-900">
                                    <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y')); ?>

                                </div>
                            </div>
                        </div>

                        <!-- Statut actuel -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut actuel</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e(($subscription['statut'] ?? '') === 'completement_payee' ? 'bg-green-100 text-green-800' : (($subscription['statut'] ?? '') === 'partiellement_payee' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>">
                                    <?php echo e($subscription['statut_libelle'] ?? 'N/A'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Champs modifiables -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-edit text-yellow-600 mr-2"></i>
                        Informations modifiables
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Modifiez uniquement les champs autorisés selon l'état de la souscription
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Montant souscrit -->
                        <div>
                            <label for="montant_souscrit" class="block text-sm font-medium text-slate-700 mb-2">
                                Montant souscrit (FCFA)
                                <?php if(!$peut_modifier_montant): ?>
                                    <span class="text-red-500">*</span>
                                <?php endif; ?>
                            </label>
                            <?php if($peut_modifier_montant): ?>
                                <div class="relative">
                                    <input type="number" name="montant_souscrit" id="montant_souscrit"
                                           min="1000" step="500" required
                                           value="<?php echo e(old('montant_souscrit', $subscription['montant_souscrit'] ?? '')); ?>"
                                           class="w-full pl-4 pr-16 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['montant_souscrit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-slate-500 text-sm">FCFA</span>
                                    </div>
                                </div>
                                <div class="mt-1 text-xs text-slate-500">
                                    Montant minimum: 1,000 FCFA
                                </div>
                                <?php $__errorArgs = ['montant_souscrit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php else: ?>
                                <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-lock text-red-500 mr-2"></i>
                                        <div>
                                            <div class="font-medium text-red-800"><?php echo e(number_format($subscription['montant_souscrit'] ?? 0, 0, ',', ' ')); ?> FCFA</div>
                                            <div class="text-sm text-red-600">Non modifiable (paiements effectués)</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="montant_souscrit" value="<?php echo e($subscription['montant_souscrit'] ?? 0); ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Date d'échéance -->
                        <div>
                            <label for="date_echeance" class="block text-sm font-medium text-slate-700 mb-2">
                                Date d'échéance
                                <?php if(!$peut_modifier_echeance): ?>
                                    <span class="text-red-500">*</span>
                                <?php endif; ?>
                            </label>
                            <?php if($peut_modifier_echeance): ?>
                                <input type="date" name="date_echeance" id="date_echeance"
                                       value="<?php echo e(old('date_echeance', $subscription['date_echeance'] ? \Carbon\Carbon::parse($subscription['date_echeance'])->format('Y-m-d') : '')); ?>"
                                       min="<?php echo e(\Carbon\Carbon::parse($subscription['date_souscription'])->format('Y-m-d')); ?>"
                                       max="<?php echo e($subscription['fimeco']['fin'] ? \Carbon\Carbon::parse($subscription['fimeco']['fin'])->format('Y-m-d') : ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['date_echeance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <div class="mt-1 text-xs text-slate-500">
                                    Doit être après la date de souscription et avant la fin du FIMECO
                                </div>
                                <?php $__errorArgs = ['date_echeance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                <!-- Boutons de suggestion -->
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button type="button" onclick="setNewDeadline(30)"
                                            class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                        +30 jours
                                    </button>
                                    <button type="button" onclick="setNewDeadline(60)"
                                            class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                        +60 jours
                                    </button>
                                    <button type="button" onclick="clearDeadline()"
                                            class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">
                                        Supprimer échéance
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-lock text-red-500 mr-2"></i>
                                        <div>
                                            <?php if($subscription['date_echeance'] ?? false): ?>
                                                <div class="font-medium text-red-800"><?php echo e(\Carbon\Carbon::parse($subscription['date_echeance'])->format('d/m/Y')); ?></div>
                                                <div class="text-sm text-red-600">Non modifiable (souscription complète)</div>
                                            <?php else: ?>
                                                <div class="font-medium text-red-800">Aucune échéance</div>
                                                <div class="text-sm text-red-600">Non modifiable (souscription complète)</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="date_echeance" value="<?php echo e($subscription['date_echeance'] ? \Carbon\Carbon::parse($subscription['date_echeance'])->format('Y-m-d') : ''); ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informations sur les changements -->
                    <?php if($peut_modifier_montant || $peut_modifier_echeance): ?>
                        <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                            <div class="text-sm text-blue-800">
                                <div class="font-medium mb-2">Impact des modifications :</div>
                                <ul class="list-disc pl-5 space-y-1">
                                    <?php if($peut_modifier_montant): ?>
                                        <li>La modification du montant recalculera automatiquement le reste à payer.</li>
                                        <li>Si le nouveau montant est inférieur aux paiements déjà effectués, un remboursement sera nécessaire.</li>
                                    <?php endif; ?>
                                    <?php if($peut_modifier_echeance): ?>
                                        <li>La modification de l'échéance mettra à jour les alertes de retard.</li>
                                        <li>Les notifications automatiques seront recalculées.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Résumé des paiements (information) -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Résumé actuel
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        État actuel de la souscription (mis à jour automatiquement)
                    </p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-xl">
                            <div class="text-lg font-bold text-slate-900"><?php echo e(number_format($subscription['montant_souscrit'] ?? 0, 0, ',', ' ')); ?></div>
                            <div class="text-sm text-slate-600">Montant souscrit (FCFA)</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="text-lg font-bold text-green-600"><?php echo e(number_format($subscription['montant_paye'] ?? 0, 0, ',', ' ')); ?></div>
                            <div class="text-sm text-slate-600">Montant payé (FCFA)</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-xl">
                            <div class="text-lg font-bold text-orange-600"><?php echo e(number_format($subscription['reste_a_payer'] ?? 0, 0, ',', ' ')); ?></div>
                            <div class="text-sm text-slate-600">Reste à payer (FCFA)</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="text-lg font-bold text-blue-600"><?php echo e(number_format($subscription['progression'] ?? 0, 1)); ?>%</div>
                            <div class="text-sm text-slate-600">Progression</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-slate-700">Progression globale</span>
                            <span class="text-sm font-bold text-slate-900"><?php echo e(number_format($subscription['progression'] ?? 0, 1)); ?>%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="h-3 rounded-full <?php echo e(($subscription['progression'] ?? 0) >= 100 ? 'bg-gradient-to-r from-green-500 to-emerald-500' : (($subscription['progression'] ?? 0) >= 75 ? 'bg-gradient-to-r from-blue-500 to-purple-500' : (($subscription['progression'] ?? 0) >= 50 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-red-500 to-pink-500'))); ?>"
                                 style="width: <?php echo e(min($subscription['progression'] ?? 0, 100)); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <a href="<?php echo e(route('private.subscriptions.show', $subscription['id'])); ?>"
                    class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>

                <?php if($peut_modifier_montant || $peut_modifier_echeance): ?>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                <?php else: ?>
                    <div class="flex-1 p-3 bg-gray-100 text-gray-500 rounded-xl text-center">
                        <i class="fas fa-lock mr-2"></i> Aucune modification possible
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Set new deadline relative to today
            function setNewDeadline(days) {
                const date = new Date();
                date.setDate(date.getDate() + days);

                // Check FIMECO end date
                const fimecoEnd = new Date("<?php echo e($subscription['fimeco']['fin'] ? \Carbon\Carbon::parse($subscription['fimeco']['fin'])->format('Y-m-d') : '2030-12-31'); ?>");
                if (date > fimecoEnd) {
                    date = fimecoEnd;
                }

                document.getElementById('date_echeance').value = date.toISOString().split('T')[0];
            }

            // Clear deadline
            function clearDeadline() {
                document.getElementById('date_echeance').value = '';
            }

            // Form validation
            document.getElementById('subscriptionEditForm').addEventListener('submit', function(e) {
                <?php if($peut_modifier_montant): ?>
                    const montant = parseFloat(document.getElementById('montant_souscrit').value);
                    const montantPaye = <?php echo e($subscription['montant_paye'] ?? 0); ?>;

                    if (montant < montantPaye) {
                        e.preventDefault();
                        if (!confirm(`Le nouveau montant (${new Intl.NumberFormat('fr-FR').format(montant)} FCFA) est inférieur au montant déjà payé (${new Intl.NumberFormat('fr-FR').format(montantPaye)} FCFA). Cela nécessitera un remboursement. Voulez-vous continuer ?`)) {
                            return;
                        }
                    }
                <?php endif; ?>

                <?php if($peut_modifier_echeance): ?>
                    const dateEcheance = document.getElementById('date_echeance').value;
                    const aujourd_hui = new Date().toISOString().split('T')[0];

                    if (dateEcheance && dateEcheance < aujourd_hui) {
                        if (!confirm('La date d\'échéance est dans le passé. Cela marquera automatiquement la souscription comme en retard. Voulez-vous continuer ?')) {
                            e.preventDefault();
                            return;
                        }
                    }
                <?php endif; ?>
            });

            // Animation au chargement
            document.addEventListener('DOMContentLoaded', function() {
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
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/subscriptions/edit.blade.php ENDPATH**/ ?>