<title>Passaggio 4 - Inserimento Manutenzione</title>
<?php
include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$pass = password_hash("cambiami", PASSWORD_BCRYPT);
$nome = cripta("Tiziano", "encrypt");
$cognome = cripta("Guerrato", "encrypt");
$o = cripta("o", "encrypt");
$uname = cripta("manutenzione", "encrypt");
$foto = cripta("logos/iisplevi.jpg","encrypt");
$permessi = cripta("maintenance", "encrypt");


$sql = "INSERT INTO users (nome, cognome, ao, username, password, foto, permessi, last_access)
VALUES ('$nome', '$cognome', '$o', '$uname', '$pass', '$foto', '$permessi', ' ')";

if ($conn->query($sql) === TRUE) {
  echo "<p>Il tuo account &egrave; stato inserito correttamente</p>\n";
  echo "<p><b>Username:</b> manutenzione<br><b>Password:</b> cambiami</p>\n";
  echo "<a href=\"#\" onclick=\"window.close()\"><button>Fine</button></a>";
} else {
  echo "Errore: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
