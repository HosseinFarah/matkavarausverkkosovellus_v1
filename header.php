<?php
if (!session_id()) session_start();
ini_set('default_charset', 'utf-8');
?>
<!DOCTYPE html>
<html lang="fi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="omniamusta_tausta.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link
    href="https://fonts.googleapis.com/css2?family=Jura:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
  <link rel="stylesheet" href="site.css">
  <?php if (isset($css)) echo "<link rel='stylesheet' href='$css'>"; ?>
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script defer src="scripts.js"></script>
  <script defer src="script.js"></script>
  <title><?= $title ?? 'Sivusto'; ?></title>
</head>

<body>
  <?php
  include_once "debuggeri.php";
  /* Huom. suojatulla sivulla on asetukset,db,rememberme.php; */
  if (!isset($loggedIn)) {
    require "asetukset.php";
    include "db.php";
    include "rememberme.php";
    $loggedIn = loggedIn();
  }
  debuggeri("loggedIn:$loggedIn");
  register_shutdown_function('debuggeri_shutdown');
  $active = basename($_SERVER['PHP_SELF'], ".php");


  function active($sivu, $active)
  {
    return $active == $sivu ? 'active' : '';
  }

  /* Huom. nav-suojaus vie viimeiset linkit oikealle. */
  ?>
  <nav class="topnav" id="myTopnav">
    <a class="brand-logo" href="index.php">
      <img src="omniamusta_tausta.png" alt="Logo"></a>
    <input type="checkbox" id="toggle-btn">
    <label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
    <label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
    <a href="<?= "http://$PALVELIN/#etusivu" ?>" class="active">Etusivu</a>
    <a href="<?= "http://$PALVELIN/aboutus.php" ?>">Tietoa meistä</a>
    <a href="<?= "http://$PALVELIN/#otayhteytta" ?>">Ota yhteyttä</a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
      <i class="fa fa-bars"></i>
    </a>
    <a class="<?= ($active == 'kuvagalleria') ? 'active' : ''; ?>" href="kuvagalleria.php">Kuvagalleria</a>
    <?php
    /*if ($loggedIn === 'admin') {
  echo "<a class='".active('kayttajat',$active). "' href='kayttajat.php'>Käyttäjät</a>";
  }
if ($loggedIn) {
  echo "<a class='".active('profiili',$active). "' href='profiili.php'>Profiili</a>";
  echo '<a class="nav-suojaus" href="poistu.php">Poistu</a>';
  }
if (!$loggedIn) {
  echo "<a class='nav-suojaus ".active('login',$active)."' href='login.php'>Kirjautuminen</a>";
  }*/

    switch ($loggedIn) {
      case 'admin':
        echo "<a class='" . active('kayttajat', $active) . "' href='kayttajat.php'>Käyttäjät</a>";
        echo "<a class='" . active('hfk', $active) . "' href='hfk.php'>HFK</a>";
        echo "<a class='" . active('profiili', $active) . "' href='profiili.php'>Asetukset</a>";
        echo '<a href="poistu.php">Poistu</a>';
        break;
      case true:
        echo "<a class='" . active('profiili', $active) . "' href='profiili.php'>Profiili</a>";
        /* Huom. tästä oikeaan laitaan. */
        // echo "<a class='nav-suojaus " . active('phpinfo', $active) . "' href='phpinfo.php'>phpinfo</a>";
        // echo "<a class='" . active('fake', $active) . "' href='fake.php'>fake</a>";
        echo '<a href="poistu.php">Poistu</a>';
        break;
      default:
        echo "<a class='nav-suojaus " . active('login', $active) . "' href='login.php'>Kirjautuminen</a>";
        break;
    }

    ?>
  </nav>