// StudyFlow - Academic Management System

// DOM Elements
const taskList = document.getElementById("taskList")
const taskModal = document.getElementById("taskModal")
const gradeModal = document.getElementById("gradeModal")
const taskForm = document.getElementById("taskForm")
const gradeForm = document.getElementById("gradeForm")
const taskSubjectSelect = document.getElementById("taskSubject")

// Cargar asignaturas desde la API y poblar el select
async function loadSubjects() {
  if (!taskSubjectSelect) return;
  try {
    const res = await fetch('/api/subjects');
    const subjects = await res.json();
    taskSubjectSelect.innerHTML = '<option value="">Seleccionar asignatura</option>';
    subjects.forEach(subject => {
      taskSubjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
    });
  } catch (err) {
    taskSubjectSelect.innerHTML = '<option value="">Error al cargar asignaturas</option>';
    console.error(err);
  }
}

// Initialize app
document.addEventListener("DOMContentLoaded", () => {
  console.log("StudyFlow App iniciada correctamente")
  loadTasks()
  // updateStats() // Si tienes estadísticas, actualízalas con datos de la API
  // loadRecentActivity() // Si tienes actividad, actualízala con datos reales

  // Set default due date to tomorrow
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  document.getElementById("taskDueDate").value = tomorrow.toISOString().slice(0, 16)
  
  console.log("Inicialización completada")
})

// Task Management Functions usando API
async function loadTasks() {
  try {
    const res = await fetch('/api/tasks')
    const tasks = await res.json()
    taskList.innerHTML = ""

    if (tasks.length === 0) {
      taskList.innerHTML = `
              <div class="text-center" style="padding: 2rem; color: var(--muted-foreground);">
                  <p>No hay tareas registradas</p>
                  <p style="font-size: 0.875rem;">¡Crea tu primera tarea para comenzar!</p>
              </div>
          `
      return
    }

    // Ordenar por fecha de entrega
    tasks.sort((a, b) => new Date(a.due_date) - new Date(b.due_date))

    tasks.forEach((task) => {
      const taskElement = createTaskElement(task)
      taskList.appendChild(taskElement)
    })
  } catch (error) {
    taskList.innerHTML = `<div style='color: red;'>Error al cargar tareas</div>`
    console.error(error)
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
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          title,
          subject_id,
          description,
          due_date,
          priority
        })
      });

      if (res.ok) {
        await loadTasks();
        closeTaskModal();
        alert('Tarea guardada correctamente');
      } else {
        const error = await res.json();
        alert('Error al guardar tarea: ' + (error.message || 'Verifica los datos.'));
      }
    } catch (err) {
      console.error(err);
      alert('Error de red al guardar tarea.');
    }
  });
}
