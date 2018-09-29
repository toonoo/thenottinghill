<?php 
include "inc_header.php"; 
include "inc_paging.php";

$current_page = 1;
if(isset($_GET['page'])) {
    $current_page = $_GET['page'];
}
$rows_per_page = 8;
$start_row = paging_start_row($current_page, $rows_per_page); 
$gallery = $db->GetAssoc("SELECT SQL_CALC_FOUND_ROWS * FROM gallery WHERE enable='1' ORDER BY created_date DESC LIMIT $start_row, $rows_per_page ");
$found_rows = mysqli_query($con,"SELECT FOUND_ROWS();");
$total_rows = mysqli_result($con,$found_rows, 0, 0);
$total_pages = paging_total_pages($total_rows, $rows_per_page);

$picture = $db->GetRow("SELECT * FROM gallery WHERE enable='1' ORDER BY created_date DESC ");
?>

<section class="gallery clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo "แกลอรี่";}else{echo"Gallery";} ?></h1>
					<img src="images/border_about.png">
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 mb30">
					<div class="pic">
						<img src="images/gallery/<?php echo $picture['image']; ?>" alt="">
					</div>
					<div class="describe">
						<p><i class="fa fa-picture-o"></i>&nbsp;&nbsp;<span id="des"><?php echo $picture['description_'.$lang]; ?></span></p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 p0">
					<?php foreach ($gallery as $key => $value) { ?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb10">
						<div class="imgbox">
							<a href="javascript:void(0);" class="<?php if($key==$picture['id']){echo "active";} ?>">
								<img src="images/gallery/<?php echo $value['image']; ?>" alt="<?php echo $value['description_'.$lang]; ?>">
								<div class="bgthumb"></div>
							</a>
						</div>
					</div>
					<?php } ?>
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
					<div><b><?php if($lang=='th'){echo "* ภาพจำลอง บรรยากาศจำลอง";}else{echo"* Scenario";} ?></b></div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>

<script type="text/javascript">
$('document').ready(function(){
	$('.imgbox a').click(function(){
		var x = $(this).parent().find('img');
		var srcImg = x.attr('src');
		var nameImg = x.attr('alt');
		$('.pic img').fadeOut().attr('src', srcImg).fadeIn();
		document.getElementById("des").innerHTML = nameImg;
		$(".imgbox a").removeClass("active");
		$(this).addClass("active");
	});
});
</script>


