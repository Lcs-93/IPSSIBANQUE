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
$sql = "SELECT numeroCompte, solde, typeDeCompte FROM comptebancaire WHERE clientId = $clientId";
$result = $conn->query($sql);
$comptes = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
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
        <h1>Mes Comptes</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Numéro de Compte</th>
                    <th>Type de Compte</th>
                    <th>Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comptes as $compte) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($compte['numeroCompte']); ?></td>
                        <td><?php echo htmlspecialchars($compte['typeDeCompte']); ?></td>
                        <td><?php echo htmlspecialchars($compte['solde']); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Retour au menu principal</a>
    </div>
    
</body>
</html>
