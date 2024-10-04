<?php
ob_start();
$PALVELIN = $_SERVER['HTTP_HOST'];
include_once 'lang.php';
$title = "Tour Translate";
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
include 'header.php';
$sql = "SELECT * FROM tours";
$result = $yhteys->query($sql);
if ($result->num_rows > 0) {
    $tours = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['painike'])) {
    // Escaping inputs to handle single quotes
    $tour_name = mysqli_real_escape_string($yhteys, $_POST['name']);
    $tour_title = mysqli_real_escape_string($yhteys, $_POST['title']);
    $tour_summary = mysqli_real_escape_string($yhteys, $_POST['summary']);
    $tour_description = mysqli_real_escape_string($yhteys, $_POST['description']);
    $tour_id = intval($_GET['tourname']);
    $language = mysqli_real_escape_string($yhteys, $_GET['language']);
    
    $sql = "SELECT * FROM translations WHERE tour_id = $tour_id AND language = '$language'";
    $result = my_query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Update queries
        $sql = "UPDATE translations SET content = '$tour_name' WHERE tour_id = $tour_id AND language = '$language' AND field_name = 'name'";
        my_query($sql);
        
        $sql = "UPDATE translations SET content = '$tour_title' WHERE tour_id = $tour_id AND language = '$language' AND field_name = 'title'";
        my_query($sql);
        
        $sql = "UPDATE translations SET content = '$tour_summary' WHERE tour_id = $tour_id AND language = '$language' AND field_name = 'summary'";
        my_query($sql);
        
        $sql = "UPDATE translations SET content = '$tour_description' WHERE tour_id = $tour_id AND language = '$language' AND field_name = 'description'";
        my_query($sql);
    } else {
        // Insert queries
        $sql = "INSERT INTO translations (tour_id, language, field_name, content) VALUES ($tour_id, '$language', 'name', '$tour_name')";
        my_query($sql);
        
        $sql = "INSERT INTO translations (tour_id, language, field_name, content) VALUES ($tour_id, '$language', 'title', '$tour_title')";
        my_query($sql);
        
        $sql = "INSERT INTO translations (tour_id, language, field_name, content) VALUES ($tour_id, '$language', 'summary', '$tour_summary')";
        my_query($sql);
        
        $sql = "INSERT INTO translations (tour_id, language, field_name, content) VALUES ($tour_id, '$language', 'description', '$tour_description')";
        my_query($sql);
    }
}






if($loggedIn =='admin') {
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= translate('tour') ?></h1>
            <form method="get">
                <!-- render just for tourname in check box and language in checkBox -->
                <div class="form-group">
                    <label for="tourname"><?= translate('tourname') ?></label>
                    <select class="form-control" name="tourname" id="tourname" required>
                        <option value=""><?= translate('select_tour') ?></option>
                        <?php
                        foreach ($tours as $tour) {
                            echo "<option value='{$tour['id']}'>{$tour['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="language"><?= translate('language') ?></label>
                    <select class="form-control" name="language" id="language" required>
                        <option value=""><?= translate('select_language') ?></option>
                        <option value="en">English</option>
                        <option value="fi">Finnish</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><?= translate('translate') ?></button>
            </form>
            <hr>
            <?php
            if (isset($_GET['tourname']) && isset($_GET['language'])) {
                $tour_id = intval($_GET['tourname']);
                $language = $_GET['language'];
                $sql = "SELECT * FROM translations WHERE tour_id = $tour_id AND language = '$language'";
                $result = my_query($sql);
                if ($result->num_rows > 0) {
                    $translations = $result->fetch_all(MYSQLI_ASSOC);
                    // get name value from translations table where field_name is name and get title value from translations table where field_name is title and get description value from translations table where field_name is description
                    switch ($language) {
                        case 'en':
                            $name = $title = $description = '';
                            foreach ($translations as $translation) {
                                if ($translation['field_name'] == 'name') {
                                    $name = $translation['content'];
                                } elseif ($translation['field_name'] == 'title') {
                                    $tour_title = $translation['content'];
                                } elseif ($translation['field_name'] == 'summary') {
                                    $summary = $translation['content'];
                                } elseif ($translation['field_name'] == 'description') {
                                    $description = $translation['content'];
                                }
                            }
                            break;
                        case 'fi':
                            $name = $title = $description = '';
                            foreach ($translations as $translation) {
                                if ($translation['field_name'] == 'name') {
                                    $name = $translation['content'];
                                } elseif ($translation['field_name'] == 'title') {
                                    $tour_title = $translation['content'];
                                } elseif ($translation['field_name'] == 'summary') {
                                    $summary = $translation['content'];
                                } elseif ($translation['field_name'] == 'description') {
                                    $description = $translation['content'];
                                }
                            }
                            break;
                    }
                }
            ?>
                <form method="post">
                    <div class="form-group">
                        <label for="name"><?= translate('tour_name') ?></label>
                        <input type="text" class="form-control" name="name" id="name" value="<?= $name ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="title"><?= translate('title') ?></label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= $tour_title ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="summary"><?= translate('summary') ?></label>
                        <input type="text" class="form-control" name="summary" id="summary" value="<?= $summary ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="description"><?= translate('description') ?></label>
                        <textarea class="form-control" name="description" id="description"><?= $description ?? '' ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="painike"><?= translate('save') ?></button>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include 'footer.php';
} else {
    header("Location: index.php");
    exit;
}
ob_end_flush();
?>