<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

if (!isset($_POST['courses'], $_POST['action'])) {
    header("Location: ../dashboard.php#courses");
    exit;
}

$courseIds = array_map('intval', $_POST['courses']);
$placeholders = implode(',', array_fill(0, count($courseIds), '?'));

if ($_POST['action'] === 'delete') {
    $stmt = $pdo->prepare(
        "DELETE FROM cours WHERE id IN ($placeholders)"
    );
    $stmt->execute($courseIds);
}

header("Location: ../dashboard.php#courses");
exit;
