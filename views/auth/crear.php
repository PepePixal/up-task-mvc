<div class="contenedor crear">
    <!-- agrega la cabecera de la vista -->
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <!-- agrega mensajes de validación si los hay -->
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>


        <form class="formulario" method="POST" action="/crear">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nonbre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                />
            </div>
            
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                    value="<?php echo $usuario->email; ?>"
                />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Mínimo 6 carácteres"
                    name="password"
                />
            </div>
            
            <div class="campo">
                <label for="password2">Confirmar Password</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu Password"
                    name="password2"
                />
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes Cuenta? - Inicia Sesión</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>

    </div> <!-- ..fin contenedor-sm -->
</div> <!-- ..fin contenedor -->
