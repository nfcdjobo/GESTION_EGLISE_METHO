<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement FIMECO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 3px solid rgba(255,255,255,0.2);
        }
        .logo-fallback {
            width: 180px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 26px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .header h2 {
            margin: 0;
            font-weight: normal;
            opacity: 0.9;
            font-size: 18px;
        }
        .content {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .payment-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 5px solid #007bff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .payment-details h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .payment-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .payment-details li {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 15px;
        }
        .payment-details li:last-child {
            border-bottom: none;
        }
        .amount-highlight {
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
        }
        .celebration {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 30px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 5px solid #28a745;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .celebration h3 {
            margin-top: 0;
            color: #155724;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .action-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .action-button.success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        }
        .action-button.success:hover {
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            font-style: italic;
            color: #007bff;
            text-align: center;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 8px 0;
        }

        /* Media queries pour responsive */
        @media (max-width: 600px) {
            body { padding: 10px; }
            .content { padding: 20px 15px; }
            .payment-details { padding: 20px; }
            .logo { max-width: 150px; }
            .header h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($logoBase64) && $logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo FIMECO" class="logo">
                {{$logoBase64}}
            @elseif(isset($logoUrl) && $logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo FIMECO" class="logo" style="max-width: 180px;">
                 {{$logoUrl}}
            @else
                <div class="logo-fallback">FIMECO</div>
            @endif

            <h1>Confirmation de Paiement</h1>
            <h2>{{ $subscription->fimeco->nom }}</h2>
        </div>

        <div class="content">
            <p class="greeting"><strong>Bonjour {{ $notifiable->nom }} {{ $notifiable->prenom }},</strong></p>

            <p>Nous confirmons la réception de votre paiement pour la FIMECO "<strong>{{ $subscription->fimeco->nom }}</strong>".</p>

            <div class="payment-details">
                <h3>📋 Détails du paiement</h3>
                <ul>
                    <li><strong>💰 Montant reçu :</strong> <span class="amount-highlight">{{ $montantPaiement }} FCFA</span></li>
                    <li><strong>💳 Type de paiement :</strong> {{ config('fimeco.types_paiement_autorises')[$payment->type_paiement] ?? ucfirst(str_replace('_', ' ', $payment->type_paiement)) }}</li>
                    <li><strong>📅 Date de paiement :</strong> {{ $payment->date_paiement->format('d/m/Y à H:i') }}</li>
                    @if($payment->reference_paiement)
                        <li><strong>🔖 Référence :</strong> {{ $payment->reference_paiement }}</li>
                    @endif
                    @if($payment->validateur)
                        <li><strong>✅ Validé par :</strong> {{ $payment->validateur->nom ?? 'Système automatique' }}</li>
                    @endif
                </ul>
            </div>

            @if($subscription->reste_a_payer > 0)
                <div class="payment-details">
                    <h3>💡 État de votre souscription</h3>
                    <ul>
                        <li><strong>Montant total souscrit :</strong> {{ number_format($subscription->montant_souscrit, 2, ',', ' ') }} FCFA</li>
                        <li><strong>Total déjà payé :</strong> {{ number_format($subscription->montant_paye, 2, ',', ' ') }} FCFA</li>
                        <li><strong>Reste à payer :</strong> <span class="amount-highlight">{{ $resteAPayer }} FCFA</span></li>
                        <li><strong>Progression :</strong> {{ number_format($subscription->progression, 1) }}%</li>
                    </ul>
                    <div style="text-align: center; margin-top: 25px;">
                        <a href="{{ route('private.subscriptions.show', $subscription->id) }}" class="action-button">
                            Effectuer un autre paiement
                        </a>
                    </div>
                </div>
            @else
                <div class="celebration">
                    <h3>🎉 Félicitations !</h3>
                    <p><strong>Votre souscription est maintenant entièrement payée.</strong></p>
                    <p>Montant total payé : <span class="amount-highlight">{{ number_format($subscription->montant_paye, 2, ',', ' ') }} FCFA</span></p>
                    <div style="margin-top: 25px;">
                        <a href="{{ route('private.subscriptions.show', $subscription->id) }}" class="action-button success">
                            Voir votre souscription
                        </a>
                    </div>
                </div>
            @endif

            <div class="signature">
                <p>Merci pour votre générosité et votre engagement.</p>
                <p><em>Que Dieu vous bénisse,</em></p>
                <p><strong>L'équipe FIMECO</strong></p>
            </div>
        </div>

        <div class="footer">
            <p><strong>📧 Notification automatique</strong></p>
            <p>Cette notification a été générée le {{ now()->format('d/m/Y à H:i') }}.</p>
            <p>Pour toute question, contactez notre équipe de support.</p>
            @if($subscription->date_echeance)
                <p><small>Échéance de votre souscription : {{ $subscription->date_echeance->format('d/m/Y') }}</small></p>
            @endif
        </div>
    </div>
</body>
</html>
