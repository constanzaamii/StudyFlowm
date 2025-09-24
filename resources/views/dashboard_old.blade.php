@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
           
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">¡Bienvenido a StudyFlow! 🎓</h1>
                    <p class="card-description">
                        Tu sistema de gestión académica para organizar tareas, calcular notas y hacer seguimiento de tu progreso universitario.
                    </p>
                </div>
            </div>

            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="totalTasks">0</div>
                    <div class="stat-label">Tareas Totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="pendingTasks">0</div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="averageGrade">0.0</div>
                    <div class="stat-label">Promedio General</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="completedTasks">0</div>
                    <div class="stat-label">Completadas</div>
                </div>
            </div>

            
            <div class="dashboard-grid">
               
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
                        <option value="Programación">Programación</option>
                        <option value="Base de Datos">Base de Datos</option>
                        <option value="Matemáticas">Matemáticas</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Física">Física</option>
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
</div>

<script>
// Actualizar estadísticas con datos del servidor
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($stats))
        document.getElementById('totalTasks').textContent = '{{ $stats["totalTasks"] }}';
        document.getElementById('pendingTasks').textContent = '{{ $stats["pendingTasks"] }}';
        document.getElementById('completedTasks').textContent = '{{ $stats["completedTasks"] }}';
        document.getElementById('averageGrade').textContent = '{{ $stats["averageGrade"] }}';
    @endif
});
</script>
@endsection
