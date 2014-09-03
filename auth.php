<?php
session_start();
include("csrf_token.php");
$sid = session_id();
$time = time();
if (isset($_SESSION['time'])){
    $zeit = $time - $_SESSION['time'];
    if ($zeit > 1800){
        session_destroy();
    }
}
if ((isset($_GET['galerie'])) && (isset($_SESSION[$_GET['galerie']]))){

    if (!password_verify($_SERVER['HTTP_USER_AGENT'].substr($_SERVER["REMOTE_ADDR"],0,7), $_SESSION['finger'])){
        session_destroy();
    }
    else {
        $logedin = true;
        $_SESSION['time'] = $time;
    }
}

?>
