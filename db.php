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

// Function to fetch dynamic content based on language
function getDynamicContent($key, $lang) {
    global $yhteys;
    
    $query = $yhteys->prepare("SELECT content_value FROM content WHERE content_key = ? LIMIT 1");
    $query->bind_param('s', $key); // Use bind_param for MySQLi
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if ($result) {
        $content = json_decode($result['content_value'], true);
        return $content[$lang] ?? $content['en']; // Fallback to English
    }
    return 'Content not found';
}
?>
