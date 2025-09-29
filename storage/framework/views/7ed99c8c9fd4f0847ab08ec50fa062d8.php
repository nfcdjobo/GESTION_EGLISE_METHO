<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport FIMECO - <?php echo e($rapport['informations_generales']['nom'] ?? 'Rapport Global'); ?></title>
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

    <!-- TITRE DU RAPPORT -->
    <div style="text-align: center; padding: 15px 0; margin: 20px 0; border-bottom: 3px solid #3b82f6;">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            RAPPORT FIMECO - FINANCEMENT ET MOBILISATION COLLECTIVE
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            <?php if(isset($rapport['informations_generales']['nom'])): ?>
                <?php echo e(htmlspecialchars($rapport['informations_generales']['nom'])); ?>

            <?php else: ?>
                Rapport Global FIMECOs
            <?php endif; ?>
            - Généré le <?php echo e($rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s')); ?>

        </p>
    </div>

    <?php if(isset($rapport['informations_generales'])): ?>
        <!-- INFORMATIONS GÉNÉRALES -->
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #3b82f6; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Informations Générales
            </h2>
            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Nom du FIMECO</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Responsable</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Date création</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                <strong><?php echo e(htmlspecialchars($rapport['informations_generales']['nom'] ?? 'N/A')); ?></strong>
                            </td>
                            <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                <?php echo e(htmlspecialchars($rapport['informations_generales']['responsable'] ?? 'N/A')); ?>

                            </td>
                            <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                <?php echo e(htmlspecialchars($rapport['informations_generales']['periode'] ?? 'N/A')); ?>

                            </td>
                            <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                <?php echo e(htmlspecialchars($rapport['informations_generales']['date_creation'] ?? 'N/A')); ?>

                            </td>
                            <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                <?php
                                    $isActive = ($rapport['informations_generales']['statut'] ?? '') === 'active';
                                    $bgColor = $isActive ? '#d1fae5' : '#fee2e2';
                                    $textColor = $isActive ? '#065f46' : '#991b1b';
                                ?>
                                <span style="padding: 3px 8px; border-radius: 12px; font-size: 8px; font-weight: bold; text-transform: uppercase; background-color: <?php echo e($bgColor); ?>; color: <?php echo e($textColor); ?>;">
                                    <?php echo e(ucfirst($rapport['informations_generales']['statut'] ?? 'N/A')); ?>

                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if(isset($rapport['informations_generales']['description']) && $rapport['informations_generales']['description']): ?>
            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 10px; margin: 10px 0;">
                <strong>Description:</strong>
                <div style="margin-top: 5px; font-size: 9px;"><?php echo e(nl2br(htmlspecialchars(strip_tags($rapport['informations_generales']['description'])))); ?></div>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($rapport['objectifs_et_resultats'])): ?>
        <!-- OBJECTIFS ET RÉSULTATS -->
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Objectifs et Résultats
            </h2>

            <?php
                $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
                $progressColor = $progression >= 75 ? '#059669' : ($progression >= 50 ? '#f59e0b' : '#dc2626');
                $progressWidth = min($progression, 100);
            ?>
            <div style="width: 100%; margin: 12px 0; position: relative;">
                <div style="width: 100%; height: 24px; background-color: #e5e7eb; border-radius: 10px; border: 1px solid #d1d5db;">
                    <div style="height: 100%; background-color: <?php echo e($progressColor); ?>; border-radius: 10px; width: <?php echo e($progressWidth); ?>%;"></div>
                </div>
                <div style="position: absolute; top: 3px; left: 0; width: 100%; text-align: center;">
                    <span style="color: <?php echo e($progressWidth > 15 ? 'white' : '#1f2937'); ?>; font-weight: bold; font-size: 11px; text-shadow: <?php echo e($progressWidth > 15 ? '1px 1px 2px rgba(0,0,0,0.8)' : 'none'); ?>;">
                        <?php echo e(number_format($progression, 1)); ?>%
                    </span>
                </div>
            </div>

            <div style="width: 100%; margin-bottom: 15px;">
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">OBJECTIF</div>
                    <div style="font-size: 12px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($rapport['objectifs_et_resultats']['cible'] ?? 0, 0)); ?> FCFA</div>
                </div>
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">COLLECTÉ</div>
                    <div style="font-size: 12px; font-weight: bold; color: #059669;"><?php echo e(number_format($rapport['objectifs_et_resultats']['montant_solde'] ?? 0, 0)); ?> FCFA</div>
                </div>
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">RESTE</div>
                    <div style="font-size: 12px; font-weight: bold; color: #f59e0b;"><?php echo e(number_format($rapport['objectifs_et_resultats']['reste'] ?? 0, 0)); ?> FCFA</div>
                </div>
                <?php if(($rapport['objectifs_et_resultats']['montant_supplementaire'] ?? 0) > 0): ?>
                <div style="float: left; width: 23%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">BONUS</div>
                    <div style="font-size: 12px; font-weight: bold; color: #7c3aed;">+<?php echo e(number_format($rapport['objectifs_et_resultats']['montant_supplementaire'], 0)); ?> FCFA</div>
                </div>
                <?php endif; ?>
                <div style="clear: both;"></div>
            </div>

            <?php if($progression >= 100): ?>
                <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                    <strong>✓ Objectif Atteint !</strong> <?php echo e(number_format($progression, 1)); ?>% de réussite.
                </div>
            <?php elseif($progression >= 75): ?>
                <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #fefce8; border: 1px solid #fde047; color: #92400e;">
                    <strong>⚠ Presque Atteint</strong> <?php echo e(number_format($progression, 1)); ?>% - Il reste <?php echo e(number_format($rapport['objectifs_et_resultats']['reste'], 0)); ?> FCFA.
                </div>
            <?php elseif($progression < 25): ?>
                <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;">
                    <strong>⚠ Progression Faible</strong> Seulement <?php echo e(number_format($progression, 1)); ?>% - Actions urgentes nécessaires.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($rapport['statistiques_souscriptions'])): ?>
        <!-- STATISTIQUES DES SOUSCRIPTIONS -->
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Statistiques des Souscriptions
            </h2>

           



            <div style="width: 100%; margin-bottom: 15px;">
                <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TOTAL</div>
                    <div style="font-size: 12px; font-weight: bold; color: #3b82f6;"><?php echo e($rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0); ?></div>
                </div>
                <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">ACTIVES</div>
                    <div style="font-size: 12px; font-weight: bold; color: #059669;"><?php echo e($rapport['statistiques_souscriptions']['nb_souscriptions_actives'] ?? 0); ?></div>
                </div>
                <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">COMPLÈTES</div>
                    <div style="font-size: 12px; font-weight: bold; color: #7c3aed;"><?php echo e($rapport['statistiques_souscriptions']['nb_souscriptions_completes'] ?? 0); ?></div>
                </div>
                <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PARTIELLES</div>
                    <div style="font-size: 12px; font-weight: bold; color: #f59e0b;"><?php echo e($rapport['statistiques_souscriptions']['nb_souscriptions_partielles'] ?? 0); ?></div>
                </div>
                <div style="clear: both;"></div>
            </div>






            <?php if(($rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0) > 0): ?>
            <div style="width: 100%; margin-bottom: 15px;">
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">EN RETARD</div>
                    <div style="font-size: 12px; font-weight: bold; color: #dc2626;"><?php echo e($rapport['statistiques_souscriptions']['nb_souscriptions_en_retard']); ?></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php endif; ?>

            <div style="width: 100%;">
                <div style="float: left; width: 48%; margin-right: 2%;">
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: bold; color: #374151; font-size: 8px;">Montant souscrit:</span>
                        <span style="color: #6b7280; font-size: 8px;"><?php echo e(number_format($rapport['statistiques_souscriptions']['montant_total_souscrit'] ?? 0, 0)); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="font-weight: bold; color: #374151; font-size: 8px;">Montant payé:</span>
                        <span style="color: #6b7280; font-size: 8px;"><?php echo e(number_format($rapport['statistiques_souscriptions']['montant_total_paye'] ?? 0, 0)); ?></span>
                    </div>
                </div>
                <div style="float: right; width: 48%;">
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: bold; color: #374151; font-size: 8px;">Progression moy.:</span>
                        <span style="color: #6b7280; font-size: 8px;"><?php echo e(number_format($rapport['statistiques_souscriptions']['progression_moyenne_souscriptions'] ?? 0, 1)); ?>%</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="font-weight: bold; color: #374151; font-size: 8px;">Taux réussite:</span>
                        <span style="color: #6b7280; font-size: 8px;"><?php echo e(number_format($rapport['analyses']['taux_reussite'] ?? 0, 1)); ?>%</span>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($rapport['analyses'])): ?>
        <!-- ANALYSES ET INDICATEURS -->
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #f59e0b; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Analyses et Indicateurs
            </h2>

            <div style="width: 100%; margin-bottom: 15px;">
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TAUX RÉUSSITE</div>
                    <div style="font-size: 12px; font-weight: bold; color: #059669;"><?php echo e(number_format($rapport['analyses']['taux_reussite'] ?? 0, 1)); ?>%</div>
                </div>
                <div style="float: left; width: 23%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">DURÉE MOY.</div>
                    <div style="font-size: 12px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($rapport['analyses']['duree_moyenne_paiement'] ?? 0, 0)); ?>j</div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <?php if(isset($rapport['analyses']['repartition_types_paiement']) && count($rapport['analyses']['repartition_types_paiement']) > 0): ?>
                <div style="margin-bottom: 15px; overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                        <thead>
                            <tr>
                                <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Type Paiement</th>
                                <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nombre</th>
                                <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Montant</th>
                                <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalMontant = array_sum(array_column($rapport['analyses']['repartition_types_paiement'], 'total'));
                            ?>
                            <?php $__currentLoopData = $rapport['analyses']['repartition_types_paiement']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="background-color: <?php echo e($loop->even ? '#f9fafb' : 'white'); ?>;">
                                    <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                        <strong><?php echo e($data['libelle'] ?? ucfirst($type)); ?></strong>
                                    </td>
                                    <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                        <?php echo e($data['count'] ?? 0); ?>

                                    </td>
                                    <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                        <?php echo e(number_format($data['total'] ?? 0, 0)); ?>

                                    </td>
                                    <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                        <?php echo e($totalMontant > 0 ? number_format(($data['total'] ?? 0) / $totalMontant * 100, 1) : 0); ?>%
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($rapport['souscriptions_detail']) && count($rapport['souscriptions_detail']) > 0): ?>
        <!-- DÉTAIL DES SOUSCRIPTIONS -->
        <div style="margin-bottom: 25px; page-break-before: always;">
            <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Détail des Souscriptions
            </h2>

            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Souscripteur</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Souscrit</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Payé</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Reste</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Progression</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rapport['souscriptions_detail']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $souscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background-color: <?php echo e($loop->even ? '#f9fafb' : 'white'); ?>;">
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                    <strong><?php echo e(htmlspecialchars($souscription['souscripteur'] ?? 'N/A')); ?></strong>
                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    <?php echo e(number_format($souscription['montant_souscrit'] ?? 0, 0)); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    <?php echo e(number_format($souscription['montant_paye'] ?? 0, 0)); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    <?php echo e(number_format($souscription['reste_a_payer'] ?? 0, 0)); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <span style="background-color: #fef3c7; padding: 1px 3px; border-radius: 2px;">
                                        <?php echo e(number_format($souscription['progression'] ?? 0, 1)); ?>%
                                    </span>
                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <?php
                                        $statut = $souscription['statut'] ?? 'inactive';
                                        $badgeStyles = match($statut) {
                                            'completement_payee' => 'background-color: #d1fae5; color: #065f46;',
                                            'partiellement_payee' => 'background-color: #fef3c7; color: #92400e;',
                                            default => 'background-color: #fee2e2; color: #991b1b;'
                                        };
                                    ?>
                                    <span style="padding: 3px 8px; border-radius: 12px; font-size: 8px; font-weight: bold; text-transform: uppercase; <?php echo e($badgeStyles); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $statut))); ?>

                                    </span>
                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <?php echo e($souscription['date_souscription'] ?? 'N/A'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Détail des Souscriptions
            </h2>
            <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
                Aucune souscription enregistrée pour ce FIMECO
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($rapport['paiements_detail']) && count($rapport['paiements_detail']) > 0): ?>
        <!-- HISTORIQUE DES PAIEMENTS -->
        <div style="margin-bottom: 25px; page-break-before: always;">
            <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Historique des Paiements (Derniers 15)
            </h2>

            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Souscripteur</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Montant</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Type</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Validateur</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = array_slice($rapport['paiements_detail'], 0, 15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background-color: <?php echo e($loop->even ? '#f9fafb' : 'white'); ?>;">
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                    <strong><?php echo e(htmlspecialchars($paiement['souscripteur'] ?? 'N/A')); ?></strong>
                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    <?php echo e(number_format($paiement['montant'] ?? 0, 0)); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <?php echo e(htmlspecialchars($paiement['type_paiement'] ?? 'N/A')); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <?php
                                        $statut = $paiement['statut'] ?? 'en_attente';
                                        $badgeStyles = match($statut) {
                                            'Validé' => 'background-color: #d1fae5; color: #065f46;',
                                            'En attente de validation' => 'background-color: #fef3c7; color: #92400e;',
                                            default => 'background-color: #fee2e2; color: #991b1b;'
                                        };
                                    ?>
                                    <span style="padding: 3px 8px; border-radius: 12px; font-size: 8px; font-weight: bold; text-transform: uppercase; <?php echo e($badgeStyles); ?>">
                                        <?php echo e($statut); ?>

                                    </span>
                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    <?php echo e($paiement['date_paiement'] ?? 'N/A'); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                    <?php echo e(htmlspecialchars($paiement['validateur'] ?? '-')); ?>

                                </td>
                                <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">
                                    <?php echo e(htmlspecialchars($paiement['reference'] ?? '-')); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- RECOMMANDATIONS -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #10b981; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Recommandations
        </h2>

        <?php
            $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
            $nbSouscriptions = $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0;
            $tauxReussite = $rapport['analyses']['taux_reussite'] ?? 0;
            $souscriptionsEnRetard = $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0;
        ?>

        <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; padding: 10px; margin-bottom: 10px;">
            <strong style="color: #065f46; font-size: 10px;">Points Positifs</strong>
            <div style="margin-top: 6px; font-size: 9px; color: #047857; line-height: 1.4;">
                <?php if($progression >= 75): ?>
                    • Excellente progression (<?php echo e(number_format($progression, 1)); ?>%)<br>
                <?php endif; ?>
                <?php if($tauxReussite >= 70): ?>
                    • Bon taux de réussite (<?php echo e(number_format($tauxReussite, 1)); ?>%)<br>
                <?php endif; ?>
                <?php if($nbSouscriptions >= 10): ?>
                    • Bonne participation (<?php echo e($nbSouscriptions); ?> souscriptions)<br>
                <?php endif; ?>
                <?php if($souscriptionsEnRetard == 0): ?>
                    • Aucune souscription en retard<br>
                <?php endif; ?>
                <?php if($progression < 75 && $tauxReussite < 70 && $nbSouscriptions < 10): ?>
                    • Projet en développement<br>
                    • Potentiel d'amélioration identifié
                <?php endif; ?>
            </div>
        </div>

        <div style="background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 4px; padding: 10px; margin-bottom: 10px;">
            <strong style="color: #92400e; font-size: 10px;">Points d'Amélioration</strong>
            <div style="margin-top: 6px; font-size: 9px; color: #b45309; line-height: 1.4;">
                <?php if($progression < 50): ?>
                    • Progression faible - Intensifier communication<br>
                <?php endif; ?>
                <?php if($tauxReussite < 50): ?>
                    • Taux faible - Revoir stratégie de suivi<br>
                <?php endif; ?>
                <?php if($souscriptionsEnRetard > 0): ?>
                    • <?php echo e($souscriptionsEnRetard); ?> en retard - Relancer<br>
                <?php endif; ?>
                <?php if($nbSouscriptions < 5): ?>
                    • Peu de souscriptions - Élargir base<br>
                <?php endif; ?>
                <?php if($progression >= 50 && $tauxReussite >= 50 && $souscriptionsEnRetard == 0 && $nbSouscriptions >= 5): ?>
                    • Maintenir dynamique actuelle<br>
                    • Optimiser processus existants
                <?php endif; ?>
            </div>
        </div>

        <?php if($progression < 25): ?>
            <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;">
                <strong>Actions Urgentes :</strong> Campagne intensive, révision communication, facilités paiement.
            </div>
        <?php elseif($progression < 75): ?>
            <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #fefce8; border: 1px solid #fde047; color: #92400e;">
                <strong>Actions Court Terme :</strong> Relancer inactifs, événements mobilisation, communication régulière.
            </div>
        <?php else: ?>
            <div style="padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 9px; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                <strong>Actions Suivi :</strong> Maintenir dynamique, préparer clôture, capitaliser succès.
            </div>
        <?php endif; ?>
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
                Généré le <?php echo e($rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s')); ?>

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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/fimecos/reports-pdf.blade.php ENDPATH**/ ?>