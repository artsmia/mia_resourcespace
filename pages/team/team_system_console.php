<?php

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php"; if (!checkperm("a")) {exit ("Permission denied.");}

// ----- Main page load -----

$callback = getval("callback","");
if ($callback == "")
	{
	include "../../include/header.php";
	foreach (array("debuglog","memorycpu","database","sqllogtransactions") as $section)
	{
		?><script>
			var timeOutControl<?php echo $section; ?> = null;
			var sortBy<?php echo $section; ?> = "";
			var filter<?php echo $section; ?> = "";
			var refreshSecs<?php echo $section; ?> = 0;

			function SystemConsole<?php echo $section; ?>Load(refresh_secs, extra)
			{
				if (extra == undefined)
				{
					extra = "";
				}
				jQuery('#SystemConsole<?php echo $section; ?>').load('team_system_console.php?callback=<?php echo $section; ?>&sortby=' + encodeURIComponent(sortBy<?php echo $section;
				?>) + '&filter=' + encodeURIComponent(filter<?php echo $section; ?>) + extra);
				if (refresh_secs >= 0)
				{
					clearTimeout(timeOutControl<?php echo $section; ?>);
				}
				if (refresh_secs > 0)
				{
					timeOutControl<?php echo $section; ?> = setTimeout(SystemConsole<?php echo $section; ?>Load, refresh_secs * 1000, refresh_secs);
				}
				refreshSecs<?php echo $section; ?> = refresh_secs;
			}
			function SystemConsole<?php echo $section; ?>Stop()
			{
				clearTimeout(timeOutControl<?php echo $section; ?>);
				jQuery('#reload<?php echo $section ?>0').text('<?php echo $lang["reload"]; ?>');
				jQuery('.reload<?php echo $section; ?>class').css('text-decoration', 'none');
			}
		</script>
		<h2 onclick="SystemConsole<?php echo $section; ?>Load(-1); return false;" class="CollapsibleSectionHead collapsed expanded"><?php echo $lang["systemconsole" . $section]; ?></h2>
		<div class="collapsiblesection">
			<?php foreach (array(0,1,5,10,30,60) as $secs)
				{
				?><a href="#" class="reload<?php echo $section; ?>class" id="reload<?php echo $section . $secs ?>" onclick="
					jQuery(this).siblings('a').css( 'text-decoration', 'none');
					if (this.id == 'reload<?php echo $section ?>0')
					{
						jQuery('#reload<?php echo $section ?>0').text('<?php echo $lang["reload"]; ?>');
					} else {
						jQuery(this).css('text-decoration', 'underline');
						jQuery('#reload<?php echo $section ?>0').text('<?php echo $lang["pause"]; ?>');
					}
					SystemConsole<?php echo $section; ?>Load(<?php echo $secs; ?>)"><?php
						echo ($secs == 0 ? $lang['reload'] : "{$secs}s");
					?></a> <?php
				}
			?>			
			<div id="SystemConsole<?php echo $section; ?>">
			</div>
		</div>
	<?php
	}
	?><script>
		registerCollapsibleSections();
	</script>
	<?php
	include "../../include/footer.php";
	return;
	}

// ----- Callbacks -----

$sortby = getval("sortby","");
$sortasc = true;
$sorted = false;

if(strlen($sortby) > 1)
	{
	if ($sortby[0] == "-")
		{
		$sortby = substr($sortby,1);
		$sortasc = false;
		}
	}

$filter = getval("filter","");

$results = array();
$actions = array();

switch ($callback)
	{
	case "debuglog":

		$debug_user = getval("debuguser","");
		$debug_expires = getval("debugexpires","");

		if ($debug_user != "" && $debug_expires != "")
			{
			include_once "../../include/debug_functions.php";
			create_debug_log_override($debug_user, $debug_expires);
			global $debug_log_override;
			unset ($debug_log_override);
			}

		$debug_user = sql_value("SELECT value FROM sysvars WHERE name='debug_override_user'", "");
		$debug_expires = sql_value("SELECT value FROM sysvars WHERE name='debug_override_expires'", "");

		if ($debug_expires != "")
			{
			$remaining_time = $debug_expires - time();
			if ($remaining_time < 0)
				{
				$remaining_time = 0;
				}
			}
		else
			{
			$remaining_time = 60;
			}

		?>
		<br />

		<input type="radio" value="" name="debugconsole<?php echo $callback; ?>control" <?php if($debug_log) { ?> checked="checked"<?php } ?> disabled="disabled"><?php echo $lang["systemconsoleonpermallusers"]; ?> <br />

		<input type="radio" value="-1" name="debugconsole<?php echo $callback; ?>control" <?php
		if(!$debug_log && ($debug_log_override && $debug_user == -1)) { ?> checked="checked"<?php }
		if($debug_log || $debug_log_override) { ?> disabled="disabled"<?php }
		?> onclick="SystemConsole<?php echo $callback; ?>Stop(); jQuery('#debugconsole<?php echo $callback; ?>').show();" ><?php echo $lang["systemconsoleonallusers"]; ?><br />

		<input type="radio" value="<?php echo $userref; ?>" name="debugconsole<?php echo $callback; ?>control" <?php
		if(!$debug_log && ($debug_log_override && $debug_user != -1)) { ?> checked="checked"<?php }
		if($debug_log || $debug_log_override) { ?> disabled="disabled"<?php }
		?> onclick="SystemConsole<?php echo $callback; ?>Stop(); jQuery('#debugconsole<?php echo $callback; ?>').show();"><?php echo $lang["on"]; ?> (<?php echo $username; ?>)<br />

		<input type="radio" value="" name="debugconsole<?php echo $callback; ?>control" <?php
		if(!$debug_log && !$debug_log_override) { ?> checked="checked"<?php }
		if($debug_log || !$debug_log_override) { ?> disabled="disabled"<?php }
		?> onclick="SystemConsoledebuglogLoad(-1,'&debuguser=-1&debugexpires=-1');"><?php echo $lang["off"]; ?><br />

		<div id="debugconsole<?php echo $callback; ?>" style="display: none;" >
		<?php if(!$debug_log && !$debug_log_override)
			{
			?><script>
				SystemConsole<?php echo $callback; ?>Stop();
			</script>
			<br /><?php echo $lang["systemconsoleturnoffafter"]; ?> <input id="duration" type="text" class="stdwidth" style="width: 50px; text-align: right;" name="duration" onchange="if (isNaN(value)) value=60;" value="60"> <?php echo $lang["seconds"]; ?>.<br />
			<br />
			<input type="button" value="Start" onclick="SystemConsoledebuglogLoad(-1,
				'&debuguser=' + jQuery('input[name=debugconsole<?php echo $callback; ?>control]:checked').val() +
				'&debugexpires=' +
				document.getElementById('duration').value);" />
			<input type="button" value="Cancel" onclick="SystemConsole<?php echo $callback; ?>Load(0)" />
			<?php
			}
			?>
		</div>
		<?php
		if(!$debug_log && $debug_log_override)
			{
			?><br />
			<?php echo $remaining_time; ?>s remaining &mdash; <a href="#" onclick="SystemConsoledebuglogLoad(-1);"><?php echo $lang['reload']; ?></a>.<br />
			<br />
			<input type="button" value="<?php echo $lang["stopbutton"]; ?>" onclick="SystemConsole<?php echo $callback; ?>Stop(); SystemConsole<?php echo $callback; ?>Load(-1,'&debuguser=-1&debugexpires=-1');" />
			<br />
			<?php
			}

		// ----- start of tail read

		if (isset($debug_log_location) && file_exists($debug_log_location) && is_readable($debug_log_location))
			{
			$data = tail($debug_log_location,1000);
			$lines = array();
			foreach (preg_split('/\n/',$data) as $line)
				{
				$line = trim($line);
				if ($line == "")
					{
					continue;
					}
				array_push($lines, $line);
				}
			for ($i=count($lines)-1; $i >= 0; $i--)
				{
				if ($filter == "" || stripos($lines[$i],$filter)!==false)
					{
					$entry = array("Tail" => count($lines)-$i, "Line" => $lines[$i]);
					array_push ($results, $entry);
					}
				}
			}
		else
			{
			?><br />
			<?php
			echo $lang["systemconsoleondebuglognotsetorfound"];
			?><br />
			<?php
			}

		// ----- end of tail read

		break;

	case "memorycpu":

		if ($config_windows)		// Windows (tasklist command)
			{
			$lines = run_command("tasklist /v /fo csv");
			$lines = explode("\n", $lines);
			if (is_array($lines) && count($lines) > 1)
				{
				$headings = str_getcsv($lines[0]);
				for ($i = 1; $i < count($lines); $i++)
					{
					$fields = str_getcsv($lines[$i]);
					if (count($fields)!=count($headings))
						{
						continue;
						}
					$filtermatch = false;
					$result = array();
					for ($y = 0; $y < count($fields); $y++)
						{
						$filtermatch = ($filtermatch || $filter == "" || stripos($fields[$y],$filter)!==false);
						$result[$headings[$y]] = $fields[$y];
						}
					if ($filtermatch)
						{
						array_push($results, $result);
						}
					}
				}
			else
				{
				?><p><?php echo $lang["systemconsoleonfailedtasklistcommand"]; ?></p><?php
				}
			}
		else		// UNIX (top command)
			{
			$lines = run_command("top -b -n 1",true);
			$lines = explode("\n", $lines);
			
			if (is_array($lines) && count($lines) > 6)		// need to burn the leading 6 lines of the top command
				{
				$headings = preg_split('/\s+/',$lines[6]);
				array_shift($headings);
				array_pop($headings);

				for ($i = 7; $i < count($lines); $i++)
					{
					$fields = preg_split('/\s+/',$lines[$i]);
					array_shift($fields);
					array_pop($fields);
					if (count($fields)!=count($headings))
						{
						continue;
						}
					$filtermatch = false;
					$result = array();
					for ($y = 0; $y < count($fields); $y++)
						{
						$filtermatch = ($filtermatch || $filter == "" || stripos($fields[$y],$filter)!==false);
						$result[$headings[$y]] = $fields[$y];
						}
					if ($filtermatch)
						{
						array_push($results, $result);
						}
					}
				}
			else
				{
				?><p><?php echo $lang["systemconsoleonfailedtopcommand"]; ?></p><?php
				}
			}

		break;

	case "database":

		$order_by = "";
		if ($sortby)
			{
			if ($sortasc)
				{
				$order_by = " ORDER BY `{$sortby}` ASC";
				}
			else
				{
				$order_by = " ORDER BY `{$sortby}` DESC";
				}
			}

		if ($filter == "")
			{
			$results = sql_query("SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST" . $order_by);
			}
		else
			{
			$result_rows = sql_query("SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST" . $order_by);

			foreach ($result_rows as $row)
				{
				foreach ($row as $cell)
					{
					if (stripos($cell,$filter)!==false)
						{
						array_push($results, $row);
						break;
						}
					}
				}
			}

		// $actions = array("Kill" => "stuff"); /* for future implementation */
		$sorted = true;
		break;

	case "sqllogtransactions":

		if (isset($mysql_log_transactions) && isset($mysql_log_location) && file_exists($mysql_log_location) && is_readable($mysql_log_location))
			{
			$data = tail($mysql_log_location,1000);
			$lines = array();
			foreach (preg_split('/\n/',$data) as $line)
				{
				$line = trim($line);
				if ($line == "")
					{
					continue;
					}
				array_push($lines, $line);
				}
			for ($i=count($lines)-1; $i >= 0; $i--)
				{
				if ($filter == "" || stripos($lines[$i],$filter)!==false)
					{
					$entry = array("Tail" => count($lines)-$i, "Line" => $lines[$i]);
					array_push ($results, $entry);
					}
				}
			}
		else
			{
			?><br />
			<?php
			echo $lang["systemconsoleonsqllognotsetorfound"];
			?><br />
			<?php
			}

		break;

	}		// end of callback switch

?>
<br />
<input type="text" class="stdwidth" placeholder="<?php echo $lang["filterbutton"]; ?>" value="<?php echo $filter; ?>"
	onblur="SystemConsole<?php echo $callback; ?>Stop();"
	onkeyup="if(this.value=='')
	{
	   jQuery('#filterbutton<?php echo $callback; ?>').attr('disabled','disabled');
	   jQuery('#clearbutton<?php echo $callback; ?>').attr('disabled','disabled')
	} else {
		jQuery('#filterbutton<?php echo $callback; ?>').removeAttr('disabled');
		jQuery('#clearbutton<?php echo $callback; ?>').removeAttr('disabled')
	}
	filter<?php echo $callback; ?>=this.value;
	var e = event;
	if (e.keyCode === 13)
	{
		SystemConsole<?php echo $callback; ?>Load(refreshSecs<?php echo $callback; ?>);
	}" ></input>

<input id="filterbutton<?php echo $callback; ?>" <?php if($filter=="") { ?>disabled="disabled" <?php } ?>type="button" onclick="SystemConsole<?php echo $callback; ?>Load(refreshSecs<?php echo $callback; ?>);" value="<?php echo $lang['filterbutton']; ?>"></input>
<input id="clearbutton<?php echo $callback; ?>" <?php if($filter=="") { ?>disabled="disabled" <?php } ?>type="button" onclick="filter<?php echo $callback; ?>=''; SystemConsole<?php echo $callback; ?>Load(refreshSecs<?php echo $callback; ?>);" value="<?php echo $lang["clearbutton"]; ?>"></input>

<?php

if (count($results)==0)
	{
	?><br /><?php echo $lang["nothing-to-display"]; ?><br /><br />
	<?php
	return;
	}

if (!$sorted && $sortby)
	{
	usort($results, function ($a, $b)
		{
		global $sortby, $sortasc;
		if ($a[$sortby] == $b[$sortby])
			return 0;
		if ($sortasc)
			{
			return ($a[$sortby] < $b[$sortby]) ? -1 : 1;
			}
		else
			{
			return ($a[$sortby] > $b[$sortby]) ? -1 : 1;
			}
		});
	}
?><div class="Listview">
	<strong><?php echo $lang['total']; ?>: <?php echo count($results); ?></strong>
	<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
		<tbody>
			<tr class="ListviewTitleStyle">
				<?php
				foreach (array_keys($results[0]) as $heading)
					{
					?>
					<td><a href="#" onclick="sortBy<?php echo $callback; ?>='<?php
					if ($sortby == $heading && $sortasc)
						{
						?>-<?php echo $heading;
						$span = "ASC";
						}
					elseif ($sortby == $heading && !$sortasc)
						{
						$span = "DESC";
						}
					else
						{
						echo $heading;
						$span = "";
						}
					?>'; SystemConsole<?php echo $callback; ?>Load(-1);"><?php
						echo $heading;
						if ($span != "")
							{
							?><span class="<?php echo $span; ?>"></span><?php
							}
						?></a></td><?php
					}
				if (count($actions) > 0)
					{
					?><td><div class="ListTools">Tools</div></td><?php
					}
				?>
			</tr>
		</tbody>
		<tbody id="resource_type_field_table_body" class="ui-sortable">
			<?php			
			for ($i=0; $i<count($results) && $i<20; $i++)
				{				
				?>
				<tr class="resource_type_field_row">
					<?php
					foreach ($results[$i] as $cell)
					{
						?><td><?php echo str_highlight(htmlspecialchars($cell),$filter); ?></td><?php
					}
					?>
					<?php
					if (count($actions) > 0)
					{
					?>
						<td>
							<div class="ListTools">
								<?php
								foreach ($actions as $title => $action)
									{
									?>&nbsp;<a href="#">&gt;&nbsp;<?php echo $title; ?></a><?php
									}
								?>
							</div>
						</td><?php
					}
					?>
				</tr>
				<?php
				}
			?>
		</tbody>
	</table>	
</div>
