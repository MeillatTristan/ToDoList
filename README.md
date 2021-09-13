# ToDoList

Améliorez une application existante de ToDo & Co.

[![SymfonyInsight](https://insight.symfony.com/projects/e41230ed-3cc1-40f7-9ab6-5e66fc6bef7f/mini.svg)](https://insight.symfony.com/projects/e41230ed-3cc1-40f7-9ab6-5e66fc6bef7f)

## Description

Ainsi, pour ce dernier projet de spécialisation, vous êtes dans la peau d’un développeur expérimenté en charge des tâches suivantes :
```
Corrections d'anomalies. 
Implémentation de nouvelles fonctionnalités.
Implémentation de tests automatisés.
```

## Prérequis

Choisissez votre serveur en fonction de votre système d'exploitation:

    - Windows : WAMP (http://www.wampserver.com/)
    - MAC : MAMP (https://www.mamp.info/en/mamp/)
    - Linux : LAMP (https://doc.ubuntu-fr.org/lamp)
    - XAMP (https://www.apachefriends.org/fr/index.html)

## Installation
1. Clonez ou téléchargez le repository GitHub dans le dossier voulu.
2. Configurez vos variables d'environnement tel que la connexion à la base de données/serveur SMTP/adresse mail dans le fichier `.env.local` ou `.env` (faire une copie du .env.test).
3. Téléchargez et installez les dépendances back-end du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
5. Créez les tables de la base de données :
```
    php bin/console doctrine:migrations:migrate
```
10. Le projet est maintenant installé, vous pouvez tester l'application.

11. Pour contribuer au projet :

    Consultez [contributing.md](https://github.com/MeillatTristan/ToDoList/blob/main/contributing.md).

12. Authentification :

    Consultez [authentication.md](https://github.com/MeillatTristan/ToDoList/blob/main/auth.md).


## Auteur

**MEILLAT Tristan**
