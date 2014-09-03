<?php
include("auth.php");
if (is_file("galerie/".$_GET['galerie']."/pass.php") && !($_SESSION[$_GET['galerie']] == 'logedin')){
	header('HTTP/1.0 403 Forbidden');
	echo 'Bitte erst einloggen';
	exit;
} else {
	header("Content-Type: image/jpeg");
	if (isset($_GET['gross']) && ($_GET['gross']) == '1'){
	    $image = "galerie/".$_GET["galerie"]."/bilder/gross/".$_GET["bild"];
	} else {
	    $image = "galerie/".$_GET["galerie"]."/bilder/".$_GET["bild"];
	}
	readfile($image);
	exit;
}
?>
