<?php
    // Inicio de Conexión
    session_start();
    include "./inc/conexionbd.php";
    $con = connection();
  
    // Verificación de que esté logueado el usuario
    if (isset($_SESSION['usuario'])){
        // Obtener el término de búsqueda
        $busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Definir variables para los includes
        $titulo_pagina = "Resultados de búsqueda";
        $pagina_actual = "buscar";
        
        // Incluir el header
        include "./inc/header.php";
        
        // Incluir el sidebar
        include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fade-in">Resultados de búsqueda</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <form action="buscar.php" method="GET" class="d-flex">
                            <input type="text" name="q" class="form-control me-2" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar...">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <?php if(empty($busqueda)): ?>
                    <div class="alert alert-info fade-in" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Ingresa un término para buscar equipos, técnicos u órdenes.
                    </div>
                <?php else: ?>
                    <div class="alert alert-light fade-in" role="alert">
                        <i class="fas fa-search me-2"></i> Resultados para: <strong><?php echo htmlspecialchars($busqueda); ?></strong>
                    </div>
                    
                    <!-- Resultados de Equipos -->
                    <div class="card mb-4 fade-in">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-laptop me-2"></i> Equipos</span>
                            <a href="equipos.php" class="btn btn-sm btn-outline-primary">Ver todos</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tipo</th>
                                            <th>Marca</th>
                                            <th>Descripción</th>
                                            <th>Departamento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para buscar equipos
                                        $query_equipos = "SELECT * FROM equipos 
                                                         WHERE tipo LIKE '%$busqueda%' 
                                                         OR marca LIKE '%$busqueda%' 
                                                         OR descripcion LIKE '%$busqueda%' 
                                                         OR departamento LIKE '%$busqueda%'
                                                         LIMIT 5";
                                        $result_equipos = mysqli_query($con, $query_equipos);
                                        
                                        if(mysqli_num_rows($result_equipos) > 0) {
                                            while($row = mysqli_fetch_assoc($result_equipos)) {
                                                echo "<tr>";
                                                echo "<td>".$row['id_equipo']."</td>";
                                                echo "<td>".ucfirst($row['tipo'])."</td>";
                                                echo "<td>".$row['marca']."</td>";
                                                echo "<td>".substr($row['descripcion'], 0, 50)."...</td>";
                                                echo "<td>".$row['departamento']."</td>";
                                                echo "<td class='action-buttons'>
                                                        <a href='equipos.php?id=".$row['id_equipo']."' class='btn btn-sm btn-info' title='Ver detalles'>
                                                            <i class='fas fa-eye'></i>
                                                        </a>
                                                        <a href='equipos.php?id=".$row['id_equipo']."&action=edit' class='btn btn-sm btn-primary' title='Editar'>
                                                            <i class='fas fa-edit'></i>
                                                        </a>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-3'><i class='fas fa-info-circle me-2 text-info'></i> No se encontraron equipos que coincidan con la búsqueda.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resultados de Técnicos -->
                    <div class="card mb-4 fade-in">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users-cog me-2"></i> Técnicos</span>
                            <a href="tecnicos.php" class="btn btn-sm btn-outline-primary">Ver todos</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Cédula</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para buscar técnicos
                                        $query_tecnicos = "SELECT * FROM tecnicos 
                                                         WHERE nombre LIKE '%$busqueda%' 
                                                         OR apellido LIKE '%$busqueda%' 
                                                         OR cedula LIKE '%$busqueda%' 
                                                         OR telefono LIKE '%$busqueda%' 
                                                         OR correo LIKE '%$busqueda%'
                                                         LIMIT 5";
                                        $result_tecnicos = mysqli_query($con, $query_tecnicos);
                                        
                                        if(mysqli_num_rows($result_tecnicos) > 0) {
                                            while($row = mysqli_fetch_assoc($result_tecnicos)) {
                                                echo "<tr>";
                                                echo "<td>".$row['id_tecnico']."</td>";
                                                echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                                echo "<td>".$row['cedula']."</td>";
                                                echo "<td>".$row['telefono']."</td>";
                                                echo "<td>".$row['correo']."</td>";
                                                echo "<td class='action-buttons'>
                                                        <a href='tecnicos.php?id=".$row['id_tecnico']."' class='btn btn-sm btn-info' title='Ver detalles'>
                                                            <i class='fas fa-eye'></i>
                                                        </a>
                                                        <a href='tecnicos.php?id=".$row['id_tecnico']."&action=edit' class='btn btn-sm btn-primary' title='Editar'>
                                                            <i class='fas fa-edit'></i>
                                                        </a>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-3'><i class='fas fa-info-circle me-2 text-info'></i> No se encontraron técnicos que coincidan con la búsqueda.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resultados de Órdenes -->
                    <div class="card mb-4 fade-in">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clipboard-list me-2"></i> Órdenes de Servicio</span>
                            <a href="ordenes.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Equipo</th>
                                            <th>Técnico</th>
                                            <th>Estado</th>
                                            <th>Fecha Entrada</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para buscar órdenes
                                        $query_ordenes = "SELECT o.id_orden, e.tipo, e.marca, t.nombre, t.apellido, o.estado, o.fecha_entrada, o.descripcion_falla 
                                                         FROM ordenes_servicio o
                                                         JOIN equipos e ON o.id_equipo = e.id_equipo
                                                         JOIN tecnicos t ON o.id_tecnico = t.id_tecnico
                                                         WHERE o.id_orden LIKE '%$busqueda%' 
                                                         OR e.tipo LIKE '%$busqueda%' 
                                                         OR e.marca LIKE '%$busqueda%' 
                                                         OR t.nombre LIKE '%$busqueda%' 
                                                         OR t.apellido LIKE '%$busqueda%'
                                                         OR o.descripcion_falla LIKE '%$busqueda%'
                                                         LIMIT 5";
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
                                            echo "<tr><td colspan='6' class='text-center py-3'><i class='fas fa-info-circle me-2 text-info'></i> No se encontraron órdenes que coincidan con la búsqueda.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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