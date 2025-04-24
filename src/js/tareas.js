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
                        type="test"
                        name="tarea"
                        id="tarea"
                        placeholder="Agrega nueva tarea"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Agregar Tarea" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>;
        `;
        
        //agrega la clase animar al formulario a los x segundos,
        //clase que hace que se muestre la ventana modal, con scss
        setTimeout(() => {
            //selecciona el elemento con clase .formulari y lo asigna a formulario
            const formulario = document.querySelector('.formulario');
            //agrega la clase animar al elemento formulario
            formulario.classList.add('animar');
        }, 0);

        //selecciona el elemento <body> del dom y le agrega el código html de la var modal 
        document.querySelector('body').appendChild(modal);
        
    }

})();