<?php
//http://www.mysohoplace.com/php_hdb/php_GL/homee/change_home_test.php

// Create connection
include('connection_header.php');

if ($_POST) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $value = mysqli_real_escape_string($con, $_POST['value']);
  $notes = mysqli_real_escape_string($con, $_POST['notes']);
  
  $insertSql = "INSERT INTO `home_test`(`name`, `value`, `notes`, `date`) VALUES ('$name', '$value', '$notes', now());";
  $result2 = mysqli_query($con, $insertSql);
  
  echo $result2;
}
else {
  echo "not POST method";
}

mysqli_close($con);
?>
