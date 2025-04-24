<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router) {
        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();
        //comprobar si el usuario está logueado
        isAuth();

        $router->render('dashboard/index', [
            //para la pestaña y para el título Proyectos
            'titulo' => 'Proyectos',
        ]);
    }

    public static function crear_proyecto(Router $router) {
        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();
        //comprobar si el usuario está logueado
        isAuth();
        //define alertas como arreglo vacio
        $alertas = [];

        //si el método de petición en $_SERVER ha sido POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //instancia del modelo Proyecto, enviando $_POST, retorna objeto
            $proyecto = new Proyecto($_POST);

            //validación del formulario crear-proyecto, retorna $alertas 
            $alertas = $proyecto->validarProyecto();

            //si $alertas viene vacio, ha pasado la validación
            if(empty($alertas)) {
                // Generar una URL (token) única, con las funcines php uniqid() y md5(),
                // md5() que requiere un string y retorna otro string de 32 carácteres,
                // el string que requiere lo genera automáticamente con uniqid()
                $hash = md5(uniqid());
                //asigna a $proyecto->url la url única generada
                $proyecto->url = $hash;
    
                //Obtener el usuario creador del proyecto en $_SESSION['id']
                $proyecto->propietarioId = $_SESSION['id'];
                
                //guardar el proyecto
                $proyecto->guardar();

                //Redirecciona a la vista proyecto, mostrando el proyecto creado
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            //para la pestaña y para el título de la pagina
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas,
        ]);
    }

    public static function perfil(Router $router) {

        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();

        //comprobar si el usuario está logueado
        isAuth();

        $router->render('dashboard/perfil', [
            //para la pestaña y para el título de la pagina
            'titulo' => 'Perfil',
        ]);
    }
}