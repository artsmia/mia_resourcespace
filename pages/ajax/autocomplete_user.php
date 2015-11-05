<?php
# Feeder page for AJAX user/group search for the user selection include file.

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";

$find=getvalescaped("term","");
$getrefs=(getvalescaped("getrefs","")!="")?true:false;
$ignoregroups=(getvalescaped("nogroups","")!="")?true:false;
$first=true;
?> [ <?php
$users=get_users(0,$find);
for ($n=0;$n<count($users) && $n<=20;$n++)
	{
	$show=true;
	if (checkperm("E") && ($users[$n]["groupref"]!=$usergroup) && ($users[$n]["groupparent"]!=$usergroup) && ($users[$n]["groupref"]!=$usergroupparent)) {$show=false;}
	if ($show)
		{
		if (!$first) { ?>, <?php }
		$first=false;
		
		?>{ "label": "<?php echo $users[$n]["fullname"]?>", "value": "<?php echo $users[$n]["username"]?>" <?php if ($getrefs){?>,  "ref": "<?php echo $users[$n]["ref"]?>"<?php }?> } <?php
		}
	}
if(!$ignoregroups)
	{
	$groups=get_usergroups(true,$find);

	for ($n=0;$n<count($groups) && $n<=20;$n++)
		{
		$show=true;
		if (checkperm("E") && ($groups[$n]["ref"]!=$usergroup) && ($groups[$n]["parent"]!=$usergroup) && ($groups[$n]["ref"]!=$usergroupparent)) {$show=false;}
		if ($show)
			{
			$users=get_users($groups[$n]["ref"]);
			$ulist="";
			
			for ($m=0;$m<count($users);$m++) {if ($ulist!="") {$ulist.=", ";};$ulist.=$users[$m]["username"];}
			if ($ulist!="")
				{
				if (!$first) { ?>, <?php }
				$first=false;
				
				?>{ "label": "<?php echo $lang["group"]?>: <?php echo $groups[$n]["name"]?>", "value": "<?php echo $lang["group"]?>: <?php echo $groups[$n]["name"]?>" <?php if ($getrefs){?>,  "ref": "<?php echo $groups[$n]["ref"]?>"<?php }?> }<?php 
				}
			}
		}
		}
?> ]
