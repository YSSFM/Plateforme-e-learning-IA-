<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT * FROM users 
             WHERE email = ? AND statut = 'actif' 
             LIMIT 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['niveau']   = $user['niveau'];

            // Redirection intelligente
            if ($user['role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            }else {
                header('Location: dashboard_user.php');
            }
            exit;

        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | AI Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>

<header class="glass header">
    <div class="header-left"><strong>YSSFM</strong></div>
    <nav class="header-right">
        <a href="../index.php">Accueil</a>
        <a href="#">À propos</a>
        <a href="#">Infos utiles</a>
    </nav>
</header>

<main class="auth-page">

    <!-- GAUCHE -->
    <section class="glass auth-info">
        <h1>Besoin d'en savoir plus ?</h1>
        <p class="subtitle">Vous êtes au bon endroit.</p>
        <p>
            Apprenez l’intelligence artificielle et les technologies émergentes
            gratuitement, avec des cours organisés par niveaux DUT (S1 à S4).
        </p>
        <p>
            Commencez à votre rythme, suivez votre progression
            et accédez aux modules adaptés à votre niveau.
        </p>
        <strong>Profitez-en !</strong>
    </section>

    <!-- DROITE -->
    <section class="glass auth-box">
        <h2>Connexion</h2>
        <p class="subtitle">Accédez à votre espace AI Courses</p>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">

            <label>Email *</label>
            <input type="email" name="email" required>

            <label>Mot de passe *</label>
            <div class="password-field">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" id="togglePassword">
                    👁
                </button>
            </div>

            <div class="auth-links">
                <a href="forgot_password.php">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p class="small">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </p>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — Technologies émergentes & IA</p>
</footer>

<!-- JS affichage mot de passe -->
<script>
document.getElementById('togglePassword')
    .addEventListener('click', function () {
        const pwd = document.getElementById('password');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    });
</script>

</body>
</html>
