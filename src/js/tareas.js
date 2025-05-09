//código JS dentro de una función IFIE Immediately Invoked Function Expressions
//o Funciones que se invocan inmediatamente, para proteger sus variables y funciones
(function () {

    //define una variable global, que se requerirá en diferentes funciones,
    //inicializada como arreglo vacio.
    let tareas = [];
    //variable global filtradas par las tareas, inicializada arrglo vacio
    let filtradas = [];

    //llama función obtenerTareas()
    obtenerTareas();

    //Codificación del botón para mostrar el Modal de Agreger tarea.
    //Selecciona el botón por su id y lo asigna
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    //agrega un evento click al botón y la función que ejecutará
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario();
    });

    //Filtros de búsqueda
    //Selecciona todos los filtros, inputs con id filtros y tipo radio
    const filtros = document.querySelectorAll('#filtros input[type="radio"');
    //como no podemos asignar directamente un eventListener a filstros de querySelector,
    //los iteramos y le asignamos a cada uno de ellos (radio) un eventListener
    filtros.forEach( radio => {
        //a cada radio, asigna evento input que ejecuta función filtrarTareas.
        //Con esta sintaxis del addEventListener, la función envia el evento (e), por defecto
        radio.addEventListener('input', filtrarTareas);
    })

    //método que recibe el evento e del input
    function filtrarTareas(e) {
        //obtiene el valor del value html del evento input, en el target del evento e
        const filtro = e.target.value;
        //según los input radio del html de pryecto.php, el value puede ser: ó '' ó 1 ó 0.
        // '' todas las tareas, 1 tareas completadas o 0 tareas pendientes.
        //si el filtro es diferente a todas '', tenemos que filtrar completas o pendientes
        if(filtro !== '') {
            //filtra de todas las tareas, cada tarea cuyo valor del atributo estado es === a filtro,
            //retorna un nuevo arreglo que asigna a filtradas
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        
        //llama método mostrar tareas
        mostrarTareas();
    }

    //función que consulta a la api para obtener las tareas de un proyecto id url
    async function obtenerTareas() {
        try {
            //obtiene la url del proyecto, de la url del endpoint y lo asigna a id
            const id = obtenerProyecto();
            //define la url del servidor endpoint api, con el id (url) según el proyecto
            const url = `/api/tareas?id=${id}`;
            //solicitud http con fetch(), por defecto tipo GET, al servidor endpoint api
            const respuesta = await fetch(url);
            //convierte el cuerpo de la respuesta (response body) a formato json, par js
            const resultado = await respuesta.json();
            //obtiene solo el arreglo index tareas (objetos), del json resultado
            tareas = resultado.tareas;
            //llama funcion mostrarTareas
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas() {

        // limpiar tareas mostradas en el html, antes de mostrarlas de nuevo
        limpiarTareas();

        //llama métodos para saber el total de tareas pendientes y completas
        totalPendientes();
        totalCompletas();

        //si el arreglo filtradas contiene algo ?, lo asigna al arreglo arrayTareas,
        //: de lo contrario, asigna todas las tareas al arreglo arrayTareas.
        const arrayTareas = filtradas.length ? filtradas : tareas;

        //si NO hay tareas en arrayTareas:
        if(arrayTareas.length === 0) {
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

        //como el arreglo arrayTareas SI contiene tareas (objetos), 
        //itera con forEach, genera los li y los inyecta en UL de proyecto.php
        arrayTareas.forEach(tarea => {
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
            //**Para editar el nombre de las tareas:
            //Agrega evento dobleclick al nombre de la tarea
            nombreTarea.ondblclick = function() {
                //llama método enviando true para indicar que estamos editando la tarea,
                //y una copia del objeto tarea, iterada, para que no modifique la original
                mostrarFormulario(true, {...tarea});
            }
            
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
            //agrega evento doble click al botón
            btnEstadoTarea.onclick = function() {
                //envia un objeto con copia de la tarea {...tarea}, porque si enviamos el objeto tarea original
                //y lo modificamos, JS modificará automáticamente el objeto original en el arreglo 
                //del DOM tareas, esto es lo que se conoce como efecto mutación (modificar), hay que evitarlo.
                cambiarEstadoTarea({...tarea});
            }

            //crea botón eliminar para cada tarea
            const btnEliminarTarea = document.createElement('BUTTON');
            //agrega class eliminar-tarea al button
            btnEliminarTarea.classList.add('eliminar-tarea');
            //agrega atributo dataset según el valor del id de la tarea
            btnEliminarTarea.dataset.idTarea = tarea.id;
            //agrega texto al contenido del botón
            btnEliminarTarea.textContent = 'Eliminar';
            //agrega evento doble click al botón
            btnEliminarTarea.ondblclick = function() {
                //envia un objeto copia de la tarea {...tarea}, porque si enviamos el objeto tarea original
                //y lo modificamos, JS modificará automáticamente el objeto original en el arreglo 
                //del DOM tareas, esto es lo que se conoce como efecto mutación (modificar), hay que evitarlo.
                confirmarEliminarTarea({...tarea});
            }
            
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

    //método para obtener el total de tareas Pendientes
    function totalPendientes() {
        //itera tareas y obtiene arreglo con las tareas cuyo atributo estado es 0 (pendiente)
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        //selecciona elemento html con id pendientes, (input type radio en proyecto.php)
        const pendientesRadio = document.querySelector('#pendientes');

        //si totalPendientes es 0, NO hay tareas pendientes
        if(totalPendientes.length === 0) {
            //deshabilita el input type radio, pendientes
            pendientesRadio.disabled = true;
            
        //de lo contrario, SI hay treas pendientes
        } else {
            //habilita el input type radio, pendientes
            pendientesRadio.disabled = false;
        }
    }

    //método para obtener el total de tareas Completas
    function totalCompletas() {
        //iteras tareas y obtiene arreglo con las tareas cuyo atributo estado es 1 (completadas)
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        //selecciona elemento html con id completadas, (input type radio en proyecto.php)
        const completasRadio = document.querySelector('#completadas');
        
        //si totalCompletas es 0, NO hay tareas copletadas
        if(totalCompletas.length === 0) {
            //deshabilita el input type radio, completadas
            completasRadio.disabled = true;
            
            //de lo contrario, SI hay treas pendientes
        } else {
            //habilita el input type radio, completadas
            completasRadio.disabled = false;

        }


    }


    //muestra la ventana modal, con crear si no recibe argumento o 
    //con editar la tarea, si recibe true como argumento editar.
    //Parámetro tarea inicializado vacio, para cuando no se reciba.
    function mostrarFormulario(editar = false, tarea = {}) {
        //crea elemento div, de hml y lo asigna a modal
        const modal = document.createElement('DIV');
        //agrega la clase 'modal' al elemento div en modal
        modal.classList.add('modal');
        //agrega código html al div que contiene la var modal,
        //usamos template string ` ` para poder codificar html en varias lineas.
        //En <legned>, si el argumento recibido editar es true agrega un texto, si no agrega otro.
        //En placeholder, si el argumento editar es true agrega un texto, si no agrega otro.
        //En value tomará el valor de la propiedad nombre en tarea, si lo recibe y si no, tomara ''.
        //En imput submit, si el argumento editar es true agrega un texto, si no agrega otro.
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Agrega una Tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder="${editar ? 'Editar la Tarea' : 'Agrega una Tarea'}"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input 
                        type="submit" 
                        class="submit-nueva-tarea" 
                        value="${editar ? 'Guardar Cambios' : 'Agregar Tarea'}" 
                    />
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
                //obtiene el value (la info) del input con id tarea, del formulario,
                //la función trim() elimina los posibles espacios en blanco antes y despues
                const nombreTarea = document.querySelector('#tarea').value.trim();
                //validación, si el valor de tarea es un string vacio ''
                if(nombreTarea === '') {
                    //llama función, enviando argumentos, para el arguento referencia
                    //enviaremos la posición dentro del html form, donde queremos mostrar la alerta
                    mostrarAlerta(
                        'El nombre de la Tarea es Obligatorio',
                        'error',
                        document.querySelector('.formulario legend')
                    );
                    //parar el código aquí 
                    return;
                }

                //si el argumento editar viene como true, se está editando la tarea
                if(editar) {
                    //actualiza el nombre de la tarea original, con el nuevo nombre
                    tarea.nombre = nombreTarea;
                    //llama función enviando el objeto tarea
                    actualizarTarea(tarea);

                //si el argumento editar NO viene como true, se está creando la tarea
                } else {
                    //llama función agregarTarea, enviando el nombre de la tarea
                    agregarTarea(nombreTarea);
                }

            }
        })

        //imprimir la ventana modal
        //selecciona el elemento con class 'dashboard' y 
        //le agrega el código html de la var modal, como hijo 
        document.querySelector('.dashboard').appendChild(modal);
    }

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
            //const url = 'http://localhost:3000/api/tarea';
            const url = '/api/tarea';
            //define la petición fetch() y espera (await) la respuesta retornada
            const respuesta = await fetch(url, {
                //indica método POST ya que el método por defecto es get (obtener)
                method: 'POST',
                //los datos enviados van en el body de la petición fetcth
                body: datos
            });

            //convierte el cuerpo de la respuesta (response body) a formato json, para js
            const resultado = await respuesta.json();

            //muestra, en la ventana modal '.formulario legend),
            // el mensaje y tipo de alerta, en el resultado de la respuesta de la api
            mostrarAlerta(
                resultado.mensaje,
                resultado.tipo,
                document.querySelector('.formulario legend')
            );

            //si el resultado de la api ha sido una alerta tipo exito
            if(resultado.tipo === 'exito') {
                //selecciona la ventana con clase .modal y a los 3 segundos la cierra
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 3000);
            }
            
            //** Técnica Virtual DOM,
            //** para mostrar la tarea agregada sin volver a consultar la DB 

            //Define el objeto tareaObj, exactamente igual al modelo tarea,
            const tareaObj = {
                id: String(resultado.id),   //convertido a tipo string
                nombre: tarea,
                estado: "0",    //estado inicial de tarea nueva
                proyectoId: resultado.proyectoId
            }

            // ...tareas toma una copia de las tareas (objetos) existentes,
            //agrega la nueva tarea (objeto) en tareaObj y lo reasigna todo de nuevo
            //al arreglo de objetos, tareas
            tareas = [...tareas, tareaObj];
            
            //llama función para mostrar el nuevo arreglo tareas,
            //esto regenera la vista del HTML, sin volver a consultar consultar la DB.
            //En la función mostrarTareas() se tendran que limpiar las tareas anteriores,
            //antes de volver a mostrar las anteriores más la nueva tarea.
            mostrarTareas();
            
            //** ... Fin técnica Virtual DOM */

        } catch (error) {
            //motrar el error de conexión, si se ha producido
            console.log(error);
        }

    }
        
    //cambia el estado de la tarea sin modificar el objeto original del Virtual DOM
    function cambiarEstadoTarea(tarea) {
        //si el estado de la tarea es = 1 (?) cambiarlo a 0, de lo contrario (:) cambiar a 1
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        //asigna el nuevo estado al estado en tarea
        tarea.estado = nuevoEstado;
        //llama método que actualiza la tarea, con su nuevo estado, en la DB
        actualizarTarea(tarea);
    }

    //actualiza la tarea en la DB
    async function actualizarTarea(tarea){
    
        //deconstrucción de las propiedades del objeto tareas a variables
        const {estado, id, nombre, proyectoId} = tarea;
        //nueva instancia de FormData() que crear el objeto necesario 
        //para enviar los datos al servidor con fetch() tipo POST
        const datos = new FormData();
        //agrega las claves y los valores de las vars de la deconstrucción del objeto tara,
        //al objeto vacio datos
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        //agrega la clave y el valor, retornado por la función obtenerProyecto() (url)
        datos.append('proyectoId', obtenerProyecto());

        //para comprobar por consola, la info del objeto datos tipo FormData()
             // for (let valor of datos.values()) {
             //   console.log(valor);
             // }
        
        //try catch para petición post con fetch al servidor
        try {
            //url donde está programada la api del servidor php local
            //const url = 'http://localhost:3000/api/tarea/actualizar'
            const url = '/api/tarea/actualizar'
            //peticion de conexión http con fetch() tipo POST, enviando datos
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //.json() extrae y convierte el cuerpo de la respuesta, a formato json, par js
            const resultado = await respuesta.json(); 
            
            //si la propiedad tipo: del objeto respuesta en la var resultado es = 'exito',
            //significa que se a guardado correctamente en la DB
            if(resultado.respuesta.tipo === 'exito') {
                //llama metodo que requiere: el mensaje de la alerta, el tipo de alerta y
                //la referencia ubicación donde se mostrará el mensaje, en proyecto.php
                    // mostrarAlerta(
                    //     resultado.respuesta.mensaje,
                    //     resultado.respuesta.tipo,
                    //     document.querySelector('.contenedor-nueva-tarea')
                    // );

                //mostrar alerta de tarea actualizada, con la libreria js sweetalert2
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                //selecciona la ventana con clase .modal
                const modal = document.querySelector('.modal');
                //si existe ventana modal (ya que solo existe para modificar el nombre tarea)
                if(modal) {
                    //elimina la ventana modal, cirra
                    modal.remove();
                }

                //**Actualizar la vista del estado de la tarea, sin mutar el Virtual DoM */
                //.map() itera el arreglo de objetos tarea, obteniendo cada tarea (tareaMemoria)
                // y retornando una copia del arreglo que reasignará a tareas
                tareas = tareas.map(tareaMemoria => {
                    //si el id de tareaMemoria es = al id de la tarea que estamos actualizando
                    if(tareaMemoria.id === id) {
                        //cambia el estado de tareaMemoria, por el nuevo estado actualizado en estado
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    //retorna la tarea con el estado actualizado a tareas
                    return tareaMemoria;
                })

                //llama metodo que mostrará las tareas con el estado actualizado
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }

    }

    function confirmarEliminarTarea(tarea) {
        //mostrar alerta de la libreria https://sweetalert2.github.io/#examples
        //requiere agregar el escrip en la pagina proyeto.php
        //<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        Swal.fire({
            title: "¿Eliminar la tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: `No`
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              //llama función enviando la tarea
              eliminarTarea(tarea);
            }
          });  
    }

    async function eliminarTarea(tarea) {
        //deconstrucción del objeto tarea en variables
        const {id, nombre, estado} = tarea;

        //crea objeto vacio tipo FormData(), para enviar con fetch() POST
        const datos = new FormData();
        //agrega claves y valores de id, nombre y estado, al FormData datos
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        //agrega clave poryectoId y valor la url obtenida con el método
        datos.append('proyectoId', obtenerProyecto());

        //para comprobar por consola, la info del objeto datos tipo FormData()
             // for (let valor of datos.values()) {
             //   console.log(valor);
             // }
        
        //try catch para la petición fetch() tipo POST, al servidor local
        try {
            //url del servidor api endpoint para la petición
            //const url = 'http://localhost:3000/api/tarea/eliminar';
            const url = '/api/tarea/eliminar';
            //peticion de conexión http con fetch() tipo POST, enviando datos
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //repuesta recibida de la api, tras la consulta fetch,
            //.json() extrae y convierte el cuerpo de la respuesta, a formato json, para js
            const resultado = await respuesta.json();
            //si el valor de la llave resultado del objeto resultado es true
            if(resultado.resultado) {
                //llamá método enviando mensaje, tipo y ubicación donde mostrarlo
                // mostrarAlerta(
                //     resultado.mensaje,
                //     resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // );

                //mostrar alerta de tarea eliminada con la libreria js sweetalert2
                Swal.fire('Eliminado', resultado.mensaje, 'success');

                //filter() itera las tareas, obteniendo cada tarea (tareaMemoria)
                //cuyo id sea diferente al id de la tarea eliminada en tarea.id,
                //creando un nuevo arreglo de objetos tareas, sin la tarea eliminada.
                //el arrow function => lleva implicito el return
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                //mustra el nuevo listado de tareas sin la tarea eliminada del DOM
                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
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

    function limpiarTareas() {
        //selecciona elemento html (UL) con id 'listado-tareas',
        //donde se muestran las tareas, en proyecto.php
        const listadoTareas = document.querySelector('#listado-tareas');

        //mientras listadoTareas tenga un elemento hijo
        while(listadoTareas.firstChild) {
            //elimina el elemento hijo de listadoTareas
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();