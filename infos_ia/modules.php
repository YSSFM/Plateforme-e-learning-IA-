<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: auth/login.php");
    exit;
}

require_once "config/db.php";

$search = $_GET['q'] ?? '';

if ($search) {
    $stmt = $pdo->prepare("
        SELECT * FROM modules 
        WHERE titre LIKE ? OR description LIKE ?
        ORDER BY created_at DESC
    ");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM modules ORDER BY created_at DESC");
}

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modules | Plateforme IA</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .modules-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .modules-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-box input {
            padding: 12px 20px;
            border-radius: 30px;
            border: none;
            outline: none;
            width: 280px;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .module-card {
            padding: 30px;
            border-radius: 18px;
            background: rgba(255,255,255,0.35);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            transition: transform 0.2s;
        }

        .module-card:hover {
            transform: translateY(-5px);
        }

        .module-card h3 {
            margin-bottom: 10px;
        }

        .module-card p {
            opacity: 0.85;
            margin-bottom: 20px;
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

<main class="modules-container">

    <div class="modules-header">
        <h1>📚 Modules</h1>

        <form method="get" class="search-box">
            <input type="text" name="q" placeholder="Rechercher un module..." value="<?= htmlspecialchars($search) ?>">
        </form>
    </div>

    <div class="modules-grid">

        <?php if ($modules): ?>
            <?php foreach ($modules as $module): ?>
                <div class="module-card glass">
                    <h3><?= htmlspecialchars($module['titre']) ?></h3>
                    <p><?= htmlspecialchars($module['description']) ?></p>

                    <a href="module_view.php?id=<?= $module['id'] ?>" class="btn">
                        Voir le contenu →
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun module trouvé.</p>
        <?php endif; ?>

    </div>

</main>

<footer class="glass footer">
    <p>YSSFM — Plateforme IA</p>
</footer>

</body>
</html>
