<?php $__env->startSection('title', 'Détails FIMECO'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent"><?php echo e($fimeco['nom']); ?></h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.fimecos.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-coins mr-2"></i>
                        FIMECO
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500"><?php echo e($fimeco['nom']); ?></span>
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Fimeco::find($fimeco['id']))): ?>
                    <?php if($fimeco['statut'] !== 'cloturee'): ?>
                        <a href="<?php echo e(route('private.fimecos.edit', $fimeco['id'])); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo e(route('private.fimecos.statistiques', $fimeco['id'])); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i> Statistiques
                </a>
                <?php if($fimeco['est_en_cours']): ?>
                    <a href="<?php echo e(route('private.subscriptions.create')); ?>?fimero=<?php echo e($fimeco->id); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-hand-holding-usd mr-2"></i> Souscrire
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Fimeco::find($fimeco['id']))): ?>
                    <?php if($fimeco['statut'] !== 'cloturee'): ?>
                        <form action="<?php echo e(route('private.fimecos.cloturer', $fimeco['id'])); ?>" method="POST" class="inline" id="clotureForm">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmerCloture()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-lock mr-2"></i> Clôturer
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails généraux -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php if($fimeco['statut'] === 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($fimeco['statut'] === 'cloturee'): ?> bg-red-100 text-red-800
                            <?php else: ?> bg-yellow-100 text-yellow-800
                            <?php endif; ?>">
                            <i class="fas fa-circle mr-2 text-xs"></i>
                            <?php echo e(ucfirst($fimeco['statut'])); ?>

                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <?php if($fimeco['description']): ?>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-700 mb-2">Description</h3>
                            <p class="text-slate-600 leading-relaxed"><?php echo e($fimeco['description']); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Date de début</h3>
                                <p class="text-slate-900 font-medium"><?php echo e(\Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y')); ?></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Date de fin</h3>
                                <p class="text-slate-900 font-medium"><?php echo e(\Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y')); ?></p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Durée</h3>
                                <p class="text-slate-900 font-medium">
                                    <?php echo e(\Carbon\Carbon::parse($fimeco['debut'])->diffInDays(\Carbon\Carbon::parse($fimeco['fin']))); ?> jours
                                </p>
                            </div>
                            <?php if(isset($fimeco['responsable']) && $fimeco['responsable']): ?>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Responsable</h3>
                                    <p class="text-slate-900 font-medium"><?php echo e($fimeco['responsable']['name']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress et objectif -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bullseye text-green-600 mr-2"></i>
                        Progression
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-2">
                                <?php echo e(number_format($fimeco['total_paye'], 0, ',', ' ')); ?> FCFA
                            </div>

                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Progression</span>
                                <span class="font-semibold text-slate-900"><?php echo e($fimeco['pourcentage_realisation']); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300"
                                     style="width: <?php echo e(min($fimeco['pourcentage_realisation'], 100)); ?>%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600"><?php echo e($fimeco->subscriptions->count()); ?></div>
                                <div class="text-sm text-blue-700">Souscripteurs</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des souscriptions -->
            <?php if(isset($fimeco['subscriptions']) && count($fimeco['subscriptions']) > 0): ?>
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Souscriptions (<?php echo e(count($fimeco['subscriptions'])); ?>)
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Souscripteur</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Payé</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php $__currentLoopData = $fimeco['subscriptions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-4">
                                                <a href="<?php echo e(route('private.users.show', $subscription->souscripteur->nom)); ?>" class="font-medium text-blue-900">
                                                    <?php echo e($subscription->souscripteur->nom ?? 'N/A'); ?>

                                                </a>
                                            </td>
                                            <td class="px-4 py-4 text-slate-900 font-medium">
                                                <?php echo e(number_format($subscription['montant_souscrit'], 0, ',', ' ')); ?> FCFA
                                            </td>
                                            <td class="px-4 py-4 text-green-600 font-medium">
                                                <?php echo e(number_format($subscription['montant_paye'], 0, ',', ' ')); ?> FCFA
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($subscription['statut'] === 'completement_payee'): ?> bg-green-100 text-green-800
                                                    <?php elseif($subscription['statut'] === 'partiellement_payee'): ?> bg-yellow-100 text-yellow-800
                                                    <?php elseif($subscription['statut'] === 'active'): ?> bg-blue-100 text-blue-800
                                                    <?php else: ?> bg-red-100 text-red-800
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $subscription['statut']))); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-slate-600">
                                                <?php echo e(\Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y')); ?>

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

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Statistiques détaillées -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php $__currentLoopData = $statistiques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700 capitalize">
                                <?php echo e(str_replace('_', ' ', $key)); ?>:
                            </span>
                            <span class="text-sm font-semibold text-slate-900">
                                <?php if(is_numeric($value)): ?>
                                    <?php if($key === 'pourcentage_realisation'): ?>
                                        <?php echo e($value); ?>%
                                    <?php elseif(in_array($key, ['total_paye', 'total_souscriptions', 'reste_a_collecter', 'montant_moyen_souscription'])): ?>
                                        <?php echo e(number_format($value, 0, ',', ' ')); ?> FCFA
                                    <?php else: ?>
                                        <?php echo e($value); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Calendrier et dates importantes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar text-pink-600 mr-2"></i>
                        Timeline
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full mt-1.5"></div>
                        <div>
                            <h3 class="font-medium text-slate-900">Début</h3>
                            <p class="text-sm text-slate-600"><?php echo e(\Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y')); ?></p>
                        </div>
                    </div>

                    <?php if($fimeco['est_en_cours']): ?>
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5 animate-pulse"></div>
                            <div>
                                <h3 class="font-medium text-slate-900">Maintenant</h3>
                                <p class="text-sm text-slate-600"><?php echo e(\Carbon\Carbon::now()->format('d/m/Y')); ?></p>
                                <p class="text-xs text-blue-600">En cours</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 <?php echo e($fimeco['est_terminee'] ? 'bg-red-500' : 'bg-gray-300'); ?> rounded-full mt-1.5"></div>
                        <div>
                            <h3 class="font-medium text-slate-900">Fin</h3>
                            <p class="text-sm text-slate-600"><?php echo e(\Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y')); ?></p>
                            <?php if($fimeco['est_terminee']): ?>
                                <p class="text-xs text-red-600">Terminée</p>
                            <?php else: ?>
                                <p class="text-xs text-gray-600">
                                    <?php echo e(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($fimeco['fin']))); ?> jours restants
                                </p>
                            <?php endif; ?>
                        </div>
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
                    <a href="<?php echo e(route('private.subscriptions.index', ['fimeco_id' => $fimeco['id']])); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Voir Souscriptions
                    </a>
                    <a href="<?php echo e(route('private.paiements.index', ['fimeco_id' => $fimeco['id']])); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i> Voir Paiements
                    </a>
                    <a href="<?php echo e(route('private.fimecos.index')); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de clôture -->
<div id="clotureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-lock text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Clôturer la FIMECO</h3>
            </div>
            <p class="text-slate-600 mb-4">Êtes-vous sûr de vouloir clôturer cette FIMECO ? Cette action est définitive et empêchera toute nouvelle souscription.</p>

            <div class="mb-4">
                <label for="commentaireCloture" class="block text-sm font-medium text-slate-700 mb-2">Commentaire (optionnel)</label>
                <textarea id="commentaireCloture" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Motif de la clôture..."></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="fermerModalCloture()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="executerCloture()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Clôturer définitivement
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmerCloture() {
    document.getElementById('clotureModal').classList.remove('hidden');
}

function fermerModalCloture() {
    document.getElementById('clotureModal').classList.add('hidden');
}

function executerCloture() {
    const commentaire = document.getElementById('commentaireCloture').value;
    const form = document.getElementById('clotureForm');

    // Ajouter le commentaire au formulaire
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'commentaire';
    input.value = commentaire;
    form.appendChild(input);

    form.submit();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('clotureModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalCloture();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fimecos/show.blade.php ENDPATH**/ ?>