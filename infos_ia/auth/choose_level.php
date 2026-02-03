<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $niveau = $_POST['niveau'] ?? '';

    // Validation
    $valid_levels = ['S1','S2','S3','S4'];
    if (!in_array($niveau, $valid_levels)) {
        $error = "Veuillez sélectionner un niveau valide.";
    } else {
        // Mettre à jour le niveau dans la base
        $stmt = $pdo->prepare("UPDATE users SET niveau = ? WHERE id = ?");
        if ($stmt->execute([$niveau, $user_id])) {
            $_SESSION['niveau'] = $niveau;
            $success = "Votre niveau a été enregistré avec succès ! Redirection…";
            header("refresh:2;url=dashboard.php"); // Redirige vers dashboard après 2s
            exit;
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisir son niveau | AI Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<header class="glass header">
    <div class="header-left"><strong>YSSFM</strong></div>
</header>

<main class="auth-page">

    <section class="glass auth-box">
        <h2>Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> !</h2>
        <p class="subtitle">Sélectionnez votre niveau pour commencer</p>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Choisissez votre niveau :</label>
            <select name="niveau" required>
                <option value="">-- Sélectionner --</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
                <option value="S4">S4</option>
            </select>

            <button type="submit" class="btn">Enregistrer</button>
        </form>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — Technologies émergentes & IA</p>
</footer>

<style>
/* Styles spécifiques au choix de niveau */
select {
    padding: 14px;
    border-radius: 12px;
    border: none;
    margin-bottom: 16px;
    font-size: 1rem;
    outline: none;
}
.success {
    background: rgba(0, 255, 0, 0.15);
    color: #007500;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 15px;
    text-align: center;
}
</style>

</body>
</html>
