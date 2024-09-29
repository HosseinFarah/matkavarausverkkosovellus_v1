<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$PALVELIN = $_SERVER['HTTP_HOST'];
$title = "Lisaä matkaopas";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';


if (isset($_POST['new_guide'])) {
    $guide_id = intval($_POST['guide_id']);
    $tour_id = intval($_POST['tour_id']);
    echo $guide_id;
    echo $tour_id;
    $sql = "INSERT INTO tours_guides (tour_id, guide_id) VALUES ('$tour_id', '$guide_id')";
    my_query($sql);
    header("Location: tour_guides.php");
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