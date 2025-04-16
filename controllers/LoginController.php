<?php

namespace Controllers; 

use MVC\Router;

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

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista enviando crear.php y datos
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
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

