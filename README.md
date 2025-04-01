# MiamMiam

MiamMiam est un projet scolaire créé lors d'un cours sur Symfony afin d'apprendre à utiliser ce framework.

## Pour commencer

- Cloner le projet sur votre serveur local (Wamp, XAMPP, etc.).
- se mettre dans le dossier cloné puis dans MiamMiam/.
- Exécuter la commande : composer install.
- Installer les dépendances front-end : npm install.
- (optionnelle si vous voulez la base distante) Ajouter un fichier .env.local toujours dans le dossier MiamMiam et y insérer la ligne suivante :
  DATABASE_URL="mysql://miammiam:Monn3t%236752@85.215.130.37:3306/miammiam?serverVersion=8.0.32&charset=utf8mb4"
  Sinon changer l'url dans le fichier .env pour correspondre à votre base de données locale.
- Compiler les assets :
  npm run build

## Créer un virtual-host

- Lancer Wamp.
- Ouvrir votre navigateur et aller à localhost/.
- Dans "Outils", cliquer sur Ajouter un Virtual Host.
- Renseigner les informations suivantes :
- Nom du Virtual Host : liste2courses.local
- Chemin du projet : l'emplacement du projet dans le dossier public (exemple : C:/wamp64/www/miammiam/miammiam/public).
- Cliquer sur Démarrer la création ou la modification du Virtual Host.
- Cliquer droit sur Wamp puis outils et Redémarrage DNS.
- Accéder au projet via l'URL : http://liste2courses.local.

## Charger les fixtures

- executer la commande `php bin/console doctrine:fixtures:load`

## Informations de connexion administrateur

Après le chargement des fixtures, vous pouvez vous connecter avec les identifiants administrateur suivants :

- Email : minh@ad.fr
- Mot de passe : minh@ad.fr
