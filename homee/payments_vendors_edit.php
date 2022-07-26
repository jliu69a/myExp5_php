<?php
//http://www.mysohoplace.com/php_hdb/php_GL/homee/payments_vendors_edit.php

// Create connection
include('connection_header.php');

if ($_POST) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $isForPayment = mysqli_real_escape_string($con, $_POST['ispayment']);
    $isEdit = mysqli_real_escape_string($con, $_POST['edit']);
    
    //-- save changes
    $name = str_replace("&amp;", "&", $name);
    
    $insertSql = "INSERT INTO `MyExp_Vendors_Home` (`vendor`) VALUES ('$name');";
    $updateSql = "UPDATE `MyExp_Vendors_Home` SET `vendor`='$name' WHERE `id`=$id;";
    $deleteSql = "DELETE FROM `MyExp_Vendors_Home` WHERE `id`=$id;";
    
    if ($isForPayment == '1') {
        $insertSql = "INSERT INTO `MyExp_Payments_Home` (`payment`) VALUES ('$name');";
        $updateSql = "UPDATE `MyExp_Payments_Home` SET `payment`='$name' WHERE `id`=$id;";
        $deleteSql = "DELETE FROM `MyExp_Payments_Home` WHERE `id`=$id;";
    }
    
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
    
    //-- query latest data
    $sql1 = "SELECT `id`, `payment` FROM `MyExp_Payments_Home` ORDER BY `payment` ASC;";
    $sql2 = "SELECT `id`, `vendor` FROM `MyExp_Vendors_Home` ORDER BY `vendor` ASC;";
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
    $data = array(array('payments' => $resultArray1), array('vendors' => $resultArray2), array('top10' => $resultArray4));
    echo json_encode($data);
}
else {
  echo "not POST method";
}

mysqli_close($con);
?>
