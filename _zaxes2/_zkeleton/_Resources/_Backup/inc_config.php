<?	$user = "root";
		$password = "";
		$dbname = "sjholdin_db";
		if($_SERVER[HTTP_HOST]=="" || count(explode(".",$_SERVER[HTTP_HOST]))>1)
		{	$user = "sjholdin_root";
			$password = "pM35d(uE:HI~";
			$dbname = "sjholdin_db";
		}
		$rs = new Result("localhost", $user, $password, $dbname);
		mysql_query("SET NAMES UTF8");
?>