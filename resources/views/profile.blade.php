<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyFlow - Mi Perfil</title>
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
                    <a href="{{ route('grades.index') }}" class="nav-link">Notas</a>
                    <a href="{{ route('profile') }}" class="nav-link active">👤 Perfil</a>
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
                    <h1 class="card-title">👤 Mi Perfil</h1>
                    <p class="card-description">
                        Gestiona tu información personal y configuración de cuenta.
                    </p>
                </div>
            </div>

            <!-- Profile Navigation Tabs -->
            <div class="card" style="margin-bottom: 0; border-bottom: none; border-radius: var(--radius) var(--radius) 0 0;">
                <div class="card-header" style="border-bottom: 1px solid var(--border);">
                    <div class="tab-navigation">
                        <button class="tab-btn active" onclick="showProfileTab('personal')" id="personalTab">
                            🧑‍💼 Información Personal
                        </button>
                        <button class="tab-btn" onclick="showProfileTab('academic')" id="academicTab">
                            🎓 Datos Académicos
                        </button>
                        <button class="tab-btn" onclick="showProfileTab('settings')" id="settingsTab">
                            ⚙️ Configuración
                        </button>
                        <button class="tab-btn" onclick="showProfileTab('security')" id="securityTab">
                            🔒 Seguridad
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="card" style="margin-top: 0; border-top: none; border-radius: 0 0 var(--radius) var(--radius);">
                
                <!-- Tab 1: Información Personal -->
                <div id="personalContent" class="tab-content active">
                    <div class="card-header">
                        <h2 class="card-title">Información Personal</h2>
                        <p class="card-description">Actualiza tus datos personales</p>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="profile-section">
                            <div class="profile-avatar-section">
                                <div class="profile-avatar">
                                    <img src="{{ asset('placeholder-user.jpg') }}" alt="Avatar" id="avatarImage">
                                </div>
                                <div class="avatar-actions">
                                    <button class="btn btn-outline-primary" onclick="changeAvatar()">
                                        📷 Cambiar Foto
                                    </button>
                                    <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                                </div>
                            </div>
                            
                            <form id="personalInfoForm" class="profile-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-input" id="firstName" value="Juan" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" class="form-input" id="lastName" value="Pérez" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-input" id="email" value="juan.perez@estudiante.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Teléfono</label>
                                        <input type="tel" class="form-input" id="phone" value="+56 9 1234 5678">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-input" id="birthDate" value="2000-01-15">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Género</label>
                                        <select class="form-select" id="gender">
                                            <option value="">Seleccionar</option>
                                            <option value="M" selected>Masculino</option>
                                            <option value="F">Femenino</option>
                                            <option value="O">Otro</option>
                                            <option value="N">Prefiero no decir</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Dirección</label>
                                    <textarea class="form-input" id="address" rows="3" placeholder="Ingresa tu dirección completa">Av. Providencia 1234, Santiago, Chile</textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        💾 Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="resetPersonalForm()">
                                        🔄 Restablecer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Datos Académicos -->
                <div id="academicContent" class="tab-content">
                    <div class="card-header">
                        <h2 class="card-title">Datos Académicos</h2>
                        <p class="card-description">Información sobre tu carrera y estudios</p>
                    </div>
                    <div style="padding: 1.5rem;">
                        <form id="academicInfoForm" class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Institución</label>
                                    <input type="text" class="form-input" id="institution" value="Universidad de Chile" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Código de Estudiante</label>
                                    <input type="text" class="form-input" id="studentId" value="2020-12345" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Carrera</label>
                                    <input type="text" class="form-input" id="career" value="Ingeniería en Informática" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Semestre Actual</label>
                                    <select class="form-select" id="currentSemester">
                                        <option value="">Seleccionar</option>
                                        <option value="1">1er Semestre</option>
                                        <option value="2">2do Semestre</option>
                                        <option value="3">3er Semestre</option>
                                        <option value="4">4to Semestre</option>
                                        <option value="5" selected>5to Semestre</option>
                                        <option value="6">6to Semestre</option>
                                        <option value="7">7mo Semestre</option>
                                        <option value="8">8vo Semestre</option>
                                        <option value="9">9no Semestre</option>
                                        <option value="10">10mo Semestre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Año de Ingreso</label>
                                    <input type="number" class="form-input" id="admissionYear" value="2020" min="2000" max="2030">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Promedio Actual</label>
                                    <input type="number" class="form-input" id="currentGPA" value="6.2" min="1.0" max="7.0" step="0.1" readonly>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Modalidad de Estudio</label>
                                    <select class="form-select" id="studyMode">
                                        <option value="presencial" selected>Presencial</option>
                                        <option value="online">Online</option>
                                        <option value="hibrido">Híbrido</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Estado Académico</label>
                                    <select class="form-select" id="academicStatus">
                                        <option value="regular" selected>Regular</option>
                                        <option value="condicional">Condicional</option>
                                        <option value="destacado">Destacado</option>
                                        <option value="egresado">Egresado</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    💾 Guardar Cambios
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetAcademicForm()">
                                    🔄 Restablecer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab 3: Configuración -->
                <div id="settingsContent" class="tab-content">
                    <div class="card-header">
                        <h2 class="card-title">Configuración</h2>
                        <p class="card-description">Personaliza tu experiencia en StudyFlow</p>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="settings-sections">
                            <!-- Preferencias de Notificaciones -->
                            <div class="settings-section">
                                <h3 class="settings-title">🔔 Notificaciones</h3>
                                <div class="settings-options">
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Recordatorios de Tareas</label>
                                            <p class="setting-desc">Recibe notificaciones sobre tareas próximas a vencer</p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="taskReminders" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Notificaciones de Notas</label>
                                            <p class="setting-desc">Alertas cuando se agreguen nuevas calificaciones</p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="gradeNotifications" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Resumen Semanal</label>
                                            <p class="setting-desc">Recibe un resumen de tu progreso cada semana</p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="weeklySummary">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferencias de Visualización -->
                            <div class="settings-section">
                                <h3 class="settings-title">🎨 Apariencia</h3>
                                <div class="settings-options">
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Tema</label>
                                            <p class="setting-desc">Selecciona el tema de color preferido</p>
                                        </div>
                                        <select class="form-select" id="themePreference" style="max-width: 150px;">
                                            <option value="auto" selected>Automático</option>
                                            <option value="light">Claro</option>
                                            <option value="dark">Oscuro</option>
                                        </select>
                                    </div>
                                    
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Idioma</label>
                                            <p class="setting-desc">Idioma de la interfaz</p>
                                        </div>
                                        <select class="form-select" id="language" style="max-width: 150px;">
                                            <option value="es" selected>Español</option>
                                            <option value="en">English</option>
                                            <option value="pt">Português</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferencias de Dashboard -->
                            <div class="settings-section">
                                <h3 class="settings-title">📊 Dashboard</h3>
                                <div class="settings-options">
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Mostrar Estadísticas Avanzadas</label>
                                            <p class="setting-desc">Incluir gráficos y métricas detalladas</p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="advancedStats" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="setting-item">
                                        <div class="setting-info">
                                            <label class="setting-label">Vista Compacta</label>
                                            <p class="setting-desc">Mostrar más información en menos espacio</p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="compactView">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions" style="margin-top: 2rem;">
                            <button type="button" class="btn btn-primary" onclick="saveSettings()">
                                💾 Guardar Configuración
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetSettings()">
                                🔄 Restablecer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Seguridad -->
                <div id="securityContent" class="tab-content">
                    <div class="card-header">
                        <h2 class="card-title">Seguridad</h2>
                        <p class="card-description">Gestiona la seguridad de tu cuenta</p>
                    </div>
                    <div style="padding: 1.5rem;">
                        <!-- Cambiar Contraseña -->
                        <div class="security-section">
                            <h3 class="settings-title">🔐 Cambiar Contraseña</h3>
                            <form id="passwordForm" class="profile-form">
                                <div class="form-group">
                                    <label class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-input" id="currentPassword" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Nueva Contraseña</label>
                                        <input type="password" class="form-input" id="newPassword" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirmar Contraseña</label>
                                        <input type="password" class="form-input" id="confirmPassword" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    🔒 Cambiar Contraseña
                                </button>
                            </form>
                        </div>

                        <!-- Autenticación de Dos Factores -->
                        <div class="security-section">
                            <h3 class="settings-title">🛡️ Autenticación de Dos Factores (2FA)</h3>
                            <div class="security-option">
                                <div class="security-info">
                                    <p>Agrega una capa extra de seguridad a tu cuenta</p>
                                    <div class="security-status">
                                        <span class="status-badge status-inactive">Inactivo</span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary" onclick="setup2FA()">
                                    🔧 Configurar 2FA
                                </button>
                            </div>
                        </div>

                        <!-- Sesiones Activas -->
                        <div class="security-section">
                            <h3 class="settings-title">💻 Sesiones Activas</h3>
                            <div class="sessions-list">
                                <div class="session-item">
                                    <div class="session-info">
                                        <div class="session-device">🖥️ Windows - Chrome</div>
                                        <div class="session-details">
                                            <span>IP: 192.168.1.100</span>
                                            <span>Última actividad: Hace 5 minutos</span>
                                            <span class="session-current">Sesión actual</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="session-item">
                                    <div class="session-info">
                                        <div class="session-device">📱 Android - Chrome Mobile</div>
                                        <div class="session-details">
                                            <span>IP: 192.168.1.105</span>
                                            <span>Última actividad: Hace 2 horas</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-destructive" onclick="terminateSession('mobile')">
                                        🚫 Cerrar
                                    </button>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-destructive" onclick="terminateAllSessions()">
                                🚪 Cerrar Todas las Sesiones
                            </button>
                        </div>

                        <!-- Zona de Peligro -->
                        <div class="security-section danger-zone">
                            <h3 class="settings-title">⚠️ Zona de Peligro</h3>
                            <div class="danger-actions">
                                <div class="danger-item">
                                    <div class="danger-info">
                                        <h4>Exportar Datos</h4>
                                        <p>Descarga una copia de todos tus datos</p>
                                    </div>
                                    <button class="btn btn-outline-secondary" onclick="exportData()">
                                        📥 Exportar
                                    </button>
                                </div>
                                
                                <div class="danger-item">
                                    <div class="danger-info">
                                        <h4>Eliminar Cuenta</h4>
                                        <p>Elimina permanentemente tu cuenta y todos los datos</p>
                                    </div>
                                    <button class="btn btn-destructive" onclick="deleteAccount()">
                                        🗑️ Eliminar Cuenta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

/* Profile Styles */
.profile-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

/* Settings Styles */
.settings-sections {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.settings-section {
    padding: 1.5rem;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.settings-title {
    margin: 0 0 1rem 0;
    color: var(--foreground);
    font-size: 1.1rem;
    font-weight: 600;
}

.settings-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border);
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info {
    flex: 1;
}

.setting-label {
    font-weight: 500;
    color: var(--foreground);
    display: block;
    margin-bottom: 0.25rem;
}

.setting-desc {
    color: var(--muted-foreground);
    font-size: 0.9rem;
    margin: 0;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
    cursor: pointer;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--muted);
    transition: 0.3s;
    border-radius: 28px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

input:checked + .toggle-slider {
    background-color: var(--primary);
}

input:checked + .toggle-slider:before {
    transform: translateX(22px);
}

/* Security Styles */
.security-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.security-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.security-info p {
    margin: 0 0 0.5rem 0;
    color: var(--muted-foreground);
}

.security-status {
    margin-top: 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: rgb(34, 197, 94);
}

.status-inactive {
    background: rgba(156, 163, 175, 0.1);
    color: rgb(156, 163, 175);
}

.sessions-list {
    margin-bottom: 1rem;
}

.session-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--muted/5);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 0.5rem;
}

.session-device {
    font-weight: 500;
    color: var(--foreground);
    margin-bottom: 0.25rem;
}

.session-details {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    font-size: 0.9rem;
    color: var(--muted-foreground);
}

.session-current {
    color: var(--primary);
    font-weight: 500;
}

.danger-zone {
    border-color: rgba(239, 68, 68, 0.3);
    background: rgba(239, 68, 68, 0.05);
}

.danger-zone .settings-title {
    color: rgb(239, 68, 68);
}

.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.danger-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(239, 68, 68, 0.1);
}

.danger-item:last-child {
    border-bottom: none;
}

.danger-info h4 {
    margin: 0 0 0.25rem 0;
    color: var(--foreground);
    font-size: 1rem;
}

.danger-info p {
    margin: 0;
    color: var(--muted-foreground);
    font-size: 0.9rem;
}

.btn-destructive {
    background-color: rgb(239, 68, 68);
    color: white;
    border: 1px solid rgb(239, 68, 68);
}

.btn-destructive:hover {
    background-color: rgb(220, 38, 38);
    border-color: rgb(220, 38, 38);
}

.btn-outline-destructive {
    color: rgb(239, 68, 68);
    border: 1px solid rgb(239, 68, 68);
    background: transparent;
}

.btn-outline-destructive:hover {
    background-color: rgb(239, 68, 68);
    color: white;
}

/* Tab Navigation Styles */
.tab-navigation {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
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
    white-space: nowrap;
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

/* Responsive Design */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .setting-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .security-option,
    .danger-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .session-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .tab-navigation {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
// Tab functionality
function showProfileTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => btn.classList.remove('active'));
    
    // Show selected tab content
    document.getElementById(tabName + 'Content').classList.add('active');
    document.getElementById(tabName + 'Tab').classList.add('active');
}

// Avatar functionality
function changeAvatar() {
    document.getElementById('avatarInput').click();
}

document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Show success message
        showNotification('Foto de perfil actualizada exitosamente', 'success');
    }
});

// Form submissions
document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Simulate saving personal info
    showNotification('Información personal guardada exitosamente', 'success');
});

document.getElementById('academicInfoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Simulate saving academic info
    showNotification('Datos académicos guardados exitosamente', 'success');
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        showNotification('Las contraseñas no coinciden', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showNotification('La contraseña debe tener al menos 8 caracteres', 'error');
        return;
    }
    
    // Simulate password change
    showNotification('Contraseña cambiada exitosamente', 'success');
    this.reset();
});

// Reset functions
function resetPersonalForm() {
    document.getElementById('personalInfoForm').reset();
    // Reset to default values
    document.getElementById('firstName').value = 'Juan';
    document.getElementById('lastName').value = 'Pérez';
    document.getElementById('email').value = 'juan.perez@estudiante.com';
    document.getElementById('phone').value = '+56 9 1234 5678';
    document.getElementById('birthDate').value = '2000-01-15';
    document.getElementById('gender').value = 'M';
    document.getElementById('address').value = 'Av. Providencia 1234, Santiago, Chile';
    showNotification('Formulario restablecido', 'info');
}

function resetAcademicForm() {
    document.getElementById('academicInfoForm').reset();
    // Reset to default values
    document.getElementById('institution').value = 'Universidad de Chile';
    document.getElementById('studentId').value = '2020-12345';
    document.getElementById('career').value = 'Ingeniería en Informática';
    document.getElementById('currentSemester').value = '5';
    document.getElementById('admissionYear').value = '2020';
    document.getElementById('currentGPA').value = '6.2';
    document.getElementById('studyMode').value = 'presencial';
    document.getElementById('academicStatus').value = 'regular';
    showNotification('Formulario restablecido', 'info');
}

// Settings functions
function saveSettings() {
    // Simulate saving settings
    showNotification('Configuración guardada exitosamente', 'success');
}

function resetSettings() {
    // Reset all settings to default
    document.getElementById('taskReminders').checked = true;
    document.getElementById('gradeNotifications').checked = true;
    document.getElementById('weeklySummary').checked = false;
    document.getElementById('themePreference').value = 'auto';
    document.getElementById('language').value = 'es';
    document.getElementById('advancedStats').checked = true;
    document.getElementById('compactView').checked = false;
    showNotification('Configuración restablecida', 'info');
}

// Security functions
function setup2FA() {
    showNotification('Funcionalidad de 2FA en desarrollo', 'info');
}

function terminateSession(sessionId) {
    showNotification('Sesión terminada exitosamente', 'success');
    // Remove session from UI (in a real app, this would be handled by the backend)
}

function terminateAllSessions() {
    if (confirm('¿Estás seguro de que quieres cerrar todas las sesiones? Tendrás que iniciar sesión nuevamente.')) {
        showNotification('Todas las sesiones han sido cerradas', 'success');
    }
}

function exportData() {
    showNotification('Preparando exportación de datos...', 'info');
    // Simulate data export
    setTimeout(() => {
        showNotification('Datos exportados exitosamente', 'success');
    }, 2000);
}

function deleteAccount() {
    const confirmation = prompt('Para confirmar la eliminación de tu cuenta, escribe "ELIMINAR CUENTA":');
    if (confirmation === 'ELIMINAR CUENTA') {
        showNotification('Cuenta eliminada exitosamente', 'success');
        // In a real app, this would redirect to a goodbye page
    } else if (confirmation !== null) {
        showNotification('Confirmación incorrecta. Cuenta no eliminada.', 'error');
    }
}

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${getNotificationIcon(type)}</span>
            <span class="notification-message">${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    // Add notification styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const styles = document.createElement('style');
        styles.id = 'notification-styles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.5rem;
                background: var(--card);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                animation: slideInRight 0.3s ease;
                max-width: 400px;
                word-wrap: break-word;
            }
            
            .notification-success { border-left: 4px solid #22c55e; }
            .notification-error { border-left: 4px solid #ef4444; }
            .notification-info { border-left: 4px solid #3b82f6; }
            .notification-warning { border-left: 4px solid #f59e0b; }
            
            .notification-content {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .notification-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: var(--muted-foreground);
                margin-left: 1rem;
            }
            
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(styles);
    }
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return '✅';
        case 'error': return '❌';
        case 'warning': return '⚠️';
        default: return 'ℹ️';
    }
}

// Theme functionality
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    const themeIcon = document.querySelector('.theme-icon');
    themeIcon.textContent = document.documentElement.classList.contains('dark') ? '☀️' : '🌙';
    
    // Update theme preference
    const themePreference = document.getElementById('themePreference');
    if (themePreference) {
        themePreference.value = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }
}

// Theme preference change handler
document.getElementById('themePreference').addEventListener('change', function(e) {
    const theme = e.target.value;
    const themeIcon = document.querySelector('.theme-icon');
    
    document.documentElement.classList.remove('dark');
    
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        themeIcon.textContent = '☀️';
    } else if (theme === 'light') {
        themeIcon.textContent = '🌙';
    } else {
        // Auto theme - you could implement system preference detection here
        themeIcon.textContent = '🌙';
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set initial theme
    const themeIcon = document.querySelector('.theme-icon');
    themeIcon.textContent = document.documentElement.classList.contains('dark') ? '☀️' : '🌙';
});
</script>

</body>
</html>