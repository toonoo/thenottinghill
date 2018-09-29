<?	include '_zaxes2/zaxes.php'	?>
<?	
		$db = NewADOConnection('mysql'); 
		// $db = &ADONewconnection('mysql');
		$db->debug = true;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		if(count(explode(".",$_SERVER[HTTP_HOST]))<=1)
			$db->Connect('localhost',"root","root","thenotting_db");
		else
			$db->Connect('localhost',"root","root","thenotting_db");
		mysql_query("SET NAMES UTF8");
		
?>

// toonoo //
<?php
// Connect To DB
$hostname="localhost";
$database="thenotting_db";
$username="root";
$password="root";

@$con = mysqli_connect($hostname, $username, $password)
        or die("Could not connect to server " . mysql_error()); 
    mysqli_select_db($con, $database)
        or die("Error: Could not connect to the database: " . mysql_error());

    /*Check for Connection*/
    if(mysqli_connect_errno()){
        // Display Error message if fails
        echo 'Error, could not connect to the database please try again again.';
    exit();
    }
?>