<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StudyFlow - Gestión Académica UCSC</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
</head>
<body>
    
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">📚 StudyFlow</div>
                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="nav-link active">Dashboard</a>
                    <a href="{{ route('tasks.index') }}" class="nav-link">Tareas</a>
                    <a href="{{ route('grades.index') }}" class="nav-link">Notas</a>
                    <a href="{{ route('profile') }}" class="nav-link">👤 Perfil</a>
                </nav>
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Cambiar tema">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </div>
    </header>

   
    <main class="main">
        <div class="container">
           
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">¡Bienvenido a StudyFlow! 🎓</h1>
                    <p class="card-description">
                        Tu sistema de gestión académica para organizar tareas, calcular notas y hacer seguimiento de tu progreso universitario.
                    </p>
                </div>
            </div>

            <!-- Login Section (overlay style) -->
            <div id="loginSection" class="card" style="display: none;">
                <div class="card-header">
                    <h2 class="card-title">🔐 Iniciar Sesión</h2>
                    <p class="card-description">Accede a tu cuenta para ver tus tareas y calificaciones</p>
                </div>
                <div class="card-content">
                    <form id="loginForm" class="space-y-4">
                        <div>
                            <label class="form-label" for="loginEmail">Email</label>
                            <input type="email" id="loginEmail" name="email" class="form-input" autocomplete="email" placeholder="tu@email.com" required>
                        </div>
                        <div>
                            <label class="form-label" for="loginPassword">Contraseña</label>
                            <input type="password" id="loginPassword" name="password" class="form-input" autocomplete="current-password" placeholder="Tu contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                    <div style="margin-top: 1rem; padding: 1rem; background: var(--muted); border-radius: var(--radius); font-size: 0.875rem;">
                        <p><strong>👥 Usuarios de prueba:</strong></p>
                        <p>📧 juan@studyflow.com | 🔑 password123</p>
                        <p>📧 maria@studyflow.com | 🔑 password123</p>
                    </div>
                </div>
            </div>

            <!-- User Section -->
            <div id="userSection" style="display: none;"></div>

             <!-- Stats Grid -->
            <div id="statsSection" class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="totalTasks">{{ $stats['totalTasks'] ?? 0 }}</div>
                    <div class="stat-label">Tareas Totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="pendingTasks">{{ $stats['pendingTasks'] ?? 0 }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="completedTasks">{{ $stats['completedTasks'] ?? 0 }}</div>
                    <div class="stat-label">Completadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="averageGrade">{{ number_format($stats['averageGrade'] ?? 0, 1) }}</div>
                    <div class="stat-label">Promedio General</div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="quick-actions-grid mb-4">
                <div class="action-card" onclick="openGradeModal()">
                    <div class="action-icon">🧮</div>
                    <div class="action-content">
                        <h3 class="action-title">Calcular Nota</h3>
                        <p class="action-description">Calcula tu promedio final por asignatura</p>
                    </div>
                </div>
                
                <div class="action-card" onclick="exportData()">
                    <div class="action-icon">📊</div>
                    <div class="action-content">
                        <h3 class="action-title">Exportar Datos</h3>
                        <p class="action-description">Descargar reporte de progreso</p>
                    </div>
                </div>
            </div>

                            <!-- Contenedor de Progreso (Académico y Semanal) -->
                    <div class="progress-container-grid mb-4">
                        <!-- Progreso Académico -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">📈 Progreso Académico</h3>
                                <p class="card-description">Tu avance general del semestre</p>
                            </div>
                            <div class="card-content">
                                <div class="progress-container-centered">
                                    <div class="progress-circle">
                                        <svg class="progress-ring" width="120" height="120">
                                            <circle class="progress-ring-background" cx="60" cy="60" r="50"></circle>
                                            <circle class="progress-ring-progress" cx="60" cy="60" r="50" id="progressCircle"></circle>
                                        </svg>
                                        <div class="progress-text">
                                            <span class="progress-percentage" id="academicProgress">{{ round($stats['averageGrade']) }}%</span>
                                            <span class="progress-label">Progreso</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Progreso Semanal -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">📊 Progreso Semanal</h3>
                                <p class="card-description">Tareas completadas por semana</p>
                            </div>
                            <div class="card-content">
                                <div class="chart-container">
                                    <canvas id="weeklyProgressChart" width="300" height="180"></canvas>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item">
                                        <span class="legend-color completed"></span>
                                        <span>Completadas</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color pending"></span>
                                        <span>Pendientes</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color overdue"></span>
                                        <span>Vencidas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendario Académico -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex justify-between align-center">
                                <div>
                                    <h3 class="card-title">📅 Calendario Académico</h3>
                                    <p class="card-description">Próximas fechas importantes</p>
                                </div>
                                <div class="calendar-controls">
                                    <button class="btn btn-secondary btn-sm" onclick="previousMonth()">‹</button>
                                    <span id="currentMonth">{{ now()->format('F Y') }}</span>
                                    <button class="btn btn-secondary btn-sm" onclick="nextMonth()">›</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="calendar-grid" id="calendarGrid">
                                <!-- Calendar will be generated by JavaScript -->
                            </div>
                            <div class="upcoming-events">
                                <h4>Próximas Fechas Importantes:</h4>
                                <div class="events-list" id="upcomingEvents">
                                    @forelse($recentTasks->where('status', '!=', 'completed')->take(5) as $task)
                                        <div class="event-item priority-{{ $task->priority }}">
                                            <div class="event-date">
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                            </div>
                                            <div class="event-details">
                                                <div class="event-title">{{ $task->title }}</div>
                                                <div class="event-subject">{{ $task->subject->name ?? 'Sin asignatura' }}</div>
                                            </div>
                                            <div class="event-priority">
                                                @if($task->priority === 'high') 🔴
                                                @elseif($task->priority === 'medium') 🟡
                                                @else 🟢
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center" style="color: var(--muted-foreground); padding: 1rem;">
                                            No hay eventos próximos
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



     
    <div class="modal" id="gradeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Calculadora de Notas</h3>
                <button class="close-btn" onclick="closeGradeModal()">&times;</button>
            </div>
            <form id="gradeForm">
                <div class="form-group">
                    <label class="form-label">Asignatura</label>
                    <select class="form-select" id="gradeSubject" required>
                        <option value="">Seleccionar asignatura</option>
                        <option value="1">Literatura</option>
                        <option value="2">Programación</option>
                        <option value="3">Matemáticas</option>
                        <option value="4">Historia</option>
                        <option value="5">Química</option>
                        <option value="6">Inglés</option>
                        <option value="7">Física</option>
                        <option value="8">Biología</option>
                        <option value="9">Filosofía</option>
                        <option value="10">Arte</option>
                        <option value="11">Base de Datos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nota 1 (30%)</label>
                    <input type="number" class="form-input" id="grade1" min="1" max="7" step="0.1">
                </div>
                <div class="form-group">
                    <label class="form-label">Nota 2 (30%)</label>
                    <input type="number" class="form-input" id="grade2" min="1" max="7" step="0.1">
                </div>
                <div class="form-group">
                    <label class="form-label">Examen (40%)</label>
                    <input type="number" class="form-input" id="examGrade" min="1" max="7" step="0.1">
                </div>
                <div class="form-group">
                    <label class="form-label">Promedio Final</label>
                    <input type="text" class="form-input" id="finalGrade" readonly>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="calculateGrade()">Calcular</button>
                    <button type="button" class="btn btn-success" onclick="saveGrade()">Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="closeGradeModal()">Cerrar</button>
                </div>
            </form>
        </div>
    </div>

    </div> <!-- Cerrar taskSection -->

    <style>
        /* Estilos para las acciones rápidas */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .action-icon {
            font-size: 2rem;
            min-width: 3rem;
            text-align: center;
        }

        .action-content {
            flex: 1;
        }

        .action-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--foreground);
            margin: 0 0 0.25rem 0;
        }

        .action-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin: 0;
        }


        /* Estilos adicionales para mejorar los botones de acción en tareas */
        .task-header .flex {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-header .btn {
            transition: all 0.2s ease;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 500;
        }

        .task-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: color-mix(in srgb, var(--success) 90%, black);
        }

        .btn-destructive {
            background-color: var(--destructive);
            color: white;
        }

        .btn-destructive:hover {
            background-color: color-mix(in srgb, var(--destructive) 90%, black);
        }

        /* Mejorar la apariencia del estado de las fechas */
        .task-due {
            margin: 0.5rem 0;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Asegurar que la prioridad se muestre correctamente */
        .task-priority {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            background-color: var(--muted);
            color: var(--muted-foreground);
        }

        .task-priority.priority-high {
            background-color: color-mix(in srgb, var(--destructive) 20%, transparent);
            color: var(--destructive);
        }

        .task-priority.priority-medium {
            background-color: color-mix(in srgb, var(--warning) 20%, transparent);
            color: var(--warning);
        }

        .task-priority.priority-low {
            background-color: color-mix(in srgb, var(--success) 20%, transparent);
            color: var(--success);
        }

        /* Progress Container Grid */
        .progress-container-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* Progress Circle Styles */
        .progress-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1rem;
        }

        .progress-container-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem 1rem;
        }

        .progress-circle {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-background {
            fill: none;
            stroke: var(--muted);
            stroke-width: 8;
        }

        .progress-ring-progress {
            fill: none;
            stroke: var(--primary);
            stroke-width: 8;
            stroke-linecap: round;
            stroke-dasharray: 408.4; /* 2 * π * 65 */
            stroke-dashoffset: 408.4;
            transition: stroke-dashoffset 0.8s ease-in-out;
        }

        .progress-text {
            position: absolute;
            text-align: center;
        }

        .progress-percentage {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: var(--foreground);
        }

        .progress-label {
            display: block;
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-top: 0.25rem;
        }

        .progress-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .progress-stat {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--foreground);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        /* Chart Styles */
        .chart-container {
            position: relative;
            height: 200px;
            margin-bottom: 1rem;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .legend-color.completed {
            background-color: var(--success);
        }

        .legend-color.pending {
            background-color: var(--warning);
        }

        .legend-color.overdue {
            background-color: var(--destructive);
        }

        /* Calendar Styles */
        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .calendar-day {
            background-color: var(--card);
            padding: 0.75rem 0.5rem;
            text-align: center;
            font-size: 0.875rem;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .calendar-day.header {
            background-color: var(--muted);
            font-weight: 600;
            color: var(--muted-foreground);
        }

        .calendar-day.other-month {
            color: var(--muted-foreground);
            opacity: 0.5;
        }

        .calendar-day.today {
            background-color: var(--primary);
            color: var(--primary-foreground);
            font-weight: 600;
        }

        .calendar-day.has-task {
            background-color: color-mix(in srgb, var(--warning) 20%, var(--card));
        }

        .calendar-day.has-task::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 6px;
            height: 6px;
            background-color: var(--warning);
            border-radius: 50%;
        }

        /* Events List */
        .upcoming-events h4 {
            margin-bottom: 1rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .events-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .event-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background-color: var(--muted);
            border-radius: var(--radius);
            border-left: 4px solid var(--border);
        }

        .event-item.priority-high {
            border-left-color: var(--destructive);
        }

        .event-item.priority-medium {
            border-left-color: var(--warning);
        }

        .event-item.priority-low {
            border-left-color: var(--success);
        }

        .event-date {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--foreground);
            min-width: 50px;
        }

        .event-details {
            flex: 1;
        }

        .event-title {
            font-weight: 500;
            color: var(--foreground);
        }

        .event-subject {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .event-priority {
            font-size: 1rem;
        }

        /* Sticky Header Styles */
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--card, #fff);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-bottom: 1px solid var(--border, #e5e7eb);
        }

        .header .nav {
            background: transparent;
        }

        .header .nav-link {
            color: var(--foreground, #222);
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(255,255,255,0.2);
            transition: color 0.2s;
        }

        .header .nav-link.active,
        .header .nav-link:hover {
            color: var(--primary, #2563eb);
            background: rgba(37,99,235,0.08);
            border-radius: 6px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .progress-container-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .progress-container {
                flex-direction: column;
                text-align: center;
            }

            .calendar-controls {
                flex-direction: column;
                gap: 0.5rem;
            }

            .chart-legend {
                gap: 0.5rem;
            }

            .event-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Dashboard functionality
        let currentDate = new Date();
        const tasksCalendar = {!! json_encode($calendarTasks) !!};

        // Initialize dashboard components
        document.addEventListener('DOMContentLoaded', function() {
            initProgressCircle();
            initWeeklyChart();
            generateCalendar();
        });

        // Progress Circle Animation
        function initProgressCircle() {
            const progressCircle = document.getElementById('progressCircle');
            const percentage = {{ round($stats['averageGrade']) }};
            const circumference = 2 * Math.PI * 50; // radius = 50 (ajustado al nuevo tamaño)
            const offset = circumference - (percentage / 100) * circumference;
            
            // Update the stroke-dasharray to match new circumference
            progressCircle.style.strokeDasharray = circumference;
            progressCircle.style.strokeDashoffset = circumference;
            
            setTimeout(() => {
                progressCircle.style.strokeDashoffset = offset;
            }, 500);
        }

        // Weekly Progress Chart
        function initWeeklyChart() {
            const canvas = document.getElementById('weeklyProgressChart');
            const ctx = canvas.getContext('2d');
            const canvasWidth = canvas.offsetWidth;
            const canvasHeight = canvas.offsetHeight;
            
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;

            // Data from backend
            const weeklyData = {!! json_encode($weeklyProgress) !!};

            drawBarChart(ctx, weeklyData, canvasWidth, canvasHeight);
        }

        function drawBarChart(ctx, data, width, height) {
            const padding = 40;
            const chartWidth = width - 2 * padding;
            const chartHeight = height - 2 * padding;
            const barWidth = chartWidth / data.length;
            
            // Colors
            const colors = {
                completed: getComputedStyle(document.documentElement).getPropertyValue('--success').trim(),
                pending: getComputedStyle(document.documentElement).getPropertyValue('--warning').trim(),
                overdue: getComputedStyle(document.documentElement).getPropertyValue('--destructive').trim()
            };

            // Find max value for scaling
            const maxValue = Math.max(...data.map(d => d.completed + d.pending + d.overdue));

            data.forEach((item, index) => {
                const x = padding + index * barWidth;
                const totalHeight = (item.completed + item.pending + item.overdue) / maxValue * chartHeight;
                
                let currentY = height - padding;
                
                // Draw completed tasks
                const completedHeight = (item.completed / maxValue) * chartHeight;
                ctx.fillStyle = colors.completed;
                ctx.fillRect(x + 10, currentY - completedHeight, barWidth - 20, completedHeight);
                currentY -= completedHeight;
                
                // Draw pending tasks
                const pendingHeight = (item.pending / maxValue) * chartHeight;
                ctx.fillStyle = colors.pending;
                ctx.fillRect(x + 10, currentY - pendingHeight, barWidth - 20, pendingHeight);
                currentY -= pendingHeight;
                
                // Draw overdue tasks
                const overdueHeight = (item.overdue / maxValue) * chartHeight;
                ctx.fillStyle = colors.overdue;
                ctx.fillRect(x + 10, currentY - overdueHeight, barWidth - 20, overdueHeight);
                
                // Draw week labels
                ctx.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--foreground').trim();
                ctx.font = '12px Inter';
                ctx.textAlign = 'center';
                ctx.fillText(item.week, x + barWidth / 2, height - 10);
            });
        }

        // Calendar Functions
        function generateCalendar() {
            const calendarGrid = document.getElementById('calendarGrid');
            const currentMonth = document.getElementById('currentMonth');
            
            currentMonth.textContent = currentDate.toLocaleDateString('es-ES', { 
                month: 'long', 
                year: 'numeric' 
            });
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Clear previous calendar
            calendarGrid.innerHTML = '';
            
            // Add day headers
            const dayHeaders = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            dayHeaders.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day header';
                dayElement.textContent = day;
                calendarGrid.appendChild(dayElement);
            });
            
            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();
            
            // Add previous month's trailing days
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day other-month';
                dayElement.textContent = daysInPrevMonth - i;
                calendarGrid.appendChild(dayElement);
            }
            
            // Add current month's days
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                // Highlight today
                if (year === today.getFullYear() && 
                    month === today.getMonth() && 
                    day === today.getDate()) {
                    dayElement.classList.add('today');
                }
                
                // Add task indicator based on real data
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                if (tasksCalendar[dateString]) {
                    dayElement.classList.add('has-task');
                    dayElement.title = `${tasksCalendar[dateString]} tarea(s)`;
                }
                
                calendarGrid.appendChild(dayElement);
            }
            
            // Add next month's leading days
            const totalCells = calendarGrid.children.length - 7; // Subtract headers
            const remainingCells = 42 - totalCells - 7; // 6 weeks * 7 days - total cells - headers
            for (let day = 1; day <= remainingCells; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day other-month';
                dayElement.textContent = day;
                calendarGrid.appendChild(dayElement);
            }
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        }

        // Handle login form
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            const success = await login(email, password);
            if (success) {
                document.getElementById('loginForm').reset();
            }
        });

        // Task management functions
        async function toggleTask(taskId) {
            try {
                const res = await fetch(`/tasks/${taskId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                if (res.ok) {
                    // Recargar la página para actualizar las estadísticas y la lista
                    location.reload();
                } else {
                    alert('No se pudo actualizar la tarea.');
                }
            } catch (err) {
                console.error(err);
                alert('Error al cambiar el estado de la tarea.');
            }
        }

        async function deleteTask(taskId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
                try {
                    const res = await fetch(`/tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });

                    if (res.ok) {
                        // Recargar la página para actualizar las estadísticas y la lista
                        location.reload();
                    } else {
                        alert('No se pudo eliminar la tarea.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error al eliminar la tarea.');
                }
            }
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

        // Funciones para los modales y nuevas funcionalidades

        // Funciones del modal de calculadora de notas
        function openGradeModal() {
            document.getElementById('gradeModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeGradeModal() {
            document.getElementById('gradeModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('gradeForm').reset();
        }

        function calculateGrade() {
            const grade1 = parseFloat(document.getElementById('grade1').value) || 0;
            const grade2 = parseFloat(document.getElementById('grade2').value) || 0;
            const examGrade = parseFloat(document.getElementById('examGrade').value) || 0;
            
            // Ponderación: 30% + 30% + 40%
            const finalGrade = (grade1 * 0.3) + (grade2 * 0.3) + (examGrade * 0.4);
            
            document.getElementById('finalGrade').value = finalGrade.toFixed(2);
        }

        async function saveGrade() {
            const subjectId = document.getElementById('gradeSubject').value;
            const grade1 = document.getElementById('grade1').value;
            const grade2 = document.getElementById('grade2').value;
            const examGrade = document.getElementById('examGrade').value;
            const finalGrade = document.getElementById('finalGrade').value;

            if (!subjectId || !finalGrade) {
                alert('Por favor, selecciona una asignatura y calcula la nota final.');
                return;
            }

            try {
                const response = await fetch('/grades', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        subject_id: subjectId,
                        grade1: grade1,
                        grade2: grade2,
                        exam_grade: examGrade,
                        final_grade: finalGrade
                    })
                });

                if (response.ok) {
                    alert('Nota guardada exitosamente');
                    closeGradeModal();
                    location.reload(); // Actualizar estadísticas
                } else {
                    alert('Error al guardar la nota');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la nota');
            }
        }



        // Función de exportar datos
        function exportData() {
            const data = {
                stats: {
                    totalTasks: document.getElementById('totalTasks').textContent,
                    pendingTasks: document.getElementById('pendingTasks').textContent,
                    completedTasks: document.getElementById('completedTasks').textContent,
                    averageGrade: document.getElementById('averageGrade').textContent
                },
                exportDate: new Date().toLocaleDateString('es-ES'),
                exportTime: new Date().toLocaleTimeString('es-ES')
            };

            const dataStr = JSON.stringify(data, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `studyflow-reporte-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            // Mostrar confirmación
            alert('Reporte descargado exitosamente');
        }

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const gradeModal = document.getElementById('gradeModal');
            
            if (event.target === gradeModal) {
                closeGradeModal();
            }
        }

        // Actualizar cálculo de nota en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const gradeInputs = ['grade1', 'grade2', 'examGrade'];
            gradeInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('input', calculateGrade);
                }
            });
        });

    </script>
</body>
</html>
