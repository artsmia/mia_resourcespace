<?php 
$download_summary=download_summary($ref);

# Work out total.
$total=0;
foreach ($download_summary as $usage)
	{ 
	if (array_key_exists($usage["usageoption"],$download_usage_options))
		{
		$total+=$usage["c"];
		} else if ($usage['usageoption'] == '-1') {
			$total+=$usage['c'];
		}
	}
	

?>

<div class="RecordDownload" id="RecordDownloadSummary" style="margin-right:10px;">
<div class="RecordDownloadSpace">

<h2><?php echo $lang["usagehistory"] ?></h2>


<table cellpadding="0" cellspacing="0">
<tr><td colspan=2><?php echo $lang["usagetotal"] ?></td></tr>
<tr class="DownloadDBlend" >
<td><?php echo $lang["usagetotalno"] ?></td>
<td width="20%"><?php echo $total ?></th>		
</tr>
</table>
<?php if($total>0 && $download_usage && $usage['usageoption'] != '-1')	{ ?>
<table cellpadding="0" cellspacing="0">
<tr><td colspan=2><?php echo $lang["usagebreakdown"] ?></td></tr>
<?php foreach ($download_summary as $usage)
	{ 
	if (array_key_exists($usage["usageoption"],$download_usage_options))
		{
		?>
		<tr class="DownloadDBlend" >
		<td><?php echo htmlspecialchars($download_usage_options[$usage["usageoption"]]) ?></td>
		<td width="20%"><?php echo $usage["c"]?></th>		
		</tr>
		<?php
		}
	}
?>
</table>
<?php } ?>

</div>
</div>
