<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes - Admisionista FastAPI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos de la Interfaz (Adaptados para Tailwind) */
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

        html,
        body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: #0f1724;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary), var(--primary-600));
            color: #fff;
            padding: 30px 0;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
        }

        .main-content {
            margin-left: 260px;
            padding: 36px 48px;
            width: calc(100% - 260px);
        }

        /* [Resto de estilos omitidos por brevedad, son idénticos a la versión anterior] */

        .sidebar h2 {
            text-align: center;
            font-size: 1.7em;
            margin-bottom: 25px;
            font-weight: 700;
            color: #fff;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0 var(--radius) var(--radius) 0;
            transition: all 0.2s ease;
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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
        }

        .user-info img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            background: linear-gradient(180deg, #fff, #f2f7ff);
        }

        .pacientes-container {
            background: var(--card-bg);
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .search-section {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .search-section input,
        .search-section select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 14px;
            transition: border-color 0.3s;
            background-color: #f9f9f9;
        }

        .search-section input:focus,
        .search-section select:focus {
            border-color: var(--primary);
            outline: none;
            background-color: var(--card-bg);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.08);
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            color: white;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--primary);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-600);
        }

        .btn-success {
            background: var(--success);
        }

        .btn-success:hover:not(:disabled) {
            background: #1c7c46;
        }

        .btn-info {
            background: #17a2b8;
        }

        .btn-info:hover:not(:disabled) {
            background: #138496;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover:not(:disabled) {
            background: #e0a800;
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-danger:hover:not(:disabled) {
            background: #c82333;
        }

        .pacientes-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .pacientes-table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            padding: 14px 16px;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .pacientes-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .pacientes-table tr:hover {
            background: #f8fbff;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .loading,
        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .modal-content {
            background: var(--card-bg);
            padding: 30px;
            border-radius: var(--radius);
            max-width: 600px;
            width: 90%;
            box-shadow: var(--shadow);
        }

        .modal-content input,
        .modal-content select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 14px;
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
</head>

<body onload="initApp()">
    <!-- Custom Modal/Message Box (Replaces alert/confirm) -->
    <div id="customModal"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000;">
        <div
            style="background: white; padding: 25px; border-radius: var(--radius); max-width: 400px; width: 90%; box-shadow: var(--shadow); text-align: center;">
            <p id="modalMessage" style="font-weight: 600; margin-bottom: 20px; color: #0f1724;"></p>
            <div id="modalActions">
                <button onclick="document.getElementById('customModal').style.display='none'"
                    class="btn btn-primary">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal for Patient Form -->
    <div id="pacienteModal"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center; z-index: 1010;">
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4" id="modalTitle">Nuevo Paciente</h3>
            <form id="pacienteForm">
                <input type="hidden" id="pacienteId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Documento</label>
                        <input type="text" id="documento" required placeholder="Número de Documento">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Nombre Completo</label>
                        <input type="text" id="nombreCompleto" required placeholder="Nombre y Apellido">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Edad</label>
                        <input type="number" id="edad" required min="1" max="120" placeholder="Edad">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Género</label>
                        <select id="genero" required>
                            <option value="" disabled>Seleccione Género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Teléfono</label>
                        <input type="tel" id="telefono" placeholder="Teléfono de contacto">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold">Última Consulta</label>
                        <!-- Este campo será solo de lectura en el formulario de edición -->
                        <input type="date" id="ultimaConsulta">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1 text-sm font-semibold">Estado</label>
                        <select id="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-5">
                    <button type="button" onclick="cerrarModal()"
                        class="btn bg-gray-500 hover:bg-gray-600">Cancelar</button>
                    <!-- El botón de guardar llama a guardarPaciente() -->
                    <button type="submit" class="btn btn-success" id="saveButton">Guardar Paciente</button>
                </div>
            </form>
        </div>
    </div>


    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" role="navigation" aria-label="Menú principal">
            <div class="brand">
                <img src="https://cdn-icons-png.flaticon.com/512/4320/4320354.png" alt="Mundo Pie"
                    style="width:72px; display:block; margin:0 auto;">
                <h2>Admisionista</h2>
            </div>

            <ul class="nav-links">
                <li><a href="#inicio">🏠 Inicio</a></li>
                <li><a href="#ingresos">📥 Ingresos</a></li>
                <li><a href="#citas">📅 Citas</a></li>
                <li><a href="#pacientes" class="active">👥 Pacientes</a></li>
                <li><a href="#reportes">📊 Reportes</a></li>
            </ul>
            <!-- Logout Mockup -->
            <button onclick="logout()" class="logout-btn" title="Cerrar sesión">Cerrar sesión</button>
            <p class="text-center text-xs mt-4 opacity-75" id="userDisplay">Conectado a FastAPI</p>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Gestión de Pacientes</h1>
                <div class="user-info">
                    <span>Bienvenido, Admisionista</span>
                    <img src="https://placehold.co/48x48/007bff/ffffff?text=U" alt="Usuario Avatar">
                </div>
            </div>

            <div class="pacientes-container">
                <div class="search-section">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Buscar Paciente</label>
                        <input type="text" id="searchInput" placeholder="Documento, nombre..." style="width: 100%;"
                            oninput="fetchPacientes(1)"> <!-- Llama a fetch al escribir -->
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Estado</label>
                        <select id="filterEstado" style="width: 150px;" onchange="fetchPacientes(1)">
                            <option value="">Todos</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Género</label>
                        <select id="filterGenero" style="width: 120px;" onchange="fetchPacientes(1)">
                            <option value="">Todos</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: end;">
                        <button onclick="fetchPacientes(1)" class="btn btn-primary">🔍 Buscar/Refrescar</button>
                        <button onclick="nuevoPaciente()" class="btn btn-success">➕ Nuevo</button>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table class="pacientes-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre Completo</th>
                                <th>Edad</th>
                                <th>Género</th>
                                <th>Teléfono</th>
                                <th>Última Consulta</th>
                                <th>Estado</th>
                                <th style="width: 200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="pacientesBody">
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #666;">
                                    <div class="loading">
                                        <div class="loading-spinner"></div>
                                        <p>Consultando API de FastAPI...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div id="paginationInfo" style="color: #666; font-size: 0.9em;">Cargando información...</div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="cambiarPagina(-1)" id="btnPrev" disabled class="btn btn-info">←
                            Anterior</button>
                        <button onclick="cambiarPagina(1)" id="btnNext" disabled class="btn btn-info">Siguiente
                            →</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // --- CONFIGURACIÓN DE FASTAPI ---
        // DEBE coincidir con el puerto y la ruta de tu servidor Python
        const API_URL_LISTA = 'http://localhost:8020/pacientes/admisionista';
        const API_URL_BASE = 'http://localhost:8020'; // Base para operaciones POST/PUT/DELETE
        const ITEMS_PER_PAGE = 10;

        // Variables de estado del frontend (Pagina actual y metadata del servidor)
        let currentPage = 1;
        let totalPacientes = 0;
        let lastPage = 1;


        // Función de utilidad para mostrar mensajes (Reemplaza alert/confirm)
        function showMessage(message, type = 'info', actions = [{
            text: 'Cerrar',
            handler: () => document.getElementById('customModal').style.display = 'none'
        }]) {
            const modal = document.getElementById('customModal');
            const msgElement = document.getElementById('modalMessage');
            const actionsElement = document.getElementById('modalActions');

            msgElement.textContent = message;
            actionsElement.innerHTML = '';

            actions.forEach(action => {
                const btn = document.createElement('button');
                btn.textContent = action.text;
                btn.className = `btn btn-${action.type || 'primary'} mr-2`;
                btn.onclick = () => {
                    action.handler();
                    modal.style.display = 'none';
                };
                actionsElement.appendChild(btn);
            });

            modal.style.display = 'flex';
        }

        function initApp() {
            // Inicialización de la aplicación, llama a la función de obtención de datos
            fetchPacientes(1);
        }

        /**
         * Realiza la petición GET al endpoint de FastAPI para obtener la lista de pacientes.
         * Maneja la paginación y filtros.
         * @param {number} pageNumber - La página a cargar.
         */
        async function fetchPacientes(pageNumber = currentPage) {
            currentPage = pageNumber;
            const tbody = document.getElementById('pacientesBody');
            const paginationInfo = document.getElementById('paginationInfo');

            tbody.innerHTML = `<tr><td colspan="8" class="loading">
                <div class="loading-spinner"></div>
                <p>Cargando datos de FastAPI...</p>
            </td></tr>`;
            paginationInfo.textContent = 'Cargando...';

            try {
                // 1. Obtener valores de búsqueda y filtros del formulario
                const searchTerm = document.getElementById('searchInput').value.trim();
                const filterEstado = document.getElementById('filterEstado').value;
                const filterGenero = document.getElementById('filterGenero').value;

                // 2. Construir la URL con Query Parameters (compatibles con FastAPI)
                const params = new URLSearchParams({
                    page: currentPage,
                    limit: ITEMS_PER_PAGE,
                    search: searchTerm,
                    estado: filterEstado,
                    genero: filterGenero
                });

                const url = `${API_URL_LISTA}?${params.toString()}`;

                // 3. Petición GET a FastAPI
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }

                const data = await response.json();

                // 4. Actualizar estado de paginación
                const pacientes = data.data || [];
                totalPacientes = data.total || 0;
                lastPage = data.last_page || 1;

                // 5. Renderizar la tabla y la paginación
                renderPacientes(pacientes);

            } catch (error) {
                console.error("Error al obtener pacientes de FastAPI:", error);
                tbody.innerHTML = `<tr><td colspan="8" class="empty-state" style="color:var(--danger)">
                    ❌ Error de conexión. Asegúrate que FastAPI está corriendo en <b>${API_URL_LISTA}</b>.
                </td></tr>`;
                paginationInfo.textContent = 'Error al cargar datos.';
                showMessage(`Error de conexión: ${error.message}.`, 'danger');
            }
        }

        // --- MANEJO DE VISTA Y PAGINACIÓN ---
        function renderPacientes(pacientes) {
            const tbody = document.getElementById('pacientesBody');
            tbody.innerHTML = '';

            if (pacientes.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="8" class="empty-state">No se encontraron pacientes que coincidan con los criterios.</td></tr>`;
            } else {
                pacientes.forEach(p => {
                    // El campo 'activo' viene como booleano de FastAPI
                    const estado = p.activo ? 'activo' : 'inactivo';
                    const estadoBadge = p.activo ?
                        `<span class="badge badge-success">Activo</span>` :
                        `<span class="badge badge-danger">Inactivo</span>`;

                    // Formatear la fecha
                    const ultimaConsulta = p.ultima_consulta ?
                        new Date(p.ultima_consulta).toLocaleDateString('es-CO') :
                        'N/A';

                    const row = tbody.insertRow();
                    row.innerHTML = `
                        <td>${p.documento_id}</td>
                        <td class="font-semibold">${p.nombre_completo}</td>
                        <td>${p.edad || 'N/A'}</td>
                        <td>${p.genero || p.sexo}</td>
                        <td>${p.telefono || 'N/A'}</td>
                        <td>${ultimaConsulta}</td>
                        <td>${estadoBadge}</td>
                        <td class="flex gap-2">
                            <!-- NOTA: Estas acciones (Editar/Eliminar) requieren endpoints POST/PUT/DELETE que aún no están en tu FastAPI -->
                            <button onclick="showMessage('Función de edición pendiente: El endpoint PUT no está implementado en FastAPI.', 'warning')" class="btn btn-warning py-1 px-2">Editar</button>
                            <button onclick="showMessage('Función de eliminación pendiente: El endpoint DELETE no está implementado en FastAPI.', 'warning')" class="btn btn-danger py-1 px-2">Eliminar</button>
                        </td>
                    `;
                });
            }

            // Actualizar la info de paginación
            const startItem = (currentPage - 1) * ITEMS_PER_PAGE + 1;
            const endItem = Math.min(currentPage * ITEMS_PER_PAGE, totalPacientes);

            document.getElementById('paginationInfo').textContent =
                `Mostrando ${startItem} - ${endItem} de ${totalPacientes} pacientes. (Página ${currentPage} de ${lastPage})`;

            document.getElementById('btnPrev').disabled = currentPage <= 1;
            document.getElementById('btnNext').disabled = currentPage >= lastPage;
        }

        function cambiarPagina(direction) {
            fetchPacientes(currentPage + direction);
        }

        // --- SIMULACIÓN DE OPERACIONES (SIN ENDPOINTS DE FASTAPI) ---

        document.getElementById('pacienteForm').addEventListener('submit', guardarPaciente);

        function nuevoPaciente() {
            document.getElementById('modalTitle').textContent = 'Nuevo Paciente';
            document.getElementById('pacienteId').value = '';
            document.getElementById('pacienteForm').reset();
            // Simulación de fecha actual para campo de consulta
            document.getElementById('ultimaConsulta').valueAsDate = new Date();
            document.getElementById('ultimaConsulta').disabled = false;
            document.getElementById('pacienteModal').style.display = 'flex';
        }

        // Estas funciones requieren que implementes los endpoints POST/PUT/DELETE en FastAPI
        async function guardarPaciente(event) {
            event.preventDefault();
            showMessage(
                "El endpoint POST/PUT para guardar pacientes no ha sido implementado en FastAPI. ¡Debes crear el endpoint y la lógica en Python!",
                'warning');
        }

        function cerrarModal() {
            document.getElementById('pacienteModal').style.display = 'none';
        }

        function logout() {
            showMessage("Simulación de cierre de sesión.");
        }
    </script>
</body>

</html>
