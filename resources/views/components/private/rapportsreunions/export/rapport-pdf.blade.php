<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $rapport->titre_rapport }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #374151;
            background: #ffffff;
        }

        .header {
            background: #667eea;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #5856eb;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .header .subtitle {
            font-size: 14px;
        }

        .status-badge {
            float: right;
            padding: 8px 16px;
            border: 1px solid;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: -60px;
        }

        .status-brouillon { background: #f3f4f6; color: #374151; }
        .status-en_revision { background: #fef3c7; color: #92400e; }
        .status-valide { background: #dbeafe; color: #1e40af; }
        .status-publie { background: #dcfce7; color: #166534; }

        .meta-grid {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .meta-grid td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            vertical-align: top;
            width: 25%;
        }

        .meta-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .meta-value {
            font-weight: bold;
            color: #111827;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-header {
            background: #f3f4f6;
            padding: 15px 20px;
            border-left: 4px solid #6366f1;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }

        .section-content {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-top: none;
            padding: 20px;
        }

        .section-content p {
            margin-bottom: 12px;
            text-align: justify;
            line-height: 1.7;
        }

        .points-list {
            list-style: none;
            padding: 0;
        }

        .points-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            padding-left: 20px;
        }

        .points-list li:before {
            content: "✓ ";
            color: #10b981;
            font-weight: bold;
            margin-left: -20px;
            margin-right: 5px;
        }

        .presences-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .presences-grid td {
            background: #ffffff;
            padding: 10px;
            text-align: center;
            border: 1px solid #e5e7eb;
            width: 33.33%;
        }

        .presence-name {
            font-weight: bold;
            color: #111827;
        }

        .presence-role {
            font-size: 9px;
            color: #6b7280;
            font-style: italic;
        }

        .actions-container {
            background: #f8fafc;
            padding: 15px;
        }

        .action-item {
            background: white;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #6366f1;
            border: 1px solid #e5e7eb;
        }

        .action-title {
            font-weight: bold;
            color: #111827;
            margin-bottom: 6px;
        }

        .action-desc {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .action-meta {
            font-size: 9px;
            color: #9ca3af;
        }

        .priority-badge {
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid;
        }

        .priority-faible { background: #dcfce7; color: #166534; }
        .priority-normale { background: #dbeafe; color: #1e40af; }
        .priority-haute { background: #fed7aa; color: #c2410c; }
        .priority-critique { background: #fecaca; color: #dc2626; }

        .stats-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .stats-grid td {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: center;
            width: 25%;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 9px;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
        }

        .rating-stars {
            color: #fbbf24;
        }

        .star {
            color: #fbbf24;
        }

        .star.empty {
            color: #d1d5db;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $rapport->titre_rapport }}</h1>
        <p class="subtitle">
            {{ $rapport->type_rapport_traduit }}
            @if($rapport->reunion) - {{ $rapport->reunion->titre }}@endif
        </p>
        <div class="status-badge status-{{ $rapport->statut }}">
            {{ $rapport->statut_traduit }}
        </div>
        <div class="clear"></div>
    </div>

    <!-- Informations générales -->
    <table class="meta-grid">
        <tr>
            <td>
                <div class="meta-label">Type</div>
                <div class="meta-value">{{ $rapport->type_rapport_traduit }}</div>
            </td>
            <td>
                <div class="meta-label">Date de création</div>
                <div class="meta-value">{{ $rapport->created_at->format('d/m/Y à H:i') }}</div>
            </td>
            <td>
                <div class="meta-label">Rédacteur</div>
                <div class="meta-value">{{ $rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'N/A' }}</div>
            </td>
            <td>
                <div class="meta-label">Statut</div>
                <div class="meta-value">{{ $rapport->statut_traduit }}</div>
            </td>
        </tr>
        @if($rapport->reunion || $rapport->validateur || $rapport->valide_le || $rapport->publie_le)
        <tr>
            <td>
                <div class="meta-label">Réunion</div>
                <div class="meta-value">{{ $rapport->reunion ? $rapport->reunion->titre : 'N/A' }}</div>
            </td>
            <td>
                <div class="meta-label">Date réunion</div>
                <div class="meta-value">{{ $rapport->reunion ? \Carbon\Carbon::parse($rapport->reunion->date_reunion)->format('d/m/Y') : 'N/A' }}</div>
            </td>
            <td>
                <div class="meta-label">Validateur</div>
                <div class="meta-value">{{ $rapport->validateur ? $rapport->validateur->nom . ' ' . $rapport->validateur->prenom : 'N/A' }}</div>
            </td>
            <td>
                <div class="meta-label">Validé le</div>
                <div class="meta-value">{{ $rapport->valide_le ? $rapport->valide_le->format('d/m/Y') : 'N/A' }}</div>
            </td>
        </tr>
        @endif
    </table>

    <!-- Statistiques -->
    @if($rapport->nombre_presents || $rapport->montant_collecte || $rapport->note_satisfaction)
    <table class="stats-grid">
        <tr>
            @if($rapport->nombre_presents)
            <td>
                <div class="stat-number">{{ $rapport->nombre_presents }}</div>
                <div class="stat-label">Présents</div>
            </td>
            @endif
            @if($rapport->montant_collecte)
            <td>
                <div class="stat-number">{{ number_format($rapport->montant_collecte, 2) }}€</div>
                <div class="stat-label">Collecté</div>
            </td>
            @endif
            @if($rapport->note_satisfaction)
            <td>
                <div class="stat-number">
                    <span class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $rapport->note_satisfaction ? '' : 'empty' }}">★</span>
                        @endfor
                    </span>
                </div>
                <div class="stat-label">Satisfaction</div>
            </td>
            @endif
            @if($rapport->points_traites)
            <td>
                <div class="stat-number">{{ count($rapport->points_traites) }}</div>
                <div class="stat-label">Points traités</div>
            </td>
            @endif
        </tr>
    </table>
    @endif

    <!-- Résumé -->
    @if($rapport->resume)
    <div class="section no-break">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">📄</span>
                Résumé Exécutif
            </div>
        </div>
        <div class="section-content">
            <p>
                {{-- {{ $rapport->resume }} --}}
                <x-ckeditor-display :model="$rapport" field="resume" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
            </p>
        </div>
    </div>
    @endif

    <!-- Points traités -->
    @if($rapport->points_traites && count($rapport->points_traites) > 0)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                📋 Points Traités ({{ count($rapport->points_traites) }})
            </div>
        </div>
        <div class="section-content">
            <ul class="points-list">
                @foreach($rapport->points_traites as $point)
                    <li>{{ is_array($point) ? ($point['titre'] ?? $point) : $point }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Décisions prises -->
    @if($rapport->decisions_prises)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">⚖️</span>
                Décisions Prises
            </div>
        </div>
        <div class="section-content">
            <p>
                {{-- {{ $rapport->decisions_prises }} --}}
                <x-ckeditor-display :model="$rapport" field="decisions_prises" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
            </p>
        </div>
    </div>
    @endif

    <!-- Actions décidées -->
    @if($rapport->actions_decidees)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">📝</span>
                Actions Décidées
            </div>
        </div>
        <div class="section-content">
            <p>
                {{-- {{ $rapport->actions_decidees }} --}}
                <x-ckeditor-display :model="$rapport" field="actions_decidees" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
            </p>
        </div>
    </div>
    @endif

    <!-- Actions de suivi -->
    @if($rapport->actions_suivre && count($rapport->actions_suivre) > 0)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">🎯</span>
                Actions de Suivi ({{ count($rapport->actions_suivre) }})
            </div>
        </div>
        <div class="section-content">
            <div class="actions-container">
                @foreach($rapport->actions_suivre as $action)
                    <div class="action-item">
                        <div class="action-title">{{ $action['titre'] ?? 'Action sans titre' }}</div>
                        @if(isset($action['description']))
                            <div class="action-desc">{{ $action['description'] }}</div>
                        @endif
                        <div class="action-meta">
                            <span>
                                @if(isset($action['echeance']))
                                    Échéance: {{ \Carbon\Carbon::parse($action['echeance'])->format('d/m/Y') }}
                                @endif
                            </span>
                            @if(isset($action['priorite']))
                                <span class="priority-badge priority-{{ $action['priorite'] }}">
                                    {{ ucfirst($action['priorite']) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Présences -->
    @if($rapport->presences && count($rapport->presences) > 0)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                👥 Liste des Présences ({{ count($rapport->presences) }})
            </div>
        </div>
        <div class="section-content">
            <table class="presences-grid">
                @foreach(array_chunk($rapport->presences, 3) as $chunk)
                    <tr>
                        @foreach($chunk as $presence)
                            <td>
                                <div class="presence-name">{{ is_array($presence) ? $presence['nom'] : $presence }}</div>
                                @if(is_array($presence) && isset($presence['role']))
                                    <div class="presence-role">{{ $presence['role'] }}</div>
                                @endif
                            </td>
                        @endforeach
                        @if(count($chunk) < 3)
                            @for($i = count($chunk); $i < 3; $i++)
                                <td></td>
                            @endfor
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Recommandations -->
    @if($rapport->recommandations)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">💡</span>
                Recommandations
            </div>
        </div>
        <div class="section-content">
            <p>
                {{-- {{ $rapport->recommandations }} --}}
                <x-ckeditor-display :model="$rapport" field="recommandations" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
            </p>
        </div>
    </div>
    @endif

    <!-- Commentaires -->
    @if($rapport->commentaires)
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                <span class="section-icon">💬</span>
                Commentaires
            </div>
        </div>
        <div class="section-content">
            <p>
                {{-- {{ $rapport->commentaires }} --}}
                <x-ckeditor-display :model="$rapport" field="commentaires" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
            </p>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Rapport ID: {{ $rapport->id }}</p>
        @if($rapport->validateur && $rapport->valide_le)
            <p>Validé par {{ $rapport->validateur->nom }} {{ $rapport->validateur->prenom }} le {{ $rapport->valide_le->format('d/m/Y à H:i') }}</p>
        @endif
    </div>
</body>
</html>
