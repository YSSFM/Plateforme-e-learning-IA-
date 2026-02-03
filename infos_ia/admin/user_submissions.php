<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

/* Récupération des soumissions */
$submissions = $pdo->query(
    "SELECT 
        s.id,
        s.fichier,
        s.note,
        s.feedback,
        s.created_at,
        u.username,
        e.titre AS exercice
     FROM soumissions s
     JOIN users u ON s.user_id = u.id
     JOIN exercices e ON s.exercice_id = e.id
     ORDER BY s.created_at DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Travaux soumis - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="admin-layout">

    <aside class="sidebar glass">
        <h2>Admin<br><small>AI Courses</small></h2>
        <nav>
            <a href="dashboard.php">⬅ Dashboard</a>
            <a href="../auth/logout.php" class="danger">🚪 Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-content">

        <section class="glass block">
            <h3>📂 Travaux soumis</h3>

            <?php if (empty($submissions)): ?>
                <p style="text-align:center;opacity:.7">
                    Aucun travail soumis.
                </p>
            <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Exercice</th>
                        <th>Fichier</th>
                        <th>Note</th>
                        <th>Feedback</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($submissions as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['username']) ?></td>
                        <td><?= htmlspecialchars($s['exercice']) ?></td>

                        <td>
                            <?php if ($s['fichier']): ?>
                                <a href="../uploads/<?= htmlspecialchars($s['fichier']) ?>"
                                   target="_blank"
                                   class="btn-outline">
                                   📄 Télécharger
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td><?= $s['note'] ?? '—' ?></td>
                        <td><?= $s['feedback'] ? nl2br(htmlspecialchars($s['feedback'])) : '—' ?></td>
                        <td><?= $s['created_at'] ?></td>

                        <td>
                            <a href="grade_submission.php?id=<?= $s['id'] ?>" class="btn">
                                ✏ Corriger
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

            <?php endif; ?>

        </section>

    </main>
</div>

</body>
</html>
