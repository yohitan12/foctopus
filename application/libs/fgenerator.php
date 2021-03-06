<?php 

class fgenerator 
{
	private $conn = null;
	private $template = "";

	function __construct()
	{
		$this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}

	public function showTables(){
		$table = "";
		$tableList = array();
		$res = $this->conn->query("SHOW TABLES");
		while($cRow = mysqli_fetch_array($res))
		{	
			$table .= '<option value='.'"'.$cRow[0].'"'.'>'.$cRow[0].'</option>';
		}
		return $table;
	}

	public function create(){

		if(isset($_POST["table"])){

			if ($_POST["table"] != null) {
				$columns = $this->conn->query("SHOW COLUMNS FROM ".DB_NAME.".".$_POST["table"]);
				$array = array();
				while($cCol = mysqli_fetch_array($columns))
				{
					$array[] = array(
						'Field'=>$cCol["Field"],
						'Key'=>$cCol["Key"],
						'AutoIncrement' => $cCol["Extra"]
						);
				}

				if(isset($_POST["template"])){
					$this->template = $_POST["template"];
					echo $this->createModel($_POST["table"],$array)?"<script>alert(\"Buen trabajo! El modelo ha sido generado\")</script>":"<script>alert(\"Oh no! Ocurrio un error al generar el modelo\")</script>";
					echo $this->createController($_POST["table"],$array)?"<script>alert(\"Buen trabajo! El controlador ha sido generado\")</script>":"<script>alert(\"Oh no! Ocurrio un error al generar el controlador\")</script>";
				}else{
					echo "<script>alert(\"Oh no! Debes seleccionar un template\")</script>";
				}
			}else{
				echo "<script>alert(\"Oh no! Debes seleccionar una tabla de la base de datos\")</script>";
			}
		}
	}

	public function createModel($nombreClase, $columns){

		require APP.'libs/templates/models/'.$this->template.'.php';

		try{
			$archivo=fopen(APP.'model/model'.ucwords($nombreClase).'.php','w');
			fwrite($archivo, $cModel);
			fclose($archivo);
			return true;
		}catch(Excpetion $e){
			return false;
		}
	}

	public function createController($nombreClase, $columns){

		require APP.'libs/templates/controllers/'.$this->template.'.php';

		try{
			$archivo=fopen(APP.'controller/'.strtolower($nombreClase).'.php','w');//abrir archivo, nombre archivo, modo apertura
			fwrite($archivo, $cController);
			fclose($archivo); //cerrar archivo
			return true;
		}catch(Excpetion $e){
			return false;
		}

	}
}
?>