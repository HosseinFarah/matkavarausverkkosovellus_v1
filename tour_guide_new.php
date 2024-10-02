<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$PALVELIN = $_SERVER['HTTP_HOST'];
include_once 'lang.php';
$title = translate('add_guide');
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';


if (isset($_POST['new_guide'])) {
    $guide_id = intval($_POST['guide_id']);
    $tour_id = intval($_POST['tour_id']);
    // check if guide is already added to the tour
    $sql_check = "SELECT * FROM tours_guides WHERE guide_id='$guide_id' AND tour_id='$tour_id'";
    $result = my_query($sql_check);
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='alert alert-danger'>Matkaopas on jo lisätty tälle matkalle</div>";
        header("refresh:1;url=tour_guides.php");
        exit;
    } else {
        // else register the guide to the tour
        $sql = "INSERT INTO tours_guides (tour_id, guide_id) VALUES ('$tour_id', '$guide_id')";
        my_query($sql);
        header("Location: tour_guides.php");
    }
}
if ($loggedIn === 'admin') {
?>
    <!-- set new tour guide from availables users for available tours -->

    <div class="container mt-3">
        <h1 class="badge text-bg-danger fs-3">Lisää matkaopas</h1>
        <form method="post">
            <div class="form-group">
                <label for="guide_id">Matkaopas</label>
                <select class="form-select" name="guide_id" id="guide_id" required>
                    <option value="">Valitse matkaopas</option>
                    <?php
                    $sql = "SELECT users.id AS user_id, users.*, roles.* FROM users LEFT JOIN roles ON users.role=roles.id WHERE roles.name='guide'";
                    $result = my_query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fullname = $row['firstname'] . " " . $row['lastname'];
                        foreach ($row as $key => $value) {
                            if ($key == 'user_id') {
                                echo "<option value='{$value}'>$fullname</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tour_id">Matka</label>
                <select class="form-select" name="tour_id" id="tour_id" required>
                    <option value="">Valitse matka</option>
                    <?php
                    $sql = "SELECT * FROM tours";
                    $result = my_query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="new_guide">Lisää</button>
        </form>
    </div>
<?php
} else {
    header("Location: index.php");
    exit;
}
