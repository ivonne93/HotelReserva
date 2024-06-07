<?php

include 'components/conexion.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');//establece una nueva cookie
   header('location:index.php');
}


if(isset($_POST['cancel'])){

   $booking_id = $_POST['reserva_id'];// Obtiene el ID de reserva enviado mediante el formulario
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   $verify_booking = $conn->prepare("SELECT * FROM `reservas` WHERE reserva_id = ?");// Prepara una consulta para verificar si la reserva existe en la base de datos
   $verify_booking->execute([$booking_id]); 


   if($verify_booking->rowCount() > 0){   // Verifica si se encontró al menos una reserva con el ID proporcionado
      $delete_booking = $conn->prepare("DELETE FROM `reservas` WHERE reserva_id = ?");// Si se encontró la reserva, prepara una consulta para eliminarla de la base de datos
      $delete_booking->execute([$resrva_id]); // Ejecuta la consulta para eliminar la reserva con el ID proporcionado
      $success_msg[] = 'reserva cancelada con éxito!';
   }else{
      $warning_msg[] = 'reserva cancelada!';
   }
   
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>reservaciones</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

    <!--font awesomw cdn link, es una biblioteca de iconos-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!--link hoja style-->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .bookings .heading {
      text-align: center;
      margin-bottom: 2rem;
      color: var(--sub-color);
      font-size: 2.5rem;
      text-transform: capitalize;
   }

   .bookings .box-container{
      display: grid;
      grid-template-columns: repeat(auto-fit, 35rem);
      gap: 1.5rem;
      justify-content: center;
      align-items: flex-start;
   }

   .bookings .box-container .box{
   border-radius: .5rem;
   padding: 2rem;
   padding-top: 1rem;
   border: var(--border);
}

   .bookings .box-container .box p{
      line-height: 1.5;
      padding-top: .5rem;
      font-size: 1.8rem;
      color: var(--sub-color);
   }

   </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- SECCION BOOKING STARTS -->
<section class="bookings">

   <h1 class="heading">mis reservaciones</h1>

   <div class="box-container">

   <?php
      // Prepara una consulta para seleccionar todas las reservas del usuario actual
      $select_bookings = $conn->prepare("SELECT * FROM `reservas` WHERE usuario_id = ?");
      $select_bookings->execute([$user_id]);
      if($select_bookings->rowCount() > 0){// Verifica si hay al menos una reserva encontrada
         while($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)){
   ?>
   <!-- Comienza la estructura HTML para mostrar la información de la reserva -->
   <div class="caja">
   <p>nombre : <span><?= $fetch_booking['nombre']; ?></span></p>
   <p>correo electrónico : <span><?= $fetch_booking['correo_electronico']; ?></span></p>
   <p>número : <span><?= $fetch_booking['numero']; ?></span></p>
   <p>fecha de entrada : <span><?= $fetch_booking['fecha_entrada']; ?></span></p>
   <p>fecha de salida : <span><?= $fetch_booking['fecha_salida']; ?></span></p>
   <p>habitaciones : <span><?= $fetch_booking['habitaciones']; ?></span></p>
   <p>adultos : <span><?= $fetch_booking['adultos']; ?></span></p>
   <p>niños : <span><?= $fetch_booking['ninos']; ?></span></p>
   <p>id de la reserva : <span><?= $fetch_booking['reserva_id']; ?></span></p>
   <form action="" method="POST"> <!--Eliminar la reservación-->
      <input type="hidden" name="booking_id" value="<?= $fetch_booking['reserva_id']; ?>"><!--booking_id es el que se usará para la cancelación-->
      <input type="submit" value="cancelar reserva" name="cancel" class="btn" onclick="return confirm('¿Cancelar esta reserva?');"><!--confirm() muestra un cuadro de diálogo modal con un mensaje y dos botones: "Aceptar" y "Cancelar"-->
   </form>
</div>

   <?php
    }
   }else{
        
   ?>   
   <!-- Si no se encontraron reservas, muestra un mensaje y un enlace para reservar -->
   <div class="box" style="text-align: center;">
      <p style="padding-bottom: .5rem; text-transform:capitalize;">no bookings found!</p>
      <a href="index.php#reservation" class="btn">book new</a>
   </div>
   <?php
   }
   ?>
   </div>

</section>
<!-- SECCION BOOKING ENDS -->




<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!--Link de js de la pagina-->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
