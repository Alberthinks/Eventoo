<title>Passaggio 2 - Creazione tabella Classi</title>
<?php
include 'config.php';

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE classi (
id TEXT NOT NULL,
indirizzo TEXT NOT NULL
)";

if (mysqli_query($conn, $sql)) {
  echo "Tabella 'classi' creata con successo\n";
  echo "<a href=\"table_system.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore durante la creazione della tabella 'classi': " . mysqli_error($conn);
}

mysqli_close($conn);
?>
