# Projet : Améliorez une application existante de ToDo & Co


# Technologie utilisée

1. Symfony 5.2.9
2. PHP 7.4.13
3. PHP unit
4. Blackfire
5. Code coverage

# Installation

1. Clonez le dépot où vous voulez : `git clone https://github.com/nbaccour/todolist.git`
2. Modifier le fichier .env : `connexion à la base de données`
3. Créez la base de données : `php bin/console doctrine:database:create`
4. Installez les dépendances : `composer install`
5. Jouez les migrations : `php bin/console d:m:m`
6. Jouez les fixtures : `php bin/console d:f:l --no-interaction`
7. Lancez le server : `symfony serve` ou `php -S localhost:8000 -t public`

# Tests unitaires et fonctionnels

- Répertoire : `/tests`


# Code coverage
`1. lien : `https://127.0.0.1:8000/docs/code-coverage/

# Liens Documentations

1. Authentification : `/docs/AuthentificationGuide.pdf`
2. Comment contribuer au projet : `/docs/CONTRIBUTE.md`
3. Audit de qualité de code et de performance : `/docs/audit-performance.pdf`


