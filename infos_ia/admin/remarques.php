<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

/* Liste des utilisateurs */
$users = $pdo->query(
    "SELECT id, username FROM users ORDER BY username"
)->fetchAll();

/* Ajout remarque */
if (isset($_POST['add_remark'])) {
    $user_id = (int)$_POST['user_id'];
    $message = trim($_POST['message']);

    if ($message !== '') {
        $stmt = $pdo->prepare(
            "INSERT INTO remarques (admin_id, user_id, message)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([$_SESSION['user_id'], $user_id, $message]);
    }
}

/* Suppression remarque */
if (isset($_POST['delete_remark'])) {
    $remark_id = (int)$_POST['remark_id'];
    $pdo->prepare("DELETE FROM remarques WHERE id = ?")
        ->execute([$remark_id]);
}

/* Historique */
$remarks = $pdo->query(
    "SELECT r.id, r.message, r.created_at, u.username
     FROM remarques r
     JOIN users u ON r.user_id = u.id
     ORDER BY r.created_at DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Remarques | Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.admin-container {
    display: flex;
    gap: 20px;
    max-width: 1200px;
    margin: 40px auto;
}
.history {
    width: 40%;
    max-height: 600px;
    overflow-y: auto;
}
.form-zone {
    width: 60%;
}
.remark {
    padding: 12px;
    margin-bottom: 12px;
    border-left: 4px solid #4CAF50;
}
.remark small {
    color: #aaa;
}
.delete-btn {
    background: #c0392b;
    color: #fff;
    border: none;
    padding: 5px 8px;
    cursor: pointer;
    float: right;
}
</style>
</head>

<body>

<h2 style="text-align:center">📝 Remarques administratives</h2>

<div class="admin-container">

    <!-- HISTORIQUE -->
    <aside class="glass history">
        <h3>📜 Historique</h3>
        <?php foreach ($remarks as $r): ?>
            <div class="remark">
                <form method="post" style="margin:0">
                    <input type="hidden" name="remark_id" value="<?= $r['id'] ?>">
                    <button class="delete-btn" name="delete_remark">✖</button>
                </form>
                <strong><?= htmlspecialchars($r['username']) ?></strong><br>
                <?= nl2br(htmlspecialchars($r['message'])) ?><br>
                <small><?= $r['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    </aside>

    <!-- FORMULAIRE -->
    <section class="glass form-zone">
        <h3>➕ Nouvelle remarque</h3>
        <form method="post">
            <label>Utilisateur</label>
            <select name="user_id" required>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>">
                        <?= htmlspecialchars($u['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Remarque</label>
            <textarea name="message" required></textarea>

            <button class="btn" name="add_remark">Enregistrer</button>
        </form>
    </section>

</div>

</body>
</html>
