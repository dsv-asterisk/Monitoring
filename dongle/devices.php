<?php
////////////////////// AGI ////////////////////////////////////
include_once('config.php');

$asm = new AGI_AsteriskManager();
 if($asm->connect($manager_host, $manager_user, $manager_pass))
 {
  $modens = $asm->Command("dongle show devices");
 }
$asm->disconnect();

$rr = 0;
$rs = 0;
$i  = 0;
$DIR = __DIR__;

//////////////////////////////////////////////////////////////


foreach(explode("\n", $modens['data']) as $line)

if (preg_match("/Stopped/i", $line) OR preg_match("/Held/i", $line) OR preg_match("/Not connec/i", $line) OR preg_match("/Not initia/i", $line) OR preg_match("/GSM not re/i", $line) OR preg_match("/Ring/i", $line) OR preg_match("/Waiting/i", $line) OR preg_match("/Dialing/i", $line) OR preg_match("/Active/i", $line) OR preg_match("/Free/i", $line) OR preg_match("/SMS/i", $line))
{
$gsm[] = $line;
$rr++;
}
////////////////////////////////////////////////////////////
$TD=array("Modem","Grupo","Estado","Sinal","Provedor","Tempo de Ligação","Tempo Total de Chamada","Ação");

$itens = count($TD);
?>
<table class="table table-striped table-bordered bootstrap-datatable datatable responsive ">
<tr>
<?php
echo "\n";
while($i < $itens){

echo "<th><center>".$TD[$i]."</center></th>";
$i++;
}
echo "</tr>";
echo "\n";

////////////////////////////////////////////////////////////
		while($rs < $rr){
	$modem = $gsm[$rs];
	$modem = trim($modem);
	$modem = preg_replace('/\s+/',',',$modem);
	$modem = explode(",",$modem);

   if($modem[2] == "Not" OR $modem[2] == "GSM"){
echo "<tr><td>".$modem[0]."</td>";
echo "<td>".$modem[1]."</td>";
   if($modem[2] == "Not"){
echo "<td><span class='label-default label'>Desconectado</span></td>";
}
   if($modem[2] == "GSM"){
echo "<td><span class='label-default label'>Não registrado</span></td>";
}
echo "<td>0%</td>";
echo "<td></td>";
echo "<td><center>00:00:00</center></td>";
echo "<td><center>00:00:00</center></td>";
echo "<td>";
$MD = $modem[0];
?>
<div class='center'>
<button type='submit' class='btn btn-default btn-xs noty' data-toggle='tooltip' title='Zerar Tempo de chamada' id='<?php echo $MD;?>' onClick="tempo(this);">
                       <i class='glyphicon glyphicon-time'></i> Reiniciar Tempo
                    </button>
<?php

echo "<button type='submit' data-toggle='tooltip' title='Reiniciar modem' class='btn btn-danger btn-xs noty' disabled ><i class='glyphicon glyphicon-refresh icon-white'></i> Reiniciar
                    </button>
<button type='submit' class='btn btn-success btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-green'></i> 2G
                    </button>
<button type='submit' class='btn btn-info btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-blue'></i> 3G
                    </button>

</div></td></tr>";
} else {

echo "<tr><td>".$modem[0]."</td>";
echo "<td>".$modem[1]."</td>";

        if($modem[2] == "Dialing"){
echo '<td><span class="label-warning label label-default">Discando</span></td>';
}
        if($modem[2] == "Ring"){
echo '<td><span class="label-warning label label-default">Tocando</span></td>';
}
        if($modem[2] == "Waiting"){
echo '<td><span class="label-default label label-danger">Aguardando</span></td>';
}
        if($modem[2] == "Active"){
echo '<td><span class="label-info label label-default">Ativo</span></td>';
}
        if($modem[2] == "Held"){
echo '<td><span class="label-default label label-danger">Mantido</span></td>';
}
        if($modem[2] == "Both"){
echo '<td><span class="label-default label label-danger">Ambos</span></td>';
}
        if($modem[2] == "Free"){
echo '<td><span class="label-success label label-default">Livre</span></td>';
}
        if($modem[2] == "Outgoing"){
echo '<td><span class="label-info label label-default">Sainte</span></td>';
}
        if($modem[2] == "Incoming"){
echo '<td><span class="label-info label label-default">Entrante</span></td>';
}
        if($modem[2] == "SMS"){
echo '<td><span class="label-default label">SMS</span></td>';
$asm = new AGI_AsteriskManager();
  if($asm->connect($manager_host, $manager_user, $manager_pass))
 {
  $reinicia = $asm->Command("dongle restart now $linha[0]");
 }
$asm->disconnect();
}
/////////////////////////////////////////////////////////////
//              Sinal GSM
/////////////////////////////////////////////////////////////
        if(number_format($modem[3]/30*100, 0, ',', '.') < 30 ){
echo "<td><i class='glyphicon glyphicon-signal red'>   ".number_format($modem[3]/30*100, 0, ',', '.') . '%'."</i></td>";
}
        if(number_format($modem[3]/30*100, 0, ',', '.') > 29 && number_format($modem[3]/30*100, 0, ',', '.') < 50 ){
echo "<td><i class='glyphicon glyphicon-signal yellow'>   ".number_format($modem[3]/30*100, 0, ',', '.') . '%'."</i></td>";
}
        if(number_format($modem[3]/30*100, 0, ',', '.') > 49 && number_format($modem[3]/30*100, 0, ',', '.') < 75){
echo "<td><i class='glyphicon glyphicon-signal blue'>   ".number_format($modem[3]/30*100, 0, ',', '.') . '%'."</i></td>";
}
        if(number_format($modem[3]/30*100, 0, ',', '.') > 74 ){
echo "<td><i class='glyphicon glyphicon-signal green'>   ".number_format($modem[3]/30*100, 0, ',', '.') . '%'."</i></td>";
}
////////////////////////////////////////////////////////////
//               Provedor                                 //
////////////////////////////////////////////////////////////
echo "<td><center>".$modem[6]."</center></td>";
////////////////////////////////////////////////////////////
//             Tempo de chamada                           //
///////////////////////////////////////////////////////////
        if($modem[2] == "Outgoing" OR $modem[2] == "Dialing" OR $modem[2] == "Ring" OR $modem[2] == "Incoming"){
foreach(explode("\n", $result['data']) as $line)

 if (preg_match("/$modem[0]/i", $line) && preg_match("/!Dialing!/i", $line) OR preg_match("/$modem[0]/i", $line) &&  preg_match("/!Up!/i", $line) && preg_match("/!Dial!/i", $line))
{
                $pieces = explode("!", $line);

                $regex = "~".preg_quote($pieces[12],"~")."!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!(.*?)!~";
                preg_match($regex,$result['data'],$to);

                echo "<td><center>" . gmdate("H:i:s", $pieces[11]) . "</center></td>";


$DURACAO = $pieces[11];

shell_exec("echo $DURACAO > $DIR/tempo/$modem[0]_tmp.txt");
}
} else {
$DURACAO = shell_exec("cat $DIR/tempo/$modem[0]_tmp.txt");
if(isset($DURACAO)){

$TEMPO   = shell_exec("cat $DIR/tempo/$modem[0].txt");
$TOTAL   = $TEMPO+$DURACAO;
shell_exec("echo $TOTAL > $DIR/tempo/$modem[0].txt");
shell_exec("rm -f $DIR/tempo/$modem[0]_tmp.txt");
}


echo "<td><center>00:00:00</center></td>";
}

echo "<td><center>";
 $DURACAO_TOTAL = shell_exec("cat $DIR/tempo/$modem[0].txt");
echo gmdate("H:i:s", $DURACAO_TOTAL);
echo "</center></td>";


///////////////////////////////////////////////////////////
//                Botoes de Acao                         //
///////////////////////////////////////////////////////////
$MD=$modem[0];
?>
<td><div class="center">
<button type='submit' class='btn btn-default btn-xs noty' data-toggle="tooltip" title='Zerar Tempo de chamada' id='<?php echo $MD;?>' onClick="tempo(this);">
                       <i class='glyphicon glyphicon-time'></i> Reiniciar Tempo
                    </button>
<?php
        if($modem[2] == "Not" OR $modem[2] == "SMS"){

?>
<!--    Modens indisponiveis    -->
<button type='submit' data-toggle="tooltip" title='Reiniciar modem' class='btn btn-danger btn-xs noty' disabled ><i class='glyphicon glyphicon-refresh icon-white'></i> Reiniciar
                    </button>
<button type='submit' class='btn btn-success btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-green'></i> 2G
                    </button>
<button type='submit' class='btn btn-info btn-xs noty' disabled ><i class='glyphicon glyphicon-signal icon-blue'></i> 3G
                    </button>
<?php } else { ?>
<!-- //////////////////////// -->
<!--    Modens disponiveis    -->
<button type='submit' class='btn btn-danger btn-xs noty' data-toggle="tooltip" title='Reiniciar modem' id='<?php echo $MD;?>' onClick="restart(this);"
                            data-noty-options='{&quot;text&quot;:&quot;This is an alert&quot;,&quot;layout&quot;:&quot;bottom&quot;,&quot;type&quot;:&quot;alert&quot;,&quot;closeButton&quot;:&quot;true&quot;}'>                        <i class='glyphicon glyphicon-refresh icon-white'></i> Reiniciar
                    </button>

<!--    Modens em ligação    -->
<?php
        if($modem[2] == "Dialing" OR $modem[2] == "Ring" OR $modem[2] == "Active" OR $modem[2] == "Outgoing" OR $modem[2] == "Incoming"){
?>
<button type='submit' disabled class='btn btn-success btn-xs noty' id='<?php echo $MD;?>' onClick="mudagsm(this);"><i class='glyphicon glyphicon-signal icon-green'></i> 2G
                    </button>
<button type='submit' disabled class='btn btn-info btn-xs noty' id='<?php echo $MD;?>'  onClick="mudawcdma(this);">
<i class='glyphicon glyphicon-signal icon-blue'></i> 3G
                    </button>
<?php } else { ?>
<button type='submit' data-toggle="tooltip" title='Fixar em frequência em 2g' class='btn btn-success btn-xs noty' id='<?php echo $MD;?>'  onClick="mudagsm(this);"><i class='glyphicon glyphicon-signal icon-green'></i> 2G
                    </button>
<button type='submit' data-toggle="tooltip" title='Fixar em frequência em 3g' class='btn btn-info btn-xs noty' id='<?php echo $MD;?>' onClick="mudawcdma(this);">
<i class='glyphicon glyphicon-signal icon-blue'></i> 3G
                    </button>

<!-- //////////////////////// -->
<?php } ?>
<!-- //////////////////////// -->
<?php } 

}
//print_r($modem);
echo "\n";
?>
</div></td></tr>
<?php
$rs++;

		}
////////////////////////////////////////////////////////////

?>
</table>
