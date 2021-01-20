# Introduction
Ce projet est la réponse au test " Application de gestion et distribution d'informations produits ".

# Pré-requis 
Pour installer le projet vous devez :
- avoir `docker-compose`
- avoir `make`

Si vous n'avez pas ces utilitaires, vous pouvez ouvrir le fichier `Makefile` et reproduire à la main les instructions qui sont à l'intérieur.

# Installation
- `git clone git@github.com:farpat/pearl`
- `make install`

# Utilisation
Pour lancer le projet et voir le résultat du projet : 
- `make dev` (attention à la fin de ne pas oublier)
- Ouvrir http://localhost:8000 (le port 8000 peut être changé dans le .env)

# Tests fonctionnels
Pour lancer tous les tests fonctionnels :
- `make test`


