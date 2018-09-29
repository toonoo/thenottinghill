<?php include "inc_header.php" ?>

<section class="progrss clearfix">
	<div class="container">
		<div class="row">

			<div class="boxbg clearfix">
				<div class="col-lg-12 text-center mb30">
					<h1 class="mb0">ความคืบหน้าโครงการ</h1>
					<img src="images/border_about.png">
				</div>

				<div class="col-lg-12">
					<div class="text-right mb10" style="color:#666;"><i class="fa fa-calendar"></i>&nbsp;&nbsp;พฤษภาคม 2558</div>
				</div>

				<div class="col-lg-12">
					
					<div id="container" class="boximg">
						<div id="gallery" class="content">
							<div id="controls" class="controls"></div>
							<div class="slideshow-container">
								<div id="loading" class="loader"></div>
								<div id="slideshow" class="slideshow"></div>
							</div>
							<!-- <div id="caption" class="caption-container"></div> -->
							<div class="gressname clearfix">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<p><b>บรรยากาศการตรวจสอบบ้านในโครงการ</b></p>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<p class="text-right"><i class="fa fa-calendar"></i>&nbsp;&nbsp;พฤษภาคม 2558</p>
								</div>
							</div>
						</div>

						<div id="thumbs" class="navigation">
							<ul class="thumbs noscript">
								<?php for ($i=1; $i<=30; $i++) { ?>
								<li>
									<a class="thumb" name="leaf" href="images/progress/progrss_01.jpg" title="Title #0">
										<img src="images/progress/progrss_01.jpg" alt="Title #0" />
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>

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

<link rel="stylesheet" href="galleriffic/css/galleriffic-2.css" type="text/css" />
<script type="text/javascript" src="galleriffic/js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="galleriffic/js/jquery.galleriffic.js"></script>
<script type="text/javascript" src="galleriffic/js/jquery.opacityrollover.js"></script>
<script type="text/javascript">
	document.write('<style>.noscript { display: none; }</style>');
</script>

<script type="text/javascript">
jQuery(document).ready(function($) {
	// We only want these styles applied when javascript is enabled
	$('div.navigation').css({'width' : '414px', 'float' : 'right', 'text-align' : 'center'});
	$('div.content').css({'display': 'block', 'float' : 'left'});

	// Initially set opacity on thumbs and add
	// additional styling for hover effect on thumbs
	var onMouseOutOpacity = 0.67;
	$('#thumbs ul.thumbs li').opacityrollover({
		mouseOutOpacity:   onMouseOutOpacity,
		mouseOverOpacity:  1.0,
		fadeSpeed:         'fast',
		exemptionSelector: '.selected'
	});
		
	// Initialize Advanced Galleriffic Gallery
	var gallery = $('#thumbs').galleriffic({
		delay:                     2500,
		numThumbs:                 20,
		preloadAhead:              10,
		enableTopPager:            true,
		enableBottomPager:         true,
		maxPagesToShow:            7,
		imageContainerSel:         '#slideshow',
		controlsContainerSel:      '#controls',
		captionContainerSel:       '#caption',
		loadingContainerSel:       '#loading',
		renderSSControls:          false,
		renderNavControls:         true,
		playLinkText:              'Play Slideshow',
		pauseLinkText:             'Pause Slideshow',
		prevLinkText:              "<i class='fa fa-chevron-left'></i>",
		nextLinkText:              "<i class='fa fa-chevron-right'></i>",
		nextPageLinkText:          'Next &rsaquo;',
		prevPageLinkText:          '&lsaquo; Prev',
		enableHistory:             false,
		autoStart:                 false,
		syncTransitions:           true,
		defaultTransitionDuration: 900,
		onSlideChange:             function(prevIndex, nextIndex) {
			// 'this' refers to the gallery, which is an extension of $('#thumbs')
			this.find('ul.thumbs').children()
				.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
				.eq(nextIndex).fadeTo('fast', 1.0);
		},
		onPageTransitionOut:       function(callback) {
			this.fadeTo('fast', 0.0, callback);
		},
		onPageTransitionIn:        function() {
			this.fadeTo('fast', 1.0);
		}
	});
});
</script>