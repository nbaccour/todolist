# Projet : Améliorez une application existante de ToDo & Co


# Avant propos
- Le projet fonctionne sur PHP 7.4.13
- Le projet est basé sur le framework symfony 5.2.9 (Doctrine, Twig et PhpUnit)
- Lien fichier Git : `git clone https://github.com/nbaccour/todolist.git`

# Comment Contribuer au projet

1. Cloner et Installer le repository sur votre serveur (voir le README.md)
2. Créez une branche à partir de *master* avec la fonction sur laquelle vous intervenez
3. Ecrivez un Issue sur les modifications que vous allez apporter
4. Ecrivez votre code EN RESPECTANT LES BONNES PRATIQUES
5. Ecrivez des Commit Clairs et precis avant de faire un Push de la branche
5. Mettez a jour vos issues
5. Faites un *Pull Request* et attendez sa validation

# Les bonnes pratiques 

   #  1. le code
    Vous devez respecter :
    - Le PSR 2 au minimum
    - Les standards de code de Symfony (`https://symfony.com/doc/current/contributing/code/standards.html`)
    - Les conventions de code de Symfony (`https://symfony.com/doc/4.4/contributing/code/conventions.html`)

   # 2. les bundles
    - Toute installation de bundle PHP doit se faire avec **Composer OBLIGATOIREMENT**

   # 3. Git
    Vous devez faire les choses dans cet ordre : 
    - **Nouvelle branche a partir de master** duement nomée
    - Commit Correctement commentés
    - Issue Correctement commentées et documentées
    - **pull Request OBLIGATOIRE**
    - **Seul le createur du projet peu faire un merge** sur le master après revision de votre code

   # 4) Tests unitaires et fonctionels
    - PhpUnit est à votre disposition pour créer vos tests
    - Toute nouvelle fonctionalité doit avoir des tests associés
    - Merci de respecter un taux de couverture au delà de 70%

   # 5) Diagramme UML
    - Réalisez des diagrammes UML (UseCase, Class, Sequence) pour les  nouvelles fonctionalités

   # 6) Architecture de fichier
    - Respectez l'architecture de symfony 5 pour vos fichiers PHP ( src\Controller\... )
    - Les vues devront etre dans le repertoire templates



