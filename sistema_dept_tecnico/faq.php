<?php
    // Inicio de Conexión
    session_start();
    include "./inc/conexionbd.php";
    $con = connection();
  
    // Verificación de que esté logueado el usuario
    if (isset($_SESSION['usuario'])){
        // Cargar configuración del usuario
        include "./php/funciones/cargar_configuracion.php";
        
        // Definir variables para los includes
        $titulo_pagina = "Ayuda";
        $pagina_actual = "faq";
        
        // Incluir el header
        include "./inc/header.php";
        
        // Incluir el sidebar
        include "./inc/sidebar.php";
?>
            
            <!-- Contenido principal -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fade-in" data-traducir="help.title">Centro de Ayuda</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchFaq" placeholder="Buscar en la ayuda..." data-traducir="help.search_placeholder">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Banner de ayuda -->
                <div class="card mb-4 bg-light">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-3" data-traducir="help.banner.title">¿Necesitas ayuda con el sistema?</h4>
                                <p class="mb-0" data-traducir="help.banner.subtitle">Encuentra respuestas a las preguntas más frecuentes o contacta con nuestro equipo de soporte técnico.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="#contacto" class="btn btn-primary">
                                    <i class="fas fa-headset me-2"></i> <span data-traducir="help.banner.support_button">Contactar Soporte</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <!-- Categorías de ayuda -->
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="list-group" id="faq-categories">
                            <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                                <i class="fas fa-info-circle me-2"></i> <span data-traducir="help.categories.general">General</span>
                            </a>
                            <a href="#equipos" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="fas fa-laptop me-2"></i> <span data-traducir="help.categories.equipment">Equipos</span>
                            </a>
                            <a href="#tecnicos" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="fas fa-users-cog me-2"></i> <span data-traducir="help.categories.technicians">Técnicos</span>
                            </a>
                            <a href="#ordenes" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="fas fa-clipboard-list me-2"></i> <span data-traducir="help.categories.orders">Órdenes</span>
                            </a>
                            <a href="#reportes" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="fas fa-chart-bar me-2"></i> <span data-traducir="help.categories.reports">Reportes</span>
                            </a>
                            <a href="#cuenta" class="list-group-item list-group-item-action" data-bs-toggle="list">
                                <i class="fas fa-user-cog me-2"></i> <span data-traducir="help.categories.account">Mi Cuenta</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Contenido de las categorías -->
                    <div class="col-md-9">
                        <div class="tab-content" id="faq-content">
                            <!-- General -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.general.title">Preguntas Generales</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionGeneral">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral1" aria-expanded="true" aria-controls="collapseGeneral1" data-traducir="help.sections.general.questions.q1">
                                                        ¿Qué es el Sistema de Gestión del Departamento Técnico?
                                                    </button>
                                                </h2>
                                                <div id="collapseGeneral1" class="accordion-collapse collapse show" data-bs-parent="#accordionGeneral">
                                                    <div class="accordion-body" data-traducir="help.sections.general.questions.a1">
                                                        Es una plataforma diseñada para administrar equipos, técnicos y órdenes de servicio de manera eficiente. Permite realizar seguimiento de reparaciones, generar reportes y gestionar el inventario de equipos.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral2" aria-expanded="false" aria-controls="collapseGeneral2" data-traducir="help.sections.general.questions.q2">
                                                        ¿Cómo puedo navegar por el sistema?
                                                    </button>
                                                </h2>
                                                <div id="collapseGeneral2" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                                                    <div class="accordion-body" data-traducir="help.sections.general.questions.a2">
                                                        Utiliza el menú lateral (sidebar) para acceder a las diferentes secciones del sistema. El dashboard principal te muestra un resumen de la actividad reciente y estadísticas importantes.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral3" aria-expanded="false" aria-controls="collapseGeneral3" data-traducir="help.sections.general.questions.q3">
                                                        ¿Puedo personalizar la interfaz del sistema?
                                                    </button>
                                                </h2>
                                                <div id="collapseGeneral3" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                                                    <div class="accordion-body" data-traducir="help.sections.general.questions.a3">
                                                        Sí, puedes personalizar varios aspectos de la interfaz desde tu perfil, incluyendo el modo oscuro, tamaño de fuente e idioma.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Equipos -->
                            <div class="tab-pane fade" id="equipos">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.equipment.title">Gestión de Equipos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionEquipos">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEquipos1" aria-expanded="true" aria-controls="collapseEquipos1" data-traducir="help.sections.equipment.questions.q1">
                                                        ¿Cómo agrego un nuevo equipo al sistema?
                                                    </button>
                                                </h2>
                                                <div id="collapseEquipos1" class="accordion-collapse collapse show" data-bs-parent="#accordionEquipos">
                                                    <div class="accordion-body" data-traducir="help.sections.equipment.questions.a1">
                                                        Ve a la sección 'Equipos' y haz clic en el botón 'Nuevo Equipo'. Completa el formulario con los datos requeridos y guarda los cambios.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEquipos2" aria-expanded="false" aria-controls="collapseEquipos2" data-traducir="help.sections.equipment.questions.q2">
                                                        ¿Puedo eliminar un equipo que ya tiene órdenes asociadas?
                                                    </button>
                                                </h2>
                                                <div id="collapseEquipos2" class="accordion-collapse collapse" data-bs-parent="#accordionEquipos">
                                                    <div class="accordion-body" data-traducir="help.sections.equipment.questions.a2">
                                                        No, por integridad de datos no se permite eliminar equipos que tienen órdenes de servicio asociadas. Esto garantiza que se mantenga el historial de reparaciones.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEquipos3" aria-expanded="false" aria-controls="collapseEquipos3" data-traducir="help.sections.equipment.questions.q3">
                                                        ¿Qué información debo incluir en la descripción del equipo?
                                                    </button>
                                                </h2>
                                                <div id="collapseEquipos3" class="accordion-collapse collapse" data-bs-parent="#accordionEquipos">
                                                    <div class="accordion-body" data-traducir="help.sections.equipment.questions.a3">
                                                        Es recomendable incluir especificaciones técnicas, número de serie, estado físico y cualquier otra información relevante que ayude a identificar el equipo.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Técnicos -->
                            <div class="tab-pane fade" id="tecnicos">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.technicians.title">Gestión de Técnicos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionTecnicos">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecnicos1" aria-expanded="true" aria-controls="collapseTecnicos1" data-traducir="help.sections.technicians.questions.q1">
                                                        ¿Cómo asigno un técnico a una orden de servicio?
                                                    </button>
                                                </h2>
                                                <div id="collapseTecnicos1" class="accordion-collapse collapse show" data-bs-parent="#accordionTecnicos">
                                                    <div class="accordion-body" data-traducir="help.sections.technicians.questions.a1">
                                                        Al crear o editar una orden de servicio, selecciona el técnico desde el menú desplegable. Solo se mostrarán los técnicos activos en el sistema.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecnicos2" aria-expanded="false" aria-controls="collapseTecnicos2" data-traducir="help.sections.technicians.questions.q2">
                                                        ¿Puedo ver el historial de órdenes atendidas por un técnico?
                                                    </button>
                                                </h2>
                                                <div id="collapseTecnicos2" class="accordion-collapse collapse" data-bs-parent="#accordionTecnicos">
                                                    <div class="accordion-body" data-traducir="help.sections.technicians.questions.a2">
                                                        Sí, en la sección de 'Reportes' puedes generar un informe filtrado por técnico que muestre todas las órdenes que ha atendido.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecnicos3" aria-expanded="false" aria-controls="collapseTecnicos3" data-traducir="help.sections.technicians.questions.q3">
                                                        ¿Cómo marco a un técnico como inactivo?
                                                    </button>
                                                </h2>
                                                <div id="collapseTecnicos3" class="accordion-collapse collapse" data-bs-parent="#accordionTecnicos">
                                                    <div class="accordion-body" data-traducir="help.sections.technicians.questions.a3">
                                                        En la ficha del técnico, cambia su estado a 'Inactivo'. Esto evitará que se le asignen nuevas órdenes pero mantendrá su historial en el sistema.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Órdenes -->
                            <div class="tab-pane fade" id="ordenes">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.orders.title">Gestión de Órdenes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionOrdenes">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrdenes1" aria-expanded="true" aria-controls="collapseOrdenes1" data-traducir="help.sections.orders.questions.q1">
                                                        ¿Cuáles son los estados posibles de una orden?
                                                    </button>
                                                </h2>
                                                <div id="collapseOrdenes1" class="accordion-collapse collapse show" data-bs-parent="#accordionOrdenes">
                                                    <div class="accordion-body" data-traducir="help.sections.orders.questions.a1">
                                                        Una orden puede estar en estado: Pendiente (recién creada), En proceso (asignada a un técnico), Reparado (trabajo técnico completado), Entregado (devuelto al cliente) o No reparable.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrdenes2" aria-expanded="false" aria-controls="collapseOrdenes2" data-traducir="help.sections.orders.questions.q2">
                                                        ¿Cómo registro la entrega de un equipo reparado?
                                                    </button>
                                                </h2>
                                                <div id="collapseOrdenes2" class="accordion-collapse collapse" data-bs-parent="#accordionOrdenes">
                                                    <div class="accordion-body" data-traducir="help.sections.orders.questions.a2">
                                                        Abre la orden correspondiente y cambia su estado a 'Entregado'. El sistema registrará automáticamente la fecha y hora de entrega.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrdenes3" aria-expanded="false" aria-controls="collapseOrdenes3" data-traducir="help.sections.orders.questions.q3">
                                                        ¿Puedo adjuntar archivos a una orden de servicio?
                                                    </button>
                                                </h2>
                                                <div id="collapseOrdenes3" class="accordion-collapse collapse" data-bs-parent="#accordionOrdenes">
                                                    <div class="accordion-body" data-traducir="help.sections.orders.questions.a3">
                                                        Sí, puedes adjuntar fotos, documentos PDF u otros archivos relevantes como evidencia del estado del equipo o documentación técnica.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reportes -->
                            <div class="tab-pane fade" id="reportes">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.reports.title">Reportes y Estadísticas</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionReportes">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReportes1" aria-expanded="true" aria-controls="collapseReportes1" data-traducir="help.sections.reports.questions.q1">
                                                        ¿Qué tipos de reportes puedo generar?
                                                    </button>
                                                </h2>
                                                <div id="collapseReportes1" class="accordion-collapse collapse show" data-bs-parent="#accordionReportes">
                                                    <div class="accordion-body" data-traducir="help.sections.reports.questions.a1">
                                                        Puedes generar reportes de órdenes por técnico, por tipo de equipo, por departamento y de tiempo promedio de reparación. Todos los reportes pueden filtrarse por rango de fechas.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReportes2" aria-expanded="false" aria-controls="collapseReportes2" data-traducir="help.sections.reports.questions.q2">
                                                        ¿Puedo exportar los reportes generados?
                                                    </button>
                                                </h2>
                                                <div id="collapseReportes2" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                                    <div class="accordion-body" data-traducir="help.sections.reports.questions.a2">
                                                        Sí, puedes imprimir los reportes o guardarlos como PDF utilizando la funcionalidad de impresión de tu navegador.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReportes3" aria-expanded="false" aria-controls="collapseReportes3" data-traducir="help.sections.reports.questions.q3">
                                                        ¿Cómo puedo ver las estadísticas generales del departamento?
                                                    </button>
                                                </h2>
                                                <div id="collapseReportes3" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                                    <div class="accordion-body" data-traducir="help.sections.reports.questions.a3">
                                                        El dashboard principal muestra un resumen de las estadísticas más importantes. Para análisis más detallados, utiliza la sección de Reportes.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mi Cuenta -->
                            <div class="tab-pane fade" id="cuenta">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0" data-traducir="help.sections.account.title">Mi Cuenta y Configuración</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionCuenta">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCuenta1" aria-expanded="true" aria-controls="collapseCuenta1" data-traducir="help.sections.account.questions.q1">
                                                        ¿Cómo cambio mi contraseña?
                                                    </button>
                                                </h2>
                                                <div id="collapseCuenta1" class="accordion-collapse collapse show" data-bs-parent="#accordionCuenta">
                                                    <div class="accordion-body" data-traducir="help.sections.account.questions.a1">
                                                        Ve a tu perfil, selecciona la pestaña 'Seguridad' y utiliza el formulario de cambio de contraseña. Deberás ingresar tu contraseña actual y la nueva contraseña dos veces.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCuenta2" aria-expanded="false" aria-controls="collapseCuenta2" data-traducir="help.sections.account.questions.q2">
                                                        ¿Cómo cambio el idioma del sistema?
                                                    </button>
                                                </h2>
                                                <div id="collapseCuenta2" class="accordion-collapse collapse" data-bs-parent="#accordionCuenta">
                                                    <div class="accordion-body" data-traducir="help.sections.account.questions.a2">
                                                        En tu perfil, ve a la pestaña 'Configuración' y selecciona el idioma deseado en la sección 'Idioma'. Los cambios se aplicarán inmediatamente.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCuenta3" aria-expanded="false" aria-controls="collapseCuenta3" data-traducir="help.sections.account.questions.q3">
                                                        ¿Qué hago si olvido mi contraseña?
                                                    </button>
                                                </h2>
                                                <div id="collapseCuenta3" class="accordion-collapse collapse" data-bs-parent="#accordionCuenta">
                                                    <div class="accordion-body" data-traducir="help.sections.account.questions.a3">
                                                        En la pantalla de inicio de sesión, haz clic en 'Olvidé mi contraseña' y sigue las instrucciones para restablecerla a través de tu correo electrónico registrado.
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
                
                <!-- Sección de contacto -->
                <div class="card mb-4" id="contacto">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> <span data-traducir="help.contact.title">Contactar con Soporte</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form>
                                    <div class="mb-3">
                                        <label for="asunto" class="form-label" data-traducir="help.contact.subject">Asunto</label>
                                        <input type="text" class="form-control" id="asunto" placeholder="Escribe el asunto de tu consulta" data-traducir="help.contact.subject_placeholder">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mensaje" class="form-label" data-traducir="help.contact.message">Mensaje</label>
                                        <textarea class="form-control" id="mensaje" rows="5" placeholder="Describe detalladamente tu problema o consulta" data-traducir="help.contact.message_placeholder"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prioridad" class="form-label" data-traducir="help.contact.priority">Prioridad</label>
                                        <select class="form-select" id="prioridad">
                                            <option value="baja" data-traducir="help.contact.priority_options.low">Baja</option>
                                            <option value="media" selected data-traducir="help.contact.priority_options.medium">Media</option>
                                            <option value="alta" data-traducir="help.contact.priority_options.high">Alta</option>
                                            <option value="urgente" data-traducir="help.contact.priority_options.urgent">Urgente</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> <span data-traducir="help.contact.send_button">Enviar Mensaje</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <h5 class="mb-3" data-traducir="help.contact.info_title">Información de Contacto</h5>
                                    <p data-traducir="help.contact.info_subtitle">Si prefieres contactarnos directamente, puedes utilizar los siguientes medios:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-phone me-2 text-primary"></i> +1 (555) 123-4567
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-envelope me-2 text-primary"></i> soporte@depttecnico.com
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-clock me-2 text-primary"></i> <span data-traducir="help.contact.hours">Lunes a Viernes, 9:00 AM - 6:00 PM</span>
                                        </li>
                                    </ul>
                                    <div class="alert alert-info mt-4">
                                        <i class="fas fa-info-circle me-2"></i> <span data-traducir="help.contact.response_time">Nuestro tiempo de respuesta promedio es de 24 horas hábiles.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script para la búsqueda en FAQ -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchFaq');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const headerText = item.querySelector('.accordion-button').textContent.toLowerCase();
                const bodyText = item.querySelector('.accordion-body').textContent.toLowerCase();
                
                if (headerText.includes(searchTerm) || bodyText.includes(searchTerm)) {
                    item.style.display = '';
                    
                    // Expandir el item si coincide con la búsqueda
                    if (searchTerm.length > 2) {
                        const collapseElement = item.querySelector('.accordion-collapse');
                        const bsCollapse = new bootstrap.Collapse(collapseElement, {
                            toggle: false
                        });
                        bsCollapse.show();
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });
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