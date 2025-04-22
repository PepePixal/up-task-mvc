<div class="contenedor login">
    <!-- agrega la cabecera de la vista -->
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Inicia Sesión</p>

        <!-- imprime alertas de error de validación, si existen -->
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POST" action="/">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="text"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                >
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                >
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿No tienes cuenta? - Crear Cuenta</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>

    </div> <!-- ..fin contenedor-sm -->
</div> <!-- ..fin contenedor -->
