<?php

include '../components/conexion.php';

// Verifica si la cookie 'admin_id' está establecida y tiene un valor
if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];  
}else{
   $admin_id = '';// SI NO ESTA, establece $admin_id como una cadena vacía
   header('location:login.php');
}


if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
   $c_pass = sha1($_POST['c_pass']);//La función sha1() calcula el valor hash SHA-1 de una cadena, lo que resulta en una cadena hexadecimal de 40 caracteres
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);   

   $select_admins = $conn->prepare("SELECT * FROM `administradores` WHERE nombre = ?"); // Consulta la base de datos para verificar si el nombre de usuario ya está en uso
   $select_admins->execute([$name]);

   if($select_admins->rowCount() > 0){// Comprueba si ya existe un usuario con el mismo nombre
      $warning_msg[] = '¡Nombre de usuario ya tomado!';     
   }else{
      if($pass != $c_pass){ //Comprueba si las contraseñas coinciden
         $warning_msg[] = '¡La contraseña no coincide!';
      }else{ // Si las contraseñas coinciden, inserta el nuevo administrador en la base de datos
         $insert_admin = $conn->prepare("INSERT INTO `administradores`(nombre, contrasena) VALUES(?,?)");
         $insert_admin->execute([$name, $c_pass]);
         $success_msg[] = '¡Registrado exitosamente!';
      }
   }

}

?>


<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrate</title>

    <!--font awesome cdn link, es una biblioteca de iconos-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!--link hoja style DEL ADMINISTRADOR-->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- SECCION HEADER STARTS DE ADMIN -->
<?php include '../components/admin_header.php'; ?>
<!-- SECCION HEADER ENDS  -->


<!-- SECCION REGISTER STARTS-->
<section class="form-container">

   <form action="" method="POST">   <!-- Inicia el formulario de registro -->
      <h3>registro nuevo</h3>
      <input type="text" name="name" placeholder="introduzca su nombre de usuario" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" placeholder="introduce tu contraseña" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="c_pass" placeholder="confirma tu contraseña" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <!--replace() para buscar y reemplazar todos los espacios en blanco (\s) es de js-->
      <input type="submit" value="Regístrate ahora" name="submit" class="btn">
   </form>

</section>
<!-- SECCION REGISTER ENDS -->









<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!--Link de js de la pagina ADMIN-->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>