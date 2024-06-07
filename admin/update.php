<?php

include '../components/conexion.php';

// Verifica si la cookie 'admin_id' está establecida y tiene un valor
if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];  
 }else{
    $admin_id = '';// SI NO ESTA, establece $admin_id como una cadena vacía
    header('location:login.php');
 }
 
 // Consulta el perfil del administrador basado en el ID proporcionado
$select_profile = $conn->prepare("SELECT * FROM `administradores` WHERE id = ? LIMIT 1");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);


if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 

   if(!empty($name)){   // Verifica si el nombre del usuario 
      $verify_name = $conn->prepare("SELECT * FROM `administradores` WHERE nombre = ?");
      $verify_name->execute([$name]);
      if($verify_name->rowCount() > 0){ 
         $warning_msg[] = '¡Nombre de usuario ya tomado!';
      }else{
        // Si el nombre de usuario no está en uso, actualiza el nombre de usuario en la base de datos
         $update_name = $conn->prepare("UPDATE `administradores` SET nombre = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
         $success_msg[] = '¡Nombre de usuario actualizado!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';// Define una contraseña de valor predeterminado para comparaciones posteriores
   $prev_pass = $fetch_profile['contrasena'];   
   $old_pass = sha1($_POST['old_pass']);   
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']); 
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $c_pass = sha1($_POST['c_pass']); 
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){// Comprueba si la contraseña anterior no está vacía
      if($old_pass != $prev_pass){
         $warning_msg[] = '¡La contraseña anterior no coincide!';
      }elseif($c_pass != $new_pass){ 
         $warning_msg[] = 'Nueva contraseña no coincide!';
      }else{
        // Si todas las verificaciones pasan, actualiza la contraseña en la base de datos
         if($new_pass != $empty_pass){
            $update_password = $conn->prepare("UPDATE `administradores` SET contrasena = ? WHERE id = ?");
            $update_password->execute([$c_pass, $admin_id]);
            $success_msg[] = '¡Contraseña actualiza!';
         }else{
            $warning_msg[] = '¡Por favor ingrese una nueva contraseña!';
         }
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
   <title>Actualizar</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- update section starts  -->
<section class="form-container">

   <form action="" method="POST">
   <h3>actualizar perfil</h3>
   <input type="text" name="name" placeholder="<?= $fetch_profile['nombre']; ?>" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="old_pass" placeholder="ingresa la contraseña antigua" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="new_pass" placeholder="ingresa la nueva contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="c_pass" placeholder="confirma la nueva contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="submit" value="actualizar ahora" name="submit" class="btn">
</form>


</section>
<!-- update section ends -->





<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>