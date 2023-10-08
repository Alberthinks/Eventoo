<?php
session_start();
include "../../default.php";
include "../../config.php";

if ($_SESSION['session_permessi_eventoo'] == "maintenance") {
$helpText = "";
?>
<html>
    <head>
        <title>CMD - <?php echo $nome_app; ?></title>
        <style>
            * {background-color: black; color: #1cd901;}
            html, body {margin: 0; padding: 0;}
            #prompt {width: 100%; height: 100%; outline: none; font-size: 18px; caret-color: #ffffff; padding: 10px; border: none; resize: none;}
        </style>
        <script>
            function executeCMD() {
                document.getElementById("command").value = document.getElementById("prompt").value;
                document.getElementById("prompt").value = "";
                document.getElementById("actionCMD").submit();
            }
            
            function inviaCommand(event){
                // ottengo il codice unicode del tasto premuto sfruttando la proprietà keyCode dell'oggetto event
                var codiceTasto = event.keyCode;
                //event.preventDefault();
                // trasformo il codice unicode in carattere
                if (codiceTasto == 13) {
                    executeCMD();
                }
                if (codiceTasto == 46) {
                    document.getElementById("prompt").innerText = "";
                }
                if (codiceTasto == 120) {
                    document.getElementById("command").value = "showLicenseKey";
                    document.getElementById("actionCMD").submit();
                }
                if (codiceTasto == 121) {
                    document.getElementById("command").value = "maintenance a";
                    document.getElementById("actionCMD").submit();
                }
                if (codiceTasto == 122) {
                    document.getElementById("command").value = "maintenance d";
                    document.getElementById("actionCMD").submit();
                }
            }
        </script>
    </head>
    <body>
        <?php
        if (isset($_POST['command'])) {
            $command = $_POST['command'];
            switch($command) {
                case "help":
                    $helpText = "Lista delle funzioni disponibili:\n\n";
                    $helpText .= "cls      Ripulisce l'area di testo\n";
                    $helpText .= "exit      Chiude il prompt comandi\n";
                    $helpText .= "help      Mostra le funzioni disponibili con questo prompt di comandi\n";
                    $helpText .= "info      Restituisce le informazioni relative alla piattaforma\n";
                    $helpText .= "showLicenseKey      Mostra la License Key del prodotto\n";
                    $helpText .= "changeLicenseKey      Modifica la License Key del prodotto\n";
                    $helpText .= "getmaintenance      Dice se la modalità manutenzione è ATTIVA/DISATTIVATA\n";
                    $helpText .= "maintenance a      Abilita la modalità manutenzione\n";
                    $helpText .= "maintenance d      Disattiva la modalità manutenzione\n";
                    $helpText .= "\n\nLista dei tasti disponibili:\n\n";
                    $helpText .= "Del (Canc)            Ripulisce l'area di testo\n";
                    $helpText .= "F9            Mostra la License Key del prodotto\n";
                    $helpText .= "F10            Abilita la modalità manutenzione\n";
                    $helpText .= "F11            Disattiva la modalità manutenzione\n";
                    break;
                case "cls":
                    break;
                case "exit":
                    echo "<script>location.href = \"../\";</script>";
                    break;
                case "showLicenseKey":
                    $helpText = "La License Key è: ".$licenseKey;
                    break;
                case "getmaintenance":
                    if ($maintenance == "true") {
                        $helpText = "La modalità manutenzione è: ATTIVA";
                    } else {
                        $helpText = "La modalità manutenzione è: DISATTIVATA";
                    }
                    break;
                case "maintenance a":
                    $myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
                    $sql = "UPDATE systems SET maintenance='true' WHERE id = 1";

                    mysqli_query($myconn,$sql) or die (mysqli_error($conn));
                    break;
                case "maintenance d":
                    $myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
                    $sql = "UPDATE systems SET maintenance='false' WHERE id = 1";

                    mysqli_query($myconn,$sql) or die (mysqli_error($conn));
                    break;
                case "changeLicenseKey":
                    echo "<script>window.open(\"../../install/create_license_key.php\", \"_blank\", \"toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=600,height=360\");</script>";
                    break;
                case "info":
                    $default_conn = mysqli_connect($host, $user, $pass, 'eventoo');
                    $default_query = mysqli_query($default_conn,"SELECT * FROM systems") or die (mysqli_error($default_conn));
                    $default_fetch = mysqli_fetch_array($default_query) or die (mysqli_error());

                    // Nome della piattaforma
                    $nome_app = $default_fetch['appName'];
                    // LicenseKey usata per l'attivazione
                    $licenseKey = $default_fetch['licenseKey'];
                    // Versione della piattaforma
                    $version = $default_fetch['version'];
                    // Data installazione
                    $installDate = $default_fetch['installDate'];
                    $helpText = "Dettagli della piattaforma:\n\nNome:\t\t\t".$nome_app."\nLicenseKey:\t\t".$licenseKey."\nVersione:\t\t".$version."\nData di installazione:\t".$installDate;
                    break;
                default:
                    $helpText = "La funzione inserita non corrisponde a nessuna keyword del prompt comandi.";
            }
        }
        ?>
        <textarea id="prompt" onkeydown="inviaCommand(event)" spellcheck="false" autofocus><?php echo $helpText; ?></textarea>
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" id="actionCMD">
            <input type="hidden" name="command" id="command" value="">
        </form>
    </body>
</html>
<?php
} else {
    header("HTTP/1.0 403 Forbidden");
}
?>
