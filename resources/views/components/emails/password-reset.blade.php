<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            margin-bottom: 20px;
        }
        .title {
            color: #2d3748;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #718096;
            font-size: 16px;
            margin: 10px 0 0 0;
        }
        .content {
            margin: 30px 0;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-1px);
        }
        .alternative-link {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            word-break: break-all;
        }
        .alternative-text {
            color: #718096;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .link {
            color: #3182ce;
            font-family: monospace;
            font-size: 14px;
        }
        .warning {
            background-color: #fed7d7;
            border-left: 4px solid #e53e3e;
            padding: 16px;
            border-radius: 4px;
            margin: 30px 0;
        }
        .warning-text {
            color: #c53030;
            font-size: 14px;
            margin: 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
            color: #a0aec0;
            font-size: 14px;
        }
        .footer-links {
            margin-top: 20px;
        }
        .footer-link {
            color: #718096;
            text-decoration: none;
            margin: 0 15px;
        }
        .footer-link:hover {
            color: #4a5568;
        }
        .security-info {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .security-title {
            color: #2b6cb0;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .security-text {
            color: #2c5aa0;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                🏛️
            </div>
            <h1 class="title">{{ $appName }}</h1>
            <p class="subtitle">Plateforme de gestion de l'église</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->prenom }} {{ $user->nom }},
            </div>

            <div class="message">
                Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte. Si vous êtes à l'origine de cette demande, cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe.
            </div>

            <div class="button-container">
                <a href="{{ route('security.password.reset', $token) }}" class="reset-button">
                    🔑 Réinitialiser mon mot de passe
                </a>
            </div>

            <div class="alternative-link">
                <div class="alternative-text">
                    Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                </div>
                <div class="link">{{ route('security.password.reset', $token) }}</div>
            </div>

            <div class="warning">
                <p class="warning-text">
                    ⚠️ <strong>Important :</strong> Ce lien de réinitialisation expirera dans 24 heures. Si vous n'avez pas demandé cette réinitialisation, ignorez cet email et votre mot de passe restera inchangé.
                </p>
            </div>

            <div class="security-info">
                <div class="security-title">🛡️ Conseils de sécurité</div>
                <div class="security-text">
                    • Utilisez un mot de passe unique d'au moins 8 caractères<br>
                    • Combinez majuscules, minuscules, chiffres et symboles<br>
                    • Ne partagez jamais vos identifiants de connexion<br>
                    • Contactez l'administrateur si vous remarquez une activité suspecte
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                Si vous avez des questions, contactez l'administrateur de la plateforme.
            </p>

            <div class="footer-links">
                <a href="#" class="footer-link">Centre d'aide</a>
                <a href="#" class="footer-link">Politique de confidentialité</a>
                <a href="#" class="footer-link">Conditions d'utilisation</a>
            </div>

            <p style="margin-top: 30px;">
                © {{ date('Y') }} {{ $appName }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
