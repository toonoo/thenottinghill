<?php include "inc_header.php" ?>

<section class="appoint2 clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo "นัดหมายเข้าชมโครงการล่วงหน้า";}else{echo"Make an appointment";} ?> </h1>
					<img src="images/border_about.png">
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="mb30">
						<img src="images/image_about.jpg" style="width:100%;">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="mb30">
						<p><?php if($lang=='th'){echo"* กรุณากรอกรายละเอียดในแบบฟอร์มให้ครบถ้วน และระบุวันที่ท่านกำหนด เข้าเยี่ยมชมโครงการ (กรุณานัดหมายล่วงหน้าอย่างน้อย 1 วันทำการ) เพื่อให้เจ้าหน้าที่ได้รอต้อนรับ";}else{echo"* Please fill in the form. And specify the date you specify Visit Project (Please make reservations at least one day in advance), so officials have welcomed.";} ?></p>
						<form method="POST" action="action.php">
							<input type="hidden" name="action" value="appoint" />
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
<script type="text/javascript">
$(document).ready(function () { 
    $('#timepicker').timepicker({
    	minuteStep: 1,
    	secondStep: 1,
    	showMeridian: false,
    });
});
</script>