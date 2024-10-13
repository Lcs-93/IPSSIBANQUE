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

$message = ""; // Variable pour stocker les messages d'erreur ou de succès

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroCompte = $_POST['numeroCompte'];
    $solde = floatval($_POST['solde']);
    $typeDeCompte = $_POST['typeDeCompte'];
    $clientId = $_SESSION['clientId'];

    // Vérifier si le numéro de compte existe déjà
    $sql = "SELECT * FROM comptebancaire WHERE numeroCompte = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $numeroCompte);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Erreur : Ce numéro de compte existe déjà.";
    } else {
        // Vérifier si l'utilisateur a déjà un compte courant
        if ($typeDeCompte === 'Courant') {
            $sql = "SELECT * FROM comptebancaire WHERE clientId = ? AND typeDeCompte = 'Courant'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Erreur : Vous avez déjà un compte courant. Un utilisateur ne peut avoir qu'un seul compte courant.";
            }
        }

        // Si toutes les vérifications passent, insérer le nouveau compte
        if (empty($message)) {
            $sql = "INSERT INTO comptebancaire (numeroCompte, solde, typeDeCompte, clientId) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdsi", $numeroCompte, $solde, $typeDeCompte, $clientId);

            if ($stmt->execute()) {
                $message = "Compte créé avec succès!";
            } else {
                $message = "Erreur lors de la création du compte: " . $stmt->error;
            }
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Création de Compte</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="numeroCompte" class="form-label">Numéro de compte</label>
                <input type="text" class="form-control" id="numeroCompte" name="numeroCompte" placeholder="Numéro de compte" required>
            </div>
            <div class="mb-3">
                <label for="solde" class="form-label">Solde initial</label>
                <input type="number" class="form-control" id="solde" name="solde" placeholder="Solde initial" required>
            </div>
            <div class="mb-3">
                <label for="typeDeCompte" class="form-label">Type de compte</label>
                <select class="form-select" id="typeDeCompte" name="typeDeCompte" required>
                    <option value="Courant">Courant</option>
                    <option value="Epargne">Épargne</option>
                    <option value="Entreprise">Entreprise</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer compte</button>
        </form>
        
        <!-- Bouton retour à l'accueil -->
        <br>
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                // Récupération des valeurs
                const numeroCompte = document.getElementById('numeroCompte').value.trim();
                const solde = parseFloat(document.getElementById('solde').value);
                const typeDeCompte = document.getElementById('typeDeCompte').value;

                // Validation du numéro de compte
                if (!numeroCompte.startsWith("FR")) {
                    alert("Le numéro de compte doit commencer par 'FR'.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }
                if (numeroCompte.length < 5 || numeroCompte.length > 15) {
                    alert("Le numéro de compte doit contenir entre 5 et 15 caractères.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }

                // Validation du solde
                if (solde < 10 || solde > 2000) {
                    alert("Le solde doit être compris entre 10 et 2000.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }

                // Validation du type de compte
                const validTypes = ["Courant", "Epargne", "Entreprise"];
                if (!validTypes.includes(typeDeCompte)) {
                    alert("Le type de compte doit être 'Courant', 'Épargne' ou 'Entreprise'.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }
            });
        });
    </script>
</body>
</html>
