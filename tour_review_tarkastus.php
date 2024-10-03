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
            $message = translate('review_updated');
            $success = "light";
        }
    }

    if ($lisays) {
        $msg = translate('review_thanks')."<br><br>".translate('your_review').": $review<br>".translate('your_rating').": $rating<br>".translate('thank_you');
        $subject = translate('review_received');
        $sql = "SELECT email FROM users WHERE id = $user_id";
        $result = $yhteys->query($sql);
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $lahetetty = posti($email, $msg, $subject);
    }
    if ($lahetetty) {
        $message = translate('review_success');
        $success = "light";
    } else if ($lahetetty == null) {
        $message = translate('review_updated_again');
        $success = "light";
    } else {
        $message = translate('save_failed');
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
