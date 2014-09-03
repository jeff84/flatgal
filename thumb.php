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
  imagejpeg( $thumbnail, "galerie/".$galname."/thumb/".$bildname, 80 );
  imagedestroy( $thumbnail );
  
}

function createDirs($galname){
  mkdir("galerie/".$galname."/bilder");
  mkdir("galerie/".$galname."/thumbs");
  touch("galerie/".$galname."/.ready");
}

function moveImages($galname){
  foreach(glob("galerie/".$galname."/*.jpg") as $bild){
    $bildname = array_pop(explode("/", $bild));
    rename($bild, "galerie/".$galname."/bilder/".$bildname);
  }
}

foreach(glob("galerie/".$galname."/*.jpg") as $bild){
  qThumb( $bild, 120);
}

?>
