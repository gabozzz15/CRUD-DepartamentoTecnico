<!DOCTYPE html>
<html lang="<?php echo isset($config_usuario['idioma']) ? $config_usuario['idioma'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?> - Sistema Departamento Técnico</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/1802/1802977.png">
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Sistema de gestión para departamento técnico. Control de equipos, técnicos y órdenes de servicio.">
    <meta name="keywords" content="departamento técnico, gestión, equipos, reparación, servicio técnico">
    
    <style>
        /* Animaciones adicionales para la carga inicial */
        body {
            opacity: 0;
            animation: fadeInPage 0.5s ease-in-out forwards;
            animation-delay: 0.2s;
        }
        
        @keyframes fadeInPage {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Barra de navegación superior */
        .top-navbar {
            background: #ffffff;
            padding: 10px 20px;
            color: #333;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .top-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        
        .top-navbar .navbar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .top-navbar .nav-link {
            color: #555;
            transition: all 0.3s;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .top-navbar .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }
        
        .top-navbar .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
            margin-top: 10px;
        }
        
        .top-navbar .dropdown-item {
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .top-navbar .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }
    </style>
    
    <style>
        /* Estilos para modo oscuro */
        body.modo-oscuro {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar {
            background-color: #0a2342 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2) !important;
            border-bottom: 1px solid #2a3457 !important;
        }
        
        body.modo-oscuro .top-navbar .navbar-brand {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar .navbar-brand i {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar .nav-link {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar .dropdown-menu {
            background-color: #16213e !important;
            border-color: #2a3457 !important;
        }
        
        body.modo-oscuro .top-navbar .dropdown-item {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .top-navbar .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        
        body.modo-oscuro h1,
        body.modo-oscuro h2,
        body.modo-oscuro h3,
        body.modo-oscuro h4,
        body.modo-oscuro h5,
        body.modo-oscuro h6 {
            color: #ffffff !important;
        }
        
        body.modo-oscuro .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <i class="fas fa-tools"></i> DeptTécnico
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarTop">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Botones de modo oscuro/claro -->
                    <li class="nav-item me-2">
                        <div class="btn-group" role="group" aria-label="Cambiar tema">
                            <button type="button" id="btn-modo-claro" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modo Claro">
                                <i class="fas fa-sun"></i>
                            </button>
                            <button type="button" id="btn-modo-oscuro" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modo Oscuro">
                                <i class="fas fa-moon"></i>
                            </button>
                        </div>
                    </li>
                    
                    <!-- Selector de idioma -->
                    <li class="nav-item me-2">
                        <div class="btn-group" role="group" aria-label="Cambiar idioma">
                            <button type="button" id="btn-idioma-es" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Español">
                                <span>ES</span>
                            </button>
                            <button type="button" id="btn-idioma-en" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="English">
                                <span>EN</span>
                            </button>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ayuda" data-traducir="navigation.help">
                            <i class="fas fa-question-circle"></i> Ayuda
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['nombre']; ?>&background=4e73df&color=fff" alt="Avatar" class="user-avatar">
                            <span><?php echo $_SESSION['nombre']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2 text-primary"></i> <span data-traducir="navigation.profile">Mi Perfil</span></a></li>
                            <li><a class="dropdown-item" href="perfil.php?tab=config"><i class="fas fa-cog me-2 text-primary"></i> <span data-traducir="navigation.configuration">Configuración</span></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="php/logout.php"><i class="fas fa-sign-out-alt me-2 text-primary"></i> <span data-traducir="navigation.logout">Cerrar Sesión</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            
    <!-- Script para los botones de modo oscuro/claro y selección de idioma -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener referencias a los botones
        const btnModoClaro = document.getElementById('btn-modo-claro');
        const btnModoOscuro = document.getElementById('btn-modo-oscuro');
        const btnIdiomaEs = document.getElementById('btn-idioma-es');
        const btnIdiomaEn = document.getElementById('btn-idioma-en');
        
        // Obtener configuración actual
        const modoOscuroActivo = document.body.classList.contains('modo-oscuro');
        const idiomaActual = document.documentElement.lang || 'es';
        
        // Actualizar estado visual de los botones según la configuración actual
        function actualizarEstadoBotones() {
            // Modo oscuro/claro
            if (document.body.classList.contains('modo-oscuro')) {
                btnModoOscuro.classList.add('active', 'btn-secondary');
                btnModoOscuro.classList.remove('btn-outline-secondary');
                btnModoClaro.classList.remove('active', 'btn-secondary');
                btnModoClaro.classList.add('btn-outline-secondary');
            } else {
                btnModoClaro.classList.add('active', 'btn-secondary');
                btnModoClaro.classList.remove('btn-outline-secondary');
                btnModoOscuro.classList.remove('active', 'btn-secondary');
                btnModoOscuro.classList.add('btn-outline-secondary');
            }
            
            // Idioma
            const idiomaActual = document.documentElement.lang || 'es';
            if (idiomaActual === 'es') {
                btnIdiomaEs.classList.add('active', 'btn-secondary');
                btnIdiomaEs.classList.remove('btn-outline-secondary');
                btnIdiomaEn.classList.remove('active', 'btn-secondary');
                btnIdiomaEn.classList.add('btn-outline-secondary');
            } else {
                btnIdiomaEn.classList.add('active', 'btn-secondary');
                btnIdiomaEn.classList.remove('btn-outline-secondary');
                btnIdiomaEs.classList.remove('active', 'btn-secondary');
                btnIdiomaEs.classList.add('btn-outline-secondary');
            }
        }
        
        // Inicializar estado de botones
        actualizarEstadoBotones();
        
        // Manejar clic en botón de modo claro
        btnModoClaro.addEventListener('click', function() {
            document.body.classList.remove('modo-oscuro');
            localStorage.setItem('modo_oscuro', 'false');
            
            // Si estamos en la página de perfil, actualizar el checkbox
            const checkboxModoOscuro = document.getElementById('modo_oscuro');
            if (checkboxModoOscuro) {
                checkboxModoOscuro.checked = false;
            }
            
            actualizarEstadoBotones();
            
            // Enviar solicitud AJAX para guardar la configuración en la base de datos
            guardarConfiguracion({modo_oscuro: false});
        });
        
        // Manejar clic en botón de modo oscuro
        btnModoOscuro.addEventListener('click', function() {
            document.body.classList.add('modo-oscuro');
            localStorage.setItem('modo_oscuro', 'true');
            
            // Si estamos en la página de perfil, actualizar el checkbox
            const checkboxModoOscuro = document.getElementById('modo_oscuro');
            if (checkboxModoOscuro) {
                checkboxModoOscuro.checked = true;
            }
            
            actualizarEstadoBotones();
            
            // Enviar solicitud AJAX para guardar la configuración en la base de datos
            guardarConfiguracion({modo_oscuro: true});
        });
        
        // Manejar clic en botón de idioma español
        btnIdiomaEs.addEventListener('click', function() {
            if (window.traductor) {
                window.traductor.cambiarIdioma('es');
                
                // Si estamos en la página de perfil, actualizar el select
                const selectIdioma = document.getElementById('idioma');
                if (selectIdioma) {
                    selectIdioma.value = 'es';
                }
                
                actualizarEstadoBotones();
                
                // Enviar solicitud AJAX para guardar la configuración en la base de datos
                guardarConfiguracion({idioma: 'es'});
            }
        });
        
        // Manejar clic en botón de idioma inglés
        btnIdiomaEn.addEventListener('click', function() {
            if (window.traductor) {
                window.traductor.cambiarIdioma('en');
                
                // Si estamos en la página de perfil, actualizar el select
                const selectIdioma = document.getElementById('idioma');
                if (selectIdioma) {
                    selectIdioma.value = 'en';
                }
                
                actualizarEstadoBotones();
                
                // Enviar solicitud AJAX para guardar la configuración en la base de datos
                guardarConfiguracion({idioma: 'en'});
            }
        });
        
        // Función para guardar configuración mediante AJAX
        function guardarConfiguracion(config) {
            // Crear objeto FormData para enviar datos
            const formData = new FormData();
            
            // Añadir datos de configuración
            for (const key in config) {
                formData.append(key, config[key]);
            }
            
            // Añadir acción
            formData.append('accion', 'guardar_configuracion');
            
            // Enviar solicitud AJAX
            fetch('php/funciones/guardar_config_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Configuración guardada:', data);
            })
            .catch(error => {
                console.error('Error al guardar configuración:', error);
            });
        }
        
        // Escuchar evento de cambio de idioma del traductor
        document.addEventListener('traduccionesAplicadas', function(e) {
            actualizarEstadoBotones();
        });
    });
    </script>