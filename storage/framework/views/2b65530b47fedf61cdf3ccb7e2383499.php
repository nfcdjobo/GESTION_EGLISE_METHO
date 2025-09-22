<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Dashboard Église</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 18px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin: 0;
        }

        .metadata {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .metadata h2 {
            color: #1f2937;
            font-size: 12px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .metadata-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .metadata-item {
            flex: 1;
            min-width: 150px;
        }

        .metadata-label {
            font-weight: bold;
            color: #374151;
        }

        .metadata-value {
            color: #6b7280;
            margin-left: 5px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #059669;
            color: white;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 12px 0;
            border-radius: 3px;
        }

        .kpis-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .kpi-card {
            flex: 1;
            min-width: 120px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }

        .kpi-label {
            font-size: 8px;
            color: #6b7280;
            margin-bottom: 3px;
            font-weight: 500;
        }

        .kpi-value {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
        }

        .table-container {
            margin-bottom: 15px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            background-color: white;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #d1d5db;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #d1d5db;
            color: #1f2937;
            white-space: nowrap;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .number {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0.5cm;
            left: 1cm;
            right: 1cm;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        .highlight {
            background-color: #fef3c7;
            padding: 1px 3px;
            border-radius: 2px;
        }

        .positive {
            color: #059669;
            font-weight: bold;
        }

        .negative {
            color: #dc2626;
            font-weight: bold;
        }

        .trend {
            font-size: 8px;
            padding: 2px 6px;
            border-radius: 10px;
            display: inline-block;
        }

        .trend.up {
            background-color: #d1fae5;
            color: #065f46;
        }

        .trend.down {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
        }

        .summary-title {
            color: #1e40af;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .summary-text {
            font-size: 8px;
            line-height: 1.4;
            color: #1f2937;
        }

        .ratio-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .ratio-card {
            flex: 1;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 10px;
        }

        .ratio-card h3 {
            margin: 0 0 6px 0;
            color: #1f2937;
            font-size: 9px;
            font-weight: bold;
        }

        .ratio-item {
            margin-bottom: 5px;
            font-size: 8px;
            display: flex;
            justify-content: space-between;
        }

        .ratio-label {
            font-weight: bold;
        }

        .ratio-value {
            text-align: right;
        }

        .no-data {
            color: #6b7280;
            font-style: italic;
            text-align: center;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1><?php echo e($data['metadata']['church_name'] ?? 'Église - Tableau de Bord'); ?></h1>
        <p class="subtitle">Rapport d'Activités - Période <?php echo e($data['metadata']['period_label']); ?></p>
    </div>

    <!-- Métadonnées -->
    <div class="section">
        <h2 class="section-title">Informations du Rapport</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th>Du</th>
                        <th>Au</th>
                        <th>Exporté le</th>
                        <th>Exporté par</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($data['metadata']['period_label']); ?></td>
                        <td><?php echo e($data['metadata']['start_date']); ?></td>
                        <td><?php echo e($data['metadata']['end_date']); ?></td>
                        <td><?php echo e($data['metadata']['exported_at']); ?></td>
                        <td><?php echo e($data['metadata']['exported_by']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section KPIs -->
    <div class="section">
        <h2 class="section-title">Indicateurs Clés de Performance (KPIs)</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="center">Total Membres</th>
                        <th class="center">Nouveaux Membres</th>
                        <th class="center">Présence Moyenne</th>
                        <th class="center">Nombre de Cultes</th>
                        <th class="center">Total Offrandes</th>
                        <th class="center">FIMECO Progression</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center"><?php echo e(number_format($data['kpis']['total_membres'])); ?></td>
                        <td class="center positive">+<?php echo e(number_format($data['kpis']['nouveaux_membres'])); ?></td>
                        <td class="center"><?php echo e(number_format($data['kpis']['avg_participants'])); ?></td>
                        <td class="center"><?php echo e(number_format($data['kpis']['nombre_cultes'])); ?></td>
                        <td class="center"><?php echo e(number_format($data['kpis']['total_offrandes'], 0, ',', ' ')); ?> FCFA</td>
                        <td class="center"><?php echo e($data['kpis']['fimeco_progression']); ?>%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Évolution des Membres -->
    <div class="section">
        <h2 class="section-title">1. Évolution des Membres</h2>

        <?php if(!empty($data['members_evolution'])): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Total Membres</th>
                        <th class="number">Nouveaux</th>
                        <th class="number">Actifs</th>
                        <th class="number">Visiteurs</th>
                        <th class="number">Nouveaux Convertis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['members_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($member['period']); ?></td>
                        <td class="number"><?php echo e(number_format($member['total_membres'])); ?></td>
                        <td class="number positive"><?php echo e(number_format($member['nouveaux_membres'])); ?></td>
                        <td class="number"><?php echo e(number_format($member['membres_actifs'])); ?></td>
                        <td class="number"><?php echo e(number_format($member['visiteurs'])); ?></td>
                        <td class="number"><?php echo e(number_format($member['nouveaux_convertis'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucune donnée disponible pour cette période.</div>
        <?php endif; ?>
    </div>

    <!-- Section Présence aux Cultes -->
    <div class="section">
        <h2 class="section-title">2. Présence aux Cultes</h2>

        <?php if(!empty($data['culte_attendance'])): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Participants Moyens</th>
                        <th class="number">Physiques</th>
                        <th class="number">En Ligne</th>
                        <th class="number">Nouveaux Visiteurs</th>
                        <th class="number">Nb Cultes</th>
                        <th class="number">Taux Présence (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['culte_attendance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($culte['period']); ?></td>
                        <td class="number"><?php echo e(number_format($culte['avg_participants'])); ?></td>
                        <td class="number"><?php echo e(number_format($culte['participants_physiques'])); ?></td>
                        <td class="number"><?php echo e(number_format($culte['participants_en_ligne'])); ?></td>
                        <td class="number"><?php echo e(number_format($culte['nouveaux_visiteurs'])); ?></td>
                        <td class="number"><?php echo e(number_format($culte['nombre_cultes'])); ?></td>
                        <td class="number"><?php echo e(number_format($culte['taux_presence'], 1)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucune donnée de culte disponible pour cette période.</div>
        <?php endif; ?>
    </div>

    <!-- Section Offrandes -->
    <div class="section page-break">
        <h2 class="section-title">3. Évolution des Offrandes</h2>

        <?php if(!empty($data['offrandes_evolution'])): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Dîmes (FCFA)</th>
                        <th class="number">Offrandes Ordinaires</th>
                        <th class="number">Offrandes Libres</th>
                        <th class="number">Offrandes Spéciales</th>
                        <th class="number">Missions</th>
                        <th class="number">Construction</th>
                        <th class="number">Total (FCFA)</th>
                        <th class="number">Nb Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['offrandes_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offrande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($offrande['period']); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['dimes'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['offrandes_ordinaires'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['offrandes_libres'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['offrandes_speciales'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['offrandes_missions'] ?? 0, 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['offrandes_construction'] ?? 0, 0, ',', ' ')); ?></td>
                        <td class="number highlight"><?php echo e(number_format($offrande['total_offrandes'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($offrande['nombre_transactions'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucune donnée d'offrandes disponible pour cette période.</div>
        <?php endif; ?>
    </div>

    <!-- Section Ratio Présence/Offrande -->
    <div class="section">
        <h2 class="section-title">4. Ratio Présence/Offrande</h2>

        <?php if(!empty($data['presence_offrande_ratio'])): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Participants Moyens</th>
                        <th class="number">Total Offrandes (FCFA)</th>
                        <th class="number">Ratio (FCFA/personne)</th>
                        <th class="number">Nb Cultes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['presence_offrande_ratio']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ratio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ratio['period']); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['avg_participants'])); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['total_offrandes'], 0, ',', ' ')); ?></td>
                        <td class="number highlight"><?php echo e(number_format($ratio['ratio_offrande_par_personne'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['nombre_cultes'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucune donnée de ratio disponible pour cette période.</div>
        <?php endif; ?>
    </div>

    <!-- Section FIMECO -->
    <?php if(!empty($data['fimeco_evolution'])): ?>
    <div class="section">
        <h2 class="section-title">5. Évolution des FIMECO</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Nb FIMECOs</th>
                        <th class="number">Cible Totale (FCFA)</th>
                        <th class="number">Collecte Totale (FCFA)</th>
                        <th class="number">Progression (%)</th>
                        <th class="number">Souscripteurs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['fimeco_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fimeco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($fimeco['period']); ?></td>
                        <td class="number"><?php echo e(number_format($fimeco['nombre_fimecos'])); ?></td>
                        <td class="number"><?php echo e(number_format($fimeco['cible_totale'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($fimeco['collecte_totale'], 0, ',', ' ')); ?></td>
                        <td class="number highlight"><?php echo e(number_format($fimeco['progression_moyenne'], 1)); ?>%</td>
                        <td class="number"><?php echo e(number_format($fimeco['souscripteurs_totaux'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Détails FIMECO -->
        <?php $__currentLoopData = $data['fimeco_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fimeco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(isset($fimeco['fimecos_details'])): ?>
                <h3 style="margin-top: 15px; color: #374151; font-size: 10px; font-weight: bold;">Détails des FIMECOs - <?php echo e($fimeco['period']); ?></h3>
                <?php $__currentLoopData = $fimeco['fimecos_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 3px; padding: 8px; margin-bottom: 8px;">
                    <h4 style="margin: 0 0 5px 0; color: #1f2937; font-size: 9px; font-weight: bold;"><?php echo e($detail['nom']); ?></h4>
                    <div style="display: flex; justify-content: space-between; font-size: 8px; margin-bottom: 3px;">
                        <span><strong>Cible:</strong> <?php echo e(number_format($detail['cible'], 0, ',', ' ')); ?> FCFA</span>
                        <span><strong>Collecté:</strong> <?php echo e(number_format($detail['montant_solde'], 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 8px;">
                        <span><strong>Progression:</strong> <?php echo e(number_format($detail['progression'], 1)); ?>%</span>
                        <span><strong>Souscripteurs:</strong> <?php echo e($detail['nombre_souscripteurs']); ?></span>
                    </div>
                    <div style="margin-top: 3px; font-size: 7px; color: #6b7280;">
                        <span><strong>Période:</strong> <?php echo e(\Carbon\Carbon::parse($detail['debut'])->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($detail['fin'])->format('d/m/Y')); ?></span>
                        <span style="margin-left: 15px;"><strong>Statut:</strong> <?php echo e(ucfirst($detail['statut'])); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <!-- Section Ratios et Souscripteurs FIMECO -->
    <?php if(!empty($data['souscripteur_fimeco_ratio'])): ?>
    <div class="section">
        <h2 class="section-title">6. Ratio Souscripteurs/Collecte FIMECO</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th class="number">Nb Souscripteurs</th>
                        <th class="number">Total Collecté (FCFA)</th>
                        <th class="number">Total Souscrit (FCFA)</th>
                        <th class="number">Ratio (FCFA/souscripteur)</th>
                        <th class="number">Taux Réalisation (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['souscripteur_fimeco_ratio']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ratio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ratio['period']); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['nombre_souscripteurs'])); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['total_collecte'], 0, ',', ' ')); ?></td>
                        <td class="number"><?php echo e(number_format($ratio['total_souscrit'], 0, ',', ' ')); ?></td>
                        <td class="number highlight"><?php echo e(number_format($ratio['ratio_collecte_par_souscripteur'], 0, ',', ' ')); ?></td>
                        <td class="number <?php echo e($ratio['taux_realisation'] >= 50 ? 'positive' : 'negative'); ?>"><?php echo e(number_format($ratio['taux_realisation'], 1)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Section Analyse et Tendances -->
    <div class="section page-break">
        <h2 class="section-title">7. Analyses et Tendances</h2>

        <div class="ratio-cards">
            <div class="ratio-card">
                <h3>Ratios Globaux</h3>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Ratio Présence/Offrande</th>
                                <th class="number">Ratio Souscripteur/Collecte</th>
                                <th class="number">Total Offrandes Période</th>
                                <th class="number">Total Collecte FIMECO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e(number_format($data['ratios']['presence_offrande_ratio'], 0, ',', ' ')); ?> FCFA/personne</td>
                                <td class="number"><?php echo e(number_format($data['ratios']['souscripteur_collecte_ratio'], 0, ',', ' ')); ?> FCFA/souscripteur</td>
                                <td class="number"><?php echo e(number_format($data['ratios']['total_offrandes'], 0, ',', ' ')); ?> FCFA</td>
                                <td class="number highlight"><?php echo e(number_format($data['ratios']['total_collecte_fimeco'], 0, ',', ' ')); ?> FCFA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



            </div>

            <?php if(isset($data['trends'])): ?>
            <div class="ratio-card">
                <h3>Évolution des Offrandes</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="number">Ratio Souscripteur/Collecte</th>
                                <th class="number">Période Actuelle</th>
                                <th class="number">Période Précédente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($data['trends']['offrandes_trend'] > 0 ? '+' : ''); ?><?php echo e($data['trends']['offrandes_trend']); ?>%</td>
                                <td class="number"><?php echo e(number_format($data['trends']['current_offrandes'], 0, ',', ' ')); ?> FCFA</td>
                                <td class="number"><?php echo e(number_format($data['trends']['previous_offrandes'], 0, ',', ' ')); ?> FCFA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <?php endif; ?>
        </div>

        <!-- Résumé Exécutif -->
        <div class="summary-box">
            <div class="summary-title">Résumé Exécutif</div>
            <div class="summary-text">
                <p style="margin: 0 0 6px 0;">
                    <strong>Membres:</strong> L'église compte actuellement <?php echo e(number_format($data['kpis']['total_membres'])); ?> membres avec <?php echo e(number_format($data['kpis']['nouveaux_membres'])); ?> nouveaux membres sur la période.
                </p>
                <p style="margin: 0 0 6px 0;">
                    <strong>Participation:</strong> La présence moyenne aux cultes est de <?php echo e(number_format($data['kpis']['avg_participants'])); ?> personnes sur <?php echo e(number_format($data['kpis']['nombre_cultes'])); ?> cultes organisés.
                </p>
                <p style="margin: 0 0 6px 0;">
                    <strong>Finances:</strong> Les offrandes totales s'élèvent à <?php echo e(number_format($data['kpis']['total_offrandes'], 0, ',', ' ')); ?> FCFA, soit <?php echo e(number_format($data['ratios']['presence_offrande_ratio'], 0, ',', ' ')); ?> FCFA par participant en moyenne.
                </p>
                <?php if($data['kpis']['fimeco_progression'] > 0): ?>
                <p style="margin: 0;">
                    <strong>FIMECO:</strong> Le projet "<?php echo e($data['kpis']['fimeco_nom']); ?>" affiche une progression de <?php echo e($data['kpis']['fimeco_progression']); ?>% avec <?php echo e(number_format($data['ratios']['total_souscripteurs'])); ?> souscripteurs.
                </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recommandations -->
        <div class="summary-box" style="background-color: #fefce8; border-color: #facc15;">
            <div class="summary-title" style="color: #a16207;">Recommandations</div>
            <div class="summary-text">
                <?php if(isset($data['trends']) && $data['trends']['offrandes_trend'] < 0): ?>
                <p style="margin: 0 0 6px 0;">• Considérer des actions pour améliorer la collecte des offrandes (tendance en baisse de <?php echo e(abs($data['trends']['offrandes_trend'])); ?>%).</p>
                <?php endif; ?>

                <?php if($data['kpis']['avg_participants'] > 0 && $data['ratios']['presence_offrande_ratio'] > 0): ?>
                <p style="margin: 0 0 6px 0;">• Maintenir l'engagement des fidèles avec un ratio de <?php echo e(number_format($data['ratios']['presence_offrande_ratio'], 0, ',', ' ')); ?> FCFA par participant.</p>
                <?php endif; ?>

                <?php if($data['kpis']['fimeco_progression'] > 0 && $data['kpis']['fimeco_progression'] < 50): ?>
                <p style="margin: 0 0 6px 0;">• Intensifier les efforts de collecte FIMECO (<?php echo e($data['kpis']['fimeco_progression']); ?>% de progression actuelle).</p>
                <?php endif; ?>

                <p style="margin: 0;">• Continuer le suivi régulier des indicateurs pour maintenir la croissance de l'église.</p>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                Généré automatiquement par le système de gestion d'église
            </div>
            <div>
                <?php echo e($data['metadata']['exported_at']); ?>

            </div>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $pageText = "Page " . $PAGE_NUM . " sur " . $PAGE_COUNT;
            $y = $pdf->get_height() - 50;
            $x = $pdf->get_width() - 100;
            $pdf->text($x, $y, $pageText, $font, $size);
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/dashboard/dashboard-pdf.blade.php ENDPATH**/ ?>