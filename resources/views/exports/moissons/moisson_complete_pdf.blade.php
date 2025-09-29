{{-- resources/views/exports/moissons/moisson_complete_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Moisson - {{ $donnees['informations_generales']['theme'] }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.3; color: #333; margin: 10mm 8mm 12mm 8mm;">

    <!-- EN-TÊTE STRUCTURE -->
    <div style="background-color: #1e40af; color: white; padding: 15px; margin: -10mm -8mm 20px -8mm; border-bottom: 4px solid #f59e0b; overflow: hidden;">
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

    <!-- Titre principal -->
    <div style="text-align: center; font-size: 16px; font-weight: bold; color: #2E74B5; margin: 12px 0 8px 0; padding: 12px 8px; background-color: #f5f7fa; border: 2px solid #2E74B5;">
        RAPPORT DÉTAILLÉ DE MOISSON
        <div style="font-size: 12px; font-weight: normal; color: #555; margin-top: 4px;">
            {{ $donnees['informations_generales']['theme'] }}
        </div>
    </div>

    <!-- Performance Overview -->
    <div style="background-color: #e8f5e8; padding: 12px; margin: 10px 0; border: 1px solid #4CAF50;">
        <div style="font-size: 12px; font-weight: bold; color: #2E7D32; margin-bottom: 8px; text-align: center;">
            Performance de la Moisson
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 8px 0;">
            <tr>
                <td style="text-align: center; background-color: white; padding: 6px 4px; border: 1px solid #e0e0e0; width: 25%;">
                    <div style="font-size: 11px; font-weight: bold; color: #1976D2;">
                        {{ number_format($donnees['objectifs_et_realisations']['objectif_initial'], 0, ',', ' ') }} FCFA
                    </div>
                    <div style="font-size: 7px; color: #666; margin-top: 2px;">Objectif Initial</div>
                </td>
                <td style="text-align: center; background-color: white; padding: 6px 4px; border: 1px solid #e0e0e0; width: 25%;">
                    <div style="font-size: 11px; font-weight: bold; color: #1976D2;">
                        {{ number_format($donnees['objectifs_et_realisations']['montant_collecte'], 0, ',', ' ') }} FCFA
                    </div>
                    <div style="font-size: 7px; color: #666; margin-top: 2px;">Montant Collecté</div>
                </td>
                <td style="text-align: center; background-color: white; padding: 6px 4px; border: 1px solid #e0e0e0; width: 25%;">
                    <div style="font-size: 11px; font-weight: bold; color: #1976D2;">
                        {{ $donnees['objectifs_et_realisations']['pourcentage_realisation'] }}%
                    </div>
                    <div style="font-size: 7px; color: #666; margin-top: 2px;">Taux Réalisation</div>
                </td>
                <td style="text-align: center; background-color: white; padding: 6px 4px; border: 1px solid #e0e0e0; width: 25%;">
                    <div style="font-size: 11px; font-weight: bold; color: #1976D2;">
                        {{ $donnees['objectifs_et_realisations']['statut_progression'] }}
                    </div>
                    <div style="font-size: 7px; color: #666; margin-top: 2px;">Statut</div>
                </td>
            </tr>
        </table>

        <!-- Barre de progression -->
        <div style="margin: 8px 0;">
            <div style="width: 100%; height: 16px; background-color: #e9ecef; border: 1px solid #ddd; position: relative;">
                <div style="height: 100%; background-color: #28a745; width: {{ min($donnees['objectifs_et_realisations']['pourcentage_realisation'], 100) }}%; color: white; font-weight: bold; font-size: 9px; text-align: center; line-height: 14px;">
                    {{ $donnees['objectifs_et_realisations']['pourcentage_realisation'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Informations générales -->
    <div style="background-color: #f8f9fa; padding: 10px; margin-bottom: 8px; border-left: 3px solid #4472C4;">
        <div style="font-weight: bold; color: #2E74B5; margin-bottom: 6px; font-size: 10px;">
            Informations Générales
        </div>
        <div style="font-size: 9px; line-height: 1.4;">
            <span style="font-weight: bold; color: #555;">Date:</span> {{ $donnees['informations_generales']['date'] }} •
            <span style="font-weight: bold; color: #555;">Culte:</span> {{ $donnees['informations_generales']['culte'] }} •
            <span style="font-weight: bold; color: #555;">Créateur:</span> {{ $donnees['informations_generales']['createur'] }} •
            <span style="font-weight: bold; color: #555;">Statut:</span>
            @php
                $statutBg = $donnees['informations_generales']['statut'] === 'Actif' ? '#d4edda' : '#f8d7da';
                $statutColor = $donnees['informations_generales']['statut'] === 'Actif' ? '#155724' : '#721c24';
            @endphp
            <span style="padding: 2px 4px; font-size: 7px; font-weight: bold; background-color: {{ $statutBg }}; color: {{ $statutColor }};">
                {{ $donnees['informations_generales']['statut'] }}
            </span> •
            <span style="font-weight: bold; color: #555;">Créé le:</span> {{ $donnees['informations_generales']['date_creation'] }}
        </div>
    </div>

    <!-- Résumé financier -->
    <div style="background-color: #e8f5e8; padding: 10px; margin-bottom: 8px; border-left: 3px solid #4CAF50;">
        <div style="font-weight: bold; color: #2E7D32; margin-bottom: 6px; font-size: 10px;">
            Résumé Financier
        </div>
        <div style="font-size: 9px; line-height: 1.4;">
            <span style="font-weight: bold; color: #555;">Reste à collecter:</span>
            <span style="font-family: 'Courier New', monospace; font-weight: bold; color: #d32f2f;">
                {{ number_format($donnees['objectifs_et_realisations']['reste_a_collecter'], 0, ',', ' ') }} FCFA
            </span> •
            <span style="font-weight: bold; color: #555;">Montant supplémentaire:</span>
            <span style="font-family: 'Courier New', monospace; font-weight: bold; color: #388e3c;">
                {{ number_format($donnees['objectifs_et_realisations']['montant_supplementaire'], 0, ',', ' ') }} FCFA
            </span> •
            <span style="font-weight: bold; color: #555;">Dernière modification:</span> {{ $donnees['informations_generales']['derniere_modification'] }}
        </div>
    </div>

    <!-- Passages bibliques -->
    @if(!empty($donnees['passages_bibliques']))
    <div style="background-color: #fff8e1; padding: 8px; border-left: 3px solid #ff9800; margin: 8px 0;">
        <div style="font-weight: bold; color: #f57c00; margin-bottom: 4px; font-size: 9px;">
            Passages Bibliques de Référence
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            @php $passages = collect($donnees['passages_bibliques'])->chunk(2); @endphp
            @foreach($passages as $row)
                <tr>
                    @foreach($row as $passage)
                        <td style="background-color: white; padding: 4px 6px; border: 1px solid #ffcc02; font-style: italic; font-size: 8px; width: 50%;">
                            @if(is_array($passage))
                                {{ $passage['livre'] ?? '' }} {{ $passage['chapitre'] ?? '' }}:{{ $passage['verset_debut'] ?? '' }}@if(!empty($passage['verset_fin']))-{{ $passage['verset_fin'] }}@endif
                            @else
                                {{ $passage }}
                            @endif
                        </td>
                    @endforeach
                    @if($row->count() === 1)
                        <td style="background-color: white; padding: 4px 6px; border: 1px solid #ffcc02;"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Résumé des activités -->
    <div style="background-color: #e3f2fd; padding: 8px; border: 1px solid #2196F3; margin: 8px 0;">
        <div style="font-weight: bold; color: #1976D2; font-size: 9px; margin-bottom: 4px;">
            Résumé des Activités
        </div>
        <div style="font-size: 8px;">
            Passages: <strong>{{ count($donnees['detail_passages']) }}</strong> •
            Ventes: <strong>{{ count($donnees['detail_ventes']) }}</strong> •
            Engagements: <strong>{{ count($donnees['detail_engagements']) }}</strong>
            @if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0)
                • <span style="color: #dc3545;">En retard: <strong>{{ collect($donnees['detail_engagements'])->where('en_retard', true)->count() }}</strong></span>
            @endif
        </div>
    </div>

    <!-- Section Passages -->
    <div style="margin: 8px 0 4px 0;">
        <div style="font-size: 11px; font-weight: bold; color: #2E74B5; margin-bottom: 4px; padding: 4px 6px; background-color: #f0f4f8; border-left: 4px solid #4472C4;">
            Détail des Passages ({{ count($donnees['detail_passages']) }})
        </div>

        @if(count($donnees['detail_passages']) > 0)
            <table style="width: 100%; border-collapse: collapse; margin: 6px 0; font-size: 8px;">
                <thead>
                    <tr>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 20%;">CATÉGORIE</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">CLASSE</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">OBJECTIF (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">COLLECTÉ (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">ÉVOLUTION</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">COLLECTEUR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_passages'] as $index => $passage)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#f8f9fa' : 'white' }};">
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $passage['categorie'] }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $passage['classe'] ?? 'N/A' }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($passage['objectif'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($passage['collecte'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: center; font-weight: bold;">{{ $passage['pourcentage'] }}%</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $passage['collecteur'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 15px; color: #999; font-style: italic; background-color: #f8f9fa; border: 2px dashed #ddd; font-size: 9px;">
                Aucun passage enregistré
            </div>
        @endif
    </div>

    <!-- Section Ventes -->
    <div style="margin: 8px 0 4px 0;">
        <div style="font-size: 11px; font-weight: bold; color: #2E74B5; margin-bottom: 4px; padding: 4px 6px; background-color: #f0f4f8; border-left: 4px solid #4472C4;">
            Détail des Ventes ({{ count($donnees['detail_ventes']) }})
        </div>

        @if(count($donnees['detail_ventes']) > 0)
            <table style="width: 100%; border-collapse: collapse; margin: 6px 0; font-size: 8px;">
                <thead>
                    <tr>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">CATÉGORIE</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 20%;">DESCRIPTION</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">OBJECTIF (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">COLLECTÉ (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">ÉVOLUTION</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">COLLECTEUR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_ventes'] as $index => $vente)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#f8f9fa' : 'white' }};">
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $vente['categorie'] }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $vente['description'] ?? 'N/A' }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($vente['objectif'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($vente['collecte'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: center; font-weight: bold;">{{ $vente['pourcentage'] }}%</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $vente['collecteur'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 15px; color: #999; font-style: italic; background-color: #f8f9fa; border: 2px dashed #ddd; font-size: 9px;">
                Aucune vente enregistrée
            </div>
        @endif
    </div>

    <!-- Section Engagements -->
    <div style="margin: 8px 0 4px 0;">
        <div style="font-size: 11px; font-weight: bold; color: #2E74B5; margin-bottom: 4px; padding: 4px 6px; background-color: #f0f4f8; border-left: 4px solid #4472C4;">
            Détail des Engagements ({{ count($donnees['detail_engagements']) }})
        </div>

        @if(count($donnees['detail_engagements']) > 0)
            <table style="width: 100%; border-collapse: collapse; margin: 6px 0; font-size: 8px;">
                <thead>
                    <tr>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">TYPE</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 20%;">DONATEUR</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">OBJECTIF (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">COLLECTÉ (FCFA)</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 8%;">ÉVOLUTION</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 12%;">ÉCHÉANCE</th>
                        <th style="background-color: #4472C4; color: white; padding: 6px 3px; text-align: center; font-weight: bold; border: 1px solid #2E74B5; font-size: 7px; width: 10%;">CONTACT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees['detail_engagements'] as $index => $engagement)
                        @php
                            $rowBg = $engagement['en_retard'] ? '#f8d7da' : ($index % 2 === 0 ? '#f8f9fa' : 'white');
                        @endphp
                        <tr style="background-color: {{ $rowBg }};">
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $engagement['categorie'] }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">{{ $engagement['donateur'] ?? $engagement['nom_entite'] }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($engagement['objectif'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: right; font-family: 'Courier New', monospace; font-weight: bold;">{{ number_format($engagement['collecte'], 0, ',', ' ') }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: center; font-weight: bold;">{{ $engagement['pourcentage'] }}%</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; text-align: center; vertical-align: top;">{{ $engagement['date_echeance'] ?? 'N/A' }}</td>
                            <td style="padding: 4px 3px; border: 1px solid #ddd; font-size: 8px; vertical-align: top;">
                                @if($engagement['telephone']){{ $engagement['telephone'] }}<br>@endif
                                @if($engagement['email']){{ $engagement['email'] }}@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(collect($donnees['detail_engagements'])->where('en_retard', true)->count() > 0)
                <div style="background-color: #f8d7da; border: 1px solid #dc3545; padding: 6px 8px; margin: 6px 0; font-size: 8px; color: #721c24;">
                    <strong>⚠️ {{ collect($donnees['detail_engagements'])->where('en_retard', true)->count() }} engagement(s) en retard</strong> nécessitent un suivi urgent.
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 15px; color: #999; font-style: italic; background-color: #f8f9fa; border: 2px dashed #ddd; font-size: 9px;">
                Aucun engagement enregistré
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div style="background-color: #37393b; color: white; padding: 10px 15px; font-size: 7px; border-top: 3px solid #3b82f6; margin: 30px -8mm -12mm -8mm;">
        <div style="text-align: center; font-style: italic; color: #fbbf24; margin-bottom: 8px; font-size: 8px; line-height: 1.3; padding-bottom: 8px; border-bottom: 1px solid #4b5563;">
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                "{{ htmlspecialchars($AppParametres->verset_biblique) }}" - {{ htmlspecialchars($AppParametres->reference_verset) }}
            @else
                "Celui qui sème peu moissonnera peu, et celui qui sème abondamment moissonnera abondamment" - 2 Corinthiens 9:6
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
                    Site web: {{ htmlspecialchars($AppParametres->website_url)) }} |
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
