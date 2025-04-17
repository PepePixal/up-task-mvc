<?php
    //como las alertas estan en un arreglo asociativo 
    //lo recorremos por su llave y tomamos la alerta con los mensajes
    foreach ($alertas as $key => $alerta):
        //como cada mensaje de alerta está en un arreglo indexado
        foreach ($alerta as $mensaje):
?>

        <!-- el segundo nombre de clase será el valor de $key ('error' o 'exito') -->
        <!-- el mensaje de la alerta será el valor de $mensaje -->
        <div class="alerta <?php echo $key; ?>"><?php echo $mensaje; ?></div>

<?php
        endforeach;
    endforeach;
?>