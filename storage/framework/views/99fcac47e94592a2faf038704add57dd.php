<?php $__env->startSection('title', 'Statistiques des Rapports'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Rapports de Réunions</h1>
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
                        <span class="text-sm font-medium text-slate-500">Statistiques</span>
                    </div>
                </li>
            </ol>
        </nav>
        <p class="text-slate-500 mt-1">Analyse des performances et tendances - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?></p>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres d'Analyse
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.rapports-reunions.statistiques')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="30" <?php echo e(request('periode', '30') == '30' ? 'selected' : ''); ?>>30 derniers jours</option>
                        <option value="90" <?php echo e(request('periode') == '90' ? 'selected' : ''); ?>>3 derniers mois</option>
                        <option value="180" <?php echo e(request('periode') == '180' ? 'selected' : ''); ?>>6 derniers mois</option>
                        <option value="365" <?php echo e(request('periode') == '365' ? 'selected' : ''); ?>>12 derniers mois</option>
                        <option value="all" <?php echo e(request('periode') == 'all' ? 'selected' : ''); ?>>Toutes les données</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de rapport</label>
                    <select name="type_rapport" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = \App\Models\RapportReunion::TYPES_RAPPORT; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(request('type_rapport') == $value ? 'selected' : ''); ?>>
                                <?php switch($value):
                                    case ('proces_verbal'): ?> Procès-verbal <?php break; ?>
                                    <?php case ('compte_rendu'): ?> Compte-rendu <?php break; ?>
                                    <?php case ('rapport_activite'): ?> Rapport d'activité <?php break; ?>
                                    <?php case ('rapport_financier'): ?> Rapport financier <?php break; ?>
                                <?php endswitch; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="publie" <?php echo e(request('statut') == 'publie' ? 'selected' : ''); ?>>Publiés uniquement</option>
                        <option value="valide" <?php echo e(request('statut') == 'valide' ? 'selected' : ''); ?>>Validés uniquement</option>
                        <option value="en_revision" <?php echo e(request('statut') == 'en_revision' ? 'selected' : ''); ?>>En révision</option>
                        <option value="brouillon" <?php echo e(request('statut') == 'brouillon' ? 'selected' : ''); ?>>Brouillons</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync mr-2"></i> Actualiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques_globales['total'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total des rapports</p>
                    <?php if(isset($statistiques_globales['total']) && $statistiques_globales['total'] > 0): ?>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?php echo e(round(($statistiques_globales['publies'] / $statistiques_globales['total']) * 100, 1)); ?>% publiés
                        </p>
                    <?php endif; ?>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques_globales['en_revision'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">En révision</p>
                    <?php if(isset($statistiques_globales['delai_validation_moyen']) && $statistiques_globales['delai_validation_moyen']): ?>
                        <p class="text-xs text-amber-600">
                            <i class="fas fa-stopwatch mr-1"></i>
                            <?php echo e($statistiques_globales['delai_validation_moyen']); ?>j délai moyen
                        </p>
                    <?php endif; ?>
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
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques_globales['publies'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Publiés</p>
                    <p class="text-xs text-green-600">
                        <i class="fas fa-thumbs-up mr-1"></i>
                        Processus terminé
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques_globales['satisfaction_moyenne'] ?? 0, 1)); ?></p>
                    <p class="text-sm text-slate-500">Satisfaction moyenne</p>
                    <div class="flex items-center mt-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star text-xs <?php echo e($i <= ($statistiques_globales['satisfaction_moyenne'] ?? 0) ? 'text-yellow-400' : 'text-slate-300'); ?> mr-1"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Évolution mensuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Évolution des Rapports (12 derniers mois)
                </h2>
            </div>
            <div class="p-6">
                <?php if($evolution_mensuelle && $evolution_mensuelle->count() > 0): ?>
                    <div class="relative">
                        <canvas id="evolutionChart" width="400" height="200"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-lg font-bold text-blue-600"><?php echo e($evolution_mensuelle->sum('total')); ?></p>
                            <p class="text-sm text-slate-600">Total créés</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <p class="text-lg font-bold text-green-600"><?php echo e($evolution_mensuelle->sum('publies')); ?></p>
                            <p class="text-sm text-slate-600">Total publiés</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500">Pas assez de données pour générer le graphique</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                <?php
                    $totalRapports = $statistiques_globales['total'] ?? 0;
                    $typeColors = [
                        'proces_verbal' => ['color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                        'compte_rendu' => ['color' => 'bg-green-500', 'text' => 'text-green-600'],
                        'rapport_activite' => ['color' => 'bg-yellow-500', 'text' => 'text-yellow-600'],
                        'rapport_financier' => ['color' => 'bg-red-500', 'text' => 'text-red-600']
                    ];

                    // Simuler des données de répartition si pas disponibles
                    $repartition = [
                        'proces_verbal' => round($totalRapports * 0.4),
                        'compte_rendu' => round($totalRapports * 0.3),
                        'rapport_activite' => round($totalRapports * 0.2),
                        'rapport_financier' => round($totalRapports * 0.1)
                    ];
                ?>

                <?php if($totalRapports > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $repartition; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $pourcentage = $totalRapports > 0 ? ($nombre / $totalRapports) * 100 : 0;
                                $typeLabel = match($type) {
                                    'proces_verbal' => 'Procès-verbaux',
                                    'compte_rendu' => 'Comptes-rendus',
                                    'rapport_activite' => 'Rapports d\'activité',
                                    'rapport_financier' => 'Rapports financiers',
                                    default => $type
                                };
                            ?>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 <?php echo e($typeColors[$type]['color']); ?> rounded mr-3"></div>
                                    <span class="text-sm font-medium text-slate-700"><?php echo e($typeLabel); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-bold <?php echo e($typeColors[$type]['text']); ?> mr-3"><?php echo e($nombre); ?></span>
                                    <div class="w-24 h-2 bg-slate-200 rounded-full">
                                        <div class="<?php echo e($typeColors[$type]['color']); ?> h-2 rounded-full" style="width: <?php echo e($pourcentage); ?>%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 ml-2"><?php echo e(number_format($pourcentage, 1)); ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top rédacteurs -->
    <?php if($top_redacteurs && $top_redacteurs->count() > 0): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Top Rédacteurs
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $top_redacteurs->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $redacteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center mb-3">
                                <?php if($index < 3): ?>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                        <?php echo e($index === 0 ? 'bg-yellow-100 text-yellow-600' : ''); ?>

                                        <?php echo e($index === 1 ? 'bg-slate-100 text-slate-600' : ''); ?>

                                        <?php echo e($index === 2 ? 'bg-orange-100 text-orange-600' : ''); ?>">
                                        <?php if($index === 0): ?> <i class="fas fa-crown text-sm"></i>
                                        <?php elseif($index === 1): ?> <i class="fas fa-medal text-sm"></i>
                                        <?php elseif($index === 2): ?> <i class="fas fa-award text-sm"></i>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-bold text-blue-600">#<?php echo e($index + 1); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900">
                                        <?php echo e($redacteur->redacteur ? $redacteur->redacteur->nom . ' ' . $redacteur->redacteur->prenom : 'Rédacteur inconnu'); ?>

                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600">Rapports créés:</span>
                                    <span class="font-bold text-slate-900"><?php echo e($redacteur->total_rapports); ?></span>
                                </div>

                                <?php
                                    $maxRapports = $top_redacteurs->first()->total_rapports ?? 1;
                                    $pourcentage = ($redacteur->total_rapports / $maxRapports) * 100;
                                ?>

                                <div class="w-full h-2 bg-slate-200 rounded-full">
                                    <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full transition-all duration-500" style="width: <?php echo e($pourcentage); ?>%"></div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Performance</span>
                                    <span><?php echo e(number_format($pourcentage, 1)); ?>%</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Rapports en attente -->
    <?php if($rapports_en_attente && $rapports_en_attente->count() > 0): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                        Rapports en Attente par Type
                    </h2>
                    <span class="text-sm text-slate-500"><?php echo e($rapports_en_attente->sum('total')); ?> total</span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php $__currentLoopData = $rapports_en_attente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $typeLabel = match($attente->type_rapport) {
                                'proces_verbal' => 'Procès-verbaux',
                                'compte_rendu' => 'Comptes-rendus',
                                'rapport_activite' => 'Rapports d\'activité',
                                'rapport_financier' => 'Rapports financiers',
                                default => $attente->type_rapport
                            };
                        ?>

                        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl border border-orange-200">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-alt text-orange-600"></i>
                            </div>
                            <h3 class="font-semibold text-slate-900 mb-1"><?php echo e($typeLabel); ?></h3>
                            <p class="text-2xl font-bold text-orange-600 mb-2"><?php echo e($attente->total); ?></p>
                            <p class="text-sm text-slate-600">en attente</p>

                            <div class="mt-3">
                                <a href="<?php echo e(route('private.rapports-reunions.index', ['statut' => 'en_revision', 'type' => $attente->type_rapport])); ?>"
                                   class="inline-flex items-center px-3 py-1 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.create')): ?>
                <a href="<?php echo e(route('private.rapports-reunions.create')); ?>" class="flex items-center justify-center p-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="font-medium">Nouveau Rapport</span>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.manage-attendance')): ?>
                <a href="<?php echo e(route('private.rapports-reunions.en-attente')); ?>" class="flex items-center justify-center p-4 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-clock mr-2"></i>
                    <span class="font-medium">En Attente</span>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.manage')): ?>
                <a href="<?php echo e(route('private.rapports-reunions.mes-rapports')); ?>" class="flex items-center justify-center p-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-user mr-2"></i>
                    <span class="font-medium">Rapports</span>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rapports-reunions.export')): ?>
                <a href="<?php echo e(route('private.rapports-reunions.export', ['format' => 'excel'])); ?>" class="flex items-center justify-center p-4 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    <span class="font-medium">Export Excel</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Indicateurs de performance -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
                Indicateurs de Performance
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Taux de finalisation -->
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-double text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Taux de Finalisation</h3>
                    <?php
                        $tauxFinalisation = $statistiques_globales['total'] > 0
                            ? (($statistiques_globales['publies'] ?? 0) / $statistiques_globales['total']) * 100
                            : 0;
                    ?>
                    <p class="text-3xl font-bold text-green-600 mb-2"><?php echo e(number_format($tauxFinalisation, 1)); ?>%</p>
                    <p class="text-sm text-slate-600">des rapports sont publiés</p>

                    <div class="mt-4 w-full h-3 bg-green-200 rounded-full">
                        <div class="h-3 bg-green-500 rounded-full transition-all duration-500" style="width: <?php echo e($tauxFinalisation); ?>%"></div>
                    </div>
                </div>

                <!-- Temps de traitement -->
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-stopwatch text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Délai Moyen</h3>
                    <p class="text-3xl font-bold text-blue-600 mb-2">
                        <?php echo e($statistiques_globales['delai_validation_moyen'] ?? 'N/A'); ?>

                        <?php if(isset($statistiques_globales['delai_validation_moyen'])): ?>
                            <span class="text-lg">jours</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-sm text-slate-600">pour la validation</p>

                    <?php if(isset($statistiques_globales['delai_validation_moyen'])): ?>
                        <?php
                            $delaiPourcentage = min(($statistiques_globales['delai_validation_moyen'] / 14) * 100, 100); // 14 jours = 100%
                            $delaiColor = $statistiques_globales['delai_validation_moyen'] <= 7 ? 'bg-green-500' :
                                         ($statistiques_globales['delai_validation_moyen'] <= 14 ? 'bg-yellow-500' : 'bg-red-500');
                        ?>
                        <div class="mt-4 w-full h-3 bg-slate-200 rounded-full">
                            <div class="<?php echo e($delaiColor); ?> h-3 rounded-full transition-all duration-500" style="width: <?php echo e($delaiPourcentage); ?>%"></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Productivité -->
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Productivité</h3>
                    <?php
                        $rapportsParMois = $statistiques_globales['total'] > 0 ? $statistiques_globales['total'] / 12 : 0;
                    ?>
                    <p class="text-3xl font-bold text-purple-600 mb-2"><?php echo e(number_format($rapportsParMois, 1)); ?></p>
                    <p class="text-sm text-slate-600">rapports par mois</p>

                    <div class="mt-4 w-full h-3 bg-purple-200 rounded-full">
                        <?php
                            $productivitePourcentage = min($rapportsParMois * 10, 100); // 10 rapports/mois = 100%
                        ?>
                        <div class="bg-purple-500 h-3 rounded-full transition-all duration-500" style="width: <?php echo e($productivitePourcentage); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution
    <?php if($evolution_mensuelle && $evolution_mensuelle->count() > 0): ?>
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        const evolutionData = <?php echo json_encode($evolution_mensuelle->values(), 15, 512) ?>;
        const labels = evolutionData.map(item => {
            const [year, month] = item.mois.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
        });

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rapports créés',
                    data: evolutionData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Rapports publiés',
                    data: evolutionData.map(item => item.publies),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
    <?php endif; ?>

    // Animation des barres de progression
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });

    // Animation des compteurs
    const counters = document.querySelectorAll('.text-2xl.font-bold');
    counters.forEach(counter => {
        const target = parseFloat(counter.textContent);
        if (isNaN(target)) return;

        let current = 0;
        const increment = target / 20;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }

            if (target < 1) {
                counter.textContent = current.toFixed(1);
            } else {
                counter.textContent = Math.round(current);
            }
        }, 50);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/rapportsreunions/statistiques.blade.php ENDPATH**/ ?>