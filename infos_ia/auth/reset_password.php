<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';
$show_form = true;

$token = $_GET['token'] ?? '';

if ($token === '') {
    $error = "Lien invalide.";
    $show_form = false;
} else {
    // Vérifier le token
    $stmt = $pdo->prepare("
        SELECT pr.id, pr.user_id, pr.expires_at, u.username 
        FROM password_resets pr 
        JOIN users u ON pr.user_id = u.id 
        WHERE pr.token = ? LIMIT 1
    ");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if (!$reset) {
        $error = "Lien invalide.";
        $show_form = false;
    } elseif (strtotime($reset['expires_at']) < time()) {
        $error = "Lien expiré.";
        $show_form = false;
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';

        if ($password === '' || $confirm === '') {
            $error = "Veuillez remplir tous les champs.";
        } elseif ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            // Hasher le mot de passe et mettre à jour
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $reset['user_id']]);

            // Supprimer le token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE id = ?");
            $stmt->execute([$reset['id']]);

            $success = "Mot de passe réinitialisé avec succès !";
            $show_form = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe | AI Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/infos_ia/assets/css/style.css">
    <link rel="stylesheet" href="/infos_ia/assets/css/auth.css">
    <style>
        /* Success */
        .success {
            background: rgba(0, 255, 0, 0.15);
            color: #0b610b;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }

        /* Input focus */
        .auth-box input:focus {
            background: rgba(255,255,255,0.55);
            box-shadow: 0 0 10px rgba(88,204,2,0.4);
        }

        /* Password toggle */
        .password-field {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-field input {
            flex: 1;
            padding-right: 50px;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .toggle-password:hover {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .auth-page {
                grid-template-columns: 1fr;
                margin: 40px auto;
                gap: 30px;
            }
        }
    </style>
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
        <h1>Réinitialiser le mot de passe</h1>
        <p class="subtitle">Saisissez un nouveau mot de passe</p>
    </section>

    <section class="glass auth-box">
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
            <a href="login.php" class="btn">Se connecter</a>
        <?php endif; ?>

        <?php if ($show_form): ?>
            <form method="post">
                <label>Nouveau mot de passe *</label>
                <div class="password-field">
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" id="togglePassword">👁</button>
                </div>

                <label>Confirmer le mot de passe *</label>
                <div class="password-field">
                    <input type="password" name="confirm" id="confirm" required>
                    <button type="button" class="toggle-password" id="toggleConfirm">👁</button>
                </div>

                <button type="submit" class="btn">Valider</button>
            </form>
        <?php endif; ?>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — Technologies émergentes & IA</p>
</footer>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    });
    document.getElementById('toggleConfirm').addEventListener('click', function() {
        const pwd = document.getElementById('confirm');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    });
</script>

</body>
</html>
