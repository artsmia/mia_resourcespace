<?php
include ("../../include/db.php");
include ("../../include/authenticate.php");
include ("../../include/general.php");
include ("../../include/header.php");
# Display the header only. This is for the admin area.
?>
<style>html {

}</style>

<?php $lfsize=400;if(isset($admin_header_height)){$topsize=$admin_header_height;}Else{$topsize=120;}?>
<iframe class="iframe AdminLeft" id="left" name="left" style="position:absolute;top:<?php echo $topsize?>px;width:<?php echo $lfsize?>px;" src="<?php echo $baseurl_short?>pages/admin/tree.php" frameborder="0" height="100%" style="display:inline;"  ></iframe>
<iframe class="iframe AdminRight" id="right" name="right" src="<?php echo $baseurl_short?>pages/admin/blank.php" frameborder="0" scrolling="auto" width="100%" height="100%" marginwidth="0" marginheight="0" style="position:absolute;top:<?php echo $topsize?>px;left:<?php echo $lfsize+25?>px;margin:0;margin-right:15px;display:inline;"></iframe>

<script type="text/javascript">

window.onresize=function(event){
	resizeadmin();
		}	

	resizeadmin();
jQuery('#CollectionDiv').onresize=function(event){
	resizeadmin();
		}	

	resizeadmin();	
					
function resizeadmin(){
	var maxheight=window.innerHeight-<?php echo $topsize?>-50;
    if (isNaN(maxheight)){maxheight=document.documentElement.clientHeight-<?php echo $topsize?>;}
    var maxwidth=window.innerWidth-<?php echo $lfsize + 45?>;
	if (isNaN(maxwidth)){maxwidth=document.documentElement.clientWidth-<?php echo $lfsize + 45?>;}
    
    
	jQuery('.iframe').each(function (index,elem) {
		if (maxheight> elem.getAttribute("height").replace(/px,*\)*/g,"")){elem.style.height=elem.getAttribute("height")+'px'; }
		else { elem.style.height=maxheight+'px';} } );
		
	jQuery('#right').each(function (index,elem) {
		if (maxwidth> elem.getAttribute("width").replace(/px,*\)*/g,"")){elem.style.height=elem.getAttribute("width")+'px'; }
		else { elem.style.width=maxwidth+'px';} } ); 
	}		
</script><?php
include("../../include/footer.php");

