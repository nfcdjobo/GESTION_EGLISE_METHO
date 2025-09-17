<?php $__env->startSection('title', 'Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Paiements</h1>
            <p class="text-slate-500 mt-1">Suivi de tous vos paiements FIMECO - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <?php if(isset($stats)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($stats['total_paye'] ?? 0, 0, ',', ' ')); ?></p>
                        <p class="text-sm text-slate-500">Total payé (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['en_attente'] ?? 0); ?></p>
                        <p class="text-sm text-slate-500">En attente</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-times-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['refuses'] ?? 0); ?></p>
                        <p class="text-sm text-slate-500">Refusés</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.paiements.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="valide" <?php echo e(request('statut') == 'valide' ? 'selected' : ''); ?>>Validés</option>
                        <option value="en_attente" <?php echo e(request('statut') == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                        <option value="refuse" <?php echo e(request('statut') == 'refuse' ? 'selected' : ''); ?>>Refusés</option>
                        <option value="annule" <?php echo e(request('statut') == 'annule' ? 'selected' : ''); ?>>Annulés</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                    <select name="type_paiement" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="especes" <?php echo e(request('type_paiement') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                        <option value="cheque" <?php echo e(request('type_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                        <option value="virement" <?php echo e(request('type_paiement') == 'virement' ? 'selected' : ''); ?>>Virement</option>
                        <option value="carte" <?php echo e(request('type_paiement') == 'carte' ? 'selected' : ''); ?>>Carte</option>
                        <option value="mobile_money" <?php echo e(request('type_paiement') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="md:col-span-2 flex gap-2 pt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.paiements.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                Historique des Paiements (<?php echo e($meta['total'] ?? 0); ?>)
            </h2>
        </div>
        <div class="p-6">
            <?php if(count($payments ?? []) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">FIMECO</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Référence</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Validé par</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900"><?php echo e(\Carbon\Carbon::parse($payment['date_paiement'])->format('d/m/Y')); ?></div>
                                        <div class="text-xs text-slate-500"><?php echo e(\Carbon\Carbon::parse($payment['date_paiement'])->format('H:i')); ?></div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($payment['subscription']['fimeco']['nom'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-900"><?php echo e(number_format($payment['montant'], 0, ',', ' ')); ?> FCFA</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-600 capitalize"><?php echo e(str_replace('_', ' ', $payment['type_paiement'])); ?></div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php if($payment['reference_paiement']): ?>
                                            <code class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded"><?php echo e($payment['reference_paiement']); ?></code>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-sm">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($payment['statut'] === 'valide'): ?> bg-green-100 text-green-800
                                            <?php elseif($payment['statut'] === 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                            <?php elseif($payment['statut'] === 'refuse'): ?> bg-red-100 text-red-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php if($payment['statut'] === 'valide'): ?>
                                                <i class="fas fa-check mr-1"></i>
                                            <?php elseif($payment['statut'] === 'en_attente'): ?>
                                                <i class="fas fa-clock mr-1"></i>
                                            <?php elseif($payment['statut'] === 'refuse'): ?>
                                                <i class="fas fa-times mr-1"></i>
                                            <?php else: ?>
                                                <i class="fas fa-ban mr-1"></i>
                                            <?php endif; ?>
                                            <?php echo e(ucfirst(str_replace('_', ' ', $payment['statut']))); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php if($payment['validateur']): ?>
                                            <div class="text-sm text-slate-900"><?php echo e($payment['validateur']['name']); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($payment['date_validation'] ? \Carbon\Carbon::parse($payment['date_validation'])->format('d/m/Y') : ''); ?></div>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-sm">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <a href="<?php echo e(route('private.paiements.show', $payment['id'])); ?>" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('paiements.update')): ?>
                                            <?php if($payment['statut'] === 'en_attente'): ?>
                                                <a href="<?php echo e(route('private.paiements.edit', $payment['id'])); ?>" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-8 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium"><?php echo e(($meta['current_page'] - 1) * $meta['per_page'] + 1); ?></span> à <span class="font-medium"><?php echo e(min($meta['current_page'] * $meta['per_page'], $meta['total'])); ?></span>
                        sur <span class="font-medium"><?php echo e($meta['total']); ?></span> résultats
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if($meta['current_page'] > 1): ?>
                            <a href="<?php echo e(request()->fullUrlWithQuery(['page' => $meta['current_page'] - 1])); ?>" class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Précédent</a>
                        <?php endif; ?>
                        <?php if($meta['current_page'] < $meta['last_page']): ?>
                            <a href="<?php echo e(request()->fullUrlWithQuery(['page' => $meta['current_page'] + 1])); ?>" class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Suivant</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun paiement trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        <?php if(request()->hasAny(['statut', 'type_paiement', 'date_debut', 'date_fin'])): ?>
                            Aucun paiement ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Vous n'avez pas encore effectué de paiement.
                        <?php endif; ?>
                    </p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('paiements.create')): ?>
                    <a href="<?php echo e(route('private.subscriptions.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-hand-holding-usd mr-2"></i> Créer une Souscription
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Légende des statuts -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Guide des Statuts
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i> Validé
                    </span>
                    <span class="text-sm text-slate-600">Paiement accepté et comptabilisé</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i> En attente
                    </span>
                    <span class="text-sm text-slate-600">En cours de vérification</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times mr-1"></i> Refusé
                    </span>
                    <span class="text-sm text-slate-600">Paiement rejeté</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-ban mr-1"></i> Annulé
                    </span>
                    <span class="text-sm text-slate-600">Paiement annulé</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/paiements/index.blade.php ENDPATH**/ ?>