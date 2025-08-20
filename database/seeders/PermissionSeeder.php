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
            $this->createClassePermissions();
            $this->createCultePermissions();
            $this->createTransactionPermissions();
            $this->createAnnoncePermissions();
            $this->createProgrammePermissions();
            $this->createReunionPermissions();
            $this->createContactPermissions();
            $this->createInterventionPermissions();
            $this->createRapportPermissions();
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
            ['name' => 'Gérer les utilisateurs', 'slug' => 'users.manage', 'resource' => 'users', 'action' => 'manage'],
            ['name' => 'Valider les utilisateurs', 'slug' => 'users.validate', 'resource' => 'users', 'action' => 'validate'],
            ['name' => 'Archiver les utilisateurs', 'slug' => 'users.archive', 'resource' => 'users', 'action' => 'archive'],
            ['name' => 'Restaurer les utilisateurs', 'slug' => 'users.restore', 'resource' => 'users', 'action' => 'restore'],
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
            ['name' => 'Gérer les rôles', 'slug' => 'roles.manage', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Attribuer des rôles', 'slug' => 'roles.assign', 'resource' => 'roles', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des rôles');
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
            ['name' => 'Gérer les classes', 'slug' => 'classes.manage', 'resource' => 'classes', 'action' => 'manage'],
            ['name' => 'Attribuer des élèves', 'slug' => 'classes.assign_students', 'resource' => 'classes', 'action' => 'update'],
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
            ['name' => 'Gérer les cultes', 'slug' => 'cultes.manage', 'resource' => 'cultes', 'action' => 'manage'],
            ['name' => 'Valider les cultes', 'slug' => 'cultes.validate', 'resource' => 'cultes', 'action' => 'validate'],
        ];

        $this->createPermissions($permissions, 'Gestion des cultes');
    }

    /**
     * Créer les permissions pour les transactions
     */
    private function createTransactionPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les transactions', 'slug' => 'transactions.read', 'resource' => 'transactions', 'action' => 'read'],
            ['name' => 'Créer des transactions', 'slug' => 'transactions.create', 'resource' => 'transactions', 'action' => 'create'],
            ['name' => 'Modifier les transactions', 'slug' => 'transactions.update', 'resource' => 'transactions', 'action' => 'update'],
            ['name' => 'Supprimer les transactions', 'slug' => 'transactions.delete', 'resource' => 'transactions', 'action' => 'delete'],
            ['name' => 'Valider les transactions', 'slug' => 'transactions.validate', 'resource' => 'transactions', 'action' => 'validate'],
            ['name' => 'Approuver les transactions', 'slug' => 'transactions.approve', 'resource' => 'transactions', 'action' => 'approve'],
            ['name' => 'Rejeter les transactions', 'slug' => 'transactions.reject', 'resource' => 'transactions', 'action' => 'reject'],
            ['name' => 'Exporter les transactions', 'slug' => 'transactions.export', 'resource' => 'transactions', 'action' => 'export'],
        ];

        $this->createPermissions($permissions, 'Gestion des transactions');
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
            ['name' => 'Publier les annonces', 'slug' => 'annonces.publish', 'resource' => 'annonces', 'action' => 'update'],
            ['name' => 'Approuver les annonces', 'slug' => 'annonces.approve', 'resource' => 'annonces', 'action' => 'approve'],
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
            ['name' => 'Gérer les programmes', 'slug' => 'programmes.manage', 'resource' => 'programmes', 'action' => 'manage'],
            ['name' => 'Valider les programmes', 'slug' => 'programmes.validate', 'resource' => 'programmes', 'action' => 'validate'],
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
            ['name' => 'Gérer les réunions', 'slug' => 'reunions.manage', 'resource' => 'reunions', 'action' => 'manage'],
            ['name' => 'Convoquer aux réunions', 'slug' => 'reunions.convoke', 'resource' => 'reunions', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des réunions');
    }

    /**
     * Créer les permissions pour les contacts
     */
    private function createContactPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les contacts', 'slug' => 'contacts.read', 'resource' => 'contacts', 'action' => 'read'],
            ['name' => 'Modifier les contacts', 'slug' => 'contacts.update', 'resource' => 'contacts', 'action' => 'update'],
            ['name' => 'Gérer les contacts', 'slug' => 'contacts.manage', 'resource' => 'contacts', 'action' => 'manage'],
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
            ['name' => 'Assigner des interventions', 'slug' => 'interventions.assign', 'resource' => 'interventions', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des interventions');
    }

    /**
     * Créer les permissions pour les rapports
     */
    private function createRapportPermissions(): void
    {
        $permissions = [
            ['name' => 'Voir les rapports de réunion', 'slug' => 'rapport_reunions.read', 'resource' => 'rapport_reunions', 'action' => 'read'],
            ['name' => 'Créer des rapports de réunion', 'slug' => 'rapport_reunions.create', 'resource' => 'rapport_reunions', 'action' => 'create'],
            ['name' => 'Modifier les rapports de réunion', 'slug' => 'rapport_reunions.update', 'resource' => 'rapport_reunions', 'action' => 'update'],
            ['name' => 'Valider les rapports de réunion', 'slug' => 'rapport_reunions.validate', 'resource' => 'rapport_reunions', 'action' => 'validate'],
            ['name' => 'Publier les rapports de réunion', 'slug' => 'rapport_reunions.publish', 'resource' => 'rapport_reunions', 'action' => 'update'],
        ];

        $this->createPermissions($permissions, 'Gestion des rapports');
    }

    /**
     * Créer les permissions système
     */
    private function createSystemPermissions(): void
    {
        $permissions = [
            ['name' => 'Accès au tableau de bord', 'slug' => 'dashboard.access', 'resource' => 'dashboard', 'action' => 'read'],
            ['name' => 'Voir les rapports', 'slug' => 'reports.read', 'resource' => 'reports', 'action' => 'read'],
            ['name' => 'Générer des rapports', 'slug' => 'reports.generate', 'resource' => 'reports', 'action' => 'create'],
            ['name' => 'Exporter les rapports', 'slug' => 'reports.export', 'resource' => 'reports', 'action' => 'export'],
            ['name' => 'Voir les logs système', 'slug' => 'logs.read', 'resource' => 'logs', 'action' => 'read'],
            ['name' => 'Gérer les paramètres', 'slug' => 'settings.manage', 'resource' => 'settings', 'action' => 'manage'],
            ['name' => 'Sauvegarder le système', 'slug' => 'backup.create', 'resource' => 'backup', 'action' => 'create'],
            ['name' => 'Restaurer le système', 'slug' => 'backup.restore', 'resource' => 'backup', 'action' => 'restore'],
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
                'description' => 'Accès complet au système',
                'level' => 100,
                'is_system_role' => true,
            ],
            [
                'name' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Administration générale du système',
                'level' => 90,
                'is_system_role' => true,
            ],
            [
                'name' => 'Pasteur',
                'slug' => 'pasteur',
                'description' => 'Responsable pastoral',
                'level' => 80,
                'is_system_role' => true,
            ],
            [
                'name' => 'Secrétaire',
                'slug' => 'secretaire',
                'description' => 'Gestion administrative',
                'level' => 70,
                'is_system_role' => true,
            ],
            [
                'name' => 'Trésorier',
                'slug' => 'tresorier',
                'description' => 'Gestion financière',
                'level' => 70,
                'is_system_role' => true,
            ],
            [
                'name' => 'Responsable de classe',
                'slug' => 'responsable-classe',
                'description' => 'Responsable d\'une classe d\'école du dimanche',
                'level' => 60,
                'is_system_role' => false,
            ],
            [
                'name' => 'Enseignant',
                'slug' => 'enseignant',
                'description' => 'Enseignant école du dimanche',
                'level' => 50,
                'is_system_role' => false,
            ],
            [
                'name' => 'Responsable de département',
                'slug' => 'responsable-departement',
                'description' => 'Responsable d\'un département',
                'level' => 50,
                'is_system_role' => false,
            ],
            [
                'name' => 'Membre actif',
                'slug' => 'membre-actif',
                'description' => 'Membre actif de l\'église',
                'level' => 20,
                'is_system_role' => false,
            ],
            [
                'name' => 'Membre',
                'slug' => 'membre',
                'description' => 'Membre de l\'église',
                'level' => 10,
                'is_system_role' => false,
            ],
            [
                'name' => 'Visiteur',
                'slug' => 'visiteur',
                'description' => 'Visiteur de l\'église',
                'level' => 5,
                'is_system_role' => false,
            ],
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
        // Super Admin - Toutes les permissions
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::pluck('id')->toArray();
            $superAdmin->syncPermissions($allPermissions);
        }

        // Admin - Presque toutes les permissions sauf système critique
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::where('is_system', false)
                ->orWhere('slug', 'dashboard.access')
                ->orWhere('slug', 'reports.read')
                ->orWhere('slug', 'reports.generate')
                ->orWhere('slug', 'settings.manage')
                ->pluck('id')->toArray();
            $admin->syncPermissions($adminPermissions);
        }

        // Pasteur
        $pasteur = Role::where('slug', 'pasteur')->first();
        if ($pasteur) {
            $pasteurPermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'users.read', 'users.create', 'users.update', 'users.validate',
                'cultes.read', 'cultes.create', 'cultes.update', 'cultes.manage',
                'classes.read', 'classes.manage',
                'annonces.read', 'annonces.create', 'annonces.update', 'annonces.approve',
                'programmes.read', 'programmes.create', 'programmes.update', 'programmes.manage',
                'reunions.read', 'reunions.create', 'reunions.update', 'reunions.manage',
                'interventions.read', 'interventions.create', 'interventions.assign',
                'rapport_reunions.read', 'rapport_reunions.validate',
                'reports.read', 'reports.generate',
                'contacts.read', 'contacts.update',
            ])->pluck('id')->toArray();
            // $pasteur->syncPermissions($pasteurPermissions);
        }

        // Secrétaire
        $secretaire = Role::where('slug', 'secretaire')->first();
        if ($secretaire) {
            $secretairePermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'users.read', 'users.create', 'users.update', 'users.export',
                'annonces.read', 'annonces.create', 'annonces.update',
                'reunions.read', 'reunions.create', 'reunions.update', 'reunions.convoke',
                'rapport_reunions.read', 'rapport_reunions.create', 'rapport_reunions.update',
                'reports.read', 'reports.generate', 'reports.export',
                'contacts.read', 'contacts.update',
            ])->pluck('id')->toArray();
            // $secretaire->syncPermissions($secretairePermissions);
        }

        // Trésorier
        $tresorier = Role::where('slug', 'tresorier')->first();
        if ($tresorier) {
            $tresorierPermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'transactions.read', 'transactions.create', 'transactions.update',
                'transactions.validate', 'transactions.export',
                'reports.read', 'reports.generate', 'reports.export',
            ])->pluck('id')->toArray();
            // $tresorier->syncPermissions($tresorierPermissions);
        }

        // Responsable de classe
        $responsableClasse = Role::where('slug', 'responsable-classe')->first();
        if ($responsableClasse) {
            $responsableClassePermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'classes.read', 'classes.update', 'classes.assign_students',
                'users.read',
                'programmes.read',
            ])->pluck('id')->toArray();
            // $responsableClasse->syncPermissions($responsableClassePermissions);
        }

        // Enseignant
        $enseignant = Role::where('slug', 'enseignant')->first();
        if ($enseignant) {
            $enseignantPermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'classes.read',
                'users.read',
                'programmes.read',
            ])->pluck('id')->toArray();
            // $enseignant->syncPermissions($enseignantPermissions);
        }

        // Membre actif
        $membreActif = Role::where('slug', 'membre-actif')->first();
        if ($membreActif) {
            $membreActifPermissions = Permission::whereIn('slug', [
                'dashboard.access',
                'annonces.read',
                'programmes.read',
                'cultes.read',
                'contacts.read',
            ])->pluck('id')->toArray();
            // $membreActif->syncPermissions($membreActifPermissions);
        }

        // Membre
        $membre = Role::where('slug', 'membre')->first();
        if ($membre) {
            $membrePermissions = Permission::whereIn('slug', [
                'annonces.read',
                'programmes.read',
                'contacts.read',
            ])->pluck('id')->toArray();
            // $membre->syncPermissions($membrePermissions);
        }

        // Visiteur - Permissions minimales
        $visiteur = Role::where('slug', 'visiteur')->first();
        if ($visiteur) {
            $visiteurPermissions = Permission::whereIn('slug', [
                'annonces.read',
                'contacts.read',
            ])->pluck('id')->toArray();
            // $visiteur->syncPermissions($visiteurPermissions);
        }
    }
}
