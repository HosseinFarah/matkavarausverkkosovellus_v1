<?php
ob_start();
include "header.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = 'Profiili';
$css = 'profiili.css';

$kentat = ['firstname', 'lastname', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$kentat_suomi = ['etunimi', 'sukunimi', 'salasana', 'salasana', 'katuosoite', 'postinumero', 'kaupunki', 'puhelinnumero',];
$pakolliset = ['firstname', 'lastname', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$js = "script.js";
$kentat_tiedosto = ['image'];

include "virheilmoitukset.php";
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";

// Fetch user data
$id = intval($_GET['id']);
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = my_query($query);
    if ($result->num_rows == 0) {
        header("Location: index.php");
        exit;
    } else {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $address = $row['address'];
        $postcode = $row['postcode'];
        $city = $row['city'];
        $mobilenumber = $row['mobilenumber'];
        $password = $row['password'];
        $image = $row['image']; // Existing image
    }
} else {
    header("Location: index.php");
    exit;
}

include 'muokkaaprofiiliatarkistus.php';

?>

<!-- Update profile page -->
    <body>
        
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4">
                    <img src="<?= "http://$PALVELIN/profiilikuvat/" . htmlspecialchars($image, ENT_QUOTES) ?>" class="img-thumbnail" alt="Profiilikuva" />
            <form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
                <fieldset>
                    <legend>Rekisteröityminen</legend>
                    <?php if (isset($message)): ?>
                        <div class='alert alert-<?= $success ?>'><?= $message ?></div>
                    <?php endif; ?>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Etunimi:</span>
                        <input pattern="<?= pattern('firstname'); ?>" type="text" id="firstname" name="firstname" class="form-control <?= is_invalid('firstname'); ?>"
                            title="Nimen tulee olla vähintään kaksi merkkiä pitkä ja saa sisältää vain kirjaimia, välilyöntejä, viivoja ja heittomerkkejä."
                            value="<?= htmlspecialchars($firstname ?? $_POST['firstname'] ?? '', ENT_QUOTES) ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['firstname'] ?? ""; ?>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Sukunimi:</span>
                        <input type="text" id="lastname" name="lastname" class="form-control <?= is_invalid('lastname'); ?>"
                            value="<?= htmlspecialchars($lastname ?? $_POST['lastname'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('lastname'); ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['lastname'] ?? ""; ?>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Katuosoite:</span>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($address ?? $_POST['address'] ?? '', ENT_QUOTES) ?>"
                            pattern="<?= pattern('address'); ?>" required autofocus
                            class="form-control <?= is_invalid('address'); ?>"
                            title="Katuosoite saa sisältää vain kirjaimia, numeroita, välilyöntejä ja viivoja." />
                        <div class="invalid-feedback">
                            <?= $errors['address'] ?? ""; ?>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Postinumero:</span>
                        <input type="text" id="postcode" name="postcode" class="form-control <?= is_invalid('postcode'); ?>"
                            value="<?= htmlspecialchars($postcode ?? $_POST['postcode'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('postcode'); ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['postcode'] ?? ""; ?>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Kaupunki:</span>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            class="form-control <?= is_invalid('city'); ?>"
                            list="kaupungit"
                            title="Kaupungin nimi saa sisältää vain kirjaimia, välilyöntejä ja viivoja."
                            value="<?= htmlspecialchars($city ?? $_POST['city'] ?? '', ENT_QUOTES) ?>"
                            pattern="<?= pattern('city'); ?>"
                            required autofocus />
                        <datalist id="kaupungit">
                            <!-- Dynamically populate city options -->
                        </datalist>
                        <div class="invalid-feedback">
                            <?= $errors['city'] ?? ""; ?>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Puhelinnumero:</span>
                        <input type="text" id="mobilenumber" name="mobilenumber" class="form-control <?= is_invalid('mobilenumber'); ?>"
                            value="<?= htmlspecialchars($mobilenumber ?? $_POST['mobilenumber'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('mobilenumber'); ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['mobilenumber'] ?? ""; ?>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Salasana:</span>
                        <input type="password" id="password" name="password" class="form-control <?= is_invalid('password'); ?>"
                            pattern="<?= pattern('password'); ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['password'] ?? ""; ?>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Vahvista salasana:</span>
                        <input type="password" id="password2" name="password2" class="form-control <?= is_invalid('password2'); ?>"
                            pattern="<?= pattern('password2'); ?>" required autofocus />
                        <div class="invalid-feedback">
                            <?= $errors['password2'] ?? ""; ?>
                        </div>
                    </div>


                    <!-- Image upload -->
                    <div class="input-group mb-3">
                        <label for="image" class="form-label">Profiilikuva:</label>
                        <input type="file" name="image" id="image" class="form-control <?= is_invalid('image'); ?>" />
                        <div class="invalid-feedback">
                            <?= isset($errors['image']) ? $errors['image'] : ""; ?>
                        </div>
                        <!-- Preview the uploaded image -->
                    </div>

                    <div>
                        <button type="submit" name="painike" class="btn btn-primary">Päivitä</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='profiili.php'"> Peruuta </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
