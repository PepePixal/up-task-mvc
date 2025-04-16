<div class="contenedor olvide">
    <!-- agrega la cabecera de la vista -->
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Restaurar Password</p>

        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="text"
                    id="email"
                    placeholder="Tu Email de usuario"
                    name="email"
                />
            </div>

            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes Cuenta? - Inicia Sesión</a>
            <a href="/crear">¿No tienes cuenta? - Crear Cuenta</a>
        </div>

    </div> <!-- ..fin contenedor-sm -->
</div> <!-- ..fin contenedor -->
