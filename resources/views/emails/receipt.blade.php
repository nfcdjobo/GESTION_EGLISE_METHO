<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu Fiscal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">{{ $egliseInfo['nom'] }}</h2>

        <p>Bonjour {{ $fonds->nom_donateur }},</p>

        <p>Nous vous remercions pour votre don de <strong>{{ $fonds->montant_format }}</strong> effectué le {{ $fonds->date_transaction->format('d/m/Y') }}.</p>

        <p>Vous trouverez en pièce jointe votre reçu fiscal n° <strong>{{ $fonds->numero_recu }}</strong>.</p>

        <p>Ce document vous permettra de bénéficier d'une réduction d'impôt selon la législation en vigueur.</p>

        <p>Que Dieu vous bénisse richement !</p>

        <hr style="margin: 20px 0;">

        <p style="font-size: 12px; color: #666;">
            {{ $egliseInfo['nom'] }}<br>
            {{ $egliseInfo['adresse'] }}<br>
            Tél: {{ $egliseInfo['telephone'] }} | Email: {{ $egliseInfo['email'] }}
        </p>
    </div>
</body>
</html>
