<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rapport->titre_rapport }}</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        // Calcul des statistiques globales
        $totalPresents = $rapport->nombre_presents ?? 0;
        $totalCollecte = $rapport->montant_collecte ?? 0;
        $nbPointsTraites = $rapport->points_traites ? count($rapport->points_traites) : 0;
        $nbActionsDecidees = $rapport->actions_suivre ? count($rapport->actions_suivre) : 0;
        $nbPresences = $rapport->presences ? count($rapport->presences) : 0;
        $noteSatisfaction = $rapport->note_satisfaction ?? 0;
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
            {{ $rapport->titre_rapport }}
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            {{ $rapport->type_rapport_traduit }}
            @if($rapport->reunion) - {{ $rapport->reunion->titre }}@endif
            - Généré le {{ now()->format('d/m/Y à H:i:s') }}
        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Statistiques Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">PARTICIPANTS</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;">{{ number_format($totalPresents, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">MONTANT COLLECTÉ</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;">{{ number_format($totalCollecte, 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">POINTS TRAITÉS</div>
                <div style="font-size: 12px; font-weight: bold; color: #7c3aed;">{{ $nbPointsTraites }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">ACTIONS DÉCIDÉES</div>
                <div style="font-size: 12px; font-weight: bold; color: #f59e0b;">{{ $nbActionsDecidees }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- INFORMATIONS GÉNÉRALES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations Générales
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TYPE RAPPORT</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;">{{ $rapport->type_rapport_traduit }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">DATE CRÉATION</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;">{{ $rapport->created_at->format('d/m/Y') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">RÉDACTEUR</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;">{{ $rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : 'Non assigné' }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">STATUT</div>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;">{{ $rapport->statut_traduit }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- RÉSUMÉ EXÉCUTIF -->
    @if($rapport->resume)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Résumé Exécutif
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <x-ckeditor-display :model="$rapport" field="resume" show-meta="true" class="content-display" />
        </div>
    </div>
    @endif

    <!-- POINTS TRAITÉS -->
    @if($rapport->points_traites && count($rapport->points_traites) > 0)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Points Traités ({{ count($rapport->points_traites) }})
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($rapport->points_traites as $index => $point)
                    <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; position: relative; padding-left: 20px;">
                        <span style="position: absolute; left: 0; color: #3b82f6; font-weight: bold;">•</span>
                        {{ is_array($point) ? ($point['titre'] ?? $point) : $point }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- DÉCISIONS PRISES -->
    @if($rapport->decisions_prises)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Décisions Prises
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <x-ckeditor-display :model="$rapport" field="decisions_prises" show-meta="true" class="content-display" />
        </div>
    </div>
    @endif

    <!-- ACTIONS DÉCIDÉES -->
    @if($rapport->actions_decidees)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Actions Décidées
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <x-ckeditor-display :model="$rapport" field="actions_decidees" show-meta="true" class="content-display" />
        </div>
    </div>
    @endif

    <!-- ACTIONS DE SUIVI -->
    @if($rapport->actions_suivre && count($rapport->actions_suivre) > 0)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #f59e0b; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Actions de Suivi ({{ count($rapport->actions_suivre) }})
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            @foreach($rapport->actions_suivre as $action)
                <div style="background: white; padding: 12px; margin-bottom: 10px; border-radius: 4px; border-left: 3px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="font-weight: bold; color: #1f2937; margin-bottom: 4px; font-size: 10px;">{{ $action['titre'] ?? 'Action sans titre' }}</div>
                    @if(isset($action['description']))
                        <div style="color: #6b7280; font-size: 9px; margin-bottom: 6px;">{{ $action['description'] }}</div>
                    @endif
                    <div style="font-size: 8px; color: #9ca3af; display: flex; justify-content: space-between;">
                        <span>
                            @if(isset($action['echeance']))
                                Échéance: {{ \Carbon\Carbon::parse($action['echeance'])->format('d/m/Y') }}
                            @endif
                            @if(isset($action['responsable']))
                                | {{ $action['responsable'] }}
                            @endif
                        </span>
                        @if(isset($action['priorite']))
                            <span style="padding: 2px 6px; font-size: 7px; font-weight: 600; border-radius: 10px; text-transform: uppercase; background: #dbeafe; color: #1e40af;">
                                {{ ucfirst($action['priorite']) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- LISTE DES PRÉSENCES -->
    @if($rapport->presences && count($rapport->presences) > 0)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Liste des Présences ({{ count($rapport->presences) }})
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <table style="width: 100%; border-collapse: collapse;">
                @foreach(array_chunk($rapport->presences, 3) as $chunk)
                    <tr>
                        @foreach($chunk as $presence)
                            <td style="width: 33.33%; padding: 8px; text-align: center; border: 1px solid #e5e7eb; background: white;">
                                <div style="font-weight: bold; color: #1f2937; font-size: 9px;">{{ is_array($presence) ? $presence['nom'] : $presence }}</div>
                                @if(is_array($presence) && isset($presence['role']))
                                    <div style="font-size: 8px; color: #6b7280; font-style: italic;">{{ $presence['role'] }}</div>
                                @endif
                            </td>
                        @endforeach
                        @if(count($chunk) < 3)
                            @for($i = count($chunk); $i < 3; $i++)
                                <td style="width: 33.33%; padding: 8px; border: 1px solid #e5e7eb;"></td>
                            @endfor
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- RECOMMANDATIONS -->
    @if($rapport->recommandations)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Recommandations
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <x-ckeditor-display :model="$rapport" field="recommandations" show-meta="true" class="content-display" />
        </div>
    </div>
    @endif

    <!-- COMMENTAIRES -->
    @if($rapport->commentaires)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Commentaires
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <x-ckeditor-display :model="$rapport" field="commentaires" show-meta="true" class="content-display" />
        </div>
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
