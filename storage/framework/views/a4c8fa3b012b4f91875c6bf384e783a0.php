<?php $__env->startSection('title', 'Gestion des FIMECO'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des FIMECO</h1>
        <p class="text-slate-500 mt-1">Administration des campagnes FIMECO - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Fimeco::class)): ?>
                        <a href="<?php echo e(route('private.fimecos.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle FIMECO
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('private.fimecos.bord')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Tableau de Bord
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.fimecos.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="active" <?php echo e(request('statut') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('statut') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        <option value="cloturee" <?php echo e(request('statut') == 'cloturee' ? 'selected' : ''); ?>>Clôturée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les périodes</option>
                        <option value="en_cours" <?php echo e(request('en_cours') ? 'selected' : ''); ?>>En cours</option>
                        <option value="terminee" <?php echo e(request('terminee') ? 'selected' : ''); ?>>Terminées</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2 pt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('private.fimecos.index')); ?>" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des FIMECO -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-list text-purple-600 mr-2"></i>
                Liste des FIMECO (<?php echo e($meta['total']); ?>)
            </h2>
        </div>
        <div class="p-6">
            <?php if(count($fimeco) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $fimeco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-2"><?php echo e($item['nom']); ?></h3>
                                        <p class="text-sm text-slate-600"><?php echo e(Str::limit($item['description'], 100)); ?></p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($item['statut'] === 'active'): ?> bg-green-100 text-green-800
                                        <?php elseif($item['statut'] === 'cloturee'): ?> bg-red-100 text-red-800
                                        <?php else: ?> bg-yellow-100 text-yellow-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst($item['statut'])); ?>

                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">Période:</span>
                                        <span class="font-medium"><?php echo e($item['debut']); ?> - <?php echo e($item['fin']); ?></span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">Réalisé:</span>
                                        <span class="font-medium text-green-600"><?php echo e(number_format($item['total_paye'], 0, ',', ' ')); ?> FCFA</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: <?php echo e(min($item['pourcentage_realisation'], 100)); ?>%"></div>
                                    </div>
                                    
                                </div>

                                <div class="flex items-center gap-2 mt-6 pt-4 border-t border-slate-200">
                                    <a href="<?php echo e(route('private.fimecos.show', $item['id'])); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors">
                                        <i class="fas fa-eye mr-1 text-sm"></i> Voir
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Fimeco::find($item['id']))): ?>
                                        <a href="<?php echo e(route('private.fimecos.edit', $item['id'])); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                                            <i class="fas fa-edit mr-1 text-sm"></i> Modifier
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <i class="fas fa-coins text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune FIMECO trouvée</h3>
                    <p class="text-slate-500 mb-6">Commencez par créer votre première campagne FIMECO.</p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Fimeco::class)): ?>
                        <a href="<?php echo e(route('private.fimecos.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer une FIMECO
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fimecos/index.blade.php ENDPATH**/ ?>