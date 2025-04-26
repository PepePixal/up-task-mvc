//código JS dentro de una función IFIE Immediately Invoked Function Expressions
//o Funciones que se invocan inmediatamente, para proteger sus variables y funciones
(function () {
    //Codificación del botón para mostrar el Modal de Agreger tarea.
    //Selecciona el botón por su id y lo asigna
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    //agrega un evento click al botón y la función que ejecutará
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        //crea elemento div, de hml y lo asigna a modal
        const modal = document.createElement('DIV');
        //agrega la clase 'modal' al elemento div en modal
        modal.classList.add('modal');
        //agrega código html al div que contiene la var modal,
        //usamos template string ` ` para poder codificar html en varias lineas
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Agrega una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder="Agrega nueva tarea"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Agregar Tarea" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;
        
        //En JavaScript el setTimeout() es lo último que se ejecuta dentro de la función.
        //agrega la clase animar al formulario a los x segundos,
        //clase que hace que se muestre la ventana modal, según su animacion css
        setTimeout(() => {
            //selecciona el elemento con clase .formulari y lo asigna a formulario
            const formulario = document.querySelector('.formulario');
            //agrega la clase animar al elemento formulario
            formulario.classList.add('animar');
        }, 0);
        
        //como modal es un elemento html (div), podemos asignarle un evento
        modal.addEventListener('click', function(e) {
            //por defecto el input tipo submit envia formulario y cierra la ventana modal,
            //para prevenir las acciones por defecto del evento en e:
            e.preventDefault();

            //En JS delegation, es conocer sobre que elemento se está ejecutando el evento.
            //Si el elemento que genera el evento (que obtenemos de e.target), 
            //contiene la class 'cerrar-modal' (en este caso el boton cerrar)
            if(e.target.classList.contains('cerrar-modal')){
                //selecciona el elemento con la clase .formulario y lo asigna a formulario
                const formulario = document.querySelector('.formulario');
                //agrega la clase cerrar al elemento formulario, para darle estilo css
                formulario.classList.add('cerrar');
                //usamos el setTiemout() para que se ejecute lo último
                setTimeout(() => {
                    //elimina el div de modal, por tanto, cierra la ventana modal,
                    modal.remove();
                }, 500);
            }

            //Si el elemento que genera el evento (que obtenemos de e.target), 
            //contiene la class 'submit-nueva-tarea' (en este caso el boton Crear Tarea)
            if(e.target.classList.contains('submit-nueva-tarea')) {
                //llama a la función
                submitFormularioNuevaTarea();
            }
        })

        //imprimir la ventana modal
        //selecciona el elemento con class 'dashboard' y 
        //le agrega el código html de la var modal, como hijo 
        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea() {
        //obtiene el value (la info) del input con id tarea, del formulario,
        //la función trim() elimina los posibles espacios en blanco antes y despues
        const tarea = document.querySelector('#tarea').value.trim();

        //si el valor de tarea es un string vacio ''
        if(tarea === '') {
            //llama función, enviando argumentos, para el arguento referencia
            //enviaremos la posición dentro del html form, donde queremos mostrar la alerta
            mostrarAlerta('El nombre de la Tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
            //parar el código aquí 
            return;
        }

        //si ha pasado la validación anterior, llama al método, enviando tarea
        agregarTarea(tarea);

        //Muestra un mensaje en la interfaz, requiere argumentos
        function mostrarAlerta(mensaje, tipo, referencia) {
            //Prevenir la creación de multiples alertas.
            //Selecciona el elemento con clase alerta, si ya existe, y lo asigna
            const alertaPrevia = document.querySelector('.alerta');
            //Si existe una alerta previa
            if(alertaPrevia) {
                //elimina el elemto con clase alerta
                alertaPrevia.remove();
            }

            //crea elemento div y lo asigna a alerta
            const alerta = document.createElement('DIV');
            //agrega a alerta la clases: 'alerta' y lo que venga en tipo
            alerta.classList.add('alerta', tipo);
            //agrega a alerta lo que venga en mensaje
            alerta.textContent = mensaje;

            //Inserta la alerta, antes del elemento legned del form
            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

            //Elimina la alerta automáticamente a los 3 segundos
            setTimeout(() => {
                alerta.remove();
            }, 3000);

        };

        //Consultar al servidor para añadir una nueva tarea al pryecto
        function agregarTarea(tarea) {

        }

    }

})();