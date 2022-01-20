<?php 
    include "php/inc/header.inc.php";
    define('DS', DIRECTORY_SEPARATOR);
    define('__ROOT__', dirname(dirname(dirname(__FILE__))).DS);  
    require_once(__ROOT__ . "local_config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$language;?>" lang="<?=$language;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Ajuda</title>

	<link rel="stylesheet" type="text/css"   media="screen" href="css/aixada_main.css" />
  	<link rel="stylesheet" type="text/css"   media="print"  href="css/print.css" />
  	<link rel="stylesheet" type="text/css"   media="screen" href="js/aixadacart/aixadacart.css?v=<?=aixada_js_version();?>" />
  	<link rel="stylesheet" type="text/css"   media="screen" href="js/fgmenu/fg.menu.css"   />
    <link rel="stylesheet" type="text/css"   media="screen" href="css/ui-themes/<?=$default_theme;?>/jqueryui.css"/>

    <script type="text/javascript" src="js/jquery/jquery.js"></script>
    <script type="text/javascript" src="js/jqueryui/jqueryui.js"></script>
    <?php echo aixada_js_src(); ?>
    <script type="text/javascript" src="js/aixadacart/jquery.aixadacart.js?v=<?=aixada_js_version();?>" ></script>
 	<script type="text/javascript" src="js/aixadacart/i18n/cart.locale-<?=$language;?>.js?v=<?=aixada_js_version();?>" ></script>

 	<script type="text/javascript" src="js/jqueryui/i18n/jquery.ui.datepicker-<?=$language;?>.js" ></script>
<script>
 function numCompte(){
    if(document.getElementById("numCompteCope").style.display === "none"){
        document.getElementById("numCompteCope").style.display = "block";        
    }
    else{
        document.getElementById("numCompteCope").style.display = "none";    
    }
 }

 function llistatUfs(){
    if(document.getElementById("llistatUfsActives").style.display === "none"){
        document.getElementById("llistatUfsActives").style.display = "block";        
    }
    else{
        document.getElementById("llistatUfsActives").style.display = "none";    
    }
 }
 
 function tornsCistella(){
    if(document.getElementById("gestioTorns").style.display === "none"){
        document.getElementById("gestioTorns").style.display = "block";        
    }
    else{
        document.getElementById("gestioTorns").style.display = "none";    
    }
 }

 function calendariProveidors(){
    if(document.getElementById("calProv").style.display === "none"){
        document.getElementById("calProv").style.display = "block";        
    }
    else{
        document.getElementById("calProv").style.display = "none";    
    }
 }

 /*Funció per guardar canvi de Torn */
function guardarTorn(elem){
    let dadesSel = document.getElementById(elem).value;
    if(dadesSel=== ""){
        $.showMsg({
            msg:"Cap Uf seleccionada",
		    type: 'atencio'});
    }
    else{
        let parametres = {
            oper : "guardarTornUsuari",
            dades : dadesSel
        };
        $.ajax({
            data: parametres,
            url: 'php/ctrl/Calendar.php',
            type: 'post',
            success: function(response){
                $('#contenidorTorns').html(response);
                $.showMsg({
                msg:"Torn modificat",
	    	    type: 'atencio'});
                }   
        });
    }
 }               

</script>
</head>
<body>
<div id="wrap">
<div>
        <?php include "php/lib/calendar_operations.php"?>
		<?php include "php/inc/menu.inc.php" ?>
	<!-- end of headwrap -->
	    <div id="stagewrap" class="ui-widget">
		    <div id="titlewrap">
		        <h1>FAQ</h1>	  	
		    </div>		
	    </div>
<div class="faq-item">
<div class="ui-widget-content ui-corner-all aix-style-observer-widget">
			<h3 class="ui-widget-header ui-corner-all" onclick="numCompte()"><span class="left-icons ui-icon ui-icon-triangle-1-e"></span><?php echo $Text['compte_titol'];?><span class="loadAnim floatRight hidden"><img class="loadSpinner" src="img/ajax-loader-redmond.gif" style="display: none;"></span></h3>
			<div id="numCompteCope" class="tblListingDefault hidden" style="display: none;">
                <p>            
                    <b><?php echo $Text['numCompte'];?></b> <?php echo get_config('coop_bank');?></br>
                    <b>Important: </b>Recordeu a l'assumpte de la transferència afegir la UF que sou!
                </p>
                </br>
                <p>
                <b><?php echo get_config('coop_name');?></b>
                </p>
                <p>
                <?php echo $Text['nif'].": ".get_config('coop_VAT_number');?>
                </p>
                </br>
                <p>
                <b><?php echo $Text['local'];?></b></br>
                <?php echo get_config('coop_address');?>
    		</div>

</div>
<div class="ui-widget-content ui-corner-all aix-style-observer-widget">
			<h3 class="ui-widget-header ui-corner-all" onclick="llistatUfs()"><span class="left-icons ui-icon ui-icon-triangle-1-e"></span><?php echo $Text['llistatUfs_titol'];?><span class="loadAnim floatRight hidden"><img class="loadSpinner" src="img/ajax-loader-redmond.gif" style="display: none;"></span></h3>
			<div id="llistatUfsActives" class="tblListingDefault hidden" style="display: none;">
                <table id="product_list_provider" class="product_list" >
		       <thead>
                <th><?php echo $Text['uf_short'];?></th>
		    	<th><?php echo $Text['uf_long'];?></th>
                <th><?php echo $Text['nav_mng_providers'];?></th>
               </thead> 
                    <?php llistatUfs(); ?>
               </table> 	
    		</div>
</div>
<div class="ui-widget-content ui-corner-all aix-style-observer-widget">
			<h3 class="ui-widget-header ui-corner-all" onclick="tornsCistella()"><span class="left-icons ui-icon ui-icon-triangle-1-e"></span><?php echo $Text['gestioTorns_titol']?><span class="loadAnim floatRight hidden"><img class="loadSpinner" src="img/ajax-loader-redmond.gif" style="display: none;"></span></h3>
			<div id="gestioTorns" class="tblListingDefault hidden" style="display: none;">
                <table id="product_list_provider" class="product_list" >
		    <thead>
		    	<tr>
                    <th><?php echo $Text['dataTorn']?></th>
                    <th><?php echo $Text['uf_short'];?></th>
		    		<th><?php echo $Text['uf_long'];?></th>
                    <th><?php echo $Text['modificar'];?></th>
                    <th><?php echo $Text['guardar'];?></th>
					</tr>
			</thead>
			<tbody id="contenidorTorns">
    		    <?php llistarTorns(); ?>
			</tbody>
			</table>		
    		</div>
</div>
<div class="ui-widget-content ui-corner-all aix-style-observer-widget">
			<h3 class="ui-widget-header ui-corner-all" onclick="calendariProveidors()"><span class="left-icons ui-icon ui-icon-triangle-1-e"></span><?php echo $Text['calendariProveidors_titol']?><span class="loadAnim floatRight hidden"><img class="loadSpinner" src="img/ajax-loader-redmond.gif" style="display: none;"></span></h3>
			<div id="calProv" class="tblListingDefault hidden" style="display: none;">
                <?php include "report_orderable_products.php" ?>
    		</div>

</div>

</body>
</html>
