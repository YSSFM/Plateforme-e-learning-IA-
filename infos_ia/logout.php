<?php
// Suppression des  variables de sessions
$_SESSION = [];

// Destruction de la session
session_destroy();

// Redirection vers la page dd'accueil
header("Location: index.php");
exit;
?>