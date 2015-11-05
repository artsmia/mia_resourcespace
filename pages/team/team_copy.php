<?php
/**
 * Copy resource page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php"; if (!checkperm("c")) {exit ("Permission denied.");}
include "../../include/resource_functions.php";

# Fetch user data

if (getval("from","")!="")
	{
	# Copy data
	$to=copy_resource(getvalescaped("from",""));
	if ($to===false) {$error=true;} else
		{
		redirect($baseurl_short."pages/edit.php?ref=" . $to);
		}
	}

include "../../include/header.php";
?>
<div class="BasicsBox">
<h1><?php echo $lang["copyresource"]?></h1>

<p><?php echo text("introtext")?></p>

<form method=post action="<?php echo $baseurl_short?>pages/team/team_copy.php" onSubmit="return CentralSpacePost(this,true);">

<div class="Question"><label><?php echo $lang["resourceid"]?></label><input name="from" type="text" class="shrtwidth" value="">
<?php if (isset($error)) { ?><div class="FormError">!! <?php echo $lang["resourceidnotfound"]?> !!</div><?php } ?><div class="clearerleft"> </div></div>

<div class="QuestionSubmit">
<label for="buttons"> </label>			
<input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["copyresource"]?>&nbsp;&nbsp;" />
</div>
</form>
</div>

<?php		
include "../../include/footer.php";
?>
