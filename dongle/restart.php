<?php
//$DIR = __DIR__;
$MD = $_POST['gsm'];
include_once('config.php');

$asm = new AGI_AsteriskManager();
 if($asm->connect($manager_host, $manager_user, $manager_pass))
 {
  $result = $asm->Command("dongle restart now $MD");
 }
$asm->disconnect();
?>
