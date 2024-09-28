<?php
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kaikki käyttäjät";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include 'header.php';

if ($loggedIn == 'admin') {
?>

    <body>
        <div class="content">
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-12">
                        <!-- <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a> -->
                        <h1 class="text-center">Kaikki käyttäjät</h1>
                        <p class="text-center">Täältä löydät kaikki käyttäjät</p>
                    </div>
                </div>
            </div>
            <div class="container overflow-x-auto">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <a href="user_new.php" class="btn btn-primary"><i class="fas fa-plus"></i> Lisää uusi käyttäjä</a>
                    </div>
                    <form method="post" action="users_search.php">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="search" class="form-control" placeholder="Hae tilausta">
                                <p class="fs-6 mt-2"><i class="fas fa-info text-warning mb-3"></i> Hae käyttäjää sähköpostin, etunimen, sukunimen, kaupungin tai käyttäjäryhmän perusteella</p>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary m-2" name="reservationBtn">Hae</button>
                                <!-- make button for clear search -->
                                <button type="submit" class="btn btn-danger m-2" name="clearBtn" <?php if (empty($search)) echo "disabled"; ?>>Tyhjennä</button>
                            </div>
                        </div>
                        <?php
                        $records_per_page = 10;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $records_per_page;
                        $sql = "SELECT users.id,users.email,users.firstname,users.lastname,users.city,roles.name,users.image FROM users LEFT JOIN roles on users.role=roles.id ORDER BY id LIMIT $records_per_page OFFSET $offset";
                        $result = my_query($sql);
                        $total_records_sql = "SELECT COUNT(*) FROM users";
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


                        <table class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Sähköposti</th>
                                    <th>Etunimi</th>
                                    <th>Sukunimi</th>
                                    <th>Kaupunki</th>
                                    <th>Käyttäjäryhmä</th>
                                    <th>Kuva</th>
                                    <th>Asetukset</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Loop through the paginated records and display them
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['firstname'] ?></td>
                                        <td><?= $row['lastname'] ?></td>
                                        <td><?= $row['city'] ?></td>
                                        <td><?= $row['name'] ?></td>
                                        <td><img src='http://<?= $PALVELIN ?>/profiilikuvat/users/<?= $row['image'] ?>' alt='kuva' class='rounded' style='width: 100px;'></td>
                                        <td>
                                            <div class='row'>
                                                <a href='user_edit.php?id=<?= $row['id'] ?>'><i class='text-primary fs-4 fas fa-edit'></i></a>
                                            </div>
                                            <div class='row mt-3'>
                                                <a href='user_pois.php?id=<?= $row['id'] ?>' onclick="return confirm('Are you sure you want to proceed?');" name='pois'>
                                                    <i class='text-danger fs-4 fas fa-trash-alt'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
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
        include 'footer.php';
        ?>
    </body>

    </html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>