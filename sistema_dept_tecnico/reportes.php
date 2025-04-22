<?php

// REALIZADO POR GABRIEL BASTARDO
// https://github.com/gabozzz15

// Inicio de Conexión
session_start();
include "./inc/conexionbd.php";
$con = connection();

// Verificación de que esté logueado el usuario
if (isset($_SESSION['usuario'])) {
    // Cargar configuración del usuario
    include "./php/funciones/cargar_configuracion.php";
    
    // Función para obtener datos de reportes según el tipo y fechas
    function obtenerDatosReporte($con, $tipo, $fecha_inicio = null, $fecha_fin = null) {
        $where_fecha = "";
        if($fecha_inicio && $fecha_fin) {
            $where_fecha = "AND o.fecha_entrada BETWEEN '$fecha_inicio' AND '$fecha_fin 23:59:59'";
        }
        
        $datos = array();
        
        switch($tipo) {
            case 'tecnicos':
                // Reporte de órdenes por técnico
                $query = "SELECT t.id_tecnico, t.nombre, t.apellido, 
                         COUNT(o.id_orden) as total_ordenes,
                         SUM(CASE WHEN o.estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                         SUM(CASE WHEN o.estado = 'reparado' THEN 1 ELSE 0 END) as reparados,
                         SUM(CASE WHEN o.estado = 'entregado' THEN 1 ELSE 0 END) as entregados,
                         SUM(CASE WHEN o.estado = 'no_reparable' THEN 1 ELSE 0 END) as no_reparables
                         FROM tecnicos t
                         LEFT JOIN ordenes_servicio o ON t.id_tecnico = o.id_tecnico
                         WHERE t.estado = 'activo' $where_fecha
                         GROUP BY t.id_tecnico
                         ORDER BY total_ordenes DESC";
                break;
                
            case 'equipos':
                // Reporte de órdenes por tipo de equipo
                $query = "SELECT e.tipo, COUNT(o.id_orden) as total_ordenes,
                         SUM(CASE WHEN o.estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                         SUM(CASE WHEN o.estado = 'reparado' THEN 1 ELSE 0 END) as reparados,
                         SUM(CASE WHEN o.estado = 'entregado' THEN 1 ELSE 0 END) as entregados,
                         SUM(CASE WHEN o.estado = 'no_reparable' THEN 1 ELSE 0 END) as no_reparables
                         FROM equipos e
                         LEFT JOIN ordenes_servicio o ON e.id_equipo = o.id_equipo
                         WHERE 1=1 $where_fecha
                         GROUP BY e.tipo
                         ORDER BY total_ordenes DESC";
                break;
                
            case 'departamentos':
                // Reporte de órdenes por departamento
                $query = "SELECT e.departamento, COUNT(o.id_orden) as total_ordenes,
                         SUM(CASE WHEN o.estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                         SUM(CASE WHEN o.estado = 'reparado' THEN 1 ELSE 0 END) as reparados,
                         SUM(CASE WHEN o.estado = 'entregado' THEN 1 ELSE 0 END) as entregados,
                         SUM(CASE WHEN o.estado = 'no_reparable' THEN 1 ELSE 0 END) as no_reparables
                         FROM equipos e
                         LEFT JOIN ordenes_servicio o ON e.id_equipo = o.id_equipo
                         WHERE 1=1 $where_fecha
                         GROUP BY e.departamento
                         ORDER BY total_ordenes DESC";
                break;
                
            case 'tiempo':
                // Reporte de tiempo promedio de reparación
                $query = "SELECT t.id_tecnico, t.nombre, t.apellido,
                         COUNT(o.id_orden) as total_ordenes,
                         AVG(TIMESTAMPDIFF(HOUR, o.fecha_entrada, o.fecha_reparacion)) as promedio_horas
                         FROM tecnicos t
                         JOIN ordenes_servicio o ON t.id_tecnico = o.id_tecnico
                         WHERE o.fecha_reparacion IS NOT NULL $where_fecha
                         GROUP BY t.id_tecnico
                         ORDER BY promedio_horas ASC";
                break;
        }
        
        $result = mysqli_query($con, $query);
        if($result) {
            while($row = mysqli_fetch_assoc($result)) {
                $datos[] = $row;
            }
        }
        
        return $datos;
    }
    
    // Procesar formulario de reporte
    $tipo_reporte = isset($_GET['tipo']) ? $_GET['tipo'] : 'tecnicos';
    $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01'); // Primer día del mes actual
    $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d'); // Hoy
    
    // Obtener datos del reporte
    $datos_reporte = obtenerDatosReporte($con, $tipo_reporte, $fecha_inicio, $fecha_fin);
    
    // Definir variables para los includes
    $titulo_pagina = "Reportes";
    $pagina_actual = "reportes";
    
    // Incluir el header
    include "./inc/header.php";
    
    // Incluir el sidebar
    include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" data-traducir="reports.title">Reportes</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> <span data-traducir="reports.print">Imprimir Reporte</span>
                        </button>
                    </div>
                </div>
                
                <!-- Filtros de reporte -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-filter"></i> <span data-traducir="reports.filters.title">Filtros de Reporte</span>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="tipo" class="form-label" data-traducir="reports.filters.report_type">Tipo de Reporte</label>
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="tecnicos" <?php echo ($tipo_reporte == 'tecnicos') ? 'selected' : ''; ?> data-traducir="reports.filters.report_types.technicians">Órdenes por Técnico</option>
                                        <option value="equipos" <?php echo ($tipo_reporte == 'equipos') ? 'selected' : ''; ?> data-traducir="reports.filters.report_types.equipment">Órdenes por Tipo de Equipo</option>
                                        <option value="departamentos" <?php echo ($tipo_reporte == 'departamentos') ? 'selected' : ''; ?> data-traducir="reports.filters.report_types.departments">Órdenes por Departamento</option>
                                        <option value="tiempo" <?php echo ($tipo_reporte == 'tiempo') ? 'selected' : ''; ?> data-traducir="reports.filters.report_types.repair_time">Tiempo Promedio de Reparación</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_inicio" class="form-label" data-traducir="reports.filters.start_date">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_fin" class="form-label" data-traducir="reports.filters.end_date">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>" required>
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> <span data-traducir="reports.filters.generate">Generar</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Resultados del reporte -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        <?php
                        $titulo_reporte = '';
                        switch($tipo_reporte) {
                            case 'tecnicos':
                                $titulo_reporte = 'Reporte de Órdenes por Técnico';
                                break;
                            case 'equipos':
                                $titulo_reporte = 'Reporte de Órdenes por Tipo de Equipo';
                                break;
                            case 'departamentos':
                                $titulo_reporte = 'Reporte de Órdenes por Departamento';
                                break;
                            case 'tiempo':
                                $titulo_reporte = 'Reporte de Tiempo Promedio de Reparación';
                                break;
                        }
                        echo $titulo_reporte;
                        ?>
                        <span class="text-muted ms-2">
                            (<?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> - <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php if($tipo_reporte == 'tecnicos'): ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th data-traducir="reports.tables.technicians.technician">Técnico</th>
                                        <th data-traducir="reports.tables.technicians.total_orders">Total Órdenes</th>
                                        <th data-traducir="reports.tables.technicians.pending">Pendientes</th>
                                        <th data-traducir="reports.tables.technicians.repaired">Reparados</th>
                                        <th data-traducir="reports.tables.technicians.delivered">Entregados</th>
                                        <th data-traducir="reports.tables.technicians.not_repairable">No Reparables</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($datos_reporte) > 0) {
                                        foreach($datos_reporte as $row) {
                                            echo "<tr>";
                                            echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                            echo "<td>".$row['total_ordenes']."</td>";
                                            echo "<td>".$row['pendientes']."</td>";
                                            echo "<td>".$row['reparados']."</td>";
                                            echo "<td>".$row['entregados']."</td>";
                                            echo "<td>".$row['no_reparables']."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center' data-traducir='reports.tables.no_data'>No hay datos para mostrar</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php elseif($tipo_reporte == 'equipos'): ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th data-traducir="reports.tables.equipment.type">Tipo de Equipo</th>
                                        <th data-traducir="reports.tables.equipment.total_orders">Total Órdenes</th>
                                        <th data-traducir="reports.tables.equipment.pending">Pendientes</th>
                                        <th data-traducir="reports.tables.equipment.repaired">Reparados</th>
                                        <th data-traducir="reports.tables.equipment.delivered">Entregados</th>
                                        <th data-traducir="reports.tables.equipment.not_repairable">No Reparables</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($datos_reporte) > 0) {
                                        foreach($datos_reporte as $row) {
                                            echo "<tr>";
                                            echo "<td>".ucfirst($row['tipo'])."</td>";
                                            echo "<td>".$row['total_ordenes']."</td>";
                                            echo "<td>".$row['pendientes']."</td>";
                                            echo "<td>".$row['reparados']."</td>";
                                            echo "<td>".$row['entregados']."</td>";
                                            echo "<td>".$row['no_reparables']."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center' data-traducir='reports.tables.no_data'>No hay datos para mostrar</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php elseif($tipo_reporte == 'departamentos'): ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th data-traducir="reports.tables.departments.department">Departamento</th>
                                        <th data-traducir="reports.tables.departments.total_orders">Total Órdenes</th>
                                        <th data-traducir="reports.tables.departments.pending">Pendientes</th>
                                        <th data-traducir="reports.tables.departments.repaired">Reparados</th>
                                        <th data-traducir="reports.tables.departments.delivered">Entregados</th>
                                        <th data-traducir="reports.tables.departments.not_repairable">No Reparables</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($datos_reporte) > 0) {
                                        foreach($datos_reporte as $row) {
                                            echo "<tr>";
                                            echo "<td>".$row['departamento']."</td>";
                                            echo "<td>".$row['total_ordenes']."</td>";
                                            echo "<td>".$row['pendientes']."</td>";
                                            echo "<td>".$row['reparados']."</td>";
                                            echo "<td>".$row['entregados']."</td>";
                                            echo "<td>".$row['no_reparables']."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center' data-traducir='reports.tables.no_data'>No hay datos para mostrar</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php elseif($tipo_reporte == 'tiempo'): ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th data-traducir="reports.tables.repair_time.technician">Técnico</th>
                                        <th data-traducir="reports.tables.repair_time.total_orders">Total Órdenes</th>
                                        <th data-traducir="reports.tables.repair_time.average_time">Tiempo Promedio (horas)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($datos_reporte) > 0) {
                                        foreach($datos_reporte as $row) {
                                            echo "<tr>";
                                            echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                            echo "<td>".$row['total_ordenes']."</td>";
                                            echo "<td>".round($row['promedio_horas'], 2)."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center' data-traducir='reports.tables.no_data'>No hay datos para mostrar</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
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