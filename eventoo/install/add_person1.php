<title>Passaggio 4 - Inserimento Amministratore</title>
<?php
include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$pass = password_hash("cambiami", PASSWORD_BCRYPT);
$nome = cripta("Emanuele", "encrypt");
$cognome = cripta("Albertin", "encrypt");
$o = cripta("o", "encrypt");
$uname = cripta("admin", "encrypt");
$foto = cripta("logos/iisplevi.jpg","encrypt");
$permessi = cripta("administration", "encrypt");


$sql = "INSERT INTO users (nome, cognome, ao, username, password, foto, permessi, last_access)
VALUES ('$nome', '$cognome', '$o', '$uname', '$pass', '$foto', '$permessi', ' ')";

if ($conn->query($sql) === TRUE) {
  echo "L'amministratore &egrave; stato aggiunto correttamente\n";
  echo "<a href=\"add_person2.php\"><button>Procedi>></button></a>";
} else {
  echo "Errore: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
