<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Dashboard Église</title>
</head>
<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    <!-- EN-TÊTE STRUCTURE -->
    <div style="background-color: #1e40af; color: white; padding: 15px; margin: -1cm -1cm 20px -1cm; border-bottom: 4px solid #f59e0b; overflow: hidden;">
        <div style="width: 100%;">
            <!-- PARTIE GAUCHE: Logo + Nom + Téléphones -->
            <div style="float: left; width: 48%;">
                <div style="float: left; width: 70px; margin-right: 10px;">
                    <?php if(!empty($AppParametres->logo)): ?>
                        <?php
                            try {
                                $logoPath = storage_path('app/public/' . $AppParametres->logo);
                                if (file_exists($logoPath)) {
                                    $imageData = base64_encode(file_get_contents($logoPath));
                                    $imageExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                                    $mimeTypes = [
                                        'jpg' => 'jpeg', 'jpeg' => 'jpeg', 'png' => 'png',
                                        'gif' => 'gif', 'svg' => 'svg+xml', 'webp' => 'webp'
                                    ];
                                    $mimeType = $mimeTypes[$imageExtension] ?? 'png';
                                    $logoBase64 = "data:image/{$mimeType};base64,{$imageData}";
                                } else {
                                    $logoBase64 = null;
                                }
                            } catch (\Exception $e) {
                                $logoBase64 = null;
                            }
                        ?>

                        <?php if(isset($logoBase64) && $logoBase64): ?>
                            <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px;">
                                <img src="<?php echo e($logoBase64); ?>" alt="Logo" style="max-width: 50px; max-height: 50px; vertical-align: middle;">
                            </div>
                        <?php else: ?>
                            <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                                <?php echo e(strtoupper(substr($AppParametres->nom_eglise, 0, 2))); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                            <?php echo e(strtoupper(substr($AppParametres->nom_eglise, 0, 2))); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div style="margin-left: 80px;">
                    <div style="font-size: 14px; font-weight: bold; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; color: white;">
                        <?php echo e(htmlspecialchars($AppParametres->nom_eglise)); ?>

                    </div>
                    <div style="font-size: 7px; line-height: 1.6; color: white;">
                        <?php if(!empty($AppParametres->telephone_1)): ?>
                            <div style="margin: 3px 0; word-wrap: break-word;"><strong>Tel 1:</strong> <?php echo e(htmlspecialchars($AppParametres->telephone_1)); ?></div>
                        <?php endif; ?>
                        <?php if(!empty($AppParametres->telephone_2)): ?>
                            <div style="margin: 3px 0; word-wrap: break-word;"><strong>Tel 2:</strong> <?php echo e(htmlspecialchars($AppParametres->telephone_2)); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PARTIE DROITE: Email + Adresse -->
            <div style="float: right; width: 48%; text-align: right;">
                <div style="font-size: 7px; line-height: 1.6; color: white;">
                    <?php if(!empty($AppParametres->email)): ?>
                        <div style="margin: 3px 0; word-wrap: break-word;"><strong>Email:</strong> <?php echo e(htmlspecialchars($AppParametres->email)); ?></div>
                    <?php endif; ?>
                    <?php if(!empty($AppParametres->adresse)): ?>
                        <div style="margin: 3px 0; word-wrap: break-word;">
                            <strong>Adresse:</strong>
                            <?php echo e(htmlspecialchars($AppParametres->adresse)); ?>

                            <?php if(!empty($AppParametres->code_postal)): ?>, <?php echo e(htmlspecialchars($AppParametres->code_postal)); ?><?php endif; ?>
                            <?php if(!empty($AppParametres->ville)): ?> <?php echo e(htmlspecialchars($AppParametres->ville)); ?><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($AppParametres->commune)): ?>
                        <div style="margin: 3px 0; word-wrap: break-word;"><?php echo e(htmlspecialchars($AppParametres->commune)); ?></div>
                    <?php endif; ?>
                    <?php if(!empty($AppParametres->pays)): ?>
                        <div style="margin: 3px 0; word-wrap: break-word;"><?php echo e(htmlspecialchars($AppParametres->pays)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- En-tête -->
    <div style="text-align: center; border-bottom: 3px solid #3b82f6; padding-bottom: 15px; margin-bottom: 20px;">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            <?php echo e($data['metadata']['church_name'] ?? 'Église - Tableau de Bord'); ?>

        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Rapport d'Activités - Période <?php echo e($data['metadata']['period_label']); ?>

        </p>
    </div>

    <!-- Métadonnées -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations du Rapport
        </h2>
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Du</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Au</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Exporté le</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Exporté par</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($data['metadata']['period_label']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($data['metadata']['start_date']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($data['metadata']['end_date']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($data['metadata']['exported_at']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($data['metadata']['exported_by']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section KPIs -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Indicateurs Clés de Performance (KPIs)
        </h2>

        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Total Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nouveaux Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Présence Moyenne</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nombre de Cultes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Total Offrandes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">FIMECO Progression</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;"><?php echo e(number_format($data['kpis']['total_membres'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; color: #059669; font-weight: bold; white-space: nowrap;">+<?php echo e(number_format($data['kpis']['nouveaux_membres'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;"><?php echo e(number_format($data['kpis']['avg_participants'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;"><?php echo e(number_format($data['kpis']['nombre_cultes'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;"><?php echo e(number_format($data['kpis']['total_offrandes'], 0, ',', ' ')); ?> FCFA</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;"><?php echo e($data['kpis']['fimeco_progression']); ?>%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Évolution des Membres -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            1. Évolution des Membres
        </h2>

        <?php if(!empty($data['members_evolution'])): ?>
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Total Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Actifs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Visiteurs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux Convertis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['members_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background-color: <?php echo e($index % 2 === 0 ? '#f9fafb' : 'white'); ?>;">
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($member['period']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($member['total_membres'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; color: #059669; font-weight: bold; white-space: nowrap;"><?php echo e(number_format($member['nouveaux_membres'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($member['membres_actifs'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($member['visiteurs'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($member['nouveaux_convertis'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée disponible pour cette période.
        </div>
        <?php endif; ?>
    </div>

    <!-- Section Présence aux Cultes -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            2. Présence aux Cultes
        </h2>

        <?php if(!empty($data['culte_attendance'])): ?>
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Participants Moyens</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Physiques</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">En Ligne</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux Visiteurs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nb Cultes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Taux Présence (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['culte_attendance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $culte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background-color: <?php echo e($index % 2 === 0 ? '#f9fafb' : 'white'); ?>;">
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($culte['period']); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['avg_participants'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['participants_physiques'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['participants_en_ligne'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['nouveaux_visiteurs'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['nombre_cultes'])); ?></td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($culte['taux_presence'], 1)); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée de culte disponible pour cette période.
        </div>
        <?php endif; ?>
    </div>

    <!-- Section Offrandes -->
    <div style="margin-bottom: 25px; page-break-before: always;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            3. Évolution des Offrandes
        </h2>

        <?php if(!empty($data['offrandes_evolution'])): ?>
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Dîmes (FCFA)</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Ordinaires</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Libres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Spéciales</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Missions</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Construction</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Total (FCFA)</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nb Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['offrandes_evolution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $offrande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background-color: <?php echo e($index % 2 === 0 ? '#f9fafb' : 'white'); ?>;">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;"><?php echo e($offrande['period']); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['dimes'], 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['offrandes_ordinaires'], 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['offrandes_libres'], 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['offrandes_speciales'], 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['offrandes_missions'] ?? 0, 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['offrandes_construction'] ?? 0, 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; background-color: #fef3c7; padding: 1px 3px; border-radius: 2px; white-space: nowrap;"><?php echo e(number_format($offrande['total_offrandes'], 0, ',', ' ')); ?></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;"><?php echo e(number_format($offrande['nombre_transactions'])); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée d'offrandes disponible pour cette période.
        </div>
        <?php endif; ?>
    </div>

    <!-- Résumé Exécutif et Recommandations -->
    <div style="margin-bottom: 25px; page-break-before: always;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Résumé Exécutif
        </h2>

        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 10px; margin: 10px 0;">
            <div style="color: #1e40af; font-size: 10px; font-weight: bold; margin-bottom: 6px;">Synthèse de la Période</div>
            <div style="font-size: 8px; line-height: 1.4; color: #1f2937;">
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
        <div style="background-color: #fefce8; border: 1px solid #facc15; border-radius: 4px; padding: 10px; margin: 10px 0;">
            <div style="color: #a16207; font-size: 10px; font-weight: bold; margin-bottom: 6px;">Recommandations</div>
            <div style="font-size: 8px; line-height: 1.4; color: #1f2937;">
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

    <!-- FOOTER -->
    <div style="background-color: #37393b; color: white; padding: 10px 15px; font-size: 7px; border-top: 3px solid #3b82f6; margin: 30px -1cm -1cm -1cm;">
        <div style="text-align: center; font-style: italic; color: #fbbf24; margin-bottom: 8px; font-size: 8px; line-height: 1.3; padding-bottom: 8px; border-bottom: 1px solid #4b5563;">
            <?php if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset)): ?>
                "<?php echo e(htmlspecialchars($AppParametres->verset_biblique)); ?>" - <?php echo e(htmlspecialchars($AppParametres->reference_verset)); ?>

            <?php else: ?>
                "Car Dieu a tant aimé le monde qu'il a donné son Fils unique..." - Jean 3:16
            <?php endif; ?>
        </div>
        <div style="text-align: center;">
            <div style="margin-bottom: 5px;">
                <?php if(!empty($AppParametres->facebook_url)): ?>
                    Facebook: <?php echo e(htmlspecialchars($AppParametres->facebook_url)); ?> |
                <?php endif; ?>
                <?php if(!empty($AppParametres->instagram_url)): ?>
                    Instagram: <?php echo e(htmlspecialchars($AppParametres->instagram_url)); ?> |
                <?php endif; ?>
                <?php if(!empty($AppParametres->youtube_url)): ?>
                    YouTube: <?php echo e(htmlspecialchars($AppParametres->youtube_url)); ?> |
                <?php endif; ?>
                <?php if(!empty($AppParametres->twitter_url)): ?>
                    Twitter: <?php echo e(htmlspecialchars($AppParametres->twitter_url)); ?>

                <?php endif; ?>
            </div>
            <div style="font-size: 7px; color: #9ca3af;">
                <?php if(!empty($AppParametres->website_url)): ?>
                    Site web: <?php echo e(htmlspecialchars($AppParametres->website_url)); ?> |
                <?php endif; ?>
                Généré le <?php echo e($data['metadata']['exported_at']); ?>

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