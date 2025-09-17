
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Moissons</title>
    <style>
        @page {
            margin: 15mm 10mm 20mm 10mm;
            @top-left {
                content: "<?php echo e($donnees['meta']['eglise']['nom'] ?? 'Église'); ?>";
                font-size: 10px;
                color: #666;
            }
            @top-right {
                content: "Page " counter(page) " sur " counter(pages);
                font-size: 10px;
                color: #666;
            }
            @bottom-center {
                content: "Généré le <?php echo e($donnees['meta']['date_export']); ?> - <?php echo e($donnees['meta']['eglise']['nom'] ?? 'Système de Gestion des Moissons'); ?>";
                font-size: 9px;
                color: #888;
                border-top: 1px solid #ddd;
                padding-top: 5px;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2E74B5;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            border-radius: 8px;
        }

        .church-info {
            text-align: left;
        }

        .church-name {
            font-size: 18px;
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 5px;
        }

        .church-details {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }

        .export-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .main-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #2E74B5;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            border: 2px solid #2E74B5;
        }

        .filters-info {
            background: #f8f9fa;
            padding: 12px;
            margin: 15px 0;
            border-radius: 6px;
            border-left: 4px solid #4472C4;
        }

        .filters-title {
            font-weight: bold;
            color: #4472C4;
            margin-bottom: 8px;
        }

        .filter-item {
            display: inline-block;
            margin-right: 20px;
            font-size: 10px;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            background: #f1f3f5;
            padding: 15px;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #2E74B5;
            display: block;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9px;
        }

        .data-table th {
            background: linear-gradient(135deg, #4472C4 0%, #2E74B5 100%);
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #2E74B5;
            font-size: 8px;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .data-table tbody tr:hover {
            background-color: #e3f2fd;
        }

        /* Couleurs selon le statut */
        .status-atteint {
            background-color: #d4edda !important;
            color: #155724;
            font-weight: bold;
        }

        .status-presque {
            background-color: #fff3cd !important;
            color: #856404;
        }

        .status-cours {
            background-color: #d1ecf1 !important;
            color: #0c5460;
        }

        .status-faible {
            background-color: #f8d7da !important;
            color: #721c24;
        }

        .status-debut {
            background-color: #ffeaa7 !important;
            color: #6c5400;
        }

        .totals-row {
            background: linear-gradient(135deg, #2E74B5 0%, #4472C4 100%) !important;
            color: white !important;
            font-weight: bold;
        }

        .totals-row td {
            border-color: #2E74B5 !important;
            padding: 10px 4px !important;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2E74B5;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #4472C4;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }

        .page-break {
            page-break-before: always;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-small {
            font-size: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <?php if(!empty($donnees['meta']['eglise']['logo']) && file_exists(public_path($donnees['meta']['eglise']['logo']))): ?>
                <img src="<?php echo e(public_path($donnees['meta']['eglise']['logo'])); ?>" alt="Logo" class="logo">
            <?php endif; ?>
            <div class="church-info">
                <div class="church-name"><?php echo e($donnees['meta']['eglise']['nom']); ?></div>
                <div class="church-details">
                    <?php echo e($donnees['meta']['eglise']['adresse']); ?><br>
                    Tél: <?php echo e($donnees['meta']['eglise']['telephone']); ?><br>
                    Email: <?php echo e($donnees['meta']['eglise']['email']); ?>

                </div>
            </div>
        </div>
        <div class="export-info">
            <strong>Date d'export:</strong> <?php echo e($donnees['meta']['date_export']); ?><br>
            <strong>Période:</strong> <?php echo e($donnees['meta']['periode']); ?><br>
            <strong>Total moissons:</strong> <?php echo e($donnees['meta']['nombre_total']); ?>

        </div>
    </div>

    <!-- Titre principal -->
    <div class="main-title">
        RAPPORT DE GESTION DES MOISSONS
    </div>

    <!-- Informations sur les filtres -->
    <?php if(!empty($filtres)): ?>
    <div class="filters-info">
        <div class="filters-title">Filtres appliqués:</div>
        <?php if(isset($filtres['date_debut'])): ?>
            <span class="filter-item"><strong>Date début:</strong> <?php echo e(\Carbon\Carbon::parse($filtres['date_debut'])->format('d/m/Y')); ?></span>
        <?php endif; ?>
        <?php if(isset($filtres['date_fin'])): ?>
            <span class="filter-item"><strong>Date fin:</strong> <?php echo e(\Carbon\Carbon::parse($filtres['date_fin'])->format('d/m/Y')); ?></span>
        <?php endif; ?>
        <?php if(isset($filtres['status'])): ?>
            <span class="filter-item"><strong>Statut:</strong> <?php echo e($filtres['status'] ? 'Actif' : 'Inactif'); ?></span>
        <?php endif; ?>
        <?php if(isset($filtres['statut_progression'])): ?>
            <span class="filter-item"><strong>Progression:</strong> <?php echo e($filtres['statut_progression']); ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Statistiques résumées -->
    <div class="summary-stats">
        <div class="stat-item">
            <span class="stat-value"><?php echo e($donnees['statistiques']['total_objectifs']); ?> FCFA</span>
            <div class="stat-label">Objectifs Totaux</div>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo e($donnees['statistiques']['total_collecte']); ?> FCFA</span>
            <div class="stat-label">Montant Collecté</div>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo e($donnees['statistiques']['objectifs_atteints']); ?></span>
            <div class="stat-label">Objectifs Atteints</div>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo e($donnees['statistiques']['performance_moyenne']); ?></span>
            <div class="stat-label">Performance Moyenne</div>
        </div>
    </div>

    <!-- Tableau des données -->
    <div class="section-title">Détail des Moissons</div>

    <?php if(count($donnees['donnees']) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Thème</th>
                    <th style="width: 8%;">Date</th>
                    <th style="width: 10%;">Culte</th>
                    <th style="width: 10%;">Objectif</th>
                    <th style="width: 10%;">Collecté</th>
                    <th style="width: 8%;">%</th>
                    <th style="width: 12%;">Statut</th>
                    <th style="width: 6%;">P</th>
                    <th style="width: 6%;">V</th>
                    <th style="width: 6%;">E</th>
                    <th style="width: 9%;">Créateur</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $donnees['donnees']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moisson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($moisson['statut'] === 'Objectif atteint' ? 'status-atteint' :
                        ($moisson['statut'] === 'Presque atteint' ? 'status-presque' :
                        ($moisson['statut'] === 'En cours' ? 'status-cours' :
                        ($moisson['statut'] === 'Début' ? 'status-debut' : 'status-faible')))); ?>">
                        <td style="text-align: left; padding-left: 6px;"><?php echo e($moisson['theme']); ?></td>
                        <td><?php echo e($moisson['date']); ?></td>
                        <td><?php echo e($moisson['culte']); ?></td>
                        <td class="text-right"><?php echo e($moisson['objectif']); ?></td>
                        <td class="text-right"><?php echo e($moisson['collecte']); ?></td>
                        <td class="font-bold"><?php echo e($moisson['pourcentage']); ?></td>
                        <td><?php echo e($moisson['statut']); ?></td>
                        <td><?php echo e($moisson['nb_passages']); ?></td>
                        <td><?php echo e($moisson['nb_ventes']); ?></td>
                        <td><?php echo e($moisson['nb_engagements']); ?></td>
                        <td style="text-align: left; padding-left: 4px;"><?php echo e($moisson['createur']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Ligne de totaux -->
                <tr class="totals-row">
                    <td colspan="3" class="font-bold">TOTAUX GÉNÉRAUX</td>
                    <td class="text-right font-bold"><?php echo e($donnees['statistiques']['total_objectifs']); ?></td>
                    <td class="text-right font-bold"><?php echo e($donnees['statistiques']['total_collecte']); ?></td>
                    <td class="font-bold"><?php echo e($donnees['statistiques']['performance_moyenne']); ?></td>
                    <td colspan="5" class="text-center font-bold"><?php echo e($donnees['statistiques']['objectifs_atteints']); ?> objectifs atteints</td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <h3>Aucune donnée disponible</h3>
            <p>Aucune moisson ne correspond aux critères sélectionnés.</p>
        </div>
    <?php endif; ?>

    <!-- Légende des statuts -->
    <div class="section-title">Légende des Statuts</div>
    <div style="display: flex; justify-content: space-around; margin: 15px 0; font-size: 9px;">
        <div style="text-align: center;">
            <div style="width: 20px; height: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; margin: 0 auto 3px;"></div>
            <div>Objectif atteint</div>
        </div>
        <div style="text-align: center;">
            <div style="width: 20px; height: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; margin: 0 auto 3px;"></div>
            <div>Presque atteint</div>
        </div>
        <div style="text-align: center;">
            <div style="width: 20px; height: 15px; background-color: #d1ecf1; border: 1px solid #bee5eb; margin: 0 auto 3px;"></div>
            <div>En cours</div>
        </div>
        <div style="text-align: center;">
            <div style="width: 20px; height: 15px; background-color: #ffeaa7; border: 1px solid #fdd835; margin: 0 auto 3px;"></div>
            <div>Début</div>
        </div>
        <div style="text-align: center;">
            <div style="width: 20px; height: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; margin: 0 auto 3px;"></div>
            <div>Très faible</div>
        </div>
    </div>
</body>
</html>

<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/moissons/liste_pdf.blade.php ENDPATH**/ ?>