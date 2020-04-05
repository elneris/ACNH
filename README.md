# ACNH : Trade Navets

Outils pour faciliter les échanges entre joueurs lors du cours du navets

Développer par Elnéris

- Php 7.3 / Symfony 4.4

## Prérequis

- Docker & docker compose si sur linux et que vous ne voulez pas installer mysql en local
- Git ( git bash pour windows )
- Composer
- PHP 7.3 minimum
- Yarn ou npm

## Init

- Cloner le projet
- docker-compose up -d ( si linux ou pas de sql en local )
- composer install
- copier/coller le .env en .env.local et remplacer l'adresse de la base de donnée par votre base en local
- pour les utilisateur de docker : DATABASE_URL=pgsql://root:root@127.0.0.1:5432/acnh?serverVersion=12

BDD sans docker
- php bin/console d:d:c

En developpement je ne fais pas de script de migration régulier donc :

- php bin/console d:s:u --force ( --dump-sql si besoin avant )

*un dump de la base de donnée de dev sera mis à dispo plus tard en attendant, pour accéder au back office il faut se créer un compte utilisateur.

- php bin/console security:encode-password 
- copier le hash dans le champs password et dans role ajouter ["ROLE_ADMIN"]

Dernière étape lancer les assets

- npm install
- npm run start

Pour finir

- php bin/console server:run pour lancer le serveur

## Architecture

- Env de prod : https://ac-market.fr/
- Env de dev : https://dev.ac-market.fr/