<?php

namespace Controllers; 

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

        // Render a la vista enviando crear.php y datos
        $router->render('auth/olvide', [
            'titulo' => 'Restaurar Password',
        ]);
    }

    public static function restablecer(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista enviando crear.php y datos
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Password',
        ]);
    }
    
    public static function mensaje(Router $router) {

        // Render a la vista enviando crear.php y datos
        $router->render('auth/mensaje', [
            'titulo' => 'Aviso Mensaje',
        ]);
    }
    
    public static function confirmar(Router $router) {

        // Render a la vista enviando crear.php y datos
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta',
        ]);
       
    }


}

