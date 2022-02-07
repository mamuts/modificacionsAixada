<?php include "php/inc/header.inc.php" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$language;?>" lang="<?=$language;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $Text['global_title'] . " - " /*.  $Text['head_ti_'.strtolower($_REQUEST['what'])]*/; ?></title>
	<link rel="stylesheet" type="text/css"   media="screen" href="css/aixada_main.css" />
  	<link rel="stylesheet" type="text/css"   media="print"  href="css/print.css" />
  	<link rel="stylesheet" type="text/css"   media="screen" href="js/aixadacart/aixadacart.css?v=<?=aixada_js_version();?>" />
  	<link rel="stylesheet" type="text/css"   media="screen" href="js/fgmenu/fg.menu.css"   />
    <link rel="stylesheet" type="text/css"   media="screen" href="css/ui-themes/<?=$default_theme;?>/jqueryui.css"/>
    <style> \<!-- a:hover{text-decoration:none;} a{text-decoration:none;} \--> </style> 
<script type="text/javascript" src="js/jquery/jquery.js"></script>
    <script type="text/javascript" src="js/jqueryui/jqueryui.js"></script>
    <?php echo aixada_js_src(); ?>
    <script type="text/javascript" src="js/aixadacart/jquery.aixadacart.js?v=<?=aixada_js_version();?>" ></script>
 	<script type="text/javascript" src="js/aixadacart/i18n/cart.locale-<?=$language;?>.js?v=<?=aixada_js_version();?>" ></script>
 	<script type="text/javascript" src="js/jqueryui/i18n/jquery.ui.datepicker-<?=$language;?>.js" ></script>
<script>                  
 let idData;
                                       
 function selDataTorn(elem) {

 if(idData==null){
    document.getElementById(elem.id).style.color='#7D7FF5';
    idData=elem.id;
    printTorn(idData);
 }
 else{
    document.getElementById(idData).style.color='#000000';
    document.getElementById(elem.id).style.color='#7D7FF5';
    idData=elem.id;
    printTorn(idData);
    }
 btnEliminar.disabled = false; 
 btnCrear.disabled = true;
 btnRoda.disabled = true;     
 }

 function selDataNova(elem) {
 if(idData==null){
    document.getElementById(elem.id).style.color='#7D7FF5';
    idData=elem.id;
    printCrearTorns(idData);
 }
 else{
    document.getElementById(idData).style.color='#000000';
    document.getElementById(elem.id).style.color='#7D7FF5';
    idData=elem.id;
    printCrearTorns(idData);
 }
 btnEliminar.disabled = true;
 btnCrear.disabled = false;
 btnRoda.disabled = false;  
 }

 /*Funció per mostrar un Torn selecionat*/
 function printTorn(dataTorn){    

    let parametres = {
        oper : "printTorn",
        data : dataTorn
    };

    document.getElementById("contenidorTorn").style.display = "block";

    $.ajax({
        data: parametres,
        url: 'php/ctrl/Calendar.php',
        type: 'post',
        dataType: "JSON",
        success: function(response){
            let html = '<table>'+
                '<thead>'+
                '<tr><th colspan="5"><h1><?php echo $Text['nav_wiz'];?> ' + dataTorn + '</h1></th></tr>'+
                '<tr><th><?php echo $Text['uf_short']?></th><th><?php echo $Text['uf_long']?></th><th><?php echo $Text['assignar_torn']?></th><th><?php echo $Text['guardar']?></th><th><?php echo $Text['eliminar_uf']?></th></tr>'; 
            for(let i=0; i<response[0].length; i++){
                html += '<tr>'+
                        '<th>' + response[0][i].id + '</th>'+
                        '<th>' + response[0][i].name + '</th>'+
                        '<th><select id="' + i + '" name="ufs">'+
                            '<option value="" name="ufSeleccionada">...</option>';
                         for(let x=0; x<response[1].length; x++){
                            html += '<option value="'+response[1][x].id+' '+response[0][i].id+' '+response[0][i].dataTorn+'" name="ufSeleccionada">' + response[1][x].nomSelect +'</option>';
                         }
                        html += '</select></th>'+
                            '<th><button class="aix-layout-fixW150" id="btnGuardar" onclick="guardarTorn('+i+')"><?php echo $Text['guardar']?></button></th>'+
                            '<th><button class="aix-layout-fixW150" id="btnEliminar" onclick="eliminarTorn('+response[0][i].id+')"><?php echo $Text['eliminar_uf']?></button></th>'+
                            '</tr>';
            }                
            html += '<th>+</th>'+
                    '<th><?php echo $Text['afegir_uf']?></th>'+
                    '<th><select id="'+response[0].length+'" name="ufs">'+
                    '<option value="" name="ufSeleccionada">...</option>';
                    for(let x=0; x<response[1].length; x++){
                       html += '<option value="'+response[1][x].id+' 0 '+response[0][0].dataTorn+'" name="ufSeleccionada">' + response[1][x].nomSelect +'</option>';
                    }
            html+= '</select></th>'+
                   '<th><button class="aix-layout-fixW150" id="btnGuardar" onclick="guardarTorn('+response[0].length+')"><?php echo $Text['afegir_uf']?></button></th>'+
                   '<th></th>'+ 
                   '</thead></table>';          
            $('#contenidorTorn').html(html);
        }   
    });
 }

 /*Funció per guardar un torn*/
 function guardarTorn(elem){
    let dadesSel = document.getElementById(elem).value;
    if(dadesSel=== ""){
        $.showMsg({
            msg:"<?php echo $Text['no_uf'];?>",
		    type: 'atencio'});
    }
    else{
        let parametres = {
            oper : "guardarTorn",
            dades : dadesSel
        };
        $.ajax({
            data: parametres,
            url: 'php/ctrl/Calendar.php',
            type: 'post',
            success: function(response){
                let dataGuardar = dadesSel.split(' ');
                $('#contenidorTorn').html("<h1><?php echo $Text['torn_guardat'];?> ("+idData+")</h1>");
                }   
        });
        let parametresCalendari = {
            oper : "actualitzarCalendari",
            mes : dataTorn.split("-")[1],
            any : dataTorn.split("-")[0],
        };
        $.ajax({
                data:  parametresCalendari,
                url:   'php/ctrl/Calendar.php',
                type:  'post',
                success:  function (response) {
                    $('#contenidorCalendari').html(response);
                }
        });
    }   
 }                                                         

 /* Funció per imprimir crear un torn */
 function printCrearTorns(){
    dataTorn = idData.split("-").reverse().join("-");
    let parametres = {
        oper : "printCrearTorns",
        data : dataTorn
    };
    document.getElementById("contenidorTorn").style.display = "block";
    $.ajax({
        data: parametres,
        url: 'php/ctrl/Calendar.php',
        type: 'post',
        dataType: "JSON",
        success: function(response){
            let html = '<table>'+
                '<tr><th COLSPAN="2"><h1><?php echo $Text['crear_torn'];?></h1></th></tr>';
            for(let i=0;i < <?php echo get_config('ufsxTorn');?>;i++){
                html += '<tr>'+
                    '<th><?echo $Text['uf_short'];?>:</th>'+
                    '<th><select id="uf'+i+'" name="ufTorn'+i+'">';
                    for(let x=0;x<response.length;x++){
                        html += '<option value="'+response[x].id+'" name="ufSeleccionada">'+response[x].nomSelect+'</option>';
                    }
                html+='</select></th>'+
                '</tr>';
            }
            html +='</table>';
            $('#contenidorTorn').html(html);
            }   
    });
 }

 /* Funció per eliminar un Torn seleccionat */
 function eliminarTorn(ufTorn) {
   $.showMsg({
        msg: "<?php echo $Text['pregunta_eliminar'];?>"+idData,
        buttons: {
		"<?=$Text['btn_ok'];?>":function(){
            let dataTorn = idData.split("-").reverse().join("-");
            let parametres = {
                    oper : "eliminarTorn",
                    data : dataTorn,
                    uf : ufTorn
            };
            $.ajax({
                    data:  parametres,
                    url:   'php/ctrl/Calendar.php',
                    type:  'post',
                    success:  function (response) {
                        $('#contenidorTorn').html("<h1><?php echo $Text['torn_eliminat'];?> ("+idData+")</h1>");
                    }
            });
            let parametresCalendari = {
                oper : "actualitzarCalendari",
                mes : dataTorn.split("-")[1],
                any : dataTorn.split("-")[0],
            };
            $.ajax({
                data:  parametresCalendari,
                url:   'php/ctrl/Calendar.php',
                type:  'post',
                success:  function (response) {
                    $('#contenidorCalendari').html(response);
                }
            });			
			$(this).dialog("close");
		},
		"<?=$Text['btn_cancel'];?>" : function(){
			$( this ).dialog( "close" );
		}
	},
	type: 'confirm'});
 }

 /* Funció per crear un Torn */
 function crearTorn(){

    let ufsArray = [];   
    for(let i=0;i< <?echo get_config('ufsxTorn');?>;i++){
        ufsArray[i] = document.getElementById("uf"+i).value;    
    }

    let dataTorn = idData.split("-").reverse().join("-");
    let parametres = {
        oper : "crearTorn",
        data : dataTorn,
        ufs : JSON.stringify(ufsArray)
    };
    $.ajax({
        data: parametres,
        url: 'php/ctrl/Calendar.php',
        type: 'post',
        success:  function (response) {
           $('#contenidorTorn').html("<h1><?php echo $Text['torn_creat'];?> "+idData+"</h1>");
        }
    });
    let parametresCalendari = {
            oper : "actualitzarCalendari",
            mes : dataTorn.split("-")[1],
            any : dataTorn.split("-")[0],
        };
        $.ajax({
                data:  parametresCalendari,
                url:   'php/ctrl/Calendar.php',
                type:  'post',
                success:  function (response) {
                    $('#contenidorCalendari').html(response);
                }
        });
 }

 /* Funció per crear roda de Torns */
 function crearRodaTorns(){
    $.showMsg({
        msg: "<?php echo $Text['pregunta_roda'];?>"+idData+"<?php echo $Text['pregunta_roda2'];?>",
        buttons: {
		"<?=$Text['btn_ok'];?>":function(){
            let dataTorn = idData.split("-").reverse().join("-");
            let parametres = {
                oper : "crearRodaTorns",
                data : dataTorn,
            };
            $.ajax({
                data: parametres,
                url: 'php/ctrl/Calendar.php',
                type: 'post',
                success:  function (response) {
                   $('#contenidorTorn').html("<h1><?php echo $Text['roda_torns_creada'];?></h1>");
                }
            });
            let parametresCalendari = {
                oper : "actualitzarCalendari",
                mes : dataTorn.split("-")[1],
                any : dataTorn.split("-")[0],
            };
            $.ajax({
                    data:  parametresCalendari,
                    url:   'php/ctrl/Calendar.php',
                    type:  'post',
                    success:  function (response) {
                        $('#contenidorCalendari').html(response);
                    }
            });
            $(this).dialog("close");
		},
		"<?=$Text['btn_cancel'];?>" : function(){
			$( this ).dialog( "close" );
		}
	},
	type: 'confirm'});
 }

 /*Funció per carregar mesos calendari */
 function mesCalendari(month, year, pos){
    if(pos==0){
        if(month-1==0){month=12;year--;}
        else {month = month-1;}
    }
    else{
        if(month+1==13){month=1;year++;}
        else {month = month+1;}
    }
    let parametres = {
            oper : "mesCalendari",
            mes : month,
            any : year,
        };    
     document.getElementById("contenidorTorn").style.display = "none"; 
     $.ajax({
            data: parametres,
            url: 'php/ctrl/Calendar.php',
            type: 'post',
            success:  function (response) {
                let mesos = ['','Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];
                let html = '<table>'+
                '<tr>'+
                '<th><button class="aix-layout-fixW150" id="btnMesAnterior" onclick="mesCalendari('+month+','+year+',0)">&lt;&lt;&lt;</button></th>'+
                '<th COLSPAN="5"><h1>'+mesos[month]+' '+year+'</h1></th>'+
                '<th><button class="aix-layout-fixW150" id="btnMesPosterior" onclick="mesCalendari('+month+','+year+',1)">>>></button></th>'+
                '</tr>'+
            	'<tr>'+
        		'<th>Dilluns</th><th>Dimarts</th><th>Dimecres</th><th>Dijous</th><th>Divendres</th><th>Dissabte</th><th>Diumenge</th>'+
                '</tr>'+
                 '<tr>'+response;
               $('#contenidorCalendari').html(html);
            }
        });
    idData = null;
 }
</script>
</head>
<body>
<?php
# Definim valors inicials per al calendari

    $month=date("n");
    $year=date("Y");
    $diaActual=date("j");
?>
<div id="wrap">
	<div>
        <?php include "php/lib/calendar_operations.php"?>
		<?php include "php/inc/menu.inc.php" ?>
	<!-- end of headwrap -->
	    <div id="stagewrap" class="ui-widget">
		    <div id="titlewrap">
		        <h1><?php echo $Text['head_ti_calendar']; ?></h1>	  	
		    </div>		
	    </div>
    <table id=contenidorCalendari class="product_list">
        <tr>
            <th><button class="aix-layout-fixW150" id="btnMesAnterior" onclick="mesCalendari(<?php echo $month;?>, <?php echo $year;?>, 0)">&lt;&lt;&lt;</button></th>
            <th COLSPAN="5"><h1><?php echo $mesos[$month]." ".$year?></h1></th>
            <th><button class="aix-layout-fixW150" id="btnMesPosterior" onclick="mesCalendari(<?php echo $month?>,<?php echo $year?>, 1)">>>></button></th>
        </tr>
        <tr>
            <th>Dilluns</th><th>Dimarts</th><th>Dimecres</th><th>Dijous</th><th>Divendres</th><th>Dissabte</th><th>Diumenge</th>
	    </tr>
        <tr>
        <?php
		printCalendar($month, $year);
	    ?>
    </table>
<div id=contenidorTorn></div>
<br>
<button class="aix-layout-fixW150" id="btnEliminar" disabled="disabled" onclick="eliminarTorn()"><?php echo $Text['eliminar_torn'];?></button>
<button class="aix-layout-fixW150" id="btnCrear" disabled="disabled" onclick="crearTorn()"><?php echo $Text['crear_torn'];?></button>
<button class="aix-layout-fixW150" id="btnRoda" disabled="disabled" onclick="crearRodaTorns()"><?php echo $Text['crear_roda'];?></button>
	</div>
    
	<!-- end of stage wrap -->
</div>
<!-- end of wrap -->
<!-- / END -->
</body>
</html>
