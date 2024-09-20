<?php
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
$title = 'Profiili';
$css = 'profiili.css';
include "header.php";




if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $yhteys->query($query);
    if (!$result) die("Tietokantayhteys ei toimi: " . mysqli_error($yhteys));
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $name = $row['firstname'] . " " . $row['lastname'];
    $phone = $row['mobilenumber'];
    $photo = $row['image'];
    $kaupunki = $row['city'];
    $katuosoite = $row['address'];
    $postinumero = $row['postcode'];
}
?>

<!-- profile page -->

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <img src="<?= "http://$PALVELIN/profiilikuvat/" . $photo ?>" style="width: 300px ;" class="card-img-top rounded" alt="<?= $photo ?>">
                </div>
                <div class="card-body">
                    <h5 class="card-title">Nimi: <?= $name ?></h5>
                    <p class="card-text">Sähköposti: <?= $email ?></p>
                    <p class="card-text">Puhelinnumero: <?= $phone ?></p>
                    <p class="card-text">kaupunki: <?= $kaupunki ?></p>
                    <p class="card-text">katuosoite: <?= $katuosoite ?></p>
                    <p class="card-text">postinumero: <?= $postinumero ?></p>
                    <a href="muokkaaprofiilia.php?id=<?= $user_id ?>" class="btn btn-primary">Muokkaa profiilia</a>
                    <a href="poistu.php" class="btn btn-primary">Kirjaudu ulos</a>
                </div>
            </div>
        </div>
        <!-- admin access part -->
        <?php if ($loggedIn === 'admin') { ?>
            <div class="col-md-8">
                <h2>Admin-Part</h2>
                <a href="kayttajat.php" class="btn btn-primary">active</a>
                <a href="tours.php" class="btn btn-primary">Kaikki matkat</a>
                <a href="users.php" class="btn btn-primary">Kaikki käyttäjät</a>
                <a href="tour_new.php" class="btn btn-primary">Lisää uusi matka</a>
                <a href="user_new.php" class="btn btn-primary">Lisää uusi käyttäjä</a>



            </div>
        <?php } ?>

    </div>
</div>


<?php include "footer.html"; ?>