<?php
#
#
# Provides the User Rating function on the resource view page (if enabled)
# ------------------------------------------------------------------------
#

$rating=$resource["user_rating"];
$modified_user_rating=hook("modifyuserrating");
if ($modified_user_rating){$result[$n]['user_rating']=$modified_user_rating;}

$rating_count=$resource["user_rating_count"];
global $user_rating_remove;
if ($rating=="") {$rating=0;}
if ($rating_count=="") {$rating_count=0;}
// for 'remove rating' tool, determine if user has a rating for this resource
if ($user_rating_remove && $user_rating_only_once) {
	$ratings=array();
	$ratings=sql_query("select user,rating from user_rating where ref='$ref'");
	$current="";
	for ($n=0;$n<count($ratings);$n++) {
		if ($ratings[$n]['user']==$userref) {
			$current=$ratings[$n]['rating'];
		}
	}
	$removeratingvis = ($current!="") ? "inline" : "none";
}
if(!hook("replaceuser_ratingbr")){
?>
<br />
<?php } # end hook("replaceuser_ratingbr") ?>
<script type="text/javascript">

var UserRatingDone=false;

function UserRatingDisplay(rating,hiclass)
	{
	if (UserRatingDone) {return false;}
	for (var n=1;n<=5;n++)
		{
		jQuery('#RatingStar'+n).removeClass('StarEmpty');
		jQuery('#RatingStar'+n).removeClass('StarCurrent');
		jQuery('#RatingStar'+n).removeClass('StarSelect');
		if (n<=rating)
			{
			jQuery('#RatingStar'+n).addClass(hiclass);
			}
		else
			{
			jQuery('#RatingStar'+n).addClass('StarEmpty');
			}
		}
	}

function UserRatingSet(userref,ref,rating)
	{
	jQuery('#RatingStarLink'+rating).blur(); // removes the white focus box around the star.
	if (UserRatingDone) {return false;}
	jQuery.ajax(baseurl_short+"pages/ajax/user_rating_save.php?userref="+userref+"&ref="+ref+"&rating=" + rating,{method: 'post'});
			
	document.getElementById('RatingCount').style.visibility='hidden';
	if (rating==0)
		{
		UserRatingDone=false;
		UserRatingDisplay(0,'StarSelect');
		UserRatingDone=true;
		document.getElementById('UserRatingMessage').innerHTML="<?php echo $lang["ratingremoved"]?>";
		document.getElementById('RatingStarLink0').style.display = 'none';
		}
	else
		{
		UserRatingDone=true;
		document.getElementById('UserRatingMessage').innerHTML="<?php echo $lang["ratingthankyou"]?>";		
		}
	}


</script>
<?php hook("beforeuserratingtable");?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr class="DownloadDBlend">
<?php hook("beforeuserratingheader"); ?>
<td id="UserRatingMessage"><?php echo $lang["ratethisresource"]?></td>
<td width="33%" class="RatingStars" onMouseOut="UserRatingDisplay(<?php echo htmlspecialchars($rating) ?>,'StarCurrent');">
<div class="RatingStarsContainer">
<?php if ($user_rating_remove && $user_rating_only_once) {?><a href="#" onClick="UserRatingSet(<?php echo $userref?>,<?php echo htmlspecialchars($ref) ?>,0);return false;" id="RatingStarLink0" title="<?php echo $lang["ratingremovehover"]?>" style="display:<?php echo $removeratingvis;?>">x&nbsp;&nbsp;&nbsp;</a><?php }?>
<?php for ($n=1;$n<=5;$n++)
	{
	?><a href="#" onMouseOver="UserRatingDisplay(<?php echo $n?>,'StarSelect');" onClick="UserRatingSet(<?php echo $userref?>,<?php echo htmlspecialchars($ref) ?>,<?php echo $n?>);return false;" id="RatingStarLink<?php echo $n?>"><span id="RatingStar<?php echo $n?>" class="Star<?php echo ($n<=$rating?"Current":"Empty")?>"><img src="<?php echo $baseurl?>/gfx/interface/sp.gif" width="15" height="15"></span></a><?php
	#&#9733;
	}
?>
</div>
<div class="RatingCount" id="RatingCount"><?php if ($user_rating_stats && $user_rating_only_once){?><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl?>/pages/user_ratings.php?ref=<?php echo $ref?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by) ?>&amp;sort=<?php echo urlencode($sort) ?>&amp;archive=<?php echo urlencode($archive) ?>"><?php } ?><?php echo urlencode($rating_count) ?> <?php echo ($rating_count==1?$lang["rating_lowercase"]:$lang["ratings"])?><?php if ($user_rating_stats && $user_rating_only_once){?></a><?php }?></div>
</td>
</tr>
<?php if(!hook("replaceuserratingtableclose")){ ?>
</table>
<?php } # end hook("replaceuserratingtableclose") ?>
