<?php
session_start();
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>AI Courses</title>
    <meta charset="utf-8">
    <meta name="author" content="Moussa Youssouf">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="http://localhost/infos_ia/assets/css/style.css">
</head>

<body>

<header class="glass header">
    <div class="header-left">
        <strong>YSSFM - <small>AI Courses</small></strong>
    </div>

    <nav class="header-right">
        <a href="#">À propos du site</a>
        <a href="#">Infos utiles</a>
        <a class="btn-outline" href="auth/login.php">Se connecter</a>
    </nav>
</header>

<main>

    <section class="hero glass">
        <h1>Envie d'apprendre l'IA ?</h1>
        <p class="subtitle">Bienvenue sur AI Courses</p>

        <p class="highlight">
            Apprendre l'IA et les technologies émergentes gratuitement,
            à votre rythme et en toute simplicité.
        </p>

        <a href="auth/login.php" class="btn">Commencer maintenant</a>
    </section>

    <section class="bottom">

        <div class="glass bottom-left">
            <h2>Pourquoi AI Courses ?</h2>
            <p>
                Une plateforme e-learning dédiée exclusivement à l'IA
                et aux technologies émergentes pour les étudiants de niveau <strong>DUT</strong>.
            </p>
            <p>
                Cours, exercices (TP/TD), corrections et suivi de progression,
                le tout dans une interface simple et moderne.
            </p>
        </div>

        <div class="glass bottom-right">
            <h2>Accéder aux différents cours</h2>
            <p>
                Connectez-vous pour découvrir les modules,
                suivre votre progression et réaliser les exercices.
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
