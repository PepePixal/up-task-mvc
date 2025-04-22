<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    //definicion de propiedades
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2; //solo para la validación del formulario
    public $token;
    public $confirmado;

    //constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        //solo para la validación del formulario
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //validación del login (email y password)
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }

        //filtrar que sea un email válido con el método php filter_var(),
        //que retorna true si el email tienen un formato válido
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;

    }

    //validación de nueva cuenta cuando se envia con POST
    public function validarNuevaCuenta() {
        //si nombre esta vacio o no tiene valor
        if(!$this->nombre) {
            //serf, porque $alertas está definida en la clase que extiende, ActiveRecord.
            //:: porque $alertas está definida como static.
            //si no existe, agrega el arreglo idexado 'error' y un elemento con el mensaje
            //si el arreglo 'error' ya existe, le agrega un nuevo elemento con el mensaje
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener mínimo 6 carácteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'Los Passwords son diferentes';
        }

        return self::$alertas;
    }

    // Valida un email
    public function validarEmail() {
        //si no hay valor en $this->email 
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        
        //filtrar que sea un email válido con el método php filter_var(),
        //que retorna true si el email tienen un formato válido
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        return self::$alertas;
    }

    //Valida el nuevo password
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener mínimo 6 carácteres';
        }

        return self::$alertas;
    }

    // Hashea el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token úncico de 13 caracteres
    public function generarToken() {
        $this->token = uniqid();
    }

}