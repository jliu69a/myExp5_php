<?php
//http://www.mysohoplace.com/php_hdb/php_GL/homee/payments_vendors_edit_2.php

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
            $result3 = mysqli_query($con, $deleteSql);
        if ($isEdit == '0') {
        }
        else {
            $result4 = mysqli_query($con, $updateSql);
        }
    }
    
    //-- query latest data
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
    
    header('Content-Type: application/json');
    $data = array(array('payments' => $resultArray1), array('vendors' => $resultArray2));
    echo json_encode($data);
}
else {
  echo "not POST method";
}

mysqli_close($con);
?>
