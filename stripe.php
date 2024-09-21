<?php

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