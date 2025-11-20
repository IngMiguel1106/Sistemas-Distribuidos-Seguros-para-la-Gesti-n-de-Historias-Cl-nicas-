@extends('layouts.app')

@section('title', 'Panel del Admisionista - Mundo Pie')

@section('styles')
    <style>
        :root {
            --primary: #007bff;
            /* Azul clínico */
            --primary-600: #006ae6;
            --primary-100: #e8f1ff;
            --bg: #f3f6f8;
            --card-bg: #ffffff;
            --muted: #6c7a86;
            --success: #22c55e;
            --danger: #ef4444;
            --glass-border: rgba(0, 0, 0, 0.04);
            --shadow: 0 6px 20px rgba(16, 24, 40, 0.06);
            --radius: 12px;
        }

        /* Base */
        html,
        body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: #0f1724;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary), var(--primary-600));
            color: #fff;
            padding: 30px 20px;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            flex-direction: column;
        }

        .sidebar .brand h2 {
            font-size: 1.35rem;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: rgba(255, 255, 255, 0.98);
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 6px 0 0 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            color: rgba(235, 245, 255, 0.95);
            text-decoration: none;
            font-weight: 600;
            border-radius: 10px;
            transition: all .18s ease;
        }

        .nav-links li a small {
            opacity: 0.9;
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .sidebar .spacer {
            flex: 1;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
            padding: 12px;
            width: 100%;
            display: block;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.98rem;
            font-weight: 700;
            transition: background .18s ease, transform .12s ease;
        }

        .logout-btn:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        /* MAIN */
        .main-content {
            margin-left: 260px;
            padding: 36px 48px;
            width: calc(100% - 260px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 26px;
        }

        .header h1 {
            font-size: 1.9rem;
            color: var(--primary-600);
            font-weight: 800;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: #0b1721;
        }

        .user-info img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            background: linear-gradient(180deg, #fff, #f2f7ff);
        }

        /* GRID: stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: left;
            border: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-number {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--primary-600);
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* QUICK ACTIONS */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .action-card {
            background: var(--card-bg);
            padding: 22px;
            border-radius: 14px;
            box-shadow: var(--shadow);
            text-align: center;
            cursor: pointer;
            border: 1px solid transparent;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .action-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0, 123, 255, 0.12);
            box-shadow: 0 12px 30px rgba(3, 102, 214, 0.08);
        }

        .action-icon {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .action-title {
            font-weight: 700;
            color: var(--primary-600);
            margin-bottom: 6px;
        }

        .action-description {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* SEARCH */
        .search-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 22px;
        }

        .search-bar input {
            width: 340px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d9e6f7;
            outline: none;
            background: #fff;
            transition: box-shadow .15s ease, border-color .15s ease;
        }

        .search-bar input:focus {
            border-color: var(--primary);
            box-shadow: 0 6px 18px rgba(0, 123, 255, 0.08);
        }

        .search-bar button {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s ease, transform .12s ease;
        }

        .search-bar button:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
        }

        /* TABLE */
        section h2.section-title {
            color: var(--primary-600);
            margin-bottom: 12px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        thead {
            background: var(--primary);
            color: #fff;
        }

        th,
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .btn {
            padding: 7px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .btn-view {
            background: #1d4ed8;
        }

        .btn-edit {
            background: #10b981;
        }

        .btn-delete {
            background: var(--danger);
        }

        .btn-view:hover {
            background: #1034b3;
        }

        .btn-edit:hover {
            background: #0ea46b;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        /* ACTIVITY */
        .activity-card {
            margin-top: 30px;
            background: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            align-items: flex-start;
        }

        .activity-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .activity-title {
            font-weight: 700;
            color: var(--primary-600);
            margin-bottom: 4px;
        }

        .activity-desc {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .activity-time {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 6px;
        }

        footer {
            margin-top: 36px;
            text-align: center;
            color: var(--muted);
            padding-bottom: 30px;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {

            .stats-grid,
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-bar input {
                width: 260px;
            }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                display: none;
            }

            /* hide on small screens, you can add a toggle */
            .main-content {
                margin-left: 0;
                padding: 22px;
                width: 100%;
            }

            .stats-grid,
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .search-bar {
                justify-content: stretch;
                flex-direction: column;
                gap: 8px;
            }

            .search-bar input {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">

        {{-- SIDEBAR --}}
        <aside class="sidebar" role="navigation" aria-label="Menú principal">
            <div class="brand">
                <img src="https://cdn-icons-png.flaticon.com/512/4320/4320354.png" alt="Mundo Pie" style="width:72px;">
                <h2>Admisionista</h2>
            </div>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('admisionista.index') }}" class="nav-link">🏠 <small>Inicio</small></a></li>
                <li><a href="{{ route('admisionista.ingresos') }}" class="nav-link">📥 <small>Gestión de
                            Ingresos</small></a></li>
                <li><a href="{{ route('admisionista.pacientes') }}" class="nav-link">👥 <small>Pacientes</small></a></li>
                <li><a href="{{ route('citas.index') }}" class="nav-link">📅 <small>Citas</small></a></li>
                <li><a href="{{ route('admisionista.reportes') }}" class="nav-link">📊 <small>Reportes</small></a></li>
            </ul>

            <div class="spacer" aria-hidden="true"></div>

            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">Cerrar sesión</button>
            </form>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="main-content" role="main">
            {{-- Header --}}
            <div class="header">
                <h1>Panel del Admisionista</h1>
                <div class="user-info" aria-live="polite">
                    <span>Bienvenido, {{ Auth::user()->nombre_usuario }}</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="Avatar usuario">
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="stats-grid" aria-hidden="false">
                <div class="stat-card" role="group" aria-label="Ingresos hoy">
                    <div class="stat-top">
                        <div class="stat-number" id="totalIngresos">0</div>
                    </div>
                    <div class="stat-label">Total Ingresos Hoy</div>
                </div>

                <div class="stat-card" role="group" aria-label="Pacientes activos">
                    <div class="stat-top">
                        <div class="stat-number" id="pacientesActivos">0</div>
                    </div>
                    <div class="stat-label">Pacientes Activos</div>
                </div>

                <div class="stat-card" role="group" aria-label="Citas hoy">
                    <div class="stat-top">
                        <div class="stat-number" id="citasHoy">0</div>
                    </div>
                    <div class="stat-label">Citas para Hoy</div>
                </div>

                <div class="stat-card" role="group" aria-label="Casos urgentes">
                    <div class="stat-top">
                        <div class="stat-number" id="urgencias">0</div>
                    </div>
                    <div class="stat-label">Casos Urgentes</div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="quick-actions" aria-label="Acciones rápidas">
                <div class="action-card" onclick="window.location.href='{{ route('admisionista.ingresos') }}'">
                    <div class="action-icon">📥</div>
                    <div class="action-title">Nuevo Ingreso</div>
                    <div class="action-description">Registrar nuevo paciente</div>
                </div>

                <div class="action-card" onclick="window.location.href='{{ route('citas.index') }}'">
                    <div class="action-icon">📅</div>
                    <div class="action-title">Agendar Cita</div>
                    <div class="action-description">Programar cita médica</div>
                </div>

                <div class="action-card" onclick="window.location.href='{{ route('admisionista.pacientes') }}'">
                    <div class="action-icon">👥</div>
                    <div class="action-title">Gestión Pacientes</div>
                    <div class="action-description">Administrar pacientes</div>
                </div>

                <div class="action-card" onclick="window.location.href='{{ route('admisionista.reportes') }}'">
                    <div class="action-icon">📊</div>
                    <div class="action-title">Generar Reportes</div>
                    <div class="action-description">Reportes del sistema</div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="search-bar" role="search" aria-label="Buscar paciente">
                <input id="buscarDocumento" type="text" placeholder="Buscar paciente por documento..."
                    aria-label="Número de documento">
                <button type="button" onclick="buscarPaciente()" aria-label="Buscar paciente">Buscar</button>
            </div>

            {{-- Table --}}
            <section aria-labelledby="ingresosTitle">
                <h2 id="ingresosTitle" class="section-title">Ingresos Recientes</h2>
                <table id="tablaIngresos" role="table" aria-describedby="ingresosTitle">
                    <thead>
                        <tr>
                            <th scope="col">Documento</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Fecha Ingreso</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Estado</th>
                            <th scope="col" style="width:150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px;">Cargando datos...</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            {{-- Recent Activity --}}
            <div class="activity-card" aria-labelledby="actividadTitle">
                <h3 id="actividadTitle" style="color:var(--primary-600); margin-bottom:12px;">Actividad Reciente</h3>
                <div class="activity-list" id="listaActividad" role="log" aria-live="polite">
                    <div class="activity-item">
                        <div class="activity-icon">⏳</div>
                        <div class="activity-content">
                            <div class="activity-title">Cargando actividad...</div>
                        </div>
                    </div>
                </div>
            </div>

            <footer>
                © {{ date('Y') }} Mundo Pie – Sistema de Historia Clínica del Admisionista
            </footer>
        </main>
    </div>
@endsection

@section('scripts')
    <script>
        // activate current nav link
        (function activateNav() {
            const links = document.querySelectorAll('#navLinks .nav-link');
            const current = location.pathname;
            links.forEach(a => {
                try {
                    const href = a.getAttribute('href');
                    if (href && current.startsWith(href)) a.classList.add('active');
                } catch (e) {}
            });
        })();

        // Inicializar datos al cargar
        document.addEventListener('DOMContentLoaded', function() {
            cargarEstadisticas();
            cargarIngresosRecientes();
            cargarActividadReciente();
        });

        function cargarEstadisticas() {
            // Aquí puedes reemplazar por fetch real a tu API
            setTimeout(() => {
                document.getElementById('totalIngresos').textContent = '15';
                document.getElementById('pacientesActivos').textContent = '42';
                document.getElementById('citasHoy').textContent = '8';
                document.getElementById('urgencias').textContent = '3';
            }, 600);
        }

        function cargarIngresosRecientes() {
            const tbody = document.querySelector("#tablaIngresos tbody");
            // Petición a tu API
            fetch('/api/admisionista/ingresos-recientes')
                .then(async res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    tbody.innerHTML = "";
                    if (!data || data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="6" style="text-align:center; padding:20px;">No se encontraron ingresos recientes</td></tr>`;
                        return;
                    }

                    data.forEach(ingreso => {
                        const fecha = new Date(ingreso.fecha_ingreso).toLocaleDateString('es-ES');
                        const estadoClass = ingreso.entorno_atencion === 'Urgencias' ? 'status-urgente' :
                            'status-normal';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${ingreso.documento_id}</td>
                        <td>${ingreso.nombre_completo}</td>
                        <td>${fecha}</td>
                        <td>${ingreso.motivo_consulta}</td>
                        <td>
                            <span class="status-badge ${estadoClass}">${ingreso.entorno_atencion}</span>
                        </td>
                        <td>
                            <button class="btn btn-view" onclick="verDetalleIngreso('${ingreso.atencion_id}')">Ver</button>
                            <button class="btn btn-edit" onclick="editarIngreso('${ingreso.atencion_id}')">Editar</button>
                            <button class="btn btn-delete" onclick="eliminarIngreso('${ingreso.atencion_id}')">Eliminar</button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML =
                        `<tr><td colspan="6" style="text-align:center; color: ${getComputedStyle(document.documentElement).getPropertyValue('--danger') || '#dc3545'}; padding:18px;">Error al cargar datos</td></tr>`;
                });
        }

        function cargarActividadReciente() {
            setTimeout(() => {
                const actividades = [{
                        icon: '📥',
                        title: 'Nuevo ingreso registrado',
                        description: 'Carlos Arias - Urgencias',
                        time: 'Hace 5 min'
                    },
                    {
                        icon: '📅',
                        title: 'Cita agendada',
                        description: 'María Gómez - Medicina General',
                        time: 'Hace 15 min'
                    },
                    {
                        icon: '👤',
                        title: 'Paciente dado de alta',
                        description: 'Juan Pérez - Hospitalización',
                        time: 'Hace 1 hora'
                    },
                    {
                        icon: '📋',
                        title: 'Resultados cargados',
                        description: 'Ana López - Laboratorio',
                        time: 'Hace 2 horas'
                    }
                ];

                const container = document.getElementById('listaActividad');
                container.innerHTML = '';
                actividades.forEach(act => {
                    const item = document.createElement('div');
                    item.className = 'activity-item';
                    item.innerHTML = `
                    <div class="activity-icon">${act.icon}</div>
                    <div class="activity-content">
                        <div class="activity-title">${act.title}</div>
                        <div class="activity-desc">${act.description}</div>
                        <div class="activity-time">${act.time}</div>
                    </div>
                `;
                    container.appendChild(item);
                });
            }, 800);
        }

        function buscarPaciente() {
            const doc = document.getElementById("buscarDocumento").value.trim();
            if (!doc) return alert("Ingrese un número de documento.");

            fetch(`/api/admisionista/buscar/${encodeURIComponent(doc)}`)
                .then(async res => {
                    if (!res.ok) throw new Error('Error de red');
                    return res.json();
                })
                .then(data => {
                    if (data.success && data.data && data.data.length > 0) {
                        const paciente = data.data[0];
                        // Mostrar modal o redirigir
                        window.location.href =
                            `{{ route('admisionista.ingresos') }}?documento=${paciente.documento_id}`;
                    } else {
                        alert("Paciente no encontrado. Verifique el número de documento.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error al buscar paciente. Intente nuevamente.");
                });
        }

        function verDetalleIngreso(id) {
            // redirigir a detalle
            window.location.href = `{{ url('admisionista/ingresos') }}/${id}`;
        }

        function editarIngreso(id) {
            window.location.href = `{{ url('admisionista/ingresos') }}/${id}/edit`;
        }

        function eliminarIngreso(id) {
            if (!confirm('¿Desea eliminar este ingreso? Esta acción no se puede deshacer.')) return;
            // Llamada DELETE a tu API
            fetch(`/api/admisionista/ingresos/${encodeURIComponent(id)}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Error al eliminar');
                    // refrescar tabla
                    cargarIngresosRecientes();
                })
                .catch(err => {
                    console.error(err);
                    alert('No se pudo eliminar el ingreso.');
                });
        }

        // Manejo tecla Enter en búsqueda
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.id === 'buscarDocumento') {
                e.preventDefault();
                buscarPaciente();
            }
        });
    </script>
@endsection
