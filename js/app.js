// StudyFlow - Academic Management System
// Database simulation using localStorage

class StudyFlowDB {
  constructor() {
    this.initDB()
  }

  initDB() {
    if (!localStorage.getItem("studyflow_tasks")) {
      localStorage.setItem("studyflow_tasks", JSON.stringify([]))
    }
    if (!localStorage.getItem("studyflow_grades")) {
      localStorage.setItem("studyflow_grades", JSON.stringify([]))
    }
    if (!localStorage.getItem("studyflow_activity")) {
      localStorage.setItem("studyflow_activity", JSON.stringify([]))
    }
  }

  // Tasks CRUD Operations
  getTasks() {
    return JSON.parse(localStorage.getItem("studyflow_tasks") || "[]")
  }

  addTask(task) {
    const tasks = this.getTasks()
    task.id = Date.now().toString()
    task.createdAt = new Date().toISOString()
    task.completed = false
    tasks.push(task)
    localStorage.setItem("studyflow_tasks", JSON.stringify(tasks))
    this.addActivity(`Nueva tarea creada: ${task.title}`)
    return task
  }

  updateTask(taskId, updates) {
    const tasks = this.getTasks()
    const index = tasks.findIndex((t) => t.id === taskId)
    if (index !== -1) {
      tasks[index] = { ...tasks[index], ...updates }
      localStorage.setItem("studyflow_tasks", JSON.stringify(tasks))
      this.addActivity(`Tarea actualizada: ${tasks[index].title}`)
      return tasks[index]
    }
    return null
  }

  deleteTask(taskId) {
    const tasks = this.getTasks()
    const task = tasks.find((t) => t.id === taskId)
    const filteredTasks = tasks.filter((t) => t.id !== taskId)
    localStorage.setItem("studyflow_tasks", JSON.stringify(filteredTasks))
    if (task) {
      this.addActivity(`Tarea eliminada: ${task.title}`)
    }
  }

  // Grades CRUD Operations
  getGrades() {
    return JSON.parse(localStorage.getItem("studyflow_grades") || "[]")
  }

  addGrade(grade) {
    const grades = this.getGrades()
    grade.id = Date.now().toString()
    grade.createdAt = new Date().toISOString()
    grades.push(grade)
    localStorage.setItem("studyflow_grades", JSON.stringify(grades))
    this.addActivity(`Nueva nota registrada: ${grade.subject} - ${grade.finalGrade}`)
    return grade
  }

  // Activity Log
  addActivity(message) {
    const activities = JSON.parse(localStorage.getItem("studyflow_activity") || "[]")
    activities.unshift({
      id: Date.now().toString(),
      message,
      timestamp: new Date().toISOString(),
    })
    // Keep only last 10 activities
    if (activities.length > 10) {
      activities.splice(10)
    }
    localStorage.setItem("studyflow_activity", JSON.stringify(activities))
  }

  getActivities() {
    return JSON.parse(localStorage.getItem("studyflow_activity") || "[]")
  }
}

// Initialize database
const db = new StudyFlowDB()

// DOM Elements
const taskList = document.getElementById("taskList")
const taskModal = document.getElementById("taskModal")
const gradeModal = document.getElementById("gradeModal")
const taskForm = document.getElementById("taskForm")
const gradeForm = document.getElementById("gradeForm")

// Bootstrap initialization
const bootstrap = window.bootstrap

// Initialize app
document.addEventListener("DOMContentLoaded", () => {
  loadTasks()
  updateStats()
  loadRecentActivity()

  // Set default due date to tomorrow
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  document.getElementById("taskDueDate").value = tomorrow.toISOString().slice(0, 16)
})

// Task Management Functions
function loadTasks() {
  const tasks = db.getTasks()
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

  // Sort tasks by due date
  tasks.sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate))

  tasks.forEach((task) => {
    const taskElement = createTaskElement(task)
    taskList.appendChild(taskElement)
  })
}

function createTaskElement(task) {
  const taskDiv = document.createElement("div")
  taskDiv.className = "task-item"

  const dueDate = new Date(task.dueDate)
  const now = new Date()
  const isOverdue = dueDate < now && !task.completed
  const isToday = dueDate.toDateString() === now.toDateString()

  let dueDateClass = ""
  if (isOverdue) dueDateClass = 'class="text-danger"'
  else if (isToday) dueDateClass = 'class="text-warning"'

  taskDiv.innerHTML = `
        <div class="task-header">
            <div>
                <div class="task-title ${task.completed ? "task-completed" : ""}">${task.title}</div>
                <span class="task-subject">${task.subject}</span>
            </div>
            <div class="task-actions">
                <button class="btn btn-sm ${task.completed ? "btn-warning" : "btn-success"}" onclick="toggleTask('${task.id}')">
                    <i class="bi ${task.completed ? "bi-arrow-counterclockwise" : "bi-check-circle"}"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteTask('${task.id}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="task-due" ${dueDateClass}>
            <i class="bi bi-calendar"></i> ${formatDate(dueDate)} ${isOverdue ? "(Vencida)" : isToday ? "(Hoy)" : ""}
        </div>
        ${task.description ? `<p class="text-muted small mb-2">${task.description}</p>` : ""}
        <span class="task-priority priority-${task.priority}">
            ${task.priority === "high" ? "🔴 Alta" : task.priority === "medium" ? "🟡 Media" : "🟢 Baja"}
        </span>
    `

  return taskDiv
}

function formatDate(date) {
  return date.toLocaleDateString("es-ES", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  })
}

// Modal Functions
function openTaskModal() {
  bootstrap.Modal.getOrCreateInstance(taskModal).show()
  setTimeout(() => {
    document.getElementById("taskTitle").focus()
  }, 150)
}

function closeTaskModal() {
  bootstrap.Modal.getOrCreateInstance(taskModal).hide()
  taskForm.reset()
}

function openGradeModal() {
  bootstrap.Modal.getOrCreateInstance(gradeModal).show()
}

function closeGradeModal() {
  bootstrap.Modal.getOrCreateInstance(gradeModal).hide()
  gradeForm.reset()
}

// Task Form Handler
taskForm.addEventListener("submit", (e) => {
  e.preventDefault()

  const task = {
    title: document.getElementById("taskTitle").value,
    subject: document.getElementById("taskSubject").value,
    description: document.getElementById("taskDescription").value,
    dueDate: document.getElementById("taskDueDate").value,
    priority: document.getElementById("taskPriority").value,
  }

  db.addTask(task)
  loadTasks()
  updateStats()
  loadRecentActivity()
  closeTaskModal()

  // Show success message
  showNotification("Tarea creada exitosamente", "success")
})

// Task Actions
function toggleTask(taskId) {
  const task = db.getTasks().find((t) => t.id === taskId)
  if (task) {
    db.updateTask(taskId, { completed: !task.completed })
    loadTasks()
    updateStats()
    loadRecentActivity()
    showNotification(task.completed ? "Tarea marcada como pendiente" : "Tarea completada", "success")
  }
}

function deleteTask(taskId) {
  if (confirm("¿Estás seguro de que quieres eliminar esta tarea?")) {
    db.deleteTask(taskId)
    loadTasks()
    updateStats()
    loadRecentActivity()
    showNotification("Tarea eliminada", "success")
  }
}

// Grade Calculator
function calculateGrade() {
  const grade1 = Number.parseFloat(document.getElementById("grade1").value) || 0
  const grade2 = Number.parseFloat(document.getElementById("grade2").value) || 0
  const examGrade = Number.parseFloat(document.getElementById("examGrade").value) || 0

  const finalGrade = grade1 * 0.3 + grade2 * 0.3 + examGrade * 0.4
  document.getElementById("finalGrade").value = finalGrade.toFixed(2)
}

function saveGrade() {
  const subject = document.getElementById("gradeSubject").value
  const grade1 = Number.parseFloat(document.getElementById("grade1").value)
  const grade2 = Number.parseFloat(document.getElementById("grade2").value)
  const examGrade = Number.parseFloat(document.getElementById("examGrade").value)
  const finalGrade = Number.parseFloat(document.getElementById("finalGrade").value)

  if (!subject || !finalGrade) {
    showNotification("Por favor completa todos los campos", "error")
    return
  }

  const grade = {
    subject,
    grade1,
    grade2,
    examGrade,
    finalGrade,
  }

  db.addGrade(grade)
  updateStats()
  loadRecentActivity()
  closeGradeModal()
  showNotification("Nota guardada exitosamente", "success")
}

// Statistics Update
function updateStats() {
  const tasks = db.getTasks()
  const grades = db.getGrades()

  document.getElementById("totalTasks").textContent = tasks.length
  document.getElementById("pendingTasks").textContent = tasks.filter((t) => !t.completed).length
  document.getElementById("completedTasks").textContent = tasks.filter((t) => t.completed).length

  if (grades.length > 0) {
    const average = grades.reduce((sum, grade) => sum + grade.finalGrade, 0) / grades.length
    document.getElementById("averageGrade").textContent = average.toFixed(1)
  } else {
    document.getElementById("averageGrade").textContent = "0.0"
  }
}

// Recent Activity
function loadRecentActivity() {
  const activities = db.getActivities()
  const activityContainer = document.getElementById("recentActivity")

  if (activities.length === 0) {
    activityContainer.innerHTML = `
            <p class="text-center text-muted py-4 mb-0">
                No hay actividad reciente
            </p>
        `
    return
  }

  activityContainer.innerHTML = activities
    .map(
      (activity) => `
        <div class="border-bottom py-2">
            <p class="mb-1 small">${activity.message}</p>
            <small class="text-muted">
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
    showNotification("No tienes entregas próximas en los próximos 7 días", "info")
    return
  }

  const message = upcoming.map((t) => `• ${t.title} (${t.subject}) - ${formatDate(new Date(t.dueDate))}`).join("\n")

  // Create a custom modal for upcoming tasks
  const upcomingModal = document.createElement("div")
  upcomingModal.className = "modal fade"
  upcomingModal.innerHTML = `
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Próximas Entregas (7 días)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="list-group list-group-flush">
            ${upcoming
              .map(
                (t) => `
              <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1">${t.title}</h6>
                  <small class="text-muted">${t.subject}</small>
                </div>
                <p class="mb-1 small text-muted">
                  <i class="bi bi-calendar"></i> ${formatDate(new Date(t.dueDate))}
                </p>
              </div>
            `,
              )
              .join("")}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  `

  document.body.appendChild(upcomingModal)
  const modal = new bootstrap.Modal(upcomingModal)
  modal.show()

  upcomingModal.addEventListener("hidden.bs.modal", () => {
    upcomingModal.remove()
  })
}

// Export Data
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

// Notification System
function showNotification(message, type = "info") {
  const notification = document.createElement("div")

  let bgClass = "bg-primary"
  let iconClass = "bi-info-circle"

  if (type === "success") {
    bgClass = "bg-success"
    iconClass = "bi-check-circle"
  } else if (type === "error") {
    bgClass = "bg-danger"
    iconClass = "bi-exclamation-circle"
  }

  notification.className = `alert ${bgClass} text-white d-flex align-items-center`
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        border: none;
    `

  notification.innerHTML = `
    <i class="bi ${iconClass} me-2"></i>
    <span>${message}</span>
    <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.remove()"></button>
  `

  document.body.appendChild(notification)

  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove()
    }
  }, 4000)
}
// Auto-calculate grade when inputs change
;["grade1", "grade2", "examGrade"].forEach((id) => {
  document.getElementById(id).addEventListener("input", calculateGrade)
})
