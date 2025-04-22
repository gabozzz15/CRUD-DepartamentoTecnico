<?php
// Iniciar sesión
session_start();

// Incluir conexión a la base de datos
include "../../inc/conexionbd.php";
$con = connection();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

// Obtener ID del usuario
$id_usuario = $_SESSION['id'];

// Verificar que se haya enviado una acción
if (!isset($_POST['accion']) || $_POST['accion'] !== 'guardar_configuracion') {
    echo json_encode(['error' => 'Acción no válida']);
    exit;
}

// Preparar array para almacenar los campos a actualizar
$campos = [];
$valores = [];
$tipos = '';

// Verificar y procesar cada campo posible
if (isset($_POST['modo_oscuro'])) {
    $modo_oscuro = $_POST['modo_oscuro'] === 'true' ? 1 : 0;
    $campos[] = 'modo_oscuro = ?';
    $valores[] = $modo_oscuro;
    $tipos .= 'i'; // integer
}

if (isset($_POST['idioma'])) {
    $idioma = $_POST['idioma'];
    if ($idioma === 'es' || $idioma === 'en') {
        $campos[] = 'idioma = ?';
        $valores[] = $idioma;
        $tipos .= 's'; // string
    }
}

if (isset($_POST['tamano_fuente'])) {
    $tamano_fuente = $_POST['tamano_fuente'];
    if (in_array($tamano_fuente, ['small', 'medium', 'large'])) {
        $campos[] = 'tamano_fuente = ?';
        $valores[] = $tamano_fuente;
        $tipos .= 's'; // string
    }
}

// Verificar si hay campos para actualizar
if (empty($campos)) {
    echo json_encode(['error' => 'No se proporcionaron campos válidos para actualizar']);
    exit;
}

// Verificar si existe la configuración para el usuario
$query_check = "SELECT COUNT(*) as existe FROM configuraciones_usuario WHERE usuario_id = ?";
$stmt_check = mysqli_prepare($con, $query_check);
mysqli_stmt_bind_param($stmt_check, "i", $id_usuario);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$row_check = mysqli_fetch_assoc($result_check);
$existe = $row_check['existe'] > 0;

// Preparar la consulta SQL según si existe o no la configuración
if ($existe) {
    // Actualizar configuración existente
    $query = "UPDATE configuraciones_usuario SET " . implode(', ', $campos) . " WHERE usuario_id = ?";
    $stmt = mysqli_prepare($con, $query);
    
    // Añadir el ID de usuario al final de los valores
    $valores[] = $id_usuario;
    $tipos .= 'i'; // integer para el ID
    
    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, $tipos, ...$valores);
} else {
    // Crear configuración por defecto con los valores proporcionados
    $campos_default = [
        'usuario_id' => $id_usuario,
        'notif_email' => 1,
        'notif_sistema' => 1,
        'modo_oscuro' => 0,
        'tamano_fuente' => 'medium',
        'idioma' => 'es'
    ];
    
    // Actualizar con los valores proporcionados
    if (isset($_POST['modo_oscuro'])) {
        $campos_default['modo_oscuro'] = $_POST['modo_oscuro'] === 'true' ? 1 : 0;
    }
    
    if (isset($_POST['idioma'])) {
        $campos_default['idioma'] = $_POST['idioma'];
    }
    
    if (isset($_POST['tamano_fuente'])) {
        $campos_default['tamano_fuente'] = $_POST['tamano_fuente'];
    }
    
    // Crear consulta de inserción
    $query = "INSERT INTO configuraciones_usuario (usuario_id, notif_email, notif_sistema, modo_oscuro, tamano_fuente, idioma) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    
    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "iiiiss", 
        $campos_default['usuario_id'], 
        $campos_default['notif_email'], 
        $campos_default['notif_sistema'], 
        $campos_default['modo_oscuro'], 
        $campos_default['tamano_fuente'], 
        $campos_default['idioma']
    );
}

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Configuración guardada correctamente']);
} else {
    echo json_encode(['error' => 'Error al guardar la configuración: ' . mysqli_error($con)]);
}

// Cerrar la conexión
mysqli_stmt_close($stmt);
mysqli_close($con);