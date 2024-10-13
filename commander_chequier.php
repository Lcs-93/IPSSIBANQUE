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
    <title>Commander un Chéquier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Commande de Chéquier</h1>
        <div class="alert alert-info" role="alert">
            Vous serez prévenu lors de l'arrivée de votre chéquier. 
            Merci de vous rendre en agence pour le récupérer.
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
    </div>
    

</body>
</html>
