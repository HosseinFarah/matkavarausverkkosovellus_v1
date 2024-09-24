<?php
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';

$kentat = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration', 'tourImage', 'locations');
$kentat_suomi = array('Matkan nimi', 'Matkan otsikko', 'Matkan yhteenveto', 'Matkan kuvaus', 'Matkan paikka', 'Matkan aloituspäivä', 'Matkan ryhmäkoko', 'Matkan hinta', 'Matkan paikkoja', 'Matkan kesto', 'Matkan kuva', 'Matkan kohteet');
$pakolliset = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration', 'tourImage', 'locations');

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";


if (isset($_POST['painike'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $startDate = $_POST['startDate'];
    $groupSize = $_POST['groupSize'];
    $price = $_POST['price'];
    $places = $_POST['places'];
    $duration = $_POST['duration'];
    $locations = $_POST['locations'];
    $tourImage = $_FILES['tourImage']['name'];
    $target_dir = "profiilikuvat/tours/";
    $target_file = $target_dir . basename($_FILES["tourImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES['tourImage']['tmp_name'], $target_dir . $tourImage);
    }

    $sql = "INSERT INTO tours (name, title, summary, description, location, startDate, groupSize, price, places, duration, tourImage, locations) VALUES ('$name', '$title', '$summary', '$description', '$location', '$startDate', '$groupSize', '$price', '$places', '$duration', '$tourImage', '$locations')";
    $result = my_query($sql);
    if ($result) {
        $success = "success";
        $message = "Matka lisätty onnistuneesti!";
        header("Location: tours.php");
    } else {
        $success = "danger";
        $message = "Matkan lisääminen epäonnistui!";
    }
}
if ($loggedIn == 'admin') {
?>

    <body>
        <div class="content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <?php if (isset($message)) { ?>
                            <div class="alert alert-<?= $success ?>" role="alert">
                                <?= $message ?>
                            </div>
                        <?php } ?>
                        <h1 class="text-center">Lisää uusi matka</h1>
                        <form method="post" enctype="multipart/form-data" needs-validation" enctype="multipart/form-data" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Matkan nimi</label>
                                <input type="text" class="form-control" id="name" name="name" pattern="<?= pattern('name'); ?>" <?= is_invalid('name'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['name'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Matkan otsikko</label>
                                <input type="text" class="form-control" id="title" name="title" pattern="<?= pattern('title'); ?>" <?= is_invalid('title'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['title'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="summary" class="form-label">Matkan yhteenveto</label>
                                <textarea class="form-control" id="summary" name="summary" pattern="<?= pattern('summary'); ?>" <?= is_invalid('summary'); ?> required></textarea>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['summary'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Matkan kuvaus</label>
                                <textarea class="form-control" id="description" name="description" pattern="<?= pattern('description'); ?>" <?= is_invalid('description'); ?> required></textarea>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['description'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Matkan paikka</label>
                                <input type="text" class="form-control" id="location" name="location" pattern="<?= pattern('location'); ?>" <?= is_invalid('location'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['location'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Matkan aloituspäivä</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" pattern="<?= pattern('startDate'); ?>" <?= is_invalid('startDate'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['startDate'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="groupSize" class="form-label">Matkan ryhmäkoko</label>
                                <input type="number" class="form-control" id="groupSize" name="groupSize" pattern="<?= pattern('groupSize'); ?>" <?= is_invalid('groupSize'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['groupSize'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Matkan hinta</label>
                                <input type="number" class="form-control" id="price" name="price" pattern="<?= pattern('price'); ?>" <?= is_invalid('price'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['price'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="places" class="form-label">Matkan paikkoja</label>
                                <input type="number" class="form-control" id="places" name="places" pattern="<?= pattern('places'); ?>" <?= is_invalid('places'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['places'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="duration" class="form-label">Matkan kesto</label>
                                <input type="number" class="form-control" id="duration" name="duration" pattern="<?= pattern('duration'); ?>" <?= is_invalid('duration'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['duration'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="tourImage" class="form-label">Matkan kuva</label>
                                <input type="file" class="form-control" id="tourImage" name="tourImage" pattern="<?= pattern('tourImage'); ?>" <?= is_invalid('tourImage'); ?> required>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['tourImage'] ?? ""; ?>
                            </div>
                            <div class="mb-3">
                                <label for="locations" class="form-label">Matkan kohteet</label>
                                <textarea class="form-control" id="locations" name="locations" pattern="<?= pattern('locations'); ?>" <?= is_invalid('locations'); ?> required></textarea>
                            </div>
                            <div class="invalid-feedback">
                                <?= $errors['locations'] ?? ""; ?>
                            </div>
                            <div class="mb-5 text-end">
                                <button type="submit" name="painike" class="btn btn-primary">Lisää</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='tours.php'">Peruuta</button>

                            </div>

                        </form>


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
}
