<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

/* USERS */
$users = $pdo->query(
    "SELECT id, username, email, niveau, statut, updated_at
     FROM users
     ORDER BY id DESC"
)->fetchAll();

/* COURS */
$courses = $pdo->query(
    "SELECT 
        c.id,
        c.titre,
        m.niveau,
        c.created_at
     FROM cours c
     JOIN modules m ON c.module_id = m.id
     ORDER BY c.created_at DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin | AI Courses</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<style>
/* ✅ Dashboard admin amélioré */
.admin-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge.actif { background: #c8f7dc; color: #146c43; }
.badge.bloque { background: #f8d7da; color: #842029; }

table th { background: rgba(0,0,0,0.08); font-weight: 600; }
table input[type="checkbox"] { transform: scale(1.2); }
</style>
</head>
<body>

<div class="admin-layout">

    <aside class="sidebar glass">
        <h2>Admin<br><small>AI Courses</small></h2>
        <nav>
            <a href="#users">👥 Utilisateurs</a>
            <a href="#courses">📚 Cours</a>
            <a href="../auth/logout.php" class="danger">🚪 Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-content">

    <!-- USERS -->
    <section id="users" class="glass block">
        <h3>Suivi des utilisateurs</h3>

        <input type="text" placeholder="Rechercher un utilisateur" id="searchUsers">

        <form method="post" action="actions/users_bulk_actions.php">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllUsers"></th>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Niveau</th>
                    <th>Statut</th>
                    <th>Dernière activité</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><input type="checkbox" name="users[]" value="<?= $u['id'] ?>"></td>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['niveau'] ?: 'Non défini' ?></td>
                    <td><span class="badge <?= $u['statut'] ?>"><?= $u['statut'] ?></span></td>
                    <td><?= $u['updated_at'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="admin-actions">
            <button type="submit" name="action" value="block" class="btn-outline">Bloquer</button>
            <button type="submit" name="action" value="unblock" class="btn-outline">Débloquer</button>
            <button type="submit" name="action" value="delete" class="btn-danger"
                onclick="return confirm('Supprimer les utilisateurs sélectionnés ?')">Supprimer</button>
            <a href="user_remark.php" class="btn">Ajouter une remarque</a>
            <a href="user_submissions.php" class="btn">Voir travaux</a>
        </div>
        </form>
    </section>

    <!-- COURS -->
    <section id="courses" class="glass block">
        <h3>Gestion des cours</h3>

        <input type="text" placeholder="Rechercher un cours" id="searchCourses">

        <form method="post" action="actions/courses_bulk_actions.php">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCourses"></th>
                    <th>Titre</th>
                    <th>Niveau</th>
                    <th>Date ajout</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $c): ?>
                <tr>
                    <td><input type="checkbox" name="courses[]" value="<?= $c['id'] ?>"></td>
                    <td><?= htmlspecialchars($c['titre']) ?></td>
                    <td><?= $c['niveau'] ?></td>
                    <td><?= $c['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="admin-actions">
            <a href="add_course.php" class="btn">➕ Ajouter</a>
            <button type="submit" name="action" value="edit" class="btn-outline">✏ Modifier</button>
            <button type="submit" name="action" value="delete" class="btn-danger"
                onclick="return confirm('Supprimer les cours sélectionnés ?')">🗑 Supprimer</button>
        </div>
        </form>
    </section>

    </main>
</div>

<script>
// Sélectionner tout pour utilisateurs
document.getElementById('selectAllUsers').addEventListener('change', function(){
    document.querySelectorAll('input[name="users[]"]').forEach(cb => cb.checked = this.checked);
});
// Sélectionner tout pour cours
document.getElementById('selectAllCourses').addEventListener('change', function(){
    document.querySelectorAll('input[name="courses[]"]').forEach(cb => cb.checked = this.checked);
});
</script>

</body>
</html>
