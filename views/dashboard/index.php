
<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <!-- comprobar si hay proyectos del usuario logueado -->
    <!-- la función php count() cuenta los elementos de un arreglo -->
    <?php if (count($proyectos) === 0) { ?>

        <p class="no-proyectos">No tienes ningún Proyecto - <a href="/crear-proyecto">Crea un Proyecto</a> </p>

    <?php } else { ?>

        <ul class="listado-proyectos">
            <!-- itera $proyectos y por cada $proyecto genera una linea li y  
             con enlace a la url proyecto, con el ?id= a la url (token) del proyecto  -->
            <?php foreach($proyectos as $proyecto) { ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        <?php echo $proyecto->proyecto; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>

    <?php } ?>



<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
