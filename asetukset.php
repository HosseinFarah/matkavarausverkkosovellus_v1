<?php
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST'];
$PALVELU = "";
$LINKKI_RESETPASSWORD = "resetpassword.php";
$LINKKI_VERIFICATION = "verification.php";
$PALVELUOSOITE = "asiakaspalvelu@huvimatka.fi";

define("OLETUSSIVU", "profiili.php");
define("PROFIILIKUVAKANSIO", "profiilikuvat");
define("PROFIILIKUVAKOKO", 5242880);
// logout user if session is not active after 10 min
define("REMEMBERMEDURATION", 600);


$DB = "tourdb";
$LOCAL = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'REMOTE_ADDR' => '::1'));
if ($LOCAL) {
    $tunnukset = "tunnukset.php";
    if (file_exists($tunnukset)) {
        include_once("tunnukset.php");
    } else {
        die("Tiedostoa ei löydy, ota yhteys ylläpitoon.");
    }
    $map_box = $map_box_token;
    $db_server = $db_server_local;
    $db_username = $db_username_local;
    $db_password = $db_password_local;
} elseif (strpos($_SERVER['HTTP_HOST'], "azurewebsites") !== false) {
    // define("DEBUG", false);
    $debug = $_ENV['PHP_DEBUG'] ?? getenv('PHP_DEBUG');
    define("DEBUG", $debug ? true : false);
    $db_server = $_ENV['MYSQL_HOSTNAME'] ?? getenv('MYSQL_HOSTNAME');
    $db_username = $_ENV['MYSQL_USERNAME'] ?? getenv('MYSQL_USERNAME');
    $db_password = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD');
    $map_box = $_ENV['MAPBOX_TOKEN'] ?? getenv('MAPBOX_TOKEN');
    $stripe_pk = $_ENV['STRIPE_PK'] ?? getenv('STRIPE_PK');
    $stripe_private = $_ENV['STRIPE_PRIVATE'] ?? getenv('STRIPE_PRIVATE');
    /* Mailtrap */
    $admin_mail = $_ENV['EMAIL_ADMIN'] ?? getenv('EMAIL_ADMIN');
    $username_mailtrap = $_ENV['EMAIL_USERNAME'] ?? getenv('EMAIL_USERNAME');
    $password_mailtrap = $_ENV['EMAIL_PASSWORD'] ?? getenv('EMAIL_PASSWORD');
}

define("SAHKOPOSTIPALVELU", "mailtrap");
if (SAHKOPOSTIPALVELU == 'sendgrid') {
    /* SendGrid */
    define("EMAIL_HOST", "smtp.sendgrid.net");
    define("EMAIL_PORT", 587);
    define("EMAIL_USERNAME", $username_sendgrid);
    define("EMAIL_PASSWORD", $password_sendgrid);
} elseif (SAHKOPOSTIPALVELU == 'mailtrap') {
    /* Mailtrap */
    define("EMAIL_HOST", 'smtp.mailtrap.io');
    define("EMAIL_PORT", 2525);
    define("EMAIL_USERNAME", $username_mailtrap);
    define("EMAIL_PASSWORD", $password_mailtrap);
    //debuggeri("username:".USERNAME.",password:".PASSWORD);
}
