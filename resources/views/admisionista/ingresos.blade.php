@extends('layouts.app')

@section('title', 'Gestión de Ingresos - Admisionista')

@section('styles')
    <style>
        :root {
            --primary: #007bff;
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

        .ingresos-container {
            display: flex;
            min-height: 100vh;
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
            width: 85%;
            margin: 25px auto;
            display: block;
            color: #fff;
            border-radius: var(--radius);
            cursor: pointer;
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

        /* Stats Grid */
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

        .stat-number {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--primary-600);
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .action-card {
            background: var(--card-bg);
            padding: 22px;
            border-radius: var(--radius);
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

        /* Forms Section */
        .forms-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 28px;
        }

        .form-card {
            background: var(--card-bg);
            padding: 22px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .form-card h3 {
            color: var(--primary-600);
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.08);
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        /* Recent Activity */
        .activity-card {
            background: var(--card-bg);
            padding: 22px;
            border-radius: var(--radius);
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

        /* Responsive */
        @media (max-width: 1100px) {

            .stats-grid,
            .quick-actions,
            .forms-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 22px;
                width: 100%;
            }

            .stats-grid,
            .quick-actions,
            .forms-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ingresos-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <img src="https://cdn-icons-png.flaticon.com/512/4320/4320354.png" alt="Mundo Pie" style="width:72px;">
                <h2>Admisionista</h2>
            </div>

            <ul class="nav-links">
                <li><a href="{{ route('admisionista.index') }}">🏠 Inicio</a></li>
                <li><a href="{{ route('admisionista.ingresos') }}" style="background: rgba(255,255,255,0.12);">📥 Gestión de
                        Ingresos</a></li>
                <li><a href="{{ route('citas.index') }}">📅 Citas</a></li>
                <li><a href="{{ route('admisionista.pacientes') }}">👥 Pacientes</a></li>
                <li><a href="{{ route('admisionista.reportes') }}">📊 Reportes</a></li>
            </ul>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">Cerrar sesión</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>Gestión de Ingresos</h1>
                <div class="user-info">
                    <span>Bienvenido, {{ Auth::user()->nombre_usuario }}</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="usuario">
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" id="ingresosHoy">0</div>
                    <div class="stat-label">Ingresos Hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="ingresosSemana">0</div>
                    <div class="stat-label">Ingresos Esta Semana</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="pacientesUrgencias">0</div>
                    <div class="stat-label">Pacientes en Urgencias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="citasProgramadas">0</div>
                    <div class="stat-label">Citas Programadas</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card" onclick="mostrarFormularioIngreso()">
                    <div class="action-icon">👤</div>
                    <div class="action-title">Nuevo Ingreso</div>
                    <div class="action-description">Registrar nuevo paciente</div>
                </div>
                <div class="action-card" onclick="mostrarFormularioCita()">
                    <div class="action-icon">📅</div>
                    <div class="action-title">Agendar Cita</div>
                    <div class="action-description">Programar nueva cita médica</div>
                </div>
                <div class="action-card" onclick="buscarPacienteRapido()">
                    <div class="action-icon">🔍</div>
                    <div class="action-title">Buscar Paciente</div>
                    <div class="action-description">Búsqueda rápida de pacientes</div>
                </div>
            </div>

            <!-- Forms Section -->
            <div class="forms-section">
                <!-- Formulario de Ingreso -->
                <div class="form-card">
                    <h3>📥 Registrar Nuevo Ingreso</h3>
                    <form id="formIngreso">
                        @csrf
                        <div class="form-group">
                            <label for="documento_paciente">Documento del Paciente *</label>
                            <input type="text" id="documento_paciente" name="documento_paciente" required
                                placeholder="Ej: 1001001001">
                        </div>

                        <div class="form-group">
                            <label for="tipo_ingreso">Tipo de Ingreso *</label>
                            <select id="tipo_ingreso" name="tipo_ingreso" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="urgencias">Urgencias</option>
                                <option value="consulta">Consulta Externa</option>
                                <option value="hospitalizacion">Hospitalización</option>
                                <option value="procedimiento">Procedimiento</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="motivo_consulta">Motivo de Consulta *</label>
                            <textarea id="motivo_consulta" name="motivo_consulta" rows="3" placeholder="Describa el motivo de la consulta..."
                                required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="prioridad">Prioridad</label>
                            <select id="prioridad" name="prioridad">
                                <option value="normal">Normal</option>
                                <option value="urgente">Urgente</option>
                                <option value="emergencia">Emergencia</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">📋 Registrar Ingreso</button>
                    </form>
                </div>

                <!-- Formulario de Búsqueda Rápida -->
                <div class="form-card">
                    <h3>🔍 Búsqueda Rápida</h3>
                    <div class="form-group">
                        <label for="busquedaDocumento">Documento o Nombre</label>
                        <input type="text" id="busquedaDocumento" placeholder="Documento o nombre del paciente">
                    </div>
                    <button class="btn btn-primary" onclick="buscarPaciente()">Buscar Paciente</button>

                    <div id="resultadoBusqueda" style="margin-top: 20px; display: none;">
                        <h4>Resultado de Búsqueda</h4>
                        <div id="infoPaciente" style="background: #f8f9fa; padding: 15px; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-card">
                <h3>📋 Ingresos Recientes</h3>
                <div class="activity-list" id="listaIngresosRecientes">
                    <div class="activity-item">
                        <div class="activity-icon">⏳</div>
                        <div class="activity-content">
                            <div class="activity-title">Cargando ingresos...</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para información del paciente -->
    <div id="modalPaciente" class="modal">
        <div class="modal-content">
            <h3 id="modalTitulo">Información del Paciente</h3>
            <div id="modalContenido"></div>
            <div style="margin-top: 20px; text-align: right;">
                <button class="btn btn-warning" onclick="cerrarModal()">Cerrar</button>
                <button class="btn btn-success" onclick="registrarIngresoDesdeModal()">Registrar Ingreso</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Cargar estadísticas al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            cargarEstadisticas();
            cargarIngresosRecientes();

            // Configurar formulario de ingreso
            document.getElementById('formIngreso').addEventListener('submit', function(e) {
                e.preventDefault();
                registrarIngreso();
            });
        });

        function cargarEstadisticas() {
            // Simular carga de estadísticas (en producción sería una API real)
            setTimeout(() => {
                document.getElementById('ingresosHoy').textContent = '12';
                document.getElementById('ingresosSemana').textContent = '89';
                document.getElementById('pacientesUrgencias').textContent = '5';
                document.getElementById('citasProgramadas').textContent = '23';
            }, 1000);
        }

        function cargarIngresosRecientes() {
            fetch('/api/admisionista/ingresos-recientes')
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('listaIngresosRecientes');

                    if (!data.length) {
                        container.innerHTML = `
                    <div class="activity-item">
                        <div class="activity-icon">📭</div>
                        <div class="activity-content">
                            <div class="activity-title">No hay ingresos recientes</div>
                        </div>
                    </div>`;
                        return;
                    }

                    let html = '';
                    data.forEach(ingreso => {
                        const fecha = new Date(ingreso.fecha_ingreso).toLocaleString('es-ES');
                        const icon = ingreso.entorno_atencion === 'Urgencias' ? '🚑' :
                            ingreso.entorno_atencion === 'Hospitalización' ? '🏥' : '👨‍⚕️';

                        html += `
                <div class="activity-item">
                    <div class="activity-icon">${icon}</div>
                    <div class="activity-content">
                        <div class="activity-title">${ingreso.nombre_completo}</div>
                        <div class="activity-description">${ingreso.entorno_atencion} - ${ingreso.motivo_consulta}</div>
                        <div class="activity-time">${fecha}</div>
                    </div>
                </div>`;
                    });
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function buscarPaciente() {
            const documento = document.getElementById('busquedaDocumento').value.trim();
            if (!documento) {
                alert('Por favor ingrese un documento o nombre para buscar');
                return;
            }

            fetch(`/api/admisionista/buscar-paciente/${documento}`)
                .then(r => r.json())
                .then(data => {
                    const resultado = document.getElementById('resultadoBusqueda');
                    const info = document.getElementById('infoPaciente');

                    if (data.success && data.paciente) {
                        const paciente = data.paciente;
                        info.innerHTML = `
                    <p><strong>Nombre:</strong> ${paciente.nombre_completo}</p>
                    <p><strong>Documento:</strong> ${paciente.documento_id}</p>
                    <p><strong>Edad:</strong> ${paciente.edad} años</p>
                    <p><strong>Género:</strong> ${paciente.sexo === 'M' ? 'Masculino' : 'Femenino'}</p>
                    <p><strong>Teléfono:</strong> ${paciente.telefono || 'No registrado'}</p>
                    <div style="margin-top: 10px;">
                        <button class="btn btn-success" onclick="seleccionarPaciente('${paciente.documento_id}')">
                            Seleccionar para Ingreso
                        </button>
                    </div>
                `;
                        resultado.style.display = 'block';
                    } else {
                        info.innerHTML = '<p style="color: #dc3545;">Paciente no encontrado</p>';
                        resultado.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al buscar paciente');
                });
        }

        function seleccionarPaciente(documento) {
            document.getElementById('documento_paciente').value = documento;
            document.getElementById('resultadoBusqueda').style.display = 'none';
            document.getElementById('busquedaDocumento').value = '';

            // Enfocar el campo de motivo de consulta
            document.getElementById('motivo_consulta').focus();
        }

        function registrarIngreso() {
            const formData = new FormData(document.getElementById('formIngreso'));

            fetch('/api/admisionista/registrar-ingreso', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Ingreso registrado exitosamente');
                        document.getElementById('formIngreso').reset();
                        cargarEstadisticas();
                        cargarIngresosRecientes();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al registrar el ingreso');
                    console.error('Error:', error);
                });
        }

        function mostrarFormularioIngreso() {
            document.getElementById('formIngreso').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function mostrarFormularioCita() {
            window.location.href = "{{ route('citas.index') }}";
        }

        function buscarPacienteRapido() {
            document.getElementById('busquedaDocumento').focus();
        }

        function mostrarModalPaciente(paciente) {
            document.getElementById('modalTitulo').textContent = `Paciente: ${paciente.nombre_completo}`;
            document.getElementById('modalContenido').innerHTML = `
        <p><strong>Documento:</strong> ${paciente.documento_id}</p>
        <p><strong>Edad:</strong> ${paciente.edad} años</p>
        <p><strong>Género:</strong> ${paciente.sexo}</p>
        <p><strong>Teléfono:</strong> ${paciente.telefono || 'No registrado'}</p>
        <p><strong>Correo:</strong> ${paciente.correo || 'No registrado'}</p>
    `;
            document.getElementById('modalPaciente').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalPaciente').style.display = 'none';
        }

        function registrarIngresoDesdeModal() {
            cerrarModal();
            mostrarFormularioIngreso();
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('modalPaciente').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
@endsection
