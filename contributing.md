# Projet : Améliorez une application existante de ToDo & Co


# Avant propos
Le projet fonctionne sur PHP 7.4.13 et superieur
Le projet est basé sur le framework symfony 5.2.9 (Doctrine, Twig et PhpUnit)
lien fichier Git : `git clone https://github.com/nbaccour/todolist.git`

# Comment Contribuer au projet

1. Cloner et Installer le repository sur votre serveur (voir le README.md)
2. Créez une branche A PARTIR DU MASTER a votre nom avec la fonction sur laquelle vous intervenez
3. Ecrivez un Issue sur les modifications que vous allez apporter
4. Ecrivez votre code EN RESPECTANT LES BONNES PRATIQUES
5. Ecrivez des Commit Clairs et precis avant de Push votre code
5. Mettez a jour vos issues
5. Faites une PullRequest et attendez sa validation

# Les bonnes pratiques 

    1. le code
    1. Clonez le dépot où vous voulez : `git clone https://github.com/nbaccour/todolist.git`
    2. Modifier le fichier .env : `connexion à la base de données`
    3. Créez la base de données : `php bin/console doctrine:database:create`
    2. les bundles
    3. Git
    4. Tests Unitaires et fonctionnels
    5. Schemas UML
    6. Architecture de fichier

a- votre code doit respecter le PSR 2 au minimum
b- Votre code doit respecter les standards de code de Symfony ( https://symfony.com/doc/current/contributing/code/standards.html )
c- Votre code doit respecter les conventions de code de Symfony ( https://symfony.com/doc/4.4/contributing/code/conventions.html )
    2. les bundles
    
 
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


