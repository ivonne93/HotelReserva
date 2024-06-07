<?php

include '../components/conexion.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}



if(isset($_POST['delete'])){
   
    // Obtiene el ID del mensaje a eliminar del formulario enviado
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
 
    // Verifica si el mensaje existe antes de intentar eliminarlo
    $verify_delete = $conn->prepare("SELECT * FROM `mensajes` WHERE id = ?");
    $verify_delete->execute([$delete_id]);
 
    if($verify_delete->rowCount() > 0){
       // Si el mensaje existe, se procede a eliminarlo de la base de datos
       $delete_messages = $conn->prepare("DELETE FROM `mensajes` WHERE id = ?");
       $delete_messages->execute([$delete_id]);
       // Agrega un mensaje de éxito para informar al usuario que el mensaje ha sido eliminado
       $success_msg[] = '¡Mensaje borrado!';
    }else{
       // Si el mensaje no existe, se muestra un mensaje de advertencia al usuario
       $warning_msg[] = '¡Mensaje eliminado!';
    }
 }


?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mensajes</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- messages section starts  -->
<section class="grid">

   <h1 class="heading">Mensajes</h1>

   <div class="box-container">

   <?php

    // Preparación y ejecución de la consulta SQL para seleccionar todos los mensajes
    $select_messages = $conn->prepare("SELECT * FROM `mensajes`");
    $select_messages->execute();
    // Verificación si hay mensajes encontrados en la base de datos
    if($select_messages->rowCount() > 0){
    // Iteración sobre cada mensaje encontrado
    while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Nombre: <span><?= $fetch_messages['nombre']; ?></span></p>
      <p>Correo electrónico: <span><?= $fetch_messages['correo_electronico']; ?></span></p>
      <p>Número: <span><?= $fetch_messages['numero']; ?></span></p>
      <p>Mensaje: <span><?= $fetch_messages['mensaje']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_messages['id']; ?>">
         <input type="submit" value="Eliminar mensaje" onclick="return confirm('¿Eliminar este mensaje?');" name="delete" class="btn">
      </form>
   </div>
   <?php
      }
   }else{
   ?>
   <div class="box" style="text-align: center;">
      <p>¡No se encontraron mensajes!</p>
      <a href="dashboard.php" class="btn">Ir a la página principal</a>
   </div>
   <?php
      }
   ?>

   </div>

</section>
<!-- messages section ends -->




<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>