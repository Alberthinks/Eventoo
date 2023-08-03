
<style>
* {font-family: sans-serif;}
body {user-select: none;}
.page {position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: white; color: black; padding-left: 30px; padding-right: 30px; user-select: none;}
input[type=radio], label {cursor: pointer;}
input[type=radio]:disabled, .disable {cursor: default; opacity: 0.8;}
code {border-bottom: 2px dotted black; font-family: monospace; font-size: 16px; user-select: text;}
</style>
<?php
include "../default.php";
include "../config.php";

$db = 'users';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
if (isset($_COOKIE['lele_password_recovery_timer_countdown65662656559'])) {
    $cookieValue = $_COOKIE['lele_password_recovery_timer_countdown65662656559'];
} else {
    setcookie("lele_password_recovery_timer_countdown65662656559", 1, time() + (86400 * 30), "/"); // 86400 = 1 day
    $cookieValue = 1;
}

$query = mysqli_query($conn,"SELECT * FROM users") or die (mysqli_error($conn));
$fetch = mysqli_fetch_array($query)or die (mysqli_error());

if ($cookieValue > 2) {
    echo "<h2>Hai sbagliato troppe volte l'inserimento delle credenziali!</h2><p>Prova a tornare tra 24 ore.</p>";
} else {
if (isset($_POST['send_password']) && $_POST['send_password'] == "Conferma") {
    $username = cripta($fetch['username'],"decrypt");
    $email = cripta($fetch['email'],"decrypt");

    if ($username == $_POST['username'] && $email == $_POST['email']) {
        $newPSW = "cambiami".rand(101230, 999878).date("dYmhs",time());
        $newPassword = password_hash($newPSW, PASSWORD_BCRYPT);
        if($result = mysqli_query($conn,"UPDATE `users` SET `password`='$newPassword' WHERE username='".cripta($username, "encrypt")."' AND email='".cripta($email, "encrypt")."'") or die (mysqli_error($conn))) {
            echo "Abbiamo appena resettato la password. La tua nuova password &egrave;: <code>".$newPSW."</code>";
        }
    } else {
        if ($username != $_POST['username']) {
            echo "Lo username &egrave; errato!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        } elseif ($email != $_POST['email']) {
            echo "L'indirizzo email &egrave; errato!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        } else {
            echo "Errore generico!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        }
    }
} elseif (isset($_POST['send_username']) && $_POST['send_username'] == "Conferma") {

    $password = $fetch['password'];
    $email2 = $_POST['email2'];
    $email = cripta($fetch['email'],"decrypt");

    if (password_verify($_POST['password'], $password) == true && $email2 == $email) {

        $email2 = cripta($email2,"encrypt");

        $query2 = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email2'") or die (mysqli_error($conn));
        $fetch2 = mysqli_fetch_array($query2)or die (mysqli_error());
        $username = $fetch['username'];

        echo "Il tuo username &egrave;: <code>".cripta($username,"decrypt")."</code>";
    } else {
        if (password_verify($_POST['password'], $password) == false) {
            echo "La password &egrave; errata!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        } elseif ($email != $email2) {
            echo "L'indirizzo email &egrave; errato!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        } else {
            echo "Errore generico!";
            $cookieValue++;
            setcookie("lele_password_recovery_timer_countdown65662656559", $cookieValue, time() + (86400 * 30), "/");
        }
    }
} else {
?>
<!--<link rel="stylesheet" href="../css/default.css" type="text/css">-->
<script>
    function gestisciRichiesta(selectedId) {
        document.getElementById(selectedId).style.display = "block";
        document.getElementById("starter").style.display = "none";
    }
</script>
<section class="container">
    <div class="page" id="starter" style="display: block;">
        <p>Di che cosa hai bisogno?</p>
        <input type="radio" value="username" name="credenziali" id="uname" onclick="gestisciRichiesta('username2')"> <label for="uname">Username</label><br>
        <input type="radio" value="password" name="credenziali" id="psw" onclick="gestisciRichiesta('password')"> <label for="psw" class="disable">Password</label>
    </div>
    <div class="page" id="password" style="display: none;">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Inserisci l'indirizzo email usato per la registrazione:</label><br>
        <input type="email" name="email" id="email" required><br><br>
        <label for="username">Inserisci lo username che usi per accedere:</label><br>
        <input type="text" name="username" id="username" required><br><br>
        <input type="submit" name="send_password" value="Conferma">
        </form>
    </div>
    <div class="page" id="username2" style="display: none;">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Inserisci l'indirizzo email usato per la registrazione:</label><br>
        <input type="email" name="email2" id="email2" required><br><br>
        <label for="password">Inserisci la password del tuo account:</label><br>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" name="send_username" value="Conferma">
        </form>
    </div>
</section>
<?php
}
}
?>