<?php
include "../include/db.php";
include "../include/general.php";
include "../include/authenticate.php"; #if (!checkperm("s")) {exit ("Permission denied.");}
include_once "../include/collections_functions.php";
include_once "../include/render_functions.php";
include_once "../include/resource_functions.php";

$offset=getvalescaped("offset",0);
$find=getvalescaped("find",getvalescaped("saved_find",""));setcookie("saved_find",$find, 0, '', '', false, true);
$col_order_by=getvalescaped("col_order_by",getvalescaped("saved_col_order_by","created"));setcookie("saved_col_order_by",$col_order_by, 0, '', '', false, true);
$sort=getvalescaped("sort",getvalescaped("saved_col_sort","ASC"));setcookie("saved_col_sort",$sort, 0, '', '', false, true);
$revsort = ($sort=="ASC") ? "DESC" : "ASC";
# pager
$per_page=getvalescaped("per_page_list",$default_perpage_list,true);setcookie("per_page_list",$per_page, 0, '', '', false, true);

$collection_valid_order_bys=array("fullname","name","ref","count","public","created");
$modified_collection_valid_order_bys=hook("modifycollectionvalidorderbys");
if ($modified_collection_valid_order_bys){$collection_valid_order_bys=$modified_collection_valid_order_bys;}
if (!in_array($col_order_by,$collection_valid_order_bys)) {$col_order_by="created";} # Check the value is one of the valid values (SQL injection filter)

$override_group_restrict=getvalescaped("override_group_restrict","false");
if (array_key_exists("find",$_POST)) {$offset=0;} # reset page counter when posting
# pager

$add=getvalescaped("add","");
if ($add!="")
	{
	# Add someone else's collection to your My Collections
	add_collection($userref,$add);
	set_user_collection($userref,$add);
	refresh_collection_frame();
	
   	# Log this
	daily_stat("Add public collection",$userref);
	}

include "../include/header.php";
?>
  <div class="BasicsBox">
    <h1><?php echo $lang["findpubliccollection"]?></h1>
    <p class="tight"><?php echo text("introtext")?></p>
<div class="BasicsBox">
    <form method="post" action="<?php echo $baseurl_short?>pages/collection_public.php">
		<div class="Question">
			<label for="find"><?php echo $lang["searchpubliccollections"]?></label>
			<div class="tickset">
			 <div class="Inline"><input type=text name="find" id="find" value="<?php echo htmlspecialchars(unescape($find)) ?>" maxlength="100" class="shrtwidth" /></div>
			 <div class="Inline"><input name="Submit" type="submit" value="&nbsp;&nbsp;<?php echo $lang["searchbutton"]?>&nbsp;&nbsp;" /></div>
			<div class="Inline"><input name="Clear" type="button" onclick="document.getElementById('find').value='';submit();" value="&nbsp;&nbsp;<?php echo $lang["clearbutton"]?>&nbsp;&nbsp;" /></div>
			</div>
			<div class="clearerleft"> </div>
		</div>
	</form>
</div>
<?php
$collections=search_public_collections($find,$col_order_by,$sort,$public_collections_exclude_themes,false,true,$override_group_restrict=="true");

$results=count($collections);
$totalpages=ceil($results/$per_page);
$curpage=floor($offset/$per_page)+1;
$jumpcount=1;

# Create an a-z index
$atoz="<div class=\"InpageNavLeftBlock\">";
if ($find=="") {$atoz.="<span class='Selected'>";}

if ($public_collections_confine_group)
	{
	$atoz.="<a onClick='return CentralSpaceLoad(this,true);' href=\"".$baseurl_short."pages/collection_public.php?col_order_by=name&override_group_restrict=false&find=\">" . $lang["viewmygroupsonly"] . "</a> &nbsp; | &nbsp;";	
	$atoz.="<a onClick='return CentralSpaceLoad(this,true);' href=\"".$baseurl_short."pages/collection_public.php?col_order_by=name&override_group_restrict=true&find=\">" . $lang["viewall"] . "</a> &nbsp;&nbsp;&nbsp;";	
	}
else
	{
	$atoz.="<a onClick='return CentralSpaceLoad(this,true);' href=\"".$baseurl_short."pages/collection_public.php?col_order_by=name&find=\">" . $lang["viewall"] . "</a>";
	}


if ($find=="") {$atoz.="</span>";}
$atoz.="&nbsp;&nbsp;";
for ($n=ord("A");$n<=ord("Z");$n++)
	{
	if ($find==chr($n)) {$atoz.="<span class='Selected'>";}
	$atoz.="<a href=\"".$baseurl_short."pages/collection_public.php?col_order_by=name&find=" . chr($n) . "&override_group_restrict=" . urlencode($override_group_restrict) . "\" onClick=\"return CentralSpaceLoad(this);\">&nbsp;" . chr($n) . "&nbsp;</a> ";
	if ($find==chr($n)) {$atoz.="</span>";}
	$atoz.=" ";
	}
$atoz.="</div>";

$url=$baseurl_short."pages/collection_public.php?paging=true&col_order_by=".urlencode($col_order_by)."&sort=".urlencode($sort)."&find=".urlencode($find)."&override_group_restrict=" . urlencode($override_group_restrict);
?><div class="TopInpageNav">
	<div class="TopInpageNavLeft">
		<?php echo $atoz?> 
		<div class="InpageNavLeftBlock"><?php echo $lang["resultsdisplay"]?>:
		<?php 
		for($n=0;$n<count($list_display_array);$n++)
			{
		 	if ($per_page==$list_display_array[$n])
		 		{
		 		?><span class="Selected"><?php echo $list_display_array[$n]?></span><?php 
		 		} 
		 	else 
		 		{ 
		 		?><a href="<?php echo $url; ?>&per_page_list=<?php echo $list_display_array[$n]?>" onClick="return CentralSpaceLoad(this);"><?php echo $list_display_array[$n]?></a><?php 
		 		} ?> &nbsp;| <?php 
			} ?>
		<?php
		if($per_page==99999)
			{
			?><span class="Selected"><?php echo $lang["all"]?></span><?php 
			} 
		else 
			{ 
			?><a href="<?php echo $url; ?>&per_page_list=99999" onClick="return CentralSpaceLoad(this);"><?php echo $lang["all"]?></a><?php 
			} ?>
		</div> 
	</div>
	<?php pager(false); ?>
	<div class="clearerleft"></div>
</div>

<form method=post id="collectionform" action="<?php echo $baseurl_short?>pages/collection_public.php">
<input type=hidden name="add" id="collectionadd" value="">

<?php

// count how many collections are owned by the user versus just shared, and show at top
$mycollcount = 0;
$othcollcount = 0;
for($i=0;$i<count($collections);$i++){
	if ($collections[$i]['user'] == $userref){
		$mycollcount++;
	} else {
		$othcollcount++;
	}
}

$collcount = count($collections);
switch ($collcount)
    {
    case 0:
        echo $lang["total-collections-0"];
        break;
    case 1:
        echo $lang["total-collections-1"];
        break;
    default:
        echo str_replace("%number", $collcount, $lang["total-collections-2"]);
    }
echo " ";
switch ($mycollcount)
    {
    case 0:
        echo $lang["owned_by_you-0"];
        break;
    case 1:
        echo $lang["owned_by_you-1"];
        break;
    default:
        echo str_replace("%mynumber", $mycollcount, $lang["owned_by_you-2"]);
    }
echo "<br />";
?>

<div class="Listview">
<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
<tr class="ListviewTitleStyle">
<td class="name"><?php if ($col_order_by=="name") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=name&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["collectionname"]?></a><?php if ($col_order_by=="name") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td>

<?php if (!$collection_public_hide_owner) { ?><td class="fullname"><?php if ($col_order_by=="user") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=user&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["owner"]?></a><?php if ($col_order_by=="user") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td><?php } ?>

<td class="ref"><?php if ($col_order_by=="ref") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=ref&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["id"]?></a><?php if ($col_order_by=="ref") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td>

<td class="created"><?php if ($col_order_by=="created") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=created&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["created"]?></a><?php if ($col_order_by=="created") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td>

<td class="count"><?php if ($col_order_by=="count") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=count&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["itemstitle"]?></a><?php if ($col_order_by=="count") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td>

<?php if (!$hide_access_column_public){ ?><td class="access"><?php if ($col_order_by=="public") {?><span class="Selected"><?php } ?><a href="<?php echo $baseurl_short?>pages/collection_public.php?offset=0&col_order_by=public&sort=<?php echo urlencode($revsort)?>&find=<?php echo urlencode($find)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["access"]?></a><?php if ($col_order_by=="public") {?><div class="<?php echo urlencode($sort)?>">&nbsp;</div><?php } ?></td><?php } ?>
<?php hook("beforecollectiontoolscolumnheader");?>

<td class="tools"><div class="ListTools"><?php echo $lang["tools"]?></div></td>
</tr>
<?php

for ($n=$offset;(($n<count($collections)) && ($n<($offset+$per_page)));$n++)
	{
	?>
    <tr <?php hook("collectionlistrowstyle");?>>
		<td class="name">
			<div class="ListTitle">
			<a href="<?php echo $baseurl_short?>pages/search.php?search=<?php echo urlencode("!collection" . $collections[$n]["ref"])?>" onClick="return CentralSpaceLoad(this,true);"><?php echo highlightkeywords(i18n_get_collection_name($collections[$n]),$find)?></a>
			</div>
		</td>
		<?php 
		if (!$collection_public_hide_owner) 
			{ ?>
			<td class="fullname"><?php echo highlightkeywords(htmlspecialchars($collections[$n]["fullname"]),$find)?></td>
			<?php 
			} ?>
		<td class="ref"><?php echo highlightkeywords($collections[$n]["ref"],$find)?></td>
		<td class="created"><?php echo nicedate($collections[$n]["created"],true)?></td>
    	<td class="count"><?php echo $collections[$n]["count"]?></td>
		<?php 
		if (!$hide_access_column_public)
			{ ?>
			<td class="access"><?php echo ($collections[$n]["public"]==0)?$lang["private"]:$lang["public"]?></td>
			<?php 
			}
		hook("beforecollectiontoolscolumn");?>
		<td class="tools">
			<div class="ListTools">
			<?php
			hook('render_collections_public_list_tools', '', array($collections[$n]));
			render_actions($collections[$n], true, false);
			?>
			</div>
		</td>
      </tr>
	<?php
	}
?>
</table>
</div>

</form>
<div class="BottomInpageNav"><?php pager(false); ?></div>
</div>

<?php		
include "../include/footer.php";
?>
