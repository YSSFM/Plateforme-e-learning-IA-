<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $niveau   = $_POST['niveau'] ?? '';

    // Vérification des champs
    if ($username === '' || $email === '' || $password === '' || $confirm === '' || $niveau === '') {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si username ou email existent déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "Nom d'utilisateur ou email déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hash = password_hash($password, PASSWORD_BCRYPT);

            // Insertion dans la base
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, role, niveau, statut, created_at) 
                VALUES (?, ?, ?, 'etudiant', ?, 'actif', NOW())
            ");
            try {
                $stmt->execute([$username, $email, $hash, $niveau]);
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } catch (PDOException $e) {
                $error = "Erreur lors de la création du compte : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte | AI Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/infos_ia/assets/css/style.css">
    <link rel="stylesheet" href="/infos_ia/assets/css/auth.css">
</head>
<body>

<header class="glass header">
    <div class="header-left"><strong>YSSFM</strong></div>
    <nav class="header-right">
        <a href="/infos_ia/index.php">Accueil</a>
        <a href="login.php">Connexion</a>
    </nav>
</header>

<main class="auth-page">

    <section class="glass auth-info">
        <h1>Créer un compte</h1>
        <p class="subtitle">Rejoignez AI Courses dès maintenant</p>
        <p>
            Apprenez l'intelligence artificielle et suivez votre progression avec des cours adaptés à votre niveau.
        </p>
    </section>

    <section class="glass auth-box">
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
            <a href="login.php" class="btn">Se connecter</a>
        <?php else: ?>
            <form method="post">
                <label>Nom d'utilisateur *</label>
                <input type="text" name="username" required>

                <label>Email *</label>
                <input type="email" name="email" required>

                <label>Mot de passe *</label>
                <input type="password" name="password" required>

                <label>Confirmer le mot de passe *</label>
                <input type="password" name="confirm" required>

                <label>Niveau *</label>
                <select name="niveau" required>
                    <option value="">-- Sélectionnez votre niveau --</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                    <option value="S4">S4</option>
                </select>

                <button type="submit" class="btn">Créer un compte</button>
            </form>
        <?php endif; ?>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — Technologies émergentes & IA</p>
</footer>

</body>
</html>
