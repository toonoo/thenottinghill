<?php 
include "inc_header.php"; 
include "inc_paging.php";

$current_page = 1;
if(isset($_GET['page'])) {
    $current_page = $_GET['page'];
}
$rows_per_page = 5;
$start_row = paging_start_row($current_page, $rows_per_page); 
$news = $db->GetAssoc("SELECT SQL_CALC_FOUND_ROWS * FROM news WHERE enable='1' ORDER BY created_date DESC LIMIT $start_row, $rows_per_page ");
$found_rows = mysql_query("SELECT FOUND_ROWS();");
$total_rows = mysql_result($found_rows, 0, 0);
$total_pages = paging_total_pages($total_rows, $rows_per_page);
?>

<section class="promo clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix hideme">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0"><?php if($lang=='th'){echo "ข่าวและโปรโมชั่น";}else{echo"News and Promotion";} ?></h1>
					<img src="images/border_about.png">
				</div>
				<?php foreach ($news as $key => $value) { 
					$str_date = date("d/m/Y",strtotime($value['created_date']));
				?>
				<div class="clearfix">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="media-img">
							<a href="news_detail.php?newsid=<?php echo $key; ?>"><img class="media-object" src="images/uploads/<?php echo $value['image']; ?>" alt=""></a>
						</div>
					</div>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
						<div class="media-text">
							<h3><a href="news_detail.php?newsid=<?php echo $key; ?>"><?php echo $value['name_'.$lang]; ?></a></h3>
							<p><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo $str_date; ?></p>
							<p class="detail"><?php echo $value['detail_'.$lang]; ?></p>
						</div>
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
			</div>

		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>
