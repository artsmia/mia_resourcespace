<?php
/**
 * Manage research requests page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("r")) {exit ("Permission denied.");}
include "../../include/research_functions.php";
include_once "../../include/collections_functions.php";

$offset=getvalescaped("offset",0);
$find=getvalescaped("find","");
$order_by=getvalescaped("order_by","ref");
$sort=getval("sort","ASC");
$revsort = ($sort=="ASC") ? "DESC" : "ASC";

if (array_key_exists("find",$_POST)) {$offset=0;} # reset page counter when posting

if (getval("reload","")!="")
	{
	refresh_collection_frame();
	}
	
include "../../include/header.php";
?>


<div class="BasicsBox"> 
  <h2>&nbsp;</h2>
  <h1><?php echo $lang["manageresearchrequests"]?></h1>
  <p><?php echo text("introtext")?></p>
 
<?php 
$requests=get_research_requests($find,$order_by,$sort);

# pager
$per_page=10;
$results=count($requests);
$totalpages=ceil($results/$per_page);
$curpage=floor($offset/$per_page)+1;
$url="team_research.php?find=" . urlencode($find)."&order_by=".$order_by."&sort=".$sort."&find=".urlencode($find)."";
$jumpcount=1;

?><div class="TopInpageNav"><?php pager();	?></div>

<div class="Listview">
<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
<tr class="ListviewTitleStyle">
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=ref&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["researchid"]?></a></td>
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=name&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["nameofproject"]?></a></td>
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=created&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["date"]?></a></td>
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=status&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["status"]?></a></td>
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=assigned_to&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["assignedto"]?></a></td>
<td><a href="<?php echo $baseurl_short?>pages/team/team_research.php?offset=0&order_by=collection&sort=<?php echo $revsort?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["collectionid"]?></a></td>
<td><div class="ListTools"><?php echo $lang["tools"]?></div></td>
</tr>

<?php
$statusname=array($lang["requeststatus0"],$lang["requeststatus1"],$lang["requeststatus2"]);
for ($n=$offset;(($n<count($requests)) && ($n<($offset+$per_page)));$n++)
	{
	?>
	<tr>
	<td><?php echo $requests[$n]["ref"]?></td>
	<td><div class="ListTitle"><a href="<?php echo $baseurl_short?>pages/team/team_research_edit.php?ref=<?php echo $requests[$n]["ref"]?>" onClick="return CentralSpaceLoad(this,true);"><?php echo i18n_get_translated($requests[$n]["name"])?></a>&nbsp;</div></td>
	<td><?php echo nicedate($requests[$n]["created"],false,true)?></td>
	<td><?php echo $statusname[$requests[$n]["status"]]?></td>
	<td><?php echo (strlen($requests[$n]["assigned_username"])==0)?"-":$requests[$n]["assigned_username"]?></td>
	<td><?php echo (strlen($requests[$n]["collection"])==0)?"-":$collection_prefix . $requests[$n]["collection"]?></td>
	<td><div class="ListTools"><a href="<?php echo $baseurl_short?>pages/team/team_research_edit.php?ref=<?php echo $requests[$n]["ref"]?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["editresearch"]?></a>&nbsp;&nbsp;<a href="<?php echo $baseurl_short?>pages/collections.php?research=<?php echo $requests[$n]["ref"]?>" onClick="return CollectionDivLoad(this);">&gt;&nbsp;<?php echo $lang["editcollection"]?></a></div></td>
	</tr>
	<?php
	}
?>

</table>
</div>
<div class="BottomInpageNav"><div class="InpageNavLeftBlock"><a href="<?php echo $baseurl_short?>pages/research_request.php?assign=true" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["createresearchforuser"]?></a></div><?php pager(false); ?></div>
</div>


<div class="BasicsBox">
    <form method="post" action="<?php echo $baseurl_short?>pages/team/team_research.php">
		<div class="Question">
			<label for="findpublic"><?php echo $lang["searchresearchrequests"]?></label>
			<div class="tickset">
			 <div class="Inline"><input type=text name="find" id="find" value="<?php echo $find?>" maxlength="100" class="shrtwidth" /></div>
			 <div class="Inline"><input name="Submit" type="submit" value="&nbsp;&nbsp;<?php echo $lang["searchbutton"]?>&nbsp;&nbsp;" /></div>
			</div>
			<div class="clearerleft"> </div>
		</div>
	</form>
</div>


<?php
include "../../include/footer.php";
?>
