<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2>UpTask</h2>
        <div class="cerrar-menu">
            <img id="cerrar-menu" src="build/img/cerrar.svg" alt="imagen cerrar">
        </div>

    </div>

    <nav class="sidebar-nav">
        <!-- operador ternario condicional de php, para las class de los <a href > : -->
        <!-- si la var $titulo es = 'Proyectos' asigna ? 'activo' a la clase, de lo contrario : asigna '' -->
        <!-- podremos dar estilo css (_sidebar.scss) a los <a href> que tengan la clase 'activo' -->
        <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear Proyecto') ? 'activo' : ''; ?>" href="/crear-proyecto">Crear Proyecto</a>
        <a class="<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>
</aside>