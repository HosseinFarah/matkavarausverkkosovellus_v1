<?php 
include_once "lang.php";
$title = translate('new_password');
//$css = 'kuvagalleria.css';
$kentat = ['password','passoword2'];
$kentat_suomi = ['salasana','salasana'];
$pakolliset = ['password','password2'];
include "virheilmoitukset.php";
$virheilmoitukset_json = json_encode($virheilmoitukset);
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php";
// include "db.php";
include "kasittelija_resetpassword.php";
?>
<div class="container"> 

<?php if (!$message) { ?>    
<form method="post" class="mb-3 needs-validation" novalidate>
<fieldset>
<legend><?= translate('new_password'); ?></legend>

<div class="row">
<label for="password" class="col-sm-4 form-label"><?= translate('password'); ?></label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password" 
       placeholder="<?= translate('password'); ?>" pattern="<?= pattern('password'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="password2" class="text-nowrap col-sm-4 form-label"><?= translate('password2'); ?></label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password2'); ?>" name="password2" id="password2" 
       placeholder="<?= translate('password2'); ?>" pattern="<?= pattern('password2'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password2'] ?? ""; ?>    
</div>
</div>
</div>
<button name='painike' type="submit" class="mt-3 float-end btn btn-primary"><?= translate('save_password'); ?></button>
</fieldset>

</form>
<?php } ?>
<div  id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<p>
<a href="forgotpassword.php" class="<?= $display ?? ""; ?>"><?= translate('request_password'); ?></a>
</p>
</div>
<?php include "footer.php"; ?>