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
    
    // Procesar formulario de agregar orden
    if(isset($_POST['agregar_orden'])) {
        $id_tecnico = $_POST['id_tecnico'];
        $id_equipo = $_POST['id_equipo'];
        $descripcion_falla = $_POST['descripcion_falla'];
        $estado = 'pendiente'; // Por defecto, toda nueva orden está pendiente
        
        $query = "INSERT INTO ordenes_servicio (id_tecnico, id_equipo, descripcion_falla, estado) 
                  VALUES ($id_tecnico, $id_equipo, '$descripcion_falla', '$estado')";
        
        if(mysqli_query($con, $query)) {
            $mensaje = "Orden de servicio creada correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al crear orden: " . mysqli_error($con);
            $tipo_mensaje = "danger";
        }
    }
    
    // Procesar formulario de actualizar estado
    if(isset($_POST['actualizar_estado'])) {
        $id_orden = $_POST['id_orden'];
        $estado = $_POST['estado'];
        
        // Actualizar campos de fecha según el estado
        $fecha_campo = '';
        if($estado == 'reparado') {
            $fecha_campo = ", fecha_reparacion = NOW()";
        } elseif($estado == 'entregado') {
            $fecha_campo = ", fecha_entrega = NOW()";
        }
        
        $query = "UPDATE ordenes_servicio SET estado = '$estado' $fecha_campo WHERE id_orden = $id_orden";
        
        if(mysqli_query($con, $query)) {
            $mensaje = "Estado de la orden actualizado correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar estado: " . mysqli_error($con);
            $tipo_mensaje = "danger";
        }
    }
    
    // Obtener orden para ver detalles
    $orden_detalle = null;
    if(isset($_GET['id'])) {
        $id_orden = $_GET['id'];
        $query = "SELECT o.*, e.tipo as tipo_equipo, e.marca, e.descripcion as desc_equipo, e.departamento,
                 t.nombre, t.apellido, t.telefono
                 FROM ordenes_servicio o
                 JOIN equipos e ON o.id_equipo = e.id_equipo
                 JOIN tecnicos t ON o.id_tecnico = t.id_tecnico
                 WHERE o.id_orden = $id_orden";
        $result = mysqli_query($con, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $orden_detalle = mysqli_fetch_assoc($result);
        }
    }
    
    // Filtrar órdenes por estado si se especifica
    $filtro_estado = '';
    if(isset($_GET['estado']) && $_GET['estado'] != 'todos') {
        $estado = $_GET['estado'];
        $filtro_estado = "WHERE o.estado = '$estado'";
    }
    
    // Consulta para listar todas las órdenes
    $query_ordenes = "SELECT o.id_orden, e.tipo, e.marca, t.nombre, t.apellido, o.estado, 
                     o.fecha_entrada, o.fecha_reparacion, o.fecha_entrega
                     FROM ordenes_servicio o
                     JOIN equipos e ON o.id_equipo = e.id_equipo
                     JOIN tecnicos t ON o.id_tecnico = t.id_tecnico
                     $filtro_estado
                     ORDER BY o.fecha_entrada DESC";
    $result_ordenes = mysqli_query($con, $query_ordenes);
    
    // Obtener todos los técnicos activos para el formulario
    $query_tecnicos = "SELECT * FROM tecnicos WHERE estado = 'activo' ORDER BY apellido, nombre";
    $result_tecnicos = mysqli_query($con, $query_tecnicos);
    
    // Obtener todos los equipos para el formulario
    $query_equipos = "SELECT * FROM equipos ORDER BY tipo, marca";
    $result_equipos = mysqli_query($con, $query_equipos);
    
    // Definir variables para los includes
    $titulo_pagina = "Gestión de Órdenes";
    $pagina_actual = "ordenes";
    
    // Incluir el header
    include "./inc/header.php";
    
    // Incluir el sidebar
    include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Órdenes de Servicio</h1>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOrden">
                            <i class="fas fa-plus"></i> Nueva Orden
                        </button>
                    </div>
                </div>
                
                <?php if(isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-filter"></i> Filtrar Órdenes
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="GET" action="">
                                    <div class="input-group">
                                        <select class="form-select" name="estado">
                                            <option value="todos" <?php echo (!isset($_GET['estado']) || $_GET['estado'] == 'todos') ? 'selected' : ''; ?>>Todos los estados</option>
                                            <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendientes</option>
                                            <option value="reparado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'reparado') ? 'selected' : ''; ?>>Reparados</option>
                                            <option value="entregado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'entregado') ? 'selected' : ''; ?>>Entregados</option>
                                            <option value="no_reparable" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'no_reparable') ? 'selected' : ''; ?>>No Reparables</option>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabla de órdenes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Listado de Órdenes de Servicio
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Equipo</th>
                                        <th>Técnico</th>
                                        <th>Estado</th>
                                        <th>Fecha Entrada</th>
                                        <th>Fecha Reparación</th>
                                        <th>Fecha Entrega</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($result_ordenes) > 0) {
                                        while($row = mysqli_fetch_assoc($result_ordenes)) {
                                            // Definir clase de estado para el badge
                                            $estado_class = '';
                                            switch($row['estado']) {
                                                case 'pendiente':
                                                    $estado_class = 'bg-warning';
                                                    break;
                                                case 'reparado':
                                                    $estado_class = 'bg-primary';
                                                    break;
                                                case 'entregado':
                                                    $estado_class = 'bg-success';
                                                    break;
                                                case 'no_reparable':
                                                    $estado_class = 'bg-danger';
                                                    break;
                                            }
                                            
                                            echo "<tr>";
                                            echo "<td>".$row['id_orden']."</td>";
                                            echo "<td>".ucfirst($row['tipo'])." ".$row['marca']."</td>";
                                            echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                            echo "<td><span class='badge ".$estado_class."'>".ucfirst($row['estado'])."</span></td>";
                                            echo "<td>".date('d/m/Y H:i', strtotime($row['fecha_entrada']))."</td>";
                                            echo "<td>".($row['fecha_reparacion'] ? date('d/m/Y H:i', strtotime($row['fecha_reparacion'])) : '-')."</td>";
                                            echo "<td>".($row['fecha_entrega'] ? date('d/m/Y H:i', strtotime($row['fecha_entrega'])) : '-')."</td>";
                                            echo "<td>
                                                    <a href='ordenes.php?id=".$row['id_orden']."' class='btn btn-sm btn-info'>
                                                        <i class='fas fa-eye'></i>
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No hay órdenes registradas</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Agregar Orden -->
    <div class="modal fade" id="modalAgregarOrden" tabindex="-1" aria-labelledby="modalAgregarOrdenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarOrdenLabel">Crear Nueva Orden de Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_tecnico" class="form-label">Técnico Asignado</label>
                            <select class="form-select" id="id_tecnico" name="id_tecnico" required>
                                <option value="">Seleccione un técnico</option>
                                <?php
                                while($tecnico = mysqli_fetch_assoc($result_tecnicos)) {
                                    echo "<option value='".$tecnico['id_tecnico']."'>".$tecnico['nombre']." ".$tecnico['apellido']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_equipo" class="form-label">Equipo</label>
                            <select class="form-select" id="id_equipo" name="id_equipo" required>
                                <option value="">Seleccione un equipo</option>
                                <?php
                                while($equipo = mysqli_fetch_assoc($result_equipos)) {
                                    echo "<option value='".$equipo['id_equipo']."'>".ucfirst($equipo['tipo'])." ".$equipo['marca']." - ".$equipo['departamento']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_falla" class="form-label">Descripción de la Falla</label>
                            <textarea class="form-control" id="descripcion_falla" name="descripcion_falla" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="agregar_orden" class="btn btn-primary">Crear Orden</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Detalle de Orden -->
    <?php if($orden_detalle): ?>
    <div class="modal fade" id="modalDetalleOrden" tabindex="-1" aria-labelledby="modalDetalleOrdenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalleOrdenLabel">Detalle de Orden #<?php echo $orden_detalle['id_orden']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Información del Equipo</h6>
                            <p><strong>Tipo:</strong> <?php echo ucfirst($orden_detalle['tipo_equipo']); ?></p>
                            <p><strong>Marca:</strong> <?php echo $orden_detalle['marca']; ?></p>
                            <p><strong>Departamento:</strong> <?php echo $orden_detalle['departamento']; ?></p>
                            <p><strong>Descripción:</strong> <?php echo $orden_detalle['desc_equipo']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Información del Técnico</h6>
                            <p><strong>Nombre:</strong> <?php echo $orden_detalle['nombre']." ".$orden_detalle['apellido']; ?></p>
                            <p><strong>Teléfono:</strong> <?php echo $orden_detalle['telefono']; ?></p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Información de la Orden</h6>
                            <p><strong>Descripción de la Falla:</strong> <?php echo $orden_detalle['descripcion_falla']; ?></p>
                            <p><strong>Estado Actual:</strong> 
                                <?php 
                                $estado_class = '';
                                switch($orden_detalle['estado']) {
                                    case 'pendiente':
                                        $estado_class = 'bg-warning';
                                        break;
                                    case 'reparado':
                                        $estado_class = 'bg-primary';
                                        break;
                                    case 'entregado':
                                        $estado_class = 'bg-success';
                                        break;
                                    case 'no_reparable':
                                        $estado_class = 'bg-danger';
                                        break;
                                }
                                echo "<span class='badge ".$estado_class."'>".ucfirst($orden_detalle['estado'])."</span>";
                                ?>
                            </p>
                            <p><strong>Fecha de Entrada:</strong> <?php echo date('d/m/Y H:i', strtotime($orden_detalle['fecha_entrada'])); ?></p>
                            
                            <?php if($orden_detalle['fecha_reparacion']): ?>
                            <p><strong>Fecha de Reparación:</strong> <?php echo date('d/m/Y H:i', strtotime($orden_detalle['fecha_reparacion'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if($orden_detalle['fecha_entrega']): ?>
                            <p><strong>Fecha de Entrega:</strong> <?php echo date('d/m/Y H:i', strtotime($orden_detalle['fecha_entrega'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if($orden_detalle['estado'] != 'entregado'): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Actualizar Estado</h6>
                            <form method="POST" action="">
                                <input type="hidden" name="id_orden" value="<?php echo $orden_detalle['id_orden']; ?>">
                                <div class="input-group">
                                    <select class="form-select" name="estado" required>
                                        <option value="">Seleccione nuevo estado</option>
                                        <?php if($orden_detalle['estado'] == 'pendiente'): ?>
                                        <option value="reparado">Reparado</option>
                                        <option value="no_reparable">No Reparable</option>
                                        <?php elseif($orden_detalle['estado'] == 'reparado'): ?>
                                        <option value="entregado">Entregado</option>
                                        <?php endif; ?>
                                    </select>
                                    <button class="btn btn-primary" type="submit" name="actualizar_estado">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Mostrar automáticamente el modal de detalle
        document.addEventListener('DOMContentLoaded', function() {
            var modalDetalleOrden = new bootstrap.Modal(document.getElementById('modalDetalleOrden'));
            modalDetalleOrden.show();
        });
    </script>
    <?php endif; ?>
    
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