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
            $this->createUserPermissions();
            $this->createRolePermissions();
            $this->createPermissionPermissions();
            $this->createClassePermissions();
            $this->createCultePermissions();
            $this->createParticipantCultePermissions();
            $this->createEventPermissions();
            $this->createAnnoncePermissions();
            $this->createProgrammePermissions();
            $this->createReunionPermissions();
            $this->createTypeReunionPermissions();
            $this->createRapportReunionPermissions();
            $this->createContactPermissions();
            $this->createInterventionPermissions();
            $this->createMultimediaPermissions();
            $this->createProjetPermissions();
            $this->createFondsPermissions();
            $this->createFimecoPermissions();
            $this->createSubscriptionPermissions();
            $this->createPaymentPermissions();
            $this->createAuditLogPermissions();
            $this->createSystemPermissions();

            // Créer les rôles de base
            $this->createRoles();

            // Attribuer les permissions aux rôles
            $this->assignPermissionsToRoles();
        });

        $this->command->info('✅ Permissions et rôles créés avec succès !');
    }

    /**
     * Créer les permissions pour les utilisateurs
     */
    private function createUserPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les utilisateurs', 'slug' => 'users.read', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Créer des utilisateurs', 'slug' => 'users.create', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'Modifier les utilisateurs', 'slug' => 'users.update', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Supprimer les utilisateurs', 'slug' => 'users.delete', 'resource' => 'users', 'action' => 'delete'],
            ['name' => 'Exporter les utilisateurs', 'slug' => 'users.export', 'resource' => 'users', 'action' => 'export'],
            ['name' => 'Importer les utilisateurs', 'slug' => 'users.import', 'resource' => 'users', 'action' => 'import'],
            ['name' => 'Rechercher les utilisateurs', 'slug' => 'users.search', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Valider les utilisateurs', 'slug' => 'users.validate', 'resource' => 'users', 'action' => 'validate'],
            ['name' => 'Archiver les utilisateurs', 'slug' => 'users.archive', 'resource' => 'users', 'action' => 'archive'],
            ['name' => 'Restaurer les utilisateurs', 'slug' => 'users.restore', 'resource' => 'users', 'action' => 'restore'],
            ['name' => 'Changer statut utilisateurs', 'slug' => 'users.toggle-status', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Réinitialiser mot de passe', 'slug' => 'users.reset-password', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Voir statistiques utilisateurs', 'slug' => 'users.statistics', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Voir rapports utilisateurs', 'slug' => 'users.reports', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'Gérer permissions utilisateurs', 'slug' => 'users.permissions', 'resource' => 'users', 'action' => 'manage'],
            ['name' => 'Gérer rôles utilisateurs', 'slug' => 'users.roles', 'resource' => 'users', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des utilisateurs');
    }

    /**
     * Créer les permissions pour les rôles
     */
    private function createRolePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les rôles', 'slug' => 'roles.read', 'resource' => 'roles', 'action' => 'read'],
            ['name' => 'Créer des rôles', 'slug' => 'roles.create', 'resource' => 'roles', 'action' => 'create'],
            ['name' => 'Modifier les rôles', 'slug' => 'roles.update', 'resource' => 'roles', 'action' => 'update'],
            ['name' => 'Supprimer les rôles', 'slug' => 'roles.delete', 'resource' => 'roles', 'action' => 'delete'],
            ['name' => 'Voir hiérarchie des rôles', 'slug' => 'roles.hierarchy', 'resource' => 'roles', 'action' => 'read'],
            ['name' => 'Comparer les rôles', 'slug' => 'roles.compare', 'resource' => 'roles', 'action' => 'read'],
            ['name' => 'Exporter les rôles', 'slug' => 'roles.export', 'resource' => 'roles', 'action' => 'export'],
            ['name' => 'Cloner des rôles', 'slug' => 'roles.clone', 'resource' => 'roles', 'action' => 'create'],
            ['name' => 'Gérer permissions des rôles', 'slug' => 'roles.permissions', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Attribuer rôles aux utilisateurs', 'slug' => 'roles.assign', 'resource' => 'roles', 'action' => 'update'],
            ['name' => 'Retirer rôles aux utilisateurs', 'slug' => 'roles.remove', 'resource' => 'roles', 'action' => 'update'],

            //roles.manage
            ["name" => 'Gérer les rôles', 'slug' => 'roles.manage', 'resource' => 'roles', 'action' => 'manage'],

        ];

        $this->createPermissions($permissions, 'Gestion des rôles');
    }


    /**
     * Créer les permissions pour les permissions
     */
    private function createPermissionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les permissions', 'slug' => 'permissions.read', 'resource' => 'permissions', 'action' => 'read'],
            ['name' => 'Créer des permissions', 'slug' => 'permissions.create', 'resource' => 'permissions', 'action' => 'create'],
            ['name' => 'Modifier les permissions', 'slug' => 'permissions.update', 'resource' => 'permissions', 'action' => 'update'],
            ['name' => 'Supprimer les permissions', 'slug' => 'permissions.delete', 'resource' => 'permissions', 'action' => 'delete'],
            ['name' => 'Attribution en lot des permissions', 'slug' => 'permissions.bulk-assign', 'resource' => 'permissions', 'action' => 'update'],
            ['name' => 'Voir statistiques des permissions', 'slug' => 'permissions.statistics', 'resource' => 'permissions', 'action' => 'read'],
            ['name' => 'Exporter les permissions', 'slug' => 'permissions.export', 'resource' => 'permissions', 'action' => 'export'],
            ['name' => 'Cloner des permissions', 'slug' => 'permissions.clone', 'resource' => 'permissions', 'action' => 'create'],
            ['name' => 'Activer/Désactiver permissions', 'slug' => 'permissions.toggle', 'resource' => 'permissions', 'action' => 'update'],
            ['name' => 'Gérer les permissions système', 'slug' => 'permissions.system', 'resource' => 'permissions', 'action' => 'manage']



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
            ['name' => 'Voir statistiques des classes', 'slug' => 'classes.statistics', 'resource' => 'classes', 'action' => 'read'],
            ['name' => 'Gérer membres des classes', 'slug' => 'classes.manage-members', 'resource' => 'classes', 'action' => 'manage'],
            ['name' => 'Inscrire dans les classes', 'slug' => 'classes.inscrire', 'resource' => 'classes', 'action' => 'update'],
            ['name' => 'Désinscrire des classes', 'slug' => 'classes.desinscrire', 'resource' => 'classes', 'action' => 'update'],
            ['name' => 'Changer statut des classes', 'slug' => 'classes.toggle-status', 'resource' => 'classes', 'action' => 'update'],
            ['name' => 'Dupliquer des classes', 'slug' => 'classes.duplicate', 'resource' => 'classes', 'action' => 'create'],
            ['name' => 'Exporter des classes', 'slug' => 'classes.export', 'resource' => 'classes', 'action' => 'export'],
            ['name' => 'Actions en lot sur les classes', 'slug' => 'classes.bulk-actions', 'resource' => 'classes', 'action' => 'update'],
            ['name' => 'Archiver des classes', 'slug' => 'classes.archive', 'resource' => 'classes', 'action' => 'archive'],
            ['name' => 'Restaurer des classes', 'slug' => 'classes.restore', 'resource' => 'classes', 'action' => 'restore'],
        ];

        $this->createPermissions($permissions, 'Gestion des classes');
    }

    /**
     * Créer les permissions pour les cultes
     */
    private function createCultePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les cultes', 'slug' => 'cultes.read', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Créer des cultes', 'slug' => 'cultes.create', 'resource' => 'cultes', 'action' => 'create'],
            ['name' => 'Modifier les cultes', 'slug' => 'cultes.update', 'resource' => 'cultes', 'action' => 'update'],
            ['name' => 'Supprimer les cultes', 'slug' => 'cultes.delete', 'resource' => 'cultes', 'action' => 'delete'],
            ['name' => 'Voir planning des cultes', 'slug' => 'cultes.planning', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Voir statistiques des cultes', 'slug' => 'cultes.statistics', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Voir tableau de bord des cultes', 'slug' => 'cultes.dashboard', 'resource' => 'cultes', 'action' => 'read'],
            ['name' => 'Changer statut des cultes', 'slug' => 'cultes.change-status', 'resource' => 'cultes', 'action' => 'update'],
            ['name' => 'Dupliquer des cultes', 'slug' => 'cultes.duplicate', 'resource' => 'cultes', 'action' => 'create'],
            ['name' => 'Restaurer des cultes', 'slug' => 'cultes.restore', 'resource' => 'cultes', 'action' => 'restore'],
            ['name' => 'Voir participants des cultes', 'slug' => 'cultes.participants', 'resource' => 'cultes', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des cultes');
    }

    /**
     * Créer les permissions pour les participants des cultes
     */
    private function createParticipantCultePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir participants des cultes', 'slug' => 'participants-cultes.read', 'resource' => 'participants-cultes', 'action' => 'read'],
            ['name' => 'Ajouter participants aux cultes', 'slug' => 'participants-cultes.create', 'resource' => 'participants-cultes', 'action' => 'create'],
            ['name' => 'Modifier participants des cultes', 'slug' => 'participants-cultes.update', 'resource' => 'participants-cultes', 'action' => 'update'],
            ['name' => 'Supprimer participants des cultes', 'slug' => 'participants-cultes.delete', 'resource' => 'participants-cultes', 'action' => 'delete'],
            ['name' => 'Voir nouveaux visiteurs', 'slug' => 'participants-cultes.nouveaux-visiteurs', 'resource' => 'participants-cultes', 'action' => 'read'],
            ['name' => 'Rechercher participants', 'slug' => 'participants-cultes.search', 'resource' => 'participants-cultes', 'action' => 'read'],
            ['name' => 'Créer avec utilisateur', 'slug' => 'participants-cultes.create-with-user', 'resource' => 'participants-cultes', 'action' => 'create'],
            ['name' => 'Création en lot avec utilisateurs', 'slug' => 'participants-cultes.bulk-create-with-user', 'resource' => 'participants-cultes', 'action' => 'create'],
            ['name' => 'Confirmer présence', 'slug' => 'participants-cultes.confirm-presence', 'resource' => 'participants-cultes', 'action' => 'update'],
            ['name' => 'Voir statistiques participants', 'slug' => 'participants-cultes.statistics', 'resource' => 'participants-cultes', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des participants aux cultes');
    }

    /**
     * Créer les permissions pour les événements
     */
    private function createEventPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les événements', 'slug' => 'events.read', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Créer des événements', 'slug' => 'events.create', 'resource' => 'events', 'action' => 'create'],
            ['name' => 'Modifier les événements', 'slug' => 'events.update', 'resource' => 'events', 'action' => 'update'],
            ['name' => 'Supprimer les événements', 'slug' => 'events.delete', 'resource' => 'events', 'action' => 'delete'],
            ['name' => 'Voir planning des événements', 'slug' => 'events.planning', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Voir statistiques des événements', 'slug' => 'events.statistics', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Voir tableau de bord des événements', 'slug' => 'events.dashboard', 'resource' => 'events', 'action' => 'read'],
            ['name' => 'Changer statut des événements', 'slug' => 'events.change-status', 'resource' => 'events', 'action' => 'update'],
            ['name' => 'Dupliquer des événements', 'slug' => 'events.duplicate', 'resource' => 'events', 'action' => 'create'],
            ['name' => 'Restaurer des événements', 'slug' => 'events.restore', 'resource' => 'events', 'action' => 'restore'],
            ['name' => 'Gérer inscriptions aux événements', 'slug' => 'events.manage-inscriptions', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Gérer liste d\'attente', 'slug' => 'events.manage-waitlist', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Exporter participants', 'slug' => 'events.export-participants', 'resource' => 'events', 'action' => 'export'],
            ['name' => 'Envoyer notifications', 'slug' => 'events.send-notifications', 'resource' => 'events', 'action' => 'update'],
            ['name' => 'Gérer récurrence', 'slug' => 'events.manage-recurrence', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Gérer médias', 'slug' => 'events.manage-media', 'resource' => 'events', 'action' => 'manage'],
            ['name' => 'Gérer finances', 'slug' => 'events.manage-finances', 'resource' => 'events', 'action' => 'manage'],
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
            ['name' => 'Voir annonces pour le culte', 'slug' => 'annonces.culte', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir annonces urgentes', 'slug' => 'annonces.urgentes', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir statistiques des annonces', 'slug' => 'annonces.statistics', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Voir annonces actives', 'slug' => 'annonces.actives', 'resource' => 'annonces', 'action' => 'read'],
            ['name' => 'Publier les annonces', 'slug' => 'annonces.publish', 'resource' => 'annonces', 'action' => 'update'],
            ['name' => 'Archiver les annonces', 'slug' => 'annonces.archive', 'resource' => 'annonces', 'action' => 'archive'],
            ['name' => 'Dupliquer des annonces', 'slug' => 'annonces.duplicate', 'resource' => 'annonces', 'action' => 'create'],
        ];

        $this->createPermissions($permissions, 'Gestion des annonces');
    }

    /**
     * Créer les permissions pour les programmes
     */
    private function createProgrammePermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les programmes', 'slug' => 'programmes.read', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Créer des programmes', 'slug' => 'programmes.create', 'resource' => 'programmes', 'action' => 'create'],
            ['name' => 'Modifier les programmes', 'slug' => 'programmes.update', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Supprimer les programmes', 'slug' => 'programmes.delete', 'resource' => 'programmes', 'action' => 'delete'],
            ['name' => 'Voir planning des programmes', 'slug' => 'programmes.planning', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir statistiques des programmes', 'slug' => 'programmes.statistics', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir programmes actifs', 'slug' => 'programmes.actifs', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Voir métadonnées des programmes', 'slug' => 'programmes.metadata', 'resource' => 'programmes', 'action' => 'read'],
            ['name' => 'Activer des programmes', 'slug' => 'programmes.activate', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Suspendre des programmes', 'slug' => 'programmes.suspend', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Terminer des programmes', 'slug' => 'programmes.terminate', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Annuler des programmes', 'slug' => 'programmes.cancel', 'resource' => 'programmes', 'action' => 'update'],
            ['name' => 'Dupliquer des programmes', 'slug' => 'programmes.duplicate', 'resource' => 'programmes', 'action' => 'create'],
        ];

        $this->createPermissions($permissions, 'Gestion des programmes');
    }

    /**
     * Créer les permissions pour les réunions
     */
    private function createReunionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les réunions', 'slug' => 'reunions.read', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Créer des réunions', 'slug' => 'reunions.create', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Modifier les réunions', 'slug' => 'reunions.update', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Supprimer les réunions', 'slug' => 'reunions.delete', 'resource' => 'reunions', 'action' => 'delete'],
            ['name' => 'Voir statistiques des réunions', 'slug' => 'reunions.statistics', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions à venir', 'slug' => 'reunions.upcoming', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions du jour', 'slug' => 'reunions.today', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir calendrier des réunions', 'slug' => 'reunions.calendar', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir réunions publiques', 'slug' => 'reunions.public', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Voir diffusion live', 'slug' => 'reunions.live-stream', 'resource' => 'reunions', 'action' => 'read'],
            ['name' => 'Confirmer des réunions', 'slug' => 'reunions.confirm', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Commencer des réunions', 'slug' => 'reunions.start', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Terminer des réunions', 'slug' => 'reunions.end', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Annuler des réunions', 'slug' => 'reunions.cancel', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Reporter des réunions', 'slug' => 'reunions.postpone', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Suspendre des réunions', 'slug' => 'reunions.suspend', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Reprendre des réunions', 'slug' => 'reunions.resume', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Marquer présences', 'slug' => 'reunions.mark-attendance', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Inscrire participants', 'slug' => 'reunions.register-participant', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Désinscrire participants', 'slug' => 'reunions.unregister-participant', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Ajouter résultats spirituels', 'slug' => 'reunions.add-spiritual-results', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Ajouter témoignages', 'slug' => 'reunions.add-testimonies', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Ajouter demandes de prière', 'slug' => 'reunions.add-prayer-requests', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Évaluer des réunions', 'slug' => 'reunions.evaluate', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Dupliquer des réunions', 'slug' => 'reunions.duplicate', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Créer récurrence', 'slug' => 'reunions.create-recurrence', 'resource' => 'reunions', 'action' => 'create'],
            ['name' => 'Envoyer rappels', 'slug' => 'reunions.send-reminders', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Notifier participants', 'slug' => 'reunions.notify-participants', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Upload photos', 'slug' => 'reunions.upload-photos', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Upload documents', 'slug' => 'reunions.upload-documents', 'resource' => 'reunions', 'action' => 'update'],
            ['name' => 'Restaurer des réunions', 'slug' => 'reunions.restore', 'resource' => 'reunions', 'action' => 'restore'],
        ];

        $this->createPermissions($permissions, 'Gestion des réunions');
    }

    /**
     * Créer les permissions pour les types de réunions
     */
    private function createTypeReunionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les types de réunions', 'slug' => 'types-reunions.read', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Créer des types de réunions', 'slug' => 'types-reunions.create', 'resource' => 'types-reunions', 'action' => 'create'],
            ['name' => 'Modifier les types de réunions', 'slug' => 'types-reunions.update', 'resource' => 'types-reunions', 'action' => 'update'],
            ['name' => 'Supprimer les types de réunions', 'slug' => 'types-reunions.delete', 'resource' => 'types-reunions', 'action' => 'delete'],
            ['name' => 'Voir statistiques des types', 'slug' => 'types-reunions.statistics', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Voir catégories disponibles', 'slug' => 'types-reunions.categories', 'resource' => 'types-reunions', 'action' => 'read'],
            ['name' => 'Archiver types de réunions', 'slug' => 'types-reunions.archive', 'resource' => 'types-reunions', 'action' => 'archive'],
            ['name' => 'Restaurer types de réunions', 'slug' => 'types-reunions.restore', 'resource' => 'types-reunions', 'action' => 'restore'],
            ['name' => 'Dupliquer types de réunions', 'slug' => 'types-reunions.duplicate', 'resource' => 'types-reunions', 'action' => 'create'],
            ['name' => 'Activer types de réunions', 'slug' => 'types-reunions.activate', 'resource' => 'types-reunions', 'action' => 'update'],
            ['name' => 'Désactiver types de réunions', 'slug' => 'types-reunions.deactivate', 'resource' => 'types-reunions', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des types de réunions');
    }

    /**
     * Créer les permissions pour les rapports de réunions
     */
    private function createRapportReunionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les rapports de réunions', 'slug' => 'rapports-reunions.read', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Créer des rapports de réunions', 'slug' => 'rapports-reunions.create', 'resource' => 'rapports-reunions', 'action' => 'create'],
            ['name' => 'Modifier les rapports de réunions', 'slug' => 'rapports-reunions.update', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Supprimer les rapports de réunions', 'slug' => 'rapports-reunions.delete', 'resource' => 'rapports-reunions', 'action' => 'delete'],
            ['name' => 'Passer en révision', 'slug' => 'rapports-reunions.revision', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Valider les rapports', 'slug' => 'rapports-reunions.validate', 'resource' => 'rapports-reunions', 'action' => 'validate'],
            ['name' => 'Publier les rapports', 'slug' => 'rapports-reunions.publish', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Rejeter les rapports', 'slug' => 'rapports-reunions.reject', 'resource' => 'rapports-reunions', 'action' => 'update'],
            ['name' => 'Gérer présences dans rapports', 'slug' => 'rapports-reunions.manage-attendance', 'resource' => 'rapports-reunions', 'action' => 'manage'],
            ['name' => 'Gérer actions de suivi', 'slug' => 'rapports-reunions.manage-actions', 'resource' => 'rapports-reunions', 'action' => 'manage'],
            ['name' => 'Voir statistiques des rapports', 'slug' => 'rapports-reunions.statistics', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Exporter les rapports', 'slug' => 'rapports-reunions.export', 'resource' => 'rapports-reunions', 'action' => 'export'],
            ['name' => 'Télécharger PDF', 'slug' => 'rapports-reunions.download-pdf', 'resource' => 'rapports-reunions', 'action' => 'read'],
            ['name' => 'Restaurer les rapports', 'slug' => 'rapports-reunions.restore', 'resource' => 'rapports-reunions', 'action' => 'restore'],
        ];

        $this->createPermissions($permissions, 'Gestion des rapports de réunions');
    }

    /**
     * Créer les permissions pour les contacts
     */
    private function createContactPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les contacts', 'slug' => 'contacts.read', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Créer des contacts', 'slug' => 'contacts.create', 'resource' => 'contacts', 'action' => 'create'],
            ['name' => 'Modifier les contacts', 'slug' => 'contacts.update', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Supprimer les contacts', 'slug' => 'contacts.delete', 'resource' => 'contacts', 'action' => 'delete'],
            ['name' => 'Vérifier les contacts', 'slug' => 'contacts.verify', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Actions en lot sur contacts', 'slug' => 'contacts.bulk-actions', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Recherche géographique', 'slug' => 'contacts.search-nearby', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Recherche avancée contacts', 'slug' => 'contacts.advanced-search', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Voir statistiques contacts', 'slug' => 'contacts.statistics', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Rapport de complétude', 'slug' => 'contacts.completeness-report', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Exporter les contacts', 'slug' => 'contacts.export', 'resource' => 'contacts', 'action' => 'export'],
            ['name' => 'Importer les contacts', 'slug' => 'contacts.import', 'resource' => 'contacts', 'action' => 'import'],
            ['name' => 'Télécharger modèle d\'import', 'slug' => 'contacts.import-template', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Modifier visibilité', 'slug' => 'contacts.update-visibility', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Générer QR code', 'slug' => 'contacts.generate-qr', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Géocoder manuellement', 'slug' => 'contacts.geocode', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Dupliquer des contacts', 'slug' => 'contacts.duplicate', 'resource' => 'contacts', 'action' => 'create'],
            ['name' => 'Fusionner des contacts', 'slug' => 'contacts.merge', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Valider liens et emails', 'slug' => 'contacts.validate-links', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Nettoyer données obsolètes', 'slug' => 'contacts.cleanup', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Synchroniser avec services externes', 'slug' => 'contacts.sync-external', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Administration avancée contacts', 'slug' => 'contacts.admin', 'resource' => 'contacts', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion des contacts');
    }

    /**
     * Créer les permissions pour les interventions
     */
    private function createInterventionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les interventions', 'slug' => 'interventions.read', 'resource' => 'interventions', 'action' => 'read'],
            ['name' => 'Créer des interventions', 'slug' => 'interventions.create', 'resource' => 'interventions', 'action' => 'create'],
            ['name' => 'Modifier les interventions', 'slug' => 'interventions.update', 'resource' => 'interventions', 'action' => 'update'],
            ['name' => 'Supprimer les interventions', 'slug' => 'interventions.delete', 'resource' => 'interventions', 'action' => 'delete'],
            ['name' => 'Voir interventions supprimées', 'slug' => 'interventions.trash', 'resource' => 'interventions', 'action' => 'read'],
            ['name' => 'Restaurer des interventions', 'slug' => 'interventions.restore', 'resource' => 'interventions', 'action' => 'restore'],
            ['name' => 'Changer statut des interventions', 'slug' => 'interventions.change-status', 'resource' => 'interventions', 'action' => 'update'],
            ['name' => 'Voir interventions par événement', 'slug' => 'interventions.by-event', 'resource' => 'interventions', 'action' => 'read'],
        ];

        $this->createPermissions($permissions, 'Gestion des interventions');
    }

    /**
     * Créer les permissions pour le multimédia
     */
    private function createMultimediaPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les médias', 'slug' => 'multimedia.read', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Créer des médias', 'slug' => 'multimedia.create', 'resource' => 'multimedia', 'action' => 'create'],
            ['name' => 'Modifier les médias', 'slug' => 'multimedia.update', 'resource' => 'multimedia', 'action' => 'update'],
            ['name' => 'Supprimer les médias', 'slug' => 'multimedia.delete', 'resource' => 'multimedia', 'action' => 'delete'],
            ['name' => 'Télécharger des médias', 'slug' => 'multimedia.download', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Approuver des médias', 'slug' => 'multimedia.approve', 'resource' => 'multimedia', 'action' => 'approve'],
            ['name' => 'Rejeter des médias', 'slug' => 'multimedia.reject', 'resource' => 'multimedia', 'action' => 'reject'],
            ['name' => 'Mettre en vedette', 'slug' => 'multimedia.toggle-featured', 'resource' => 'multimedia', 'action' => 'update'],
            ['name' => 'Modération en lot', 'slug' => 'multimedia.bulk-moderate', 'resource' => 'multimedia', 'action' => 'moderate'],
            ['name' => 'Voir galerie publique', 'slug' => 'multimedia.gallery', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Voir statistiques médias', 'slug' => 'multimedia.statistics', 'resource' => 'multimedia', 'action' => 'read'],
            ['name' => 'Modération des médias', 'slug' => 'multimedia.moderate', 'resource' => 'multimedia', 'action' => 'moderate'],
            ['name' => 'Gestion avancée des médias', 'slug' => 'multimedia.manage', 'resource' => 'multimedia', 'action' => 'manage'],
        ];

        $this->createPermissions($permissions, 'Gestion du multimédia');
    }

    /**
     * Créer les permissions pour les projets
     */
    private function createProjetPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les projets', 'slug' => 'projets.read', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Créer des projets', 'slug' => 'projets.create', 'resource' => 'projets', 'action' => 'create'],
            ['name' => 'Modifier les projets', 'slug' => 'projets.update', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Supprimer les projets', 'slug' => 'projets.delete', 'resource' => 'projets', 'action' => 'delete'],
            ['name' => 'Voir statistiques des projets', 'slug' => 'projets.statistics', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Voir projets publics', 'slug' => 'projets.public', 'resource' => 'projets', 'action' => 'read'],
            ['name' => 'Approuver des projets', 'slug' => 'projets.approve', 'resource' => 'projets', 'action' => 'approve'],
            ['name' => 'Planifier des projets', 'slug' => 'projets.plan', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Rechercher financement', 'slug' => 'projets.seek-funding', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Démarrer des projets', 'slug' => 'projets.start', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Suspendre des projets', 'slug' => 'projets.suspend', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Reprendre des projets', 'slug' => 'projets.resume', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Terminer des projets', 'slug' => 'projets.complete', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Annuler des projets', 'slug' => 'projets.cancel', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Mettre à jour progression', 'slug' => 'projets.update-progress', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Mettre en attente', 'slug' => 'projets.put-on-hold', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Dupliquer des projets', 'slug' => 'projets.duplicate', 'resource' => 'projets', 'action' => 'create'],
            ['name' => 'Upload images de projets', 'slug' => 'projets.upload-images', 'resource' => 'projets', 'action' => 'update'],
            ['name' => 'Restaurer des projets', 'slug' => 'projets.restore', 'resource' => 'projets', 'action' => 'restore'],
        ];

        $this->createPermissions($permissions, 'Gestion des projets');
    }

    /**
     * Créer les permissions pour les fonds
     */
    private function createFondsPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les fonds', 'slug' => 'fonds.read', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Créer des transactions', 'slug' => 'fonds.create', 'resource' => 'fonds', 'action' => 'create'],
            ['name' => 'Modifier les fonds', 'slug' => 'fonds.update', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Supprimer les fonds', 'slug' => 'fonds.delete', 'resource' => 'fonds', 'action' => 'delete'],
            ['name' => 'Voir tableau de bord des fonds', 'slug' => 'fonds.dashboard', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir statistiques des fonds', 'slug' => 'fonds.statistics', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir analyses des fonds', 'slug' => 'fonds.analytics', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Voir rapports des fonds', 'slug' => 'fonds.reports', 'resource' => 'fonds', 'action' => 'read'],
            ['name' => 'Valider des transactions', 'slug' => 'fonds.validate', 'resource' => 'fonds', 'action' => 'validate'],
            ['name' => 'Annuler des transactions', 'slug' => 'fonds.cancel', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Rembourser des transactions', 'slug' => 'fonds.refund', 'resource' => 'fonds', 'action' => 'update'],
            ['name' => 'Générer reçu fiscal', 'slug' => 'fonds.generate-receipt', 'resource' => 'fonds', 'action' => 'create'],
            ['name' => 'Dupliquer des transactions', 'slug' => 'fonds.duplicate', 'resource' => 'fonds', 'action' => 'create'],
            ['name' => 'Restaurer des transactions', 'slug' => 'fonds.restore', 'resource' => 'fonds', 'action' => 'restore'],
            ['name' => 'Exporter données financières', 'slug' => 'fonds.export', 'resource' => 'fonds', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des fonds');
    }

    /**
     * Créer les permissions pour les FIMECO
     */
    private function createFimecoPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les FIMECO', 'slug' => 'fimecos.read', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Créer des FIMECO', 'slug' => 'fimecos.create', 'resource' => 'fimecos', 'action' => 'create'],
            ['name' => 'Modifier les FIMECO', 'slug' => 'fimecos.update', 'resource' => 'fimecos', 'action' => 'update'],
            ['name' => 'Supprimer les FIMECO', 'slug' => 'fimecos.delete', 'resource' => 'fimecos', 'action' => 'delete'],
            ['name' => 'Voir tableau de bord FIMECO', 'slug' => 'fimecos.dashboard', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Clôturer des FIMECO', 'slug' => 'fimecos.close', 'resource' => 'fimecos', 'action' => 'update'],
            ['name' => 'Voir statistiques FIMECO', 'slug' => 'fimecos.statistics', 'resource' => 'fimecos', 'action' => 'read'],
            ['name' => 'Désactiver des FIMECO', 'slug' => 'fimecos.deactivate', 'resource' => 'fimecos', 'action' => 'update'],
            ['name' => 'Réactiver des FIMECO', 'slug' => 'fimecos.reactivate', 'resource' => 'fimecos', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des FIMECO');
    }

    /**
     * Créer les permissions pour les souscriptions
     */
    private function createSubscriptionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les souscriptions', 'slug' => 'subscriptions.read', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Créer des souscriptions', 'slug' => 'subscriptions.create', 'resource' => 'subscriptions', 'action' => 'create'],
            ['name' => 'Modifier les souscriptions', 'slug' => 'subscriptions.update', 'resource' => 'subscriptions', 'action' => 'update'],
            ['name' => 'Supprimer les souscriptions', 'slug' => 'subscriptions.delete', 'resource' => 'subscriptions', 'action' => 'delete'],
            ['name' => 'Voir mes statistiques', 'slug' => 'subscriptions.my-statistics', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Voir FIMECO disponibles', 'slug' => 'subscriptions.available-fimecos', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Vérifier possibilité souscription', 'slug' => 'subscriptions.can-subscribe', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Voir utilisateurs disponibles', 'slug' => 'subscriptions.available-users', 'resource' => 'subscriptions', 'action' => 'read'],
            ['name' => 'Annuler des souscriptions', 'slug' => 'subscriptions.cancel', 'resource' => 'subscriptions', 'action' => 'update'],
            ['name' => 'Suspendre des souscriptions', 'slug' => 'subscriptions.suspend', 'resource' => 'subscriptions', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des souscriptions');
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
            ['name' => 'Voir logs par utilisateur', 'slug' => 'audit-logs.user-logs', 'resource' => 'audit-logs', 'action' => 'read'],
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
                'name' => 'Pasteur',
                'slug' => 'pasteur',
                'description' => 'Chargé de voir tout les tableaux de bord sans exception',
                'level' => 100, // Autorité spirituelle et administrative suprême
                'is_system_role' => true,
            ],
            [
                'name' => 'Président(e) du laïque',
                'slug' => 'president-laique',
                'description' => 'Chargé de voir tout les tableaux de bord sans exception',
                'level' => 95, // Deuxième niveau hiérarchique, représente les laïcs
                'is_system_role' => true,
            ],
            [
                'name' => 'Secrétaire',
                'slug' => 'secretaire',
                'description' => 'Pour créer les classes, ajouter les fidèles de la communauté, gérer les réunions et les rapports de réunion.',
                'level' => 90, // Responsabilités administratives étendues
                'is_system_role' => true,
            ],
            [
                'name' => 'Trésorier(ère)',
                'slug' => 'tresorier',
                'description' => 'Gestion financière au niveau de offrande, dime, et autres contributions financières de l\'église.',
                'level' => 85, // Responsabilité financière critique (fonds sacrés)
                'is_system_role' => true,
            ],
            [
                'name' => 'Annonceur(se)',
                'slug' => 'annonceur',
                'description' => 'Lecture des comptes rendus des réunions. Pour ajouter les fidèles de la communauté, Pour faire toutes les actions sur les annonces.',
                'level' => 80, // Communication officielle, responsabilités importantes
                'is_system_role' => true,
            ],
            [
                'name' => 'Registreur(se)',
                'slug' => 'registrateur',
                'description' => 'Gestion tous ce qui est FIMECO',
                'level' => 75, // Spécialisé FIMECO, responsabilités ciblées
                'is_system_role' => true,
            ],
            [
                'name' => 'Service d\'ordre',
                'slug' => 'service-ordre',
                'description' => 'Pour seulement ajouter les fidèles de la communauté et ajouter les participants aux cultes. (Lecture seulement)',
                'level' => 70, // Rôle de soutien, responsabilités limitées
                'is_system_role' => true,
            ],
            [
                'name' => 'Conducteur(trice)',
                'slug' => 'conducteur',
                'description' => 'Affecter les fidèles aux différentes classes',
                'level' => 65, // Spécialisé affectation, responsabilité ciblée
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
        $pasteur = Role::where('slug', 'pasteur')->first();
        if ($pasteur) {

            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Utilisateurs
                'users.read', 'users.export', 'users.search', 'users.statistics', 'users.reports',
                // Classes
                'classes.read', 'classes.statistics', 'classes.export',
                // Réunions
                'reunions.read', 'reunions.statistics', 'reunions.upcoming', 'reunions.today', 'reunions.calendar', 'reunions.public', 'reunions.live-stream',

                // Rapports de réunions
                'rapports-reunions.read', 'rapports-reunions.export', 'rapports-reunions.download-pdf', 'types-reunions.categories',
                // Types de réunions
                'types-reunions.read', 'types-reunions.statistics', 'types-reunions.export',
                // Contacts
                'contacts.read', 'contacts.statistics', 'contacts.export', 'contacts.completeness-report', 'contacts.search-nearby', 'contacts.advanced-search', 'contacts.import', 'contacts.import-template',

                //Programmes
                'programmes.read', 'programmes.planning', 'programmes.statistics', 'programmes.actifs', 'programmes.metadata',

                //Evénements
                'events.read','events.planning','events.statistics','events.dashboard','events.export-participants',

                // Annonces (lecture seule)
                'annonces.read', 'annonces.culte', 'annonces.urgentes', 'annonces.statistics', 'annonces.actives',

                // Cultes (lecture seule)
                'cultes.read', 'cultes.planning', 'cultes.statistics', 'cultes.dashboard', 'cultes.participants', 'cultes.export-participants',

                // Participants des cultes (lecture seule)
                'participants-cultes.read', 'participants-cultes.nouveaux-visiteurs', 'participants-cultes.search', 'participants-cultes.statistics',

                // Rapports généraux
                'reports.read', 'reports.generate', 'reports.export',
                //Paiements
                'paiements.read',
                //FIMECO
                'fimecos.read', 'fimecos.statistics', 'fimecos.dashboard',
                //Souscriptions
                'subscriptions.read', 'subscriptions.my-statistics',
                //Fonds
                'fonds.read', 'fonds.statistics', 'fonds.analytics', 'fonds.reports', 'fonds.export', 'fonds.dashboard', 'fonds.generate-receipt',
                // Projets
                'projets.read', 'projets.statistics', 'projets.public', 'projets.seek-funding',

                // Multimédia
                'multimedia.read', 'multimedia.gallery', 'multimedia.statistics', 'multimedia.download',
                // Interventions
                'interventions.read', 'interventions.by-event',



            ])->pluck('id')->toArray();
            $pasteur->syncPermissions($permissions);
        }

        // Président du laïque - Presque toutes les permissions (dashboard, lectures, rapports)
        $presidentLaique = Role::where('slug', 'president-laique')->first();
        if ($presidentLaique) {
            // $permissions = Permission::where(function($query) {
            //     $query->where('action', 'read')
            //           ->orWhere('slug', 'like', '%.dashboard')
            //           ->orWhere('slug', 'like', '%.statistics')
            //           ->orWhere('slug', 'like', '%.reports');
            // })->pluck('id')->toArray();
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Utilisateurs
                'users.read', 'users.export', 'users.search', 'users.statistics', 'users.reports',
                // Classes
                'classes.read', 'classes.statistics', 'classes.export',
                // Réunions
                'reunions.read', 'reunions.statistics', 'reunions.upcoming', 'reunions.today', 'reunions.calendar', 'reunions.public', 'reunions.live-stream',

                // Rapports de réunions
                'rapports-reunions.read', 'rapports-reunions.export', 'rapports-reunions.download-pdf', 'types-reunions.categories',
                // Types de réunions
                'types-reunions.read', 'types-reunions.statistics', 'types-reunions.export',
                // Contacts
                'contacts.read', 'contacts.statistics', 'contacts.export', 'contacts.completeness-report', 'contacts.search-nearby', 'contacts.advanced-search', 'contacts.import', 'contacts.import-template',

                //Programmes
                'programmes.read', 'programmes.planning', 'programmes.statistics', 'programmes.actifs', 'programmes.metadata',

                //Evénements
                'events.read','events.planning','events.statistics','events.dashboard','events.export-participants',

                // Annonces (lecture seule)
                'annonces.read', 'annonces.culte', 'annonces.urgentes', 'annonces.statistics', 'annonces.actives',

                // Cultes (lecture seule)
                'cultes.read', 'cultes.planning', 'cultes.statistics', 'cultes.dashboard', 'cultes.participants', 'cultes.export-participants',

                // Participants des cultes (lecture seule)
                'participants-cultes.read', 'participants-cultes.nouveaux-visiteurs', 'participants-cultes.search', 'participants-cultes.statistics',

                // Rapports généraux
                'reports.read', 'reports.generate', 'reports.export',
                //Paiements
                'paiements.read',
                //FIMECO
                'fimecos.read', 'fimecos.statistics', 'fimecos.dashboard',
                //Souscriptions
                'subscriptions.read', 'subscriptions.my-statistics',
                //Fonds
                'fonds.read', 'fonds.statistics', 'fonds.analytics', 'fonds.reports', 'fonds.export', 'fonds.dashboard', 'fonds.generate-receipt',
                // Projets
                'projets.read', 'projets.statistics', 'projets.public', 'projets.seek-funding',

                // Multimédia
                'multimedia.read', 'multimedia.gallery', 'multimedia.statistics', 'multimedia.download',
                // Interventions
                'interventions.read', 'interventions.by-event',



            ])->pluck('id')->toArray();
            $presidentLaique->syncPermissions($permissions);
        }

        // Secrétaire
        $secretaire = Role::where('slug', 'secretaire')->first();
        if ($secretaire) {

            $permissions = Permission::all()->pluck('id')->toArray();
            $secretaire->syncPermissions($permissions);
        }

        // Annonceur
        $annonceur = Role::where('slug', 'annonceur')->first();
        if ($annonceur) {
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Utilisateurs (ajouter fidèles)
                'users.read', 'users.create', 'users.update',
                // Annonces (toutes les actions)
                'annonces.read', 'annonces.create', 'annonces.update', 'annonces.delete',
                'annonces.publish', 'annonces.archive', 'annonces.duplicate',
                'annonces.culte', 'annonces.urgentes', 'annonces.statistics', 'annonces.actives',
                // Rapports de réunions (lecture)
                'rapports-reunions.read',
                // Contacts
                'contacts.read',
            ])->pluck('id')->toArray();
            $annonceur->syncPermissions($permissions);
        }

        // Service d'ordre
        $serviceOrdre = Role::where('slug', 'service-ordre')->first();
        if ($serviceOrdre) {
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Utilisateurs (ajouter fidèles seulement)
                'users.read', 'users.create',
                // Participants aux cultes
                'participants-cultes.read', 'participants-cultes.create',
                'participants-cultes.search', 'participants-cultes.confirm-presence',
                'participants-cultes.nouveaux-visiteurs',
                // Cultes (lecture + participants)
                'cultes.read', 'cultes.participants',
                // Contacts (lecture)
                'contacts.read',
            ])->pluck('id')->toArray();
            $serviceOrdre->syncPermissions($permissions);
        }

        // Registrateur (FIMECO)
        $registrateur = Role::where('slug', 'registrateur')->first();
        if ($registrateur) {
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // FIMECO (toutes les actions)
                'fimecos.read', 'fimecos.create', 'fimecos.update', 'fimecos.delete',
                'fimecos.dashboard', 'fimecos.close', 'fimecos.statistics',
                'fimecos.deactivate', 'fimecos.reactivate',
                // Souscriptions
                'subscriptions.read', 'subscriptions.create', 'subscriptions.update',
                'subscriptions.cancel', 'subscriptions.suspend',
                'subscriptions.available-fimecos', 'subscriptions.available-users',
                // Utilisateurs (lecture)
                'users.read',
                // Rapports
                'reports.read', 'reports.generate',
            ])->pluck('id')->toArray();
            $registrateur->syncPermissions($permissions);
        }

        // Trésorier
        $tresorier = Role::where('slug', 'tresorier')->first();
        if ($tresorier) {
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Fonds (gestion financière)
                'fonds.read', 'fonds.create', 'fonds.update', 'fonds.dashboard',
                'fonds.statistics', 'fonds.analytics', 'fonds.reports',
                'fonds.validate', 'fonds.generate-receipt', 'fonds.export',
                // Paiements
                'paiements.read', 'paiements.create', 'paiements.update',
                'paiements.validate', 'paiements.reject', 'paiements.pending',
                'paiements.batch-process', 'paiements.types',
                // FIMECO (lecture et paiements)
                'fimecos.read', 'fimecos.statistics',
                // Souscriptions (lecture)
                'subscriptions.read', 'subscriptions.my-statistics',
                // Rapports financiers
                'reports.read', 'reports.generate', 'reports.export',
                // Utilisateurs (lecture)
                'users.read',
            ])->pluck('id')->toArray();
            $tresorier->syncPermissions($permissions);
        }

        // Conducteur
        $conducteur = Role::where('slug', 'conducteur')->first();
        if ($conducteur) {
            $permissions = Permission::whereIn('slug', [
                'dashboard.access',
                // Classes (affecter fidèles)
                'classes.read', 'classes.manage-members', 'classes.inscrire',
                'classes.desinscrire', 'classes.statistics',
                // Utilisateurs
                'users.read', 'users.search',
                // Programmes
                'programmes.read', 'programmes.statistics',
                // Cultes (lecture)
                'cultes.read', 'cultes.statistics',
            ])->pluck('id')->toArray();
            $conducteur->syncPermissions($permissions);
        }
    }
}
