<?php 
include '../components/conexion.php';


// Verifica si la cookie 'admin_id' está establecida y tiene un valor
if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';// SI NO ESTA, establece $admin_id como una cadena vacía
   header('location:login.php');
}

?>




<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tablero</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- SECCION DASBOARD STARTS -->
<section class="dashboard">

   <h1 class="heading">tablero</h1>

   <div class="box-container">

   <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `administradores` WHERE id = ? LIMIT 1");// La consulta busca una fila donde el campo "id" sea igual al valor proporcionado por la variable $admin_id. 
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>Bienvenido!</h3>
      <p><?= $fetch_profile['nombre']; ?></p><!--Aquí se muestra el nombre del administrador recuperado de la base de datos.-->
      <a href="update.php" class="btn">actualización del perfil</a>
   </div>

   <div class="box">
      <?php
         $select_bookings = $conn->prepare("SELECT * FROM `reservas`");// Se prepara una consulta para seleccionar todas las reservas de la tabla 'reservas'
         $select_bookings->execute();
         $count_bookings = $select_bookings->rowCount();//Se cuenta el número total de reservas 
      ?>
      <h3><?= $count_bookings; ?></h3><!-- Se muestra el número total de reservas -->
      <p>reservas totales</p>
      <a href="bookings.php" class="btn">ver reservas</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `administradores`");// Se prepara una consulta para seleccionar todos los administradores de la tabla 'admins'
         $select_admins->execute();
         $count_admins = $select_admins->rowCount();/// Se cuenta el número total de administradores 
      ?>
      <h3><?= $count_admins; ?></h3><!-- Se muestra el número total de administradores -->
      <p>total de administradores</p>
      <a href="admins.php" class="btn">ver administradores</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `mensajes`");// Preparación de la consulta SQL para seleccionar todos los mensajes de la tabla 'messages'
         $select_messages->execute();
         $count_messages = $select_messages->rowCount();//se cuneta el numero tota  de mensajes
      ?>
      <h3><?= $count_messages; ?></h3><!-- Muestra el número total de mensajes -->
      <p>total de mensajes</p>
      <a href="messages.php" class="btn">ver mensajes</a>
   </div>

   <div class="box">
      <h3>selección rápida</h3>
      <p>iniciar sesión o registrarse</p>
      <a href="login.php" class="btn" style="margin-right: 1rem;">ingresar</a>
      <a href="register.php" class="btn" style="margin-left: 1rem;">registrarse</a>
   </div>


   </div>

</section>
<!-- SECCION DASHBOARD ENDS -->











<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!--LINK DE JS  -->
<script src="../js/admin_script.js"></script>


<?php include '../components/message.php'; ?>

</body>
</html>