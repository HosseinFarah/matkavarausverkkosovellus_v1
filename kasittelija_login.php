<?php
/* ALOITUS */
$display = "d-none";
$message = "";
$attempt_msg = "";
$success = "success";
$ilmoitukset['errorMsg'] = translate('login_failed');
// hfk get debuge for login information
// debuggeri("POST:" . var_export($_POST, true));

if (isset($_POST['painike'])) {
   //////////////////////////////////////// check for login attempts - Start
   $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;

   // Check if login attempts are 5 or more
   if ($_SESSION['login_attempts'] >= 5) {
      // Store the current time if this is the first time hitting 5 attempts
      if (!isset($_SESSION['last_attempt_time'])) {
         $_SESSION['last_attempt_time'] = time();
      }
      // Check if 60 seconds have passed
      if (time() - $_SESSION['last_attempt_time'] >= 60) {
         // Reset login attempts after 60 seconds
         $_SESSION['login_attempts'] = 0;
         unset($_SESSION['last_attempt_time']);
      }
   }
   //////////////////////////////////////// check for login attempts - End

   foreach ($_POST as $kentta => $arvo) {
      if (in_array($kentta, $pakolliset) and empty($arvo)) {
         $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
      } else {
         if (isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
            $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
         } else {
            if (is_array($arvo)) $$kentta = $arvo;
            else $$kentta = $yhteys->real_escape_string(strip_tags(trim($arvo)));
         }
      }
   }


   // $rememberme = isset($rememberme) ? true : false;
   // update in 27.9.2024 korjattu rememberme
   $rememberme = $rememberme ?? false;
   if ($errors) debuggeri($errors);
   if (!$errors) {
      $query = "SELECT users.id,password,is_active,name FROM users LEFT JOIN roles ON role = roles.id WHERE email = '$email'";
      debuggeri($query);
      $result = $yhteys->query($query);
      if (!$result) die("Tietokantayhteys ei toimi: " . mysqli_error($connection));
      if (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $ilmoitukset['errorMsg'];
         $success = "danger";
         $display = "d-block";
      } else {
         [$id, $password_hash, $is_active, $role] = $result->fetch_row();
         if (password_verify($password, $password_hash)) {
            if ($is_active) {
               if (!session_id()){
                  // logout user if session is not active after 10 min
                  session_set_cookie_params(REMEMBERMEDURATION);
                  session_start();
               }
               $_SESSION["loggedIn"] = $role;
               $_SESSION["user_id"] = $id;
               if ($rememberme) rememberme($id);
               if (isset($_SESSION['next_page'])) {
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
               } else $location = OLETUSSIVU;
               header("location: $location");
               exit;
            } else {
               $errors['email'] = $virheilmoitukset['verificationRequiredErr'];
            }
         } else {
            $errors['password'] = $virheilmoitukset['emailPwdErr'];
         }
      }
   }
}
