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
                '<tr><th colspan="4"><h1><?php echo $Text['nav_wiz'];?> ' + dataTorn + '</h1></th></tr>'+
                '<tr><th><?php echo $Text['uf_short']?></th><th><?php echo $Text['uf_long']?></th><th><?php echo $Text['assignar_torn']?></th><th><?php echo $Text['guardar']?></th></tr>'; 
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
                            '</tr>';
            }                
                html += '</thead></table>';          
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
        success: function(response){
            let html = '<table>'+
                '<tr><th COLSPAN="2"><h1><?php echo $Text['crear_torn'];?></h1></th></tr>'+response;
            $('#contenidorTorn').html(html);
            }   
    });
 }

 /* Funció per eliminar un Torn seleccionat */
 function eliminarTorn() {
   $.showMsg({
        msg: "Estàs segur d'eliminar el torn amb data: "+idData,
        buttons: {
		"<?=$Text['btn_ok'];?>":function(){
            let dataTorn = idData.split("-").reverse().join("-");
            let parametres = {
                    oper : "eliminarTorn",
                    data : dataTorn
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
    
    var selectUf1 = document.getElementById("uf1").value;
    var selectUf2 = document.getElementById("uf2").value;

    let dataTorn = idData.split("-").reverse().join("-");
    let parametres = {
        oper : "crearTorn",
        data : dataTorn,
        uf1 : selectUf1,
        uf2 : selectUf2
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
        msg: "Estàs segur de crear una roda de torns a partir de la data: "+idData+" Tots els torns posteriors seran eliminats",
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

 /*Funció per carregar un mes anterior */
 function mesAnterior(month, year){
    if(month-1==0){month=12;year--;}
    else {month = month-1;}
    let parametres = {
            oper : "mesAnterior",
            mes : month,
            any : year,
        };
     document.getElementById("contenidorTorn").style.display = "none"; 
     $.ajax({
            data: parametres,
            url: 'php/ctrl/Calendar.php',
            type: 'post',
            success:  function (response) {
               $('#contenidorCalendari').html(response);
            }
        });
    idData = null;
 }

/*Funció per carregar un mes posterior */
 function mesPosterior(month, year){
    if(month+1==13){month=1;year++;}
    else {month = month+1;}
    let parametres = {
            oper : "mesAnterior",
            mes : month,
            any : year,
        };
     document.getElementById("contenidorTorn").style.display = "none";
     $.ajax({
            data: parametres,
            url: 'php/ctrl/Calendar.php',
            type: 'post',
            success:  function (response) {
               $('#contenidorCalendari').html(response);
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
