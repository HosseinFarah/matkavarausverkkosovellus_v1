<?php
ob_start();
$PALVELIN = $_SERVER['HTTP_HOST'];
include_once 'lang.php';
$title = translate('all_reservations');
include "header.php";
$loggedIn = secure_page();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = trim($_POST['search']) ?? '';

    $sql = "SELECT reservations.id, CONCAT(users.firstname, ' ', users.lastname) as customer, tours.name as tour, reservations.reservation_id, reservations.price, reservations.created 
    FROM reservations 
    LEFT JOIN users ON reservations.user_id = users.id 
    LEFT JOIN tours ON reservations.tour_id = tours.id 
    WHERE users.firstname LIKE ? OR users.lastname LIKE ? OR tours.name LIKE ? OR reservations.reservation_id LIKE ? OR reservations.price LIKE ? OR reservations.created LIKE ?";

    $stmt = $yhteys->prepare($sql);
    $searchParam = "%{$search}%";
    $stmt->bind_param("ssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

if (isset($_POST['clearBtn'])) {
    $sql = "SELECT reservations.id, CONCAT(users.firstname, ' ', users.lastname) as customer, tours.name as tour, reservations.reservation_id, reservations.price, reservations.created 
    FROM reservations 
    LEFT JOIN users ON reservations.user_id = users.id 
    LEFT JOIN tours ON reservations.tour_id = tours.id";
    $result = my_query($sql);
    header("Location: reserved.php");
}

if ($loggedIn == 'admin') {
?>

    <body>
        <div class="content">
            <div class="container mt-5 mb-5">
                <!-- <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a>
 -->
                <!-- search for a reservation by fullname or tour name or reservation_id or price or created date -->
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="search" class="form-control" placeholder="<?= translate('search') ?>">
                            <p class="fs-6 mt-2"><i class="fas fa-info text-warning mb-3"></i> <?= translate('search_placeholder') ?></p>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary m-2" name="reservationBtn"><?= translate('search') ?></button>
                            <!-- make button for clear search -->
                            <button type="submit" class="btn btn-danger m-2" name="clearBtn" <?php if (empty($search)) echo "disabled"; ?>><?php echo translate('clear'); ?></button>

                        </div>
                    </div>
                </form>

                <div class="container">
                    <div class="row mb-5">
                        <table class="table table-striped table-hover mb-5">
                            <thead>
                                <tr>
                                    <th scope="col"><?= translate('id') ?></th>
                                    <th scope="col"><?= translate('customer') ?></th>
                                    <th scope="col"><?= translate('trip') ?></th>
                                    <th scope="col"><?= translate('order_code') ?></th>
                                    <th scope="col"><?= translate('price') ?></th>
                                    <th scope="col"><?= translate('order_date') ?></th>
                                    <th scope="col"><?= translate('delete') ?></th>
                                </tr>
                            </thead>
                            <?php
                            if (isset($result)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['customer'] . "</td>";
                                    echo "<td>" . $row['tour'] . "</td>";
                                    echo "<td>" . $row['reservation_id'] . "</td>";
                                    echo "<td>" . $row['price'] . "</td>";
                                    echo "<td>" . $row['created'] . "</td>";
                                    echo "<td><a href='reservation_pois.php?id=" . $row['id'] . "' onclick='return confirm(\"" . translate('confirm_delete') . "\")'><i class='fas fa-trash-alt text-danger'></i></a></td>";
                                    echo "</tr>";
                                    echo "</thead>";
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            include "footer.php";
            ?>
        </div>
    </body>

<?php
    ob_end_flush();
} else {
    header("Location: index.php");
    exit();
}
