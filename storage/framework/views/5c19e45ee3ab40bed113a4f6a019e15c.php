<?php $__env->startSection('title', 'Statistiques des Événements'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Événements</h1>
            <p class="text-slate-500 mt-1">Analyse et métriques des événements - Période du <?php echo e(\Carbon\Carbon::parse($statistiques['periode']['debut'])->format('d/m/Y')); ?> au <?php echo e(\Carbon\Carbon::parse($statistiques['periode']['fin'])->format('d/m/Y')); ?></p>
        </div>
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
            <form method="GET" action="<?php echo e(route('private.events.statistiques')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut', $statistiques['periode']['debut'])); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin', $statistiques['periode']['fin'])); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type d'événement</label>
                    <select name="type_evenement" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="conference" <?php echo e(request('type_evenement') == 'conference' ? 'selected' : ''); ?>>Conférence</option>
                        <option value="seminaire" <?php echo e(request('type_evenement') == 'seminaire' ? 'selected' : ''); ?>>Séminaire</option>
                        <option value="celebration" <?php echo e(request('type_evenement') == 'celebration' ? 'selected' : ''); ?>>Célébration</option>
                        <option value="concert" <?php echo e(request('type_evenement') == 'concert' ? 'selected' : ''); ?>>Concert</option>
                        <option value="retraite" <?php echo e(request('type_evenement') == 'retraite' ? 'selected' : ''); ?>>Retraite</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Organisateur</label>
                    <select name="organisateur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les organisateurs</option>
                        <?php $__currentLoopData = $organisateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($organisateur->id); ?>" <?php echo e(request('organisateur_id') == $organisateur->id ? 'selected' : ''); ?>>
                                <?php echo e($organisateur->prenom); ?> <?php echo e($organisateur->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i> Générer les statistiques
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800"><?php echo e($statistiques['totaux']['nombre_events']); ?></p>
                    <p class="text-sm text-slate-500">Total événements</p>
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
                    <p class="text-3xl font-bold text-slate-800"><?php echo e($statistiques['totaux']['events_termines']); ?></p>
                    <p class="text-sm text-slate-500">Événements terminés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800"><?php echo e(number_format($statistiques['totaux']['total_participants'])); ?></p>
                    <p class="text-sm text-slate-500">Total participants</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800"><?php echo e(number_format($statistiques['totaux']['total_recettes'])); ?></p>
                    <p class="text-sm text-slate-500">Recettes (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Moyennes et taux -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-friends text-white text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['moyennes']['participants_par_event']); ?></div>
                <div class="text-sm text-slate-500">Participants / événement</div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['moyennes']['note_globale']); ?><span class="text-lg">/10</span></div>
                <div class="text-sm text-slate-500">Note globale</div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-cogs text-white text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['moyennes']['note_organisation']); ?><span class="text-lg">/10</span></div>
                <div class="text-sm text-slate-500">Note organisation</div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-book-open text-white text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['moyennes']['note_contenu']); ?><span class="text-lg">/10</span></div>
                <div class="text-sm text-slate-500">Note contenu</div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-teal-400 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-smile text-white text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['moyennes']['taux_satisfaction']); ?><span class="text-lg">%</span></div>
                <div class="text-sm text-slate-500">Taux satisfaction</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                <?php if($statistiques['par_type']->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $statistiques['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-slate-700"><?php echo e(ucfirst(str_replace('_', ' ', $type->type_evenement))); ?></span>
                                        <span class="text-sm text-slate-500"><?php echo e($type->nombre); ?> événements</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-400 to-purple-500 h-2 rounded-full" style="width: <?php echo e($statistiques['totaux']['nombre_events'] > 0 ? ($type->nombre / $statistiques['totaux']['nombre_events']) * 100 : 0); ?>%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mt-1 text-xs text-slate-500">
                                        <span><?php echo e($type->total_participants); ?> participants</span>
                                        <span>Moyenne: <?php echo e($type->moyenne_participants); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée pour cette période</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Évolution mensuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Évolution Mensuelle
                </h2>
            </div>
            <div class="p-6">
                <?php if($statistiques['par_mois']->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $statistiques['par_mois']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mois): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-slate-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-slate-900">
                                        <?php echo e(\Carbon\Carbon::createFromDate($mois->annee, $mois->mois, 1)->format('F Y')); ?>

                                    </h3>
                                    <span class="text-sm text-slate-500"><?php echo e($mois->nombre_events); ?> événements</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="text-center p-2 bg-blue-50 rounded">
                                        <div class="font-bold text-blue-600"><?php echo e($mois->total_participants); ?></div>
                                        <div class="text-blue-800">Participants</div>
                                    </div>
                                    <div class="text-center p-2 bg-green-50 rounded">
                                        <div class="font-bold text-green-600"><?php echo e(number_format($mois->total_recettes)); ?> FCFA</div>
                                        <div class="text-green-800">Recettes</div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée mensuelle</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-table text-indigo-600 mr-2"></i>
                    Détail des Métriques
                </h2>
                <button onclick="exportStats()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Indicateurs de performance -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 text-lg">Performance</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-slate-700">Taux de réussite</span>
                            <span class="font-bold text-green-600">
                                <?php echo e($statistiques['totaux']['nombre_events'] > 0 ? round(($statistiques['totaux']['events_termines'] / $statistiques['totaux']['nombre_events']) * 100, 1) : 0); ?>%
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-slate-700">Taux d'annulation</span>
                            <span class="font-bold text-red-600">
                                <?php echo e($statistiques['totaux']['nombre_events'] > 0 ? round(($statistiques['totaux']['events_annules'] / $statistiques['totaux']['nombre_events']) * 100, 1) : 0); ?>%
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-slate-700">Budget moyen</span>
                            <span class="font-bold text-blue-600"><?php echo e(number_format($statistiques['totaux']['budget_total'] / max($statistiques['totaux']['nombre_events'], 1))); ?> FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Tendances -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 text-lg">Tendances</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-800 font-medium">Événements par mois</span>
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-blue-600 mt-1">
                                <?php echo e($statistiques['par_mois']->count() > 0 ? round($statistiques['totaux']['nombre_events'] / $statistiques['par_mois']->count(), 1) : 0); ?>

                            </div>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-purple-800 font-medium">Croissance participation</span>
                                <i class="fas fa-trending-up text-purple-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-purple-600 mt-1">+12%</div>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-green-800 font-medium">Satisfaction moyenne</span>
                                <i class="fas fa-smile text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600 mt-1"><?php echo e($statistiques['moyennes']['taux_satisfaction']); ?>%</div>
                        </div>
                    </div>
                </div>

                <!-- Top performers -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 text-lg">Top Performances</h3>
                    <div class="space-y-3">
                        <?php if($statistiques['par_type']->isNotEmpty()): ?>
                            <?php
                                $topType = $statistiques['par_type']->sortByDesc('total_participants')->first();
                            ?>
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-yellow-800 font-medium">Type le plus populaire</span>
                                    <i class="fas fa-trophy text-yellow-600"></i>
                                </div>
                                <div class="text-lg font-bold text-yellow-600 mt-1"><?php echo e(ucfirst($topType->type_evenement ?? 'N/A')); ?></div>
                                <div class="text-sm text-yellow-700"><?php echo e($topType->total_participants ?? 0); ?> participants</div>
                            </div>
                        <?php endif; ?>

                        <div class="p-3 bg-indigo-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-800 font-medium">Meilleur mois</span>
                                <i class="fas fa-calendar-star text-indigo-600"></i>
                            </div>
                            <?php if($statistiques['par_mois']->isNotEmpty()): ?>
                                <?php
                                    $bestMonth = $statistiques['par_mois']->sortByDesc('nombre_events')->first();
                                ?>
                                <div class="text-lg font-bold text-indigo-600 mt-1">
                                    <?php echo e(\Carbon\Carbon::createFromDate($bestMonth->annee, $bestMonth->mois, 1)->format('F')); ?>

                                </div>
                                <div class="text-sm text-indigo-700"><?php echo e($bestMonth->nombre_events); ?> événements</div>
                            <?php else: ?>
                                <div class="text-lg font-bold text-indigo-600 mt-1">N/A</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportStats() {
    // Fonction pour exporter les statistiques
    alert('Fonctionnalité d\'export à implémenter');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/events/statistiques.blade.php ENDPATH**/ ?>