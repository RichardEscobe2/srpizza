<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sr. Pizza</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .btn-primary {
            background-color: #f15a24;
            border: none;
            height: 48px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #d14a1c;
        }
        /* Estilo para el botón del ojito */
        .toggle-password {
            cursor: pointer;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <h2 class="mb-1 fw-bold" style="color: #f15a24;">Sr. Pizza</h2>
    <p class="text-muted mb-4">Bienvenido</p>

    @if($errors->any())
        <div class="alert alert-danger p-2" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf 
        
        <!-- MATRÍCULA MODIFICADA -->
        <div class="mb-3 text-start">
            <label class="form-label text-muted small">Matrícula</label>
            <!-- El oninput usa RegEx para borrar cualquier cosa que no sea número al instante -->
            <input type="text" name="matricula" class="form-control" placeholder="Ej. 1001" required 
                   inputmode="numeric" 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <!-- CONTRASEÑA MODIFICADA CON INPUT-GROUP Y OJITO -->
        <div class="mb-4 text-start">
            <label class="form-label text-muted small">Contraseña</label>
            <div class="input-group">
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="********" required>
                <span class="input-group-text toggle-password" onclick="togglePassword()">
                    <!-- Símbolo de un ojo usando un emoji simple, o puedes usar iconos de FontAwesome -->
                    <span id="icono-ojo">👁️</span>
                </span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">INICIAR SESIÓN</button>
    </form>
</div>

<!-- SCRIPT PARA MOSTRAR/OCULTAR CONTRASEÑA -->
<script>
    function togglePassword() {
        var input = document.getElementById("contrasena");
        
        if (input.type === "password") {
            input.type = "text"; // Muestra la contraseña
        } else {
            input.type = "password"; // Oculta la contraseña
        }
    }
</script>

</body>
</html>