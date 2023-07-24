<title>Passaggio 1.1 - Creazione utente per accedere ai database</title>
<?php

$servername = "localhost";
$username = "root";
$dbname = "users";

// Create connection
$conn = mysqli_connect($servername, $username, "mysql");

// Check connection
if ($conn->connect_error) {
  //die("Connessione fallita:" . $conn->connect_error);
  $conn = mysqli_connect($servername, $username, "");
  if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
  }
}

// Creare l'utente che gestira' i database del planner
$createUser = "CREATE USER 'eventooRootUser'@'localhost' IDENTIFIED BY 'QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$';";

if ($conn->query($createUser) === TRUE) {
  // Dare una password all'utente che gestira' i database del planner
  $setUserPassword = "GRANT ALL PRIVILEGES ON *.* TO 'eventooRootUser'@'localhost';";

  if ($conn->query($setUserPassword) === TRUE) {
    // Concedere i permessi per i database all'utente che gestira' i database del planner
    $setUserPrivileges = "FLUSH PRIVILEGES;";

    if ($conn->query($setUserPrivileges) === TRUE) { 
      echo "Utente creato correttamente\n";
      echo "<a href=\"db_creation.php\"><button>Procedi>></button></a>";
    } else {
      echo "Errore (C): " . $setUserPrivileges . "<br>" . $conn->error;
    }
  } else {
    echo "Errore (B): " . $setUserPassword . "<br>" . $conn->error;
  }
} else {
  echo "Errore (A): " . $createUser . "<br>" . $conn->error;
}

$conn->close();
?>