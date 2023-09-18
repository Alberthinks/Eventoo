<title>Passaggio 1.2 - Creazione database mySQL</title>
<?php
include 'config_planner.php';

// Create connection
$connected = new mysqli($servername, $username, $password);
// Check connection
if ($connected->connect_error) {
  die("Connessione fallita:" . $connected->connect_error);
}

// Create database
$sql = "CREATE DATABASE $dbname";
if ($connected->query($sql) === TRUE) {
  echo "Database 'Planner' creato correttamente\n";
  echo "<a href=\"table_utenti.php\"><button>Procedi>></button></a>";
} else {
  echo "Si &egrave; verificato un errore durante la creazione del database 'Planner': " . $connected->error;
  $sql2 = "DROP DATABASE $dbname";
  if ($connected->query($sql2) === TRUE) {
    echo "Database creato correttamente\n";
    echo "<a href=\"db_planner.php\"><button>Procedi>></button></a>";
  } else {
    echo "Si &egrave; verificato un errore durante la creazione del database: " . $connected->error;
  }
}

$connected->close();
?>