<?php
include "posti.php";
$display = "d-none";
$message = "";
$success = "";
$viestivalmis = $lahetetty = false;

if (isset($_POST['btn'])) {
    $fullname = $_POST['fullname'] ?? '';
    $kenta_1 = "fullname";
    if (in_array($kenta_1, $pakolliset) && empty($fullname)) {
        $virheilmoitukset[$kenta_1] = translate('fullname_required');
    } else {
        if (isset($patterns[$kenta_1]) && !preg_match($patterns[$kenta_1], $fullname)) {
            $virheilmoitukset[$kenta_1] = translate('fullname_invalid');
        } else {
            $fullname = mysqli_real_escape_string($yhteys, $fullname);
        }
    }

    $title = $_POST['title'] ?? '';
    $kenta_2 = "title";
    if (in_array($kenta_2, $pakolliset) && empty($title)) {
        $virheilmoitukset[$kenta_2] = translate('title_required');
    } else {
        if (isset($patterns[$kenta_2]) && !preg_match($patterns[$kenta_2], $title)) {
            $virheilmoitukset[$kenta_2] = translate('title_invalid');
        } else {
            $title = mysqli_real_escape_string($yhteys, $title);
        }
    }

    $message = $_POST['message'] ?? '';
    $kenta_3 = "message";
    if (in_array($kenta_3, $pakolliset) && empty($message)) {
        $virheilmoitukset[$kenta_3] = translate('message_required');
    } else {
        if (isset($patterns[$kenta_3]) && !preg_match($patterns[$kenta_3], $message)) {
            $virheilmoitukset[$kenta_3] = translate('message_invalid');
        } else {
            $message = mysqli_real_escape_string($yhteys, $message);
        }
    }

    $email = $_POST['email'] ?? '';
    $kenta_4 = "email";
    if (in_array($kenta_4, $pakolliset) && empty($email)) {
        $virheilmoitukset[$kenta_4] = translate('email_required');
    } else {
        if (isset($patterns[$kenta_4]) && !preg_match($patterns[$kenta_4], $email)) {
            $virheilmoitukset[$kenta_4] = translate('email_invalid');
        } else {
            $email = mysqli_real_escape_string($yhteys, $email);
        }
    }
    if (empty($errors)) {
        $sql = "INSERT INTO contact_us (fullname, title, message, email) VALUES ('$fullname', '$title', '$message', '$email')";
        $result = $yhteys->query($sql);
        $viestivalmis = $yhteys->affected_rows;
    }
    if ($viestivalmis) {
        $subject = $title;
        $msg = $message . "\n\n" . $fullname . "\n" . $email;
        $lahetetty = posti($email, $msg, $subject);
        $lahetetty = posti($PALVELUOSOITE, $msg, $subject);
    }

    if ($lahetetty) {
        $message = translate('message_sent');
        $success = "success";
        // clear form fields after successful submission and redirect to the same page after 5 seconds
        $fullname = $title = $message = $email = "";
        echo "<script>setTimeout(() => { window.location.href = 'contact_us.php'; }, 3000);</script>";



     
    } else {
        $message = translate('message_failed');
        $success = "danger";
    }
    $display = "d-block";
}
