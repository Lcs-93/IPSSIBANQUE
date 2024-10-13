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

$message = ""; // Variable pour stocker le message d'erreur ou de succès
$montant = ""; // Montant entré par l'utilisateur
$compteId = ""; // Compte sélectionné par l'utilisateur

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $montant = floatval($_POST['montant']);
    $compteId = intval($_POST['compteId']);
    $action = $_POST['action'];

    // Récupérer le solde actuel
    $sql = "SELECT solde FROM comptebancaire WHERE compteId = $compteId";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $soldeActuel = $row['solde'];

    if ($action == 'depot') {
        $nouveauSolde = $soldeActuel + $montant;
    } elseif ($action == 'retrait') {
        if ($soldeActuel >= $montant) {
            $nouveauSolde = $soldeActuel - $montant;
        } else {
            $message = "Fonds insuffisants. Veuillez entrer un montant inférieur ou égal à votre solde de " . htmlspecialchars($soldeActuel) . " €.";
        }
    }

    // Si l'opération a été validée sans erreur
    if (empty($message)) {
        // Mettre à jour le solde
        $sql = "UPDATE comptebancaire SET solde = $nouveauSolde WHERE compteId = $compteId";
        if ($conn->query($sql) === TRUE) {
            $message = "Transaction réussie! Nouveau solde: " . htmlspecialchars($nouveauSolde) . " €";
        } else {
            $message = "Erreur lors de la mise à jour du solde: " . $conn->error;
        }
    }
}

// Récupérer les comptes de l'utilisateur
$clientId = $_SESSION['clientId'];
$sql = "SELECT compteId, numeroCompte FROM comptebancaire WHERE clientId = $clientId";
$result = $conn->query($sql);
$comptes = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dépôt / Retrait</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Dépôt / Retrait</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Erreur') !== false ? 'danger' : 'success'; ?>" role="alert">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="compteId" class="form-label">Sélectionnez un compte</label>
                <select class="form-select" id="compteId" name="compteId" required>
                    <?php foreach ($comptes as $compte) : ?>
                        <option value="<?php echo $compte['compteId']; ?>" <?php echo ($compte['compteId'] == $compteId) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($compte['numeroCompte']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" value="<?php echo htmlspecialchars($montant); ?>" required>
            </div>
            <div class="mb-3">
                <label for="action" class="form-label">Action</label>
                <select class="form-select" id="action" name="action" required>
                    <option value="depot">Dépôt</option>
                    <option value="retrait">Retrait</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>

        <!-- Bouton retour à l'accueil -->
        <br>
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>

</body>
</html>
