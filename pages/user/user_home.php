<?php

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";
include "../../include/header.php";
?>
<div class="BasicsBox"> 
  <h1><?php echo $lang["myaccount"]?></h1>
  <p><?php echo text("introtext")?></p>
  
	<div class="VerticalNav">
	<ul>
        <li><a href="<?php echo $baseurl_short?>pages/user/user_change_password.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["changeyourpassword"]?></a></li>
        <?php
      	if ($disable_languages==false && $show_language_chooser)
			{?>
			<li><a href="<?php echo $baseurl_short?>pages/change_language.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["languageselection"]?></a></li>
			<?php
			} ?>
		<li><a href="<?php echo $baseurl_short?>pages/contribute.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["mycontributions"]?></a></li>
		<li><a href="<?php echo $baseurl_short?>pages/collection_manage.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["mycollections"]?></a></li>
		
		<script>message_poll();</script>
		<li><a href="<?php echo $baseurl_short?>pages/user/user_messages.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["mymessages"]; 
		?></a><span style="display: none;" class="MessageCountPill"></span></li>
		
		<?php
		if($home_dash && checkPermission_dashmanage())
			{ ?>
			<li><a href="<?php echo $baseurl_short?>pages/user/user_dash_admin.php"	onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["manage_own_dash"];?></a></li>
			<?php
			}
		if($user_preferences)
			{ ?>
			<li>
				<a href="<?php echo $baseurl_short?>pages/user/user_preferences.php" onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["userpreferences"];?></a>
			</li>
			<?php
			} ?>
	</ul>
	</div>

</div>

<?php
include "../../include/footer.php";
