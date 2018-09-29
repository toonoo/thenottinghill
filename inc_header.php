<?php  
session_start();
$page = basename($_SERVER['PHP_SELF']); 

if($_GET['lang']){
    $_SESSION['lang'] = $_GET['lang'];
}else{
    $_SESSION['lang'] = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'th';
}
$lang = $_SESSION['lang'];

// Counter 
$sql = "SELECT * FROM counter WHERE IP='$_SERVER[REMOTE_ADDR]' AND DATE = '".date("Y-m-d")."' ";  

$result = mysqli_query($sql);  

$num = mysqli_num_rows($result);  
if($num == 0){
    $strSQL = " INSERT INTO counter (DATE,IP) VALUES ('".date("Y-m-d")."','".$_SERVER["REMOTE_ADDR"]."') ";
    mysqli_query($strSQL);   
}

$slider = mysqli_query("SELECT * FROM slide WHERE enable='1' ORDER BY created_date DESC");

// if($db->isConnected()){
// 	echo 'toonoo1111222';die();
// 	$slider = $db->GetAssoc("SELECT * FROM slide WHERE enable='1' ORDER BY created_date DESC");
// } else {
	// echo 'toonoo=';die();
// }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>The Notting Hill</title>

	<!-- Style CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,900' rel='stylesheet' type='text/css'>
	<!-- Favicons -->
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80650434-6', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>

	<div class="wrapper">
		<section class="topbar clearfix">
    		<div class="container">
    	        <div class="row">
    	            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    	            	<div class="logo">
    	            		<img src="images/top_logo.png">
    	            	</div>
    	            </div>
    	            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    	            	<div>
    	            		<ul class="topmenu">
    	            			<li><a href="news.php">SOCIETY</a></li>
    	            			<li><a href="about.php">ABOUT US</a></li>
    	            			<li class="toplang"><img src="images/icon_anthems_thai.png">&nbsp;<?php if($lang=='th'){echo "ไทย";}else{echo"English";} ?>&nbsp;<i class="fa fa-caret-down"></i>
    	            				<ul class="subtopbar">
	                                    <li><a href="<?php basename($_SERVER['PHP_SELF'])?>?<?php parse_str($_SERVER['QUERY_STRING']."&lang=en", $query_string); echo http_build_query($query_string); ?>"><img src="images/icon_anthems_eng.png">&nbsp;<?php if($lang=='th'){echo "อังกฤษ";}else{echo"English";} ?></a></li>
	                                    <li><a href="<?php basename($_SERVER['PHP_SELF'])?>?<?php parse_str($_SERVER['QUERY_STRING']."&lang=th", $query_string); echo http_build_query($query_string); ?>"><img src="images/icon_anthems_thai.png">&nbsp;<?php if($lang=='th'){echo "ไทย";}else{echo"Thai";} ?></a></li>
	                                </ul>
    	            			</li>
    	            		</ul>
    	            	</div>
    	            </div>
    	        </div>
    	    </div>
    	</section>

		<header class="header clearfix">
			<div class="container">
    		   	<div class="row">			
    		        <div class="col-lg-12">
    		        	<?php if ($page=='index.php') { ?>
						<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						  	<!-- Indicators -->
							<ol class="carousel-indicators">
								<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
								<li data-target="#carousel-example-generic" data-slide-to="1"></li>
								<li data-target="#carousel-example-generic" data-slide-to="2"></li>
							</ol>
						  	<!-- Wrapper for slides -->
						  	<div class="carousel-inner" role="listbox">
						  		<?php foreach ($slider as $key => $value) { ?>
						  	  	<div class="item <?php if($key=='1'){echo "active";} ?>">
						  	  	  	<img src="images/slide/<?php echo $value['image']; ?>" alt="">
						  	  	</div>
						  	  	<?php } ?>
						  	</div>
						  	<!-- Controls -->
							<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
							  	<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							  	<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
							  	<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							  	<span class="sr-only">Next</span>
							</a>
						</div>
						<?php } ?>
						<nav class="navbar navbar-default <?php if($page=='index.php'){echo 'shadow';} ?>" role="navigation">
    		                <div class="navbar-header">
    		                   <button type="button" data-toggle="collapse" data-target="#defaultmenu" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
    	                    </div>
    	                    <div id="defaultmenu" class="navbar-collapse collapse container">
    	                        <ul class="nav navbar-nav">
    	                            <li><a href="index.php" class="<?php if($page=='index.php'){echo "active";} ?>"><?php if($lang=='th'){echo "หน้าแรก";}else{echo"Home";} ?></a></li>
    	                            <li><a href="projectdata.php" class="<?php if($page=='projectdata.php'){echo "active";} ?>"><?php if($lang=='th'){echo "ข้อมูลโครงการ";}else{echo"Information";} ?></a></li>
    	                            <li><a href="plans.php" class="<?php if($page=='plans.php'){echo "active";} ?>"><?php if($lang=='th'){echo "แบบบ้าน";}else{echo"House Type";} ?></a></li>
    	                            <li><a href="gallery.php" class="<?php if($page=='gallery.php'){echo "active";} ?>"><?php if($lang=='th'){echo "แกลอรี่";}else{echo"Gallery";} ?></a></li>
    	                            <li><a href="progress.php" class="<?php if($page=='progress.php'){echo "active";} ?>"><?php if($lang=='th'){echo "ความคืบหน้าโครงการ";}else{echo"Progress";} ?></a></li>
    	                            <li><a href="location.php" class="<?php if($page=='location.php'){echo "active";} ?>"><?php if($lang=='th'){echo "ที่ตั้งโครงการ";}else{echo"Location";} ?></a></li>
    	                            <li><a href="appointments.php" class="<?php if($page=='appointments.php'){echo "active";} ?>"><?php if($lang=='th'){echo "นัดหมายเข้าชมโครงการล่วงหน้า";}else{echo"Appointment";} ?></a></li>
    	                        </ul>
    	                    </div>
						</nav>
    	            </div>
				</div>
			</div>

		</header>