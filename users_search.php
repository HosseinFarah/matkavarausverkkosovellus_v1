<?php
ob_start();
include "header.php";
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "kaikki tilaukset";
$loggedIn = secure_page();

// filter usres by role with combo box with values admin guide user
if (isset($_POST['roleBtn']) && isset($_POST['role'])) {
    $role = $_POST['role'];
    $sql = "SELECT users.id, users.firstname, users.lastname, users.email, users.city, roles.name as role_name FROM users LEFT JOIN roles ON users.role = roles.id WHERE users.role = ?";
    $stmt = $yhteys->prepare($sql);
    $stmt->bind_param("i", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $search = trim($_POST['search']) ?? '';
        $sql = "SELECT users.id, users.firstname, users.lastname, users.email, users.city,  roles.name as role_name FROM users LEFT JOIN roles ON users.role = roles.id WHERE users.firstname LIKE ? OR users.lastname LIKE ? OR users.city LIKE ? OR roles.name LIKE ? OR users.email LIKE ?";
        $stmt = $yhteys->prepare($sql);

        $searchParam = "%{$search}%";
        $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } elseif (isset($_POST['roleBtn']) && isset($_POST['role'])) {
        $role = $_POST['role'];
        $sql = "SELECT users.id, users.firstname, users.lastname, users.email, users.city, roles.name as role_name FROM users LEFT JOIN roles ON users.role = roles.id WHERE users.role = ?";
        $stmt = $yhteys->prepare($sql);
        $stmt->bind_param("i", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } elseif (isset($_POST['clearBtn'])) {
        $sql = "SELECT users.id, users.firstname, users.lastname,users.email, users.city, roles.name as role_name FROM users LEFT JOIN roles ON users.role = roles.id";
        $result = my_query($sql);
        header("Location: users_search.php");
    }
}
if ($loggedIn == 'admin') {
?>

    <body>
        <div class="content">
            <div class="container mt-5 mb-5">
                <!-- <a href="profiili.php" class="fs-3"><i class="fas fa-home text-warning mb-3"></i> Asetukset</a> -->

                <!-- search for a reservation by fullname or tour name or reservation_id or price or created date -->
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="search" class="form-control" placeholder="Hae tilausta">
                            <p class="fs-6 mt-2"><i class="fas fa-info text-warning mb-3"></i> Hae käyttäjää sähköpostin, etunimen, sukunimen, kaupungin tai käyttäjäryhmän perusteella</p>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary m-2" name="reservationBtn">Hae</button>
                            <!-- make button for clear search -->
                            <button type="submit" class="btn btn-danger m-2" name="clearBtn" <?php if (empty($search)) echo "disabled"; ?>>Tyhjennä</button>

                        </div>
                    </div>
                </form>
                <!-- filter usres by role with combo box with values admin guide user-->
                <form method="post">
                    <div class="row d-flex justify-content-start mt-5">
                        <div class="col-md-4 text-start">
                            <select name="role" class="form-select" aria-label="Default select example">
                                <option value="0" disabled selected></option>
                                <option value="1">Käyttäjä</option>
                                <option value="2">Opas</option>
                                <option value="3">Admin</option>
                            </select>
                            <button type="submit" class="btn btn-primary m-2" name="roleBtn">Hae</button>
                            <button type="submit" class="btn btn-danger m-2" name="clearBtn" <?php if (empty($_POST['role'])) echo "disabled"; ?>>Tyhjennä</button>

                        </div>
                    </div>
                </form>

                <div class="container">
                    <div class="row mb-5">
                        <table class="table table-striped table-hover mb-5">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Sähköposti</th>
                                    <th scope="col">Etunimi</th>
                                    <th scope="col">Sukunimi</th>
                                    <th scope="col">Kaupunki</th>
                                    <th scope="col">Rooli</th>
                                    <th scope="col">Asetukset</th>

                                </tr>
                            </thead>
                            <?php
                            if (isset($result)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['firstname'] . "</td>";
                                    echo "<td>" . $row['lastname'] . "</td>";
                                    echo "<td>" . $row['city'] . "</td>";
                                    echo "<td>" . $row['role_name'] . "</td>";
                                    echo "<td><a href='user_edit.php?id=" . $row['id'] . "'><i class='fas fa-edit text-primary'></i></a></td>";
                                    echo "<td><a href='user_pois.php?id=" . $row['id'] . "' onclick='return confirm(\"Haluatko varmasti poistaa tämän tilauksen?\")'><i class='fas fa-trash-alt text-danger'></i></a></td>";
                                    echo "</tr>";
                                    echo "</thead>";
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            include "footer.php";
            ?>
        </div>
    </body>

<?php
ob_end_flush();
} else {
    header("Location: index.php");
    exit;
}
