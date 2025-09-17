
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Moisson - <?php echo e($donnees['informations_generales']['theme']); ?></title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 10mm 8mm 12mm 8mm;
        }

        /* Styles de base - compatibles PDF */
        .header {
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #2E74B5;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 45px;
            height: 45px;
            border: 1px solid #ddd;
        }

        .church-name {
            font-size: 14px;
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 3px;
        }

        .church-details {
            font-size: 8px;
            color: #666;
        }

        .export-info {
            text-align: right;
            font-size: 8px;
            color: #666;
        }

        .main-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #2E74B5;
            margin: 12px 0 8px 0;
            padding: 12px 8px;
            background-color: #f5f7fa;
            border: 2px solid #2E74B5;
        }

        .subtitle {
            font-size: 12px;
            font-weight: normal;
            color: #555;
            margin-top: 4px;
        }

        /* Conteneur pour les statistiques - utilise table au lieu de grid */
        .performance-overview {
            background-color: #e8f5e8;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #4CAF50;
        }

        .performance-title {
            font-size: 12px;
            font-weight: bold;
            color: #2E7D32;
            margin-bottom: 8px;
            text-align: center;
        }

        .performance-stats {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .performance-stats td {
            text-align: center;
            background-color: white;
            padding: 6px 4px;
            border: 1px solid #e0e0e0;
            width: 25%;
        }

        .performance-value {
            font-size: 11px;
            font-weight: bold;
            color: #1976D2;
            display: block;
        }

        .performance-label {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
        }

        /* Barre de progression simplifiée */
        .progress-container {
            margin: 8px 0;
        }

        .progress-bar {
            width: 100%;
            height: 16px;
            background-color: #e9ecef;
            border: 1px solid #ddd;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            font-size: 9px;
            text-align: center;
            line-height: 14px;
        }

        /* Informations utilisant des tableaux */
        .info-section {
            width: 100%;
            margin: 12px 0;
        }

        .info-card {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 8px;
            border-left: 3px solid #4472C4;
        }

        .info-title {
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .info-content {
            font-size: 9px;
            line-height: 1.4;
        }

        .info-content .label {
            font-weight: bold;
            color: #555;
        }

        /* Passages bibliques */
        .passages-bibliques {
            background-color: #fff8e1;
            padding: 8px;
            border-left: 3px solid #ff9800;
            margin: 8px 0;
        }

        .passages-title {
            font-weight: bold;
            color: #f57c00;
            margin-bottom: 4px;
            font-size: 9px;
        }

        .passages-table {
            width: 100%;
            border-collapse: collapse;
        }

        .passages-table td {
            background-color: white;
            padding: 4px 6px;
            border: 1px solid #ffcc02;
            font-style: italic;
            font-size: 8px;
            width: 50%;
        }

        /* Sections */
        .section {
            margin: 15px 0 8px 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 6px;
            padding: 6px 8px;
            background-color: #f0f4f8;
            border-left: 4px solid #4472C4;
        }

        /* Tableaux de détail */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8px;
        }

        .detail-table th {
            background-color: #4472C4;
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #2E74B5;
            font-size: 7px;
        }

        .detail-table td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            font-size: 8px;
            vertical-align: top;
        }

        .detail-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Badges de statut */
        .status-badge {
            padding: 2px 4px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }

        .status-actif {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactif {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Styles utilitaires */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 8px;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            color: #999;
            font-style: italic;
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
            font-size: 9px;
        }

        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 6px 8px;
            margin: 6px 0;
            font-size: 8px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
        }

        .summary-box {
            background-color: #e3f2fd;
            padding: 8px;
            border: 1px solid #2196F3;
            margin: 8px 0;
        }

        .summary-title {
            font-weight: bold;
            color: #1976D2;
            font-size: 9px;
            margin-bottom: 4px;
        }

        .summary-content {
            font-size: 8px;
        }

        /* Largeurs de colonnes */
        .narrow-col { width: 8%; }
        .medium-col { width: 12%; }
        .wide-col { width: 20%; }
        .contact-col { width: 10%; }

        /* Styles compacts */
        .compact-section {
            margin: 8px 0 4px 0;
        }

        .compact-section .section-title {
            margin-bottom: 4px;
            padding: 4px 6px;
            font-size: 11px;
        }

        /* Éviter les sauts de page */
        .avoid-break {
            page-break-inside: avoid;
        }

        /* Footer de page - style simple */
        .page-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <!-- Header avec table -->
    <div class="header avoid-break">
        <table>
            <tr>
                <td style="width: 60px;">
                    <?php if(!empty(config('app.church_logo')) && file_exists(public_path(config('app.church_logo')))): ?>
                        <img src="<?php echo e(public_path(config('app.church_logo'))); ?>" alt="Logo" class="logo">
                    <?php endif; ?>
                </td>
                <td style="vertical-align: top;">
                    <div class="church-name"><?php echo e(config('app.church_name', 'Église Baptiste')); ?></div>
                    <div class="church-details">
                        <?php echo e(config('app.church_address', '')); ?><br>
                        Tél: <?php echo e(config('app.church_phone', '')); ?> | Email: <?php echo e(config('app.church_email', '')); ?>

                    </div>
                </td>
                <td style="width: 150px; vertical-align: top;">
                    <div class="export-info">
                        <strong>Export:</strong> <?php echo e(now()->format('d/m/Y H:i')); ?><br>
                        <strong>Statut:</strong> <?php echo e($donnees['informations_generales']['statut']); ?>

                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Titre principal -->
    <div class="main-title avoid-break">
        RAPPORT DÉTAILLÉ DE MOISSON
        <div class="subtitle"><?php echo e($donnees['informations_generales']['theme']); ?></div>
    </div>

    <!-- Vue d'ensemble avec table -->
    <div class="performance-overview avoid-break">
        <div class="performance-title">Performance de la Moisson</div>

        <table class="performance-stats">
            <tr>
                <td>
                    <div class="performance-value"><?php echo e(number_format($donnees['objectifs_et_realisations']['objectif_initial'], 0, ',', ' ')); ?> FCFA</div>
                    <div class="performance-label">Objectif Initial</div>
                </td>
                <td>
                    <div class="performance-value"><?php echo e(number_format($donnees['objectifs_et_realisations']['montant_collecte'], 0, ',', ' ')); ?> FCFA</div>
                    <div class="performance-label">Montant Collecté</div>
                </td>
                <td>
                    <div class="performance-value"><?php echo e($donnees['objectifs_et_realisations']['pourcentage_realisation']); ?>%</div>
                    <div class="performance-label">Taux Réalisation</div>
                </td>
                <td>
                    <div class="performance-value"><?php echo e($donnees['objectifs_et_realisations']['statut_progression']); ?></div>
                    <div class="performance-label">Statut</div>
                </td>
            </tr>
        </table>

        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo e(min($donnees['objectifs_et_realisations']['pourcentage_realisation'], 100)); ?>%;">
                    <?php echo e($donnees['objectifs_et_realisations']['pourcentage_realisation']); ?>%
                </div>
            </div>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="info-card avoid-break">
        <div class="info-title">Informations Générales</div>
        <div class="info-content">
            <span class="label">Date:</span> <?php echo e($donnees['informations_generales']['date']); ?> •
            <span class="label">Culte:</span> <?php echo e($donnees['informations_generales']['culte']); ?> •
            <span class="label">Créateur:</span> <?php echo e($donnees['informations_generales']['createur']); ?> •
            <span class="label">Statut:</span>
            <span class="status-badge <?php echo e($donnees['informations_generales']['statut'] === 'Actif' ? 'status-actif' : 'status-inactif'); ?>">
                <?php echo e($donnees['informations_generales']['statut']); ?>

            </span> •
            <span class="label">Créé le:</span> <?php echo e($donnees['informations_generales']['date_creation']); ?>

        </div>
    </div>

    <!-- Résumé financier -->
    <div class="info-card" style="background-color: #e8f5e8; border-left: 3px solid #4CAF50;">
        <div class="info-title" style="color: #2E7D32;">Résumé Financier</div>
        <div class="info-content">
            <span class="label">Reste à collecter:</span>
            <span class="amount" style="color: #d32f2f;"><?php echo e(number_format($donnees['objectifs_et_realisations']['reste_a_collecter'], 0, ',', ' ')); ?> FCFA</span> •
            <span class="label">Montant supplémentaire:</span>
            <span class="amount" style="color: #388e3c;"><?php echo e(number_format($donnees['objectifs_et_realisations']['montant_supplementaire'], 0, ',', ' ')); ?> FCFA</span> •
            <span class="label">Dernière modification:</span> <?php echo e($donnees['informations_generales']['derniere_modification']); ?>

        </div>
    </div>

    <!-- Passages bibliques avec table -->
    <?php if(!empty($donnees['passages_bibliques'])): ?>
    <div class="passages-bibliques avoid-break">
        <div class="passages-title">Passages Bibliques de Référence</div>
        <table class="passages-table">
            <?php $passages = collect($donnees['passages_bibliques'])->chunk(2); ?>
            <?php $__currentLoopData = $passages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td>
                            <?php if(is_array($passage)): ?>
                                <?php echo e($passage['livre'] ?? ''); ?> <?php echo e($passage['chapitre'] ?? ''); ?>:<?php echo e($passage['verset_debut'] ?? ''); ?><?php if(!empty($passage['verset_fin'])): ?>-<?php echo e($passage['verset_fin']); ?><?php endif; ?>
                            <?php else: ?>
                                <?php echo e($passage); ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($row->count() === 1): ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    <?php endif; ?>

    <!-- Résumé des activités -->
    <div class="summary-box avoid-break">
        <div class="summary-title">Résumé des Activités</div>
        <div class="summary-content">
            Passages: <strong><?php echo e(count($donnees['detail_passages'])); ?></strong> •
            Ventes: <strong><?php echo e(count($donnees['detail_ventes'])); ?></strong> •
            Engagements: <strong><?php echo e(count($donnees['detail_engagements'])); ?></strong>
            <?php if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0): ?>
                • <span style="color: #dc3545;">En retard: <strong><?php echo e(collect($donnees['detail_engagements'])->where('en_retard', true)->count()); ?></strong></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section Passages -->
    <div class="compact-section">
        <div class="section-title">Détail des Passages (<?php echo e(count($donnees['detail_passages'])); ?>)</div>

        <?php if(count($donnees['detail_passages']) > 0): ?>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="wide-col">CATEGORIE</th>
                        <th class="medium-col">CLASSE</th>
                        <th class="narrow-col">OBJECTIF (FCFA)</th>
                        <th class="narrow-col">COLLECTÉ (FCFA)</th>
                        <th class="narrow-col">ÉVOLUTION</th>
                        <th class="medium-col">COLLECTEUR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $donnees['detail_passages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($passage['categorie']); ?></td>
                            <td><?php echo e($passage['classe'] ?? 'N/A'); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($passage['objectif'], 0, ',', ' ')); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($passage['collecte'], 0, ',', ' ')); ?></td>
                            <td class="text-center font-bold"><?php echo e($passage['pourcentage']); ?>%</td>
                            <td><?php echo e($passage['collecteur']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Aucun passage enregistré</div>
        <?php endif; ?>
    </div>

    <!-- Section Ventes -->
    <div class="compact-section">
        <div class="section-title">Détail des Ventes (<?php echo e(count($donnees['detail_ventes'])); ?>)</div>

        <?php if(count($donnees['detail_ventes']) > 0): ?>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="medium-col">CATEGORIE</th>
                        <th class="wide-col">DESCRIPTION</th>
                        <th class="narrow-col">OBJECTIF (FCFA)</th>
                        <th class="narrow-col">COLLECTÉ (FCFA)</th>
                        <th class="narrow-col">ÉVOLUTION</th>
                        <th class="medium-col">COLLECTEUR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $donnees['detail_ventes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($vente['categorie']); ?></td>
                            <td><?php echo e($vente['description'] ?? 'N/A'); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($vente['objectif'], 0, ',', ' ')); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($vente['collecte'], 0, ',', ' ')); ?></td>
                            <td class="text-center font-bold"><?php echo e($vente['pourcentage']); ?>%</td>
                            <td><?php echo e($vente['collecteur']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Aucune vente enregistrée</div>
        <?php endif; ?>
    </div>

    <!-- Section Engagements -->
    <div class="compact-section">
        <div class="section-title">Détail des Engagements (<?php echo e(count($donnees['detail_engagements'])); ?>)</div>

        <?php if(count($donnees['detail_engagements']) > 0): ?>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th class="medium-col">TYPE</th>
                        <th class="wide-col">DONNATEUR</th>
                        <th class="narrow-col">OBJECTIF (FCFA)</th>
                        <th class="narrow-col">COLLECTÉ  (FCFA)</th>
                        <th class="narrow-col">ÉVOLUTION </th>
                        <th class="medium-col">ÉCHÉANCE</th>
                        <th class="contact-col">CONTACT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $donnees['detail_engagements']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engagement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($engagement['en_retard'] ? 'status-inactif' : ''); ?>">
                            <td><?php echo e($engagement['categorie']); ?></td>
                            <td><?php echo e($engagement['donateur'] ?? $engagement['nom_entite']); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($engagement['objectif'], 0, ',', ' ')); ?></td>
                            <td class="text-right amount"><?php echo e(number_format($engagement['collecte'], 0, ',', ' ')); ?></td>
                            <td class="text-center font-bold"><?php echo e($engagement['pourcentage']); ?>%</td>
                            <td class="text-center"><?php echo e($engagement['date_echeance'] ?? 'N/A'); ?></td>
                            <td class="contact-col">
                                <?php if($engagement['telephone']): ?><?php echo e($engagement['telephone']); ?><br><?php endif; ?>
                                <?php if($engagement['email']): ?><?php echo e($engagement['email']); ?><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0): ?>
                <div class="alert-box alert-danger">
                    <strong>⚠️ <?php echo e(collect($donnees['detail_engagements'])->where('en_retard', true)->count()); ?> engagement(s) en retard</strong> nécessitent un suivi urgent.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-data">Aucun engagement enregistré</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/moissons/moisson_complete_pdf.blade.php ENDPATH**/ ?>