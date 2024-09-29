<?php
$title = "Huvimatkojen palvelut- Etusivu";
include 'header.php';
include 'posti.php';
$sql = "SELECT * FROM tours";
$result = my_query($sql);
$slider = ["(1).jpg", "(2).jpg", "(3).jpg", "(4).jpg", "(5).jpg", "(6).jpg", "(7).jpg", "(8).jpg", "(9).jpg"];
$searchSql = "SELECT * FROM tours";
$searchResult = my_query($searchSql);


// if (isset($_POST['contact'])) {
//   $name = $_POST['name'];
//   $email = $_POST['email'];
//   $subject = $_POST['subject'];
//   $message = $_POST['message'];
//   $newsletter = isset($_POST['newsletter']) ? $_POST['newsletter'] : 'no';
//   $support_email =  "h.farah61@gmail.com";

//   $msg = "Nimi: $name\nSähköposti: $email\nAihe: $subject\nViesti: $message\n";
//   if ($newsletter == 'yes') {
//     $msg .= "Haluan tilata Puutarhaliike Neilikan uutiskirjeen\n";
//   }
//   posti($support_email, $msg, "Yhteydenottopyyntö");
//   echo "<script>alert('Kiitos yhteydenotostasi!');</script>";
// }

?>

<body>
  <div class="content">
    <?php
    if (isset($_GET['search'])) {
      $search = $_GET['search'];
      $searchSql = "SELECT * FROM tours WHERE name LIKE '%$search%' OR title LIKE '%$search%' OR summary LIKE '%$search%' OR description LIKE '%$search%'";
      $result = my_query($searchSql);
      if ($result->num_rows == 0) {
        echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>Ei hakutuloksia</h2></div></div></div>";
      } else {
        echo "<div class='container my-5'><div class='row'><div class='col-md-12'><h2 class='text-center'>Hakutulokset</h2></div></div></div>";
      }
    }
    ?>

    <div class="container my-5">
      <div class="row">
        <div class="col-md-12">
          <h1 class="text-center">Tervetuloa Huvimatkojen sivuille</h1>
          <p class="text-center">Täältä löydät kaikki matkamme</p>
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
        <div class="col-md-12">
          <form method="GET">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Hae matkaa" name="search">
              <button class="btn btn-primary" type="submit">Hae</button>
            </div>
          </form>
        </div>
      </div>

      <div class="container my-5">
        <div class="row">
          <div class="col-md-12">
            <h2 class="text-center">Matkamme</h2>
          </div>
        </div>
      </div>

      <div class="container my-5">
        <div class="row">
          <?php
          if ($result->num_rows > 0 && $loggedIn != 'guide') {
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
              // Extract fields
              $id = $row['id'];
              $name = htmlspecialchars($row['name']);
              $title = htmlspecialchars($row['title']);
              $summary = htmlspecialchars($row['summary']);
              $description = htmlspecialchars($row['description']);
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
                  <p class='card-text'><i class='far fa-clock'></i> $duration tuntia</p>
                  <p class='card-text'><i class='fas fa-map-marker-alt'></i> $places</p>
                  <p class='card-text'><strong><i class='fas fa-users'></i> Max osallistujamäärä: </strong><span class ='badge text-bg-warning fs-6'> $groupSize </span></p>
                  <p class='card-text'><strong><i class='fas fa-users'></i> Vapaita paikkoja: </strong><span class ='badge text-bg-warning fs-6'> $vapaa </span></p>
                  <div class='card-footer rounded'>
                    <small class='text-body-secondary'><p class='card-text badge text-bg-secondary fs-6 mb-3'>Hinta: $price €</p></small>
                    <small class='text-body-secondary'><p class='card-text fs-6 text-primary-emphasis '><i class='fas fa-calendar'></i> $startDate</p></small>
                    <p class='card-text mt-2 text-end'><strong></strong><span class ='badge text-bg-primary'> $rating</span></p>
                  </div>
                  <div class='text-end'>
                    <a href='tour.php?id=$id' class='btn btn-primary m-1'> <i class='fas fa-binoculars fs-5 text-light'></i></a>";
              if ($vapaa > 0 && strtotime($startDate) > strtotime(date('Y-m-d'))) {
                echo "<a href='reserve.php?id=$id' class='btn btn-success m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>  </a>";
              } else {
                if ($vapaa == 0) {
                  echo "<a href='#' class='btn btn-danger m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>Kaikki paikat varattu</a>";
                } else {
                  echo "<a href='#' class='btn btn-danger m-1'><i class='fas fa-cart-plus fs-5 text-light'></i>Varauksia on päättynyt</a>";
                }
              }
              echo "</div>
                </div>
              </div>
            </div>";

              $counter++;
            }
          } else {
          //  render only tours for guides
          $guideSql = "SELECT * FROM tours_guides LEFT JOIN tours ON tours_guides.tour_id = tours.id WHERE guide_id = $_SESSION[user_id]";
          $guideResult = my_query($guideSql);
          $counter = 0;
          while ($row = $guideResult->fetch_assoc()) {
            // Extract fields
            $id = $row['id'];
            $name = htmlspecialchars($row['name']);
            $title = htmlspecialchars($row['title']);
            $summary = htmlspecialchars($row['summary']);
            $description = htmlspecialchars($row['description']);
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
                <p class='card-text'><i class='far fa-clock'></i> $duration tuntia</p>
                <p class='card-text'><i class='fas fa-map-marker-alt'></i> $places</p>
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
</body>

</html>