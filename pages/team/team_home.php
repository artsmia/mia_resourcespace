<?php
/**
 * Team center home page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("t")) {exit ("Permission denied.");}
include "../../include/resource_functions.php";

if ($send_statistics) {send_statistics();}

$overquota=overquota();


# Work out free space / usage for display
if (!file_exists($storagedir)) {mkdir($storagedir,0777);}

if (isset($disksize)) # Use disk quota rather than real disk size
	{
	$avail=$disksize*(1024*1024*1024);
	$used=get_total_disk_usage();
	$free=$avail-$used;
	}
else
	{		
	$avail=disk_total_space($storagedir);
	$free=disk_free_space($storagedir);
	$used=$avail-$free;
	}
if ($free<0) {$free=0;}
		
include "../../include/header.php";
?>


<div class="BasicsBox"> 
  <h1><?php echo $lang["teamcentre"]?></h1>
  <p><?php echo text("introtext")?></p>
  
	<div class="VerticalNav">
	<ul>
	
	<?php if (checkperm("c")) { 
		if ($overquota)
			{
			?><li><?php echo $lang["manageresources"]?> : <strong><?php echo $lang["manageresources-overquota"]?></strong></li><?php
			}
		else
			{
			?><li><a href="<?php echo $baseurl_short?>pages/team/team_resource.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["manageresources"]?></a></li><?php
			}
 		}
 	?>
				
	<?php if (checkperm("R")) { ?><li><a href="<?php echo $baseurl_short ?>pages/team/team_request.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["managerequestsorders"]?></a>
        &nbsp;&nbsp;<?php
        $condition = "";
        if (checkperm("Rb")) {$condition = "and assigned_to='" . $userref . "'";} # Only show pending for this user?
        $pending = sql_value("select count(*) value from request where status = 0 $condition",0);
        switch ($pending)
            {
            case 0:
                echo $lang["resources-with-requeststatus0-0"];
                break;
            case 1:
                echo $lang["resources-with-requeststatus0-1"];
                break;
            default:
                echo str_replace("%number",$pending,$lang["resources-with-requeststatus0-2"]);
                break;
            } ?>
    </li><?php } ?>

    <?php if (checkperm("r") && $research_request) { ?><li><a href="<?php echo $baseurl_short?>pages/team/team_research.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["manageresearchrequests"]?></a>
        &nbsp;&nbsp;<?php
        $unassigned = sql_value("select count(*) value from research_request where status = 0",0);
        switch ($unassigned)
            {
            case 0:
                echo $lang["researches-with-requeststatus0-0"];
                break;
            case 1:
                echo $lang["researches-with-requeststatus0-1"];
                break;
            default:
                echo str_replace("%number", $unassigned,$lang["researches-with-requeststatus0-2"]);
                break;
            } ?> 
        </li><?php } ?>

    <?php if (checkperm("u")) { ?><li><a href="<?php echo $baseurl_short?>pages/team/team_user.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["manageusers"]?></a></li><?php } ?>

    <?php if((checkperm("h") && !checkperm("hdta")) || (checkperm("dta") && !checkperm("h"))){ ?><li><a href="<?php echo $baseurl_short?>pages/team/team_dash_tile.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["managedefaultdash"]?></a></li><?php } ?>
    
    <li><a href="<?php echo $baseurl_short?>pages/team/team_stats.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["viewstatistics"]?></a></li>
    
    <li><a href="<?php echo $baseurl_short?>pages/team/team_report.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["viewreports"]?></a></li>

    <?php if (checkperm("m")) { ?><li><a href="<?php echo $baseurl_short?>pages/team/team_mail.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["sendbulkmail"]?></a></li><?php } ?>

    	<?php hook("customteamfunction")?>

	<?php
	# Include a link to the System Setup area for those with the appropriate permissions.
	if (checkperm("a")) { ?>

	<li><a href="<?php echo $baseurl_short?>pages/admin/admin_home.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["systemsetup"]?></a></li>
	<?php hook("customteamfunctionadmin")?>
	<?php } ?>

		
	</ul>
	</div>
	
<?php if (checkperm("u") && !checkperm("U")) { # Full admin access only to user/disk quota status
if (!hook("replaceusersonline")) {
?>
<p><?php echo $lang["usersonline"]?>:
<?php
$active=get_active_users();
for ($n=0;$n<count($active);$n++) {if($n>0) {echo", ";}echo "<b>" . $active[$n]["username"] . "</b> (" . $active[$n]["t"] . ")";}
?>
</p>	
<?php } // end hook("replaceusersonline")
?>

<p><?php echo $lang["diskusage"]?>: <b><?php echo round(($avail?$used/$avail:0)*100,0)?>%</b> (<?php echo $lang["available"]?>: <?php echo formatfilesize($avail)?>; <?php echo $lang["used"]?>: <?php echo formatfilesize($used)?>; <?php echo $lang["free"]?>:  <?php echo formatfilesize($free)?>)
</p>
<?php } ?>

</div>

<?php
include "../../include/footer.php";
?>
