<?php
ob_start();
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

include 'header.php';

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";

$id = $_GET['id'];
$sql = "SELECT * FROM tours WHERE id = $id";
$result = my_query($sql);
if (isset($_POST['updateBtn'])) {
    $target_dir = "profiilikuvat/tours/";
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

    // Fetch the row from the database
    $result = mysqli_query($yhteys, "SELECT * FROM tours WHERE id = $id");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            // Handle the error: the query didn't return any rows
            die("No rows returned.");
        }
    } else {
        // Handle the error: the query failed
        die("Query failed.");
    }

    if (is_uploaded_file($_FILES['tourImage']['tmp_name'])) {
        // New image uploaded
        $tourImage = $_FILES['tourImage']['name'];
        $target_file = $target_dir . basename($tourImage);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");

        // Check if the file type is allowed
        if (in_array($imageFileType, $extensions_arr)) {
            move_uploaded_file($_FILES['tourImage']['tmp_name'], $target_file);
        } else {
            // Handle invalid file type
            die("Invalid file type.");
        }
    } else {
        // No new image, use the current one
        $tourImage = $row['tourImage'];
    }

    // upload multiple images uploaded
    if (isset($_FILES['images'])) {
        $images = "";
        $target_dir = "profiilikuvat/tours/";
        $image_names = is_array($_FILES['images']['name']) ? $_FILES['images']['name'] : array($_FILES['images']['name']);

        $total = count($image_names);
        for ($i = 0; $i < $total; $i++) {
            $image = $image_names[$i];
            $target_file = $target_dir . basename($image);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $extensions_arr = array("jpg", "jpeg", "png", "gif");

            // Check if the file type is allowed
            if (in_array($imageFileType, $extensions_arr)) {
                move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file);
                $images .= $image . ",";
            } else {
                // Handle invalid file type
                die("Invalid file type.");
            }
        }
        $images = rtrim($images, ",");
    } else {
        // No new image, use the current one
        $images = $row['images'];
    }


    $query = "UPDATE tours SET name = '$name', title = '$title', summary = '$summary', description = '$description', location = '$location', startDate = '$startDate', groupSize = '$groupSize', price = '$price', places = '$places', duration = '$duration', tourImage = '$tourImage' ,locations= '$locations', images='$images' WHERE id = $id";
    $result = my_query($query);
    if ($result) {
        $success = "success";
        $message = "Matka päivitetty onnistuneesti!";
        header("Location: tours.php");
        exit;
    } else {
        $success = "danger";
        $message = "Matkan päivitys epäonnistui!";
    }
}

if ($loggedIn == 'admin') {
?>

    <!-- selected tour for update -->

    <body>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">Muokkaa matkaa</h1>
                        <p class="text-center">Täältä voit muokata matkaa</p>
                        <?php if (isset($message)) { ?>
                            <div class="alert alert-<?= $success ?>" role="alert">
                                <?= $message ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <form method="post" enctype="multipart/form-data">
                            <?php
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                foreach ($row as $key => $value) {
                                    $row[$key] = $value;
                                }
                            ?>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Matkan nimi</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Matkan otsikko</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?= $row['title'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="summary" class="form-label">Matkan yhteenveto</label>
                                    <textarea class="form-control" id="summary" name="summary" required><?= $row['summary'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Matkan kuvaus</label>
                                    <textarea class="form-control" id="description" name="description" required><?= $row['description'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Matkan paikka</label>
                                    <input type="text" class="form-control" id="location" name="location" value="<?= $row['location'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Matkan aloituspäivä</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" value="<?= $row['startDate'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="groupSize" class="form-label">Matkan ryhmäkoko</label>
                                    <input type="number" class="form-control" id="groupSize" name="groupSize" value="<?= $row['groupSize'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Matkan hinta</label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= $row['price'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="places" class="form-label">Matkan paikkoja</label>
                                    <input type="number" class="form-control" id="places" name="places" value="<?= $row['places'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Matkan kesto</label>
                                    <input type="text" class="form-control" id="duration" name="duration" value="<?= $row['duration'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tourImage" class="form-label">Matkan kuva</label>
                                    <input type="file" class="form-control" id="tourImage" name="tourImage">
                                    <img id="imagePreview" src="profiilikuvat/tours/<?= $row['tourImage'] ?>" alt="<?= $row['name'] ?>" class="img-fluid mt-2">
                                </div>
                                <!-- make upload multiple images max 5 images -->
                                <div class="mb-3">
                                    <label for="images" class="form-label">Matkan kuvat</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple>
                                    <img id="imagePreview" src="profiilikuvat/tours/<?= $row['images'] ?>" alt="<?= $row['images'] ?>" class="img-fluid mt-2">
                                </div>
                                <?php
                                if (empty($row['images'])) {
                                    $row['images'] = "default.jpg";
                                }
                                $image_names = explode(",", $row['images']);
                                foreach ($image_names as $image_name) {
                                    echo '<img src="profiilikuvat/tours/' . $image_name . '" alt="' . $row['name'] . '" class="img-fluid mt-2" width="100">';
                                }
                                ?>

                                <div class="mb-3">
                                    <label for="locations" class="form-label">Matkan kohteet</label>
                                    <input type="text" class="form-control" id="locations" name="locations" value="<?= $row['locations'] ?>" required>
                                </div>

                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" type="submit" name="updateBtn" class="btn btn-primary">Tallenna muutokset</button>
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Peruuta</button>
                            <?php
                            }
                            ?>
                        </form>

                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>

    </html>
<?php
    ob_end_flush();
} else {
    include '404.html';
    include 'footer.php';
}
