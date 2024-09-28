<?php
ob_start();
include "header.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = 'Päivitä salasanasi';

$kentat = ['password', 'password2', 'currentPassword'];
$kentat_suomi = ['salasana', 'salasana','nykyinen salasana'];
$pakolliset = ['password', 'password2', 'currentPassword'];
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
        

    }
} else {
    header("Location: index.php");
    exit;
}

include 'update_Password_tarkistus.php';

?>

<!-- Update profile page -->

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
                    <fieldset>
                        <legend><?= $title ?></legend>
                        <?php if (isset($message)): ?>
                            <div class='alert alert-<?= $success ?>'><?= $message ?></div>
                        <?php endif; ?>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Nykyinen salasana:</span>
                            <input type="password" id="currentPassword" name="currentPassword" class="form-control <?= is_invalid('currentPassword'); ?>"
                             autofocus />
                            <div class="invalid-feedback">
                                <?= $errors['currentPassword'] ?? ""; ?>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Salasana:</span>
                            <input type="password" id="password" name="password" class="form-control <?= is_invalid('password'); ?>"
                                pattern="<?= pattern('password'); ?>" autofocus />
                            <div class="invalid-feedback">
                                <?= $errors['password'] ?? ""; ?>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Vahvista salasana:</span>
                            <input type="password" id="password2" name="password2" class="form-control <?= is_invalid('password2'); ?>"
                                pattern="<?= pattern('password2'); ?>" autofocus />
                            <div class="invalid-feedback">
                                <?= $errors['password2'] ?? ""; ?>
                            </div>
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