<?php
include "asetukset.php";
include "db.php";
include "rememberme.php";
include "debuggeri.php";
if ($loggedIn = loggedIn()) {
  header("location: profiili.php");
  exit;
}
$title = 'Kirjautuminen';
/* Lomakkeen kentät, nimet samat kuin users-taulussa. */
$kentat = ['email', 'password', 'rememberme'];
$kentat_suomi = ['sähköpostiosoite', 'salasana', 'muista minut'];
$pakolliset = ['email', 'password'];
include "virheilmoitukset.php";
include 'kasittelija_login.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
$css = 'login.css';
include "header.php";
?>
<div class="content">

  <div class="container login-bg shadow-effect">
    <div class="container">
      <div class="row g-3 align-items-center mt-1 d-flex justify-content-center">
        <h1 class="text-center m-4 fs-1 text-light">Tervetuloa</h1>
        <form method="post" autocomplete="on" class="mb-3 needs-validation bg-secondary p-5 rounded col-md-6 " novalidate>
          <fieldset>
            <legend class="text-light">Kirjautuminen</legend>
            <div class="col-md-12">
              <div class="input-group mb-3 ">
                <span class="input-group-text">Sähköposti:</span>
                <input type="email" class="form-control <?= is_invalid('email'); ?>" name="email" id="email"
                  placeholder="etunimi.sukunimi@palvelu.fi" value="<?= arvo("email"); ?>"
                  pattern="<?= pattern('email'); ?>" autofocus required>
                <div class="invalid-feedback text-warning fs-5">
                  <?= $errors['email'] ?? ""; ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="input-group ">
                <span class="input-group-text mb-1">Salasana:</span>
                <input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password"
                  placeholder="salasana" pattern="<?= pattern('password'); ?>" required>
                <div class="invalid-feedback text-warning fs-5">
                  <?= $errors['password'] ?? ""; ?>
                </div>
              </div>
            </div>
            <div class="form-check ">
              <div class="form-check ms-2">
                <input class="form-check-input" type="checkbox" value="checked" <?= nayta_rememberme('rememberme'); ?> id="rememberme" name="rememberme" />
                <label class="form-check-label text-light" for="rememberme">Muista minut</label>
                <div class="invalid-feedback">
                  <?= $errors['rememberme'] ?? ""; ?>
                </div>
              </div>
            </div>
            <div class="div-button">
              <input type="submit" name="painike" class="mt-3 float-start btn btn-primary" value="Kirjaudu">
            </div>
          </fieldset>
          <div class="row fs-5">
            <a class="text-light" href="forgotpassword.php">Unohtuiko salasana</a>
          </div>
  
          <div class="row fs-5">
            <!--<p class="mt-2 pt-1 mb-0">Käyttäjätunnus puuttuu?-->
            <a class="text-light" href="rekisteroitymislomake.php">Rekisteröidy</a>
          </div>
        </div>
        </form>



      <?php
      /*if (isset($_POST['painike']) && $errors){
    echo '<div class="ilmoitukset mt-3">';
    foreach ($errors as $kentta => $arvo) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">$arvo</div>";   
      }
    echo "</div>";
    }*/
      ?>

      <div id="ilmoitukset" class="alert alert-<?= $success; ?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
        <p><?= $message; ?></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    </div>
  </div>
  <?php
  include('footer.php');
  ?>