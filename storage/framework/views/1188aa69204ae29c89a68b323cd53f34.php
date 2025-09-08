<?php $__env->startSection('title', 'Statistiques des Réunions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Réunions</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('private.reunions.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Réunions
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
    </div>

    <!-- Filtres de période -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Période d'analyse
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('private.reunions.statistiques')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut', now()->subMonth()->format('Y-m-d'))); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin', now()->format('Y-m-d'))); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de réunion</label>
                    <select name="type_reunion_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <?php if(isset($typesReunions)): ?>
                            <?php $__currentLoopData = $typesReunions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(request('type_reunion_id') == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i> Analyser
                    </button>
                    <div class="relative">
                        <button type="button" id="presetButton" class="inline-flex items-center justify-center px-3 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-clock mr-2"></i>
                        </button>
                        <div id="presetMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-10">
                            <div class="p-2">
                                <button type="button" onclick="setPreset('cette_semaine')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">Cette semaine</button>
                                <button type="button" onclick="setPreset('ce_mois')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">Ce mois</button>
                                <button type="button" onclick="setPreset('trimestre')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">Ce trimestre</button>
                                <button type="button" onclick="setPreset('semestre')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">Ce semestre</button>
                                <button type="button" onclick="setPreset('annee')" class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">Cette année</button>
                            </div>
                        </div>
                    </div>
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
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['globales']['total_reunions'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Total réunions</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Terminées</span>
                    <span class="font-medium text-green-600"><?php echo e($statistiques['globales']['reunions_terminees'] ?? 0); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Annulées</span>
                    <span class="font-medium text-red-600"><?php echo e($statistiques['globales']['reunions_annulees'] ?? 0); ?></span>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['globales']['total_participants'] ?? 0)); ?></p>
                    <p class="text-sm text-slate-500">Total participants</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Moyenne/réunion</span>
                    <span class="font-medium text-slate-900"><?php echo e(number_format($statistiques['globales']['moyenne_participants'] ?? 0, 1)); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Nouveaux</span>
                    <span class="font-medium text-blue-600"><?php echo e($statistiques['globales']['total_nouveaux'] ?? 0); ?></span>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e($statistiques['globales']['total_decisions'] ?? 0); ?></p>
                    <p class="text-sm text-slate-500">Décisions spirituelles</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Taux conversion</span>
                    <?php
                        $totalParticipants = $statistiques['globales']['total_participants'] ?? 0;
                        $totalDecisions = $statistiques['globales']['total_decisions'] ?? 0;
                        $tauxConversion = $totalParticipants > 0 ? round(($totalDecisions / $totalParticipants) * 100, 1) : 0;
                    ?>
                    <span class="font-medium text-purple-600"><?php echo e($tauxConversion); ?>%</span>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($statistiques['globales']['satisfaction_moyenne'] ?? 0, 1)); ?></p>
                    <p class="text-sm text-slate-500">Satisfaction moyenne (%)</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Note globale</span>
                    <span class="font-medium text-amber-600"><?php echo e(number_format(($statistiques['globales']['note_moyenne'] ?? 0), 1)); ?>/10</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Graphique d'évolution -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Évolution mensuelle
                </h2>
            </div>
            <div class="p-6">
                <canvas id="evolutionChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Répartition par type
                </h2>
            </div>
            <div class="p-6">
                <canvas id="typeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques par type de réunion -->
    <?php if(isset($statistiques['par_type']) && count($statistiques['par_type']) > 0): ?>
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-table text-cyan-600 mr-2"></i>
                    Détail par type de réunion
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Participants</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Moyenne</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Max</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Décisions</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Satisfaction</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Note</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Annulations</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__currentLoopData = $statistiques['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: <?php echo e($type->couleur ?? '#6B7280'); ?>"></div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900"><?php echo e($type->nom_type); ?></div>
                                            <div class="text-sm text-slate-500"><?php echo e($type->categorie ?? 'Non définie'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-slate-900"><?php echo e($type->nombre_reunions); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-slate-900"><?php echo e(number_format($type->total_participants)); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-green-600"><?php echo e(number_format($type->moyenne_participants, 1)); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-slate-600"><?php echo e($type->max_participants); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-purple-600"><?php echo e($type->total_decisions ?? 0); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-slate-900"><?php echo e(number_format($type->satisfaction_moyenne ?? 0, 1)); ?>%</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-sm font-medium text-amber-600"><?php echo e(number_format($type->note_moyenne ?? 0, 1)); ?></span>
                                        <span class="text-xs text-slate-500 ml-1">/10</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($type->nombre_annulations > 0): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <?php echo e($type->nombre_annulations); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-slate-400">0</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Top performers -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Réunions les mieux notées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Top réunions
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php if(isset($topReunions) && count($topReunions) > 0): ?>
                    <?php $__currentLoopData = $topReunions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reunion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate"><?php echo e($reunion->titre); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e(\Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y')); ?></p>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex items-center">
                                <span class="text-sm font-bold text-amber-600"><?php echo e($reunion->note_globale); ?>/10</span>
                                <div class="ml-2 flex">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= ceil($reunion->note_globale / 2)): ?>
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-slate-300 text-xs"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-sm text-slate-500 text-center py-4">Aucune évaluation disponible</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Réunions les plus fréquentées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    Plus fréquentées
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php if(isset($reunionsFrequentees) && count($reunionsFrequentees) > 0): ?>
                    <?php $__currentLoopData = $reunionsFrequentees->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reunion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate"><?php echo e($reunion->titre); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e(\Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y')); ?></p>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo e($reunion->nombre_participants_reel); ?> participants
                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-sm text-slate-500 text-center py-4">Aucune donnée de participation</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Organisateurs les plus actifs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                    Top organisateurs
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <?php if(isset($topOrganisateurs) && count($topOrganisateurs) > 0): ?>
                    <?php $__currentLoopData = $topOrganisateurs->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organisateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-medium mr-3">
                                    <?php echo e(substr($organisateur->nom, 0, 1)); ?><?php echo e(substr($organisateur->prenom, 0, 1)); ?>

                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900 truncate"><?php echo e($organisateur->nom); ?> <?php echo e($organisateur->prenom); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($organisateur->nombre_reunions); ?> réunion(s)</p>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <span class="text-xs text-slate-600"><?php echo e(number_format($organisateur->moyenne_participants, 1)); ?> moy.</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-sm text-slate-500 text-center py-4">Aucun organisateur trouvé</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions d'export -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="exportStatistiques('pdf')" class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i> Exporter en PDF
                </button>
                <button onclick="exportStatistiques('excel')" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i> Exporter en Excel
                </button>
                <button onclick="exportStatistiques('csv')" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i> Exporter en CSV
                </button>
                <button onclick="imprimerStatistiques()" class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données pour les graphiques (à injecter depuis PHP)
const evolutionData = <?php echo json_encode($evolutionData ?? [], 15, 512) ?>;
const typeData = <?php echo json_encode($typeData ?? [], 15, 512) ?>;

// Configuration du graphique d'évolution
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
const evolutionChart = new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: evolutionData.map(item => item.mois),
        datasets: [{
            label: 'Nombre de réunions',
            data: evolutionData.map(item => item.nombre),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Participants totaux',
            data: evolutionData.map(item => item.participants),
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                }
            }
        }
    }
});

// Configuration du graphique par type
const typeCtx = document.getElementById('typeChart').getContext('2d');
const typeChart = new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: typeData.map(item => item.nom),
        datasets: [{
            data: typeData.map(item => item.nombre),
            backgroundColor: [
                '#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444',
                '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Gestion du menu des presets
document.getElementById('presetButton').addEventListener('click', function() {
    const menu = document.getElementById('presetMenu');
    menu.classList.toggle('hidden');
});

document.addEventListener('click', function(e) {
    if (!document.getElementById('presetButton').contains(e.target)) {
        document.getElementById('presetMenu').classList.add('hidden');
    }
});

function setPreset(periode) {
    const today = new Date();
    let dateDebut, dateFin = today;

    switch(periode) {
        case 'cette_semaine':
            dateDebut = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay());
            break;
        case 'ce_mois':
            dateDebut = new Date(today.getFullYear(), today.getMonth(), 1);
            break;
        case 'trimestre':
            const trimestre = Math.floor(today.getMonth() / 3);
            dateDebut = new Date(today.getFullYear(), trimestre * 3, 1);
            break;
        case 'semestre':
            const semestre = Math.floor(today.getMonth() / 6);
            dateDebut = new Date(today.getFullYear(), semestre * 6, 1);
            break;
        case 'annee':
            dateDebut = new Date(today.getFullYear(), 0, 1);
            break;
    }

    document.querySelector('input[name="date_debut"]').value = dateDebut.toISOString().split('T')[0];
    document.querySelector('input[name="date_fin"]').value = dateFin.toISOString().split('T')[0];
    document.getElementById('presetMenu').classList.add('hidden');
}

// Fonctions d'export
function exportStatistiques(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);

    window.open(`<?php echo e(route('private.reunions.statistiques')); ?>?${params.toString()}`, '_blank');
}

function imprimerStatistiques() {
    window.print();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/statistiques.blade.php ENDPATH**/ ?>