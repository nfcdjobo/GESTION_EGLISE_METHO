<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Rapports de Réunions</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        // Calcul des statistiques globales
        $totalRapports = $rapports->count();
        $totalPublies = $rapports->where('statut', 'publie')->count();
        $totalPresents = $rapports->sum('nombre_presents') ?: 0;
        $totalCollecte = $rapports->sum('montant_collecte') ?: 0;
        $satisfaction = $rapports->whereNotNull('note_satisfaction')->avg('note_satisfaction');
        $participation = $rapports->where('nombre_presents', '>', 0)->avg('nombre_presents');
    @endphp

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

    <!-- TITRE DU RAPPORT -->
    <div style="text-align: center; padding: 15px 0; margin: 20px 0; border-bottom: 3px solid #3b82f6;">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            EXPORT RAPPORTS DE RÉUNIONS
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Export consolidé - Total: {{ $totalRapports }} rapport(s) - Généré le {{ now()->format('d/m/Y à H:i:s') }}
        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Statistiques Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TOTAL RAPPORTS</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;">{{ number_format($totalRapports, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PUBLIÉS</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;">{{ number_format($totalPublies, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PARTICIPANTS TOTAL</div>
                <div style="font-size: 12px; font-weight: bold; color: #7c3aed;">{{ number_format($totalPresents, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">MONTANT TOTAL</div>
                <div style="font-size: 12px; font-weight: bold; color: #f59e0b;">{{ number_format($totalCollecte, 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- TABLEAU RÉCAPITULATIF -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Tableau Récapitulatif
        </h2>

        @if($totalRapports > 0)
            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">#</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Titre</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Type</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Présents</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Collecté</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapports as $index => $rapport)
                            @php
                                $statutColors = [
                                    'brouillon' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'en_revision' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'valide' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'publie' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                ];
                                $statutColor = $statutColors[$rapport->statut] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp

                            <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $loop->iteration }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: left;">
                                    <strong>{{ Str::limit($rapport->titre_rapport, 40) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $rapport->type_rapport_traduit }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center; background-color: {{ $statutColor['bg'] }}; white-space: nowrap;">
                                    <strong style="color: {{ $statutColor['text'] }}; font-size: 7px;">{{ ucfirst($rapport->statut_traduit) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $rapport->created_at->format('d/m/Y') }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    {{ $rapport->nombre_presents ?: '-' }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    {{ $rapport->montant_collecte ? number_format($rapport->montant_collecte, 0, ',', ' ') : '-' }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $rapport->note_satisfaction ? $rapport->note_satisfaction . '/5' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
                <strong>Aucune donnée disponible</strong><br>
                Aucun rapport ne correspond aux critères sélectionnés.
            </div>
        @endif
    </div>

    <!-- ANALYSE STATISTIQUE -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Analyse Statistique
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <div style="margin-bottom: 15px;">
                <strong style="color: #1f2937; font-size: 10px;">Répartition par statut :</strong>
                <ul style="list-style: none; padding: 0; margin: 5px 0 0 0;">
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        Brouillons : {{ $rapports->where('statut', 'brouillon')->count() }} ({{ $totalRapports > 0 ? round(($rapports->where('statut', 'brouillon')->count() / $totalRapports) * 100, 1) : 0 }}%)
                    </li>
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        En révision : {{ $rapports->where('statut', 'en_revision')->count() }} ({{ $totalRapports > 0 ? round(($rapports->where('statut', 'en_revision')->count() / $totalRapports) * 100, 1) : 0 }}%)
                    </li>
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        Validés : {{ $rapports->where('statut', 'valide')->count() }} ({{ $totalRapports > 0 ? round(($rapports->where('statut', 'valide')->count() / $totalRapports) * 100, 1) : 0 }}%)
                    </li>
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        Publiés : {{ $rapports->where('statut', 'publie')->count() }} ({{ $totalRapports > 0 ? round(($rapports->where('statut', 'publie')->count() / $totalRapports) * 100, 1) : 0 }}%)
                    </li>
                </ul>
            </div>

            @if($satisfaction)
                <div style="margin-bottom: 10px;">
                    <strong style="color: #1f2937; font-size: 10px;">Satisfaction moyenne :</strong>
                    <span style="font-size: 9px; color: #6b7280;">{{ number_format($satisfaction, 1) }}/5</span>
                </div>
            @endif

            @if($participation)
                <div style="margin-bottom: 10px;">
                    <strong style="color: #1f2937; font-size: 10px;">Participation moyenne :</strong>
                    <span style="font-size: 9px; color: #6b7280;">{{ number_format($participation, 0) }} personnes par réunion</span>
                </div>
            @endif
        </div>
    </div>

    <!-- DÉTAIL DES RAPPORTS -->
    @if($totalRapports > 0)
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Détail des Rapports
            </h2>

            @foreach($rapports as $rapport)
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px; margin-bottom: 15px; page-break-inside: avoid;">
                    <!-- En-tête du rapport -->
                    <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; margin-bottom: 12px;">
                        <h3 style="color: #1f2937; font-size: 11px; font-weight: bold; margin: 0 0 4px 0;">
                            {{ $loop->iteration }}. {{ $rapport->titre_rapport }}
                        </h3>
                        <div style="font-size: 8px; color: #6b7280;">
                            {{ $rapport->type_rapport_traduit }} -
                            <span style="padding: 2px 6px; font-size: 7px; font-weight: 600; border-radius: 10px; text-transform: uppercase;
                                background: {{ $statutColors[$rapport->statut]['bg'] ?? '#f3f4f6' }};
                                color: {{ $statutColors[$rapport->statut]['text'] ?? '#374151' }};">
                                {{ $rapport->statut_traduit }}
                            </span>
                        </div>
                    </div>

                    <!-- Métadonnées -->
                    <div style="margin-bottom: 12px;">
                        <div style="width: 100%;">
                            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 8px;">
                                <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase;">Date création</div>
                                <div style="font-size: 8px; color: #1f2937; font-weight: 600;">{{ $rapport->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 8px;">
                                <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase;">Rédacteur</div>
                                <div style="font-size: 8px; color: #1f2937; font-weight: 600;">{{ $rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'N/A' }}</div>
                            </div>
                            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 8px;">
                                <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase;">Présents</div>
                                <div style="font-size: 8px; color: #1f2937; font-weight: 600;">{{ $rapport->nombre_presents ?: 'N/A' }}</div>
                            </div>
                            <div style="float: left; width: 21%; margin-bottom: 8px;">
                                <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase;">Satisfaction</div>
                                <div style="font-size: 8px; color: #1f2937; font-weight: 600;">{{ $rapport->note_satisfaction ? $rapport->note_satisfaction . '/5' : 'N/A' }}</div>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>

                    <!-- Résumé -->
                    @if($rapport->resume)
                        <div style="margin-bottom: 10px;">
                            <div style="font-size: 9px; font-weight: 600; color: #374151; margin-bottom: 4px;">Résumé :</div>
                            <div style="font-size: 8px; color: #6b7280; line-height: 1.4;">{{ Str::limit(strip_tags($rapport->resume), 200) }}</div>
                        </div>
                    @endif

                    <!-- Points traités -->
                    @if($rapport->points_traites && count($rapport->points_traites) > 0)
                        <div style="margin-bottom: 10px;">
                            <div style="font-size: 9px; font-weight: 600; color: #374151; margin-bottom: 4px;">Points traités ({{ count($rapport->points_traites) }}) :</div>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach(array_slice($rapport->points_traites, 0, 3) as $point)
                                    <li style="padding: 2px 0; position: relative; padding-left: 12px; font-size: 8px; color: #6b7280;">
                                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                                        {{ is_array($point) ? ($point['titre'] ?? $point) : $point }}
                                    </li>
                                @endforeach
                                @if(count($rapport->points_traites) > 3)
                                    <li style="padding: 2px 0; position: relative; padding-left: 12px; font-size: 8px; color: #9ca3af; font-style: italic;">
                                        ... et {{ count($rapport->points_traites) - 3 }} autres point(s)
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <!-- Actions de suivi -->
                    @if($rapport->actions_suivre && count($rapport->actions_suivre) > 0)
                        <div style="margin-bottom: 10px;">
                            <div style="font-size: 9px; font-weight: 600; color: #374151; margin-bottom: 4px;">Actions de suivi ({{ count($rapport->actions_suivre) }}) :</div>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach(array_slice($rapport->actions_suivre, 0, 2) as $action)
                                    <li style="padding: 2px 0; position: relative; padding-left: 12px; font-size: 8px; color: #6b7280;">
                                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                                        {{ $action['titre'] ?? 'Action sans titre' }}
                                        @if(isset($action['echeance'])) - Échéance: {{ \Carbon\Carbon::parse($action['echeance'])->format('d/m/Y') }}@endif
                                    </li>
                                @endforeach
                                @if(count($rapport->actions_suivre) > 2)
                                    <li style="padding: 2px 0; position: relative; padding-left: 12px; font-size: 8px; color: #9ca3af; font-style: italic;">
                                        ... et {{ count($rapport->actions_suivre) - 2 }} autres action(s)
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- FOOTER -->
    <div style="background-color: #37393b; color: white; padding: 10px 15px; font-size: 7px; border-top: 3px solid #3b82f6; margin: 30px -1cm -1cm -1cm;">
        <div style="text-align: center; font-style: italic; color: #fbbf24; margin-bottom: 8px; font-size: 8px; line-height: 1.3; padding-bottom: 8px; border-bottom: 1px solid #4b5563;">
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                "{{ htmlspecialchars($AppParametres->verset_biblique) }}" - {{ htmlspecialchars($AppParametres->reference_verset) }}
            @else
                "Honore l'Éternel avec tes biens, et avec les prémices de tout ton revenu" - Proverbes 3:9
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
                Généré le {{ now()->format('d/m/Y à H:i:s') }}
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
