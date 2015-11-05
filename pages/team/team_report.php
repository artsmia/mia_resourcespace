<?php
/**
 * Report creation page (part of Team Center)
 * 
 * @package ResourceSpace
 * @subpackage Pages_Team
 */
include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";if (!checkperm("t")) {exit ("Permission denied.");}
include "../../include/reporting_functions.php";
set_time_limit(0);
$report=getvalescaped("report","");
$period=getvalescaped("period",$reporting_periods_default[0]);
$period_init=$period;

if ($period==0)
	{
	# Specific number of days specified.
	$period=getvalescaped("period_days","");
	if (!is_numeric($period) || $period<1) {$period=1;} # Invalid period specified.
	}

if ($period==-1)
	{
	# Specific date range specified.
	$from_y = getvalescaped("from-y","");
	$from_m = getvalescaped("from-m","");
	$from_d = getvalescaped("from-d","");
	
	$to_y = getvalescaped("to-y","");
	$to_m = getvalescaped("to-m","");
	$to_d = getvalescaped("to-d","");
	}
else
	{
	# Work out the from and to range based on the provided period in days.
	$start=time()-(60*60*24*$period);

	$from_y = date("Y",$start);
	$from_m = date("m",$start);
	$from_d = date("d",$start);
		
	$to_y = date("Y");
	$to_m = date("m");
	$to_d = date("d");
	}
	
$from=getvalescaped("from","");
$to=getvalescaped("to","");
$output="";


# Execute report.
if ($report!="" && (getval("createemail","")==""))
	{
	$download=getval("download","")!="";
	$output=do_report($report, $from_y, $from_m, $from_d, $to_y, $to_m, $to_d,$download);
	}

include "../../include/header.php";	
	
if (getval("createemail","")!="")
	{
	# Create a new periodic e-mail report
	create_periodic_email($userref,$report,$period,getval("email_days",""),getval("send_all_users","")=="yes");
	?>
	<script type="text/javascript">
	alert("<?php echo $lang["newemailreportcreated"] ?>");
	</script>
	<?php
	}
	
$unsubscribe=getvalescaped("unsubscribe","");
if ($unsubscribe!="")
	{
	unsubscribe_periodic_report($unsubscribe);
	?>
	<div class="BasicsBox"> 
	  <h2>&nbsp;</h2>
	  <h1><?php echo $lang["unsubscribed"]?></h1>
	  <p><?php echo $lang["youhaveunsubscribedreport"]?></p>
	</div>
	<?php
	}
else
	{
	# Normal behaviour.
?>

<div class="BasicsBox"> 
  <h1><?php echo $lang["viewreports"]?></h1>
  <p><?php echo text("introtext")?></p>
  
<form method="post" action="<?php echo $baseurl ?>/pages/team/team_report.php" onSubmit="if (!do_download) {return CentralSpacePost(this);}">
<div class="Question">
<label for="report"><?php echo $lang["viewreport"]?></label><select id="report" name="report" class="stdwidth">
<?php
$reports=get_reports();

$ref=getval("ref","");

for ($n=0;$n<count($reports);$n++)
	{
	?><option value="<?php echo $reports[$n]["ref"]; ?>"<?php if($reports[$n]["ref"]==$ref) { ?> selected="selected"<?php } ?>"><?php echo $reports[$n]["name"]?></option><?php
	}
?>
</select>
<div class="clearerleft"> </div>
</div>

<?php include "../../include/date_range_selector.php" ?>


<!-- E-mail Me function -->
<div id="EmailMe" <?php if ($period_init==-1) { ?>style="display:none;"<?php } ?>>
<div class="Question">
<label for="email"><?php echo $lang["emailperiodically"]?></label>
<input type="checkbox" onClick="
if (this.checked)
	{
	document.getElementById('EmailSetup').style.display='block';
	
	// Copy reporting period to e-mail period
	if (document.getElementById('period').value==0)
		{
		// Copy from specific day box
		document.getElementById('email_days').value=document.getElementById('period_days').value;
		}
	else
		{
		document.getElementById('email_days').value=document.getElementById('period').value;		
		}
	}
else
	{
	document.getElementById('EmailSetup').style.display='none';
	}
	">
<div class="clearerleft"> </div>
</div>

<div id="EmailSetup" style="display:none;">

<!-- E-mail Period select -->
<div class="Question">
<label for="email_days">&nbsp;</label>
<div class="Fixed">
<?php
$textbox="<input type=\"text\" id=\"email_days\" name=\"email_days\" size=\"4\" value=\"7\">";
echo str_replace("?",$textbox,$lang["emaileveryndays"]);

if (checkperm("m"))
	{
	# Option to send to all active users
	echo "<br/>" . $lang["report-send-all-users"];
	?><input type="checkbox" name="send_all_users" value="yes" /><br /><br /><?php
	}

?>
&nbsp;&nbsp;<input name="createemail" type="submit" onClick="do_download=true;" value="&nbsp;&nbsp;<?php echo $lang["create"] ?>&nbsp;&nbsp;" />
</div>
<div class="clearerleft"> </div>
</div>
<!-- End of E-mail Period Select -->

</div>
</div>
<!-- End of E-mail Me function -->

<?php hook('customreportform', '', array($report)); ?>

<script language="text/javascript">
var do_download=false;
</script>


<div class="QuestionSubmit" id="SubmitBlock">
<label for="buttons"> </label>			
<input name="save" type="submit" onClick="do_download=false;" value="&nbsp;&nbsp;<?php echo $lang["viewreport"] ?>&nbsp;&nbsp;" />
<input name="download" type="submit" onClick="do_download=true;" value="&nbsp;&nbsp;<?php echo $lang["downloadreport"] ?>&nbsp;&nbsp;" />
</div>
</form>

<?php echo $output; ?>

</div>

<?php
}
include "../../include/footer.php";
?>
