<?php
$errors = [];
if (isset($_POST['painike'])) {

    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $currentPassword = $_POST['currentPassword'];

    if (empty($currentPassword)) {
        $errors['currentPassword'] = translate('current_password_missing');
    } else {
        $query = "SELECT * FROM users WHERE id='$id'";
        $result = my_query($query);
        $row = $result->fetch_assoc();
        if (!password_verify($currentPassword, $row['password'])) {
            $errors['currentPassword'] = translate('current_password_error');
        }
    }

    if (empty($password)) {
        $errors['password'] = "Salasana puuttuu";
    } elseif (!preg_match('/' . pattern('password') . '/', $password)) {
        $errors['password'] = translate('password_invalid');
    }

    if (empty($password2)) {
        $errors['password2'] = "Salasana puuttuu";
    } elseif (!preg_match('/' . pattern('password2') . '/', $password2)) {
        $errors['password2'] = translate('password_invalid');
    }

    if (empty($errors['password2']) and empty($errors['password'])) {
        if ($_POST['password'] != $_POST['password2']) {
            $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }


    if (empty($errors)) {
        $updated = date('Y-m-d H:i:s');
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query_update = "UPDATE users SET password='$password', updated='$updated' WHERE id='$id'";
        $result_update = my_query($query_update);
        if ($result_update) {
            $success = "success";
            $message = translate('profile_updated_msg');
            echo "<html><body>";
            echo "<div class='alert alert-$success'>$message</div>";
            echo "<meta http-equiv='refresh' content='2;url=poistu.php'>";
            echo "</body></html>";
            exit;
        } else {
            $success = "danger";
            $message = translate('profile_update_failed_msg');
        }
    }
}
ob_end_flush();
