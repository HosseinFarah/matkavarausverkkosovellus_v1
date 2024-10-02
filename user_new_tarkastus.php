<?php
$display = "d-none";
$message = "";
$success = "";
$lisays = $lisattiin_token = $lahetetty = false;


function hae_kuva($kentta){   
/* Huom. foreach-silmukka on tässä malliksi, ei valmis.
   Nimen tarkistukseen ei ole tässä koodia. */
    $kentat_tiedosto = $GLOBALS['kentat_tiedosto'];   
    $allowed_images = $GLOBALS['allowed_images'];
    $virhe = false;   
    $image = "";
    foreach ($kentat_tiedosto as $kentta){
    if (!isset($_FILES[$kentta])) continue;    
    if (is_uploaded_file($_FILES[$kentta]['tmp_name'])) {
       $random = randomString(3);
       $maxsize = PROFIILIKUVAKOKO;
       $temp_file = $_FILES[$kentta]["tmp_name"];       
       $filesize = $_FILES[$kentta]['size'];
       $pathinfo = pathinfo($_FILES[$kentta]["name"]);
       $filetype = strtolower($pathinfo['extension']);
       $image = $pathinfo['filename']."_$random.$filetype";
       $target_dir = PROFIILIKUVAKANSIO."/users/";
       $target_file = "$target_dir/$image";
       /* Check if image file is a actual image or fake image */
       if (!$check = getimagesize($temp_file)) $virhe = "Kuva ei kelpaa.";
       elseif (file_exists($target_file)) $virhe = "Kuvatiedosto on jo olemassa.";
       elseif (!in_array($filetype,$allowed_images)) $virhe = "Väärä tiedostotyyppi.";
       elseif ($filesize > $maxsize) $virhe = "Kuvan koon tulee olla korkeintaan 5 MB.";
       debuggeri("File $image,mime: {$check['mime']}, $filetype, $filesize tavua");
       if (!$virhe){
          if (!move_uploaded_file($temp_file,$target_file)) 
             $virhe = "Kuvan tallennus ei onnistunut.";
          } 
       }
       }
    return [$image,$virhe];
    }


if (isset($_POST['painike'])){
[$errors,$values] = validointi($kentat);  
extract($values);

if (empty($errors['password2']) and empty($errors['password'])) {
    if ($_POST['password'] != $_POST['password2']) {
        $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }
    
    
if (empty($errors)){
    [$image,$virhe] = hae_kuva($kentat_tiedosto);
    if ($virhe) $errors['image'] = $virhe;
    $image = ($image) ? "'$image'" : "NULL";
    }   
    
if (empty($errors)) {    
$query = "SELECT 1 FROM users WHERE email = '$email'";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
    $errors['email'] = $virheilmoitukset['email']['emailExistsError'];
    }

}    

debuggeri($errors);    
if (empty($errors)) {
    // $departments = $_POST['departments'] ?? [];
    // $departments_str = implode(", ", $departments);

    $created = date('Y-m-d H:i:s');
    $password = password_hash($password, PASSWORD_DEFAULT);
    $active = 1;
    $query = "INSERT INTO users (firstname, lastname, email, image, created, password,address,postcode,city,mobilenumber,is_active) VALUES ('$firstname', '$lastname', '$email', $image, '$created', '$password','$address','$postcode','$city','$mobilenumber','$active')";
    debuggeri($query);
    $result = $yhteys->query($query);
    if ($result) {
        $success = "Käyttäjä lisätty onnistuneesti.";
        $display = "d-none";
        header("Location: users.php");
        exit;
        }
    else {
        $message = $virheilmoitukset['user']['userNotAdded'];
        $display = "d-block";
        }
    }

}
ob_end_flush();
?>