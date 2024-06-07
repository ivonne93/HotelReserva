<?php 


//MENSAJE DE QUE FUE EXITOSO
if(isset($success_msg)){/* La función isset() devuelve true si la variable está definida y no es nula, y false*/
    foreach($success_msg as $success_msg){// Este bucle foreach recorre cada elemento del array $success_msg
       echo '<script>swal("'.$success_msg.'", "" ,"success");</script>';//En cada iteración del bucle, se imprime un script JavaScript que utiliza SweetAlert para mostrar un mensaje de éxito.
    }
 }


//MENSAJE DE advertencia
 if(isset($warning_msg)){
    foreach($warning_msg as $warning_msg){
       echo '<script>swal("'.$warning_msg.'", "" ,"warning");</script>';
    }
 }

 //MENSAJE DE INFOMACION
 if(isset($info_msg)){
    foreach($info_msg as $success_msg){
       echo '<script>swal("'.$info_msg.'", "" ,"info");</script>';
    }
 }

 //Mensaje de error
 if(isset($error_msg)){
    foreach($error_msg as $error_msg){
       echo '<script>swal("'.$error_msg.'", "" ,"error");</script>';
    }
 }

?>
