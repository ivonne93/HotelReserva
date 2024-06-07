<?php

include '../components/conexion.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $pass = sha1($_POST['pass']); 
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 

   $select_admins = $conn->prepare("SELECT * FROM `administradores` WHERE nombre = ? AND contrasena = ? LIMIT 1");// Prepara una consulta para seleccionar al administrador que coincide con el nombre de usuario y la contraseña proporcionados
   $select_admins->execute([$name, $pass]);
   $row = $select_admins->fetch(PDO::FETCH_ASSOC);

   if($select_admins->rowCount() > 0){// Comprueba si se encontró al menos un administrador con las credenciales proporcionada
      setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/'); // Establece una cookie 'admin_id' que contiene el ID del administrador durante 30 días
      header('location:dashboard.php');
   }else{
      $warning_msg[] = '¡Nombre de usuario o contraseña incorrecta!';
   }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iniciar sesión</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.">

</head>
<body>

<!-- SECCION LOGIN starts  -->
<section class="form-container" style="min-height: 100vh;">

   <form action="" method="POST">
   <h3>¡Bienvenido de nuevo!</h3>
   <p>Nombre de usuario por defecto = <span>admin</span> <br>y contraseña = <span>111</span></p>
      <input type="text" name="name" placeholder="introduzca su nombre de usuario" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" placeholder="introduzca su contraseña" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Inicia sesión ahora" name="submit" class="btn">
   </form>

</section>
<!--SECCION LOGIN ends -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>


