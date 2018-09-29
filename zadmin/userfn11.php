<?php

// Global user functions
function Page_Loading() {   
	if($_POST["alt"]){    
		GLOBAL $conn;    
		$data = explode(" ",$_POST["alt"]);    
		if($data[3]=="checked")     
			$value = 0;    
		else    
			$value = 1;    
		$conn->Execute("UPDATE `$data[1]` SET `$data[0]` = '$value' WHERE id =$data[2]");    
		echo $data[3];    
		exit();    
	}    
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

function Page_Unloaded() {   
	if($_GET['add_now']){   
		header("location:".$_GET['add_now']);      
		exit();
	}                         
	if($_POST['a_addopt']!='A'){    
		echo '  <link href="lightbox/css/jquery.lightbox-0.5.css" rel="stylesheet" type="text/css">
						<script type="text/javascript" src="lightbox/js/jquery.lightbox-0.5.js"></script>  
						<script type="text/javascript">     
							$(document).ready(function(){    
								var ua = navigator.userAgent;    
								var isiPad = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);        
								if(isiPad)    
								{    var t_num = $("textarea").length;     
									for(var i=1;i<=t_num;i++)    
										$("textarea:eq("+(i-1)+")").parent().after($("textarea:eq("+(i-1)+")").clone().css({    "visibility":"visible","width":"680px","height":"200px"    })).remove();    
								} 
								var enable_num = $("input[type=checkbox]").length;     
								$(".ewListOptionBody input[type=checkbox]").attr("onclick","");
								for(var i=1;i<=enable_num;i++)
								{   if($("input[type=checkbox]:eq("+(i-1)+")").parent().parent().attr("alt"))    
										$("input[type=checkbox]:eq("+(i-1)+")").addClass("e_enable").attr({    "disabled":false    });    
								}   
								var img_num = $(".light_box").length;     
								for(var i=1;i<=img_num;i++)    
								{  if($(".light_box:eq("+(i-1)+") img:eq(0)").attr("src")) 
									{    var c_img = $(".light_box:eq("+(i-1)+") img:eq(0)").clone();
										$(".light_box:eq("+(i-1)+") img:eq(0)").after("<a href="+($(".light_box:eq("+(i-1)+") img:eq(0)").attr("src"))+" class=lightbox  ></a>").remove();    
										$(".light_box:eq("+(i-1)+") .lightbox").html(c_img);
									}
								}
								$(".e_enable").click(function(){    
									enable_ajax($(this),$(this).parent().parent().attr("alt")+" "+$(this).attr("checked"));    
								});  
								$(".lightbox").lightBox();
							});    

							function enable_ajax(this_,alt)    
							{  $.post("'.$_SERVER[PHP_SELF].'", { alt: alt },function(data){        
									if(data=="checked")    
										this_.removeAttr("checked");    
									else    
										this_.attr({    "checked":""  });    
								});    
							} 
						</script>';
	}
}
?>
