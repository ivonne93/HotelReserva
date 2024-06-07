<?php

include '../components/conexion.php';

// Verifica si la cookie 'admin_id' está establecida y tiene un valor
if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];   
 }else{
    $admin_id = '';// SI NO ESTA, establece $admin_id como una cadena vacía
    header('location:login.php');
 }


 if(isset($_POST['delete'])){

 
    // Obtiene el ID del administrador a eliminar del formulario enviado
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
 
    // Verifica si el administrador existe antes de intentar eliminarlo
    $verify_delete = $conn->prepare("SELECT * FROM `administradores` WHERE id = ?");
    $verify_delete->execute([$delete_id]);
 
    if($verify_delete->rowCount() > 0){
       // Si el administrador existe, se procede a eliminarlo de la base de datos
       $delete_admin = $conn->prepare("DELETE FROM `administradores` WHERE id = ?");
       $delete_admin->execute([$delete_id]);
       $success_msg[] = 'Administrador eliminado!';
    }else{
       $warning_msg[] = '¡El administrador ya fue eliminado!';
    }
 }




?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administradores</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- admins section starts  -->
<section class="grid">

   <h1 class="heading">administradores</h1>

   <div class="box-container">

   <div class="box" style="text-align: center;">
      <p>crear un nuevo administrador</p>
      <a href="register.php" class="btn">registrate ahora</a>
   </div>

   <?php
      $select_admins = $conn->prepare("SELECT * FROM `administradores`");// Preparación y ejecución de la consulta SQL para seleccionar todos los administradores
      $select_admins->execute();
      if($select_admins->rowCount() > 0){//Verificación si hay administradores encontrados en la base de datos
         while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" <?php if( $fetch_admins['nombre'] == 'admin'){ echo 'style="display:none;"'; } ?>> <!-- Div que contiene los detalles de cada administrador -->
      <p>nombre : <span><?= $fetch_admins['nombre']; ?></span></p><!-- Mostrar el nombre del administrador -->
      <form action="" method="POST"> 
         <input type="hidden" name="delete_id" value="<?= $fetch_admins['id']; ?>">
         <input type="submit" value="eliminar administrador" onclick="return confirm('¿eliminar este administrador?');" name="delete" class="btn">
      </form>
   </div>
   <?php
      }
   }else{
   }
   ?>

   </div>

</section>
<!-- admins section ends -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>