# Sistema de Gestión para Departamento Técnico

![Sistema Dept Técnico](https://img.shields.io/badge/Sistema-Dept%20T%C3%A9cnico-blue)
![PHP](https://img.shields.io/badge/PHP-8.0+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blueviolet)

Un sistema web completo para la gestión y administración de equipos, técnicos y órdenes de servicio en un departamento técnico. Permite llevar un control detallado de los equipos que ingresan para reparación, asignar técnicos, y hacer seguimiento del estado de las órdenes.

## 📋 Características

- **Gestión de Equipos**: Registro y seguimiento de equipos (PC, laptops, impresoras, routers, etc.)
- **Gestión de Técnicos**: Administración del personal técnico disponible
- **Órdenes de Servicio**: Creación y seguimiento de órdenes de reparación
- **Dashboard Interactivo**: Visualización de estadísticas y acciones rápidas
- **Reportes**: Generación de informes sobre el estado de las reparaciones
- **Sistema Multiidioma**: Soporte para español e inglés
- **Modo Oscuro**: Interfaz adaptable con modo claro/oscuro
- **Responsive Design**: Adaptable a diferentes dispositivos

## 🚀 Tecnologías Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP
- **Base de Datos**: MySQL
- **Librerías**: Font Awesome, Chart.js

## 🔧 Instalación

1. Clona este repositorio:
   ```
   git clone https://github.com/gabozzz15/sistema-dept-tecnico.git
   ```

2. Importa la base de datos:
   ```
   mysql -u usuario -p < sistema_dept_tecnico.sql
   ```

3. Configura la conexión a la base de datos en `inc/conexionbd.php`

4. Accede al sistema a través de tu servidor web local:
   ```
   http://localhost/sistema-dept-tecnico
   ```

## 📊 Estructura del Proyecto

```
sistema-dept-tecnico/
├── css/                  # Hojas de estilo
├── inc/                  # Archivos de inclusión (header, footer, sidebar)
├── js/                   # Scripts JavaScript
├── lang/                 # Archivos de idioma
├── php/                  # Funciones y lógica PHP
├── index.php             # Página de inicio de sesión
├── home.php              # Dashboard principal
├── equipos.php           # Gestión de equipos
├── tecnicos.php          # Gestión de técnicos
├── ordenes.php           # Gestión de órdenes de servicio
├── reportes.php          # Generación de reportes
└── sistema_dept_tecnico.sql  # Estructura de la base de datos
```

## 📸 Capturas de Pantalla

[![image.png](https://i.postimg.cc/9MpRzZYt/image.png)](https://postimg.cc/1n85jgTf) [![image.png](https://i.postimg.cc/gkrXkwX8/image.png)](https://postimg.cc/RN5VgZTZ)
[![image.png](https://i.postimg.cc/c15TMfYZ/image.png)](https://postimg.cc/w3Lh9y0b) [![image.png](https://i.postimg.cc/CL3N9YmQ/image.png)](https://postimg.cc/8jbM688R)

## 🔐 Credenciales de Prueba

Para probar el sistema, puedes utilizar las siguientes credenciales:

- **Usuario**: admin
- **Contraseña**: admin123

## 🌐 Funcionalidades Principales

### Dashboard
- Visualización de estadísticas generales
- Acceso rápido a las funciones más utilizadas
- Listado de órdenes recientes

### Gestión de Equipos
- Registro de nuevos equipos
- Categorización por tipo (PC, laptop, impresora, etc.)
- Historial de reparaciones por equipo

### Gestión de Técnicos
- Registro de información de técnicos
- Control de disponibilidad
- Asignación de órdenes de servicio

### Órdenes de Servicio
- Creación de nuevas órdenes
- Seguimiento del estado (pendiente, reparado, entregado, no reparable)
- Registro de fechas de entrada, reparación y entrega

### Reportes
- Generación de informes por período
- Estadísticas de rendimiento
- Exportación de datos

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Para cambios importantes, por favor abre primero un issue para discutir lo que te gustaría cambiar.

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## ✒️ Autor

- **Gabriel Bastardo** - [gabozzz15](https://github.com/gabozzz15)

---

⌨️ con ❤️ por [Gabriel Bastardo](https://github.com/gabozzz15)
