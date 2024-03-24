<?php
// Nasconde gli errori agli utenti
ini_set("display_errors", false);

include 'licenza/Check.php';

$default_servername = "localhost";
$default_username = "eventooRootUser";
$default_password = "QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$";
$default_dbname = "eventoo";

// Create connection
$default_conn = mysqli_connect($default_servername, $default_username, $default_password, $default_dbname);
$default_query = mysqli_query($default_conn,"SELECT * FROM systems") or die (mysqli_error($default_conn));
$default_fetch = mysqli_fetch_array($default_query) or die (mysqli_error());

// Nome della piattaforma
$nome_app = $default_fetch['appName'];

// Versione della piattaforma
$version = $default_fetch['version'];

$dataValidity = date('Ymd');

// Stato della modalita' manutenzione (ATTIVATA/DISATTIVATA)
$maintenance = $default_fetch['maintenance'];


// Controllo se la modalita' manutenzione e' attiva o no
if ($maintenance == "true") {
    // Verifico se l'utente e' il tecnico di manutenzione o l'admin per lasciargli la possibilita' di vedere la piattaforma anche in fase di manutenzione
    if ($_SESSION['session_permessi_eventoo'] != "maintenance" && $_SESSION['session_permessi_eventoo'] != "administration") {
        header('Location: /eventoo/settings/error/error.php?status=err5077x26-Maintenance');
    }
    ?>
    <link rel="stylesheet" href="/eventoo/css/default.css" type="text/css">
    <div style="width: 100%; position: fixed; left: 0; right: 0; bottom: 0; padding: 20px; background: var(--color-alert);">
        <h2 style="margin-block-start: 0.83em; margin-block-end: 0.83em;">Sei in modalit&agrave; manutenzione!</h2>
        <p style="margin-block-start: 1em; margin-block-end: 1em;">
            Solo gli amministratori e i tecnici di manutenzione possono accedere e utilizzare la piattaforma in questo momento.
            Tutti gli altri utenti sono stati reindirizzati a questa pagina:
            <a href="/eventoo/settings/error/error.php?status=err5077x26-Maintenance" target="_blank">Pagina di manutenzione</a>
        </p>
    </div>
    <?php
}

// Formattare i dati inseriti dagli utenti nei form per caricarli nei db
function writeRecord($data) {
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Criptare i dati nei db
function cripta($string, $method) {
    $secretKey = "fdgjgtn544h1th1gb1ff";
    $secretIv = "fg4g4g1t11gfcfss";

    $algorithm = "AES-256-CBC";
    $key = hash("sha256", $secretKey);
    $iv = substr(hash('sha256', $secretIv), 0, 16);

    if ($method == "encrypt") {
        $output = base64_encode(openssl_encrypt($string, $algorithm, $key, 0, $iv));
    }

    if ($method == "decrypt") {
        $output = stripslashes(openssl_decrypt(base64_decode($string), $algorithm, $key, 0, $iv));
    }

    return $output;
}
?>
