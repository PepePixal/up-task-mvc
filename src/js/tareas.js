//código JS dentro de una función IFIE Immediately Invoked Function Expressions
//o Funciones que se invocan inmediatamente, para proteger sus variables y funciones
(function () {
    //llama función obtenerTareas()
    obtenerTareas();

    //Codificación del botón para mostrar el Modal de Agreger tarea.
    //Selecciona el botón por su id y lo asigna
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    //agrega un evento click al botón y la función que ejecutará
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    //función que consulta a la api para obtener las tareas de un proyecto id url
    async function obtenerTareas() {
        try {
            //obtiene la url del proyecto, de la url del endpoint y lo asigna a id
            const id = obtenerProyecto();
            //define la url del servidor endpoint api, con el id (url) según el proyecto
            const url = `/api/tareas?id=${id}`;
            //solicitud http con fetch(), por defecto tipo GET, al servidor endpoint api
            const respuesta = await fetch(url);
            //convierte el cuerpo de la respuesta (response body) a formato json
            const resultado = await respuesta.json();
            //obtiene solo el arreglo index tareas (objetos), del json resultado
            tareas = resultado.tareas;
            //llama funcion, enviando el arreglo de tareas
            mostrarTareas(tareas);

        } catch (error) {
            console.log(error);
        }
        
    }

    function mostrarTareas(tareas) {

        //si NO hay tareas en tareas
        if(tareas.length === 0) {
            //selecciona el elemento html con id listado-tareas,UL en proyecto.php
            const contenedorTareas = document.querySelector('#listado-tareas');
            //crea un elemento html LI
            const textoNoTareas = document.createElement('LI');
            //agrega el texto al contenido del LI creado
            textoNoTareas.textContent = 'No Hay Tareas'
            //agrega la clase no-tareas al elemento LI, para dar estilo css
            textoNoTareas.classList.add('no-tareas');
            //agrega el elmento textoNoTarea con el texto y la clase, al contenedorTareas (UL)
            contenedorTareas.appendChild(textoNoTareas);
            //par ael código de la función
            return;
        }

        //objeto con estados de las tareas, para mostrarlos con texto
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        //como el arreglo tareas SI contiene tareas (objetos), 
        //itera con forEach, genera los li y los inyecta en UL de proyecto.php
        tareas.forEach(tarea => {
            //crea elemento html LI
            const contenedorTarea = document.createElement('LI');
            //agrega atributo tipo dataset tareaId, con valor tarea.id, al elemento LI
            contenedorTarea.dataset.tareaId = tarea.id;
            //agrega clase tarea, al elemento LI, para estilo css
            contenedorTarea.classList.add('tarea');

            //crea elemento html párrafo P
            const nombreTarea = document.createElement('P');
            //agrega el valor la propiedad nombre del objeto tarea, como texto al P
            nombreTarea.textContent = tarea.nombre;
            
            //crea elemento html DIV para las opciones
            const opcionesDiv = document.createElement('DIV');
            //agrega clase opciones al elemento DIV
            opcionesDiv.classList.add('opciones');
            
            //crea botón para el estado de cada tarea
            const btnEstadoTarea = document.createElement('BUTTON');
            //agrega clase estado-tarea al botón
            btnEstadoTarea.classList.add('estado-tarea');
            //toma el valor del objeto estados, según el estado de la tarea,
            //lo convierte todo a minúsculas y lo asigna como clase al botón
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            //agrega contenido al botón,
            //el valor del objeto estados, se obtiene del estado de la tarea
            btnEstadoTarea.textContent = estados[tarea.estado];
            //agrega atributo tipo dataset, tomando el valor de la tarea 0 ó 1
            btnEstadoTarea.dataset.estadoTarea = tarea.estado

            //crea botón eliminar para cada tarea
            const btnEliminarTarea = document.createElement('BUTTON');
            //agrega class eliminar-tarea al button
            btnEliminarTarea.classList.add('eliminar-tarea');
            //agrega atributo dataset según el valor del id de la tarea
            btnEliminarTarea.dataset.idTarea = tarea.id;
            //agrega texto al contenido del botón
            btnEliminarTarea.textContent = 'Eliminar';
            
            //agrega el botón btnEstadoTarea como hijo, al div opcionesDiv 
            opcionesDiv.appendChild(btnEstadoTarea);
            //agrega el botón btnEliminarTarea como hijo, al div opcionesDiv 
            opcionesDiv.appendChild(btnEliminarTarea);

            //agrega el P nombreTarea a cada LI contenedorTarea
            contenedorTarea.appendChild(nombreTarea);
            //agrega el DIV opcionesDiv con los botones, a cada LI contenedorTarea
            contenedorTarea.appendChild(opcionesDiv);

            //selecciona elemento UL por su id listado-tareas, en proyecto.php
            const listadoTareas = document.querySelector('#listado-tareas');
            //inserta el contendor DIV contenedorTarea, al elemento UL en proyecto.php
            listadoTareas.appendChild(contenedorTarea);

        });
    }


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
            //contiene la class 'cerrar-modal' (en este caso el boton cerrar):
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

        //Consultar al servidor, para añadir una nueva tarea al pryecto.
        //Usaremos una función asíncrona, para hacer la petición usando await
        async function agregarTarea(tarea) {
            //FormData() es el objeto necesario para enviar datos de formulario HTML, en JS
            //Construir la petición:
            //nueva instancia de FormData(), retorna objeto vacio
            const datos = new FormData();
            //agregar datos ('clave', 'valor') al objeto datos
            datos.append('nombre', tarea);
            //agrega clave proyectoId y como valor la url obtenida con el método obtenerProyecto()
            datos.append('proyectoId', obtenerProyecto());

            //petición al servidor con try catch, si hay error de conexión,
            //no se para el código.
            try {
                //defina la url del servidor api, hacia donde hacemos la petición,
                //en nustro caso localhost y el endpoint api/tarea, del index.php
                const url = 'http://localhost:3000/api/tarea';
                //define la petición fetch() y espera (await) la respuesta retornada
                const respuesta = await fetch(url, {
                    //indica método POST ya que el método por defecto es get (obtener)
                    method: 'POST',
                    //los datos enviados van en el body de la petición fetcth
                    body: datos
                });

                //convierte el cuerpo de la respuesta (response body) a formato json
                const resultado = await respuesta.json();

                //muestra alerta en la ventana modal, si la api lo retorna por no 
                //haber encontrado ningun proyecto en la DB
                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

                //si el resultado de la api ha sido una alerta tipo exito
                if(resultado.tipo === 'exito') {
                    //selecciona la ventana con clase .modal y a los 3 segundos la cierra
                    const modal = document.querySelector('.modal');
                    setTimeout(() => {
                        modal.remove();
                    }, 3000);
                }

            } catch (error) {
                //motrar el error de conexión, si se ha producido
                console.log(error);
            }

        }

    }

    //función para obtener la url del proyecto, desde la URL activa
    function obtenerProyecto() {
            //obtiene el ?id=..., de la url de la ventana activa,
            //en nuestro caso la url corresponde a un proyecto concreto.
            //window.location.search devuelve la parte de la URL actual que contine
            //los parámetros de busqueda, es decir, lo que está despues de ? en la url,
            //URLSearchParams() crea un objeto que permite trabajar con los parámetros de búsqueda
            const proyectoParams = new URLSearchParams(window.location.search);
            
            //al igual que un FormData(), el objeto en proyectoParams no se puede iterar,
            //para acceder a la información requerimos el método Object.fromEntries().
            //.entries() devuelve un iterable de pares clave-valor del objeto en proyectoPrarams,
            //Object.fromEntriens() convierte el iterable de pares clave-valor en un objeto JavaScript
            const proyecto = Object.fromEntries(proyectoParams.entries());
            
            return proyecto.id;
    }

})();