# Projet : Améliorez une application existante de ToDo & Co


# Technologie utilisée

1. Symfony 5.2.9
2. PHP 7.4.13
3. PHP unit
4. blackfire
5. 

# Installation

1. Clonez le dépot où vous voulez : `git clone https://github.com/nbaccour/todolist.git`
2. Modifier le fichier .env : `connexion à la base de données`
3. Créez la base de données : `php bin/console doctrine:database:create`
4. Installez les dépendances : `composer install`
5. Jouez les migrations : `php bin/console d:m:m`
6. Jouez les fixtures : `php bin/console d:f:l --no-interaction`
7. Lancez le server : `symfony serve` ou `php -S localhost:8000 -t public`

# Tests unitaires

Des tests unitaires et fonctionnels sont présents dans le projet dans le répertoire /tests 

`php bin/phpunit`

# Qualité du code
`

# Lien Documentation en locale


