<?php 	
	//modelo: clase con todos los servicios para acceder a la bbdd
	require 'conexion_Libreria.php';

	class Libreria extends Conexion_Libreria	{
		//Método de consulta
		public function consultaLibros($pagina) {
			//variables de paginación // inicialización variables de paginación
			$filaInicial=0;
			$numFilasMostrar=15;
			try {
				$filaInicial=($pagina-1)*$numFilasMostrar;
				//Montar la sentencia sql
				$sql = "SELECT * FROM libros LIMIT $filaInicial,$numFilasMostrar";
				//ejecutar la sentencia
				$resultado = $this->conexion->query($sql);	
				//con el fetch_all ya obtenemos el array sin necesidad de un while
				//convierte en array asociativo
				$arrayLibros = $resultado->fetch_all(MYSQLI_ASSOC);

				//total de filas de la consulta
				$numeroFilas = $resultado->num_rows;
				$sql = "SELECT COUNT(*) as numeroFilas FROM libros";
				$resultado = $this->conexion->query($sql);
				$filas = $resultado->fetch_array(MYSQLI_ASSOC);
				//recuperar filas totales
				$numFilas=$filas['numeroFilas'];
				//calcular el número de páginas 
				$paginas=ceil($numFilas/$numFilasMostrar);

				//devolver el array al controlador
				$codigo='00';
				$mensaje='OK';
				$control=array('codigo'=>$codigo, 'mensaje'=> $mensaje);
				$respuesta=array($control, $arrayLibros, $paginas);
				return $respuesta;

			} catch (Exception $e) {
				throw new Exception($e->getCode().' '.$e->getMessage());
			}
		}


		//Método de alta
		public function altaLibro($titulo, $precio) {

			try {
				//validar los datos
				$this->validar($titulo, $precio);
				//montar sql
				$sql = "INSERT INTO libros  VALUES(NULL, '$titulo', '$precio')";

				//ejecutar
				$this->conexion->query($sql);
				//recuperar el id asignada en el alta
				$ultimoId=$this->conexion->insert_id;
				//respuesta
				$codigo='00';
				$mensaje='Alta efectuada';
				$respuesta=array('codigo'=>'00', 'mensaje'=> $mensaje,'ultimoId'=> $ultimoId);
				return $respuesta; 

			} catch (Exception $e) {
				throw new Exception('titulo ya existe', $e->getCode() );
			}
		}


		//Método de modificación
		public function modifLibro($idlibros,$titulo, $precio) {

			try {
				//validar los datos
				$this->validar($titulo, $precio);
				//montar sql
				$sql = "UPDATE libros  SET titulo='$titulo', precio='$precio' WHERE idlibros=$idlibros";

				//ejecutar
				$this->conexion->query($sql);
				//comprobar si se han realizado cambios en la fila
				$numFilas=$this->conexion->affected_rows;
				if ($numFilas==0) {
					$codigo='10';
					$mensaje='código libro no existe o no se ha modificado ningún dato';
				} else {
					$codigo='00';
					$mensaje='Modificación realizada';
				} 
				//respuesta
				
				$respuesta=array('codigo'=> $codigo, 'mensaje'=> $mensaje);
				return $respuesta; 

			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
		}

		//Método de baja	
		public function borrarLibro($idlibros) {

			try {
				//montar sql
				$sql="DELETE FROM libros WHERE idlibros='$idlibros'";
			
				//ejecutar
				$this->conexion->query($sql);
				$numFilas=$this->conexion->affected_rows;
				if ($numFilas==0) {
					throw new Exception('código libro no existe', 11);
				} 

				//respuesta
				$codigo='00';
				$mensaje='libro dado de baja';
				$respuesta=array('codigo'=>'00', 'mensaje'=> $mensaje);
				return $respuesta; 

			} catch (Exception $e) {
				if ($e->getCode()=='1451') {
					throw new Exception('tabla con otras relaciones / restricción semántica', $e->getCode());
				} else {
					throw new Exception($e->getMessage(), $e->getCode());
				}
			}
		}

		private function validar($titulo, $precio) {
			if (empty($titulo) || empty($precio)) {
				throw new Exception('Titulo y/o Precio, son obligatorios', 20);
			}
		}

	}	





//secccion para probar 
/*
	//provisional para pruebas
	$libreria = new Libreria();
	

	//Alta libro
	try {
		print_r($libreria->altalibro('zzzzz', 99)) ;
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
	
	//Modificación libro
	try {
		print_r($libreria->modiflibro(24,'bbbbb', 666)) ;		
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
	
	//Borrar libro
	try {
		print_r($libreria->borrarlibro(14)) ;		
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
*/
/*
	//Consulta libro
	try {
		$mensaje=$libreria->consultaLibros(1);
		print_r($mensaje);
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
*/

?>