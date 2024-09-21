<?php
include 'header.php';
include 'posti.php'; // Ensure this file is available and correctly included
$pk =  $map_box;

// Check if 'id' is set in the URL and sanitize it
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
    $name = htmlspecialchars($row['name']);
    $title = htmlspecialchars($row['title']);
    $summary = htmlspecialchars($row['summary']);
    $description = htmlspecialchars($row['description']);
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
                <div class="col-md-6 ">
                    <a href="index.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Etusivu</a>
                    <img src="profiilikuvat/tours/<?= htmlspecialchars($tourImage) ?>" alt="<?= htmlspecialchars($name) ?>" class="img-fluid">


                    <div class="mt-5 shadow" id='map' style='width: 100%; height: 500px;'></div>
                </div>
                <div class="col-md-6">
                    <h1 class="badge text-bg-secondary"><?= htmlspecialchars($name) ?></h1>
                    <h2><?= htmlspecialchars($title) ?></h2>
                    <p><strong class="text-danger">Summary:</strong><br><?= htmlspecialchars($summary) ?></p>
                    <p><strong class="text-danger">Description:</strong> <?= nl2br(htmlspecialchars(str_replace('-', "\n-", $description))) ?></p>

                </div>
                <div class="row mt-5">
                    <div class="col-md-6">
                        <p class="badge text-bg-success fs-6 "><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
                        <p><strong class="text-danger"><i class="fas fa-stopwatch text-danger fs-5"></i> Duration:</strong> <?= htmlspecialchars($duration) ?> hours</p>
                        <p><strong class="text-danger"><i class="fas fa-map-marker-alt text-danger fs-5"></i> Places:</strong> <?= htmlspecialchars($places) ?></p>
                        <p><strong class="text-danger"><i class="fas fa-users text-danger fs-5"></i> Max group size:</strong> <span class="badge text-bg-warning fs-6"><?= htmlspecialchars($groupSize) ?></span></p>

                    </div>
                    <div class="col-md-6">
                        <p><strong class="text-danger"><i class="fas fa-money-check-alt text-danger fs-5"></i> Price:</strong> <?= htmlspecialchars($price) ?> €</p>
                        <p><strong class="text-danger"><i class="fas fa-shuttle-van text-danger fs-5"></i> Start date:</strong> <?= htmlspecialchars($startDate) ?></p>
                        <?php
                        // Check if the user is logged in
                        if (isset($_SESSION['user_id'])) {
                            $userId = $_SESSION['user_id'];
                            $sql = "SELECT * FROM `reservations` WHERE `tour_id` = $id AND `user_id` = $userId";
                            $result = my_query($sql);
                            if ($result && $result->num_rows > 0) {
                                $num_rows = $result->fetch_assoc();
                                echo "<p class='badge text-bg-success fs-6'>Olet jo varannut tämän kierroksen</p>";
                                echo "<p class='text-center'><strong>varausnumero:</strong> <span class='badge text-bg-primary fs-6'>" . $num_rows['reservation_id'] . "</span></p>
                                <p class='text-center'><strong>Päivämäärä:</strong> <span class='badge text-bg-primary fs-6'> " . $num_rows['created'] . "</span></p>";
                            } else {
                                if ($vapaa > 0) {
                                    echo "<p><strong class='text-danger'><i class='fas fa-users text-danger fs-5'></i> Vapaita paikkoja:</strong> <span class='badge text-bg-warning fs-6'>" . $vapaa . "</span></p>";
                                    echo "<a href='reserve.php?id=" . $id . "' class='btn btn-success mt-1'><i class='fas fa-cart-plus fs-5 text-light'></i> Varaa nyt </a>";
                                } else {
                                    echo "<p><strong class='text-danger'><i class='fas fa-users text-danger fs-5'></i> Vapaita paikkoja:</strong> <span class='badge text-bg-danger fs-6'>0</span></p>";
                                }
                            }
                        }
                        // If the user is not logged in, display a login button
                        else {
                            echo "<a href='login.php' class='btn btn-primary mt-1'><i class='fas fa-sign-in-alt fs-5 text-light'></i> Kirjaudu sisään</a>";
                        }


                        ?>
                    </div>


                </div>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
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

</html>