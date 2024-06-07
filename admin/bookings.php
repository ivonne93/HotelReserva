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

   $delete_id = $_POST['delete_id'];//obtiene el ID
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

 // Se verifica si la reserva existe antes de intentar eliminarla
   $verify_delete = $conn->prepare("SELECT * FROM `reservas` WHERE reserva_id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){  // Si la reserva existe, se procede a eliminarla de la base de datos
      $delete_bookings = $conn->prepare("DELETE FROM `reservas` WHERE reserva_id = ?");
      $delete_bookings->execute([$delete_id]);
      $success_msg[] = '¡Reserva eliminada!';
   }else{
      $warning_msg[] = '¡Reserva eliminada ya!';
   }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservas</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- bookings section starts  -->

<section class="grid">

   <h1 class="heading">reservar</h1>

   <div class="box-container">

   <?php
      $select_bookings = $conn->prepare("SELECT * FROM `reservas`");// Preparación y ejecución de la consulta SQL para seleccionar todas las reservas
      $select_bookings->execute();
      if($select_bookings->rowCount() > 0){
         while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){ // Iteración sobre cada reserva encontrada
   ?>

<div class="box">
      <p>id de reserva : <span><?= $fetch_bookings['reserva_id']; ?></span></p>
      <p>nombre : <span><?= $fetch_bookings['nombre']; ?></span></p>
      <p>correo electrónico : <span><?= $fetch_bookings['correo_electronico']; ?></span></p>
      <p>número : <span><?= $fetch_bookings['numero']; ?></span></p>
      <p>check in : <span><?= $fetch_bookings['fecha_entrada']; ?></span></p>
      <p>check out : <span><?= $fetch_bookings['fecha_salida']; ?></span></p>
      <p>habitaciones : <span><?= $fetch_bookings['habitaciones']; ?></span></p>
      <p>adultos : <span><?= $fetch_bookings['adultos']; ?></span></p>
      <p>niños : <span><?= $fetch_bookings['ninos']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_bookings['reserva_id']; ?>"><!--delete_id se elimina la reserva-->
         <input type="submit" value="eliminar reserva" onclick="return confirm('¿Eliminar esta reserva?');" name="delete" class="btn"> 

      </form>
   </div>

   <?php
      }
   }else{
   ?>
   <div class="box" style="text-align: center;">
      <p>¡No se encontraron reservas!</p>
      <a href="dashboard.php" class="btn">ir a inicio</a>

   </div>
   <?php
      }
   ?>

   </div>

</section>

<!-- bookings section ends -->


</body>
</html>