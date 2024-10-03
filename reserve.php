<?php

include_once 'lang.php';
$title = translate('book_tour');
include 'header.php';
$tourId = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : 0;
$user_Id = intval($_SESSION['user_id']);


// Check if the user is logged in
if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
} else {
    // Fetch user details
    $userQuery = "SELECT firstname,lastname, mobilenumber,email FROM users WHERE id = $user_Id";
    $userResult = my_query($userQuery);
    if ($userResult->num_rows > 0) {
        $row = $userResult->fetch_assoc();
        $userName = htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
        $userMobile = htmlspecialchars($row['mobilenumber']);
        $userEmail = htmlspecialchars($row['email']);
    }
    // Check if the user has already reserved this tour
    $sql = "SELECT * FROM reservations WHERE user_id = $user_Id AND tour_id = $tourId";
    $result = my_query($sql);
    if ($result->num_rows > 0) {
        $num_rows = $result->fetch_assoc();
?>
        <div class='container my-5'>
            <div class='row'>
                <div class='col-md-12'>
                    <h2 class='text-center'><?= translate('already_reserved') ?>: </h2>
                    <hr>
                    <h2 class='text-center'><?= translate('reservation_number') ?>: <?= $num_rows['reservation_id'] ?></h2>
                    <p class='text-center'><strong><?= translate('date') ?>:</strong> <span class="badge text-bg-success fs-5"> <?= $num_rows['created'] ?></span></p>

                </div>
            </div>
        </div>";
        <?php
    } else {
        // Get the tour information
        $sql = "SELECT * FROM tours WHERE id = $tourId";
        $result = my_query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = htmlspecialchars($row['name']);
            $title = htmlspecialchars($row['title']);
            $summary = htmlspecialchars($row['summary']);
            $location = htmlspecialchars($row['location']);
            $startDate = htmlspecialchars($row['startDate']);
            $price = htmlspecialchars($row['price']);
            $duration = htmlspecialchars($row['duration']);
            $tourImage = htmlspecialchars($row['tourImage']);
        ?>

            <div class='container'>
                <div class='row'>
                    <div class='col-md-12'>
                        <h2 class='text-center'><?= translate('book_tour') ?></h2>
                    </div>
                </div>
            </div>
            <div class='container'>
                <!-- back to home page -->
                <div class='row'>
                    <div class='col-md-12'>
                        <a href="index.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> <?= translate('main_page') ?></a>
                    </div>
                    <div class='row d-flex alig-item-center'>
                        <div class='col-md-5'>
                            <div class='card mb-3'><img src="profiilikuvat/tours/<?= $tourImage ?>" class='card-img-top' alt='$name'>
                                <div class='card-body'>
                                    <h5 class='card-title'><?= $title ?></h5>
                                    <p class='card-text'><small class='text-muted fs-5'><i class="fas fa-map-marker-alt"></i> <?= translate('tour_location') ?>: <strong class="badge text-bg-secondary fs-6"> <?= $location ?></strong> </small></p>
                                    <p class='card-text'><small class='text-muted fs-5'><i class="fas fa-clock"></i> <?= translate('tour_start_date') ?>: <strong class="badge text-bg-secondary fs-6"> <?= $startDate ?> </strong></small></p>
                                    <p class='card-text'><small class='text-muted fs-5'><i class="fas fa-money-check-alt"></i> <?= translate('price') ?>: <strong class="badge text-bg-secondary fs-6"><?= $price ?> </strong></small></p>
                                    <p class='card-text'><small class='text-muted fs-5'><i class="fas fa-hourglass-half"></i> <?= translate('duration') ?>: <strong class="badge text-bg-secondary fs-6"><?= $duration ?></strong> </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='container'>
                    <div class='row'>
                        <div class='col-md-12'>
                            <!-- Reserve button with stripe checkout session -->
                            <form action='stripe.php' method='POST'>
                                <input type='hidden' name='tourId' value='<?= $tourId ?>'>
                                <input type='hidden' name='price' value='<?= $price ?>'>
                                <input type='hidden' name='title' value='<?= $title ?>'>
                                <input type='hidden' name='name' value='<?= $name ?>'>
                                <input type="hidden" name="user_Id" value="<?= $user_Id ?>">
                                <input type="hidden" name="user_name" value="<?= $userName ?>">
                                <input type="hidden" name="user_mobile" value="<?= $userMobile ?>">
                                <input type="hidden" name="user_email" value="<?= $userEmail ?>">
                                <script src='https://checkout.stripe.com/checkout.js'
                                    class='stripe-button'
                                    data-key='<?= $stripe_pk ?>'
                                    data-amount='<?= $price * 100 ?>'
                                    data-name='<?= $userName ?>'
                                    data-description='<?= $title ?>'
                                    data-email='<?= $userEmail ?>'
                                    data-currency='eur'
                                    data-locale='auto'>
                                </script>

                            </form>
                        </div>
                    </div>
                </div>
    <?php
        } else {
            echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>".translate('tour_not_found')."</h2></div></div></div>";
        }
    }
}
    ?>