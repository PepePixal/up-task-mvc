<div class="contenedor restablecer">
    <!-- agrega la cabecera de la vista -->
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Teclea tu nuevo Password</p>

        <!-- muestra alertas de restablecer -->
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <!-- mostrar el formulario si la var $mostrar viene como true del render -->
        <?php 
        if ($mostrar) {
        ?>
            <!-- sin action, para que se quede en la url con el token -->
            <form class="formulario" method="POST">

                <div class="campo">
                    <label for="password">Password</label>
                    <input 
                        type="password"
                        id="password"
                        placeholder="Tu nuevo Password"
                        name="password"
                    >
                </div>

                <input type="submit" class="boton" value="Guardar Password">
            </form>

        <?php
        }
        ?>

        <div class="acciones">
            <a href="/crear">¿No tienes cuenta? - Crear Cuenta</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>

    </div> <!-- ..fin contenedor-sm -->
</div> <!-- ..fin contenedor -->
