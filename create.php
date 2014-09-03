<?php

function qThumb ( $bild, $thumbkant, $galname )
{
  $bildname = array_pop(explode("/", $bild));
  $orgbildinfo = getimagesize( $bild );
  $orgbildbrei = $orgbildinfo[0];
  $orgbildhoeh = $orgbildinfo[1];
  $orgbildkant = $orgbildbrei < $orgbildhoeh ? $orgbildbrei : $orgbildhoeh;
  $tempbild = imagecreatefromjpeg( $bild );
  $neuesbild = imagecreatetruecolor( $orgbildkant, $orgbildkant );
  
  if ( $orgbildbrei > $orgbildhoeh ){
    imagecopy( $neuesbild, $tempbild, 0, 0, round($orgbildbrei-$orgbildkant)/2, 0, $orgbildbrei, $orgbildhoeh );
  }
  else if ( $orgbildbrei <= $orgbildhoeh ){
    imagecopy( $neuesbild, $tempbild, 0, 0, 0, round($orgbildhoeh-$orgbildkant)/2, $orgbildbrei, $orgbildhoeh );
  }
  
  $thumbnail = imagecreatetruecolor( $thumbkant, $thumbkant );
  imagecopyresampled($thumbnail, $neuesbild, 0, 0, 0, 0, $thumbkant, $thumbkant, $orgbildkant, $orgbildkant);
  imagejpeg( $thumbnail, "galerie/".$galname."/thumbs/".$bildname, 80 );
  imagedestroy( $thumbnail );
  
}

function createDirs($galname){
  if (!is_dir("galerie/".$galname."/bilder")){
    mkdir("galerie/".$galname."/bilder");
  }
  if (!is_dir("galerie/".$galname."/thumbs")){
    mkdir("galerie/".$galname."/thumbs");
  }
}

function passSchutz($galname, $password){
  $line[0] = "<?php \n";
  $line[1] = '$schutz = TRUE;'."\n";
  $passhash = password_hash($password, PASSWORD_BCRYPT);
  $line[2] = '$galpass = \''.$passhash.'\';'."\n";
  $tokhash = substr(password_hash($galname.microtime(), PASSWORD_BCRYPT), 29);
  $line[3] = '$token = \''.$tokhash.'\';'."\n".'?>';
  $datei = fopen("galerie/".$galname."/pass.php","w");
  fwrite($datei, $line[0]);
  fwrite($datei, $line[1]);
  fwrite($datei, $line[2]);
  fwrite($datei, $line[3]);
  fclose($datei);
  return $tokhash;
}

function moveImages($galname){
  foreach(glob("galerie/".$galname."/*.jpg") as $bild){
    $bildname = array_pop(explode("/", $bild));
    rename($bild, "galerie/".$galname."/bilder/".$bildname);
  }
}

include("config.inc.php");
if (isset($_POST['galerie'], $_POST['pass'])){
  if ($_POST['pass'] == $pass){
    if (!is_dir("galerie/".$_POST['galerie'])){
      exit("Verzeichnis existiert nicht");
    }
    if (is_file("galerie/".$_POST['galerie']."/.ready")){
      exit("Galerie schon erstellt");
    }
    else {
      if ($_POST['schutz'] == "ja"){
        if (!isset($_POST['gal-pass'])){
          exit("Kein Galeriepasswort angegeben");
        }
        else if ($_POST['gal-pass'] != $_POST['gal-pass-bes']){
          exit("Zwei verschiedene Passwoerter");
        }
        else {
          $tokhash = passSchutz($_POST['galerie'], $_POST['gal-pass']);
        }
      }
      createDirs($_POST['galerie']);
      foreach(glob("galerie/".$_POST['galerie']."/*.jpg") as $bild){
        qThumb( $bild, 75, $_POST['galerie']);
      }
      moveImages($_POST['galerie']);
      touch("galerie/".$_POST['galerie']."/.ready");
      echo "Galerie erfolgreich erstellt</br>";
      echo "Token: ".$tokhash."</br>";
      $pfad = split('/', $_SERVER['PHP_SELF']);
      echo '<a href="index.php?galerie='.$_POST['galerie'].'&token='.$tokhash.'">http://'.$_SERVER['SERVER_NAME'];
      if (count($pfad) == 3){
        echo '/'.$pfad[1];
      }
      echo '/index.php?galerie='.$_POST['galerie'].'&token='.$tokhash.'</a>';
    }
  }
  else {
    exit ("Passwort falsch");
  }
}
else {
  echo "Welche Galerie soll erstellt werden?</br>";
  echo '<form id="loginbox" action="'.$_SERVER['PHP_SELF'].'" method="post">';
  echo '<p>Galerie:<br><input name="galerie" type="text" size="15" maxlength="50"></p>';
  echo '<p>Passwort:<br><input name="pass" type="password" size="15" maxlength="20"></p>';
  echo 'Moechten sie die Galerie mit einem Passwort schuetzen?';
  echo '<input type="radio" name="schutz" value="nein" checked> nein <input type="radio" name="schutz" value="ja"> ja';
  echo '<p>Galerie-Passwort:<br><input name="gal-pass" type="password" size="15" maxlength="20"></p>';
  echo '<p>Passwort wdh.:<br><input name="gal-pass-bes" type="password" size="15" maxlength="20"></p>';
  echo '<input type="submit" value="Create"><br>';
  echo '</form>';
}

?>
