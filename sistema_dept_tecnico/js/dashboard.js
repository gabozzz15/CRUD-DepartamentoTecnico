// Script para inicializar los gráficos y animaciones del dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el gráfico de actividad
    initActivityChart();
    
    // Añadir animaciones a las tarjetas
    animateCards();
    
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Función para inicializar el gráfico de actividad
function initActivityChart() {
    // Verificar si el elemento canvas existe
    var ctx = document.getElementById('activityChart');
    if (!ctx) return;
    
    // Datos de ejemplo para el gráfico (últimos 7 días)
    var labels = [];
    var today = new Date();
    
    for (var i = 6; i >= 0; i--) {
        var date = new Date(today);
        date.setDate(today.getDate() - i);
        labels.push(date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' }));
    }
    
    // Datos de ejemplo
    var completedData = [5, 8, 12, 7, 10, 15, 9];
    var pendingData = [3, 5, 7, 4, 6, 8, 5];
    var unreparableData = [1, 0, 2, 1, 0, 1, 0];
    
    // Crear el gráfico
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Completadas',
                    data: completedData,
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pendientes',
                    data: pendingData,
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'No reparables',
                    data: unreparableData,
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}

// Función para añadir animaciones a las tarjetas
function animateCards() {
    // Seleccionar todas las tarjetas del dashboard
    const cards = document.querySelectorAll('.card-dashboard');
    
    // Añadir clase de animación con retraso incremental
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, 100 * index);
    });
    
    // Añadir efecto de pulsación a los iconos
    const icons = document.querySelectorAll('.card-dashboard i.fa-3x');
    icons.forEach(icon => {
        setInterval(() => {
            icon.classList.add('pulse-animation');
            setTimeout(() => {
                icon.classList.remove('pulse-animation');
            }, 1000);
        }, 3000);
    });
}

// Añadir clase CSS para la animación de pulsación
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse 1s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});