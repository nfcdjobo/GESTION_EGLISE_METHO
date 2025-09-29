<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Créer les permissions par ressource
            // $this->createUserPermissions();
            // $this->createRolePermissions();
            // $this->createPermissionPermissions();
            // $this->createClassePermissions();
            // $this->createCultePermissions();
            // $this->createParticipantCultePermissions();
            // $this->createEventPermissions();
            // $this->createAnnoncePermissions();
            // $this->createProgrammePermissions();
            // $this->createReunionPermissions();
            // $this->createTypeReunionPermissions();
            // $this->createRapportReunionPermissions();
            // $this->createContactPermissions();
            // $this->createInterventionPermissions();
            // $this->createMultimediaPermissions();
            // $this->createProjetPermissions();
            // $this->createFondsPermissions();
            // $this->createFimecoPermissions();
            // $this->createSubscriptionPermissions();
            // $this->createPaymentPermissions();
            // $this->createAuditLogPermissions();
            // $this->createSystemPermissions();

            // $this->createPaiementPermissions();


            // $this->createMoissonPermissions();
            // $this->createEngagementMoissonPermissions();
            // $this->createVenteMoissonPermissions();
            // $this->createPassageMoissonPermissions();
            // $this->createDonPermissions();
            // $this->createParametreDonPermissions();
            // $this->createParametrePermissions();
            // $this->createDashboardPermissions();

            $this->createPaiementAlertes();


            // Créer les rôles de base
            // $this->createRoles();

            // Attribuer les permissions aux rôles
            // $this->assignPermissionsToRoles();
        });

        $this->command->info('✅ Permissions et rôles créés avec succès !');
    }


    /**
     * Créer les permissions pour le dashboard
     */
    private function createDashboardPermissions(): void
    {
        $permissions = [
            // Accès principal
            ['name' => 'Voir le dashboard', 'slug' => 'dashboard.read', 'resource' => 'dashboard', 'action' => 'read'],

            // Export et statistiques
            ['name' => 'Exporter données dashboard', 'slug' => 'dashboard.export', 'resource' => 'dashboard', 'action' => 'export'],
            ['name' => 'Voir statistiques par période', 'slug' => 'dashboard.statistics-periode', 'resource' => 'dashboard', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion du dashboard');
    }

    /**
     * Créer les permissions pour les paramètres
     */
    private function createParametrePermissions(): void
    {
        $permissions = [
            // CRUD de base (adapté pour les paramètres)
            ['name' => 'Voir les paramètres', 'slug' => 'parametres.read', 'resource' => 'parametres', 'action' => 'read'],
            ['name' => 'Modifier les paramètres', 'slug' => 'parametres.update', 'resource' => 'parametres', 'action' => 'update'],

            // Actions spécialisées
            ['name' => 'Modifier logo système', 'slug' => 'parametres.update-logo', 'resource' => 'parametres', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des paramètres');
    }

    private function createPaiementAlertes(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir le membres absencent aux cultes deux dimanches successifs ou deux fois dans le mois précedent', 'slug' => 'users.absences-cultes', 'resource' => 'users', 'action' => 'read'],

        ];

        $this->createPermissions($permissions, 'Gestion des membres');
    }


    /**
     * Créer les permissions pour les paramètres dons  $this->createPaiementAlertes();
     */
    private function createParametreDonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les paramètres dons', 'slug' => 'parametresdons.read', 'resource' => 'parametresdons', 'action' => 'read'],
            ['name' => 'Créer des paramètres dons', 'slug' => 'parametresdons.create', 'resource' => 'parametresdons', 'action' => 'create'],
            ['name' => 'Modifier les paramètres dons', 'slug' => 'parametresdons.update', 'resource' => 'parametresdons', 'action' => 'update'],
            ['name' => 'Supprimer les paramètres dons', 'slug' => 'parametresdons.delete', 'resource' => 'parametresdons', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques paramètres dons', 'slug' => 'parametresdons.statistics', 'resource' => 'parametresdons', 'action' => 'read'],
            ['name' => 'Voir paramètres dons publics', 'slug' => 'parametresdons.publics', 'resource' => 'parametresdons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Changer statut paramètres dons', 'slug' => 'parametresdons.toggle-status', 'resource' => 'parametresdons', 'action' => 'update'],
            ['name' => 'Publier/dépublier paramètres dons', 'slug' => 'parametresdons.toggle-publication', 'resource' => 'parametresdons', 'action' => 'update'],

            // Export
            ['name' => 'Exporter les paramètres dons', 'slug' => 'parametresdons.export', 'resource' => 'parametresdons', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des paramètres dons');
    }


    /**
     * Créer les permissions pour les dons
     */
    private function createDonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les dons', 'slug' => 'donation.read', 'resource' => 'dons', 'action' => 'read'],
            ['name' => 'Créer des dons', 'slug' => 'donation.create', 'resource' => 'dons', 'action' => 'create'],
            ['name' => 'Modifier les dons', 'slug' => 'donation.update', 'resource' => 'dons', 'action' => 'update'],
            ['name' => 'Supprimer les dons', 'slug' => 'donation.delete', 'resource' => 'dons', 'action' => 'delete'],

            // Vues spécialisées et tableaux de bord
            ['name' => 'Voir statistiques dons', 'slug' => 'donation.statistics', 'resource' => 'dons', 'action' => 'read'],
            ['name' => 'Voir dashboard dons', 'slug' => 'donation.dashboard', 'resource' => 'dons', 'action' => 'read'],
            ['name' => 'Voir dons publics', 'slug' => 'donation.publics', 'resource' => 'dons', 'action' => 'read'],
            ['name' => 'Voir dons par donateur', 'slug' => 'donation.par-donateur', 'resource' => 'dons', 'action' => 'read'],
            ['name' => 'Rapport personnalisé dons', 'slug' => 'donation.rapport-personnalise', 'resource' => 'dons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Dupliquer les dons', 'slug' => 'donation.dupliquer', 'resource' => 'dons', 'action' => 'create'],
            ['name' => 'Changer statut dons', 'slug' => 'donation.toggle-statut', 'resource' => 'dons', 'action' => 'update'],
            ['name' => 'Publier/dépublier dons', 'slug' => 'donation.toggle-publication', 'resource' => 'dons', 'action' => 'update'],

            // Export et documents
            ['name' => 'Exporter les dons', 'slug' => 'donation.export', 'resource' => 'dons', 'action' => 'export'],
            ['name' => 'Télécharger preuves dons', 'slug' => 'donation.telecharger-preuve', 'resource' => 'dons', 'action' => 'read'],

            // Actions en lot
            ['name' => 'Suppression en lot dons', 'slug' => 'donation.bulk-delete', 'resource' => 'dons', 'action' => 'delete'],
        ];

        $this->createPermissions($permissions, 'Gestion des dons');
    }



    /**
     * Créer les permissions pour les passages moissons
     */
    private function createPassageMoissonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les passages moissons', 'slug' => 'passagesmoissons.read', 'resource' => 'passagesmoissons', 'action' => 'read'],
            ['name' => 'Créer des passages moissons', 'slug' => 'passagesmoissons.create', 'resource' => 'passagesmoissons', 'action' => 'create'],
            ['name' => 'Modifier les passages moissons', 'slug' => 'passagesmoissons.update', 'resource' => 'passagesmoissons', 'action' => 'update'],
            ['name' => 'Supprimer les passages moissons', 'slug' => 'passagesmoissons.delete', 'resource' => 'passagesmoissons', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard passages moissons', 'slug' => 'passagesmoissons.dashboard', 'resource' => 'passagesmoissons', 'action' => 'read'],
            ['name' => 'Voir statistiques passages moissons', 'slug' => 'passagesmoissons.statistics', 'resource' => 'passagesmoissons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Ajouter montant passages moissons', 'slug' => 'passagesmoissons.ajouter-montant', 'resource' => 'passagesmoissons', 'action' => 'manage'],
            ['name' => 'Changer statut passages moissons', 'slug' => 'passagesmoissons.toggle-status', 'resource' => 'passagesmoissons', 'action' => 'manage'],

            // Export
            ['name' => 'Exporter les passages moissons', 'slug' => 'passagesmoissons.export', 'resource' => 'passagesmoissons', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des passages moissons');
    }


    /**
     * Créer les permissions pour les ventes moissons
     */
    private function createVenteMoissonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les ventes moissons', 'slug' => 'ventesmoissons.read', 'resource' => 'ventesmoissons', 'action' => 'read'],
            ['name' => 'Créer des ventes moissons', 'slug' => 'ventesmoissons.create', 'resource' => 'ventesmoissons', 'action' => 'create'],
            ['name' => 'Modifier les ventes moissons', 'slug' => 'ventesmoissons.update', 'resource' => 'ventesmoissons', 'action' => 'update'],
            ['name' => 'Supprimer les ventes moissons', 'slug' => 'ventesmoissons.delete', 'resource' => 'ventesmoissons', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard ventes moissons', 'slug' => 'ventesmoissons.dashboard', 'resource' => 'ventesmoissons', 'action' => 'read'],
            ['name' => 'Voir statistiques ventes moissons', 'slug' => 'ventesmoissons.statistics', 'resource' => 'ventesmoissons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Ajouter montant ventes moissons', 'slug' => 'ventesmoissons.ajouter-montant', 'resource' => 'ventesmoissons', 'action' => 'manage'],
            ['name' => 'Changer statut ventes moissons', 'slug' => 'ventesmoissons.toggle-status', 'resource' => 'ventesmoissons', 'action' => 'manage'],

            // Export
            ['name' => 'Exporter les ventes moissons', 'slug' => 'ventesmoissons.export', 'resource' => 'ventesmoissons', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des ventes moissons');
    }


    /**
     * Créer les permissions pour les engagements moissons
     */
    private function createEngagementMoissonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les engagements moissons', 'slug' => 'engagementsmoissons.read', 'resource' => 'engagementsmoissons', 'action' => 'read'],
            ['name' => 'Créer des engagements moissons', 'slug' => 'engagementsmoissons.create', 'resource' => 'engagementsmoissons', 'action' => 'create'],
            ['name' => 'Modifier les engagements moissons', 'slug' => 'engagementsmoissons.update', 'resource' => 'engagementsmoissons', 'action' => 'update'],
            ['name' => 'Supprimer les engagements moissons', 'slug' => 'engagementsmoissons.delete', 'resource' => 'engagementsmoissons', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard engagements moissons', 'slug' => 'engagementsmoissons.dashboard', 'resource' => 'engagementsmoissons', 'action' => 'read'],
            ['name' => 'Voir statistiques engagements moissons', 'slug' => 'engagementsmoissons.statistics', 'resource' => 'engagementsmoissons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Ajouter montant engagements moissons', 'slug' => 'engagementsmoissons.ajouter-montant', 'resource' => 'engagementsmoissons', 'action' => 'manage'],
            ['name' => 'Planifier rappel engagements moissons', 'slug' => 'engagementsmoissons.planifier-rappel', 'resource' => 'engagementsmoissons', 'action' => 'manage'],
            ['name' => 'Prolonger échéance engagements moissons', 'slug' => 'engagementsmoissons.prolonger-echeance', 'resource' => 'engagementsmoissons', 'action' => 'manage'],
            ['name' => 'Changer statut engagements moissons', 'slug' => 'engagementsmoissons.toggle-status', 'resource' => 'engagementsmoissons', 'action' => 'manage'],

            // Export
            ['name' => 'Exporter les engagements moissons', 'slug' => 'engagementsmoissons.export', 'resource' => 'engagementsmoissons', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des engagements moissons');
    }


    /**
     * Créer les permissions pour les moissons
     */
    private function createMoissonPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les moissons', 'slug' => 'moissons.read', 'resource' => 'moissons', 'action' => 'read'],
            ['name' => 'Créer des moissons', 'slug' => 'moissons.create', 'resource' => 'moissons', 'action' => 'create'],
            ['name' => 'Modifier les moissons', 'slug' => 'moissons.update', 'resource' => 'moissons', 'action' => 'update'],
            ['name' => 'Supprimer les moissons', 'slug' => 'moissons.delete', 'resource' => 'moissons', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard moissons', 'slug' => 'moissons.dashboard', 'resource' => 'moissons', 'action' => 'read'],
            ['name' => 'Voir statistiques moissons', 'slug' => 'moissons.statistics', 'resource' => 'moissons', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Clôturer les moissons', 'slug' => 'moissons.cloturer', 'resource' => 'moissons', 'action' => 'manage'],
            ['name' => 'Recalculer totaux moissons', 'slug' => 'moissons.recalculer-totaux', 'resource' => 'moissons', 'action' => 'manage'],
            ['name' => 'Changer statut moissons', 'slug' => 'moissons.toggle-status', 'resource' => 'moissons', 'action' => 'manage'],

            // Export
            ['name' => 'Exporter les moissons', 'slug' => 'moissons.export', 'resource' => 'moissons', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des moissons');
    }


    /**
     * Créer les permissions pour les paiements
     */
    private function createPaiementPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les paiements', 'slug' => 'paiements.read', 'resource' => 'paiements', 'action' => 'read'],
            ['name' => 'Créer des paiements', 'slug' => 'paiements.create', 'resource' => 'paiements', 'action' => 'create'],
            ['name' => 'Modifier les paiements', 'slug' => 'paiements.update', 'resource' => 'paiements', 'action' => 'update'],
            ['name' => 'Supprimer les paiements', 'slug' => 'paiements.delete', 'resource' => 'paiements', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir paiements en attente', 'slug' => 'paiements.en-attente', 'resource' => 'paiements', 'action' => 'read'],
            ['name' => 'Voir types paiement', 'slug' => 'paiements.types-paiement', 'resource' => 'paiements', 'action' => 'read'],

            // Actions de validation
            ['name' => 'Valider les paiements', 'slug' => 'paiements.validate', 'resource' => 'paiements', 'action' => 'validate'],
            ['name' => 'Rejeter les paiements', 'slug' => 'paiements.reject', 'resource' => 'paiements', 'action' => 'validate'],

            // Actions en lot
            ['name' => 'Traiter paiements en lot', 'slug' => 'paiements.traiter-en-lot', 'resource' => 'paiements', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des paiements');
    }



    /**
     * Créer les permissions pour les membres (mise à jour)
     */
    private function createUserPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les membres', 'slug' => 'users.read', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Créer des membres', 'slug' => 'users.create', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'Modifier les membres', 'slug' => 'users.update', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Supprimer les membres', 'slug' => 'users.delete', 'resource' => 'users', 'action' => 'delete'],
            ['name' => 'Exporter les membres', 'slug' => 'users.export', 'resource' => 'users', 'action' => 'export'],
            ['name' => 'Importer les membres', 'slug' => 'users.import', 'resource' => 'users', 'action' => 'import'],
            ['name' => 'Rechercher les membres', 'slug' => 'users.search', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Ajouter membre spécial', 'slug' => 'users.ajoutmembre', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'Valider les membres', 'slug' => 'users.validate', 'resource' => 'users', 'action' => 'validate'],
            ['name' => 'Archiver les membres', 'slug' => 'users.archive', 'resource' => 'users', 'action' => 'archive'],
            ['name' => 'Restaurer les membres', 'slug' => 'users.restore', 'resource' => 'users', 'action' => 'restore'],
            ['name' => 'Changer statut membres', 'slug' => 'users.toggle-status', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Réinitialiser mot de passe', 'slug' => 'users.reset-password', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Voir statistiques membres', 'slug' => 'users.statistics', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Voir rapports membres', 'slug' => 'users.reports', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Gérer permissions membres', 'slug' => 'users.permissions', 'resource' => 'users', 'action' => 'manage'],
            ['name' => 'Gérer rôles membres', 'slug' => 'users.roles', 'resource' => 'users', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des membres');
    }

    /**
     * Créer les permissions pour les rôles
     */
    private function createRolePermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les rôles', 'slug' => 'roles.read', 'resource' => 'roles', 'action' => 'read'],
            ['name' => 'Créer des rôles', 'slug' => 'roles.create', 'resource' => 'roles', 'action' => 'create'],
            ['name' => 'Modifier les rôles', 'slug' => 'roles.update', 'resource' => 'roles', 'action' => 'update'],
            ['name' => 'Supprimer les rôles', 'slug' => 'roles.delete', 'resource' => 'roles', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir hiérarchie des rôles', 'slug' => 'roles.hierarchy', 'resource' => 'roles', 'action' => 'read'],
            ['name' => 'Comparer les rôles', 'slug' => 'roles.compare', 'resource' => 'roles', 'action' => 'read'],

            // Gestion des permissions
            ['name' => 'Gérer les rôles', 'slug' => 'roles.manage', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Gérer permissions des rôles', 'slug' => 'roles.permissions', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Synchroniser permissions des rôles', 'slug' => 'roles.sync-permissions', 'resource' => 'roles', 'action' => 'manage'],

            // Attribution et retrait d'utilisateurs
            ['name' => 'Assigner rôles aux utilisateurs', 'slug' => 'roles.assign', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Retirer rôles aux utilisateurs', 'slug' => 'roles.remove', 'resource' => 'roles', 'action' => 'manage'],

            // Actions spécialisées
            ['name' => 'Cloner les rôles', 'slug' => 'roles.clone', 'resource' => 'roles', 'action' => 'create'],
            ['name' => 'Exporter les rôles', 'slug' => 'roles.export', 'resource' => 'roles', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des rôles');
    }


    /**
     * Créer les permissions pour les permissions
     */
    private function createPermissionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les permissions', 'slug' => 'permissions.read', 'resource' => 'permissions', 'action' => 'read'],
            ['name' => 'Créer des permissions', 'slug' => 'permissions.create', 'resource' => 'permissions', 'action' => 'create'],
            ['name' => 'Modifier les permissions', 'slug' => 'permissions.update', 'resource' => 'permissions', 'action' => 'update'],
            ['name' => 'Supprimer les permissions', 'slug' => 'permissions.delete', 'resource' => 'permissions', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques permissions', 'slug' => 'permissions.statistics', 'resource' => 'permissions', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Attribution en lot permissions', 'slug' => 'permissions.bulk-assign', 'resource' => 'permissions', 'action' => 'manage'],
            ['name' => 'Cloner les permissions', 'slug' => 'permissions.clone', 'resource' => 'permissions', 'action' => 'create'],
            ['name' => 'Activer/désactiver permissions', 'slug' => 'permissions.toggle', 'resource' => 'permissions', 'action' => 'update'],

            // Export
            ['name' => 'Exporter les permissions', 'slug' => 'permissions.export', 'resource' => 'permissions', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des permissions');
    }

    /**
     * Créer les permissions pour les classes
     */
    private function createClassePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les classes', 'slug' => 'classes.read', 'resource' => 'classes', 'action' => 'read'],
            ['name' => 'Créer des classes', 'slug' => 'classes.create', 'resource' => 'classes', 'action' => 'create'],
            ['name' => 'Modifier les classes', 'slug' => 'classes.update', 'resource' => 'classes', 'action' => 'update'],
            ['name' => 'Supprimer les classes', 'slug' => 'classes.delete', 'resource' => 'classes', 'action' => 'delete'],
            ['name' => 'Voir statistiques classes', 'slug' => 'classes.statistics', 'resource' => 'classes', 'action' => 'read'],
            ['name' => 'Exporter les classes', 'slug' => 'classes.export', 'resource' => 'classes', 'action' => 'export'],
            ['name' => 'Archiver les classes', 'slug' => 'classes.archive', 'resource' => 'classes', 'action' => 'archive'],
            ['name' => 'Restaurer les classes', 'slug' => 'classes.restore', 'resource' => 'classes', 'action' => 'restore'],
            ['name' => 'Dupliquer les classes', 'slug' => 'classes.duplicate', 'resource' => 'classes', 'action' => 'create'],
            ['name' => 'Actions en lot classes', 'slug' => 'classes.bulk-actions', 'resource' => 'classes', 'action' => 'manage'],
            ['name' => 'Gérer membres classes', 'slug' => 'classes.manage-members', 'resource' => 'classes', 'action' => 'manage'],
            ['name' => 'Voir membres classes', 'slug' => 'classes.members', 'resource' => 'classes', 'action' => 'read'],
            ['name' => 'Ajouter membres en lot', 'slug' => 'classes.bulk-add-members', 'resource' => 'classes', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des classes');
    }

    /**
     * Créer les permissions pour les cultes
     */
    private function createCultePermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les cultes', 'slug' => 'cultes.read', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Créer des cultes', 'slug' => 'cultes.create', 'resource' => 'cultes', 'action' => 'create'],
            ['name' => 'Modifier les cultes', 'slug' => 'cultes.update', 'resource' => 'cultes', 'action' => 'update'],
            ['name' => 'Supprimer les cultes', 'slug' => 'cultes.delete', 'resource' => 'cultes', 'action' => 'delete'],

            // Vues spécialisées et tableaux de bord
            ['name' => 'Voir planning cultes', 'slug' => 'cultes.planning', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Voir statistiques cultes', 'slug' => 'cultes.statistics', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Voir dashboard cultes', 'slug' => 'cultes.dashboard', 'resource' => 'cultes', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Changer statut cultes', 'slug' => 'cultes.change-status', 'resource' => 'cultes', 'action' => 'update'],
            ['name' => 'Dupliquer les cultes', 'slug' => 'cultes.duplicate', 'resource' => 'cultes', 'action' => 'create'],
            ['name' => 'Restaurer les cultes', 'slug' => 'cultes.restore', 'resource' => 'cultes', 'action' => 'restore'],

            // Export
            ['name' => 'Exporter les cultes', 'slug' => 'cultes.export', 'resource' => 'cultes', 'action' => 'export'],

            // Gestion des participants
            ['name' => 'Voir participants cultes', 'slug' => 'cultes.participants', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Gérer participants cultes', 'slug' => 'cultes.manage-participants', 'resource' => 'cultes', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des cultes');
    }


    /**
     * Créer les permissions pour les participants cultes
     */
    private function createParticipantCultePermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les participants cultes', 'slug' => 'participant_cultes.read', 'resource' => 'participants_cultes', 'action' => 'read'],
            ['name' => 'Créer des participants cultes', 'slug' => 'participant_cultes.create', 'resource' => 'participants_cultes', 'action' => 'create'],
            ['name' => 'Modifier les participants cultes', 'slug' => 'participant_cultes.update', 'resource' => 'participants_cultes', 'action' => 'update'],
            ['name' => 'Supprimer les participants cultes', 'slug' => 'participant_cultes.delete', 'resource' => 'participants_cultes', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques participants cultes', 'slug' => 'participant_cultes.statistics', 'resource' => 'participants_cultes', 'action' => 'read'],
            ['name' => 'Voir nouveaux visiteurs', 'slug' => 'participant_cultes.nouveaux-visiteurs', 'resource' => 'participants_cultes', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Rechercher participants cultes', 'slug' => 'participant_cultes.search', 'resource' => 'participants_cultes', 'action' => 'read'],
            ['name' => 'Ajouter participant culte', 'slug' => 'participant_cultes.ajouter-participant', 'resource' => 'participants_cultes', 'action' => 'create'],
            ['name' => 'Créer avec utilisateur', 'slug' => 'participant_cultes.create-with-user', 'resource' => 'participants_cultes', 'action' => 'create'],
            ['name' => 'Création en lot avec utilisateur', 'slug' => 'participant_cultes.bulk-create-with-user', 'resource' => 'participants_cultes', 'action' => 'create'],
            ['name' => 'Confirmer présence participant', 'slug' => 'participant_cultes.confirmer-presence', 'resource' => 'participants_cultes', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des participants cultes');
    }



    /**
     * Créer les permissions pour les événements
     */
    private function createEventPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les événements', 'slug' => 'events.read', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Créer des événements', 'slug' => 'events.create', 'resource' => 'events', 'action' => 'create'],
            ['name' => 'Modifier les événements', 'slug' => 'events.update', 'resource' => 'events', 'action' => 'update'],
            ['name' => 'Supprimer les événements', 'slug' => 'events.delete', 'resource' => 'events', 'action' => 'delete'],

            // Vues spécialisées et tableaux de bord
            ['name' => 'Voir planning événements', 'slug' => 'events.planning', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Voir statistiques événements', 'slug' => 'events.statistics', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Voir dashboard événements', 'slug' => 'events.dashboard', 'resource' => 'events', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Changer statut événements', 'slug' => 'events.change-status', 'resource' => 'events', 'action' => 'update'],
            ['name' => 'Dupliquer les événements', 'slug' => 'events.duplicate', 'resource' => 'events', 'action' => 'create'],
            ['name' => 'Restaurer les événements', 'slug' => 'events.restore', 'resource' => 'events', 'action' => 'restore'],

            // Gestion des inscriptions
            ['name' => 'Gérer inscriptions événements', 'slug' => 'events.manage-inscriptions', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Voir inscriptions événements', 'slug' => 'events.inscriptions', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Voir liste attente événements', 'slug' => 'events.liste-attente', 'resource' => 'events', 'action' => 'read'],

            // Export et notifications
            ['name' => 'Exporter participants événements', 'slug' => 'events.export-participants', 'resource' => 'events', 'action' => 'export'],
            ['name' => 'Envoyer notifications événements', 'slug' => 'events.notifications', 'resource' => 'events', 'action' => 'manage'],

            // Gestion des récurrences
            ['name' => 'Gérer récurrence événements', 'slug' => 'events.recurrence', 'resource' => 'events', 'action' => 'manage'],

            // Gestion des médias
            ['name' => 'Gérer médias événements', 'slug' => 'events.medias', 'resource' => 'events', 'action' => 'manage'],

            // Gestion financière
            ['name' => 'Gérer finances événements', 'slug' => 'events.finances', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Voir rapports financiers événements', 'slug' => 'events.finances-rapport', 'resource' => 'events', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des événements');
    }

    /**
     * Créer les permissions pour les annonces
     */
    private function createAnnoncePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les annonces', 'slug' => 'annonces.read', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Créer des annonces', 'slug' => 'annonces.create', 'resource' => 'annonces', 'action' => 'create'],
            ['name' => 'Modifier les annonces', 'slug' => 'annonces.update', 'resource' => 'annonces', 'action' => 'update'],
            ['name' => 'Supprimer les annonces', 'slug' => 'annonces.delete', 'resource' => 'annonces', 'action' => 'delete'],
            ['name' => 'Voir annonces pour culte', 'slug' => 'annonces.culte', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir annonces urgentes', 'slug' => 'annonces.urgentes', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir statistiques annonces', 'slug' => 'annonces.statistics', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir annonces actives', 'slug' => 'annonces.actives', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Publier les annonces', 'slug' => 'annonces.publish', 'resource' => 'annonces', 'action' => 'update'],
            ['name' => 'Archiver les annonces', 'slug' => 'annonces.archive', 'resource' => 'annonces', 'action' => 'archive'],
            ['name' => 'Dupliquer les annonces', 'slug' => 'annonces.duplicate', 'resource' => 'annonces', 'action' => 'create'],
        ];

        $this->createPermissions($permissions, 'Gestion des annonces');
    }

    /**
     * Créer les permissions pour les programmes
     */
    private function createProgrammePermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les programmes', 'slug' => 'programmes.read', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Créer des programmes', 'slug' => 'programmes.create', 'resource' => 'programmes', 'action' => 'create'],
            ['name' => 'Modifier les programmes', 'slug' => 'programmes.update', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Supprimer les programmes', 'slug' => 'programmes.delete', 'resource' => 'programmes', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir planning programmes', 'slug' => 'programmes.planning', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir statistiques programmes', 'slug' => 'programmes.statistics', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir programmes actifs', 'slug' => 'programmes.actifs', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir métadonnées programmes', 'slug' => 'programmes.metadata', 'resource' => 'programmes', 'action' => 'read'],

            // Actions de gestion du statut
            ['name' => 'Activer les programmes', 'slug' => 'programmes.activate', 'resource' => 'programmes', 'action' => 'manage'],
            ['name' => 'Suspendre les programmes', 'slug' => 'programmes.suspend', 'resource' => 'programmes', 'action' => 'manage'],
            ['name' => 'Terminer les programmes', 'slug' => 'programmes.terminate', 'resource' => 'programmes', 'action' => 'manage'],
            ['name' => 'Annuler les programmes', 'slug' => 'programmes.cancel', 'resource' => 'programmes', 'action' => 'manage'],

            // Actions utilitaires
            ['name' => 'Dupliquer les programmes', 'slug' => 'programmes.duplicate', 'resource' => 'programmes', 'action' => 'create'],
        ];

        $this->createPermissions($permissions, 'Gestion des programmes');
    }

    /**
     * Créer les permissions pour les réunions
     */
    private function createReunionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les réunions', 'slug' => 'reunions.read', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Créer des réunions', 'slug' => 'reunions.create', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Modifier les réunions', 'slug' => 'reunions.update', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Supprimer les réunions', 'slug' => 'reunions.delete', 'resource' => 'reunions', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques réunions', 'slug' => 'reunions.statistics', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions à venir', 'slug' => 'reunions.upcoming', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions du jour', 'slug' => 'reunions.today', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir calendrier réunions', 'slug' => 'reunions.calendar', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions publiques', 'slug' => 'reunions.public', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir diffusions live', 'slug' => 'reunions.live-stream', 'resource' => 'reunions', 'action' => 'read'],

            // Gestion du cycle de vie
            ['name' => 'Confirmer les réunions', 'slug' => 'reunions.confirm', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Commencer les réunions', 'slug' => 'reunions.start', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Terminer les réunions', 'slug' => 'reunions.end', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Annuler les réunions', 'slug' => 'reunions.cancel', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Reporter les réunions', 'slug' => 'reunions.postpone', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Suspendre les réunions', 'slug' => 'reunions.suspend', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Reprendre les réunions', 'slug' => 'reunions.resume', 'resource' => 'reunions', 'action' => 'manage'],

            // Gestion des participants
            ['name' => 'Marquer présences réunions', 'slug' => 'reunions.mark-attendance', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Inscrire participants réunions', 'slug' => 'reunions.register-participant', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Désinscrire participants réunions', 'slug' => 'reunions.unregister-participant', 'resource' => 'reunions', 'action' => 'manage'],

            // Gestion spirituelle
            ['name' => 'Ajouter résultats spirituels', 'slug' => 'reunions.add-spiritual-results', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Ajouter témoignages réunions', 'slug' => 'reunions.add-testimonies', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Ajouter demandes prière', 'slug' => 'reunions.add-prayer-requests', 'resource' => 'reunions', 'action' => 'manage'],

            // Évaluation et feedback
            ['name' => 'Évaluer les réunions', 'slug' => 'reunions.evaluate', 'resource' => 'reunions', 'action' => 'manage'],

            // Actions spécialisées
            ['name' => 'Dupliquer les réunions', 'slug' => 'reunions.duplicate', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Créer récurrence réunions', 'slug' => 'reunions.create-recurrence', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Restaurer les réunions', 'slug' => 'reunions.restore', 'resource' => 'reunions', 'action' => 'restore'],

            // Notifications
            ['name' => 'Envoyer rappels réunions', 'slug' => 'reunions.send-reminders', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Notifier participants réunions', 'slug' => 'reunions.notify-participants', 'resource' => 'reunions', 'action' => 'manage'],

            // Gestion de documents
            ['name' => 'Upload documents réunions', 'slug' => 'reunions.upload-documents', 'resource' => 'reunions', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des réunions');
    }



    /**
     * Créer les permissions pour les types de réunions
     */
    private function createTypeReunionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les types de réunions', 'slug' => 'types-reunions.read', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Créer des types de réunions', 'slug' => 'types-reunions.create', 'resource' => 'types-reunions', 'action' => 'create'],
            ['name' => 'Modifier les types de réunions', 'slug' => 'types-reunions.update', 'resource' => 'types-reunions', 'action' => 'update'],
            ['name' => 'Supprimer les types de réunions', 'slug' => 'types-reunions.delete', 'resource' => 'types-reunions', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques types réunions', 'slug' => 'types-reunions.statistics', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Voir catégories types réunions', 'slug' => 'types-reunions.categories', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Voir options types réunions', 'slug' => 'types-reunions.options', 'resource' => 'types-reunions', 'action' => 'read'],

            // Gestion du cycle de vie
            ['name' => 'Archiver les types de réunions', 'slug' => 'types-reunions.archive', 'resource' => 'types-reunions', 'action' => 'archive'],
            ['name' => 'Restaurer les types de réunions', 'slug' => 'types-reunions.restore', 'resource' => 'types-reunions', 'action' => 'restore'],
            ['name' => 'Activer les types de réunions', 'slug' => 'types-reunions.activate', 'resource' => 'types-reunions', 'action' => 'manage'],
            ['name' => 'Désactiver les types de réunions', 'slug' => 'types-reunions.deactivate', 'resource' => 'types-reunions', 'action' => 'manage'],

            // Actions spécialisées
            ['name' => 'Dupliquer les types de réunions', 'slug' => 'types-reunions.duplicate', 'resource' => 'types-reunions', 'action' => 'create'],
        ];

        $this->createPermissions($permissions, 'Gestion des types de réunions');
    }

    /**
     * Créer les permissions pour les rapports réunions
     */
    private function createRapportReunionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les rapports réunions', 'slug' => 'rapports-reunions.read', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Créer des rapports réunions', 'slug' => 'rapports-reunions.create', 'resource' => 'rapports-reunions', 'action' => 'create'],
            ['name' => 'Modifier les rapports réunions', 'slug' => 'rapports-reunions.update', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Supprimer les rapports réunions', 'slug' => 'rapports-reunions.delete', 'resource' => 'rapports-reunions', 'action' => 'delete'],

            // Workflow de validation
            ['name' => 'Mettre en révision rapports réunions', 'slug' => 'rapports-reunions.revision', 'resource' => 'rapports-reunions', 'action' => 'manage'],
            ['name' => 'Valider les rapports réunions', 'slug' => 'rapports-reunions.validate', 'resource' => 'rapports-reunions', 'action' => 'validate'],
            ['name' => 'Publier les rapports réunions', 'slug' => 'rapports-reunions.publish', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Rejeter les rapports réunions', 'slug' => 'rapports-reunions.reject', 'resource' => 'rapports-reunions', 'action' => 'reject'],

            // Gestion des présences
            ['name' => 'Gérer présences rapports réunions', 'slug' => 'rapports-reunions.manage-presences', 'resource' => 'rapports-reunions', 'action' => 'manage'],

            // Gestion des actions de suivi
            ['name' => 'Gérer actions suivi rapports réunions', 'slug' => 'rapports-reunions.manage-actions', 'resource' => 'rapports-reunions', 'action' => 'manage'],

            // Export et téléchargement
            ['name' => 'Exporter les rapports réunions', 'slug' => 'rapports-reunions.export', 'resource' => 'rapports-reunions', 'action' => 'export'],
            ['name' => 'Télécharger PDF rapports réunions', 'slug' => 'rapports-reunions.download-pdf', 'resource' => 'rapports-reunions', 'action' => 'export'],

            // Statistiques et vues spécialisées
            ['name' => 'Voir statistiques rapports réunions', 'slug' => 'rapports-reunions.statistics', 'resource' => 'rapports-reunions', 'action' => 'read'],

            // Restauration
            ['name' => 'Restaurer les rapports réunions', 'slug' => 'rapports-reunions.restore', 'resource' => 'rapports-reunions', 'action' => 'restore'],

            // Vues de workflow spécialisées
            ['name' => 'Voir rapports en attente', 'slug' => 'rapports-reunions.en-attente', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Voir rapports publiés', 'slug' => 'rapports-reunions.publies', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Voir mes rapports', 'slug' => 'rapports-reunions.mes-rapports', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Voir rapports à valider', 'slug' => 'rapports-reunions.a-valider', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Voir archives rapports réunions', 'slug' => 'rapports-reunions.archives', 'resource' => 'rapports-reunions', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des rapports réunions');
    }

    /**
     * Créer les permissions pour les contacts
     */
    private function createContactPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les contacts', 'slug' => 'contacts.read', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Créer des contacts', 'slug' => 'contacts.create', 'resource' => 'contacts', 'action' => 'create'],
            ['name' => 'Modifier les contacts', 'slug' => 'contacts.update', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Supprimer les contacts', 'slug' => 'contacts.delete', 'resource' => 'contacts', 'action' => 'delete'],

            // Actions spécialisées
            ['name' => 'Vérifier les contacts', 'slug' => 'contacts.verify', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Actions en lot contacts', 'slug' => 'contacts.bulk-actions', 'resource' => 'contacts', 'action' => 'manage'],
            ['name' => 'Dupliquer les contacts', 'slug' => 'contacts.duplicate', 'resource' => 'contacts', 'action' => 'create'],
            ['name' => 'Fusionner les contacts', 'slug' => 'contacts.merge', 'resource' => 'contacts', 'action' => 'update'],

            // Recherche et géolocalisation
            ['name' => 'Recherche géographique', 'slug' => 'contacts.search-nearby', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Recherche avancée contacts', 'slug' => 'contacts.advanced-search', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Géocoder les contacts', 'slug' => 'contacts.geocode', 'resource' => 'contacts', 'action' => 'update'],

            // Statistiques et rapports
            ['name' => 'Voir statistiques contacts', 'slug' => 'contacts.statistics', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Rapport complétude contacts', 'slug' => 'contacts.completeness-report', 'resource' => 'contacts', 'action' => 'read'],

            // Export et import
            ['name' => 'Exporter les contacts', 'slug' => 'contacts.export', 'resource' => 'contacts', 'action' => 'export'],
            ['name' => 'Importer les contacts', 'slug' => 'contacts.import', 'resource' => 'contacts', 'action' => 'import'],
            ['name' => 'Télécharger modèle import', 'slug' => 'contacts.import-template', 'resource' => 'contacts', 'action' => 'import'],

            // Validation et maintenance
            ['name' => 'Valider liens contacts', 'slug' => 'contacts.validate-links', 'resource' => 'contacts', 'action' => 'validate'],
            ['name' => 'Nettoyer données contacts', 'slug' => 'contacts.cleanup', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Synchroniser externes', 'slug' => 'contacts.sync-external', 'resource' => 'contacts', 'action' => 'update'],

            // Fonctionnalités spécialisées
            ['name' => 'Gérer visibilité contacts', 'slug' => 'contacts.visibility', 'resource' => 'contacts', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des contacts');
    }

    /**
     * Créer les permissions pour les interventions
     */
    private function createInterventionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les interventions', 'slug' => 'interventions.read', 'resource' => 'interventions', 'action' => 'read'],
            ['name' => 'Créer des interventions', 'slug' => 'interventions.create', 'resource' => 'interventions', 'action' => 'create'],
            ['name' => 'Modifier les interventions', 'slug' => 'interventions.update', 'resource' => 'interventions', 'action' => 'update'],
            ['name' => 'Supprimer les interventions', 'slug' => 'interventions.delete', 'resource' => 'interventions', 'action' => 'delete'],

            // Actions spécialisées
            ['name' => 'Restaurer les interventions', 'slug' => 'interventions.restore', 'resource' => 'interventions', 'action' => 'restore'],
            ['name' => 'Changer statut interventions', 'slug' => 'interventions.toggle-statut', 'resource' => 'interventions', 'action' => 'update'],

            // Vues spécialisées
            ['name' => 'Voir corbeille interventions', 'slug' => 'interventions.trash', 'resource' => 'interventions', 'action' => 'read'],
            ['name' => 'Voir interventions par événement', 'slug' => 'interventions.par-evenement', 'resource' => 'interventions', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des interventions');
    }











    /**
     * Créer les permissions pour les multimedia
     */
    private function createMultimediaPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les multimedia', 'slug' => 'multimedia.read', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Créer des multimedia', 'slug' => 'multimedia.create', 'resource' => 'multimedia', 'action' => 'create'],
            ['name' => 'Modifier les multimedia', 'slug' => 'multimedia.update', 'resource' => 'multimedia', 'action' => 'update'],
            ['name' => 'Supprimer les multimedia', 'slug' => 'multimedia.delete', 'resource' => 'multimedia', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir galerie multimedia', 'slug' => 'multimedia.galerie', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Voir statistiques multimedia', 'slug' => 'multimedia.statistics', 'resource' => 'multimedia', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Télécharger multimedia', 'slug' => 'multimedia.download', 'resource' => 'multimedia', 'action' => 'download'],
            ['name' => 'Approuver multimedia', 'slug' => 'multimedia.approve', 'resource' => 'multimedia', 'action' => 'approve'],
            ['name' => 'Rejeter multimedia', 'slug' => 'multimedia.reject', 'resource' => 'multimedia', 'action' => 'reject'],
            ['name' => 'Mettre en vedette multimedia', 'slug' => 'multimedia.toggle-featured', 'resource' => 'multimedia', 'action' => 'manage'],

            // Modération
            ['name' => 'Modérer multimedia', 'slug' => 'multimedia.moderate', 'resource' => 'multimedia', 'action' => 'moderate'],
            ['name' => 'Modération en lot multimedia', 'slug' => 'multimedia.bulk-moderate', 'resource' => 'multimedia', 'action' => 'moderate'],

            // Gestion avancée (pour administrateurs)
            ['name' => 'Gérer stockage multimedia', 'slug' => 'multimedia.manage-storage', 'resource' => 'multimedia', 'action' => 'manage'],
            ['name' => 'Nettoyer stockage multimedia', 'slug' => 'multimedia.storage-cleanup', 'resource' => 'multimedia', 'action' => 'manage'],
            ['name' => 'Optimiser stockage multimedia', 'slug' => 'multimedia.storage-optimize', 'resource' => 'multimedia', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des multimedia');
    }

    /**
     * Créer les permissions pour les projets
     */
    private function createProjetPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les projets', 'slug' => 'projets.read', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Créer des projets', 'slug' => 'projets.create', 'resource' => 'projets', 'action' => 'create'],
            ['name' => 'Modifier les projets', 'slug' => 'projets.update', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Supprimer les projets', 'slug' => 'projets.delete', 'resource' => 'projets', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir statistiques projets', 'slug' => 'projets.statistics', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Voir projets publics', 'slug' => 'projets.public', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Voir options projets', 'slug' => 'projets.options', 'resource' => 'projets', 'action' => 'read'],

            // Workflow - Actions d'approbation et planification
            ['name' => 'Approuver les projets', 'slug' => 'projets.approve', 'resource' => 'projets', 'action' => 'approve'],
            ['name' => 'Planifier les projets', 'slug' => 'projets.plan', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Rechercher financement projets', 'slug' => 'projets.seek-funding', 'resource' => 'projets', 'action' => 'manage'],

            // Workflow - Gestion du cycle de vie
            ['name' => 'Démarrer les projets', 'slug' => 'projets.start', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Suspendre les projets', 'slug' => 'projets.suspend', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Reprendre les projets', 'slug' => 'projets.resume', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Terminer les projets', 'slug' => 'projets.complete', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Annuler les projets', 'slug' => 'projets.cancel', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Mettre projets en attente', 'slug' => 'projets.put-on-hold', 'resource' => 'projets', 'action' => 'manage'],

            // Actions spécialisées
            ['name' => 'Mettre à jour progression projets', 'slug' => 'projets.update-progress', 'resource' => 'projets', 'action' => 'manage'],
            ['name' => 'Dupliquer les projets', 'slug' => 'projets.duplicate', 'resource' => 'projets', 'action' => 'create'],
            ['name' => 'Upload images projets', 'slug' => 'projets.upload-images', 'resource' => 'projets', 'action' => 'manage'],

            // Workflow et statut
            ['name' => 'Voir workflow projets', 'slug' => 'projets.workflow', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Voir statut détaillé projets', 'slug' => 'projets.statut-detaille', 'resource' => 'projets', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des projets');
    }



    /**
     * Créer les permissions pour les fonds
     */
    private function createFondsPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les fonds', 'slug' => 'fonds.read', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Créer des fonds', 'slug' => 'fonds.create', 'resource' => 'fonds', 'action' => 'create'],
            ['name' => 'Modifier les fonds', 'slug' => 'fonds.update', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Supprimer les fonds', 'slug' => 'fonds.delete', 'resource' => 'fonds', 'action' => 'delete'],

            // Vues spécialisées et tableaux de bord
            ['name' => 'Voir dashboard fonds', 'slug' => 'fonds.dashboard', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir statistiques fonds', 'slug' => 'fonds.statistics', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir analytics fonds', 'slug' => 'fonds.analytics', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir rapports fonds', 'slug' => 'fonds.reports', 'resource' => 'fonds', 'action' => 'read'],

            // Actions de gestion des transactions
            ['name' => 'Valider les transactions', 'slug' => 'fonds.validate', 'resource' => 'fonds', 'action' => 'validate'],
            ['name' => 'Annuler les transactions', 'slug' => 'fonds.cancel', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Rembourser les transactions', 'slug' => 'fonds.refund', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Restaurer les fonds', 'slug' => 'fonds.restore', 'resource' => 'fonds', 'action' => 'restore'],

            // Fonctionnalités spécialisées
            ['name' => 'Générer reçus fiscaux', 'slug' => 'fonds.generate-receipt', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Dupliquer les transactions', 'slug' => 'fonds.duplicate', 'resource' => 'fonds', 'action' => 'create'],

            // Export
            ['name' => 'Exporter les fonds', 'slug' => 'fonds.export', 'resource' => 'fonds', 'action' => 'export'],

            // Gestion des reçus
            ['name' => 'Voir formulaires reçus', 'slug' => 'fonds.receipt-form', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Télécharger reçus', 'slug' => 'fonds.receipt-download', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Prévisualiser reçus', 'slug' => 'fonds.receipt-preview', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Envoyer reçus par email', 'slug' => 'fonds.receipt-email', 'resource' => 'fonds', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des fonds');
    }

    /**
     * Créer les permissions pour les fimecos
     */
    private function createFimecoPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les fimecos', 'slug' => 'fimecos.read', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Créer des fimecos', 'slug' => 'fimecos.create', 'resource' => 'fimecos', 'action' => 'create'],
            ['name' => 'Modifier les fimecos', 'slug' => 'fimecos.update', 'resource' => 'fimecos', 'action' => 'update'],
            ['name' => 'Supprimer les fimecos', 'slug' => 'fimecos.delete', 'resource' => 'fimecos', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard fimecos', 'slug' => 'fimecos.dashboard', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Voir rapports fimecos', 'slug' => 'fimecos.rapport', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Voir statistiques fimecos', 'slug' => 'fimecos.statistiques', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Voir stats temps réel fimecos', 'slug' => 'fimecos.live-stats', 'resource' => 'fimecos', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Clôturer les fimecos', 'slug' => 'fimecos.cloture', 'resource' => 'fimecos', 'action' => 'manage'],
            ['name' => 'Rouvrir les fimecos', 'slug' => 'fimecos.reouvrir', 'resource' => 'fimecos', 'action' => 'manage'],
            ['name' => 'Désactiver les fimecos', 'slug' => 'fimecos.desactiver', 'resource' => 'fimecos', 'action' => 'manage'],
            ['name' => 'Réactiver les fimecos', 'slug' => 'fimecos.reactiver', 'resource' => 'fimecos', 'action' => 'manage'],

            // Export et recherche
            ['name' => 'Exporter les fimecos', 'slug' => 'fimecos.export', 'resource' => 'fimecos', 'action' => 'export'],
            ['name' => 'Rechercher les fimecos', 'slug' => 'fimecos.search', 'resource' => 'fimecos', 'action' => 'read'],

            // Validation
            ['name' => 'Valider données fimecos', 'slug' => 'fimecos.validate-data', 'resource' => 'fimecos', 'action' => 'validate'],
        ];

        $this->createPermissions($permissions, 'Gestion des fimecos');
    }

    /**
     * Créer les permissions pour les subscriptions
     */
    private function createSubscriptionPermissions(): void
    {
        $permissions = [
            // CRUD de base
            ['name' => 'Voir les subscriptions', 'slug' => 'subscriptions.read', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Créer des subscriptions', 'slug' => 'subscriptions.create', 'resource' => 'subscriptions', 'action' => 'create'],
            ['name' => 'Modifier les subscriptions', 'slug' => 'subscriptions.update', 'resource' => 'subscriptions', 'action' => 'update'],
            ['name' => 'Supprimer les subscriptions', 'slug' => 'subscriptions.delete', 'resource' => 'subscriptions', 'action' => 'delete'],

            // Vues spécialisées
            ['name' => 'Voir dashboard subscriptions', 'slug' => 'subscriptions.dashboard', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Voir mes statistiques subscriptions', 'slug' => 'subscriptions.mes-statistiques', 'resource' => 'subscriptions', 'action' => 'read'],

            // Actions spécialisées
            ['name' => 'Effectuer paiements subscriptions', 'slug' => 'subscriptions.paiement', 'resource' => 'subscriptions', 'action' => 'manage'],
            ['name' => 'Désactiver les subscriptions', 'slug' => 'subscriptions.desactiver', 'resource' => 'subscriptions', 'action' => 'manage'],
            ['name' => 'Réactiver les subscriptions', 'slug' => 'subscriptions.reactiver', 'resource' => 'subscriptions', 'action' => 'manage'],
            ['name' => 'Valider les subscriptions', 'slug' => 'subscriptions.validate', 'resource' => 'subscriptions', 'action' => 'validate'],
            ['name' => 'Annuler les subscriptions', 'slug' => 'subscriptions.annuler', 'resource' => 'subscriptions', 'action' => 'manage'],
            ['name' => 'Suspendre les subscriptions', 'slug' => 'subscriptions.suspendre', 'resource' => 'subscriptions', 'action' => 'manage'],

            // Export et validation
            ['name' => 'Exporter les subscriptions', 'slug' => 'subscriptions.export', 'resource' => 'subscriptions', 'action' => 'export'],
            ['name' => 'Valider données subscriptions', 'slug' => 'subscriptions.validate-data', 'resource' => 'subscriptions', 'action' => 'validate'],

            // Rapports
            ['name' => 'Voir rapports subscriptions', 'slug' => 'subscriptions.rapport', 'resource' => 'subscriptions', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des subscriptions');
    }

    /**
     * Créer les permissions pour les paiements
     */
    private function createPaymentPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les paiements', 'slug' => 'paiements.read', 'resource' => 'paiements', 'action' => 'read'],
            ['name' => 'Créer des paiements', 'slug' => 'paiements.create', 'resource' => 'paiements', 'action' => 'create'],
            ['name' => 'Modifier les paiements', 'slug' => 'paiements.update', 'resource' => 'paiements', 'action' => 'update'],
            ['name' => 'Supprimer les paiements', 'slug' => 'paiements.delete', 'resource' => 'paiements', 'action' => 'delete'],
            ['name' => 'Voir paiements en attente', 'slug' => 'paiements.pending', 'resource' => 'paiements', 'action' => 'read'],
            ['name' => 'Traiter en lot', 'slug' => 'paiements.batch-process', 'resource' => 'paiements', 'action' => 'update'],
            ['name' => 'Voir types de paiement', 'slug' => 'paiements.types', 'resource' => 'paiements', 'action' => 'read'],
            ['name' => 'Valider des paiements', 'slug' => 'paiements.validate', 'resource' => 'paiements', 'action' => 'validate'],
            ['name' => 'Refuser des paiements', 'slug' => 'paiements.reject', 'resource' => 'paiements', 'action' => 'reject'],
            ['name' => 'Annuler des paiements', 'slug' => 'paiements.cancel', 'resource' => 'paiements', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des paiements');
    }

    /**
     * Créer les permissions pour les logs d'audit
     */
    private function createAuditLogPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les logs d\'audit', 'slug' => 'audit-logs.read', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Voir détails des logs', 'slug' => 'audit-logs.show', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Voir statistiques des logs', 'slug' => 'audit-logs.statistics', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Voir logs par membres', 'slug' => 'audit-logs.user-logs', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Exporter les logs', 'slug' => 'audit-logs.export', 'resource' => 'audit-logs', 'action' => 'export'],
            ['name' => 'Recherche avancée dans logs', 'slug' => 'audit-logs.search', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Voir logs en temps réel', 'slug' => 'audit-logs.realtime', 'resource' => 'audit-logs', 'action' => 'read'],
            ['name' => 'Nettoyer les logs', 'slug' => 'audit-logs.cleanup', 'resource' => 'audit-logs', 'action' => 'delete'],
            ['name' => 'Suppression en lot des logs', 'slug' => 'audit-logs.bulk-delete', 'resource' => 'audit-logs', 'action' => 'delete'],
            ['name' => 'Gérer les audits', 'slug' => 'audit-logs.manage', 'resource' => 'audit-logs', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des logs d\'audit', true);
    }

    /**
     * Créer les permissions système
     */
    private function createSystemPermissions(): void
    {
        $permissions = [
            ['name' => 'Accès au tableau de bord', 'slug' => 'dashboard.access', 'resource' => 'dashboard', 'action' => 'read'],
            ['name' => 'Accès administrateur', 'slug' => 'admin.access', 'resource' => 'admin', 'action' => 'read'],
            ['name' => 'Voir les rapports', 'slug' => 'reports.read', 'resource' => 'reports', 'action' => 'read'],
            ['name' => 'Générer des rapports', 'slug' => 'reports.generate', 'resource' => 'reports', 'action' => 'create'],
            ['name' => 'Exporter les rapports', 'slug' => 'reports.export', 'resource' => 'reports', 'action' => 'export'],
            ['name' => 'Voir les logs système', 'slug' => 'logs.read', 'resource' => 'logs', 'action' => 'read'],
            ['name' => 'Gérer les paramètres', 'slug' => 'settings.manage', 'resource' => 'settings', 'action' => 'manage'],
            ['name' => 'Sauvegarder le système', 'slug' => 'backup.create', 'resource' => 'backup', 'action' => 'create'],
            ['name' => 'Restaurer le système', 'slug' => 'backup.restore', 'resource' => 'backup', 'action' => 'restore'],
            ['name' => 'Maintenance du système', 'slug' => 'system.maintenance', 'resource' => 'system', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Système', true);
    }

    /**
     * Créer les permissions
     */
    private function createPermissions(array $permissions, string $category, bool $isSystem = false): void
    {
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                array_merge($permission, [
                    'category' => $category,
                    'is_system' => $isSystem,
                    'is_active' => true,
                    'guard_name' => 'web',
                    'description' => $permission['name'] . ' dans le système',
                ])
            );
        }
    }

    /**
     * Créer les rôles de base
     */
    private function createRoles(): void
    {
        $roles = [
            [
                'name' => 'Super Administrateur',
                'slug' => 'super-admin',
                'description' => 'Chargé de faire la maintenant en cas de disfonctionnement du système',
                'level' => 100, // Autorité spirituelle et administrative suprême
                'is_system_role' => true,
            ],
            [
                'name' => 'Pasteur',
                'slug' => 'pasteur',
                'description' => 'Chargé de voir tout les tableaux de bord sans exception',
                'level' => 90, // Autorité spirituelle et administrative suprême
                'is_system_role' => true,
            ],
            [
                'name' => 'Président(e) du laïque',
                'slug' => 'president-laique',
                'description' => 'Chargé de voir tout les tableaux de bord sans exception',
                'level' => 80, // Deuxième niveau hiérarchique, représente les laïcs
                'is_system_role' => true,
            ],
            [
                'name' => 'Secrétaire',
                'slug' => 'secretaire',
                'description' => 'Pour créer les classes, ajouter les fidèles de la communauté, gérer les réunions et les rapports de réunion.',
                'level' => 70, // Responsabilités administratives étendues
                'is_system_role' => true,
            ],
            [
                'name' => 'Trésorier(ère)',
                'slug' => 'tresorier',
                'description' => 'Gestion financière au niveau de offrande, dime, et autres contributions financières de l\'église.',
                'level' => 60, // Responsabilité financière critique (fonds sacrés)
                'is_system_role' => true,
            ],

            [
                'name' => 'Registreur(se)',
                'slug' => 'regisseur',
                'description' => 'Gestion tous ce qui est FIMECO',
                'level' => 50, // Spécialisé FIMECO, responsabilités ciblées
                'is_system_role' => true,
            ],
            [
                'name' => 'Annonceur(se)',
                'slug' => 'annonceur',
                'description' => 'Lecture des comptes rendus des réunions. Pour ajouter les fidèles de la communauté, Pour faire toutes les actions sur les annonces.',
                'level' => 40, // Communication officielle, responsabilités importantes
                'is_system_role' => true,
            ],
            [
                'name' => 'Service d\'ordre',
                'slug' => 'service-ordre',
                'description' => 'Pour seulement ajouter les fidèles de la communauté et ajouter les participants aux cultes. (Lecture seulement)',
                'level' => 30, // Rôle de soutien, responsabilités limitées
                'is_system_role' => true,
            ],
            [
                'name' => 'Conducteur(trice)',
                'slug' => 'conducteur',
                'description' => 'Affecter les fidèles aux différentes classes',
                'level' => 20, // Spécialisé affectation, responsabilité ciblée
                'is_system_role' => true,
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }


    /**
     * Attribuer les permissions aux rôles
     */
    private function assignPermissionsToRoles(): void
    {
        // Pasteur - Toutes les permissions
        $pasteur = Role::where('slug', 'super-admin')->first();
        if ($pasteur) {

            $permissions = Permission::whereIn(
                'slug',
                [

                    'dashboard.access',
'admin.access',
'reports.read',
'reports.generate',
'reports.export',
'logs.read',
'settings.manage',
'backup.create',
'backup.restore',
'system.maintenance',


'audit-logs.export',
'audit-logs.manage',
'audit-logs.cleanup',
'audit-logs.search',
'audit-logs.bulk-delete',
'audit-logs.show',
'audit-logs.read',
'audit-logs.realtime',
'audit-logs.user-logs',
'audit-logs.statistics',

                    // Users (Membres)
                    'users.read',
                    'users.create',
                    'users.update',
                    'users.delete',
                    'users.export',
                    'users.import',
                    'users.search',
                    'users.ajoutmembre',
                    'users.validate',
                    'users.archive',
                    'users.restore',
                    'users.toggle-status',
                    'users.reset-password',
                    'users.statistics',
                    'users.reports',
                    'users.permissions',
                    'users.roles',

                    // Annonces
                    'annonces.read',
                    'annonces.create',
                    'annonces.update',
                    'annonces.delete',
                    'annonces.culte',
                    'annonces.urgentes',
                    'annonces.statistics',
                    'annonces.actives',
                    'annonces.publish',
                    'annonces.archive',
                    'annonces.duplicate',

                    // Classes
                    'classes.read',
                    'classes.create',
                    'classes.update',
                    'classes.delete',
                    'classes.statistics',
                    'classes.export',
                    'classes.archive',
                    'classes.restore',
                    'classes.duplicate',
                    'classes.bulk-actions',
                    'classes.manage-members',
                    'classes.members',
                    'classes.bulk-add-members',

                    // Contacts
                    'contacts.read',
                    'contacts.create',
                    'contacts.update',
                    'contacts.delete',
                    'contacts.verify',
                    'contacts.bulk-actions',
                    'contacts.duplicate',
                    'contacts.merge',
                    'contacts.search-nearby',
                    'contacts.advanced-search',
                    'contacts.geocode',
                    'contacts.statistics',
                    'contacts.completeness-report',
                    'contacts.export',
                    'contacts.import',
                    'contacts.import-template',
                    'contacts.validate-links',
                    'contacts.cleanup',
                    'contacts.sync-external',
                    'contacts.visibility',

                    // Cultes
                    'cultes.read',
                    'cultes.create',
                    'cultes.update',
                    'cultes.delete',
                    'cultes.planning',
                    'cultes.statistics',
                    'cultes.dashboard',
                    'cultes.change-status',
                    'cultes.duplicate',
                    'cultes.restore',
                    'cultes.export',
                    'cultes.participants',
                    'cultes.manage-participants',

                    // Dons (Donations)
                    'donation.read',
                    'donation.create',
                    'donation.update',
                    'donation.delete',
                    'donation.statistics',
                    'donation.dashboard',
                    'donation.publics',
                    'donation.par-donateur',
                    'donation.rapport-personnalise',
                    'donation.dupliquer',
                    'donation.toggle-statut',
                    'donation.toggle-publication',
                    'donation.export',
                    'donation.telecharger-preuve',
                    'donation.bulk-delete',

                    // Events (Événements)
                    'events.read',
                    'events.create',
                    'events.update',
                    'events.delete',
                    'events.planning',
                    'events.statistics',
                    'events.dashboard',
                    'events.change-status',
                    'events.duplicate',
                    'events.restore',
                    'events.manage-inscriptions',
                    'events.inscriptions',
                    'events.liste-attente',
                    'events.export-participants',
                    'events.notifications',
                    'events.recurrence',
                    'events.medias',
                    'events.finances',
                    'events.finances-rapport',

                    // Fimecos
                    'fimecos.read',
                    'fimecos.create',
                    'fimecos.update',
                    'fimecos.delete',
                    'fimecos.dashboard',
                    'fimecos.rapport',
                    'fimecos.statistiques',
                    'fimecos.live-stats',
                    'fimecos.cloture',
                    'fimecos.reouvrir',
                    'fimecos.desactiver',
                    'fimecos.reactiver',
                    'fimecos.export',
                    'fimecos.search',
                    'fimecos.validate-data',

                    // Subscriptions
                    'subscriptions.read',
                    'subscriptions.create',
                    'subscriptions.update',
                    'subscriptions.delete',
                    'subscriptions.dashboard',
                    'subscriptions.mes-statistiques',
                    'subscriptions.paiement',
                    'subscriptions.desactiver',
                    'subscriptions.reactiver',
                    'subscriptions.validate',
                    'subscriptions.annuler',
                    'subscriptions.suspendre',
                    'subscriptions.export',
                    'subscriptions.validate-data',
                    'subscriptions.rapport',

                    // Paiements
                    'paiements.read',
                    'paiements.create',
                    'paiements.update',
                    'paiements.delete',
                    'paiements.en-attente',
                    'paiements.types-paiement',
                    'paiements.validate',
                    'paiements.reject',
                    'paiements.traiter-en-lot',
                    'paiements.types',
                    'paiements.pending',
                    'paiements.cancel',

                    // Fonds
                    'fonds.read',
                    'fonds.create',
                    'fonds.update',
                    'fonds.delete',
                    'fonds.dashboard',
                    'fonds.statistics',
                    'fonds.analytics',
                    'fonds.reports',
                    'fonds.validate',
                    'fonds.cancel',
                    'fonds.refund',
                    'fonds.restore',
                    'fonds.generate-receipt',
                    'fonds.duplicate',
                    'fonds.export',
                    'fonds.receipt-form',
                    'fonds.receipt-download',
                    'fonds.receipt-preview',
                    'fonds.receipt-email',

                    // Interventions
                    'interventions.read',
                    'interventions.create',
                    'interventions.update',
                    'interventions.delete',
                    'interventions.restore',
                    'interventions.toggle-statut',
                    'interventions.trash',
                    'interventions.par-evenement',

                    // Moissons
                    'moissons.read',
                    'moissons.create',
                    'moissons.update',
                    'moissons.delete',
                    'moissons.dashboard',
                    'moissons.statistics',
                    'moissons.cloturer',
                    'moissons.recalculer-totaux',
                    'moissons.toggle-status',
                    'moissons.export',

                    // Passages Moissons
                    'passagesmoissons.read',
                    'passagesmoissons.create',
                    'passagesmoissons.update',
                    'passagesmoissons.delete',
                    'passagesmoissons.dashboard',
                    'passagesmoissons.statistics',
                    'passagesmoissons.ajouter-montant',
                    'passagesmoissons.toggle-status',
                    'passagesmoissons.export',

                    // Ventes Moissons
                    'ventesmoissons.read',
                    'ventesmoissons.create',
                    'ventesmoissons.update',
                    'ventesmoissons.delete',
                    'ventesmoissons.dashboard',
                    'ventesmoissons.statistics',
                    'ventesmoissons.ajouter-montant',
                    'ventesmoissons.toggle-status',
                    'ventesmoissons.export',

                    // Engagements Moissons
                    'engagementsmoissons.read',
                    'engagementsmoissons.create',
                    'engagementsmoissons.update',
                    'engagementsmoissons.delete',
                    'engagementsmoissons.dashboard',
                    'engagementsmoissons.statistics',
                    'engagementsmoissons.ajouter-montant',
                    'engagementsmoissons.planifier-rappel',
                    'engagementsmoissons.prolonger-echeance',
                    'engagementsmoissons.toggle-status',
                    'engagementsmoissons.export',

                    // Multimedia
                    'multimedia.read',
                    'multimedia.create',
                    'multimedia.update',
                    'multimedia.delete',
                    'multimedia.galerie',
                    'multimedia.statistics',
                    'multimedia.download',
                    'multimedia.approve',
                    'multimedia.reject',
                    'multimedia.toggle-featured',
                    'multimedia.moderate',
                    'multimedia.bulk-moderate',
                    'multimedia.manage-storage',
                    'multimedia.storage-cleanup',
                    'multimedia.storage-optimize',

                    // Paramètres
                    'parametres.read',
                    'parametres.update',
                    'parametres.update-logo',

                    // Paramètres Dons
                    'parametresdons.read',
                    'parametresdons.create',
                    'parametresdons.update',
                    'parametresdons.delete',
                    'parametresdons.statistics',
                    'parametresdons.publics',
                    'parametresdons.toggle-status',
                    'parametresdons.toggle-publication',
                    'parametresdons.export',

                    // Participants Cultes
                    'participant_cultes.read',
                    'participant_cultes.create',
                    'participant_cultes.update',
                    'participant_cultes.delete',
                    'participant_cultes.statistics',
                    'participant_cultes.nouveaux-visiteurs',
                    'participant_cultes.search',
                    'participant_cultes.ajouter-participant',
                    'participant_cultes.create-with-user',
                    'participant_cultes.bulk-create-with-user',
                    'participant_cultes.confirmer-presence',

                    // Permissions
                    'permissions.read',
                    'permissions.create',
                    'permissions.update',
                    'permissions.delete',
                    'permissions.statistics',
                    'permissions.bulk-assign',
                    'permissions.clone',
                    'permissions.toggle',
                    'permissions.export',
                    'permissions.grant',
                    'permissions.revoke',
                    'permissions.sync',
                    'permissions.audit',

                    // Programmes
                    'programmes.read',
                    'programmes.create',
                    'programmes.update',
                    'programmes.delete',
                    'programmes.planning',
                    'programmes.statistics',
                    'programmes.actifs',
                    'programmes.metadata',
                    'programmes.activate',
                    'programmes.suspend',
                    'programmes.terminate',
                    'programmes.cancel',
                    'programmes.duplicate',

                    // Projets
                    'projets.read',
                    'projets.create',
                    'projets.update',
                    'projets.delete',
                    'projets.statistics',
                    'projets.public',
                    'projets.options',
                    'projets.approve',
                    'projets.plan',
                    'projets.seek-funding',
                    'projets.start',
                    'projets.suspend',
                    'projets.resume',
                    'projets.complete',
                    'projets.cancel',
                    'projets.put-on-hold',
                    'projets.update-progress',
                    'projets.duplicate',
                    'projets.upload-images',
                    'projets.workflow',
                    'projets.statut-detaille',

                    // Rapports Réunions
                    'rapports-reunions.read',
                    'rapports-reunions.create',
                    'rapports-reunions.update',
                    'rapports-reunions.delete',
                    'rapports-reunions.revision',
                    'rapports-reunions.validate',
                    'rapports-reunions.publish',
                    'rapports-reunions.reject',
                    'rapports-reunions.manage-presences',
                    'rapports-reunions.manage-actions',
                    'rapports-reunions.export',
                    'rapports-reunions.download-pdf',
                    'rapports-reunions.statistics',
                    'rapports-reunions.restore',
                    'rapports-reunions.en-attente',
                    'rapports-reunions.publies',
                    'rapports-reunions.mes-rapports',
                    'rapports-reunions.a-valider',
                    'rapports-reunions.archives',

                    // Réunions
                    'reunions.read',
                    'reunions.create',
                    'reunions.update',
                    'reunions.delete',
                    'reunions.statistics',
                    'reunions.upcoming',
                    'reunions.today',
                    'reunions.calendar',
                    'reunions.public',
                    'reunions.live-stream',
                    'reunions.confirm',
                    'reunions.start',
                    'reunions.end',
                    'reunions.cancel',
                    'reunions.postpone',
                    'reunions.suspend',
                    'reunions.resume',
                    'reunions.mark-attendance',
                    'reunions.register-participant',
                    'reunions.unregister-participant',
                    'reunions.add-spiritual-results',
                    'reunions.add-testimonies',
                    'reunions.add-prayer-requests',
                    'reunions.evaluate',
                    'reunions.duplicate',
                    'reunions.create-recurrence',
                    'reunions.restore',
                    'reunions.send-reminders',
                    'reunions.notify-participants',
                    'reunions.upload-documents',

                    // Rôles
                    'roles.read',
                    'roles.create',
                    'roles.update',
                    'roles.delete',
                    'roles.hierarchy',
                    'roles.compare',
                    'roles.permissions',
                    'roles.sync-permissions',
                    'roles.assign',
                    'roles.remove',
                    'roles.clone',
                    'roles.export',
                    'roles.manage',

                    // Types de Réunions
                    'types-reunions.read',
                    'types-reunions.create',
                    'types-reunions.update',
                    'types-reunions.delete',
                    'types-reunions.statistics',
                    'types-reunions.categories',
                    'types-reunions.options',
                    'types-reunions.archive',
                    'types-reunions.restore',
                    'types-reunions.activate',
                    'types-reunions.deactivate',
                    'types-reunions.duplicate',

                    // Dashboard
                    'dashboard.read',
                    'dashboard.export',
                    'dashboard.statistics-periode'
                ]
            )->pluck('id')->toArray();

            $pasteur->syncPermissions($permissions);
        }

        // // Président du laïque - Presque toutes les permissions (dashboard, lectures, rapports)
        // $presidentLaique = Role::where('slug', 'president-laique')->first();
        // if ($presidentLaique) {

        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // Membress
        //         'users.read', 'users.export', 'users.search', 'users.statistics', 'users.reports',
        //         // Classes
        //         'classes.read', 'classes.statistics', 'classes.export',
        //         // Réunions
        //         'reunions.read', 'reunions.statistics', 'reunions.upcoming', 'reunions.today', 'reunions.calendar', 'reunions.public', 'reunions.live-stream',

        //         // Rapports de réunions
        //         'rapports-reunions.read', 'rapports-reunions.export', 'rapports-reunions.download-pdf', 'types-reunions.categories',
        //         // Types de réunions
        //         'types-reunions.read', 'types-reunions.statistics', 'types-reunions.export',
        //         // Contacts
        //         'contacts.read', 'contacts.statistics', 'contacts.export', 'contacts.completeness-report', 'contacts.search-nearby', 'contacts.advanced-search', 'contacts.import', 'contacts.import-template',

        //         //Programmes
        //         'programmes.read', 'programmes.planning', 'programmes.statistics', 'programmes.actifs', 'programmes.metadata',

        //         //Evénements
        //         'events.read','events.planning','events.statistics','events.dashboard','events.export-participants',

        //         // Annonces (lecture seule)
        //         'annonces.read', 'annonces.culte', 'annonces.urgentes', 'annonces.statistics', 'annonces.actives',

        //         // Cultes (lecture seule)
        //         'cultes.read', 'cultes.planning', 'cultes.statistics', 'cultes.dashboard', 'cultes.participants', 'cultes.export-participants',

        //         // Participants des cultes (lecture seule)
        //         'participants-cultes.read', 'participants-cultes.nouveaux-visiteurs', 'participants-cultes.search', 'participants-cultes.statistics',

        //         // Rapports généraux
        //         'reports.read', 'reports.generate', 'reports.export',
        //         //Paiements
        //         'paiements.read',
        //         //FIMECO
        //         'fimecos.read', 'fimecos.statistics', 'fimecos.dashboard',
        //         //Souscriptions
        //         'subscriptions.read', 'subscriptions.my-statistics',
        //         //Fonds
        //         'fonds.read', 'fonds.statistics', 'fonds.analytics', 'fonds.reports', 'fonds.export', 'fonds.dashboard', 'fonds.generate-receipt',
        //         // Projets
        //         'projets.read', 'projets.statistics', 'projets.public', 'projets.seek-funding',

        //         // Multimédia
        //         'multimedia.read', 'multimedia.gallery', 'multimedia.statistics', 'multimedia.download',
        //         // Interventions
        //         'interventions.read', 'interventions.by-event',



        //     ])->pluck('id')->toArray();
        //     $presidentLaique->syncPermissions($permissions);
        // }

        // Secrétaire
        // $secretaire = Role::where('slug', 'secretaire')->first();
        // if ($secretaire) {

        //     $permissions = Permission::all()->pluck('id')->toArray();
        //     $secretaire->syncPermissions($permissions);
        // }

        // Annonceur
        // $annonceur = Role::where('slug', 'annonceur')->first();
        // if ($annonceur) {
        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // Membress (ajouter fidèles)
        //         'users.read', 'users.create', 'users.update',
        //         // Annonces (toutes les actions)
        //         'annonces.read', 'annonces.create', 'annonces.update', 'annonces.delete',
        //         'annonces.publish', 'annonces.archive', 'annonces.duplicate',
        //         'annonces.culte', 'annonces.urgentes', 'annonces.statistics', 'annonces.actives',
        //         // Rapports de réunions (lecture)
        //         'rapports-reunions.read',
        //         // Contacts
        //         'contacts.read',
        //     ])->pluck('id')->toArray();
        //     $annonceur->syncPermissions($permissions);
        // }

        // Service d'ordre
        // $serviceOrdre = Role::where('slug', 'service-ordre')->first();
        // if ($serviceOrdre) {
        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // Membress (ajouter fidèles seulement)
        //         'users.read', 'users.create',
        //         // Participants aux cultes
        //         'participants-cultes.read', 'participants-cultes.create',
        //         'participants-cultes.search', 'participants-cultes.confirm-presence',
        //         'participants-cultes.nouveaux-visiteurs',
        //         // Cultes (lecture + participants)
        //         'cultes.read', 'cultes.participants',
        //         // Contacts (lecture)
        //         'contacts.read',
        //     ])->pluck('id')->toArray();
        //     $serviceOrdre->syncPermissions($permissions);
        // }

        // Registrateur (FIMECO)
        // $regisseur = Role::where('slug', 'regisseur')->first();
        // if ($regisseur) {
        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // FIMECO (toutes les actions)
        //         'fimecos.read', 'fimecos.create', 'fimecos.update', 'fimecos.delete',
        //         'fimecos.dashboard', 'fimecos.close', 'fimecos.statistics',
        //         'fimecos.deactivate', 'fimecos.reactivate',
        //         // Souscriptions
        //         'subscriptions.read', 'subscriptions.create', 'subscriptions.update',
        //         'subscriptions.cancel', 'subscriptions.suspend',
        //         'subscriptions.available-fimecos', 'subscriptions.available-users',
        //         // Membress (lecture)
        //         'users.read',
        //         // Rapports
        //         'reports.read', 'reports.generate',
        //     ])->pluck('id')->toArray();
        //     $regisseur->syncPermissions($permissions);
        // }

        // Trésorier
        // $tresorier = Role::where('slug', 'tresorier')->first();
        // if ($tresorier) {
        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // Fonds (gestion financière)
        //         'fonds.read', 'fonds.create', 'fonds.update', 'fonds.dashboard',
        //         'fonds.statistics', 'fonds.analytics', 'fonds.reports',
        //         'fonds.validate', 'fonds.generate-receipt', 'fonds.export',
        //         // Paiements
        //         'paiements.read', 'paiements.create', 'paiements.update',
        //         'paiements.validate', 'paiements.reject', 'paiements.pending',
        //         'paiements.batch-process', 'paiements.types',
        //         // FIMECO (lecture et paiements)
        //         'fimecos.read', 'fimecos.statistics',
        //         // Souscriptions (lecture)
        //         'subscriptions.read', 'subscriptions.my-statistics',
        //         // Rapports financiers
        //         'reports.read', 'reports.generate', 'reports.export',
        //         // Membress (lecture)
        //         'users.read',
        //     ])->pluck('id')->toArray();
        //     $tresorier->syncPermissions($permissions);
        // }

        // Conducteur
        // $conducteur = Role::where('slug', 'conducteur')->first();
        // if ($conducteur) {
        //     $permissions = Permission::whereIn('slug', [
        //         'dashboard.access',
        //         // Classes (affecter fidèles)
        //         'classes.read', 'classes.manage-members', 'classes.inscrire',
        //         'classes.desinscrire', 'classes.statistics',
        //         // Membress
        //         'users.read', 'users.search',
        //         // Programmes
        //         'programmes.read', 'programmes.statistics',
        //         // Cultes (lecture)
        //         'cultes.read', 'cultes.statistics',
        //     ])->pluck('id')->toArray();
        //     $conducteur->syncPermissions($permissions);
        // }
    }
}
