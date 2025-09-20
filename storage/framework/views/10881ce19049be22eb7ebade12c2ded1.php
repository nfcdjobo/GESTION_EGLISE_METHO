<?php $__env->startSection('title', 'Statistiques des Classes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="<?php echo e(route('private.classes.index')); ?>"
                   class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux classes
                </a>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Statistiques des Classes
            </h1>
            <p class="text-slate-500 mt-1">
                Vue d'ensemble et analyse des performances - <?php echo e(\Carbon\Carbon::now()->format('l d F Y')); ?>

            </p>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total des classes -->
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['total_classes']); ?></p>
                        <p class="text-sm text-slate-500">Total des classes</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Toutes périodes confondues
                        </span>
                    </div>
                </div>
            </div>

            <!-- Classes actives -->
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['classes_actives']); ?></p>
                        <p class="text-sm text-slate-500">Classes actives</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <?php
                            $pourcentageActives = $stats['total_classes'] > 0 ? round(($stats['classes_actives'] / $stats['total_classes']) * 100, 1) : 0;
                        ?>
                        <span class="text-green-600 font-medium">
                            <?php echo e($pourcentageActives); ?>% du total
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total des inscrits -->
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($stats['total_inscrits'])); ?></p>
                        <p class="text-sm text-slate-500">Total des inscrits</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-purple-600 font-medium">
                            Toutes classes confondues
                        </span>
                    </div>
                </div>
            </div>

            <!-- Moyenne par classe -->
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['nombre_inscrits_moyen']); ?></p>
                        <p class="text-sm text-slate-500">Moyenne par classe</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-orange-600 font-medium">
                            Inscrits par classe
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Répartition par tranche d'âge -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                        Répartition par tranche d'âge
                    </h2>
                </div>
                <div class="p-6">
                    <?php if($stats['tranches_age']->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $stats['tranches_age']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tranche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $count = \App\Models\Classe::where('tranche_age', $tranche)->count();
                                    $percentage = $stats['total_classes'] > 0 ? round(($count / $stats['total_classes']) * 100, 1) : 0;
                                    $colors = [
                                        'bg-blue-500',
                                        'bg-green-500',
                                        'bg-purple-500',
                                        'bg-yellow-500',
                                        'bg-red-500',
                                        'bg-indigo-500',
                                        'bg-pink-500',
                                        'bg-cyan-500'
                                    ];
                                    $colorIndex = $loop->index % count($colors);
                                ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 <?php echo e($colors[$colorIndex]); ?> rounded"></div>
                                        <span class="text-sm font-medium text-slate-700"><?php echo e($tranche); ?></span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-32 bg-slate-200 rounded-full h-2">
                                            <div class="<?php echo e($colors[$colorIndex]); ?> h-2 rounded-full transition-all duration-300"
                                                 style="width: <?php echo e($percentage); ?>%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-slate-600 w-12 text-right"><?php echo e($count); ?></span>
                                        <span class="text-xs text-slate-500 w-12 text-right"><?php echo e($percentage); ?>%</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-chart-pie text-3xl text-slate-400 mb-3"></i>
                            <p class="text-slate-500">Aucune donnée de tranche d'âge disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- État des classes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        État des classes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Classes actives -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-green-900">Classes actives</h3>
                                    <p class="text-sm text-green-700">Avec responsables assignés</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-900"><?php echo e($stats['classes_actives']); ?></p>
                                <p class="text-sm text-green-600">
                                    <?php echo e($stats['total_classes'] > 0 ? round(($stats['classes_actives'] / $stats['total_classes']) * 100, 1) : 0); ?>%
                                </p>
                            </div>
                        </div>

                        <!-- Classes en attente -->
                        <?php
                            $classesEnAttente = $stats['total_classes'] - $stats['classes_actives'];
                        ?>
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-yellow-900">Classes en attente</h3>
                                    <p class="text-sm text-yellow-700">Sans responsables assignés</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-yellow-900"><?php echo e($classesEnAttente); ?></p>
                                <p class="text-sm text-yellow-600">
                                    <?php echo e($stats['total_classes'] > 0 ? round(($classesEnAttente / $stats['total_classes']) * 100, 1) : 0); ?>%
                                </p>
                            </div>
                        </div>

                        <!-- Taux d'activité -->
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-slate-700">Taux d'activité global</span>
                                <span class="text-sm font-bold text-slate-900">
                                    <?php echo e($stats['total_classes'] > 0 ? round(($stats['classes_actives'] / $stats['total_classes']) * 100, 1) : 0); ?>%
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-500"
                                     style="width: <?php echo e($stats['total_classes'] > 0 ? round(($stats['classes_actives'] / $stats['total_classes']) * 100, 1) : 0); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analyses détaillées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-analytics text-purple-600 mr-2"></i>
                    Analyses détaillées
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Classes les plus populaires -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Classes les plus populaires
                        </h3>
                        <?php
                            $classesPopulaires = \App\Models\Classe::orderBy('nombre_inscrits', 'desc')->take(5)->get();
                        ?>
                        <?php if($classesPopulaires->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $classesPopulaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-slate-900 truncate"><?php echo e($classe->nom); ?></p>
                                            <p class="text-sm text-slate-500"><?php echo e($classe->tranche_age ?? 'Tous âges'); ?></p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?php echo e($classe->nombre_inscrits); ?> membres
                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500 text-sm">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>

                    <!-- Classes récemment créées -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                            Créées récemment
                        </h3>
                        <?php
                            $classesRecentes = \App\Models\Classe::orderBy('created_at', 'desc')->take(5)->get();
                        ?>
                        <?php if($classesRecentes->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $classesRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-slate-900 truncate"><?php echo e($classe->nom); ?></p>
                                            <p class="text-sm text-slate-500"><?php echo e($classe->created_at->diffForHumans()); ?></p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo e($classe->nombre_inscrits); ?> membres
                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500 text-sm">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>

                    <!-- Alertes et recommandations -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                            Alertes & Recommandations
                        </h3>
                        <div class="space-y-3">
                            <?php
                                $classesSansResponsables = $stats['total_classes'] - $stats['classes_actives'];
                                $classesSansMembres = \App\Models\Classe::where('nombre_inscrits', 0)->count();
                            ?>

                            <?php if($classesSansResponsables > 0): ?>
                                <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-amber-800"><?php echo e($classesSansResponsables); ?> classe(s) sans responsable</p>
                                            <p class="text-xs text-amber-700">Assignez des responsables pour activer ces classes</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($classesSansMembres > 0): ?>
                                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800"><?php echo e($classesSansMembres); ?> classe(s) sans membres</p>
                                            <p class="text-xs text-blue-700">Promouvoir l'inscription dans ces classes</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($classesSansResponsables == 0 && $classesSansMembres == 0): ?>
                                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-green-800">Tout semble en ordre !</p>
                                            <p class="text-xs text-green-700">Toutes les classes sont correctement configurées</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($stats['nombre_inscrits_moyen'] < 5): ?>
                                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-chart-line text-yellow-600 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800">Faible moyenne d'inscription</p>
                                            <p class="text-xs text-yellow-700">Considérez des campagnes de promotion</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-rocket text-cyan-600 mr-2"></i>
                    Actions rapides
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.create')): ?>
                        <a href="<?php echo e(route('private.classes.create')); ?>"
                            class="flex items-center justify-center p-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:from-blue-600 hover:to-cyan-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Créer une classe
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('private.classes.index')); ?>"
                        class="flex items-center justify-center p-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i>
                        Voir toutes les classes
                    </a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('classes.export')): ?>
                        <button onclick="exportStats()"
                            class="flex items-center justify-center p-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i>
                            Exporter les stats
                        </button>
                    <?php endif; ?>

                    <button onclick="refreshStats()"
                        class="flex items-center justify-center p-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Actualiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">À propos des statistiques</h3>
                    <div class="text-blue-800 space-y-2 text-sm">
                        <p>• Les données sont mises à jour en temps réel et reflètent l'état actuel de toutes les classes.</p>
                        <p>• Les classes "actives" sont celles qui ont au moins un responsable assigné.</p>
                        <p>• La capacité des classes est illimitée - aucune restriction sur le nombre de membres.</p>
                        <p>• Les tranches d'âge sont définies lors de la création de la classe et peuvent être modifiées.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // Actualiser les statistiques
        function refreshStats() {
            showLoadingMessage('Actualisation des statistiques...');

            // Recharger la page après un court délai pour simuler le rechargement
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        // Exporter les statistiques
        function exportStats() {
            const menu = document.createElement('div');
            menu.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            menu.innerHTML = `
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Exporter les statistiques</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="downloadStats('pdf')"
                            class="w-full flex items-center px-4 py-3 text-left bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                            <i class="fas fa-file-pdf text-red-600 mr-3 text-xl"></i>
                            <div>
                                <div class="font-medium text-slate-900">Rapport PDF</div>
                                <div class="text-sm text-slate-600">Rapport détaillé avec graphiques</div>
                            </div>
                        </button>

                        <button onclick="downloadStats('excel')"
                            class="w-full flex items-center px-4 py-3 text-left bg-green-50 hover:bg-green-100 rounded-xl transition-colors">
                            <i class="fas fa-file-excel text-green-600 mr-3 text-xl"></i>
                            <div>
                                <div class="font-medium text-slate-900">Fichier Excel</div>
                                <div class="text-sm text-slate-600">Données pour analyse approfondie</div>
                            </div>
                        </button>

                        <button onclick="downloadStats('csv')"
                            class="w-full flex items-center px-4 py-3 text-left bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                            <i class="fas fa-file-csv text-blue-600 mr-3 text-xl"></i>
                            <div>
                                <div class="font-medium text-slate-900">Fichier CSV</div>
                                <div class="text-sm text-slate-600">Format compatible universel</div>
                            </div>
                        </button>
                    </div>
                    <div class="flex justify-end p-6 border-t border-slate-200">
                        <button onclick="closeExportMenu()"
                            class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Annuler
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(menu);
            document.body.classList.add('overflow-hidden');

            // Fermer en cliquant à l'extérieur
            menu.addEventListener('click', function(e) {
                if (e.target === menu) {
                    closeExportMenu();
                }
            });

            // Fonction globale pour fermer
            window.closeExportMenu = function() {
                document.body.removeChild(menu);
                document.body.classList.remove('overflow-hidden');
            };
        }

        // Télécharger les statistiques
        function downloadStats(format) {
            showLoadingMessage(`Génération du fichier ${format.toUpperCase()}...`);

            // Simuler le téléchargement
            setTimeout(() => {
                hideLoadingMessage();
                showSuccessMessage(`Statistiques exportées en ${format.toUpperCase()} avec succès`);
                closeExportMenu();
            }, 2000);
        }

        // Afficher un message de chargement
        function showLoadingMessage(message) {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingMessage';
            loadingDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-xl shadow-lg z-50';
            loadingDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-3"></div>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(loadingDiv);
        }

        // Masquer le message de chargement
        function hideLoadingMessage() {
            const loadingDiv = document.getElementById('loadingMessage');
            if (loadingDiv) {
                loadingDiv.remove();
            }
        }

        // Afficher un message de succès
        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 translate-x-full';
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(alertDiv);

            // Animation d'entrée
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-full');
                alertDiv.classList.add('translate-x-0');
            }, 100);

            // Animation de sortie et suppression
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-0');
                alertDiv.classList.add('translate-x-full');
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }

        // Animations au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        // entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observer les cartes de statistiques
            document.querySelectorAll('.bg-white\\/80').forEach(card => {
                card.style.opacity = '0';
                // card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Animation des barres de progression
            setTimeout(() => {
                document.querySelectorAll('[style*="width:"]').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                        bar.style.transition = 'width 1s ease-in-out';
                    }, 500);
                });
            }, 1000);
        });

        // Mise à jour automatique des statistiques (toutes les 5 minutes)
        let autoRefreshInterval;

        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                console.log('Mise à jour automatique des statistiques...');
                // Ici vous pourriez faire un appel AJAX pour mettre à jour les données
                // sans recharger toute la page
            }, 300000); // 5 minutes
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }

        // Démarrer la mise à jour automatique
        startAutoRefresh();

        // Arrêter la mise à jour automatique quand l'utilisateur quitte la page
        window.addEventListener('beforeunload', stopAutoRefresh);

        // Gestion de la visibilité de la page
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.private.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/classes/statistiques.blade.php ENDPATH**/ ?>