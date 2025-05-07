<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
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
        //var arreglo vacio para almacenar las alertas y enviarlas a la vista
        $alertas = [];

        //obtiene el id del usuario logueado, en $_SESSION
        $usuario = Usuario::find($_SESSION['id']);

        //si el método de la consulta al servidor es tipo POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincroniza los datos de $usuario con los nuevos en $_POST,
            //actualizando con nuevo nombre y o email recibido en $_POST
            $usuario->sincronizar($_POST);

            //llama metodo para validar los datos recibidos en POST
            $alertas  = $usuario->validar_perfil();
            
            //is aler4tas está vacio, ha pasado la validación
            if(empty($alertas)) {
                //busca en la columna mail de la tabla de la DB,
                //si existe un usuario con el nuevo email del form
                $existeUsuario = Usuario::where('email', $usuario->email);

                //valida, si ya existe un usuario con el nuevo email, en la DB y
                //el id del usuario es diferente al id del usuario logueado
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //crea mensaje de alerta exito, con el modelo Usuario
                    Usuario::setAlerta('error', 'Ya existe un Usuario con este email');
                    //obtiene la alerta y la asigna a $aletas
                    $alertas = $usuario->getAlertas();
                //si no existe ningun usuario con el email, en la DB
                } else {
                    //guardar/actualizar el usuario con los nuevos datos, en la DB
                    $usuario->guardar();

                    //crea mensaje de alerta exito, con el modelo Usuario
                    Usuario::setAlerta('exito', 'Actualizado Correctamente');
                    //obtiene la alerta y la asigna a $aletas
                    $alertas = $usuario->getAlertas();

                    //actualizar el nombre de usuario de la sesión, por el nuevo,
                    //para que se actualice el nombre en la barra superior
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            //titulo para la pestaña y el título de la pagina
            'titulo' => 'Perfil',
            //envia el arreglo usuario con todos los datos
            'usuario' => $usuario,
            //envia alertas a la vista
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {
        //inicia sesión, para poder obtener datos de $_SESSION
        session_start();
        //comprobar si el usuario está logueado
        isAuth();
        //var arreglo vacio para almacenar las alertas y enviarlas a la vista
        $alertas = [];

        //si el método de la consulta al servidor es tipo POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //obtiene los datos de la DB, del usuario logueado, por su id
            $usuario = Usuario::find($_SESSION['id']);

            //sincronzar los datos de $usuario de la DB con los nuevos datos del $_POST
            $usuario->sincronizar($_POST); 

            //llama método para validar el nuevo password
            $alertas = $usuario->nuevo_password();

           //si $alertas está vacio, el nuevo_password ha pasado la validación
           if(empty($alertas)) {
                //llama método que comprueba si el pass actual es el del usuario logueado,
                //el método retorna bool, true o false
                $resultado = $usuario->comprobar_password();

                //si resultado es true, el pass actual es correcto
                if($resultado) {
                    //asignar el nuevo_password del form, al password del objto $usuario
                    $usuario->password = $usuario->password_nuevo;

                    //elimina propiedades del objeto $usuario, no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    unset($usuario->password2);

                    //llama método que hashea el nuevo password de $usuario
                    $usuario->hashPassword();

                    //guardar / actualizar el usuario en la DB
                    $resultado = $usuario->guardar();

                    //si el es correcto, true
                    if ($resultado) {
                        //crea mensaje de alerta exito, con el modelo Usuario
                        Usuario::setAlerta('exito', 'Actualizado Correctamente');
                        //obtiene la alerta y la asigna a $aletas
                        $alertas = $usuario->getAlertas();
                    }

                //si el pass actual NO es correcto
                } else {
                    //crea mensaje de alerta error, con el modelo Usuario
                    Usuario::setAlerta('error', 'El Password actual Incorrecto');
                    //obtiene la alerta y la asigna a $aletas
                    $alertas = $usuario->getAlertas();
                }
           }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas,
        ]);

    }
}