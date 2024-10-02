<footer class="footer mt-auto py-3 bg-light w-100">
  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5><?= translate('contact_info') ?></h5>
          <p>
            Huvimatkat Oy <br>
            Rautatienkatu 21 <br>
            33100 Tampere <br>
            <?php echo translate('phone') ?>: 010 123 4567 <br>
            <?php echo translate('email') ?>: <?php echo $PALVELUOSOITE ?> <br>
          </p>
        </div>
        <!-- Lue lisää meistä -->

        <div class="col-md-6">
          <h5><?= translate('read_more_about_us') ?></h5>
          <p> <?= translate('about_us_desc') ?></p>
          <a data-bs-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapse">
            <?= translate('read_more') ?> </a>
          <div class="collapse" id="collapse">
            <p>
              <?= translate('about_us_desc_more') ?>
            </p>
          </div>
        </div>
      </div>
      <!-- providers -->
      <div class="row">
        <div class="col-md-12 text-start ml-4">
          <h5><?= translate('providers') ?></h5>
          <p>
            <a href="https://www.tallinksilja.fi" target="_blank">Tallink Silja</a>
            | <a href="https://www.vr.fi" target="_blank">VR</a> |
            <a href="https://www.finnair.fi" target="_blank">Finnair</a>
          </p>
        </div>
      </div>

      <hr />
      <div class="row">
        <div class="col-md-12">
          <p class="text-center">
            <?php
            if (isset($_SESSION['user_id'])) {
              echo '<a href="poistu.php">' . translate('logout') . '</a>';
              echo ' | ';
              echo '<a href="profiili.php">' . translate('my_reservations') . '</a>';
            } else {
              echo '<a href="rekisteroitymislomake.php">' . translate('register') . '</a> | <a href="login.php">' . translate("login") . '</a>';
            }
            ?>
          </p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-center">
          <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook fa-2x"></i></a>
          <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>
          <a href="https://www.telegram.com" target="_blank"><i class="fab fa-telegram fa-2x"></i></a>
          <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter fa-2x"></i></a>
          <a href="https://www.youtube.com" target="_blank"><i class="fab fa-youtube fa-2x"></i></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class=" col-sm-12 m-1">
        <span class="text-muted">
          <h6 class="text-center">&copy; 2024 Huvimatkat Oy | HosseinFarahkordmahaleh</h6>
          </h6>
        </span>
      </div>
    </div>
    <!-- set HuviMatka.png to the footer in the right middle width:250px -->
    <div class="row">
      <div class="col-md-3 text-center">
        <img src="HuviMatka.png" alt="Logo" width="150px" class="rounded">
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>