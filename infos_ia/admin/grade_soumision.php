<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT s.*, u.username, e.titre
     FROM soumissions s
     JOIN users u ON s.user_id = u.id
     JOIN exercices e ON s.exercice_id = e.id
     WHERE s.id = ?"
);
$stmt->execute([$id]);
$submission = $stmt->fetch();

if (!$submission) {
    header("Location: user_submissions.php");
    exit;
}

/* Enregistrement note */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'] !== '' ? (int)$_POST['note'] : null;
    $feedback = trim($_POST['feedback']);

    $update = $pdo->prepare(
        "UPDATE soumissions
         SET note = ?, feedback = ?
         WHERE id = ?"
    );
    $update->execute([$note, $feedback, $id]);

    header("Location: user_submissions.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Correction | Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="glass block" style="max-width:600px;margin:40px auto">

<h3>Correction du devoir</h3>

<p><strong>Étudiant :</strong> <?= htmlspecialchars($submission['username']) ?></p>
<p><strong>Exercice :</strong> <?= htmlspecialchars($submission['titre']) ?></p>

<form method="post">

    <label>Note (/20)</label>
    <input type="number" name="note" min="0" max="20"
           value="<?= $submission['note'] ?>">

    <label>Feedback</label>
    <textarea name="feedback"><?= htmlspecialchars($submission['feedback']) ?></textarea>

    <button class="btn">💾 Enregistrer</button>
    <a href="user_submissions.php" class="btn-outline">Annuler</a>

</form>

</div>

</body>
</html>
