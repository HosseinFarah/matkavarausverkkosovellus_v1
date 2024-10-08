<?php
include "asetukset.php";
include "db.php";
include "rememberme.php";
include "debuggeri.php";
if ($loggedIn = loggedIn()) {
  header("location: profiili.php");
  exit;
}
/* Lomakkeen kentät, nimet samat kuin users-taulussa. */
$kentat = ['email', 'password', 'rememberme'];
$kentat_suomi = ['sähköpostiosoite', 'salasana', 'muista minut'];
$pakolliset = ['email', 'password'];
include "virheilmoitukset.php";
include 'kasittelija_login.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
$css = 'login.css';
include_once "lang.php";
$title = translate('login');
$_SESSION['lang'] ??= 'fi';
include "header.php";
?>
<div class="content">

  <div class="login-bg shadow-effect">
    <div class="container">
      <div id="atempts" class="row g-3 align-items-center mt-1 d-flex justify-content-center alert alert-info  d-none " role="alert">

      </div>
      <div class="row g-3 align-items-center mt-1 d-flex justify-content-center">
        <h1 class="text-center m-4 fs-1 text-light"><?= translate('welcome'); ?></h1>
        <form method="post" autocomplete="on" id="loginForm" class="mb-3 needs-validation bg-secondary p-5 rounded col-md-6 " novalidate>
          <fieldset>
            <legend class="text-light"><?= translate('login'); ?></legend>
            <div class="col-md-12">
              <div class="input-group mb-3 ">
                <span class="input-group-text"><?= translate('email'); ?>:</span>
                <input type="email" class="form-control <?= is_invalid('email'); ?>" name="email" id="email"
                  placeholder="<?= translate('email_placeholder') ?>" value="<?= arvo("email"); ?>"
                  pattern="<?= pattern('email'); ?>" autofocus required>
                <div class="invalid-feedback text-warning fs-5">
                  <?= $errors['email'] ?? ""; ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="input-group ">
                <span class="input-group-text mb-1"><?= translate('password'); ?>:</span>
                <input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password"
                  placeholder="<?= translate('password') ?>" pattern="<?= pattern('password'); ?>" required>
                <div class="invalid-feedback text-warning fs-5">
                  <?= $errors['password'] ?? ""; ?>
                </div>
              </div>
            </div>
            <div class="form-check ">
              <div class="form-check ms-2">
                <input class="form-check-input" type="checkbox" value="checked" <?= nayta_rememberme('rememberme'); ?> id="rememberme" name="rememberme" />
                <label class="form-check-label text-light" for="rememberme"><?= translate('rememberme'); ?></label>
                <div class="invalid-feedback">
                  <?= $errors['rememberme'] ?? ""; ?>
                </div>
              </div>
            </div>
            <!-- check for login attempts -->
            <div class="div-button">
              <input type="submit" name="painike" id="painike" class="mt-3 float-start btn btn-primary" value="<?php echo translate('login'); ?>"
                <?= (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) ? 'disabled' : ''; ?>>
            </div>


          </fieldset>
          <div class="row fs-5">
            <a class="text-light" href="forgotpassword.php"><?= translate('forgot_password'); ?></a>
          </div>

          <div class="row fs-5">
            <!--<p class="mt-2 pt-1 mb-0">Käyttäjätunnus puuttuu?-->
            <a class="text-light" href="rekisteroitymislomake.php"><?= translate('register'); ?></a>
          </div>
      </div>
      </form>

      <!-- login with google -->
      <!-- <div class="row g-3 align-items-center mt-1 d-flex justify-content-center">
        <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=656915444750-jencm5pb6chr7gri8547p9qrmp6iilqq.apps.googleusercontent.com&redirect_uri=http://localhost/google_login.php&scope=https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile&access_type=offline" class="btn btn-danger">
          <i class="fab fa-google"></i> <?= translate('login_with_google'); ?>
        </a>
      </div> -->


      <div id="ilmoitukset" class="alert alert-<?= $success; ?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
        <p><?= $message; ?></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    </div>
  </div>
  <?php
  include('footer.php');
  ?>

  <!-- check for login attempts -->
  <script>
    let attemptTime = <?= isset($_SESSION['last_attempt_time']) ? $_SESSION['last_attempt_time'] : 0 ?>;

    if (attemptTime) {
      // Disable the login button initially
      document.getElementById("painike").disabled = true;

      // Create a countdown element
      let countdownElement = document.createElement("p");
      countdownElement.id = "countdown";
      countdownElement.classList.add("text-secondary", "fs-4", "mt-1", "text-center");
      document.querySelector('#atempts').appendChild(countdownElement);

      // Start the countdown
      let countdown = setInterval(function() {
        let now = Math.floor(Date.now() / 1000);
        let timeLeft = 60 - (now - attemptTime); // 60 seconds countdown

        // Display the countdown to the user
        if (timeLeft > 0) {
          document.querySelector('#atempts').classList.remove('d-none');
          countdownElement.innerHTML = "<?php echo translate('many_attempts'); ?>: " + timeLeft + "s";
        } else {
          // When time is up, stop the countdown and enable the button
          clearInterval(countdown);
          countdownElement.innerHTML = ""; // Clear the countdown message
          document.querySelector('#atempts').classList.add('d-none');
          document.getElementById("painike").disabled = false;
        }
      }, 1000);
    }
  </script>