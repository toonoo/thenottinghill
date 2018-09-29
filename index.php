<?php 
include "inc_header.php"; 

// $news = $db->GetAssoc("SELECT * FROM news WHERE enable='1' ORDER BY created_date DESC LIMIT 2 ");
// $gallery = $db->GetAssoc("SELECT * FROM gallery WHERE enable='1' ORDER BY created_date DESC LIMIT 4 ");
// $pic = $db->GetRow("SELECT * FROM gallery WHERE enable='1' ORDER BY created_date DESC ");
// $sumcount = $db->GetOne("SELECT count(id) FROM counter ");
$con=mysqli_connect("localhost","root","root","thenotting_db");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$news = mysqli_query($con,"SELECT * FROM news WHERE enable='1' ORDER BY created_date DESC LIMIT 2 ");
$gallery = mysqli_query($con,"SELECT * FROM gallery WHERE enable='1' ORDER BY created_date DESC LIMIT 4 ");
$pic = mysqli_fetch_assoc($con,"SELECT * FROM gallery WHERE enable='1' ORDER BY created_date DESC ");

$row_sumcount = mysqli_query($con,"SELECT count(id) as totals FROM counter");
$sumcount = mysqli_fetch_row($row_sumcount);
?>

<section class="home clearfix">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="postwrapper clearfix">

					<div class="mappoint clearfix hideme">
						<div class="text-center mb10">
							<img src="images/logo_nottinghill_1.png" class="notting1">
							<img src="images/logo_nottinghill_2.png" class="notting2">
						</div>
						<div>
							<button class="btn mapmark btn_map" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><?php if($lang=='th'){echo "แผนที่ตั้งโครงการ";}else{echo"Project Location";} ?> <i class="fa fa-map-marker"></i></button><br>
						</div>
						<div class="collapse" id="collapseExample">
							<div class="well">
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15320.625279310289!2d103.25366635915829!3d16.263756897640214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDE1JzU5LjEiTiAxMDPCsDE1JzA2LjYiRQ!5e0!3m2!1sth!2sth!4v1435220903161" width="100%" height="350" frameborder="0" style="border:0"></iframe>
							</div>
						</div>
					</div>

					<div class="appoint clearfix hideme">
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
							<div class="text">
								<h3><?php if($lang=='th'){echo"นัดหมายเข้าชมโครงการล่วงหน้า";}else{echo"Make an appointment in advance";} ?></h3>
								<p><?php if($lang=='th'){echo"* กรุณากรอกรายละเอียดในแบบฟอร์มให้ครบถ้วน และระบุวันที่ท่านกำหนด เข้าเยี่ยมชมโครงการ (กรุณานัดหมายล่วงหน้าอย่างน้อย 1 วันทำการ) เพื่อให้เจ้าหน้าที่ได้รอต้อนรับ";}else{echo"* Please fill in the form. And specify the date you specify Visit Project (Please make reservations at least one day in advance), welcome.";} ?></p>
							</div>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
							<form method="POST" action="action.php?index">
								<input type="hidden" name="action" value="appoint" />
								<input type="hidden" name="action2" value="index" />
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="name"><?php if($lang=='th'){echo"ชื่อ";}else{echo"First Name";} ?></label>
										<input type="text" name="name" class="form-control" id="name" required>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="lastname"><?php if($lang=='th'){echo"นามสกุล";}else{echo"Last name";} ?></label>
										<input type="text" name="lastname" class="form-control" id="lastname" required>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="email"><?php if($lang=='th'){echo"อีเมล์";}else{echo"Email";} ?></label>
										<input type="email" name="email" class="form-control" id="email" required>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="phone"><?php if($lang=='th'){echo"เบอร์โทรศัพท์";}else{echo"Phone";} ?></label>
										<input type="text" name="phone" class="form-control" id="phone" maxlength="10" required pattern="[0-9]{10}" title="Fill only numbers">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="datepicker"><?php if($lang=='th'){echo"เลือกวันเข้าชมโครงการ";}else{echo"Select a day to visit projects";} ?></label>
										<input type="text" name="datepicker" class="form-control" id="datepicker" required><i class="fa fa-calendar"></i>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="timepicker"><?php if($lang=='th'){echo"เลือกเวลาเข้าชมโครงการ";}else{echo"Select a time to visit projects";} ?></label>
										<input type="text" name="timepicker" class="form-control" id="timepicker" required><i class="fa fa-clock-o"></i>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="detail"><?php if($lang=='th'){echo"รายละเอียดที่ต้องการเพิ่มเติม";}else{echo"Message";} ?></label>
										<textarea name="detail" class="form-control" rows="4" id="detail"></textarea>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group text-center">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn_form"><?php if($lang=='th'){echo"ส่งข้อความ";}else{echo"Send";} ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class="dee clearfix hideme">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="title">
								<h3><?php if($lang=='th'){echo "5 ดี ชีวิตคุณภาพ";}else{echo"5 Quality of Life";} ?></h3>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="boximg">
								<div class="boximg1"></div>
							</div>
							<div class="boxtext">
								<h3><?php if($lang=='th'){echo "วัสดุดี";}else{echo"Good materials";} ?></h3>
								<p><?php if($lang=='th'){echo "วัสดุที่ใช้ในโครงการล้วนคัดสรรมาเป็น อย่างดี เพื่อบ้านที่มีคุณภาพ";}else{echo"The materials used in the project were selected as well to quality home.";} ?></p>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="boximg">
								<div class="boximg2"></div>
							</div>
							<div class="boxtext">
								<h3><?php if($lang=='th'){echo "สังคมดี";}else{echo"Good society";} ?></h3>
								<p><?php if($lang=='th'){echo "การอยู่อาศัยที่เพียบพร้อมด้วยระบบ ความปลอดภัยตลอด 24 ชั่วโมง";}else{echo"The housing is equipped with the system. 24-hour security.";} ?></p>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="boximg">
								<div class="boximg3"></div>
							</div>
							<div class="boxtext">
								<h3><?php if($lang=='th'){echo "สิ่งแวดล้อมดี";}else{echo"Good environment";} ?></h3>
								<p><?php if($lang=='th'){echo "ให้ชีวิตได้ใกล้ชิดธรรมชาติ ด้วยสวนสวย  ต้นไม้ใหญ่ ใจกลางโครงการ";}else{echo"To live close to nature with beautiful gardens, mature trees, the heart of the project.";} ?></p>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="boximg">
								<div class="boximg4"></div>
							</div>
							<div class="boxtext">
								<h3><?php if($lang=='th'){echo "สิ่งอำนวยความสะดวกดี";}else{echo"Good facilities";} ?></h3>
								<p><?php if($lang=='th'){echo "พร้อมพรรค์ด้วยสิ่งอำนวยความสะดวก อย่าง ฟิตเนส สระว่ายน้ำ ร้านเครื่องดื่ม";}else{echo"Callings along with amenities such as a fitness, swimming pool, cafe.";} ?></p>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
							<div class="boximg">
								<div class="boximg5"></div>
							</div>
							<div class="boxtext">
								<h3><?php if($lang=='th'){echo "รสนิยมดี";}else{echo"Good taste";} ?></h3>
								<p><?php if($lang=='th'){echo "ด้วยการออกแบบที่ลงตัว ในรูปแบบบ้านสไตล์อังกฤษ ที่หลายคนหลงใหล";}else{echo"With a modular design English-style home in style Many passionate.";} ?></p>
							</div>
						</div>
					</div>

					<div class="news clearfix hideme">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 bder">
							<div class="text-center mb20">
								<h1><?php if($lang=='th'){echo"โครงการ หมู่บ้านเดอะน๊อตติ้งฮิลล์";}else{echo"The Notting Hill Village Project";} ?></h1>
								<p><?php if($lang=='th'){echo"เริ่มก่อตั้งขึ้นเมื่อวันที่ 13 มกราคม 2557 เป็นโครงการในเครือเดอะวิคตอเรีย พีค ขอนแก่นและมหาสารคาม  โดยโครงการเดอะวิคตอเรีย พีค มีบ้านสร้างแล้ว เสร็จ 102 หลัง โครงการเป็นธุรกิจประเภทพัฒนาอสังหริมทรัพย์เพื่อการขาย ทางโครงการมีการออกแบบหลากหลายเหมาะกับทุกครอบครัว ซึ่งมีลักษณะโดดเด่นในเรื่องดีไซน์ร่วมสมัยสไตล์อังกฤษ";}else{echo"Founded on January 13, 2557 as a project in Khon Kaen and Mahasarakham The Victoria Peak. The Victoria Peak project has completed 102 houses built after the project is the development of business real estate for estate for sale. The project design, suitable for all the family. Which is featured in the contemporary English style.";} ?> </p>
								<img src="images/logo_village.png">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p0">
							<div class="title">
								<h3><i class="fa fa-list-alt"></i>&nbsp;<?php if($lang=='th'){echo"ข่าวและโปรโมชั่น";}else{echo"News and Promotion";} ?></h3>
							</div>
							<?php foreach ($news as $key => $value) { 
								$date = date("d/m/Y",strtotime($value['created_date']));
							?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 box<?=$i?>">
								<div class="imgbox">
									<a href="news_detail.php?newsid=<?php echo $key; ?>">
										<img src="images/uploads/<?php echo $value['image']; ?>">
										<div class="ImageOverlayH"></div>
									</a>
								</div>
								<div class="boxtext">
									<h3><?php if($lang=='th'){echo"โปรโมชั่น";}else{echo"Promotion";} ?></h3>
									<p class="calendar"><i class="fa fa-calendar"></i>&nbsp;<?php echo $date; ?></p>
									<a href="news_detail.php?newsid=<?php echo $key; ?>">
										<p class="text"><?php echo $value['detail_'.$lang]; ?></p>
									</a>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="picture clearfix hideme">
						<div class="col-lg-12">
							<h3><i class="fa fa-picture-o"></i>&nbsp;<?php if($lang=='th'){echo"ภาพบรรยากาศโครงการ";}else{echo"Gallery";} ?></h3>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="atmos mb20">
								<img src="images/gallery/<?php echo $pic['image']; ?>">
								<div class="taxtbox"><?php if($lang=='th'){echo"เดอะน๊อตติ้งฮิลล์";}else{echo"The Notting Hill";} ?></div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding:0;">
							<?php foreach ($gallery as $key => $value) { ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="imgbox">
									<a href="javascript:void(0);" class="<?php if($key==$pic['id']){echo "active";} ?>">
										<img src="images/gallery/<?php echo $value['image']; ?>" alt="" class="">
										<div class="bgthumb"></div>
									</a>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="col-lg-12">
							<div class="totalcount"><?php if($lang=='th'){echo"ผู้เข้าชมเว็บไซต์";}else{echo"Counter";} ?> <?php echo number_format($sumcount[0]); ?> <?php if($lang=='th'){echo"ครั้ง";}else{echo"";} ?><div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>

<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	$( "#datepicker").datepicker({
		dateFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true
	}).datepicker("setDate", new Date());
});
</script>

<link rel="stylesheet" href="css/bootstrap-timepicker.css">
<script type="text/javascript" src="js/bootstrap-timepicker.js"></script>
<!-- <script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script> -->
<script type="text/javascript">
$(document).ready(function () { 
    $('#timepicker').timepicker({
    	minuteStep: 1,
    	secondStep: 1,
    	showMeridian: false,
    });
});
</script>

<script type="text/javascript">
$('document').ready(function(){
	$('.imgbox a').click(function(){
		var x = $(this).parent().find('img');
		var srcImg = x.attr('src');
		//var nameImg = x.attr('alt');
		$('.atmos img').fadeOut().attr('src', srcImg).fadeIn();
		//document.getElementById("des").innerHTML = nameImg;
		$(".imgbox a").removeClass("active");
		$(this).addClass("active");
	});
});
</script>
