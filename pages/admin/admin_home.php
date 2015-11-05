<?php
include ("../../include/db.php");
include ("../../include/general.php");
include ("../../include/authenticate.php");
include ("../../include/header.php");
?>

<div class="BasicsBox"> 
   
  <h1><?php echo $lang["systemsetup"]?></h1>
  <p><?php echo text("introtext")?></p>

  <div class="VerticalNav">
	<ul>
		<?php if (!hook('replacegroupadmin')) { ?>
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_group_management.php" onclick="return CentralSpaceLoad(this,true);" ><?php echo $lang['page-title_user_group_management']; ?></a></li>
		<?php } ?>
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_resource_types.php" onclick="return CentralSpaceLoad(this,true);"><?php echo $lang["treenode-resource_types_and_fields"] ?></a></li>
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_resource_type_fields.php" onclick="return CentralSpaceLoad(this,true);"><?php echo $lang["admin_resource_type_fields"] ?></a></li>
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_report_management.php" onclick="return CentralSpaceLoad(this,true);"><?php echo $lang['page-title_report_management']; ?></a></li>
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_size_management.php" onclick="return CentralSpaceLoad(this,true);"><?php echo $lang["page-title_size_management"] ?></a></li>
		
		<?php if (checkperm("o")) { ?><li><a href="<?php echo $baseurl_short?>pages/admin/admin_content.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["managecontent"]?></a></li><?php } ?>
		
		<?php if ($use_plugins_manager == true){ ?>
		<li><a href="<?php echo $baseurl_short?>pages/team/team_plugins.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["pluginssetup"]?></a></li>
		<?php } ?>
		
		<?php if($team_centre_bug_report && !hook("custom_bug_report")) { ?>   
		<li><a href="<?php echo $baseurl_short?>pages/admin/admin_reportbug.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["reportbug"]?></a></li>
		<?php } ?>	
		
		<li><a href="<?php echo $baseurl_short?>pages/team/team_export.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["exportdata"]?></a></li>

		<?php
		if (checkperm("a"))
			{
			?><li><a href="<?php echo $baseurl?>/pages/team/team_system_console.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["systemconsole"]?></a></li>
			<?php
			}
		?>

		<li><a href="<?php echo $baseurl_short?>pages/check.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["installationcheck"]?></a></li>

<?php
if ($web_config_edit)
	{
?>		<li><a href="<?php echo $baseurl_short?>pages/admin/fileedit.php?file=../../include/config.php" target="_blank"><?php echo $lang["action-edit"]; ?> config.php</a></li>
		<li><a href="<?php echo $baseurl_short?>pages/admin/fileedit.php?file=../../include/config.default.php" target="_blank"><?php echo $lang["action-view"]; ?> config.default.php</a></li>
<?php
	}	

hook("customadminfunction");
?>

	</ul>
	</div>
</div> <!-- End of BasicsBox -->


<?php


include("../../include/footer.php");
