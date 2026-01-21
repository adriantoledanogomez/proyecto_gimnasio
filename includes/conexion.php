<?php
$host = "localhost";
$usuario = "root";
$password = "";
$bd = "gimnasio";

$conexion = mysqli_connect($host, $usuario, $password, $bd);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
