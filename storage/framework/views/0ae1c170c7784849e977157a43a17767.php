<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($rapport->titre_rapport); ?></title>
    <style>
        /* Importation de polices Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

        /* Reset et Configuration de Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Configuration Office 2016 - Marges Standards */
        @page {
            size: A4;
            margin: 2.54cm 2.54cm 2.54cm 2.54cm; /* Office 2016 Standard: 1 inch = 2.54cm */
            @top-center {
                content: "<?php echo e($rapport->titre_rapport); ?>";
                font-family: 'Inter', sans-serif;
                font-size: 9pt;
                color: #666;
            }
            @bottom-center {
                content: "Page " counter(page) " / " counter(pages);
                font-family: 'Inter', sans-serif;
                font-size: 8pt;
                color: #888;
            }
        }

        /* Configuration Body - Office 2016 Style */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11pt; /* Office 2016 standard */
            line-height: 1.15; /* Office 2016 line spacing */
            color: #000000; /* Noir pur pour impression */
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-feature-settings: "liga" 1, "kern" 1;
            text-rendering: optimizeLegibility;
        }

        /* Variables CSS pour consistance */
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --light-gray: #f8fafc;
            --medium-gray: #e2e8f0;
            --dark-gray: #1e293b;
            --border-radius: 8px;
            --shadow-light: 0 2px 4px rgba(0,0,0,0.06);
            --shadow-medium: 0 4px 8px rgba(0,0,0,0.12);
            --shadow-heavy: 0 8px 16px rgba(0,0,0,0.15);
        }

        /* === HEADER DOCUMENT === */
        .document-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #334155 50%, #475569 100%);
            color: white;
            padding: 32pt 24pt;
            margin: -2.54cm -2.54cm 24pt -2.54cm;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .document-header::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -15%;
            width: 200pt;
            height: 200pt;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .document-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 150pt;
            height: 150pt;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 10;
        }

        .document-title {
            font-size: 24pt;
            font-weight: 700;
            margin-bottom: 6pt;
            letter-spacing: -0.02em;
            text-shadow: 0 2pt 4pt rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .document-subtitle {
            font-size: 14pt;
            font-weight: 400;
            opacity: 0.92;
            margin-bottom: 16pt;
            line-height: 1.3;
        }

        .status-badge {
            position: absolute;
            top: 24pt;
            right: 24pt;
            padding: 8pt 16pt;
            border-radius: 20pt;
            font-size: 9pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            box-shadow: var(--shadow-medium);
            border: 1pt solid rgba(255,255,255,0.2);
        }

        /* Status Colors */
        .status-brouillon {
            background: linear-gradient(45deg, #f1f5f9, #e2e8f0);
            color: #475569;
        }
        .status-en_revision {
            background: linear-gradient(45deg, #fef3c7, #fde68a);
            color: #92400e;
        }
        .status-valide {
            background: linear-gradient(45deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        .status-publie {
            background: linear-gradient(45deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        /* === SECTIONS META INFORMATIONS === */
        .meta-section {
            margin: 24pt 0;
            background: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 20pt;
            border: 1pt solid var(--medium-gray);
            page-break-inside: avoid;
        }

        .meta-title {
            font-size: 12pt;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 16pt;
            display: flex;
            align-items: center;
            border-bottom: 1pt solid var(--medium-gray);
            padding-bottom: 8pt;
        }

        .meta-title::before {
            content: '📊';
            margin-right: 8pt;
            font-size: 14pt;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200pt, 1fr));
            gap: 12pt;
            margin-top: 16pt;
        }

        .meta-cell {
            background: white;
            padding: 16pt;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            border: 1pt solid var(--medium-gray);
            transition: all 0.2s ease;
        }

        .meta-cell:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-1pt);
        }

        .meta-label {
            font-weight: 500;
            color: var(--secondary-color);
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            margin-bottom: 4pt;
        }

        .meta-value {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 10pt;
            line-height: 1.4;
        }

        /* === STATISTIQUES VISUELLES === */
        .stats-section {
            margin: 24pt 0;
            page-break-inside: avoid;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150pt, 1fr));
            gap: 16pt;
            margin-bottom: 16pt;
        }

        .stats-cell {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 50%, #1d4ed8 100%);
            color: white;
            padding: 20pt;
            text-align: center;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            position: relative;
            overflow: hidden;
        }

        .stats-cell::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100pt;
            height: 100pt;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-number {
            font-size: 20pt;
            font-weight: 800;
            margin-bottom: 6pt;
            text-shadow: 0 2pt 4pt rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }

        .stat-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            opacity: 0.95;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* === SECTIONS DE CONTENU === */
        .content-section {
            margin: 20pt 0;
            page-break-inside: avoid;
        }

        .section-header {
            background: linear-gradient(90deg, var(--light-gray) 0%, #f1f5f9 100%);
            padding: 14pt 18pt;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            border-left: 3pt solid var(--primary-color);
            border: 1pt solid var(--medium-gray);
            border-bottom: none;
        }

        .section-title {
            font-size: 12pt;
            font-weight: 600;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            letter-spacing: -0.01em;
        }

        .section-icon {
            margin-right: 8pt;
            font-size: 14pt;
            filter: brightness(1.1);
        }

        .section-content {
            background: white;
            border: 1pt solid var(--medium-gray);
            border-top: none;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            padding: 18pt;
            box-shadow: var(--shadow-light);
            line-height: 1.6;
        }

        .section-content p {
            margin-bottom: 12pt;
            text-align: justify;
            color: #374151;
        }

        .section-content p:last-child {
            margin-bottom: 0;
        }

        /* === LISTES AMÉLIORÉES === */
        .enhanced-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .enhanced-list li {
            padding: 10pt 0 10pt 24pt;
            border-bottom: 1pt solid #f8fafc;
            position: relative;
            transition: all 0.2s ease;
        }

        .enhanced-list li:last-child {
            border-bottom: none;
        }

        .enhanced-list li:hover {
            background-color: #f8fafc;
            border-radius: 4pt;
            padding-left: 28pt;
        }

        .enhanced-list li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
            font-size: 10pt;
            top: 10pt;
        }

        /* === TABLEAUX PROFESSIONNELS === */
        .professional-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 12pt 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            border: 1pt solid var(--medium-gray);
        }

        .professional-table td {
            background: white;
            padding: 12pt;
            text-align: center;
            border-right: 1pt solid #f1f5f9;
            vertical-align: middle;
            transition: background-color 0.2s ease;
        }

        .professional-table td:last-child {
            border-right: none;
        }

        .professional-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .professional-table tr:hover td {
            background: #f1f5f9;
        }

        /* === ACTIONS CONTAINER === */
        .actions-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 16pt;
            border-radius: var(--border-radius);
            margin: 8pt 0;
        }

        .action-card {
            background: white;
            padding: 14pt;
            margin-bottom: 10pt;
            border-radius: var(--border-radius);
            border-left: 3pt solid var(--primary-color);
            box-shadow: var(--shadow-light);
            border: 1pt solid var(--medium-gray);
            transition: all 0.2s ease;
        }

        .action-card:last-child {
            margin-bottom: 0;
        }

        .action-card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateX(2pt);
        }

        .action-title {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 6pt;
            font-size: 10pt;
        }

        .action-desc {
            color: var(--secondary-color);
            font-size: 9pt;
            margin-bottom: 8pt;
            line-height: 1.5;
        }

        .action-meta {
            font-size: 8pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8pt;
        }

        .priority-badge {
            padding: 3pt 8pt;
            font-size: 7pt;
            font-weight: 600;
            border-radius: 12pt;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        .priority-faible {
            background: #dcfce7;
            color: #166534;
            border: 1pt solid #22c55e;
        }
        .priority-normale {
            background: #dbeafe;
            color: #1e40af;
            border: 1pt solid #3b82f6;
        }
        .priority-haute {
            background: #fed7aa;
            color: #c2410c;
            border: 1pt solid #f97316;
        }
        .priority-critique {
            background: #fecaca;
            color: #dc2626;
            border: 1pt solid #ef4444;
        }

        /* === SYSTÈME DE NOTATION === */
        .rating-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2pt;
        }

        .star {
            font-size: 14pt;
            text-shadow: 0 1pt 2pt rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .star:hover {
            transform: scale(1.1);
        }

        .star.filled {
            color: #fbbf24;
        }

        .star.empty {
            color: #d1d5db;
        }

        /* === CARTES DE PRÉSENCE === */
        .presence-card {
            background: white;
            padding: 10pt;
            text-align: center;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            border: 1pt solid var(--medium-gray);
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .presence-card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-1pt);
        }

        .presence-name {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 9pt;
            margin-bottom: 3pt;
        }

        .presence-role {
            font-size: 8pt;
            color: var(--secondary-color);
            font-style: italic;
        }

        /* === FOOTER PROFESSIONNEL === */
        .document-footer {
            margin-top: 40pt;
            padding: 20pt;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: 2pt solid var(--primary-color);
            border-radius: var(--border-radius);
            text-align: center;
            page-break-inside: avoid;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16pt;
            text-align: center;
        }

        .footer-section {
            padding: 0 12pt;
        }

        .footer-title {
            font-weight: 600;
            color: #374151;
            font-size: 8pt;
            margin-bottom: 3pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .footer-text {
            color: var(--secondary-color);
            font-size: 8pt;
            line-height: 1.4;
        }

        /* === UTILITIES === */
        .clear {
            clear: both;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* === RESPONSIVE ET PRINT === */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 10pt;
            }

            .content-section {
                page-break-inside: avoid;
                margin: 16pt 0;
            }

            .meta-section,
            .stats-section,
            .document-footer {
                page-break-inside: avoid;
            }

            .action-card,
            .stats-cell,
            .meta-cell {
                page-break-inside: avoid;
            }

            .professional-table {
                page-break-inside: avoid;
            }

            /* Éviter les orphelines et veuves */
            h1, h2, h3, h4, h5, h6 {
                page-break-after: avoid;
            }

            p {
                orphans: 3;
                widows: 3;
            }
        }

        @media screen and (max-width: 768px) {
            .meta-grid,
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 12pt;
            }

            .document-header {
                padding: 20pt 16pt;
            }

            .status-badge {
                position: relative;
                top: auto;
                right: auto;
                margin-top: 12pt;
                display: inline-block;
            }
        }

        /* === ANIMATIONS ET TRANSITIONS === */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20pt);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-section {
            animation: fadeInUp 0.6s ease-out;
        }

        /* === SÉLECTION DE TEXTE === */
        ::selection {
            background: rgba(37, 99, 235, 0.2);
            color: var(--dark-gray);
        }

        ::-moz-selection {
            background: rgba(37, 99, 235, 0.2);
            color: var(--dark-gray);
        }

        /* === FOCUS STYLES === */
        .action-card:focus,
        .meta-cell:focus,
        .stats-cell:focus {
            outline: 2pt solid var(--primary-color);
            outline-offset: 2pt;
        }

        /* === SCROLLBAR CUSTOM (pour preview web) === */
        @media screen {
            ::-webkit-scrollbar {
                width: 8pt;
            }

            ::-webkit-scrollbar-track {
                background: var(--light-gray);
            }

            ::-webkit-scrollbar-thumb {
                background: var(--medium-gray);
                border-radius: 4pt;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--secondary-color);
            }
        }
    </style>
</head>
<body>
    <!-- Header Document -->
    <div class="document-header">
        <div class="header-content">
            <h1 class="document-title"><?php echo e($rapport->titre_rapport); ?></h1>
            <p class="document-subtitle">
                <?php echo e($rapport->type_rapport_traduit); ?>

                <?php if($rapport->reunion): ?> - <?php echo e($rapport->reunion->titre); ?><?php endif; ?>
            </p>
            <div class="status-badge status-<?php echo e($rapport->statut); ?>">
                <?php echo e($rapport->statut_traduit); ?>

            </div>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Informations Générales -->
    <div class="meta-section">
        <div class="meta-title">Informations Générales</div>
        <div class="meta-grid">
            <div class="meta-cell">
                <div class="meta-label">Type de Rapport</div>
                <div class="meta-value"><?php echo e($rapport->type_rapport_traduit); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Date de Création</div>
                <div class="meta-value"><?php echo e($rapport->created_at->format('d/m/Y à H:i')); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Rédacteur</div>
                <div class="meta-value"><?php echo e($rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'Non assigné'); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Statut Actuel</div>
                <div class="meta-value"><?php echo e($rapport->statut_traduit); ?></div>
            </div>
            <?php if($rapport->reunion || $rapport->validateur || $rapport->valide_le): ?>
            <div class="meta-cell">
                <div class="meta-label">Réunion</div>
                <div class="meta-value"><?php echo e($rapport->reunion ? $rapport->reunion->titre : 'Non associée'); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Date Réunion</div>
                <div class="meta-value"><?php echo e($rapport->reunion ? \Carbon\Carbon::parse($rapport->reunion->date_reunion)->format('d/m/Y') : 'N/A'); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Validateur</div>
                <div class="meta-value"><?php echo e($rapport->validateur ? $rapport->validateur->nom . ' ' . $rapport->validateur->prenom : 'En attente'); ?></div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Validé le</div>
                <div class="meta-value"><?php echo e($rapport->valide_le ? $rapport->valide_le->format('d/m/Y') : 'En attente'); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistiques Visuelles -->
    <?php if($rapport->nombre_presents || $rapport->montant_collecte || $rapport->note_satisfaction || ($rapport->points_traites && count($rapport->points_traites) > 0)): ?>
    <div class="stats-section">
        <div class="stats-grid">
            <?php if($rapport->nombre_presents): ?>
            <div class="stats-cell">
                <div class="stat-number"><?php echo e($rapport->nombre_presents); ?></div>
                <div class="stat-label">Participants</div>
            </div>
            <?php endif; ?>
            <?php if($rapport->montant_collecte): ?>
            <div class="stats-cell">
                <div class="stat-number"><?php echo e(number_format($rapport->montant_collecte, 0)); ?>FCFA</div>
                <div class="stat-label">Montant Collecté</div>
            </div>
            <?php endif; ?>
            <?php if($rapport->note_satisfaction): ?>
            <div class="stats-cell">
                <div class="stat-number">
                    <div class="rating-container">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo e($i <= $rapport->note_satisfaction ? 'filled' : 'empty'); ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="stat-label">Satisfaction</div>
            </div>
            <?php endif; ?>
            <?php if($rapport->points_traites && count($rapport->points_traites) > 0): ?>
            <div class="stats-cell">
                <div class="stat-number"><?php echo e(count($rapport->points_traites)); ?></div>
                <div class="stat-label">Points Traités</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Résumé Exécutif -->
    <?php if($rapport->resume): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">📄</span>
                Résumé Exécutif
            </div>
        </div>
        <div class="section-content">
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

    <!-- Points Traités -->
    <?php if($rapport->points_traites && count($rapport->points_traites) > 0): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">📋</span>
                Points Traités (<?php echo e(count($rapport->points_traites)); ?>)
            </div>
        </div>
        <div class="section-content">
            <ul class="enhanced-list">
                <?php $__currentLoopData = $rapport->points_traites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e(is_array($point) ? ($point['titre'] ?? $point) : $point); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- Décisions Prises -->
    <?php if($rapport->decisions_prises): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">⚖️</span>
                Décisions Prises
            </div>
        </div>
        <div class="section-content">
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

    <!-- Actions Décidées -->
    <?php if($rapport->actions_decidees): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">🎯</span>
                Actions Décidées
            </div>
        </div>
        <div class="section-content">
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

    <!-- Actions de Suivi -->
    <?php if($rapport->actions_suivre && count($rapport->actions_suivre) > 0): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">📌</span>
                Actions de Suivi (<?php echo e(count($rapport->actions_suivre)); ?>)
            </div>
        </div>
        <div class="section-content">
            <div class="actions-container">
                <?php $__currentLoopData = $rapport->actions_suivre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="action-card">
                        <div class="action-title"><?php echo e($action['titre'] ?? 'Action sans titre'); ?></div>
                        <?php if(isset($action['description'])): ?>
                            <div class="action-desc"><?php echo e($action['description']); ?></div>
                        <?php endif; ?>
                        <div class="action-meta">
                            <span>
                                <?php if(isset($action['echeance'])): ?>
                                    📅 Échéance: <?php echo e(\Carbon\Carbon::parse($action['echeance'])->format('d/m/Y')); ?>

                                <?php endif; ?>
                                <?php if(isset($action['responsable'])): ?>
                                    👤 <?php echo e($action['responsable']); ?>

                                <?php endif; ?>
                            </span>
                            <?php if(isset($action['priorite'])): ?>
                                <span class="priority-badge priority-<?php echo e($action['priorite']); ?>">
                                    <?php echo e(ucfirst($action['priorite'])); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liste des Présences -->
    <?php if($rapport->presences && count($rapport->presences) > 0): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">👥</span>
                Liste des Présences (<?php echo e(count($rapport->presences)); ?>)
            </div>
        </div>
        <div class="section-content">
            <table class="professional-table">
                <?php $__currentLoopData = array_chunk($rapport->presences, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <div class="presence-card">
                                    <div class="presence-name"><?php echo e(is_array($presence) ? $presence['nom'] : $presence); ?></div>
                                    <?php if(is_array($presence) && isset($presence['role'])): ?>
                                        <div class="presence-role"><?php echo e($presence['role']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($chunk) < 3): ?>
                            <?php for($i = count($chunk); $i < 3; $i++): ?>
                                <td></td>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recommandations -->
    <?php if($rapport->recommandations): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">💡</span>
                Recommandations
            </div>
        </div>
        <div class="section-content">
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

    <!-- Commentaires -->
    <?php if($rapport->commentaires): ?>
    <div class="content-section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">💬</span>
                Commentaires
            </div>
        </div>
        <div class="section-content">
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

    <!-- Footer Professionnel -->
    <div class="document-footer">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-title">Génération</div>
                <div class="footer-text"><?php echo e(now()->format('d/m/Y à H:i')); ?></div>
            </div>
            <div class="footer-section">
                <div class="footer-title">Référence</div>
                <div class="footer-text">Rapport #<?php echo e($rapport->id); ?></div>
            </div>
            <div class="footer-section">
                <div class="footer-title">Validation</div>
                <div class="footer-text">
                    <?php if($rapport->validateur && $rapport->valide_le): ?>
                        <?php echo e($rapport->validateur->nom); ?> <?php echo e($rapport->validateur->prenom); ?><br>
                        <?php echo e($rapport->valide_le->format('d/m/Y')); ?>

                    <?php else: ?>
                        En attente
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/rapportsreunions/export/rapport-pdf.blade.php ENDPATH**/ ?>