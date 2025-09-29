<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Dashboard Église</title>
</head>
<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    <!-- EN-TÊTE STRUCTURE -->
    <div style="background-color: #1e40af; color: white; padding: 15px; margin: -1cm -1cm 20px -1cm; border-bottom: 4px solid #f59e0b; overflow: hidden;">
        <div style="width: 100%;">
            <!-- PARTIE GAUCHE: Logo + Nom + Téléphones -->
            <div style="float: left; width: 48%;">
                <div style="float: left; width: 70px; margin-right: 10px;">
                    @if(!empty($AppParametres->logo))
                        @php
                            try {
                                $logoPath = storage_path('app/public/' . $AppParametres->logo);
                                if (file_exists($logoPath)) {
                                    $imageData = base64_encode(file_get_contents($logoPath));
                                    $imageExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                                    $mimeTypes = [
                                        'jpg' => 'jpeg', 'jpeg' => 'jpeg', 'png' => 'png',
                                        'gif' => 'gif', 'svg' => 'svg+xml', 'webp' => 'webp'
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
                            <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px;">
                                <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 50px; max-height: 50px; vertical-align: middle;">
                            </div>
                        @else
                            <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                                {{ strtoupper(substr($AppParametres->nom_eglise, 0, 2)) }}
                            </div>
                        @endif
                    @else
                        <div style="width: 60px; height: 60px; background-color: white; border-radius: 8px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                            {{ strtoupper(substr($AppParametres->nom_eglise, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div style="margin-left: 80px;">
                    <div style="font-size: 14px; font-weight: bold; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; color: white;">
                        {{ htmlspecialchars($AppParametres->nom_eglise) }}
                    </div>
                    <div style="font-size: 7px; line-height: 1.6; color: white;">
                        @if(!empty($AppParametres->telephone_1))
                            <div style="margin: 3px 0; word-wrap: break-word;"><strong>Tel 1:</strong> {{ htmlspecialchars($AppParametres->telephone_1) }}</div>
                        @endif
                        @if(!empty($AppParametres->telephone_2))
                            <div style="margin: 3px 0; word-wrap: break-word;"><strong>Tel 2:</strong> {{ htmlspecialchars($AppParametres->telephone_2) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PARTIE DROITE: Email + Adresse -->
            <div style="float: right; width: 48%; text-align: right;">
                <div style="font-size: 7px; line-height: 1.6; color: white;">
                    @if(!empty($AppParametres->email))
                        <div style="margin: 3px 0; word-wrap: break-word;"><strong>Email:</strong> {{ htmlspecialchars($AppParametres->email) }}</div>
                    @endif
                    @if(!empty($AppParametres->adresse))
                        <div style="margin: 3px 0; word-wrap: break-word;">
                            <strong>Adresse:</strong>
                            {{ htmlspecialchars($AppParametres->adresse) }}
                            @if(!empty($AppParametres->code_postal)), {{ htmlspecialchars($AppParametres->code_postal) }}@endif
                            @if(!empty($AppParametres->ville)) {{ htmlspecialchars($AppParametres->ville) }}@endif
                        </div>
                    @endif
                    @if(!empty($AppParametres->commune))
                        <div style="margin: 3px 0; word-wrap: break-word;">{{ htmlspecialchars($AppParametres->commune) }}</div>
                    @endif
                    @if(!empty($AppParametres->pays))
                        <div style="margin: 3px 0; word-wrap: break-word;">{{ htmlspecialchars($AppParametres->pays) }}</div>
                    @endif
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- En-tête -->
    <div style="text-align: center; border-bottom: 3px solid #3b82f6; padding-bottom: 15px; margin-bottom: 20px;">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            {{ $data['metadata']['church_name'] ?? 'Église - Tableau de Bord' }}
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Rapport d'Activités - Période {{ $data['metadata']['period_label'] }}
        </p>
    </div>

    <!-- Métadonnées -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations du Rapport
        </h2>
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Du</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Au</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Exporté le</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Exporté par</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $data['metadata']['period_label'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $data['metadata']['start_date'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $data['metadata']['end_date'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $data['metadata']['exported_at'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $data['metadata']['exported_by'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section KPIs -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Indicateurs Clés de Performance (KPIs)
        </h2>

        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Total Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nouveaux Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Présence Moyenne</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nombre de Cultes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Total Offrandes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">FIMECO Progression</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">{{ number_format($data['kpis']['total_membres']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; color: #059669; font-weight: bold; white-space: nowrap;">+{{ number_format($data['kpis']['nouveaux_membres']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">{{ number_format($data['kpis']['avg_participants']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">{{ number_format($data['kpis']['nombre_cultes']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">{{ number_format($data['kpis']['total_offrandes'], 0, ',', ' ') }} FCFA</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">{{ $data['kpis']['fimeco_progression'] }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Évolution des Membres -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            1. Évolution des Membres
        </h2>

        @if(!empty($data['members_evolution']))
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Total Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Actifs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Visiteurs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux Convertis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['members_evolution'] as $index => $member)
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $member['period'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($member['total_membres']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; color: #059669; font-weight: bold; white-space: nowrap;">{{ number_format($member['nouveaux_membres']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($member['membres_actifs']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($member['visiteurs']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($member['nouveaux_convertis']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée disponible pour cette période.
        </div>
        @endif
    </div>

    <!-- Section Présence aux Cultes -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            2. Présence aux Cultes
        </h2>

        @if(!empty($data['culte_attendance']))
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Participants Moyens</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Physiques</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">En Ligne</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nouveaux Visiteurs</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nb Cultes</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Taux Présence (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['culte_attendance'] as $index => $culte)
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $culte['period'] }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['avg_participants']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['participants_physiques']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['participants_en_ligne']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['nouveaux_visiteurs']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['nombre_cultes']) }}</td>
                        <td style="padding: 5px 4px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($culte['taux_presence'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée de culte disponible pour cette période.
        </div>
        @endif
    </div>

    <!-- Section Offrandes -->
    <div style="margin-bottom: 25px; page-break-before: always;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            3. Évolution des Offrandes
        </h2>

        @if(!empty($data['offrandes_evolution']))
        <div style="margin-bottom: 15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Période</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Dîmes (FCFA)</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Ordinaires</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Libres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Offrandes Spéciales</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Missions</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Construction</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Total (FCFA)</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Nb Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['offrandes_evolution'] as $index => $offrande)
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; white-space: nowrap;">{{ $offrande['period'] }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['dimes'], 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['offrandes_ordinaires'], 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['offrandes_libres'], 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['offrandes_speciales'], 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['offrandes_missions'] ?? 0, 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['offrandes_construction'] ?? 0, 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; background-color: #fef3c7; padding: 1px 3px; border-radius: 2px; white-space: nowrap;">{{ number_format($offrande['total_offrandes'], 0, ',', ' ') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">{{ number_format($offrande['nombre_transactions']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            Aucune donnée d'offrandes disponible pour cette période.
        </div>
        @endif
    </div>

    <!-- Résumé Exécutif et Recommandations -->
    <div style="margin-bottom: 25px; page-break-before: always;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Résumé Exécutif
        </h2>

        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 10px; margin: 10px 0;">
            <div style="color: #1e40af; font-size: 10px; font-weight: bold; margin-bottom: 6px;">Synthèse de la Période</div>
            <div style="font-size: 8px; line-height: 1.4; color: #1f2937;">
                <p style="margin: 0 0 6px 0;">
                    <strong>Membres:</strong> L'église compte actuellement {{ number_format($data['kpis']['total_membres']) }} membres avec {{ number_format($data['kpis']['nouveaux_membres']) }} nouveaux membres sur la période.
                </p>
                <p style="margin: 0 0 6px 0;">
                    <strong>Participation:</strong> La présence moyenne aux cultes est de {{ number_format($data['kpis']['avg_participants']) }} personnes sur {{ number_format($data['kpis']['nombre_cultes']) }} cultes organisés.
                </p>
                <p style="margin: 0 0 6px 0;">
                    <strong>Finances:</strong> Les offrandes totales s'élèvent à {{ number_format($data['kpis']['total_offrandes'], 0, ',', ' ') }} FCFA, soit {{ number_format($data['ratios']['presence_offrande_ratio'], 0, ',', ' ') }} FCFA par participant en moyenne.
                </p>
                @if($data['kpis']['fimeco_progression'] > 0)
                <p style="margin: 0;">
                    <strong>FIMECO:</strong> Le projet "{{ $data['kpis']['fimeco_nom'] }}" affiche une progression de {{ $data['kpis']['fimeco_progression'] }}% avec {{ number_format($data['ratios']['total_souscripteurs']) }} souscripteurs.
                </p>
                @endif
            </div>
        </div>

        <!-- Recommandations -->
        <div style="background-color: #fefce8; border: 1px solid #facc15; border-radius: 4px; padding: 10px; margin: 10px 0;">
            <div style="color: #a16207; font-size: 10px; font-weight: bold; margin-bottom: 6px;">Recommandations</div>
            <div style="font-size: 8px; line-height: 1.4; color: #1f2937;">
                @if(isset($data['trends']) && $data['trends']['offrandes_trend'] < 0)
                <p style="margin: 0 0 6px 0;">• Considérer des actions pour améliorer la collecte des offrandes (tendance en baisse de {{ abs($data['trends']['offrandes_trend']) }}%).</p>
                @endif

                @if($data['kpis']['avg_participants'] > 0 && $data['ratios']['presence_offrande_ratio'] > 0)
                <p style="margin: 0 0 6px 0;">• Maintenir l'engagement des fidèles avec un ratio de {{ number_format($data['ratios']['presence_offrande_ratio'], 0, ',', ' ') }} FCFA par participant.</p>
                @endif

                @if($data['kpis']['fimeco_progression'] > 0 && $data['kpis']['fimeco_progression'] < 50)
                <p style="margin: 0 0 6px 0;">• Intensifier les efforts de collecte FIMECO ({{ $data['kpis']['fimeco_progression'] }}% de progression actuelle).</p>
                @endif

                <p style="margin: 0;">• Continuer le suivi régulier des indicateurs pour maintenir la croissance de l'église.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div style="background-color: #37393b; color: white; padding: 10px 15px; font-size: 7px; border-top: 3px solid #3b82f6; margin: 30px -1cm -1cm -1cm;">
        <div style="text-align: center; font-style: italic; color: #fbbf24; margin-bottom: 8px; font-size: 8px; line-height: 1.3; padding-bottom: 8px; border-bottom: 1px solid #4b5563;">
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                "{{ htmlspecialchars($AppParametres->verset_biblique) }}" - {{ htmlspecialchars($AppParametres->reference_verset) }}
            @else
                "Car Dieu a tant aimé le monde qu'il a donné son Fils unique..." - Jean 3:16
            @endif
        </div>
        <div style="text-align: center;">
            <div style="margin-bottom: 5px;">
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
            <div style="font-size: 7px; color: #9ca3af;">
                @if(!empty($AppParametres->website_url))
                    Site web: {{ htmlspecialchars($AppParametres->website_url) }} |
                @endif
                Généré le {{ $data['metadata']['exported_at'] }}
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
