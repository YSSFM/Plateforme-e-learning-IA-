<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user'){
	header("Location: ../auth/login.php");
	exit;
}

require_once "../config/database.php";

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// modules dispo
$stmt = $pdo->query("SELECT id, titre, description FROM modules ORDER BY created_at DESC");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// exercices soumis
$stmt = $pdo->prepare(
    "SELECT e.id, e.titre
	 FROM exercices e
	 LEFT JOIN submissions s
	     ON e.id = s.exercice_id AND s.user_id = ?
	 WHERE s.id IS NULL"
);
$stmt->execute([$user_id]);
$peinding_exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour user
$stmt = $pdo->prepare(
    "SELECT sujet, statut, created_at
	 FROM user_requests
	 ORDER BY created_at DESC
	 LIMIT 5"
);
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Utilisateur</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
        .dashboard {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(12px);
            padding: 30px 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            color: #1f2937;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.35);
        }

        .main {
            padding: 40px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            padding: 25px;
            border-radius: 16px;
            background: rgba(255,255,255,0.35);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-bottom: 15px;
        }

        .list-item {
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            background: #58cc02;
            color: #fff;
        }

        .badge.pending {
            background: #f59e0b;
        }
    </style>
<body>
   <div class="dashboard">
      <aside class="sidebar">
	     <h2>🎓 AI Courses</h2>
		 
		 <a href="dasboard_user.php">🏠 Espace utilisateur</a>
		 <a href="modules.php">📚 Modules</a>
		 <a href="ressources.php">📂 Ressources</a>
		 <a href="exercices.php">✍️ Exercices</a>
		 <a href="progression.php">📊 Progression</a>
		 <a href="requests.php">📨 Mes requêtes</a>
		 <a href="profile.php">Profil</a>
		 <a href="../auth/logout.php">🚪 Déconnexion</a>
	  </aside>
	  
	  <main class="main">
	     <div class="welcome">
		    <h1>Bienvenu(e), <?= htmlspecialchars($username) ?> 👋</h1>
			<p>Voici un aperçu de votre activité.</p>
		 </div>
		 
		 <div class="cards">
		   <div class="card">
		      <h3>📚 Modules disponibles</h3>
			  <?php if ($modules): ?>
			      <?php foreach ($modules as $m): ?>
				     <div class="list-item">
					    <strong><?= htmlspecialchars($m['titre']) ?></strong>
						<small><?= htmlspecialchars($m['description']) ?></small>
					 </div>
				  <?php endforeach; ?>
			   <?php else: ?>
			       <p>Aucun module pour le moment.</p>
			   <?php endif; ?>
		   </div>
		   
		   <div class="card">
		      <h3>✍️  Exercices à faire</h3>
			  <?php if ($pending_exercices): ?>
			     <?php foreach ($pending_exercices as $e): ?>
				     <div class="list-item">
					    <?= htmlspecialchars($e['titre']) ?>
						<span class="badge pending">À faire</span>
					 </div>
				 <?php endforeach; ?>
			  <?php else: ?>
			      <p>Tous les exercices sont soumis ✅</p>
			  <?php endif; ?>
		   </div>
		   
		   <div class="card">
		     <h3>📨 Mes dernières requêtes</h3>
			 <?php if($requests): ?>
			     <?php foreach ($requests as $r): ?>
				     <div class="list-item">
					    <strong><?= htmlspecialchars($r['sujet']) ?></strong>
						<small>
						    statut :
							<span class="badge"><?= htmlspecialchars($r['statut']) ?></span>
						</small>
					 </div>
				 <?php endforeach; ?>
			 <?php else: ?>
			     <p>Aucune requête envoyée pour le moment.</p>
			 <?php endif; ?>
		   </div>
		 </div>
	  </main>
   </div>
</body>
</html>