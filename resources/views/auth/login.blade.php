<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sr. Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body class="pantalla-centrada">

<div style="position: absolute; top: -10%; left: -10%; width: 300px; height: 300px; background: var(--accent-orange); border-radius: 50%; filter: blur(150px); opacity: 0.2; z-index: -1;"></div>
<div style="position: absolute; bottom: -10%; right: -10%; width: 300px; height: 300px; background: var(--unified-red); border-radius: 50%; filter: blur(150px); opacity: 0.1; z-index: -1;"></div>

<div class="liquid-card text-center">
    <h2 class="mb-1 fw-bold text-accent">Sr. Pizza</h2>
    <p class="text-liquid-muted mb-4">Bienvenido al Sistema</p>

    @if($errors->any())
        <div class="alert alert-danger p-2 border-0" style="background-color: rgba(231, 76, 60, 0.2); color: #ff6b6b;" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf 
        
        <div class="mb-3 text-start">
            <label class="form-label text-liquid-muted small mb-1">Matrícula</label>
            <input type="text" name="matricula" class="form-control liquid-input" placeholder="Ej. 1001" required 
                   inputmode="numeric" 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="mb-4 text-start">
            <label class="form-label text-liquid-muted small mb-1">Contraseña</label>
            <div class="input-group">
                <input type="password" id="contrasena" name="contrasena" class="form-control liquid-input" style="border-right: none;" placeholder="••••••••" required>
                <span class="input-group-text liquid-input-group-text" onclick="togglePassword()" id="toggle-span">
                    <svg id="icono-ojo" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    </svg>
                </span>
            </div>
        </div>

        <button type="submit" class="btn btn-sr-pizza w-100 mt-2">INICIAR SESIÓN</button>
    </form>
</div>

<script>
    function togglePassword() {
        var input = document.getElementById("contrasena");
        var icono = document.getElementById("icono-ojo");
        
        if (input.type === "password") {
            input.type = "text"; // Muestra la contraseña
            // Cambia el icono a un ojo tachado
            icono.innerHTML = '<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755l-.809-.805zm-4.485-.297a3.5 3.5 0 0 1-3.66-3.66l.548.548a2.5 2.5 0 0 0 2.564 2.564l.548.548z"/><path d="M11.838 10.422l-1.41-1.41a3.5 3.5 0 0 0-4.004-4.004l-1.41-1.41A7.028 7.028 0 0 0 2 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755l.809.805zM8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8zM14.5 14.5l-13-13 .707-.707 13 13-.707.707z"/>';
        } else {
            input.type = "password"; // Oculta la contraseña
            // Restaura el ojo normal
            icono.innerHTML = '<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>';
        }
    }
</script>

</body>
</html>