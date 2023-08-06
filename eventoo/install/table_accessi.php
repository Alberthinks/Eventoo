<title>Passaggio 2 - Creazione tabella Accesses</title>
<?php
include 'config_system.php';

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE accesses (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
nome TEXT NOT NULL,
cognome TEXT NOT NULL,
ip TEXT NOT NULL,
azione TEXT NOT NULL,
timestamp VARCHAR(255),
validity text
)";

if (mysqli_query($conn, $sql)) {
  echo "Tabella 'accesses' creata con successo\n";
  echo "<a href=\"table_classi.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore durante la creazione della tabella 'accesses': " . mysqli_error($conn);
}

mysqli_close($conn);
?>
