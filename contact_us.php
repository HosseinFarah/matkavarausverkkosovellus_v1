<?php
$title = "Ota yhteyttä";
include 'header.php';

$kentat = array('fullname', 'title', 'message', 'email');
$kentat_suomi = array('Etunimi ja sukunimi', 'Otsikko', 'Viesti', 'Sähköposti');
$pakolliset = array('fullname', 'title', 'message', 'email');

include 'virheilmoitukset.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";

include 'contact_us_post.php';
?>

<!-- Ota yhteyttä sivu fullname title message email -->

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Ota yhteyttä</h1>
                <p>Voit ottaa yhteyttä meihin alla olevalla lomakkeella. Vastaamme mahdollisimman pian.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php
                if (isset($message)) {
                    echo "<div class='alert alert-info'>$message</div>";
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <form action="contact_us.php" method="post" class="mt-3 needs-validation" novalidate>
                    <div class="form-group">
                        <label for="fullname">Etunimi ja sukunimi</label>
                        <input type="text" class="form-control <?= is_invalid('fullname'); ?>" id="fullname" name="fullname"
                        value="<?= arvo("fullname"); ?>" pattern="<?= pattern('fullname'); ?>"
                        required>
                        <div class="invalid-feedback">
                            <?= $errors['fullname'] ?? ""; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Otsikko</label>
                        <input type="text" class="form-control <?= is_invalid('title'); ?>" id="title" name="title" 
                        value="<?= arvo("title"); ?>" pattern="<?= pattern('title'); ?>"
                        required>
                        <div class="invalid-feedback">
                            <?= $errors['title'] ?? ""; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Viesti</label>
                        <textarea class="form-control <?= is_invalid('message'); ?>" id="message" name="message" rows="3"
                        pattern="<?= pattern('description'); ?>"
                        required><?= arvo("message"); ?></textarea>
                        <div class="invalid-feedback">
                            <?= $errors['message'] ?? ""; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Sähköposti</label>
                        <input type="email" class="form-control <?= is_invalid('email'); ?>" id="email" name="email" 
                        value="<?= arvo("email"); ?>" pattern="<?= pattern('email'); ?>"
                        required>
                        <div class="invalid-feedback">
                            <?= $errors['email'] ?? ""; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3" name="btn">Lähetä</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>
</body>