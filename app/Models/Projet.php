<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projet extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom_projet',
        'description',
        'objectif',
        'budget_prevu',
        'budget_collecte',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'statut',
        'priorite',
        'responsable_id',
        'beneficiaires',
        'localisation',
        'notes',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'budget_prevu' => 'decimal:2',
        'budget_collecte' => 'decimal:2',
    ];

    /**
     * Relation avec le responsable du projet
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relation avec les transactions spirituelles
     */
    public function transactionsSpirituelless()
    {
        return $this->hasMany(TransactionSpirituelle::class, 'projet_id');
    }

    /**
     * Scope pour les projets actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les projets terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Accesseur pour le pourcentage de collecte
     */
    public function getPourcentageCollecteAttribute()
    {
        if (!$this->budget_prevu || $this->budget_prevu == 0) {
            return 0;
        }

        return min(100, ($this->budget_collecte / $this->budget_prevu) * 100);
    }

    /**
     * Accesseur pour le montant restant à collecter
     */
    public function getMontantRestantAttribute()
    {
        return max(0, $this->budget_prevu - $this->budget_collecte);
    }
}
