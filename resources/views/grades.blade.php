<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyFlow - Mis Notas</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
</head>
<body>
    
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">📚 StudyFlow</div>
                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('tasks.index') }}" class="nav-link">Tareas</a>
                    <a href="{{ route('grades.index') }}" class="nav-link active">Notas</a>
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
            <!-- Welcome Section -->
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">📊 Gestión de Notas Académicas</h1>
                    <p class="card-description">
                        Administra tus calificaciones, información académica y malla curricular.
                    </p>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="card" style="margin-bottom: 0; border-bottom: none; border-radius: var(--radius) var(--radius) 0 0;">
                <div class="card-header" style="border-bottom: 1px solid var(--border);">
                    <div class="tab-navigation">
                        <button class="tab-btn active" onclick="showTab('notas')" id="notasTab">
                            📝 Notas
                        </button>
                        <button class="tab-btn" onclick="showTab('academico')" id="academicoTab">
                            🎓 Académico
                        </button>
                        <button class="tab-btn" onclick="showTab('malla')" id="mallaTab">
                            🗂️ Malla Curricular
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="card" style="margin-top: 0; border-top: none; border-radius: 0 0 var(--radius) var(--radius);">
                
                <!-- Tab 1: Notas -->
                <div id="notasContent" class="tab-content active">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="card-title">Mis Calificaciones</h2>
                                <p class="card-description">Registro de notas por asignatura</p>
                            </div>
                            <button class="btn btn-primary" onclick="openGradeModal()">
                                ➕ Agregar Nota
                            </button>
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div id="gradesTable">
                            <div id="loading" class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                                Cargando notas...
                            </div>
                        </div>
                        <!-- Pagination for Notas -->
                        <div class="pagination-container" id="gradesPagination" style="display: none;">
                            <div class="pagination">
                                <button class="btn btn-sm btn-secondary" onclick="changeGradesPage(-1)" id="gradesPrevBtn">‹ Anterior</button>
                                <span id="gradesPageInfo">Página 1 de 1</span>
                                <button class="btn btn-sm btn-secondary" onclick="changeGradesPage(1)" id="gradesNextBtn">Siguiente ›</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Académico -->
                <div id="academicoContent" class="tab-content">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="card-title">Información Académica</h2>
                                <p class="card-description">Detalles de asignaturas y rendimiento</p>
                            </div>
                            <button class="btn btn-primary" onclick="openSubjectModal()">
                                ➕ Agregar Asignatura
                            </button>
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div id="subjectsTable">
                            <div class="loading-academic text-center" style="padding: 2rem; color: var(--muted-foreground);">
                                Cargando información académica...
                            </div>
                        </div>
                        <!-- Pagination for Academic -->
                        <div class="pagination-container" id="academicPagination" style="display: none;">
                            <div class="pagination">
                                <button class="btn btn-sm btn-secondary" onclick="changeAcademicPage(-1)" id="academicPrevBtn">‹ Anterior</button>
                                <span id="academicPageInfo">Página 1 de 1</span>
                                <button class="btn btn-sm btn-secondary" onclick="changeAcademicPage(1)" id="academicNextBtn">Siguiente ›</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Malla Curricular -->
                <div id="mallaContent" class="tab-content">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="card-title">Malla Curricular Interactiva</h2>
                                <p class="card-description">Visualiza tu progreso académico</p>
                            </div>
                            <div>
                                <input type="file" id="excelFile" accept=".xlsx,.xls" style="display: none;" onchange="loadExcelFile(this)">
                                <button class="btn btn-secondary" onclick="document.getElementById('excelFile').click()">
                                    📤 Cargar Excel
                                </button>
                                <button class="btn btn-primary" onclick="generateSampleMalla()">
                                    🔄 Generar Ejemplo
                                </button>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div id="mallaVisualization">
                            <div class="text-center" style="padding: 3rem; color: var(--muted-foreground);">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                                <h3>Malla Curricular</h3>
                                <p>Carga un archivo Excel con tu malla curricular o genera un ejemplo para comenzar.</p>
                                <div style="margin-top: 1.5rem;">
                                    <button class="btn btn-outline-primary" onclick="showMallaInstructions()">
                                        📖 Ver Instrucciones
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Grade Modal -->
    <div class="modal" id="gradeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Agregar Nota</h3>
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
                    <label class="form-label">Tipo de Evaluación</label>
                    <select class="form-select" id="evaluationType" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="Parcial 1">Parcial 1</option>
                        <option value="Parcial 2">Parcial 2</option>
                        <option value="Examen Final">Examen Final</option>
                        <option value="Laboratorio">Laboratorio</option>
                        <option value="Proyecto">Proyecto</option>
                        <option value="Tarea">Tarea</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nota (1.0 - 7.0)</label>
                    <input type="number" class="form-input" id="gradeValue" min="1.0" max="7.0" step="0.1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Porcentaje (%)</label>
                    <input type="number" class="form-input" id="gradeWeight" min="1" max="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Evaluación</label>
                    <input type="date" class="form-input" id="evaluationDate" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Nota</button>
                    <button type="button" class="btn btn-secondary" onclick="closeGradeModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Subject Modal -->
    <div class="modal" id="subjectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Agregar Asignatura</h3>
                <button class="close-btn" onclick="closeSubjectModal()">&times;</button>
            </div>
            <form id="subjectForm">
                <div class="form-group">
                    <label class="form-label">Nombre de la Asignatura</label>
                    <input type="text" class="form-input" id="subjectName" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Código</label>
                    <input type="text" class="form-input" id="subjectCode" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Créditos</label>
                    <input type="number" class="form-input" id="subjectCredits" min="1" max="10" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Semestre</label>
                    <select class="form-select" id="subjectSemester" required>
                        <option value="">Seleccionar semestre</option>
                        <option value="1">1° Semestre</option>
                        <option value="2">2° Semestre</option>
                        <option value="3">3° Semestre</option>
                        <option value="4">4° Semestre</option>
                        <option value="5">5° Semestre</option>
                        <option value="6">6° Semestre</option>
                        <option value="7">7° Semestre</option>
                        <option value="8">8° Semestre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Profesor</label>
                    <input type="text" class="form-input" id="subjectProfessor">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Asignatura</button>
                    <button type="button" class="btn btn-secondary" onclick="closeSubjectModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions Modal -->
    <div class="modal" id="instructionsModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">📖 Instrucciones para Cargar Excel</h3>
                <button class="close-btn" onclick="closeInstructionsModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div class="instructions-content">
                    <div class="instruction-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Formato del Archivo Excel</h4>
                            <p>El archivo debe contener las siguientes columnas:</p>
                            <div class="column-list">
                                <div class="column-item">
                                    <span class="column-name">Asignatura/Materia</span>
                                    <span class="column-desc">Nombre de la asignatura</span>
                                </div>
                                <div class="column-item">
                                    <span class="column-name">Codigo/Code</span>
                                    <span class="column-desc">Código de la asignatura</span>
                                </div>
                                <div class="column-item">
                                    <span class="column-name">Semestre</span>
                                    <span class="column-desc">Número del semestre (1, 2, 3, etc.)</span>
                                </div>
                                <div class="column-item">
                                    <span class="column-name">Creditos/Credits</span>
                                    <span class="column-desc">Número de créditos</span>
                                </div>
                                <div class="column-item">
                                    <span class="column-name">Estado/Status</span>
                                    <span class="column-desc">completed, current, o pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Guardar el Archivo</h4>
                            <p>Asegúrate de guardar el archivo en formato <strong>.xlsx</strong> o <strong>.xls</strong></p>
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Subir el Archivo</h4>
                            <p>Haz clic en el botón <strong>"Cargar Excel"</strong> para subir tu archivo</p>
                        </div>
                    </div>
                    
                    <div class="instruction-tip">
                        <div class="tip-icon">💡</div>
                        <div class="tip-content">
                            <strong>Consejo:</strong> Utiliza la opción <strong>"Generar Ejemplo"</strong> para ver cómo debe verse tu archivo Excel.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="margin-top: 2rem;">
                    <button class="btn btn-primary" onclick="closeInstructionsModal()">
                        ✓ Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>

<style>
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

/* Tab Navigation Styles */
.tab-navigation {
    display: flex;
    gap: 0.5rem;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    background-color: transparent;
    color: var(--muted-foreground);
    border-radius: var(--radius);
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.tab-btn:hover {
    background-color: var(--muted);
    color: var(--foreground);
}

.tab-btn.active {
    background-color: var(--primary);
    color: var(--primary-foreground);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Table Styles */
.grades-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.grades-table th,
.grades-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.grades-table th {
    background-color: var(--muted);
    font-weight: 600;
    color: var(--foreground);
}

.grades-table tr:hover {
    background-color: var(--accent);
}

.grade-value {
    font-weight: 700;
    font-size: 1.1rem;
}

.grade-excellent { color: #059669; }
.grade-good { color: #d97706; }
.grade-sufficient { color: #dc2626; }
.grade-insufficient { color: #991b1b; }

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

.pagination {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Malla Curricular Styles */
.malla-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.semester-column {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    background-color: var(--card);
}

.semester-header {
    font-weight: 600;
    text-align: center;
    padding: 0.5rem;
    background-color: var(--primary);
    color: var(--primary-foreground);
    border-radius: var(--radius);
    margin-bottom: 1rem;
}

.subject-item {
    background-color: var(--accent);
    padding: 0.75rem;
    border-radius: var(--radius);
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    border-left: 4px solid transparent;
}

.subject-item:hover {
    background-color: var(--muted);
    transform: translateX(2px);
}

.subject-item.completed {
    border-left-color: var(--success, #059669);
    background-color: rgba(5, 150, 105, 0.1);
}

.subject-item.current {
    border-left-color: var(--primary);
    background-color: rgba(59, 130, 246, 0.1);
}

.subject-item.pending {
    border-left-color: var(--muted-foreground);
}

.subject-name {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.subject-details {
    font-size: 0.75rem;
    color: var(--muted-foreground);
}

/* Progress Bar */
.progress-bar {
    width: 100%;
    height: 8px;
    background-color: var(--muted);
    border-radius: 4px;
    overflow: hidden;
    margin: 1rem 0;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary);
    transition: width 0.3s ease;
}

/* Instructions Modal Styles */
.instructions-content {
    line-height: 1.6;
}

.instruction-step {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    align-items: flex-start;
}

.step-number {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark, #2563eb));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.step-content h4 {
    margin: 0 0 0.5rem 0;
    color: var(--foreground);
    font-size: 1.1rem;
    font-weight: 600;
}

.step-content p {
    margin: 0 0 0.75rem 0;
    color: var(--muted-foreground);
}

.column-list {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    margin-top: 0.75rem;
}

.column-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border);
}

.column-item:last-child {
    border-bottom: none;
}

.column-name {
    font-weight: 600;
    color: var(--primary);
    font-family: 'Courier New', monospace;
    background: rgba(59, 130, 246, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
}

.column-desc {
    color: var(--muted-foreground);
    font-size: 0.9rem;
}

.instruction-tip {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: var(--radius);
    padding: 1rem;
    margin-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}

.tip-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.tip-content {
    color: var(--foreground);
    font-size: 0.95rem;
}

.tip-content strong {
    color: var(--primary);
}

.modal-footer {
    display: flex;
    justify-content: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

/* Responsive adjustments for instructions modal */
@media (max-width: 768px) {
    .instruction-step {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .step-number {
        align-self: flex-start;
    }
    
    .column-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// Variables globales
let allGrades = [];
let allSubjects = [];
let mallaData = [];
let currentGradesPage = 1;
let currentAcademicPage = 1;
const itemsPerPage = 10;

document.addEventListener('DOMContentLoaded', async () => {
    await loadGrades();
    await loadSubjects();
    showTab('notas'); // Mostrar tab por defecto
});

// Tab Navigation
function showTab(tabName) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Desactivar todos los botones
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar contenido activo
    document.getElementById(tabName + 'Content').classList.add('active');
    document.getElementById(tabName + 'Tab').classList.add('active');
    
    // Cargar datos específicos del tab
    if (tabName === 'notas') {
        displayGrades();
    } else if (tabName === 'academico') {
        displaySubjects();
    } else if (tabName === 'malla') {
        // La malla se carga cuando se sube el Excel
    }
}

// Grades Management
async function loadGrades() {
    try {
        const res = await fetch('/grades');
        allGrades = await res.json();
        
        // Agregar algunos datos de ejemplo si no hay datos
        if (allGrades.length === 0) {
            allGrades = [
                {
                    id: 1,
                    evaluation_type: 'Parcial 1',
                    grade: 6.5,
                    weight: 30,
                    evaluation_date: '2025-09-15',
                    subject: { name: 'Base de Datos', code: 'BD101' }
                },
                {
                    id: 2,
                    evaluation_type: 'Laboratorio',
                    grade: 7.0,
                    weight: 20,
                    evaluation_date: '2025-09-20',
                    subject: { name: 'Programación', code: 'PROG101' }
                },
                {
                    id: 3,
                    evaluation_type: 'Examen Final',
                    grade: 5.8,
                    weight: 40,
                    evaluation_date: '2025-09-22',
                    subject: { name: 'Matemáticas', code: 'MAT101' }
                }
            ];
        }
        
    } catch (error) {
        console.error('Error loading grades:', error);
        allGrades = [];
    }
}

function displayGrades() {
    const container = document.getElementById('gradesTable');
    const loading = document.getElementById('loading');
    
    if (loading) loading.remove();
    
    if (allGrades.length === 0) {
        container.innerHTML = `
            <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                <p>No hay notas registradas</p>
                <p style="font-size: 0.875rem;">¡Agrega tu primera nota para comenzar!</p>
            </div>
        `;
        return;
    }

    // Paginación
    const totalPages = Math.ceil(allGrades.length / itemsPerPage);
    const startIndex = (currentGradesPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentGrades = allGrades.slice(startIndex, endIndex);

    let html = `
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Evaluación</th>
                    <th>Nota</th>
                    <th>Porcentaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
    `;

    currentGrades.forEach(grade => {
        const gradeClass = grade.grade >= 6.0 ? 'grade-excellent' :
                          grade.grade >= 5.0 ? 'grade-good' :
                          grade.grade >= 4.0 ? 'grade-sufficient' : 'grade-insufficient';
        
        html += `
            <tr>
                <td>
                    <div style="font-weight: 600;">${grade.subject.name}</div>
                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">${grade.subject.code || ''}</div>
                </td>
                <td>${grade.evaluation_type}</td>
                <td><span class="grade-value ${gradeClass}">${grade.grade}</span></td>
                <td>${grade.weight}%</td>
                <td>${new Date(grade.evaluation_date).toLocaleDateString('es-ES')}</td>
                <td>
                    ${grade.grade >= 4.0 ? 
                        '<span style="color: #059669;">✅ Aprobado</span>' : 
                        '<span style="color: #dc2626;">❌ Reprobado</span>'
                    }
                </td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    container.innerHTML = html;

    // Mostrar paginación si hay más de una página
    const pagination = document.getElementById('gradesPagination');
    if (totalPages > 1) {
        pagination.style.display = 'block';
        document.getElementById('gradesPageInfo').textContent = `Página ${currentGradesPage} de ${totalPages}`;
        document.getElementById('gradesPrevBtn').disabled = currentGradesPage === 1;
        document.getElementById('gradesNextBtn').disabled = currentGradesPage === totalPages;
    } else {
        pagination.style.display = 'none';
    }
}

function changeGradesPage(direction) {
    const totalPages = Math.ceil(allGrades.length / itemsPerPage);
    currentGradesPage += direction;
    
    if (currentGradesPage < 1) currentGradesPage = 1;
    if (currentGradesPage > totalPages) currentGradesPage = totalPages;
    
    displayGrades();
}

// Subjects Management
async function loadSubjects() {
    // Datos de ejemplo para asignaturas
    allSubjects = [
        {
            id: 1,
            name: 'Base de Datos',
            code: 'BD101',
            credits: 4,
            semester: 3,
            professor: 'Dr. García',
            status: 'current',
            average: 6.2
        },
        {
            id: 2,
            name: 'Programación',
            code: 'PROG101',
            credits: 5,
            semester: 3,
            professor: 'Ing. López',
            status: 'current',
            average: 6.8
        },
        {
            id: 3,
            name: 'Matemáticas',
            code: 'MAT101',
            credits: 4,
            semester: 2,
            professor: 'Prof. Rodríguez',
            status: 'completed',
            average: 5.9
        }
    ];
}

function displaySubjects() {
    const container = document.getElementById('subjectsTable');
    const loading = container.querySelector('.loading-academic');
    
    if (loading) loading.remove();
    
    if (allSubjects.length === 0) {
        container.innerHTML = `
            <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                <p>No hay asignaturas registradas</p>
                <p style="font-size: 0.875rem;">¡Agrega tu primera asignatura para comenzar!</p>
            </div>
        `;
        return;
    }

    // Paginación
    const totalPages = Math.ceil(allSubjects.length / itemsPerPage);
    const startIndex = (currentAcademicPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentSubjects = allSubjects.slice(startIndex, endIndex);

    let html = `
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Código</th>
                    <th>Créditos</th>
                    <th>Semestre</th>
                    <th>Profesor</th>
                    <th>Promedio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
    `;

    currentSubjects.forEach(subject => {
        const statusText = subject.status === 'completed' ? '✅ Completada' :
                          subject.status === 'current' ? '🔄 Cursando' : '⏳ Pendiente';
        
        const averageClass = subject.average >= 6.0 ? 'grade-excellent' :
                            subject.average >= 5.0 ? 'grade-good' :
                            subject.average >= 4.0 ? 'grade-sufficient' : 'grade-insufficient';
        
        html += `
            <tr>
                <td>
                    <div style="font-weight: 600;">${subject.name}</div>
                </td>
                <td>${subject.code}</td>
                <td>${subject.credits}</td>
                <td>${subject.semester}°</td>
                <td>${subject.professor}</td>
                <td><span class="grade-value ${averageClass}">${subject.average || 'N/A'}</span></td>
                <td>${statusText}</td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    container.innerHTML = html;

    // Mostrar paginación si hay más de una página
    const pagination = document.getElementById('academicPagination');
    if (totalPages > 1) {
        pagination.style.display = 'block';
        document.getElementById('academicPageInfo').textContent = `Página ${currentAcademicPage} de ${totalPages}`;
        document.getElementById('academicPrevBtn').disabled = currentAcademicPage === 1;
        document.getElementById('academicNextBtn').disabled = currentAcademicPage === totalPages;
    } else {
        pagination.style.display = 'none';
    }
}

function changeAcademicPage(direction) {
    const totalPages = Math.ceil(allSubjects.length / itemsPerPage);
    currentAcademicPage += direction;
    
    if (currentAcademicPage < 1) currentAcademicPage = 1;
    if (currentAcademicPage > totalPages) currentAcademicPage = totalPages;
    
    displaySubjects();
}

// Malla Curricular Management
function loadExcelFile(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const jsonData = XLSX.utils.sheet_to_json(worksheet);

            mallaData = processMallaData(jsonData);
            displayMalla();
            
        } catch (error) {
            console.error('Error procesando Excel:', error);
            alert('Error al procesar el archivo Excel. Verifica el formato.');
        }
    };
    reader.readAsArrayBuffer(file);
}

function processMallaData(data) {
    // Procesar datos del Excel y organizarlos por semestre
    const processed = {};
    
    data.forEach(row => {
        const semester = row.Semestre || row.semestre || 1;
        if (!processed[semester]) {
            processed[semester] = [];
        }
        
        processed[semester].push({
            name: row.Asignatura || row.asignatura || row.Materia || row.materia,
            code: row.Codigo || row.codigo || row.Code || row.code,
            credits: row.Creditos || row.creditos || row.Credits || row.credits || 0,
            status: row.Estado || row.estado || row.Status || row.status || 'pending'
        });
    });
    
    return processed;
}

function generateSampleMalla() {
    // Generar malla de ejemplo
    mallaData = {
        1: [
            { name: 'Introducción a la Programación', code: 'PROG101', credits: 5, status: 'completed' },
            { name: 'Matemática I', code: 'MAT101', credits: 4, status: 'completed' },
            { name: 'Física I', code: 'FIS101', credits: 4, status: 'completed' },
            { name: 'Inglés I', code: 'ING101', credits: 3, status: 'completed' }
        ],
        2: [
            { name: 'Programación Orientada a Objetos', code: 'PROG201', credits: 5, status: 'completed' },
            { name: 'Matemática II', code: 'MAT201', credits: 4, status: 'completed' },
            { name: 'Física II', code: 'FIS201', credits: 4, status: 'completed' },
            { name: 'Inglés II', code: 'ING201', credits: 3, status: 'completed' }
        ],
        3: [
            { name: 'Base de Datos', code: 'BD301', credits: 4, status: 'current' },
            { name: 'Estructuras de Datos', code: 'ED301', credits: 5, status: 'current' },
            { name: 'Estadística', code: 'EST301', credits: 4, status: 'current' },
            { name: 'Inglés III', code: 'ING301', credits: 3, status: 'current' }
        ],
        4: [
            { name: 'Ingeniería de Software', code: 'IS401', credits: 5, status: 'pending' },
            { name: 'Sistemas Operativos', code: 'SO401', credits: 4, status: 'pending' },
            { name: 'Redes de Computadores', code: 'RC401', credits: 4, status: 'pending' },
            { name: 'Metodología de Investigación', code: 'MI401', credits: 3, status: 'pending' }
        ]
    };
    
    displayMalla();
}

function displayMalla() {
    const container = document.getElementById('mallaVisualization');
    
    if (Object.keys(mallaData).length === 0) {
        container.innerHTML = `
            <div class="text-center" style="padding: 3rem; color: var(--muted-foreground);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                <h3>Malla Curricular</h3>
                <p>Carga un archivo Excel con tu malla curricular o genera un ejemplo para comenzar.</p>
            </div>
        `;
        return;
    }

    // Calcular progreso
    let totalSubjects = 0;
    let completedSubjects = 0;
    
    Object.values(mallaData).forEach(semester => {
        totalSubjects += semester.length;
        completedSubjects += semester.filter(s => s.status === 'completed').length;
    });
    
    const progressPercentage = totalSubjects > 0 ? Math.round((completedSubjects / totalSubjects) * 100) : 0;

    let html = `
        <div style="margin-bottom: 2rem;">
            <div class="flex justify-between items-center" style="margin-bottom: 1rem;">
                <h4>Progreso Académico</h4>
                <div style="font-weight: 600;">${completedSubjects}/${totalSubjects} asignaturas (${progressPercentage}%)</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: ${progressPercentage}%"></div>
            </div>
        </div>
        
        <div class="malla-grid">
    `;

    // Ordenar semestres
    const sortedSemesters = Object.keys(mallaData).sort((a, b) => parseInt(a) - parseInt(b));
    
    sortedSemesters.forEach(semester => {
        html += `
            <div class="semester-column">
                <div class="semester-header">${semester}° Semestre</div>
        `;
        
        mallaData[semester].forEach(subject => {
            html += `
                <div class="subject-item ${subject.status}" onclick="showSubjectDetails('${subject.code}')">
                    <div class="subject-name">${subject.name}</div>
                    <div class="subject-details">${subject.code} • ${subject.credits} créditos</div>
                </div>
            `;
        });
        
        html += '</div>';
    });

    html += '</div>';
    container.innerHTML = html;
}

function showSubjectDetails(code) {
    // Encontrar la asignatura
    let subject = null;
    Object.values(mallaData).forEach(semester => {
        const found = semester.find(s => s.code === code);
        if (found) subject = found;
    });
    
    if (subject) {
        const statusText = subject.status === 'completed' ? 'Completada' :
                          subject.status === 'current' ? 'Cursando' : 'Pendiente';
        
        alert(`Asignatura: ${subject.name}\nCódigo: ${subject.code}\nCréditos: ${subject.credits}\nEstado: ${statusText}`);
    }
}

function showMallaInstructions() {
    document.getElementById('instructionsModal').classList.add('active');
}

function closeInstructionsModal() {
    document.getElementById('instructionsModal').classList.remove('active');
}

// Modal Functions
function openGradeModal() {
    document.getElementById('gradeModal').classList.add('active');
    document.getElementById('gradeSubject').focus();
}

function closeGradeModal() {
    document.getElementById('gradeModal').classList.remove('active');
    document.getElementById('gradeForm').reset();
}

function openSubjectModal() {
    document.getElementById('subjectModal').classList.add('active');
    document.getElementById('subjectName').focus();
}

function closeSubjectModal() {
    document.getElementById('subjectModal').classList.remove('active');
    document.getElementById('subjectForm').reset();
}

// Form Handlers
document.getElementById('gradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newGrade = {
        id: Date.now(),
        evaluation_type: document.getElementById('evaluationType').value,
        grade: parseFloat(document.getElementById('gradeValue').value),
        weight: parseInt(document.getElementById('gradeWeight').value),
        evaluation_date: document.getElementById('evaluationDate').value,
        subject: {
            name: document.getElementById('gradeSubject').value,
            code: document.getElementById('gradeSubject').value.substring(0, 3).toUpperCase() + '101'
        }
    };
    
    allGrades.push(newGrade);
    displayGrades();
    closeGradeModal();
    
    // Mostrar notificación
    alert('Nota agregada exitosamente');
});

document.getElementById('subjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newSubject = {
        id: Date.now(),
        name: document.getElementById('subjectName').value,
        code: document.getElementById('subjectCode').value,
        credits: parseInt(document.getElementById('subjectCredits').value),
        semester: parseInt(document.getElementById('subjectSemester').value),
        professor: document.getElementById('subjectProfessor').value,
        status: 'current',
        average: null
    };
    
    allSubjects.push(newSubject);
    displaySubjects();
    closeSubjectModal();
    
    // Mostrar notificación
    alert('Asignatura agregada exitosamente');
});

// Cerrar modales al hacer clic fuera
window.addEventListener('click', (e) => {
    const gradeModal = document.getElementById('gradeModal');
    const subjectModal = document.getElementById('subjectModal');
    const instructionsModal = document.getElementById('instructionsModal');
    
    if (e.target === gradeModal) {
        closeGradeModal();
    }
    
    if (e.target === subjectModal) {
        closeSubjectModal();
    }
    
    if (e.target === instructionsModal) {
        closeInstructionsModal();
    }
});

// Cerrar modales con tecla ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const gradeModal = document.getElementById('gradeModal');
        const subjectModal = document.getElementById('subjectModal');
        const instructionsModal = document.getElementById('instructionsModal');
        
        if (gradeModal.classList.contains('active')) {
            closeGradeModal();
        } else if (subjectModal.classList.contains('active')) {
            closeSubjectModal();
        } else if (instructionsModal.classList.contains('active')) {
            closeInstructionsModal();
        }
    }
});

// Funciones de tema (para mantener compatibilidad)
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    const themeIcon = document.querySelector('.theme-icon');
    themeIcon.textContent = document.documentElement.classList.contains('dark') ? '☀️' : '🌙';
}
</script>

</body>
</html>