<?php

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();

        //comprobar si el usuario está logueado
        isAuth();

        $router->render('dashboard/index', [
            
        ]);
    }
}