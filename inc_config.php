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