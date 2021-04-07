<?php
// http://www.mysohoplace.com/php_hdb/php_GL/homee/expenses_change.php

// Create connection
include('connection_header.php');

if ($_POST) {
  $id = mysqli_real_escape_string($con, $_POST['id']);
  $expDate = mysqli_real_escape_string($con, $_POST['date']);
  $expTime = mysqli_real_escape_string($con, $_POST['time']);
  $vendorId = mysqli_real_escape_string($con, $_POST['vendorid']);
  $paymentId = mysqli_real_escape_string($con, $_POST['paymentid']);
  $amount = mysqli_real_escape_string($con, $_POST['amount']);
  $note = mysqli_real_escape_string($con, $_POST['note']);
  $isEdit = mysqli_real_escape_string($con, $_POST['isedit']);
  
  //-- $id == -1 : insert
  //-- $id >= 0, $isEdit == 0 : delete
  //-- $id >= 0, $isEdit == 1 : update
  
  $insertSql = "INSERT INTO `MyExp_Data_Home`(`date`, `time`, `vendor_id`, `payment_id`, `amount`, `note`, `is_active`) VALUES ('$expDate', '$expTime', $vendorId, $paymentId, '$amount', '$note', '1');";

  $updateSql = "UPDATE `MyExp_Data_Home` SET `date`='$expDate', `time`='$expTime', `vendor_id`=$vendorId, `payment_id`=$paymentId, `amount`='$amount', `note`='$note' WHERE `id` = $id and `is_active` = '1';";

  $deleteSql = "DELETE FROM `MyExp_Data_Home` WHERE `id` = $id and `is_active` = '1';";
  
  if ($id == '-1') {
    $result2 = mysqli_query($con, $insertSql);
  }
  else {
    if ($isEdit == '0') {
      $result3 = mysqli_query($con, $deleteSql);
    }
    else {
      $result4 = mysqli_query($con, $updateSql);
    }
  }
  
  
  //-- get latest data
  $sql3 = "SELECT a.id, a.date, a.time, a.vendor_id, b.vendor, a.payment_id, c.payment, a.amount, a.note FROM MyExp_Data_Home a, MyExp_Vendors_Home b, MyExp_Payments_Home c WHERE a.vendor_id = b.id AND a.payment_id = c.id AND a.date = '$expDate' AND a.is_active = '1' ORDER BY a.time DESC;";
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
  $sql4 = "SELECT a.vendor_id AS 'id', b.vendor AS 'vendor', COUNT(a.vendor_id) AS 'total' FROM MyExp_Data_Home a, MyExp_Vendors_Home b WHERE a.vendor_id = b.id AND date LIKE '$currentYear%' GROUP BY `vendor_id` ORDER BY `total` DESC LIMIT 10;";
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
  $data = array(array('expense' => $resultArray3), array('top10' => $resultArray4));
  echo json_encode($data);
}
else {
  echo "not POST method";
}

mysqli_close($con);
?>
