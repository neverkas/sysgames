<?php
$facebook_id = $_POST['facebook_id'];
$db = new PDO('mysql:dbname=emmade6_sys_pagos;host=localhost', 'emmade6_5y5g4m3s', '@)!%g!CX6IZOm-%');
$db->prepare("select * from sys_info where facebook_id = :facebook_id");
$db->bindParam(':facebook_id', $facebook_id, PDO::PARAM_STR);
$db->execute();
$result = $db->fetchAll();

echo "<pre>";
print_r($result);
echo "</pre>";

?>