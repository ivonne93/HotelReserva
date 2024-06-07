<?php
   $db_name = 'mysql:host=localhost;dbname=db_hotel';
   $db_user_name = 'root';
   $db_user_pass = '123';

   $conn = new PDO($db_name, $db_user_name, $db_user_pass);



   // Definir una función para generar un ID único
   function create_unique_id(){
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $rand = ''; // Inicializar una cadena vacía para almacenar los caracteres aleatorios
      $length = strlen($str) - 1;
  
      for($i = 0; $i < 10; $i++){
          $rand .= $str[mt_rand(0, $length)]; // Agregar un carácter aleatorio a la cadena $rand
      }
      return $rand; // Devolver la cadena $rand como el ID único
  }
  


?>