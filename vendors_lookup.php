<?php
// http://www.mysohoplace.com/php_hdb/php_GL/homee/vendors_lookup.php

// Create connection
include('connection_header.php');

if ($_GET) {
    $year = mysqli_real_escape_string($con, $_GET['year']);
    $vendorId = mysqli_real_escape_string($con, $_GET['vendorid']);
    
    if (is_null($vendorId)) {
        $vendorId = "0";
    }
    
    if (is_null($year)) {
        $year = date("Y");
    }
    
    $sql = "SELECT a.id, a.date, a.time, a.vendor_id, b.vendor, a.payment_id, c.payment, a.amount, a.note FROM MyExp_Data_Home a, MyExp_Vendors_Home b, MyExp_Payments_Home c WHERE a.vendor_id = b.id AND a.payment_id = c.id AND a.date LIKE '$year-%' AND `vendor_id` = $vendorId ORDER BY a.date DESC, a.time DESC;";
  
    if ($result = mysqli_query($con, $sql)) {
        $tempArray = array();
        $resultArray = array();
        
        while($row = $result->fetch_object()) {
            $tempArray = $row;
            array_push($resultArray, $tempArray);
        }
    }
    
    header('Content-Type: application/json');
    $data = array(array('expense' => $resultArray));
    
    echo json_encode($data);
}
else {
    echo "not POST method";
}

mysqli_close($con);
?>
