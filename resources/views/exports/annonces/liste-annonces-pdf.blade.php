<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Annonces</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        $dateGeneration = now()->format('d/m/Y à H:i:s');
        $totalAnnonces = $stats['total'];
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
            LISTE DES ANNONCES
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Export consolidé - Total: {{ $totalAnnonces }} annonce(s) - Généré le {{ $dateGeneration }}
        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Statistiques Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TOTAL ANNONCES</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;">{{ number_format($stats['total'], 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PUBLIÉES</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;">{{ number_format($stats['par_statut']['publiee'] ?? 0, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">BROUILLONS</div>
                <div style="font-size: 12px; font-weight: bold; color: #6b7280;">{{ number_format($stats['par_statut']['brouillon'] ?? 0, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">URGENTES</div>
                <div style="font-size: 12px; font-weight: bold; color: #dc2626;">{{ number_format($stats['urgentes'], 0, ',', ' ') }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- FILTRES APPLIQUÉS -->
    @if(!empty(array_filter($filtres)))
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #f59e0b; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Filtres Appliqués
        </h2>
        <div style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 4px; padding: 12px;">
            <div style="font-size: 8px; line-height: 1.6; color: #374151;">
                @if(!empty($filtres['statut']))
                    <div style="margin-bottom: 5px;"><strong>Statut:</strong> {{ ucfirst($filtres['statut']) }}</div>
                @endif
                @if(!empty($filtres['type_annonce']))
                    <div style="margin-bottom: 5px;"><strong>Type:</strong> {{ \App\Models\Annonce::getTypesAnnonces()[$filtres['type_annonce']] ?? ucfirst($filtres['type_annonce']) }}</div>
                @endif
                @if(!empty($filtres['audience_cible']))
                    <div style="margin-bottom: 5px;"><strong>Audience:</strong> {{ \App\Models\Annonce::getAudiencesCibles()[$filtres['audience_cible']] ?? ucfirst($filtres['audience_cible']) }}</div>
                @endif
                @if(!empty($filtres['niveau_priorite']))
                    <div style="margin-bottom: 5px;"><strong>Priorité:</strong> {{ ucfirst($filtres['niveau_priorite']) }}</div>
                @endif
                @if(!empty($filtres['search']))
                    <div><strong>Recherche:</strong> "{{ htmlspecialchars($filtres['search']) }}"</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- TABLEAU RÉCAPITULATIF -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Tableau Récapitulatif
        </h2>

        @if($annonces->count() > 0)
            <div style="margin-bottom: 15px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                    <thead>
                        <tr>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; white-space: nowrap;">#</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Titre</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Type</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Statut</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Priorité</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Date pub.</th>
                            <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; white-space: nowrap;">Expiration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($annonces as $index => $annonce)
                            @php
                                $statutColors = [
                                    'brouillon' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'publiee' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'expiree' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                ];
                                $statutColor = $statutColors[$annonce->statut] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];

                                $prioriteColors = [
                                    'normal' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'important' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'urgent' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                ];
                                $prioriteColor = $prioriteColors[$annonce->niveau_priorite] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp

                            <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $loop->iteration }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: left;">
                                    <strong>{{ \Illuminate\Support\Str::limit($annonce->titre, 50) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ \App\Models\Annonce::getTypesAnnonces()[$annonce->type_annonce] ?? ucfirst($annonce->type_annonce) }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center; background-color: {{ $statutColor['bg'] }}; white-space: nowrap;">
                                    <strong style="color: {{ $statutColor['text'] }}; font-size: 7px; text-transform: uppercase;">{{ ucfirst($annonce->statut) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center; background-color: {{ $prioriteColor['bg'] }}; white-space: nowrap;">
                                    <strong style="color: {{ $prioriteColor['text'] }}; font-size: 7px; text-transform: uppercase;">{{ ucfirst($annonce->niveau_priorite) }}</strong>
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $annonce->publie_le ? $annonce->publie_le->format('d/m/Y') : '-' }}
                                </td>
                                <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; white-space: nowrap;">
                                    {{ $annonce->expire_le ? $annonce->expire_le->format('d/m/Y') : 'Permanente' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
                <strong>Aucune donnée disponible</strong><br>
                Aucune annonce ne correspond aux critères sélectionnés.
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
                    @foreach($stats['par_statut'] as $statut => $count)
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        {{ ucfirst($statut) }} : {{ $count }} ({{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%)
                    </li>
                    @endforeach
                </ul>
            </div>

            <div style="margin-bottom: 15px;">
                <strong style="color: #1f2937; font-size: 10px;">Répartition par type :</strong>
                <ul style="list-style: none; padding: 0; margin: 5px 0 0 0;">
                    @foreach($stats['par_type'] as $type => $count)
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        {{ \App\Models\Annonce::getTypesAnnonces()[$type] ?? ucfirst($type) }} : {{ $count }} ({{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%)
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <strong style="color: #1f2937; font-size: 10px;">Répartition par priorité :</strong>
                <ul style="list-style: none; padding: 0; margin: 5px 0 0 0;">
                    @foreach($stats['par_priorite'] as $priorite => $count)
                    <li style="padding: 3px 0; position: relative; padding-left: 15px; font-size: 9px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        {{ ucfirst($priorite) }} : {{ $count }} ({{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%)
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- DÉTAIL DES ANNONCES (APERÇU) -->
    @if($annonces->count() > 0 && $annonces->count() <= 10)
        <div style="margin-bottom: 25px;">
            <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
                Détail des Annonces
            </h2>

            @foreach($annonces as $annonce)
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px; margin-bottom: 12px; page-break-inside: avoid;">
                    <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 8px;">
                        <h3 style="color: #1f2937; font-size: 10px; font-weight: bold; margin: 0 0 3px 0;">
                            {{ $loop->iteration }}. {{ $annonce->titre }}
                        </h3>
                        <div style="font-size: 7px; color: #6b7280;">
                            {{ \App\Models\Annonce::getTypesAnnonces()[$annonce->type_annonce] ?? ucfirst($annonce->type_annonce) }} -
                            <span style="padding: 2px 6px; font-size: 6px; font-weight: 600; border-radius: 10px; text-transform: uppercase; background: {{ $statutColors[$annonce->statut]['bg'] ?? '#f3f4f6' }}; color: {{ $statutColors[$annonce->statut]['text'] ?? '#374151' }};">
                                {{ ucfirst($annonce->statut) }}
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 8px; font-size: 8px; color: #374151; line-height: 1.4;">
                        @php
                            // Nettoyer le contenu HTML de CKEditor
                            $contenuApercu = $annonce->contenu;
                            $contenuApercu = str_replace(['<p>', '</p>'], ['', ' '], $contenuApercu);
                            $contenuApercu = str_replace(['<br>', '<br/>', '<br />'], ' ', $contenuApercu);
                            $contenuApercu = strip_tags($contenuApercu);
                            $contenuApercu = preg_replace('/\s+/', ' ', $contenuApercu);
                            $contenuApercu = trim($contenuApercu);
                        @endphp
                        {{ \Illuminate\Support\Str::limit($contenuApercu, 200) }}
                    </div>

                    <div style="font-size: 7px; color: #6b7280;">
                        <strong>Date de publication:</strong> {{ $annonce->publie_le ? $annonce->publie_le->format('d/m/Y') : 'Non publiée' }}
                        @if($annonce->expire_le)
                            | <strong>Expiration:</strong> {{ $annonce->expire_le->format('d/m/Y') }}
                        @endif
                    </div>
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
                Généré le {{ $dateGeneration }}
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
