<?php $__env->startSection('title', 'Statistiques des Participations'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Participations</h1>
        <p class="text-slate-500 mt-1">Analyse détaillée des participations aux cultes et tendances de fréquentation</p>
    </div>

    <!-- Filtres de période -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres et Période d'Analyse
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.participantscultes.statistiques')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut', now()->subMonths(6)->format('Y-m-d'))); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin', now()->format('Y-m-d'))); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de culte</label>
                    <select name="type_culte" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="dimanche_matin" <?php echo e(request('type_culte') == 'dimanche_matin' ? 'selected' : ''); ?>>Dimanche Matin</option>
                        <option value="dimanche_soir" <?php echo e(request('type_culte') == 'dimanche_soir' ? 'selected' : ''); ?>>Dimanche Soir</option>
                        <option value="mercredi" <?php echo e(request('type_culte') == 'mercredi' ? 'selected' : ''); ?>>Mercredi</option>
                        <option value="vendredi" <?php echo e(request('type_culte') == 'vendredi' ? 'selected' : ''); ?>>Vendredi</option>
                        <option value="special" <?php echo e(request('type_culte') == 'special' ? 'selected' : ''); ?>>Spécial</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i> Analyser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['total_participations'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total participations</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['premieres_visites'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Premières visites</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-praying-hands text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['demandes_suivi'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Demandes de suivi</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['participations_confirmees'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Présences confirmées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par statut de présence -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Répartition par Statut de Présence
                </h2>
            </div>
            <div class="p-6">
                <?php if(isset($stats['par_statut']) && count($stats['par_statut']) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $stats['par_statut']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statut => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $percentage = $stats['total_participations'] > 0 ? round(($count / $stats['total_participations']) * 100, 1) : 0;
                                $statutColors = [
                                    'present' => 'bg-green-500',
                                    'present_partiel' => 'bg-yellow-500',
                                    'en_retard' => 'bg-orange-500',
                                    'parti_tot' => 'bg-red-500'
                                ];
                                $statutLabels = [
                                    'present' => 'Présent',
                                    'present_partiel' => 'Présent Partiel',
                                    'en_retard' => 'En Retard',
                                    'parti_tot' => 'Parti Tôt'
                                ];
                            ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded <?php echo e($statutColors[$statut] ?? 'bg-gray-500'); ?>"></div>
                                    <span class="text-sm font-medium text-slate-700"><?php echo e($statutLabels[$statut] ?? ucfirst($statut)); ?></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-slate-500"><?php echo e($count); ?></span>
                                    <span class="text-sm font-bold text-slate-700"><?php echo e($percentage); ?>%</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="h-2 rounded-full <?php echo e($statutColors[$statut] ?? 'bg-gray-500'); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucune donnée disponible pour cette période</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Répartition par type de participation -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-donut text-purple-600 mr-2"></i>
                    Type de Participation
                </h2>
            </div>
            <div class="p-6">
                <?php if(isset($stats['par_type']) && count($stats['par_type']) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $stats['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $percentage = $stats['total_participations'] > 0 ? round(($count / $stats['total_participations']) * 100, 1) : 0;
                                $typeColors = [
                                    'physique' => 'bg-blue-500',
                                    'en_ligne' => 'bg-purple-500',
                                    'hybride' => 'bg-cyan-500'
                                ];
                                $typeLabels = [
                                    'physique' => 'Physique',
                                    'en_ligne' => 'En Ligne',
                                    'hybride' => 'Hybride'
                                ];
                            ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded <?php echo e($typeColors[$type] ?? 'bg-gray-500'); ?>"></div>
                                    <span class="text-sm font-medium text-slate-700"><?php echo e($typeLabels[$type] ?? ucfirst($type)); ?></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-slate-500"><?php echo e($count); ?></span>
                                    <span class="text-sm font-bold text-slate-700"><?php echo e($percentage); ?>%</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="h-2 rounded-full <?php echo e($typeColors[$type] ?? 'bg-gray-500'); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-donut text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucune donnée disponible pour cette période</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tendances et évolution -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Évolution des Participations
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Graphique d'évolution -->
                <div class="lg:col-span-2">
                    <div class="h-64 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Graphique d'évolution</p>
                            <p class="text-sm text-slate-400">À implémenter avec Chart.js</p>
                        </div>
                    </div>
                </div>

                <!-- Métriques clés -->
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-xl border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Moyenne par culte</p>
                                <p class="text-2xl font-bold text-blue-800">
                                    <?php echo e(isset($stats['moyenne_par_culte']) ? round($stats['moyenne_par_culte'], 1) : '0'); ?>

                                </p>
                            </div>
                            <i class="fas fa-calculator text-blue-500 text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Taux de présence</p>
                                <p class="text-2xl font-bold text-green-800">
                                    <?php if(isset($stats['par_statut']['present']) && $stats['total_participations'] > 0): ?>
                                        <?php echo e(round(($stats['par_statut']['present'] / $stats['total_participations']) * 100, 1)); ?>%
                                    <?php else: ?>
                                        0%
                                    <?php endif; ?>
                                </p>
                            </div>
                            <i class="fas fa-percentage text-green-500 text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Croissance</p>
                                <p class="text-2xl font-bold text-purple-800">
                                    <?php echo e(isset($stats['croissance']) ? ($stats['croissance'] > 0 ? '+' : '') . $stats['croissance'] . '%' : '0%'); ?>

                                </p>
                            </div>
                            <i class="fas fa-trending-up text-purple-500 text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 rounded-xl border border-amber-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-amber-600 font-medium">Fidélité</p>
                                <p class="text-2xl font-bold text-amber-800">
                                    <?php echo e(isset($stats['taux_fidelite']) ? round($stats['taux_fidelite'], 1) . '%' : '0%'); ?>

                                </p>
                            </div>
                            <i class="fas fa-heart text-amber-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyse détaillée par période -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top participants -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Participants les Plus Assidus
                </h2>
            </div>
            <div class="p-6">
                <?php if(isset($topParticipants) && count($topParticipants) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $topParticipants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e($index < 3 ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-slate-200 text-slate-600'); ?> font-bold text-sm">
                                        <?php echo e($index + 1); ?>

                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($participant['nom'] ?? 'N/A'); ?> <?php echo e($participant['prenom'] ?? ''); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($participant['participations'] ?? 0); ?> participations</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-green-600"><?php echo e($participant['taux_presence'] ?? 0); ?>%</p>
                                    <p class="text-xs text-slate-500">Présence</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-trophy text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Aucun participant trouvé pour cette période</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Insights et recommandations -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                    Insights et Recommandations
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php if(isset($insights) && count($insights) > 0): ?>
                        <?php $__currentLoopData = $insights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 <?php echo e($insight['type'] == 'positive' ? 'bg-green-50 border-green-200' : ($insight['type'] == 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-blue-50 border-blue-200')); ?> border rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas <?php echo e($insight['type'] == 'positive' ? 'fa-check-circle text-green-500' : ($insight['type'] == 'warning' ? 'fa-exclamation-triangle text-yellow-500' : 'fa-info-circle text-blue-500')); ?> mt-1"></i>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($insight['title'] ?? 'Insight'); ?></p>
                                        <p class="text-sm text-slate-600 mt-1"><?php echo e($insight['message'] ?? 'Message par défaut'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Insights par défaut basés sur les données -->
                        <?php if(isset($stats['total_participations']) && $stats['total_participations'] > 0): ?>
                            <div class="p-4 bg-blue-50 border-blue-200 border rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                    <div>
                                        <p class="font-medium text-slate-900">Analyse de la fréquentation</p>
                                        <p class="text-sm text-slate-600 mt-1">
                                            Vous avez enregistré <?php echo e($stats['total_participations']); ?> participations sur la période sélectionnée.
                                            <?php if(isset($stats['premieres_visites']) && $stats['premieres_visites'] > 0): ?>
                                                <?php echo e($stats['premieres_visites']); ?> nouvelles personnes ont visité l'église.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php if(isset($stats['par_statut']['present']) && $stats['total_participations'] > 0): ?>
                                <?php $tauxPresence = round(($stats['par_statut']['present'] / $stats['total_participations']) * 100, 1); ?>
                                <div class="p-4 <?php echo e($tauxPresence >= 80 ? 'bg-green-50 border-green-200' : ($tauxPresence >= 60 ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200')); ?> border rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas <?php echo e($tauxPresence >= 80 ? 'fa-check-circle text-green-500' : ($tauxPresence >= 60 ? 'fa-exclamation-triangle text-yellow-500' : 'fa-times-circle text-red-500')); ?> mt-1"></i>
                                        <div>
                                            <p class="font-medium text-slate-900">Taux de présence</p>
                                            <p class="text-sm text-slate-600 mt-1">
                                                Le taux de présence complète est de <?php echo e($tauxPresence); ?>%.
                                                <?php if($tauxPresence >= 80): ?>
                                                    Excellent! Votre communauté est très assidue.
                                                <?php elseif($tauxPresence >= 60): ?>
                                                    Correct, mais il y a une marge d'amélioration.
                                                <?php else: ?>
                                                    Attention, le taux de présence est faible. Considérez des actions pour améliorer l'engagement.
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="p-4 bg-slate-50 border-slate-200 border rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-info-circle text-slate-500 mt-1"></i>
                                    <div>
                                        <p class="font-medium text-slate-900">Aucune donnée</p>
                                        <p class="text-sm text-slate-600 mt-1">Aucune participation enregistrée pour cette période. Ajustez les filtres ou vérifiez la saisie des données.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="exportStatistiques()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                    <i class="fas fa-download mr-2"></i> Exporter les données
                </button>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('participants-cultes.nouveaux-visiteurs')): ?>
                <a href="<?php echo e(route('private.participantscultes.nouveaux-visiteurs')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Nouveaux visiteurs
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('private.participantscultes.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                    <i class="fas fa-list mr-2"></i> Toutes les participations
                </a>
                <button type="button" onclick="imprimerRapport()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200">
                    <i class="fas fa-print mr-2"></i> Imprimer le rapport
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function exportStatistiques() {
    // Récupérer les paramètres actuels
    const urlParams = new URLSearchParams(window.location.search);
    const exportUrl = new URL('<?php echo e(route("private.participantscultes.statistiques")); ?>', window.location.origin);

    // Ajouter les paramètres existants
    urlParams.forEach((value, key) => {
        exportUrl.searchParams.append(key, value);
    });

    // Ajouter le paramètre d'export
    exportUrl.searchParams.append('export', 'excel');

    // Télécharger
    window.open(exportUrl.toString(), '_blank');
}

function imprimerRapport() {
    window.print();
}

// Ajout de styles pour l'impression
const printStyles = `
    @media print {
        .no-print { display: none !important; }
        .bg-white\\/80 { background-color: white !important; }
        .shadow-lg { box-shadow: none !important; }
        .hover\\:shadow-xl:hover { box-shadow: none !important; }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/particitantscultes/statistiques.blade.php ENDPATH**/ ?>