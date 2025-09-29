<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Classe - {{ $classe->nom }}</title>
</head>

<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; line-height: 1.3; color: #1f2937; margin: 0; padding: 0;">

    @php
        // Calcul des statistiques globales
        $totalMembres = $stats['total_membres'] ?? 0;
        $totalResponsables = $stats['total_responsables'] ?? 0;
        $membresSimples = $stats['membres_simples'] ?? 0;
        $agesCouverts = $stats['ages_couverts'] ?? 'N/A';
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

    <!-- TITRE DU RAPPORT -->
    <div style="text-align: center; padding: 15px 0; margin: 20px 0; border-bottom: 3px solid #3b82f6;">
        <h1 style="color: #1f2937; font-size: 18px; margin: 0 0 8px 0; font-weight: bold;">
            GESTION DES CLASSES
        </h1>
        <p style="color: #6b7280; font-size: 10px; margin: 0;">
            Détails de la classe : {{ htmlspecialchars($classe->nom) }} - Généré le {{ $dateGeneration }}
        </p>
    </div>

    <!-- STATISTIQUES RÉSUMÉES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Indicateurs de Performance
        </h2>
        <div style="width: 100%; margin-bottom: 15px;">
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">TOTAL MEMBRES</div>
                <div style="font-size: 12px; font-weight: bold; color: #3b82f6;">{{ number_format($totalMembres, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">RESPONSABLES</div>
                <div style="font-size: 12px; font-weight: bold; color: #059669;">{{ number_format($totalResponsables, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-right: 2%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">MEMBRES SIMPLES</div>
                <div style="font-size: 12px; font-weight: bold; color: #7c3aed;">{{ number_format($membresSimples, 0, ',', ' ') }}</div>
            </div>
            <div style="float: left; width: 21%; margin-bottom: 10px; background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px; text-align: center; box-sizing: border-box;">
                <div style="font-size: 8px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">ÂGES COUVERTS</div>
                <div style="font-size: 12px; font-weight: bold; color: #f59e0b;">{{ $agesCouverts }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- INFORMATIONS DE LA CLASSE -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Informations de la Classe
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Nom</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Tranche d'âge</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Âge Min/Max</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Total Membres</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Responsables</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Date création</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;"><strong>{{ $classe->nom }}</strong></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $classe->tranche_age ?: 'Non spécifiée' }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $classe->age_minimum ?: 'N/A' }} - {{ $classe->age_maximum ?: 'N/A' }} ans</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center; background-color: #fef3c7;"><strong>{{ number_format($classe->nombre_inscrits) }}</strong></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $responsables->count() }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $classe->created_at->format('d/m/Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DESCRIPTION -->
    @if($classe->description)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Description
        </h2>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 15px;">
            <div style="font-size: 9px; line-height: 1.4; color: #374151;">{{ nl2br(htmlspecialchars($classe->description)) }}</div>
        </div>
    </div>
    @endif

    <!-- PROGRAMME -->
    @if($classe->programme && count($classe->programme) > 0)
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #6366f1; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Programme de la Classe
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center; width: 10%;">N°</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left; width: 90%;">Élément du Programme</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classe->programme as $index => $element)
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;">{{ htmlspecialchars($element) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- RESPONSABLES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #dc2626; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Responsables de la Classe
        </h2>

        @if($responsables->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Nom Complet</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Responsabilité</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Supérieur</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Email</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Téléphone</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Ville</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($responsables as $index => $responsable)
                    @php
                        $responsableData = collect($classe->responsables)->firstWhere('id', $responsable->id);
                        $isSuperieur = $responsableData['superieur'] ?? false;
                    @endphp
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;"><strong>{{ htmlspecialchars($responsable->nom_complet) }}</strong></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars(ucfirst($responsableData['responsabilite'] ?? '')) }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center;">
                            @if($isSuperieur)
                                <span style="background-color: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 8px; font-size: 7px; font-weight: bold;">OUI</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;">{{ htmlspecialchars($responsable->email ?: 'Non renseigné') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars($responsable->telephone_1 ?: 'Non renseigné') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars($responsable->ville ?: 'Non renseignée') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            <strong>Aucun responsable assigné</strong><br>
            Aucun responsable n'est assigné à cette classe.
        </div>
        @endif
    </div>

    <!-- MEMBRES -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #7c3aed; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Membres de la Classe
        </h2>

        @if($membres->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 7px; background-color: white;">
                <thead>
                    <tr>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Nom Complet</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Statut</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: left;">Email</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Téléphone</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Ville</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Âge</th>
                        <th style="background-color: #f3f4f6; color: #374151; font-weight: bold; padding: 6px 3px; border: 1px solid #d1d5db; text-align: center;">Sexe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membres as $index => $membre)
                    @php
                        $age = $membre->date_naissance ? $membre->date_naissance->diffInYears(now()) . ' ans' : 'N/A';
                        $adresse = $membre->ville ?: ($membre->adresse_ligne_1 ?: 'Non renseignée');

                        $statutColors = [
                            'actif' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                            'inactif' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                            'visiteur' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        ];
                        $statut = $membre->statut_membre ?: 'visiteur';
                        $statutColor = $statutColors[$statut] ?? ['bg' => '#fef3c7', 'text' => '#92400e'];
                    @endphp
                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : 'white' }};">
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;"><strong>{{ htmlspecialchars($membre->nom_complet) }}</strong></td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; text-align: center;">
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 7px; font-weight: bold; text-transform: uppercase; background-color: {{ $statutColor['bg'] }}; color: {{ $statutColor['text'] }};">
                                {{ htmlspecialchars(ucfirst($statut)) }}
                            </span>
                        </td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937;">{{ htmlspecialchars($membre->email ?: 'Non renseigné') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars($membre->telephone_1 ?: 'Non renseigné') }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars($adresse) }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ $age }}</td>
                        <td style="padding: 4px 3px; border: 1px solid #d1d5db; color: #1f2937; text-align: center;">{{ htmlspecialchars(ucfirst($membre->sexe ?? 'N/A')) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="color: #6b7280; font-style: italic; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 4px;">
            <strong>Aucun membre inscrit</strong><br>
            Aucun membre n'est inscrit dans cette classe.
        </div>
        @endif
    </div>

    <!-- RÉSUMÉ DE LA CLASSE -->
    <div style="margin-bottom: 25px;">
        <h2 style="background-color: #059669; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold; margin: 0 0 12px 0; border-radius: 3px;">
            Résumé de la Classe
        </h2>
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 15px;">
            <div style="font-size: 9px; line-height: 1.4; color: #374151;">
                <p style="margin: 0 0 6px 0;">
                    <strong>Composition:</strong> La classe "{{ $classe->nom }}" compte {{ number_format($classe->nombre_inscrits) }} membre(s) au total, dont {{ $responsables->count() }} responsable(s) et {{ ($membres->count() - $responsables->count()) }} membre(s) simple(s).
                </p>

                @if($classe->tranche_age)
                <p style="margin: 0 0 6px 0;">
                    <strong>Tranche d'âge:</strong> Cette classe est destinée à la tranche d'âge "{{ htmlspecialchars($classe->tranche_age) }}"
                    @if($classe->age_minimum || $classe->age_maximum)
                        (âges de {{ $classe->age_minimum ?: 'tout âge' }} à {{ $classe->age_maximum ?: 'tout âge' }} ans)
                    @endif
                    .
                </p>
                @endif

                @php
                    $superieur = $responsables->where('superieur', true)->first();
                @endphp
                @if($superieur)
                <p style="margin: 0 0 6px 0;">
                    <strong>Responsable principal:</strong> {{ htmlspecialchars($superieur->nom_complet) }} assure la supervision générale de la classe.
                </p>
                @endif

                <p style="margin: 0;">
                    <strong>Création:</strong> Cette classe a été créée le {{ $classe->created_at->format('d/m/Y') }} et est active depuis {{ $classe->created_at->diffInDays(now()) }} jour(s).
                </p>
            </div>
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


