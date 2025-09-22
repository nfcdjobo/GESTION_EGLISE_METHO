<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Classe - <?php echo e($classe->nom); ?></title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
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

        .section-title.responsables {
            background-color: #dc2626;
        }

        .section-title.membres {
            background-color: #7c3aed;
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

        .superieur-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-actif {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactif {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-visiteur {
            background-color: #fef3c7;
            color: #92400e;
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
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>ÉGLISE - GESTION DES CLASSES</h1>
        <p class="subtitle">Détails de la classe : <?php echo e(htmlspecialchars($classe->nom)); ?></p>
    </div>

    <!-- Métadonnées de la classe -->
    <div class="section">
        <h2 class="section-title">Informations de la Classe</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Tranche d'âge</th>
                        <th>Âge Min/Max</th>
                        <th>Total Membres</th>
                        <th>Responsables</th>
                        <th>Date création</th>
                        <th>Document généré</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($classe->nom); ?></td>
                        <td><?php echo e($classe->tranche_age ?: 'Non spécifiée'); ?></td>
                        <td><?php echo e($classe->age_minimum ?: 'N/A'); ?> - <?php echo e($classe->age_maximum ?: 'N/A'); ?> ans</td>
                        <td class="center highlight"><?php echo e(number_format($classe->nombre_inscrits)); ?></td>
                        <td class="center"><?php echo e($responsables->count()); ?></td>
                        <td><?php echo e($classe->created_at->format('d/m/Y')); ?></td>
                        <td><?php echo e($dateGeneration); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- KPIs de la classe -->
    <div class="section">
        <h2 class="section-title">Indicateurs de Performance</h2>
        <div class="kpis-grid">
            <div class="kpi-card">
                <div class="kpi-label">TOTAL MEMBRES</div>
                <div class="kpi-value"><?php echo e(number_format($stats['total_membres'])); ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">RESPONSABLES</div>
                <div class="kpi-value"><?php echo e($stats['total_responsables']); ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">MEMBRES SIMPLES</div>
                <div class="kpi-value"><?php echo e($stats['membres_simples']); ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">ÂGES COUVERTS</div>
                <div class="kpi-value"><?php echo e($stats['ages_couverts']); ?></div>
            </div>
        </div>
    </div>

    <!-- Description si disponible -->
    <?php if($classe->description): ?>
    <div class="section">
        <h2 class="section-title">Description</h2>
        <div class="summary-box">
            <div style="font-size: 9px; line-height: 1.4;"><?php echo e(nl2br(htmlspecialchars($classe->description))); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Programme si disponible -->
    <?php if($classe->programme && count($classe->programme) > 0): ?>
    <div class="section">
        <h2 class="section-title">Programme de la Classe</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%">N°</th>
                        <th style="width: 90%">Élément du Programme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $classe->programme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="center"><?php echo e($index + 1); ?></td>
                        <td><?php echo e(htmlspecialchars($element)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Responsables -->
    <div class="section">
        <h2 class="section-title responsables">Responsables de la Classe</h2>

        <?php if($responsables->count() > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%">Nom Complet</th>
                        <th style="width: 15%">Responsabilité</th>
                        <th style="width: 8%">Supérieur</th>
                        <th style="width: 22%">Email</th>
                        <th style="width: 12%">Téléphone</th>
                        <th style="width: 12%">Téléphone 2</th>
                        <th style="width: 11%">Ville</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $responsables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $responsableData = collect($classe->responsables)->firstWhere('id', $responsable->id);
                        $isSuperieur = $responsableData['superieur'] ?? false;
                    ?>
                    <tr>
                        <td><strong><?php echo e(htmlspecialchars($responsable->nom_complet)); ?></strong></td>
                        <td><?php echo e(htmlspecialchars(ucfirst($responsableData['responsabilite'] ?? ''))); ?></td>
                        <td class="center">
                            <?php if($isSuperieur): ?>
                                <span class="superieur-badge">OUI</span>
                            <?php else: ?>
                                Non
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(htmlspecialchars($responsable->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($responsable->telephone_1 ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($responsable->telephone_2 ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($responsable->ville ?: 'Non renseignée')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucun responsable assigné à cette classe</div>
        <?php endif; ?>
    </div>

    <!-- Membres -->
    <div class="section page-break">
        <h2 class="section-title membres">Membres de la Classe</h2>

        <?php if($membres->count() > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 18%">Nom Complet</th>
                        <th style="width: 10%">Statut</th>
                        <th style="width: 20%">Email</th>
                        <th style="width: 12%">Téléphone 1</th>
                        <th style="width: 12%">Téléphone 2</th>
                        <th style="width: 15%">Adresse/Ville</th>
                        <th style="width: 8%">Âge</th>
                        <th style="width: 5%">Sexe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $membres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $age = $membre->date_naissance ? $membre->date_naissance->diffInYears(now()) . ' ans' : 'N/A';
                        $statusClass = 'status-' . ($membre->statut_membre ?: 'visiteur');
                        $adresse = $membre->ville ?: ($membre->adresse_ligne_1 ?: 'Non renseignée');
                    ?>
                    <tr>
                        <td><strong><?php echo e(htmlspecialchars($membre->nom_complet)); ?></strong></td>
                        <td class="center">
                            <span class="status-badge <?php echo e($statusClass); ?>">
                                <?php echo e(htmlspecialchars(ucfirst($membre->statut_membre ?: 'visiteur'))); ?>

                            </span>
                        </td>
                        <td><?php echo e(htmlspecialchars($membre->email ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($membre->telephone_1 ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($membre->telephone_2 ?: 'Non renseigné')); ?></td>
                        <td><?php echo e(htmlspecialchars($adresse)); ?></td>
                        <td class="center"><?php echo e($age); ?></td>
                        <td class="center"><?php echo e(htmlspecialchars(ucfirst($membre->sexe ?? 'N/A'))); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">Aucun membre inscrit dans cette classe</div>
        <?php endif; ?>
    </div>

    <!-- Résumé de la classe -->
    <div class="section">
        <h2 class="section-title">Résumé de la Classe</h2>
        <div class="summary-box">
            <div style="font-size: 9px; line-height: 1.4;">
                <p style="margin: 0 0 6px 0;">
                    <strong>Composition:</strong> La classe "<?php echo e($classe->nom); ?>" compte <?php echo e(number_format($classe->nombre_inscrits)); ?> membre(s) au total, dont <?php echo e($responsables->count()); ?> responsable(s) et <?php echo e(($membres->count() - $responsables->count())); ?> membre(s) simple(s).
                </p>

                <?php if($classe->tranche_age): ?>
                <p style="margin: 0 0 6px 0;">
                    <strong>Tranche d'âge:</strong> Cette classe est destinée à la tranche d'âge "<?php echo e(htmlspecialchars($classe->tranche_age)); ?>"
                    <?php if($classe->age_minimum || $classe->age_maximum): ?>
                        (âges de <?php echo e($classe->age_minimum ?: 'tout âge'); ?> à <?php echo e($classe->age_maximum ?: 'tout âge'); ?> ans)
                    <?php endif; ?>
                    .
                </p>
                <?php endif; ?>

                <?php
                    $superieur = $responsables->where('superieur', true)->first();
                ?>
                <?php if($superieur): ?>
                <p style="margin: 0 0 6px 0;">
                    <strong>Responsable principal:</strong> <?php echo e(htmlspecialchars($superieur->nom_complet)); ?> assure la supervision générale de la classe.
                </p>
                <?php endif; ?>

                <p style="margin: 0;">
                    <strong>Création:</strong> Cette classe a été créée le <?php echo e($classe->created_at->format('d/m/Y')); ?> et est active depuis <?php echo e($classe->created_at->diffInDays(now())); ?> jour(s).
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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/classes/classe-pdf.blade.php ENDPATH**/ ?>