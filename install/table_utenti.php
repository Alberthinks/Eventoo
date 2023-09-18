<title>Passaggio 2 - Creazione tabella Utenti</title>
<?php
include 'config.php';

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE users (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome TEXT NOT NULL,
cognome TEXT NOT NULL,
ao TEXT NOT NULL,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
foto TEXT,
permessi TEXT NOT NULL,
last_access TEXT
)";

if (mysqli_query($conn, $sql)) {
  echo "Tabella 'users' creata con successo\n";
  echo "<a href=\"table_planner.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore durante la creazione della tabella 'users': " . mysqli_error($conn);
}

mysqli_close($conn);
?>
