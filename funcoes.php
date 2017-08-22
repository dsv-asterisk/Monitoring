<?php

include_once('config.php');

/* ----------------------------------------------
        VARIAVEIS GLOBAIS
---------------------------------------------- */

$modem = $_POST['modem'];
$comando = $_POST['comando'];

$asterisk_manager = new AGI_AsteriskManager();
if($asterisk_manager->connect($manager_host, $manager_user, $manager_pass)){
    switch ($comando) {
        case 'reiniciar_modem':
            $result = $asterisk_manager->Command("dongle restart now $modem");
            break;
        
        case 'operar_gsm':
            $result = $asterisk_manager->Command("dongle cmd $modem AT^SYSCFG=13,1,3FFFFFFF,2,4");
            break;

        case 'operar_cdma':
            $result = $asterisk_manager->Command("dongle cmd $modem AT^SYSCFG=14,2,3FFFFFFF,2,4");
            break;            
            
        case 'reiniciar_tempo':
            $DIR = __DIR__;     
            shell_exec("echo 0 > $DIR/tempo/$modem.txt");
            break;          

        case 'reset_modem':
            $result = $asterisk_manager->Command("dongle reset $modem");
            break;                                    
    }
}
$asterisk_manager->disconnect();
?>
