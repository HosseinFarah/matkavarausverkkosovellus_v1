<?php
$errors = [];
if (isset($_POST['painike'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $city = $_POST['city'];
    $mobilenumber = $_POST['mobilenumber'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $target_dir = "profiilikuvat/users/";

    
if (empty($errors['password2']) and empty($errors['password'])) {
    if ($_POST['password'] != $_POST['password2']) {
        $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }

    // Handle image upload
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image']['name'];
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $extensions_arr)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            $errors['image'] = "Invalid file type.";
        }
    } else {
        // If no new image uploaded, retain the existing image
        if (!isset($image)) {
            $image = $row['image'];
        }
    }

    if (empty($errors)) {
        $updated = date('Y-m-d H:i:s');
        $password = password_hash($password, PASSWORD_DEFAULT);
        if(isset($POST['password']) && !empty($POST['password'])) {
            $password = password_hash($POST['password'], PASSWORD_DEFAULT);
        } else {
            $password = $row['password'];
        }
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $query_update = "UPDATE users SET firstname='$firstname', lastname='$lastname', address='$address', postcode='$postcode', city='$city', mobilenumber='$mobilenumber',  password='$password', image='$image' , updated='$updated' , is_active='$is_active' WHERE id='$id'";
        $result_update=my_query($query_update);
        if ($result_update) {
            $success = "success";
            $message = "Profiili päivitetty onnistuneesti! Kirjaudu uudelleen!";
            //reload the page
            header("Location: users.php"); 
            exit;
        } else {
            $success = "danger";
            $message = "Profiilin päivitys epäonnistui.";
        }
    }
}
ob_end_flush();

?>
