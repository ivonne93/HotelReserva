<?php

include 'components/conexion.php';

if(isset($_COOKIE['user_id'])){ //isset() se utiliza aquí para verificar si la cookie 'user_id' está presente
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/'); //se crea una nueva cookie, tine un tiempo de 30 dias
   header('location:index.php');
}



/*manejar una solicitud POST y verificar la disponibilidad de habitaciones en un hotel para una fecha de check-in específica */
if(isset($_POST['check'])){

   $check_in = $_POST['check_in']; //el formulario lo alamacena en la variable
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   /*preparan y ejecutan una consulta SQL para seleccionar todas las reservas que tienen la misma fecha de check-in que la proporcionada en el formulario */
   $check_bookings = $conn->prepare("SELECT * FROM `reservas` WHERE fecha_entrada = ?");
   $check_bookings->execute([$check_in]);

  /* Este bucle while recorre los resultados de la consulta y suma el número de habitaciones reservadas para la fecha de check-in proporcionada a la variable $total_rooms*/
   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['habitaciones'];
   }

   /*se verifica si el número total de habitaciones reservadas es mayor o igual a 35 */
   if($total_rooms >= 35){
      $warning_msg[] = 'las habitaciones no están disponibles';
   }else{
      $success_msg[] = 'hay habitaciones disponibles';
   }

}



if(isset($_POST['book'])){

   $booking_id = create_unique_id(); // Generar ID único para la reserva
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `reservas` WHERE fecha_entrada = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['habitaciones'];
   }

   if($total_rooms >= 35){
      $warning_msg[] = 'las habitaciones no están disponibles';
   } else {
      $verify_bookings = $conn->prepare("SELECT * FROM `reservas` WHERE usuario_id = ? AND nombre = ? AND correo_electronico = ? AND numero = ? AND habitaciones= ? AND fecha_entrada = ? AND fecha_salida = ? AND adultos = ? AND ninos = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'habitación reservada ya!';
      } else {
         $book_room = $conn->prepare("INSERT INTO `reservas`(reserva_id, usuario_id, nombre, correo_electronico, numero, habitaciones, fecha_entrada, fecha_salida, adultos, ninos) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'Habitación reservada con exito!';
      }
   }
}



if(isset($_POST['send'])){

   $id = create_unique_id(); // Generar un ID único para el mensaje
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   // Verificar si el mensaje ya existe en la base de datos
   $verify_message = $conn->prepare("SELECT * FROM `mensajes` WHERE nombre = ? AND correo_electronico = ? AND numero = ? AND mensaje = ?");//Esta función se utiliza para preparar una consulta SQL para su ejecución.
   $verify_message->execute([$name, $email, $number, $message]);

   // Si el mensaje ya existe, mostrar una advertencia
   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'mensaje enviado ya!';
   }else{
      // Si el mensaje no existe, insertarlo en la base de datos
      $insert_message = $conn->prepare("INSERT INTO `mensajes`(id, nombre, correo_electronico, numero, mensaje) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = '¡Mensaje enviado exitosamente!';
   }

}

?>




<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HotelReserva</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?><!--INCLUYE user_header.php-->

<!-- home section starts  -->
<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="box swiper-slide">
            <img src="img/home-img-1.jpg" alt="">
            <div class="flex">
               <h3>habitaciones lujosas</h3>
               <a href="#availability" class="btn">ver disponibilidad</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="img/home-img-2.jpg" alt="">
            <div class="flex">
               <h3>comidas y bebidas</h3>
               <a href="#reservation" class="btn">hacer una reserva</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="img/home-img-3.jpg" alt="">
            <div class="flex">
               <h3>salones lujosos</h3>
               <a href="#contact" class="btn">contáctanos</a>
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>
<!-- home section ends -->

<!-- availability section starts  -->
<section class="availability" id="availability">

<form action="" method="post">
   <div class="flex">
      <div class="box">
         <p>Fecha de entrada </p>
         <input type="date" name="check_in" class="input" required>
      </div>
      <div class="box">
         <p>Fecha de salida </p>
         <input type="date" name="check_out" class="input" required>
      </div>
      <div class="box">
         <p>Adultos</p>
         <select name="adults" class="input" required>
            <option value="1">1 adulto</option>
            <option value="2">2 adultos</option>
            <option value="3">3 adultos</option>
            <option value="4">4 adultos</option>
            <option value="5">5 adultos</option>
            <option value="6">6 adultos</option>
         </select>
      </div>
      <div class="box">
         <p>Niños </p>
         <select name="childs" class="input" required>
            <option value="-">0 niño</option>
            <option value="1">1 niño</option>
            <option value="2">2 niños</option>
            <option value="3">3 niños</option>
            <option value="4">4 niños</option>
            <option value="5">5 niños</option>
            <option value="6">6 niños</option>
         </select>
      </div>
      <div class="box">
         <p>Habitaciones </p>
         <select name="rooms" class="input" required>
            <option value="1">1 habitación</option>
            <option value="2">2 habitaciones</option>
            <option value="3">3 habitaciones</option>
            <option value="4">4 habitaciones</option>
            <option value="5">5 habitaciones</option>
            <option value="6">6 habitaciones</option>
         </select>
      </div>
   </div>
   <input type="submit" value="Ver disponibilidad" name="check" class="btn">
</form>

</section>
<!-- availability section ends -->

<!-- about section starts  -->
<section class="about" id="about">
<div class="row">
      <div class="image">
         <img src="img/1.jpg" alt="">
      </div>
      <div class="content">
         <h3>el mejor personal</h3>
         <p>Nuestra amabilidad, eficiencia y pasión por la hospitalidad hacen que cada estancia sea única y memorable.</p>
         <a href="#reservation" class="btn">hacer una reserva</a>
      </div>
   </div>

   

   <div class="row revers">
      <div class="image">
         <img src="img/2.jpg" alt="">
      </div>
      <div class="content">
         <h3>mejor comida</h3>
         <p> 
      Nuestro equipo de chefs talentosos se dedica a crear platos exquisitos que deleitan el paladar de nuestros huéspedes.</p>
         <a href="#contact" class="btn">contáctanos</a>
      </div>
   </div>
</section>
<!-- about section ends -->

<!-- services section starts  -->
<section class="services">

   <div class="box-container">

      <div class="box">
         <img src="img/icon-1.png" alt="">
         <h3>comida & bebidas</h3>
         <p>Disfruta de una amplia selección de platos y bebidas preparados por nuestros chefs expertos.</p>
      </div>

      <div class="box">
         <img src="img/icon-2.png" alt="">
         <h3>comedor al aire libre</h3>
         <p>Experimenta una comida deliciosa con vistas impresionantes en nuestro comedor al aire libre.</p>
      </div>

      <div class="box">
         <img src="img/icon-3.png" alt="">
         <h3>vista a la playa</h3>
         <p>Relájate y disfruta de la hermosa vista a la playa desde nuestro hotel.</p>
      </div>
      
      <div class="box">
         <img src="img/icon-4.png" alt="">
         <h3>decoraciones</h3>
         <p>Nuestras decoraciones elegantes y sofisticadas crean el ambiente perfecto para tu estancia.</p>
      </div>

      <div class="box">
         <img src="img/icon-5.png" alt="">
         <h3>piscina</h3>
         <p>Sumérgete en nuestra piscina y disfruta de un refrescante chapuzón en un entorno relajante.</p>
      </div>

      <div class="box">
         <img src="img/icon-6.png" alt="">
         <h3>teatro</h3>
         <p>Disfruta de presentaciones teatrales en vivo que ofrecen entretenimiento de alta calidad.</p>
      </div>

   </div>

</section>
<!-- services section ends -->


<!-- reservation section starts  -->
<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>hacer una reserva</h3>
      <div class="flex">
         <div class="box">
            <p>tu nombre <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="ingresa tu nombre" class="input">
         </div>
         <div class="box">
            <p>tu correo <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="ingresa tu correo" class="input">
         </div>
         <div class="box">
            <p>tu número <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="ingresa tu número" class="input">
         </div>
         <div class="box">
            <p>habitaciones <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 habitación</option>
               <option value="2">2 habitaciones</option>
               <option value="3">3 habitaciones</option>
               <option value="4">4 habitaciones</option>
               <option value="5">5 habitaciones</option>
               <option value="6">6 habitaciones</option>
            </select>
         </div>
         <div class="box">
            <p>fecha de entrada <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>fecha de salida <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adultos <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adulto</option>
               <option value="2">2 adultos</option>
               <option value="3">3 adultos</option>
               <option value="4">4 adultos</option>
               <option value="5">5 adultos</option>
               <option value="6">6 adultos</option>
            </select>
         </div>
         <div class="box">
            <p>niños <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 niños</option>
               <option value="1">1 niño</option>
               <option value="2">2 niños</option>
               <option value="3">3 niños</option>
               <option value="4">4 niños</option>
               <option value="5">5 niños</option>
               <option value="6">6 niños</option>
            </select>
         </div>
      </div>
      <input type="submit" value="reservar ahora" name="book" class="btn">
   </form>

</section>
<!-- reservation section ends -->


<!-- gallery section starts  -->
<section class="gallery" id="gallery">

   <div class="swiper gallery-slider">
      <div class="swiper-wrapper">
         <img src="img/gallery-img-1.jpg" class="swiper-slide" alt="">
         <img src="img/gallery-img-2.jpg" class="swiper-slide" alt="">
         <img src="img/gallery-img-3.jpg" class="swiper-slide" alt="">
         <img src="img/gallery-img-4.webp" class="swiper-slide" alt="">
         <img src="img/gallery-img-5.webp" class="swiper-slide" alt="">
         <img src="img/gallery-img-6.webp" class="swiper-slide" alt="">
      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>
<!-- gallery section ends -->


<!-- contact section starts  -->
<section class="contact" id="contact">
   <div class="row">
      <form action="" method="post">
         <h3>Envíanos un mensaje</h3>
         <input type="text" name="name" required maxlength="50" placeholder="Ingresa tu nombre" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="Ingresa tu correo electrónico" class="box">
         <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="Ingresa tu número" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="Ingresa tu mensaje" cols="30" rows="10"></textarea>
         <input type="submit" value="Enviar mensaje" name="send" class="btn">
      </form>
      <div class="faq">
         <h3 class="title">Preguntas frecuentes</h3>
         <div class="box active">
            <h3>¿Cómo cancelar?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus sunt aspernatur excepturi eos! Quibusdam, sapiente.</p>
         </div>
         <div class="box">
            <h3>¿Hay vacantes disponibles?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>¿Cuáles son los métodos de pago?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>¿Cómo reclamar códigos de cupones?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>¿Cuáles son los requisitos de edad?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
      </div>
   </div>
</section>
<!-- contact section ends -->

<!-- reviews section starts  -->
<section class="reviews" id="reviews">

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">
         <div class="swiper-slide box">
            <img src="img/pic-1.png" alt="">
            <h3>José</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="img/pic-2.png" alt="">
            <h3>Alejandra</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="img/pic-3.png" alt="">
            <h3>Carlos</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="img/pic-4.png" alt="">
            <h3>Rosa María</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="img/pic-5.png" alt="">
            <h3>Juan Carlos</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="img/pic-6.png" alt="">
            <h3>Ana Laura</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
      </div>

      <div class="swiper-pagination"></div>
   </div>

</section>
<!-- reviews section ends  -->






<?php include 'components/footer.php'; ?>



<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!--libreria para mensajes-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


<!-- link de js  -->
<script src="js/script.js.."></script>


<?php include 'components/message.php'; ?>



</body>
</html>