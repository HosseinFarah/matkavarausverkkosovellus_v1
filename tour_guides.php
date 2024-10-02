<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$PALVELIN = $_SERVER['HTTP_HOST'];
include_once 'lang.php';
$title = translate('all_guides');
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';

if ($loggedIn === 'admin') {
?>
<div class="container mt-3">
    <h1 class="badge text-bg-danger fs-3">Matkaoppaat</h1>
    <!-- create a new tour guide for available tours -->
    <div class="mt-3">
        <a href="tour_guide_new.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Lisää matkaopas</a>
    </div>
    <!-- display all tour guides -->
    <table class='table table-striped table-hover table-sm mt-3'>
        <thead class='thead-dark'>
            <tr>
                <th>Matkaopas</th>
                <th>Kierros</th>
                <th>Alkaa</th>
                <th>Asetukset</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM tours_guides  LEFT JOIN users ON tours_guides.guide_id = users.id LEFT JOIN tours ON tours_guides.tour_id = tours.id";
            $result = my_query($sql);
            while ($row = $result->fetch_assoc()) {
                $guide_id = $row['guide_id'];
                $tour_id = $row['tour_id'];
                $tour_name = $row['name'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $start_date = $row['startDate'];
                $id = $row['tour_guide_id'];
                echo "<tr>";
                echo "<td>$firstname $lastname</td>";
                echo "<td>$tour_name</td>";
                echo "<td>$start_date</td>";
                // delete with confirmation
                echo "<td><a href='tour_guide_pois.php?id=$id' class='btn btn-danger btn-sm' onclick='return confirm(\"Haluatko varmasti poistaa matkaoppaan?\")'><i class='fas fa-trash text-light fs-5'></i></a></td>";
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
</div>
<?php
} else {
    header("Location: index.php");
    exit;
}