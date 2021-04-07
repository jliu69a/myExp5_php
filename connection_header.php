<?php
// initializing connection

$con = mysqli_connect("localhost","mysohopl_dbuser","Homehome1","mysohopl_homedb");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysql_error();
}
