<?php

// REALIZADO POR GABRIEL BASTARDO
// https://github.com/gabozzz15

    // Inicio de Conexión
    session_start();
    include "./inc/conexionbd.php";
    $con = connection();
  
    // Verificación de que esté logueado el usuario
    if (isset($_SESSION['usuario'])){
        // Obtener datos del usuario
        $id_usuario = $_SESSION['id'];
        $query_usuario = "SELECT * FROM usuario WHERE id = '$id_usuario'";
        $result_usuario = mysqli_query($con, $query_usuario);
        $usuario = mysqli_fetch_assoc($result_usuario);
        
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
        
        // Incluir función para obtener configuración del usuario
        include "./php/funciones/config_usuario.php";
        
        // Procesar configuración
        $mensaje = '';
        $tipo_mensaje = '';
        
        if(isset($_POST['guardar_configuracion'])) {
            // Obtener valores de configuración
            $notif_email = isset($_POST['notif_email']) ? 1 : 0;
            $notif_sistema = isset($_POST['notif_sistema']) ? 1 : 0;
            $modo_oscuro = isset($_POST['modo_oscuro']) ? 1 : 0;
            $tamano_fuente = $_POST['tamano_fuente'];
            $idioma = $_POST['idioma'];
            
            // Preparar consulta con sentencia preparada
            $query_update_config = "REPLACE INTO configuraciones_usuario 
                                    (usuario_id, notif_email, notif_sistema, modo_oscuro, tamano_fuente, idioma) 
                                    VALUES 
                                    (?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($con, $query_update_config);
            mysqli_stmt_bind_param($stmt, "iiiiss", $id_usuario, $notif_email, $notif_sistema, $modo_oscuro, $tamano_fuente, $idioma);
            
            if(mysqli_stmt_execute($stmt)) {
                $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? "Configuration saved successfully" : "Configuración guardada correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? 
                    "Error saving configuration: " . mysqli_error($con) : 
                    "Error al guardar la configuración: " . mysqli_error($con);
                $tipo_mensaje = "danger";
            }
            
            mysqli_stmt_close($stmt);
        }
        
        // Obtener configuración actual del usuario
        $config_usuario = obtener_config_usuario($id_usuario, $con);
        
        // Procesar actualización de perfil
        if(isset($_POST['actualizar_perfil'])) {
            $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
            
            $query_update = "UPDATE usuario SET nombre = '$nombre' WHERE id = '$id_usuario'";
            
            if(mysqli_query($con, $query_update)) {
                $_SESSION['nombre'] = $nombre;
                $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? "Profile updated successfully" : "Perfil actualizado correctamente";
                $tipo_mensaje = "success";
                
                // Actualizar datos del usuario
                $result_usuario = mysqli_query($con, $query_usuario);
                $usuario = mysqli_fetch_assoc($result_usuario);
            } else {
                $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? 
                    "Error updating profile: " . mysqli_error($con) : 
                    "Error al actualizar el perfil: " . mysqli_error($con);
                $tipo_mensaje = "danger";
            }
        }
        
        // Procesar cambio de contraseña
        if(isset($_POST['cambiar_password'])) {
            $password_actual = $_POST['password_actual'];
            $password_nuevo = $_POST['password_nuevo'];
            $password_confirmar = $_POST['password_confirmar'];
            
            // Verificar contraseña actual
            $query_pass = "SELECT password FROM usuario WHERE id = '$id_usuario'";
            $result_pass = mysqli_query($con, $query_pass);
            $row_pass = mysqli_fetch_assoc($result_pass);
            
            if(password_verify($password_actual, $row_pass['password'])) {
                // Verificar que las nuevas contraseñas coincidan
                if($password_nuevo === $password_confirmar) {
                    // Encriptar nueva contraseña
                    $password_hash = password_hash($password_nuevo, PASSWORD_DEFAULT);
                    
                    // Actualizar contraseña
                    $query_update_pass = "UPDATE usuario SET password = '$password_hash' WHERE id = '$id_usuario'";
                    
                    if(mysqli_query($con, $query_update_pass)) {
                        $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? "Password updated successfully" : "Contraseña actualizada correctamente";
                        $tipo_mensaje = "success";
                    } else {
                        $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? 
                            "Error updating password: " . mysqli_error($con) : 
                            "Error al actualizar la contraseña: " . mysqli_error($con);
                        $tipo_mensaje = "danger";
                    }
                } else {
                    $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? "New passwords do not match" : "Las nuevas contraseñas no coinciden";
                    $tipo_mensaje = "warning";
                }
            } else {
                $mensaje = isset($_POST['idioma']) && $_POST['idioma'] == 'en' ? "Current password is incorrect" : "La contraseña actual es incorrecta";
                $tipo_mensaje = "danger";
            }
        }
        
        // Definir variables para los includes
        $titulo_pagina = "Mi Perfil";
        $pagina_actual = "perfil";
        
        // Obtener la pestaña activa
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'perfil';
        
        // Incluir el header
        include "./inc/header.php";
        
        // Incluir el sidebar
        include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fade-in" data-traducir="profile.title">Mi Perfil</h1>
                </div>
                
                <?php if(!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="profile-image mb-3">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo $usuario['nombre']; ?>&background=4e73df&color=fff&size=200" alt="Avatar" class="img-fluid rounded-circle" style="width: 150px; height: 150px; border: 3px solid var(--primary-color);">
                                </div>
                                <h5 class="card-title"><?php echo $usuario['nombre']; ?></h5>
                                <p class="card-text text-muted"><?php echo $usuario['usuario']; ?></p>
                                <p class="card-text">
                                    <span class="badge bg-primary">Administrador</span>
                                </p>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="?tab=perfil" class="list-group-item list-group-item-action <?php echo ($tab == 'perfil') ? 'active' : ''; ?>" data-traducir="profile.personal_info">
                                    <i class="fas fa-user me-2"></i> Información Personal
                                </a>
                                <a href="?tab=config" class="list-group-item list-group-item-action <?php echo ($tab == 'config') ? 'active' : ''; ?>" data-traducir="profile.configuration">
                                    <i class="fas fa-cog me-2"></i> Configuración
                                </a>
                                <a href="?tab=seguridad" class="list-group-item list-group-item-action <?php echo ($tab == 'seguridad') ? 'active' : ''; ?>" data-traducir="profile.security">
                                    <i class="fas fa-lock me-2"></i> Seguridad
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <?php if($tab == 'perfil'): ?>
                            <!-- Información Personal -->
                            <div class="card">
                                <div class="card-header" data-traducir="profile.personal_info">
                                    <i class="fas fa-user me-2"></i> Información Personal
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="nombre" class="form-label" data-traducir="profile.full_name">Nombre Completo</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="usuario" class="form-label" data-traducir="profile.username">Nombre de Usuario</label>
                                                <input type="text" class="form-control" id="usuario" value="<?php echo $usuario['usuario']; ?>" readonly>
                                                <small class="text-muted" data-traducir="profile.username_note">El nombre de usuario no se puede cambiar</small>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" name="actualizar_perfil" class="btn btn-primary" data-traducir="profile.buttons.save_changes">
                                                <i class="fas fa-save me-2"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php elseif($tab == 'config'): ?>
                            <!-- Configuración -->
                            <div class="card">
                                <div class="card-header" data-traducir="profile.configuration">
                                    <i class="fas fa-cog me-2"></i> Configuración
                                </div>
                                <div class="card-body">
                                    <form id="configuracion-form" action="?tab=config" method="POST">
                                        <h5 class="card-title mb-4" data-traducir="profile.app_preferences">Preferencias de la Aplicación</h5>
                                        
                                        <div class="mb-4">
                                            <h6 data-traducir="profile.notifications.title">Notificaciones</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="notif_email" name="notif_email" 
                                                       <?php echo isset($config_usuario['notif_email']) && $config_usuario['notif_email'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="notif_email" data-traducir="profile.notifications.email">Recibir notificaciones por correo</label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="notif_sistema" name="notif_sistema" 
                                                       <?php echo isset($config_usuario['notif_sistema']) && $config_usuario['notif_sistema'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="notif_sistema" data-traducir="profile.notifications.system">Notificaciones del sistema</label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6 data-traducir="profile.appearance.title">Apariencia</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="modo_oscuro" name="modo_oscuro" 
                                                       <?php echo isset($config_usuario['modo_oscuro']) && $config_usuario['modo_oscuro'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="modo_oscuro" data-traducir="profile.appearance.dark_mode">Modo oscuro</label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tamano_fuente" class="form-label" data-traducir="profile.appearance.font_size.title">Tamaño de fuente</label>
                                                <select class="form-select" id="tamano_fuente" name="tamano_fuente">
                                                    <option value="small" <?php echo isset($config_usuario['tamano_fuente']) && $config_usuario['tamano_fuente'] == 'small' ? 'selected' : ''; ?> data-traducir="profile.appearance.font_size.small">Pequeño</option>
                                                    <option value="medium" <?php echo !isset($config_usuario['tamano_fuente']) || $config_usuario['tamano_fuente'] == 'medium' ? 'selected' : ''; ?> data-traducir="profile.appearance.font_size.medium">Mediano</option>
                                                    <option value="large" <?php echo isset($config_usuario['tamano_fuente']) && $config_usuario['tamano_fuente'] == 'large' ? 'selected' : ''; ?> data-traducir="profile.appearance.font_size.large">Grande</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6 data-traducir="profile.language.title">Idioma</h6>
                                            <div class="mb-3">
                                                <label for="idioma" class="form-label" data-traducir="profile.language.title">Seleccionar idioma</label>
                                                <select class="form-select" id="idioma" name="idioma">
                                                    <option value="es" <?php echo !isset($config_usuario['idioma']) || $config_usuario['idioma'] == 'es' ? 'selected' : ''; ?> data-traducir="profile.language.spanish">Español</option>
                                                    <option value="en" <?php echo isset($config_usuario['idioma']) && $config_usuario['idioma'] == 'en' ? 'selected' : ''; ?> data-traducir="profile.language.english">English</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" name="guardar_configuracion" class="btn btn-primary" data-traducir="profile.buttons.save_config">
                                                <i class="fas fa-save me-2"></i> Guardar Configuración
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php elseif($tab == 'seguridad'): ?>
                            <!-- Seguridad -->
                            <div class="card">
                                <div class="card-header" data-traducir="profile.security">
                                    <i class="fas fa-lock me-2"></i> Seguridad
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Cambiar Contraseña</h5>
                                    
                                    <form action="" method="POST">
                                        <div class="mb-3">
                                            <label for="password_actual" class="form-label" data-traducir="profile.current_password">Contraseña Actual</label>
                                            <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_nuevo" class="form-label" data-traducir="profile.new_password">Nueva Contraseña</label>
                                            <input type="password" class="form-control" id="password_nuevo" name="password_nuevo" required>
                                            <div class="form-text" data-traducir="profile.password_requirements">La contraseña debe tener al menos 8 caracteres, incluir letras mayúsculas, minúsculas y números.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmar" class="form-label" data-traducir="profile.confirm_password">Confirmar Nueva Contraseña</label>
                                            <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                                        </div>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" name="cambiar_password" class="btn btn-primary" data-traducir="profile.buttons.change_password">
                                                <i class="fas fa-key me-2"></i> Cambiar Contraseña
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <hr class="my-4">
                                    
                                    <h5 class="card-title mb-4" data-traducir="profile.active_sessions">Sesiones Activas</h5>
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1" data-traducir="profile.current_session">Sesión Actual</h6>
                                                <small class="text-success" data-traducir="profile.active_now">Activa ahora</small>
                                            </div>
                                            <p class="mb-1">
                                                <i class="fas fa-desktop me-2"></i> 
                                                <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-2"></i> 
                                                <span data-traducir="profile.ip">IP</span>: <?php echo $_SERVER['REMOTE_ADDR']; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script para aplicar configuraciones en tiempo real -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener los valores de configuración desde PHP
        const configuracion = {
            modoOscuro: <?php echo isset($config_usuario['modo_oscuro']) && $config_usuario['modo_oscuro'] ? 'true' : 'false'; ?>,
            tamanoFuente: '<?php echo $config_usuario['tamano_fuente'] ?? 'medium'; ?>',
            idioma: '<?php echo $config_usuario['idioma'] ?? 'es'; ?>'
        };

        // Aplicar tamaño de fuente
        function aplicarTamanoFuente(tamano) {
            document.body.classList.remove('font-small', 'font-medium', 'font-large');
            document.body.classList.add(`font-${tamano}`);
            
            // Ajustar tamaños de fuente específicos
            const estilosFuente = document.createElement('style');
            estilosFuente.textContent = `
                body.font-small { font-size: 0.875rem !important; }
                body.font-medium { font-size: 1rem !important; }
                body.font-large { font-size: 1.125rem !important; }
                
                body.font-small h1 { font-size: 1.5rem !important; }
                body.font-medium h1 { font-size: 2rem !important; }
                body.font-large h1 { font-size: 2.5rem !important; }
                
                body.font-small h2 { font-size: 1.25rem !important; }
                body.font-medium h2 { font-size: 1.75rem !important; }
                body.font-large h2 { font-size: 2.25rem !important; }
                
                body.font-small .card-title { font-size: 1rem !important; }
                body.font-medium .card-title { font-size: 1.25rem !important; }
                body.font-large .card-title { font-size: 1.5rem !important; }
            `;
            document.head.appendChild(estilosFuente);
        }

        // Aplicar configuraciones
        function aplicarConfiguraciones() {
            // Modo oscuro
            if (configuracion.modoOscuro) {
                document.body.classList.add('modo-oscuro');
            } else {
                document.body.classList.remove('modo-oscuro');
            }
            
            // Tamaño de fuente
            aplicarTamanoFuente(configuracion.tamanoFuente);
        }
        
        // Aplicar configuraciones al cargar
        aplicarConfiguraciones();
        
        // Añadir estilos para modo oscuro
        const estilosAdicionales = document.createElement('style');
        estilosAdicionales.textContent = `
            /* Modo oscuro - Paleta mejorada */
            body.modo-oscuro {
                background-color: #1a1a2e !important;
                color: #ffffff !important;
                transition: all 0.3s ease;
            }
            
            body.modo-oscuro .card {
                background-color: #16213e !important;
                color: #ffffff !important;
                border-color: #2a3457 !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2) !important;
            }
            
            body.modo-oscuro .card-header {
                background-color: #1f2b49 !important;
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .table {
                color: #ffffff !important;
                background-color: #16213e !important;
            }
            
            body.modo-oscuro .table thead th {
                background-color: #1f2b49 !important;
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .table td {
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .sidebar {
                background: linear-gradient(180deg, #0f3460 0%, #1a1a2e 100%) !important;
            }
            
            body.modo-oscuro .sidebar .nav-link:not(.active) {
                color: #b8c1ec !important;
            }
            
            body.modo-oscuro .navbar {
                background-color: #0a2342 !important;
                border-bottom: 1px solid #2a3457 !important;
            }
            
            body.modo-oscuro .navbar-brand,
            body.modo-oscuro .navbar-nav .nav-link {
                color: #ffffff !important;
            }
            
            body.modo-oscuro .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
            }
            
            body.modo-oscuro .nav-link.active {
                background-color: #533483 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .btn-primary {
                background-color: #533483 !important;
                border-color: #533483 !important;
            }
            
            body.modo-oscuro .btn-primary:hover {
                background-color: #6247aa !important;
                border-color: #6247aa !important;
            }
            
            body.modo-oscuro .btn-outline-secondary {
                color: #ffffff !important;
                border-color: #ffffff !important;
            }
            
            body.modo-oscuro .btn-outline-secondary:hover {
                background-color: #2a3457 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .form-control, 
            body.modo-oscuro .form-select {
                background-color: #1f2b49 !important;
                border-color: #2a3457 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .form-control:focus, 
            body.modo-oscuro .form-select:focus {
                box-shadow: 0 0 0 0.25rem rgba(83, 52, 131, 0.25) !important;
            }
            
            body.modo-oscuro .modal-content {
                background-color: #16213e !important;
                color: #ffffff !important;
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .modal-header,
            body.modo-oscuro .modal-footer {
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .footer {
                background-color: #0a2342 !important;
                color: #ffffff !important;
                border-top: 1px solid #2a3457 !important;
            }
            
            body.modo-oscuro .alert {
                background-color: #1f2b49 !important;
                color: #ffffff !important;
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro a {
                color: #a78bfa !important;
            }
            
            body.modo-oscuro a:hover {
                color: #c4b5fd !important;
            }
            
            body.modo-oscuro .accordion-button {
                background-color: #1f2b49 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .accordion-button:not(.collapsed) {
                background-color: #2a3457 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .accordion-body {
                background-color: #16213e !important;
                border-color: #2a3457 !important;
            }
            
            body.modo-oscuro .list-group-item {
                background-color: #16213e !important;
                border-color: #2a3457 !important;
                color: #ffffff !important;
            }
            
            body.modo-oscuro .list-group-item.active {
                background-color: #533483 !important;
                border-color: #533483 !important;
            }
            
            /* Iconos y texto */
            body.modo-oscuro i,
            body.modo-oscuro .text-muted {
                color: #ffffff !important;
            }
        `;
        document.head.appendChild(estilosAdicionales);
        
        // Manejar cambio de idioma en tiempo real
        const selectIdioma = document.getElementById('idioma');
        if (selectIdioma) {
            selectIdioma.addEventListener('change', function(e) {
                const nuevoIdioma = e.target.value;
                console.log(`Cambiando idioma a: ${nuevoIdioma}`);
                
                // Actualizar el traductor si existe
                if (window.traductor) {
                    window.traductor.cambiarIdioma(nuevoIdioma);
                }
            });
        }
    });
    </script>
    
<?php
    // Incluir el footer
    include "./inc/footer.php";
} else {
    echo "<script>
            alert('No puede ingresar a esa página sin loguearse');
            location.href = 'index.php'
        </script>";
}           
?>