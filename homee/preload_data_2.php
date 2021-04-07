<?php
// http://www.mysohoplace.com/php_hdb/php_GL/homee/preload_data_2.php

// Create connection
include('connection_header.php');

$sql1 = "SELECT `id` AS 'id', `payment` AS 'name' FROM `MyExp_Payments_Home` ORDER BY `payment` ASC;";
$sql2 = "SELECT `id` AS 'id', `vendor` AS 'name' FROM `MyExp_Vendors_Home` ORDER BY `vendor` ASC;";
$resultArray1 = array();
$resultArray2 = array();
  
if ($result = mysqli_query($con, $sql1)) {
  $tempArray = array();
  
  while($row = $result->fetch_object()) {
    $tempArray = $row;
    array_push($resultArray1, $tempArray);
  }
}

if ($result = mysqli_query($con, $sql2)) {
  $tempArray = array();
  
  while($row = $result->fetch_object()) {
    $tempArray = $row;
    array_push($resultArray2, $tempArray);
  }
}


$now = new \DateTime('now');
$month = $now->format('m');
$year = $now->format('Y');
$day = $now->format('d');
$currentDate = $year . "-" . $month . "-" . $day;

if ($_GET) {
  $currentDate = mysqli_real_escape_string($con, $_GET['date']);  
}

$sql3 = "SELECT a.id, a.date, a.time, a.vendor_id, b.vendor, a.payment_id, c.payment, a.amount, a.note FROM MyExp_Data_Home a, MyExp_Vendors_Home b, MyExp_Payments_Home c WHERE a.vendor_id = b.id AND a.payment_id = c.id AND a.date = '$currentDate' AND a.is_active = '1' ORDER BY a.time DESC;";
$resultArray3 = array();

if ($result = mysqli_query($con, $sql3)) {
  $tempArray = array();
  $resultArray = array();
  
  while($row = $result->fetch_object()) {
    $tempArray = $row;
    array_push($resultArray3, $tempArray);
  }
}


$currentYear = date('Y');
$sql4 = "SELECT a.vendor_id AS 'id', b.vendor AS 'name', COUNT(a.vendor_id) AS 'total' FROM MyExp_Data_Home a, MyExp_Vendors_Home b WHERE a.vendor_id = b.id AND date LIKE '$currentYear%' GROUP BY `vendor_id` ORDER BY `total` DESC LIMIT 10;";
$resultArray4 = array();

if ($result = mysqli_query($con, $sql4)) {
  $tempArray = array();
  $resultArray = array();
  
  while($row = $result->fetch_object()) {
    $tempArray = $row;
    array_push($resultArray4, $tempArray);
  }
}


header('Content-Type: application/json');
$data = array(array('payments' => $resultArray1), array('vendors' => $resultArray2), array('expense' => $resultArray3), array('top10' => $resultArray4));
echo json_encode($data);

mysqli_close($con);
?>
