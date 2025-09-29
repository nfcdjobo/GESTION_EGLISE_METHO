<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Culte - {{ $culte->titre }}</title>
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

        /* EN-TÊTE STRUCTURE - CORRIGÉ */
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
            width: 100%;
        }

        .column {
            float: left;
            width: 48%;
            margin-right: 2%;
        }

        .column:nth-child(2n) {
            margin-right: 0;
        }

        .two-column:after {
            content: "";
            display: table;
            clear: both;
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
                                
                                // Vérifier si le fichier existe
                                if (file_exists($logoPath)) {
                                    $imageData = base64_encode(file_get_contents($logoPath));
                                    $imageExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                                    
                                    // Déterminer le type MIME
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
        <h1>RAPPORT DE CULTE</h1>
        <p class="subtitle">{{ htmlspecialchars($culte->titre) }} -
            {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }}</p>
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
                        <td><strong>{{ htmlspecialchars($culte->titre) }}</strong></td>
                        <td>{{ htmlspecialchars($culte->type_culte_libelle) }}</td>
                        <td>{{ htmlspecialchars($culte->categorie_libelle) }}</td>
                        <td>{{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}
                            @if($culte->heure_fin)
                                - {{ \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') }}
                            @endif
                        </td>
                        <td>{{ htmlspecialchars($culte->lieu) }}</td>
                        <td class="center">
                            <span class="status-badge status-{{ $culte->statut }}">
                                {{ htmlspecialchars($culte->statut_libelle) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($culte->description)
            <div class="summary-box">
                <strong>Description:</strong>
                <div style="margin-top: 5px; font-size: 9px;">{{ nl2br(htmlspecialchars(strip_tags($culte->description))) }}
                </div>
            </div>
        @endif
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
                    @if($culte->officiants_detail->isNotEmpty())
                        <tr style="background-color: #f3f4f6;">
                            <td colspan="4"
                                style="text-align: center; font-weight: bold; font-size: 9px; padding: 5px; color: #374151;">
                                OFFICIANTS
                            </td>
                        </tr>

                        @foreach($culte->officiants_detail as $officiant)
                            <tr>
                                <td><strong>{{ htmlspecialchars($officiant['titre']) }}</strong>
                                    @if($officiant['provenance'] && $officiant['provenance'] !== 'Église Locale')
                                        <br><span
                                            style="font-size: 7px; color: #6b7280; font-weight: normal;">({{ htmlspecialchars($officiant['provenance']) }})</span>
                                    @endif
                                </td>
                                <td>{{ htmlspecialchars($officiant['user']->nom_complet) }}</td>
                                <td>{{ htmlspecialchars($officiant['user']->email ?? 'Non renseigné') }}</td>
                                <td>{{ htmlspecialchars($officiant['user']->telephone_1 ?? 'Non renseigné') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Message et Prédication -->
    @if($culte->titre_message || $culte->passage_biblique || $culte->resume_message)
        <div class="section">
            <h2 class="section-title message">Message et Prédication</h2>

            @if($culte->titre_message)
                <div class="summary-box">
                    <strong>Titre du message:</strong> {{ htmlspecialchars($culte->titre_message) }}
                </div>
            @endif

            @if($culte->passage_biblique)
                <div class="bible-verse">
                    <strong>Passage biblique:</strong> {{ htmlspecialchars($culte->passage_biblique) }}
                </div>
            @endif

            @if($culte->resume_message)
                <div class="message-content">
                    <strong>Résumé du message:</strong><br>
                    {{ nl2br(htmlspecialchars(strip_tags($culte->resume_message))) }}
                </div>
            @endif
        </div>
    @endif

    <!-- Statistiques de Participation -->
    @if($culte->nombre_participants || $culte->statut === 'termine')
        <div class="section">
            <h2 class="section-title statistiques">Statistiques de Participation</h2>

            <div class="kpis-grid">
                @if($culte->nombre_participants)
                    <div class="kpi-card blue">
                        <div class="kpi-label">TOTAL PARTICIPANTS</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_participants) }}</div>
                    </div>
                @endif

                @if($culte->nombre_adultes)
                    <div class="kpi-card green">
                        <div class="kpi-label">ADULTES</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_adultes) }}</div>
                    </div>
                @endif

                @if($culte->nombre_jeunes)
                    <div class="kpi-card purple">
                        <div class="kpi-label">JEUNES</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_jeunes) }}</div>
                    </div>
                @endif

                @if($culte->nombre_enfants)
                    <div class="kpi-card amber">
                        <div class="kpi-label">ENFANTS</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_enfants) }}</div>
                    </div>
                @endif

                @if($culte->nombre_nouveaux)
                    <div class="kpi-card blue">
                        <div class="kpi-label">NOUVEAUX</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_nouveaux) }}</div>
                    </div>
                @endif

                @if($culte->nombre_conversions)
                    <div class="kpi-card green">
                        <div class="kpi-label">CONVERSIONS</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_conversions) }}</div>
                    </div>
                @endif

                @if($culte->nombre_baptemes)
                    <div class="kpi-card purple">
                        <div class="kpi-label">BAPTÊMES</div>
                        <div class="kpi-value">{{ number_format($culte->nombre_baptemes) }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Statistiques Financières -->
    @if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0)
        <div class="section page-break">
            <h2 class="section-title financier">Statistiques Financières</h2>

            <div class="kpis-grid">
                <div class="kpi-card green">
                    <div class="kpi-label">MONTANT TOTAL</div>
                    <div class="kpi-value">{{ number_format($fondsStatistiques['montant_total'], 0) }} FCFA</div>
                </div>

                <div class="kpi-card blue">
                    <div class="kpi-label">TRANSACTIONS</div>
                    <div class="kpi-value">{{ $fondsStatistiques['total_transactions'] }}</div>
                </div>

                <div class="kpi-card purple">
                    <div class="kpi-label">DONATEURS</div>
                    <div class="kpi-value">{{ $fondsStatistiques['donateurs_uniques'] }}</div>
                </div>

                <div class="kpi-card amber">
                    <div class="kpi-label">MOYENNE/TRANSACTION</div>
                    <div class="kpi-value">{{ number_format($metriques['transaction_moyenne'], 0) }} FCFA</div>
                </div>
            </div>

            @if(count($fondsStatistiques['par_type']) > 0)
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
                            @foreach($fondsStatistiques['par_type'] as $type => $data)
                                <tr>
                                    <td>{{ htmlspecialchars(ucfirst(str_replace('_', ' ', $type))) }}</td>
                                    <td class="center">{{ $data['nombre'] }}</td>
                                    <td class="number"><strong>{{ number_format($data['montant'], 0) }}</strong></td>
                                    <td class="center">{{ $data['pourcentage'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-verse">
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                "{{ htmlspecialchars($AppParametres->verset_biblique) }}" - {{ htmlspecialchars($AppParametres->reference_verset) }}
            @else
                "Car Dieu a tant aimé le monde qu'il a donné son Fils unique..." - Jean 3:16
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
                Généré le {{ $dateGeneration }}
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