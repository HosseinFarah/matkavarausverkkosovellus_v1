<?php
$display = "d-none";
$message = "";
$success = "";
$lisays = $lisattiin_token = $lahetetty = false;


if (isset($_POST['reviewBtn'])) {
    [$errors, $values] = validointi($kentat);
    extract($values);

    //insert into reviews
    debuggeri($errors);
    if (empty($errors)) {
        $created = date('Y-m-d H:i:s');

        // check if user has already reviewed this tour
        $query = "SELECT * FROM reviews WHERE tour_id = $tour_id AND user_id = $user_id";
        $result = $yhteys->query($query);
        $row = $result->fetch_assoc();
        if ($row == null) {
            $query = "INSERT INTO reviews (tour_id,user_id,review,rating,created_at) VALUES ($tour_id,$user_id,'$review',$rating,'$created')";
            debuggeri($query);
            $result = $yhteys->query($query);
            $lisays = $yhteys->affected_rows;
        }
        // insert review
        else {
            $query = "UPDATE reviews SET review = '$review', rating = $rating WHERE tour_id = $tour_id AND user_id = $user_id";
            $result = $yhteys->query($query);
            $lahetetty = null;
            $message = "Arvostelusi on päivitetty!";
            $success = "light";
        }
    }

    if ($lisays) {
        $msg = "Kiitos arvostelustasi!<br><br>";
        $msg .= "Arvostelusi : $review<br> ja arvosanasi : $rating<br> Kiitos palautteestasi!";
        $subject = "Arvostelusi on vastaanotettu";
        $sql = "SELECT email FROM users WHERE id = $user_id";
        $result = $yhteys->query($sql);
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $lahetetty = posti($email, $msg, $subject);
    }
    if ($lahetetty) {
        $message = "Tallennus onnistui! Vahvistuspyyntö on lähetetty sähköpostiisi.";
        $success = "light";
    } else if ($lahetetty == null) {
        $message = "Taas onnistui! Arvostelusi on päivitetty.";
        $success = "light";
    } else {
        $message = "Tallennus epäonnistui!";
        $success = "danger";
    }
    $display = "d-block";

    /*
var_export($_POST);
var_export($_FILES);
echo "<br>";
var_export($errors);*/
}
ob_end_flush();
