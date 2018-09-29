<?php 
include "inc_header.php"; 

$newsdetail = $db->GetRow("SELECT * FROM news WHERE enable='1' AND id='".$_GET['newsid']."' ");
$date = date("d/m/Y",strtotime($newsdetail['created_date']));
?>

<section class="promo clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix">
				<div class="col-lg-12">
					<div class="top_nav">
						<a href="#"><?php if($lang=='th'){echo "หน้าแรก";}else{echo"Home";} ?></a> > <a href="#">Society</a> > <span><?php echo $newsdetail['name_'.$lang]; ?></span>
					</div>
					<div class="cont_head">
						<h1><?php echo $newsdetail['name_'.$lang]; ?></h1>
						<p><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo $date; ?></p>
					</div>
					<!-- div class="clearfix cont_img F2F2F2 mb30">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" style="padding:0;">
							<img src="images/uploads/<?php echo $newsdetail['image']; ?>" style="width:100%;">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding:0;">
							<?php for ($i=1; $i<=6; $i++) { ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<a href="#"><img src="images/uploads/news_01.png" style="width:100%;"></a>
							</div>
							<?php } ?>
						</div>
					</div> -->
					<div class="col-lg-12 mb30 text-center">
						<img src="images/uploads/<?php echo $newsdetail['image']; ?>" style="width:80%;">
					</div>
					<div class="cont_taxt">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<h3><?php if($lang=='th'){echo "รายละเอียด";}else{echo"Detail";} ?></h3>
							<div>
								<?php echo $newsdetail['detail_'.$lang]; ?>
							</div>
							<hr>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mb30">
							<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.3&appId=634102490036925";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
							<div class="fb-page" data-href="https://www.facebook.com/TheNotthingHill" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/TheNotthingHill"><a href="https://www.facebook.com/TheNotthingHill">หมู่บ้านเดอะน๊อตติ้งฮิลล์</a></blockquote></div></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h3><?php if($lang=='th'){echo "แชร์";}else{echo"Share";} ?></h3>
							<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-527ca6d44e7b5d1e" async="async"></script>
							<div class="addthis_sharing_toolbox"></div>
						</div>
					</div>
				</div>
			</div>
				
		</div>
	</div>
</section>

<?php include "inc_footer.php" ?>

<style type="text/css">
.fb_iframe_widget, .fb_iframe_widget span, .fb_iframe_widget span iframe[style] {width: 100% !important;}
</style>