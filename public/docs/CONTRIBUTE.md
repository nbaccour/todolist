# Projet : Améliorez une application existante de ToDo & Co

## Guide à l'usage de l'équipe qui reprendra ce projet pour que le travail à plusieurs se passe de la meilleure façon possible.

# Avant propos
- Le projet fonctionne sur PHP 7.4.13
- Le projet est basé sur le framework symfony 5.2.9 (Doctrine, Twig et PhpUnit)
- Clone projet : `git clone https://github.com/nbaccour/todolist.git`

# Comment Contribuer au projet

1. Cloner et Installer le repository sur votre serveur (voir le README.md)
2. Créez une branche à partir de *master* : git checkout -b nom de la branche
3. Ecrivez un Issue sur les modifications que vous allez apporter
4. Ecrivez votre code EN RESPECTANT LES BONNES PRATIQUES
5. Ecrivez des Commit Clairs et precis avant de faire un Push de la branche : git push origin maBranche
5. Mettez a jour vos issues
5. Faites un *Pull Request* et attendez sa validation

# Les bonnes pratiques 

   #  1. le code
    Vous devez respecter :
    - Le PSR 2 au minimum
    - Les standards du code de Symfony (`https://symfony.com/doc/current/contributing/code/standards.html`)
    - Les conventions du code de Symfony (`https://symfony.com/doc/5.2/contributing/code/conventions.html`)

   # 2. les bundles
    - Toute installation de bundle PHP doit se faire avec "Composer OBLIGATOIREMENT"

   # 3. Git
    Vous devez faire les choses dans cet ordre : 
    - Nouvelle branche à partir de master duement nomée
    - Commit Correctement commentés
    - Issue Correctement commentées et documentées
    - pull Request OBLIGATOIRE
    - Seul le chef de projet peu faire un "merge" sur "master" après révision de votre code.
    - Faire un update sur le code principal : git pull origin master

   # 4) Tests unitaires et fonctionnels
    - PhpUnit est à votre disposition pour créer vos tests
    - Toute nouvelle fonctionnalité doit avoir des tests associés
    - Vous devez respecter un taux de couverture au delà de 70%

   # 5) Diagramme UML
    - Réalisez des diagrammes UML (UseCase, Class, Sequence) pour les nouvelles fonctionnalités

   # 6) Architecture de fichier
    - Respectez l'architecture de symfony 5 pour vos fichiers PHP ( src\Controller\... )
    - Les vues devront être dans le repertoire templates.



