<?php 
include "inc_header.php";
//include "PHPMailer_v2.0.4/sendmail.php";

if($_POST['action']=='contact') {
    if($_POST['captcha'] AND $_POST['captcha']==$_SESSION['security_code']&&$_SESSION['security_code']) {
    	$_POST['email'] = strtolower($_POST['email']);
        function isValidEmail($email) { 
            if(preg_match("/^([_a-z0-9-]+)(\\.[_a-z0-9-]+)*@([a-z0-9-]+)(\\.[a-z0-9-]+)*(\\.[a-z]{2,4})$/" , $email)) { 
                list($username, $domain) = explode('@', $email);
                if(getmxrr($domain, $mxhosts)) {    //if MX Record exists return the email address
                    return $email;
                }
            }
            return false;  
            //return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);  
        }
        if(isValidEmail($_POST['email'])==true) {
            
            $message = "
                <table width='700' border='0' cellspacing='0' cellpadding='10' style='border:0px solid #000000;'>
                     <tr>
                        <td valign='top'>
                            <fieldset  style='margin:0px 0px; padding:0 10px 10px 10px;'>
                                <legend ><strong>Contact Us</strong></legend><br />
                                <span>Name : ".$_POST['name']."</span><br /><br />
                                <span>Telephone : ".$_POST['phone']."</span><br /><br />
                                <span>Email : ".$_POST['email']."</span><br /><br />
                                <span>Message : ".nl2br($_POST['message'])."</span><br /><br />
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <p></p>
            ";
                   
            $from = $_POST['email'];     
            $to = "jerawat@zaxisit.com";
            $subject = "Contact Us";
            $headers = "From: $from\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "X-Mailer: PHP's mail() Function\n"; 

            mail($to, $subject, $message, $headers);
            echo "<script>alert('ส่งข้อมูลเรียบร้อยแล้ว');</script>";
        }else{
            echo "<script>alert('รูปแบบอีเมล์ไม่ถูกต้อง');</script>";
        }
    }else{
    	echo "<script>alert('รหัสภาพไม่ถูกต้อง');</script>";
    }
}
?>

<section class="location clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo"ที่ตั้งโครงการ";}else{echo"Location";} ?></h1>
					<img src="images/border_about.png">
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 bdr">
					<div class="base">
						<h3><?php if($lang=='th'){echo"ทำเลที่ตั้งที่ดีที่สุด";}else{echo"Best location";} ?></h3>
						<?php if($lang=='th'){ ?>
						<p><b>โครงการน๊อตติ้งฮิลล์ห่างจากตัวเมืองเพียง 5 นาที* ใกล้มหาวิทยาลัยมหาสารคาม (แห่งใหม่) โรงเรียนสาธิตมหาวิทยาลัยมหาสารคาม โรงพยาบาลส่งเสริมสุขภาพตำบลบ้านท่าขอนยาง และ Big c</b></p>
						<?php }else{ ?>
						<p style="font-size:16px;"><b>The notting hill. Just five minutes from downtown. Near mahasarakham university (new) mahasarakham university demonstration school, Hospital Health Ban Tha Khon Yang and Big c.</b></p>
						<?php } ?>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="base">
						<h3><?php if($lang=='th'){echo"โครงการเดอะน๊อตติ้งฮิลล์";}else{echo"The Notting Hill Project";} ?></h3>
						<?php if($lang=='th'){ ?>
						<p><b><i class="fa fa-map-marker"></i>&nbsp;&nbsp;ที่อยู่โครงการ<br>&nbsp;&nbsp;&nbsp;&nbsp;ถนนวัดป่ากู่แก้ว - บ้านดอนหน่อง ต.ขามเรียง อ.กันทรวิชัย จ.มหาสารคาม</b></p>
						<p><b><i class="fa fa-phone"></i>&nbsp;&nbsp;โทร : 089-8469222, 093-5373222, 093-5427770</b></p>
						<?php }else{ ?>
						<p style="font-size:16px;"><b><i class="fa fa-map-marker"></i>&nbsp;&nbsp;address<br>&nbsp;&nbsp;&nbsp;&nbsp;Ban Donnong - Wat Phrakukae Road, Kham Riang sub-district, <br>&nbsp;&nbsp;&nbsp;&nbsp;KantharaWichai, Maha Sarakham</b></p>
						<p style="font-size:16px;"><b><i class="fa fa-phone"></i>&nbsp;&nbsp;Tel : 089-8469222, 093-5373222, 093-5427770</b></p>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="mb10 text-center">
						<img src="images/image_location.jpg">
					</div>
					<div class="col-lg-6">
						<a href="images/image_location.jpg" class="btn_load fancybox" data-fancybox-group="gallery" title=""><i class="fa fa-search-plus"></i></a>
						<a href="save.php?path=images/image_location.jpg" class="btn_load"><i class="fa fa-download"></i></a>
					</div>
					<div class="col-lg-6">
						<P class="coord"><?php if($lang=='th'){echo"พิกัด";}else{echo"coordinates";} ?> 16.266409,103.251821</P>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="mb30">
						<h2 class="conn"><?php if($lang=='th'){echo"ติดต่อเรา";}else{echo"Contact";} ?></h2>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
							<input type="hidden" name="action" value="contact" />
							<div class="form-group">
								<label for="name"><?php if($lang=='th'){echo"ชื่อ-นามสกุล";}else{echo"Name-Lastname";} ?></label>
								<input type="text" name="name" class="form-control" id="name" required>
							</div>
							<div class="form-group">
								<label for="phone"><?php if($lang=='th'){echo"เบอร์โทรศัพท์";}else{echo"Phone";} ?></label>
								<input type="text" name="phone" class="form-control" id="phone" maxlength="10" required pattern="[0-9]{10}" title="Fill only numbers">
							</div>
							<div class="form-group">
								<label for="email"><?php if($lang=='th'){echo"อีเมล์";}else{echo"Email";} ?></label>
								<input type="email" name="email" class="form-control" id="email" required>
							</div>
							<div class="form-group">
								<label for="message"><?php if($lang=='th'){echo"ข้อความ";}else{echo"Message";} ?></label>
								<textarea name="message" class="form-control" id="message" rows="4" required></textarea>
							</div>
							<div class="form-group">
								<label for="captcha"><?php if($lang=='th'){echo"กรอกรหัส";}else{echo"Captcha";} ?></label>
								<div class="clear"></div>
								<img src="captcha/CaptchaSecurityImages.php?sid=<?=md5(uniqid(time()))?>" class="image_cap"/>
								<input type="text" name="captcha" class="form-control form_cap" id="captcha" maxlength="4" required>
							</div>
							<div class="text-center">
								<button type="submit" class="btn_submit"><?php if($lang=='th'){echo"ส่งข้อความ";}else{echo"Send message";} ?></button>
							</div>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="map">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15320.625279310289!2d103.25366635915829!3d16.263756897640214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDE1JzU5LjEiTiAxMDPCsDE1JzA2LjYiRQ!5e0!3m2!1sth!2sth!4v1435220903161" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
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