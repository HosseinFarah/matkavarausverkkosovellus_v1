<?php
if (!session_id()) {
    session_start();
}
// Default language is English if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fi';
}
$currentLanguage = $_SESSION['lang'];

// Change language based on user selection via ?lang=
if (isset($_GET['lang'])) {
    $selected_lang = $_GET['lang'];
    if (file_exists(__DIR__ . "/lang/$selected_lang.php")) {
        $_SESSION['lang'] = $selected_lang;
    }
}
// Load the correct language file
function loadLanguage($lang) {
    $lang_file = __DIR__ . "/lang/$lang.php";
    if (file_exists($lang_file)) {
        return include($lang_file);
    }
    return include(__DIR__ . "/lang/en.php");  // Default to English if the language file is not found
}

// Load the language into a variable
$lang = loadLanguage($_SESSION['lang']);

// Function to translate a key using the loaded language array
function translate($key) {
    global $lang;
    return $lang[$key] ?? $key;  // Return the translation or the key if not found
}



// Function to fetch dynamic content based on language
function getTranslation($tourId, $fieldName, $lang) {
    global $yhteys;
    
    $query = $yhteys->prepare("SELECT content FROM translations WHERE tour_id = ? AND field_name = ? AND language = ? LIMIT 1");
    $query->bind_param('iss', $tourId, $fieldName, $lang);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();
    
    if ($result) {
        return $result['content'];
    }
    
    return 'Content not found'; // Handle cases where translation doesn't exist
}
