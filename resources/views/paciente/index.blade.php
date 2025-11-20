@extends('layouts.app')

@section('title', 'Panel del Paciente | Clínica Mundo Salud')

@section('content')
    <style>
        /* Variables amigables para pacientes */
        :root {
            --primary: #4fc3f7;
            /* Azul claro */
            --secondary: #0288d1;
            /* Azul más oscuro para contraste */
            --accent: #26a69a;
            /* Verde turquesa para botones y badges */
            --white: #ffffff;
            --gray: #555555;
            --danger: #ef5350;
            --shadow: rgba(0, 0, 0, 0.15);
        }

        /* Reset básico */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #e3f2fd;
            /* Fondo azul muy claro */
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--secondary);
            color: var(--white);
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            box-shadow: 0 6px 20px var(--shadow);
            position: fixed;
            height: 100vh;
            border-radius: 0 20px 20px 0;
        }

        .sidebar h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 40px;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            margin-bottom: 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: var(--primary);
            color: var(--secondary);
            transform: translateX(5px);
        }

        /* Main content */
        .content {
            margin-left: 260px;
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 15px;
        }

        .logout-btn {
            background-color: var(--danger);
            padding: 10px 22px;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }

        /* Card container */
        .card-container {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        /* Individual card */
        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            color: var(--secondary);
            box-shadow: 0 6px 20px var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px var(--shadow);
        }

        /* Card icons */
        .card-icon {
            font-size: 50px;
            margin-bottom: 15px;
            color: var(--accent);
        }

        /* Card content */
        .card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .card p {
            font-size: 15px;
            color: var(--gray);
            margin-bottom: 20px;
        }

        /* Card button */
        .card a {
            display: inline-block;
            background: var(--primary);
            padding: 10px 18px;
            color: #fff;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .card a:hover {
            background: var(--accent);
            transform: translateY(-2px);
        }

        /* Stats badges */
        .card .badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent);
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                overflow-x: auto;
                padding: 15px 10px;
                border-radius: 0;
            }

            .sidebar h2 {
                display: none;
            }

            .sidebar a {
                margin-right: 12px;
                font-size: 14px;
                white-space: nowrap;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>


    <aside class="sidebar" role="navigation" aria-label="Menú principal">
        <div class="brand">
            <img src="https://cdn-icons-png.flaticon.com/512/4320/4320354.png" alt="Mundo Pie"
                style="width:72px; display:block; margin:0 auto;">
            <h2>Paciente</h2>
        </div>
        <a href="{{ route('paciente.index') }}">🏠 Inicio</a>
        <a href="{{ route('paciente.consultas') }}" style="background: #0056b3;">📋 Mis Consultas</a>
        <a href="{{ route('paciente.resultados') }}">📄 Mis Resultados</a>
        <button class="logout-btn" onclick="logout()" style="margin-top: 20px; width: 100%;">Cerrar Sesión</button>
    </aside>

    <div class="content">
        <div class="header">
            <h1>Bienvenido al Panel del Paciente</h1>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">Cerrar sesión</button>
            </form>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="card-icon">🩺</div>
                <div class="badge">5</div>
                <h3>Consultas Médicas</h3>
                <p>Visualiza el historial completo de tus consultas.</p>
                <a href="{{ route('paciente.consultas') }}">Ver Consultas</a>
            </div>

            <div class="card">
                <div class="card-icon">📊</div>
                <div class="badge">2</div>
                <h3>Resultados Clínicos</h3>
                <p>Accede a resultados de laboratorios y estudios.</p>
                <a href="{{ route('paciente.resultados') }}">Ver Resultados</a>
            </div>

            <div class="card">
                <div class="card-icon">📅</div>
                <div class="badge">1</div>
                <h3>Citas Médicas</h3>
                <p>Consulta tus citas programadas y próximas fechas.</p>
                <a href="#">Ver Citas</a>
            </div>
        </div>
    </div>
@endsection
