<?php
include_once('config.php');

/* ----------------------------------------------
        Outras Informações

        $modem[0] -> Nome do Modem
        $modem[1] -> Grupo
        $modem[2] -> Status
        $modem[3] -> Estado do Modem
        $modem[4] -> Sinal

---------------------------------------------- */


/* ----------------------------------------------
        CONEXÃO Asterisk Manager
---------------------------------------------- */
$asterisk_manager = new AGI_AsteriskManager();
if($asterisk_manager->connect($manager_host, $manager_user, $manager_pass)){ 
        $concise = $asterisk_manager->Command("core show channels concise");
        $modens = $asterisk_manager->Command("dongle show devices");
}                 
$asterisk_manager->disconnect();


/* ----------------------------------------------
        VARIAVEIS GLOBAIS
---------------------------------------------- */
$rr = 0;
$rs = 0;


/* ----------------------------------------------
        MÉTODOS/FUNÇÕES
---------------------------------------------- */
class estadoModem{
        public function func_estadoModem($modem){
                
                switch ($modem) {
                        case 'Not':
                                $badge = 'badge badge-danger';
                                $icone = 'fa fa-ban';                        
                                $estado_mensagem = 'Desconectado';
                                break;

                        case 'GSM':
                                $badge = 'badge badge-danger';
                                $icone = 'fa fa-ban';                        
                                $estado_mensagem = 'Não registrado';
                                break;

                        case 'Dialing':
                                $badge = 'badge badge-primary';
                                $icone = 'fa fa-phone';                        
                                $estado_mensagem = 'Discando';
                                break;

                        case 'Ring':
                                $badge = 'badge badge-info';
                                $icone = 'fa fa-bell';                        
                                $estado_mensagem = 'Tocando';
                                break;
                                
                        case 'Waiting':
                                $badge = 'badge badge-info';
                                $icone = 'fa fa-volume-control-phone';                        
                                $estado_mensagem = 'Aguardando';
                                break;
                                
                        case 'Active':
                                $badge = 'badge badge-success';
                                $icone = 'fa fa-microphone';                        
                                $estado_mensagem = 'Ativo';
                                break;

                        case 'Held':
                                $badge = 'badge badge-secondary';
                                $icone = 'fa fa-circle';                        
                                $estado_mensagem = 'Mantido';
                                break;

                        case 'Both':
                                $badge = 'badge badge-secondary';
                                $icone = 'fa fa-circle';                        
                                $estado_mensagem = 'Ambos';
                                break;
                                
                        case 'Free':
                                $badge = 'badge badge-info';
                                $icone = 'fa fa-circle-thin';                        
                                $estado_mensagem = 'Livre';
                                break;                                
                                
                        case 'Outgoing':
                                $badge = 'badge badge-success';
                                $icone = 'fa fa-microphone';                        
                                $estado_mensagem = 'Falando';
                                break;

                        case 'Incoming':
                                $badge = 'badge badge-info';
                                $icone = 'fa fa-circle';                        
                                $estado_mensagem = 'Entrante';
                                break;
                                
                        case 'SMS':
                                $badge = 'badge badge-dark';
                                $icone = 'fa fa-circle';                        
                                $estado_mensagem = 'SMS';
                                // Reiniciando o Modem
                                // Obs: Não testado.
                                $asterisk_manager = new AGI_AsteriskManager();
                                if($asterisk_manager->connect($manager_host, $manager_user, $manager_pass)){
                                        $reinicia = $asterisk_manager->Command("dongle restart now ".$modem);
                                }
                                $asterisk_manager->disconnect();
                                // --------------------
                                break;

                        default:
                                $badge = 'badge badge-danger';
                                $icone = 'fa fa-circle';                        
                                $estado_mensagem =  'Não Identificado';
                                break;
                }

                return '<span class="'.$badge.'"><i class="'.$icone.'" aria-hidden="true"></i> '.$estado_mensagem.'</span>';
        }


        public function func_sinalModem($modem){
                $sinal_modem = number_format($modem/30*100, 0, ',', '.');
                $icone ='fa fa-signal';
                $badge ='badge badge-success';
                $sinal_mensagem = $sinal_modem;

                if ($sinal_modem < 30) { $badge ='badge badge-danger'; }
                if ($sinal_modem > 29 && $sinal_modem < 50) { $badge ='badge badge-warning'; }
                if ($sinal_modem > 49 && $sinal_modem < 75) { $badge ='badge badge-primary'; }                                

                return '<span class="'.$badge.'"><i class="'.$icone.'" aria-hidden="true"></i> '.$sinal_mensagem.'%</span>';
        }


        public function func_duracaoChamada($modem_status,$modem_nome,$DIR,$concise){
                if(
                        $modem_status == "Outgoing" OR
                        $modem_status == "Dialing" OR 
                        $modem_status == "Ring" OR 
                        $modem_status == "Incoming"
                        ){                

                        foreach (explode("\n", $concise['data']) as $concise_item) {
                                if (
                                        preg_match("/$modem_nome/i", $concise_item) && preg_match("/!Up!/i", $concise_item) && preg_match("/!Dial!/i", $concise_item)
                                        ) {
                                        
                                                $item = explode("!", $concise_item);
                                                $regex = "~".preg_quote($item[12],"~")."!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!~";                                        
                                                shell_exec("echo ".$item[11]." > $DIR/tempo/".$modem_nome."_tmp.txt");
                                                return gmdate("H:i:s", $item[11]); 
                                }        
                        }

                } else{

                        $DURACAO = shell_exec("cat $DIR/tempo/".$modem_nome."_tmp.txt");
                        if(isset($DURACAO)){

                                $TEMPO = shell_exec("cat $DIR/tempo/".$modem_nome.".txt");
                                $TEMPO = $TEMPO+$DURACAO;
                                shell_exec("echo $TEMPO > $DIR/tempo/".$modem_nome.".txt");
                                shell_exec("rm -f $DIR/tempo/".$modem_nome."_tmp.txt");

                        }
                        return "-";

                }
        }


        public function func_duracaoTotal($modem_nome,$DIR){
                $DURACAO_TOTAL = shell_exec("cat $DIR/tempo/".$modem_nome.".txt");
                return gmdate("H:i:s", $DURACAO_TOTAL);                
        }
}



/* ----------------------------------------------
        GERANDO O PHP
---------------------------------------------- */

foreach(explode("\n", $modens['data']) as $line) {

        if (    preg_match("/Outgoing/i", $line) OR
                preg_match("/Stopped/i", $line) OR
                preg_match("/Held/i", $line) OR 
                preg_match("/Not connec/i", $line) OR 
                preg_match("/Not initia/i", $line) OR 
                preg_match("/GSM not re/i", $line) OR 
                preg_match("/Ring/i", $line) OR 
                preg_match("/Waiting/i", $line) OR 
                preg_match("/Dialing/i", $line) OR 
                preg_match("/Active/i", $line) OR 
                preg_match("/Free/i", $line) OR 
                preg_match("/SMS/i", $line)){

                        $gsm[] = $line;
                        $rr++;

        } 

        while($rs < $rr){
	        $modem = $gsm[$rs];
	        $modem = trim($modem);
	        $modem = preg_replace('/\s+/',',',$modem);
                $modem = explode(",",$modem);
                
                // Limpando Variáveis
                $duracao_mensagem = "-";
                $duracaototal_mensagem = "-";
                $reiniciar_indisponivel = ""; 
                $opcoes_indisponivel = "";                


                $estadoModem = new estadoModem();
                $estado_mensagem = $estadoModem->func_estadoModem($modem[2]);
                $sinal_mensagem = $estadoModem->func_sinalModem($modem[3]);
                $duracao_mensagem = $estadoModem->func_duracaoChamada($modem[2],$modem[0],__DIR__,$concise);
                $duracaototal_mensagem = $estadoModem->func_duracaoTotal($modem[0],__DIR__);


                if($modem[2] == "Not" OR $modem[2] == "SMS"){ 
                        $reiniciar_indisponivel = "style='display:none;'"; 
                        $opcoes_indisponivel = "disabled";
                }


                /* ----------------------------------------------
                        MONTAGEM DA TELA
                ---------------------------------------------- */

                echo '
                <div class="container  container-dongle">                
                <div class="row">        

                <div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle">
                <h6>'.$modem[0].'</h6><hr class="detalhes-dongle__hr">
                <p><small> <strong><i class="fa fa-group" aria-hidden="true"></i> Grupo: </strong>'.$modem[1].'</small></p>
                <p><small> <strong><i class="fa fa-id-badge" aria-hidden="true"></i> Provedor: </strong>'.$modem[6].'</small></p>
                </div>
                
                <div class="col col-12 col-sm-6 col-md-6 col-lg-3  detalhes-dongle  detalhes-dongle__estado-sinal">

                <p><small> <strong>Estado: </strong> </small>'.$estado_mensagem.'</p>
                <p><small> <strong>Sinal: </strong> </small>'.$sinal_mensagem.'</p>
                </div>

                <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__ligacao">
                <hr class="detalhes-dongle__hr">

                <p><small> <strong><i class="fa fa-hourglass-start" aria-hidden="true"></i> Ligação Ativa: </strong> '.$duracao_mensagem.'</small></p>
                </div>
                
                <div class="col col-12 col-sm-6 col-md-6 col-lg-2  detalhes-dongle  detalhes-dongle__ligacao">
                <p><small> <strong><i class="fa fa-hourglass" aria-hidden="true"></i> Tempo Total: </strong> '.$duracaototal_mensagem.'</small></p>
                <hr class="detalhes-dongle__hr">
                </div>

                <div class="col col-12 col-lg-2  detalhes-dongle--botoes">
                <div class="detalhes-dongle__acoes-botoes" role="group">
                    <button type="button" class="btn btn-success" '.$opcoes_indisponivel.' id="'.$modem[0].'" onClick="executar_funcao(this.id,\'operar_gsm\');"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>2G</strong></button>
                    <button type="button" class="btn btn-primary" '.$opcoes_indisponivel.' id="'.$modem[0].'" onClick="executar_funcao(this.id,\'operar_cdma\');"><i class="fa fa-signal" aria-hidden="true"></i> Operar em <strong>3G</strong></button>
                
                    <div class="btn-group  detalhes-dongle__acoes-reiniciar" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle  detalhes-dongle__acoes-reiniciar  detalhes-dongle__acoes-reiniciar--desktop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <strong><i class="fa fa-refresh" aria-hidden="true"></i> Reiniciar</strong>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="#" '.$reiniciar_indisponivel.' id="'.$modem[0].'" onClick="executar_funcao(this.id,\'reiniciar_modem\');"><i class="fa fa-rocket" aria-hidden="true"></i> Reiniciar Dongle</a>
                        <a class="dropdown-item" href="#" id="'.$modem[0].'" onClick="executar_funcao(this.id,\'reiniciar_tempo\');"><i class="fa fa-history" aria-hidden="true"></i> Reiniciar Tempo</a>
                        <hr>
                        <a class="dropdown-item" href="#" id="'.$modem[0].'" onClick="executar_funcao(this.id,\'reset_modem\');"><i class="fa fa-cogs" aria-hidden="true"></i> Reset Modem</a>
                    </div>
                    </div>
                </div>

                </div>                
                        

                </div>
                </div>';

                $rs++;
	}
}
?>