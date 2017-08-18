<?php
$DIR = __DIR__;

$gsm = $_POST['gsm'];

shell_exec("echo 0 > $DIR/tempo/$gsm.txt");


?>
