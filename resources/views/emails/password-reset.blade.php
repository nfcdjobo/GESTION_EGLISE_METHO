<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe - {{ $AppParametres->nom_eglise }}</title>
</head>
<body style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #1f2937; margin: 0; padding: 0; background-color: #f3f4f6;">

    <!-- Conteneur principal -->
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

        <!-- EN-TÊTE STRUCTURE (inspiré du PDF) -->
        <div style="background-color: #1e40af; color: white; padding: 30px 40px; border-bottom: 4px solid #f59e0b; overflow: hidden;">
            <div style="width: 100%;">
                <!-- PARTIE GAUCHE: Logo + Nom -->
                <div style="float: left; width: 48%;">
                    <div style="float: left; width: 70px; margin-right: 15px;">
                        @if(!empty($AppParametres->logo))
                            @php
                                $logoPath = storage_path('app/public/' . $AppParametres->logo);
                                $logoExists = file_exists($logoPath);
                            @endphp

                            @if($logoExists)
                                <div style="width: 60px; height: 60px; background-color: white; border-radius: 10px; padding: 5px; text-align: center; line-height: 60px;">
                                    <img src="{{ $message->embed($logoPath) }}"
                                        alt="Logo {{ $AppParametres->nom_eglise }}"
                                        style="max-width: 50px; max-height: 50px; vertical-align: middle; display: inline-block;">
                                </div>
                            @else
                                <div style="width: 60px; height: 60px; background-color: white; border-radius: 10px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                                    {{ strtoupper(substr($AppParametres->nom_eglise, 0, 2)) }}
                                </div>
                            @endif
                        @else
                            <div style="width: 60px; height: 60px; background-color: white; border-radius: 10px; padding: 5px; text-align: center; line-height: 60px; font-size: 24px; font-weight: bold; color: #3b82f6;">
                                {{ strtoupper(substr($AppParametres->nom_eglise ?? 'EG', 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <div style="margin-left: 85px;">
                        <div style="font-size: 18px; font-weight: bold; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; color: white;">
                            {{ htmlspecialchars($AppParametres->nom_eglise) }}
                        </div>
                        <div style="font-size: 12px; line-height: 1.6; color: rgba(255, 255, 255, 0.9);">
                            Plateforme de gestion
                        </div>
                    </div>
                </div>

                <!-- PARTIE DROITE: Contact -->
                <div style="float: right; width: 48%; text-align: right;">
                    <div style="font-size: 11px; line-height: 1.8; color: rgba(255, 255, 255, 0.9);">
                        @if(!empty($AppParametres->email))
                            <div style="margin: 3px 0;"><strong>Email:</strong> {{ htmlspecialchars($AppParametres->email) }}</div>
                        @endif
                        @if(!empty($AppParametres->telephone_1))
                            <div style="margin: 3px 0;"><strong>Tel:</strong> {{ htmlspecialchars($AppParametres->telephone_1) }}</div>
                        @endif
                        @if(!empty($AppParametres->ville))
                            <div style="margin: 3px 0;">{{ htmlspecialchars($AppParametres->ville) }}</div>
                        @endif
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

        <!-- TITRE -->
        <div style="text-align: center; padding: 30px 40px 20px; border-bottom: 2px solid #e5e7eb;">
            <h1 style="color: #1f2937; font-size: 24px; margin: 0 0 8px 0; font-weight: bold;">
                RÉINITIALISATION DE MOT DE PASSE
            </h1>
            <p style="color: #6b7280; font-size: 13px; margin: 0;">
                Demande de sécurité - {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div style="padding: 40px;">
            <!-- Salutation -->
            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px 20px; margin-bottom: 25px; border-radius: 4px;">
                <div style="font-size: 16px; color: #1e40af; font-weight: 600;">
                    Bonjour {{ $user->prenom }} {{ $user->nom }},
                </div>
            </div>

            <!-- Message principal -->
            <div style="color: #4b5563; font-size: 14px; line-height: 1.8; margin-bottom: 30px;">
                <p style="margin: 0 0 15px 0;">
                    Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte sur la plateforme de gestion de <strong style="color: #1f2937;">{{ $AppParametres->nom_eglise }}</strong>.
                </p>
                <p style="margin: 0;">
                    Si vous êtes à l'origine de cette demande, cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe sécurisé.
                </p>
            </div>

            <!-- Bouton d'action principal -->
            <div style="text-align: center; margin: 35px 0;">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ route('security.password.reset', $token) }}" style="height:54px;v-text-anchor:middle;width:300px;" arcsize="15%" stroke="f" fillcolor="#1e40af">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:16px;font-weight:bold;">🔒 Réinitialiser mon mot de passe</center>
                </v:roundrect>
                <![endif]-->
                <!--[if !mso]><!-->
                <table border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <tr>
                        <td align="center" style="background-color: #1e40af; border-radius: 8px; padding: 0;">
                            <a href="{{ route('security.password.reset', $token) }}"
                               target="_blank"
                               style="display: inline-block;
                                      background-color: #1e40af;
                                      color: #ffffff !important;
                                      text-decoration: none;
                                      padding: 16px 40px;
                                      border-radius: 8px;
                                      font-weight: bold;
                                      font-size: 16px;
                                      font-family: Arial, sans-serif;
                                      line-height: 1.4;
                                      border: 2px solid #1e3a8a;
                                      mso-line-height-rule: exactly;
                                      -webkit-text-size-adjust: none;">
                                <span style="color: #ffffff; text-decoration: none;">🔒 Réinitialiser mon mot de passe</span>
                            </a>
                        </td>
                    </tr>
                </table>
                <!--<![endif]-->
            </div>

            <!-- Lien alternatif -->
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin: 30px 0;">
                <div style="color: #6b7280; font-size: 13px; margin-bottom: 12px; font-weight: 500;">
                    Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                </div>
                <div style="color: #3b82f6; font-family: 'Courier New', monospace; font-size: 12px; word-break: break-all; background-color: white; padding: 10px; border-radius: 4px; border: 1px solid #dbeafe;">
                    {{ route('security.password.reset', $token) }}
                </div>
            </div>

            <!-- Avertissement de sécurité -->
            <div style="background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 16px 20px; border-radius: 4px; margin: 30px 0;">
                <div style="color: #991b1b; font-size: 14px; line-height: 1.7;">
                    <strong style="display: block; margin-bottom: 8px; font-size: 15px;">⚠️ Important - Sécurité</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li style="margin: 5px 0;">Ce lien de réinitialisation <strong>expirera dans 24 heures</strong></li>
                        <li style="margin: 5px 0;">Si vous n'avez pas demandé cette réinitialisation, <strong>ignorez cet email</strong></li>
                        <li style="margin: 5px 0;">Votre mot de passe actuel restera inchangé si vous n'utilisez pas ce lien</li>
                        <li style="margin: 5px 0;">En cas de doute, contactez immédiatement l'administrateur</li>
                    </ul>
                </div>
            </div>

            <!-- Conseils de sécurité -->
            <div style="margin-bottom: 25px;">
                <h2 style="background-color: #059669; color: white; padding: 10px 15px; font-size: 14px; font-weight: bold; margin: 0 0 15px 0; border-radius: 4px;">
                    🛡️ Conseils de Sécurité
                </h2>
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 20px;">
                    <div style="color: #166534; font-size: 13px; line-height: 1.9;">
                        <div style="margin: 8px 0; padding-left: 20px; position: relative;">
                            <span style="position: absolute; left: 0; color: #059669; font-weight: bold;">✓</span>
                            Utilisez un mot de passe <strong>unique</strong> d'au moins 8 caractères
                        </div>
                        <div style="margin: 8px 0; padding-left: 20px; position: relative;">
                            <span style="position: absolute; left: 0; color: #059669; font-weight: bold;">✓</span>
                            Combinez majuscules, minuscules, chiffres et symboles
                        </div>
                        <div style="margin: 8px 0; padding-left: 20px; position: relative;">
                            <span style="position: absolute; left: 0; color: #059669; font-weight: bold;">✓</span>
                            Ne partagez <strong>jamais</strong> vos identifiants de connexion
                        </div>
                        <div style="margin: 8px 0; padding-left: 20px; position: relative;">
                            <span style="position: absolute; left: 0; color: #059669; font-weight: bold;">✓</span>
                            Activez l'authentification à deux facteurs si disponible
                        </div>
                        <div style="margin: 8px 0; padding-left: 20px; position: relative;">
                            <span style="position: absolute; left: 0; color: #059669; font-weight: bold;">✓</span>
                            Signalez toute activité suspecte immédiatement
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verset biblique (si disponible) -->
            @if(!empty($AppParametres->verset_biblique) && !empty($AppParametres->reference_verset))
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                    <tr>
                        <td style="background-color: #7c3aed; padding: 20px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 14px; line-height: 1.7; margin-bottom: 10px; color: #ffffff; font-style: italic; font-family: Arial, sans-serif;">
                                “{{ $AppParametres->verset_biblique }}”
                            </div>
                            <div style="font-size: 12px; color: #fbbf24; font-weight: 600; font-family: Arial, sans-serif;">
                                {{ $AppParametres->reference_verset }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        </div>

        <!-- FOOTER (inspiré du PDF) -->
        <div style="background-color: #1f2937; color: #d1d5db; padding: 30px 40px; font-size: 12px;">
            <!-- Informations de contact -->
            <div style="margin: 20px 0; padding: 20px; background-color: #374151; border-radius: 6px; text-align: center;">
                <strong style="color: #f59e0b; font-size: 14px; display: block; margin-bottom: 15px;">
                    Contactez-nous
                </strong>

                @if(!empty($AppParametres->adresse))
                    <div style="margin: 8px 0; font-size: 11px; line-height: 1.6;">
                        📍 {{ $AppParametres->adresse }}@if(!empty($AppParametres->code_postal)), {{ $AppParametres->code_postal }}@endif @if(!empty($AppParametres->ville)) {{ $AppParametres->ville }}@endif
                        @if(!empty($AppParametres->commune))<br>{{ $AppParametres->commune }}@endif
                        @if(!empty($AppParametres->pays)), {{ $AppParametres->pays }}@endif
                    </div>
                @endif

                <div style="margin: 15px 0;">
                    @if(!empty($AppParametres->telephone_1))
                        <div style="display: inline-block; margin: 5px 15px; font-size: 11px;">📞 {{ $AppParametres->telephone_1 }}</div>
                    @endif
                    @if(!empty($AppParametres->telephone_2))
                        <div style="display: inline-block; margin: 5px 15px; font-size: 11px;">📞 {{ $AppParametres->telephone_2 }}</div>
                    @endif
                </div>

                @if(!empty($AppParametres->email))
                    <div style="margin: 8px 0; font-size: 11px;">📧 {{ $AppParametres->email }}</div>
                @endif

                @if(!empty($AppParametres->website_url))
                    <div style="margin: 8px 0; font-size: 11px;">
                        🌐 <a href="{{ $AppParametres->website_url }}" style="color: #60a5fa; text-decoration: none;">{{ $AppParametres->website_url }}</a>
                    </div>
                @endif
            </div>

            <!-- Réseaux sociaux -->
            @if(!empty($AppParametres->facebook_url) || !empty($AppParametres->instagram_url) || !empty($AppParametres->youtube_url) || !empty($AppParametres->twitter_url))
                <div style="margin: 20px 0; padding-top: 20px; border-top: 1px solid #4b5563; text-align: center;">
                    <strong style="color: #f59e0b; font-size: 13px; display: block; margin-bottom: 12px;">
                        Suivez-nous
                    </strong>

                    @if(!empty($AppParametres->facebook_url))
                        <a href="{{ $AppParametres->facebook_url }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 11px;">Facebook</a>
                    @endif

                    @if(!empty($AppParametres->instagram_url))
                        <a href="{{ $AppParametres->instagram_url }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 11px;">Instagram</a>
                    @endif

                    @if(!empty($AppParametres->youtube_url))
                        <a href="{{ $AppParametres->youtube_url }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 11px;">YouTube</a>
                    @endif

                    @if(!empty($AppParametres->twitter_url))
                        <a href="{{ $AppParametres->twitter_url }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 11px;">Twitter</a>
                    @endif
                </div>
            @endif

            <!-- Mentions légales -->
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #4b5563; font-size: 10px; color: #9ca3af; text-align: center;">
                <p style="margin: 5px 0; line-height: 1.6;">
                    Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.<br>
                    Pour toute question, veuillez utiliser les coordonnées ci-dessus.
                </p>
                <p style="margin: 15px 0 5px 0;">
                    © {{ date('Y') }} {{ $AppParametres->nom_eglise }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
