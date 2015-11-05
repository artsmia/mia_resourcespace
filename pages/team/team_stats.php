<?php
/**
 * Statistics Display Page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("t")) {exit ("Permission denied.");}
include "../../include/reporting_functions.php";

$activity_type=getvalescaped("activity_type","User session");
$year=getvalescaped("year",date("Y"));
$month=getvalescaped("month","");
$groupselect=getvalescaped("groupselect","viewall");
if ($groupselect=="select")
	{
	$groups=@$_POST["groups"];
	}
else
	{
	$groups=array();
	}

# For child group only access, set that selected groups only are displayed by default
# and select all available groups (if none already selected).
if (checkperm("U"))
	{
	$groupselect="select";
	if (count($groups)==0)
		{
		# No groups selected, add to groups array.
		$groups=array();
		$grouplist=get_usergroups(true);
		for ($n=0;$n<count($grouplist);$n++)
			{
			$groups[]=$grouplist[$n]["ref"];
			}
		}
	}

include "../../include/header.php";

if (getval("print","")!="") { # Launch printable page in an iframe
?>
<iframe width=1 height=1 style="visibility:hidden" src="<?php echo $baseurl_short?>pages/team/team_stats_print.php?year=<?php echo $year?>&groupselect=<?php echo $groupselect?>&groups=<?php echo join("_",$groups)?>"></iframe>
<?php } ?>

<div class="BasicsBox"> 
  <h1><?php echo $lang["viewstatistics"]?></h1>
  <p><?php echo text("introtext")?></p>
  
  <form method="post" action="<?php echo $baseurl_short?>pages/team/team_stats.php" onSubmit="return CentralSpacePost(this);">
	<div class="Question">
<label for="activity_type"><?php echo $lang["activity"]?></label><select id="activity_type" name="activity_type" class="shrtwidth">
<?php $types=get_stats_activity_types(); 
for ($n=0;$n<count($types);$n++)
	{ 
	
		
	?><option <?php if ($activity_type==$types[$n]) { ?>selected<?php } ?> value="<?php echo $types[$n]?>"><?php echo get_translated_activity_type($types[$n]) ?></option><?php
	}
?>
</select>
<div class="clearerleft"> </div>
</div>

	<div class="Question">
<label for="year"><?php echo $lang["year"]?></label><select id="year" name="year" class="shrtwidth">
<?php $years=get_stats_years(); 
for ($n=0;$n<count($years);$n++)
	{
	?><option <?php if ($year==$years[$n]) { ?>selected<?php } ?>><?php echo $years[$n]?></option><?php
	}
?>
</select>
<div class="clearerleft"> </div>
</div>


<div class="Question">
<label for="month"><?php echo $lang["month"]?></label><select id="month" name="month" class="shrtwidth">
<option value=""><?php echo $lang["allmonths"] ?></option>
<?php 
for ($n=1;$n<=12;$n++)
	{
	?><option value="<?php echo $n ?>" <?php if ($month==$n) { ?>selected<?php } ?>><?php echo $lang["months"][$n-1]?></option><?php
	}
?>
</select>
<div class="clearerleft"> </div>
</div>


<?php include "../../include/usergroup_select.php" ?>


<div class="Question">
<label for="print"><?php echo $lang["printallforyear"]?></label><input type=checkbox name="print" id="print" value="yes">
<div class="clearerleft"> </div>
</div>

<div class="QuestionSubmit">
<label for="buttons"> </label>			
<input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["viewstatistics"]?>&nbsp;&nbsp;" />
</div>
</form>

	<?php if ($activity_type!="") { ?>	
	<br/>
	<div class="BasicsBox">
	<img style="border:1px solid black;" src="<?php echo $baseurl_short?>pages/graph.php?activity_type=<?php echo urlencode($activity_type)?>&year=<?php echo $year?>&month=<?php echo $month ?>&groupselect=<?php echo $groupselect?>&groups=<?php echo join("_",$groups)?>" width=700 height=350>
	</div>
	<?php } ?>
	
  </div>

<?php
include "../../include/footer.php";
?>
