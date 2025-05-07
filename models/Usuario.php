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
    public $token;
    public $confirmado;
    public $password2; //solo para la validación del formulario
    public $password_actual; //solo para la validación del form cambiar password
    public $password_nuevo; //solo para la validación del form cambiar password

    //constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;

        //solo para la validación del form nuevo usuario
        $this->password2 = $args['password2'] ?? '';
    
        //solo para la validación del form cambiar password
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
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

    //Valida el nuevo password, retorna : array
    public function validarPassword() : array {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener mínimo 6 carácteres';
        }

        return self::$alertas;
    }

    // Valida los datos del form para cambio de perfil, retorna : array
    public function validar_perfil() : array {
        //si no viene contenido en nombre
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nobre es Obligatorio';
        }
        //si no viene contenido en email
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        
        //retorna las alertas
        return self::$alertas;
    }

    // Valida los passwords actual y nuevo del form, retorna : array
    public function nuevo_password() : array {
        //si password_actual viene vacio
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password actual es Obligatorio';
        }
        //si password_actual viene vacio
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password nuevo es Obligatorio';
        }
        //si el número de carácteres del password_nuefo es > 6
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe tener mínimo 6 carácteres';
        }

        return self::$alertas;
    }

    // comprueba que el pass actual corresponda al del usuario logueado,
    // retorna : bool
    public function comprobar_password() : bool {
        //retorna resultado de verificar, con la función de php password_verify,
        //si el pass actual es igual al pass ya hasheado del usuario, retorna bool
        return password_verify($this->password_actual, $this->password);
    }

    // Hashea el password, no retorna nada : void
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token úncico de 13 caracteres, no retorna nada : void
    public function generarToken() : void {
        $this->token = uniqid();
    }

}