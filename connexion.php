<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'devoirBanque');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $sql = "SELECT clientId, mdp FROM client WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($mdp, $row['mdp'])) {
            $_SESSION['clientId'] = $row['clientId'];
            header("Location: index.php"); // Redirection vers index.php après connexion réussie
            exit;
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Aucun utilisateur trouvé avec cet email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Connexion</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <div class="mt-3 text-center">
            <p>Pas encore inscrit? <a href="inscription.php" class="btn btn-secondary">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>
