<?
require_once('xajax/xajax.inc.php'); //incluimos la librelia xajax
require_once ('conexion/class.conexionDB.inc.php');  //incluimos la clase conexion

function guardar($formulario,$profesor){

    $flag = 0;
    extract($formulario);
    $respuesta = new xajaxResponse("utf-8");
    $conn = new conexionBD ( ); //Genera una nueva conexion
	 
  $conn->EjecutarSQL("SET NAMES 'utf8'");

                 

// al guardar los numeros de las lineas nos aseguramos que si borran una no perderemos las referencias.
    foreach($hdnIdCampos as $id){


               $profesor= $formulario['hdnProfesor_' . $id];
               $sqlpro = "SELECT * FROM  `equipamiento_eii`.`profesores` WHERE Nombre =  '".limpiar($profesor)."' ";                
               $result = $conn->EjecutarSQL($sqlpro);
               $filas = mysql_num_rows($result);
              
                if($filas==0){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
                  $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");                  
                  $MSG = "El profesor seleccionado no existe.\nPor favor, intentelo nuevamente.";
                  $respuesta->addAlert($MSG);
                  return $respuesta;
                }
              $row = $conn->FetchArray($result);
              $monitor = str_replace('||','"', $formulario['hdnMonitor_' . $id]);

      
	$Str_SQL = "INSERT INTO material(`Id_profesor`, `PC` ,  `Fecha_PC` ,  `Portatil` ,  `Fecha_Portatil` ,  `Monitor` , `Fecha_Monitor`,  `Otros`, `Fecha_Otros` ) 
  VALUES ('','". $formulario['hdnPC_' . $id] ."', '". fecha($formulario['hdnFechaPC_' . $id]) ."', '". $formulario['hdnPortatil_' . $id] ."', '". fecha($formulario['hdnFechaPortatil_' . $id]) ."',
   '".$monitor."',
 '" . fecha($formulario['hdnFechaMonitor_' . $id]) . "', '" . $formulario['hdnOtros_' . $id] . "', '" . fecha($formulario['hdnFechaOtros_' . $id]) . "')";

$Str_SQL2 = "INSERT INTO material(`Id_profesor`, `PC` ,  `Fecha_PC` ,  `Portatil` ,  `Fecha_Portatil` ,  `Monitor` , `Fecha_Monitor`,  `Otros`, `Fecha_Otros` ) 
  VALUES ('".$row['Id']."','". $formulario['hdnPC_' . $id] ."', '". fecha($formulario['hdnFechaPC_' . $id]) ."', '". $formulario['hdnPortatil_' . $id] ."', '". fecha($formulario['hdnFechaPortatil_' . $id]) ."',
   '".$monitor."',
 '" . fecha($formulario['hdnFechaMonitor_' . $id]) . "', '" . $formulario['hdnOtros_' . $id] . "', '" . fecha($formulario['hdnFechaOtros_' . $id]) . "')";


        if($row['Id_material']=='0'){
           if(!$conn->EjecutarSQL($Str_SQL)){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
                  $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");
                  $flag = 1;
                  $MSG = "Ha ocurrido un error al insertar los datos de los materiales.\nPor favor, intentelo nuevamente.";
           }
	       }else{
            if(!$conn->EjecutarSQL($Str_SQL2)){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
                  $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");
                  $flag = 1;
                  $MSG = "Ha ocurrido un error al insertar los datos de los materiales.\nPor favor, intentelo nuevamente.";
           }

         }
	
           if($flag == 0){
               $lastid = mysql_insert_id();

               $conn->EjecutarSQL("SET NAMES 'utf8'");
               

               
               //$row = $conn->FetchArray($result);
               $sql= "UPDATE  `equipamiento_eii`.`material` SET  `Id_profesor` =  '".$row['Id']."'  WHERE  `material`.`Id` = '".$lastid."' ";
               
               if(!$conn->EjecutarSQL($sql)){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
                  $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");                  
                  $MSG = "Ha ocurrido un error al actualizar los materiales.\nPor favor, intentelo nuevamente.";
               }
  

              if($row['Id_material']=='0'){
                  $conn->EjecutarSQL("SET NAMES 'utf8'");                  
                  $sql= "UPDATE  `equipamiento_eii`.`profesores` SET  `Id_material` =  '".$row['Id']."' WHERE  `profesores`.`Id` = '".$row['Id']."' ";
                  if(!$conn->EjecutarSQL($sql)){                
                      printf("ultimo registro insertado tiene el id %d\n", $lastid);
                  }
		              $conn->EjecutarSQL("COMMIT TRANSACTION A1");		              
              }
              $MSG = "Datos guardados con exito"; 
              
	       }
        
	}
  
	$respuesta->addAlert($MSG);
  return $respuesta;

}

function asigna($ultimo) //asigna el material recien insertado al profesor elegido
{
    $respuesta = new xajaxResponse();
    
      javascript:alert("funciona"); 
   
    
    $MSG = "Datos asignados con exito"; 
    $respuesta->addAlert($MSG);
    return $respuesta;
}

function fecha($var) //convierte la fecha para la db
{
    $final = date("Y/m/d", strtotime($var)); 
    return $final;
}
	
  function limpiar($String)
  {
     $String = str_replace(' ',' ',$String);   /////###########importantisimo!! no borrar!! 
     //$String = str_replace('&quot','\"',$String);
     $String = str_replace('"','\"',$String);


return $String;
}
    
   


$xajax=new xajax();         // Crea un nuevo objeto xajax
$xajax->setCharEncoding("utf-8"); // le indica la codificación que debe utilizar
$xajax->decodeUTF8InputOn();            // decodifica los caracteres extraños
$xajax->registerFunction("agregarFila"); //Registramos la función para indicar que se utilizará con xajax.
$xajax->registerFunction("cancelar");
$xajax->registerFunction("eliminarFila");
$xajax->registerFunction("guardar");
$xajax->registerFunction("fecha");
$xajax->registerFunction("asigna");
$xajax->registerFunction("limpiar");
$xajax->processRequests();
?>

<html>
<meta http-equiv="Pragma"content="no-cache">
<meta http-equiv="expires"content="0">
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
<head>
<?php $xajax->printJavascript("xajax"); //imprime el codigo javascript necesario para que funcione todo. ?>
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui-1.8.18.custom.css" />
<link rel="stylesheet" href="CSS/style.css" type="text/css" media="screen">
<link rel="stylesheet" href="CSS/demo.css" type="text/css" media="screen">

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '&#x3c;Ant',
        nextText: 'Sig&#x3e;',
        currentText: 'Hoy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
        'Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
        dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).ready(function(){
   
  
   $("#txtFechaPC").datepicker({
       changeYear: true,
       defaultDate: null
    });
   $("#txtFechaPortatil").datepicker({
       changeYear: true,
       defaultDate: null
    }); 
    $("#txtFechaMonitor").datepicker({
       changeYear: true,
       defaultDate: null
    }); 
    $("#txtFechaOtros").datepicker({
       changeYear: true,
       defaultDate: null
    }); 

var valores = [];
$("#valor option").each(function(i, value) {
    if (i > 0) {
        valores[i - 1] = $(value).text();
    }
});

$("#txtvalor").autocomplete({
    source: valores,
    minLength: 0,
    select: function(event, ui) {
        var opcion = $("#valor option").filter(function(index) {
            return $(this).text() == ui.item.label;
        }).val();

    }
});
$("#btnvalor").click(function() {
    $("#txtvalor").autocomplete("search", "");
    $("#txtvalor").focus();
});
$("#txtvalor").click(function() {
    $("#txtvalor").select();
    $("#txtvalor").autocomplete("search", "");
    $("#txtvalor").focus();
});

var valotros = [];
$("#otros option").each(function(i, value) {
    if (i > 0) {
        valotros[i - 1] = $(value).text();
    }
});

$("#txtotros").autocomplete({
    source: valotros,
    minLength: 0,
    select: function(event, ui) {
        var opcion = $("#otros value").filter(function(index) {
            return $(this).text() == ui.item.label;
        }).val();

    }
});
$("#btnotros").click(function() {
    $("#txtotros").autocomplete("search", "");
    $("#txtotros").focus();
});
$("#txtotros").click(function() {
    $("#txtotros").select();
    $("#txtotros").autocomplete("search", "");
    $("#txtotros").focus();
});

  
   $("#btnGuardar").click(function() { 
      
        if($(this).hasClass('save-big')){
            if(confirm('Desea insertar estos datos?'))
            {
               xajax_guardar(xajax.getFormValues('proyecto') ,document.getElementById('txtvalor').value); 
               cancelar(); 
               $(this).removeClass('button save-big');
               $(this).addClass('guarda');
            }       
        }
    return false;
    });

    $("#btnAgregar").click(function() {       
       agregarFila(document.getElementById("cant_campos"));       
       $('#btnGuardar').removeClass("guarda");
       $('#btnGuardar').addClass("button save-big");
       return false;
    });

    $("#btnCancel").click(function() {   
       cancelar();
       $('#btnGuardar').removeClass('button save-big');
       $('#btnGuardar').addClass('guarda');
       return false;
    });
});

function agregarFila(obj){
    $("#cant_campos").val(parseInt($("#cant_campos").val()) + 1);
		var oId = $("#cant_campos").val();
    var profesor = $("#txtvalor").val();
    var pc = $("#txtPC").val();
		var fechapc = $("#txtFechaPC").val();
		var portatil = $("#txtPortatil").val();
		var fechaportatil = $("#txtFechaPortatil").val();
		var monitor = $("#txtMonitor").val();
    monitor = monitor.replace('"',"||");
    var fechamonitor = $("#txtFechaMonitor").val();
		var otros= $("#txtotros").val();
    var fechaotros = $("#txtFechaOtros").val();

    var strHtml0 = '<input type="hidden" id="hdnProfesor_' + oId + '" name="hdnProfesor_' + oId + '" value="' + profesor + '"/>';
    var strHtml1 = "<td>" + pc + '<input type="hidden" id="hdnPC_' + oId + '" name="hdnPC_' + oId + '" value="' + pc + '"/></td>';
		var strHtml2 = "<td>" + fechapc + '<input type="hidden" id="hdnFechaPC_' + oId + '" name="hdnFechaPC_' + oId + '" value="' + fechapc + '"/></td>' ;
		var strHtml3 = "<td>" + monitor + '<input type="hidden" id="hdnMonitor_' + oId + '" name="hdnMonitor_' + oId + '" value="' + monitor + '"/></td>' ;
		var strHtml4 = "<td>" + fechamonitor + '<input type="hidden" id="hdnFechaMonitor_' + oId + '" name="hdnFechaMonitor_' + oId + '" value="' + fechamonitor + '"/></td>' ;
    var strHtml5 = "<td>" + portatil + '<input type="hidden" id="hdnPortatil_' + oId + '" name="hdnPortatil_' + oId + '" value="' + portatil + '"/></td>' ;
    var strHtml6 = "<td>" + fechaportatil + '<input type="hidden" id="hdnFechaPortatil_' + oId + '" name="hdnFechaPortatil_' + oId + '" value="' + fechaportatil + '"/></td>' ;
    var strHtml7 = "<td>" + otros + '<input type="hidden" id="hdnOtros_' + oId + '" name="hdnOtros_' + oId + '" value="' + otros + '"/></td>' ;
    var strHtml8 = "<td>" + fechaotros + '<input type="hidden" id="hdnFechaOtros_' + oId + '" name="hdnFechaOtros_' + oId + '" value="' + fechaotros + '"/></td>' ;
    var strHtml9= '<td><img src="images/delete.png" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){eliminarFila(' + oId + ');}"/>';
    strHtml9 += '<input type="hidden" id="hdnIdCampos_' + oId +'" name="hdnIdCampos[]" value="' + oId + '" /></td>';
    var strHtmlTr = "<tr id='rowDetalle_" + oId + "'></tr>";
    var strHtmlFinal = strHtml0 + strHtml1 + strHtml2 + strHtml3 + strHtml4 + strHtml5 + strHtml6 + strHtml7 + strHtml8 + strHtml9;
        //tambien se puede agregar todo el HTML de una sola vez.
        //var strHtmlTr = "<tr id='rowDetalle_" + oId + "'>" + strHtml1 + strHtml2 + strHtml3 + strHtml4 + strHtml5 + strHtml6 +"</tr>";
    $("#tbDetalle").append(strHtmlTr);
        //si se agrega el HTML de una sola vez se debe comentar la linea siguiente.
    $("#rowDetalle_" + oId).html(strHtmlFinal);
    vaciarCampos(); 
		proyecto.txtPC.focus();   //añadido el foco al primer campo cuando termina de insertar
    document.getElementById('btnGuardar').disabled=false;
    return false;
	}

	function eliminarFila(oId){	    
	    $("#rowDetalle_" + oId).remove();	
		$("#cant_campos").val(parseInt($("#cant_campos").val()) - 1);  //cantidad de campos menos 1
		var can = $("#cant_campos").val();		
		if (can == 0){
		    document.getElementById('btnGuardar').disabled=true;   //si no queda ninguna fila mas deshabilito el boton de guardar
		}
		return false;
	}

	function cancelar(){
	  $("#cant_campos").val(0); 
    $("#tbDetalle").html("");
    vaciarCampos(); 	
		proyecto.txtPC.focus(); 
		return false;
	}
	
  function vaciarCampos()
    {     
       //document.proyecto.txtvalor.value = '';  
       document.proyecto.txtPC.value = '';
       document.proyecto.txtFechaPC.value = '';
       document.proyecto.txtPortatil.value = ''; 
       document.proyecto.txtFechaPortatil.value = '';
       document.proyecto.txtMonitor.value = '';
       document.proyecto.txtFechaMonitor.value = '';
       document.proyecto.txtotros.value = '';
       document.proyecto.txtFechaOtros.value = '';      
    }



</script>
</head>
<body>
<?
if(isset($_GET['usuario'])){
   $user = $_GET['usuario'];
}
//echo "<div style='position:absolute; top: 0; left: 0;'> Hola usuario bienvenido! <a href='logout.php'>Logout</a> </div>"
//echo "Variable $user: $user <br>"
?>

<div id="cont" class="container">
    
<form name="proyecto" id="proyecto" action="" method="post">
    <input type="hidden" id="num_campos" name="num_campos" value="0" />
    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />
<fieldset>
    
	<legend>Insertar Material</legend>
	<div class="top">

  <label class="label" for="txtvalor">Nombre</label>        
        <div class="div_texbox" ><input type="text" value='<?if(isset($_GET['usuario'])){echo str_replace(' ', '&nbsp;', $user );}?>' placeholder="Elige Profesor" id="txtvalor" class="ccmbtxt" />
            <button id="btnvalor" type="button" class="ccmbbtn">&#x25BC;</button></div>  
            <select id="valor" style="float:left; display:none;">
                <option value="default">default value</option>        
                  <?php 
                     $conn = new conexionBD ();
                     //$conn->EjecutarSQL("SET NAMES 'utf8'");
                     $sqlista=$conn->EjecutarSQL("SELECT * FROM profesores");
                     while($lista = $conn->FetchArray($sqlista)){ 
                        echo '<option value="'.utf8_decode($lista['Nombre']).'">'.utf8_decode($lista['Nombre']).'</option>'; 
                     } 
                  ?> 
            </select>

        
	     <label class="label" for="txtPC">PC</label>
        <div class="div_texbox"><textarea id="txtPC" class="textarea"></textarea></div>
        <label class="label" for="txtFechaPC">Fecha del PC</label>
        <div class="div_texbox"><input type="text" id="txtFechaPC" name="txtFechaPC" value="" class="textbox_FEC" /></div>
        <label class="label" for="txtMonitor">Monitor</label>
        <div class="div_texbox"><input type="text" id="txtMonitor" name="txtMonitor" value="" class="textbox" /></div>
        <label class="label" for="txtFechaMonitor">Fecha del Monitor</label>
        <div class="div_texbox"><input type="text" id="txtFechaMonitor" name="txtFechaMonitor" value="" class="textbox_FEC" /></div>
        <label class="label" for="txtPortatil">Portatil</label>
        <div class="div_texbox"><textarea id="txtPortatil" class="textarea"></textarea></div>
        <label class="label" for="txtFechaPortatil">Fecha del Portatil</label>
        <div class="div_texbox"><input type="text" id="txtFechaPortatil" name="txtFechaPortatil" value="" class="textbox_FEC" /></div>       
		    <label class="label" for="txtotros">Otros</label>
        
        <div class="div_texbox" ><input type="text" value='' id="txtotros" class="ccmbtxt" />
            <button id="btnotros" type="button" class="ccmbbtn">&#x25BC;</button></div>  
            <select id="otros" style="float:left; display:none;">
                <option value="default">default value</option>
                <option value="Impresora: ">Impresora: </option>
                <option value="Memoria USB: ">Memoria USB: </option>
                <option value="Disco Duro externo: ">Disco Duro externo: </option>                
            </select>

        <!--div class="div_texbox"><input type="text" id="txtOtros" name="txtOtros" value="" class="textbox" /></div-->
        <label class="label" for="txtFechaOtros">Fecha de Otros</label>
        <div class="div_texbox"><input type="text" id="txtFechaOtros" name="txtFechaOtros" value="" class="textbox_FEC" /></div>
	</div>

</fieldset>
<!--grupo de botones que agregan personas a un pequeño formulario para luego guardarlo en la base de datos o acceder a la pantalla de eliminacion de datos-->
<div class="button_div">      	
	  <a class="button delete-big" href="#" id="btnCancel">Cancelar</a> 
    <a class="button add-big" href="#" id="btnAgregar">Agregar Material</a>
    <a class="guarda" href="#" id="btnGuardar">Guardar</a>    
    <a class="button flag-big" href="index.html">Perfiles</a>    
</div>

<fieldset class="fieldset">    
    <legend class="legend">
        Detalle de Materiales
    </legend>
    <div class="clear"></div>
    <div id="form3" class="form-horiz">
	<table width="100%" id="tblDetalle" class="listado">
		<thead>
			<tr>
				<th>PC</th>
				<th>Fecha PC</th>			
				<th>Monitor</th>
        <th>Fecha Monitor</th>
        <th>Portatil</th>
        <th>Fecha Portatil</th>
        <th>Otros</th>
        <th>Fecha Otros</th>
				<th>Accion</th>				
			</tr>
		</thead>
		<tbody id="tbDetalle">
		</tbody>
	</table>
    </div>
</fieldset>
</div>   <!--fin de container-->
</form>
</body>
</html>