{{-- resources/views/exports/moissons/liste_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Moissons</title>
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

        /* EN-TÊTE STRUCTURE - IDENTIQUE AUX AUTRES RAPPORTS */
        .structure-header {
            background-color: #1e40af;
            color: white;
            padding: 15px;
            margin: -1cm -1cm 20px -1cm;
            border-bottom: 4px solid #f59e0b;
            overflow: hidden;
        }

        .structure-header-content {
            width: 100%;
        }

        .structure-header-left {
            float: left;
            width: 48%;
        }

        .structure-header-right {
            float: right;
            width: 48%;
            text-align: right;
        }

        .logo-section {
            float: left;
            width: 70px;
            margin-right: 10px;
        }

        .info-left {
            margin-left: 80px;
        }

        .structure-logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 8px;
            padding: 5px;
            text-align: center;
            line-height: 60px;
        }

        .structure-logo img {
            max-width: 50px;
            max-height: 50px;
            vertical-align: middle;
        }

        .structure-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .structure-contact {
            font-size: 7px;
            line-height: 1.6;
            color: white;
        }

        .structure-contact div {
            margin: 3px 0;
            word-wrap: break-word;
        }

        /* Clearfix pour le float */
        .structure-header-content:after {
            content: "";
            display: table;
            clear: both;
        }

        /* TITRE RAPPORT */
        .report-title {
            text-align: center;
            padding: 15px 0;
            margin: 20px 0;
            border-bottom: 3px solid #3b82f6;
        }

        .report-title h1 {
            color: #1f2937;
            font-size: 18px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .report-title .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin: 0;
        }

        /* SECTIONS */
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

        .section-title.statistiques {
            background-color: #7c3aed;
        }

        .section-title.donnees {
            background-color: #dc2626;
        }

        .section-title.legende {
            background-color: #6366f1;
        }

        /* FILTRES */
        .filters-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
        }

        .filters-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .filter-item {
            display: inline-block;
            margin-right: 15px;
            font-size: 8px;
            color: #374151;
        }

        /* KPIs - CORRIGÉ */
        .kpis-grid {
            width: 100%;
            margin-bottom: 15px;
        }

        .kpi-card {
            float: left;
            width: 23%;
            margin-right: 2%;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            box-sizing: border-box;
        }

        .kpi-card:nth-child(4n) {
            margin-right: 0;
        }

        /* Clearfix pour les KPIs */
        .kpis-grid:after {
            content: "";
            display: table;
            clear: both;
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

        /* TABLEAUX */
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

        /* STATUTS - Couleurs selon progression */
        .status-atteint {
            background-color: #d1fae5 !important;
            color: #065f46;
            font-weight: bold;
        }

        .status-presque {
            background-color: #fef3c7 !important;
            color: #92400e;
        }

        .status-cours {
            background-color: #dbeafe !important;
            color: #1e40af;
        }

        .status-debut {
            background-color: #fef9c3 !important;
            color: #854d0e;
        }

        .status-faible {
            background-color: #fee2e2 !important;
            color: #991b1b;
        }

        /* LIGNE DE TOTAUX */
        .totals-row {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
            color: white !important;
            font-weight: bold;
        }

        .totals-row td {
            border-color: #047857 !important;
            padding: 8px 4px !important;
            color: white !important;
        }

        /* LÉGENDE */
        .legend-grid {
            width: 100%;
            margin: 15px 0;
        }

        .legend-item {
            float: left;
            width: 18%;
            margin-right: 2%;
            text-align: center;
            font-size: 8px;
        }

        .legend-item:nth-child(5n) {
            margin-right: 0;
        }

        .legend-grid:after {
            content: "";
            display: table;
            clear: both;
        }

        .legend-color {
            width: 100%;
            height: 15px;
            border: 1px solid #d1d5db;
            margin: 0 0 3px 0;
            border-radius: 2px;
        }

        /* FOOTER */
        .footer {
            background-color: #37393b;
            color: white;
            padding: 10px 15px;
            font-size: 7px;
            border-top: 3px solid #3b82f6;
            margin: 30px -1cm -1cm -1cm;
        }

        .footer-verse {
            text-align: center;
            font-style: italic;
            color: #fbbf24;
            margin-bottom: 8px;
            font-size: 8px;
            line-height: 1.3;
            padding-bottom: 8px;
            border-bottom: 1px solid #4b5563;
        }

        .footer-content {
            text-align: center;
        }

        .footer-social {
            margin-bottom: 5px;
        }

        .footer-platform {
            font-size: 7px;
            color: #9ca3af;
        }

        /* AUTRES STYLES */
        .page-break {
            page-break-before: always;
        }

        .no-data {
            color: #6b7280;
            font-style: italic;
            text-align: center;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- EN-TÊTE STRUCTURE -->
    <div class="structure-header">
        <div class="structure-header-content">
            <!-- PARTIE GAUCHE: Logo + Nom + Téléphones -->
            <div class="structure-header-left">
                <div class="logo-section">
                    @if(!empty($AppParametres->logo))
                        @php
                            try {
                                $logoPath = storage_path('app/public/' . $AppParametres->logo);

                                if (file_exists($logoPath)) {
                                    $imageData = base64_encode(file_get_contents($logoPath));
                                    $imageExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));

                                    $mimeTypes = [
                                        'jpg' => 'jpeg',
                                        'jpeg' => 'jpeg',
                                        'png' => 'png',
                                        'gif' => 'gif',
                                        'svg' => 'svg+xml',
                                        'webp' => 'webp'
                                    ];

                                    $mimeType = $mimeTypes[$imageExtension] ?? 'png';
                                    $logoBase64 = "data:image/{$mimeType};base64,{$imageData}";
                                } else {
                                    $logoBase64 = null;
                                }
                            } catch (\Exception $e) {
                                $logoBase64 = null;
                            }
                        @endphp

                        @if(isset($logoBase64) && $logoBase64)
                            <div class="structure-logo">
                                <img src="{{ $logoBase64 }}" alt="Logo">
                            </div>
                        @else
                            <div class="structure-logo" style="font-size: 24px; font-weight: bold; color: #3b82f6;">
                                {{ strtoupper(substr($AppParametres->nom_eglise, 0, 2)) }}
                            </div>
                        @endif
                    @else
                        <div class="structure-logo" style="font-size: 24px; font-weight: bold; color: #3b82f6;">
                            {{ strtoupper(substr($AppParametres->nom_eglise, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div class="info-left">
                    <div class="structure-name">{{ htmlspecialchars($AppParametres->nom_eglise) }}</div>
                    <div class="structure-contact">
                        @if(!empty($AppParametres->telephone_1))
                            <div><strong>Tel 1:</strong> {{ htmlspecialchars($AppParametres->telephone_1) }}</div>
                        @endif
                        @if(!empty($AppParametres->telephone_2))
                            <div><strong>Tel 2:</strong> {{ htmlspecialchars($AppParametres->telephone_2) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PARTIE DROITE: Email + Adresse -->
            <div class="structure-header-right">
                <div class="structure-contact">
                    @if(!empty($AppParametres->email))
                        <div><strong>Email:</strong> {{ htmlspecialchars($AppParametres->email) }}</div>
                    @endif
                    @if(!empty($AppParametres->adresse))
                        <div>
                            <strong>Adresse:</strong>
                            {{ htmlspecialchars($AppParametres->adresse) }}
                            @if(!empty($AppParametres->code_postal)), {{ htmlspecialchars($AppParametres->code_postal) }}@endif
                            @if(!empty($AppParametres->ville)) {{ htmlspecialchars($AppParametres->ville) }}@endif
                        </div>
                    @endif
                    @if(!empty($AppParametres->commune))
                        <div>{{ htmlspecialchars($AppParametres->commune) }}</div>
                    @endif
                    @if(!empty($AppParametres->pays))
                        <div>{{ htmlspecialchars($AppParametres->pays) }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TITRE DU RAPPORT -->
    <div class="report-title">
        <h1>RAPPORT DE GESTION DES MOISSONS</h1>
        <p class="subtitle">
            Période: {{ $donnees['meta']['periode'] }} - Total: {{ $donnees['meta']['nombre_total'] }} moisson(s)
        </p>
    </div>

    <!-- FILTRES APPLIQUÉS -->
    @if(!empty($filtres))
    <div class="section">
        <div class="filters-box">
            <div class="filters-title">Filtres appliqués:</div>
            @if(isset($filtres['date_debut']))
                <span class="filter-item"><strong>Date début:</strong> {{ \Carbon\Carbon::parse($filtres['date_debut'])->format('d/m/Y') }}</span>
            @endif
            @if(isset($filtres['date_fin']))
                <span class="filter-item"><strong>Date fin:</strong> {{ \Carbon\Carbon::parse($filtres['date_fin'])->format('d/m/Y') }}</span>
            @endif
            @if(isset($filtres['status']))
                <span class="filter-item"><strong>Statut:</strong> {{ $filtres['status'] ? 'Actif' : 'Inactif' }}</span>
            @endif
            @if(isset($filtres['statut_progression']))
                <span class="filter-item"><strong>Progression:</strong> {{ $filtres['statut_progression'] }}</span>
            @endif
        </div>
    </div>
    @endif

    <!-- STATISTIQUES RÉSUMÉES -->
    <div class="section">
        <h2 class="section-title statistiques">Statistiques Générales</h2>
        <div class="kpis-grid">
            <div class="kpi-card blue">
                <div class="kpi-label">OBJECTIFS TOTAUX</div>
                <div class="kpi-value">{{ $donnees['statistiques']['total_objectifs'] }}</div>
            </div>
            <div class="kpi-card green">
                <div class="kpi-label">MONTANT COLLECTÉ</div>
                <div class="kpi-value">{{ $donnees['statistiques']['total_collecte'] }}</div>
            </div>
            <div class="kpi-card purple">
                <div class="kpi-label">OBJECTIFS ATTEINTS</div>
                <div class="kpi-value">{{ $donnees['statistiques']['objectifs_atteints'] }}</div>
            </div>
            <div class="kpi-card amber">
                <div class="kpi-label">PERFORMANCE MOY.</div>
                <div class="kpi-value">{{ $donnees['statistiques']['performance_moyenne'] }}</div>
            </div>
        </div>
    </div>

    <!-- TABLEAU DES DONNÉES -->
    <div class="section">
        <h2 class="section-title donnees">Détail des Moissons</h2>

        @if(count($donnees['donnees']) > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%;">Thème</th>
                            <th style="width: 8%;">Date</th>
                            <th style="width: 10%;">Culte</th>
                            <th class="number" style="width: 10%;">Objectif</th>
                            <th class="number" style="width: 10%;">Collecté</th>
                            <th class="center" style="width: 6%;">%</th>
                            <th class="center" style="width: 11%;">Statut</th>
                            <th class="center" style="width: 5%;">P</th>
                            <th class="center" style="width: 5%;">V</th>
                            <th class="center" style="width: 5%;">E</th>
                            <th style="width: 10%;">Créateur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donnees['donnees'] as $moisson)
                            <tr class="{{
                                $moisson['statut'] === 'Objectif atteint' ? 'status-atteint' :
                                ($moisson['statut'] === 'Presque atteint' ? 'status-presque' :
                                ($moisson['statut'] === 'En cours' ? 'status-cours' :
                                ($moisson['statut'] === 'Début' ? 'status-debut' : 'status-faible')))
                            }}">
                                <td style="text-align: left;">{{ $moisson['theme'] }}</td>
                                <td class="center">{{ $moisson['date'] }}</td>
                                <td style="text-align: left;">{{ $moisson['culte'] }}</td>
                                <td class="number">{{ $moisson['objectif'] }}</td>
                                <td class="number">{{ $moisson['collecte'] }}</td>
                                <td class="center font-bold">{{ $moisson['pourcentage'] }}</td>
                                <td class="center">{{ $moisson['statut'] }}</td>
                                <td class="center">{{ $moisson['nb_passages'] }}</td>
                                <td class="center">{{ $moisson['nb_ventes'] }}</td>
                                <td class="center">{{ $moisson['nb_engagements'] }}</td>
                                <td style="text-align: left;">{{ $moisson['createur'] }}</td>
                            </tr>
                        @endforeach

                        <!-- Ligne de totaux -->
                        <tr class="totals-row">
                            <td colspan="3" class="font-bold">TOTAUX GÉNÉRAUX</td>
                            <td class="number font-bold">{{ $donnees['statistiques']['total_objectifs'] }}</td>
                            <td class="number font-bold">{{ $donnees['statistiques']['total_collecte'] }}</td>
                            <td class="center font-bold">{{ $donnees['statistiques']['performance_moyenne'] }}</td>
                            <td colspan="5" class="center font-bold">{{ $donnees['statistiques']['objectifs_atteints'] }} objectifs atteints</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <strong>Aucune donnée disponible</strong><br>
                Aucune moisson ne correspond aux critères sélectionnés.
            </div>
        @endif
    </div>

    <!-- LÉGENDE DES STATUTS -->
    <div class="section">
        <h2 class="section-title legende">Légende des Statuts</h2>
        <div class="legend-grid">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #d1fae5; border-color: #a7f3d0;"></div>
                <div>Objectif atteint</div>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fef3c7; border-color: #fde047;"></div>
                <div>Presque atteint</div>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #dbeafe; border-color: #93c5fd;"></div>
                <div>En cours</div>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fef9c3; border-color: #fde047;"></div>
                <div>Début</div>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fee2e2; border-color: #fca5a5;"></div>
                <div>Très faible</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-verse">
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                "{{ htmlspecialchars($AppParametres->verset_biblique) }}" - {{ htmlspecialchars($AppParametres->reference_verset) }}
            @else
                "Celui qui sème peu moissonnera peu, et celui qui sème abondamment moissonnera abondamment" - 2 Corinthiens 9:6
            @endif
        </div>
        <div class="footer-content">
            <div class="footer-social">
                @if(!empty($AppParametres->facebook_url))
                    Facebook: {{ htmlspecialchars($AppParametres->facebook_url) }} |
                @endif
                @if(!empty($AppParametres->instagram_url))
                    Instagram: {{ htmlspecialchars($AppParametres->instagram_url) }} |
                @endif
                @if(!empty($AppParametres->youtube_url))
                    YouTube: {{ htmlspecialchars($AppParametres->youtube_url) }} |
                @endif
                @if(!empty($AppParametres->twitter_url))
                    Twitter: {{ htmlspecialchars($AppParametres->twitter_url) }}
                @endif
            </div>
            <div class="footer-platform">
                @if(!empty($AppParametres->website_url))
                    Site web: {{ htmlspecialchars($AppParametres->website_url) }} |
                @endif
                Généré le {{ $donnees['meta']['date_export'] }}
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
