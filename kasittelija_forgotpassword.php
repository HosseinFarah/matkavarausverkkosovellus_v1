<?php
$display = "d-none";
$message = "";
$success = "success";
$ilmoitukset['okMsg'] = translate('okMsg');

/* ALOITUS */  
if (isset($_POST['painike'])){
   foreach ($_POST as $kentta => $arvo) {
      if (in_array($kentta, $pakolliset) and empty($arvo)) {
          $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
          }
      else {
         if (isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
            $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
            }
         else {
            if (is_array($arvo)) $$kentta = $arvo;
            else $$kentta = $yhteys->real_escape_string(strip_tags(trim($arvo)));
            } 
         }
      }
   debuggeri($errors);
   if (!$errors){
      $lisattiin_token = false;
      $query = "SELECT id FROM users WHERE email = '$email'";
      $result = $yhteys->query($query);
      if(!$result) die("Tietokantayhteys ei toimi: ".mysqli_error($connection));
      if (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $virheilmoitukset['accountNotExistErr'];
         $success = "danger";
         $display = "d-block";
         }
      else {
         list($id) = $result->fetch_row();
         $token = bin2hex(random_bytes(50));
         //$voimassa = date('Y-m-d', strtotime("+1 day"));
         $voimassa = date('Y-m-d');
         $msg = translate('resetPasswordMsg').'<br> <br>';
         $msg.= "<a href='http://$PALVELIN/$LINKKI_RESETPASSWORD?token=$token'>".translate('new_password')."</a><br>";
         // $msg.= "<a href='http://$PALVELIN/$PALVELU/$LINKKI_RESETPASSWORD?token=$token'>Uusi salasana</a><br>";
         $subject = translate('your_password');
         $lahetys = posti($email,$msg,$subject);
         if ($lahetys) {
         /* Lisää resetpassword_tokens tauluun id ja token */
            $query = "INSERT INTO reset_password_tokens (users_id,token,voimassa) VALUES ($id,'$token','$voimassa') 
                      ON DUPLICATE KEY UPDATE token = '$token',voimassa = '$voimassa'";
            debuggeri($query);          
            $result = $yhteys->query($query);
            $lisattiin_token = $yhteys->affected_rows;
            debuggeri("Lisättiin $lisattiin_token token.");
            }
         else {
            $message = $virheilmoitukset['emailErr'];
            $success = "danger";
            }
         if ($lisattiin_token){
            $message = $ilmoitukset['okMsg'];
            }   
         $display = "d-block";
         }
      }  
   }   
?>