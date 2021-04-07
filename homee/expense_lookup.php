<?php
// http://www.mysohoplace.com/php_hdb/php_GL/homee/expense_lookup.php

// Create connection
include('connection_header.php');

$now = new \DateTime('now');
$month = $now->format('m');
$year = $now->format('Y');
$day = $now->format('d');
$monthAndYear = $year . "-" . $month . "-%";

if ($_GET) {
  $monthAndYear = mysqli_real_escape_string($con, $_GET['date']) . "-%";
}

$sql3 = "SELECT a.id, a.date, a.time, a.vendor_id, b.vendor, a.payment_id, c.payment, a.amount, a.note FROM MyExp_Data_Home a, MyExp_Vendors_Home b, MyExp_Payments_Home c WHERE a.vendor_id = b.id AND a.payment_id = c.id AND a.date LIKE '$monthAndYear' AND a.is_active = '1' ORDER BY a.date DESC, a.time DESC;";
$resultArray3 = array();

if ($result = mysqli_query($con, $sql3)) {
  $tempArray = array();
  $resultArray = array();
  
  while($row = $result->fetch_object()) {
    $tempArray = $row;
    array_push($resultArray3, $tempArray);
  }
}

header('Content-Type: application/json');
echo json_encode($resultArray3);

mysqli_close($con);
?>
