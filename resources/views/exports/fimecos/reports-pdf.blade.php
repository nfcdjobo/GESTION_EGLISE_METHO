@if(isset($rapport['statistiques_souscriptions']))
        <!-- Statistiques des Souscriptions et Analyses combinées -->
        <div class="inline-sections">
            <div class="inline-section">
                <h2 class="section-title statistiques">Statistiques des Souscriptions</h2>

                <div class="kpis-grid">
                    <div class="kpi-card blue">
                        <div class="kpi-label">TOTAL</div>
                        <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-card green">
                        <div class="kpi-label">ACTIVES</div>
                        <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_actives'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="kpis-grid">
                    <div class="kpi-card purple">
                        <div class="kpi-label">COMPLÈTES</div>
                        <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_completes'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-card amber">
                        <div class="kpi-label">PARTIELLES</div>
                        <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_partielles'] ?? 0 }}</div>
                    </div>
                </div>
                @if(($rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0) > 0)
                <div class="kpis-grid">
                    <div class="kpi-card red">
                        <div class="kpi-label">EN RETARD</div>
                        <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] }}</div>
                    </div>
                </div>
                @endif

                <div class="info-grid">
                    <div class="info-column">
                        <div class="info-line">
                            <span class="info-label">Montant souscrit:</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_souscrit'] ?? 0, 0, ',', ' ') }}</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Montant payé:</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_paye'] ?? 0, 0, ',', ' ') }}</span>
                        </div>
                    </div>
                    <div class="info-column">
                        <div class="info-line">
                            <span class="info-label">Progression moy.:</span>
                            <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['progression_moyenne_souscriptions'] ?? 0, 1) }}%</span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Taux réussite:</span>
                            <span class="info-value">{{ number_format($rapport['analyses']['taux_reussite'] ?? 0, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($rapport['analyses']))
            <div class="inline-section">
                <h2 class="section-title analyses">Analyses et Indicateurs</h2>

                <div class="kpis-grid">
                    <div class="kpi-card green">
                        <div class="kpi-label">TAUX RÉUSSITE</div>
                        <div class="kpi-value">{{ number_format($rapport['analyses']['taux_reussite'] ?? 0, 1) }}%</div>
                    </div>
                    <div class="kpi-card blue">
                        <div class="kpi-label">DURÉE MOY.</div>
                        <div class="kpi-value">{{ number_format($rapport['analyses']['duree_moyenne_paiement'] ?? 0, 0) }}j</div>
                    </div>
                </div>

                @if(isset($rapport['analyses']['repartition_types_paiement']) && count($rapport['analyses']['repartition_types_paiement']) > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Type Paiement</th>
                                    <th class="center">Nb</th>
                                    <th class="number">Montant</th>
                                    <th class="center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMontant = array_sum(array_column($rapport['analyses']['repartition_types_paiement'], 'total'));
                                @endphp
                                @foreach($rapport['analyses']['repartition_types_paiement'] as $type => $data)
                                    <tr>
                                        <td><strong>{{ $data['libelle'] ?? ucfirst($type) }}</strong></td>
                                        <td class="center">{{ $data['count'] ?? 0 }}</td>
                                        <td class="number">{{ number_format($data['total'] ?? 0, 0, ',', ' ') }}</td>
                                        <td class="center">
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
        </div>
    @endif

    @if(isset($rapport['souscriptions_detail']) && count($rapport['souscriptions_detail']) > 0)
        <!-- Détail des Souscriptions -->
        <div class="compact-section avoid-break">
            <h2 class="section-title souscriptions">Détail des Souscriptions</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="number">Souscrit</th>
                            <th class="number">Payé</th>
                            <th class="number">Reste</th>
                            <th class="center">%</th>
                            <th class="center">Statut</th>
                            <th class="center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapport['souscriptions_detail'] as $souscription)
                            <tr>
                                <td><strong>{{ htmlspecialchars($souscription['souscripteur'] ?? 'N/A') }}</strong></td>
                                <td class="number">{{ number_format($souscription['montant_souscrit'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="number">{{ number_format($souscription['montant_paye'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="number">{{ number_format($souscription['reste_a_payer'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="center">{{ number_format($souscription['progression'] ?? 0, 1) }}%</td>
                                <td class="center">
                                    @php
                                        $statut = $souscription['statut'] ?? 'inactive';
                                        $badgeClass = match($statut) {
                                            'completement_payee' => 'status-complete',
                                            'partiellement_payee' => 'status-partielle',
                                            default => 'status-inactive'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                    </span>
                                </td>
                                <td class="center">{{ $souscription['date_souscription'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(isset($rapport['paiements_detail']) && count($rapport['paiements_detail']) > 0)
        <!-- Historique des Paiements -->
        <div class="compact-section avoid-break">
            <h2 class="section-title paiements">Historique des Paiements (Derniers 15)</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="number">Montant</th>
                            <th class="center">Type</th>
                            <th class="center">Statut</th>
                            <th class="center">Date</th>
                            <th>Validateur</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($rapport['paiements_detail'], 0, 15) as $paiement)
                            <tr>
                                <td><strong>{{ htmlspecialchars($paiement['souscripteur'] ?? 'N/A') }}</strong></td>
                                <td class="number">{{ number_format($paiement['montant'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="center">{{ htmlspecialchars($paiement['type_paiement'] ?? 'N/A') }}</td>
                                <td class="center">
                                    @php
                                        $statut = $paiement['statut'] ?? 'en_attente';
                                        $badgeClass = match($statut) {
                                            'Validé' => 'status-valide',
                                            'En attente de validation' => 'status-attente',
                                            default => 'status-rejete'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $statut }}</span>
                                </td>
                                <td class="center">{{ $paiement['date_paiement'] ?? 'N/A' }}</td>
                                <td>{{ htmlspecialchars($paiement['validateur'] ?? '-') }}</td>
                                <td>{{ htmlspecialchars($paiement['reference'] ?? '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recommandations et Résumé combinés -->
    <div class="inline-sections">
        <div class="inline-section">
            <h2 class="section-title recommandations">Recommandations</h2>

            @php
                $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
                $nbSouscriptions = $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0;
                $tauxReussite = $rapport['analyses']['taux_reussite'] ?? 0;
                $souscriptionsEnRetard = $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0;
            @endphp

            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 3px; padding: 6px; margin-bottom: 6px;">
                <strong style="color: #065f46; font-size: 8px;">Points Positifs</strong>
                <div style="margin-top: 4px; font-size: 7px; color: #047857;">
                    @if($progression >= 75)
                        • Excellente progression ({{ number_format($progression, 1) }}%)<br>
                    @endif
                    @if($tauxReussite >= 70)
                        • Bon taux de réussite ({{ number_format($tauxReussite, 1) }}%)<br>
                    @endif
                    @if($nbSouscriptions >= 10)
                        • Bonne participation ({{ $nbSouscriptions }} souscriptions)<br>
                    @endif
                    @if($souscriptionsEnRetard == 0)
                        • Aucune souscription en retard<br>
                    @endif
                    @if($progression < 75 && $tauxReussite < 70 && $nbSouscriptions < 10)
                        • Projet en développement<br>
                        • Potentiel d'amélioration identifié
                    @endif
                </div>
            </div>

            <div style="background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 3px; padding: 6px; margin-bottom: 6px;">
                <strong style="color: #92400e; font-size: 8px;">Points d'Amélioration</strong>
                <div style="margin-top: 4px; font-size: 7px; color: #b45309;">
                    @if($progression < 50)
                        • Progression faible - Intensifier communication<br>
                    @endif
                    @if($tauxReussite < 50)
                        • Taux faible - Revoir stratégie de suivi<br>
                    @endif
                    @if($souscriptionsEnRetard > 0)
                        • {{ $souscriptionsEnRetard }} en retard - Relancer<br>
                    @endif
                    @if($nbSouscriptions < 5)
                        • Peu de souscriptions - Élargir base<br>
                    @endif
                    @if($progression >= 50 && $tauxReussite >= 50 && $souscriptionsEnRetard == 0 && $nbSouscriptions >= 5)
                        • Maintenir dynamique actuelle<br>
                        • Optimiser processus existants
                    @endif
                </div>
            </div>

            @if($progression < 25)
                <div class="alert-box alert-danger">
                    <strong>Actions Urgentes :</strong> Campagne intensive, révision communication, facilités paiement.
                </div>
            @elseif($progression < 75)
                <div class="alert-box alert-warning">
                    <strong>Actions Court Terme :</strong> Relancer inactifs, événements mobilisation, communication régulière.
                </div>
            @else
                <div class="alert-box alert-success">
                    <strong>Actions Suivi :</strong> Maintenir dynamique, préparer clôture, capitaliser succès.
                </div>
            @endif
        </div>

        <div class="inline-section">
            <h2 class="section-title">Résumé du FIMECO</h2>
            <div class="summary-box">
                <div style="font-size: 7px; line-height: 1.3;">
                    <p style="margin: 0 0 4px 0;">
                        <strong>Vue d'ensemble:</strong>
                        @if(isset($rapport['informations_generales']['nom']))
                            Le FIMECO "{{ $rapport['informations_generales']['nom'] }}"
                        @else
                            Ce rapport global de FIMECOs
                        @endif
                        présente un état
                        @if($progression >= 75)
                            satisfaisant avec {{ number_format($progression, 1) }}% de progression.
                        @elseif($progression >= 50)
                            correct avec {{ number_format($progression, 1) }}% de progression.
                        @else
                            nécessitant des actions avec {{ number_format($progression, 1) }}% seulement.
                        @endif
                    </p>

                    @if(isset($rapport['statistiques_souscriptions']['nb_souscriptions_total']) && $rapport['statistiques_souscriptions']['nb_souscriptions_total'] > 0)
                    <p style="margin: 0 0 4px 0;">
                        <strong>Participation:</strong> {{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] }} souscription(s)
                        @if(isset($rapport['statistiques_souscriptions']['nb_souscriptions_completes']))
                            dont {{ $rapport['statistiques_souscriptions']['nb_souscriptions_completes'] }} complète(s)
                        @endif
                        @if(isset($rapport['statistiques_souscriptions']['nb_souscriptions_partielles']))
                            et {{ $rapport['statistiques_souscriptions']['nb_souscriptions_partielles'] }} partielle(s)
                        @endif
                        .
                    </p>
                    @endif

                    @if(isset($rapport['objectifs_et_resultats']['montant_solde']) && $rapport['objectifs_et_resultats']['montant_solde'] > 0)
                    <p style="margin: 0 0 4px 0;">
                        <strong>Finances:</strong> {{ number_format($rapport['objectifs_et_resultats']['montant_solde'], 0, ',', ' ') }} FCFA collecté
                        @if(isset($rapport['objectifs_et_resultats']['cible']))
                            sur {{ number_format($rapport['objectifs_et_resultats']['cible'], 0, ',', ' ') }} FCFA
                        @endif
                        .
                    </p>
                    @endif

                    @if(isset($rapport['informations_generales']['responsable']))
                    <p style="margin: 0 0 4px 0;">
                        <strong>Responsable:</strong> {{ htmlspecialchars($rapport['informations_generales']['responsable']) }}.
                    </p>
                    @endif

                    <p style="margin: 0;">
                        <strong>Généré:</strong> {{ $rapport['date_generation'] ?? now()->format('d/m/Y à H:i') }}.
                    </p>
                </div>
            </div>
        </div>
    </div><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport FIMECO - {{ $rapport['informations_generales']['nom'] ?? 'Rapport Global' }}</title>
    <style>
        @page {
            margin: 0.5cm;
            size: A4 portrait;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 8px;
            line-height: 1.2;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #6b7280;
            font-size: 9px;
            margin: 0;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: auto;
        }

        .section-title {
            background-color: #667eea;
            color: white;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: bold;
            margin: 0 0 8px 0;
            border-radius: 2px;
        }

        .section-title.info {
            background-color: #3b82f6;
        }

        .section-title.objectifs {
            background-color: #059669;
        }

        .section-title.statistiques {
            background-color: #7c3aed;
        }

        .section-title.souscriptions {
            background-color: #dc2626;
        }

        .section-title.analyses {
            background-color: #f59e0b;
        }

        .section-title.paiements {
            background-color: #6366f1;
        }

        .section-title.recommandations {
            background-color: #10b981;
        }

        .kpis-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }

        .kpi-card {
            flex: 1;
            min-width: 100px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            padding: 6px;
            text-align: center;
        }

        .kpi-label {
            font-size: 7px;
            color: #6b7280;
            margin-bottom: 2px;
            font-weight: 500;
        }

        .kpi-value {
            font-size: 10px;
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

        .kpi-card.red .kpi-value {
            color: #dc2626;
        }

        .table-container {
            margin-bottom: 8px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            background-color: white;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 4px 3px;
            border: 1px solid #d1d5db;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 3px 3px;
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
            bottom: 0.3cm;
            left: 0.5cm;
            right: 0.5cm;
            text-align: center;
            font-size: 6px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 3px;
        }

        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-complete {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-partielle {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-valide {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-attente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-rejete {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 3px;
            padding: 6px;
            margin: 5px 0;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
            margin: 5px 0;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #059669, #10b981);
            border-radius: 5px;
            transition: width 0.3s ease;
            position: relative;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 7px;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
        }

        .progress-fill.warning {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .progress-fill.danger {
            background: linear-gradient(90deg, #dc2626, #ef4444);
        }

        .executive-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 12px;
            text-align: center;
        }

        .executive-summary h3 {
            font-size: 12px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .executive-grid {
            display: flex;
            gap: 15px;
            justify-content: space-around;
        }

        .executive-item {
            text-align: center;
            flex: 1;
        }

        .executive-value {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .executive-label {
            font-size: 7px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .alert-box {
            padding: 6px;
            border-radius: 3px;
            margin: 6px 0;
            font-size: 8px;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-warning {
            background-color: #fefce8;
            border: 1px solid #fde047;
            color: #92400e;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .info-grid {
            display: flex;
            gap: 10px;
        }

        .info-column {
            flex: 1;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-line:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 7px;
        }

        .info-value {
            color: #6b7280;
            font-size: 7px;
        }

        .two-column {
            display: flex;
            gap: 10px;
        }

        .column {
            flex: 1;
        }

        .compact-section {
            margin-bottom: 10px;
        }

        .inline-sections {
            display: flex;
            gap: 15px;
        }

        .inline-section {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RAPPORT FIMECO - FINANCEMENT ET MOBILISATION COLLECTIVE</h1>
        <p class="subtitle">
            @if(isset($rapport['informations_generales']['nom']))
                {{ htmlspecialchars($rapport['informations_generales']['nom']) }}
            @else
                Rapport Global FIMECOs
            @endif
            - Généré le {{ $rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s') }}
        </p>
    </div>

    @if(isset($rapport['informations_generales']))
        <!-- Résumé exécutif -->
        <div class="executive-summary">
            <h3>Résumé Exécutif</h3>
            <div class="executive-grid">
                <div class="executive-item">
                    <div class="executive-value">{{ number_format($rapport['objectifs_et_resultats']['cible'] ?? 0, 0, ',', ' ') }}</div>
                    <div class="executive-label">Objectif (FCFA)</div>
                </div>
                <div class="executive-item">
                    <div class="executive-value">{{ number_format($rapport['objectifs_et_resultats']['montant_solde'] ?? 0, 0, ',', ' ') }}</div>
                    <div class="executive-label">Collecté (FCFA)</div>
                </div>
                <div class="executive-item">
                    <div class="executive-value">{{ number_format($rapport['objectifs_et_resultats']['progression'] ?? 0, 1) }}%</div>
                    <div class="executive-label">Progression</div>
                </div>
                <div class="executive-item">
                    <div class="executive-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0 }}</div>
                    <div class="executive-label">Souscriptions</div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($rapport['informations_generales']))
        <!-- Informations générales et Objectifs combinés -->
        <div class="inline-sections">
            <div class="inline-section">
                <h2 class="section-title info">Informations Générales</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du FIMECO</th>
                                <th>Responsable</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>{{ htmlspecialchars($rapport['informations_generales']['nom'] ?? 'N/A') }}</strong></td>
                                <td>{{ htmlspecialchars($rapport['informations_generales']['responsable'] ?? 'N/A') }}</td>
                                <td class="center">
                                    <span class="status-badge status-{{ $rapport['informations_generales']['statut'] === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($rapport['informations_generales']['statut'] ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-grid">
                    <div class="info-column">
                        <div class="info-line">
                            <span class="info-label">Période:</span>
                            <span class="info-value">{{ htmlspecialchars($rapport['informations_generales']['periode'] ?? 'N/A') }}</span>
                        </div>
                    </div>
                    <div class="info-column">
                        <div class="info-line">
                            <span class="info-label">Date création:</span>
                            <span class="info-value">{{ htmlspecialchars($rapport['informations_generales']['date_creation'] ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>

                @if(isset($rapport['informations_generales']['description']) && $rapport['informations_generales']['description'])
                <div class="summary-box">
                    <strong>Description:</strong>
                    <div style="margin-top: 3px; font-size: 7px;">{{ nl2br(htmlspecialchars(strip_tags($rapport['informations_generales']['description']))) }}</div>
                </div>
                @endif
            </div>

            @if(isset($rapport['objectifs_et_resultats']))
            <div class="inline-section">
                <h2 class="section-title objectifs">Objectifs et Résultats</h2>

                @php
                    $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
                    $progressClass = $progression >= 75 ? '' : ($progression >= 50 ? 'warning' : 'danger');
                @endphp
                <div class="progress-bar">
                    <div class="progress-fill {{ $progressClass }}" style="width: {{ min($progression, 100) }}%">
                        <div class="progress-text">{{ number_format($progression, 1) }}%</div>
                    </div>
                </div>

                <div class="kpis-grid">
                    <div class="kpi-card blue">
                        <div class="kpi-label">OBJECTIF</div>
                        <div class="kpi-value">{{ number_format($rapport['objectifs_et_resultats']['cible'] ?? 0, 0, ',', ' ') }}</div>
                    </div>
                    <div class="kpi-card green">
                        <div class="kpi-label">COLLECTÉ</div>
                        <div class="kpi-value">{{ number_format($rapport['objectifs_et_resultats']['montant_solde'] ?? 0, 0, ',', ' ') }}</div>
                    </div>
                </div>

                <div class="kpis-grid">
                    <div class="kpi-card amber">
                        <div class="kpi-label">RESTE</div>
                        <div class="kpi-value">{{ number_format($rapport['objectifs_et_resultats']['reste'] ?? 0, 0, ',', ' ') }}</div>
                    </div>
                    @if(($rapport['objectifs_et_resultats']['montant_supplementaire'] ?? 0) > 0)
                    <div class="kpi-card purple">
                        <div class="kpi-label">BONUS</div>
                        <div class="kpi-value">+{{ number_format($rapport['objectifs_et_resultats']['montant_supplementaire'], 0, ',', ' ') }}</div>
                    </div>
                    @endif
                </div>

                @if($progression >= 100)
                    <div class="alert-box alert-success">
                        <strong>Objectif Atteint !</strong> {{ number_format($progression, 1) }}% de réussite.
                    </div>
                @elseif($progression >= 75)
                    <div class="alert-box alert-warning">
                        <strong>Presque Atteint</strong> {{ number_format($progression, 1) }}% - Il reste {{ number_format($rapport['objectifs_et_resultats']['reste'], 0, ',', ' ') }} FCFA.
                    </div>
                @elseif($progression < 25)
                    <div class="alert-box alert-danger">
                        <strong>Progression Faible</strong> Seulement {{ number_format($progression, 1) }}% - Actions urgentes nécessaires.
                    </div>
                @endif
            </div>
            @endif
        </div>
    @endif

    @if(isset($rapport['statistiques_souscriptions']))
        <!-- Statistiques des Souscriptions -->
        <div class="section">
            <h2 class="section-title statistiques">Statistiques des Souscriptions</h2>

            <div class="kpis-grid">
                <div class="kpi-card blue">
                    <div class="kpi-label">TOTAL SOUSCRIPTIONS</div>
                    <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0 }}</div>
                </div>
                <div class="kpi-card green">
                    <div class="kpi-label">ACTIVES</div>
                    <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_actives'] ?? 0 }}</div>
                </div>
                <div class="kpi-card purple">
                    <div class="kpi-label">COMPLÈTES</div>
                    <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_completes'] ?? 0 }}</div>
                </div>
                <div class="kpi-card amber">
                    <div class="kpi-label">PARTIELLES</div>
                    <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_partielles'] ?? 0 }}</div>
                </div>
                @if(($rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] ?? 0) > 0)
                <div class="kpi-card red">
                    <div class="kpi-label">EN RETARD</div>
                    <div class="kpi-value">{{ $rapport['statistiques_souscriptions']['nb_souscriptions_en_retard'] }}</div>
                </div>
                @endif
            </div>

            <div class="info-grid">
                <div class="info-column">
                    <div class="info-line">
                        <span class="info-label">Montant total souscrit:</span>
                        <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_souscrit'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="info-line">
                        <span class="info-label">Montant total payé:</span>
                        <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['montant_total_paye'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                <div class="info-column">
                    <div class="info-line">
                        <span class="info-label">Progression moyenne:</span>
                        <span class="info-value">{{ number_format($rapport['statistiques_souscriptions']['progression_moyenne_souscriptions'] ?? 0, 1) }}%</span>
                    </div>
                    <div class="info-line">
                        <span class="info-label">Taux de réussite:</span>
                        <span class="info-value">{{ number_format($rapport['analyses']['taux_reussite'] ?? 0, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($rapport['souscriptions_detail']) && count($rapport['souscriptions_detail']) > 0)
        <!-- Détail des Souscriptions -->
        <div class="section page-break">
            <h2 class="section-title souscriptions">Détail des Souscriptions</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="number">Montant Souscrit</th>
                            <th class="number">Montant Payé</th>
                            <th class="number">Reste à Payer</th>
                            <th class="center">Progression</th>
                            <th class="center">Statut</th>
                            <th class="center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapport['souscriptions_detail'] as $souscription)
                            <tr>
                                <td><strong>{{ htmlspecialchars($souscription['souscripteur'] ?? 'N/A') }}</strong></td>
                                <td class="number">{{ number_format($souscription['montant_souscrit'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="number">{{ number_format($souscription['montant_paye'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="number">{{ number_format($souscription['reste_a_payer'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="center">{{ number_format($souscription['progression'] ?? 0, 1) }}%</td>
                                <td class="center">
                                    @php
                                        $statut = $souscription['statut'] ?? 'inactive';
                                        $badgeClass = match($statut) {
                                            'completement_payee' => 'status-complete',
                                            'partiellement_payee' => 'status-partielle',
                                            default => 'status-inactive'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                    </span>
                                </td>
                                <td class="center">{{ $souscription['date_souscription'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(isset($rapport['analyses']))
        <!-- Analyses et Indicateurs -->
        <div class="section">
            <h2 class="section-title analyses">Analyses et Indicateurs Clés</h2>

            <div class="kpis-grid">
                <div class="kpi-card green">
                    <div class="kpi-label">TAUX DE RÉUSSITE</div>
                    <div class="kpi-value">{{ number_format($rapport['analyses']['taux_reussite'] ?? 0, 1) }}%</div>
                </div>
                <div class="kpi-card blue">
                    <div class="kpi-label">DURÉE MOY. PAIEMENT</div>
                    <div class="kpi-value">{{ number_format($rapport['analyses']['duree_moyenne_paiement'] ?? 0, 0) }} jours</div>
                </div>
                <div class="kpi-card purple">
                    <div class="kpi-label">TYPES DE PAIEMENT</div>
                    <div class="kpi-value">{{ count($rapport['analyses']['repartition_types_paiement'] ?? []) }}</div>
                </div>
            </div>

            @if(isset($rapport['analyses']['repartition_types_paiement']) && count($rapport['analyses']['repartition_types_paiement']) > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Type de Paiement</th>
                                <th class="center">Nombre</th>
                                <th class="number">Montant Total (FCFA)</th>
                                <th class="center">Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalMontant = array_sum(array_column($rapport['analyses']['repartition_types_paiement'], 'total'));
                            @endphp
                            @foreach($rapport['analyses']['repartition_types_paiement'] as $type => $data)
                                <tr>
                                    <td><strong>{{ $data['libelle'] ?? ucfirst($type) }}</strong></td>
                                    <td class="center">{{ $data['count'] ?? 0 }}</td>
                                    <td class="number">{{ number_format($data['total'] ?? 0, 0, ',', ' ') }}</td>
                                    <td class="center">
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
            <h2 class="section-title paiements">Historique des Paiements (Derniers 20)</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Souscripteur</th>
                            <th class="number">Montant</th>
                            <th class="center">Type</th>
                            <th class="center">Statut</th>
                            <th class="center">Date</th>
                            <th>Validateur</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($rapport['paiements_detail'], 0, 20) as $paiement)
                            <tr>
                                <td><strong>{{ htmlspecialchars($paiement['souscripteur'] ?? 'N/A') }}</strong></td>
                                <td class="number">{{ number_format($paiement['montant'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="center">{{ htmlspecialchars($paiement['type_paiement'] ?? 'N/A') }}</td>
                                <td class="center">
                                    @php
                                        $statut = $paiement['statut'] ?? 'en_attente';
                                        $badgeClass = match($statut) {
                                            'Validé' => 'status-valide',
                                            'En attente de validation' => 'status-attente',
                                            default => 'status-rejete'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $statut }}</span>
                                </td>
                                <td class="center">{{ $paiement['date_paiement'] ?? 'N/A' }}</td>
                                <td>{{ htmlspecialchars($paiement['validateur'] ?? '-') }}</td>
                                <td>{{ htmlspecialchars($paiement['reference'] ?? '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recommandations -->
    <div class="section">
        <h2 class="section-title recommandations">Recommandations et Actions</h2>

        @php
            $progression = $rapport['objectifs_et_resultats']['progression'] ?? 0;
            $nbSouscriptions = $rapport['statistiques_souscriptions']['nb_souscriptions_total'] ?? 0;
            $tauxReussite =     <!-- Pied de page -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>Généré automatiquement par le système de gestion FIMECO</div>
            <div>{{ $rapport['date_generation'] ?? now()->format('d/m/Y à H:i:s') }}</div>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 7;
            $pageText = "Page " . $PAGE_NUM . " sur " . $PAGE_COUNT;
            $y = $pdf->get_height() - 40;
            $x = $pdf->get_width() - 80;
            $pdf->text($x, $y, $pageText, $font, $size);
        }
    </script>
</body>
</html>
