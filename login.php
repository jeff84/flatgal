<?php
session_start();
$sid = session_id();
if (isset($_POST['pass']) && ($_SESSION['token'] == $_POST['token'])){
  include("galerie/".$_GET['galerie']."/pass.php");
  if (password_verify($_POST['pass'], $galpass)){
    $_SESSION[$_GET['galerie']] = 'logedin';
    $_SESSION['finger'] = password_hash($_SERVER['HTTP_USER_AGENT'].substr($_SERVER["REMOTE_ADDR"],0,7), PASSWORD_BCRYPT);
    echo '<script type="text/javascript"> setTimeout("self.location.href=\'index.php?galerie='.$_GET['galerie'].'\'",2000); </script>';
    echo 'Falls sie nicht automatisch weitergeleitet werden <a href="index.php?galerie='.$_GET['galerie'].'" >klick</a>';
  }
  else {
    'Passwort falsch. <a href="index.php" >Zurueck</a>';
  }
}
else {
  exit('Fehler <a href="index.php" >Zurueck</a>');
}

?>
