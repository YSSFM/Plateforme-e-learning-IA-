<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . "/config/database.php";

$module_id = $_GET['id'] ?? null;

if (!$module_id || !ctype_digit($module_id)) {
    die("Module invalide.");
}

$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
$stmt->execute([$module_id]);
$module = $stmt->fetch();

if (!$module) {
    die("Module introuvable.");
}

$stmt = $pdo->prepare("
    SELECT id, titre, resume 
    FROM courses 
    WHERE module_id = ? 
    ORDER BY ordre ASC
");
$stmt->execute([$module_id]);
$courses = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT titre, type, url 
    FROM ressources 
    WHERE module_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$module_id]);
$ressources = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT id, titre, description 
    FROM exercices 
    WHERE module_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$module_id]);
$exercices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($module['titre']) ?> | Module</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .module-header {
            padding: 30px;
            margin-bottom: 40px;
        }

        .section {
            margin-bottom: 50px;
        }

        .section h2 {
            margin-bottom: 20px;
        }

        .item {
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 14px;
            background: rgba(255,255,255,0.35);
        }

        .item p {
            opacity: 0.85;
            margin: 8px 0;
        }

        .item a {
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>

<header class="glass header">
    <div><strong>YSSFM</strong></div>
    <nav>
        <a href="dashboard_user.php">Dashboard</a>
        <a href="modules.php">Modules</a>
        <a href="auth/logout.php">Déconnexion</a>
    </nav>
</header>

<main class="container">

    <!-- MODULE -->
    <div class="glass module-header">
        <h1><?= htmlspecialchars($module['titre']) ?></h1>
        <p><?= htmlspecialchars($module['description']) ?></p>
    </div>

    <!-- COURS -->
    <section class="section">
        <h2>📘 Cours</h2>

        <?php if ($courses): ?>
            <?php foreach ($courses as $course): ?>
                <div class="item glass">
                    <h3><?= htmlspecialchars($course['titre']) ?></h3>
                    <p><?= htmlspecialchars($course['resume']) ?></p>
                    <a href="course_view.php?id=<?= $course['id'] ?>" class="btn">
                        Lire le cours →
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun cours disponible pour ce module.</p>
        <?php endif; ?>
    </section>

    <!-- RESSOURCES -->
    <section class="section">
        <h2>📂 Ressources</h2>

        <?php if ($ressources): ?>
            <?php foreach ($ressources as $res): ?>
                <div class="item glass">
                    <strong><?= htmlspecialchars($res['titre']) ?></strong>
                    <p>Type : <?= htmlspecialchars($res['type']) ?></p>
                    <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="btn-outline">
                        Ouvrir →
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune ressource disponible.</p>
        <?php endif; ?>
    </section>

   
    <section class="section">
        <h2>✍️ Exercices</h2>

        <?php if ($exercices): ?>
            <?php foreach ($exercices as $ex): ?>
                <div class="item glass">
                    <h3><?= htmlspecialchars($ex['titre']) ?></h3>
                    <p><?= htmlspecialchars($ex['description']) ?></p>
                    <a href="exercise_view.php?id=<?= $ex['id'] ?>" class="btn">
                        Faire l’exercice →
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun exercice pour ce module.</p>
        <?php endif; ?>
    </section>

</main>

<footer class="glass footer">
    <p>YSSFM — <small>AI Courses</small></p>
</footer>

</body>
</html>
