Système de Gestion Bancaire en PHP
Ce projet est un système de gestion bancaire développé en PHP avec une base de données MySQL. Il permet aux utilisateurs de créer un compte, se connecter, et effectuer des opérations bancaires comme les virements entre comptes.

Fonctionnalités
Inscription : Les utilisateurs peuvent s'inscrire avec leur nom, prénom, email, numéro de téléphone et mot de passe.
Connexion : Les utilisateurs peuvent se connecter à leur compte avec leur email et mot de passe.
Virement :
Virement à un bénéficiaire externe : L'utilisateur peut effectuer un virement vers un autre compte bancaire.
Virement entre ses propres comptes : L'utilisateur peut transférer de l'argent entre ses comptes personnels (compte courant et épargne).
Validation des formulaires : Les formulaires sont validés côté serveur et côté client (via JavaScript).
Messages d'erreur et de succès : Les utilisateurs reçoivent des retours visuels sur les opérations effectuées (succès ou échec).
Prérequis
Avant de pouvoir exécuter ce projet, assurez-vous que votre environnement est configuré avec les éléments suivants :

PHP (version 7.4 ou supérieure)
MySQL ou MariaDB pour la base de données
Apache ou un autre serveur web compatible avec PHP


Utilisation
Inscription
Accédez à la page d'inscription (par exemple /inscription.php).
Remplissez le formulaire avec vos informations personnelles.
Cliquez sur "S'inscrire". Si l'inscription réussit, vous serez redirigé vers la page de connexion.
Connexion
Après vous être inscrit, rendez-vous sur la page de connexion.
Entrez votre email et mot de passe pour accéder à votre compte.
Virement
Une fois connecté, accédez à la section "Virement".
Choisissez entre un virement à un bénéficiaire externe ou un virement entre vos propres comptes.
Entrez les informations requises et soumettez le formulaire.

Sécurité
Hashage des mots de passe : Les mots de passe sont sécurisés en utilisant password_hash() lors de l'inscription et vérifiés avec password_verify() lors de la connexion.
Prévention des injections SQL : Les requêtes préparées avec PDO empêchent les injections SQL en sécurisant les données utilisateurs.
Validation des formulaires : Les entrées utilisateur sont validées à la fois côté client (JavaScript) et côté serveur (PHP) pour garantir que seules les données valides sont soumises.
Améliorations Futures
Implémenter une fonctionnalité de récupération de mot de passe.
Ajouter un système de gestion des comptes multiples pour les utilisateurs.
Améliorer l'interface utilisateur avec un design plus moderne et réactif.
Implémenter un tableau de bord pour suivre les transactions effectuées.
