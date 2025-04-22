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
    
    // Procesar formulario de agregar técnico
    if(isset($_POST['agregar_tecnico'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];
        
        // Verificar si la cédula ya existe
        $query_check = "SELECT * FROM tecnicos WHERE cedula = '$cedula'";
        $result_check = mysqli_query($con, $query_check);
        
        if(mysqli_num_rows($result_check) > 0) {
            $mensaje = "Error: Ya existe un técnico con esa cédula";
            $tipo_mensaje = "danger";
        } else {
            $query = "INSERT INTO tecnicos (nombre, apellido, cedula, telefono, correo, estado) 
                      VALUES ('$nombre', '$apellido', '$cedula', '$telefono', '$correo', '$estado')";
            
            if(mysqli_query($con, $query)) {
                $mensaje = "Técnico agregado correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al agregar técnico: " . mysqli_error($con);
                $tipo_mensaje = "danger";
            }
        }
    }
    
    // Procesar formulario de editar técnico
    if(isset($_POST['editar_tecnico'])) {
        $id_tecnico = $_POST['id_tecnico'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];
        
        // Verificar si la cédula ya existe en otro técnico
        $query_check = "SELECT * FROM tecnicos WHERE cedula = '$cedula' AND id_tecnico != $id_tecnico";
        $result_check = mysqli_query($con, $query_check);
        
        if(mysqli_num_rows($result_check) > 0) {
            $mensaje = "Error: Ya existe otro técnico con esa cédula";
            $tipo_mensaje = "danger";
        } else {
            $query = "UPDATE tecnicos SET 
                      nombre = '$nombre', 
                      apellido = '$apellido', 
                      cedula = '$cedula', 
                      telefono = '$telefono', 
                      correo = '$correo', 
                      estado = '$estado' 
                      WHERE id_tecnico = $id_tecnico";
            
            if(mysqli_query($con, $query)) {
                $mensaje = "Técnico actualizado correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar técnico: " . mysqli_error($con);
                $tipo_mensaje = "danger";
            }
        }
    }
    
    // Procesar cambio de estado (activar/desactivar)
    if(isset($_GET['cambiar_estado'])) {
        $id_tecnico = $_GET['cambiar_estado'];
        $nuevo_estado = $_GET['estado'] == 'activo' ? 'inactivo' : 'activo';
        
        $query = "UPDATE tecnicos SET estado = '$nuevo_estado' WHERE id_tecnico = $id_tecnico";
        
        if(mysqli_query($con, $query)) {
            $mensaje = "Estado del técnico actualizado correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar estado: " . mysqli_error($con);
            $tipo_mensaje = "danger";
        }
    }
    
    // Obtener técnico para editar
    $tecnico_editar = null;
    if(isset($_GET['editar'])) {
        $id_tecnico = $_GET['editar'];
        $query = "SELECT * FROM tecnicos WHERE id_tecnico = $id_tecnico";
        $result = mysqli_query($con, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $tecnico_editar = mysqli_fetch_assoc($result);
        }
    }
    
    // Consulta para listar todos los técnicos
    $query_tecnicos = "SELECT * FROM tecnicos ORDER BY apellido, nombre";
    $result_tecnicos = mysqli_query($con, $query_tecnicos);
    
    // Definir variables para los includes
    $titulo_pagina = "Gestión de Técnicos";
    $pagina_actual = "tecnicos";
    
    // Incluir el header
    include "./inc/header.php";
    
    // Incluir el sidebar
    include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Técnicos</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarTecnico">
                        <i class="fas fa-plus"></i> Nuevo Técnico
                    </button>
                </div>
                
                <?php if(isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Tabla de técnicos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Listado de Técnicos
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($result_tecnicos) > 0) {
                                        while($row = mysqli_fetch_assoc($result_tecnicos)) {
                                            $estado_badge = ($row['estado'] == 'activo') ? 'bg-success' : 'bg-danger';
                                            
                                            echo "<tr>";
                                            echo "<td>".$row['id_tecnico']."</td>";
                                            echo "<td>".$row['nombre']." ".$row['apellido']."</td>";
                                            echo "<td>".$row['cedula']."</td>";
                                            echo "<td>".$row['telefono']."</td>";
                                            echo "<td>".$row['correo']."</td>";
                                            echo "<td><span class='badge ".$estado_badge."'>".ucfirst($row['estado'])."</span></td>";
                                            echo "<td>
                                                    <a href='tecnicos.php?editar=".$row['id_tecnico']."' class='btn btn-sm btn-warning'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <a href='tecnicos.php?cambiar_estado=".$row['id_tecnico']."&estado=".$row['estado']."' class='btn btn-sm btn-".($row['estado'] == 'activo' ? 'danger' : 'success')."'>
                                                        <i class='fas fa-".($row['estado'] == 'activo' ? 'user-slash' : 'user-check')."'></i>
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No hay técnicos registrados</td></tr>";
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
    
    <!-- Modal Agregar Técnico -->
    <div class="modal fade" id="modalAgregarTecnico" tabindex="-1" aria-labelledby="modalAgregarTecnicoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarTecnicoLabel">Agregar Nuevo Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" name="agregar_tecnico" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Técnico -->
    <?php if($tecnico_editar): ?>
    <div class="modal fade" id="modalEditarTecnico" tabindex="-1" aria-labelledby="modalEditarTecnicoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarTecnicoLabel">Editar Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id_tecnico" value="<?php echo $tecnico_editar['id_tecnico']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_edit" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre_edit" name="nombre" value="<?php echo $tecnico_editar['nombre']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_edit" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido_edit" name="apellido" value="<?php echo $tecnico_editar['apellido']; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cedula_edit" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula_edit" name="cedula" value="<?php echo $tecnico_editar['cedula']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono_edit" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono_edit" name="telefono" value="<?php echo $tecnico_editar['telefono']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo_edit" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo_edit" name="correo" value="<?php echo $tecnico_editar['correo']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado_edit" class="form-label">Estado</label>
                            <select class="form-select" id="estado_edit" name="estado" required>
                                <option value="activo" <?php echo ($tecnico_editar['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo ($tecnico_editar['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" name="editar_tecnico" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Mostrar automáticamente el modal de edición
        document.addEventListener('DOMContentLoaded', function() {
            var modalEditarTecnico = new bootstrap.Modal(document.getElementById('modalEditarTecnico'));
            modalEditarTecnico.show();
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