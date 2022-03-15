<?php
$facebook_id 	= $_POST['facebook_id'];
$get_fields 	= $_POST['fields'];
/*
$db 			= new PDO('mysql:dbname=emmade6_sys_pagos;host=localhost', 'emmade6_5y5g4m3s', '@)!%g!CX6IZOm-%');
$update_status 	= $db->prepare("UPDATE sys_info SET status = :status WHERE facebook_id = :facebook_id");
$update_status->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
$update_status->bindParam(':facebook_id', $_POST['facebook_id'], PDO::PARAM_STR);
$update_status->execute();
*/
echo "<pre>";
print_r($_POST);
echo "</pre>";
?>