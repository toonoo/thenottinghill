<?php 
include "inc_header.php";
include "inc_paging.php";

$current_page = 1;
if(isset($_GET['page'])) {
    $current_page = $_GET['page'];
}
$rows_per_page = 16;
$start_row = paging_start_row($current_page, $rows_per_page); 
$progress = $db->GetAssoc("SELECT SQL_CALC_FOUND_ROWS * FROM progress WHERE enable='1' ORDER BY created_date DESC LIMIT $start_row, $rows_per_page ");
$found_rows = mysql_query("SELECT FOUND_ROWS();");
$total_rows = mysql_result($found_rows, 0, 0);
$total_pages = paging_total_pages($total_rows, $rows_per_page);

$picture = $db->GetRow("SELECT * FROM progress WHERE enable='1' ORDER BY created_date DESC ");
$picdate = explode("-",$picture['project_date']);
$picyear = $picdate[0]+543;

$date = date("d-m-Y");
$today = explode("-",$date);
if ($lang=='th') {
	$mount = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฎาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
	$year = $today[2]+543;
}else{
	$mount = array('01'=>'January','02'=>'Febuary','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	$year = $today[2];
}

foreach ($mount as $key => $value) {
	if ($today[1]==$key) { $mounttoday = $value." ".$year;}
	if ($picdate[1]==$key) { $pictoday = $value." ".$picyear;}
}
?>

<section class="progrss clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo "ความคืบหน้าโครงการ";}else{echo"Progress";} ?></h1>
					<img src="images/border_about.png">
				</div>

				<div class="col-lg-12">
					<div class="text-right mb10" style="color:#666;"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo $mounttoday; ?></div>
				</div>
				<div class="clearfix"></div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb10">
					<div class="imgbox">
						<img src="images/progress/<?php echo $picture['image']; ?>" alt="" style="width:100%;">
					</div>
					<div class="gressname clearfix">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<p class=""><b>บรรยากาศการตรวจสอบบ้านในโครงการ</b></p>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<p class="text-right"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo $pictoday; ?></p>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb10">
					<?php foreach ($progress as $key => $value) { ?>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding:0 5px 5px;">
						<div class="link mb10">
							<a href="javascript:void(0);" class="<?php if($key==$picture['id']){echo "active";} ?>">
								<img src="images/progress/<?php echo $value['image']; ?>" alt="">
								<div class="bglink"></div>
							</a>
						</div>
					</div>
					<?php } ?>
					<div class="clearfix"></div>
					<nav class="text-center">
						<ul class="pagination">
							<?php
	                            $page_range = 5;
	                            $qry_string = "";
	                            $page_str = paging_pagenum($current_page, $total_pages, $page_range, $qry_string);
	                            echo $page_str;
	                        ?>
						</ul>
					</nav>
				</div>
				<div class="clearfix"></div>

				<div class="col-lg-12">
					<div>
						<img src="images/progress_thumbnail.png">
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>

<script type="text/javascript">
$('document').ready(function(){
	$('.link a').click(function(){
		var x = $(this).parent().find('img');
		var srcImg = x.attr('src');
		//var nameImg = x.attr('alt');
		$('.imgbox img').fadeOut().attr('src', srcImg).fadeIn();
		//document.getElementById("des").innerHTML = nameImg;
		$(".link a").removeClass("active");
		$(this).addClass("active");
	});
});
</script>
