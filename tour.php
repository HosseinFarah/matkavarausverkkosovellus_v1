<?php
ob_start();
// Check if the form is submitted
$kentat = array('review', 'rating');
$kentat_suomi = array('Arvostelu', 'Arvosana');
$pakolliset = array('review', 'rating');
include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
require_once 'lang.php';
$title = translate('tour_info');
include 'header.php';
$pk =  $map_box;
include 'posti.php'; // Ensure this file is available and correctly included
$js = "script.js";
$message = "";

// Check if the form is submitted
if (isset($_POST['reviewBtn']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $tour_id = intval($_GET['id']);
    $review = $_POST["review"] ?? "";
    $kentta_1 = "review";
    if (in_array($kentta_1, $pakolliset) and empty($review)) {
        $errors[$kentta_1] = translate('review_required');
    } else {
        if (isset($patterns[$kentta_1]) and !preg_match($patterns[$kentta_1], $review)) {
            $errors[$kentta_1] = translate('review_invalid');
        } else {
            $review = $yhteys->real_escape_string(strip_tags(trim($review)));
        }
    }
    $rating = $_POST["rating"] ?? "";
    $kentta_2 = "rating";
    if (in_array($kentta_2, $pakolliset) and empty($rating)) {
        $errors[$kentta_2] = translate('rating_required');
    } else {
        if (isset($patterns[$kentta_2]) and !preg_match($patterns[$kentta_2], $rating)) {
            $errors[$kentta_2] = translate('rating_invalid');
        } else {
            $rating = $yhteys->real_escape_string(strip_tags(trim($rating)));
        }
    }
    include 'tour_review_tarkastus.php';
}

//  Check if 'id' is set in the URL and sanitize it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    header('Location: index.php');
    exit;
}

// SQL query to fetch the tour details
$sql = "SELECT * FROM `tours` WHERE `id` = $id";
$result = my_query($sql);

// Ensure the query was successful and data is retrieved
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sql2 = "SELECT * FROM translations WHERE language = '$_SESSION[lang]' AND tour_id = $id";
    $result2 = my_query($sql2);
    $row2 = $result2->fetch_assoc();
    $name = getTranslation($id, 'name', $_SESSION['lang']);
    $title = getTranslation($id, 'title', $_SESSION['lang']);
    $summary = getTranslation($id, 'summary', $_SESSION['lang']);
    $description = getTranslation($id, 'description', $_SESSION['lang']);
    $location = htmlspecialchars($row['location']);
    $startDate = htmlspecialchars($row['startDate']);
    $groupSize = htmlspecialchars($row['groupSize']);
    $price = htmlspecialchars($row['price']);
    $places = htmlspecialchars($row['places']);
    $duration = htmlspecialchars($row['duration']);
    $tourImage = htmlspecialchars($row['tourImage']);
    $locations = $row['locations'];

    $sql_free = "SELECT * FROM reservations WHERE tour_id = $id";
    $result_free = my_query($sql_free);
    $vapaa = $groupSize - $result_free->num_rows;
} else {
    echo "Tour not found.";
    exit;
}
?>

<head>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css' rel='stylesheet' />
    <title><?= htmlspecialchars($title) ?></title>

</head>

<body>
    <div class="content">
        <div class="container my-5">
            <div class="row">
                <?php
                // show update tour button for admin
                if ($loggedIn == 'admin') {
                    echo "<div class='col-md-12 text-end'><a href='tour_edit.php?id=$id' class='btn btn-warning'><i class='fas fa-edit fs-5 text-light'></i> ".translate('update_tour')."</a></div>";
                }
                ?>
                <!-- end -->
                <div id="ilmoitukset" class="col-md-12 alert alert-<?= $success; ?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
                    <p><?= $message; ?></p>
                </div>
                <div class="col-md-6">
                    <a href="index.php" class="fs-3"><i class="fas fa-home text-warning mb-1"></i> <?= translate('main_page') ?></a>
                    <div class="shadow mb-2 mt-1 rounded" id='map' style='width: 100%; height: 500px;'></div>
                    <img src="profiilikuvat/tours/<?= htmlspecialchars($tourImage) ?>" alt="<?= htmlspecialchars($name) ?>" class="img-fluid rounded">
                    <!-- show tour guide for this tour Start-->
                    <hr>
                    <h2 class="badge text-bg-success fs-5 mt-1 display-1"><i class="fas fa-user-tie text-light fs-3"></i> <?= translate('tour_guides') ?></h2>
                    <?php
                    $guide_sql = "SELECT * FROM tours_guides LEFT JOIN users ON tours_guides.guide_id = users.id WHERE tour_id = $id";
                    $guide_result = my_query($guide_sql);
                    if ($guide_result && $guide_result->num_rows > 0) {
                        while ($guide_row = $guide_result->fetch_assoc()) {
                            $guide_id = $guide_row['guide_id'];
                            $guide_firstname = $guide_row['firstname'];
                            $guide_lastname = $guide_row['lastname'];
                            $guide_image = $guide_row['image'];
                            $guide_email = $guide_row['email'];
                    ?>
                            <div class="row d-flex align-items-center mt-1 bg-secondary rounded w-75 shine-effect">
                                <div class='col-md-3 text-end'>
                                    <img src='profiilikuvat/users/<?= $guide_image ?>' alt='<?= $guide_firstname ?>' class='img-fluid rounded-circle' style='width: 50px; height: 50px;'>
                                </div>
                                <div class='col-md-9'>
                                    <h5 class='text-light'><i class='fas fa-user-tie text-info fs-6'> </i> <?= $guide_firstname . " " . $guide_lastname ?></h5>
                                    <p class='text-light'><i class='fas fa-envelope text-info fs-6'> </i> <?= $guide_email ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <!-- show tour guide for this tour End-->
                    <hr>
                </div>
                <div class=" col-md-6">
                    <h1 class="badge text-bg-success fs-5"><?= htmlspecialchars($name) ?></h1>
                    <h2><?= htmlspecialchars($title) ?></h2>
                    <p><strong class="text-danger"><?= translate('summary') ?>:</strong><br><?= htmlspecialchars($summary) ?></p>
                    <p><strong class="text-danger"><?= translate('description') ?>:</strong> <?= nl2br(htmlspecialchars(str_replace('-', "\n-", $description))) ?></p>

                </div>
                <div class="row mt-5">
                    <div class="col-md-6">
                        <p class="badge text-bg-success fs-6 "><strong> <?= translate('location') ?>:</strong> <?= htmlspecialchars($location) ?></p>
                        <p><strong class="text-danger"><i class="fas fa-stopwatch text-danger fs-5"></i> <?= translate('duration') ?>:</strong> <?= htmlspecialchars($duration) ?> hours</p>
                        <p><strong class="text-danger"><i class="fas fa-map-marker-alt text-danger fs-5"></i> <?= translate('tour_places') ?>:</strong> <?= htmlspecialchars($places) ?></p>
                        <p><strong class="text-danger"><i class="fas fa-users text-danger fs-5"></i> <?= translate('max_participants') ?>:</strong> <span class="badge text-bg-warning fs-6"><?= htmlspecialchars($groupSize) ?></span></p>

                    </div>
                    <div class="col-md-6">
                        <p><strong class="text-danger"><i class="fas fa-money-check-alt text-danger fs-5"></i> <?= translate('price') ?>:</strong> <?= htmlspecialchars($price) ?> €</p>
                        <p><strong class="text-danger"><i class="fas fa-shuttle-van text-danger fs-5"></i> <?= translate('tour_start_date') ?>:</strong> <?= htmlspecialchars($startDate) ?></p>
                    </div>
                </div>

                <hr>
                <!-- tour-slider -->
                <div class="row d-flex align-items-center justify-content-center mb-3 ">
                    <div id="carouselExampleInterval" class="carousel slide " data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $sql = "SELECT * FROM `tours` WHERE `id` = $id";
                            $result = my_query($sql);
                            $row = $result->fetch_assoc();
                            $slider = explode(',', $row['images']);
                            foreach ($slider as $key => $image) {
                                $active = $key == 0 ? 'active' : '';
                                echo "<div class='carousel-item shadow $active' data-bs-interval='2000'>
                                    <img src='profiilikuvat/tours/$image' class='d-block w-100' alt='$image'>
                                    </div>";
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <!-- tour-slider -->
                <hr>
                <!-- show all reviews for this tour join left for geting user firstname with table reviews and tours -->
                <h2 class="badge text-bg-danger text-light fs-3"><?= translate('reviews') ?></h2>
                <div class="row flex-nowrap overflow-auto">
                    <?php
                    $tour_id = intval($_GET['id']);
                    $sql = "SELECT * FROM `reviews` LEFT JOIN `users` ON `reviews`.`user_id` = `users`.`id` WHERE `tour_id` = $tour_id";
                    $result = my_query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $rating = $row['rating'];
                            $comment = $row['review'];
                            $user_firstname = $row['firstname'];
                    ?>
                            <div class="card mb-3 col-md-4 m-2">
                                <div class="card-body">
                                    <img src="profiilikuvat/users/<?= $row['image'] ?>" alt="<?= $user_firstname ?>" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                                    <h5 class="card-title"><?= $user_firstname ?></h5>

                                    <p class="card-text"><?php for ($i = 1; $i <= 5; $i++) {
                                                                if ($i <= $rating) {
                                                                    echo "<i class='fas fa-star text-success'></i>";
                                                                } else {
                                                                    echo "<i class='fas fa-star text-secondary'></i>";
                                                                }
                                                            } ?></p>
                                    <p class="card-text"><?= $comment ?></p>

                                </div>
                            </div>
                    <?php

                        }
                    } else {
                        echo "<p class='badge text-bg-danger fs-6'>".translate('no_reviews')."</p>";
                    }
                    ?>
                </div>

                <hr>
                <div class="row mt-1 mb-1">
                    <div class="col-md-6"
                        <?php
                        // Check if the user is logged in
                        if (isset($_SESSION['user_id'])) {
                            $userId = $_SESSION['user_id'];
                            $sql = "SELECT * FROM `reservations` WHERE `tour_id` = $id AND `user_id` = $userId";
                            $result = my_query($sql);
                            // Check if the user has already reserved this tour -start
                            if ($result && $result->num_rows > 0) {
                                $num_rows = $result->fetch_assoc();
                                echo "<p class='badge text-bg-success fs-6 text-start'>".translate('already_reserved')."</p>";
                                echo "<p class='text-start'><strong>".translate('reservation_number').":</strong> <span class='badge text-bg-primary fs-6'>" . $num_rows['reservation_id'] . "</span></p>
                                <p class='text-start'><strong>".translate('date').":</strong> <span class='badge text-bg-primary fs-6'> " . $num_rows['created'] . "</span></p>";
                                // user can add new review for this tour with review textarea and rating value between 1-5
                        ?>
                        </div>
                    </div>
                    <hr>
                    <h3>Arvostele kierros</h3>
                    <!-- check if user does not have a review for this tour -->
                    <?php
                                $sql = "SELECT * FROM `reviews` WHERE `tour_id` = $id AND `user_id` = $userId";
                                $result = my_query($sql);
                                if ($result && $result->num_rows == 0) {
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" class="mb-3 needs-validation" novalidate>
                                    <div class="form-group">
                                        <label for="review"><?= translate('review_tour') ?></label>
                                        <textarea pattern="<?= pattern('review'); ?>" id="review" name="review" class="form-control <?= is_invalid('review'); ?>" title="review" required autofocus><?= arvo("review"); ?></textarea>
                                        <div class="invalid-feedback">
                                            <?= $errors['review'] ?? ""; ?>
                                        </div>
                                    </div>
                                    <!-- arvo sana 1-5 combo box -->
                                    <div class="form-group">
                                        <label for="rating" class="form-label"><?= translate('rating') ?></label>
                                        <div class="input-group has-validation">
                                            <select class="form-select <?= is_invalid('rating'); ?>" name="rating" id="rating" required>
                                                <?php
                                                $ratings = array("", "1", "2", "3", "4", "5");
                                                foreach ($ratings as $rating_option) {
                                                    $selected = ($rating_option == $rating) ? 'selected' : '';
                                                    echo "<option value='$rating_option' $selected>$rating_option</option>";
                                                }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                <?= $errors['rating'] ?? ""; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="reviewBtn"><?= translate('send') ?></button>
                                </form>
                            </div>
                        </div>
                    <?php
                                } else {
                                    // If the user has already reviewed this tour, display the review
                                    $row = $result->fetch_assoc();
                                    $review = htmlspecialchars($row['review']);
                                    $rating = htmlspecialchars($row['rating']);
                                    $created = htmlspecialchars($row['created_at']);
                    ?>
                        <!-- If the user has already reviewed this tour, display the review -->
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="text-secondary fs-5"><?= translate('review_sent') . $created; ?></h1>
                                <!-- Button trigger modal for updating the review -->
                                <button type="button" class="btn btn-warning w-50 mt-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <?= translate('show_review') ?>
                                </button>

                                <!-- Updating the review with Modal -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= translate('your_review') ?></h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" class="mb-3 needs-validation" novalidate>
                                                    <div class="form-group">
                                                        <label for="review"><?= translate('review_tour') ?></label>
                                                        <textarea pattern="<?= pattern('review'); ?>" id="review" name="review" class="form-control <?= is_invalid('review'); ?>" title="review" required autofocus><?= $review; ?></textarea>
                                                        <div class="invalid-feedback">
                                                            <?= $errors['review'] ?? ""; ?>
                                                        </div>
                                                    </div>
                                                    <!-- arvo sana 1-5 combo box -->
                                                    <div class="form-group">
                                                        <label for="rating" class="form-label"><?= translate('rating') ?></label>
                                                        <div class="input-group has-validation">
                                                            <select class="form-select <?= is_invalid('rating'); ?>" name="rating" id="rating" required>
                                                                <?php
                                                                $ratings = array("", "1", "2", "3", "4", "5");
                                                                foreach ($ratings as $rating_option) {
                                                                    $selected = ($rating_option == $rating) ? 'selected' : '';
                                                                    echo "<option value='$rating_option' $selected>$rating_option</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                <?= $errors['rating'] ?? ""; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary" name="reviewBtn"><?= translate('update') ?></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php
                                }
                        ?>
                        </div>

                    <?php
                            }
                            // END-  if user has not reserved this tour, display the reservation button
                            else {

                                if ($vapaa > 0 && strtotime($startDate) > strtotime(date('Y-m-d'))) {
                                    echo "<p><strong class='text-danger'><i class='fas fa-users text-danger fs-5'></i> ".translate('free_places')."</strong> <span class='badge text-bg-warning fs-6'>" . $vapaa . "</span></p>";
                                    echo "<a href='reserve.php?id=" . $id . "' class='btn btn-success mt-1'><i class='fas fa-cart-plus fs-5 text-light'></i> ".translate('book_now')." </a>";
                                } else {
                                    if ($vapaa == 0) {
                                        echo "<p><strong class='text-danger'><i class='fas fa-users text-danger fs-5'></i> ".translate('free_places')."</strong> <span class='badge text-bg-warning fs-6'>" . $vapaa . "</span></p>";
                                        echo "<a href='#' class='btn btn-danger mt-1'><i class='fas fa-cart-plus fs-5 text-light'></i> ".translate('all_places_reserved')."</a>";
                                    } else {
                                        echo "<p><strong class='text-danger'><i class='fas fa-users text-danger fs-5'></i> ".translate('reservations_ended')."</strong></p>";
                                    }
                                }
                            }
                        }
                        // If the user is not logged in, display a login button
                        else {
                    ?>
                    <p class='badge text-bg-danger fs-6 m-1'><?= translate('login_to_book') ?></p>
                    <a href='/login.php' class='btn btn-primary m-1'><i class='fas fa-sign-in-alt fs-5 text-light'></i> <?= translate('login') ?></a>
                <?php
                        }
                        if ($loggedIn == 'admin'|| $loggedIn == 'guide') {
                ?>
                    <!-- registered user in this tour -->
                    <hr>
                    <h2 class="badge text-bg-danger text-light fs-3 mt-3"><?= translate('registered_users') ?></h2>
                    <div class="row flex-nowrap overflow-auto">

                        <?php
                            $sql = "SELECT * FROM `reservations` LEFT JOIN `users` ON `reservations`.`user_id` = `users`.`id` WHERE `tour_id` = $id";
                            $result = my_query($sql);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $user_firstname = $row['firstname'];
                        ?>
                                <div class="card mb-3 col-md-6 m-2">
                                    <div class="card-body">
                                        <img src="profiilikuvat/users/<?= $row['image'] ?>" alt="<?= $user_firstname ?>" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                                        <h5 class="card-title"><?= $user_firstname . $row['lastname'] ?></h5>
                                        <h5 class="card-text"><?= $row['email'] ?></h5>
                                        <h5 class="card-text"><?= $row['mobilenumber'] ?></h5>
                                    </div>

                                </div>

                    <?php
                                }
                            }
                        }
                    ?>
                    </div>
                </div>

                <?php
                include 'footer.php';
                ?>
            </div>

            <script>
                mapboxgl.accessToken = "<?= $pk ?>";
                var map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    scrollZoom: false
                });
                var locationsString = '<?php echo $locations; ?>';
                var locations = locationsString.split(',').map(function(item) {
                    var parts = item.split('-');
                    return [parseFloat(parts[0]), parseFloat(parts[1])];
                });
                var bounds = new mapboxgl.LngLatBounds();
                locations.forEach(function(location) {
                    new mapboxgl.Marker({
                            color: 'red',
                            draggable: false,
                            scale: 1,
                        })
                        .setLngLat(location)
                        .addTo(map);
                    bounds.extend(location);
                });

                map.fitBounds(bounds, {
                    padding: 50,
                    duration: 2000
                });
            </script>
</body>