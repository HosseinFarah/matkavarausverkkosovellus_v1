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
    <img src="HuviMatka.png" alt="Logo"></a>
  <!-- liged in user image -->
  <input type="checkbox" id="toggle-btn">
  <label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
  <label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
  <a href="<?= "http://$PALVELIN/" ?>" class="active">Etusivu</a>
  <a href="<?= "http://$PALVELIN/aboutus.php" ?>">Tietoa meistä</a>
  <a href="<?= "http://$PALVELIN/contact_us.php
      " ?>">Ota yhteyttä</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
  <!-- <a class="<?= ($active == 'kuvagalleria') ? 'active' : ''; ?>" href="kuvagalleria.php">Kuvagalleria</a> -->
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
      // echo "<a class='" . active('kayttajat', $active) . "' href='kayttajat.php'>Käyttäjät</a>";
      // echo '<a href="poistu.php">Poistu</a>';
      break;
    case true:
      // echo "<a class='" . active('profiili', $active) . "' href='profiili.php'>Profiili</a>";
      /* Huom. tästä oikeaan laitaan. */
      // echo "<a class='nav-suojaus " . active('phpinfo', $active) . "' href='phpinfo.php'>phpinfo</a>";
      // echo "<a class='" . active('fake', $active) . "' href='fake.php'>fake</a>";
      // echo '<a href="poistu.php">Poistu</a>';
      break;
    default:
      echo "<a class='nav-suojaus " . active('login', $active) . "' href='login.php'><i class='fas fa-sign-in-alt'></i> Kirjautuminen</a>";
      break;
  }
  // dropdown menu
  if ($loggedIn && $_SESSION['user_id']) {
    $id = intval($_SESSION['user_id']);
    $sql = "SELECT image FROM users WHERE id = $id";
    $result = my_query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $image = $row['image'];
  ?>
      <ul class="navbar-nav">
        <li class="nav-item dropdown text-end">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="profiilikuvat/users/<?= $image ?>" alt="Profile" class="rounded-circle" width="40" height="40">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class='dropdown-item text-primary " . active(' profiili', $active) . "' href='profiili.php'>Profiili</a></li>
              <li>
                <hr class=" dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="poistu.php">Log out</a></li>
          </ul>
        </li>
      </ul>
  <?php
    }
  }
  ?>
</nav>

<body>

<?php
if($loggedIn === 'admin') {
  ?>
  <!-- sidebar start -->
    <div class="sidebar" id="sidebar">
      <h2 class="text-center mt-4">Dashboard</h2>
      <!-- <a href="#allUsers" class="collapsed" data-bs-toggle="collapse">
        <i class="fas fa-users"></i> All Users <i class="fas fa-caret-down collapse-icon"></i>
      </a>
      <div class="collapse" id="allUsers">
        <a href="#">View Users</a>
        <a href="#">Edit Users</a>
      </div> -->
      <a href="users.php">
        <i class="fas fa-users"></i> Kaikki käyttäjät
      </a>
      <a href="tours.php">
        <i class="fas fa-plane"></i> Kaikki matkat
      </a>
      <a href="reviews.php">
        <i class="fas fa-star"></i> Kaikki arvostelut 
      </a>
      <a href="reserved.php" >
        <i class="fas fa-book"></i> Kaiikki varaukset
      </a>
      
      <!-- tour guides -->
      <a href="tour_guides.php">
        <i class="fas fa-user-tie"></i> Kaikki oppaat
      </a>
      <a href="user_new.php">
        <i class="fas fa-user-plus"></i> Lisää käyttäjä
      </a>
      <a href="tour_new.php">
        <i class="fas fa-plus-circle"></i> Lisää matka
      </a>
      <!-- add tour guide -->
      <a href="tour_guide_new.php">
        <i class="fas fa-user-plus"></i> Lisää opas
      </a>
    </div>

    <button class="btn btn-primary toggle-button" id="toggleSidebar">
    <i class="fas fa-arrows-alt-h"></i>
    </button>
  </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#toggleSidebar').click(function() {
          $('#sidebar').toggleClass('active');
        });

        // Handle collapse icons rotation
        $('.collapsed').on('click', function() {
          $(this).find('.collapse-icon').toggleClass('collapsed');
        });
      });
    </script>
    <?php
    }
    ?>
    <!-- sidebar-end -->
    <?php
    if ($_SERVER['PHP_SELF'] == '/profiili.php') {
    } else {
      echo '<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>';
    }
