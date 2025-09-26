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
                    <a href="/" class="nav-link active">Dashboard</a>
                    <a href="/tasks" class="nav-link">Tareas</a>
                    <a href="/grades" class="nav-link">Notas</a>
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

            <!-- Dashboard Grid -->
            <div id="mainContent" class="dashboard-grid">
               
                <div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="card-title">Mis Tareas</h2>
                                    <p class="card-description">Organiza y gestiona tus entregas académicas</p>
                                </div>
                                <button class="btn btn-primary" onclick="openTaskModal()">
                                    ➕ Nueva Tarea
                                </button>
                            </div>
                        </div>
                        <div class="task-list" id="taskList">
                            @forelse($recentTasks as $task)
                                <div class="task-item">
                                    <div class="task-header">
                                        <div>
                                            <div class="task-title" @if($task->status === 'completed') style="text-decoration: line-through; opacity: 0.6;" @endif>{{ $task->title }}</div>
                                            <span class="task-subject">{{ $task->subject->name ?? 'Sin asignatura' }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="btn btn-success" onclick="toggleTask('{{ $task->id }}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                @if($task->status === 'completed') ↩️ @else ✅ @endif
                                            </button>
                                            <button class="btn btn-destructive" onclick="deleteTask('{{ $task->id }}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                �️
                                            </button>
                                        </div>
                                    </div>
                                    <div class="task-due" @if($task->status !== 'completed' && \Carbon\Carbon::parse($task->due_date)->isPast()) style="color: var(--destructive);" @elseif(\Carbon\Carbon::parse($task->due_date)->isToday()) style="color: var(--warning);" @endif>
                                        📅 {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}
                                        @if($task->status !== 'completed' && \Carbon\Carbon::parse($task->due_date)->isPast()) (Vencida)
                                        @elseif(\Carbon\Carbon::parse($task->due_date)->isToday()) (Hoy)
                                        @endif
                                    </div>
                                    @if($task->description)
                                        <p style="color: var(--muted-foreground); font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $task->description }}</p>
                                    @endif
                                    <span class="task-priority priority-{{ $task->priority }}">
                                        @if($task->priority === 'high') 🔴 Alta
                                        @elseif($task->priority === 'medium') 🟡 Media
                                        @else 🟢 Baja
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                                    <p>No hay tareas registradas</p>
                                    <p style="font-size: 0.875rem;">¡Crea tu primera tarea para comenzar!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Acciones Rápidas</h3>
                        </div>
                        <div class="flex flex-direction: column; gap: 0.5rem;">
                            <button class="btn btn-secondary" onclick="openGradeModal()">
                                📊 Calcular Notas
                            </button>
                            <button class="btn btn-secondary" onclick="showUpcoming()">
                                ⏰ Próximas Entregas
                            </button>
                            <button class="btn btn-secondary" onclick="exportData()">
                                📤 Exportar Datos
                            </button>
                        </div>
                    </div>

               
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Actividad Reciente</h3>
                        </div>
                        <div id="recentActivity">
                            <p class="text-center" style="color: var(--muted-foreground); padding: 2rem;">
                                No hay actividad reciente
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

        
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
    </style>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
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

        // Handle task form submission
        document.getElementById('taskForm').addEventListener('submit', async function(e) {
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (res.ok) {
                    closeTaskModal();
                    location.reload(); // Recargar para mostrar la nueva tarea
                } else {
                    const errorData = await res.json();
                    console.error('Error response:', errorData);
                    alert('Error al crear la tarea: ' + (errorData.message || 'Error desconocido'));
                }
            } catch (err) {
                console.error(err);
                alert('Error al crear la tarea.');
            }
        });
    </script>
</body>
</html>
