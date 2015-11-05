<?php
include "../include/db.php";
include "../include/general.php";

include_once "../include/collections_functions.php";
# External access support (authenticate only if no key provided, or if invalid access key provided)
$k=getvalescaped("k","");if (($k=="") || (!check_access_key_collection(getvalescaped("collection","",true),$k))) {include "../include/authenticate.php";}

include "../include/resource_functions.php";
include "../include/search_functions.php";

$collection=getvalescaped("collection","",true);
$errors="";
$done=false;

# Fetch collection data
$cinfo=get_collection($collection);if ($cinfo===false) {exit("Collection not found.");}

# Check access
if (!collection_readable($collection)) {exit($lang["no_access_to_collection"]);}
if (!$cinfo["request_feedback"]) {exit("Access denied.");}


# Check that comments have been added.
$comments=get_collection_comments($collection);
if (count($comments)==0 && $feedback_resource_select==false) {$errors=$lang["feedbacknocomments"];}

$comment="";
if (getval("save","")!="")
	{
	# Save comment
	$comment=trim(getvalescaped("comment",""));
	$saveerrors=send_collection_feedback($collection,$comment);	
	if(is_array($saveerrors))
		{
		foreach($saveerrors as $saveerror)
			{
			if($errors=="")
				{$errors = $saveerror;}
			else
				{$errors.="<br><br> " . $saveerror;}
			}
		}
	else
		{
		# Stay on this page for external access users (no access to search)
		refresh_collection_frame();
		$done=true;
		}
	}

$headerinsert.="<script src=\"../lib/lightbox/js/jquery.lightbox-0.5.min.js\" type=\"text/javascript\"></script>";
$headerinsert.="<link type=\"text/css\" href=\"../lib/lightbox/css/jquery.lightbox-0.5.css?css_reload_key=" . $css_reload_key . "\" rel=\"stylesheet\">";



include "../include/header.php";

if ($errors!="")
	{
	echo "<script>alert('" .  str_replace(array("<br>","<br/>","<br />"),"\\n\\n",$errors) . "');</script>";
	}
?>



<div class="BasicsBox">
<h1><?php echo $lang["sendfeedback"]?></h1>
<?php if ($done) { ?><p><?php echo $lang["feedbacksent"]?></p><?php } else { ?>

<form method="post" action="<?php echo $baseurl_short?>pages/collection_feedback.php">
<input type="hidden" name="k" value="<?php echo htmlspecialchars($k) ?>">
<input type="hidden" name="collection" value="<?php echo htmlspecialchars($collection) ?>">

<p><a class="downloadcollection" href="<?php echo $baseurl_short?>pages/collection_download.php?collection=<?php echo urlencode($collection)?>&k=<?php echo urlencode($k)?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["download_collection"]?></a></p>
<?php if ($feedback_resource_select)
	{
	?><h2><?php echo $lang["selectedresources"]?>:</h2><?php
	# Show thumbnails and allow the user to select resources.
	$result=do_search("!collection" . $collection,"","resourceid",0,-1,"desc");
	for ($n=0;$n<count($result);$n++)
		{
		$ref=$result[$n]["ref"];
		$access=get_resource_access($ref);
		$use_watermark=check_use_watermark($ref);
		
		$title=$ref . " : " . htmlspecialchars(tidy_trim (i18n_get_translated ($result[$n]["field".$view_title_field]),60));
		if (isset($collection_feedback_display_field)) {
			$displaytitle=htmlspecialchars(get_data_by_field($ref,$collection_feedback_display_field));
			}
		else {$displaytitle=$title;}
		?>
		<!--Resource Panel-->
		<div class="ResourcePanelShell" id="ResourceShell<?php echo urlencode($ref)?>">
		<div class="ResourcePanel">
		
		<table border="0" class="ResourceAlign<?php if (in_array($result[$n]["resource_type"],$videotypes)) { ?> IconVideo<?php } ?>"><tr><td>
		
		<?php if ($result[$n]["has_image"]==1) {
			$path=get_resource_path($ref,true,"scr",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"]);
			if (file_exists($path))
				{
				# Use 'scr' path
				$path=get_resource_path ($ref, false,"scr",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"]);
				}
			else if (!file_exists($path))
				{
				# Attempt original file if jpeg
				$path=get_resource_path ($ref, false,"",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"]);
				}
		
		?><a class="lightbox" href="<?php echo $path?>" title="<?php echo htmlspecialchars($displaytitle) ?>"><img width="<?php echo $result[$n]["thumb_width"]?>" height="<?php echo $result[$n]["thumb_height"]?>" src="<?php echo get_resource_path($ref,false,"thm",false,$result[$n]["preview_extension"],-1,1,(checkperm("w") || ($k!="" && isset($watermark))) && $access==1,$result[$n]["file_modified"])?>" class="ImageBorder"></a>
		<?php } else { ?>		<img border=0 src="../gfx/<?php echo get_nopreview_icon($result[$n]["resource_type"],$result[$n]["file_extension"],false) ?>"/><?php } ?>

		
		</td>
		</tr></table>
		<span class="ResourceSelect"><input type="checkbox" name="select_<?php echo urlencode($ref) ?>" value="yes"></span>

		<div class="ResourcePanelInfo"><?php echo htmlspecialchars($displaytitle) ?>&nbsp;</div>
			
		<div class="clearer"> </div>
		</div>
		<div class="PanelShadow"></div>
		</div>
		
		<?php
		}
	?><div class="clearer"> </div> <?php
	}
?>

<div class="Question">
<?php if ($errors!="") { ?><div class="FormError"><?php echo $errors?></div><?php } ?>
<label for="comment"><?php echo $lang["message"]?></label><textarea class="stdwidth" style="width:450px;" rows=20 cols=80 name="comment" id="comment"><?php echo htmlspecialchars($comment) ?></textarea>
<div class="clearerleft"> </div>
</div>

<?php if (!isset($userfullname))
	{
	# For external users, ask for their name/e-mail in case this has been passed to several users.
	?>
	<div class="Question">
	<label for="name"><?php echo $lang["yourname"]?></label><input type="text" class="stdwidth" name="name" id="name" value="<?php echo htmlspecialchars(getvalescaped("name","")) ?>">
	<div class="clearerleft"> </div>
	</div>
	<div class="Question">
	<label for="email"><?php echo $lang["youremailaddress"]; if ($feedback_email_required){echo " *";}?></label><input type="text" class="stdwidth" name="email" id="email" value="<?php echo htmlspecialchars(getvalescaped("email","")) ?>">
	<div class="clearerleft"> </div>
	</div>
	<?php
	}
?>

<div class="QuestionSubmit">
<label for="buttons"> </label>			
<input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["send"]?>&nbsp;&nbsp;" />
</div>
</form>
<?php } ?>
</div>
<?php if ($feedback_resource_select){?>
<script type="text/javascript">
    jQuery('.lightbox').lightBox(
        {
        imageLoading: '<?php echo $baseurl_short?>gfx/lightbox/loading.gif',
        imageBtnClose: '<?php echo $baseurl_short?>gfx/lightbox/close.gif',
        imageBtnPrev: '<?php echo $baseurl_short?>gfx/lightbox/previous.png',
        imageBtnNext: '<?php echo $baseurl_short?>gfx/lightbox/next.png',
        keyToClose: 'c', // The key to close the lightBox.
        keyToPrev: 'p', // The key to show the previous image.
        keyToNext: 'n', // The key to show the next image.
        txtImage: '<?php echo $lang["lightbox-image"]?>',
        txtOf: '<?php echo $lang["lightbox-of"]?>'
        }
        ); 
</script>
<?php } ?>

<?php
include "../include/footer.php";
?>
