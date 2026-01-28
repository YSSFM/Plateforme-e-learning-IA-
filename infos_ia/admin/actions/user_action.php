<?php
session_start();

require_once "../../config/database.php";
require_once "../admin_middleware.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
	header("Location: ../dashboard.php");
	exit;
}

$action = $_POST['action'] ?? null;
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if (!$action || !$user_id){
	header("Location: ../dashboard.php");
	exit;
}

switch ($action){
	case 'block':
	   $stmt = $pdo->prepare("UPDATE users SET status = 'blocked' WHERE id = ?");
	   $stmt->execute([$user_id]);
	   break;
	case 'delete':
	   // Suppression sécurisée
	   $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
	   $stmt->execute([$user_id]);
	   break;
}

header("Location: ../dashboard.php");
exit;
?>