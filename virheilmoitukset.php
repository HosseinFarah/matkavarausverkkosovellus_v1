<?php
/* Virheilmoituksia on ainakin kolmea tyyppiä:
1. Käyttäjän syötteiden virheilmoitukset, jotka näytetään lomakkeella
2. Tietokannan tai muut palvelimen virheilmoitukset, jotka näytetään lomakkeella
3. Muut palvelimen virheilmoitukset, jotka näytetään esim. lomakkeen alla
*/


$errors ??= [];
$kentat ??= ['firstname', 'lastname', 'email', 'mobilenumber', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber', 'feedback', 'payment_method', 'departments', 'terms_of_delivery', 'name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'tourImage', 'locations'];
$kentat_suomi ??= ['etunimi', 'sukunimi', 'sähköpostiosoite', 'matkapuhelinnumero', 'salasana', 'salasana', 'osoite', 'postinumero', 'kaupunki', 'puhelinnumero', 'palaute', 'maksutapa', 'osasto', 'toimitusehdot', 'nimi', 'otsikko', 'yhteenveto', 'kuvaus', 'paikka', 'aloituspäivä', 'ryhmäkoko', 'hinta', 'kuva', 'paikat'];
$pakolliset ??= ['firstname', 'lastname', 'email', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber', 'feedback', 'payment_method', 'terms_of_delivery'];
$kaannokset = array_combine($kentat, $kentat_suomi);
$allowed_images = ['gif', 'png', 'jpg', 'jpeg'];
//$kaannokset = ['firstname' => 'etunimi', 'lastname' => 'sukunimi', 'email' => 'sähköpostiosoite', 'mobilenumber' => 'matkapuhelinnumero', 'password' => 'salasana', 'password2' => 'salasana uudestaan'];
//$kaannokset = $kentat_suomi[array_search('lastname',$kentat)]
$w = "a-zA-Z0-9";
$patterns['password'] = "/^.{12,}$/";
$patterns['password2'] = $patterns['password'];
/* Huom. Myös heittomerkki ja tavuviiva */
$patterns['firstname'] = "/^[a-zåäöA-ZÅÄÖ'\-]+$/";
$patterns['lastname'] = $patterns['firstname'];
$patterns['name'] = "/^[a-zåäöA-ZÅÄÖ '\-]+$/";
$patterns['mobilenumber'] = "/^[0-9]{7,15}$/";
$patterns['email'] = "/^[$w]+[$w.+-]*@[$w-]+(\.[$w-]{2,})?\.[a-zA-Z]{2,}$/";
$patterns['image'] = "/^[^\s]+\.(jpe?g|png|gif|bmp)$/";
$patterns['rememberme'] = "/^checked$/";
$patterns['address'] = "/^[a-zåäöA-ZÅÄÖ0-9 '\-]+$/";
$patterns['postcode'] = "/^[0-9]{5}$/";
$patterns['city'] = "/^[a-zåäöA-ZÅÄÖ '\-]+$/";
$patterns['payment_method'] = "/^(sampo|nordea|osuuspankki|aktia)$/";
$patterns['feedback'] = "/^[a-zåäöA-ZÅÄÖ0-9 '\-]+$/";
$patterns['name'] = "/^[a-zåäöA-ZÅÄÖ '\-]+$/";
$patterns['title'] = "/^[a-zåäöA-ZÅÄÖ0-9 '\-]+$/";
$patterns['summary'] = "/^[a-zåäöA-ZÅÄÖ0-9 '\-]+$/";
$patterns['description'] = "/^[a-zåäöA-ZÅÄÖ0-9 '\-]+$/";
$patterns['location'] = "/^[a-zåäöA-ZÅÄÖ '\-]+$/";
$patterns['startDate'] = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
$patterns['groupSize'] = "/^[0-9]+$/";
$patterns['price'] = "/^[0-9]+$/";
$patterns['places'] = "/^[0-9]+$/";
$patterns['duration'] = "/^[0-9]+$/";
$patterns['tourImage'] = "/^[^\s]+\.(jpe?g|png|gif|bmp)$/";
$patterns['locations'] = "/^[0-9\-,]+$/";



function randomString($length = 3)
{
    return bin2hex(random_bytes($length));
}

function kaannos($kentta)
{
    return $GLOBALS['kaannokset'][$kentta];
}

function validationMessages($kentat)
{
    foreach ($kentat as $input) {
        $kentta = kaannos($input);
        $validationMessage[$input]['customError'] = "Virheellinen $kentta";
        $validationMessage[$input]['patternMismatch'] = "Virheellinen $kentta";
        $validationMessage[$input]['rangeOverflow'] = "Liian suuri $kentta";
        $validationMessage[$input]['rangeUnderflow'] = "Liian pieni $kentta";
        $validationMessage[$input]['stepMismatch'] = "Väärän kokoinen muutos";
        $validationMessage[$input]['tooShort'] = "Liian lyhyt $kentta";
        $validationMessage[$input]['tooLong'] = "Liian pitkä $kentta";
        $validationMessage[$input]['typeMismatch'] = "Väärän tyyppinen $kentta";
        $validationMessage[$input]['valueMissing'] = ucfirst($kentta) . " puuttuu";
        $validationMessage[$input]['valid'] = "Oikea arvo";
    }
    return $validationMessage;
}

function pattern($kentta)
{
    return trim($GLOBALS['patterns'][$kentta], "/");
}

function error($kentta)
{
    return $GLOBALS['errors'][$kentta] ?? $GLOBALS['virhetekstit'][$kentta]['puuttuu'];
}

function arvo($kentta)
{
    $error = $GLOBALS['errors'][$kentta] ?? false;
    return ($error) ? "" : $_POST[$kentta] ?? "";
}

function is_invalid($kentta)
{
    return (isset($GLOBALS['errors'][$kentta])) ? "is-invalid" : "";
}

$virheilmoitukset = validationMessages($kentat);
$virheilmoitukset['password']['patternMismatch'] = "Salasanan pitää olla vähintään 12 merkkiä pitkä";
$virheilmoitukset['password2']['valueMissing'] = "Anna salasana uudestaan";
$virheilmoitukset['password2']['customError'] = "Salasanat eivät täsmää";
$virheilmoitukset['email']['emailExistsError'] = "Sähköpostiosoite on jo käytössä";
$virheilmoitukset['firstname']['nameExistsError'] = "Nimi on jo käytössä";
$virheilmoitukset['lastname']['nameExistsError'] = "Nimi on jo käytössä";
$virheilmoitukset['accountNotExistErr'] = "Tuntematon sähköpostiosoite";
$virheilmoitukset['accountExistsMsg'] = "Sähköposti on lähetetty antamaasi sähköpostiosoitteeseen";
$virheilmoitukset['verificationRequiredErr'] = "Vahvista sähköpostiosoite ensin";
$virheilmoitukset['emailPwdErr'] = "Väärä käyttäjätunnus tai salasana";
$virheilmoitukset['emailErr'] = "Sähköpostin lähetys epäonnistui, yritä myöhemmin uudelleen";
$virheilmoitukset_json = json_encode($virheilmoitukset);

function validointi($kentat)
{
    // Define the error messages
    $patternErrorMessages = array(
        "firstname" => "Etunimi saa sisältää vain kirjaimia, välilyöntejä, viivoja ja heittomerkkejä.",
        "lastname" => "Sukunimi saa sisältää vain kirjaimia, välilyöntejä, viivoja ja heittomerkkejä.",
        "address" => "Katuosoite saa sisältää vain kirjaimia, numeroita, välilyöntejä ja viivoja.",
        "postcode" => "Postinumero saa sisältää vain numeroita ja olla 5 merkkiä pitkä.",
        "city" => "Kaupungin nimi saa sisältää vain kirjaimia, välilyöntejä ja viivoja.",
        "mobilenumber" => "Puhelinnumero saa sisältää vain numeroita ja olla muodossa +358 123 456 7890.",
        "email" => "Sähköpostiosoite ei ole oikeassa muodossa.",
        "password" => "Salasanan tulee olla vähintään 12 merkkiä pitkä ja sisältää vähintään yhden ison kirjaimen, pienen kirjaimen, numeron ja erikoismerkin.",
        "password2" => "Salasanat eivät täsmää.",
        "feedback" => "Palautteen tulee olla tekstiä.",
        "payment_method" => "Valitse maksutapa.",
        "departments_str" => "Valitse osasto.",
        "terms_of_delivery" => "Hyväksy toimitusehdot.",
        "name" => "Nimi saa sisältää vain kirjaimia, välilyöntejä ja viivoja.",
        "title" => "Otsikko saa sisältää vain kirjaimia, numeroita, välilyöntejä ja viivoja.",
        "summary" => "Yhteenveto saa sisältää vain kirjaimia, numeroita, välilyöntejä ja viivoja.",
        "description" => "Kuvaus saa sisältää vain kirjaimia, numeroita, välilyöntejä ja viivoja.",
        "location" => "Paikka saa sisältää vain kirjaimia, välilyöntejä ja viivoja.",
        "startDate" => "Aloituspäivä saa olla muodossa YYYY-MM-DD.",
        "groupSize" => "Ryhmäkoko saa sisältää vain numeroita.",
        "price" => "Hinta saa sisältää vain numeroita.",
        "tourImage" => "Kuvan tulee olla muodossa jpg, jpeg, png tai gif.",
        "locations" => "Kohteet saa sisältää vain numeroita ja pilkkuja."
        

    );


    $pakolliset = $GLOBALS['pakolliset'] ?? [];
    $patterns = $GLOBALS['patterns'] ?? [];
    $virheilmoitukset = $GLOBALS['virheilmoitukset'] ?? [];
    $yhteys = $GLOBALS['yhteys'] ?? null;
    $errors = [];
    $values = [];
    foreach ($kentat as $kentta) {
        $values[$kentta] = "";
        $arvo = $_POST[$kentta] ?? "";

        if ($kentta == 'departments' && !is_array($arvo)) {
            $errors[$kentta] = $virheilmoitukset['departments_str'];
        } else {
            /*
         if ($kentta == 'email' and !filter_var($arvo, FILTER_VALIDATE_EMAIL)) {
                $errors[$kentta] = $virheilmoitukset[$kentta]['typeMismatch'];
                }
        */

            if (in_array($kentta, $pakolliset) and empty($arvo)) {
                $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
            } else {
                if (!empty($kentta) and isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
                    $errors[$kentta] = $patternErrorMessages[$kentta];
                    // $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
                } else {
                    if (is_array($arvo)) $values[$kentta] = $arvo;
                    else $values[$kentta] = $yhteys->real_escape_string(strip_tags(trim($arvo)));
                }
            }
        }
        
    }

    return array($errors, $values);
}
