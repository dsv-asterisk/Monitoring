<?php
include_once('config.php');

/* ----------------------------------------------
        CONEXÃO Asterisk Manager
---------------------------------------------- */
$asterisk_manager = new AGI_AsteriskManager();
if($asterisk_manager->connect($manager_host, $manager_user, $manager_pass)){
        $modens = $asterisk_manager->Command("dongle show devices");
}
$asterisk_manager->disconnect();

$rr = 0;
$rs = 0;
$i  = 0;
$DIR = __DIR__;

/*
    <div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle">
        <h6>VIVO01</h6><hr class="detalhes-dongle__hr">
        <p><small> <strong><i class="fa fa-group" aria-hidden="true"></i> Grupo: </strong>0</small></p>
        <p><small> <strong><i class="fa fa-id-badge" aria-hidden="true"></i> Provedor: </strong> Vivo</small></p>
    </div>
    <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__estado-sinal">
        <p><small> <strong>Estado: </strong> </small><span class="badge badge-success"><i class="fa fa-circle" aria-hidden="true"></i> Livre</span></p>
        <p><small> <strong>Sinal: </strong> </small><span class="badge badge-warning"><i class="fa fa-signal" aria-hidden="true"></i> 27%</span></p>
    </div>        
    <div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle  detalhes-dongle__ligacao">
        <hr class="detalhes-dongle__hr">
        <p><small> <strong><i class="fa fa-wifi" aria-hidden="true"></i> Ligação Ativa: </strong> 01:10:20</small></p>
    </div>        
    <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__ligacao">
        <p><small> <strong><i class="fa fa-hourglass" aria-hidden="true"></i> Tempo Total: </strong> 00:00:00</small></p>
        <hr class="detalhes-dongle__hr">
    </div>
    <div class="col col-12 col-lg-2  detalhes-dongle--botoes">
        <div class="detalhes-dongle__acoes-botoes" role="group">
            <button type="button" class="btn btn-success"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>2G</strong></button>
            <button type="button" class="btn btn-primary"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>3G</strong></button>
        
            <div class="btn-group  detalhes-dongle__acoes-reiniciar" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle  detalhes-dongle__acoes-reiniciar  detalhes-dongle__acoes-reiniciar--desktop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <strong><i class="fa fa-refresh" aria-hidden="true"></i> Reiniciar</strong>
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="#"><i class="fa fa-rocket" aria-hidden="true"></i> Reiniciar Dongle</a>
                <a class="dropdown-item" href="#"><i class="fa fa-history" aria-hidden="true"></i> Reiniciar Tempo</a>
            </div>
            </div>
        </div>        
    </div>
*/

foreach(explode("\n", $modens['data']) as $line) {
        if (preg_match("/Stopped/i", $line) OR preg_match("/Held/i", $line) OR preg_match("/Not connec/i", $line) OR preg_match("/Not initia/i", $line) OR preg_match("/GSM not re/i", $line) OR preg_match("/Ring/i", $line) OR preg_match("/Waiting/i", $line) OR preg_match("/Dialing/i", $line) OR preg_match("/Active/i", $line) OR preg_match("/Free/i", $line) OR preg_match("/SMS/i", $line)){
                $gsm[] = $line;
                $rr++;
        }

//        $TD=array("Modem","Grupo","Estado","Sinal","Provedor","Tempo de Ligação","Tempo Total de Chamada","Ação");
//        $itens = count($TD);        

/*        while($i < $itens){ 
                echo "<th><center>".$TD[$i]."</center></th>";
                $i++;
        }
        echo "</tr>";
        echo "\n";
*/

        while($rs < $rr){
	        $modem = $gsm[$rs];
	        $modem = trim($modem);
	        $modem = preg_replace('/\s+/',',',$modem);
                $modem = explode(",",$modem);
                
                /*
                echo $modem[0]."<br/>"; // Nome do Modem
                echo $modem[1]."<br/>"; // Grupo
                echo $modem[2]."<br/>"; // Status
                echo $modem[3]."<br/>"; // Estado do Modem (Registrado..)
                echo $modem[4]."<br/>"; // Sinal
                */


                /*
                        NOME MODEM
                        PROVEDOR
                        ESTADO
                */
                echo '
                <div class="container  container-dongle">                
                <div class="row">        

                <div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle">
                <h6>'.$modem[0].'</h6><hr class="detalhes-dongle__hr">
                <p><small> <strong><i class="fa fa-group" aria-hidden="true"></i> Grupo: </strong>'.$modem[1].'</small></p>
                <p><small> <strong><i class="fa fa-id-badge" aria-hidden="true"></i> Provedor: </strong>'.$modem[6].'</small></p>
                </div>';
                
                echo '<div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle  detalhes-dongle__estado-sinal">';

                switch ($modem[2]) {
                        case 'Not':
                                $estado_mensagem = '<span class="badge badge-danger"><i class="fa fa-ban" aria-hidden="true"></i> Desconectado</span>';
                                break;

                        case 'GSM':
                                $estado_mensagem = '<span class="badge badge-danger"><i class="fa fa-ban" aria-hidden="true"></i> Não registrado</span>';
                                break;

                        case 'Dialing':
                                $estado_mensagem = '<span class="badge badge-primary"><i class="fa fa-phone" aria-hidden="true"></i> Discando</span>';
                                break;

                        case 'Ring':
                                $estado_mensagem = '<span class="badge badge-info"><i class="fa fa-bell" aria-hidden="true"></i> Tocando</span>';
                                break;
                                
                        case 'Waiting':
                                $estado_mensagem = '<span class="badge badge-info"><i class="fa fa-volume-control-phone" aria-hidden="true"></i> Aguardando</span>';
                                break;
                                
                        case 'Active':
                                $estado_mensagem = '<span class="badge badge-success"><i class="fa fa-microphone" aria-hidden="true"></i> Ativo</span>';
                                break;

                        case 'Held':
                                $estado_mensagem = '<span class="badge badge-secondary"><i class="fa fa-circle" aria-hidden="true"></i> Mantido</span>';
                                break;

                        case 'Both':
                                $estado_mensagem = '<span class="badge badge-secondary"><i class="fa fa-circle" aria-hidden="true"></i> Ambos</span>';
                                break;
                                
                        case 'Free':
                                $estado_mensagem = '<span class="badge badge-info"><i class="fa fa-circle-thin" aria-hidden="true"></i> Livre</span>';
                                break;                                
                                
                        case 'Outgoing':
                                $estado_mensagem = '<span class="badge badge-info"><i class="fa fa-circle" aria-hidden="true"></i> Sainte</span>';
                                break;

                        case 'Incoming':
                                $estado_mensagem = '<span class="badge badge-info"><i class="fa fa-circle" aria-hidden="true"></i> Entrante</span>';
                                break;
                                
                        case 'SMS':
                                $estado_mensagem = '<span class="badge badge-dark"><i class="fa fa-circle" aria-hidden="true"></i> SMS</span>';
/*

                                $asterisk_manager = new AGI_AsteriskManager();
                                if($asterisk_manager->connect($manager_host, $manager_user, $manager_pass)){
                                        $reinicia = $asterisk_manager->Command("dongle restart now $linha[0]");
                                }
                                $asterisk_manager->disconnect();
                                */

                                break;

                        default:
                                $estado_mensagem =  '<span class="badge badge-danger"><i class="fa fa-circle" aria-hidden="true"></i> Não Identificado</span>';
                                break;
                }

                echo '<p><small> <strong>Estado: </strong> </small>'.$estado_mensagem.'</p>';                

                /*
                        SINAL
                */
                $sinal_modem = number_format($modem[3]/30*100, 0, ',', '.');
                $sinal_mensagem = '<span class="badge badge-success"><i class="fa fa-signal" aria-hidden="true"></i> '.$sinal_modem.'%</span>';

                if ($sinal_modem < 30) {
                        $sinal_mensagem = '<span class="badge badge-danger"><i class="fa fa-signal" aria-hidden="true"></i> '.$sinal_modem.'%</span>';
                }

                if ($sinal_modem > 29 && $sinal_modem < 50) {
                        $sinal_mensagem = '<span class="badge badge-warning"><i class="fa fa-signal" aria-hidden="true"></i> '.$sinal_modem.'%</span>';
                }

                if ($sinal_modem > 49 && $sinal_modem < 75) {
                        $sinal_mensagem = '<span class="badge badge-primary"><i class="fa fa-signal" aria-hidden="true"></i> '.$sinal_modem.'%</span>';
                }                

                echo '
                <p><small> <strong>Sinal: </strong> </small>'.$sinal_mensagem.'</p>
                </div>';                
                 
                /*
                        DURACAO DA CHAMADA
                */

                if($modem[2] == "Outgoing" OR $modem[2] == "Dialing" OR $modem[2] == "Ring" OR $modem[2] == "Incoming"){
                        foreach(explode("\n", $result['data']) as $line){
                                if (preg_match("/$modem[0]/i", $line) && preg_match("/!Dialing!/i", $line) OR preg_match("/$modem[0]/i", $line) &&  preg_match("/!Up!/i", $line) && preg_match("/!Dial!/i", $line)){
                                        $pieces = explode("!", $line);
                                        $regex = "~".preg_quote($pieces[12],"~")."!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!~";
                                        preg_match($regex,$result['data'],$to);
                                        $duracao_mensagem = gmdate("H:i:s", $pieces[11]);
                                        $DURACAO = $pieces[11];
                                        shell_exec("echo $DURACAO > $DIR/tempo/$modem[0]_tmp.txt");
                                }
                        }
                } else {
                        $DURACAO = shell_exec("cat $DIR/tempo/$modem[0]_tmp.txt");
                        if(isset($DURACAO)){
                                $TEMPO   = shell_exec("cat $DIR/tempo/$modem[0].txt");
                                $TOTAL   = $TEMPO+$DURACAO;
                                shell_exec("echo $TOTAL > $DIR/tempo/$modem[0].txt");
                                shell_exec("rm -f $DIR/tempo/$modem[0]_tmp.txt");
                        }
                        $duracao_mensagem = "-";
                }
                $DURACAO_TOTAL = shell_exec("cat $DIR/tempo/$modem[0].txt");
                $duracaototal_mensagem = gmdate("H:i:s", $DURACAO_TOTAL);
                                        
                echo '
                <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__ligacao">
                <hr class="detalhes-dongle__hr">
                <p><small> <strong><i class="fa fa-hourglass-start" aria-hidden="true"></i> Ligação Ativa: </strong> '.$duracao_mensagem.'</small></p>
                </div>
                
                <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__ligacao">
                <p><small> <strong><i class="fa fa-hourglass" aria-hidden="true"></i> Tempo Total: </strong> '.$duracaototal_mensagem.'</small></p>
                <hr class="detalhes-dongle__hr">
                </div>';


                /*
                        DURACAO DA CHAMADA
                */

                echo '
                <div class="col col-12 col-lg-2  detalhes-dongle--botoes">
                <div class="detalhes-dongle__acoes-botoes" role="group">
                    <button type="button" class="btn btn-success"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>2G</strong></button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>3G</strong></button>
                
                    <div class="btn-group  detalhes-dongle__acoes-reiniciar" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle  detalhes-dongle__acoes-reiniciar  detalhes-dongle__acoes-reiniciar--desktop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <strong><i class="fa fa-refresh" aria-hidden="true"></i> Reiniciar</strong>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="#"><i class="fa fa-rocket" aria-hidden="true"></i> Reiniciar Dongle</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-history" aria-hidden="true"></i> Reiniciar Tempo</a>
                    </div>
                    </div>
                </div>

            </div>                
                

            </div>
            </div>';
/*
                        <button type='submit' class='btn btn-default btn-xs noty' data-toggle="tooltip" title='Zerar Tempo de chamada' id='<?php echo $MD;?>' onClick="tempo(this);">Reiniciar Tempo</button>
                        <?php
                        if($modem[2] == "Not" OR $modem[2] == "SMS"){
                                ?>
                                <!--    Modens indisponiveis    -->
                                <button type='submit' data-toggle="tooltip" title='Reiniciar modem' class='btn btn-danger btn-xs noty' disabled ><i class='glyphicon glyphicon-refresh icon-white'></i> Reiniciar</button>
                                <button type='submit' class='btn btn-success btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-green'></i> 2G</button>
                                <button type='submit' class='btn btn-info btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-blue'></i> 3G</button>
                                <?php
                        } else { 
                                ?>
                                <!-- //////////////////////// -->
                                <!--    Modens disponiveis    -->
                                <button type='submit' class='btn btn-danger btn-xs noty' data-toggle="tooltip" title='Reiniciar modem' id='<?php echo $MD;?>' onClick="restart(this);" data-noty-options='{&quot;text&quot;:&quot;This is an alert&quot;,&quot;layout&quot;:&quot;bottom&quot;,&quot;type&quot;:&quot;alert&quot;,&quot;closeButton&quot;:&quot;true&quot;}'>Reiniciar</button>

                                <!--    Modens em ligação    -->
                                <?php
                                if($modem[2] == "Dialing" OR $modem[2] == "Ring" OR $modem[2] == "Active" OR $modem[2] == "Outgoing" OR $modem[2] == "Incoming"){
                                        ?>
                                        <button type='submit' disabled class='btn btn-success btn-xs noty' id='<?php echo $MD;?>' onClick="mudagsm(this);"><i class='glyphicon glyphicon-signal icon-green'></i> 2G</button>
                                        <button type='submit' disabled class='btn btn-info btn-xs noty' id='<?php echo $MD;?>'  onClick="mudawcdma(this);"> 3G</button>
                                        <?php
                                } else { 
                                        ?>
                                        <button type='submit' data-toggle="tooltip" title='Fixar em frequência em 2g' class='btn btn-success btn-xs noty' id='<?php echo $MD;?>'  onClick="mudagsm(this);"><i class='glyphicon glyphicon-signal icon-green'></i> 2G</button>
                                        <button type='submit' data-toggle="tooltip" title='Fixar em frequência em 3g' class='btn btn-info btn-xs noty' id='<?php echo $MD;?>' onClick="mudawcdma(this);"> 3G</button>
                                        <!-- //////////////////////// -->
                                        <?php
                                }
                                ?>
                */
                ?>
                <?php
                $rs++;
	}
}
?>