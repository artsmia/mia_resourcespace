<?php
/**
 * Manage resource request page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("R")) {exit ("Permission denied.");}
include "../../include/request_functions.php";
include_once "../../include/collections_functions.php";

$offset=getvalescaped("offset",0);

include "../../include/header.php";
?>


<div class="BasicsBox"> 
  <h1><?php echo $lang["managerequestsorders"]?></h1>
  <p><?php echo text("introtext")?></p>
 
<?php 
$requests=get_requests();

# pager
$per_page=20;
$results=count($requests);
$totalpages=ceil($results/$per_page);
$curpage=floor($offset/$per_page)+1;
$url="team_request.php?";
$jumpcount=1;

?><div class="TopInpageNav"><?php pager();	?></div>

<div class="Listview">
<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
<tr class="ListviewTitleStyle">
<?php if(!hook("requestlistheader")): ?>
<td><?php echo $lang["requestorderid"]?></td>
<td><?php echo $lang["username"]?></td>
<td><?php echo $lang["fullname"]?></td>
<td><?php echo $lang["date"]?></td>
<td><?php echo $lang["itemstitle"]?></td>
<td><?php echo $lang["type"]?></td>
<td><?php echo $lang["assignedto"]?></td>
<td><?php echo $lang["status"]?></td>
<td><div class="ListTools"><?php echo $lang["tools"]?></div></td>
<?php endif; ?>
</tr>

<?php
$statusname=array("","","","");
$requesttypes=array("","","","");

for ($n=$offset;(($n<count($requests)) && ($n<($offset+$per_page)));$n++)
	{
	?>
	<tr>
<?php if(!hook("requestlistitems")): ?>
	<td><?php echo $requests[$n]["ref"]?></td>
	<td><?php echo $requests[$n]["username"] ?></td>
	<td><?php echo $requests[$n]["fullname"] ?></td>
	<td><?php echo nicedate($requests[$n]["created"],true)?></td>
	<td><?php echo $requests[$n]["c"] ?></td>
	<td><?php echo $lang["resourcerequesttype" . $requests[$n]["request_mode"]] ?></td>
	<td><?php echo $requests[$n]["assigned_to_username"] ?></td>
	<td><?php echo $lang["resourcerequeststatus" . $requests[$n]["status"]] ?></td>
	<td><div class="ListTools"><a href="<?php echo $baseurl_short?>pages/team/team_request_edit.php?ref=<?php echo $requests[$n]["ref"]?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["action-edit"]?></a></a></div></td>
<?php endif; ?>
	</tr>
	<?php
	}
?>

</table>
</div><!--end of Listview -->
<div class="BottomInpageNav"><?php pager(false); ?></div>
</div><!-- end of BasicsBox -->




<?php
include "../../include/footer.php";
?>
