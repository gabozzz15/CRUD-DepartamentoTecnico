<?php

// REALIZADO POR GABRIEL BASTARDO
// https://github.com/gabozzz15

// Iniciar sesión para manejar el estado de login
session_start();

// Incluir el archivo de conexión a la base de datos
include "./inc/conexionbd.php";

// Si ya hay una sesión activa, redirigir al home
if(isset($_SESSION['usuario'])) {
    header("Location: home.php");
    exit();
}

// Procesar el formulario cuando se envía
if(isset($_POST['login'])) {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    // Establecer conexión a la base de datos
    $con = connection();
    
    // Consulta para verificar las credenciales
    $query = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($con, $query);
    
    // Verificar si el usuario existe
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Verificar la contraseña
        if(password_verify($password, $row['password'])) {
            // Credenciales correctas, crear sesión
            $_SESSION['id'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['usuario'] = $row['usuario'];
            
            // Redirigir al home
            header("Location: home.php");
            exit();
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta";
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Departamento Técnico</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <h2><i class="fas fa-laptop-medical"></i> Sistema Dept. Técnico</h2>
                <p class="text-muted">Control de Equipos</p>
                <a class="small mb-0" href="https://github.com/gabozzz15">By Gabriel Bastardo</a>
                
            </div>
            
            <div class="login-form">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label"><i class="fas fa-user"></i> Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>