<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu Fiscal - {{ $fonds->numero_recu }}</title>
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

        /* EN-TÊTE STRUCTURE - IDENTIQUE AUX AUTRES RAPPORTS */
        .structure-header {
            background-color: #1e40af;
            color: white;
            padding: 15px;
            margin: -1cm -1cm 20px -1cm;
            border-bottom: 4px solid #f59e0b;
            overflow: hidden;
        }

        .structure-header-content {
            width: 100%;
        }

        .structure-header-left {
            float: left;
            width: 48%;
        }

        .structure-header-right {
            float: right;
            width: 48%;
            text-align: right;
        }

        .logo-section {
            float: left;
            width: 70px;
            margin-right: 10px;
        }

        .info-left {
            margin-left: 80px;
        }

        .structure-logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 8px;
            padding: 5px;
            text-align: center;
            line-height: 60px;
        }

        .structure-logo img {
            max-width: 50px;
            max-height: 50px;
            vertical-align: middle;
        }

        .structure-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .structure-contact {
            font-size: 7px;
            line-height: 1.6;
            color: white;
        }

        .structure-contact div {
            margin: 3px 0;
            word-wrap: break-word;
        }

        /* Clearfix pour le float */
        .structure-header-content:after {
            content: "";
            display: table;
            clear: both;
        }

        /* TITRE RAPPORT */
        .report-title {
            text-align: center;
            padding: 15px 0;
            margin: 20px 0;
            border-bottom: 3px solid #3b82f6;
        }

        .report-title h1 {
            color: #1f2937;
            font-size: 18px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .report-title .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin: 0;
        }

        /* SECTIONS */
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

        .section-title.montant {
            background-color: #059669;
        }

        .section-title.transaction {
            background-color: #7c3aed;
        }

        .section-title.legal {
            background-color: #dc2626;
        }

        .section-title.signatures {
            background-color: #6366f1;
        }

        /* TABLEAUX */
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

        /* BOÎTES D'INFORMATION */
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-block {
            float: left;
            width: 48%;
            margin-right: 2%;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            padding: 10px;
            box-sizing: border-box;
        }

        .info-block:nth-child(2n) {
            margin-right: 0;
        }

        .info-grid:after {
            content: "";
            display: table;
            clear: both;
        }

        .info-block h3 {
            color: #1f2937;
            font-size: 10px;
            margin-bottom: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-block p {
            color: #374151;
            margin-bottom: 3px;
            font-size: 8px;
            line-height: 1.4;
        }

        .info-block strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* SECTION MONTANT PRINCIPAL */
        .amount-section {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .amount-label {
            font-size: 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .amount-words {
            font-size: 11px;
            font-style: italic;
            opacity: 0.95;
        }

        /* MENTION LÉGALE */
        .legal-notice {
            background-color: #fef2f2;
            border: 2px solid #dc2626;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            page-break-inside: avoid;
        }

        .legal-notice h3 {
            color: #991b1b;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .legal-notice p {
            color: #7f1d1d;
            font-size: 9px;
            line-height: 1.5;
        }

        /* SIGNATURES */
        .signatures-grid {
            width: 100%;
            margin: 20px 0;
        }

        .signature-block {
            float: left;
            width: 48%;
            margin-right: 2%;
            text-align: center;
            padding: 15px;
            border: 2px dashed #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .signature-block:nth-child(2n) {
            margin-right: 0;
        }

        .signatures-grid:after {
            content: "";
            display: table;
            clear: both;
        }

        .signature-block h4 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .signature-line {
            border-bottom: 2px solid #475569;
            height: 40px;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 8px;
            color: #64748b;
            font-weight: 500;
        }

        /* FOOTER */
        .footer {
            background-color: #37393b;
            color: white;
            padding: 10px 15px;
            font-size: 7px;
            border-top: 3px solid #3b82f6;
            margin: 30px -1cm -1cm -1cm;
        }

        .footer-verse {
            text-align: center;
            font-style: italic;
            color: #fbbf24;
            margin-bottom: 8px;
            font-size: 8px;
            line-height: 1.3;
            padding-bottom: 8px;
            border-bottom: 1px solid #4b5563;
        }

        .footer-content {
            text-align: center;
        }

        .footer-social {
            margin-bottom: 5px;
        }

        .footer-platform {
            font-size: 7px;
            color: #9ca3af;
        }

        /* AUTRES STYLES */
        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
        }

        .number {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- EN-TÊTE STRUCTURE -->
    <div class="structure-header">
        <div class="structure-header-content">
            <!-- PARTIE GAUCHE: Logo + Nom + Téléphones -->
            <div class="structure-header-left">
                <div class="logo-section">
                    @if(!empty($egliseInfo['logo']))
                        @php
                            try {
                                $logoPath = storage_path('app/public/' . $egliseInfo['logo']);

                                if (file_exists($logoPath)) {
                                    $imageData = base64_encode(file_get_contents($logoPath));
                                    $imageExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));

                                    $mimeTypes = [
                                        'jpg' => 'jpeg',
                                        'jpeg' => 'jpeg',
                                        'png' => 'png',
                                        'gif' => 'gif',
                                        'svg' => 'svg+xml',
                                        'webp' => 'webp'
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
                            <div class="structure-logo">
                                <img src="{{ $logoBase64 }}" alt="Logo">
                            </div>
                        @else
                            <div class="structure-logo" style="font-size: 24px; font-weight: bold; color: #3b82f6;">
                                {{ strtoupper(substr($egliseInfo['nom'], 0, 2)) }}
                            </div>
                        @endif
                    @else
                        <div class="structure-logo" style="font-size: 24px; font-weight: bold; color: #3b82f6;">
                            {{ strtoupper(substr($egliseInfo['nom'], 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div class="info-left">
                    <div class="structure-name">{{ htmlspecialchars($egliseInfo['nom']) }}</div>
                    <div class="structure-contact">
                        @if(!empty($egliseInfo['telephone']))
                            <div><strong>Tel:</strong> {{ htmlspecialchars($egliseInfo['telephone']) }}</div>
                        @endif
                        @if(!empty($egliseInfo['telephone_2']))
                            <div><strong>Tel 2:</strong> {{ htmlspecialchars($egliseInfo['telephone_2']) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PARTIE DROITE: Email + Adresse -->
            <div class="structure-header-right">
                <div class="structure-contact">
                    @if(!empty($egliseInfo['email']))
                        <div><strong>Email:</strong> {{ htmlspecialchars($egliseInfo['email']) }}</div>
                    @endif
                    @if(!empty($egliseInfo['adresse']))
                        <div>
                            <strong>Adresse:</strong>
                            {{ htmlspecialchars($egliseInfo['adresse']) }}
                        </div>
                    @endif
                    @if(!empty($egliseInfo['website']))
                        <div><strong>Site web:</strong> {{ htmlspecialchars($egliseInfo['website']) }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TITRE DU RAPPORT -->
    <div class="report-title">
        <h1>REÇU FISCAL POUR DON</h1>
        <p class="subtitle">N° {{ $fonds->numero_recu }} - Émis le {{ $fonds->date_emission_recu?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
    </div>

    <!-- INFORMATIONS PRINCIPALES -->
    <div class="section">
        <h2 class="section-title info">Informations du Reçu</h2>
        <div class="info-grid">
            <div class="info-block">
                <h3>Informations du Reçu</h3>
                <p><strong>N° Reçu:</strong> {{ $fonds->numero_recu }}</p>
                <p><strong>Date d'émission:</strong> {{ $fonds->date_emission_recu?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
                <p><strong>N° Transaction:</strong> {{ $fonds->numero_transaction }}</p>
                <p><strong>Exercice fiscal:</strong> {{ $fonds->date_transaction->year }}</p>
            </div>

            <div class="info-block">
                <h3>Informations du Donateur</h3>
                <p><strong>Nom complet:</strong> {{ $fonds->nom_donateur }}</p>
                @if($fonds->donateur)
                    <p><strong>Téléphone:</strong> {{ $fonds->donateur->telephone_1 }}</p>
                    @if($fonds->donateur->email)
                        <p><strong>Email:</strong> {{ $fonds->donateur->email }}</p>
                    @endif
                    <p><strong>Statut:</strong> {{ ucfirst($fonds->donateur->statut_membre) }}</p>
                @else
                    @if($fonds->contact_donateur)
                        <p><strong>Contact:</strong> {{ $fonds->contact_donateur }}</p>
                    @endif
                    <p><strong>Statut:</strong> {{ $fonds->est_membre ? 'Membre' : 'Visiteur' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION MONTANT -->
    <div class="section">
        <div class="amount-section">
            <div class="amount-label">Montant Total du Don</div>
            <div class="amount-value">{{ $fonds->montant_format }}</div>
            <div class="amount-words">{{ $montantEnLettres }}</div>
        </div>
    </div>

    <!-- DÉTAILS DE LA TRANSACTION -->
    <div class="section">
        <h2 class="section-title transaction">Détails de la Transaction</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Détail</th>
                        <th>Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Type de don</strong></td>
                        <td>{{ $fonds->type_transaction_libelle }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date de transaction</strong></td>
                        <td>{{ $fonds->date_transaction->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mode de paiement</strong></td>
                        <td>{{ $fonds->mode_paiement_libelle }}</td>
                    </tr>
                    @if($fonds->reference_paiement)
                    <tr>
                        <td><strong>Référence paiement</strong></td>
                        <td>{{ $fonds->reference_paiement }}</td>
                    </tr>
                    @endif
                    @if($fonds->culte)
                    <tr>
                        <td><strong>Culte associé</strong></td>
                        <td>{{ $fonds->culte->titre }} du {{ $fonds->culte->date_culte->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    @if($fonds->collecteur)
                    <tr>
                        <td><strong>Collecteur</strong></td>
                        <td>{{ $fonds->collecteur->prenom }} {{ $fonds->collecteur->nom }}</td>
                    </tr>
                    @endif
                    @if($fonds->validateur)
                    <tr>
                        <td><strong>Validé par</strong></td>
                        <td>{{ $fonds->validateur->prenom }} {{ $fonds->validateur->nom }}</td>
                    </tr>
                    @endif
                    @if($fonds->projet)
                    <tr>
                        <td><strong>Projet bénéficiaire</strong></td>
                        <td>{{ $fonds->projet->nom_projet }}</td>
                    </tr>
                    @endif
                    @if($fonds->destination)
                    <tr>
                        <td><strong>Destination</strong></td>
                        <td>{{ $fonds->destination }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- DON EN NATURE -->
    @if($fonds->est_don_nature)
    <div class="section">
        <h2 class="section-title transaction">Détails du Don en Nature</h2>
        <div class="table-container">
            <table>
                <tbody>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td>{{ $fonds->description_don_nature }}</td>
                    </tr>
                    @if($fonds->valeur_estimee)
                    <tr>
                        <td><strong>Valeur estimée</strong></td>
                        <td>{{ number_format($fonds->valeur_estimee, 0, ',', ' ') }} {{ $fonds->devise }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- INSTRUCTIONS DU DONATEUR -->
    @if($fonds->instructions_donateur)
    <div class="section">
        <div class="summary-box">
            <strong>Instructions du Donateur:</strong>
            <div style="margin-top: 5px; font-size: 9px;">{{ nl2br(htmlspecialchars($fonds->instructions_donateur)) }}</div>
        </div>
    </div>
    @endif

    <!-- MENTION LÉGALE -->
    <div class="section">
        <div class="legal-notice">
            <h3>⚠ Mention Légale</h3>
            <p>
                Ce reçu atteste que la somme mentionnée ci-dessus a été versée à {{ $egliseInfo['nom'] }},
                organisme d'intérêt général reconnu par l'État de Côte d'Ivoire. Ce don ouvre droit à une réduction
                d'impôt dans les conditions prévues par la législation fiscale en vigueur.
                Conservez précieusement ce document pour votre déclaration fiscale.
            </p>
        </div>
    </div>

    <!-- SIGNATURES -->
    <div class="section">
        <h2 class="section-title signatures">Signatures</h2>
        <div class="signatures-grid">
            <div class="signature-block">
                <h4>Le Donateur</h4>
                <div class="signature-line"></div>
                <p class="signature-name">{{ $fonds->nom_donateur }}</p>
            </div>

            <div class="signature-block">
                <h4>Le Responsable Financier</h4>
                <div class="signature-line"></div>
                <p class="signature-name">
                    {{ $fonds->validateur ? $fonds->validateur->prenom . ' ' . $fonds->validateur->nom : 'Responsable Financier' }}
                </p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-verse">
            @if(!empty($egliseInfo['verset_biblique']) && !empty($egliseInfo['reference_verset']))
                "{{ htmlspecialchars($egliseInfo['verset_biblique']) }}" - {{ htmlspecialchars($egliseInfo['reference_verset']) }}
            @else
                "Car Dieu a tant aimé le monde qu'il a donné son Fils unique..." - Jean 3:16
            @endif
        </div>
        <div class="footer-content">
            <div class="footer-social">
                @if(!empty($egliseInfo['telephone']))
                    Tel: {{ htmlspecialchars($egliseInfo['telephone']) }} |
                @endif
                @if(!empty($egliseInfo['email']))
                    Email: {{ htmlspecialchars($egliseInfo['email']) }} |
                @endif
                @if(!empty($egliseInfo['website']))
                    Web: {{ htmlspecialchars($egliseInfo['website']) }}
                @endif
            </div>
            <div class="footer-platform">
                Généré le {{ now()->format('d/m/Y à H:i') }} | Système de gestion EgliseFlow v2.1
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
