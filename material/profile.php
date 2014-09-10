<?
require_once('xajax/xajax.inc.php'); //incluimos la librelia xajax
require_once('conexion/class.conexionDB.inc.php');
$conn = new conexionBD ( );

$sql2=$conn->EjecutarSQL("SELECT * FROM profesores");
$sql3=$conn->EjecutarSQL("SELECT * FROM profesores ORDER BY Nombre DESC");

$Id = 1;
$fId = 1; 
$ultimo = 1; 
$marca=0;
$sel=false;
$fechamin = "";
$fechamax = "";
$alter=0; //variable para ordenar por nombre
$vacio=1;
$xajax=new xajax(); 
$nombre="";   

function eliminar($material,$tipo){



$respuesta = new xajaxResponse();
$conn = new conexionBD ();	 

$sql = "UPDATE  `material` SET  $tipo =  '' WHERE  Id IN  ($material)";


if(!$conn->EjecutarSQL($sql)){
   $MSG = "Ha ocurrido un error al eliminar los datos del material.\nPor favor, intentelo nuevamente.";
}else{
   $MSG = "Datos Eliminados correctamente";
}
//Eliminamos la fila entera si no tiene ningun elemento mas
$sql=$conn->EjecutarSQL("SELECT * FROM material WHERE Id IN  ($material)");
$row = $conn->FetchArray($sql);
if($row['PC']=='' && $row['Portatil']=='' && $row['Monitor']=='' && $row['Otros']==''){  
  $sql = "DELETE FROM `material`  WHERE Id IN  ($material)";
  $conn->EjecutarSQL($sql);
  $sql = "UPDATE `profesores` SET Id_material = '' WHERE Id_material IN  ($material)";  
  $conn->EjecutarSQL($sql);
}
	
 $respuesta->addAlert($MSG);
 return $respuesta;

}

function eliminarprofesor($profesor){
   $respuesta = new xajaxResponse(); 
   $conn = new conexionBD ();
   $conn->EjecutarSQL("SET NAMES 'utf8'");
   $profe = utf8_encode(limpiar2($profesor));
   $sql = "DELETE FROM profesores WHERE Nombre IN ('$profe')";   

   if(!$conn->EjecutarSQL($sql)){
      $MSG = "Ha ocurrido un error al eliminar los datos del profesor.\nPor favor, intentelo nuevamente.";
   }else{
      $MSG = "Datos Eliminados correctamente";
  }
  
   $respuesta->addAlert($MSG);
   return $respuesta;
}

function fecha($var) //convierte la fecha de la db a dd/mm/aaaa
{
	$final = date("d/m/Y", strtotime($var));
	return $final;
}


function guardar($texto,$material,$campo) //guarda los datos
{
	  $respuesta = new xajaxResponse();
    $conn = new conexionBD ( );
    $texto = utf8_encode($texto);

    $sql = "UPDATE `material` SET $campo = '$texto' WHERE Id IN ($material)";
    if(!$conn->EjecutarSQL($sql)){
       $MSG = "Ha ocurrido un error al actualizar los datos del material.\nPor favor, intentelo nuevamente.";
    }else{
      $MSG = "Datos modificados correctamente";
    }
 
    $respuesta->addAlert($MSG);
    return $respuesta;

}

function buscar($busqueda)
{
    $respuesta = new xajaxResponse();
    $conn = new conexionBD ( );
      
    $sql = "SELECT * FROM profesores WHERE Nombre LIKE '%$busqueda%'";
    if(!$conn->EjecutarSQL($sql)){
       $MSG = "Ha ocurrido un error al buscar en la base de datos.\nPor favor, intentelo nuevamente.";
    }
    
       $respuesta->addAlert("Buscando: ".$sql);
       return $respuesta;
  
}

function limpiar($String){
$String = str_replace(' ',' ',$String);   /////###########importantisimo!! no borrar!!
$String = str_replace(' ',"&nbsp",$String);
// $String = str_replace(' ','',$String);
$String = str_replace('"','&quot',$String);
return $String;
}

function limpiar2($String){
//$String = str_replace(' ',' ',$String);   /////###########importantisimo!! no borrar!!
//$String = str_replace(' ',"&nbsp",$String);
$String = str_replace("&nbsp",' ',$String);
// $String = str_replace(' ','',$String);
//$String = str_replace('&quot','"',$String);
return $String;
}



$xajax->setCharEncoding("iso-8859-1"); // le indica la codificación que debe utilizar
$xajax->decodeUTF8InputOn();            // decodifica los caracteres extraños
$xajax->registerFunction("eliminar");   //Registramos la función para indicar que se utilizará con xajax.
$xajax->registerFunction("eliminarprofesor");
$xajax->registerFunction("fecha"); 
$xajax->registerFunction("ordenar");
$xajax->registerFunction("guardar");
$xajax->registerFunction("buscar"); 
$xajax->processRequests();
?>

<html>
<head>
<title>Profesores y materiales</title>
<?php $xajax->printJavascript("xajax");  ?>


<!-- Include stylesheet -->  
<link rel="stylesheet" href="CSS/sweet-tooltip.css"> 
<link rel="stylesheet" href="CSS/jquery-ui-1.8.10.custom.css">
<link rel="stylesheet" type="text/css" href="CSS/jquery-ui-1.8.18.custom.css" />
<link href="CSS/style.css" rel="stylesheet" type="text/css"> 
<!-- estilo usado sólo al imprimir -->
<link href="CSS/print.css" rel="stylesheet" type="text/css" media="print"> </head>
<!--                               -->
<link rel="stylesheet" href="CSS/demo.css" type="text/css" media="screen">
<link href="CSS/editinplace.css" rel="stylesheet" type="text/css"> 
<link rel="stylesheet" href="CSS/dev.css"> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<!-- Include JavaScript --> 
<script language="javascript" src="scriptaculous/lib/prototype.js"></script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/sweet-tooltip.js"></script>
<script src="js/jquery.tablesorter.js" type="text/javascript"></script>
<!-- edit in place--> 
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script language="javascript" src="js/jquery.editinplace.js"></script>
<!--script language="javascript" src="js/jquery.jeditable"></script-->
<!--script language="javascript" src="js/jquery.jeditable.datepicker"></script-->
<!-- Slider fecha--> 
<script src="js/jquery.mousewheel.min.js"></script>
<script src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jQRangeSlider.js"></script>
<script type="text/javascript" src="js/jQDateRangeSlider.js"></script> 
 

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



$(document).ready(function() 
   { 

    $("#txtFechaMin").datepicker({
       changeYear: true,           
    });

  
    $("#txtFechaMax").datepicker({
       changeYear: true,            
    });

     $("#addpro").click(function() {
       
       var els = document.getElementsByTagName("div");   
       for (var i=0; i<els.length; i++) {
            
            if (els[i].id=="ui-datepicker-div"){
                els[i].parentNode.removeChild(els[i]); 
        }
      }
      $("#txtFechaMin").removeClass("hasDatepicker");
      $("#txtFechaMax").removeClass("hasDatepicker");

       $.ajax({ url: "add_profesor.php", success: function(html) {
            $("#ajax-content").empty().append(html).hide().fadeIn('slow');
         }
         }); 
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

        //xajax_buscar(opcion);
        buscar(opcion);
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


  $("#fechasave").click(function() { 
      
         var minimo = $("#txtFechaMin").datepicker("getDate");
         var maximo = $("#txtFechaMax").datepicker("getDate");
         if(minimo!=null && maximo!=null){
            var dia1= minimo.getDate(); 
            var dia2= maximo.getDate(); 
            var mes1 = minimo.getMonth() + 1;
            var mes2 = maximo.getMonth() + 1;
            var fecha1 = "" + minimo.getFullYear() + "-" + (mes1 < 10 ? "0" + mes1 : mes1) + "-" + (dia1 < 10 ? "0" + dia1 : dia1);
            var fecha2 = "" + maximo.getFullYear() + "-" + (mes2 < 10 ? "0" + mes2 : mes2) + "-" + (dia2 < 10 ? "0" + dia2 : dia2);         
            $fechamin = fecha1;
            $fechamax = fecha2;
    
            $.ajax({ url: "profile.php?tipo="+document.getElementById("order").value+"&orden=1&fechamin="+fecha1+"&fechamax="+fecha2, success: function(html) {                 
                 $("#ajax-content").empty().append(html).hide().fadeIn('slow');
            }
            });
          }else{alert("Debe seleccionar un intervalo de fechas para mostrar");return false;}
    return false;
    });

  

  $("#fecha").dateRangeSlider({
  	
  	  bounds:{min:new Date(2008,1,1), max:new Date(2014,12,31)},
     	defaultValues: {min: new Date(2010,1,11), max: new Date(2012,1,11)}, 
      valueLabels: "show",
      event: "valuesChanging",   
      formatter: function(value){
        var month = value.getMonth() + 1,
        day = value.getDate();
        var date = "" + (day < 10 ? "0" + day : day) + "/" + (month < 10 ? "0" + month : month) + "/" + value.getFullYear();
        //var left = document.getElementByClass('ui-rangeSlider-label ui-rangeSlider-leftLabel').innerHTML;
        //alert(left);
        //alert($this._values.min);
        $("#txtFechaMin").datepicker("setDate",date);
        $("#txtFechaMax").datepicker("setDate",date);
        return date;
       },
    
    });  


   	   $("#order").change(function() {
   	      
            $sel = true;
           
           var value = $("#order option:selected").val();
           if(value!="" && value!="Portatil" && value!="PC" && value!="Monitor" && value!="Otros"){
              $.ajax({ url: "profile.php?tipo="+document.getElementById("order").value+"&orden=0", success: function(html) {
                 
                 $("#ajax-content").empty().append(html).hide().fadeIn('slow'); 
                 
                 }          
              });
           }
		   if(value=="Portatil" || value=="PC" || value=="Monitor" || value=="Otros"){    
                //$("#fecha").css('visibility','visible').hide().fadeIn('slow');
                $("#txtFechaMin").css('visibility','visible').hide().fadeIn('slow');
                $("#txtFechaMax").css('visibility','visible').hide().fadeIn('slow');
                $("#fechasave").css('visibility','visible').hide().fadeIn('slow');
           }
        
          return false;
        });


    //Edit in place
  $(".inplace-editor").editInPlace({       
    callback:  function(unused, enteredText) { 
      if(confirm("¿Desea modificar este material?")){ 
         var padre = $(this).parent().parent().get(0).id; 
         var material = $('#'+padre).attr('name'); 
         var campo = $('#'+padre).attr('value'); 
         xajax_guardar(enteredText,material,campo);
         return enteredText;
       };  },
    field_type: "textarea",
    callback_skip_dom_reset: false,
    value_required: true,
    element_id: "editar",
    
    
  
      default_text: "Introduce los datos aqui",
      save_button: "<a class='inplace_save button save' id='save' href='#'>Guardar</a>",
      cancel_button: "<a class='inplace_cancel button delete' id='cancel' href='#'>Cancelar</a>",
    
    show_buttons: true
  });


   } 
); 



////////////////////////////
function buscar(opcion){
  $.ajax({ url: "profile.php?tipo="+document.getElementById("order").value+"&orden=0&profesor="+opcion, success: function(html) {
            $("#ajax-content").empty().append(html).hide().fadeIn('slow');
         }
         });

  return false;
}
//////////////////////////////////
function editar(){

  return false;
}
function alerta(){
	alert(document.getElementById("order").value);
}
function writeCookie()
{
	
       document.cookie ='selecciona='+true+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
    
}

function mostrar(Id) { 
     $('#deletepro'+ Id).css("display", "inline");
 }

function eliminarFila(tabla,fila){
    
      
      $("#fila"+tabla+fila).remove();   //eliminamos las filas seleccionadas
      
      if(document.getElementById("tablita"+tabla).hasChildNodes()==false){
        alert("tablita"+tabla);
        mostrar(tabla);
      }      			  
		  return true;  
		  
}

function eliminarProfe(tabla){
   
      $("#titulo"+tabla).remove(); 
      $("#tabla"+tabla).remove();
      $("#fotazo"+tabla).remove();
                
      return true;
      
}
	
	function marcar(fila) {  
	
		     fila.className = "selected";
		     if(document.getElementById('secreta['+ fila.id +']').value==0){
		     	document.getElementById('secreta['+ fila.id +']').value=1;
		     	fila.className = "selected";
		     }else{
		     	document.getElementById('secreta['+ fila.id +']').value=0;
		     	fila.className = "bordered";
		     }
}

function ocultar(Id) { 
     $('#tablita'+ Id).slideToggle(400);
 }

function noclick(Id) {
     $('#titulo'+Id).removeAttr('onClick');
 }

function fechar() {
   $("#fecha").css("display", "none");
 }
</script>



</head>
<body>	


<fieldset id="wrap">
<div id="control" class="control"> 

   <a class="button add-big" id="addpro" href="#" style='float:left'>Profesor</a>  
        <div class="sort_texbox" style="float:left" >
            <select id="order" style="height:42px; margin-left:2px;" name="order" class="textbox">
                <option value="">Ordenar por:</option> 
                <option value=""></option>  
                <option value="NombreD">Nombre &dArr;</option>               
                <option value="NombreU">Nombre &uArr;</option>                
                <option value="PC">Fecha del PC</option>
                <option value="Monitor">Fecha del Monitor</option>  
                <option value="Portatil">Fecha del Portatil</option>
                <option value="Otros">Fecha de Otros</option>                 
            </select>
        </div>
    <div class="ui-rangeSlider ui-rangeSlider-withArrows ui-dateRangeSlider" id="fecha" style="position: relative; visibility:hidden; width:600px; float:left;"></div>         
    <input type="text" id="txtFechaMin"  value="" class="textbox_FEC" style="visibility:hidden; margin-left:18px;"/>
    <input type="text" id="txtFechaMax"  value="" class="textbox_FEC" style="visibility:hidden;"/>
    <a class='inplace_save button save' id='fechasave'  style="visibility:hidden;" href='#' >Filtrar</a>
    <a class='button save-big' id='imprimir' style="float:left;" onclick="window.print();" href='#' >Imprimir&nbsp;</a>
    
    <div class="ccmb" ><input type="text" placeholder="Buscar Profesor" id="txtvalor" class="ccmbtxt" />
            <button id="btnvalor" type="button" class="ccmbbtn">&#x25BC;</button></div>  
    <select id="valor" style="float:left; display:none;">
        <option value="default">default value</option>        
         <?php 
                  $conn = new conexionBD ( );
                  $conn->EjecutarSQL("SET NAMES 'utf8'");
                  $sqlista=$conn->EjecutarSQL("SELECT * FROM profesores");
                while($lista = $conn->FetchArray($sqlista)){ 
                  echo '<option value="'.utf8_encode($lista['Nombre']).'">'.$lista['Nombre'].'</option>'; 
                } 
        ?> 
    </select>

</div>
</fieldset>

<div class="profile">
<?php
$orderby='';

if(!isset($_GET['profesor'])){ //si no se especifica el nombre de un profesor

if(isset($_GET['tipo'])){
  $orderby = $_GET['tipo'];  
  $fec = $_GET['orden'];
  if(isset($_GET['fechamin'])){
     $fechamin = $_GET['fechamin'];
     $fechamax = $_GET['fechamax'];
     $sql4 = $conn->EjecutarSQL("SELECT * FROM profesores where Id_material IN (SELECT Id_profesor FROM material WHERE Fecha_PC BETWEEN '$fechamin' AND '$fechamax' ORDER BY Fecha_PC DESC)");
     $sql5 = $conn->EjecutarSQL("SELECT * FROM profesores where Id_material IN (SELECT Id_profesor FROM material WHERE Fecha_Portatil BETWEEN '$fechamin' AND '$fechamax')");
     $sql6 = $conn->EjecutarSQL("SELECT * FROM profesores where Id_material IN (SELECT Id_profesor FROM material WHERE Fecha_Monitor BETWEEN '$fechamin' AND '$fechamax')");
     $sql7 = $conn->EjecutarSQL("SELECT * FROM profesores where Id_material IN (SELECT Id_profesor FROM material WHERE Fecha_Otros BETWEEN '$fechamin' AND '$fechamax')");
   }
}else{
  $orderby="NombreD";
}




$order=$sql2;
//Ordenar la informacion de los perfiles
switch($orderby){
	case "NombreU":  //ordena por nombres descendente
	   $order=$sql3;
	   break;
	case "NombreD":  //ordena por nombres ascendente
	   $order=$sql2;
	   break;
	case "PC":  //ordena por nombres ascendente
	   if($fec==1){
         $order=$sql4;
     }
	   break;
  case "Portatil":  //ordena por nombres ascendente
     if($fec==1){
        $order=$sql5;
     }
     break;
  case "Monitor":  //ordena por nombres ascendente
     if($fec==1){
         $order=$sql6;
     }
     break;
  case "Otros":  //ordena por nombres ascendente
     if($fec==1){
         $order=$sql7;
     }
     break;
}

}else{

  $profesor = $_GET['profesor'];
  $sql6 = $conn->EjecutarSQL("SELECT * FROM profesores WHERE Nombre = '$profesor'");
  $order = $sql6;
} 


while($row2 = $conn->FetchArray($order)){  //bucle para extraer los profesores de la tabla correspondiente y mostrarlos

if($row2['Foto']==""){
	$foto="who.png";
}else{
	$foto=$row2['Foto'];
}
$nombre=$row2['Nombre'];

$vacio=0;
//echo "</table>";

//foto de perfil//////////



echo "<img id='fotazo$Id' class='fotazo' src='images/perfiles/".$foto."'/>";





//echo "<div id='pic' class='picture'><img src='images/paper-corner.jpg' style= ' position:relative; z-index:1; left:-35px; top:45px; border:6px;'/></div>";
    


echo "<table class='bordered' id=tabla$Id >";
echo "<thead>";
echo "<tr id=titulo$Id onClick='ocultar($Id)'>";
	 echo "<script>ocultar($Id)</script>";
    echo "<th colspan='3' align='left'><div style='position:relative; left:45px; display:inline; float:left; clear:right;'>".$nombre."</div> <div id='info' style='display:inline; float:right;' >
    <font color='#455943'>".$row2['Despacho']."</font>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#ED8200'>".$row2['Telefono']."</font>&nbsp;&nbsp;&nbsp;&nbsp;<a target='_blank' href='mailto:".$row2['Correo']."'>".$row2['Correo']."</a> 
    <a id='deletepro$Id' onclick='if(confirm(".'"¿Desea eliminar este profesor?"'.")){eliminarProfe($Id);xajax_eliminarprofesor(&quot;".limpiar($nombre)."&quot;);};' style='display:none;' class='botonmenos sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Eliminar Profesor'>x</a>
    <a id='addmat' href='add_material.php?usuario=$nombre';' class='botonmas sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Añadir Material'>+</a> </div> </th>";
	
	
echo "</tr>";
echo "</thead>";


echo "<tbody id=tablita$Id >";


$sql=$conn->EjecutarSQL("SELECT * FROM material WHERE Id_profesor IN (SELECT Id_material FROM profesores WHERE Nombre='$nombre')");
$sql_PC= $conn->EjecutarSQL("SELECT * FROM material WHERE Id_profesor IN (SELECT Id_material FROM profesores WHERE Nombre='$nombre') ORDER BY `material`.`Fecha_PC` DESC");
$sql_Monitor= $conn->EjecutarSQL("SELECT * FROM material WHERE Id_profesor IN (SELECT Id_material FROM profesores WHERE Nombre='$nombre') ORDER BY `material`.`Fecha_Monitor` DESC");
$sql_Portatil= $conn->EjecutarSQL("SELECT * FROM material WHERE Id_profesor IN (SELECT Id_material FROM profesores WHERE Nombre='$nombre') ORDER BY `material`.`Fecha_Portatil` DESC");
$sql_Otros= $conn->EjecutarSQL("SELECT * FROM material WHERE Id_profesor IN (SELECT Id_material FROM profesores WHERE Nombre='$nombre') ORDER BY `material`.`Fecha_Otros` DESC");



if($row = $conn->FetchArray($sql)){  //para extraer los datos de material del profesor correspondiente 
  //echo "<script language='javascript'>alert('entraa');document.getElementById('deletepro').css('display', 'inline');</script>";
  mysql_data_seek($sql, 0);
  
while($row = $conn->FetchArray($sql_PC)){  
   
  
     if ($row['PC']!='' && $orderby!="Portatil" && $orderby!="Monitor" && $orderby!="Otros"){
	echo "	<tr id=fila$Id$fId name=".$row['Id']." value='PC'>";  //background=#FFFFFF>";
	//echo " <input type='hidden' id=secreta[$Id] name='num_profe' value=0 method='POST'/>";
	//echo " <input type='hidden' id='nombre' name='nombrepro' value=$nombre method='POST'/>";
  echo " <input type='hidden' id='Id_PC$fId' value=".$row['Id']." />";
	//mediante el evento onclick llamaremos a la funcion eliminarDato(), la cual tiene como parametro
	//de entrada el ID del empleado
	//echo " 		<th><a style=\"text-decoration:underline;cursor:pointer;\" onclick=\"eliminarDato('".$row['Nombre']."')\">".$row['Nombre']."</a></th>";
	//echo "      <table width="100%" id="tblDetalle" class="listado">";
	if($ultimo == 1){
	  echo " 		<td style='border-left: none;'><img src='images/server.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor' style='color:#F00'>". $row['PC']." </div></td>";
	}else{
    echo "    <td style='border-left: none;'><img src='images/server.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor'>". $row['PC']." </div></td>";
  }
  echo " 		<td style= 'width:90px;'><div style= 'float:left;' id='fecha_PC'class='editable'>".fecha($row['Fecha_PC'])." </div></td>";     //transformamos el formato fecha a mostrar
	echo " 		<td style= 'width:10px;'>
	            <img id='eliminar' onclick='if(confirm(".'"¿Desea eliminar este material?"'.")){eliminarFila($Id,$fId);xajax_eliminar(".$row['Id'].",".'"PC"'.");};' src='images/delete.ico' class='sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Eliminar' style= 'width:16px; height:16px; '</img>
            </td>";
	 echo "  </tr>";
    $fId=$fId+1;
    $ultimo=0;
     }
  }
   
   mysql_data_seek($sql, 0);
   $ultimo=1;
   while($row = $conn->FetchArray($sql_Monitor)){
    if ($row['Monitor']!='' && $orderby!="PC" && $orderby!="Portatil" && $orderby!="Otros"){
	echo "	<tr id=fila$Id$fId name=".$row['Id']." value='Monitor'>"; 
  if($ultimo == 1){ 
	  echo " 		<td><img src='images/monitor.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor' style='color:#F00'> ".$row['Monitor']."</div></td>";
	}else{
    echo "    <td><img src='images/monitor.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor'> ".$row['Monitor']."</div></td>";

  }
  echo "    <td style= 'width:90px;'>".fecha($row['Fecha_Monitor'])."</td>";
  echo " 		<td style= 'width:10px;'> 
	            <img id='eliminar' onclick='if(confirm(".'"¿Desea eliminar este material?"'.")){eliminarFila($Id,$fId);xajax_eliminar(".$row['Id'].",".'"Monitor"'.");};' src='images/delete.ico' class='sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Eliminar' style= 'width:16px; height:16px;'</img>
            </td>";
	echo "	    </tr>";
  $fId=$fId+1;
  $ultimo=0;
      }
    }
    mysql_data_seek($sql, 0);
    $ultimo=1;
  while($row = $conn->FetchArray($sql_Portatil)){
    if ($row['Portatil']!='' && $orderby!="PC" && $orderby!="Monitor" && $orderby!="Otros"){
  echo "  <tr id=fila$Id$fId name=".$row['Id']." value='Portatil'>";
  if($ultimo == 1){   
    echo "    <td><img src='images/laptop.png' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor' style='color:#F00'>".$row['Portatil']."</div></td>";
  }else{
    echo "    <td><img src='images/laptop.png' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor'>".$row['Portatil']."</div></td>";
  }
  echo "    <td style= 'width:90px;'>".fecha($row['Fecha_Portatil'])."</td>";   //transformamos el formato fecha a mostrar
  echo "    <td style= 'width:10px;'> 
              <img id='eliminar' onclick='if(confirm(".'"¿Desea eliminar este material?"'.")){eliminarFila($Id,$fId);xajax_eliminar(".$row['Id'].",".'"Portatil"'.");};' src='images/delete.ico' class='sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Eliminar' style= 'width:16px; height:16px; '</img>
            </td>"; 
  echo "  </tr>";
  $fId=$fId+1;
  $ultimo=0;
    }
   }
    mysql_data_seek($sql, 0);
    $ultimo=1;
  while($row = $conn->FetchArray($sql_Otros)){
     if ($row['Otros']!='' && $orderby!="PC" && $orderby!="Portatil" && $orderby!="Monitor"){
	echo "	<tr id=fila$Id$fId name=".$row['Id']." value='Otros'>";
  if($ultimo == 1){
	   echo " 		<td><img src='images/keyboard.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor' style='color:#F00'>".$row['Otros']."</div></td>";
	}else{
     echo "    <td><img src='images/keyboard.ico' style= 'float:left; width:16px; height:16px; margin-right:3px;'</img> <div class='inplace-editor'>".$row['Otros']."</div></td>";
  }
  echo "    <td style= 'width:90px;'>".fecha($row['Fecha_Otros'])."</td>";
  echo " 		<td style= 'width:10px;'>
               <img id='eliminar' onclick='if(confirm(".'"¿Desea eliminar este material?"'.")){eliminarFila($Id,$fId);xajax_eliminar(".$row['Id'].",".'"Otros"'.");};' src='images/delete.ico' class='sweet-tooltip' data-style-tooltip='tooltip-mini-slick' data-text-tooltip='Eliminar' style= 'width:16px; height:16px; '</img> 
            </td>";
	echo "	    </tr>";
  $fId=$fId+1;
  $ultimo=0;
      }
   }
 }else{echo "<script language='javascript'>mostrar($Id);</script>";}
	

	$Id = $Id+1;
	echo "</table>";

}

if ($vacio==1)  echo "<div>LO SENTIMOS, NO SE HA ENCONTRADO NINGUN RESULTADO CON LOS PARAMETROS SELECCIONADOS</div>";    // echo "<img src='images/notfound.jpg' style= position:relative; width:600px;/>";
?>





<!--/fieldset-->
</form> 
</div>  <!--final del div principal container-->
</body>
</html>