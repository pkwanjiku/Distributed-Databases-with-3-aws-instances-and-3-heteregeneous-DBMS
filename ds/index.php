<?php
//connections
#connection 001

define('DB_SERVER1','18.218.43.161');
define('DB_USER1','Ian');
define('DB_PASS1' ,'123');
define('DB_NAME1', 's1');
$winmssql_con = mysqli_connect(DB_SERVER1,DB_USER1,DB_PASS1,DB_NAME1);

#connection 002
//site 2 connection - postgresql
$link = pg_connect ("host=18.223.121.93 dbname=s2 user=postgres password=");
// $result = pg_exec($link, "select * from F14");

#connection 003
// site 3 connection
define('DB_SERVER','18.225.33.110');
define('DB_USER','pk');
define('DB_PASS' ,'123');
define('DB_NAME', 'Site3');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);


//check if county id is set
if(isset($_GET['county'])){
	$county = $_GET['county'];
}else{
	$county = 1;
}




//Functions
function split_name($name) {

	$arr = preg_split('/[\s,"]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
	return $arr;

}

function qdecomposition($calculus_query)
{
	$i = 0;
	$sel = -1;
	$from = -1;
	// $where = 0;
	$cquery = $calculus_query;
	for ($i=0; $i < sizeof($cquery); $i++) { 

		if ($cquery[$i] == "SELECT") {
			# code...
			$cquery[$i] = "π";
			$sel = $i;

		}

		if ($cquery[$i] == "WHERE") {
			# code...
			$cquery[$i] = "σ";
		}

		if ($cquery[$i] == "FROM") {
			# code...
			$from = $i;
		}
		# code...
	}

	if(($sel == -1) OR ($sel > $from)){
		return -1;
	}

	$projection  = "";
	$cartprod = "";
	$selection = "";
	$i = 0;

	while (($cquery[$i] != "FROM") AND ($i < sizeof($cquery)) ) {
		$projection = $projection . " " . $cquery[$i];
		$i = $i + 1;
	}


	$i = $i + 1;
	

	while (($cquery[$i] != "σ") AND ($i < sizeof($cquery)) ) {
		$cartprod = $cartprod . " " . $cquery[$i];
		if ($i < sizeof($cquery)) {
			$i = $i + 1;
		}	
	}


	while ($i < sizeof($cquery) ) {
		$selection = $selection . " " . $cquery[$i];
		if ($i < sizeof($cquery)) {
			$i = $i + 1;
		}
	}



	$algebraic_query =  $projection . " (" . $selection . " (" . $cartprod . " ) )";

	return($algebraic_query);
	# code...
}

function data_localiser($algebraic_query, $fragment){

	$algebraic_query = split_name($algebraic_query);


	$projection  = "";
	$cartprod = "";
	$selection = "";
	$i = 0;

	while (($algebraic_query[$i] != "(") AND ($i < sizeof($algebraic_query)) ) {
		$projection = $projection . " " . $algebraic_query[$i];
		$i = $i + 1;
	}


	$i = $i + 1;
	

	while (($algebraic_query[$i] != "(") AND ($i < sizeof($algebraic_query))) {
		$selection = $selection . " " . $algebraic_query[$i];
		if ($i < sizeof($algebraic_query)) {
			$i = $i + 1;
		}	
	}
	$i = $i + 1;

	while (($algebraic_query[$i] != ")") AND ($i < sizeof($algebraic_query))) {
		$cartprod = $cartprod . " " . $algebraic_query[$i];
		if ($i < sizeof($algebraic_query)) {
			$i = $i + 1;
		}
	}
	// Generate relevant queries and communication information



	// Store to json

	$projection = split_name($projection);
	$fragss2 = [ "F4", "F9", "F12"];


if (in_array ( $fragment, $fragss2)) {
			for ($i=0; $i < sizeof($projection); $i++) {



		if ($projection[$i] == "π") {
			# code...
			$projection[$i] = "SELECT";

		}elseif ($projection[$i] == "*") {
			# code...
		}else{
			$projection[$i] = "\"".$projection[$i]."\"";
		}
	}

	$selection = split_name($selection);

	for ($i=0; $i < sizeof($selection); $i++) { 

		if ($selection[$i] == "σ") {
			# code...
			$selection[$i] = "WHERE";

		}
	}
	$selection[1] = "\"".$selection[1]."\"";

	$selection = implode(" ",$selection);
	$projection = implode(" ",$projection);

	$local_query = "".$projection." FROM ".$fragment." ".$selection."";
	return $local_query;
	}else{



	for ($i=0; $i < sizeof($projection); $i++) {



		if ($projection[$i] == "π") {
			# code...
			$projection[$i] = "SELECT";

		}elseif ($projection[$i] == "*") {
			# code...
		}else{
			// $projection[$i] = "\"".$projection[$i]."\"";
		}
	}

	$selection = split_name($selection);

	for ($i=0; $i < sizeof($selection); $i++) { 

		if ($selection[$i] == "σ") {
			# code...
			$selection[$i] = "WHERE";

		}
	}
	// $selection[1] = "\"".$selection[1]."\"";

	$selection = implode(" ",$selection);
	$projection = implode(" ",$projection);

	$local_query = "".$projection." FROM ".$fragment." ".$selection."";
	return $local_query;





	}

		// echo "string";


}


?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Application 1: Result per County</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    body {
        color: #566787;
        background: #f5f5f5;
		font-family: 'Roboto', sans-serif;
	}
	.table-wrapper {
        width: 850px;
        background: #fff;
        padding: 20px 30px 5px;
        margin: 30px auto;
        box-shadow: 0 0 1px 0 rgba(0,0,0,.25);
    }
	.table-title .btn-group {
		float: right;
	}
	.table-title .btn {
		min-width: 50px;
		border-radius: 2px;
		border: none;
		padding: 6px 12px;
		font-size: 95%;
		outline: none !important;
		height: 30px;
	}
    .table-title {
		border-bottom: 1px solid #e9e9e9;
		padding-bottom: 15px;
		margin-bottom: 5px;
		background: rgb(0, 50, 74);
		margin: -20px -31px 10px;
		padding: 15px 30px;
		color: #fff;
    }
    .table-title h2 {
		margin: 2px 0 0;
		font-size: 24px;
	}
    table.table tr th, table.table tr td {
        border-color: #e9e9e9;
		padding: 12px 15px;
		vertical-align: middle;
    }
	table.table tr th:first-child {
		width: 40px;
	}
	table.table tr th:last-child {
		width: 100px;
	}
    table.table-striped tbody tr:nth-of-type(odd) {
    	background-color: #fcfcfc;
	}
	table.table-striped.table-hover tbody tr:hover {
		background: #f5f5f5;
	}
    table.table td a {
        color: #2196f3;
    }
	table.table td .btn.manage {
		padding: 2px 10px;
		background: #37BC9B;
		color: #fff;
		border-radius: 2px;
	}
	table.table td .btn.manage:hover {
		background: #2e9c81;		
	}
</style>


</head>
<body>
	<div class="table-wrapper">
	    <div class="table-title">
            <div class="row">
                <div class="col-sm-6"><h2>Application 1: <b>Sort By County</b></h2></div>
                <div class="col-sm-6">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-info active" >
                        	<a href="index.php?county=1">Mombasa</a>
                        </label>
                        <label class="btn btn-success">
                        	<a href="index.php?county=2">Kwale</a>
                        </label>
                        <label class="btn btn-warning">
                        	<a href="index.php?county=12">Meru</a>
                        </label>
                        <label class="btn btn-danger">
                        	<a href="index.php?county=30">Baringo</a>
                        </label>
                        <label class="btn btn-info active">
                        	<a href="index.php?county=47"> Nairobi</a>
                            
                        </label>						
                    </div>
                </div>
            </div>
        </div>
        <p>Click  <a href="app1.php">here</a>  to see application 2</p><br>
        <p>Click  <a href="app2.php">here</a>  to see application 3</p><br>
    </div>
<?php
$query = "SELECT * FROM t1 WHERE County_Id = ".$county."";
// $result = mysqli_query($winmssql_con, $query);
$tokens = split_name($query);
$algebraicquery =  qdecomposition($tokens);

//Fragments version 001 containing county_id, name, and number of households -- subsets of table 1
$fragsv1 = ["F1", "F2", "F3", "F4", "F5"];
//Fragments version 002 containing county_id, Area, and Total Population -- subsets of table 1
$fragsv2 = ["F6", "F7", "F8", "F9", "F10"];
//Fragments version 001 containing county_id, Month, Climate_no and Rainfall -- subsets of table 2
$fragsv3 = ["F11", "F12", "F13", "F14", "F15"];


//fragments per site
$fragss1 = ["F2", "F7","F1", "F5", "F8", "F11", "F14"];
$fragss2 = [ "F4",  "F9", "F12"];
$fragss3 = ["F3", "F6", "F10", "F13", "F15"];



//Query fragments of type 001
$i = 0;
foreach ($fragsv1 as $frags) {

	# code...
	$local_query = data_localiser($algebraicquery, $frags);

	//Query depending on site
	if (in_array ( $frags, $fragss1)) {
			$results = mysqli_query($winmssql_con, "$local_query");
			if ((mysqli_num_rows($results) != 0)) {
				$fv1results[$i] = mysqli_fetch_array($results) ;
				$i++;
			}
	}elseif (in_array ( $frags, $fragss2)) {

		$results = pg_exec($link, "$local_query");
		if ((pg_numrows($results) != 0)) {

			$fv1results[$i] = pg_fetch_array($results);
			$i++;

		}
			

	}elseif (in_array ( $frags, $fragss3)) {
			$results = mysqli_query($con, "$local_query");
			if ((mysqli_num_rows($results) != 0)) {
				$fv1results[$i] = mysqli_fetch_array($results) ;
				$i++;echo "string<br>";
			}
	}





}

//Query fragments of type 002
$i = 0;
foreach ($fragsv2 as $frags) {
	# code...

	$local_query = data_localiser($algebraicquery, $frags);

		//Query depending on site
	if (in_array ( $frags, $fragss1)) {

			$results = mysqli_query($winmssql_con, "$local_query");
			if ((mysqli_num_rows($results) != 0)) {
				$fv2results[$i] = mysqli_fetch_array($results) ;
				$i++;


			}
	}elseif (in_array ( $frags, $fragss2)) {


		$results = pg_exec($link, "$local_query");
		if ((pg_numrows($results) != 0)) {
			$fv2results[$i] = pg_fetch_array($results);
			$i++;
		}

	}elseif (in_array ( $frags, $fragss3)) {
			$results = mysqli_query($con, "$local_query");
			if ((mysqli_num_rows($results) != 0)) {
				$fv2results[$i] = mysqli_fetch_array($results) ;
				$i++;
			}
	}

}


//Merge the two fragments since they are VF products
// $vf_final = [];
$j = 0;

for ($i=0; $i < sizeof($fv2results); $i++) { 
	# code...
	for ($k=0; $k < sizeof($fv1results); $k++) { 
		if ($fv2results[$i]["County_Id"] == $fv1results[$k]["County_Id"]) {
			# code...
			// echo "huree";
			$vf_final[$j]["County_Id"] = $fv2results[$i]["County_Id"];
			$vf_final[$j]["Name"] = $fv1results[$k]["Name"];
			$vf_final[$j]["households"] = $fv1results[$k]["households"];
			$vf_final[$j]["area"] = $fv2results[$i]["Area"];
			$vf_final[$j]["population"] = $fv2results[$i]["Popu"];
		}
	}
}




?>
    <div class="table-wrapper">
	    <div class="table-title">
            <div class="row">
                <div class="row"><h2>Parent Application 1  : <b><?php echo ($query);  ?></b></h2></div><br>
                <div class="row"><h2>Algebra : <b><?php echo ($algebraicquery);  ?></b></h2></div><br><br>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>County Id</th>
                    <th>Name</th>
                    <th>Households</th>
                    <th>Area</th>
                    <th>Population</th>

                </tr>
            </thead>
            <tbody>
            	<?php
            	for ($i=0; $i < sizeof($vf_final); $i++) {
            		echo "


                <tr data-status='inactive'>
                    <td>".$vf_final[$i]["County_Id"]."</td>
                    <td>".$vf_final[$i]["Name"]."</td>
                    <td>".$vf_final[$i]["households"]."</td>
                    <td>".$vf_final[$i]["area"]."</td>
                    <td>".$vf_final[$i]["population"]."</td>
                </tr>


            		";
            		# code...
            	}
            	?>


            </tbody>
        </table>
    </div>     
<?php
$query = "SELECT * FROM t2 WHERE County_Id = ".$county."";

$tokens = split_name($query);
$algebraicquery =  qdecomposition($tokens);

//Query fragments of type 001
$i = 0;
foreach ($fragsv3 as $frags) {
	# code...
	$local_query = data_localiser($algebraicquery, $frags);


		//Query depending on site
	if (in_array ( $frags, $fragss1)) {
		$results = mysqli_query($winmssql_con, "$local_query");
		if ((mysqli_num_rows($results) != 0)) {
			while($row=mysqli_fetch_array($results)){
			$fv3results[$i] = $row;
			$i++;
		}
		}
	}elseif (in_array ( $frags, $fragss2)) {

		$results = pg_exec($link, "$local_query");
		if ((pg_numrows($results) != 0)) {
			while($row=pg_fetch_array($results)){
			$fv3results[$i] = $row;
			$i++;
		}
		}

	}elseif (in_array ( $frags, $fragss3)) {

		$results = mysqli_query($con, "$local_query");
		if ((mysqli_num_rows($results) != 0)) {
			while($row=mysqli_fetch_array($results)){
			$fv3results[$i] = $row;
			$i++;
		}
		}

	}




}

?>

    <div class="table-wrapper">
	    <div class="table-title">
            <div class="row">
                <div class="row"><h2>Child Application 1: <b><?php echo $query;  ?></b></h2></div>
            </div><br>
            <div class="row">
                <div class="row"><h2>Algebraic Query: <b><?php echo $algebraicquery;  ?></b></h2></div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>County_Id</th>
                    <th>Month</th>
                    <th>Climate_No</th>
                    <th>Rainfall</th>

                </tr>
            </thead>
            <tbody>

            <?php

            foreach ($fv3results as $record) {
            	# code...
            	echo "
            	<tr data-status='inactive'>
                    <td>".$record["County_Id"]."</td>
                    <td>".$record["month"]."</td>
                    <td>".$record["Climate_no"]."</td>
                    <td>".$record["Rainfall"]."</td>
                </tr>


            	";
            }

            ?>
                
            </tbody>
        </table>
    </div>  


</body>
</html>                                		                            