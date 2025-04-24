<?php

namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    //declaración de propiedades, obligatoria a partir de PHP 8.2
    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }
    
    //método para validar los datos del form proyecto
    public function validarProyecto() {
        //si no viene nada en proyecto (nombre del proyecto)
        if(!$this->proyecto) {
            //crea arreglo indexado 'error' y el mensaje en la posición final,
            //dentro del arreglo asoc $alertas
            self::$alertas['error'][] = 'El Nombre del Proyecto es Obligatorio';
        }

        return self::$alertas;
    }
}