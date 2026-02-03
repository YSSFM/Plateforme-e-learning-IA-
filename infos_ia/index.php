<?php
// index.php
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AI Courses</title>

    <meta name="author" content="Moussa Youssouf">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="/infos_ia/assets/css/style.css">
</head>

<body>

<header class="glass header">
    <div class="header-left">
        <strong>YSSFM - <small>AI Courses</small></strong>
    </div>

    <nav class="header-right">
        <a href="#">À propos du site</a>
        <a href="#">Infos utiles</a>
        <a class="btn-outline" href="/infos_ia/auth/login.php">Se connecter</a>
    </nav>
</header>

<main>

    <!-- HERO -->
    <section class="hero glass">
        <h1>Envie d'apprendre l'IA ?</h1>
        <p class="subtitle">Bienvenue sur AI Courses</p>

        <p class="highlight">
            Apprenez l’intelligence artificielle et les technologies émergentes
            gratuitement, à votre rythme et en toute simplicité.
        </p>

        <a href="/infos_ia/auth/login.php" class="btn">Commencer maintenant</a>
    </section>

    <!-- CONTENT -->
    <section class="bottom">

        <div class="glass bottom-left">
            <h2>Pourquoi AI Courses ?</h2>
            <p>
                Une plateforme e-learning dédiée exclusivement à l’IA
                et aux technologies émergentes pour les étudiants de niveau <strong>DUT</strong>.
            </p>
            <p>
                Cours structurés, TD/TP, corrections détaillées
                et suivi de progression dans une interface moderne.
            </p>
        </div>

        <div class="glass bottom-right">
            <h2>Accéder aux cours</h2>
            <p>
                Connectez-vous pour découvrir les modules,
                suivre votre progression et accéder aux exercices.
            </p>
        </div>

    </section>

</main>

<footer class="glass footer">
    <p>
        <strong><a href="#">YSSFM</a></strong>
        — Technologies émergentes & Intelligence Artificielle
    </p>
</footer>

</body>
</html>
