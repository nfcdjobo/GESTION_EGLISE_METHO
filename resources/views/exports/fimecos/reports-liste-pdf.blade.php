{{-- resources/views/exports/fimecos/reports-liste-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des FIMECOs</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        // Calcul des statistiques globales
        $totalCible = array_sum(array_column($data, 'Cible'));
        $totalSolde = array_sum(array_column($data, 'Montant soldé'));
        $totalReste = array_sum(array_column($data, 'Reste'));
        $progressionMoyenne = count($data) > 0 ? array_sum(array_column($data, 'Progression (%)')) / count($data) : 0;
        $nbActifs = count(array_filter($data, fn($f) => $f['Statut'] === 'active'));
        $nbCompletes = count(array_filter($data, fn($f) => $f['Statut global'] === 'objectif_atteint'));
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
            RAPPORT DES FIMECOs
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Total: {{ count($data) }} FIMECO(s) - Généré le {{ now()->format('d/m/Y à H:i:s') }}
        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Statistiques Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">CIBLE TOTALE</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;">{{ number_format($totalCible, 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">MONTANT COLLECTÉ</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;">{{ number_format($totalSolde, 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">ACTIFS</div>
                <div style="font-size: 12px; font-weight: bold; color: #7c3aed;">{{ $nbActifs }} / {{ count($data) }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PROGRESSION MOY.</div>
                <div style="font-size: 12px; font-weight: bold; color: #f59e0b;">{{ number_format($progressionMoyenne, 1) }}%</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- TABLEAU DES DONNÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Détail des FIMECOs
        </h2>

        @if(count($data) > 0)
            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Nom</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">Responsable</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date début</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date fin</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Cible</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Collecté</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: right; white-space: nowrap;">Reste</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">%</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Nb Souscriptions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $fimeco)
                            @php
                                $progression = $fimeco['Progression (%)'];
                                $statutGlobal = $fimeco['Statut global'];

                                $progressBgColor = $progression >= 100 ? '#d1fae5' :
                                                  ($progression >= 75 ? '#fef3c7' :
                                                  ($progression >= 50 ? '#dbeafe' :
                                                  ($progression >= 25 ? '#fef9c3' : '#fee2e2')));

                                $progressTextColor = $progression >= 100 ? '#065f46' :
                                                    ($progression >= 75 ? '#92400e' :
                                                    ($progression >= 50 ? '#1e40af' :
                                                    ($progression >= 25 ? '#854d0e' : '#991b1b')));

                                $statutColors = [
                                    'active' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                    'inactive' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'cloturee' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                ];
                                $statutColor = $statutColors[$fimeco['Statut']] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp

                            <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: left; white-space: nowrap;">
                                    <strong>{{ $fimeco['Nom'] }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: left; white-space: nowrap;">
                                    {{ $fimeco['Responsable'] }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $fimeco['Date début'] }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $fimeco['Date fin'] }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    {{ number_format($fimeco['Cible'], 0, ',', ' ') }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    {{ number_format($fimeco['Montant soldé'], 0, ',', ' ') }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: right; white-space: nowrap;">
                                    {{ number_format($fimeco['Reste'], 0, ',', ' ') }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center; background-color: {{ $progressBgColor }}; white-space: nowrap;">
                                    <strong style="color: {{ $progressTextColor }};">{{ number_format($progression, 1) }}%</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center; background-color: {{ $statutColor['bg'] }}; white-space: nowrap;">
                                    <strong style="color: {{ $statutColor['text'] }}; font-size: 7px;">{{ ucfirst($fimeco['Statut']) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $fimeco['Nb souscriptions'] }}
                                </td>
                            </tr>
                        @endforeach

                        <!-- LIGNE DE TOTAUX -->
                        <tr style="background-color: #059669; color: white; font-weight: bold;">
                            <td colspan="4" style="padding: 8px 3px; border: 1px solid #047857; text-align: center;">
                                TOTAUX GÉNÉRAUX
                            </td>
                            <td style="padding: 8px 3px; border: 1px solid #047857; text-align: right;">
                                {{ number_format($totalCible, 0, ',', ' ') }}
                            </td>
                            <td style="padding: 8px 3px; border: 1px solid #047857; text-align: right;">
                                {{ number_format($totalSolde, 0, ',', ' ') }}
                            </td>
                            <td style="padding: 8px 3px; border: 1px solid #047857; text-align: right;">
                                {{ number_format($totalReste, 0, ',', ' ') }}
                            </td>
                            <td style="padding: 8px 3px; border: 1px solid #047857; text-align: center;">
                                {{ number_format($progressionMoyenne, 1) }}%
                            </td>
                            <td colspan="2" style="padding: 8px 3px; border: 1px solid #047857; text-align: center;">
                                {{ $nbCompletes }} objectifs atteints
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
                <strong>Aucune donnée disponible</strong><br>
                Aucun FIMECO ne correspond aux critères sélectionnés.
            </div>
        @endif
    </div>

    <!-- LÉGENDE DES PROGRESSIONS -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Légende des Progressions
        </h2>
        <div style="width: 100%; margin: 15px 0;">
            <div style="float: left; width: 18%; margin-right: 2%; text-align: center; font-size: 8px;">
                <div style="width: 100%; height: 15px; border: 1px solid #a7f3d0; margin: 0 0 3px 0; border-radius: 2px; background-color: #d1fae5;"></div>
                <div>100%+ - Objectif atteint</div>
            </div>
            <div style="float: left; width: 18%; margin-right: 2%; text-align: center; font-size: 8px;">
                <div style="width: 100%; height: 15px; border: 1px solid #fde047; margin: 0 0 3px 0; border-radius: 2px; background-color: #fef3c7;"></div>
                <div>75-99% - Presque atteint</div>
            </div>
            <div style="float: left; width: 18%; margin-right: 2%; text-align: center; font-size: 8px;">
                <div style="width: 100%; height: 15px; border: 1px solid #93c5fd; margin: 0 0 3px 0; border-radius: 2px; background-color: #dbeafe;"></div>
                <div>50-74% - En cours</div>
            </div>
            <div style="float: left; width: 18%; margin-right: 2%; text-align: center; font-size: 8px;">
                <div style="width: 100%; height: 15px; border: 1px solid #fde047; margin: 0 0 3px 0; border-radius: 2px; background-color: #fef9c3;"></div>
                <div>25-49% - Début</div>
            </div>
            <div style="float: left; width: 18%; text-align: center; font-size: 8px;">
                <div style="width: 100%; height: 15px; border: 1px solid #fca5a5; margin: 0 0 3px 0; border-radius: 2px; background-color: #fee2e2;"></div>
                <div>0-24% - Très faible</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

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
