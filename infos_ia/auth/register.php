<?php
session_start();

require_once "../config/database.php";

$error = null;
$succes = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$username = trim($_POST['username'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';
	
	if ($username === '' || $email === '' || $password === ''){
		$error = "Tous les champs sont obligatoires.";
	}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = "Adresse email invalide.";
	}elseif (strlen($password) < 6){
		$error = "Le mot de passe doit contenir au moins 6 caractères.";
	}else{
		// Vérifier si l'email existe déjà
		$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
		$stmt->execute([$email]);
		
		if ($stmt->rowcount() > 0){
			$error = "Cet email est déjà utilisé.";
		}else{
			// Hash du mot de passe
			$hash = password_hash($password, PASSWORD_DEFAULT);
			
			// Insertion utilisateur
			$stmt= $pdo->prepare("
			INSERT INTO users (username, email, password, role, status)
			VALUES (?, ?, ?, 'étudiant', 'active')
			");
			$stmt->execute([$username, $email, $hash]);
			
			$succes = "Compte créé avec succès. Vous pouvez vous connecter.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – AI Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <header class="header">
    <strong>AI Courses</strong>
    <a href="../index.php">Accueil</a>
  </header>
  
  <main class="bottom">
     <div class="bottom-left">
	   <h2>Créer un compte</h2>
	   <p>
	      Rejoignez la plateforme pour apprendre l'intelligence artficielle et les technologies émergentes.
	   </p>
	 </div>
	 
	 <div class="bottom-right">
	    <div class="auth_box">
		   <h2>Inscription</h2>
		   <?php if ($error): ?>
		       <p style="color:red;"><?= htmlspecialchars($error) ?></p>
		   <?php elseif ($succes): ?>
		       <p style="color:green;"><?= htmlspecialchars($succes) ?></p>
		   <?php endif; ?>
		   
		   <form method="post">
		      <label>Nom d'utilisateur</label>
			  <input type="text" name="username" required>
			  
			  <label>Email</label>
			  <input type="email" name="email" required>
			  
			  <label>Mot de passe</label>
			  <div class="password-field">
			     <input type="password" name="password" id="password" required>
				 <button type="button" id="togglePassword">👁</button>
			  </div>
			  
			  <button class="btn" type="submit">Créer le compte</button>
		   </form>
		   
		   <div class="register">
		       Déjà inscrit ?
			   <a href="login.php">Se connecter</a>
		   </div>
		</div>
	 </div>
  </main>
  
  <footer class="footer">
     <p>© AI Courses</p>
  </footer>
  <script src="../assets/js/password.js"></script>
</body>
</html>