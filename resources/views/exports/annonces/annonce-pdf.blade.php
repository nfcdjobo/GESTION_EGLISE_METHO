<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonce - {{ $annonce->titre }}</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        // Définition des couleurs par statut
        $statutColors = [
            'brouillon' => ['bg' => '#f3f4f6', 'text' => '#374151'],
            'publiee' => ['bg' => '#dcfce7', 'text' => '#166534'],
            'expiree' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
        ];
        $statutColor = $statutColors[$annonce->statut] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];

        // Définition des couleurs par priorité
        $prioriteColors = [
            'normal' => ['bg' => '#f3f4f6', 'text' => '#374151'],
            'important' => ['bg' => '#fef3c7', 'text' => '#92400e'],
            'urgent' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
        ];
        $prioriteColor = $prioriteColors[$annonce->niveau_priorite] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];

        // Définition des couleurs par type
        $typeColors = [
            'evenement' => '#3b82f6',
            'administrative' => '#8b5cf6',
            'pastorale' => '#10b981',
            'urgence' => '#ef4444',
            'information' => '#6b7280',
        ];
        $typeColor = $typeColors[$annonce->type_annonce] ?? '#6b7280';

        $dateGeneration = now()->format('d/m/Y à H:i:s');
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

    <!-- TITRE DU DOCUMENT -->
    <div style="text-align: center; padding: 15px 0; margin: 20px 0; border-bottom: 3px solid {{ $typeColor }};">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            ANNONCE
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            {{ htmlspecialchars($annonce->titre) }} - Généré le {{ $dateGeneration }}
        </p>
    </div>

    <!-- BADGES PRINCIPAUX -->
    <div style="margin-bottom: 25px; text-align: center;">
        <span style="display: inline-block; padding: 6px 12px; margin: 0 5px; border-radius: 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; background-color: {{ $statutColor['bg'] }}; color: {{ $statutColor['text'] }};">
            {{ ucfirst($annonce->statut) }}
        </span>
        <span style="display: inline-block; padding: 6px 12px; margin: 0 5px; border-radius: 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; background-color: {{ $prioriteColor['bg'] }}; color: {{ $prioriteColor['text'] }};">
            Priorité: {{ ucfirst($annonce->niveau_priorite) }}
        </span>
        <span style="display: inline-block; padding: 6px 12px; margin: 0 5px; border-radius: 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; background-color: #e0f2fe; color: #0c4a6e;">
            {{ \App\Models\Annonce::getTypesAnnonces()[$annonce->type_annonce] ?? ucfirst($annonce->type_annonce) }}
        </span>
    </div>

    <!-- INFORMATIONS PRINCIPALES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations Principales
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <tbody>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold; width: 25%;">Titre</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ htmlspecialchars($annonce->titre) }}</td>
                    </tr>
                    <tr style="background-color: white;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Type</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ \App\Models\Annonce::getTypesAnnonces()[$annonce->type_annonce] ?? ucfirst($annonce->type_annonce) }}</td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Audience cible</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ \App\Models\Annonce::getAudiencesCibles()[$annonce->audience_cible] ?? ucfirst($annonce->audience_cible) }}</td>
                    </tr>
                    <tr style="background-color: white;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Date de création</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ $annonce->created_at->format('d/m/Y à H:i') }}</td>
                    </tr>
                    @if($annonce->publie_le)
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Date de publication</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ $annonce->publie_le->format('d/m/Y à H:i') }}</td>
                    </tr>
                    @endif
                    @if($annonce->expire_le)
                    <tr style="background-color: white;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Date d'expiration</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ $annonce->expire_le->format('d/m/Y à H:i') }}</td>
                    </tr>
                    @endif
                    @if($annonce->auteur)
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #6b7280; font-weight: bold;">Créé par</td>
                        <td style="padding: 8px; border: 1px solid #d1d5db; color: #1f2937;">{{ htmlspecialchars($annonce->auteur->nom . ' ' . $annonce->auteur->prenom) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- DÉTAILS DE L'ÉVÉNEMENT -->
    @if($annonce->type_annonce === 'evenement' && ($annonce->date_evenement || $annonce->lieu_evenement))
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #10b981; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Détails de l'Événement
        </h2>
        <div style="background-color: #f0fdf4; border: 1px solid #86efac; border-radius: 4px; padding: 15px;">
            <div style="width: 100%;">
                @if($annonce->date_evenement)
                <div style="float: left; width: 48%; margin-right: 4%;">
                    <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase; margin-bottom: 3px;">Date de l'événement</div>
                    <div style="font-size: 10px; color: #1f2937; font-weight: bold;">{{ \Carbon\Carbon::parse($annonce->date_evenement)->format('d/m/Y') }}</div>
                </div>
                @endif
                @if($annonce->lieu_evenement)
                <div style="float: left; width: 48%;">
                    <div style="font-size: 7px; color: #6b7280; font-weight: 500; text-transform: uppercase; margin-bottom: 3px;">Lieu</div>
                    <div style="font-size: 10px; color: #1f2937; font-weight: bold;">{{ htmlspecialchars($annonce->lieu_evenement) }}</div>
                </div>
                @endif
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- CONTENU DE L'ANNONCE -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Contenu de l'Annonce
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <div style="font-size: 9px; line-height: 1.6; color: #374151;">
                {!! $annonce->getContenuForPdf() !!}
            </div>
        </div>
    </div>

    <!-- CONTACT PRINCIPAL -->
    @if($annonce->contactPrincipal)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Contact Principal
        </h2>
        <div style="background-color: #eef2ff; border: 1px solid #c7d2fe; border-radius: 4px; padding: 15px;">
            <div style="font-size: 9px; line-height: 1.6; color: #374151;">
                <div style="margin-bottom: 5px;"><strong>Nom:</strong> {{ htmlspecialchars($annonce->contactPrincipal->nom . ' ' . $annonce->contactPrincipal->prenom) }}</div>
                @if($annonce->contactPrincipal->email)
                <div style="margin-bottom: 5px;"><strong>Email:</strong> {{ htmlspecialchars($annonce->contactPrincipal->email) }}</div>
                @endif
                @if($annonce->contactPrincipal->telephone_1)
                <div style="margin-bottom: 5px;"><strong>Téléphone:</strong> {{ htmlspecialchars($annonce->contactPrincipal->telephone_1) }}</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- PARAMÈTRES DE DIFFUSION -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #f59e0b; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Paramètres de Diffusion
        </h2>
        <div style="width: 100%;">
            <div style="float: left; width: 48%; margin-right: 4%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 5px; font-weight: 500; text-transform: uppercase;">Site Web</div>
                <div style="font-size: 11px; font-weight: bold; color: {{ $annonce->afficher_site_web ? '#059669' : '#6b7280' }};">
                    {{ $annonce->afficher_site_web ? 'Oui' : 'Non' }}
                </div>
            </div>
            <div style="float: left; width: 48%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 5px; font-weight: 500; text-transform: uppercase;">Annoncer au Culte</div>
                <div style="font-size: 11px; font-weight: bold; color: {{ $annonce->annoncer_culte ? '#059669' : '#6b7280' }};">
                    {{ $annonce->annoncer_culte ? 'Oui' : 'Non' }}
                </div>
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
