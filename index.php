<?php

include_once "lang.php";
$title = translate('index_title');
$_SESSION['lang'] ??= 'fi';
include 'header.php';
include 'posti.php';
$sql = "SELECT * FROM tours";
$result = my_query($sql);
$slider = ["(1).jpg", "(2).jpg", "(3).jpg", "(4).jpg", "(5).jpg", "(6).jpg", "(7).jpg", "(8).jpg", "(9).jpg"];


?>

<body>
  <div class="content">
    <?php
    if (isset($_GET['search'])) {
      $search = $_GET['search'];
      $searchSql = "SELECT * FROM `tours` WHERE `name` LIKE '%$search%' OR `location` LIKE '%$search%' OR `price` LIKE '%$search%' OR `startDate` LIKE '%$search%'";
      $result = my_query($searchSql); // Execute the search query
      if ($result->num_rows == 0) {
        echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>" .  translate('no_results') . "</h2></div></div></div>";
      }
    } else {
      // If no search, show all tours
      echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>" .  translate('all_tours') . "</h2></div></div></div>";
    }
    ?>
    <!-- sort tours with price and date in dropdown -->
    <?php
    if (isset($_GET['sort'])) {
      $sort = $_GET['sort'];
      if ($sort == 'highest_price') {
        $sortSql = "SELECT * FROM tours ORDER BY price DESC";
        $result = my_query($sortSql);
      } elseif ($sort == 'lowest_price') {
        $sortSql = "SELECT * FROM tours ORDER BY price ASC";
        $result = my_query($sortSql);
      } elseif ($sort == 'date') {
        $sortSql = "SELECT * FROM tours ORDER BY startDate ASC";
        $result = my_query($sortSql);
      }
    }

    if (isset($_GET['filter'])) {
      $min_price = $_GET['min_price'];
      $max_price = $_GET['max_price'];
      $filterSql = "SELECT * FROM tours WHERE price BETWEEN $min_price AND $max_price";
      $result = my_query($filterSql);
    }

    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-md-12">
          <h1 class="text-center"><?= translate('welcome') ?></h1>
          <p class="text-center"><?= translate('desc_1') ?></p>
        </div>
      </div>
    </div>

    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        foreach ($slider as $key => $image) {
          $active = $key == 0 ? 'active' : '';
          echo "<div class='carousel-item $active' data-bs-interval='3000'>
      <img src='profiilikuvat/slider/$image' class='d-block w-100' alt='$image'>
    </div>";
        }
        ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
    <!-- search tours -->
    <div class="container my-5">
      <div class="row">
        <div class="col-md-8">
          <form method="GET">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="<?= translate('search_tour') ?>" name="search">
              <button class="btn btn-primary" type="submit"><?= translate('search') ?></button>
              <!-- clear search -->
              <a href="index.php" class="btn btn-secondary"><i class="fas fa-broom text-light"></i></a>
            </div>
          </form>
        </div>
        <div class="col-md-4">
          <form method="GET">
            <div class="input-group mb-3">
              <select class="form-select" name="sort">
                <option value=""><?= translate('sort_by') ?></option>
                <option value="highest_price"><?= translate('highest_price') ?></option>
                <option value="lowest_price"><?= translate('lowest_price') ?></option>
                <option value="date"><?= translate('date') ?></option>
              </select>
              <button class="btn btn-primary" type="submit"><?= translate('sort') ?></button>
            </div>
          </form>
        </div>
      </div>
      <!-- filter tours with price range useing form-range Start-->
      <div class="row">
        <div class="col-md-4">
          <form method="GET">
            <div class="input-group mb-3">
              <!-- Use PHP to set the value from the GET request or a default if not set -->
              <input type="range" class="form-range" min="50" max="1000" name="min_price"
                value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : 0 ?>"
                id="min_price" oninput="updateMinValue()">

              <input type="range" class="form-range" min="50" max="1000" name="max_price"
                value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : 100 ?>"
                id="max_price" oninput="updateMaxValue()">

              <!-- Display the selected values next to the sliders -->
              <div class="d-flex justify-content-between">
                <label for="min_price" class="form-label">Min Price: <span id="min_value"><?= isset($_GET['min_price']) ? $_GET['min_price'] : 0 ?></span> €</label>
                <label for="max_price" class="form-label">Max Price: <span id="max_value"><?= isset($_GET['max_price']) ? $_GET['max_price'] : 100 ?></span> €</label>
              </div>

              <!-- Filter button -->
            </div>
            <button class="btn btn-primary mt-3" type="submit" name="filter"><?= translate('filter') ?></button>
          </form>
        </div>
      </div>
      <!-- filter tours with price range useing form-range End-->







      <div class="container my-5">
        <div class="row">
          <div class="col-md-12">
            <h2 class="text-center"><?= translate('all_tours') ?></h2>
          </div>
        </div>
      </div>

      <div class="container my-5">
        <div class="row">
          <?php
          // $sql = "SELECT * FROM tours";
          // $result = my_query($sql);
          if ($result->num_rows > 0 && $loggedIn != 'guide') {
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
              // Extract fields
              $id = $row['id'];
              $sql2 = "SELECT * FROM translations WHERE language = '$_SESSION[lang]' AND tour_id = $id";
              $result2 = my_query($sql2);
              $row2 = $result2->fetch_assoc();
              $name = getTranslation($id, 'name', $_SESSION['lang']);
              $title = getTranslation($id, 'title', $_SESSION['lang']);
              $summary = getTranslation($id, 'summary', $_SESSION['lang']);
              $description = getTranslation($id, 'description', $_SESSION['lang']);
              $location = htmlspecialchars($row['location']);
              $startDate = htmlspecialchars($row['startDate']);
              $groupSize = htmlspecialchars($row['groupSize']);
              $price = htmlspecialchars($row['price']);
              $places = htmlspecialchars($row['places']);
              $duration = htmlspecialchars($row['duration']);
              $tourImage = htmlspecialchars($row['tourImage']);

              $sql_free = "SELECT * FROM reservations WHERE tour_id = $id";
              $result_free = my_query($sql_free);
              $vapaa = $groupSize - $result_free->num_rows;

              // Start a new row every 3 cards
              if ($counter % 3 == 0 && $counter > 0) {
                echo "</div><div class='row'>";
              }

              $reviewSql = "SELECT AVG(rating) as rating FROM reviews WHERE tour_id = $id";
              $reviewResult = my_query($reviewSql);
              $reviewRow = $reviewResult->fetch_assoc();

              $votesSql = "SELECT COUNT(*) as votes FROM reviews WHERE tour_id = $id";
              $votesResult = my_query($votesSql);
              $votesRow = $votesResult->fetch_assoc();
              $voter = $votesRow['votes'];

              $rating = intval($reviewRow['rating']) . " <i class= 'fas fa-star text-light'></i>" . " | " . $voter . " <i class='fas fa-user text-light'></i>";

              echo "
            <div class='col-md-4 mb-4'>
              <div class='card shadow-lg shine-effect'>
                <img src='profiilikuvat/tours/$tourImage' class='card-img-top' alt='$name'>
                <div class='card-body'>
                  <h5 class='card-title fs-2 text-primary'>$name</h5>
                  <p class='card-text'><i class='fas fa-info'> </i><strong> $summary</strong></p>
                  <p class='card-text'><strong><i class='far fa-clock'></i> " . translate('duration') . ": </strong> $duration " . translate('hours') . "</p>
                  <p class='card-text'><strong><i class='fas fa-map-marker-alt'></i> " . translate('tour_places') . ":</strong> $places</p>
                  <p class='card-text'><strong><i class='fas fa-users'></i> " . translate('max_participants') . ": </strong><span class ='badge text-bg-warning fs-6'> $groupSize </span></p>
                  <p class='card-text'><strong><i class='fas fa-users'></i> " . translate('free_places') . ": </strong><span class ='badge text-bg-warning fs-6'> $vapaa </span></p>
                  <div class='card-footer rounded'>
                    <small class='text-body-secondary'><p class='card-text badge text-bg-secondary fs-6 mb-3'>" . translate('price') . ": $price €</p></small>
                    <small class='text-body-secondary'><p class='card-text fs-6 text-primary-emphasis '><i class='fas fa-calendar'></i> $startDate</p></small>
                    <p class='card-text mt-2 text-end'><strong></strong><span class ='badge text-bg-primary'> $rating</span></p>
                  </div>
                  <div class='text-end'>
                    <a href='tour.php?id=$id' class='btn btn-primary m-1'> <i class='fas fa-binoculars fs-5 text-light'></i></a>";
              if ($vapaa > 0 && strtotime($startDate) > strtotime(date('Y-m-d'))) {
                echo "<a href='reserve.php?id=$id' class='btn btn-success m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>  </a>";
              } else {
                if ($vapaa == 0) {
                  echo "<a href='#' class='btn btn-danger m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>" . translate('all_places_reserved') . "</a>";
                } else {
                  echo "<a href='#' class='btn btn-danger m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>" . translate('reservations_ended') . "</a>";
                }
              }
              echo "</div>
                </div>
              </div>
            </div>";

              $counter++;
            }
          } elseif ($result->num_rows > 0 && $loggedIn === 'guide') {
            //  render only tours for guides
            $guideSql = "SELECT * FROM tours_guides LEFT JOIN tours ON tours_guides.tour_id = tours.id WHERE guide_id = $_SESSION[user_id]";
            $guideResult = my_query($guideSql);
            $counter = 0;
            while ($row = $guideResult->fetch_assoc()) {
              // Extract fields
              $id = $row['id'];
              $sql2 = "SELECT * FROM translations WHERE language = '$_SESSION[lang]' AND tour_id = $id";
              $result2 = my_query($sql2);
              $row2 = $result2->fetch_assoc();
              $name = getTranslation($id, 'name', $_SESSION['lang']);
              $title = getTranslation($id, 'title', $_SESSION['lang']);
              $summary = getTranslation($id, 'summary', $_SESSION['lang']);
              $description = getTranslation($id, 'description', $_SESSION['lang']);
              $location = htmlspecialchars($row['location']);
              $startDate = htmlspecialchars($row['startDate']);
              $groupSize = htmlspecialchars($row['groupSize']);
              $price = htmlspecialchars($row['price']);
              $places = htmlspecialchars($row['places']);
              $duration = htmlspecialchars($row['duration']);
              $tourImage = htmlspecialchars($row['tourImage']);

              $sql_free = "SELECT * FROM reservations WHERE tour_id = $id";
              $result_free = my_query($sql_free);
              $vapaa = $groupSize - $result_free->num_rows;

              // Start a new row every 3 cards
              if ($counter % 3 == 0 && $counter > 0) {
                echo "</div><div class='row'>";
              }

              $reviewSql = "SELECT AVG(rating) as rating FROM reviews WHERE tour_id = $id";
              $reviewResult = my_query($reviewSql);
              $reviewRow = $reviewResult->fetch_assoc();

              $votesSql = "SELECT COUNT(*) as votes FROM reviews WHERE tour_id = $id";
              $votesResult = my_query($votesSql);
              $votesRow = $votesResult->fetch_assoc();
              $voter = $votesRow['votes'];

              $rating = intval($reviewRow['rating']) . " <i class= 'fas fa-star text-light'></i>" . " | " . $voter . " <i class='fas fa-user text-light'></i>";

              echo "<div class='col-md-4 mb-4'>
            <div class='card shadow-lg shine-effect'>
              <img src='profiilikuvat/tours/$tourImage' class='card-img-top' alt='$name'>
              <div class='card-body'>
                <h5 class='card-title fs-2 text-primary'>$name</h5>
                <p class='card-text'><i class='fas fa-info'> </i><strong> $summary</strong></p>
                <p class='card-text'><strong><i class='far fa-clock'></i> Kesto: </strong> $duration tuntia</p>
                <p class='card-text'><strong><i class='fas fa-map-marker-alt'></i> Kiertueen paikat:</strong> $places</p>
                <p class='card-text'><strong><i class='fas fa-users'></i> Max osallistujamäärä: </strong><span class ='badge text-bg-warning fs-6'> $groupSize </span></p>
                <p class='card-text'><strong><i class='fas fa-users'></i> Vapaita paikkoja: </strong><span class ='badge text-bg-warning fs-6'> $vapaa </span></p>
                <div class='card-footer rounded'>
                  <small class='text-body-secondary'><p class='card-text badge text-bg-secondary fs-6 mb-3'>Hinta: $price €</p></small>
                  <small class='text-body-secondary'><p class='card-text fs-6 text-primary-emphasis '><i class='fas fa-calendar'></i> $startDate</p></small>
                  <p class='card-text mt-2 text-end'><strong></strong><span class ='badge text-bg-primary'> $rating</span></p>
                </div>
                <div class='text-end'>
                  <a href='tour.php?id=$id' class='btn btn-primary m-1'> <i class='fas fa-binoculars fs-5 text-light'></i></a>
                 </div>
              </div>
            </div>
          </div>";

              $counter++;
            }
          }

          ?>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>


  <!-- Include Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <!-- filter tours with price range useing form-range Start-->

  <script>
    // Function to update minimum price display
    function updateMinValue() {
      var minValue = document.getElementById("min_price").value;
      document.getElementById("min_value").innerText = minValue;
    }

    // Function to update maximum price display
    function updateMaxValue() {
      var maxValue = document.getElementById("max_price").value;
      document.getElementById("max_value").innerText = maxValue;
    }

    // Initialize displayed values on page load
    document.addEventListener("DOMContentLoaded", function() {
      updateMinValue();
      updateMaxValue();
    });
  </script>
  <!-- filter tours with price range useing form-range End-->

</body>

</html>