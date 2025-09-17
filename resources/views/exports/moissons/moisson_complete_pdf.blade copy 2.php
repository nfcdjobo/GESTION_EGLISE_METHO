{{-- resources/views/exports/moissons/moisson_complete_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Moisson - {{ $donnees['informations_generales']['theme'] }}</title>
    <style>
        @page {
            margin: 10mm 8mm 12mm 8mm;
            @top-center {
                content: "{{ $donnees['informations_generales']['theme'] }}";
                font-size: 9px;
                color: #666;
                font-weight: bold;
            }
            @bottom-center {
                content: "Page " counter(page) " - {{ now()->format('d/m/Y H:i') }} - {{ config('app.church_name', 'Église') }}";
                font-size: 8px;
                color: #888;
                border-top: 1px solid #ddd;
                padding-top: 3px;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #2E74B5;
        }

        .logo-section {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .logo {
            width: 45px;
            height: 45px;
            margin-right: 12px;
            border-radius: 6px;
        }

        .church-info {
            text-align: left;
            flex: 1;
        }

        .church-name {
            font-size: 14px;
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 3px;
            line-height: 1.1;
        }

        .church-details {
            font-size: 8px;
            color: #666;
            line-height: 1.2;
        }

        .export-info {
            text-align: right;
            font-size: 8px;
            color: #666;
            flex: 0 0 auto;
            margin-left: 10px;
        }

        .main-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #2E74B5;
            margin: 12px 0 8px 0;
            padding: 12px 8px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e3f2fd 100%);
            border-radius: 6px;
            border: 2px solid #2E74B5;
        }

        .subtitle {
            font-size: 12px;
            font-weight: normal;
            color: #555;
            margin-top: 4px;
        }

        .performance-overview {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%);
            padding: 12px;
            border-radius: 6px;
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

        .performance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 8px;
            margin: 8px 0;
        }

        .performance-stat {
            text-align: center;
            background: white;
            padding: 6px 4px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }

        .performance-value {
            font-size: 11px;
            font-weight: bold;
            color: #1976D2;
            display: block;
            line-height: 1.1;
        }

        .performance-label {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
            line-height: 1.1;
        }

        .progress-container {
            margin: 8px 0;
        }

        .progress-bar {
            width: 100%;
            height: 16px;
            background-color: #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            border: 1px solid #ddd;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 9px;
            min-width: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 12px 0;
        }

        .info-card {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
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
            display: inline-block;
            width: 85px;
            font-size: 8px;
        }

        .info-content .value {
            font-size: 9px;
        }

        .passages-bibliques {
            background: #fff8e1;
            padding: 8px;
            border-radius: 4px;
            border-left: 3px solid #ff9800;
            margin: 8px 0;
        }

        .passages-title {
            font-weight: bold;
            color: #f57c00;
            margin-bottom: 4px;
            font-size: 9px;
        }

        .passages-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
        }

        .passage-item {
            background: white;
            padding: 4px 6px;
            border-radius: 3px;
            border: 1px solid #ffcc02;
            font-style: italic;
            font-size: 8px;
        }

        .section {
            margin: 15px 0 8px 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2E74B5;
            margin-bottom: 6px;
            padding: 6px 8px;
            background: linear-gradient(90deg, #f0f4f8 0%, transparent 100%);
            border-left: 4px solid #4472C4;
            border-radius: 0 4px 4px 0;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8px;
        }

        .detail-table th {
            background: linear-gradient(135deg, #4472C4 0%, #2E74B5 100%);
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #2E74B5;
            font-size: 7px;
            line-height: 1.2;
        }

        .detail-table td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            font-size: 8px;
            line-height: 1.2;
            vertical-align: top;
        }

        .detail-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .compact-table {
            font-size: 7px;
        }

        .compact-table th {
            padding: 4px 2px;
            font-size: 7px;
        }

        .compact-table td {
            padding: 3px 2px;
            font-size: 7px;
        }

        .status-badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }

        .status-actif {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactif {
            background-color: #f8d7da;
            color: #721c24;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            color: #999;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 6px;
            border: 2px dashed #ddd;
            font-size: 9px;
        }

        .page-break {
            page-break-before: always;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 8px;
        }

        .alert-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 6px 8px;
            margin: 6px 0;
            font-size: 8px;
        }

        .alert-danger {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .summary-box {
            background: #e3f2fd;
            padding: 8px;
            border-radius: 4px;
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
            line-height: 1.3;
        }

        .inline-stats {
            display: inline-block;
            margin-right: 15px;
            font-size: 8px;
        }

        .inline-stats .stat-value {
            font-weight: bold;
            color: #2E74B5;
        }

        /* Styles pour optimiser l'espace des tableaux */
        .narrow-col { width: 8%; }
        .medium-col { width: 12%; }
        .wide-col { width: 20%; }
        .contact-col { width: 10%; font-size: 7px; }

        /* Réduction des marges pour les sections */
        .compact-section {
            margin: 8px 0 4px 0;
        }

        .compact-section .section-title {
            margin-bottom: 4px;
            padding: 4px 6px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Header optimisé -->
    <div class="header">
        <div class="logo-section">
            @if(!empty(config('app.church_logo')) && file_exists(public_path(config('app.church_logo'))))
                <img src="{{ public_path(config('app.church_logo')) }}" alt="Logo" class="logo">
            @endif
            <div class="church-info">
                <div class="church-name">{{ config('app.church_name', 'Église Baptiste') }}</div>
                <div class="church-details">
                    {{ config('app.church_address', '') }}<br>
                    Tél: {{ config('app.church_phone', '') }} | Email: {{ config('app.church_email', '') }}
                </div>
            </div>
        </div>
        <div class="export-info">
            <strong>Export:</strong> {{ now()->format('d/m/Y H:i') }}<br>
            <strong>Statut:</strong> {{ $donnees['informations_generales']['statut'] }}
        </div>
    </div>

    <!-- Titre principal compact -->
    <div class="main-title">
        RAPPORT DÉTAILLÉ DE MOISSON
        <div class="subtitle">{{ $donnees['informations_generales']['theme'] }}</div>
    </div>

    <!-- Vue d'ensemble compacte -->
    <div class="performance-overview">
        <div class="performance-title">Performance de la Moisson</div>
        <div class="performance-grid">
            <div class="performance-stat">
                <span class="performance-value amount">{{ number_format($donnees['objectifs_et_realisations']['objectif_initial'], 0, ',', ' ') }}</span>
                <div class="performance-label">Objectif (FCFA)</div>
            </div>
            <div class="performance-stat">
                <span class="performance-value amount">{{ number_format($donnees['objectifs_et_realisations']['montant_collecte'], 0, ',', ' ') }}</span>
                <div class="performance-label">Collecté (FCFA)</div>
            </div>
            <div class="performance-stat">
                <span class="performance-value">{{ $donnees['objectifs_et_realisations']['pourcentage_realisation'] }}%</span>
                <div class="performance-label">Taux Réalisation</div>
            </div>
            <div class="performance-stat">
                <span class="performance-value">{{ $donnees['objectifs_et_realisations']['statut_progression'] }}</span>
                <div class="performance-label">Statut</div>
            </div>
        </div>

        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min($donnees['objectifs_et_realisations']['pourcentage_realisation'], 100) }}%;">
                    {{ $donnees['objectifs_et_realisations']['pourcentage_realisation'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Informations en deux colonnes compactes -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-title">Informations Générales</div>
            <div class="info-content">
                <div><span class="label">Date moisson:</span><span class="value">{{ $donnees['informations_generales']['date'] }}</span></div>
                <div><span class="label">Culte:</span><span class="value">{{ \Str::limit($donnees['informations_generales']['culte'], 20) }}</span></div>
                <div><span class="label">Créateur:</span><span class="value">{{ $donnees['informations_generales']['createur'] }}</span></div>
                <div><span class="label">Création:</span><span class="value">{{ $donnees['informations_generales']['date_creation'] }}</span></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-title">Résumé Financier</div>
            <div class="info-content">
                <div><span class="label">Reste:</span><span class="value amount">{{ number_format($donnees['objectifs_et_realisations']['reste_a_collecter'], 0, ',', ' ') }} F</span></div>
                <div><span class="label">Supplément:</span><span class="value amount">{{ number_format($donnees['objectifs_et_realisations']['montant_supplementaire'], 0, ',', ' ') }} F</span></div>
                <div><span class="label">Modifié:</span><span class="value">{{ $donnees['informations_generales']['derniere_modification'] }}</span></div>
            </div>
        </div>
    </div>

    <!-- Passages bibliques optimisés -->
    @if(!empty($donnees['passages_bibliques']))
    <div class="passages-bibliques">
        <div class="passages-title">Passages Bibliques de Référence</div>
        <div class="passages-grid">
            @foreach($donnees['passages_bibliques'] as $passage)
                <div class="passage-item">
                    @if(is_array($passage))
                        {{ $passage['livre'] ?? '' }} {{ $passage['chapitre'] ?? '' }}:{{ $passage['verset_debut'] ?? '' }}@if(!empty($passage['verset_fin']))-{{ $passage['verset_fin'] }}@endif
                    @else
                        {{ $passage }}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Résumé des activités -->
    <div class="summary-box">
        <div class="summary-title">Résumé des Activités</div>
        <div class="summary-content">
            <span class="inline-stats">Passages: <span class="stat-value">{{ count($donnees['detail_passages']) }}</span></span>
            <span class="inline-stats">Ventes: <span class="stat-value">{{ count($donnees['detail_ventes']) }}</span></span>
            <span class="inline-stats">Engagements: <span class="stat-value">{{ count($donnees['detail_engagements']) }}</span></span>
            @if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0)
                <span class="inline-stats" style="color: #dc3545;">En retard: <span class="stat-value">{{ collect($donnees['detail_engagements'])->where('en_retard', true)->count() }}</span></span>
            @endif
        </div>
    </div>

    <!-- Section Passages compacte -->
    <div class="compact-section">
        <div class="section-title">Détail des Passages ({{ count($donnees['detail_passages']) }})</div>

        @if(count($donnees['detail_passages']) > 0)
            <table class="detail-table compact-table">
                <thead>
                    <tr>
                        <th class="wide-col">Catégorie</th>
                        <th class="medium-col">Classe</th>
                        <th class="narrow-col">Objectif</th>
                        <th class="narrow-col">Collecté</th>
                        <th class="narrow-col">%</th>
                        <th class="medium-col">Collecteur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_passages'] as $passage)
                        <tr>
                            <td>{{ \Str::limit($passage['categorie'], 25) }}</td>
                            <td>{{ $passage['classe'] ?? 'N/A' }}</td>
                            <td class="text-right amount">{{ number_format($passage['objectif'], 0, ',', ' ') }}</td>
                            <td class="text-right amount">{{ number_format($passage['collecte'], 0, ',', ' ') }}</td>
                            <td class="text-center font-bold">{{ $passage['pourcentage'] }}%</td>
                            <td>{{ \Str::limit($passage['collecteur'], 15) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Aucun passage enregistré</div>
        @endif
    </div>

    <!-- Section Ventes compacte -->
    <div class="compact-section">
        <div class="section-title">Détail des Ventes ({{ count($donnees['detail_ventes']) }})</div>

        @if(count($donnees['detail_ventes']) > 0)
            <table class="detail-table compact-table">
                <thead>
                    <tr>
                        <th class="medium-col">Catégorie</th>
                        <th class="wide-col">Description</th>
                        <th class="narrow-col">Objectif</th>
                        <th class="narrow-col">Collecté</th>
                        <th class="narrow-col">%</th>
                        <th class="medium-col">Collecteur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_ventes'] as $vente)
                        <tr>
                            <td>{{ \Str::limit($vente['categorie'], 15) }}</td>
                            <td>{{ \Str::limit($vente['description'] ?? 'N/A', 30) }}</td>
                            <td class="text-right amount">{{ number_format($vente['objectif'], 0, ',', ' ') }}</td>
                            <td class="text-right amount">{{ number_format($vente['collecte'], 0, ',', ' ') }}</td>
                            <td class="text-center font-bold">{{ $vente['pourcentage'] }}%</td>
                            <td>{{ \Str::limit($vente['collecteur'], 15) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Aucune vente enregistrée</div>
        @endif
    </div>

    <!-- Section Engagements compacte -->
    <div class="compact-section">
        <div class="section-title">Détail des Engagements ({{ count($donnees['detail_engagements']) }})</div>

        @if(count($donnees['detail_engagements']) > 0)
            <table class="detail-table compact-table">
                <thead>
                    <tr>
                        <th class="medium-col">Type</th>
                        <th class="wide-col">Donateur</th>
                        <th class="narrow-col">Objectif</th>
                        <th class="narrow-col">Collecté</th>
                        <th class="narrow-col">%</th>
                        <th class="medium-col">Échéance</th>
                        <th class="contact-col">Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_engagements'] as $engagement)
                        <tr class="{{ $engagement['en_retard'] ? 'status-inactif' : '' }}">
                            <td>{{ \Str::limit($engagement['categorie'], 12) }}</td>
                            <td>{{ \Str::limit($engagement['donateur'], 20) }}</td>
                            <td class="text-right amount">{{ number_format($engagement['objectif'], 0, ',', ' ') }}</td>
                            <td class="text-right amount">{{ number_format($engagement['collecte'], 0, ',', ' ') }}</td>
                            <td class="text-center font-bold">{{ $engagement['pourcentage'] }}%</td>
                            <td class="text-center">{{ $engagement['date_echeance'] ?? 'N/A' }}</td>
                            <td class="contact-col">
                                @if($engagement['telephone']){{ \Str::limit($engagement['telephone'], 12) }}<br>@endif
                                @if($engagement['email']){{ \Str::limit($engagement['email'], 15) }}@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0)
                <div class="alert-box alert-danger">
                    <strong>⚠️ {{ collect($donnees['detail_engagements'])->where('en_retard', true)->count() }} engagement(s) en retard</strong> nécessitent un suivi urgent.
                </div>
            @endif
        @else
            <div class="no-data">Aucun engagement enregistré</div>
        @endif
    </div>
</body>
</html>
