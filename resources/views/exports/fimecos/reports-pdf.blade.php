<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport FIMECO - {{ $rapport['informations_generales']['nom'] ?? 'Rapport Global' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #667eea;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }

        .grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .grid-item {
            display: table-cell;
            vertical-align: top;
            padding: 10px;
        }

        .grid-2 .grid-item {
            width: 50%;
        }

        .grid-3 .grid-item {
            width: 33.33%;
        }

        .grid-4 .grid-item {
            width: 25%;
        }

        .stat-card {
            background: white;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-bar {
            background: #e9ecef;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
            position: relative;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
        }

        .table th {
            background: #495057;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }

        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .table tbody tr:hover {
            background: #e9ecef;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary {
            background: #d6e9ff;
            color: #004085;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-small {
            font-size: 10px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-15 {
            margin-bottom: 15px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-line:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .footer {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }

        .page-break {
            page-break-before: always;
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 12px;
            opacity: 0.9;
        }

        .chart-placeholder {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #6c757d;
            font-style: italic;
        }

        .highlight-box {
            background: #fff;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .highlight-box.warning {
            border-color: #ffc107;
            background: #fff8e1;
        }

        .highlight-box.danger {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-active {
            background: #28a745;
        }

        .status-inactive {
            background: #6c757d;
        }

        .status-warning {
            background: #ffc107;
        }

        .status-danger {
            background: #dc3545;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
            }

            .section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- En-tête du rapport -->
    <div class="header">
        <h1>
            @if(isset($rapport['informations_generales']['nom']))
                Rapport FIMECO - {{ $rapport['informations_generales']['nom'] }}
            @else
                Rapport Global FIMECOs
            @endif
        </h1>
        <div class="subtitle">
            Généré le {{ $rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s') }}
            @if(isset($rapport['informations_generales']['periode']))
                <br>Période : {{ $rapport['informations_generales']['periode'] }}
            @endif
        </div>
    </div>

    <div class="container">
        @if(isset($rapport['informations_generales']))
            <!-- Résumé exécutif -->
            <div class="summary-box">
                <h3>Résumé Exécutif</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-value">{{ number_format($rapport['objectifs_et_resultats']['cible'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="summary-label">Objectif (FCFA)</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value">{{ number_format($rapport['objectifs_et_resultats']['montant_solde'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="summary-label">Collecté (FCFA)</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value">{{ number_format($rapport['objectifs_et_resultats']['progression'] ?? 0, 1) }}%</div>
                        <div class="summary-label">Progression</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0 }}</div>
                        <div class="summary-label">Souscriptions</div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($rapport['informations_generales']))
            <!-- Informations générales -->
            <div class="section">
                <h2 class="section-title">Informations Générales</h2>
                <div class="grid grid-2">
                    <div class="grid-item">
                        <div class="info-line">
                            <span class="info-label">Nom du FIMECO :</span>
                            <span class="info-value">{{ $rapport['informations_generales']['nom'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Responsable :</span>
                            <span class="info-value">{{ $rapport['informations_generales']['responsable'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Période :</span>
                            <span class="info-value">{{ $rapport['informations_generales']['periode'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="info-line">
                            <span class="info-label">Statut :</span>
                            <span class="info-value">
                                <span class="status-indicator status-{{ $rapport['informations_generales']['statut'] === 'active' ? 'active' : 'inactive' }}"></span>
                                {{ ucfirst($rapport['informations_generales']['statut'] ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Date de création :</span>
                            <span class="info-value">{{ $rapport['informations_generales']['date_creation'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if(isset($rapport['informations_generales']['description']) && $rapport['informations_generales']['description'])
                    <div class="mt-10">
                        <div class="info-label">Description :</div>
                        <div class="info-value mt-10">{{ $rapport['informations_generales']['description'] }}</div>
                    </div>
                @endif
            </div>
        @endif

        @if(isset($rapport['objectifs_et_resultats']))
            <!-- Objectifs et Résultats -->
            <div class="section">
                <h2 class="section-title">Objectifs et Résultats</h2>

                <!-- Barre de progression -->
                <div class="mb-15">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ min($rapport['objectifs_et_resultats']['progression'] ?? 0, 100) }}%">
                            <div class="progress-text">{{ number_format($rapport['objectifs_et_resultats']['progression'] ?? 0, 1) }}%</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-4">
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($rapport['objectifs_et_resultats']['cible'] ?? 0, 0, ',', ' ') }}</div>
                            <div class="stat-label">Objectif (FCFA)</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($rapport['objectifs_et_resultats']['montant_solde'] ?? 0, 0, ',', ' ') }}</div>
                            <div class="stat-label">Montant Collecté</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($rapport['objectifs_et_resultats']['reste'] ?? 0, 0, ',', ' ') }}</div>
                            <div class="stat-label">Reste à Collecter</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">
                                @if(($rapport['objectifs_et_resultats']['montant_supplementaire'] ?? 0) > 0)
                                    +{{ number_format($rapport['objectifs_et_resultats']['montant_supplementaire'], 0, ',', ' ') }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="stat-label">Montant Supplémentaire</div>
                        </div>
                    </div>
                </div>

                @if(($rapport['objectifs_et_resultats']['progression'] ?? 0) >= 100)
                    <div class="highlight-box">
                        <strong>🎉 Objectif Atteint !</strong><br>
                        Le FIMECO a atteint {{ number_format($rapport['objectifs_et_resultats']['progression'], 1) }}% de son objectif.
                        @if(($rapport['objectifs_et_resultats']['montant_supplementaire'] ?? 0) > 0)
                            Un montant supplémentaire de {{ number_format($rapport['objectifs_et_resultats']['montant_supplementaire'], 0, ',', ' ') }} FCFA a été collecté.
                        @endif
                    </div>
                @elseif(($rapport['objectifs_et_resultats']['progression'] ?? 0) >= 75)
                    <div class="highlight-box warning">
                        <strong>⚠️ Presque Atteint</strong><br>
                        Le FIMECO est à {{ number_format($rapport['objectifs_et_resultats']['progression'], 1) }}% de son objectif.
                        Il reste {{ number_format($rapport['objectifs_et_resultats']['reste'], 0, ',', ' ') }} FCFA à collecter.
                    </div>
                @elseif(($rapport['objectifs_et_resultats']['progression'] ?? 0) < 25)
                    <div class="highlight-box danger">
                        <strong>🚨 Progression Très Faible</strong><br>
                        Le FIMECO n'a atteint que {{ number_format($rapport['objectifs_et_resultats']['progression'], 1) }}% de son objectif.
                        Des actions urgentes sont nécessaires.
                    </div>
                @endif
            </div>
        @endif

        @if(isset($rapport['statistiques_souscriptions']))
            <!-- Statistiques des Souscriptions -->
            <div class="section">
                <h2 class="section-title">Statistiques des Souscriptions</h2>

                <div class="grid grid-4">
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0 }}</div>
                            <div class="stat-label">Total Souscriptions</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_actives'] ?? 0 }}</div>
                            <div class="stat-label">Actives</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_completes'] ?? 0 }}</div>
                            <div class="stat-label">Complètes</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_partielles'] ?? 0 }}</div>
                            <div class="stat-label">Partielles</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-2 mt-10">
                    <div class="grid-item">
                        <div class="info-line">
                            <span class="info-label">Montant total souscrit :</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_souscrit'] ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Montant total payé :</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_paye'] ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="info-line">
                            <span class="info-label">Progression moyenne :</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['progression_moyenne_souscriptions'] ?? 0, 1) }}%</span>
                        </div>
                        @if(($rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0) > 0)
                            <div class="info-line">
                                <span class="info-label">Souscriptions en retard :</span>
                                <span class="info-value" style="color: #dc3545; font-weight: bold;">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if(isset($rapport['souscriptions_detail']) && count($rapport['souscriptions_detail']) > 0)
            <!-- Détail des Souscriptions -->
            <div class="section page-break">
                <h2 class="section-title">Détail des Souscriptions</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="text-right">Montant Souscrit</th>
                            <th class="text-right">Montant Payé</th>
                            <th class="text-right">Reste à Payer</th>
                            <th class="text-center">Progression</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapport['souscriptions_detail'] as $souscription)
                            <tr>
                                <td class="font-bold">{{ $souscription['souscripteur'] ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($souscription['montant_souscrit'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($souscription['montant_paye'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($souscription['reste_a_payer'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-center">{{ number_format($souscription['progression'] ?? 0, 1) }}%</td>
                                <td class="text-center">
                                    @php
                                        $statut = $souscription['statut'] ?? 'inactive';
                                        $badgeClass = match($statut) {
                                            'completement_payee' => 'badge-success',
                                            'partiellement_payee' => 'badge-warning',
                                            default => 'badge-danger'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $statut)) }}</span>
                                </td>
                                <td class="text-center text-small">{{ $souscription['date_souscription'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(isset($rapport['analyses']))
            <!-- Analyses et Indicateurs -->
            <div class="section">
                <h2 class="section-title">Analyses et Indicateurs Clés</h2>

                <div class="grid grid-3">
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($rapport['analyses']['taux_reussite'] ?? 0, 1) }}%</div>
                            <div class="stat-label">Taux de Réussite</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($rapport['analyses']['duree_moyenne_paiement'] ?? 0, 0) }}</div>
                            <div class="stat-label">Durée Moy. Paiement (jours)</div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="stat-card">
                            <div class="stat-value">{{ count($rapport['analyses']['repartition_types_paiement'] ?? []) }}</div>
                            <div class="stat-label">Types de Paiement</div>
                        </div>
                    </div>
                </div>

                @if(isset($rapport['analyses']['repartition_types_paiement']) && count($rapport['analyses']['repartition_types_paiement']) > 0)
                    <div class="mt-10">
                        <h3 class="font-bold mb-10">Répartition par Type de Paiement</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type de Paiement</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-right">Montant Total (FCFA)</th>
                                    <th class="text-center">Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMontant = array_sum(array_column($rapport['analyses']['repartition_types_paiement'], 'total'));
                                    $totalCount = array_sum(array_column($rapport['analyses']['repartition_types_paiement'], 'count'));
                                @endphp
                                @foreach($rapport['analyses']['repartition_types_paiement'] as $type => $data)
                                    <tr>
                                        <td class="font-bold">{{ $data['libelle'] ?? ucfirst($type) }}</td>
                                        <td class="text-center">{{ $data['count'] ?? 0 }}</td>
                                        <td class="text-right">{{ number_format($data['total'] ?? 0, 0, ',', ' ') }}</td>
                                        <td class="text-center">
                                            {{ $totalMontant > 0 ? number_format(($data['total'] ?? 0) / $totalMontant * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

        @if(isset($rapport['paiements_detail']) && count($rapport['paiements_detail']) > 0)
            <!-- Historique des Paiements -->
            <div class="section page-break">
                <h2 class="section-title">Historique des Paiements (Derniers 20)</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="text-right">Montant</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Date</th>
                            <th>Validateur</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($rapport['paiements_detail'], 0, 20) as $paiement)
                            <tr>
                                <td class="font-bold">{{ $paiement['souscripteur'] ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($paiement['montant'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-center">{{ $paiement['type_paiement'] ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @php
                                        $statut = $paiement['statut'] ?? 'en_attente';
                                        $badgeClass = match($statut) {
                                            'Validé' => 'badge-success',
                                            'En attente de validation' => 'badge-warning',
                                            default => 'badge-danger'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $statut }}</span>
                                </td>
                                <td class="text-center text-small">{{ $paiement['date_paiement'] ?? 'N/A' }}</td>
                                <td class="text-small">{{ $paiement['validateur'] ?? '-' }}</td>
                                <td class="text-small">{{ $paiement['reference'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Recommandations -->
        <div class="section">
            <h2 class="section-title">Recommandations et Actions</h2>

            @php
                $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
                $nbSouscriptions = $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0;
                $tauxReussite = $rapport['analyses']['taux_reussite'] ?? 0;
                $souscriptionsEnRetard = $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0;
            @endphp

            <div class="grid grid-2">
                <div class="grid-item">
                    <h3 class="font-bold mb-10">Points Positifs</h3>
                    <ul style="list-style-type: disc; margin-left: 20px;">
                        @if($progression >= 75)
                            <li>Excellente progression vers l'objectif ({{ number_format($progression, 1) }}%)</li>
                        @endif
                        @if($tauxReussite >= 70)
                            <li>Bon taux de réussite des souscriptions ({{ number_format($tauxReussite, 1) }}%)</li>
                        @endif
                        @if($nbSouscriptions >= 10)
                            <li>Bonne participation avec {{ $nbSouscriptions }} souscriptions</li>
                        @endif
                        @if($souscriptionsEnRetard == 0)
                            <li>Aucune souscription en retard</li>
                        @endif
                    </ul>
                </div>

                <div class="grid-item">
                    <h3 class="font-bold mb-10">Points d'Amélioration</h3>
                    <ul style="list-style-type: disc; margin-left: 20px;">
                        @if($progression < 50)
                            <li style="color: #dc3545;">Progression faible - Intensifier la communication</li>
                        @endif
                        @if($tauxReussite < 50)
                            <li style="color: #dc3545;">Taux de réussite faible - Revoir la stratégie de suivi</li>
                        @endif
                        @if($souscriptionsEnRetard > 0)
                            <li style="color: #ffc107;">{{ $souscriptionsEnRetard }} souscription(s) en retard - Relancer les souscripteurs</li>
                        @endif
                        @if($nbSouscriptions < 5)
                            <li style="color: #ffc107;">Peu de souscriptions - Élargir la base de souscripteurs</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mt-10">
                <h3 class="font-bold mb-10">Actions Recommandées</h3>
                <div class="highlight-box">
                    @if($progression < 25)
                        <strong>🚨 Actions Urgentes :</strong><br>
                        • Organiser une campagne de sensibilisation intensive<br>
                        • Revoir la stratégie de communication<br>
                        • Proposer des facilités de paiement<br>
                        • Impliquer davantage les leaders communautaires
                    @elseif($progression < 75)
                        <strong>⚠️ Actions à Court Terme :</strong><br>
                        • Relancer les souscripteurs inactifs<br>
                        • Organiser des événements de mobilisation<br>
                        • Communiquer régulièrement sur l'avancement<br>
                        • Identifier de nouveaux souscripteurs potentiels
                    @else
                        <strong>✅ Actions de Suivi :</strong><br>
                        • Maintenir la dynamique actuelle<br>
                        • Préparer la clôture du FIMECO<br>
                        • Planifier la communication des résultats<br>
                        • Capitaliser sur ce succès pour futurs projets
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div class="grid grid-3">
            <div class="grid-item text-left">
                <strong>FIMECO</strong><br>
                Financement et Mobilisation Collective
            </div>
            <div class="grid-item text-center">
                Rapport généré automatiquement<br>
                {{ $rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s') }}
            </div>
            <div class="grid-item text-right">
                Page 1/1<br>
                Document confidentiel
            </div>
        </div>
    </div>
</body>
</html>
