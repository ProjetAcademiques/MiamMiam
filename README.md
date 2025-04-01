# MiamMiam
MiamMiam est un projet scolaire créé lors d'un cours sur Symfony afin d'apprendre à utiliser ce framework.

## pour commencer

- Cloner le projet sur votre serveur local (Wamp, XAMPP, etc.).
- Exécuter la commande : composer install.
- Lancer : php bin/console.
- Installer les dépendances front-end : npm install.
- (optionnelle si vous vouler la base distante) Ajouter un fichier .env.local dans le dossier MIAMMIAM et y insérer la ligne suivante :
DATABASE_URL="mysql://miammiam:Monn3t%236752@85.215.130.37:3306/miammiam?serverVersion=8.0.32&charset=utf8mb4"
- Compiler les assets :
npm run dev
npm run build
npm run watch

## crée un virtual-host
- Lancer Wamp.
- Ouvrir votre navigateur et aller à localhost/.
- Dans "Outils", cliquer sur Ajouter un Virtual Host.
- Renseigner les informations suivantes :
- Nom du Virtual Host : liste2courses.local
- Chemin du projet : indiquez l'emplacement du projet (exemple : C:/wamp/www/miammiam/miammiam).
- Cliquer sur Démarrer la création ou la modification du Virtual Host.
- Redémarrer Wamp.
- Accéder au projet via l'URL : http://liste2courses.local.

