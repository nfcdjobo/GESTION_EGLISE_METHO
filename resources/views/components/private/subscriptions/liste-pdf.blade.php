<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Souscriptions - Export PDF</title>
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
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #3B82F6;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #1E293B;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            color: #64748B;
            font-size: 14px;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
        }

        .meta-info .left,
        .meta-info .right {
            flex: 1;
        }

        .meta-info h3 {
            font-size: 14px;
            color: #374151;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .meta-info p {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            text-align: center;
            padding: 15px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: #F9FAFB;
        }

        .stat-card .number {
            font-size: 20px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .stat-card .label {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.primary .number { color: #3B82F6; }
        .stat-card.success .number { color: #10B981; }
        .stat-card.warning .number { color: #F59E0B; }
        .stat-card.danger .number { color: #EF4444; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }

        table th {
            background: #F8FAFC;
            color: #374151;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #E5E7EB;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 8px 6px;
            border: 1px solid #E5E7EB;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        table tbody tr:hover {
            background: #F3F4F6;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-complete {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-partial {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-inactive {
            background: #F3F4F6;
            color: #374151;
        }

        .progress-bar {
            width: 60px;
            height: 6px;
            background: #E5E7EB;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3B82F6, #8B5CF6);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .progress-fill.success {
            background: linear-gradient(90deg, #10B981, #059669);
        }

        .progress-fill.warning {
            background: linear-gradient(90deg, #F59E0B, #D97706);
        }

        .progress-fill.danger {
            background: linear-gradient(90deg, #EF4444, #DC2626);
        }

        .amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            text-align: right;
        }

        .amount.positive {
            color: #059669;
        }

        .amount.negative {
            color: #DC2626;
        }

        .amount.neutral {
            color: #6B7280;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #E5E7EB;
            background: #F8FAFC;
            font-size: 10px;
            color: #6B7280;
        }

        .page-break {
            page-break-before: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-small {
            font-size: 9px;
        }

        .text-xs {
            font-size: 8px;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-muted {
            color: #6B7280;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #9CA3AF;
            font-style: italic;
        }

        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
        }

        .summary-section h3 {
            font-size: 16px;
            color: #1F2937;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #D1D5DB;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #6B7280;
            font-size: 11px;
        }

        .summary-value {
            font-weight: bold;
            color: #1F2937;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Liste des Souscriptions FIMECO</h1>
        <p>Rapport généré le {{ \Carbon\Carbon::now()->locale('fr')->format('d F Y à H:i') }}</p>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <div class="left">
            <h3>Informations du rapport</h3>
            <p><strong>Période :</strong> {{ isset($filters['date_debut']) ? \Carbon\Carbon::parse($filters['date_debut'])->format('d/m/Y') : 'Toutes' }} - {{ isset($filters['date_fin']) ? \Carbon\Carbon::parse($filters['date_fin'])->format('d/m/Y') : 'Toutes' }}</p>
            <p><strong>Filtres appliqués :</strong> {{ count($filters ?? []) > 0 ? count($filters) . ' filtre(s)' : 'Aucun' }}</p>
            <p><strong>Total d'enregistrements :</strong> {{ count($data) }}</p>
        </div>
        <div class="right">
            <h3>Système FIMECO</h3>
            <p><strong>Organisation :</strong> {{ config('app.name', 'FIMECO System') }}</p>
            <p><strong>Version :</strong> 2.0</p>
            <p><strong>Contact :</strong> support@fimeco.com</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="number">{{ count($data) }}</div>
            <div class="label">Total Souscriptions</div>
        </div>
        <div class="stat-card success">
            <div class="number">{{ collect($data)->where('Statut', 'Complètement payée')->count() }}</div>
            <div class="label">Complétées</div>
        </div>
        <div class="stat-card warning">
            <div class="number">{{ collect($data)->where('Statut', 'Partiellement payée')->count() }}</div>
            <div class="label">Partielles</div>
        </div>
        <div class="stat-card danger">
            <div class="number">{{ collect($data)->where('En retard', 'Oui')->count() }}</div>
            <div class="label">En retard</div>
        </div>
    </div>

    <!-- Main Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%">Souscripteur</th>
                    <th style="width: 15%">FIMECO</th>
                    <th style="width: 10%">Date Souscription</th>
                    <th style="width: 10%">Date Échéance</th>
                    <th style="width: 12%">Montant Souscrit</th>
                    <th style="width: 12%">Montant Payé</th>
                    <th style="width: 8%">Progression</th>
                    <th style="width: 10%">Statut</th>
                    <th style="width: 6%">Paiements</th>
                    <th style="width: 5%">Retard</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $subscription)
                    <tr>
                        <td>
                            <div class="font-bold">{{ $subscription['Souscripteur'] ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="font-bold text-small">{{ Str::limit($subscription['FIMECO'] ?? 'N/A', 20) }}</div>
                        </td>
                        <td class="text-center">
                            {{ $subscription['Date souscription'] ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            {{ $subscription['Date échéance'] ?? '-' }}
                        </td>
                        <td class="amount neutral">
                            {{ number_format($subscription['Montant souscrit'] ?? 0, 0, ',', ' ') }}
                        </td>
                        <td class="amount positive">
                            {{ number_format($subscription['Montant payé'] ?? 0, 0, ',', ' ') }}
                        </td>
                        <td class="text-center">
                            <div class="progress-bar">
                                <div class="progress-fill {{ ($subscription['Progression (%)'] ?? 0) >= 100 ? 'success' : (($subscription['Progression (%)'] ?? 0) >= 75 ? '' : (($subscription['Progression (%)'] ?? 0) >= 50 ? 'warning' : 'danger')) }}"
                                     style="width: {{ min($subscription['Progression (%)'] ?? 0, 100) }}%"></div>
                            </div>
                            <div class="text-xs text-center" style="margin-top: 2px;">{{ number_format($subscription['Progression (%)'] ?? 0, 1) }}%</div>
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{
                                $subscription['Statut'] === 'Complètement payée' ? 'status-complete' :
                                ($subscription['Statut'] === 'Partiellement payée' ? 'status-partial' : 'status-inactive')
                            }}">
                                {{ $subscription['Statut'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{ $subscription['Nb paiements'] ?? 0 }}
                        </td>
                        <td class="text-center">
                            @if(($subscription['En retard'] ?? 'Non') === 'Oui')
                                <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">OUI</span>
                            @else
                                <span class="status-badge" style="background: #D1FAE5; color: #065F46;">NON</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Page break every 25 rows -->
                    @if(($index + 1) % 25 === 0 && $index < count($data) - 1)
                        </tbody>
                        </table>

                        <div class="page-break"></div>

                        <!-- Repeat header on new page -->
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 12%">Souscripteur</th>
                                    <th style="width: 15%">FIMECO</th>
                                    <th style="width: 10%">Date Souscription</th>
                                    <th style="width: 10%">Date Échéance</th>
                                    <th style="width: 12%">Montant Souscrit</th>
                                    <th style="width: 12%">Montant Payé</th>
                                    <th style="width: 8%">Progression</th>
                                    <th style="width: 10%">Statut</th>
                                    <th style="width: 6%">Paiements</th>
                                    <th style="width: 5%">Retard</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>Aucune donnée disponible</h3>
            <p>Aucune souscription ne correspond aux critères de filtrage spécifiés.</p>
        </div>
    @endif

    <!-- Summary Section -->
    @if(count($data) > 0)
        <div class="summary-section">
            <h3>Résumé des données</h3>
            <div class="summary-grid">
                <div>
                    <div class="summary-item">
                        <span class="summary-label">Total des souscriptions :</span>
                        <span class="summary-value">{{ count($data) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Souscriptions complètes :</span>
                        <span class="summary-value">{{ collect($data)->where('Statut', 'Complètement payée')->count() }} ({{ count($data) > 0 ? number_format((collect($data)->where('Statut', 'Complètement payée')->count() / count($data)) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Souscriptions partielles :</span>
                        <span class="summary-value">{{ collect($data)->where('Statut', 'Partiellement payée')->count() }} ({{ count($data) > 0 ? number_format((collect($data)->where('Statut', 'Partiellement payée')->count() / count($data)) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Souscriptions inactives :</span>
                        <span class="summary-value">{{ collect($data)->where('Statut', 'Inactive')->count() }} ({{ count($data) > 0 ? number_format((collect($data)->where('Statut', 'Inactive')->count() / count($data)) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Souscriptions en retard :</span>
                        <span class="summary-value">{{ collect($data)->where('En retard', 'Oui')->count() }} ({{ count($data) > 0 ? number_format((collect($data)->where('En retard', 'Oui')->count() / count($data)) * 100, 1) : 0 }}%)</span>
                    </div>
                </div>
                <div>
                    <div class="summary-item">
                        <span class="summary-label">Montant total souscrit :</span>
                        <span class="summary-value">{{ number_format(collect($data)->sum('Montant souscrit'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Montant total payé :</span>
                        <span class="summary-value">{{ number_format(collect($data)->sum('Montant payé'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Reste à collecter :</span>
                        <span class="summary-value">{{ number_format(collect($data)->sum('Montant souscrit') - collect($data)->sum('Montant payé'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Progression moyenne :</span>
                        <span class="summary-value">{{ count($data) > 0 ? number_format(collect($data)->avg('Progression (%)'), 1) : 0 }}%</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Nombre total de paiements :</span>
                        <span class="summary-value">{{ collect($data)->sum('Nb paiements') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>
            Rapport généré automatiquement par le système FIMECO -
            {{ \Carbon\Carbon::now()->locale('fr')->format('d F Y à H:i') }} -
            Page <span class="pagenum"></span>
        </p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 520;
            $y = 820;
            $text = "Page {PAGE_NUM} sur {PAGE_COUNT}";
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $size = 9;
            $color = array(0.5, 0.5, 0.5);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>
</html>
