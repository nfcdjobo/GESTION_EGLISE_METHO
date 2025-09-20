<?php $__env->startSection('title', 'Détails du FIMECO - ' . $fimeco['nom']); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('private.fimecos.index')); ?>"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-arrow-left text-slate-600"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            <?php echo e($fimeco['nom']); ?>

                        </h1>
                        <p class="text-slate-500 mt-1">
                            FIMECO créé le <?php echo e(\Carbon\Carbon::parse($fimeco['created_at'])->format('d/m/Y')); ?>

                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.update')): ?>
                        <a href="<?php echo e(route('private.fimecos.edit', $fimeco['id'])); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.rapport')): ?>
                        <a href="<?php echo e(route('private.fimecos.rapport', $fimeco['id'])); ?>"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-file-alt mr-2"></i> Rapport
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistiques en temps réel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($fimeco['cible'], 0, ',', ' ')); ?></p>
                        <p class="text-sm text-slate-500">Cible (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($fimeco['montant_solde'], 0, ',', ' ')); ?></p>
                        <p class="text-sm text-slate-500">Collecté (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($fimeco['progression'], 1)); ?>%</p>
                        <p class="text-sm text-slate-500">Progression</p>
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
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($fimeco['jours_restants']); ?></p>
                        <p class="text-sm text-slate-500">Jours restants</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression visuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Progression du FIMECO
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Barre de progression principale -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-slate-700">Progression générale</span>
                            <span class="text-sm font-medium text-slate-700"><?php echo e(number_format($fimeco['progression'], 1)); ?>%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-4">
                            <div class="h-4 rounded-full <?php echo e($fimeco['progression'] >= 100 ? 'bg-green-500' : ($fimeco['progression'] >= 75 ? 'bg-blue-500' : ($fimeco['progression'] >= 50 ? 'bg-yellow-500' : 'bg-red-500'))); ?>"
                                 style="width: <?php echo e(min($fimeco['progression'], 100)); ?>%"></div>
                        </div>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm text-blue-600 font-medium">Statut global</div>
                            <div class="text-lg font-bold text-blue-800 capitalize">
                                <?php echo e(str_replace('_', ' ', $fimeco['statut_global'])); ?>

                            </div>
                        </div>

                        <?php if($fimeco['reste'] > 0): ?>
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200">
                                <div class="text-sm text-orange-600 font-medium">Reste à collecter</div>
                                <div class="text-lg font-bold text-orange-800">
                                    <?php echo e(number_format($fimeco['reste'], 0, ',', ' ')); ?> FCFA
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($fimeco['montant_supplementaire'] > 0): ?>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                <div class="text-sm text-green-600 font-medium">Montant supplémentaire</div>
                                <div class="text-lg font-bold text-green-800">
                                    +<?php echo e(number_format($fimeco['montant_supplementaire'], 0, ',', ' ')); ?> FCFA
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm text-purple-600 font-medium">Statut</div>
                            <div class="text-lg font-bold text-purple-800 capitalize">
                                <?php echo e($fimeco['statut']); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations générales et Souscriptions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations générales
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <?php if($fimeco['description']): ?>
                        <div>
                            <label class="text-sm font-medium text-slate-600">Description</label>
                            <p class="text-slate-800 mt-1"><?php echo e($fimeco['description']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($fimeco['responsable'])): ?>
                        
                        <div>
                            <label class="text-sm font-medium text-slate-600">Responsable</label>
                            <div class="flex items-center mt-1">
                                <?php if(!empty($fimeco['responsable']['photo_profil'])): ?>
                                    <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                                        <img src="<?php echo e(Storage::url($fimeco['responsable']['photo_profil'])); ?>"
                                            alt="Photo de <?php echo e($fimeco['responsable']['nom']); ?>"
                                            class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <p class="text-slate-800 font-medium">
                                        <?php echo e($fimeco['responsable']['nom'] . ' ' . $fimeco['responsable']['prenom']); ?>

                                    </p>
                                    <p class="text-xs text-slate-500"><?php echo e($fimeco['responsable']['email']); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($fimeco['responsable']['telephone_1']); ?></p>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Période</label>
                        <p class="text-slate-800 mt-1">
                            Du <?php echo e(\Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y')); ?>

                            au <?php echo e(\Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y')); ?>

                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Durée : <?php echo e(\Carbon\Carbon::parse($fimeco['debut'])->diffInDays(\Carbon\Carbon::parse($fimeco['fin']))); ?> jours
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Dates importantes</label>
                        <div class="space-y-1 mt-1">
                            <p class="text-sm text-slate-700">
                                <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
                                Créé le <?php echo e(\Carbon\Carbon::parse($fimeco['created_at'])->format('d/m/Y à H:i')); ?>

                            </p>
                            <p class="text-sm text-slate-700">
                                <i class="fas fa-calendar-edit text-blue-600 mr-2"></i>
                                Modifié le <?php echo e(\Carbon\Carbon::parse($fimeco['updated_at'])->format('d/m/Y à H:i')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des souscriptions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-purple-600 mr-2"></i>
                        Souscriptions
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <?php echo e($statistiques['nb_souscriptions_total']); ?>

                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($statistiques['nb_souscriptions_completes']); ?></div>
                            <div class="text-sm text-green-700">Complètes</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($statistiques['nb_souscriptions_actives']); ?></div>
                            <div class="text-sm text-blue-700">Actives</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600"><?php echo e($statistiques['nb_souscriptions_partielles']); ?></div>
                            <div class="text-sm text-yellow-700">Partielles</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg border border-gray-200">
                            <div class="text-2xl font-bold text-gray-600"><?php echo e($statistiques['nb_souscriptions_inactives']); ?></div>
                            <div class="text-sm text-gray-700">Inactives</div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Montant total souscrit</span>
                            <span class="text-sm font-bold text-slate-800">
                                <?php echo e(number_format($statistiques['montant_total_souscrit'], 0, ',', ' ')); ?> FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Montant total payé</span>
                            <span class="text-sm font-bold text-slate-800">
                                <?php echo e(number_format($statistiques['montant_total_paye'], 0, ',', ' ')); ?> FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Progression moyenne</span>
                            <span class="text-sm font-bold text-slate-800">
                                <?php echo e(number_format($statistiques['progression_moyenne_souscriptions'], 1)); ?>%
                            </span>
                        </div>
                        <?php if($statistiques['nb_souscriptions_en_retard'] > 0): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-red-600">Souscriptions en retard</span>
                                <span class="text-sm font-bold text-red-800">
                                    <?php echo e($statistiques['nb_souscriptions_en_retard']); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Souscriptions récentes et Paiements en attente -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Souscriptions récentes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        Souscriptions récentes
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?php echo e(count($souscriptions_recentes)); ?>

                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <?php if(count($souscriptions_recentes) > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $souscriptions_recentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $souscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800"><?php echo e($souscription['souscripteur']['nom']); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo e(\Carbon\Carbon::parse($souscription['date_souscription'])->format('d/m/Y')); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-slate-800"><?php echo e(number_format($souscription['montant_souscrit'], 0, ',', ' ')); ?> FCFA</p>
                                        <p class="text-xs text-slate-500"><?php echo e($souscription['statut']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-users text-3xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucune souscription récente</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paiements en attente -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                        Paiements en attente
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <?php echo e(count($paiements_en_attente)); ?>

                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <?php if(count($paiements_en_attente) > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $paiements_en_attente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-clock text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800"><?php echo e(number_format($paiement['montant'], 0, ',', ' ')); ?> FCFA</p>
                                            <p class="text-xs text-slate-500"><?php echo e(\Carbon\Carbon::parse($paiement['date_paiement'])->format('d/m/Y H:i')); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <?php echo e($paiement['type_paiement']); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-3xl text-green-300 mb-3"></i>
                            <p class="text-slate-500">Aucun paiement en attente</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Évolution mensuelle -->
        <?php if(count($evolution_mensuelle) > 0): ?>
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-area text-green-600 mr-2"></i>
                        Évolution mensuelle des paiements
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Mois</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nombre de paiements</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php $__currentLoopData = $evolution_mensuelle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mois): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            <?php echo e(\Carbon\Carbon::parse($mois->mois)->format('F Y')); ?>

                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">
                                            <?php echo e($mois->nb_paiements); ?>

                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            <?php echo e(number_format($mois->montant_total, 0, ',', ' ')); ?> FCFA
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-blue-600 mr-2"></i>
                Actions rapides
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if($fimeco['statut'] === 'active'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.cloture')): ?>
                        <button onclick="cloturerFimeco('<?php echo e($fimeco['id']); ?>')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-lock mr-2"></i>
                            Clôturer le FIMECO
                        </button>
                    <?php endif; ?>
                <?php elseif($fimeco['statut'] === 'cloturee'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.reouvrir')): ?>
                        <button onclick="reouvririFimeco('<?php echo e($fimeco['id']); ?>')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-unlock mr-2"></i>
                            Réouvrir le FIMECO
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subscriptions.create')): ?>
                    <a href="<?php echo e(route('private.subscriptions.create', $fimeco['id'])); ?>"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Nouvelle souscription
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fimecos.rapport')): ?>
                    <a href="<?php echo e(route('private.fimecos.rapport', ['id' => $fimeco['id'], 'format' => 'pdf'])); ?>"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Télécharger PDF
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function cloturerFimeco(fimecoId) {
                if (confirm('Êtes-vous sûr de vouloir clôturer ce FIMECO ? Cette action changera son statut.')) {
                    const url = "<?php echo e(route('private.fimecos.cloture', ':fimecoid')); ?>".replace(':fimecoid', fimecoId);

                    fetch(url, {
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
                                alert(data.message || 'Erreur lors de la clôture');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la clôture');
                        });
                }
            }

            function reouvririFimeco(fimecoId) {
                if (confirm('Êtes-vous sûr de vouloir réouvrir ce FIMECO ?')) {
                    const url = "<?php echo e(route('private.fimecos.reouvrir', ':fimecoid')); ?>".replace(':fimecoid', fimecoId);

                    fetch(url, {
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
                                alert(data.message || 'Erreur lors de la réouverture');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la réouverture');
                        });
                }
            }

            // Animation des cartes au chargement
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

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/fimecos/show.blade.php ENDPATH**/ ?>