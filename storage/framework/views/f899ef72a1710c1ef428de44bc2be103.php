<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($rapport->titre_rapport); ?></title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    <?php
        // Calcul des statistiques globales
        $totalPresents = $rapport->nombre_presents ?? 0;
        $totalCollecte = $rapport->montant_collecte ?? 0;
        $nbPointsTraites = $rapport->points_traites ? count($rapport->points_traites) : 0;
        $nbActionsDecidees = $rapport->actions_suivre ? count($rapport->actions_suivre) : 0;
        $nbPresences = $rapport->presences ? count($rapport->presences) : 0;
        $noteSatisfaction = $rapport->note_satisfaction ?? 0;
    ?>

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
            <?php echo e($rapport->titre_rapport); ?>

        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            <?php echo e($rapport->type_rapport_traduit); ?>

            <?php if($rapport->reunion): ?> - <?php echo e($rapport->reunion->titre); ?><?php endif; ?>
            - Généré le <?php echo e(now()->format('d/m/Y à H:i:s')); ?>

        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Statistiques Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PARTICIPANTS</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;"><?php echo e(number_format($totalPresents, 0, ',', ' ')); ?></div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">MONTANT COLLECTÉ</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;"><?php echo e(number_format($totalCollecte, 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">POINTS TRAITÉS</div>
                <div style="font-size: 12px; font-weight: bold; color: #7c3aed;"><?php echo e($nbPointsTraites); ?></div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">ACTIONS DÉCIDÉES</div>
                <div style="font-size: 12px; font-weight: bold; color: #f59e0b;"><?php echo e($nbActionsDecidees); ?></div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- INFORMATIONS GÉNÉRALES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TYPE RAPPORT</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;"><?php echo e($rapport->type_rapport_traduit); ?></div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">DATE CRÉATION</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;"><?php echo e($rapport->created_at->format('d/m/Y')); ?></div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">RÉDACTEUR</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;"><?php echo e($rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'Non assigné'); ?></div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">STATUT</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;"><?php echo e($rapport->statut_traduit); ?></div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- RÉSUMÉ EXÉCUTIF -->
    <?php if($rapport->resume): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Résumé Exécutif
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $rapport,'field' => 'resume','showMeta' => 'true','class' => 'content-display']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rapport),'field' => 'resume','show-meta' => 'true','class' => 'content-display']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- POINTS TRAITÉS -->
    <?php if($rapport->points_traites && count($rapport->points_traites) > 0): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Points Traités (<?php echo e(count($rapport->points_traites)); ?>)
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php $__currentLoopData = $rapport->points_traites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; position: relative; padding-left: 20px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        <?php echo e(is_array($point) ? ($point['titre'] ?? $point) : $point); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- DÉCISIONS PRISES -->
    <?php if($rapport->decisions_prises): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Décisions Prises
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $rapport,'field' => 'decisions_prises','showMeta' => 'true','class' => 'content-display']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rapport),'field' => 'decisions_prises','show-meta' => 'true','class' => 'content-display']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ACTIONS DÉCIDÉES -->
    <?php if($rapport->actions_decidees): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Actions Décidées
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $rapport,'field' => 'actions_decidees','showMeta' => 'true','class' => 'content-display']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rapport),'field' => 'actions_decidees','show-meta' => 'true','class' => 'content-display']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ACTIONS DE SUIVI -->
    <?php if($rapport->actions_suivre && count($rapport->actions_suivre) > 0): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #f59e0b; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Actions de Suivi (<?php echo e(count($rapport->actions_suivre)); ?>)
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php $__currentLoopData = $rapport->actions_suivre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background: white; padding: 12px; margin-bottom: 10px; border-radius: 4px; border-left: 3px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="font-weight: bold; color: #1f2937; margin-bottom: 4px; font-size: 10px;"><?php echo e($action['titre'] ?? 'Action sans titre'); ?></div>
                    <?php if(isset($action['description'])): ?>
                        <div style="color: #6b7280; font-size: 9px; margin-bottom: 6px;"><?php echo e($action['description']); ?></div>
                    <?php endif; ?>
                    <div style="font-size: 8px; color: #9ca3af; display: flex; justify-content: space-between;">
                        <span>
                            <?php if(isset($action['echeance'])): ?>
                                Échéance: <?php echo e(\Carbon\Carbon::parse($action['echeance'])->format('d/m/Y')); ?>

                            <?php endif; ?>
                            <?php if(isset($action['responsable'])): ?>
                                | <?php echo e($action['responsable']); ?>

                            <?php endif; ?>
                        </span>
                        <?php if(isset($action['priorite'])): ?>
                            <span style="padding: 2px 6px; font-size: 7px; font-weight: 600; border-radius: 10px; text-transform: uppercase; background: #dbeafe; color: #1e40af;">
                                <?php echo e(ucfirst($action['priorite'])); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- LISTE DES PRÉSENCES -->
    <?php if($rapport->presences && count($rapport->presences) > 0): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Liste des Présences (<?php echo e(count($rapport->presences)); ?>)
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <table style="width: 100%; border-collapse: collapse;">
                <?php $__currentLoopData = array_chunk($rapport->presences, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td style="width: 33.33%; padding: 8px; text-align: center; border: 1px solid #e5e7eb; background: white;">
                                <div style="font-weight: bold; color: #1f2937; font-size: 9px;"><?php echo e(is_array($presence) ? $presence['nom'] : $presence); ?></div>
                                <?php if(is_array($presence) && isset($presence['role'])): ?>
                                    <div style="font-size: 8px; color: #6b7280; font-style: italic;"><?php echo e($presence['role']); ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($chunk) < 3): ?>
                            <?php for($i = count($chunk); $i < 3; $i++): ?>
                                <td style="width: 33.33%; padding: 8px; border: 1px solid #e5e7eb;"></td>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- RECOMMANDATIONS -->
    <?php if($rapport->recommandations): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Recommandations
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $rapport,'field' => 'recommandations','showMeta' => 'true','class' => 'content-display']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rapport),'field' => 'recommandations','show-meta' => 'true','class' => 'content-display']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- COMMENTAIRES -->
    <?php if($rapport->commentaires): ?>
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Commentaires
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <?php if (isset($component)) { $__componentOriginal55db839a53cf43454e10df1b99ef9479 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55db839a53cf43454e10df1b99ef9479 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ckeditor-display','data' => ['model' => $rapport,'field' => 'commentaires','showMeta' => 'true','class' => 'content-display']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ckeditor-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rapport),'field' => 'commentaires','show-meta' => 'true','class' => 'content-display']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $attributes = $__attributesOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__attributesOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55db839a53cf43454e10df1b99ef9479)): ?>
<?php $component = $__componentOriginal55db839a53cf43454e10df1b99ef9479; ?>
<?php unset($__componentOriginal55db839a53cf43454e10df1b99ef9479); ?>
<?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FOOTER -->
    <div style="background-color: #37393b; color: white; padding: 10px 15px; font-size: 7px; border-top: 3px solid #3b82f6; margin: 30px -1cm -1cm -1cm;">
        <div style="text-align: center; font-style: italic; color: #fbbf24; margin-bottom: 8px; font-size: 8px; line-height: 1.3; padding-bottom: 8px; border-bottom: 1px solid #4b5563;">
            <?php if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset)): ?>
                "<?php echo e(htmlspecialchars($AppParametres->verset_biblique)); ?>" - <?php echo e(htmlspecialchars($AppParametres->reference_verset)); ?>

            <?php else: ?>
                "Honore l'Éternel avec tes biens, et avec les prémices de tout ton revenu" - Proverbes 3:9
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
                Généré le <?php echo e(now()->format('d/m/Y à H:i:s')); ?>

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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/rapportsreunions/rapport-pdf.blade.php ENDPATH**/ ?>