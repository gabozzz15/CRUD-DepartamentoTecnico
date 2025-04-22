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
    
    // Procesar formulario de agregar equipo
    if(isset($_POST['agregar_equipo'])) {
        $tipo = $_POST['tipo'];
        $marca = $_POST['marca'];
        $descripcion = $_POST['descripcion'];
        $departamento = $_POST['departamento'];
        
        $query = "INSERT INTO equipos (tipo, marca, descripcion, departamento) 
                  VALUES ('$tipo', '$marca', '$descripcion', '$departamento')";
        
        if(mysqli_query($con, $query)) {
            $mensaje = "Equipo agregado correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al agregar equipo: " . mysqli_error($con);
            $tipo_mensaje = "danger";
        }
    }
    
    // Procesar formulario de editar equipo
    if(isset($_POST['editar_equipo'])) {
        $id_equipo = $_POST['id_equipo'];
        $tipo = $_POST['tipo'];
        $marca = $_POST['marca'];
        $descripcion = $_POST['descripcion'];
        $departamento = $_POST['departamento'];
        
        $query = "UPDATE equipos SET 
                  tipo = '$tipo', 
                  marca = '$marca', 
                  descripcion = '$descripcion', 
                  departamento = '$departamento' 
                  WHERE id_equipo = $id_equipo";
        
        if(mysqli_query($con, $query)) {
            $mensaje = "Equipo actualizado correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar equipo: " . mysqli_error($con);
            $tipo_mensaje = "danger";
        }
    }
    
    // Procesar eliminación de equipo
    if(isset($_GET['eliminar'])) {
        $id_equipo = $_GET['eliminar'];
        
        // Verificar si el equipo está en alguna orden
        $query_check = "SELECT * FROM ordenes_servicio WHERE id_equipo = $id_equipo";
        $result_check = mysqli_query($con, $query_check);
        
        if(mysqli_num_rows($result_check) > 0) {
            $mensaje = "No se puede eliminar el equipo porque está asociado a una o más órdenes";
            $tipo_mensaje = "warning";
        } else {
            $query = "DELETE FROM equipos WHERE id_equipo = $id_equipo";
            
            if(mysqli_query($con, $query)) {
                $mensaje = "Equipo eliminado correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar equipo: " . mysqli_error($con);
                $tipo_mensaje = "danger";
            }
        }
    }
    
    // Obtener equipo para editar
    $equipo_editar = null;
    if(isset($_GET['editar'])) {
        $id_equipo = $_GET['editar'];
        $query = "SELECT * FROM equipos WHERE id_equipo = $id_equipo";
        $result = mysqli_query($con, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $equipo_editar = mysqli_fetch_assoc($result);
        }
    }
    
    // Consulta para listar todos los equipos
    $query_equipos = "SELECT * FROM equipos ORDER BY id_equipo DESC";
    $result_equipos = mysqli_query($con, $query_equipos);
    
    // Definir variables para los includes
    $titulo_pagina = "Gestión de Equipos";
    $pagina_actual = "equipos";
    
    // Incluir el header
    include "./inc/header.php";
    
    // Incluir el sidebar
    include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" data-traducir="equipment.title">Gestión de Equipos</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarEquipo">
                        <i class="fas fa-plus"></i> <span data-traducir="equipment.new_equipment">Nuevo Equipo</span>
                    </button>
                </div>
                
                <?php if(isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Tabla de equipos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Listado de Equipos
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Marca</th>
                                        <th>Departamento</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($result_equipos) > 0) {
                                        while($row = mysqli_fetch_assoc($result_equipos)) {
                                            echo "<tr>";
                                            echo "<td>".$row['id_equipo']."</td>";
                                            echo "<td>".ucfirst($row['tipo'])."</td>";
                                            echo "<td>".$row['marca']."</td>";
                                            echo "<td>".$row['departamento']."</td>";
                                            echo "<td>".$row['descripcion']."</td>";
                                            echo "<td>
                                                    <a href='equipos.php?editar=".$row['id_equipo']."' class='btn btn-sm btn-warning'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <a href='equipos.php?eliminar=".$row['id_equipo']."' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Está seguro de eliminar este equipo?\")'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No hay equipos registrados</td></tr>";
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
    
    <!-- Modal Agregar Equipo -->
    <div class="modal fade" id="modalAgregarEquipo" tabindex="-1" aria-labelledby="modalAgregarEquipoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarEquipoLabel">Agregar Nuevo Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Equipo</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="pc">PC</option>
                                <option value="laptop">Laptop</option>
                                <option value="impresora">Impresora</option>
                                <option value="router">Router</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="departamento" class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="departamento" name="departamento" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            <div class="form-text">Incluya detalles como componentes, especificaciones, etc.</div>
                        </div>
                        <button type="submit" name="agregar_equipo" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Equipo -->
    <?php if($equipo_editar): ?>
    <div class="modal fade" id="modalEditarEquipo" tabindex="-1" aria-labelledby="modalEditarEquipoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarEquipoLabel">Editar Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id_equipo" value="<?php echo $equipo_editar['id_equipo']; ?>">
                        <div class="mb-3">
                            <label for="tipo_edit" class="form-label">Tipo de Equipo</label>
                            <select class="form-select" id="tipo_edit" name="tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="pc" <?php echo ($equipo_editar['tipo'] == 'pc') ? 'selected' : ''; ?>>PC</option>
                                <option value="laptop" <?php echo ($equipo_editar['tipo'] == 'laptop') ? 'selected' : ''; ?>>Laptop</option>
                                <option value="impresora" <?php echo ($equipo_editar['tipo'] == 'impresora') ? 'selected' : ''; ?>>Impresora</option>
                                <option value="router" <?php echo ($equipo_editar['tipo'] == 'router') ? 'selected' : ''; ?>>Router</option>
                                <option value="otro" <?php echo ($equipo_editar['tipo'] == 'otro') ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="marca_edit" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca_edit" name="marca" value="<?php echo $equipo_editar['marca']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="departamento_edit" class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="departamento_edit" name="departamento" value="<?php echo $equipo_editar['departamento']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_edit" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion_edit" name="descripcion" rows="3" required><?php echo $equipo_editar['descripcion']; ?></textarea>
                            <div class="form-text">Incluya detalles como componentes, especificaciones, etc.</div>
                        </div>
                        <button type="submit" name="editar_equipo" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Mostrar automáticamente el modal de edición
        document.addEventListener('DOMContentLoaded', function() {
            var modalEditarEquipo = new bootstrap.Modal(document.getElementById('modalEditarEquipo'));
            modalEditarEquipo.show();
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