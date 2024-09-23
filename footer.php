<footer class="footer mt-auto py-3 bg-light w-100">
  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Yhteystiedot</h5>
          <p>
            Web-ohjelmointikoulutus<br />
            Osoite: Koulukatu 123, 12345 Kaupunki<br />
            Puhelin: 040 123 4567<br />
            Sähköposti: info@huvimatka.fi
          </p>
        </div>
        <!-- Lue lisää meistä -->

        <div class="col-md-6">
          <h5>Lue lisää meistä</h5>
          <p> Meidän tavoitteena on tarjota asiakkaillemme laadukkaita ja turvallisia matkoja ympäri maailmaa.
            <a data-bs-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapse">
              Lue lisää</a>
          <div class="collapse" id="collapse">
            <p>
              Olemme toimineet alalla jo yli 20 vuotta ja olemme erikoistuneet järjestämään matkoja yksin matkustaville, pariskunnille ja perheille. Tarjoamme laajan valikoiman matkoja, joista voit valita mieleisesi. Tervetuloa Huvimatkojen sivuille!
              Aloita matkasi kanssamme ja varaa matkasi jo tänään!
              Hyvää matkaa! ja tervetuloa Huvimatkojen sivuille!
            </p>
          </div>
        </div>
      </div>
      <!-- providers -->
      <div class="row">
        <div class="col-md-12 text-start ml-4">
          <h5>Yhteistyökumppanit</h5>
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
              echo '<a href="poistu.php">Kirjaudu ulos</a>';
              echo ' | ';
              echo '<a href="profiili.php">Omat varaukset</a>';
            } else {
              echo '<a href="rekisteroitymislomake.php">Rekisteröidy</a> | <a href="login.php">Kirjaudu</a>';
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
          <h6 class="text-center">&copy; 2023 Web-ohjelmointikoulutus|HosseinFarahkordmahaleh</h6>
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