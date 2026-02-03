<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';
$show_form = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $error = "Veuillez entrer votre email.";
    } else {
        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Aucun compte trouvé avec cet email.";
        } else {
            // Générer un token unique et expiration (1h)
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Supprimer anciens tokens
            $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?")->execute([$user['id']]);

            // Insérer le nouveau token
            $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $token, $expires]);

            // Générer le lien de réinitialisation
            $reset_link = "http://localhost/infos_ia/auth/reset_password.php?token=$token";

            // Normalement ici on envoie l'email, mais pour dev on affiche le lien
            $success = "Un email de réinitialisation a été envoyé !<br>Pour le développement, voici le lien : <a href='$reset_link'>$reset_link</a>";
            $show_form = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié | AI Courses</title>
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
        <h1>Mot de passe oublié</h1>
        <p class="subtitle">Entrez votre email pour recevoir un lien de réinitialisation.</p>
    </section>

    <section class="glass auth-box">
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if ($show_form): ?>
            <form method="post">
                <label>Email *</label>
                <input type="email" name="email" required>
                <button type="submit" class="btn">Envoyer le lien</button>
            </form>
        <?php endif; ?>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — Technologies émergentes & IA</p>
</footer>

</body>
</html>
