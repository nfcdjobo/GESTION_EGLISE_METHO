<?php

namespace App\Http\Controllers\Auth\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordChangeAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AuthenteController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function viewlogin()
    {
        return view('components.auth.login');
    }

    /**
     * Traiter la connexion
     */
    // public function login(Request $request)
    // {
    //     try {
    //         // Validation des données
    //         $validator = Validator::make($request->all(), [
    //             'login' => 'required|string', // Peut être email ou téléphone
    //             'password' => 'required|string|min:6',
    //             'remember' => 'boolean'
    //         ], [
    //             'login.required' => 'L\'email ou le numéro de téléphone est requis.',
    //             'password.required' => 'Le mot de passe est requis.',
    //             'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
    //         ]);

    //         if ($validator->fails()) {
    //             return back()->withErrors($validator)->withInput();
    //         }

    //         $credentials = $request->only('password');
    //         $loginField = $request->input('login');
    //         $remember = $request->boolean('remember');

    //         // Déterminer si c'est un email ou un téléphone
    //         if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
    //             // C'est un email
    //             $credentials['email'] = $loginField;
    //             $user = User::where('email', $loginField)->first();
    //         } else {
    //             // C'est un téléphone
    //             $credentials['telephone_1'] = $loginField;
    //             $user = User::where('telephone_1', $loginField)->first();
    //         }

    //         // Vérifier si l'membres existe
    //         if (!$user) {
    //             return back()->withErrors([
    //                 'login' => 'Aucun compte trouvé avec ces identifiants.'
    //             ])->withInput();
    //         }

    //         // Vérifier si le compte est actif
    //         if (!$user->actif) {
    //             return back()->withErrors([
    //                 'login' => 'Votre compte est désactivé. Contactez l\'administrateur.'
    //             ])->withInput();
    //         }

    //         // Vérifier le mot de passe manuellement
    //         if (!Hash::check($request->password, $user->password)) {
    //             return back()->withErrors([
    //                 'login' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'
    //             ])->withInput();
    //         }

    //         // Connexion manuelle de l'membres
    //         Auth::login($user, $remember);
    //         $request->session()->regenerate();

    //         // Rediriger vers le dashboard ou la page prévue
    //         return redirect()->intended(route('private.dashboard'))->with('success', 'Connexion réussie !');

    //     } catch (\Exception $e) {
    //         return back()->withErrors([
    //             'login' => 'Une erreur est survenue lors de la connexion. Veuillez réessayer.'
    //         ])->withInput();
    //     }
    // }

    public function login(Request $request)
{
    try {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // Peut être email ou téléphone
            'password' => 'required|string|min:6',
            'remember' => 'boolean'
        ], [
            'login.required' => 'L\'email ou le numéro de téléphone est requis.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('password');
        $loginField = $request->input('login');

        // Normaliser le champ login : supprimer les espaces et convertir en minuscules
        $normalizedLogin = strtolower(trim(str_replace(' ', '', $loginField)));

        $remember = $request->boolean('remember');

        // Déterminer si c'est un email ou un téléphone
        if (filter_var($normalizedLogin, FILTER_VALIDATE_EMAIL)) {
            // C'est un email - rechercher en ignorant la casse et les espaces
            $credentials['email'] = $normalizedLogin;
            $user = User::whereRaw("LOWER(REPLACE(email, ' ', '')) = ?", [$normalizedLogin])->first();
        } else {
            // C'est un téléphone - nettoyer tous les caractères non numériques (sauf le + initial)
            $cleanPhone = preg_replace('/[^\d+]/', '', $loginField);
            // Si le numéro commence par +, on le garde, sinon on supprime tout caractère non numérique
            if (strpos($loginField, '+') === 0) {
                $cleanPhone = '+' . preg_replace('/[^\d]/', '', substr($loginField, 1));
            } else {
                $cleanPhone = preg_replace('/[^\d]/', '', $loginField);
            }

            $credentials['telephone_1'] = $cleanPhone;
            // Rechercher en nettoyant aussi le téléphone en base
            $user = User::whereRaw("REGEXP_REPLACE(telephone_1, '[^0-9+]', '', 'g') = ?", [$cleanPhone])->first();
        }

        // Vérifier si l'membres existe
        if (!$user) {
            return back()->withErrors([
                'login' => 'Aucun compte trouvé avec ces identifiants.'
            ])->withInput();
        }

        // Vérifier si le compte est actif
        if (!$user->actif) {
            return back()->withErrors([
                'login' => 'Votre compte est désactivé. Contactez l\'administrateur.'
            ])->withInput();
        }

        // Vérifier le mot de passe manuellement
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'
            ])->withInput();
        }

        // Connexion manuelle de l'membres
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Rediriger vers le dashboard ou la page prévue
        return redirect()->intended(route('private.dashboard'))->with('success', 'Connexion réussie !');

    } catch (\Exception $e) {
        return back()->withErrors([
            'login' => 'Une erreur est survenue lors de la connexion. Veuillez réessayer.'
        ])->withInput();
    }
}

    /**
     * Afficher le formulaire d'inscription
     */
    public function viewregister()
    {
        return view('components.auth.login'); // Même vue avec section inscription
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:20|unique:users,telephone_1',
                'password' => 'required|string|min:8|confirmed',
                'terms' => 'accepted',
            ], [
                'first_name.required' => 'Le prénom est requis.',
                'last_name.required' => 'Le nom est requis.',
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'L\'adresse email doit être valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'phone.required' => 'Le numéro de téléphone est requis.',
                'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Créer l'membres
            DB::beginTransaction();

            $user = User::create([
                'prenom' => $request->first_name,
                'nom' => $request->last_name,
                'email' => $request->email,
                'telephone_1' => $request->phone,
                'sexe' => 'masculin', // Valeur par défaut, peut être modifiée plus tard
                'adresse_ligne_1' => 'Non renseignée', // Sera complétée plus tard
                'ville' => 'Non renseignée', // Sera complétée plus tard
                'statut_membre' => 'visiteur',
                'statut_bapteme' => 'non_baptise',
                'actif' => true,
                'date_adhesion' => Carbon::now(),
            ]);

            // Hacher le mot de passe explicitement
            $user->hashPassword($request->password);
            $user->save();

            DB::commit();

            // Connecter automatiquement l'membres
            Auth::login($user);

            return redirect()->route('private.dashboard')->with('success', 'Votre compte a été créé avec succès ! Bienvenue !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'register' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.'
            ])->withInput();
        }
    }

    /**
     * Traiter la demande de réinitialisation de mot de passe
     */
    public function request(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'recovery' => 'required|string',
            ], [
                'recovery.required' => 'L\'email ou le numéro de téléphone est requis.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $recoveryField = $request->input('recovery');

            // Déterminer si c'est un email ou un téléphone
            if (filter_var($recoveryField, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $recoveryField)->first();
            } else {
                $user = User::where('telephone_1', $recoveryField)->first();
            }

            if (!$user) {
                return back()->withErrors([
                    'recovery' => 'Aucun compte trouvé avec ces informations.'
                ])->withInput();
            }

            // Enregistrer la tentative de récupération
            PasswordChangeAttempt::recordAttempt($user->id, 'reset_request');

            // Générer le token de réinitialisation
            $token = Str::random(64);

            // Supprimer les anciens tokens
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            // Créer le nouveau token
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            // Envoyer l'email de réinitialisation
            // Note: Vous devez configurer le service d'email dans votre application
            try {
                // Mail::send('emails.password-reset', ['token' => $token, 'user' => $user], function($message) use ($user) {
                //     $message->to($user->email);
                //     $message->subject('Réinitialisation de votre mot de passe');
                // });

                // Pour le moment, retourner le token (à des fins de test)
                // En production, supprimez cette partie et utilisez l'envoi d'email
                session()->flash('reset_token', $token);
            } catch (\Exception $e) {
                return back()->withErrors([
                    'recovery' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer plus tard.'
                ]);
            }

            return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'recovery' => 'Une erreur est survenue. Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Afficher le formulaire de réinitialisation
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Traiter la réinitialisation du mot de passe
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            // Vérifier le token
            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
                return back()->withErrors(['token' => 'Le token de réinitialisation est invalide.']);
            }

            // Vérifier l'expiration (24 heures)
            if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
                return back()->withErrors(['token' => 'Le token de réinitialisation a expiré.']);
            }

            // Mettre à jour le mot de passe
            $user = User::where('email', $request->email)->first();
            $user->hashPassword($request->password);
            $user->save();

            // Supprimer le token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Enregistrer la réinitialisation réussie
            PasswordChangeAttempt::recordAttempt($user->id, 'change_success', 'Réinitialisation par email');

            return redirect()->route('security.login')->with('success', 'Votre mot de passe a été réinitialisé avec succès.');

        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'Une erreur est survenue lors de la réinitialisation.']);
        }
    }

    /**
     * Changer le mot de passe (avec restrictions)
     */
    public function changePassword(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = Auth::user();

            // Vérifier les restrictions de changement de mot de passe
            $canChange = PasswordChangeAttempt::canChangePassword($user->id);

            if (!$canChange['can_change']) {
                return back()->withErrors(['password' => $canChange['reason']]);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'current_password.required' => 'Le mot de passe actuel est requis.',
                'new_password.required' => 'Le nouveau mot de passe est requis.',
                'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            // Vérifier le mot de passe actuel
            if (!$user->checkPassword($request->current_password)) {
                // Enregistrer l'échec
                PasswordChangeAttempt::recordAttempt($user->id, 'change_failed', 'Mot de passe actuel incorrect');

                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }

            // Mettre à jour le mot de passe
            $user->hashPassword($request->new_password);
            $user->save();

            // Enregistrer le succès
            PasswordChangeAttempt::recordAttempt($user->id, 'change_success', 'Changement manuel');

            return back()->with('success', 'Votre mot de passe a été modifié avec succès.');

        } catch (\Exception $e) {
            // Enregistrer l'erreur système
            PasswordChangeAttempt::recordAttempt(Auth::id(), 'change_failed', 'Erreur système: ' . $e->getMessage());

            return back()->withErrors(['password' => 'Une erreur est survenue lors du changement de mot de passe.']);
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.accueil')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Vérifier le statut des tentatives de changement de mot de passe
     */
    public function passwordChangeStatus()
    {
        $user = Auth::user();
        $status = PasswordChangeAttempt::canChangePassword($user->id);

        return response()->json($status);
    }

    /**
     * Obtenir les statistiques des tentatives
     */
    public function getPasswordAttemptStats()
    {
        $user = Auth::user();

        $stats = [
            'last_successful_change' => PasswordChangeAttempt::where('user_id', $user->id)
                ->where('type', 'change_success')
                ->orderBy('attempted_at', 'desc')
                ->first()?->attempted_at,
            'failed_attempts_this_week' => PasswordChangeAttempt::where('user_id', $user->id)
                ->where('type', 'change_failed')
                ->where('attempted_at', '>=', Carbon::now()->startOfWeek())
                ->count(),
            'next_allowed_change' => PasswordChangeAttempt::getNextAllowedChangeDate($user->id),
            'can_change_now' => PasswordChangeAttempt::canChangePassword($user->id)['can_change']
        ];

        return response()->json($stats);
    }

    // Méthodes de base du ResourceController (si nécessaire)
    public function index()
    {
        // Liste des membres (admin seulement)
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Formulaire de création d'membres (admin)
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Logique de création d'membres par admin
    }

    public function show(string $id)
    {
        // Afficher un membres spécifique
        $user = User::with('roles', 'permissions')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        // Formulaire d'édition d'membres
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Logique de mise à jour d'membres
    }

    public function destroy(string $id)
    {
        // Suppression d'membres (soft delete)
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Membres supprimé.');
    }
}
