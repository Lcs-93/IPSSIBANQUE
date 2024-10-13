<?php
session_start();
if (!isset($_SESSION['clientId'])) {
    header("Location: connexion.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'devoirBanque');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clientId = $_SESSION['clientId'];
$sql = "SELECT nom, prenom FROM client WHERE clientId = $clientId";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Bienvenue, <?php echo htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']); ?></h1>
        
        <h2 class="mb-4">Fonctionnalités</h2>
        <ul class="list-group">
            <li class="list-group-item">
                <a href="creation_compte.php" class="btn btn-primary w-100">Créer un nouveau compte bancaire</a>
            </li>
            <li class="list-group-item">
                <a href="depot_retrait.php" class="btn btn-primary w-100">Faire un dépôt ou un retrait</a>
            </li>
            <li class="list-group-item">
                <a href="virement.php" class="btn btn-primary w-100">Effectuer un virement</a>
            </li>
            <li class="list-group-item">
                <a href="dashboard.php" class="btn btn-primary w-100">Consulter mon solde</a>
            </li>
            <li class="list-group-item">
                <a href="deconnexion.php" class="btn btn-danger w-100">Se déconnecter</a>
            </li>
            <li class="list-group-item">
                <a href="commander_chequier.php" class="btn btn-secondary w-100">Commander un chéquier</a>
            </li>
            <li class="list-group-item">
                <a href="supprimer_compte.php" class="btn btn-secondary w-100">Supprimer mon compte</a>
            </li>
        </ul>
    </div>

</body>
</html>
