<?php
function csrf_token(){
    $token = md5('LKJhdsf7634n/&%$§'.time());
    $_SESSION['token'] = $token;
    return $token;
}
?>
