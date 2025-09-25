<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - StudyFlow</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
</head>
<body>
    <main class="main" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--background);">
        <div class="card" style="max-width: 400px; width: 100%;">
            <div class="card-header" style="text-align: center;">
                <div class="logo" style="font-size: 2rem; margin-bottom: 1rem;">📚 StudyFlow</div>
                <h1 class="card-title">Registrarse</h1>
                <p class="card-description">Crea tu cuenta para comenzar a gestionar tus tareas y notas.</p>
            </div>
            <form method="POST" action="{{ route('register') }}" style="padding: 1.5rem 0;">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-input" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <div style="margin: 1rem 0;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarse</button>
                </div>
                @if(session('error'))
                    <div class="alert alert-destructive" style="margin-top: 1rem;">{{ session('error') }}</div>
                @endif
            </form>
            <div style="text-align:center; margin-top:1rem;">
                <a href="{{ route('login') }}" class="nav-link">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </main>
</body>
</html>
