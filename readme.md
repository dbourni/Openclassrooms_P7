# P7 - Projet OpenClassrooms
[![Maintainability](https://api.codeclimate.com/v1/badges/8588c5e0ad599102feab/maintainability)](https://codeclimate.com/github/dbourni/Openclassrooms_P7/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5c31a5b429be474b9f4fcff9e2b6ae89)](https://www.codacy.com/app/dbourni/Openclassrooms_P7?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dbourni/Openclassrooms_P7&amp;utm_campaign=Badge_Grade)

Créez un web service exposant une API

## Installation

*   Clonez ou téléchargez le repository GitHub :
```system
git clone https://github.com/dbourni/Openclassrooms_P7.git
```
*   Configurez vos variables d'environnement telles que la connexion à la base de données .env

*   Installez les dépendances avec Composer :
```system
composer install
```

*   Créez la structure de la base de données :
```system
php bin/console doctrine:schema:create
```

*   Créez les fixtures vous permettant de tester :
```system
php bin/console doctrine:fixtures:load
```

*   Accédez à l'aide de l'API :
127.0.0.1:8000/doc (en fonction de l'adresse d'hébergement de l'API)

*   Se connecter et obtenir un token :
Requête GET sur http://127.0.0.1:8000/login_check, body {"username": "clientsfr@client.com", "password": "clientsfr"}