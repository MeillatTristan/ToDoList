<h1>Contributing Guide sur Github</h1>

<h4>Installation du projet</h4>

Pour contribuer au projet:  
- [forker](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo) le projet sur votre machine pour cloner le repository sur votre compte Github.
- Suivez le fichier [README.md](https://github.com/WainlaiN/todoV2/README.md) pour installer le projet.
- Créez une nouvelle branche pour la fonctionnalité et positionnez vous sur cette branche.
- Codez la nouvelle fonctionnalité ou bugfix.

<h4>Modification</h4>

Toutes modification devra être testée avec PhpUnit. Les tests devront être executés avec la commande `php bin/phpunit`

Voici les commandes liées à phpunit :
- `php bin/phpunit` Lance tous les tests
- `php bin/phpunit <NomDuFichier>.php` Lance tous les tests d'un fichier
- `php bin/phpunit --filter <NomDeLaMéthode>` Test d'une méthode spécifique
- `php bin/phpunit --coverage-html web\test-coverage` Coverage généré par xDebug


Quand vos modification sont terminés et que les tests sont valides, vous pouvez soumettre votre [pull request](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/about-pull-requests) et attendre
qu'elle soit acceptée.


<h4>Les règles à respecter</h4>

Respect des normes PSR-1 / PSR-12 / PSR-4.
Vérifiez les bonnes pratique de [Symfony](https://symfony.com/doc/current/best_practices.html)

Une fois tous les éléments validés vous pouvez soumettre une [merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html).
