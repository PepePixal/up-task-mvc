
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

    <!-- para mostrar las tareas, con html inyectado desde tareas.js -->
    <ul id="listado-tareas" class="listado-tareas"></ul>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<!-- define el valor de la var $script, para la vista principal layout.php -->
<?php
    //para usar las ventanas de alertas de la libreria sweetalert
    $script = '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tareas.js"></script>
    ';
?>