<?php
ob_start();
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Uusi matka";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';

$kentat = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration', 'tourImage', 'locations');
$kentat_suomi = array('Matkan nimi', 'Matkan otsikko', 'Matkan yhteenveto', 'Matkan kuvaus', 'Matkan paikka', 'Matkan aloituspäivä', 'Matkan ryhmäkoko', 'Matkan hinta', 'Matkan paikkoja', 'Matkan kesto', 'Matkan kuva', 'Matkan kohteet');
$pakolliset = array('name', 'title', 'summary', 'description', 'location', 'startDate', 'groupSize', 'price', 'places', 'duration',  'locations');

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";

if (isset($_POST['painike'])) {
    // Initialize variables
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $groupSize = $_POST['groupSize'] ?? '';
    $price = $_POST['price'] ?? '';
    $places = $_POST['places'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $locations = $_POST['locations'] ?? '';
    // include 'tour_new_validation.php';

    // File upload handling
    $tourImage = $_FILES['tourImage']['name'];
    $target_dir = "profiilikuvat/tours/";
    $target_file = $target_dir . basename($_FILES["tourImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES['tourImage']['tmp_name'], $target_dir . $tourImage);
    }

    $name = $_POST["name"] ?? "";
    $kentta_1 = "name";
    if (in_array($kentta_1, $pakolliset) and empty($title)) {
        $errors[$kentta_1] = "Etuni on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_1]) and !preg_match($patterns[$kentta_1], $name)) {
            $errors[$kentta_1] = "Etunimi ei ole kelvollinen!";
        } else {
            $name = $yhteys->real_escape_string(strip_tags(trim($name)));
        }
    }

    $title = $_POST["title"] ?? "";
    $kentta_2 = "title";
    if (in_array($kentta_2, $pakolliset) and empty($title)) {
        $errors[$kentta_2] = "Otsikko on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_2]) and !preg_match($patterns[$kentta_2], $title)) {
            $errors[$kentta_2] = "Otsikko ei ole kelvollinen!";
        } else {
            $title = $yhteys->real_escape_string(strip_tags(trim($title)));
        }
    }

    $summary = $_POST["summary"] ?? "";
    $kentta_3 = "summary";
    if (in_array($kentta_3, $pakolliset) and empty($summary)) {
        $errors[$kentta_3] = "Yhteenveto on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_3]) and !preg_match($patterns[$kentta_3], $summary)) {
            $errors[$kentta_3] = "Yhteenveto ei ole kelvollinen!";
        } else {
            $summary = $yhteys->real_escape_string(strip_tags(($summary)));
        }
    }

    $description = $_POST["description"] ?? "";
    $kentta_4 = "description";
    if (in_array($kentta_4, $pakolliset) and empty($description)) {
        $errors[$kentta_4] = "Kuvaus on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_4]) and !preg_match($patterns[$kentta_4], $description)) {
            $errors[$kentta_4] = "Kuvaus ei ole kelvollinen!";
        } else {
            $description = $yhteys->real_escape_string(strip_tags(($description)));
        }
    }

    $location = $_POST["location"] ?? "";
    $kentta_5 = "location";
    if (in_array($kentta_5, $pakolliset) and empty($location)) {
        $errors[$kentta_5] = "Paikka on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_5]) and !preg_match($patterns[$kentta_5], $location)) {
            $errors[$kentta_5] = "Paikka ei ole kelvollinen!";
        } else {
            $location = $yhteys->real_escape_string(strip_tags(trim($location)));
        }
    }



    $startDate = $_POST["startDate"] ?? "";
    $kentta_6 = "startDate";
    if (in_array($kentta_6, $pakolliset) and empty($startDate)) {
        $errors[$kentta_6] = "Aloituspäivä on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_6]) and !preg_match($patterns[$kentta_6], $startDate)) {
            $errors[$kentta_6] = "Aloituspäivä ei ole kelvollinen!";
        } else {
            $startDate = $yhteys->real_escape_string(strip_tags(trim($startDate)));
        }
    }

    $groupSize = $_POST["groupSize"] ?? "";
    $kentta_7 = "groupSize";
    if (in_array($kentta_7, $pakolliset) and empty($groupSize)) {
        $errors[$kentta_7] = "Ryhmäkoko on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_7]) and !preg_match($patterns[$kentta_7], $groupSize)) {
            $errors[$kentta_7] = "Ryhmäkoko ei ole kelvollinen!";
        } else {
            $groupSize = $yhteys->real_escape_string(strip_tags(trim($groupSize)));
        }
    }

    $price = $_POST["price"] ?? "";
    $kentta_8 = "price";
    if (in_array($kentta_8, $pakolliset) and empty($price)) {
        $errors[$kentta_8] = "Hinta on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_8]) and !preg_match($patterns[$kentta_8], $price)) {
            $errors[$kentta_8] = "Hinta ei ole kelvollinen!";
        } else {
            $price = $yhteys->real_escape_string(strip_tags(trim($price)));
        }
    }

    $places = $_POST["places"] ?? "";
    $kentta_9 = "places";
    if (in_array($kentta_9, $pakolliset) and empty($places)) {
        $errors[$kentta_9] = "Paikkoja on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_9]) and !preg_match($patterns[$kentta_9], $places)) {
            $errors[$kentta_9] = "Paikkoja ei ole kelvollinen!";
        } else {
            $places = $yhteys->real_escape_string(strip_tags(trim($places)));
        }
    }

    $duration = $_POST["duration"] ?? "";
    $kentta_10 = "duration";
    if (in_array($kentta_10, $pakolliset) and empty($duration)) {
        $errors[$kentta_10] = "Kesto on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_10]) and !preg_match($patterns[$kentta_10], $duration)) {
            $errors[$kentta_10] = "Kesto ei ole kelvollinen!";
        } else {
            $duration = $yhteys->real_escape_string(strip_tags(trim($duration)));
        }
    }



    $locations = $_POST["locations"] ?? "";
    $kentta_12 = "locations";
    if (in_array($kentta_12, $pakolliset) and empty($locations)) {
        $errors[$kentta_12] = "Kohteet on pakollinen tieto!";
    } else {
        if (isset($patterns[$kentta_12]) and !preg_match($patterns[$kentta_12], $locations)) {
            $errors[$kentta_12] = "Kohteet ei ole kelvollinen!";
        } else {
            $locations = $yhteys->real_escape_string(strip_tags(trim($locations)));
        }
    }




    // // Validation function call
    //  [$errors, $values] = validointi($kentat);
    //  extract($values);

    if (empty($errors)) {
        $sql = "INSERT INTO tours (name, title, summary, description, location, startDate, groupSize, price, places, duration, tourImage, locations) VALUES ('$name', '$title', '$summary', '$description', '$location', '$startDate', '$groupSize', '$price', '$places', '$duration', '$tourImage', '$locations')";
        $result = my_query($sql);
        if ($result) {
            $success = "success";
            $message = "Matka lisätty onnistuneesti!";
            header("Location: tours.php");
            exit;
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
                        <form method="post" enctype="multipart/form-data" novalidate>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan nimi:</span>
                                <input pattern="<?= pattern('name'); ?>" type="text" id="name" name="name" class="form-control <?= is_invalid('name'); ?> "
                                    title="Matkan nimi"
                                    value="<?= arvo("name"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['name'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan otsikko:</span>
                                <input pattern="<?= pattern('title'); ?>" type="text" id="title" name="title" class="form-control <?= is_invalid('title'); ?> "
                                    title="Matkan otsikko"
                                    value="<?= arvo("title"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['title'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan yhteenveto:</span>
                                <textarea pattern="<?= pattern('summary'); ?>" id="summary" name="summary" class="form-control <?= is_invalid('summary'); ?>" title="Matkan yhteenveto" required autofocus><?= arvo("summary"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['summary'] ?? ""; ?>
                                </div>
                            </div>


                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kuvaus:</span>
                                <textarea pattern="<?= pattern('description'); ?>" id="description" name="description" class="form-control <?= is_invalid('description'); ?>" title="Matkan kuvaus" required autofocus><?= arvo("description"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['description'] ?? ""; ?>
                                </div>
                            </div>


                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan paikka:</span>
                                <input pattern="<?= pattern('location'); ?>" type="text" id="location" name="location" class="form-control <?= is_invalid('location'); ?> "
                                    title="Matkan paikka"
                                    value="<?= arvo("location"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['location'] ?? ""; ?>
                                </div>
                            </div>


                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan aloituspäivä:</span>
                                <input pattern="<?= pattern('startDate'); ?>" type="date" id="startDate" name="startDate" class="form-control <?= is_invalid('startDate'); ?> "
                                    title="Matkan aloituspäivä"
                                    value="<?= arvo("startDate"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['startDate'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan ryhmäkoko:</span>
                                <input pattern="<?= pattern('groupSize'); ?>" type="number" id="groupSize" name="groupSize" class="form-control <?= is_invalid('groupSize'); ?> "
                                    title="Matkan ryhmäkoko"
                                    value="<?= arvo("groupSize"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['groupSize'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan hinta:</span>
                                <input pattern="<?= pattern('price'); ?>" type="number" id="price" name="price" class="form-control <?= is_invalid('price'); ?> "
                                    title="Matkan hinta"
                                    value="<?= arvo("price"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['price'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan paikkoja:</span>
                                <input pattern="<?= pattern('places'); ?>" type="number" id="places" name="places" class="form-control <?= is_invalid('places'); ?> "
                                    title="Matkan paikkoja"
                                    value="<?= arvo("places"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['places'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kesto:</span>
                                <input pattern="<?= pattern('duration'); ?>" type="number" id="duration" name="duration" class="form-control <?= is_invalid('duration'); ?> "
                                    title="Matkan kesto"
                                    value="<?= arvo("duration"); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['duration'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kuva:</span>
                                <input type="file" id="tourImage" name="tourImage" class="form-control <?= is_invalid('tourImage'); ?> "
                                    title="Matkan kuva"
                                    required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['tourImage'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Matkan kohteet:</span>
                                <textarea pattern="<?= pattern('locations'); ?>" id="locations" name="locations" class="form-control <?= is_invalid('locations'); ?>" title="Matkan kohteet" required autofocus><?= arvo("locations"); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $errors['locations'] ?? ""; ?>
                                </div>
                            </div>


                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="painike">Tallenna</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

<?php
    include 'footer.html';
}
ob_end_flush();
?>