# Modèles Laravel - Système de Gestion d'Église

## Vue d'ensemble

Ce document présente tous les modèles Laravel générés pour le système de gestion d'église, avec leurs relations principales et fonctionnalités.

## Liste des Modèles

### 1. **User.php** - Modèle principal des membres
- **Table**: `users`
- **Relations principales**:
  - `belongsTo`: Classe
  - `belongsToMany`: Role, Permission
  - `hasMany`: Classes (responsable/enseignant), Cultes, Réunions, Transactions, etc.
- **Fonctionnalités**: Authentification, gestion des rôles et permissions

### 2. **Classe.php** - Classes de l'école du dimanche
- **Table**: `classes`
- **Relations principales**:
  - `belongsTo`: User (responsable, enseignant)
  - `hasMany`: User (membres)
- **Fonctionnalités**: Gestion des groupes d'apprentissage

### 3. **Permission.php** - Permissions du système
- **Table**: `permissions`
- **Relations principales**:
  - `belongsToMany`: Role, User
- **Fonctionnalités**: Contrôle d'accès granulaire avec actions et ressources

### 4. **Role.php** - Rôles membres
- **Table**: `roles`
- **Relations principales**:
  - `belongsToMany`: User, Permission
- **Fonctionnalités**: Gestion hiérarchique des rôles

### 5. **Culte.php** - Gestion des cultes
- **Table**: `cultes`
- **Relations principales**:
  - `belongsTo`: User (pasteur, prédicateur, responsable, dirigeant louange)
  - `hasMany`: TransactionSpirituelle, Intervention
- **Fonctionnalités**: Planification et suivi des cultes avec statistiques

### 6. **Event.php** - Événements généraux
- **Table**: `events`
- **Fonctionnalités**: Structure de base pour futurs événements

### 7. **TransactionSpirituelle.php** - Finances de l'église
- **Table**: `transactions_spirituelles`
- **Relations principales**:
  - `belongsTo`: Culte, User (donateur, collecteur, validateur), Projet
- **Fonctionnalités**: Gestion complète des dîmes, offrandes et dons

### 8. **TypeReunion.php** - Types de réunions configurables
- **Table**: `type_reunions`
- **Relations principales**:
  - `belongsTo`: User (responsable)
  - `hasMany`: Reunion
- **Fonctionnalités**: Templates configurables pour différents types de réunions

### 9. **Reunion.php** - Instances de réunions
- **Table**: `reunions`
- **Relations principales**:
  - `belongsTo`: TypeReunion, User (organisateur, animateur, etc.)
  - `hasMany`: Intervention, RapportReunion
- **Fonctionnalités**: Gestion complète du cycle de vie des réunions

### 10. **RapportReunion.php** - Rapports de réunions
- **Table**: `rapport_reunions`
- **Relations principales**:
  - `belongsTo`: Reunion, User (rédacteur, validateur, secrétaire)
- **Fonctionnalités**: Workflow de rédaction et validation des rapports

### 11. **Annonce.php** - Système d'annonces
- **Table**: `annonces`
- **Relations principales**:
  - `belongsTo`: User (contact, approbateur, créateur)
- **Fonctionnalités**: Gestion multi-canal des annonces avec ciblage et statistiques

### 12. **Intervention.php** - Interventions lors des cultes/réunions
- **Table**: `interventions`
- **Relations principales**:
  - `belongsTo`: Culte, Reunion, User (intervenant)
- **Fonctionnalités**: Planning et évaluation des interventions

### 13. **Contact.php** - Informations de contact de l'église
- **Table**: `contacts`
- **Relations principales**:
  - `belongsTo`: User (responsable contact)
- **Fonctionnalités**: Coordonnées complètes avec réseaux sociaux et géolocalisation

### 14. **Programme.php** - Programmes et activités
- **Table**: `programmes`
- **Relations principales**:
  - `belongsTo`: User (responsable, coordinateur)
- **Fonctionnalités**: Planification et suivi des programmes récurrents

### 15. **Projet.php** - Projets de l'église
- **Table**: `projets` (à créer)
- **Relations principales**:
  - `belongsTo`: User (responsable)
  - `hasMany`: TransactionSpirituelle
- **Fonctionnalités**: Gestion des projets avec suivi budgétaire

## Modèles Pivot

### 16. **UserRole.php** - Liaison membres-rôles
- **Table**: `user_roles`
- **Fonctionnalités**: Gestion des attributions de rôles avec expiration

### 17. **UserPermission.php** - Permissions directes membres
- **Table**: `user_permissions`
- **Fonctionnalités**: Permissions spécifiques avec métadonnées et révocation

### 18. **RolePermission.php** - Permissions des rôles
- **Table**: `role_permissions`
- **Fonctionnalités**: Attribution de permissions aux rôles avec conditions

## Architecture des Relations

```
User (centre du système)
├── Rôles et Permissions (système de sécurité)
├── Classes (éducation)
├── Cultes (service religieux)
├── Réunions (activités)
├── Transactions (finances)
├── Annonces (communication)
├── Programmes (planification)
└── Projets (initiatives)
```

## Fonctionnalités Transversales

### Audit et Traçabilité
- Tous les modèles incluent `created_by`, `modified_by`
- SoftDeletes sur tous les modèles principaux
- Historique des modifications

### Géolocalisation
- Support latitude/longitude sur Contact, TransactionSpirituelle, etc.
- Calcul de distances

### Système de Notifications
- Support des rappels programmés
- Multi-canal (email, SMS, app)

### Statistiques et Rapports
- Méthodes de calcul intégrées
- Vues de base de données pour les statistiques

### Validation et Workflow
- États configurables (brouillon, validé, publié, etc.)
- Processus d'approbation

## Utilisation

1. **Placez chaque modèle** dans `app/Models/`
2. **Configurez les relations** selon vos besoins spécifiques
3. **Ajustez les fillable** selon votre politique de sécurité
4. **Personnalisez les scopes** pour vos requêtes fréquentes
5. **Implémentez les observers** si nécessaire pour les événements

## Bonnes Pratiques Implémentées

- ✅ UUIDs comme clés primaires
- ✅ SoftDeletes pour la traçabilité
- ✅ Casts appropriés pour les types de données
- ✅ Relations bien définies
- ✅ Scopes pour les requêtes communes
- ✅ Mutateurs et accesseurs
- ✅ Validation des données
- ✅ Gestion des erreurs
- ✅ Documentation des méthodes

## Migration Recommandée

Ordre de création des tables pour respecter les contraintes de clés étrangères :
1. `users`
2. `classes`
3. `permissions`, `roles`
4. `user_roles`, `user_permissions`, `role_permissions`
5. `projets`
6. `cultes`
7. `type_reunions`, `reunions`
8. `transactions_spirituelles`
9. `interventions`
10. `rapport_reunions`
11. `annonces`
12. `contacts`
13. `programmes`
