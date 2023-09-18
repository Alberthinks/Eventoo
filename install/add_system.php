<title>Passaggio 4 - Inserimento Manutenzione</title>
<?php
include 'config_system.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$installDate = date("d/m/Y");

$sql = "INSERT INTO systems (appName,version,maintenance,installDate,license)
VALUES ('Eventoo','08/2023','false','$installDate','')";

if ($conn->query($sql) === TRUE) {
  echo "<p>Il database di sistema &egrave; stato creato e configurato correttamente.</p>\n";
  echo "<a href=\"create_license_key.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
