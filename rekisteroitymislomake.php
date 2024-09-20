<?php
/* Huom. Tässä salasanakenttien täsmääminen tarkistetaan vain palvelimella. */
$title = 'Rekisteröityminen';
$kentat = ['firstname', 'lastname', 'email', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$kentat_suomi = ['etunimi', 'sukunimi', 'sähköpostiosoite', 'salasana', 'salasana', 'katuosoite', 'postinumero', 'kaupunki', 'puhelinnumero'];
$pakolliset = ['firstname', 'lastname', 'email', 'password', 'password2', 'address', 'postcode', 'city', 'mobilenumber'];
$js = "script.js";

// $payment_method = $_POST['payment_method'] ?? '';
$terms_of_delivery = isset($_POST['terms_of_delivery']) ? 1 : '';

$kentat_tiedosto = ['image'];
//$css = 'rekisteroityminen.css';
include "virheilmoitukset.php";
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php";
include "posti.php";
include "rekisterointi.php";
?>
<div class="container">
       <div class="row g-3 align-items-center mt-3 d-flex justify-content-center">
              <div class="col-8 d-flex justify-content-center align-items-center">
                     <?php
                     if ($success != "success") { ?>
                            <form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
                                   <fieldset>
                                          <legend>Rekisteröityminen</legend>

                                          <div class="input-group mb-3">
                                                 <span class="input-group-text">etunimi:</span>
                                                 <input pattern="<?= pattern('firstname'); ?>" type="text" id="firstname" name="firstname" class="form-control <?= is_invalid('firstname'); ?>"
                                                        title="Nimen tulee olla vähintään kaksi merkkiä pitkä ja saa sisältää vain kirjaimia, välilyöntejä, viivoja ja heittomerkkejä."
                                                        value="<?= arvo("firstname"); ?>" required autofocus />
                                                 <div class="invalid-feedback">
                                                        <?= $errors['firstname'] ?? ""; ?>
                                                 </div>
                                          </div>
                                          <div class="input-group mb-3">
                                                 <span class="input-group-text">Sukunimi:</span>
                                                 <input type="text" id="lastname" name="lastname" class="form-control <?= is_invalid('lastname'); ?>"
                                                        title="Nimen tulee olla vähintään kaksi merkkiä pitkä ja saa sisältää vain kirjaimia, välilyöntejä, viivoja ja heittomerkkejä."
                                                        value="<?= arvo("lastname"); ?>" pattern="<?= pattern('lastname'); ?>" required autofocus />
                                                 <div class="invalid-feedback">
                                                        <?= $errors['lastname'] ?? ""; ?>
                                                 </div>
                                          </div>
                                          <div class="input-group mb-3">
                                                 <span class="input-group-text">Katuosoite:</span>
                                                 <input type="text" id="address" name="address" value="<?= arvo("address"); ?>"
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
                                                        value="<?= arvo("postcode"); ?>" pattern="<?= pattern('postcode'); ?>" required autofocus />
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
                                                 <span class="input-group-text">Puhelinnumero:</span>
                                                 <input type="text" id="mobilenumber" name="mobilenumber" class="form-control <?= is_invalid('mobilenumber'); ?>"
                                                        value="<?= arvo("mobilenumber"); ?>" pattern="<?= pattern('mobilenumber'); ?>" required autofocus />
                                                 <div class="invalid-feedback">
                                                        <?= $errors['mobilenumber'] ?? ""; ?>
                                                 </div>
                                          </div>

                                          <div class="input-group mb-3">
                                                 <span class="input-group-text">Sähköposti:</span>
                                                 <input type="email" id="email" name="email" class="form-control <?= is_invalid('email'); ?>"
                                                        value="<?= arvo("email"); ?>" pattern="<?= pattern('email'); ?>" required autofocus />
                                                 <div class="invalid-feedback">
                                                        <?= $errors['email'] ?? ""; ?>
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

                                          <!-- <div class="input-group mb-3">
                                                 <span class="input-group-text">Palautetta:</span>
                                                 <textarea id="feedback" name="feedback" class="form-control"><?= arvo("feedback"); ?></textarea>
                                          </div> -->

                                          <!-- departments items is  Muoti  Urheilu  Sisustaminen  Pelit  Elokuvat -->
                                          <!-- <div class="input-group mb-3">
                                                 <label class="form-label">Osastot:</label>
                                                 <div>
                                                        <div class="form-check">
                                                               <input class="form-check-input" type="checkbox" id="muoti" name="departments[]" value="Muoti" <?= in_array('Muoti', $_POST['departments'] ?? []) ? 'checked' : ''; ?>>
                                                               <label class="form-check-label" for="muoti">Muoti</label>
                                                        </div>
                                                        <div class="form-check">
                                                               <input class="form-check-input" type="checkbox" id="urheilu" name="departments[]" value="Urheilu" <?= in_array('Urheilu', $_POST['departments'] ?? []) ? 'checked' : ''; ?>>
                                                               <label class="form-check-label" for="urheilu">Urheilu</label>
                                                        </div>
                                                        <div class="form-check">
                                                               <input class="form-check-input" type="checkbox" id="sisustaminen" name="departments[]" value="Sisustaminen" <?= in_array('Sisustaminen', $_POST['departments'] ?? []) ? 'checked' : ''; ?>>
                                                               <label class="form-check-label" for="sisustaminen">Sisustaminen</label>
                                                        </div>
                                                        <div class="form-check">
                                                               <input class="form-check-input" type="checkbox" id="pelit" name="departments[]" value="Pelit" <?= in_array('Pelit', $_POST['departments'] ?? []) ? 'checked' : ''; ?>>
                                                               <label class="form-check-label" for="pelit">Pelit</label>
                                                        </div>
                                                        <div class="form-check">
                                                               <input class="form-check-input" type="checkbox" id="elokuvat" name="departments[]" value="Elokuvat" <?= in_array('Elokuvat', $_POST['departments'] ?? []) ? 'checked' : ''; ?>>
                                                               <label class="form-check-label" for="elokuvat">Elokuvat</label>
                                                        </div>
                                                 </div> -->
                                          <!-- <div class="invalid-feedback">
                                                        <?= $errors['departments'] ?? ""; ?>
                                                 </div>
                                          </div> -->



                                          <div class="row mb-sm-1">
                                                 <label for="image" class="form-label mb-0 col-sm-4">Kuva</label>
                                                 <div class="col-sm-8">
                                                        <input id="image" name="image" type="file" accept="image/*" pattern="<?= pattern('image'); ?>" class="form-control <?= is_invalid('image'); ?>" placeholder="kuva"></input>
                                                        <div class="invalid-feedback">
                                                               <?= $errors['image'] ?? ""; ?>
                                                        </div>
                                                        <div class="previewDiv mt-1 col-sm-8 d-none" id="previewDiv">
                                                               <img class="previewImage" src="" id="previewImage" width="" height="">
                                                               <button type="button" class="btn btn-outline-secondary btn-sm float-end mt-1" onclick="tyhjennaKuva('image')">Poista</button>
                                                        </div>
                                                 </div>
                                          </div>
                                          <div class="form-check mb-3">
                                                 <input type="checkbox" id="terms_of_delivery" name="terms_of_delivery" class="form-check-input <?= is_invalid('terms_of_delivery'); ?>"
                                                        value="kylla" <?= $terms_of_delivery === 'kylla' ? 'checked' : ''; ?> required autofocus />
                                                 <label class="form-check-label" for="terms_of_delivery">Hyväksyn toimitusehdot</label>
                                                 <div class="invalid-feedback">
                                                        <?= $errors['terms_of_delivery'] ?? ""; ?>
                                                 </div>
                                          </div>
                                          <button name='painike' type="submit" class="mt-3 float-end btn btn-primary">Rekisteröidy</button>
                                   </fieldset>
                            </form>

                     <?php } ?>
              </div>
       </div>

       <div id="ilmoitukset" class="alert alert-<?= $success; ?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
              <p><?= $message; ?></p>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>

</div>
<?php include "footer.html"; ?>