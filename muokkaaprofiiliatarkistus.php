<?php
$errors = [];
if (isset($_POST['painike'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $city = $_POST['city'];
    $mobilenumber = $_POST['mobilenumber'];
    $target_dir = "profiilikuvat/users/";


    // Handle image upload
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image']['name'];

        // add user id to the image name
        $image = $id . "_" . $image;
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = ["jpg", "jpeg", "png", "gif"];
        // check image size if more than 2MB
        if ($_FILES['image']['size'] > 2097152) {
            $errors['image'] = translate('image_size_error');
        }
       


        if (in_array($imageFileType, $extensions_arr)) {
            // Delete the old image
            if ($row['image'] != "default.jpg") {
                unlink($target_dir . $row['image']);
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            }
            else {
                $image = "default.jpg";
            }
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
        $query_update = "UPDATE users SET firstname='$firstname', lastname='$lastname', address='$address', postcode='$postcode', city='$city', mobilenumber='$mobilenumber',  image='$image' , updated='$updated' WHERE id='$id'";
        $result_update=my_query($query_update);
        debuggeri('muokkaaprofiiliatarkistus.php', $result_update);
        if ($result_update) {
            $success = "success";
            $message = translate('profile_updated');
            //reload the page
            header("Location: profiili.php");
            exit;
        } else {
            $success = "danger";
            $message = translate('profile_update_failed');
        }
    }
}
ob_end_flush();

?>
