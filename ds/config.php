<?php
$link = pg_connect ("host=18.223.121.93 dbname=s2 user=postgres password=");
$value = 47;
$result = pg_exec($link, 'select * from F14 WHERE "County_Id" > 0');
?>