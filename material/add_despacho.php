<?
require_once('xajax/xajax.inc.php'); //incluimos la librelia xajax
require_once ('conexion/class.conexionDB.inc.php');  //incluimos la clase conexion
 //require_once('eliminar/eliminar.php');



$xajax=new xajax();         // Crea un nuevo objeto xajax
$xajax->setCharEncoding("iso-8859-1"); // le indica la codificación que debe utilizar
$xajax->decodeUTF8InputOn();            // decodifica los caracteres extraños
$xajax->processRequests();


?>

<html>
<meta http-equiv="Pragma"content="no-cache">
<meta http-equiv="expires"content="0">
<head>
<?php $xajax->printJavascript("xajax"); //imprime el codigo javascript necesario para que funcione todo. ?>

<link rel="stylesheet" href="CSS/style.css" type="text/css" media="screen">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){   
  
    $('#cont').fadeIn(1200);  

    var els = document.getElementsByTagName("div");   
       for (var i=0; i<els.length; i++) {
            
            if (els[i].id=="ui-datepicker-div"){
                els[i].parentNode.removeChild(els[i]); 
        }

});

	
</script>
</head>
<body>
<?
//$user = $_GET['usuario'];
//echo "<div style='position:absolute; top: 0; left: 0;'> Hola usuario bienvenido! <a href='logout.php'>Logout</a> </div>"
?>


<div id="cont" class="container">
<form name="proyecto" id="proyecto" action="" method="post">
    <input type="hidden" id="num_campos" name="num_campos" value="0" />
    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />
<fieldset>
    <img src="images/despachos.jpg"/>
    <img style='float:right;' src="images/despachos2.jpg"/>
    <img style='float:left;' src="images/despachos3.jpg"/>
	<legend class="legend">
        Despachos
    </legend>	

</fieldset>
<!--grupo de botones que agregan personas a un pequeño formulario para luego guardarlo en la base de datos o acceder a la pantalla de eliminacion de datos-->

</div>
</form>
</body>
</html>