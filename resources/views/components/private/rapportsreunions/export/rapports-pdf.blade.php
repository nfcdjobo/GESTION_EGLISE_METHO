<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Rapports de Réunions</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #374151;
            background: #ffffff;
        }

        .cover-page {
            text-align: center;
            padding: 100px 50px;
            page-break-after: always;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .cover-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .cover-subtitle {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .cover-stats {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            backdrop-filter: blur(10px);
        }

        .cover-stats-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px;
        }

        .cover-stat-row {
            display: table-row;
        }

        .cover-stat-cell {
            display: table-cell;
            text-align: center;
            width: 25%;
        }

        .cover-stat-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .cover-stat-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cover-footer {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            opacity: 0.7;
        }

        .table-of-contents {
            page-break-after: always;
            padding: 50px;
        }

        .toc-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .toc-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #d1d5db;
        }

        .toc-title-item {
            font-weight: 500;
        }

        .toc-page {
            color: #6b7280;
        }

        .rapport-section {
            page-break-before: always;
            margin-bottom: 50px;
        }

        .rapport-header {
            background: linear-gradient(90deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #6366f1;
            margin-bottom: 20px;
        }

        .rapport-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }

        .rapport-subtitle {
            font-size: 11px;
            color: #6b7280;
        }

        .rapport-meta {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .rapport-meta-row {
            display: table-row;
        }

        .rapport-meta-cell {
            display: table-cell;
            padding: 8px 12px;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
            vertical-align: top;
            width: 25%;
        }

        .rapport-meta-cell:last-child {
            border-right: none;
        }

        .rapport-meta-row:nth-child(even) .rapport-meta-cell {
            background: #ffffff;
        }

        .meta-label {
            font-weight: 600;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .meta-value {
            font-size: 9px;
            color: #111827;
        }

        .content-section {
            margin-bottom: 15px;
            break-inside: avoid;
        }

        .content-title {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            padding: 5px 8px;
            background: #f3f4f6;
            border-radius: 4px;
        }

        .content-text {
            font-size: 9px;
            line-height: 1.5;
            text-align: justify;
            padding: 0 8px;
        }

        .points-list {
            list-style: none;
            padding: 0 8px;
        }

        .points-list li {
            font-size: 9px;
            padding: 3px 0;
            position: relative;
            padding-left: 15px;
        }

        .points-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #6366f1;
            font-weight: bold;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-brouillon { background: #f3f4f6; color: #374151; }
        .status-en_revision { background: #fef3c7; color: #92400e; }
        .status-valide { background: #dbeafe; color: #1e40af; }
        .status-publie { background: #dcfce7; color: #166534; }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 8px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #e5e7eb;
            padding: 6px;
            text-align: left;
        }

        .summary-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .summary-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .page-footer {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            color: #9ca3af;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <!-- Page de couverture -->
    <div class="cover-page">
        <h1 class="cover-title">Rapports de Réunions</h1>
        <p class="cover-subtitle">Export consolidé - {{ now()->format('d/m/Y') }}</p>

        <div class="cover-stats">
            <div class="cover-stats-grid">
                <div class="cover-stat-row">
                    <div class="cover-stat-cell">
                        <div class="cover-stat-number">{{ $rapports->count() }}</div>
                        <div class="cover-stat-label">Rapports</div>
                    </div>
                    <div class="cover-stat-cell">
                        <div class="cover-stat-number">{{ $rapports->where('statut', 'publie')->count() }}</div>
                        <div class="cover-stat-label">Publiés</div>
                    </div>
                    <div class="cover-stat-cell">
                        <div class="cover-stat-number">{{ $rapports->sum('nombre_presents') ?: 0 }}</div>
                        <div class="cover-stat-label">Total présents</div>
                    </div>
                    <div class="cover-stat-cell">
                        <div class="cover-stat-number">{{ number_format($rapports->sum('montant_collecte') ?: 0, 0) }}FCFA</div>
                        <div class="cover-stat-label">Montant total</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cover-footer">
            Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <!-- Table des matières -->
    <div class="table-of-contents">
        <h2 class="toc-title">Table des Matières</h2>

        <div class="toc-item">
            <span class="toc-title-item">Résumé Exécutif</span>
            <span class="toc-page">3</span>
        </div>

        @foreach($rapports as $index => $rapport)
        <div class="toc-item">
            <span class="toc-title-item">{{ $loop->iteration }}. {{ Str::limit($rapport->titre_rapport, 60) }}</span>
            <span class="toc-page">{{ 4 + $index }}</span>
        </div>
        @endforeach
    </div>

    <!-- Résumé exécutif -->
    <div class="rapport-section">
        <div class="rapport-header">
            <div class="rapport-title">Résumé Exécutif</div>
            <div class="rapport-subtitle">Vue d'ensemble des {{ $rapports->count() }} rapports</div>
        </div>

        <!-- Tableau de synthèse -->
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Présents</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapports as $rapport)
                <tr>
                    <td>{{ Str::limit($rapport->titre_rapport, 30) }}</td>
                    <td>{{ $rapport->type_rapport_traduit }}</td>
                    <td><span class="status-badge status-{{ $rapport->statut }}">{{ $rapport->statut_traduit }}</span></td>
                    <td>{{ $rapport->created_at->format('d/m/Y') }}</td>
                    <td>{{ $rapport->nombre_presents ?: '-' }}</td>
                    <td>{{ $rapport->note_satisfaction ? $rapport->note_satisfaction . '/5' : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Statistiques générales -->
        <div class="content-section">
            <div class="content-title">Analyse Statistique</div>
            <div class="content-text">
                <p><strong>Répartition par statut :</strong></p>
                <ul class="points-list">
                    <li>Brouillons : {{ $rapports->where('statut', 'brouillon')->count() }} ({{ $rapports->count() > 0 ? round(($rapports->where('statut', 'brouillon')->count() / $rapports->count()) * 100, 1) : 0 }}%)</li>
                    <li>En révision : {{ $rapports->where('statut', 'en_revision')->count() }} ({{ $rapports->count() > 0 ? round(($rapports->where('statut', 'en_revision')->count() / $rapports->count()) * 100, 1) : 0 }}%)</li>
                    <li>Validés : {{ $rapports->where('statut', 'valide')->count() }} ({{ $rapports->count() > 0 ? round(($rapports->where('statut', 'valide')->count() / $rapports->count()) * 100, 1) : 0 }}%)</li>
                    <li>Publiés : {{ $rapports->where('statut', 'publie')->count() }} ({{ $rapports->count() > 0 ? round(($rapports->where('statut', 'publie')->count() / $rapports->count()) * 100, 1) : 0 }}%)</li>
                </ul>

                @php
                    $satisfaction = $rapports->whereNotNull('note_satisfaction')->avg('note_satisfaction');
                    $participation = $rapports->where('nombre_presents', '>', 0)->avg('nombre_presents');
                @endphp

                @if($satisfaction)
                <p><strong>Satisfaction moyenne :</strong> {{ number_format($satisfaction, 1) }}/5</p>
                @endif

                @if($participation)
                <p><strong>Participation moyenne :</strong> {{ number_format($participation, 0) }} personnes par réunion</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Rapports individuels -->
    @foreach($rapports as $rapport)
    <div class="rapport-section">
        <div class="rapport-header">
            <div class="rapport-title">{{ $loop->iteration }}. {{ $rapport->titre_rapport }}</div>
            <div class="rapport-subtitle">{{ $rapport->type_rapport_traduit }} - <span class="status-badge status-{{ $rapport->statut }}">{{ $rapport->statut_traduit }}</span></div>
        </div>

        <!-- Métadonnées -->
        <div class="rapport-meta">
            <div class="rapport-meta-row">
                <div class="rapport-meta-cell">
                    <div class="meta-label">Date création</div>
                    <div class="meta-value">{{ $rapport->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Rédacteur</div>
                    <div class="meta-value">{{ $rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'N/A' }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Présents</div>
                    <div class="meta-value">{{ $rapport->nombre_presents ?: 'N/A' }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Satisfaction</div>
                    <div class="meta-value">{{ $rapport->note_satisfaction ? $rapport->note_satisfaction . '/5' : 'N/A' }}</div>
                </div>
            </div>
            @if($rapport->reunion || $rapport->validateur)
            <div class="rapport-meta-row">
                <div class="rapport-meta-cell">
                    <div class="meta-label">Réunion</div>
                    <div class="meta-value">{{ $rapport->reunion ? Str::limit($rapport->reunion->titre, 20) : 'N/A' }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Validateur</div>
                    <div class="meta-value">{{ $rapport->validateur ? $rapport->validateur->nom . ' ' . $rapport->validateur->prenom : 'N/A' }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Validé le</div>
                    <div class="meta-value">{{ $rapport->valide_le ? $rapport->valide_le->format('d/m/Y') : 'N/A' }}</div>
                </div>
                <div class="rapport-meta-cell">
                    <div class="meta-label">Publié le</div>
                    <div class="meta-value">{{ $rapport->publie_le ? $rapport->publie_le->format('d/m/Y') : 'N/A' }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Résumé -->
        @if($rapport->resume)
        <div class="content-section">
            <div class="content-title">Résumé</div>
            <div class="content-text">{{ $rapport->resume }}</div>
        </div>
        @endif

        <!-- Points traités -->
        @if($rapport->points_traites && count($rapport->points_traites) > 0)
        <div class="content-section">
            <div class="content-title">Points Traités ({{ count($rapport->points_traites) }})</div>
            <ul class="points-list">
                @foreach($rapport->points_traites as $point)
                    <li>{{ is_array($point) ? ($point['titre'] ?? $point) : $point }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Décisions -->
        @if($rapport->decisions_prises)
        <div class="content-section">
            <div class="content-title">Décisions Prises</div>
            <div class="content-text">{{ Str::limit($rapport->decisions_prises, 300) }}</div>
        </div>
        @endif

        <!-- Actions -->
        @if($rapport->actions_suivre && count($rapport->actions_suivre) > 0)
        <div class="content-section">
            <div class="content-title">Actions de Suivi ({{ count($rapport->actions_suivre) }})</div>
            <ul class="points-list">
                @foreach($rapport->actions_suivre as $action)
                    <li>{{ $action['titre'] ?? 'Action sans titre' }}
                        @if(isset($action['echeance'])) - Échéance: {{ \Carbon\Carbon::parse($action['echeance'])->format('d/m/Y') }}@endif
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endforeach

    <!-- Footer sur chaque page -->
    <div class="page-footer">
        Export Rapports - {{ now()->format('d/m/Y H:i') }} - Page <span class="pagenum"></span>
    </div>
</body>
</html>
