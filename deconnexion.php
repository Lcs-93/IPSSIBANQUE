<?php
session_start();
session_destroy();
header("Location: connexion.php"); // Redirection vers la page de connexion
exit;
?>
