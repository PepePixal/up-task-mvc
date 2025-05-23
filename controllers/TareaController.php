<?php

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

//No requerimos el Router ya que este controller hace de API,
//que responde a las peticiones Fetch tipo POST, desde tareas.js

class TareaController {
    
    public static function index() {
        //obtiene el id (url) del proyecto, en $_GET
        $proyectoId = $_GET['id'];

        //si no hay un id (url) redirecciona
        if(!$proyectoId) header('Location: /dashboard');

        //busca el proyecto cuyo id (url) se igual a $proyectoId
        $proyecto = Proyecto::where('url', $proyectoId);

        //abrir sesión para obtener el id del usuario logueado
        session_start();

        //si no existe proyecto o el propietarioId no es el usuario logueado
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header ('Location: /404');

        //como existe un $proyecto y el propietarioId es el usuario logueado,
        //obtiene todas las tareas cuyas columnas proyectoId contengan el id del proyecto,
        //obtendremos un arreglo de objetos modelo Tarea
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        //la api retorna un json, obtenido del arreglo tareas con las $tareas
        echo json_encode(['tareas' => $tareas]);

    }

    public static function crear() {
        //si el método de la petición al servidor es tipo POST:
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //inica sesion para poder obtener el id del usuario
            session_start();

            //obtiene el la url del proyecto, recibida en la peticion POST
            $proyectoId = $_POST['proyectoId'];

            //busca el proyecto en la DB, cuya columna url sea igual al valor
            //de la url recibida en $proyectoId
            $proyecto = Proyecto::where('url', $proyectoId);

            //si tras la busqueda, no viene nada (null) en $proyecto o
            //el propietarioId del proyecto no es igual al id del usuario logueado
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
                //retorna la respuesta a la petición, en formato json
                echo json_encode($respuesta);
                //para el código de la función crear
                return;
            }

            //como existe el proyecto buscado y el propietarioId es el usuario logueado.
            //Nueva instancia del modelo Tarea, enviando el nombre y el proyectoId, de latarea
            //que estan en $_POST, el resto de campos, id es automático y estado tiene valor inicial 0
            $tarea = new Tarea($_POST);

            //cambia la url en $tarea->proyecotId, por el id de $proyecto->id encontrado en la DB,
            //antes de almacenarlo en la tabla tareas de la DB
            $tarea->proyectoId = $proyecto->id;

            //almacena el modelo tarea en la DB
            $resultado = $tarea->guardar();

            //genera una alerta tipo exito
            $respuesta = [
                'tipo' => 'exito',
                'mensaje' => 'Tarea Creada Correctamente',
                'id' => $resultado['id'],
                'proyectoId' => $proyecto->id
            ];

            //retorna la respuesta a la petición, en formato json
            echo json_encode($respuesta);
        }
    }

    public static function actualizar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //valida q el proyectoId (url) recibido en la petición, existe en DB
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            //inica sesion para poder obtener el id del usuario
            session_start();

            //si tras la busqueda, no viene nada (null) en $proyecto o
            //el propietarioId del proyecto no es igual al id del usuario logueado
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];
                //retorna la respuesta a la petición, en formato json
                echo json_encode($respuesta);
                //para el código de la función crear
                return;
            }

            //como existe el proyecto buscado y el propietarioId es el usuario logueado.
            //Nueva instancia del modelo Tarea, enviando el contenido de $_POST: 
            //el id, nombre, el nuevo estado y el proyectoId (url), de latarea
            $tarea = new Tarea($_POST);
            //cambia la url de proyectoId por el correspondiente id del pryecto encontrado
            $tarea->proyectoId = $proyecto->id;
            //método que guarda (actualiza) la tarea en la DB y retorna resultado
            $resultado = $tarea->guardar();          
            //si existe resultado es ok, genera un arespuesca con alerta exito
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Tarea actualizada Correctamente'
                ];
                //retorna, respuesta a la petición, en tipo json
                echo json_encode(['respuesta' => $respuesta]);
            }            
        }
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //valida q el proyectoId (url) recibido en la petición, existe en DB
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            //inica sesion para poder obtener el id del usuario
            session_start();

            //si tras la busqueda, no viene nada (null) en $proyecto o
            //el propietarioId del proyecto no es igual al id del usuario logueado
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al eliminar la tarea'
                ];
                //retorna la respuesta a la petición, en formato json
                echo json_encode($respuesta);
                //para el código de la función crear
                return;
            }
            //como existe el proyecto buscado y el propietarioId es el usuario logueado:
            //Nueva instancia del modelo Tarea (arreglo), enviando el contenido de $_POST: 
            //el id, nombre, el nuevo estado y el proyectoId (url), de latarea
            $tarea = new Tarea($_POST);
            //cambia la url de proyectoId por el correspondiente id del pryecto encontrado
            $tarea->proyectoId = $proyecto->id;
            //elimina la tareas en la DB, con el método de ActiveRecord eliminar()
            $resultado = $tarea->eliminar();

            //arreglo para enviar el resultado, un mensaje personalizado y el tipo
            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' => 'exito'
            ];

            //envia la respuesta a la petición, en formato json
            echo json_encode($resultado);
        }
    }
}