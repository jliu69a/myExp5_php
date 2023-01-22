<?php
// http://www.mysohoplace.com/php_hdb/php_GL/homee/expenses_change.php

// Create connection
include('connection_header.php');

if ($_GET) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $expDate = mysqli_real_escape_string($con, $_GET['date']);
    $expTime = mysqli_real_escape_string($con, $_GET['time']);
    $vendorId = mysqli_real_escape_string($con, $_GET['vendorid']);
    $paymentId = mysqli_real_escape_string($con, $_GET['paymentid']);
    $amount = mysqli_real_escape_string($con, $_GET['amount']);
    $note = mysqli_real_escape_string($con, $_GET['note']);
    $isEdit = mysqli_real_escape_string($con, $_GET['isedit']);
    
    $note = str_replace("+", " ", $note);
    $note = str_replace("*plus*", "+", $note);
    $note = str_replace("*and*", "&", $note);
    
    //-- $id == -1 : insert
    //-- $id >= 0, $isEdit == 0 : delete
    //-- $id >= 0, $isEdit == 1 : update
    
    $insertSql = "INSERT INTO `MyExp_Data_Home`(`date`, `time`, `vendor_id`, `payment_id`, `amount`, `note`, `is_active`, `update_date`) VALUES ('$expDate', '$expTime', $vendorId, $paymentId, '$amount', '$note', '1', now());";
    
    $updateSql = "UPDATE `MyExp_Data_Home` SET `date`='$expDate', `time`='$expTime', `vendor_id`=$vendorId, `payment_id`=$paymentId, `amount`='$amount', `note`='$note', `update_date`=now() WHERE `id` = $id and `is_active` = '1';";
    
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
}
else {
  echo "not GET method";
}

//-- get latest data
$sql3 = "SELECT a.id, a.date, a.time, a.vendor_id, b.vendor, a.payment_id, c.payment, a.amount, a.note FROM MyExp_Data_Home a, MyExp_Vendors_Home b, MyExp_Payments_Home c WHERE a.vendor_id = b.id AND a.payment_id = c.id AND a.date = '$expDate' AND a.is_active = '1' ORDER BY a.time DESC;";
$resultArray3 = array();

if ($result3 = mysqli_query($con, $sql3)) {
    $tempArray = array();
    $resultArray = array();
    
    while($row3 = $result3->fetch_object()) {
        $tempArray = $row3;
        array_push($resultArray3, $tempArray);
    }
}

$currentYear = date('Y');
$sql4 = "SELECT a.vendor_id AS 'id', b.vendor AS 'vendor', COUNT(a.vendor_id) AS 'total' FROM MyExp_Data_Home a, MyExp_Vendors_Home b WHERE a.vendor_id = b.id AND date LIKE '$currentYear%' GROUP BY `vendor_id` ORDER BY `total` DESC LIMIT 10;";
$resultArray4 = array();

if ($result4 = mysqli_query($con, $sql4)) {
    $tempArray = array();
    $resultArray = array();
    
    while($row4 = $result4->fetch_object()) {
        $tempArray = $row4;
        array_push($resultArray4, $tempArray);
    }
}

//$parameters

header('Content-Type: application/json');
$data = array(array('expense' => $resultArray3), array('top10' => $resultArray4));
echo json_encode($data);

mysqli_close($con);
?>
