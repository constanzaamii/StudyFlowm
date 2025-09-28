<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StudyFlow - Mis Tareas</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
</head>
<body>
    
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">📚 StudyFlow</div>
                <nav class="nav">
                    <a href="/" class="nav-link">Dashboard</a>
                    <a href="/tasks" class="nav-link active">Tareas</a>
                    <a href="/grades" class="nav-link">Notas</a>
                    <a href="/profile" class="nav-link">👤 Perfil</a>
                </nav>
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Cambiar tema">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <!-- Welcome Section -->
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">📌 Mis Tareas</h1>
                    <p class="card-description">
                        Organiza y gestiona tus entregas académicas con calendario integrado.
                    </p>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Tasks Section (Left) -->
                <div>
                    <div class="card">
                        <div class="card-header">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="card-title">Lista de Tareas</h2>
                                    <p class="card-description">Gestiona tus entregas pendientes</p>
                                </div>
                                <button class="btn btn-primary" onclick="openTaskModal()">
                                    ➕ Nueva Tarea
                                </button>
                            </div>
                        </div>
                        <div class="task-list" id="taskList">
                            <div id="loading" class="text-gray-500">Cargando tareas...</div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section (Right) -->
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">🔍 Filtros</h3>
                        </div>
                        <div style="padding: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select class="form-select" id="statusFilter" onchange="filterTasks()">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendiente</option>
                                    
                                    <option value="completed">Completada</option>
                                    <option value="overdue">Vencida</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" id="priorityFilter" onchange="filterTasks()">
                                    <option value="">Todas</option>
                                    <option value="high">Alta</option>
                                    <option value="medium">Media</option>
                                    <option value="low">Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section (Full Width Below) -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="card-title">📅 Calendario de Tareas</h3>
                        <div class="calendar-controls">
                            <button class="btn btn-sm btn-secondary" id="monthView" onclick="setCalendarView('month')">Mes</button>
                            <button class="btn btn-sm btn-secondary" id="weekView" onclick="setCalendarView('week')">Semana</button>
                            <button class="btn btn-sm btn-secondary" id="dayView" onclick="setCalendarView('day')">Día</button>
                        </div>
                    </div>
                </div>
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button class="btn btn-sm btn-secondary" onclick="navigateCalendar(-1)">‹</button>
                        <h4 id="calendarTitle">Septiembre 2025</h4>
                        <button class="btn btn-sm btn-secondary" onclick="navigateCalendar(1)">›</button>
                    </div>
                    <div id="calendarView" class="calendar-view">
                        <!-- Calendar content will be generated here -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Task Modal -->
    <div class="modal" id="taskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Nueva Tarea</h3>
                <button class="close-btn" onclick="closeTaskModal()">&times;</button>
            </div>
            <form id="taskForm">
                <div class="form-group">
                    <label class="form-label">Título de la Tarea</label>
                    <input type="text" class="form-input" id="taskTitle" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Asignatura</label>
                    <select class="form-select" id="taskSubject" required>
                        <option value="">Seleccionar asignatura</option>
                        <option value="Programación">Programación</option>
                        <option value="Base de Datos">Base de Datos</option>
                        <option value="Matemáticas">Matemáticas</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Física">Física</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-textarea" id="taskDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Entrega</label>
                    <input type="datetime-local" class="form-input" id="taskDueDate" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Prioridad</label>
                    <select class="form-select" id="taskPriority" required>
                        <option value="low">Baja</option>
                        <option value="medium">Media</option>
                        <option value="high">Alta</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                    <button type="button" class="btn btn-secondary" onclick="closeTaskModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>


<style>
.calendar-container {
    padding: 1rem;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.calendar-view {
    min-height: 300px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: var(--border);
    border: 1px solid var(--border);
}

.calendar-day {
    background-color: var(--card);
    padding: 0.5rem;
    min-height: 60px;
    border: none;
    position: relative;
}

.calendar-day.other-month {
    background-color: var(--muted);
    color: var(--muted-foreground);
}

.calendar-day.today {
    background-color: var(--primary);
    color: var(--primary-foreground);
}

.calendar-day.has-tasks {
    border-left: 3px solid var(--primary);
}

.calendar-controls {
    display: flex;
    gap: 0.25rem;
}

.calendar-controls .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.calendar-controls .btn.active {
    background-color: var(--primary);
    color: var(--primary-foreground);
}

.week-view, .day-view {
    display: none;
}

.week-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1rem;
}

.day-column {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    min-height: 400px;
}

.day-header {
    font-weight: 600;
    text-align: center;
    padding: 0.5rem;
    border-bottom: 1px solid var(--border);
    margin-bottom: 1rem;
}

.task-item-small {
    background-color: var(--accent);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius);
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    cursor: pointer;
}

.task-item-small.priority-high {
    border-left: 3px solid var(--destructive);
}

.task-item-small.priority-medium {
    border-left: 3px solid #d97706;
}

.task-item-small.priority-low {
    border-left: 3px solid #059669;
}

/* Estilos para el texto de prioridad - color blanco */
.task-priority.priority-high,
.task-priority.priority-medium,
.task-priority.priority-low {
    color: #ffffff !important;
}

/* Estilos para el texto de las tareas en el calendario - color blanco */
.task-item-small {
    color: #ffffff !important;
}

.task-item-small.priority-high,
.task-item-small.priority-medium,
.task-item-small.priority-low {
    color: #ffffff !important;
}
</style>

<script>
// Variables globales para el calendario
let currentView = 'month';
let currentDate = new Date();
let allTasks = [];

document.addEventListener('DOMContentLoaded', async () => {
    await loadTasks();
    await loadSubjects();
    initializeCalendar();
    setCalendarView('month');
});

async function loadSubjects() {
    try {
        const res = await fetch('/api/subjects', {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (res.ok) {
            const subjects = await res.json();
            const select = document.getElementById('taskSubject');
            
            // Limpiar opciones existentes excepto la primera
            while (select.children.length > 1) {
                select.removeChild(select.lastChild);
            }
            
            // Agregar asignaturas
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading subjects:', error);
    }
}

async function loadTasks() {
    const container = document.getElementById('taskList');
    const loading = document.getElementById('loading');
    
    try {
        const res = await fetch('/api/tasks', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const data = await res.json();
        allTasks = Array.isArray(data) ? data : [];
        
        if (loading) {
            loading.remove();
        }

        displayTasks(allTasks);
        updateCalendar();

    } catch (error) {
        console.error('Error loading tasks:', error);
        if (loading) {
            loading.innerText = 'Error al cargar tareas. Por favor, recarga la página.';
        }
        allTasks = [];
    }
}

function displayTasks(tasks) {
    const container = document.getElementById('taskList');
    container.innerHTML = '';

    if (tasks.length === 0) {
        container.innerHTML = `
            <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                <p>No hay tareas registradas</p>
                <p style="font-size: 0.875rem;">¡Crea tu primera tarea para comenzar!</p>
            </div>
        `;
        return;
    }

    tasks.forEach(task => {
        const taskElement = createTaskElement(task);
        container.appendChild(taskElement);
    });
}

function createTaskElement(task) {
    const taskDiv = document.createElement('div');
    taskDiv.className = 'task-item';

    const dueDate = new Date(task.due_date);
    const now = new Date();
    const isOverdue = dueDate < now && task.status !== 'completed';
    const isToday = dueDate.toDateString() === now.toDateString();

    let dueDateClass = '';
    if (isOverdue) dueDateClass = 'style="color: var(--destructive);"';
    else if (isToday) dueDateClass = 'style="color: var(--warning);"';

    const subjectName = task.subject && task.subject.name ? task.subject.name : 'Sin asignatura';
    taskDiv.innerHTML = `
        <div class="task-header">
            <div>
                <div class="task-title" ${task.status === 'completed' ? 'style=\"text-decoration: line-through; opacity: 0.6;\"' : ''}>${task.title}</div>
                <span class="task-subject">${subjectName}</span>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-success" onclick="toggleTask('${task.id}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                    ${task.status === 'completed' ? 'Desmarcar' : 'Completar'}
                </button>
                <button class="btn btn-destructive" onclick="deleteTask('${task.id}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                Eliminar
                </button>
            </div>
        </div>
        <div class="task-due" ${dueDateClass}>
            📅 ${formatDate(dueDate)} ${isOverdue ? '(Vencida)' : isToday ? '(Hoy)' : ''}
        </div>
        ${task.description ? `<p style=\"color: var(--muted-foreground); font-size: 0.875rem; margin-bottom: 0.5rem;\">${task.description}</p>` : ''}
        <span class="task-priority priority-${task.priority}">
            ${task.priority === 'high' ? '🔴 Alta' : task.priority === 'medium' ? '🟡 Media' : '🟢 Baja'}
        </span>
    `;

    return taskDiv;
}

function formatDate(date) {
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Funciones del calendario
function initializeCalendar() {
    updateCalendarTitle();
}

function setCalendarView(view) {
    currentView = view;
    
    // Actualizar botones activos
    document.querySelectorAll('.calendar-controls .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(view + 'View').classList.add('active');
    
    updateCalendar();
}

function navigateCalendar(direction) {
    if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + direction);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() + (direction * 7));
    } else if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() + direction);
    }
    
    updateCalendarTitle();
    updateCalendar();
}

function updateCalendarTitle() {
    const title = document.getElementById('calendarTitle');
    
    if (currentView === 'month') {
        title.textContent = currentDate.toLocaleDateString('es-ES', { 
            month: 'long', 
            year: 'numeric' 
        });
    } else if (currentView === 'week') {
        const startWeek = new Date(currentDate);
        startWeek.setDate(currentDate.getDate() - currentDate.getDay());
        const endWeek = new Date(startWeek);
        endWeek.setDate(startWeek.getDate() + 6);
        
        title.textContent = `${startWeek.getDate()} - ${endWeek.getDate()} ${startWeek.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
    } else if (currentView === 'day') {
        title.textContent = currentDate.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    }
}

function updateCalendar() {
    const calendarView = document.getElementById('calendarView');
    
    if (currentView === 'month') {
        calendarView.innerHTML = generateMonthView();
    } else if (currentView === 'week') {
        calendarView.innerHTML = generateWeekView();
    } else if (currentView === 'day') {
        calendarView.innerHTML = generateDayView();
    }
}

function generateMonthView() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    let html = '<div class="calendar-grid">';
    
    // Días de la semana
    const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    daysOfWeek.forEach(day => {
        html += `<div class="calendar-day" style="font-weight: 600; text-align: center;">${day}</div>`;
    });
    
    // Días del mes
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const isCurrentMonth = date.getMonth() === month;
        const isToday = date.toDateString() === new Date().toDateString();
        const tasksForDay = getTasksForDate(date);
        
        let classes = 'calendar-day';
        if (!isCurrentMonth) classes += ' other-month';
        if (isToday) classes += ' today';
        if (tasksForDay.length > 0) classes += ' has-tasks';
        
        html += `<div class="${classes}">
            <div>${date.getDate()}</div>
            ${tasksForDay.slice(0, 2).map(task => 
                `<div class="task-item-small priority-${task.priority}" title="${task.title}">
                    ${task.title.substring(0, 15)}${task.title.length > 15 ? '...' : ''}
                </div>`
            ).join('')}
            ${tasksForDay.length > 2 ? `<div class="task-item-small">+${tasksForDay.length - 2} más</div>` : ''}
        </div>`;
    }
    
    html += '</div>';
    return html;
}

function generateWeekView() {
    const startWeek = new Date(currentDate);
    startWeek.setDate(currentDate.getDate() - currentDate.getDay());
    
    let html = '<div class="week-grid">';
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(startWeek);
        date.setDate(startWeek.getDate() + i);
        const tasksForDay = getTasksForDate(date);
        
        html += `<div class="day-column">
            <div class="day-header">${date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' })}</div>
            ${tasksForDay.map(task => 
                `<div class="task-item-small priority-${task.priority}" title="${task.description}">
                    <div style="font-weight: 600;">${task.title}</div>
                    <div style="font-size: 0.6rem; opacity: 0.8;">${task.subject.name}</div>
                </div>`
            ).join('')}
        </div>`;
    }
    
    html += '</div>';
    return html;
}

function generateDayView() {
    const tasksForDay = getTasksForDate(currentDate);
    
    let html = '<div class="day-column" style="width: 100%;">';
    html += `<div class="day-header">Tareas para ${currentDate.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' })}</div>`;
    
    if (tasksForDay.length === 0) {
        html += '<div style="text-align: center; color: var(--muted-foreground); padding: 2rem;">No hay tareas para este día</div>';
    } else {
        tasksForDay.forEach(task => {
            html += `<div class="task-item priority-${task.priority}" style="margin-bottom: 1rem;">
                <div class="task-header">
                    <div>
                        <div class="task-title">${task.title}</div>
                        <span class="task-subject">${task.subject.name}</span>
                    </div>
                    <span class="task-priority priority-${task.priority}">
                        ${task.priority === 'high' ? '🔴 Alta' : task.priority === 'medium' ? '🟡 Media' : '🟢 Baja'}
                    </span>
                </div>
                <div class="task-due">📅 ${formatDate(new Date(task.due_date))}</div>
                ${task.description ? `<p style="color: var(--muted-foreground); font-size: 0.875rem;">${task.description}</p>` : ''}
            </div>`;
        });
    }
    
    html += '</div>';
    return html;
}

function getTasksForDate(date) {
    return allTasks.filter(task => {
        const taskDate = new Date(task.due_date);
        return taskDate.toDateString() === date.toDateString();
    });
}

// Funciones de filtrado
function filterTasks() {
    const statusFilter = document.getElementById('statusFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    
    let filteredTasks = allTasks;
    
    if (statusFilter) {
        filteredTasks = filteredTasks.filter(task => task.status === statusFilter);
    }
    
    if (priorityFilter) {
        filteredTasks = filteredTasks.filter(task => task.priority === priorityFilter);
    }
    
    displayTasks(filteredTasks);
}

// Funciones de modal y tareas
function openTaskModal() {
    document.getElementById('taskModal').classList.add('active');
    document.getElementById('taskTitle').focus();
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.remove('active');
    document.getElementById('taskForm').reset();
}

async function toggleTask(taskId) {
    console.log('Toggling task with ID:', taskId);
    try {
        const res = await fetch(`/api/tasks/${taskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        
        if (res.ok && data.success) {
            await loadTasks(); // Recargar tareas
            updateCalendar(); // Actualizar calendario
        } else {
            console.error('Error response:', data);
            alert(data.message || 'No se pudo actualizar la tarea.');
        }
    } catch (err) {
        console.error('Network error:', err);
        alert('Error de conexión. Verifica tu conexión a internet.');
    }
}

async function deleteTask(taskId) {
    console.log('Deleting task with ID:', taskId);
    if (confirm('¿Estás seguro de que quieres eliminar esta tarea? Esta acción no se puede deshacer.')) {
        try {
            const res = await fetch(`/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();
            
            if (res.ok && data.success) {
                await loadTasks(); // Recargar tareas
                updateCalendar(); // Actualizar calendario
                alert('Tarea eliminada exitosamente');
            } else {
                console.error('Error response:', data);
                alert(data.message || 'No se pudo eliminar la tarea.');
            }
        } catch (err) {
            console.error('Network error:', err);
            alert('Error de conexión. No se pudo eliminar la tarea.');
        }
    }
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', (e) => {
    const taskModal = document.getElementById('taskModal');
    if (e.target === taskModal) {
        closeTaskModal();
    }
});

// Funciones de tema (para mantener compatibilidad)
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    const themeIcon = document.querySelector('.theme-icon');
    themeIcon.textContent = document.documentElement.classList.contains('dark') ? '☀️' : '🌙';
}

// Event listener para el formulario de tareas
document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                title: document.getElementById('taskTitle').value,
                subject_id: document.getElementById('taskSubject').value,
                description: document.getElementById('taskDescription').value,
                due_date: document.getElementById('taskDueDate').value,
                priority: document.getElementById('taskPriority').value
            };
            
            try {
                const res = await fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await res.json();
                
                if (res.ok && data.success) {
                    closeTaskModal();
                    await loadTasks();
                    updateCalendar();
                    alert('Tarea creada exitosamente');
                } else {
                    console.error('Error response:', data);
                    alert(data.message || 'Error al crear la tarea');
                }
            } catch (err) {
                console.error('Network error:', err);
                alert('Error de conexión. No se pudo crear la tarea.');
            }
        });
    }
});
</script>

</body>
</html>
