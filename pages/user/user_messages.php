<?php

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("k")) {exit ("Permission denied.");}
include "../../include/header.php";

?>
<div class="BasicsBox">
  <h1><?php echo $lang["mymessages"]?></h1>
  <p><?php echo text("mymessages_introtext")?></p>
</div>

<?php
	$messages=array();
	if (!message_get($messages,$userref,true,true))		// if no messages get out of here with a message
		{
		echo $lang['mymessages_youhavenomessages'];
		include "../../include/footer.php";
		return;
		}

	$unread = false;

	foreach ($messages as $message)		// if there are unread messages show option to mark all as read
		{
		if ($message['seen']==0)
			{
			$unread=true;
			break;
			}
		}
	if ($unread)
		{
?><a href="<?php echo $baseurl_short?>pages/user/user_messages.php" onclick="jQuery.get('<?php
		echo $baseurl; ?>/pages/ajax/message.php?allseen=<?php echo $userref; ?>', function(){
			return CentralSpaceLoad(this,true);
		});">&gt;&nbsp;<?php echo $lang['mymessages_markallread']; ?></a>
<?php
		}
?><div class="Listview">
	<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
		<tr class="ListviewTitleStyle">
			<td><?php echo $lang["created"]; ?></td>
			<td><?php echo $lang["from"]; ?></td>
			<td><?php echo $lang["message"]; ?></td>
			<td><?php echo $lang["link"]; ?></td>
			<td><?php echo $lang["expires"]; ?></td>
			<td><?php echo $lang["seen"]; ?></td>
			<td><div class="ListTools"><?php echo $lang["tools"]?></div></td>
		</tr>
<?php
for ($n=0;$n<count($messages);$n++)
	{
	$message=escape_check($messages[$n]["message"]);
	$message=htmlspecialchars($message,ENT_QUOTES);
	$url_encoded=urlencode($messages[$n]["url"]);
	?>
		<tr>
			<td><?php echo nicedate($messages[$n]["created"],true); ?></td>
			<td><?php echo $messages[$n]["owner"]; ?></td>
			<td><a href="#Header" onclick="message_display('<?php echo $message; ?>','<?php
				echo $url_encoded; ?>',<?php echo $messages[$n]["ref"]; ?>);"><?php echo htmlentities($messages[$n]["message"]); ?></a></td>

			<td><a href="<?php echo $messages[$n]["url"]; ?>"><?php echo htmlentities($messages[$n]["url"]);  ?></a></td>
			<td><?php echo nicedate($messages[$n]["expires"]); ?></td>
			<td><?php echo ($messages[$n]["seen"]==0 ? $lang['no'] : $lang['yes']); ?></td>
			<td>
				<div class="ListTools">
					<?php
					if ($messages[$n]["seen"]==0)
						{
						?><a href="<?php echo $baseurl_short?>pages/user/user_messages.php" onclick="jQuery.get('<?php
							echo $baseurl; ?>/pages/ajax/message.php?seen=<?php echo $messages[$n]['ref']; ?>');
							return CentralSpaceLoad(this,true);
							">&gt;&nbsp;<?php echo $lang["mymessages_markread"]; ?></a><?php
						}
?>				</div>
			</td>
		</tr>
<?php
	}
?></table>
</div>
<?php

include "../../include/footer.php";
