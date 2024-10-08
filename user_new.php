<?php
ob_start();
include_once 'lang.php';
$title = translate('new_user');
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
/* Huom. Tässä salasanakenttien täsmääminen tarkistetaan vain palvelimella. */
$kentat = ['firstname', 'lastname', 'email', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$kentat_suomi = ['etunimi', 'sukunimi', 'sähköpostiosoite', 'salasana', 'salasana', 'katuosoite', 'postinumero', 'kaupunki', 'puhelinnumero'];
$pakolliset = ['firstname', 'lastname', 'email', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];

// $payment_method = $_POST['payment_method'] ?? '';
$terms_of_delivery = isset($_POST['terms_of_delivery']) ? 1 : '';
$kentat_tiedosto = ['image'];
//$css = 'rekisteroityminen.css';
include "virheilmoitukset.php";
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php";
$js = "script.js";
include "posti.php";
include "user_new_tarkastus.php";

if ($loggedIn == 'admin') {
?>
       <div class="container">
              <div class="row g-3 align-items-center mt-3 d-flex justify-content-center">
                     <div class="col-8 d-flex justify-content-center align-items-center">
                            <?php
                            if ($success != "success") { ?>
                                   <form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
                                          <fieldset>
                                                 <legend><?= translate('new_user'); ?></legend>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('firstname'); ?>:</span>
                                                        <input pattern="<?= pattern('firstname'); ?>" type="text" id="firstname" name="firstname" class="form-control <?= is_invalid('firstname'); ?>"
                                                               value="<?= arvo("firstname"); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['firstname'] ?? ""; ?>
                                                        </div>
                                                 </div>
                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('lastname'); ?>:</span>
                                                        <input type="text" id="lastname" name="lastname" class="form-control <?= is_invalid('lastname'); ?>"
                                                               value="<?= arvo("lastname"); ?>" pattern="<?= pattern('lastname'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['lastname'] ?? ""; ?>
                                                        </div>
                                                 </div>
                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('street_address'); ?>:</span>
                                                        <input type="text" id="address" name="address" value="<?= arvo("address"); ?>"
                                                               pattern="<?= pattern('address'); ?>" required autofocus
                                                               class="form-control <?= is_invalid('address'); ?>" />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['address'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('postal_code'); ?>:</span>
                                                        <input type="text" id="postcode" name="postcode" class="form-control <?= is_invalid('postcode'); ?>"
                                                               value="<?= arvo("postcode"); ?>" pattern="<?= pattern('postcode'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['postcode'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('city'); ?>:</span>
                                                        <input
                                                               type="text"
                                                               id="city"
                                                               name="city"
                                                               class="form-control <?= is_invalid('city'); ?>"
                                                               list="kaupungit"
                                                               value="<?= arvo("city") ?>"
                                                               pattern="<?= pattern('city'); ?>"
                                                               required autofocus />
                                                        <datalist id="kaupungit">

                                                        </datalist>
                                                        <div class="invalid-feedback">
                                                               <?= $errors['city'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('phone_number'); ?>:</span>
                                                        <input type="text" id="mobilenumber" name="mobilenumber" class="form-control <?= is_invalid('mobilenumber'); ?>"
                                                               value="<?= arvo("mobilenumber"); ?>" pattern="<?= pattern('mobilenumber'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['mobilenumber'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('email'); ?>:</span>
                                                        <input type="email" id="email" name="email" class="form-control <?= is_invalid('email'); ?>"
                                                               value="<?= arvo("email"); ?>" pattern="<?= pattern('email'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['email'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('password'); ?>:</span>
                                                        <input type="password" id="password" name="password" class="form-control <?= is_invalid('password'); ?>"
                                                               pattern="<?= pattern('password'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['password'] ?? ""; ?>
                                                        </div>
                                                 </div>

                                                 <div class="input-group mb-3">
                                                        <span class="input-group-text"><?= translate('confirm_password'); ?>:</span>
                                                        <input type="password" id="password2" name="password2" class="form-control" <?= is_invalid('password2'); ?>"
                                                               pattern="<?= pattern('password2'); ?>" required autofocus />
                                                        <div class="invalid-feedback">
                                                               <?= $errors['password2'] ?? ""; ?>
                                                        </div>
                                                 </div>
                                                 <div class="row mb-sm-1">
                                                        <div class="col-sm-8">
                                                               <input id="image" name="image" type="file" accept="image/*" pattern="<?= pattern('image'); ?>" class="form-control <?= is_invalid('image'); ?>" placeholder="<?= translate('photo'); ?>"></input>
                                                               <div class="invalid-feedback">
                                                                      <?= $errors['image'] ?? ""; ?>
                                                               </div>
                                                               <div class="previewDiv mt-1 col-sm-8 d-none" id="previewDiv">
                                                                      <img class="previewImage" src="" id="previewImage" width="300px" height="">
                                                                      <button type="button" class="btn btn-outline-secondary btn-sm float-end mt-1" onclick="tyhjennaKuva('image')"><?= translate('delete') ?></button>
                                                               </div>
                                                        </div>
                                                 </div>
                                                 <button type="button" class="float-end btn btn-secondary m-3" onclick="window.location.href='users.php'"><?= translate('cancel') ?></button>
                                                 <button name='painike' type="submit" class="mt-3 float-end btn btn-primary"><?= translate('login') ?></button>

                                          </fieldset>
                                   </form>

                     </div>



              <?php } ?>
              </div>
       </div>

       <div id="ilmoitukset" class="alert alert-<?= $success; ?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
              <p><?= $message; ?></p>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>

       </div>
       <?php include "footer.php"; ?>
<?php
} else {
       header("Location: index.php");
       exit;
}
