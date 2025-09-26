<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StudyFlow - Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5; 
        }
        .card { 
            background: white; 
            padding: 20px; 
            margin: 10px 0; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .form-input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: white;
            color: black;
        }
        .form-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        .form-input:disabled {
            background-color: #e9ecef;
            opacity: 1;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <h1>StudyFlow Dashboard</h1>
    
    <div id="loginSection" class="card">
        <h2>Iniciar Sesión</h2>
        <form id="loginForm">
            <label class="form-label" for="loginEmail">Email:</label>
            <input type="email" id="loginEmail" name="email" class="form-input" autocomplete="email" required>
            
            <label class="form-label" for="loginPassword">Contraseña:</label>
            <input type="password" id="loginPassword" name="password" class="form-input" autocomplete="current-password" required>
            
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
        
        <div style="margin-top: 20px; padding: 10px; background: #e9ecef; border-radius: 4px;">
            <strong>Usuarios de prueba:</strong><br>
            Email: juan@studyflow.com | Password: password123<br>
            Email: maria@studyflow.com | Password: password123
        </div>
    </div>

    <div id="userSection" class="card" style="display: none;">
        <!-- Usuario info will be inserted here -->
    </div>

    <div id="taskSection" class="card" style="display: none;">
        <h2>Mis Tareas</h2>
        <div id="taskList">
            <p>Cargando tareas...</p>
        </div>
    </div>

    <script>
        // Simple authentication system
        let authToken = localStorage.getItem('auth_token');
        let currentUser = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded');
            
            const storedUser = localStorage.getItem('current_user');
            if (authToken && storedUser) {
                currentUser = JSON.parse(storedUser);
                console.log('Found stored user:', currentUser);
            }
            
            updateUI();
            
            if (currentUser) {
                loadTasks();
            }
        });

        // Get CSRF token
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        // Login function
        async function login(email, password) {
            try {
                console.log('Attempting login for:', email);
                
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                
                const csrfToken = getCSRFToken();
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken;
                }
                
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                console.log('Login response:', data);

                if (data.success) {
                    authToken = data.access_token;
                    currentUser = data.user;
                    localStorage.setItem('auth_token', authToken);
                    localStorage.setItem('current_user', JSON.stringify(currentUser));
                    
                    alert('Login exitoso!');
                    updateUI();
                    loadTasks();
                    return true;
                } else {
                    alert('Error: ' + (data.message || 'Credenciales incorrectas'));
                    return false;
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Error de conexión');
                return false;
            }
        }

        // Logout function
        async function logout() {
            try {
                if (authToken) {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${authToken}`,
                            'Accept': 'application/json'
                        }
                    });
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
            
            authToken = null;
            currentUser = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('current_user');
            updateUI();
            alert('Sesión cerrada');
        }

        // Update UI based on auth status
        function updateUI() {
            const loginSection = document.getElementById('loginSection');
            const userSection = document.getElementById('userSection');
            const taskSection = document.getElementById('taskSection');
            
            if (currentUser && authToken) {
                console.log('Showing authenticated UI');
                loginSection.style.display = 'none';
                userSection.style.display = 'block';
                userSection.innerHTML = `
                    <h2>Bienvenido, ${currentUser.first_name || currentUser.name}!</h2>
                    <p>Email: ${currentUser.email}</p>
                    <button onclick="logout()" class="btn btn-secondary">Cerrar Sesión</button>
                `;
                taskSection.style.display = 'block';
            } else {
                console.log('Showing login UI');
                loginSection.style.display = 'block';
                userSection.style.display = 'none';
                taskSection.style.display = 'none';
            }
        }

        // Load tasks
        async function loadTasks() {
            const taskList = document.getElementById('taskList');
            
            if (!authToken) {
                taskList.innerHTML = '<p>Debes iniciar sesión para ver tus tareas</p>';
                return;
            }

            try {
                console.log('Loading tasks...');
                const response = await fetch('/api/tasks', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    alert('Sesión expirada');
                    logout();
                    return;
                }

                const tasks = await response.json();
                console.log('Tasks loaded:', tasks);

                if (tasks.length === 0) {
                    taskList.innerHTML = '<p>No tienes tareas registradas</p>';
                } else {
                    taskList.innerHTML = tasks.map(task => `
                        <div style="border: 1px solid #ddd; padding: 10px; margin: 5px 0; border-radius: 4px;">
                            <h4>${task.title}</h4>
                            <p>${task.description || 'Sin descripción'}</p>
                            <small>Vence: ${new Date(task.due_date).toLocaleDateString()}</small>
                            <br><small>Estado: ${task.status} | Prioridad: ${task.priority}</small>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading tasks:', error);
                taskList.innerHTML = '<p>Error al cargar tareas</p>';
            }
        }

        // Debug: Check if inputs are working
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('loginEmail');
            const passwordInput = document.getElementById('loginPassword');
            
            console.log('Email input found:', emailInput);
            console.log('Password input found:', passwordInput);
            
            // Test input functionality
            passwordInput.addEventListener('input', function() {
                console.log('Password input working, length:', this.value.length);
            });
            
            passwordInput.addEventListener('focus', function() {
                console.log('Password input focused');
            });
        });

        // Handle login form
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            console.log('Form submitted with email:', email, 'password length:', password.length);
            
            const success = await login(email, password);
            if (success) {
                document.getElementById('loginForm').reset();
            }
        });
    </script>
</body>
</html>