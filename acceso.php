<?php 
session_start();
header('Content-type: application/json; charset=utf-8');
require_once "{$_SERVER['DOCUMENT_ROOT']}/taller/app/clases/Conexion.php";
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();


$usuario = $_POST['usu_alias'];
$clave = $_POST['usu_clave'];

// Usa una consulta preparada para prevenir la inyección SQL"$1 y $2"
$sql = "SELECT u.*, p.id_perfil, p.per_desc, f.id_funcionarios, f.id_personas,
p2.id_personas, p2.per_nombres ||' '|| p2.per_apellidos as funcionario, 
c.id_cargos, c.desc_cargo
FROM usuario AS u
INNER JOIN perfil AS p ON u.id_perfil = p.id_perfil 
INNER JOIN funcionarios AS f ON u.id_funcionarios = f.id_funcionarios
INNER JOIN personas AS p2 ON f.id_personas = p2.id_personas
INNER JOIN cargos AS c ON f.id_cargos = c.id_cargos
WHERE usu_login = $1 AND usu_clave = md5($2);";

// Ejecuta la consulta con parámetros
$resultado = pg_query_params($conexion, $sql, array($usuario, $clave));

// Procesa el resultado de la consulta
$datos = pg_fetch_assoc($resultado);
if($datos) {
    $_SESSION['usuario'] = $datos;
}
echo json_encode($datos);
?>