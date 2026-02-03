<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion utilisateur</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="glass block" style="max-width:720px;margin:40px auto">

    <h2>👤 <?= htmlspecialchars($user['username']) ?></h2>

    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Niveau :</strong> <?= $user['niveau'] ?? 'Non défini' ?></p>
    <p>
        <strong>Statut :</strong>
        <span class="badge <?= $user['statut'] ?>">
            <?= $user['statut'] ?>
        </span>
    </p>

    <!-- 🔥 ZONE ACTIONS CORRECTE -->
    <div class="actions-bar">

        <?php if ($user['statut'] === 'actif'): ?>
            <a class="btn-danger"
               href="actions/users_bulk_actions.php?action=block&id=<?= $user['id'] ?>">
               🔒 Bloquer l'utilisateur
            </a>
        <?php else: ?>
            <a class="btn-outline"
               href="actions/users_bulk_actions.php?action=unblock&id=<?= $user['id'] ?>">
               🔓 Débloquer l'utilisateur
            </a>
        <?php endif; ?>

        <a class="btn-outline"
           href="remarques.php?user_id=<?= $user['id'] ?>">
           📝 Remarques administratives
        </a>

        <a class="btn-outline"
           href="user_submissions.php?user_id=<?= $user['id'] ?>">
           📂 Travaux soumis
        </a>

        <a class="btn"
           href="dashboard.php">
           ← Retour
        </a>
    </div>

</div>

</body>
</html>
