CREATE DATABASE infos_ia;
USE infos_ia;

SET GLOBAL default_storage_engine = InnoDB;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('etudiant', 'admin') DEFAULT 'etudiant',
  statut ENUM('actif', 'bloque') DEFAULT 'actif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE modules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  description TEXT,
  niveau ENUM('DUT1', 'DUT2') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  module_id INT NOT NULL,
  titre VARCHAR(100) NOT NULL,
  contenu TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

CREATE TABLE exercices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cours_id INT NOT NULL,
  titre VARCHAR(100) NOT NULL,
  enonce TEXT NOT NULL,
  type ENUM('qcm', 'theorique', 'pratique') DEFAULT 'pratique',
  correction TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);

CREATE TABLE soumissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  exercice_id INT NOT NULL,
  fichier VARCHAR(255),
  note INT CHECK (note BETWEEN 0 AND 20),
  feedback TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (exercice_id) REFERENCES exercices(id) ON DELETE CASCADE
);

CREATE TABLE progressions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  cours_id INT NOT NULL,
  statut ENUM('non_commence', 'en_cours', 'termine') DEFAULT 'non_commence',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(user_id, cours_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);


CREATE TABLE remarques (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  user_id INT NOT NULL,
  type ENUM('info', 'avertissement') DEFAULT 'info',
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);
