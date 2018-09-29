<?php include "inc_header.php" ?>

<section class="about clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 title">
					<h1><?php if($lang=='th'){echo "ประวัติบริษัท";}else{echo"Company Background​";} ?></h1>
					<img src="images/border_about.png">
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="text-center mb30">
						<img src="images/logo_nottinghill_2.png" class="mb30">
						<h1><?php if($lang=='th'){echo "โครงการ เดอะน๊อตติ้งฮิลล์";}else{echo"The Notting Hill Project";} ?></h1>
						<p>ตุ๊กอวอร์ด ริคเตอร์วาซาบิดีพาร์ทเมนต์ไฮกุสปอต ตรวจทานว้อดก้าธัมโมออร์เดอร์ สต็อกเยอร์บีร่ากราวนด์ละตินเคลม แชมปิยองซัพพลายแซนด์วิช ติ่มซำแทกติคเลิฟ มายาคติไบเบิล สปอต แพนดาโซน โอเลี้ยงวาฟเฟิลเมเปิลดาวน์ สุริยยาตรเวสต์ โค้กสามแยกวอลซ์ ดีไซน์เนอร์คอรัปชั่นสามแยกกาญจน์พุทธภูมิ ฮาราคีรีไฮเอนด์แอพพริคอท เวิร์กเจ๊าะแจ๊ะบิลแฟ้บกาญจน์ ป๊อกหงวนโค้ชดราม่า</p>
						<img src="images/carriage.png">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="mb30">
						<div class="mb30 text-center"><img src="images/image_about.jpg"></div>
						<?php if($lang=='th'){ ?>
						<p><b><i class="fa fa-map-marker"></i>&nbsp;&nbsp;ที่อยู่โครงการ<br>&nbsp;&nbsp;&nbsp;&nbsp;ถนนวัดป่ากู่แก้ว-บ้านดอนหน่อง ต.ขามเรียง อ.กันทรวิชัย จ.มหาสารคาม"</b></p>
						<p><b><i class="fa fa-phone"></i>&nbsp;&nbsp;โทร : 089-8469222, 093-5373222, 093-5427770</b></p>
						<?php }else{ ?>
						<p style="font-size:16px;"><b><i class="fa fa-map-marker"></i>&nbsp;&nbsp;Address<br>&nbsp;&nbsp;&nbsp;&nbsp;Ban Donnong - Wat Phrakukae Road, Kham Riang sub-district, <br>&nbsp;&nbsp;&nbsp;&nbsp;KantharaWichai, Maha Sarakham</b></p>
						<p style="font-size:16px;"><b><i class="fa fa-phone"></i>&nbsp;&nbsp;Tel. 089-8469222, 093-5373222, 093-5427770</b></p>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="dee clearfix">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box bd_r">
						<div class="title">
							<h3><?php if($lang=='th'){echo "5 ดี ชีวิตคุณภาพ";}else{echo"5 Quality of Life";} ?></h3>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box bd_r">
						<div class="boximg">
							<div class="icond1"></div>
						</div>
						<div class="boxtext">
							<h3><?php if($lang=='th'){echo "วัสดุดี";}else{echo"Good materials";} ?></h3>
							<p><?php if($lang=='th'){echo "วัสดุที่ใช้ในโครงการล้วนคัดสรรมาเป็น อย่างดี เพื่อบ้านที่มีคุณภาพ";}else{echo"The materials used in the project were selected as well to quality home.";} ?></p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
						<div class="boximg">
							<div class="icond2"></div>
						</div>
						<div class="boxtext">
							<h3><?php if($lang=='th'){echo "สังคมดี";}else{echo"Good society";} ?></h3>
							<p><?php if($lang=='th'){echo "การอยู่อาศัยที่เพียบพร้อมด้วยระบบ ความปลอดภัยตลอด 24 ชั่วโมง";}else{echo"The housing is equipped with the system. 24-hour security.";} ?></p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box bd_r">
						<div class="boximg">
							<div class="icond3"></div>
						</div>
						<div class="boxtext">
							<h3><?php if($lang=='th'){echo "สิ่งแวดล้อมดี";}else{echo"Good environment";} ?></h3>
							<p><?php if($lang=='th'){echo "ให้ชีวิตได้ใกล้ชิดธรรมชาติ ด้วยสวนสวย  ต้นไม้ใหญ่ ใจกลางโครงการ";}else{echo"To live close to nature with beautiful gardens, mature trees, the heart of the project.";} ?></p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box bd_r">
						<div class="boximg">
							<div class="icond4"></div>
						</div>
						<div class="boxtext">
							<h3><?php if($lang=='th'){echo "สิ่งอำนวยความสะดวกดี";}else{echo"Good facilities";} ?></h3>
							<p><?php if($lang=='th'){echo "พร้อมพรรค์ด้วยสิ่งอำนวยความสะดวก อย่าง ฟิตเนส สระว่ายน้ำ ร้านเครื่องดื่ม";}else{echo"Callings along with amenities such as a fitness, swimming pool, cafe.";} ?></p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
						<div class="boximg">
							<div class="icond5"></div>
						</div>
						<div class="boxtext">
							<h3><?php if($lang=='th'){echo "รสนิยมดี";}else{echo"Good taste";} ?></h3>
							<p><?php if($lang=='th'){echo "ด้วยการออกแบบที่ลงตัว ในรูปแบบบ้านสไตล์อังกฤษ ที่หลายคนหลงใหล";}else{echo"With a modular design English-style home in style Many passionate.";} ?></p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>