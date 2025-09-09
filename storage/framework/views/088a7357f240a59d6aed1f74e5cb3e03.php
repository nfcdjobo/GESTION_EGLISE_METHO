<?php $__env->startSection('title', 'Tableau de Bord - Cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Tableau de Bord des Cultes</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo e(route('private.cultes.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-church mr-2"></i>
                                Cultes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Tableau de bord</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="refreshDashboard()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser
                </button>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                    <a href="<?php echo e(route('private.cultes.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouveau Culte
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <p class="text-slate-500 mt-2">Vue d'ensemble de l'activité religieuse - <?php echo e(\Carbon\Carbon::now()->format('l d F Y à H:i')); ?></p>
    </div>

    <!-- Cultes d'aujourd'hui -->
    <?php if($dashboard['aujourd_hui']['nombre'] > 0): ?>
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-blue-200">
                <h2 class="text-xl font-bold text-blue-800 flex items-center">
                    <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                    Cultes d'Aujourd'hui
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                        <?php echo e($dashboard['aujourd_hui']['nombre']); ?>

                    </span>
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $dashboard['aujourd_hui']['cultes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl p-4 border border-blue-200 hover:shadow-md transition-all duration-200 cursor-pointer"
                             onclick="window.location.href='<?php echo e(route('private.cultes.show', $culte)); ?>'">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900 truncate"><?php echo e($culte->titre); ?></h3>
                                    <p class="text-sm text-slate-600"><?php echo e($culte->type_culte_libelle); ?></p>
                                </div>
                                <?php
                                    $statutColors = [
                                        'planifie' => 'bg-blue-100 text-blue-800',
                                        'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                        'en_cours' => 'bg-orange-100 text-orange-800',
                                        'termine' => 'bg-green-100 text-green-800',
                                        'annule' => 'bg-red-100 text-red-800',
                                        'reporte' => 'bg-purple-100 text-purple-800'
                                    ];
                                ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e($culte->statut_libelle); ?>

                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-slate-600">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?>

                                </div>
                                <?php if($culte->pasteurPrincipal): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-1"></i>
                                        <?php echo e($culte->pasteurPrincipal->nom); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Métriques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Cette semaine -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($dashboard['cette_semaine']['cultes_a_venir']); ?></p>
                    <p class="text-sm text-slate-500">À venir cette semaine</p>
                    <p class="text-xs text-green-600"><?php echo e($dashboard['cette_semaine']['cultes_termines']); ?> terminés</p>
                </div>
            </div>
        </div>

        <!-- Ce mois -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($dashboard['ce_mois']['total_cultes']); ?></p>
                    <p class="text-sm text-slate-500">Total ce mois</p>
                    <p class="text-xs text-blue-600"><?php echo e(number_format($dashboard['ce_mois']['total_participants'])); ?> participants</p>
                </div>
            </div>
        </div>

        <!-- Offrandes du mois -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($dashboard['ce_mois']['total_offrandes'], 0)); ?>€</p>
                    <p class="text-sm text-slate-500">Offrandes ce mois</p>
                </div>
            </div>
        </div>

        <!-- Conversions du mois -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($dashboard['ce_mois']['total_conversions']); ?></p>
                    <p class="text-sm text-slate-500">Conversions ce mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Prochains cultes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-clock text-amber-600 mr-2"></i>
                    Prochains Cultes
                </h2>
            </div>
            <div class="p-6">
                <?php if($dashboard['prochains_cultes']->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $dashboard['prochains_cultes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $daysUntil = \Carbon\Carbon::parse($culte->date_culte)->diffInDays(now());
                                $isToday = \Carbon\Carbon::parse($culte->date_culte)->isToday();
                                $isTomorrow = \Carbon\Carbon::parse($culte->date_culte)->isTomorrow();
                            ?>
                            <div class="flex items-center space-x-4 p-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer"
                                 onclick="window.location.href='<?php echo e(route('private.cultes.show', $culte)); ?>'">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-xs font-bold
                                        <?php echo e($isToday ? 'bg-orange-100 text-orange-800' : ($isTomorrow ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800')); ?>">
                                        <div><?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('j')); ?></div>
                                        <div><?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('M')); ?></div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate"><?php echo e($culte->titre); ?></h3>
                                    <div class="flex items-center space-x-4 text-sm text-slate-600">
                                        <span><?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?></span>
                                        <span><?php echo e($culte->type_culte_libelle); ?></span>
                                        <?php if($culte->pasteurPrincipal): ?>
                                            <span><?php echo e($culte->pasteurPrincipal->nom); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <?php if($isToday): ?>
                                        <span class="text-xs font-medium text-orange-600">Aujourd'hui</span>
                                    <?php elseif($isTomorrow): ?>
                                        <span class="text-xs font-medium text-blue-600">Demain</span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-500">Dans <?php echo e($daysUntil); ?> jour<?php echo e($daysUntil > 1 ? 's' : ''); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.planning')): ?>
                    <div class="mt-6 pt-4 border-t border-slate-200 text-center">
                        <a href="<?php echo e(route('private.cultes.planning')); ?>" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-xl hover:bg-amber-700 transition-colors">
                            <i class="fas fa-calendar mr-2"></i> Voir le planning complet
                        </a>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-slate-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun culte planifié</h3>
                        <p class="text-slate-500 mb-4">Aucun culte n'est planifié dans les prochains jours.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                            <a href="<?php echo e(route('private.cultes.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Planifier un culte
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Indicateurs de Performance
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Note moyenne -->
                <?php if($dashboard['statistiques_rapides']['note_moyenne_mois']): ?>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Note moyenne ce mois</span>
                            <span class="text-lg font-bold text-amber-600"><?php echo e(round($dashboard['statistiques_rapides']['note_moyenne_mois'], 1)); ?>/10</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2 rounded-full transition-all duration-300"
                                 style="width: <?php echo e(($dashboard['statistiques_rapides']['note_moyenne_mois'] / 10) * 100); ?>%"></div>
                        </div>
                        <div class="flex mt-2">
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <i class="fas fa-star text-sm <?php echo e($i <= round($dashboard['statistiques_rapides']['note_moyenne_mois']) ? 'text-amber-400' : 'text-slate-300'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Taux de participation -->
                <?php if($dashboard['statistiques_rapides']['taux_participation']): ?>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Taux de participation</span>
                            <span class="text-lg font-bold text-green-600"><?php echo e(round($dashboard['statistiques_rapides']['taux_participation'], 1)); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-300"
                                 style="width: <?php echo e(min($dashboard['statistiques_rapides']['taux_participation'], 100)); ?>%"></div>
                        </div>
                        <div class="mt-1 text-xs text-slate-500">
                            Basé sur la capacité prévue vs participants réels
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Évolution mensuelle -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600"><?php echo e($dashboard['ce_mois']['total_cultes']); ?></div>
                        <div class="text-xs text-slate-600">Cultes ce mois</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600"><?php echo e(round($dashboard['ce_mois']['total_participants'] / max($dashboard['ce_mois']['total_cultes'], 1), 0)); ?></div>
                        <div class="text-xs text-slate-600">Moy. par culte</div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="pt-4 border-t border-slate-200">
                    <div class="grid grid-cols-1 gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.statistiques')): ?>
                        <a href="<?php echo e(route('private.cultes.statistiques')); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques détaillées
                        </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.read')): ?>
                        <a href="<?php echo e(route('private.cultes.index')); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200">
                            <i class="fas fa-list mr-2"></i> Tous les cultes
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et liens utiles -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-red-600 mr-2"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                    <a href="<?php echo e(route('private.cultes.create')); ?>" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-md transition-all duration-200 group">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-plus text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Nouveau Culte</div>
                            <div class="text-sm text-slate-600">Planifier un culte</div>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.planning')): ?>
                <a href="<?php echo e(route('private.cultes.planning')); ?>" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-200 hover:shadow-md transition-all duration-200 group">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">Planning</div>
                        <div class="text-sm text-slate-600">Vue calendrier</div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.statistics')): ?>
                <a href="<?php echo e(route('private.cultes.statistiques')); ?>" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200 hover:shadow-md transition-all duration-200 group">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">Statistiques</div>
                        <div class="text-sm text-slate-600">Analyses détaillées</div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.read')): ?>
                <a href="<?php echo e(route('private.cultes.index')); ?>" class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200 hover:shadow-md transition-all duration-200 group">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-amber-200 transition-colors">
                        <i class="fas fa-list text-amber-600"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">Liste Complète</div>
                        <div class="text-sm text-slate-600">Tous les cultes</div>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    <?php if($dashboard['prochains_cultes']->where('statut', 'en_preparation')->count() > 0 || $dashboard['aujourd_hui']['cultes']->where('statut', 'planifie')->count() > 0): ?>
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl shadow-lg border border-yellow-200">
            <div class="p-6 border-b border-yellow-200">
                <h2 class="text-xl font-bold text-yellow-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Alertes et Notifications
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php if($dashboard['aujourd_hui']['cultes']->where('statut', 'planifie')->count() > 0): ?>
                    <div class="flex items-center p-4 bg-orange-100 rounded-xl border border-orange-200">
                        <i class="fas fa-clock text-orange-600 mr-3"></i>
                        <div>
                            <div class="font-semibold text-orange-800">Cultes d'aujourd'hui en attente</div>
                            <div class="text-sm text-orange-700">
                                <?php echo e($dashboard['aujourd_hui']['cultes']->where('statut', 'planifie')->count()); ?> culte(s) planifié(s) pour aujourd'hui
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($dashboard['prochains_cultes']->where('statut', 'en_preparation')->count() > 0): ?>
                    <div class="flex items-center p-4 bg-blue-100 rounded-xl border border-blue-200">
                        <i class="fas fa-tasks text-blue-600 mr-3"></i>
                        <div>
                            <div class="font-semibold text-blue-800">Cultes en préparation</div>
                            <div class="text-sm text-blue-700">
                                <?php echo e($dashboard['prochains_cultes']->where('statut', 'en_preparation')->count()); ?> culte(s) nécessitent votre attention
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Actualisation du tableau de bord
function refreshDashboard() {
    const button = document.querySelector('button[onclick="refreshDashboard()"]');
    const icon = button.querySelector('i');

    // Animation de rotation
    icon.classList.add('animate-spin');
    button.disabled = true;

    // Simule le rechargement
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Animation des compteurs
function animateCounters() {
    const counters = document.querySelectorAll('.text-2xl.font-bold');

    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        if (target && target > 0) {
            let current = 0;
            const increment = Math.ceil(target / 30);
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = counter.textContent.replace(/\d+/, target);
                    clearInterval(timer);
                } else {
                    counter.textContent = counter.textContent.replace(/\d+/, current);
                }
            }, 50);
        }
    });
}

// Animation des barres de progression
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.bg-gradient-to-r[style*="width"]');

    progressBars.forEach(bar => {
        const finalWidth = bar.style.width;
        bar.style.width = '0%';
        bar.style.transition = 'width 1s ease-out';

        setTimeout(() => {
            bar.style.width = finalWidth;
        }, 200);
    });
}

// Observation des éléments pour animation au scroll
function observeElements() {
    const elements = document.querySelectorAll('.hover\\:-translate-y-1, .hover\\:shadow-xl');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
}

// Mise à jour automatique de l'heure
function updateTime() {
    const timeElements = document.querySelectorAll('[data-time]');
    const now = new Date();

    timeElements.forEach(element => {
        const format = element.dataset.timeFormat || 'HH:mm';
        // Mise à jour de l'heure si nécessaire
    });
}

// Notifications en temps réel (simulation)
function checkNotifications() {
    // Vérifier s'il y a des cultes qui commencent bientôt
    const now = new Date();
    const cultes = <?php echo json_encode($dashboard['aujourd_hui']['cultes'], 15, 512) ?>;

    cultes.forEach(culte => {
        const heureDebut = new Date(`${now.toDateString()} ${culte.heure_debut}`);
        const diff = heureDebut.getTime() - now.getTime();
        const minutesUntil = Math.floor(diff / (1000 * 60));

        // Notification 30 minutes avant
        if (minutesUntil === 30 && culte.statut === 'planifie') {
            showNotification(`Le culte "${culte.titre}" commence dans 30 minutes`, 'warning');
        }

        // Notification 5 minutes avant
        if (minutesUntil === 5 && culte.statut === 'planifie') {
            showNotification(`Le culte "${culte.titre}" commence dans 5 minutes`, 'urgent');
        }
    });
}

// Affichage des notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${
        type === 'urgent' ? 'bg-red-100 border border-red-300 text-red-800' :
        type === 'warning' ? 'bg-yellow-100 border border-yellow-300 text-yellow-800' :
        'bg-blue-100 border border-blue-300 text-blue-800'
    }`;

    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${type === 'urgent' ? 'fa-exclamation-triangle' : type === 'warning' ? 'fa-clock' : 'fa-info-circle'}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-sm hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Suppression automatique après 5 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'r':
                e.preventDefault();
                refreshDashboard();
                break;
            case 'n':
                e.preventDefault();
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.create')): ?>
                    window.location.href = '<?php echo e(route("private.cultes.create")); ?>';
                <?php endif; ?>
                break;
            case 'p':
                e.preventDefault();
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.planning')): ?>
                window.location.href = '<?php echo e(route("private.cultes.planning")); ?>';
                <?php endif; ?>
                break;
            case 's':
                e.preventDefault();
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cultes.statistics')): ?>
                window.location.href = '<?php echo e(route("private.cultes.statistiques")); ?>';
                <?php endif; ?>
                break;
        }
    }
});

// Gestion de la visibilité de la page pour les mises à jour
let isVisible = true;
document.addEventListener('visibilitychange', function() {
    isVisible = !document.hidden;
    if (isVisible) {
        // Reprendre les vérifications
        checkNotifications();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Démarrer les animations
    setTimeout(() => {
        animateCounters();
        animateProgressBars();
        observeElements();
    }, 200);

    // Vérification périodique des notifications
    setInterval(() => {
        if (isVisible) {
            checkNotifications();
        }
    }, 60000); // Chaque minute

    // Mise à jour de l'heure chaque minute
    setInterval(updateTime, 60000);
    updateTime();

    // Première vérification des notifications
    setTimeout(checkNotifications, 1000);
});

// Auto-refresh toutes les 5 minutes
setInterval(() => {
    if (isVisible && document.hasFocus()) {
        // Refresh silencieux en arrière-plan si la page est active
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            // Vérifier s'il y a des changements et notifier l'utilisateur
            // Implémentation optionnelle
        });
    }
}, 300000); // 5 minutes

// Gestion des erreurs globales
window.addEventListener('error', function(e) {
    console.error('Erreur sur le tableau de bord:', e.error);
});

// Amélioration de l'accessibilité
document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        document.body.classList.add('user-is-tabbing');
    }
});

document.addEventListener('mousedown', function() {
    document.body.classList.remove('user-is-tabbing');
});
</script>

<style>
/* Animations personnalisées */
@keyframes pulse-soft {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.pulse-soft {
    animation: pulse-soft 2s infinite;
}

/* Amélioration de l'accessibilité */
.user-is-tabbing *:focus {
    outline: 2px solid #3b82f6 !important;
    outline-offset: 2px !important;
}

/* Animation des cartes */
.hover\:-translate-y-1:hover {
    transform: translateY(-4px);
    transition: transform 0.2s ease;
}

/* Style pour les notifications */
.notification-enter {
    transform: translateX(100%);
}

.notification-enter-active {
    transform: translateX(0);
    transition: transform 0.3s ease-out;
}

/* Responsive amélioré */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }

    .text-2xl {
        font-size: 1.5rem;
    }
}

/* Mode sombre partiel pour les cartes */
@media (prefers-color-scheme: dark) {
    .bg-white\/80 {
        background-color: rgba(15, 23, 42, 0.8);
        border-color: rgba(71, 85, 105, 0.3);
    }

    .text-slate-800 {
        color: rgb(226, 232, 240);
    }

    .text-slate-600 {
        color: rgb(148, 163, 184);
    }
}

/* Animation de chargement */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/cultes/dashboard.blade.php ENDPATH**/ ?>