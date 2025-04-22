<!-- Sidebar / Menú lateral -->
<div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-4">
        <div class="text-center mb-4 fade-in">
            <div class="sidebar-logo mb-3">
                <i class="fas fa-tools fa-3x"></i>
            </div>
            <h5 class="text-white mb-1" data-traducir="sidebar.title">Departamento Técnico</h5>
            <p class="text-light opacity-75 small mb-3" data-traducir="sidebar.subtitle">Panel de Administración</p>
            <div class="user-info d-flex align-items-center justify-content-center">
                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['nombre']; ?>&background=4e73df&color=fff" alt="Avatar" class="user-avatar me-2">
                <span class="text-white"><?php echo $_SESSION['nombre']; ?></span>
            </div>
        </div>
        
        <div class="px-3 mb-4">
            <form action="buscar.php" method="GET" class="sidebar-search">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0 text-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control bg-transparent border-0 text-white" placeholder="Buscar..." data-traducir="sidebar.search">
                    <button type="submit" class="d-none"></button>
                </div>
            </form>
        </div>
        
        <h6 class="sidebar-heading px-3 mt-4 mb-2 text-uppercase text-light opacity-50">
            <span data-traducir="sidebar.main_menu">Menú Principal</span>
        </h6>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'home') ? 'active' : ''; ?>" href="home.php">
                    <i class="fas fa-tachometer-alt"></i> <span data-traducir="sidebar.dashboard">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'equipos') ? 'active' : ''; ?>" href="equipos.php">
                    <i class="fas fa-laptop"></i> <span data-traducir="sidebar.equipment">Equipos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'tecnicos') ? 'active' : ''; ?>" href="tecnicos.php">
                    <i class="fas fa-users-cog"></i> <span data-traducir="sidebar.technicians">Técnicos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'ordenes') ? 'active' : ''; ?>" href="ordenes.php">
                    <i class="fas fa-clipboard-list"></i> <span data-traducir="sidebar.orders">Órdenes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'reportes') ? 'active' : ''; ?>" href="reportes.php">
                    <i class="fas fa-chart-bar"></i> <span data-traducir="sidebar.reports">Reportes</span>
                </a>
            </li>
        </ul>
        
        <h6 class="sidebar-heading px-3 mt-4 mb-2 text-uppercase text-light opacity-50">
            <span data-traducir="sidebar.user_menu">Usuario</span>
        </h6>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'perfil') ? 'active' : ''; ?>" href="perfil.php">
                    <i class="fas fa-user"></i> <span data-traducir="sidebar.my_profile">Mi Perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($pagina_actual == 'faq') ? 'active' : ''; ?>" href="faq.php">
                    <i class="fas fa-question-circle"></i> <span data-traducir="sidebar.help">Ayuda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="php/logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span data-traducir="sidebar.logout">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
        
        <div class="px-3 mt-5">
            <div class="card bg-primary bg-opacity-25 border-0 text-white">
                <div class="card-body">
                    <h6 class="card-title" data-traducir="sidebar.support.title"><i class="fas fa-headset me-2"></i> ¿Necesitas ayuda?</h6>
                    <p class="card-text small" data-traducir="sidebar.support.description">Contacta con soporte técnico para resolver cualquier problema.</p>
                    <a href="faq.php" class="btn btn-sm btn-primary w-100" data-traducir="sidebar.support.button">Contactar Soporte</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos adicionales para el sidebar */
    .sidebar {
        background: linear-gradient(180deg, #343a40 0%, #212529 100%);
        box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 100;
        transition: all 0.3s ease;
    }
    
    .sidebar-logo {
        background: rgba(255, 255, 255, 0.1);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .sidebar-logo:hover {
        transform: rotate(10deg) scale(1.05);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .sidebar-search {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 5px 15px;
        transition: all 0.3s ease;
    }
    
    .sidebar-search:focus-within {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-search input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .sidebar-heading {
        font-size: 0.75rem;
        letter-spacing: 1px;
    }
    
    .sidebar .nav-link {
        padding: 10px 15px;
        margin: 2px 0;
        border-radius: 8px;
        transition: all 0.3s ease;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(5px);
    }
    
    .sidebar .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 500;
    }
    
    .sidebar .nav-link i {
        width: 20px;
        margin-right: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .sidebar .nav-link:hover i {
        transform: scale(1.2);
    }
</style>