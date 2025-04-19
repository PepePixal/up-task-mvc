<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {

    public static function login(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista enviando login.php y datos
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
        ]);

    }

    public static function logout() {
        echo "Desde LOGOUT";
        
    }

    public static function crear(Router $router) {
        //definir arrelgo alertas para que render no de error antes del POST
        $alertas = [];

        //instancia nuevo modelo de usuario, retorna objeto vacio
        $usuario = new Usuario;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincroniza el objeto vacio con los datos de $_POST
            $usuario->sincronizar($_POST);

            //llama método validar $usuario sincronizado,
            //retorna arreglo con alertas
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                //comprobar si el email del formulario ya existe en la tabla de ususrios
                //el método static where, requiere la columna de la tabla y el valor a buscar,
                //la tabla la toma del modelo class Usuario,
                //retorna true o false a $eixisteUsuario
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'Ya existe un Usuario registrado con ese email');
                    $alertas = Usuario::getAlertas();
                } else {
                    //llama método hashPassword del la clase Usuario en una instancia $usuario
                    $usuario->hashPassword();
                    //eliminar la propiedad password2, no se requier para la DB
                    unset($usuario->password2);
                    //llama método generarToken
                    $usuario->generarToken();

                    // Guardar el nuevo usuario en al DB
                    // el método guardar() retorna bool y un id
                    $resultado = $usuario->guardar();

                    //Enviar email con token al usuario para confirmar
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    //llama método enviarConfirmacion() por email
                    $email->enviarConfirmacion();
                    
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }      
            } 
        }

        // Render de la vista crear.php y datos
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    
    public static function olvide(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista enviando olvide.php y datos
        $router->render('auth/olvide', [
            'titulo' => 'Restaurar Password',
        ]);
    }

    public static function restablecer(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista enviando restablecer.php y datos
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Password',
        ]);
    }
    
    public static function mensaje(Router $router) {

        // Render a la vista enviando mensaje.php y datos
        $router->render('auth/mensaje', [
            'titulo' => 'Aviso Mensaje',
        ]);
    }
    
    public static function confirmar(Router $router) {

        //obtiene el token de la url...?token=, en $_GET y lo sanitiza s
        $token = s($_GET['token']);

        //si no hay token, redirige a la página principal /
        if(!$token) header('Location: /');

        //Buscar si existe un usuario en la DB con el token
        $usuario = Usuario::where('token', $token);

        //Si en la DB no existe el usuario con el token recibido,
        //llama al método que genera una alerta en el arreglo $alertas
        if(empty($usuario)) {
            //genera alerta de error
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // El toquen es correcto:
            //Asignar 1 a la propiedad confirmado, del objeto usuario
            $usuario->confirmado = 1;
            //Eliminar el token del usuario ya confirmado
            $usuario->token = "";
            //Eliminar la propiedad password2 del objeto $usuario
            unset($usuario->password2);
            //llama método guardar() que actualizará la nueva info del usuario,
            //en la tabla de la DB
            $usuario->guardar();

            //genera alerta de exito
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');

        }
        
        //llama al método que obtiene el arreglo alertas y lo asigna a $alertas
        $alertas = Usuario::getAlertas();

        // Render a la vista enviando confirmar.php y datos
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta',
            'alertas' => $alertas
        ]);
       
    }


}

