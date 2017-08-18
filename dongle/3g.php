<?php
$MD = $_POST['gsm'];
include_once('config.php');

$asm = new AGI_AsteriskManager();
 if($asm->connect($manager_host, $manager_user, $manager_pass))
 {
  $result = $asm->Command("dongle cmd $MD AT^SYSCFG=14,2,3FFFFFFF,2,4");
 }
$asm->disconnect();

?>
