# Outils
* [Symfony5](https://symfony.com/5)

# Pour commencer

Pour lancer le projet vous aurez besoin de 
* [Apache](http://httpd.apache.org/docs/2.4/fr/install.html) >= 2
* [MySQL](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/) >= 5.7
* [PHP](https://www.php.net/manual/fr/install.php) >= 7.2

 [Aide Linux](https://www.digitalocean.com/community/tutorials/comment-installer-la-pile-linux-apache-mysql-php-lamp-sur-un-serveur-ubuntu-18-04-fr)
  ou [Aide Mac](https://documentation.mamp.info/en/MAMP-Mac/Installation/) 
  
# Lancer le projet

#### :warning: Créer son fichier .env.local à partir des informations manquantes, sinon la commande suivante ne pourra pas fonctionner!

Deux options : 

* Commande makefile (fonctionne sous linux)
Cette commande va se charger de créer un vhost starter.anaxago.local.com, de charger le schéma de base de données, et de jouer les fixtures (données de base avec des projets et un utilisateur)

```
make start-project
```

* N'importe quel outil que vous l'habitude d'utiliser : MAMP, WAMP, Docker, Symfony CLI
