<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{

    use HasFactory, HasUuids, SoftDeletes, Notifiable, HasPermissions;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'classe_id',
        'prenom',
        'nom',
        'date_naissance',
        'sexe',
        'telephone_1',
        'telephone_2',
        'email',
        'email_verified_at',
        'adresse_ligne_1',
        'adresse_ligne_2',
        'ville',
        'code_postal',
        'region',
        'pays',
        'statut_matrimonial',
        'nombre_enfants',
        'profession',
        'employeur',
        'date_adhesion',
        'statut_membre',
        'statut_bapteme',
        'date_bapteme',
        'eglise_precedente',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'contact_urgence_relation',
        'temoignage',
        'dons_spirituels',
        'demandes_priere',
        'password',
        'actif',
        'photo_profil',
        'notes_admin',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_naissance' => 'date',
        'email_verified_at' => 'datetime',
        'date_adhesion' => 'date',
        'date_bapteme' => 'date',
        'actif' => 'boolean',
        'password' => 'hashed',
        'nombre_enfants' => 'integer',
    ];

    /**
     * Le nom de la colonne "password".
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Relation avec la classe
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Relation avec les rôles (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif')
                    ->withTimestamps();
    }

    /**
     * Relation avec les permissions directes
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot('is_granted', 'granted_by', 'granted_at', 'expires_at', 'reason')
                    ->withTimestamps();
    }

   



   

 

    /**
     * Transactions spirituelles en tant que donateur
     */
    public function transactionsDonateur()
    {
        return $this->hasMany(Fonds::class, 'donateur_id');
    }

    /**
     * Transactions spirituelles en tant que collecteur
     */
    public function transactionsCollecteur()
    {
        return $this->hasMany(Fonds::class, 'collecteur_id');
    }

    /**
     * Réunions organisées
     */
    public function reunionsOrganisees()
    {
        return $this->hasMany(Reunion::class, 'organisateur_principal_id');
    }

    /**
     * Interventions de l'utilisateur
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'intervenant_id');
    }

    /**
     * Annonces créées par l'utilisateur
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'cree_par');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'souscripteur_id');
    }

    /**
     * Programmes dont l'utilisateur est responsable
     */
    public function programmesResponsable()
    {
        return $this->hasMany(Programme::class, 'responsable_principal_id');
    }



    /**
     * Retourne tous les utilisateurs qui possèdent le rôle donné.
     *
     * @param string $roleName  Le nom du rôle recherché.
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public static function withRole(string $roleName)
    {
        return static::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->orderBy('nom')->get();
    }



    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les membres
     */
    public function scopeMembres($query)
    {
        return $query->where('statut_membre', 'actif');
    }

    /**
     * Mutateur pour le mot de passe
     */
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Accesseur pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Vérifier si l'utilisateur a un rôle
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return $this->roles->contains($role);
    }

    /**
     * Vérifier si l'utilisateur a une permission
     */
    public function hasPermission($permission)
    {
        // Vérifier les permissions directes
        if ($this->permissions->contains('slug', $permission)) {
            return true;
        }

        // Vérifier les permissions via les rôles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Méthode pour forcer le hash du mot de passe
     */
    public function hashPassword($password)
    {
        $this->password = Hash::make($password);
        return $this;
    }

    /**
     * Vérifier si le mot de passe est correct
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }




    /**
     * Relation avec les rapports rédigés par cet utilisateur
     */
    public function rapportsRediges()
    {
        return $this->hasMany(RapportReunion::class, 'redacteur_id');
    }

    /**
     * Relation avec les rapports validés par cet utilisateur
     */
    public function rapportsValides()
    {
        return $this->hasMany(RapportReunion::class, 'validateur_id');
    }

    /**
     * Relation avec les rapports créés par cet utilisateur
     */
    public function rapportsCrees()
    {
        return $this->hasMany(RapportReunion::class, 'cree_par');
    }

    /**
     * Relation avec les rapports modifiés par cet utilisateur
     */
    public function rapportsModifies()
    {
        return $this->hasMany(RapportReunion::class, 'modifie_par');
    }
}
