<?php 

include 'conexion.php';

setcookie('admin_id', '', time() - 1, '/');// elimina la cookie al establecer su tiempo de vida en el pasado.

header('location:../admin/login.php');

?>