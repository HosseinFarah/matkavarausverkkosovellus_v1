<?php
include "header.php";
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "kaikki tilaukset";
$loggedIn = secure_page();

$sql = "SELECT * FROM reservations";
$result = my_query($sql);
if ($loggedIn == 'admin') {
?>
    <div class="content">
        <div class="container mt-5 mb-5">
            <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a>
            <!-- search for a reservation by fullname or tour name or reservation_id or price or created date -->
            <!-- go to reservation_search.php -->
            <form method="post" action="reservation.php">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" name="search" class="form-control" placeholder="Hae tilausta">
                        <p class="fs-6 mt-2"><i class="fas fa-info text-warning mb-3"></i> Hae tilausta asikkaan nimen, matkan nimen, tilauksen koodin, hinnan tai tilaus päivämäärän perusteella</p>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary m-2" name="reservationBtn">Hae</button>
                        <!-- make button for clear search -->
                        <button type="submit" class="btn btn-danger m-2" name="clearBtn" <?php if (empty($search)) echo "disabled"; ?>>Tyhjennä</button>
                    </div>
                </div>
        </div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-12">
                    <h1 class="text-center">Kaikki tilaukset</h1>
                    <p class="text-center">Täältä löydät kaikki tilaukset</p>
                </div>
            </div>
        </div>
        <div class="container overflow-x-auto">
            <div class="row">
                <?php
                $records_per_page = 5;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $records_per_page;
                $sql = "SELECT * FROM reservations ORDER BY id LIMIT $records_per_page OFFSET $offset";
                $result = my_query($sql);
                $total_records_sql = "SELECT COUNT(*) FROM reservations";
                $total_records_result = my_query($total_records_sql);
                $total_records_row = $total_records_result->fetch_row();
                $total_records = $total_records_row[0];
                $total_pages = ceil($total_records / $records_per_page);
                $start_page = $page - 2;
                $end_page = $page + 2;
                if ($start_page < 1) {
                    $start_page = 1;
                    $end_page = 5;
                }
                if ($end_page > $total_pages) {
                    $end_page = $total_pages;
                    $start_page = $total_pages - 4;
                }
                if ($start_page < 1) {
                    $start_page = 1;
                }

                ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Asiakas</th>
                            <th scope="col">Matka</th>
                            <th scope="col">Tilauksen koodi</th>
                            <th scope="col">Hinta</th>
                            <th scope="col">Tilaus päivämäärä</th>
                            <th scope="col">Poista</th>
                        </tr>
                    </thead>
                    <?php
                    // join left on reservations and users and tours
                    $sql = "SELECT reservations.id , reservations.user_id as users,reservations.tour_id as tours, reservations.reservation_id , reservations.price, reservations.created FROM reservations LEFT JOIN users ON reservations.user_id = users.id LEFT JOIN tours ON reservations.tour_id = tours.id";

                    $result = my_query($sql);
                    // reppalce users with user full name and tours with tour name
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['users'];
                        $tour_id = $row['tours'];
                        $sql = "SELECT * FROM users WHERE id = $user_id";
                        $result2 = my_query($sql);
                        $row2 = mysqli_fetch_assoc($result2);
                        $user_name = $row2['firstname'] . " " . $row2['lastname'];
                        $sql = "SELECT * FROM tours WHERE id = $tour_id";
                        $result3 = my_query($sql);
                        $row3 = mysqli_fetch_assoc($result3);
                        $tour_name = $row3['name'];
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $user_name . "</td>";
                        echo "<td>" . $tour_name . "</td>";
                        echo "<td>" . $row['reservation_id'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['created'] . "</td>";
                        // delete button with confirmation 
                        echo "<td><a href='reservation_pois.php?id=" . $row['id'] . "' onclick='return confirm(\"Haluatko varmasti poistaa tämän tilauksen?\")'><i class='fas fa-trash-alt text-danger'></i></a></td>";
                        echo "</tr>";
                    }

                    ?>
                </table>

                <!-- Pagination -->
                <nav aria-label="...">
                    <ul class="pagination">

                        <!-- Previous button: disable if on the first page -->
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= ($page > 1) ? '?page=' . ($page - 1) : '#' ?>" tabindex="-1">Previous</a>
                        </li>

                        <?php
                        // Show page numbers
                        for ($i = 1; $i <= $total_pages; $i++) {
                        ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php
                        }
                        ?>

                        <!-- Next button: disable if on the last page -->
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= ($page < $total_pages) ? '?page=' . ($page + 1) : '#' ?>">Next</a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
<?php
include "footer.php";
}
else {
    header("Location: index.php");
}
?>