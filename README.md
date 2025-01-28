# toubeelib

# Membres
- [BENCHERGUI Timothee]
- [BIECHY Maxime]
- [KHENFER Vadim]

# Installation

- Démarrer docker
``` bash
    docker compose up
```

- Copier le fichier .toubeelibdb.env.dist en .toubeelibdb.env
``` bash
    cp .toubeelibdb.env.dist .toubeelibdb.env
```

- Copier le fichier .toubeelib.env.dist en .toubeelib.env
``` bash
    cp .toubeelib.env.dist .toubeelib.env
```

- Ajouter les informations de connexion à la base de données dans le fichier .toubeelibdb.env. Par exemple:<br>
POSTGRES_DB=toubeelib<br>
POSTGRES_USER=root<br>
POSTGRES_PASSWORD=root<br>


- Insertions des données dans sql

- Ouvrir un navigateur et aller sur localhost:8080 et rentrer les informations suivantes:<br>
System: PostgreSQL<br>
Server: toubeelib.db<br>
Utilisateur: root<br>
Mot de passe: root<br>
Base de données: toubeelib<br>

- Créer les bd suivantes :<br>
    - toubeelib_auth
    - toubeelib_rdvs
    - toubeelib_praticien
    - toubeelib_patient
  

- Importer les fichiers sql dans les bases de données correspondantes (dans le dossier sql)
- Commencer par les fichiers dans sql/architechture; 
- Puis les fichiers dans sql/data

- Pour créer un rendez-vous, vous pouvez utiliser postman et insérer dans le body de la requête POST les informations suivantes:<br>
  {<br>
      "from": "no-reply@example.com",<br>
      "subject": "nouveau rdv",<br>
      "text": "nouveau rdv",<br>
      "html": "<p>nouveau rdv.</p>",<br>
      "mailPraticien": "timothee@gmail.com",<br>
      "mailPatient": "maximeee@gmail.com",<br>
      "date": "2025-07-1 09:00:00",<br>
      "duree": 30,<br>
      "praticienID": "84cad6e1-b71c-4517-a435-edc70f3e114d",<br>
      "patientID": "5a087e85-0739-4bb3-b829-9e05b6a68680",<br>
      "specialiteDM": "1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb"<br>
  }<br>


- Vous pouvez observer les rendez-vous créés à l'url suivante:<br>
  http://localhost:1080/

