
<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button
                class="agregar-tarea"
                type="button"
                id="agregar-tarea"
            >&#43; Nueva Tarea</button>
        </div>
    </div>

    <div id="filtros" class="filtros">
        <div class="filtros-inputs">
            <h3>Filtros:</h3>
            <div class="campo">
                <label for="todas">Todas</label>
                <input 
                    type="radio"
                    id="todas"
                    name="filtro"
                    value=""
                    checked
                />
            </div>
            
            <div class="campo">
                <label for="completadas">Completadas</label>
                <input 
                    type="radio"
                    id="completadas"
                    name="filtro"
                    value="1"
                />
            </div>

            <div class="campo">
                <label for="pendientes">Pendientes</label>
                <input 
                    type="radio"
                    id="pendientes"
                    name="filtro"
                    value="0"
                />
            </div>
        </div>
    </div>

    <!-- para mostrar las tareas, con html inyectado desde tareas.js -->
    <ul id="listado-tareas" class="listado-tareas"></ul>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<!-- define el valor de la var $script, para la vista principal layout.php -->
<?php
    //la sintaxsis .= hace que estos scripts de la variable $script,
    //se concatenen a otros scripts y no se sobrescriban,
    //no se sobrescriban al script de la variable $scirpt en footer-dashboard.php
    $script .= '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tareas.js"></script>
    ';
?>