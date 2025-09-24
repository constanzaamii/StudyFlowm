# StudyFlow - Sistema de Gestión Académica UCSC

## 📚 Descripción del Proyecto

**StudyFlow** es un sistema de gestión académica desarrollado para el Hackathón UCSC 2024, enfocado en el **Eje 2: Gestión académica básica**. La aplicación permite a los estudiantes de Ingeniería de Ejecución en Informática organizar sus tareas, calcular notas, hacer seguimiento de su progreso académico y gestionar recordatorios.

## 🎯 Problema y Usuario Objetivo

**Problema**: Los estudiantes universitarios necesitan una herramienta centralizada para organizar sus entregas académicas, calcular promedios de notas y hacer seguimiento de su progreso, especialmente durante los primeros años de carrera donde la carga académica puede ser abrumadora.

**Usuario Objetivo**: Estudiantes de 1° y 2° año de Ingeniería de Ejecución en Informática de la UCSC que buscan mejorar su organización académica y rendimiento estudiantil.

## 🚀 Solución Propuesta (MVP)

### Funcionalidades Principales:
- ✅ **Gestión de Tareas**: CRUD completo para crear, editar, completar y eliminar tareas académicas
- 📊 **Calculadora de Notas**: Sistema de cálculo de promedios con ponderaciones personalizables
- 📈 **Dashboard de Progreso**: Estadísticas visuales del rendimiento académico
- ⏰ **Sistema de Recordatorios**: Alertas para entregas próximas y tareas vencidas
- 📱 **Interfaz Responsive**: Diseño adaptable para desktop y móvil con Bootstrap 5
- 💾 **Persistencia de Datos**: Almacenamiento local con opción de exportación

### Alcance del MVP:
- Gestión individual de tareas y notas
- Interfaz web moderna y accesible con Bootstrap
- Base de datos SQL estructurada para escalabilidad futura
- Sistema de notificaciones básico

## 🛠️ Tecnologías Utilizadas

### Frontend:
- **HTML5**: Estructura semántica y accesible
- **Bootstrap 5.3.2**: Framework CSS para diseño responsive y componentes modernos
- **Bootstrap Icons**: Iconografía consistente y profesional
- **CSS3**: Variables CSS personalizadas y estilos complementarios
- **JavaScript (ES6+)**: Lógica de aplicación y manipulación del DOM

### Backend/Persistencia:
- **LocalStorage**: Simulación de base de datos para el prototipo
- **MySQL**: Esquema de base de datos completo para implementación futura

### Herramientas de Desarrollo:
- **Bootstrap 5**: Sistema de diseño robusto y probado
- **Tipografía**: Inter font family para legibilidad óptima
- **Iconos**: Bootstrap Icons para consistencia visual
- **CDN**: Carga rápida de recursos desde CDN oficial

## 🗄️ Arquitectura y Modelo de Datos

### Modelo Entidad-Relación (MER):

\`\`\`
USERS (Estudiantes)
├── id (PK)
├── student_id (UNIQUE)
├── first_name, last_name
├── email (UNIQUE)
├── career, year_level
└── timestamps

SUBJECTS (Asignaturas)
├── id (PK)
├── code (UNIQUE)
├── name, credits
├── semester, year_level
└── description

TASKS (Tareas)
├── id (PK)
├── user_id (FK)
├── subject_id (FK)
├── title, description
├── due_date, priority
├── status, completion_date
└── timestamps

GRADES (Notas)
├── id (PK)
├── user_id (FK)
├── subject_id (FK)
├── evaluation_type
├── grade, weight
├── evaluation_date
└── comments

ENROLLMENTS (Inscripciones)
├── user_id (FK)
├── subject_id (FK)
├── semester, status
└── final_grade
\`\`\`

### Flujo de Datos:
1. **Entrada**: Usuario crea/edita tareas y registra notas
2. **Procesamiento**: Validación y cálculos automáticos de promedios
3. **Almacenamiento**: Persistencia en localStorage (prototipo) / MySQL (producción)
4. **Salida**: Dashboard actualizado con estadísticas y recordatorios

## 🎨 Decisiones de Diseño

### Framework CSS:
- **Bootstrap 5.3.2**: Framework CSS moderno y responsive
- **Sistema de Grid**: Layout flexible con breakpoints estándar
- **Componentes**: Cards, modals, forms y navegación pre-diseñados
- **Utilidades**: Clases de espaciado, colores y tipografía consistentes

### Paleta de Colores (Bootstrap + Custom):
- **Primario**: Azul Bootstrap (#0d6efd) para acciones principales
- **Secundario**: Gris (#6c757d) para elementos secundarios
- **Éxito**: Verde Bootstrap (#198754) para completado/positivo
- **Advertencia**: Amarillo Bootstrap (#ffc107) para fechas próximas
- **Peligro**: Rojo Bootstrap (#dc3545) para eliminación/vencido
- **Fondo**: Light (#f8f9fa) para contraste suave

### Componentes Bootstrap Utilizados:
- **Navbar**: Navegación responsive con collapse
- **Cards**: Contenedores de contenido con header y body
- **Modals**: Diálogos para formularios de tareas y notas
- **Forms**: Inputs, selects y textareas con validación visual
- **Buttons**: Botones con variantes de color y tamaño
- **Grid System**: Layout responsive con containers y rows
- **Alerts**: Notificaciones toast personalizadas

### UX/UI:
- **Diseño Mobile-First**: Bootstrap grid responsive automático
- **Accesibilidad**: Componentes Bootstrap con ARIA labels incluidos
- **Microinteracciones**: Hover states y transiciones Bootstrap
- **Feedback Visual**: Sistema de notificaciones con Bootstrap alerts

## 🔧 Instalación y Uso

### Requisitos:
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
- Conexión a internet para cargar Bootstrap desde CDN
- No requiere instalación de dependencias

### Instrucciones:
1. Descargar los archivos del proyecto
2. Abrir `index.html` en un navegador web
3. ¡Comenzar a gestionar tus tareas académicas!

### Para Desarrollo:
\`\`\`bash
# Clonar el repositorio
git clone [url-del-repo]

# Opción 1: Abrir directamente
# Abrir index.html en navegador

# Opción 2: Servidor local (recomendado)
# Con Python
python -m http.server 8000

# Con Node.js (si tienes live-server instalado)
npm install -g live-server
live-server --port=3000

# Con PHP
php -S localhost:8000
\`\`\`

### Estructura del Proyecto:
\`\`\`
studyflow-academic-manager/
├── index.html              # Página principal con Bootstrap
├── styles/
│   └── globals.css         # Estilos personalizados + variables CSS
├── js/
│   └── app.js             # Lógica de aplicación con Bootstrap JS
├── scripts/
│   ├── database-schema.sql # Esquema de base de datos
│   └── sample-data.sql    # Datos de ejemplo
├── package.json           # Configuración del proyecto
└── README.md             # Documentación
\`\`\`

## 📋 Funcionalidades Implementadas

### ✅ CRUD de Tareas:
- Crear tareas con título, asignatura, descripción, fecha límite y prioridad
- Marcar tareas como completadas/pendientes
- Eliminar tareas con confirmación
- Filtrado visual por estado y prioridad

### ✅ Calculadora de Notas:
- Cálculo automático de promedios ponderados (30%-30%-40%)
- Registro histórico de evaluaciones
- Promedio general actualizado en tiempo real

### ✅ Dashboard Estadístico:
- Contador de tareas totales, pendientes y completadas
- Promedio general de notas
- Actividad reciente del usuario

### ✅ Sistema de Recordatorios:
- Identificación visual de tareas vencidas y próximas
- Lista de entregas en los próximos 7 días
- Log de actividad del usuario

## 🚀 Aprendizajes y Límites Actuales

### Aprendizajes:
- **Bootstrap Framework**: Implementación de sistema de diseño profesional
- **Responsive Design**: Grid system automático y breakpoints estándar
- **Componentes Reutilizables**: Modals, cards y forms consistentes
- **Accesibilidad Web**: Componentes Bootstrap con ARIA incluido
- **Desarrollo Rápido**: Prototipado acelerado con framework maduro

### Límites Actuales:
- **Persistencia**: Datos almacenados localmente (no sincronización)
- **Autenticación**: Sin sistema de usuarios múltiples
- **Notificaciones**: Solo visuales, sin push notifications
- **Colaboración**: Funcionalidad individual únicamente
- **Dependencia CDN**: Requiere conexión a internet para Bootstrap

## 🔮 Futuras Mejoras

### Corto Plazo:
- [ ] Integración con base de datos real (MySQL/PostgreSQL)
- [ ] Sistema de autenticación de usuarios
- [ ] Notificaciones push y por email
- [ ] Importación/exportación de datos académicos
- [ ] Tema oscuro con Bootstrap variables CSS

### Mediano Plazo:
- [ ] Aplicación móvil nativa (React Native/Flutter)
- [ ] Integración con sistemas académicos UCSC
- [ ] Funcionalidades colaborativas (grupos de estudio)
- [ ] Analytics avanzados de rendimiento
- [ ] PWA (Progressive Web App) con Service Workers

### Largo Plazo:
- [ ] IA para recomendaciones de estudio
- [ ] Gamificación del proceso académico
- [ ] Integración con calendarios externos
- [ ] Dashboard para profesores y coordinadores
- [ ] Migración a framework moderno (React/Vue + Bootstrap)

## 📦 Dependencias

### CDN (Cargadas automáticamente):
- **Bootstrap CSS 5.3.2**: Framework CSS principal
- **Bootstrap JS 5.3.2**: Componentes interactivos (modals, dropdowns)
- **Bootstrap Icons 1.11.1**: Iconografía completa
- **Google Fonts (Inter)**: Tipografía moderna

### Desarrollo (Opcionales):
- **live-server**: Servidor de desarrollo local
- **http-server**: Alternativa para servidor estático

## 👥 Equipo de Desarrollo

**Proyecto desarrollado para el Hackathón UCSC 2024**
- **Eje Temático**: Gestión académica básica
- **Tecnologías**: HTML, CSS, JavaScript, Bootstrap 5, SQL
- **Framework**: Bootstrap 5.3.2 para diseño responsive
- **Duración**: 3 días de desarrollo intensivo

## 📄 Licencia

Este proyecto fue desarrollado como parte del Hackathón UCSC 2024 con fines educativos y de demostración de habilidades técnicas.

---

**StudyFlow** - Organizando el éxito académico con Bootstrap, una tarea a la vez 📚✨
