<?php
ob_start();
require_once('vendor/autoload.php');
include ('header.php');
\Stripe\Stripe::setApiKey($stripe_private);

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// Get the rest of the post data
$tourId = $_POST['tourId'];
$price = $_POST['price'];
$user_Id = $_SESSION['user_id'];
$title = $_POST['title'];
$name = $_POST['name'];



// Create a charge: this will charge the user's card
try {
    $charge = \Stripe\Charge::create(array(
        "amount" => $price * 100, // Amount in cents
        "currency" => "eur",
        "source" => $token,
        "description" => "Charge for tourId: $tourId"
    ));
    if ($charge->status == 'succeeded') {
        // Insert into reservations table
        $reservationId = $charge->id;
        $sql = "INSERT INTO reservations (tour_id, user_id, price,reservation_id) VALUES ('$tourId', '$user_Id', '$price','$reservationId')";
        $result = my_query($sql);
        echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>Kiitos varauksestasi</h2></div></div></div>";
        // Get the reservation id
       //send invoice to user
        $sql = "SELECT * FROM reservations WHERE reservation_id = '$reservationId'";
        $result = my_query($sql);
        $row = mysqli_fetch_assoc($result);
        $reservationId = $row['id'];
        $date = $row['created'];
        $price = $row['price'];
        $reservation_id = $row['reservation_id'];
        $sql = "SELECT * FROM users WHERE id = '$user_Id'";
        $result = my_query($sql);
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $full_name = $row['firstname'] . ' ' . $row['lastname'];
        $tour_sql = "SELECT * FROM tours WHERE id = '$tourId'";
        $tour_result = my_query($tour_sql);
        $tour_row = mysqli_fetch_assoc($tour_result);
        $subject = "Kiitos varauksestasi";
        // create html invoice and send it to user with bootstrap for $fullname, $reservation_id, $date, $price, $title
        $message = "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>Kiitos varauksestasi</h2></div></div><div class='row'><div class='col-md-12'><p>Hei $full_name,</p><p>Varauksesi on vastaanotettu. Tässä varauksesi tiedot:</p><p>Varausnumero: $reservation_id</p><p>Päivämäärä: $date</p><p>Hinta: $price €</p><p>Kohde: $title</p></div></div></div>";        
        include 'posti.php';
        posti($email, $message,$subject);
    }
} catch(\Stripe\Error\Card $e) {
    // The card has been declined
    echo $e->getMessage();
} catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
    echo $e->getMessage();
}

// You'll want to save the charge details in your database here.

// Then you can redirect back to the tour page.
header("Location: tour.php?id=$tourId");
exit;

ob_end_flush();