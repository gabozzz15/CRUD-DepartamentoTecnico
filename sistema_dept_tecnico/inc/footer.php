    </div>
</div>
<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="footer-content py-4">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="footer-logo me-3">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" data-traducir="dashboard.title">DeptTécnico</h5>
                            <p class="mb-0 small" data-traducir="footer.technical_management">Sistema de Gestión Técnica</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0 text-center">
                    <p class="mb-0">Sistema de Control de Equipos © <?php echo date('Y'); ?></p>
                    <a class="small mb-0" href="https://github.com/gabozzz15">By Gabriel Bastardo</a>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="footer-links">
                        <a href="faq.php" class="me-3"><i class="fas fa-question-circle me-1"></i> <span data-traducir="navigation.help">Ayuda</span></a>
                        <a href="perfil.php"><i class="fas fa-user-cog me-1"></i> <span data-traducir="navigation.profile">Mi Perfil</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script de traducción -->
<script src="js/traductor.js"></script>

<!-- Scripts personalizados -->
<script src="js/dashboard.js"></script>

<!-- Script para inicializar el traductor con la configuración del usuario -->
<script>
    // Asegurarse de que el traductor use el idioma configurado por el usuario
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el idioma configurado por el usuario desde PHP
        const idiomaUsuario = '<?php echo $config_usuario['idioma'] ?? 'es'; ?>';
        
        // Si ya existe un traductor, actualizar su idioma
        if (window.traductor) {
            if (window.traductor.idioma !== idiomaUsuario) {
                console.log(`Actualizando idioma del traductor a: ${idiomaUsuario}`);
                window.traductor.cambiarIdioma(idiomaUsuario);
            }
        }
    });
</script>

<!-- Script para animaciones de entrada y configuraciones -->
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
                background-color: #0f3460 !important;
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
                background-color: #0f3460 !important;
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
    });
</script>
</body>
</html>