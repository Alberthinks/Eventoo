<title>Passaggio 3 - Creazione tabella Planner</title>
<?php
include 'config_planner.php';

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE planner (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
titolo TEXT NOT NULL,
descrizione TEXT,
data VARCHAR(25) NOT NULL,
ora_inizio VARCHAR(25) NOT NULL,
ora_fine VARCHAR(25) NOT NULL,
organizzatore TEXT NOT NULL,
stanza TEXT NOT NULL,
classe TEXT NOT NULL,
categoria TEXT NOT NULL,
link_videoconferenza TEXT,
link_locandina TEXT,
data_modifica VARCHAR(255),
validity TEXT
)";

if (mysqli_query($conn, $sql)) {
  echo "Tabella 'planner' creata con successo\n";
  echo "<a href=\"add_person1.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore durante la creazione della tabella 'planner': " . mysqli_error($conn);
}

mysqli_close($conn);
?>
