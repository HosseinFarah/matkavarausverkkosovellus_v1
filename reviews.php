<?php
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

$sql = "SELECT * FROM reviews";
$result = my_query($sql);

include 'header.php';

if ($loggedIn == 'admin') {
?>
    <div class="content">
        <div class="container mt-5 mb-5">
            <div class="row d-flex align-items-center">
                <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a>
                <div class="col-md-8>
                <!-- Start Pagination Part-1 -->
                <?php
                $records_per_page = 5;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $records_per_page;
                $sql = "SELECT * FROM reviews ORDER BY id LIMIT $records_per_page OFFSET $offset";
                $result = my_query($sql);
                $total_records_sql = "SELECT COUNT(*) FROM reviews";
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
                <!-- End Pagination Part-1 -->
                <h5 class=" card-title">Kaikki arvostelut</h5>
                    <!-- show all reviews with user and tour in table with delete button -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Arvostelu</th>
                                <th scope="col">Käyttäjä</th>
                                <th scope="col">Matka</th>
                                <th scope="col">Poista</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $review_id = $row['id'];
                                $review = $row['review'];
                                $user_id = $row['user_id'];
                                $tour_id = $row['tour_id'];
                                $sql = "SELECT * FROM users WHERE id = $user_id";
                                $result2 = my_query($sql);
                                $row2 = $result2->fetch_assoc();
                                $user = $row2['firstname'] . " " . $row2['lastname'];
                                $sql = "SELECT * FROM tours WHERE id = $tour_id";
                                $result3 = my_query($sql);
                                $row3 = $result3->fetch_assoc();
                                $tour = $row3['name'];
                            ?>
                                <tr>
                                    <td><?= $review ?></td>
                                    <td><?= $user ?></td>
                                    <td><?= $tour ?></td>
                                    <td><div class='row mt-3'>
                                            <a href='review_pois.php?id=<?= $review_id ?>' onclick="return confirm('Are you sure you want to proceed?');" name='pois'>
                                                <i class='text-danger fs-4 fas fa-trash-alt'></i>
                                            </a>
                                        </div></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination with Bootstrap -->
                    <nav aria-label="User pagination">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1">Previous</a>
                            </li>

                            <!-- Page Number Links (dynamically generated) -->
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <!-- End Pagination Part-2 -->
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo "<h1 class='text-center'>Ei oikeuksia</h1>";
}
include "footer.php";
?>