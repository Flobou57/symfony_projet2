# Backoffice Boutique (Symfony 7.4)

Projet de backoffice pour la gestion des utilisateurs, produits et clients.

## Prerequis

- PHP >= 8.4
- Composer
- MySQL 8.x (ex: MAMP sur le port 3306)

## Installation

1. Installer les dependances :
   - `composer install`
2. Creer le fichier `.env.local` et definir la base :
   - Exemple : `DATABASE_URL="mysql://root:root@127.0.0.1:3306/boutique?serverVersion=8.0&charset=utf8mb4"`
3. Creer la base :
   - `php bin/console doctrine:database:create`
4. Lancer les migrations :
   - `php bin/console doctrine:migrations:migrate`
5. (Optionnel) Charger les fixtures :
   - `php bin/console doctrine:fixtures:load`

## Comptes fixtures

- admin@example.com (ROLE_ADMIN)
- manager@example.com (ROLE_MANAGER)
- user@example.com (ROLE_USER)
- Mot de passe : `password`

## Fonctionnalites implementees

- Authentification (login/logout)
- Utilisateurs (admin uniquement) : liste, ajout, modification, suppression
- Produits : liste triee par prix decroissant, ajout/modif multi-etapes, export CSV, import CSV
- Clients (admin/manager) : liste, ajout, modification, validations, fixtures

## Commandes utiles

- Importer des produits depuis `public/products.csv` :
  - `php bin/console app:products:import products.csv`
- Ajouter un client en CLI :
  - `php bin/console app:client:add`

## Tests

- Lancer les tests :
  - `php bin/phpunit`

## Livrables finaux

- Deposer le code source sur GitHub (le fichier `.gitignore` exclut `vendor` et fichiers locaux).
- Ajouter une video de demonstration (optionnel).
