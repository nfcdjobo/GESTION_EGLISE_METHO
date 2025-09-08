<?php $__env->startSection('title', 'Détails de la Souscription'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            Souscription #<?php echo e(substr($subscription['id'], 0, 8)); ?>

        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.subscriptions.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Souscriptions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Souscription #<?php echo e(substr($subscription['id'], 0, 8)); ?></span>
                    </div>
                </li>
            </ol>
        </nav>
        <p class="text-slate-500 mt-1"><?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <?php if(in_array($subscription['statut'], ['active', 'partiellement_payee'])): ?>
                    <a href="<?php echo e(route('private.subscriptions.edit', $subscription['id'])); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                <?php endif; ?>
                <?php if($subscription['reste_a_payer'] > 0 && $subscription['statut'] !== 'annulee'): ?>
                    <a href="<?php echo e(route('private.paiements.create', $subscription->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-credit-card mr-2"></i> Solder la souscription
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('private.paiements.index', ['subscription_id' => $subscription['id']])); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Historique des Paiements
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations FIMECO -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-coins text-blue-600 mr-2"></i>
                        FIMECO Associée
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2"><?php echo e($subscription['fimeco']['nom']); ?></h3>
                            <?php if($subscription['fimeco']['description']): ?>
                                <p class="text-slate-600 mb-4"><?php echo e($subscription['fimeco']['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php if($subscription['fimeco']['statut'] === 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($subscription['fimeco']['statut'] === 'cloturee'): ?> bg-red-100 text-red-800
                            <?php else: ?> bg-yellow-100 text-yellow-800
                            <?php endif; ?>">
                            <?php echo e(ucfirst($subscription['fimeco']['statut'])); ?>

                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Période</h4>
                            <p class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($subscription['fimeco']['debut'])->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($subscription['fimeco']['fin'])->format('d/m/Y')); ?></p>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Objectif</h4>
                            <p class="text-slate-900 font-semibold"><?php echo e(number_format($subscription['fimeco']['cible'], 0, ',', ' ')); ?> FCFA</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Progression globale</h4>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: <?php echo e(min($subscription['fimeco']['pourcentage_realisation'], 100)); ?>%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-700"><?php echo e($subscription['fimeco']['pourcentage_realisation']); ?>%</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Souscripteurs</h4>
                            <p class="text-slate-900"><?php echo e($subscription['fimeco']['nombre_souscripteurs']); ?> membres</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de la souscription -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-file-invoice-dollar text-green-600 mr-2"></i>
                            Détails de la Souscription
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php if($subscription['statut'] === 'completement_payee'): ?> bg-green-100 text-green-800
                            <?php elseif($subscription['statut'] === 'partiellement_payee'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($subscription['statut'] === 'active'): ?> bg-blue-100 text-blue-800
                            <?php else: ?> bg-red-100 text-red-800
                            <?php endif; ?>">
                            <i class="fas fa-circle mr-2 text-xs"></i>
                            <?php echo e(ucfirst(str_replace('_', ' ', $subscription['statut']))); ?>

                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Date de souscription</h4>
                            <p class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y')); ?></p>
                        </div>
                        <?php if($subscription['date_echeance']): ?>
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Date d'échéance</h4>
                                <p class="text-slate-900 <?php echo e(\Carbon\Carbon::parse($subscription['date_echeance'])->isPast() && $subscription['reste_a_payer'] > 0 ? 'text-red-600 font-semibold' : ''); ?>">
                                    <?php echo e(\Carbon\Carbon::parse($subscription['date_echeance'])->format('d/m/Y')); ?>

                                    <?php if(\Carbon\Carbon::parse($subscription['date_echeance'])->isPast() && $subscription['reste_a_payer'] > 0): ?>
                                        <span class="text-red-600 text-sm ml-2">(Échue)</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Progression financière -->
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-2">
                                <?php echo e(number_format($subscription['montant_paye'], 0, ',', ' ')); ?> FCFA
                            </div>
                            <div class="text-sm text-slate-600">
                                payés sur <?php echo e(number_format($subscription['montant_souscrit'], 0, ',', ' ')); ?> FCFA
                            </div>
                        </div>

                        <?php
                            $pourcentagePaye = ($subscription['montant_souscrit'] > 0) ?
                                              round(($subscription['montant_paye'] / $subscription['montant_souscrit']) * 100, 1) : 0;
                        ?>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Progression</span>
                                <span class="font-semibold text-slate-900"><?php echo e($pourcentagePaye); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-300"
                                     style="width: <?php echo e($pourcentagePaye > 100 ? 100 : $pourcentagePaye); ?>%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-600"><?php echo e(number_format($subscription['montant_paye'], 0, ',', ' ')); ?></div>
                                <div class="text-sm text-green-700">Montant payé</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-xl">
                                <div class="text-2xl font-bold text-orange-600"><?php echo e(number_format($subscription['reste_a_payer'], 0, ',', ' ')); ?></div>
                                <div class="text-sm text-orange-700">Reste à payer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des paiements -->
            <?php if(isset($subscription['payments']) && count($subscription['payments']) > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-purple-600 mr-2"></i>
                            Historique des Paiements (<?php echo e(count($subscription['payments'])); ?>)
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Validé par</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php $__currentLoopData = $subscription['payments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-4 text-slate-900">
                                                <?php echo e(\Carbon\Carbon::parse($payment['date_paiement'])->format('d/m/Y')); ?>

                                            </td>
                                            <td class="px-4 py-4 text-slate-900 font-medium">
                                                <?php echo e(number_format($payment['montant'], 0, ',', ' ')); ?> FCFA
                                            </td>
                                            <td class="px-4 py-4 text-slate-600">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $payment['type_paiement']))); ?>

                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($payment['statut'] === 'valide'): ?> bg-green-100 text-green-800
                                                    <?php elseif($payment['statut'] === 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                                    <?php elseif($payment['statut'] === 'refuse'): ?> bg-red-100 text-red-800
                                                    <?php else: ?> bg-gray-100 text-gray-800
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $payment['statut']))); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-slate-600">
                                                <?php if($payment['validateur']): ?>
                                                    <?php echo e($payment['validateur']['name']); ?>

                                                    <div class="text-xs text-slate-500">
                                                        <?php echo e($payment['date_validation'] ? \Carbon\Carbon::parse($payment['date_validation'])->format('d/m/Y') : ''); ?>

                                                    </div>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Métriques rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                        Métriques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php
                        $paiementsValides = collect($subscription['payments'] ?? [])->where('statut', 'valide');
                        $nombrePaiements = $paiementsValides->count();
                        $montantMoyenPaiement = $nombrePaiements > 0 ? $paiementsValides->avg('montant') : 0;
                        $dernierPaiement = $paiementsValides->sortByDesc('date_paiement')->first();
                    ?>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Nombre de paiements:</span>
                        <span class="text-sm font-semibold text-slate-900"><?php echo e($nombrePaiements); ?></span>
                    </div>

                    <?php if($montantMoyenPaiement > 0): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Paiement moyen:</span>
                            <span class="text-sm font-semibold text-slate-900"><?php echo e(number_format($montantMoyenPaiement, 0, ',', ' ')); ?> FCFA</span>
                        </div>
                    <?php endif; ?>

                    <?php if($dernierPaiement): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Dernier paiement:</span>
                            <span class="text-sm font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($dernierPaiement['date_paiement'])->format('d/m/Y')); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Taux de réalisation:</span>
                        <span class="text-sm font-semibold text-green-600"><?php echo e($pourcentagePaye); ?>%</span>
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
                    <?php if($subscription['reste_a_payer'] > 0 && $subscription['statut'] !== 'annulee'): ?>
                        <a href="<?php echo e(route('private.paiements.create', $subscription->id)); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Solder la souscription
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('private.fimecos.show', $subscription['fimeco']['id'])); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-coins mr-2"></i> Voir la FIMECO
                    </a>

                    <a href="<?php echo e(route('private.paiements.index', ['subscription_id' => $subscription['id']])); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Tous les Paiements
                    </a>

                    <a href="<?php echo e(route('private.subscriptions.index')); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-gray-600 mr-2"></i>
                        Informations Système
                    </h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">ID:</span>
                        <code class="px-2 py-1 bg-slate-100 text-slate-800 rounded text-xs"><?php echo e($subscription['id']); ?></code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Version:</span>
                        <span class="text-slate-900">#<?php echo e($subscription['version'] ?? 0); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Créé le:</span>
                        <span class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($subscription['created_at'])->format('d/m/Y H:i')); ?></span>
                    </div>
                    <?php if($subscription['updated_at'] !== $subscription['created_at']): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Modifié le:</span>
                            <span class="text-slate-900"><?php echo e(\Carbon\Carbon::parse($subscription['updated_at'])->format('d/m/Y H:i')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/subscriptions/show.blade.php ENDPATH**/ ?>