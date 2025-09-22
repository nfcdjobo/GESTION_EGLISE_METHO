<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu Fiscal - <?php echo e($fonds->numero_recu); ?></title>
    <style>
        /* Reset et base - compatible PDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.4;
            color: #2c3e50;
            background: white;
            font-size: 12px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #3498db;
        }

        /* En-tête simplifié pour PDF */
        .header {
            background-color: #667eea;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background-color: #f093fb;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 60px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }

        .church-info h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .church-details {
            font-size: 11px;
        }

        .receipt-title {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .receipt-title h2 {
            font-size: 18px;
            font-weight: 600;
        }

        /* Corps principal */
        .main-content {
            padding: 20px;
        }

        .receipt-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .receipt-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-info td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }

        .info-block {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin-bottom: 10px;
        }

        .info-block h3 {
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .info-block p {
            color: #34495e;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .info-block strong {
            color: #2c3e50;
        }

        /* Section montant principal */
        .amount-section {
            background-color: #667eea;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-label {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .amount-words {
            font-size: 12px;
            font-style: italic;
        }

        /* Détails de la transaction */
        .transaction-details {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            margin: 20px 0;
        }

        .transaction-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .transaction-details th {
            background-color: #495057;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }

        .transaction-details td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }

        .transaction-details tr:nth-child(even) {
            background-color: #f1f3f4;
        }

        /* Mention légale */
        .legal-notice {
            background-color: #f093fb;
            color: white;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #e91e63;
        }

        .legal-notice h3 {
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .legal-notice p {
            font-size: 11px;
            line-height: 1.4;
        }

        /* Signatures */
        .signatures {
            width: 100%;
            margin: 30px 0;
        }

        .signatures table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-block {
            text-align: center;
            padding: 15px;
            border: 2px dashed #bdc3c7;
            background-color: #f8f9fa;
            width: 45%;
        }

        .signature-block h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .signature-line {
            border-bottom: 2px solid #34495e;
            height: 40px;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 10px;
            color: #7f8c8d;
        }

        /* Pied de page */
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .footer-content {
            width: 100%;
        }

        .footer-content table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-section {
            text-align: center;
            vertical-align: top;
            padding: 5px;
        }

        .footer-section h4 {
            font-size: 12px;
            margin-bottom: 5px;
            color: #ecf0f1;
            font-weight: 600;
        }

        .footer-section p {
            font-size: 10px;
            line-height: 1.3;
        }

        .qr-code {
            width: 50px;
            height: 50px;
            background-color: white;
            color: #2c3e50;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            line-height: 50px;
            margin: 0 auto;
        }

        /* Styles spécifiques pour l'impression PDF */
        @page {
            margin: 1cm;
            size: A4;
        }

        /* Éviter les coupures de page */
        .amount-section,
        .legal-notice,
        .signatures {
            page-break-inside: avoid;
        }

        /* Table responsive pour PDF */
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pdf-table td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- En-tête -->
        <div class="header">
            <div class="logo-section">
                <div class="logo"><?php echo e($egliseInfo['logo'] ?? 'Logo'); ?></div>
                <div class="church-info">
                    <h1><?php echo e($egliseInfo['nom']); ?></h1>
                    <div class="church-details">
                        <p><?php echo e($egliseInfo['adresse']); ?></p>
                        <p>Tél: <?php echo e($egliseInfo['telephone']); ?> | Email: <?php echo e($egliseInfo['email']); ?></p>
                        <p>Site web: <?php echo e($egliseInfo['website']); ?></p>
                    </div>
                </div>
            </div>
            <div class="receipt-title">
                <h2>REÇU FISCAL POUR DON</h2>
            </div>
        </div>

        <!-- Corps principal -->
        <div class="main-content">
            <!-- Informations du reçu -->
            <div class="receipt-info">
                <table class="pdf-table">
                    <tr>
                        <td>
                            <div class="info-block">
                                <h3>Informations du Reçu</h3>
                                <p><strong>N° Reçu:</strong> <?php echo e($fonds->numero_recu); ?></p>
                                <p><strong>Date d'émission:</strong> <?php echo e($fonds->date_emission_recu?->format('d/m/Y') ?? now()->format('d/m/Y')); ?></p>
                                <p><strong>N° Transaction:</strong> <?php echo e($fonds->numero_transaction); ?></p>
                                <p><strong>Exercice fiscal:</strong> <?php echo e($fonds->date_transaction->year); ?></p>
                            </div>
                        </td>
                        <td>
                            <div class="info-block">
                                <h3>Informations du Donateur</h3>
                                <p><strong>Nom complet:</strong> <?php echo e($fonds->nom_donateur); ?></p>
                                <?php if($fonds->donateur): ?>
                                    <p><strong>Téléphone:</strong> <?php echo e($fonds->donateur->telephone_1); ?></p>
                                    <?php if($fonds->donateur->email): ?>
                                        <p><strong>Email:</strong> <?php echo e($fonds->donateur->email); ?></p>
                                    <?php endif; ?>
                                    <p><strong>Statut:</strong> <?php echo e(ucfirst($fonds->donateur->statut_membre)); ?></p>
                                <?php else: ?>
                                    <?php if($fonds->contact_donateur): ?>
                                        <p><strong>Contact:</strong> <?php echo e($fonds->contact_donateur); ?></p>
                                    <?php endif; ?>
                                    <p><strong>Statut:</strong> <?php echo e($fonds->est_membre ? 'Membre' : 'Visiteur'); ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section montant principal -->
            <div class="amount-section">
                <div class="amount-label">MONTANT TOTAL DU DON</div>
                <div class="amount-value"><?php echo e($fonds->montant_format); ?></div>
                <div class="amount-words"><?php echo e($montantEnLettres); ?></div>
            </div>

            <!-- Détails de la transaction -->
            <div class="transaction-details">
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
                            <td><?php echo e($fonds->type_transaction_libelle); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date de transaction</strong></td>
                            <td><?php echo e($fonds->date_transaction->format('d/m/Y')); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Mode de paiement</strong></td>
                            <td><?php echo e($fonds->mode_paiement_libelle); ?></td>
                        </tr>
                        <?php if($fonds->reference_paiement): ?>
                        <tr>
                            <td><strong>Référence paiement</strong></td>
                            <td><?php echo e($fonds->reference_paiement); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($fonds->culte): ?>
                        <tr>
                            <td><strong>Culte associé</strong></td>
                            <td><?php echo e($fonds->culte->titre); ?> du <?php echo e($fonds->culte->date_culte->format('d/m/Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($fonds->collecteur): ?>
                        <tr>
                            <td><strong>Collecteur</strong></td>
                            <td><?php echo e($fonds->collecteur->prenom); ?> <?php echo e($fonds->collecteur->nom); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($fonds->validateur): ?>
                        <tr>
                            <td><strong>Validé par</strong></td>
                            <td><?php echo e($fonds->validateur->prenom); ?> <?php echo e($fonds->validateur->nom); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($fonds->projet): ?>
                        <tr>
                            <td><strong>Projet bénéficiaire</strong></td>
                            <td><?php echo e($fonds->projet->nom_projet); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($fonds->destination): ?>
                        <tr>
                            <td><strong>Destination</strong></td>
                            <td><?php echo e($fonds->destination); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Don en nature -->
            <?php if($fonds->est_don_nature): ?>
            <div class="transaction-details">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Détails du Don en Nature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td><?php echo e($fonds->description_don_nature); ?></td>
                        </tr>
                        <?php if($fonds->valeur_estimee): ?>
                        <tr>
                            <td><strong>Valeur estimée</strong></td>
                            <td><?php echo e(number_format($fonds->valeur_estimee, 0, ',', ' ')); ?> <?php echo e($fonds->devise); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- Instructions du donateur -->
            <?php if($fonds->instructions_donateur): ?>
            <div class="info-block">
                <h3>Instructions du Donateur</h3>
                <p><?php echo e($fonds->instructions_donateur); ?></p>
            </div>
            <?php endif; ?>

            <!-- Mention légale -->
            <div class="legal-notice">
                <h3>MENTION LÉGALE</h3>
                <p>
                    Ce reçu atteste que la somme mentionnée ci-dessus a été versée à <?php echo e($egliseInfo['nom']); ?>,
                    organisme d'intérêt général reconnu par l'État de Côte d'Ivoire. Ce don ouvre droit à une réduction
                    d'impôt dans les conditions prévues par la législation fiscale en vigueur.
                    Conservez précieusement ce document pour votre déclaration fiscale.
                </p>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <table class="pdf-table">
                    <tr>
                        <td style="width: 45%;">
                            <div class="signature-block">
                                <h4>Le Donateur</h4>
                                <div class="signature-line"></div>
                                <p class="signature-name"><?php echo e($fonds->nom_donateur); ?></p>
                            </div>
                        </td>
                        <td style="width: 10%;"></td>
                        <td style="width: 45%;">
                            <div class="signature-block">
                                <h4>Le Responsable Financier</h4>
                                <div class="signature-line"></div>
                                <p class="signature-name">
                                    <?php echo e($fonds->validateur ? $fonds->validateur->prenom . ' ' . $fonds->validateur->nom : 'Responsable Financier'); ?>

                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-content">
                <table>
                    <tr>
                        <td class="footer-section">
                            <h4>Contact</h4>
                            <p><?php echo e($egliseInfo['telephone']); ?></p>
                            <p><?php echo e($egliseInfo['email']); ?></p>
                        </td>
                        <td class="footer-section">
                            <h4>Authentification</h4>
                            <div class="qr-code">QR Code</div>
                        </td>
                        <td class="footer-section">
                            <h4>Généré le</h4>
                            <p><?php echo e(now()->format('d/m/Y à H:i')); ?></p>
                            <p>Système de gestion EgliseFlow v2.1</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/exports/receipts/fiscal-receipt.blade.php ENDPATH**/ ?>