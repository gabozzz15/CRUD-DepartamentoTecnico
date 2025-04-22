<?php
/**
 * Obtiene la configuración del usuario desde la base de datos
 * 
 * @param int $id_usuario ID del usuario
 * @param mysqli $con Conexión a la base de datos
 * @return array Configuración del usuario
 */
function obtener_config_usuario($id_usuario, $con) {
    // Valores por defecto
    $config_usuario = [
        'notif_email' => 1,
        'notif_sistema' => 1,
        'modo_oscuro' => 0,
        'tamano_fuente' => 'medium',
        'idioma' => 'es'
    ];
    
    // Verificar si existe la tabla de configuraciones
    $query_check_table = "SHOW TABLES LIKE 'configuraciones_usuario'";
    $result_check_table = mysqli_query($con, $query_check_table);
    
    if(mysqli_num_rows($result_check_table) > 0) {
        // Obtener configuración actual del usuario
        $query_config = "SELECT * FROM configuraciones_usuario WHERE usuario_id = '$id_usuario'";
        $result_config = mysqli_query($con, $query_config);
        
        if(mysqli_num_rows($result_config) > 0) {
            $config_usuario = mysqli_fetch_assoc($result_config);
        }
    } else {
        // Crear tabla de configuraciones si no existe
        $query_crear_tabla = "CREATE TABLE IF NOT EXISTS configuraciones_usuario (
            usuario_id INT PRIMARY KEY,
            notif_email BOOLEAN DEFAULT 1,
            notif_sistema BOOLEAN DEFAULT 1,
            modo_oscuro BOOLEAN DEFAULT 0,
            tamano_fuente ENUM('small', 'medium', 'large') DEFAULT 'medium',
            idioma ENUM('es', 'en') DEFAULT 'es',
            FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
        )";
        mysqli_query($con, $query_crear_tabla);
    }
    
    return $config_usuario;
}
?>