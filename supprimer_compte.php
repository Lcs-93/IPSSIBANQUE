<?php
session_start();
if (!isset($_SESSION['clientId'])) {
    header("Location: connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression de Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Suppression de Compte</h1>
        <div class="alert alert-warning" role="alert">
            <strong>Important!</strong> Pour supprimer votre compte, vous devez vous rendre en agence. 
            Veuillez contacter votre conseiller pour plus d'informations.
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
    </div>
    
    
</body>
</html>
