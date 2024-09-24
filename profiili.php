<?php
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
$title = 'Profiili';
include "header.php";
$css = 'site.css';




if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $yhteys->query($query);
    if (!$result) die("Tietokantayhteys ei toimi: " . mysqli_error($yhteys));
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $name = $row['firstname'] . " " . $row['lastname'];
    $phone = $row['mobilenumber'];
    $photo = $row['image'];
    $kaupunki = $row['city'];
    $katuosoite = $row['address'];
    $postinumero = $row['postcode'];
}
?>

<!-- profile page -->

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            z-index: 1;
            height: 100%;
            width: 80px;
            position: fixed;
            left: 0;
            background-color: #555;
            color: #fff;
            padding-top: 1rem;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .nav-link {
            color: #ddd;
            padding: 10px;
            margin: 0 15px;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            text-align: center;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .content {
            margin-left: 80px;
            padding: 1px;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 80px;
        }

        .toggle-btn {
            position: fixed;
            top: 70%;
            background-color: #555;
            color: white;
            border: none;

            cursor: pointer;
        }

        .collapsed+.toggle-btn {
            left: 0px;
        }

        @media screen and (max-height: 600px) {
            .sidebar {
                width: 80px;
            }
            
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 class="text-center"><a class="toggle-btn" id="toggleBtn"></a></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" data-content="ttt"></i><span>Menu</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-content="profileContent"><i class="bi bi-person"></i><span></span></a>
            </li>
            <?php
            if ($loggedIn === 'user') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-content="comentContent"><i class="bi bi-chat"></i><span></span></a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="#" data-content="reservedContent"><i class="bi bi-calendar-check"></i><span></span></a>
            </li>
        </ul>
    </div>

    <!-- Toggle Button -->


    <!-- Main Content -->
    <div class="content" id="content">
        <div id="profileContent" class="content-section">
            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <img src="<?= "http://$PALVELIN/profiilikuvat/" . $photo ?>" style="width: 300px ;" class="card-img-top rounded" alt="<?= $photo ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Nimi: <?= $name ?></h5>
                                <p class="card-text">Sähköposti: <?= $email ?></p>
                                <p class="card-text">Puhelinnumero: <?= $phone ?></p>
                                <p class="card-text">kaupunki: <?= $kaupunki ?></p>
                                <p class="card-text">katuosoite: <?= $katuosoite ?></p>
                                <p class="card-text">postinumero: <?= $postinumero ?></p>
                                <a href="muokkaaprofiilia.php?id=<?= $user_id ?>" class="btn btn-primary">Muokkaa profiilia</a>
                                <a href="poistu.php" class="btn btn-primary">Kirjaudu ulos</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        </div>
        <div id="comentContent" class="content-section" style="display: none;">
            <?php
            if ($loggedIn === 'admin') {
            ?>
                <hr>
            <?php
            } else {
            ?>
                <div class="container">
                    <h2 class="badge text-bg-danger text-light fs-3">Arvostelut</h2>
                    <div class="row flex-nowrap overflow-x-scroll">
                        <!-- show this users all reviews for tours -->
                        <?php
                        $sql = "SELECT * FROM `reviews` WHERE `user_id` = $user_id";
                        $result = my_query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $tour_id = $row['tour_id'];
                                $rating = $row['rating'];
                                $comment = $row['review'];
                                $sql = "SELECT * FROM `tours` WHERE `id` = $tour_id";
                                $result2 = my_query($sql);
                                if ($result2 && $result2->num_rows > 0) {
                                    $row2 = $result2->fetch_assoc();
                                    $tour_id = $row2['id'];
                                    $tour_name = $row2['name'];
                                }
                        ?>
                                <div class="card mb-3 col-md-4 m-2">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $tour_name ?></h5>
                                        <!-- replace arvosana with <i class="fas fa-star"></i> stars are 5 starts and based on rating value starts color change to green -->
                                        <p class="card-text"><?php for ($i = 1; $i <= 5; $i++) {
                                                                    if ($i <= $rating) {
                                                                        echo "<i class='fas fa-star text-success'></i>";
                                                                    } else {
                                                                        echo "<i class='far fa-star'></i>";
                                                                    }
                                                                } ?></p>


                                        <p class="card-text"><?= $comment ?></p>
                                        <a href="tour.php?id=<?= $tour_id ?>" class="btn btn-primary">Näytä matka</a>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p class='badge text-bg-danger fs-6'>Et ole tehnyt yhtään arvostelua</p>";
                        }
                        ?>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div id="reservedContent" class="content-section" style="display: none;">
            <!-- admin access part -->
            <div class="col-md-8">
                <?php if ($loggedIn === 'admin') { ?>
                    <h2>Admin-Part</h2>
                    <a href="kayttajat.php" class="btn btn-primary m-1">active</a>
                    <a href="tours.php" class="btn btn-primary m-1">Kaikki matkat</a>
                    <a href="users.php" class="btn btn-primary m-1">Kaikki käyttäjät</a>
                    <a href="tour_new.php" class="btn btn-primary m-1">Lisää uusi matka</a>
                    <a href="user_new.php" class="btn btn-primary m-1">Lisää uusi käyttäjä</a>
                    <a href="reviews.php" class="btn btn-primary mt-1">Kaikki arvostelut</a>
                    <a href="reserved.php" class="btn btn-primary mt-1">Kaikki varaukset</a>
                <?php } else {
                ?>
                    <h2 class="badge text-bg-danger text-light fs-3">Tilaukset</h2>
                    <?php
                    $sql = "SELECT * FROM `reservations` WHERE `user_id` = $user_id";
                    $result = my_query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $tour_id = $row['tour_id'];
                            $reservation_id = $row['reservation_id'];
                            $sql = "SELECT * FROM `tours` WHERE `id` = $tour_id";
                            $result2 = my_query($sql);
                            if ($result2 && $result2->num_rows > 0) {
                                $row2 = $result2->fetch_assoc();
                                $tour_id = $row2['id'];
                                $tour_name = $row2['name'];
                                $tour_image = $row2['tourImage'];
                                $tour_summary = $row2['summary'];
                                $tour_price = $row2['price'];
                                $tour_start_date = $row2['startDate'];
                                $tour_start_date = date("d.m.Y", strtotime($tour_start_date));
                                $tour_image = "http://$PALVELIN/profiilikuvat/tours/" . $tour_image;
                                echo "<div class='card mb-3'>
                            <div class='row g-0'>
                                <div class='col-md-4'>
                                    <img src='$tour_image' class='img-fluid rounded-start' alt='$tour_name'>
                                </div>
                                <div class='col-md-8'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>$tour_name</h5>
                                        <p class='card-text'>$tour_summary</p>
                                        <p class='card-text'><strong>Hinta:</strong> $tour_price €</p>
                                        <p class='card-text'><strong>Alkamispäivä:</strong> $tour_start_date</p>
                                        <a href='tour.php?id=$tour_id' class='btn btn-primary'>Näytä matka</a>
                                    </div>
                                </div>
                            </div>
                        </div>";
                            }
                        }
                    } else {
                        echo "<p class='badge text-bg-danger fs-6'>Et ole tehnyt yhtään varausta</p>";
                    }
                    ?>
                <?php }
                ?>
            </div>
        </div>
        <div id="comentContent" class="content-section" style="display: none;">

        </div>
    </div>

    <!-- Bootstrap JS (for collapse functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleBtn');
        const navLinks = document.querySelectorAll('.nav-link');
        const contentSections = document.querySelectorAll('.content-section');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
        });

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const contentId = e.target.closest('.nav-link').getAttribute('data-content');

                contentSections.forEach(section => {
                    section.style.display = 'none';
                });

                document.getElementById(contentId).style.display = 'block';
            });
        });
    </script>

    <?php include "footer.php"; ?>
</body>

</html>