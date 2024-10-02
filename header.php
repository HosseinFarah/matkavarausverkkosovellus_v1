<?php
if (!session_id()) session_start();
ini_set('default_charset', 'utf-8');

include_once "debuggeri.php";
/* Huom. suojatulla sivulla on asetukset,db,rememberme.php; */
if (!isset($loggedIn)) {
  require "asetukset.php";
  include "db.php";
  include_once 'lang.php';
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
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fi'; ?>">
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
  <title><?= $title ?></title>
</head>

<nav class="topnav" id="myTopnav">
  <a class="brand-logo" href="index.php">
    <img src="HuviMatka.png" alt="Logo"></a>
  <!-- liged in user image -->
  <input type="checkbox" id="toggle-btn">
  <label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
  <label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
  <a href="<?= "http://$PALVELIN/" ?>" class="active"><?php echo translate('main_page') ?></a>
  <a href="<?= "http://$PALVELIN/aboutus.php" ?>"><?= translate('about_us') ?></a>
  <a href="<?= "http://$PALVELIN/contact_us.php
      " ?>"><?= translate('contact_us') ?></a>
      <a href="?lang=en">English</a>
      <a href="?lang=fi">Suomi</a>
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
      echo "<a class='nav-suojaus' " . active('login', $active) . "' href='login.php'><i class='fas fa-sign-in-alt'> </i> " . translate('login') . "</a>";
      break;
  }
  // dropdown menu
  if ($loggedIn && $_SESSION['user_id']) {
    $id = intval($_SESSION['user_id']);
    $sql = "SELECT image FROM users WHERE id = $id";
    $result = my_query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if ($row['image'] == NULL || $row['image'] == "") {
        $image = "default.jpg";
      } else {
        $image = $row['image'];
      }
  ?>
      <ul class="navbar-nav">
        <li class="nav-item dropdown text-end nav-suojaus-">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="profiilikuvat/users/<?= $image ?>" alt="Profile" class="rounded-circle" width="40" height="40">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class='dropdown-item text-primary " . active(' profiili', $active) . "' href='profiili.php'><?= translate('profile') ?></a></li>
              <li>
                <hr class=" dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="poistu.php"><?= translate('logout') ?></a></li>
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
  if ($loggedIn === 'admin') {
  ?>
    <!-- sidebar start -->
    <div class="sidebar" id="sidebar">
      <h2 class="text-center mt-4"><?= translate('dashboard') ?></h2>
      <!-- <a href="#allUsers" class="collapsed" data-bs-toggle="collapse">
        <i class="fas fa-users"></i> All Users <i class="fas fa-caret-down collapse-icon"></i>
      </a>
      <div class="collapse" id="allUsers">
        <a href="#">View Users</a>
        <a href="#">Edit Users</a>
      </div> -->
      <a href="users.php">
        <i class="fas fa-users"></i> <?= translate('all_users') ?>
      </a>
      <a href="tours.php">
        <i class="fas fa-plane"></i> <?= translate('all_tours') ?>
      </a>
      <a href="reviews.php">
        <i class="fas fa-star"></i> <?= translate('all_comments') ?>
      </a>
      <a href="reserved.php">
        <i class="fas fa-book"></i> <?= translate('all_reservations') ?>
      </a>

      <!-- tour guides -->
      <a href="tour_guides.php">
        <i class="fas fa-user-tie"></i> <?= translate('all_guides') ?>
      </a>
      <a href="user_new.php">
        <i class="fas fa-user-plus"></i> <?= translate('add_user') ?>
      </a>
      <a href="tour_new.php">
        <i class="fas fa-plus-circle"></i> <?= translate('add_tour') ?>
      </a>
      <!-- add tour guide -->
      <a href="tour_guide_new.php">
        <i class="fas fa-user-plus"></i> <?= translate('add_guide') ?>
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
