<?php

    define('DS', DIRECTORY_SEPARATOR);
    define('__ROOT__', dirname(dirname(dirname(__FILE__))).DS); 
    require_once(__ROOT__ . "php/inc/database.php");
    require_once(__ROOT__ . "local_config/config.php");
    include '../lib/calendar_operations.php';
  
    switch ($_POST['oper']) {      
        case 'printTorn':
            global $dataTorns;
            $i=0;
            $a = explode('-',$_POST['data']);
            $dataR = $a[2].'-'.$a[1].'-'.$a[0];
            $dataY = date("Y-m-d", strtotime($dataR));
            $db = DBWrap::get_instance();
            $rs = $db->Execute('select * from aixada_torns where dataTorn=:1q', $dataY);
            while($row = $rs->fetch_assoc()){
                $ufTemp = $row['ufTorn'];
                $rsUf = $db->Execute('select * from aixada_uf where id=:1q', $ufTemp);
                $results = $rsUf -> fetch_array();?>
                <tr>
                        <th><?php echo$ufTemp;?></th>
                        <th><?php echo $results["name"];?></th>
                        <?php $rsllista = $db ->Execute('select id,name FROM aixada_uf WHERE active=:1q', 1);?>
                        <th>
	                    <select id="<?php echo $i;?>" name="ufs">
                        <option value="" name="ufSeleccionada">...</option>
                        <?php
                        while($rowuf = $rsllista->fetch_assoc()){ 
    	                    if(ufsAnulada($rowuf['id'])){
                            $nomSelect=$rowuf['id']." - ".$rowuf['name'];?>
                            <option value="<?php echo $rowuf['id']." ".$ufTemp." ".$dataY?>" name="ufSeleccionada"><?php echo $nomSelect ?></option>
                            <?php
       	                    }
                        }
                        ?>             
                        </select>
                        </th>
                        <th><button class="aix-layout-fixW150" id="btnGuardar" onclick="guardarTorn(<?php echo $i;?>)">Guardar</button></th>
                    </tr>
                <?php
                $i++;
                }
            exit;

        case 'printCrearTorns':
                $db = DBWrap::get_instance();
                $rs = $db ->Execute('select id,name FROM aixada_uf WHERE active=:1q', 1);
                $llistat = $rs -> fetch_all();         
                ?>
                <tr>
                    <th>Uf 1: </th>
                    <th><select id="uf1" name="ufTorn1">
                    <?php
                    foreach($llistat as $objecte) {
                        if(ufsAnulada($objecte[0])){
                      		$nomSelect=$objecte[0]." - ".$objecte[1];?>
                            <option value="<?php echo $objecte[0]?>" name="ufSeleccionada1"><?php echo $nomSelect ?></option>
                        <?php
                        }     	
                    }?>
                    </select></th>
                </tr>
                <tr>
                    <th>Uf 2: </th>
                    <th><select id="uf2" name="ufTorn2">
                    <?php
                    foreach($llistat as $objecte) {
                        if(ufsAnulada($objecte[0])){
                      		$nomSelect=$objecte[0]." - ".$objecte[1];?>
                            <option value="<?php echo $objecte[0]?>" name="ufSeleccionada2"><?php echo $nomSelect?></option>
                        <?php
                        }
                    }?>
                    </select></th>
                </tr>
            </table>
            <?php
            exit;

        case 'crearTorn':
            $db = DBWrap::get_instance();
            $db->Execute('insert into aixada_torns (dataTorn, ufTorn) values (:1q,:2q)', $_POST['data'], $_POST['uf1']);
            $db->Execute('insert into aixada_torns (dataTorn, ufTorn) values (:1q,:2q)', $_POST['data'], $_POST['uf2']);
            exit;

        case 'guardarTornUsuari';
            $ufSel = $_POST['dades'];
        	$dades = explode(" ", $ufSel, 3);
            $db = DBWrap::get_instance();
            $db->Execute('update aixada_torns set ufTorn = :1q where ufTorn = :2q and dataTorn= :3q limit 1', $dades[0], $dades[1], $dades[2]);
            llistartorns();           
            exit;

        case 'guardarTorn';
            $ufSel = $_POST['dades'];
        	$dades = explode(" ", $ufSel, 3);
            $db = DBWrap::get_instance();
            $db->Execute('update aixada_torns set ufTorn = :1q where ufTorn = :2q and dataTorn= :3q limit 1', $dades[0], $dades[1], $dades[2]);
            exit;

        case 'crearRodaTorns':
            $db = DBWrap::get_instance();
            $rsBorrar = $db ->Execute('delete from aixada_torns where dataTorn >= :1q', $_POST['data']);
            $rs = $db ->Execute('select id FROM aixada_uf WHERE active=:1q', 1);
            $ultimaUf = get_row_query('select MAX(id) FROM aixada_uf WHERE active=1');
            $dataInici = date("Y-m-d", strtotime($_POST['data']));
            $uf=0;
            while($row = $rs->fetch_assoc()) 
            {
                if(ufsAnulada($row['id'])){
                    $ufId = $row['id'];
                    if($uf>=get_config('ufsxTorn') && $ufId != $ultimaUf[0]){
                        $dataInici = date("Y-m-d",strtotime($dataInici."+ 1 week"));
                        $db->Execute('insert into aixada_torns (dataTorn,ufTorn) values (:1q,:2q)', $dataInici, $ufId);               
                        $uf=1;
                    }
                    else{
                        $db->Execute('insert into aixada_torns (dataTorn,ufTorn) values (:1q,:2q)', $dataInici, $ufId);               
                        $uf++;
                    }
                }
            }
            exit;

        case 'eliminarTorn':
	        $db = DBWrap::get_instance();
            $db->Execute('delete from aixada_torns where dataTorn=:1q', $_POST['data']);
            exit;

        case 'mesAnterior':
            printCalendar($_POST['mes'], $_POST['any']);
            exit;

        case 'actualitzarCalendari':
            printCalendar($_POST['mes'], $_POST['any']);
            exit;
            
        default:
	       throw new Exception(
					    "ctrlAccount: operation {$_REQUEST['oper']} not supported");
        }

 function ufsAnulada ($uf){

    $resultat = true;
    for( $contador = 0; $contador < count(get_config('ufAnulades')); $contador++ )
    {        
         if(get_config('ufAnulades')[$contador] == $uf) {
            $resultat = false;             
         }
    }
    return $resultat;
 }

?>

