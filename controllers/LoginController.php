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

