<?php $__env->startSection('title', 'Mes Statistiques'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Mes Statistiques FIMECO</h1>
            <p class="text-slate-500 mt-1">Analyse de votre activité et performance - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
        </div>
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
                        <span class="text-sm font-medium text-slate-500">Mes Statistiques</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['total_souscrit'] ?? 0, 0, ',', ' ')); ?></p>
                    <p class="text-sm text-slate-500">Total souscrit (FCFA)</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['total_paye'] ?? 0, 0, ',', ' ')); ?></p>
                    <p class="text-sm text-slate-500">Total payé (FCFA)</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['total_reste'] ?? 0, 0, ',', ' ')); ?></p>
                    <p class="text-sm text-slate-500">Reste à payer (FCFA)</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-list text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['nombre_souscriptions'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Souscriptions actives</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance globale -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Performance Globale
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Taux de paiement global -->
                <?php
                    $tauxPaiementGlobal = ($statistiques['total_souscrit'] ?? 0) > 0 ?
                                         round((($statistiques['total_paye'] ?? 0) / ($statistiques['total_souscrit'] ?? 1)) * 100, 1) : 0;
                ?>

                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo e($tauxPaiementGlobal); ?>%</div>
                    <div class="text-sm text-slate-600">Taux de paiement global</div>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 h-4 rounded-full transition-all duration-500"
                         style="width: <?php echo e($tauxPaiementGlobal); ?>%"></div>
                </div>

                <!-- Détails financiers -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600"><?php echo e(number_format($statistiques['total_paye'] ?? 0, 0, ',', ' ')); ?></div>
                        <div class="text-sm text-green-700">Payé</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-orange-600"><?php echo e(number_format($statistiques['total_reste'] ?? 0, 0, ',', ' ')); ?></div>
                        <div class="text-sm text-orange-700">À payer</div>
                    </div>
                </div>

                <!-- Moyennes -->
                <?php if(($statistiques['nombre_souscriptions'] ?? 0) > 0): ?>
                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Souscription moyenne:</span>
                            <span class="text-sm font-bold text-slate-900">
                                <?php echo e(number_format(($statistiques['total_souscrit'] ?? 0) / ($statistiques['nombre_souscriptions'] ?? 1), 0, ',', ' ')); ?> FCFA
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Paiement moyen:</span>
                            <span class="text-sm font-bold text-slate-900">
                                <?php echo e(number_format(($statistiques['total_paye'] ?? 0) / ($statistiques['nombre_souscriptions'] ?? 1), 0, ',', ' ')); ?> FCFA
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Répartition par Statut
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php if(isset($statistiques['repartition_statuts'])): ?>
                    <?php $__currentLoopData = $statistiques['repartition_statuts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statut => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($statut === 'completement_payee'): ?> bg-green-100 text-green-800
                                    <?php elseif($statut === 'partiellement_payee'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($statut === 'active'): ?> bg-blue-100 text-blue-800
                                    <?php else: ?> bg-red-100 text-red-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $statut))); ?>

                                </span>
                                <span class="text-sm text-slate-700"><?php echo e($data['nombre'] ?? 0); ?> souscriptions</span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-slate-900"><?php echo e(number_format($data['montant'] ?? 0, 0, ',', ' ')); ?> FCFA</div>
                                <div class="text-xs text-slate-500">
                                    <?php echo e(($statistiques['total_souscrit'] ?? 0) > 0 ? round((($data['montant'] ?? 0) / ($statistiques['total_souscrit'] ?? 1)) * 100, 1) : 0); ?>%
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-3xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucune donnée de répartition disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Historique mensuel -->
    <?php if(isset($statistiques['evolution_mensuelle']) && count($statistiques['evolution_mensuelle']) > 0): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-area text-green-600 mr-2"></i>
                    Évolution Mensuelle
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $statistiques['evolution_mensuelle']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periode => $donnees): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-lg">
                            <div class="font-medium text-slate-900 mb-2"><?php echo e($periode); ?></div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Souscriptions:</span>
                                    <span class="font-medium"><?php echo e($donnees['souscriptions'] ?? 0); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Paiements:</span>
                                    <span class="font-medium"><?php echo e($donnees['paiements'] ?? 0); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Montant:</span>
                                    <span class="font-medium text-green-600"><?php echo e(number_format($donnees['montant'] ?? 0, 0, ',', ' ')); ?> FCFA</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Comparaison et objectifs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance par FIMECO -->
        <?php if(isset($statistiques['performance_par_fimeco']) && count($statistiques['performance_par_fimeco']) > 0): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trophy text-amber-600 mr-2"></i>
                        Performance par FIMECO
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php $__currentLoopData = $statistiques['performance_par_fimeco']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fimeco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 border border-slate-200 rounded-lg">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-medium text-slate-900"><?php echo e($fimeco['nom']); ?></h3>
                                <span class="text-sm font-bold text-green-600"><?php echo e($fimeco['taux_paiement']); ?>%</span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Souscrit:</span>
                                    <span><?php echo e(number_format($fimeco['montant_souscrit'], 0, ',', ' ')); ?> FCFA</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Payé:</span>
                                    <span class="text-green-600"><?php echo e(number_format($fimeco['montant_paye'], 0, ',', ' ')); ?> FCFA</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                                     style="width: <?php echo e($fimeco['taux_paiement']); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Conseils et recommandations -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                    Recommandations
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php
                    $recommendations = [];

                    if($tauxPaiementGlobal < 50) {
                        $recommendations[] = [
                            'type' => 'warning',
                            'icon' => 'fas fa-exclamation-triangle',
                            'title' => 'Améliorer le taux de paiement',
                            'message' => 'Votre taux de paiement est en dessous de 50%. Planifiez vos paiements régulièrement.'
                        ];
                    }

                    if(($statistiques['total_reste'] ?? 0) > 0) {
                        $recommendations[] = [
                            'type' => 'info',
                            'icon' => 'fas fa-calendar-alt',
                            'title' => 'Paiements en attente',
                            'message' => 'Vous avez encore ' . number_format($statistiques['total_reste'], 0, ',', ' ') . ' FCFA à payer.'
                        ];
                    }

                    if($tauxPaiementGlobal >= 90) {
                        $recommendations[] = [
                            'type' => 'success',
                            'icon' => 'fas fa-trophy',
                            'title' => 'Excellent engagement !',
                            'message' => 'Vous maintenez un excellent taux de paiement. Continuez ainsi !'
                        ];
                    }

                    if(($statistiques['nombre_souscriptions'] ?? 0) === 1) {
                        $recommendations[] = [
                            'type' => 'info',
                            'icon' => 'fas fa-plus-circle',
                            'title' => 'Diversifier vos engagements',
                            'message' => 'Considérez souscrire à d\'autres FIMECO pour diversifier vos contributions.'
                        ];
                    }
                ?>

                <?php if(count($recommendations) > 0): ?>
                    <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 rounded-lg border
                            <?php if($rec['type'] === 'success'): ?> bg-green-50 border-green-200
                            <?php elseif($rec['type'] === 'warning'): ?> bg-yellow-50 border-yellow-200
                            <?php else: ?> bg-blue-50 border-blue-200
                            <?php endif; ?>">
                            <div class="flex">
                                <i class="<?php echo e($rec['icon']); ?>

                                    <?php if($rec['type'] === 'success'): ?> text-green-400
                                    <?php elseif($rec['type'] === 'warning'): ?> text-yellow-400
                                    <?php else: ?> text-blue-400
                                    <?php endif; ?> mt-0.5 mr-3"></i>
                                <div class="text-sm">
                                    <p class="font-medium
                                        <?php if($rec['type'] === 'success'): ?> text-green-800
                                        <?php elseif($rec['type'] === 'warning'): ?> text-yellow-800
                                        <?php else: ?> text-blue-800
                                        <?php endif; ?>"><?php echo e($rec['title']); ?></p>
                                    <p class="mt-1
                                        <?php if($rec['type'] === 'success'): ?> text-green-700
                                        <?php elseif($rec['type'] === 'warning'): ?> text-yellow-700
                                        <?php else: ?> text-blue-700
                                        <?php endif; ?>"><?php echo e($rec['message']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-3xl text-green-400 mb-4"></i>
                        <p class="text-slate-600">Vous gérez très bien vos souscriptions !</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                
                <a href="<?php echo e(route('private.subscriptions.index')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-list mr-2"></i> Mes Souscriptions
                </a>
                <a href="<?php echo e(route('private.paiements.index')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i> Paiements
                </a>
                <button onclick="window.print()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/subscriptions/statistiques.blade.php ENDPATH**/ ?>