// StudyFlow - Academic Management System

// DOM Elements
const taskList = document.getElementById("taskList")
const taskModal = document.getElementById("taskModal")
const gradeModal = document.getElementById("gradeModal")
const taskForm = document.getElementById("taskForm")
const gradeForm = document.getElementById("gradeForm")
const taskSubjectSelect = document.getElementById("taskSubject")

// Authentication
let authToken = localStorage.getItem('auth_token')
let currentUser = null

// Headers for authenticated requests
function getAuthHeaders() {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
    if (authToken) {
        headers['Authorization'] = `Bearer ${authToken}`
    }
    
    // Add CSRF token for non-API routes
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }
    
    return headers
}

// Cargar asignaturas desde la API y poblar el select
async function loadSubjects() {
  if (!taskSubjectSelect) {
    console.log("taskSubjectSelect no encontrado")
    return;
  }
  try {
    console.log("Cargando asignaturas...")
    const res = await fetch('/api/subjects');
    const subjects = await res.json();
    taskSubjectSelect.innerHTML = '<option value="">Seleccionar asignatura</option>';
    subjects.forEach(subject => {
      taskSubjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
    });
    console.log("Asignaturas cargadas:", subjects.length)
  } catch (err) {
    if (taskSubjectSelect) {
      taskSubjectSelect.innerHTML = '<option value="">Error al cargar asignaturas</option>';
    }
    console.error("Error cargando asignaturas:", err);
  }
}

// Initialize app
document.addEventListener("DOMContentLoaded", () => {
  console.log("StudyFlow App iniciada correctamente")
  
  // Check if user is already logged in
  const storedToken = localStorage.getItem('auth_token')
  const storedUser = localStorage.getItem('current_user')
  
  if (storedToken && storedUser) {
    authToken = storedToken
    currentUser = JSON.parse(storedUser)
    console.log("Usuario encontrado en localStorage:", currentUser.email)
  }
  
  updateAuthUI()
  
  // Solo cargar tareas si hay usuario autenticado
  if (authToken && currentUser) {
    loadTasks()
    loadSubjects()
  }

  // Set default due date to tomorrow
  const dueDateInput = document.getElementById("taskDueDate")
  if (dueDateInput) {
    const tomorrow = new Date()
    tomorrow.setDate(tomorrow.getDate() + 1)
    dueDateInput.value = tomorrow.toISOString().slice(0, 16)
  }
  
  console.log("Inicialización completada")
})

// Authentication functions
async function login(email, password) {
  try {
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })

    const data = await res.json()
    
    if (data.success) {
      authToken = data.access_token
      currentUser = data.user
      localStorage.setItem('auth_token', authToken)
      localStorage.setItem('current_user', JSON.stringify(currentUser))
      
      console.log('Login exitoso para:', currentUser.email)
      showNotification('Login exitoso', 'success')
      
      // Primero actualizar UI, luego cargar tareas
      updateAuthUI()
      
      // Pequeña pausa para que la UI se actualice
      setTimeout(() => {
        loadTasks()
      }, 100)
      
      return true
    } else {
      showNotification(data.message || 'Error de login', 'error')
      return false
    }
  } catch (error) {
    console.error('Error en login:', error)
    showNotification('Error de conexión', 'error')
    return false
  }
}

async function logout() {
  try {
    if (authToken) {
      await fetch('/api/logout', {
        method: 'POST',
        headers: getAuthHeaders()
      })
    }
  } catch (error) {
    console.error('Error en logout:', error)
  } finally {
    authToken = null
    currentUser = null
    localStorage.removeItem('auth_token')
    localStorage.removeItem('current_user')
    updateAuthUI()
    showNotification('Sesión cerrada', 'success')
  }
}

function updateAuthUI() {
  // Update UI based on authentication status
  const loginSection = document.getElementById('loginSection')
  const userSection = document.getElementById('userSection')
  const statsSection = document.getElementById('statsSection')
  const mainContent = document.getElementById('mainContent')
  
  console.log("Actualizando UI. Usuario actual:", currentUser)
  
  if (currentUser && authToken) {
    console.log("Mostrando UI autenticada")
    // Ocultar login y mostrar contenido autenticado
    if (loginSection) {
      loginSection.style.display = 'none'
    }
    if (userSection) {
      userSection.style.display = 'block'
      userSection.innerHTML = `
        <div class="card">
          <div class="card-content" style="padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-weight: 500;">👋 Bienvenido, ${currentUser.first_name || currentUser.name}!</span>
              <button class="btn btn-secondary" onclick="logout()" style="font-size: 0.875rem; padding: 0.5rem 1rem;">Cerrar Sesión</button>
            </div>
          </div>
        </div>
      `
    }
    if (statsSection) {
      statsSection.style.display = 'grid'
    }
    if (mainContent) {
      mainContent.style.display = 'grid'
    }
  } else {
    console.log("Mostrando UI no autenticada")
    if (loginSection) {
      loginSection.style.display = 'block'
    }
    if (userSection) {
      userSection.style.display = 'none'
    }
    if (statsSection) {
      statsSection.style.display = 'none'
    }
    if (mainContent) {
      mainContent.style.display = 'none'
    }
  }
}

// Task Management Functions usando API
async function loadTasks() {
  console.log('=== INICIO LOAD TASKS ===')
  console.log('Cargando tareas...')
  console.log('Token disponible:', !!authToken)
  console.log('Token valor:', authToken)
  console.log('Usuario actual:', currentUser)
  
  const taskListElement = document.getElementById("taskList")
  console.log('TaskList element found:', !!taskListElement)
  console.log('TaskList element:', taskListElement)
  
  if (!authToken) {
    console.log('No hay token de autenticación')
    if (taskListElement) {
      taskListElement.innerHTML = `
        <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
          <p>Debes iniciar sesión para ver tus tareas</p>
        </div>
      `
    }
    return
  }

  if (!taskListElement) {
    console.log('No se encontró el elemento taskList')
    return
  }

  try {
    console.log('Haciendo petición a /api/tasks')
    const res = await fetch('/api/tasks', {
      headers: getAuthHeaders()
    })
    
    console.log('Response status:', res.status)
    
    if (res.status === 401) {
      showNotification('Sesión expirada', 'error')
      logout()
      return
    }
    
    const tasks = await res.json()
    console.log('Tareas obtenidas:', tasks.length, tasks)
    
    taskListElement.innerHTML = ""

    if (tasks.length === 0) {
      taskListElement.innerHTML = `
              <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                  <p>No hay tareas registradas</p>
                  <p style="font-size: 0.875rem;">¡Crea tu primera tarea para comenzar!</p>
              </div>
          `
      // Actualizar estadísticas
      updateStats([])
      return
    }

    // Ordenar por fecha de entrega
    tasks.sort((a, b) => new Date(a.due_date) - new Date(b.due_date))

    tasks.forEach((task) => {
      const taskElement = createTaskElement(task)
      taskListElement.appendChild(taskElement)
    })
    
    // Actualizar estadísticas
    updateStats(tasks)
    
  } catch (error) {
    console.error('Error al cargar tareas:', error)
    if (taskListElement) {
      taskListElement.innerHTML = `<div style='color: red;'>Error al cargar tareas: ${error.message}</div>`
    }
  }
}

// Función para actualizar estadísticas
function updateStats(tasks) {
  console.log('Actualizando estadísticas con', tasks.length, 'tareas')
  
  const totalTasks = tasks.length
  const pendingTasks = tasks.filter(t => t.status === 'pending' || t.status === 'in_progress').length
  const completedTasks = tasks.filter(t => t.status === 'completed').length
  
  const totalTasksElement = document.getElementById("totalTasks")
  const pendingTasksElement = document.getElementById("pendingTasks")
  const completedTasksElement = document.getElementById("completedTasks")
  
  if (totalTasksElement) totalTasksElement.textContent = totalTasks
  if (pendingTasksElement) pendingTasksElement.textContent = pendingTasks
  if (completedTasksElement) completedTasksElement.textContent = completedTasks
  
  console.log('Estadísticas actualizadas:', { totalTasks, pendingTasks, completedTasks })
}

// Función de test temporal
async function testLoadTasks() {
  console.log('=== TEST LOAD TASKS ===')
  console.log('Token actual:', authToken)
  console.log('Usuario actual:', currentUser)
  
  const taskListElement = document.getElementById("taskList")
  
  if (!taskListElement) {
    console.log('No se encontró taskList')
    return
  }

  // Test 1: Sin autenticación
  try {
    console.log('=== TEST 1: Sin autenticación ===')
    const res1 = await fetch('/debug-tasks')
    const allTasks = await res1.json()
    console.log('Todas las tareas (sin auth):', allTasks.length)
    
    // Test 2: Con autenticación (si existe)
    if (authToken) {
      console.log('=== TEST 2: Con autenticación ===')
      const res2 = await fetch('/api/tasks', {
        headers: getAuthHeaders()
      })
      console.log('Response status:', res2.status)
      
      if (res2.ok) {
        const userTasks = await res2.json()
        console.log('Tareas del usuario autenticado:', userTasks.length)
        
        // Mostrar las tareas del usuario
        taskListElement.innerHTML = ""
        if (userTasks.length === 0) {
          taskListElement.innerHTML = `<div>Usuario no tiene tareas</div>`
        } else {
          userTasks.forEach((task) => {
            const taskElement = document.createElement('div')
            taskElement.className = 'task-item'
            taskElement.innerHTML = `
              <h4>${task.title}</h4>
              <p>Materia: ${task.subject ? task.subject.name : 'Sin materia'}</p>
              <p>Estado: ${task.status}</p>
              <p>Fecha: ${task.due_date}</p>
            `
            taskListElement.appendChild(taskElement)
          })
        }
      } else {
        console.log('Error en API autenticada:', await res2.text())
        taskListElement.innerHTML = `<div>Error de autenticación</div>`
      }
    } else {
      console.log('No hay token, mostrando todas las tareas')
      taskListElement.innerHTML = ""
      allTasks.forEach((task) => {
        const taskElement = document.createElement('div')
        taskElement.className = 'task-item'
        taskElement.innerHTML = `
          <h4>${task.title}</h4>
          <p>Usuario: ${task.user ? task.user.email : 'Sin usuario'}</p>
          <p>Materia: ${task.subject ? task.subject.name : 'Sin materia'}</p>
          <p>Estado: ${task.status}</p>
        `
        taskListElement.appendChild(taskElement)
      })
    }
    
  } catch (error) {
    console.error('Error en test:', error)
    taskListElement.innerHTML = `<div>Error: ${error.message}</div>`
  }
}

// Llamar test después de 2 segundos
setTimeout(() => {
  console.log('Ejecutando test de carga de tareas...')
  testLoadTasks()
}, 2000)

// Modal functions
function openTaskModal() {
  console.log('Abriendo modal de tarea')
  const modal = document.getElementById('taskModal')
  if (modal) {
    modal.style.display = 'block'
    // Cargar asignaturas cuando se abre el modal
    loadSubjects()
  } else {
    console.error('Modal de tarea no encontrado')
  }
}

function closeTaskModal() {
  const modal = document.getElementById('taskModal')
  if (modal) {
    modal.style.display = 'none'
    // Limpiar formulario
    const form = document.getElementById('taskForm')
    if (form) {
      form.reset()
    }
  }
}

function openGradeModal() {
  const modal = document.getElementById('gradeModal')
  if (modal) {
    modal.style.display = 'block'
  }
}

function closeGradeModal() {
  const modal = document.getElementById('gradeModal')
  if (modal) {
    modal.style.display = 'none'
  }
}

function createTaskElement(task) {
  const taskDiv = document.createElement("div")
  taskDiv.className = "task-item"

  const dueDate = new Date(task.due_date)
  const now = new Date()
  const isOverdue = dueDate < now && task.status !== 'completed'
  const isToday = dueDate.toDateString() === now.toDateString()

  let dueDateClass = ""
  if (isOverdue) dueDateClass = 'style="color: var(--destructive);"'
  else if (isToday) dueDateClass = 'style="color: var(--warning);"'

  const subjectName = task.subject && task.subject.name ? task.subject.name : 'Sin asignatura'
  taskDiv.innerHTML = `
        <div class="task-header">
            <div>
                <div class="task-title" ${task.status === 'completed' ? 'style="text-decoration: line-through; opacity: 0.6;"' : ""}>${task.title}</div>
                <span class="task-subject">${subjectName}</span>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-success" onclick="toggleTask('${task.id}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                    ${task.status === 'completed' ? '↩️' : '✅'}
                </button>
                <button class="btn btn-destructive" onclick="deleteTask('${task.id}')" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                    🗑️
                </button>
            </div>
        </div>
        <div class="task-due" ${dueDateClass}>
            📅 ${dueDate.toLocaleDateString("es-ES", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit" })} ${isOverdue ? '(Vencida)' : isToday ? '(Hoy)' : ''}
        </div>
        ${task.description ? `<p style="color: var(--muted-foreground); font-size: 0.875rem; margin-bottom: 0.5rem;">${task.description}</p>` : ''}
        <span class="task-priority priority-${task.priority}">
            ${task.priority === 'high' ? '🔴 Alta' : task.priority === 'medium' ? '🟡 Media' : '🟢 Baja'}
        </span>
    `
  return taskDiv
}
  document.getElementById("totalTasks").textContent = tasks.length
  document.getElementById("pendingTasks").textContent = tasks.filter((t) => t.status === 'pending' || t.status === 'in_progress').length
  document.getElementById("completedTasks").textContent = tasks.filter((t) => t.status === 'completed').length

// Recent Activity
function loadRecentActivity() {
  const activities = db.getActivities()
  const activityContainer = document.getElementById("recentActivity")

  if (activities.length === 0) {
    activityContainer.innerHTML = `
            <p class="text-center" style="color: var(--muted-foreground); padding: 2rem;">
                No hay actividad reciente
            </p>
        `
    return
  }

  activityContainer.innerHTML = activities
    .map(
      (activity) => `
        <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border); font-size: 0.875rem;">
            <p>${activity.message}</p>
            <small style="color: var(--muted-foreground);">
                ${formatDate(new Date(activity.timestamp))}
            </small>
        </div>
    `,
    )
    .join("")
}

// Utility Functions
function showUpcoming() {
  const tasks = db.getTasks().filter((t) => !t.completed)
  const upcoming = tasks.filter((t) => {
    const dueDate = new Date(t.dueDate)
    const now = new Date()
    const diffTime = dueDate - now
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    return diffDays <= 7 && diffDays >= 0
  })

  if (upcoming.length === 0) {
    alert("No tienes entregas próximas en los próximos 7 días")
    return
  }

  const message = upcoming.map((t) => `• ${t.title} (${t.subject}) - ${formatDate(new Date(t.dueDate))}`).join("\n")

  alert(`Próximas entregas (7 días):\n\n${message}`)
}

function exportData() {
  const data = {
    tasks: db.getTasks(),
    grades: db.getGrades(),
    exportDate: new Date().toISOString(),
  }

  const blob = new Blob([JSON.stringify(data, null, 2)], { type: "application/json" })
  const url = URL.createObjectURL(blob)
  const a = document.createElement("a")
  a.href = url
  a.download = `studyflow-backup-${new Date().toISOString().split("T")[0]}.json`
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)

  showNotification("Datos exportados exitosamente", "success")
}

function showNotification(message, type = "info") {
  // Simple notification system
  const notification = document.createElement("div")
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background-color: ${type === "success" ? "var(--success)" : type === "error" ? "var(--destructive)" : "var(--primary)"};
        color: white;
        border-radius: var(--radius);
        z-index: 1001;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    `
  notification.textContent = message

  document.body.appendChild(notification)

  setTimeout(() => {
    notification.remove()
  }, 3000)
}

// Close modals when clicking outside
window.addEventListener("click", (e) => {
  if (e.target === taskModal) {
    closeTaskModal()
  }
  if (e.target === gradeModal) {
    closeGradeModal()
  }
})

// Auto-calculate grade when inputs change
;["grade1", "grade2", "examGrade"].forEach((id) => {
  document.getElementById(id).addEventListener("input", calculateGrade)
})

// Envío de formulario de nueva tarea por AJAX
if (taskForm) {
  taskForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Obtener datos del formulario
    const title = document.getElementById('taskTitle').value;
    const subject_id = document.getElementById('taskSubject').value;
    const description = document.getElementById('taskDescription').value;
    const due_date = document.getElementById('taskDueDate').value;
    const priority = document.getElementById('taskPriority').value;

    // Validar datos mínimos
    if (!title || !subject_id || !due_date || !priority) {
      alert('Completa todos los campos obligatorios.');
      return;
    }

    try {
      const res = await fetch('/api/tasks', {
        method: 'POST',
        headers: getAuthHeaders(),
        body: JSON.stringify({
          title,
          subject_id,
          description,
          due_date,
          priority
        })
      });

      if (res.status === 401) {
        showNotification('Sesión expirada', 'error');
        logout();
        return;
      }

      if (res.ok) {
        await loadTasks();
        closeTaskModal();
        showNotification('Tarea guardada correctamente', 'success');
      } else {
        const error = await res.json();
        showNotification('Error al guardar tarea: ' + (error.message || 'Verifica los datos.'), 'error');
      }
    } catch (err) {
      console.error(err);
      showNotification('Error de red al guardar tarea.', 'error');
    }
  });
}
