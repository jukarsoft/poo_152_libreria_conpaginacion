<?php 

	//convertir todos los errores de la gestión de bbdd en excepciones
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	//conexión a bbdd mediante mysqli
	class Conexion_Libreria {
		protected $conexion;
		public function __construct() {	
			try {
				$this->conexion = new mysqli('localhost', 'root', '', 'libreria');
				$this->conexion->set_charset('utf8');
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
		}
	}
	//Prueba de conexión	
	//$conexion =  new Conexion();

?>