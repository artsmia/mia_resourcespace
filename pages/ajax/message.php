 <?php

 	DEFINE ("MESSAGE_POLLING_ABSENT_USER_TIMEOUT_SECONDS",30);
 	DEFINE ("MESSAGE_FADEOUT_SECONDS",5);

	// check for callback, i.e. this file being called directly to get any new messages
	if (basename(__FILE__)==basename($_SERVER['PHP_SELF']))
		{

		include __DIR__ . "/../../include/general.php";
		include __DIR__ . "/../../include/db.php";

		// It is an acknowledgement so set as seen and get out of here
		if (isset($_GET['seen']))
			{
			message_seen($_GET['seen']);
			return;
			}

		// Acknowledgement all messages then get out of here
		if (isset($_GET['allseen']))
			{
			message_seen_all($_GET['allseen']);
			return;
			}

		// Purge message that have an expired TTL then get out of here
		if (isset($_GET['purge']))
			{
			message_purge();
			return;
			}

		if(isset($_GET['user']))
			{
			$user=$_GET['user'];
			}
		else
			{
			include __DIR__ . "/../../include/authenticate.php";	// no user specified so default to the current user
			$user=$userref;
			}

		// Check if there are messages
		$messages = array();
		if (message_get($messages,$user))
			{
			echo json_encode($messages);		// note: messages are passed by reference
			}
		else
			{
			http_response_code(204);		// 204 No Content - i.e. no messages to display
			}

		return;
		}

?><script>

 	var activeSeconds=<?php echo MESSAGE_POLLING_ABSENT_USER_TIMEOUT_SECONDS; ?>;

	var message_timer = null;
	var message_refs = new Array();
	var message_poll_first_run = true;

	function message_poll()
	{
		if (message_timer != null)
		{
			clearTimeout(message_timer);
			message_timer = null;
		}
		activeSeconds-=<?php echo $message_polling_interval_seconds; ?>;
		<?php
		if ($message_polling_interval_seconds > 0)
			{
			?>if(activeSeconds < 0)
			{
				message_timer = window.setTimeout(message_poll,<?php echo $message_polling_interval_seconds; ?> * 1000);
				return;
			}
			<?php
			}
		?>
		jQuery.ajax({
			url: '<?php echo $baseurl; ?>/pages/ajax/message.php',
			type: 'GET',
			success: function(messages, textStatus, xhr) {
				if(xhr.status==200)
				{
					messages = jQuery.parseJSON(messages);
					jQuery('span.MessageCountPill').html(messages.length).click(function() {
						document.location.href='<?php echo $baseurl; ?>/pages/user/user_messages.php';
					}).fadeIn();
					if (activeSeconds > 0 || message_poll_first_run)
					{
						for(var i=0; i < messages.length; i++)
						{
							var ref = messages[i]['ref'];
							if (message_poll_first_run)
							{
								message_refs.push(ref);
								continue;
							}
							if (message_refs.indexOf(ref)!=-1)
							{
								continue;
							}
							message_refs.push(ref);
							var message = messages[i]['message'];
							var url = messages[i]['url'];
							message_display(message, url, ref, function (ref) {
								jQuery.get('<?php echo $baseurl; ?>/pages/ajax/message.php?seen=' + ref).done(function () {
									message_poll();
								});
							});
						}
					}
				}
				else
				{
					jQuery('span.MessageCountPill').hide();
				}
			}
		}).done(function() {
			<?php if ($message_polling_interval_seconds > 0)
			{
				?>message_timer = window.setTimeout(message_poll,<?php echo $message_polling_interval_seconds; ?> * 1000);
				<?php
			}
			?>
			message_poll_first_run = false;
		});
	}

	jQuery(document).bind("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error",
		function() {
			activeSeconds=<?php echo MESSAGE_POLLING_ABSENT_USER_TIMEOUT_SECONDS; ?>;
		});

	jQuery(document).ready(function () {
			message_poll();
		});

	function message_display(message, url, ref, callback)
	{
		if (typeof ref==="undefined")
		{
			ref=new Date().getTime();
		}
		if (typeof url==="undefined")
		{
			url="";
		}
		if (url!="")
		{
			url=decodeURIComponent(url);
			url="<a href='" + url + "'>" + url + "</a>";
		}
		var id='message' + ref;
		if (jQuery("#" + id).length)		// already being displayed
		{
			return;
		}
		jQuery('div#MessageContainer').append("<div class='MessageBox' style='display: none;' id='" + id + "'>" + message + "<br />" + url + "</div>").after(function()
		{
			jQuery("div#" + id).show().bind("click",function() {
				jQuery("div#" + id).fadeOut("fast").remove();
				if (typeof callback!=="undefined")
				{
					callback(ref);
				}
			}).delay(<?php echo MESSAGE_FADEOUT_SECONDS; ?>*1000).fadeOut(<?php echo MESSAGE_FADEOUT_SECONDS; ?>*1000, function() {
				jQuery("div#" + id).remove();
			});
		});
	}

</script>
