<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Culte - <?php echo e($culte->titre); ?></title>
    <style>
        @page {
            margin: 1cm;
            size: A4 portrait;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
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

        .section-title.info {
            background-color: #3b82f6;
        }

        .section-title.responsables {
            background-color: #dc2626;
        }

        .section-title.message {
            background-color: #f59e0b;
        }

        .section-title.statistiques {
            background-color: #7c3aed;
        }

        .section-title.financier {
            background-color: #059669;
        }

        .section-title.notes {
            background-color: #6366f1;
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

        .kpi-card.green .kpi-value {
            color: #059669;
        }

        .kpi-card.blue .kpi-value {
            color: #3b82f6;
        }

        .kpi-card.purple .kpi-value {
            color: #7c3aed;
        }

        .kpi-card.amber .kpi-value {
            color: #f59e0b;
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

        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-planifie {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-en-preparation {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-en-cours {
            background-color: #fed7aa;
            color: #9a3412;
        }

        .status-termine {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-annule {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-reporte {
            background-color: #ede9fe;
            color: #6b21a8;
        }

        .no-data {
            color: #6b7280;
            font-style: italic;
            text-align: center;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }

        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
        }

        .two-column {
            display: flex;
            gap: 15px;
        }

        .column {
            flex: 1;
        }

        .financial-highlight {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 4px;
            padding: 8px;
            margin: 5px 0;
            text-align: center;
        }

        .financial-highlight .amount {
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
        }

        .financial-highlight .label {
            font-size: 8px;
            color: #047857;
            margin-top: 2px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin: 3px 0;
        }

        .progress-fill {
            height: 100%;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .progress-fill.green {
            background-color: #059669;
        }

        .progress-fill.purple {
            background-color: #7c3aed;
        }

        .message-content {
            background-color: #fefce8;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin: 8px 0;
            font-size: 9px;
            line-height: 1.4;
        }

        .bible-verse {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 8px;
            margin: 8px 0;
            font-style: italic;
            text-align: center;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>ÉGLISE - RAPPORT DE CULTE</h1>
        <p class="subtitle"><?php echo e(htmlspecialchars($culte->titre)); ?> - <?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('l d F Y')); ?></p>
    </div>

    <!-- Informations générales -->
    <div class="section">
        <h2 class="section-title info">Informations Générales</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Horaires</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php echo e(htmlspecialchars($culte->titre)); ?></strong></td>
                        <td><?php echo e(htmlspecialchars($culte->type_culte_libelle)); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->categorie_libelle)); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y')); ?></td>
                        <td>
                            <?php echo e(\Carbon\Carbon::parse($culte->heure_debut)->format('H:i')); ?>

                            <?php if($culte->heure_fin): ?>
                                - <?php echo e(\Carbon\Carbon::parse($culte->heure_fin)->format('H:i')); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e(htmlspecialchars($culte->lieu)); ?></td>
                        <td class="center">
                            <span class="status-badge status-<?php echo e($culte->statut); ?>">
                                <?php echo e(htmlspecialchars($culte->statut_libelle)); ?>

                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if($culte->description): ?>
        <div class="summary-box">
            <strong>Description:</strong>
            <div style="margin-top: 5px; font-size: 9px;"><?php echo e(nl2br(htmlspecialchars(strip_tags($culte->description)))); ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Responsables et Intervenants -->
    <div class="section">
        <h2 class="section-title responsables">Responsables et Intervenants</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rôle</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($culte->pasteurPrincipal): ?>
                    <tr>
                        <td><strong>Pasteur Principal</strong></td>
                        <td><?php echo e(htmlspecialchars($culte->pasteurPrincipal->nom_complet)); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->pasteurPrincipal->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->pasteurPrincipal->telephone_1 ?: 'Non renseigné')); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if($culte->predicateur && $culte->predicateur->id !== $culte->pasteurPrincipal?->id): ?>
                    <tr>
                        <td><strong>Prédicateur</strong></td>
                        <td><?php echo e(htmlspecialchars($culte->predicateur->nom_complet)); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->predicateur->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->predicateur->telephone_1 ?: 'Non renseigné')); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if($culte->responsableCulte): ?>
                    <tr>
                        <td><strong>Responsable Culte</strong></td>
                        <td><?php echo e(htmlspecialchars($culte->responsableCulte->nom_complet)); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->responsableCulte->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->responsableCulte->telephone_1 ?: 'Non renseigné')); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if($culte->dirigeantLouange): ?>
                    <tr>
                        <td><strong>Dirigeant Louange</strong></td>
                        <td><?php echo e(htmlspecialchars($culte->dirigeantLouange->nom_complet)); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->dirigeantLouange->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($culte->dirigeantLouange->telephone_1 ?: 'Non renseigné')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Message et Prédication -->
    <?php if($culte->titre_message || $culte->passage_biblique || $culte->resume_message): ?>
    <div class="section">
        <h2 class="section-title message">Message et Prédication</h2>

        <?php if($culte->titre_message): ?>
        <div class="summary-box">
            <strong>Titre du message:</strong> <?php echo e(htmlspecialchars($culte->titre_message)); ?>

        </div>
        <?php endif; ?>

        <?php if($culte->passage_biblique): ?>
        <div class="bible-verse">
            <strong>Passage biblique:</strong> <?php echo e(htmlspecialchars($culte->passage_biblique)); ?>

        </div>
        <?php endif; ?>

        <?php if($culte->resume_message): ?>
        <div class="message-content">
            <strong>Résumé du message:</strong><br>
            <?php echo e(nl2br(htmlspecialchars(strip_tags($culte->resume_message)))); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Statistiques de Participation -->
    <?php if($culte->nombre_participants || $culte->statut === 'termine'): ?>
    <div class="section">
        <h2 class="section-title statistiques">Statistiques de Participation</h2>

        <div class="kpis-grid">
            <?php if($culte->nombre_participants): ?>
            <div class="kpi-card blue">
                <div class="kpi-label">TOTAL PARTICIPANTS</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_participants)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_adultes): ?>
            <div class="kpi-card green">
                <div class="kpi-label">ADULTES</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_adultes)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_jeunes): ?>
            <div class="kpi-card purple">
                <div class="kpi-label">JEUNES</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_jeunes)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_enfants): ?>
            <div class="kpi-card amber">
                <div class="kpi-label">ENFANTS</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_enfants)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_nouveaux): ?>
            <div class="kpi-card blue">
                <div class="kpi-label">NOUVEAUX</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_nouveaux)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_conversions): ?>
            <div class="kpi-card green">
                <div class="kpi-label">CONVERSIONS</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_conversions)); ?></div>
            </div>
            <?php endif; ?>

            <?php if($culte->nombre_baptemes): ?>
            <div class="kpi-card purple">
                <div class="kpi-label">BAPTÊMES</div>
                <div class="kpi-value"><?php echo e(number_format($culte->nombre_baptemes)); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if($culte->heure_debut_reelle || $culte->heure_fin_reelle): ?>
        <div class="summary-box">
            <strong>Horaires réels:</strong>
            <?php if($culte->heure_debut_reelle): ?>
                Début: <?php echo e(\Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i')); ?>

            <?php endif; ?>
            <?php if($culte->heure_fin_reelle): ?>
                - Fin: <?php echo e(\Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i')); ?>

            <?php endif; ?>
            <?php if($culte->duree_totale): ?>
                (Durée: <?php echo e($culte->duree_totale); ?>)
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Statistiques Financières -->
    <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
    <div class="section page-break">
        <h2 class="section-title financier">Statistiques Financières</h2>

        <!-- Métriques principales -->
        <div class="kpis-grid">
            <div class="kpi-card green">
                <div class="kpi-label">MONTANT TOTAL</div>
                <div class="kpi-value"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?> FCFA</div>
            </div>

            <div class="kpi-card blue">
                <div class="kpi-label">TRANSACTIONS</div>
                <div class="kpi-value"><?php echo e($fondsStatistiques['total_transactions']); ?></div>
            </div>

            <div class="kpi-card purple">
                <div class="kpi-label">DONATEURS</div>
                <div class="kpi-value"><?php echo e($fondsStatistiques['donateurs_uniques']); ?></div>
            </div>

            <div class="kpi-card amber">
                <div class="kpi-label">MOYENNE/TRANSACTION</div>
                <div class="kpi-value"><?php echo e(number_format($metriques['transaction_moyenne'], 0)); ?> FCFA</div>
            </div>
        </div>

        <?php if($culte->nombre_participants > 0): ?>
        <!-- Ratios par participant -->
        <div class="summary-box">
            <strong>Ratios par participant:</strong>
            <div style="margin-top: 8px;">
                <div style="margin: 3px 0;">
                    Offrande par participant: <strong><?php echo e(number_format($metriques['offrande_par_participant'], 0)); ?> FCFA</strong>
                </div>
                <div style="margin: 3px 0;">
                    Dîme par participant: <strong><?php echo e(number_format($metriques['dime_par_participant'], 0)); ?> FCFA</strong>
                </div>
                <div style="margin: 3px 0;">
                    Taux de participation financière: <strong><?php echo e($metriques['taux_participation_financiere']); ?>%</strong>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Répartition par type -->
        <?php if(count($fondsStatistiques['par_type']) > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Type de Transaction</th>
                        <th class="center">Nombre</th>
                        <th class="number">Montant (FCFA)</th>
                        <th class="center">Pourcentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $fondsStatistiques['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(htmlspecialchars(ucfirst(str_replace('_', ' ', $type)))); ?></td>
                        <td class="center"><?php echo e($data['nombre']); ?></td>
                        <td class="number"><strong><?php echo e(number_format($data['montant'], 0)); ?></strong></td>
                        <td class="center"><?php echo e($data['pourcentage']); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Top donateurs -->
        <?php if(count($fondsStatistiques['top_donateurs']) > 0): ?>
        <div style="margin-top: 15px;">
            <strong style="font-size: 10px;">Top 5 des donateurs:</strong>
            <div class="table-container" style="margin-top: 5px;">
                <table>
                    <thead>
                        <tr>
                            <th class="center">Rang</th>
                            <th>Donateur</th>
                            <th class="center">Nb Dons</th>
                            <th class="number">Montant Total (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $fondsStatistiques['top_donateurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $donateur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="center"><strong><?php echo e($index + 1); ?></strong></td>
                            <td><?php echo e(htmlspecialchars($donateur['donateur'])); ?></td>
                            <td class="center"><?php echo e($donateur['nombre_dons']); ?></td>
                            <td class="number"><strong><?php echo e(number_format($donateur['montant_total'], 0)); ?></strong></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Comparaison avec moyenne -->
        <?php if(isset($metriques['comparaison']) && $metriques['comparaison']['moyenne_type_culte'] > 0): ?>
        <div class="financial-highlight">
            <div style="font-size: 10px; margin-bottom: 5px;">
                <strong>Comparaison avec la moyenne des <?php echo e($culte->type_culte_libelle); ?>s:</strong>
            </div>
            <div style="display: flex; gap: 15px; align-items: center; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 8px; color: #6b7280;">Ce culte</div>
                    <div class="amount"><?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?> FCFA</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 8px; color: #6b7280;">Moyenne</div>
                    <div class="amount"><?php echo e(number_format($metriques['comparaison']['moyenne_type_culte'], 0)); ?> FCFA</div>
                </div>
                <div style="text-align: center;">
                    <?php
                        $ecart = $metriques['comparaison']['ecart_pourcentage'];
                        $couleur = $ecart > 0 ? '#059669' : '#dc2626';
                        $signe = $ecart > 0 ? '+' : '';
                    ?>
                    <div style="font-size: 8px; color: #6b7280;">Écart</div>
                    <div style="font-size: 12px; font-weight: bold; color: <?php echo e($couleur); ?>;"><?php echo e($signe); ?><?php echo e($ecart); ?>%</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Évaluations -->
    <?php if($culte->note_globale || $culte->note_louange || $culte->note_message || $culte->note_organisation): ?>
    <div class="section">
        <h2 class="section-title">Évaluations</h2>

        <div class="kpis-grid">
            <?php if($culte->note_globale): ?>
            <div class="kpi-card amber">
                <div class="kpi-label">NOTE GLOBALE</div>
                <div class="kpi-value"><?php echo e($culte->note_globale); ?>/10</div>
            </div>
            <?php endif; ?>

            <?php if($culte->note_louange): ?>
            <div class="kpi-card purple">
                <div class="kpi-label">LOUANGE</div>
                <div class="kpi-value"><?php echo e($culte->note_louange); ?>/10</div>
            </div>
            <?php endif; ?>

            <?php if($culte->note_message): ?>
            <div class="kpi-card blue">
                <div class="kpi-label">MESSAGE</div>
                <div class="kpi-value"><?php echo e($culte->note_message); ?>/10</div>
            </div>
            <?php endif; ?>

            <?php if($culte->note_organisation): ?>
            <div class="kpi-card green">
                <div class="kpi-label">ORGANISATION</div>
                <div class="kpi-value"><?php echo e($culte->note_organisation); ?>/10</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Notes et Commentaires -->
    <?php if($culte->notes_pasteur || $culte->notes_organisateur || $culte->points_forts || $culte->points_amelioration): ?>
    <div class="section">
        <h2 class="section-title notes">Notes et Commentaires</h2>

        <?php if($culte->notes_pasteur): ?>
        <div class="summary-box">
            <strong>Notes du pasteur:</strong>
            <div style="margin-top: 5px; font-size: 9px;"><?php echo e(nl2br(htmlspecialchars(strip_tags($culte->notes_pasteur)))); ?></div>
        </div>
        <?php endif; ?>

        <?php if($culte->notes_organisateur): ?>
        <div class="summary-box">
            <strong>Notes de l'organisateur:</strong>
            <div style="margin-top: 5px; font-size: 9px;"><?php echo e(nl2br(htmlspecialchars(strip_tags($culte->notes_organisateur)))); ?></div>
        </div>
        <?php endif; ?>

        <?php if($culte->points_forts || $culte->points_amelioration): ?>
        <div class="two-column">
            <?php if($culte->points_forts): ?>
            <div class="column">
                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; padding: 8px;">
                    <strong style="color: #065f46;">Points forts:</strong>
                    <div style="margin-top: 5px; font-size: 9px; color: #047857;"><?php echo e(nl2br(htmlspecialchars(strip_tags($culte->points_forts)))); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($culte->points_amelioration): ?>
            <div class="column">
                <div style="background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 4px; padding: 8px;">
                    <strong style="color: #92400e;">Points d'amélioration:</strong>
                    <div style="margin-top: 5px; font-size: 9px; color: #b45309;"><?php echo e(nl2br(htmlspecialchars(strip_tags($culte->points_amelioration)))); ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Résumé du culte -->
    <div class="section">
        <h2 class="section-title">Résumé du Culte</h2>
        <div class="summary-box">
            <div style="font-size: 9px; line-height: 1.4;">
                <p style="margin: 0 0 6px 0;">
                    <strong>Vue d'ensemble:</strong> Le culte "<?php echo e($culte->titre); ?>" s'est déroulé le <?php echo e(\Carbon\Carbon::parse($culte->date_culte)->format('l d F Y')); ?> et avait pour statut "<?php echo e($culte->statut_libelle); ?>".
                </p>

                <?php if($culte->nombre_participants): ?>
                <p style="margin: 0 0 6px 0;">
                    <strong>Participation:</strong> <?php echo e(number_format($culte->nombre_participants)); ?> personne(s) ont participé à ce culte
                    <?php if($culte->nombre_adultes || $culte->nombre_jeunes || $culte->nombre_enfants): ?>
                        , répartis comme suit :
                        <?php if($culte->nombre_adultes): ?> <?php echo e($culte->nombre_adultes); ?> adulte(s)<?php endif; ?>
                        <?php if($culte->nombre_jeunes): ?><?php if($culte->nombre_adultes): ?>, <?php endif; ?> <?php echo e($culte->nombre_jeunes); ?> jeune(s)<?php endif; ?>
                        <?php if($culte->nombre_enfants): ?><?php if($culte->nombre_adultes || $culte->nombre_jeunes): ?>, <?php endif; ?> <?php echo e($culte->nombre_enfants); ?> enfant(s)<?php endif; ?>
                    <?php endif; ?>
                    .
                </p>
                <?php endif; ?>

                <?php if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0): ?>
                <p style="margin: 0 0 6px 0;">
                    <strong>Finances:</strong> Un total de <?php echo e(number_format($fondsStatistiques['montant_total'], 0)); ?> FCFA a été collecté lors de ce culte, réparti sur <?php echo e($fondsStatistiques['total_transactions']); ?> transaction(s) de <?php echo e($fondsStatistiques['donateurs_uniques']); ?> donateur(s).
                </p>
                <?php endif; ?>

                <?php if($culte->pasteurPrincipal): ?>
                <p style="margin: 0 0 6px 0;">
                    <strong>Responsabilité:</strong> <?php echo e(htmlspecialchars($culte->pasteurPrincipal->nom_complet)); ?> était le pasteur principal de ce culte.
                </p>
                <?php endif; ?>

                <p style="margin: 0;">
                    <strong>Enregistrement:</strong> Ce rapport a été généré le <?php echo e($dateGeneration); ?> et reflète l'état du culte au moment de l'extraction.
                </p>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>Généré automatiquement par le système de gestion d'église</div>
            <div><?php echo e($dateGeneration); ?></div>
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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/cultes/culte-pdf.blade.php ENDPATH**/ ?>