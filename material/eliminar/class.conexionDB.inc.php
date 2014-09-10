<?php

class conexionBD
{
  	public $NombreBD = "";
  	public $Host = "";
  	public $NombreUsr = "";
  	public $PassWd = "";
  	public $id = "";
  
	function __construct()
	{
	
	

		$this->NombreBD = "pruebaphp";
		$this->Host = "localhost";
		$this->NombreUsr ="root";
		$this->PassWd = "";
		$this->id = "";
		$this->ConectarBD();
	}
	
	public function SeleccionBBDD($bbdd)
	{
		mysql_select_db($bbdd, $this->id);
        $this->NombreBD = $bbdd;
	}
	
	public function ConectarBD()
	{
	  	$this->id = mysql_connect($this->Host, $this->NombreUsr, $this->PassWd);
		mysql_select_db($this->NombreBD, $this->id);
	}

	public function EjecutarSQL($Str_SQL)
	{
		return mysql_query($Str_SQL, $this->id);
	}
	
    public function FetchArray($result)
    {
        return mysql_fetch_array($result);
    }

    public function FetchRow($result)
    {
        return mysql_fetch_row($result);
    }

    public function NumRows($result)
    {
        return mysql_num_rows($result);
    }

    public function ObtUltError(){
        return mysql_error();
    }
}
?>