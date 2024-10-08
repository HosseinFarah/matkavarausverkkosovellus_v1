<?php
ob_start();

require_once 'lang.php';
$title = translate('edit_user');
include "header.php";
$css = 'profiili.css';

$kentat = ['firstname', 'lastname', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber', 'is_active', 'role'];
$kentat_suomi = ['etunimi', 'sukunimi', 'salasana', 'salasana', 'katuosoite', 'postinumero', 'kaupunki', 'puhelinnumero', 'aktiivinen', 'rooli'];
$pakolliset = ['firstname', 'lastname', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$js = "script.js";
$kentat_tiedosto = ['image'];

include "virheilmoitukset.php";
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";

// Fetch user data
$id = intval($_GET['id']);
if ($id) {
    $query = "SELECT * FROM users WHERE users.id=$id";
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
        $is_active = $row['is_active'];
        $role = $_POST['role'] ?? $row['role'];
    }
} else {
    header("Location: index.php");
    exit;
}

include 'user_edit_tarkastus.php';

if ($loggedIn == 'admin') {
?>

    <!-- Update profile page -->

    <body>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4">
                    <img src="<?= "http://$PALVELIN/profiilikuvat/users/" . htmlspecialchars($image, ENT_QUOTES) ?>" class="img-thumbnail" alt="Profiilikuva" />
                    <form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
                        <fieldset>
                            <legend><?= translate('edit_user')?></legend>
                            <?php if (isset($message)): ?>
                                <div class='alert alert-<?= $success ?>'><?= $message ?></div>
                            <?php endif; ?>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('firstname')?></span>
                                <input pattern="<?= pattern('firstname'); ?>" type="text" id="firstname" name="firstname" class="form-control <?= is_invalid('firstname'); ?>"
                                    value="<?= htmlspecialchars($firstname ?? $_POST['firstname'] ?? '', ENT_QUOTES) ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['firstname'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('lastname')?></span>
                                <input type="text" id="lastname" name="lastname" class="form-control <?= is_invalid('lastname'); ?>"
                                    value="<?= htmlspecialchars($lastname ?? $_POST['lastname'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('lastname'); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['lastname'] ?? ""; ?>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('street_address')?></span>
                                <input type="text" id="address" name="address" value="<?= htmlspecialchars($address ?? $_POST['address'] ?? '', ENT_QUOTES) ?>"
                                    pattern="<?= pattern('address'); ?>" required autofocus
                                    class="form-control <?= is_invalid('address'); ?>"
                                <div class="invalid-feedback">
                                    <?= $errors['address'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('postal_code')?></span>
                                <input type="text" id="postcode" name="postcode" class="form-control <?= is_invalid('postcode'); ?>"
                                    value="<?= htmlspecialchars($postcode ?? $_POST['postcode'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('postcode'); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['postcode'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('city')?></span>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    class="form-control <?= is_invalid('city'); ?>"
                                    list="kaupungit"
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
                                <span class="input-group-text"><?= translate('phone_number')?></span>
                                <input type="text" id="mobilenumber" name="mobilenumber" class="form-control <?= is_invalid('mobilenumber'); ?>"
                                    value="<?= htmlspecialchars($mobilenumber ?? $_POST['mobilenumber'] ?? '', ENT_QUOTES) ?>" pattern="<?= pattern('mobilenumber'); ?>" required autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['mobilenumber'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('password')?></span>
                                <input type="password" id="password" name="password" class="form-control <?= is_invalid('password'); ?>"
                                    pattern="<?= pattern('password'); ?>" autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['password'] ?? ""; ?>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><?= translate('confirm_password')?></span>
                                <input type="password" id="password2" name="password2" class="form-control <?= is_invalid('password2'); ?>"
                                    pattern="<?= pattern('password2'); ?>" autofocus />
                                <div class="invalid-feedback">
                                    <?= $errors['password2'] ?? ""; ?>
                                </div>
                            </div>


                            <!-- Image upload -->
                            <div class="input-group mb-3">
                                <label for="image" class="form-label"><?= translate('profile_image')?></label>
                                <input type="file" name="image" id="image" class="form-control <?= is_invalid('image'); ?>" />
                                <div class="invalid-feedback">
                                    <?= isset($errors['image']) ? $errors['image'] : ""; ?>
                                </div>
                                <!-- Preview the uploaded image -->
                            </div>

                            <!-- is_active -->
                            <div class="input-group mb-3">
                                <label for="is_active" class="form-label"><?= translate('active')?></label>
                                <input type="checkbox" name="is_active" id="is_active" <?= $is_active ? 'checked' : ''; ?> />
                            </div>

                            <!-- Role -->
                            <div class="input-group mb-3">
                                <label for="role" class="form-label"><?= translate('role')?></label>
                                <select name="role" id="role" class="form-select"
                                    value="<?= htmlspecialchars($role ?? $_POST['role'] ?? '', ENT_QUOTES) ?>">
                                    <?php
                                    $query = "SELECT * FROM roles";
                                    $result = my_query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ((isset($_POST['role']) && $row['id'] == $_POST['role']) || (!isset($_POST['role']) && $role == $row['id'])) ? 'selected' : '';
                                        echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- submit -->
                            <div>
                                <button type="submit" name="painike" class="btn btn-primary"><?= translate('update')?></button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='users.php'"><?= translate('cancel')?></button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
<?php
} else {
    header("Location: index.php");
    exit;
}
