<?
require_once('xajax/xajax.inc.php'); //incluimos la librelia xajax
require_once ('conexion/class.conexionDB.inc.php');  //incluimos la clase conexion
 //require_once('eliminar/eliminar.php');

function guardar($formulario){
//comentaré todo lo que tenga que ver con la Base de Datos
    $flag = 0;
    extract($formulario);
    $respuesta = new xajaxResponse();
    $conn = new conexionBD ( ); //Genera una nueva coneccion
	$conn->EjecutarSQL("BEGIN TRANSACTION A1");
	
	if(!$conn->EjecutarSQL("BEGIN TRANSACTION A1")){ 
	     $MSG = "No se puede conectar a la base de datos";
	}

// al guardar los numeros de las lineas nos aseguramos que si borran una no perderemos las referencias.
    foreach($hdnIdCampos as $id){      // Así recorro cada campo en cada linea
//	Guardo la consulta en una cadena  (cambiado el nombre de la tabla de personas a profesores)
	$Str_SQL = 		"INSERT INTO profesores(  `Nombre` , `Despacho`, `Telefono` ,  `Correo`, `Id_material`, `Foto`) 
VALUES (
'".utf8_encode($formulario['hdnNombre_' . $id])."', '" . $formulario['hdnDes_' . $id] . "' , '" . $formulario['hdnTelefono_' . $id] . "', '" . $formulario['hdnCorreo_' . $id] . "' ,'0','" . $formulario['hdnFoto_' . $id] . "' )";

		
        	 
           if(!$conn->EjecutarSQL($Str_SQL)){  //CONTROL DE ERRORES.  muy importante, si no guarda uno, no guarda nada.
                  $conn->EjecutarSQL("ROLLBACK TRANSACTION A1");
                  $flag = 1;
                  $MSG = "Ha ocurrido un error al insertar los datos de la persona.\nPor favor, intentelo nuevamente.";
           }
          
	        //$respuesta->addAlert($Str_SQL);
	
           if($flag == 0){
		       $conn->EjecutarSQL("COMMIT TRANSACTION A1");
		       $MSG = "Datos guardados con exito";		
	       }	
      //  }else{
		//     echo "el profesor ya existe";
		//}
	}
	//$copy = copy(, 'images/image.jpg');
	$respuesta->addAlert($MSG);
    return $respuesta;

}

 
		
		///////////////////////////////////////////////////////////////#####################################################################################################
		 function borrar(){ 
	
	
	
    if($flag == 0){
		$conn->EjecutarSQL("COMMIT TRANSACTION A1");
		$MSG = "Datos borrados con exito";		
	}
    
    $respuesta->addAlert($MSG);
    return $respuesta;
	   // ajax = objetoAjax();
		 $respuesta = new xajaxResponse();
		ajax.open("GET", "eliminar.php");
		 $MSG = "cambiando de pagina";	
		 $respuesta->addAlert($MSG);
         return $respuesta;
		
         }	
		 
		 function cargar()
        {
          //  $('#miDiv').load('pagina.html');
		  ("#cont").load("eliminar.php");
         }


$xajax=new xajax();         // Crea un nuevo objeto xajax
$xajax->setCharEncoding("iso-8859-1"); // le indica la codificación que debe utilizar
$xajax->decodeUTF8InputOn();            // decodifica los caracteres extraños
$xajax->registerFunction("agregarFila"); //Registramos la función para indicar que se utilizará con xajax.
$xajax->registerFunction("cancelar");
$xajax->registerFunction("eliminarFila");
$xajax->registerFunction("guardar");
$xajax->registerFunction("borrar");
$xajax->registerFunction("cargar");
$xajax->processRequests();


?>

<html>
<meta http-equiv="Pragma"content="no-cache">
<meta http-equiv="expires"content="0">
<head>
<?php $xajax->printJavascript("xajax"); //imprime el codigo javascript necesario para que funcione todo. ?>

<link rel="stylesheet" href="CSS/style.css" type="text/css" media="screen">
<link rel="stylesheet" href="CSS/demo.css" type="text/css" media="screen">

<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){

       var els = document.getElementsByTagName("div");   
       for (var i=0; i<els.length; i++) {
            
            if (els[i].id=="ui-datepicker-div"){
                els[i].parentNode.removeChild(els[i]); 
        }
      }

    $("#btnGuardar").click(function() { 
      
        if($(this).hasClass('save-big')){
            if(confirm('¿Desea insertar estos datos?'))
            {
               xajax_guardar(xajax.getFormValues('proyecto')); 
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

function hasClassName(classname,id) {
 return  String ( ( document.getElementById(id)||{} ) .className )
         .split(/\s/)
         .indexOf(classname) >= 0;
}
function nombre(fic) {
  fic = fic.split('\\');
  return(fic[fic.length-1]);
}
function agregarFila(obj){
        $("#cant_campos").val(parseInt($("#cant_campos").val()) + 1);
		    var oId = $("#cant_campos").val();
        var nombre = $("#txtNombre").val();
        var despacho= $("#selDes").val();
		    var telefono = $("#txtTelefono").val();
		    var correo = $("#txtCorreo").val();
            var foto = $("#txtFoto").val();
		    //foto = foto.split('\\');
            //foto = fic[fic.length-1];
            foto = foto.replace("C:\\fakepath\\","");

            var strHtml1 = "<td>" + nombre + '<input type="hidden" id="hdnNombre_' + oId + '" name="hdnNombre_' + oId + '" value="' + nombre + '"/></td>';
		    var strHtml2 = "<td>" + despacho + '<input type="hidden" id="hdnDes_' + oId + '" name="hdnDes_' + oId + '" value="' + despacho + '"/></td>' ;
            var strHtml3 = "<td>" + telefono + '<input type="hidden" id="hdnTelefono_' + oId + '" name="hdnTelefono_' + oId + '" value="' + telefono + '"/></td>' ;
		    var strHtml4 = "<td>" + correo + '<input type="hidden" id="hdnCorreo_' + oId + '" name="hdnCorreo_' + oId + '" value="' + correo + '"/></td>' ;
            var strHtml5 = "<td>" + foto + '<input type="hidden" id="hdnFoto_' + oId + '" name="hdnFoto_' + oId + '" value="' + foto + '"/></td>' ;
		    var strHtml6= '<td><img src="images/delete.png" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){eliminarFila(' + oId + ');}"/>';
        strHtml6 += '<input type="hidden" id="hdnIdCampos_' + oId +'" name="hdnIdCampos[]" value="' + oId + '" /></td>';
        var strHtmlTr = "<tr id='rowDetalle_" + oId + "'></tr>";
        var strHtmlFinal = strHtml1 + strHtml2 + strHtml3 + strHtml4 + strHtml5 + strHtml6;
        //tambien se puede agregar todo el HTML de una sola vez.
        //var strHtmlTr = "<tr id='rowDetalle_" + oId + "'>" + strHtml1 + strHtml2 + strHtml3 + strHtml4 + strHtml5 + strHtml6 +"</tr>";
        $("#tbDetalle").append(strHtmlTr);
        //si se agrega el HTML de una sola vez se debe comentar la linea siguiente.
        $("#rowDetalle_" + oId).html(strHtmlFinal);
        vaciarCampos();
		proyecto.txtNombre.focus();   //añadido el foco al primer campo cuando termina de insertar
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
		proyecto.txtNombre.focus(); 
		return false;
	}

    function vaciarCampos()
    {
       document.proyecto.txtNombre.value = '';
       document.proyecto.selDes.value = '';
       document.proyecto.txtTelefono.value = '';
       document.proyecto.txtCorreo.value = '';       
    }

	
</script>
</head>
<body>
<?
//$user = $_GET['usuario'];
//echo "<DIV STYLE='position:absolute; top: 0; left: 0;'> Hola usuario bienvenido! <a href='logout.php'>Logout</a> </div>"
?>

<div id="cont" class="container">
<form name="proyecto" id="proyecto" action="" method="post">
    <input type="hidden" id="num_campos" name="num_campos" value="0" />
    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />
<fieldset>
	<legend>Insertar Profesores</legend>
	<div class="top">
	<label class="label" for="txtNombre">Nombre</label>
        <div class="div_texbox"><input type="text" id="txtNombre" name="txtNombre" value="" class="textbox txtUser" /></div>          
        <label class="label" for="selDes">Despacho</label>
        <div class="div_texbox">
            <select id="selDes" name="selDes" class="textbox txtFec">
                <option value="">Seleccione</option>                     
                <option value="d1-1">d1-1</option>
                <option value="d1-2">d1-2</option>
                <option value="d1-3">d1-3</option>
                <option value="d1-4">d1-4</option>
                <option value="d1-5">d1-5</option>
                <option value="d1-6">d1-6</option>
                <option value="d1-7">d1-7</option>
                <option value="d1-8">d1-8</option>
                <option value="d1-9">d1-9</option>
                <option value="d1-10">d1-10</option>
                <option value="d1-11">d1-11</option>
                <option value="d1-12">d1-12</option>
                <option value="d1-13">d1-13</option>                
                <option value="d1-24">s1</option>
                <option value="d1-24">s2</option>
                <option value="d1-24">s3</option>
                <option value="d1-24">s4</option>
                <option value="d1-10">d2-1</option>                
                <option value="d1-12">d2-2</option>
                <option value="d1-12">d2-3</option>
                <option value="d1-14">d2-4</option>
                <option value="d1-15">d2-5</option>
                <option value="d1-16">d2-6</option>
                <option value="d1-17">d2-7</option>
                <option value="d1-18">d2-8</option>
                <option value="d1-19">d2-9</option>
                <option value="d1-20">d2-10</option>
                <option value="d1-21">d2-11</option>
                <option value="d1-22">d2-12</option>
                <option value="d1-23">d2-13</option>
                <option value="d1-22">d2-14</option>
                <option value="d1-23">d2-15</option>
                <option value="d1-24">s5</option>
                <option value="d1-24">s6</option>
                <option value="d1-24">s7</option>
                <option value="d1-24">s8</option>
                <option value="d1-10">d3-1</option>                
                <option value="d1-12">d3-2</option>
                <option value="d1-12">d3-3</option>
                <option value="d1-14">d3-4</option>
                <option value="d1-15">d3-5</option>
                <option value="d1-16">d3-6</option>
                <option value="d1-17">d3-7</option>
                <option value="d1-18">d3-8</option>
                <option value="d1-19">d3-9</option>
                <option value="d1-20">d3-10</option>
                <option value="d1-21">d3-11</option>
                <option value="d1-22">d3-12</option>
                <option value="d1-23">d3-13</option>
                <option value="d1-24">s9</option>
                <option value="d1-24">s10</option>
                <option value="d1-24">s11</option>
                <option value="d1-24">s12</option>
            </select>
        </div>
        <label class="label" for="txtTelefono">Telefono</label>
        <div class="div_texbox"><input type="text" id="txtTelefono" name="txtTelefono" value="" class="textbox txtUser" /></div>
        <label class="label" for="txtCorreo">Correo</label>
        <div class="div_texbox"><input type="text" id="txtCorreo" name="txtCorreo" value="" class="textbox txtCmt" /></div> 
        <label class="label" for="txtFoto">Foto</label>
        <div class="div_texbox"><input type="file"  id="txtFoto" name="txtFoto" value="" class="enterpic" /></div>     
	</div>

</fieldset>

<!--grupo de botones que agregan personas a un pequeño formulario para luego guardarlo en la base de datos o acceder a la pantalla de eliminacion de datos-->
<div class="button_div">     
    <a class="button delete-big" href="#" id="btnCancel">Cancelar</a>    
    <a class="button add-big" href="#" id="btnAgregar">Agregar Profesor</a>
    <a class="guarda" href="#" id="btnGuardar">Guardar</a>
    <a class="button flag-big" href="index.html">Perfiles</a>
</div>

     

<fieldset class="fieldset">
    <legend class="legend">
        Detalle de Profesores
    </legend>
    <div class="clear"></div>
    <div id="form3" class="form-horiz">
	<table width="100%" id="tblDetalle" class="listado">
		<thead>
			<tr>
				<th ALIGN=CENTER>Nombre</th>				
				<th ALIGN=CENTER>Despacho</th>
				<th ALIGN=CENTER>Telefono</th>
                <th ALIGN=CENTER>Correo</th>
                <th ALIGN=CENTER>Foto</th>	
                <th ALIGN=CENTER>Accion</th>			
			</tr>
		</thead>
		<tbody id="tbDetalle">
		</tbody>
	</table>
    </div>
</fieldset>
</div>
</form>
</body>
</html>
