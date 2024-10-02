<?php
include_once "lang.php";     
$title = translate('about_us');
$_SESSION['lang'] ??= 'fi';
include 'header.php';
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center"><?php echo translate('about_us'); ?></h1>
            <p class="text-center"><?php echo translate('about_us_desc_2'); ?></p>
            <p class="text-center"><?php echo translate('start_your_journey'); ?></p>
        </div>

    </div>
</div>
<?php
include 'footer.php';
?>
</body>

