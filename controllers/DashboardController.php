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

        //obtiene el id del usuario logueado
        $id = $_SESSION['id'];
        //llama método que obtendrá todos los proyectos cuyo valor
        //de la columna propeitarioId, sea igual la id del usuario logueado
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            //para la pestaña y para el título Proyectos
            'titulo' => 'Proyectos',
            //pasa todos los proyectos encontrados, a la vista index.php
            'proyectos' => $proyectos
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

    public static function proyecto(Router $router) {
        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();
        //comprobar si el usuario está logueado
        isAuth();

        //Revisar que el usuario que visita el proyecto, es quien lo creó.
        //Obtener de $_GET, la url única (token) que viene en la url proyecto?id=.. 
        $token = $_GET['id'];
        //Si no viene la url (token), redirigir a /dashboard, para que no vea el proyecto
        if(!$token) header('Location: /dashboard');
        //obtener el proyecto de la DB, cuyo valor de la columna 'url' de la tabla proyectos,
        //sea la url (token) que tenemos en $_GET
        $proyecto = Proyecto::where('url', $token);
        //validar si el valor de propietarioId del proyecto obtenido de la DB, en $proyecto,
        //es igual al id del usuario de la $_SESSION.
        //Si no es igual:
        if ($proyecto->propietarioId !== $_SESSION['id']) {
            //redirigir al usuario al /dashboard, para que no vea el proyecto
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            //para la pestaña y el título de la página
            'titulo' => $proyecto->proyecto,
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