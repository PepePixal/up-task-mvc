//selecciona elemento html con id mobile-menu, en barra.php
const mobileMenuBtn = document.querySelector('#mobile-menu');
//selecciona elemento html con id cerrar-menu, en sidebar.php
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
//selecciona elemento html con class .sidebar
const sidebar = document.querySelector('.sidebar');

//como mobile-menu no estará en todos los sitios,
//condicina su comportamiento a si existe:
if (mobileMenuBtn) {
    //agrega evento click al elemento con id mobile-menu
    mobileMenuBtn.addEventListener('click', function(){
        //.add, agrega la clase .mostrar al elemento html
        //con class .sidebar
        sidebar.classList.add('mostrar');
    });
}

//como cerrar-menu no estará en todos los sitios,
//condicina su comportamiento a si existe:
if (cerrarMenuBtn) {
    //agrega evento click al elemento con id cerrar-menu
    cerrarMenuBtn.addEventListener('click', function(){
        //.remove, elimina la clase .mostrar al elemento html
        //con class .sidebar
        sidebar.classList.remove('mostrar');
    });
}

//** Eliminar la clase mostrar en sidebar, que muestra el menu-mobile,
//** controlando el tamaño anchura de window.
//obtiene el ancho de pantalla 
//const anchoPantalla = document.bodyclientWidth;
//asigna evento resize al window, que escucha su cambio de tamaño 
window.addEventListener('resize', function() {
    //según se va ensanchando, se va obteniendo el nuevo tamaño del window
    const anchoPantalla = document.body.clientWidth;
    //si el ancho de pantalla llega a >= de 768 px (tablet):
    if(anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
    }
});



