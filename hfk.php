<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Kasvien hoito";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();

include 'header.php';
if($loggedIn=='admin'){
?>
<body>
  <div class="container-style">
    <div class="grid-item grid-item-1" id="etusivu">
      <h1 class="title">
        <a
          href="<?= "http://$PALVELIN/#tuotteet"; ?>"><i class="fas fa-chevron-left"></i> Tuotteet</a>
        <strong>Kasvishoito</strong>
      </h1>
    </div>
    <div class="product-item product-item-1">
      <a href="kakku4.php">
        <img
          src="https://plus.unsplash.com/premium_photo-1679366697638-af8505ceb16d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDl8fEdhcmRlbiUyMEZlcnRpbGl6YXRpb258ZW58MHwxfDB8fHww"
          alt="img-1" />
      </a>
      <ul>
        <li><strong>Palvenun nimi:</strong>Puutarhan Lannoitus</li>
        <li><strong>Hinta: </strong> 50€</li>
        <li>
          <strong>Kuvaus: </strong> <br />
          Puutarhan lannoitus on tärkeä osa kasvien hoitoa, joka auttaa
          varmistamaan niiden terveellisen kasvun ja runsaan kukinnan. Se
          tarkoittaa ravinteiden lisäämistä maahan tai kasvien juurille, jotta
          ne saavat tarvitsemansa vitamiinit ja mineraalit. Lannoitteita on
          erilaisia, kuten orgaanisia (esimerkiksi komposti tai eläinperäinen
          lannoite) ja mineraalipohjaisia (kemialliset seokset). Oikea
          lannoitus auttaa parantamaan maaperän laatua, edistämään juurten
          kehitystä ja lisäämään sadon määrää ja laatua. Lannoituksen ajoitus
          ja määrä vaihtelevat kasvilajien ja maaperän tarpeiden mukaan.
        </li>
      </ul>
    </div>
<?php
}

else{
  include '404.html';
?>
    <?php include 'footer.html'; ?>
<?php
}
?>
  </div>
</body>

</html>