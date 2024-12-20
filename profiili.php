<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include_once 'lang.php';
$title = translate('profile');

include "header.php";
$css = 'site.css';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $yhteys->query($query);
    if (!$result) die("Tietokantayhteys ei toimi: " . mysqli_error($yhteys));
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $name = $row['firstname'] . " " . $row['lastname'];
    $phone = $row['mobilenumber'];
    $kaupunki = $row['city'];
    $katuosoite = $row['address'];
    $postinumero = $row['postcode'];
    $google_id = $row['google_id'];
    if ($row['image'] == NULL || $row['image'] == "") {
        $photo = "default.jpg";
    } else {
        $photo = $row['image'];
    }
}
?>

<!-- profile page -->
<?php
if ($loggedIn === 'user' || isset($_SESSION['user'])) { ?>

    <body>
        <!-- Main Content -->
        <div class="row">
            <div class="col-md-3 mt-2">
                <div class="card">
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <img src="<?= "http://$PALVELIN/profiilikuvat/users/" . $photo ?>" style="width: 300px ;" class="card-img-top rounded" alt="<?= $photo ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= translate('full_name') ?>: <?= $name ?></h5>
                        <p class="card-text"><?= translate('email') ?>: <?= $email ?></p>
                        <p class="card-text"><?= translate('phone_number') ?>: <?= $phone ?></p>
                        <p class="card-text"><?= translate('city') ?>: <?= $kaupunki ?></p>
                        <p class="card-text"><?= translate('street_address') ?>: <?= $katuosoite ?></p>
                        <p class="card-text"><?= translate('postal_code') ?>: <?= $postinumero ?></p>
                        <a href="muokkaaprofiilia.php?id=<?= $user_id ?>" class="btn btn-primary"><?= translate('edit_profile') ?></a>
                        <a href="poistu.php" class="btn btn-primary"><?= translate('logout') ?></a>
                        <div>
                            <?php
                                if($google_id != NULL){
                                    echo "<p class='badge bg-secondary text-light fs-6 mt-1'>".translate('google_logged_in')."</p>";
                                }
                                else{
                                    echo "<a href='update_password.php?id=". $user_id ."' class='btn btn-warning mt-1'>". translate('update_password')."</a>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mt-2">
                <h2 class="badge text-bg-danger text-light fs-3"><?= translate('reviews') ?></h2>
                <div class="row flex-nowrap overflow-x-scroll">
                    <!-- show this users all reviews for tours -->
                    <?php
                    $sql = "SELECT * FROM `reviews` WHERE `user_id` = $user_id";
                    $result = my_query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $tour_id = $row['tour_id'];
                            $rating = $row['rating'];
                            $comment = $row['review'];
                            $sql = "SELECT * FROM `tours` WHERE `id` = $tour_id";
                            $result2 = my_query($sql);
                            if ($result2 && $result2->num_rows > 0) {
                                $row2 = $result2->fetch_assoc();
                                $tour_id = $row2['id'];
                                $tour_name = $row2['name'];
                            }
                    ?>
                            <div class="card mb-3 col-md-4 m-2">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $tour_name ?></h5>
                                    <!-- replace arvosana with <i class="fas fa-star"></i> stars are 5 starts and based on rating value starts color change to green -->
                                    <p class="card-text"><?php for ($i = 1; $i <= 5; $i++) {
                                                                if ($i <= $rating) {
                                                                    echo "<i class='fas fa-star text-success'></i>";
                                                                } else {
                                                                    echo "<i class='far fa-star'></i>";
                                                                }
                                                            } ?></p>


                                    <p class="card-text"><?= $comment ?></p>
                                    <a href="tour.php?id=<?= $tour_id ?>" class="btn btn-primary"><?= translate('show_tour') ?></a>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p class='badge text-bg-danger fs-6'>" . translate('no_reviews') . "</p>";
                    }
                    ?>

                </div>
            </div>

        </div>
        <hr>
        <!-- show this users all reservations -->
        <div class="col-md-12 mt-3 mb-3">
            <h2 class="badge text-bg-danger text-light fs-3"><?= translate('reservations') ?></h2>
            <div class='row flex-nowrap overflow-x-scroll'>

                <?php
                $sql = "SELECT * FROM `reservations` WHERE `user_id` = $user_id";
                $result = my_query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $tour_id = $row['tour_id'];
                        $reservation_id = $row['reservation_id'];
                        $sql = "SELECT * FROM `tours` WHERE `id` = $tour_id";
                        $result2 = my_query($sql);
                        if ($result2 && $result2->num_rows > 0) {
                            $row2 = $result2->fetch_assoc();
                            $tour_id = $row2['id'];
                            $tour_name = $row2['name'];
                            $tour_image = $row2['tourImage'];
                            $tour_summary = getTranslation($tour_id, 'summary',$_SESSION['lang']);
                            $tour_price = $row2['price'];
                            $tour_start_date = $row2['startDate'];
                            $tour_start_date = date("d.m.Y", strtotime($tour_start_date));
                            $tour_image = "http://$PALVELIN/profiilikuvat/tours/" . $tour_image;
                ?>

                            <div class="col-md-3 card m-3">
                                <div class="row g-0">
                                    <img src="<?= $tour_image ?>" class="card-img-top rounded img-fluid" alt="<?= $tour_image ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $tour_name ?></h5>
                                        <p class="card-text"><small class="text-body-secondary">Hinta: <?= $tour_price ?> €</small></p>
                                        <p class="card-text"><small class="text-body-secondary">Päivämäärä: <?= $tour_start_date ?></small></p>
                                        <a href="tour.php?id=<?= $tour_id ?>" class="btn btn-success"><?= translate('show_tour') ?></a>
                                        <!-- // print reservation invoice based on user_id and tour_id and reservation_id -->
                                        <a target="_blank" href="invoice.php?id=<?= $reservation_id ?>&tour_id=<?= $tour_id ?>&user_id=<?= $user_id ?>" class="btn btn-warning"><i class="fas fa-file-invoice"></i> <?= translate('show_invoice') ?></a>
                                        
                                    </div>
                                </div>
                            </div>
                <?php
                        }
                    }
                } else {
                    echo "<p class='badge text-bg-danger fs-6'>".translate('no_reservations')."</p>";
                }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <hr>
        <?php
        include "footer.php";
        ?>

    <?php
} elseif ($loggedIn === 'admin' || $loggedIn === 'guide') {
    ?>
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <img src="<?= "http://$PALVELIN/profiilikuvat/users/" . $photo ?>" style="width: 300px ;" class="card-img-top rounded" alt="<?= $photo ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= translate('full_name') ?>: <?= $name ?></h5>
                            <p class="card-text"><?= translate('email') ?>: <?= $email ?></p>
                            <p class="card-text"><?= translate('phone_number') ?>: <?= $phone ?></p>
                            <p class="card-text"><?= translate('city') ?>: <?= $kaupunki ?></p>
                            <p class="card-text"><?= translate('street_address') ?>: <?= $katuosoite ?></p>
                            <p class="card-text"><?= translate('postal_code') ?>: <?= $postinumero ?></p>
                            <a href="muokkaaprofiilia.php?id=<?= $user_id ?>" class="btn btn-primary"><?= translate('edit_profile') ?></a>
                            <a href="update_password.php?id=<?= $user_id ?>" class="btn btn-primary"><?= translate('update_password') ?></a>
                            <a href="poistu.php" class="btn btn-primary mt-1"><?= translate('logout') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
}
    ?>

