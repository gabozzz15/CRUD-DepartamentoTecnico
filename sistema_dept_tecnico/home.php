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
        
        // Incluir función para obtener configuración del usuario
        include "./php/funciones/config_usuario.php";
        
        // Obtener configuración del usuario
        $config_usuario = obtener_config_usuario($id_usuario, $con);
        
        // Obtener estadísticas para el dashboard
        
        // Contar equipos
        $query_equipos = "SELECT COUNT(*) as total FROM equipos";
        $result_equipos = mysqli_query($con, $query_equipos);
        $total_equipos = mysqli_fetch_assoc($result_equipos)['total'];
        
        // Contar técnicos
        $query_tecnicos = "SELECT COUNT(*) as total FROM tecnicos WHERE estado = 'activo'";
        $result_tecnicos = mysqli_query($con, $query_tecnicos);
        $total_tecnicos = mysqli_fetch_assoc($result_tecnicos)['total'];
        
        // Contar órdenes pendientes
        $query_pendientes = "SELECT COUNT(*) as total FROM ordenes_servicio WHERE estado = 'pendiente'";
        $result_pendientes = mysqli_query($con, $query_pendientes);
        $total_pendientes = mysqli_fetch_assoc($result_pendientes)['total'];
        
        // Contar órdenes completadas
        $query_completadas = "SELECT COUNT(*) as total FROM ordenes_servicio WHERE estado = 'entregado'";
        $result_completadas = mysqli_query($con, $query_completadas);
        $total_completadas = mysqli_fetch_assoc($result_completadas)['total'];
        
        // Definir variables para los includes
        $titulo_pagina = "Dashboard";
        $pagina_actual = "home";
        
        // Incluir el header
        include "./inc/header.php";
        
        // Incluir el sidebar
        include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fade-in" data-traducir="home.title">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-alt me-2"></i> Hoy: <?php echo date('d/m/Y'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bienvenida personalizada -->
                <div class="alert alert-light mb-4 fade-in" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x me-3 text-primary"></i>
                        <div>
                            <h4 class="alert-heading mb-1" data-traducir="home.welcome.title">¡Bienvenido al Departamento Técnico!</h4>
                            <p class="mb-0" data-traducir="home.welcome.subtitle">Gestiona tus equipos, técnicos y órdenes de servicio de manera eficiente.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tarjetas de estadísticas -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4 card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title" data-traducir="home.stats.equipment.title">Equipos</h5>
                                        <h2 class="mb-0"><?php echo $total_equipos; ?></h2>
                                        <p class="small mb-0 mt-2 opacity-75" data-traducir="home.stats.equipment.subtitle">Total registrados</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-laptop fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="equipos.php" data-traducir="home.stats.equipment.details">Ver detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4 card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title" data-traducir="home.stats.technicians.title">Técnicos</h5>
                                        <h2 class="mb-0"><?php echo $total_tecnicos; ?></h2>
                                        <p class="small mb-0 mt-2 opacity-75" data-traducir="home.stats.technicians.subtitle">Activos actualmente</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-users-cog fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="tecnicos.php" data-traducir="home.stats.technicians.details">Ver detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4 card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title" data-traducir="home.stats.pending.title">Pendientes</h5>
                                        <h2 class="mb-0"><?php echo $total_pendientes; ?></h2>
                                        <p class="small mb-0 mt-2 opacity-75" data-traducir="home.stats.pending.subtitle">Órdenes en proceso</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-clock fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="ordenes.php?estado=pendiente" data-traducir="home.stats.pending.details">Ver detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white mb-4 card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title" data-traducir="home.stats.completed.title">Completadas</h5>
                                        <h2 class="mb-0"><?php echo $total_completadas; ?></h2>
                                        <p class="small mb-0 mt-2 opacity-75" data-traducir="home.stats.completed.subtitle">Órdenes finalizadas</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-check-circle fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="ordenes.php?estado=entregado" data-traducir="home.stats.completed.details">Ver detalles</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de acciones rápidas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-bolt"></i> <span data-traducir="home.quick_actions.title">Acciones Rápidas</span></span>
                                <button class="btn btn-sm btn-outline-primary" data-traducir="home.quick_actions.view_all">Ver todas</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                        <a href="ordenes.php?action=nueva" class="btn btn-primary w-100 py-3">
                                            <i class="fas fa-plus-circle mb-2 fa-2x"></i>
                                            <div data-traducir="home.quick_actions.new_order">Nueva Orden</div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                        <a href="equipos.php?action=nuevo" class="btn btn-success w-100 py-3">
                                            <i class="fas fa-laptop mb-2 fa-2x"></i>
                                            <div data-traducir="home.quick_actions.new_equipment">Nuevo Equipo</div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                        <a href="tecnicos.php?action=nuevo" class="btn btn-warning w-100 py-3">
                                            <i class="fas fa-user-plus mb-2 fa-2x"></i>
                                            <div data-traducir="home.quick_actions.new_technician">Nuevo Técnico</div>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <a href="reportes.php" class="btn btn-info w-100 py-3">
                                            <i class="fas fa-chart-bar mb-2 fa-2x"></i>
                                            <div data-traducir="home.quick_actions.reports">Ver Reportes</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Órdenes recientes -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-table me-1"></i> <span data-traducir="home.recent_orders.title">Órdenes de servicio recientes</span></span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> <span data-traducir="home.recent_orders.filter">Filtrar</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#" data-traducir="home.recent_orders.filter_options.all">Todas</a></li>
                                <li><a class="dropdown-item" href="#" data-traducir="home.recent_orders.filter_options.pending">Pendientes</a></li>
                                <li><a class="dropdown-item" href="#" data-traducir="home.recent_orders.filter_options.completed">Completadas</a></li>
                                <li><a class="dropdown-item" href="#" data-traducir="home.recent_orders.filter_options.not_repairable">No reparables</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th data-traducir="home.recent_orders.table_headers.id">ID</th>
                                        <th data-traducir="home.recent_orders.table_headers.equipment">Equipo</th>
                                        <th data-traducir="home.recent_orders.table_headers.technician">Técnico</th>
                                        <th data-traducir="home.recent_orders.table_headers.status">Estado</th>
                                        <th data-traducir="home.recent_orders.table_headers.entry_date">Fecha Entrada</th>
                                        <th data-traducir="home.recent_orders.table_headers.actions">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Consulta para obtener las órdenes más recientes
                                    $query_ordenes = "SELECT o.id_orden, e.tipo, e.marca, t.nombre, t.apellido, o.estado, o.fecha_entrada 
                                                     FROM ordenes_servicio o
                                                     JOIN equipos e ON o.id_equipo = e.id_equipo
                                                     JOIN tecnicos t ON o.id_tecnico = t.id_tecnico
                                                     ORDER BY o.fecha_entrada DESC LIMIT 5";
                                    $result_ordenes = mysqli_query($con, $query_ordenes);
                                    
                                    if(mysqli_num_rows($result_ordenes) > 0) {
                                        while($row = mysqli_fetch_assoc($result_ordenes)) {
                                            // Definir clase de estado para el badge
                                            $estado_class = '';
                                            $icon_class = '';
                                            switch($row['estado']) {
                                                case 'pendiente':
                                                    $estado_class = 'bg-warning';
                                                    $icon_class = 'fa-clock';
                                                    break;
                                                case 'reparado':
                                                    $estado_class = 'bg-primary';
                                                    $icon_class = 'fa-tools';
                                                    break;
                                                case 'entregado':
                                                    $estado_class = 'bg-success';
                                                    $icon_class = 'fa-check-circle';
                                                    break;
                                                case 'no_reparable':
                                                    $estado_class = 'bg-danger';
                                                    $icon_class = 'fa-times-circle';
                                                    break;
                                            }
                                            
                                            echo "<tr>";
                                            echo "<td><strong>#".$row['id_orden']."</strong></td>";
                                            echo "<td>".ucfirst($row['tipo'])." ".$row['marca']."</td>";
                                            echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                            echo "<td><span class='badge ".$estado_class."'><i class='fas ".$icon_class." me-1'></i> ".ucfirst($row['estado'])."</span></td>";
                                            echo "<td>".date('d/m/Y H:i', strtotime($row['fecha_entrada']))."</td>";
                                            echo "<td class='action-buttons'>
                                                    <a href='ordenes.php?id=".$row['id_orden']."' class='btn btn-sm btn-info' title='Ver detalles'>
                                                        <i class='fas fa-eye'></i>
                                                    </a>
                                                    <a href='ordenes.php?id=".$row['id_orden']."&action=edit' class='btn btn-sm btn-primary' title='Editar'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-4'><i class='fas fa-info-circle me-2 text-info'></i> <span data-traducir='home.recent_orders.no_orders'>No hay órdenes registradas</span></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="ordenes.php" class="btn btn-primary" data-traducir="home.recent_orders.view_all">Ver todas las órdenes</a>
                    </div>
                </div>
                
                <!-- Resumen de actividad reciente -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-1"></i> <span data-traducir="home.activity_summary.title">Resumen de actividad</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="activityChart" width="100%" height="40"></canvas>
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-3" data-traducir="home.activity_summary.monthly_stats">Estadísticas del mes</h5>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span data-traducir="home.activity_summary.completed_orders">Órdenes completadas</span>
                                        <span class="text-success">65%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span data-traducir="home.activity_summary.pending_orders">Órdenes pendientes</span>
                                        <span class="text-warning">25%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span data-traducir="home.activity_summary.not_repairable_orders">No reparables</span>
                                        <span class="text-danger">10%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 10%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    
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