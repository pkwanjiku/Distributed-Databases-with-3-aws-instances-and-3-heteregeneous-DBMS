<?php
#connection 003
// site 3 connection
define('DB_SERVER','ec2-18-225-33-110.us-east-2.compute.amazonaws.com');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'Site3');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
?>