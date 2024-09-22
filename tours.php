<?php
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
$sql = "SELECT * FROM tours";
$result = my_query($sql);

include 'header.php';

if ($loggedIn == 'admin') {
?>
    <!-- all tours for admin -->

    <body>
        <div class="content">
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-12">
                        <!-- back to main page -->
                        <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a>
                        <h1 class="text-center">Kaikki matkat</h1>
                        <p class="text-center">Täältä löydät kaikki matkat</p>
                    </div>
                </div>
            </div>
            <div class="container overflow-x-auto">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <a href="tour_new.php" class="btn btn-primary"><i class="fas fa-plus"></i> Lisää uusi matka</a>
                    </div>
                    <!-- Start Pagination Part-1 -->
                    <?php

                    $records_per_page = 3;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $records_per_page;
                    $sql = "SELECT * FROM tours ORDER BY id LIMIT $records_per_page OFFSET $offset";
                    $result = my_query($sql);
                    $total_records_sql = "SELECT COUNT(*) FROM tours";
                    $total_records_result = my_query($total_records_sql);
                    $total_records_row = mysqli_fetch_array($total_records_result);
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
                    <table class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Matkan nimi</th>
                                <th>Matkan paikka</th>
                                <th>Matkan kesto</th>
                                <th>Matkan hinta</th>
                                <th>Matkan aloituspäivä</th>
                                <th>Matkan ryhmäkoko</th>
                                <th>Matkan paikkoja</th>
                                <th>Matkan kuva</th>
                                <th>Asetukset</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            foreach ($row as $key => $value) {
                                $row[$key] = htmlspecialchars($value);
                            }
                            echo
                            "<tbody> 
                        <tr> <td>$row[id]</td>
                            <td>$row[name]</td>  
                            <td>$row[location]</td> 
                            <td>$row[duration]</td> 
                            <td>$row[price]</td> 
                            <td>$row[startDate]</td> 
                            <td>$row[groupSize]</td> 
                            <td>$row[places]</td> 
                            <td><img src='profiilikuvat/tours/$row[tourImage]' alt='$row[name]' class='img-fluid'></td> 
                            <td>
                                <div class='row'>
                                <a href='tour_edit.php?id=$row[id]'><i class='text-primary fs-4 fas fa-edit'></i></a>
                                </div>
                                <div class='row mt-3'>
                                <a href='tour_pois.php?id=$row[id]' onclick=\"return confirm('Are you sure you want to proceed?');\" name='pois'><i class='text-danger fs-4 fas fa-trash-alt'></i></a>
                                </div>
                            </td>  
                        </tr> 
                        </tbody>";
                        }
                        ?>
                    </table>
                    <!-- Start Pagination Part-2 -->
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
        <?php include 'footer.php'; ?>
        </div>
    </body>

    </html>
<?php
} else {
    include '404.html';
    include 'footer.php';
}
?>