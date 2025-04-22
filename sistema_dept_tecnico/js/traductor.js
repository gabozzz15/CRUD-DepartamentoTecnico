// Sistema de traducción
class Traductor {
    constructor(idioma = 'es') {
        this.idioma = idioma;
        this.traducciones = {};
        this.cargarTraducciones();
    }

    async cargarTraducciones() {
        try {
            // Corregir la ruta para que sea relativa al directorio actual
            const respuesta = await fetch(`lang/${this.idioma}.json`);
            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }
            this.traducciones = await respuesta.json();
            this.aplicarTraducciones();
            console.log(`Traducciones cargadas correctamente para idioma: ${this.idioma}`);
            
            // Actualizar el atributo lang del documento HTML
            document.documentElement.lang = this.idioma;
            
            // Disparar un evento personalizado para notificar que las traducciones se han cargado
            const event = new CustomEvent('traduccionesAplicadas', { detail: { idioma: this.idioma } });
            document.dispatchEvent(event);
        } catch (error) {
            console.error('Error cargando traducciones:', error);
        }
    }

    traducir(clave) {
        const partes = clave.split('.');
        let resultado = this.traducciones;
        
        for (let parte of partes) {
            if (resultado && resultado[parte]) {
                resultado = resultado[parte];
            } else {
                // Extraer el último texto después del último punto
                const ultimaParte = partes[partes.length - 1];
                
                // Convertir de snake_case o camelCase a texto legible
                let textoLegible = ultimaParte
                    .replace(/([A-Z])/g, ' $1') // Separar palabras en camelCase
                    .replace(/_/g, ' ') // Reemplazar guiones bajos
                    .replace(/\b\w/g, l => l.toUpperCase()); // Capitalizar primera letra de cada palabra
                
                return textoLegible;
            }
        }
        
        return resultado;
    }

    aplicarTraducciones() {
        // Traducir elementos con atributo data-traducir
        const elementosTraducir = document.querySelectorAll('[data-traducir]');
        console.log(`Aplicando traducciones a ${elementosTraducir.length} elementos`);
        
        elementosTraducir.forEach(elemento => {
            const clave = elemento.getAttribute('data-traducir');
            const traduccion = this.traducir(clave);
            
            // Manejar diferentes tipos de elementos
            switch (elemento.tagName) {
                case 'INPUT':
                    if (elemento.type === 'submit' || elemento.type === 'button') {
                        elemento.value = traduccion;
                    } else {
                        elemento.setAttribute('placeholder', traduccion);
                    }
                    break;
                case 'OPTION':
                    elemento.textContent = traduccion;
                    break;
                case 'IMG':
                    elemento.setAttribute('alt', traduccion);
                    break;
                case 'TEXTAREA':
                    elemento.setAttribute('placeholder', traduccion);
                    break;
                default:
                    elemento.textContent = traduccion;
            }
        });

        // Traducir títulos de páginas
        const tituloPagina = document.querySelector('title');
        if (tituloPagina) {
            const claveTraduccion = tituloPagina.getAttribute('data-traducir');
            if (claveTraduccion) {
                tituloPagina.textContent = this.traducir(claveTraduccion);
            }
        }

        // Traducir mensajes de alerta
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(alerta => {
            const claveTraduccion = alerta.getAttribute('data-traducir');
            if (claveTraduccion) {
                alerta.textContent = this.traducir(claveTraduccion);
            }
        });

        // Traducir tooltips
        const tooltips = document.querySelectorAll('[title]');
        tooltips.forEach(tooltip => {
            const claveTraduccion = tooltip.getAttribute('data-traducir-title');
            if (claveTraduccion) {
                tooltip.setAttribute('title', this.traducir(claveTraduccion));
            }
        });
    }

    cambiarIdioma(nuevoIdioma) {
        if (this.idioma === nuevoIdioma) {
            console.log(`El idioma ya está configurado como: ${nuevoIdioma}`);
            return;
        }
        
        this.idioma = nuevoIdioma;
        this.cargarTraducciones();
        
        // Guardar idioma en localStorage
        localStorage.setItem('idioma_sistema', nuevoIdioma);
        
        console.log(`Idioma cambiado a: ${nuevoIdioma}`);
    }
}

// Inicializar traductor al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('Inicializando sistema de traducción...');
    
    // Obtener idioma guardado o usar español por defecto
    const idiomaGuardado = localStorage.getItem('idioma_sistema') || 'es';
    console.log(`Idioma detectado: ${idiomaGuardado}`);
    
    // Crear instancia global del traductor
    window.traductor = new Traductor(idiomaGuardado);

    // Manejar cambio de idioma
    const selectIdioma = document.getElementById('idioma');
    if (selectIdioma) {
        // Asegurarse de que el valor del select coincida con el idioma actual
        selectIdioma.value = idiomaGuardado;
        
        selectIdioma.addEventListener('change', (e) => {
            const nuevoIdioma = e.target.value;
            console.log(`Cambiando idioma a: ${nuevoIdioma}`);
            window.traductor.cambiarIdioma(nuevoIdioma);
        });
    }
    
    // Observar cambios en el DOM para traducir elementos nuevos
    const observador = new MutationObserver((mutaciones) => {
        mutaciones.forEach((mutacion) => {
            if (mutacion.type === 'childList' && mutacion.addedNodes.length > 0) {
                // Verificar si hay nuevos elementos con data-traducir
                mutacion.addedNodes.forEach(nodo => {
                    if (nodo.nodeType === 1) { // Elemento
                        const elementosTraducir = nodo.querySelectorAll ? nodo.querySelectorAll('[data-traducir]') : [];
                        if (elementosTraducir.length > 0 || nodo.hasAttribute && nodo.hasAttribute('data-traducir')) {
                            // Aplicar traducciones a los nuevos elementos
                            window.traductor.aplicarTraducciones();
                        }
                    }
                });
            }
        });
    });
    
    // Configurar el observador para observar cambios en todo el documento
    observador.observe(document.body, { childList: true, subtree: true });
});