<?php 
include "inc_header.php";

// // toonoo==>Connect To DB
// $hostname="localhost";
// $database="thenotting_db";
// $username="root";
// $password="root";

// @$con = mysqli_connect($hostname, $username, $password)
//         or die("Could not connect to server " . mysql_error()); 
//     mysqli_select_db($con, $database)
//         or die("Error: Could not connect to the database: " . mysql_error());

//     /*Check for Connection*/
//     if(mysqli_connect_errno()){
//         // Display Error message if fails
//         echo 'Error, could not connect to the database please try again again.';
//     exit();
//     }

$planid = $_GET['planid'];
// $maxid = $db->GetOne("SELECT MAX(id) FROM plans WHERE enable='1' ");
// $planAll = $db->GetAssoc("SELECT * FROM plans WHERE enable='1' ORDER BY created_date DESC ");

$maxid_ = mysqli_query($con,"SELECT MAX(id) FROM plans WHERE enable='1' ");
$maxid_arr = mysqli_fetch_row($maxid_);
$maxid = $maxid_arr[0];

$planAll = mysqli_query($con,"SELECT * FROM plans WHERE enable='1' ORDER BY created_date DESC ");

if ($planid) {
	// $planDetail = $db->GetRow("SELECT * FROM plans WHERE enable='1' AND id='$planid' ");
	$planDetails = mysqli_query($con,"SELECT * FROM plans WHERE enable='1' AND id='$planid' ");
	$planDetail = mysqli_fetch_assoc($planDetails);
}else{
	// $planDetail = $db->GetRow("SELECT * FROM plans WHERE enable='1' AND id='$maxid' ");
	$planDetails = mysqli_query($con,"SELECT * FROM plans WHERE enable='1' AND id='$maxid' ");
	$planDetail = mysqli_fetch_assoc($planDetails);
}
// print_r($planDetail);die();
// $planHouse = $db->GetAssoc("SELECT * FROM plans_house WHERE enable='1' AND plans_id='".$planDetail['id']."' ");
$planHouse = mysqli_fetch_assoc($con,"SELECT * FROM plans_house WHERE enable='1' AND plans_id='".$planDetail['id']."' ");
// dump($planDetail);die();
?>

<section class="plan clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo "แบบบ้าน";}else{echo"House Plans";} ?></h1>
					<img src="images/border_about.png">
				</div>
				<div class="clearfix"></div>

				<?php foreach ($planAll as $key => $value) { ?>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<div class="home <?php if($planid==null){if($key==$maxid) echo "active";}else{if($key==$planid) echo "active";} ?>">
						<a href="?planid=<?php echo $key; ?>">
							<div class="pic">
								<img src="images/plans/<?php echo $value['image']; ?>">
								<div class="hoblack"></div>
							</div>
							<div class="title">
								<h3><?php echo $value['name_en']; ?></h3>
								<p><?php echo $value['name_th']; ?></p>
							</div>
						</a>
					</div>
					<div class="triangle <?php if($planid==null){if($key==$maxid) echo "active";}else{if($key==$planid) echo "active";} ?>"></div>
				</div>
				<?php } ?>
				<div class="clearfix"></div>
				<hr>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="imgmain">
						<img src="images/plans/<?php echo $planDetail['image']; ?>" style="width:100%;">
					</div>
					<?php foreach ($planHouse as $key => $value) { ?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0;">
						<a href="images/plans/<?php echo $value['image']; ?>" class="fancybox" data-fancybox-group="gallery">
							<div class="footpan">
								<img src="images/plans/<?php echo $value['image']; ?>">
								<div class="hoblack"></div>
							</div>
						</a>
						<p class="plan_design"><?php echo $value['name_'.$lang]; ?></p>
					</div>
					<?php } ?>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="plan_detail">
						<p class="capitalize"><?php echo $planDetail['name_en']." ".$planDetail['name_th']; ?></p>
						<?php echo $planDetail['detail_'.$lang]; ?>
					</div>
					<div class="note">
						<p>
							<?php if($lang=='th'){ ?>
							* ภาพแสดงการแบ่งพื้นที่ใช้สอยภายในอาคารเท่านั้น<br>
							* บริษัทขอสงวนสิทธิ์ในการเปลี่ยนแปลงข้อมูลรายละเอียด โดยมิต้องแจ้งให้ทราบล่วงหน้า
							<?php }else{ ?>
							* The picture shows the division of space indoors.<br>
							* The Company reserves the right to change the information. Without notice.
							<?php } ?>
						</p>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>

<script type="text/javascript" src="fancybox/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript">
$(document).ready(function() {
	$('.fancybox').fancybox();
});
</script>