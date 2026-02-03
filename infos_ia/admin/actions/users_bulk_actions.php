<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /infos_ia/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if ($id <= 0) {
    header("Location: ../dashboard.php");
    exit;
}

/* Sécurité : empêcher l’admin de se supprimer */
if ($id === (int)$_SESSION['user_id'] && $action === 'delete') {
    header("Location: ../dashboard.php");
    exit;
}

switch ($action) {

    case 'block':
        $stmt = $pdo->prepare(
            "UPDATE users SET statut = 'bloque' WHERE id = ?"
        );
        $stmt->execute([$id]);
        break;

    case 'unblock':
        $stmt = $pdo->prepare(
            "UPDATE users SET statut = 'actif' WHERE id = ?"
        );
        $stmt->execute([$id]);
        break;

    case 'delete':
        $stmt = $pdo->prepare(
            "DELETE FROM users WHERE id = ?"
        );
        $stmt->execute([$id]);
        break;
}

header("Location: ../dashboard.php");
exit;
