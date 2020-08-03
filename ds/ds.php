<?php

// if(isset($_POST['submit']))

//site 1 connection





//site 2 connection - postgresql
$link = pg_connect ("host=18.223.121.93 dbname=s2 user=postgres password=");
$result = pg_exec($link, "select * from F14");

// site 3 connection
// define('DB_SERVER','ec2-18-225-33-110.us-east-2.compute.amazonaws.com');
// define('DB_USER','root');
// define('DB_PASS' ,'');
// define('DB_NAME', 'Site3');
// $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);


$query = $_POST['query'];
error_reporting(E_ERROR | E_PARSE);



$tokens = split_name($query);
$algebraicquery =  qdecomposition($tokens);
$fragment_queries = data_localiser($algebraicquery);

// print_r();
echo $fragment_queries;

 //    $myarray = array();

	// while ($row = mysqli_fetch_array($fragment_queries)) {
 //  		print_r($row) ;
	// }

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

function data_localiser($algebraic_query){

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

	for ($i=0; $i < sizeof($projection); $i++) { 

		if ($projection[$i] == "π") {
			# code...
			$projection[$i] = "SELECT";

		}

		if (condition) {
			# code...
		}
	}

	$selection = split_name($selection);

	for ($i=0; $i < sizeof($selection); $i++) { 

		if ($selection[$i] == "σ") {
			# code...
			$selection[$i] = "WHERE";

		}
	}





	$col = ["*", "County_Id","Name", "households", "Area", "Popu", "month", "Climate_no" , "Rainfall"];

	$set1 = [ "Name", "households"];
	$set2 = [ "Area", "Popu"];
	$set3 = [ "month", "Climate_no" , "Rainfall"];


	$selectorvar = $selection[1];
	$selectorvar1 = $selection[3];
	$selectorcomp = $selection[2];


	for ($i=0; $i < sizeof($projection); $i++) {
		for ($j=0; $j < sizeof($col); $j++) {
			if ($projection[$i] == $col[$j]) {
				# code...
				#$columns["".$col[$j].""] = 1;
				if ($col[$j] == $selectorvar) {
					$columns["".$col[$j].""] = 2;

				}else{
					$columns["".$col[$j].""] = 1;


				}
				// 
			}else{
				$columns["".$col[$j].""] = 0;
				if ($col[$j] == $selectorvar) {
					$columns["".$col[$j].""] = -2;

				}
			}
			



	}

	}	






	
	$tables = split_name($cartprod);
	// $variables = split_name($variables);

	//connection 001

define('DB_SERVER1','18.218.43.161');
define('DB_USER1','Ian');
define('DB_PASS1' ,'123');
define('DB_NAME1', 's1');
$winmssql_con = mysqli_connect(DB_SERVER1,DB_USER1,DB_PASS1,DB_NAME1);



	if (sizeof($tables == 1)) {


		if (($tables[0] == "t1")) {
			# code...


			if (($columns["*"] == 1)) {
				# code...
				



				for ($i=0; $i < sizeof($set1); $i++) {


					if ($columns["".$set1[$i].""] == -2) {
						# code...
						// echo "ssdkhfkjsdhflkazsngy";
						$f1result = mysqli_query($winmssql_con, "SELECT * FROM F1 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f2result = mysqli_query($winmssql_con, "SELECT * FROM F2 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f3result = mysqli_query($winmssql_con,"SELECT * FROM F3 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f4result = mysqli_query($winmssql_con, "SELECT * FROM F4 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f5result = mysqli_query($winmssql_con,"SELECT * FROM F5 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");

	// $myarray = array();

	// while ($row = mysqli_fetch_array($f1result)) {
 //  		print_r($row) ;
	// }

					}else{

						$f1result = mysqli_query($winmssql_con, "SELECT County_Id, $set1[$i] FROM F2 ");
						$f2result = mysqli_query($winmssql_con,  "SELECT County_Id, $set1[$i] FROM F2 ");
						$f3result = mysqli_query($winmssql_con, "SELECT County_Id, $set1[$i] FROM F3 ");
						$f4result = mysqli_query($winmssql_con,  "SELECT County_Id, $set1[$i] FROM F4 ");
						$f5result = mysqli_query($winmssql_con, "SELECT County_Id, $set1[$i] FROM F5 ");


					}

				}

				for ($i=0; $i < sizeof($set2); $i++) {

					if ($columns["".$set2[$i].""] == -2) {
						# code...
						$f6result = mysqli_query($winmssql_con,"SELECT County_Id, $set2[$i] FROM F6 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f7result =  pg_exec($link, "SELECT County_Id, $set2[$i] FROM F7 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f8result = mysqli_query($winmssql_con,"SELECT County_Id, $set2[$i] FROM F8 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f9result =  pg_exec($link, "SELECT County_Id, $set2[$i] FROM F9 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
						$f10result = mysqli_query($winmssql_con, "SELECT County_Id, $set2[$i] FROM F10 WHERE ".$selectorvar." ".$selectorcomp." ".$selectorvar1."");
					}else{

						$f6result = mysqli_query($winmssql_con, "SELECT County_Id, $set2[$i] FROM F6 ");
						$f7result =  pg_exec($link,  "SELECT County_Id, $set2[$i] FROM F7 ");
						$f8result = mysqli_query($winmssql_con, "SELECT County_Id, $set2[$i] FROM F8 ");
						$f9result =  pg_exec($link,  "SELECT County_Id, $set2[$i] FROM F9 ");
						$f10result = mysqli_query($winmssql_con, "SELECT County_Id, $set2[$i] FROM F10 ");

					}

				}






			}
		}






























		# code...
	}elseif (($tables[0] == "t2")) {
		# code...
	}



	
	// if ($f6result) {
	// 	return 10;
	// 	# code...
	// }else{
	// 	return 0;
	// }
	$results = [ $f1result,  $f2result,  $f3result, $f4result, $f5result,  $f6result,  $f7result, $f8result, $f9result , $f10result];

		while ($row = mysqli_fetch_array($f3result)) {
			$myarray[] = $row;
			}

		

		# code...

	
  		
	
	$returns = json_encode($myarray);
	// echo $returns["0"];
	return($returns);
	
}





?>