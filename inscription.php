<?php
$conn = new mysqli('localhost', 'root', '', 'devoirBanque');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; // Variable pour stocker les messages d'erreur

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    // Vérification de l'existence de l'email
    $sql = "SELECT * FROM client WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $message = "Cet email est déjà utilisé.";
    } else {
        // Vérification de l'existence du numéro de téléphone
        $sql = "SELECT * FROM client WHERE telephone = '$telephone'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $message = "Ce numéro de téléphone est déjà utilisé.";
        } else {
            // Si l'email et le téléphone sont disponibles, on peut les insérer
            $sql = "INSERT INTO client (nom, prenom, telephone, email, mdp) VALUES ('$nom', '$prenom', '$telephone', '$email', '$mdp')";

            if ($conn->query($sql) === TRUE) {
                // Redirection vers index.php après une inscription réussie
                header("Location: index.php");
                exit;
            } else {
                $message = "Erreur: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Inscription</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-warning" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Téléphone" required pattern="^\d{10}$" title="Le numéro de téléphone doit comporter exactement 10 chiffres.">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">S’inscrire</button>
        </form>

        <!-- Bouton pour rediriger vers la page de connexion -->
        <form action="connexion.php" method="get" style="margin-top: 20px;">
            <button type="submit" class="btn btn-secondary">Déjà un compte ? Connectez-vous</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                const mdp = document.getElementById('mdp').value;

                // Validation du mot de passe
                if (mdp.includes(' ')) {
                    alert("Le mot de passe ne doit pas contenir d'espaces.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                    return;
                }
            });
        });
    </script>
</body>
</html>
