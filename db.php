<?php
// Database connection setup
try {
   $yhteys = new mysqli($db_server, $db_username, $db_password, $DB);
   if ($yhteys->connect_error) {
       die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
   }
   $yhteys->set_charset("utf8");
} catch (Throwable $e) {
    die("Virhe yhteyden muodostamisessa: " . $e->getMessage() . " Error code: " . $e->getCode());
}

// Function to return the database connection
function db_connect() {
    return $GLOBALS['yhteys'];   
}

// Function to execute SQL queries
function my_query($sql) {
    try {
        global $yhteys;
        $result = $yhteys->query($sql);
        if ($result) {
            return $result;
        } else {
            echo "Error: " . $yhteys->error;
        }
    } catch (Exception $e) {
        echo "Error Num: " . $yhteys->errno . " Error: " . $e->getMessage();
    }
}

