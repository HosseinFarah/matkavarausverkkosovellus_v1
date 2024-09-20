<?php
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

$kentat = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration', 'tourImage', 'locations');
$kentat_suomi = array('Matkan nimi', 'Matkan otsikko', 'Matkan yhteenveto', 'Matkan kuvaus', 'Matkan paikka', 'Matkan aloituspäivä', 'Matkan ryhmäkoko', 'Matkan hinta', 'Matkan paikkoja', 'Matkan kesto', 'Matkan kuva', 'Matkan kohteet');
$pakolliset = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration', 'tourImage', 'locations');


include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include 'header.php';
// include 'tour_new_validation.php';


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

    [$errors, $values] = validointi($kentat);
    extract($values);


debuggeri($errors);
if (empty($errors)) {
    $created = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tours (name, title, summary, description, location, startDate, groupSize, price, places, duration, tourImage, locations, created) VALUES ('$name', '$title', '$summary', '$description', '$location', '$startDate', '$groupSize', '$price', '$places', '$duration', '$tourImage', '$locations', '$created')";
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

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan nimi:</span>
                                <input pattern="<?= pattern('name'); ?>" type="text" id="name" name="name" class="form-control <?= is_invalid('name'); ?>"
                                    title="Matkan nimi"
                                    value="<?= arvo("name"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['name'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan otsikko:</span>
                                <input pattern="<?= pattern('title'); ?>" type="text" id="title" name="title" class="form-control <?= is_invalid('title'); ?>"
                                    title="Matkan otsikko"
                                    value="<?= arvo("title"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['title'] ?? ""; ?>
                                </div>

                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan yhteenveto:</span>
                                <textarea pattern="<?= pattern('summary'); ?>" id="summary" name="summary" class="form-control <?= is_invalid('summary'); ?>"
                                    title="Matkan yhteenveto"
                                    required><?= arvo("summary"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['summary'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kuvaus:</span>
                                <textarea pattern="<?= pattern('description'); ?>" id="description" name="description" class="form-control <?= is_invalid('description'); ?>"
                                    title="Matkan kuvaus"
                                    required><?= arvo("description"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['description'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan paikka:</span>
                                <input pattern="<?= pattern('location'); ?>" type="text" id="location" name="location" class="form-control <?= is_invalid('location'); ?>"
                                    title="Matkan paikka"
                                    value="<?= arvo("location"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['location'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan aloituspäivä:</span>
                                <input pattern="<?= pattern('startDate'); ?>" type="date" id="startDate" name="startDate" class="form-control <?= is_invalid('startDate'); ?>"
                                    title="Matkan aloituspäivä"
                                    value="<?= arvo("startDate"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['startDate'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan ryhmäkoko:</span>
                                <input pattern="<?= pattern('groupSize'); ?>" type="number" id="groupSize" name="groupSize" class="form-control <?= is_invalid('groupSize'); ?>"
                                    title="Matkan ryhmäkoko"
                                    value="<?= arvo("groupSize"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['groupSize'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan hinta:</span>
                                <input pattern="<?= pattern('price'); ?>" type="number" id="price" name="price" class="form-control <?= is_invalid('price'); ?>"
                                    title="Matkan hinta"
                                    value="<?= arvo("price"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['price'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan paikkoja:</span>
                                <input pattern="<?= pattern('places'); ?>" type="number" id="places" name="places" class="form-control <?= is_invalid('places'); ?>"
                                    title="Matkan paikkoja"
                                    value="<?= arvo("places"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['places'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kesto:</span>
                                <input pattern="<?= pattern('duration'); ?>" type="number" id="duration" name="duration" class="form-control <?= is_invalid('duration'); ?>"
                                    title="Matkan kesto"
                                    value="<?= arvo("duration"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['duration'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kuva:</span>
                                <input pattern="<?= pattern('tourImage'); ?>" type="file" id="tourImage" name="tourImage" class="form-control <?= is_invalid('tourImage'); ?>"
                                    title="Matkan kuva"
                                    value="<?= arvo("tourImage"); ?>" required />
                                <div class="invalid-feedback">
                                    <?= $errors['tourImage'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kohteet:</span>
                                <textarea pattern="<?= pattern('locations'); ?>" id="locations" name="locations" class="form-control <?= is_invalid('locations'); ?>"
                                    title="Matkan kohteet"
                                    required><?= arvo("locations"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['locations'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-5 text-end">
                                <button type="submit" name="painike" class="btn btn-primary">Lisää</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='tours.php'">Peruuta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php include 'footer.html'; ?>
        </div>
    </body>

    </html>
<?php
} else {
    include '404.html';
}
?>