<?php
namespace Devtech\License;

// Verifica della licenza

session_start();

include_once 'License.php';
include_once 'Generator.php';
include_once 'Validator.php';

$host = 'localhost';
$user = 'eventooRootUser';
$pass = 'QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$';
$db = 'eventoo';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
$query = mysqli_query($conn,"SELECT * FROM systems WHERE id = 1") or die (mysqli_error($conn));
$fetch = mysqli_fetch_array($query) or die (mysqli_error());

$licenseKey = $fetch['license'];
$license = new License("I.I.S. Primo Levi di Badia Polesine", $fetch['version'], $fetch['appName'], "12540532850705839", $_SERVER['SERVER_NAME']);
$validator = new Validator();

if(!($validator->validate($license, $licenseKey))) {
    if ($_SESSION['session_permessi_eventoo'] != "maintenance") {
        header('Location: /eventoo/settings/error/error.php?status=err5801x24-License-Key');
    }
    ?>
    <div style="width: 100%; position: fixed; left: 0; right: 0; bottom: 0; padding: 20px; background: #ffe166;">
        <h2 style="margin-block-start: 0.83em; margin-block-end: 0.83em;">La piattaforma &egrave; disattivata!</h2>
        <p style="margin-block-start: 1em; margin-block-end: 1em;">
            La licenza inserita non &egrave; valida, perci&ograve; la piattaforma &egrave; stata disattivata. Solo i tecnici di manutenzione possono accedere e utilizzare la piattaforma in questo momento.
            Tutti gli altri utenti sono stati reindirizzati a questa pagina:
            <a href="/eventoo/settings/error/error.php?status=err5801x24-License-Key" target="_blank">Pagina di blocco</a>
        </p>
    </div>
    <?php
}
?>