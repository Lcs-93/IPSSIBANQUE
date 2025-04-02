# IPSSIBANQUE

IPSSIBANQUE est un projet de système bancaire réalisé en PHP, incluant plusieurs fonctionnalités essentielles telles que l'inscription, la connexion, la gestion de comptes, les dépôts, retraits, et transferts d'argent.

## 📌 Description
L'application permet aux utilisateurs de créer un compte, de se connecter et d'effectuer diverses opérations bancaires comme des dépôts, des retraits, et des transferts. Un système de validation sécurisée est en place pour assurer la protection des données utilisateur.

## 🚀 Fonctionnalités principales
- **Inscription et connexion sécurisées**.
- **Gestion des comptes utilisateurs** : Création, consultation, et modification.
- **Dépôts et retraits d'argent**.
- **Transferts entre comptes** (incluant les transferts vers ses propres comptes).
- **Validation des formulaires en JavaScript**.
- **Gestion des erreurs et messages de confirmation**.
- **Interface utilisateur intuitive et responsive**.

## 📂 Structure du projet
```
.
├── index.php            # Page d'accueil
├── register.php         # Inscription des utilisateurs
├── login.php            # Connexion des utilisateurs
├── dashboard.php        # Tableau de bord utilisateur
├── deposit.php          # Dépôt d'argent
├── withdraw.php         # Retrait d'argent
├── transfer.php         # Transfert d'argent
├── assets/              # Fichiers CSS et JavaScript
├── includes/            # Fichiers PHP inclus (header, footer, etc.)
├── README.md            # Documentation du projet
```

## 🔧 Prérequis
- PHP >= 8.0
- Serveur web (Apache, Nginx, etc.)
- Base de données MySQL ou MariaDB

## 📥 Installation
1. Clonez ce dépôt :
```bash
$ git clone https://github.com/Lcs-93/IPSSIBANQUE.git
```
2. Rendez-vous dans le répertoire cloné :
```bash
$ cd IPSSIBANQUE
```
3. Placez les fichiers sur votre serveur web local ou distant.
4. Configurez la base de données dans le fichier `config.php` :
```php
$host = '127.0.0.1';
$dbname = 'ipssibanque';
$username = 'root';
$password = '';
```
5. Importez le fichier SQL fourni pour créer les tables nécessaires.
6. Accédez à l'application via votre navigateur (par exemple : `http://localhost/IPSSIBANQUE`).

## 📌 Utilisation
- Créez un compte ou connectez-vous avec un compte existant.
- Effectuez des dépôts, retraits, ou transferts d'argent.
- Visualisez votre solde actuel et vos transactions précédentes.

## 🛠️ Technologies utilisées
- **PHP** : Langage principal pour le backend.
- **HTML / CSS / JavaScript** : Pour le frontend.
- **MySQL / MariaDB** : Base de données relationnelle.

## 📄 Licence
Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📣 Auteur
Projet créé par **Lcs-93**. N'hésitez pas à me contacter pour toute suggestion ou amélioration !

---

🔥 Bon développement !

