<?php
/**
 * Archivo para cargar la configuración del usuario en todas las páginas
 * 
 * Este archivo debe ser incluido al principio de cada página después de iniciar la sesión
 * y establecer la conexión a la base de datos.
 */

// Verificar que la sesión esté iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verificar que exista la conexión a la base de datos
if (!isset($con) || !$con) {
    include_once "./inc/conexionbd.php";
    $con = connection();
}

// Verificar que el usuario esté logueado
if (isset($_SESSION['usuario']) && isset($_SESSION['id'])) {
    // Obtener ID del usuario
    $id_usuario = $_SESSION['id'];
    
    // Incluir función para obtener configuración del usuario
    include_once "./php/funciones/config_usuario.php";
    
    // Obtener configuración del usuario
    $config_usuario = obtener_config_usuario($id_usuario, $con);
}
?>