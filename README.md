# POC dans le cadre du cours de PIDV - ESA 2025-2025
> Projet de fin d'études — Application web développée avec **Laravel 12**, **Livewire 4** et **Bootstrap 5**.

---

## Table des matières

1. [Présentation du projet](#présentation-du-projet)
2. [Fonctionnalités principales](#fonctionnalités-principales)
3. [Architecture technique](#architecture-technique)
4. [Prérequis](#prérequis)
5. [Installation et déploiement](#installation-et-déploiement)
6. [Configuration de l'environnement](#configuration-de-lenvironnement)
7. [Initialisation de la base de données](#initialisation-de-la-base-de-données)
8. [Comptes utilisateurs par défaut](#comptes-utilisateurs-par-défaut)
9. [Structure des rôles et permissions](#structure-des-rôles-et-permissions)

---

## Présentation du projet

Cette plateforme centralise la **gestion des plaintes et recours** au sein du réseau IFAPME (Institut wallon de Formation en Alternance et des indépendants et des PME). Elle permet aux différents services de l'organisation de réceptionner, instruire et clôturer les dossiers de plaintes tout en assurant une traçabilité complète des actions réalisées.

L'application est accessible via une interface sécurisée pour les agents internes.

---

## Fonctionnalités principales

### Gestion des plaintes et recours

- **Encodage de dossiers** : création manuelle de plaintes par le secrétariat avec gestion du plaignant (création ou association à un existant), du client Smarter, de la profession concernée et de la catégorie d'objet.
- **Workflow complet** : chaque dossier suit un cycle de vie structuré — *Nouvelle → Attribuée → Réceptionnée → Évaluée → Répondue → Clôturée* — avec possibilité de rejet ou d'annulation à certaines étapes.

### Gestion documentaire

- **Génération de documents PDF** : accusé de réception,


### Gestion des accès

- **Authentification sécurisée** via Laravel Fortify (avec authentification à deux facteurs optionnelle).
- **Système de rôles et permissions** entièrement personnalisé (sans package tiers).
- **Filtrage automatique par département** : chaque utilisateur ne voit que les dossiers relevant de son périmètre, grâce à un *Global Scope* Eloquent.
- **Interface d'administration** : console dédiée pour gérer les utilisateurs et leurs rôles.

## Architecture technique

| Composant | Technologie |
|---|---|
| Framework backend | Laravel 12 (PHP 8.2+) |
| Interface dynamique | Livewire 4 |
| Frontend | Bootstrap 5.3 + Bootstrap Icons |
| Génération PDF | barryvdh/laravel-dompdf |
| Import Excel | spatie/simple-excel |
| Authentification | Laravel Fortify |
| Base de données | MySQL (production) / SQLite (tests) |

---

## Prérequis

- **PHP** >= 8.2 avec les extensions : `ext-ldap`, `ext-dom`, `ext-mbstring`, `ext-zip`, `ext-gd`, `ext-json`, `ext-pdo`
- **Composer** >= 2.x
- **Node.js** >= 18.x et **npm**
- **MySQL** >= 8.0 (ou MariaDB >= 10.6)
- Un serveur web : Apache (avec `mod_rewrite`) ou Nginx

---

## Installation et déploiement

### 1. Cloner le dépôt

```bash
git clone https://github.com/dmosfet/pidv2026 pidv
cd pidv
```

### 2. Installer les dépendances PHP

```bash
composer install --optimize-autoloader
```

### 3. Installer les dépendances JavaScript et compiler les assets

```bash
npm install
npm run build
```

> En développement, utilisez `npm run dev` pour le rechargement à chaud.

### 4. Copier et configurer le fichier d'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Ouvrez ensuite `.env` et renseignez les paramètres décrits dans la section suivante.

### 5. Initialiser la base de données

```bash
php artisan migrate --force
php artisan db:seed
```

### 6. Créer le lien symbolique pour le stockage public

```bash
php artisan storage:link
```

### 7. Optimiser pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Configuration de l'environnement

Voici les variables clés à renseigner dans le fichier `.env` :

```dotenv
# Général
APP_NAME="POC PIDV"
APP_ENV=production          # production | demo | develop
APP_DEBUG=false
APP_URL=https://votre-domaine.be # utilisé dans la configuration de votre serveur Web

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pidv # production | demo | develop
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

# Sessions et cache (base de données recommandé)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Messagerie
MAIL_MAILER=smtp
MAIL_HOST=votre-serveur-smtp
MAIL_PORT=587
MAIL_USERNAME=votre-email@ifapme.be
MAIL_PASSWORD=votre-mot-de-passe
MAIL_FROM_ADDRESS=""
MAIL_FROM_NAME=""

# Import des professions (chemin relatif à storage/app)
IMPORT_PROFESSIONS_PATH=default/professions.xlsx
```

---

## Initialisation de la base de données

Le seeder principal (`DatabaseSeeder`) initialise automatiquement :

- Les **types de référence** (statuts, canaux, types de plainte, types de département, etc.)
- Les **rôles et permissions**
- La liste des **79 départements** IFAPME (depuis `database/data/departments.csv`)
- Les **métiers / professions** (depuis le fichier Excel configuré via `IMPORT_PROFESSIONS_PATH`)

En environnement `demo` ou `develop`, le seeder crée également :

- Des utilisateurs de test pour chaque département (managers et secrétariats)
- Un utilisateur administrateur (à modifier selon vos préférences)

---

## Comptes utilisateurs par défaut

Ces comptes sont créés uniquement en environnement `demo` ou `develop`.

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | `admin@ifapme.be` | `Supermot2passe!` |
| Manager (ex. dept. DIR) | `dir.manager@ifapme.be` | `manager` |
| Secrétariat (ex. dept. DIR) | `dir.sec@ifapme.be` | `secretariat` |

> Le pattern des comptes de test est `{code_dept}.manager@ifapme.be` et `{code_dept}.sec@ifapme.be`. Les codes de département sont visibles dans `database/data/departments.csv`.

---

## Structure des rôles et permissions

| Rôle | Description |
|---|---|
| `admin` | Accès complet à toutes les fonctionnalités |
| `secretariat` | Encodage, assignation et accusé de réception des plaintes |
| `manager` | Évaluation, réponse et clôture des plaintes |

Le filtrage des données par département est assuré de manière transparente par le `DepartmentFilterScope`. Chaque utilisateur ne voit que les dossiers relevant de son service, sans configuration supplémentaire.
