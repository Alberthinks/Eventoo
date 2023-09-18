<?php
include "../default.php";
$error = $_GET['error'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Icone -->
        <link rel="apple-touch-icon" sizes="180x180" href="../img/icon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../img/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../img/icon/favicon-16x16.png">
        <link rel="manifest" href="../manifest.json">
        <!-- Titolo -->
        <title>Login | <?php echo $nome_app ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/fonts.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- JavaScript -->
        <script src="js/script.js" type="text/javascript"></script>
    </head>
    <body>
        <section class="form">
            <form name="enter" method="post" action="login.php" enctype="application/x-www-form-urlencoded">
                <h1>Login</h1>
                <div>Per continuare su <b><?php echo $nome_app; ?></b>.</div>
                    
                <!-- Input username -->
                <label class="username" id="inputname" for="uname">
                    <span id="placeholder" style="position: relative;" class="placeholder"><i class="material-icons">person</i> Inserisci username</span>
                    <input type="text" oninput="checkinput()" onfocus="focusFunction()" onblur="blurFunction()" onkeypress="inviaLogin(event)" name="username" id="uname" class="uname">
                </label>
                    
                <!-- Input password -->
                <label class="password" id="inputname" for="password">
                    <span id="placeholderpsw" style="position: relative;" class="placeholder"><i class="material-icons">password</i> Inserisci password</span>
                    <input type="password" oninput="checkinputPsw()" onfocus="focusFunctionPsw()" onblur="blurFunctionPsw()" onkeypress="inviaLogin(event)" name="password" id="password" class="uname">
                    <div onclick="showPsw()" class="showBtn"><i id="showBtn" class="material-icons" title="Mostra password">visibility</i></div>
                </label>
            </form>
            <!-- Link password dimenticata -->
            <p style="text-align: right;">
                <a href="#" onclick="forgottenPsw()" style="color: #187deb;">Credenziali dimenticate?</a>
            </p>
            <!-- Bottone di invio -->
            <button onclick="confirmation()" name="login" id="submit">Accedi</button>
		</section>
        <!-- Avvisi di errore -->
        <?php
        if (isset($error)) {
            if ($error == "0x5d66e0") {
        ?>
        <!-- Credenziali errate -->
        <section class="form alert">
            <b>Attenzione!</b><br>
            Credenziali utente errate.
        </section>
        <?php
            } elseif ($error == "0x85ar6q") {
        ?>
        <!-- Connessione al database fallita -->
        <section class="form alert">
            <b>Attenzione!</b><br>
            Errore durante la connessione al database.
        </section>
        <?php
            } else {
        ?>
        <!-- Errori generici -->
        <section class="form alert">
            <b>Attenzione!</b><br>
            Si &egrave; verificato un errore.
        </section>
        <?php
            }
        }
        ?>
        <!-- Form di recupero della password -->
        <div id="forgottenForm" style="display: none; background-color: #fff; width: 50%; min-width: 600px; height: 480px; position: fixed; top: 25%; bottom: 25%; left: 25%; right: 25%; z-index: 7; border-radius: 15px; box-shadow: 0 0 15px #333;">
            <iframe style="width: 100%; height: 100%;" frameborder="0" marginheight="0" marginwidth="0" id="forgottenFrame">Caricamento…</iframe>
        </div>
        <div onclick="closeForgotten()" id="background_div" style="display: none; width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 6; background: rgba(0, 0, 0, 0.5);"></div>
        <script>
            // Ripple button

            function createRipple(event) {
            const button = document.querySelector("button");

            const circle = document.createElement("span");
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
            circle.classList.add("ripple");

            const ripple = button.getElementsByClassName("ripple")[0];

            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
            }

            const buttons = document.getElementsByTagName("button");
            for (const button of buttons) {
            button.addEventListener("mousedown", createRipple);
            }
        </script>
    </body>
</html>