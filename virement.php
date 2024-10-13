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
$soldeDestinataire = null; // Initialisation par défaut

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $montant = floatval($_POST['montant']);
    $typeVirement = isset($_POST['typeVirement']) ? $_POST['typeVirement'] : null;
    $compteExpediteur = intval($_POST['compteExpediteur']);
    $clientId = $_SESSION['clientId'];

    if ($typeVirement == 'beneficiaire') {
        $numeroCompteBeneficiaire = $_POST['numeroCompteBeneficiaire'];

        // Vérifier si le numéro de compte bénéficiaire appartient à l'utilisateur lui-même
        $sql = "SELECT compteId FROM comptebancaire WHERE clientId = ? AND numeroCompte = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $clientId, $numeroCompteBeneficiaire);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si le compte bénéficiaire appartient à l'utilisateur, afficher un message d'erreur
            $message = "Vous ne pouvez pas utiliser votre propre compte comme bénéficiaire.";
        } else {
            // Vérifier si le bénéficiaire existe (autre utilisateur)
            $sql = "SELECT compteId, solde, numeroCompte, typeDeCompte FROM comptebancaire WHERE numeroCompte = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $numeroCompteBeneficiaire);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $beneficiaire = $result->fetch_assoc();
                $compteDestinataire = $beneficiaire['compteId'];
                $soldeDestinataire = $beneficiaire['solde'];
                $numeroCompteDestinataire = $beneficiaire['numeroCompte']; // Ajout du numéro de compte destinataire
                $typeCompteDestinataire = $beneficiaire['typeDeCompte']; // Ajout du type de compte destinataire
            } else {
                $message = "Le compte bénéficiaire n'existe pas.";
            }
        }
    } elseif ($typeVirement == 'comptePerso') {
        $compteDestinataire = isset($_POST['compteDestinataire']) ? intval($_POST['compteDestinataire']) : null;

        if ($compteExpediteur == $compteDestinataire) {
            $message = "Vous ne pouvez pas effectuer un virement vers le même compte.";
        } else {
            // Récupérer les infos du compte destinataire
            $sql = "SELECT solde, numeroCompte, typeDeCompte FROM comptebancaire WHERE compteId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $compteDestinataire);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $soldeDestinataire = $row['solde'];
                $numeroCompteDestinataire = $row['numeroCompte']; // Ajout du numéro de compte destinataire
                $typeCompteDestinataire = $row['typeDeCompte']; // Ajout du type de compte destinataire
            } else {
                $message = "Le compte destinataire n'existe pas.";
            }
        }
    }

    if ($soldeDestinataire !== null && empty($message)) {
        // Vérifier le solde de l'expéditeur
        $sql = "SELECT solde, numeroCompte, typeDeCompte FROM comptebancaire WHERE compteId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $compteExpediteur);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $soldeActuel = $row['solde'];
            $numeroCompteExpediteur = $row['numeroCompte']; // Ajout du numéro de compte expéditeur
            $typeCompteExpediteur = $row['typeDeCompte']; // Ajout du type de compte expéditeur

            if ($soldeActuel >= $montant) {
                // Débiter l'expéditeur
                $nouveauSoldeExpediteur = $soldeActuel - $montant;
                $sql = "UPDATE comptebancaire SET solde = ? WHERE compteId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $nouveauSoldeExpediteur, $compteExpediteur);
                $stmt->execute();

                // Créditer le destinataire
                $nouveauSoldeDestinataire = $soldeDestinataire + $montant;
                $sql = "UPDATE comptebancaire SET solde = ? WHERE compteId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $nouveauSoldeDestinataire, $compteDestinataire);
                $stmt->execute();

                // Message avec détails du virement
                $message = "Virement réussi de $montant € du compte $numeroCompteExpediteur ($typeCompteExpediteur) vers le compte $numeroCompteDestinataire ($typeCompteDestinataire).";
            } else {
                // Nouveau message d'erreur avec solde et numéro de compte
                $message = "Fonds insuffisants dans le compte $numeroCompteExpediteur ($typeCompteExpediteur). Solde actuel: $soldeActuel €.";
            }
        } else {
            $message = "Le compte expéditeur n'existe pas.";
        }
    }
}

$clientId = $_SESSION['clientId'];
$sql = "SELECT compteId, numeroCompte, typeDeCompte FROM comptebancaire WHERE clientId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $clientId);
$stmt->execute();
$result = $stmt->get_result();
$comptes = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Virement d'Argent</h1>

        <?php if ($message): ?>
            <div class="alert alert-warning" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="compteExpediteur" class="form-label">Compte expéditeur</label>
                <select class="form-select" id="compteExpediteur" name="compteExpediteur" required>
                    <?php foreach ($comptes as $compte) : ?>
                        <option value="<?php echo $compte['compteId']; ?>">
                            <?php echo $compte['numeroCompte'] . " (" . $compte['typeDeCompte'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Radio button for type of transfer -->
            <div class="mb-3">
                <label for="typeVirement" class="form-label">Type de virement</label>
                <div>
                    <input type="radio" id="beneficiaire" name="typeVirement" value="beneficiaire" checked>
                    <label for="beneficiaire">Vers un bénéficiaire</label>
                    <br>
                    <input type="radio" id="comptePerso" name="typeVirement" value="comptePerso">
                    <label for="comptePerso">Vers mes autres comptes</label>
                </div>
            </div>

            <!-- For beneficiaries: Enter account number -->
            <div class="mb-3" id="beneficiaireField">
                <label for="numeroCompteBeneficiaire" class="form-label">Numéro de compte du bénéficiaire</label>
                <input type="text" class="form-control" id="numeroCompteBeneficiaire" name="numeroCompteBeneficiaire" placeholder="Numéro du compte bénéficiaire">
            </div>

            <!-- For personal accounts: Select another account -->
            <div class="mb-3" id="comptePersoField" style="display: none;">
                <label for="compteDestinataire" class="form-label">Sélectionnez votre autre compte</label>
                <select class="form-select" id="compteDestinataire" name="compteDestinataire">
                    <?php foreach ($comptes as $compte) : ?>
                        <option value="<?php echo $compte['compteId']; ?>">
                            <?php echo $compte['numeroCompte'] . " (" . $compte['typeDeCompte'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>

        <!-- Bouton retour à l'accueil -->
        <form action="index.php" method="get" style="margin-top: 20px;">
            <button type="submit" class="btn btn-secondary">Retour à l'accueil</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const beneficiaireRadio = document.getElementById('beneficiaire');
            const comptePersoRadio = document.getElementById('comptePerso');
            const beneficiaireField = document.getElementById('beneficiaireField');
            const comptePersoField = document.getElementById('comptePersoField');

            beneficiaireRadio.addEventListener('change', function () {
                beneficiaireField.style.display = 'block';
                comptePersoField.style.display = 'none';
            });

            comptePersoRadio.addEventListener('change', function () {
                beneficiaireField.style.display = 'none';
                comptePersoField.style.display = 'block';
            });
        });
    </script>
</body>
</html>
