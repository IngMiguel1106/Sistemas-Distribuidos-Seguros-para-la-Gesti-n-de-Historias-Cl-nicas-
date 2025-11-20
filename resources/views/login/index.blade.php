@extends('layouts.app')

@section('title', 'Portal del Paciente | Clínica Mundo Salud')

@section('content')
    <div class="login-container">
        <div class="left-panel">
            <img src="https://cdn-icons-png.flaticon.com/512/4320/4320354.png" alt="Ilustración médica">
            <h2>Bienvenido</h2>
            <p>Clínica de manera segura y sencilla.</p>
        </div>

        <div class="right-panel">
            <h3>Iniciar Sesión</h3>
            <p>Ingresa tus credenciales para acceder a tu cuenta</p>

            <div class="error" id="errorMessage">Documento, contraseña o rol incorrectos.</div>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-group">
                    <label for="documento_id">Número de Documento</label>
                    <input type="text" id="documento_id" name="documento_id" placeholder="Ej: 1045678901" required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="••••••••" required>
                    <span class="toggle-password" id="togglePassword">👁️</span>
                </div>

                <div class="form-group">
                    <label for="rol_id">Seleccione su Rol</label>
                    <select name="rol_id" id="rol_id" required>
                        <option value="" disabled selected>Seleccione una opción</option>
                        <option value="paciente">Paciente</option>
                        <option value="doctor">Doctor</option>
                        <option value="admisionista">Admisionista</option>
                    </select>
                </div>

                <button type="submit" class="login-btn">Ingresar</button>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Variables de color amigables */
        :root {
            --primary: #4fc3f7;
            /* Azul pastel */
            --secondary: #0288d1;
            /* Azul profundo */
            --accent: #26a69a;
            /* Verde turquesa */
            --white: #ffffff;
            --gray: #555555;
            --danger: #ef5350;
            --shadow: rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e3f2fd, #f1f8e9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            animation: fadeIn 1s ease forwards;
        }

        /* Animación de fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Contenedor principal */
        .login-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px var(--shadow);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            animation: fadeIn 1s ease forwards;
        }

        /* Panel izquierdo */
        .left-panel {
            flex: 1;
            background: linear-gradient(160deg, var(--secondary), var(--primary));
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px 30px;
            text-align: center;
            animation: slideInLeft 1s ease forwards;
        }

        .left-panel img {
            width: 65%;
            max-width: 250px;
            margin-bottom: 30px;
            animation: fadeIn 1s 0.5s ease forwards;
        }

        .left-panel h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .left-panel p {
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Animación slide-left */
        @keyframes slideInLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Panel derecho */
        .right-panel {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: slideInRight 1s ease forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .right-panel h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--secondary);
        }

        .right-panel p {
            font-size: 1rem;
            margin-bottom: 25px;
            color: var(--gray);
        }

        .error {
            display: none;
            background: #ffe5e5;
            color: var(--danger);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        /* Formularios */
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        input,
        select {
            width: 100%;
            padding: 14px 45px 14px 12px;
            border-radius: 12px;
            border: 1px solid #ccc;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.8);
        }

        input:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 12px var(--primary);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            color: var(--gray);
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .toggle-password:hover {
            color: var(--secondary);
        }

        /* Botón login */
        .login-btn {
            margin-top: 25px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            border-radius: 12px;
            padding: 16px;
            width: 100%;
            color: var(--white);
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, var(--accent), var(--secondary));
            transform: translateY(-3px);
            box-shadow: 0 8px 20px var(--shadow);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .left-panel {
                display: none;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        const password = document.getElementById('contrasena');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', () => {
            password.type = password.type === 'password' ? 'text' : 'password';
            togglePassword.textContent = password.type === 'password' ? '👁️' : '🙈';
        });
    </script>
@endsection
