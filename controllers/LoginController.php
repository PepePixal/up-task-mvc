<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {

    public static function login(Router $router) {
        //define arreglo vacio $alertas
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //nueva instancia del objeto Usuario, enviando datos en $_POST,
            $usuario = new Usuario($_POST);

            //llama método validarLogin() en models/Usuario.php, retorna alertas
            $alertas = $usuario->validarLogin();

            //si no hay alertas de error de validación
            if(empty($alertas)) {
                //buscar el usuario por el email, en la DB
                $usuario = Usuario::where('email', $usuario->email);

                //si usuario no ! existe o no ! está confirmado
                if(!$usuario || !$usuario->confirmado) {
                    //genera alerta de error
                    Usuario::setAlerta('error', 'El Usuario no esxiste o No está Cofirmado');
                } else {
                    //como el usuario existe y está confirmado, verificar el password,
                    //del formulario (en $_POST), con el password hasheado en la DB (en $usuario)
                    if( password_verify($_POST['password'], $usuario->password)) {
                        //como la contraña está verificada, iniciar sesión
                        session_start();
                        //agregar datos del usuario, al arreglo asoc super global $_SESSION
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        //crear elemento login en el arreglo
                        $_SESSION['login'] = true;

                        //redireccionar al end-point /dashboard (panel de control)
                        header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Contraseña no valida');
                    }
                }


            } 

        }

        //obtiene las alertas, en memeria $alertas
        $alertas = Usuario::getAlertas();

        // Render a la vista enviando login.php y datos
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
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
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //instancia objeto $usuario enviando $_POST
            $usuario = new Usuario($_POST);
            //llama método validarEmail() en models/Usuario.php
            $alertas = $usuario->validarEmail();

            //si $alertas está vacio, el email ha pasado la validación
            if(empty($alertas) ) {
                //buscar el usuario por el email, en la DB
                $usuario = Usuario::where('email', $usuario->email);
                
                //si el usuario existe y está confirmado
                if($usuario && $usuario->confirmado === "1") {
                    //generar un nuevo token
                    $usuario->generarToken();
                    
                    //eliminar la propiedad password2
                    unset($usuario->password2);
                    
                    //Actualizar los datos del usuario
                    $usuario->guardar();
                    
                    //Enviar email con nuevo token al usuario, para confirmar.
                    //Nueva instancia de la clase Email, enviando argumentos
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    //llama método enviarConfirmacion() que envia el email
                    $email->enviarInstrucciones();

                    //Generar alerta de exito
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                } else {
                    //genera alerta de error llamando al método
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
                } 
            }
        }

        //obtener alertas de error o de exito
        $alertas = Usuario::getAlertas();

        // Render a la vista enviando olvide.php y datos
        $router->render('auth/olvide', [
            'titulo' => 'Restaurar Password',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router) {

        //obtiene el token de $_GET, lo sanitiza s
        $token = s($_GET['token']);

        //var para ocultar el form si hay alerta de token no valido
        $mostrar = true;

        //si no vienen token, redirigir al usuario
        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        //si no existe usuario con el token, genera alerta error
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No válido');
            //para ocultar el form de restablecer.php
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincroniza el $usuario con los nuevos datos del post (password)
            $usuario->sincronizar($_POST);

            //llama método validarPassword() que retorna $alertas
            $alertas = $usuario->validarPassword();

            //si no hay alertas de error, tras la validación
            if(!$alertas) {
                //hashear el nuevo password
                $usuario->hashPassword();
                //Eliminar el valor de token del usuario ya confirmado
                $usuario->token = "";
                //eliminar la propiedad password2
                unset($usuario->password2);
                //guardar (actualizar) nuevos datos del usuario
                $resultado = $usuario->guardar();

                if ($resultado) {
                    //redireccionar al usuario, al login raiz
                    header('Location: /');
                }
            }
        }

        //obtiene las alertas
        $alertas = Usuario::getAlertas();

        // Render a la vista enviando restablecer.php y datos
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
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

