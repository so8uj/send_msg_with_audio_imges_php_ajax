<?php 
session_start();
echo $user__name = rand(0,99).rand(0,99).'_'.$_POST['usr_name'];
$expiry = time() + (86400 * 700);
setcookie('user_name', $user__name, $expiry, "/");
exit;
?>