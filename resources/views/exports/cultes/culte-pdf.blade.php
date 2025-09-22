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

        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 18px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin: 0;
        }

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

        .kpis-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .kpi-card {
            flex: 1;
            min-width: 120px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
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

        .footer {
            position: fixed;
            bottom: 0.5cm;
            left: 1cm;
            right: 1cm;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        .highlight {
            background-color: #fef3c7;
            padding: 1px 3px;
            border-radius: 2px;
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
            display: flex;
            gap: 15px;
        }

        .column {
            flex: 1;
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

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin: 3px 0;
        }

        .progress-fill {
            height: 100%;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .progress-fill.green {
            background-color: #059669;
        }

        .progress-fill.purple {
            background-color: #7c3aed;
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
    <!-- En-tête -->
    <div class="header">
        <h1>ÉGLISE - RAPPORT DE CULTE</h1>
        <p class="subtitle">{{ htmlspecialchars($culte->titre) }} - {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }}</p>
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
            <div style="margin-top: 5px; font-size: 9px;">{{ nl2br(htmlspecialchars(strip_tags($culte->description))) }}</div>
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
                    @if($culte->pasteurPrincipal)
                    <tr>
                        <td><strong>Pasteur Principal</strong></td>
                        <td>{{ htmlspecialchars($culte->pasteurPrincipal->nom_complet) }}</td>
                        <td>{{ htmlspecialchars($culte->pasteurPrincipal->email ?: 'Non renseigné') }}</td>
                        <td>{{ htmlspecialchars($culte->pasteurPrincipal->telephone_1 ?: 'Non renseigné') }}</td>
                    </tr>
                    @endif

                    @if($culte->predicateur && $culte->predicateur->id !== $culte->pasteurPrincipal?->id)
                    <tr>
                        <td><strong>Prédicateur</strong></td>
                        <td>{{ htmlspecialchars($culte->predicateur->nom_complet) }}</td>
                        <td>{{ htmlspecialchars($culte->predicateur->email ?: 'Non renseigné') }}</td>
                        <td>{{ htmlspecialchars($culte->predicateur->telephone_1 ?: 'Non renseigné') }}</td>
                    </tr>
                    @endif

                    @if($culte->responsableCulte)
                    <tr>
                        <td><strong>Responsable Culte</strong></td>
                        <td>{{ htmlspecialchars($culte->responsableCulte->nom_complet) }}</td>
                        <td>{{ htmlspecialchars($culte->responsableCulte->email ?: 'Non renseigné') }}</td>
                        <td>{{ htmlspecialchars($culte->responsableCulte->telephone_1 ?: 'Non renseigné') }}</td>
                    </tr>
                    @endif

                    @if($culte->dirigeantLouange)
                    <tr>
                        <td><strong>Dirigeant Louange</strong></td>
                        <td>{{ htmlspecialchars($culte->dirigeantLouange->nom_complet) }}</td>
                        <td>{{ htmlspecialchars($culte->dirigeantLouange->email ?: 'Non renseigné') }}</td>
                        <td>{{ htmlspecialchars($culte->dirigeantLouange->telephone_1 ?: 'Non renseigné') }}</td>
                    </tr>
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

        @if($culte->heure_debut_reelle || $culte->heure_fin_reelle)
        <div class="summary-box">
            <strong>Horaires réels:</strong>
            @if($culte->heure_debut_reelle)
                Début: {{ \Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i') }}
            @endif
            @if($culte->heure_fin_reelle)
                - Fin: {{ \Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i') }}
            @endif
            @if($culte->duree_totale)
                (Durée: {{ $culte->duree_totale }})
            @endif
        </div>
        @endif
    </div>
    @endif

    <!-- Statistiques Financières -->
    @if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0)
    <div class="section page-break">
        <h2 class="section-title financier">Statistiques Financières</h2>

        <!-- Métriques principales -->
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

        @if($culte->nombre_participants > 0)
        <!-- Ratios par participant -->
        <div class="summary-box">
            <strong>Ratios par participant:</strong>
            <div style="margin-top: 8px;">
                <div style="margin: 3px 0;">
                    Offrande par participant: <strong>{{ number_format($metriques['offrande_par_participant'], 0) }} FCFA</strong>
                </div>
                <div style="margin: 3px 0;">
                    Dîme par participant: <strong>{{ number_format($metriques['dime_par_participant'], 0) }} FCFA</strong>
                </div>
                <div style="margin: 3px 0;">
                    Taux de participation financière: <strong>{{ $metriques['taux_participation_financiere'] }}%</strong>
                </div>
            </div>
        </div>
        @endif

        <!-- Répartition par type -->
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

        <!-- Top donateurs -->
        @if(count($fondsStatistiques['top_donateurs']) > 0)
        <div style="margin-top: 15px;">
            <strong style="font-size: 10px;">Top 5 des donateurs:</strong>
            <div class="table-container" style="margin-top: 5px;">
                <table>
                    <thead>
                        <tr>
                            <th class="center">Rang</th>
                            <th>Donateur</th>
                            <th class="center">Nb Dons</th>
                            <th class="number">Montant Total (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fondsStatistiques['top_donateurs'] as $index => $donateur)
                        <tr>
                            <td class="center"><strong>{{ $index + 1 }}</strong></td>
                            <td>{{ htmlspecialchars($donateur['donateur']) }}</td>
                            <td class="center">{{ $donateur['nombre_dons'] }}</td>
                            <td class="number"><strong>{{ number_format($donateur['montant_total'], 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Comparaison avec moyenne -->
        @if(isset($metriques['comparaison']) && $metriques['comparaison']['moyenne_type_culte'] > 0)
        <div class="financial-highlight">
            <div style="font-size: 10px; margin-bottom: 5px;">
                <strong>Comparaison avec la moyenne des {{ $culte->type_culte_libelle }}s:</strong>
            </div>
            <div style="display: flex; gap: 15px; align-items: center; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 8px; color: #6b7280;">Ce culte</div>
                    <div class="amount">{{ number_format($fondsStatistiques['montant_total'], 0) }} FCFA</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 8px; color: #6b7280;">Moyenne</div>
                    <div class="amount">{{ number_format($metriques['comparaison']['moyenne_type_culte'], 0) }} FCFA</div>
                </div>
                <div style="text-align: center;">
                    @php
                        $ecart = $metriques['comparaison']['ecart_pourcentage'];
                        $couleur = $ecart > 0 ? '#059669' : '#dc2626';
                        $signe = $ecart > 0 ? '+' : '';
                    @endphp
                    <div style="font-size: 8px; color: #6b7280;">Écart</div>
                    <div style="font-size: 12px; font-weight: bold; color: {{ $couleur }};">{{ $signe }}{{ $ecart }}%</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Évaluations -->
    @if($culte->note_globale || $culte->note_louange || $culte->note_message || $culte->note_organisation)
    <div class="section">
        <h2 class="section-title">Évaluations</h2>

        <div class="kpis-grid">
            @if($culte->note_globale)
            <div class="kpi-card amber">
                <div class="kpi-label">NOTE GLOBALE</div>
                <div class="kpi-value">{{ $culte->note_globale }}/10</div>
            </div>
            @endif

            @if($culte->note_louange)
            <div class="kpi-card purple">
                <div class="kpi-label">LOUANGE</div>
                <div class="kpi-value">{{ $culte->note_louange }}/10</div>
            </div>
            @endif

            @if($culte->note_message)
            <div class="kpi-card blue">
                <div class="kpi-label">MESSAGE</div>
                <div class="kpi-value">{{ $culte->note_message }}/10</div>
            </div>
            @endif

            @if($culte->note_organisation)
            <div class="kpi-card green">
                <div class="kpi-label">ORGANISATION</div>
                <div class="kpi-value">{{ $culte->note_organisation }}/10</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Notes et Commentaires -->
    @if($culte->notes_pasteur || $culte->notes_organisateur || $culte->points_forts || $culte->points_amelioration)
    <div class="section">
        <h2 class="section-title notes">Notes et Commentaires</h2>

        @if($culte->notes_pasteur)
        <div class="summary-box">
            <strong>Notes du pasteur:</strong>
            <div style="margin-top: 5px; font-size: 9px;">{{ nl2br(htmlspecialchars(strip_tags($culte->notes_pasteur))) }}</div>
        </div>
        @endif

        @if($culte->notes_organisateur)
        <div class="summary-box">
            <strong>Notes de l'organisateur:</strong>
            <div style="margin-top: 5px; font-size: 9px;">{{ nl2br(htmlspecialchars(strip_tags($culte->notes_organisateur))) }}</div>
        </div>
        @endif

        @if($culte->points_forts || $culte->points_amelioration)
        <div class="two-column">
            @if($culte->points_forts)
            <div class="column">
                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; padding: 8px;">
                    <strong style="color: #065f46;">Points forts:</strong>
                    <div style="margin-top: 5px; font-size: 9px; color: #047857;">{{ nl2br(htmlspecialchars(strip_tags($culte->points_forts))) }}</div>
                </div>
            </div>
            @endif

            @if($culte->points_amelioration)
            <div class="column">
                <div style="background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 4px; padding: 8px;">
                    <strong style="color: #92400e;">Points d'amélioration:</strong>
                    <div style="margin-top: 5px; font-size: 9px; color: #b45309;">{{ nl2br(htmlspecialchars(strip_tags($culte->points_amelioration))) }}</div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

    <!-- Résumé du culte -->
    <div class="section">
        <h2 class="section-title">Résumé du Culte</h2>
        <div class="summary-box">
            <div style="font-size: 9px; line-height: 1.4;">
                <p style="margin: 0 0 6px 0;">
                    <strong>Vue d'ensemble:</strong> Le culte "{{ $culte->titre }}" s'est déroulé le {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }} et avait pour statut "{{ $culte->statut_libelle }}".
                </p>

                @if($culte->nombre_participants)
                <p style="margin: 0 0 6px 0;">
                    <strong>Participation:</strong> {{ number_format($culte->nombre_participants) }} personne(s) ont participé à ce culte
                    @if($culte->nombre_adultes || $culte->nombre_jeunes || $culte->nombre_enfants)
                        , répartis comme suit :
                        @if($culte->nombre_adultes) {{ $culte->nombre_adultes }} adulte(s)@endif
                        @if($culte->nombre_jeunes)@if($culte->nombre_adultes), @endif {{ $culte->nombre_jeunes }} jeune(s)@endif
                        @if($culte->nombre_enfants)@if($culte->nombre_adultes || $culte->nombre_jeunes), @endif {{ $culte->nombre_enfants }} enfant(s)@endif
                    @endif
                    .
                </p>
                @endif

                @if(isset($fondsStatistiques) && $fondsStatistiques['total_transactions'] > 0)
                <p style="margin: 0 0 6px 0;">
                    <strong>Finances:</strong> Un total de {{ number_format($fondsStatistiques['montant_total'], 0) }} FCFA a été collecté lors de ce culte, réparti sur {{ $fondsStatistiques['total_transactions'] }} transaction(s) de {{ $fondsStatistiques['donateurs_uniques'] }} donateur(s).
                </p>
                @endif

                @if($culte->pasteurPrincipal)
                <p style="margin: 0 0 6px 0;">
                    <strong>Responsabilité:</strong> {{ htmlspecialchars($culte->pasteurPrincipal->nom_complet) }} était le pasteur principal de ce culte.
                </p>
                @endif

                <p style="margin: 0;">
                    <strong>Enregistrement:</strong> Ce rapport a été généré le {{ $dateGeneration }} et reflète l'état du culte au moment de l'extraction.
                </p>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>Généré automatiquement par le système de gestion d'église</div>
            <div>{{ $dateGeneration }}</div>
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
