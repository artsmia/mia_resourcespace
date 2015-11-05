<?php
# Script that checks for available disk space of the storage drive. If the drive 
# reaches the notification limit an email will be sent out. This should be set up as a 
# cron job.

include "../../include/db.php";
include "../../include/general.php";

if(!isset($disk_quota_limit_size_warning_noupload) && !isset($disk_quota_notification_limit_percent_warning)){
	die("Please set the disk quota limits in your configuration before running this script!");
}
else{
	echo "Checking disk usage...<br/>";

	# Work out free space / usage
	if (!file_exists($storagedir)) {mkdir($storagedir,0777);}

	if (isset($disksize)){ # Use disk quota rather than real disk size
		$avail=$disksize*(1024*1024*1024);
		$used=get_total_disk_usage();
		$free=$avail-$used;
	}
	else{		
		$avail=disk_total_space($storagedir);
		$free=disk_free_space($storagedir);
		$used=$avail-$free;
	}
	if ($free<0){
		$free=0;
	}
	
	$avail_format=str_replace("&nbsp;", " ",formatfilesize($avail));
	$used_format=str_replace("&nbsp;"," ",formatfilesize($used));
	$free_format=str_replace("&nbsp;"," ",formatfilesize($free));
	$used_percent=round(($avail?$used/$avail:0)*100,0);

	echo $lang["diskusage"].": ".$used_percent."%\n".$lang["available"].": ".$avail_format."\n".$lang["used"].": ".$used_format."\n".$lang["free"].": ".$free_format."<br/><br/>";
	
	if(isset($disk_quota_notification_limit_percent_warning)){
		$send_email=false;
		if($used_percent>=$disk_quota_notification_limit_percent_warning){
			// Check the last time this notice was sent
			echo "Percentage used is greater than or equal to ".$disk_quota_notification_limit_percent_warning."%.";
			if(!isset($disk_quota_notification_interval)){
				$send_email=true;
			}
			else{
				$last_sent=sql_value("select value from sysvars where name='last_sent_disk_quota'","");
				echo "Last Sent:".strtotime($last_sent)." - ".$last_sent."<br/>";
				echo "Now:".time()." - ".date("Y-m-d H:i:s")."<br/>";
				echo "Interval:".($disk_quota_notification_interval*60*60)."<br/>";
				if($last_sent=='' || (time()-strtotime($last_sent))>($disk_quota_notification_interval*60*60)){
					$send_email=true;
				}
			}
			if($send_email){
				# Send notifications
				$subject="Space used has reached or exceeded " . $disk_quota_notification_limit_percent_warning . "%!";
				$body="Space used has reached or exceeded ".$disk_quota_notification_limit_percent_warning."%!\n".$lang["diskusage"].": ".$used_percent."%\n".$lang["available"].": ".$avail_format."\n".$lang["used"].": ".$used_format."\n".$lang["free"].": ".$free_format;
				
				echo" Sending email...<br/>";
				
				send_mail($disk_quota_notification_email,$subject,$body);
				// update last sent
				sql_query("delete from sysvars where name='last_sent_disk_quota'");
				sql_query("insert into sysvars(name,value) values ('last_sent_disk_quota',now())");
			}
		}
		else{
			echo "Percentage used is less than ".$disk_quota_notification_limit_percent_warning."%.<br/>";
		}
	}
	if(isset($disk_quota_limit_size_warning_noupload)){
		# convert limit
		$limit=$disk_quota_limit_size_warning_noupload*1024*1024*1024;
		if($free<=$limit){
			
			echo "Free space is less than or equal to ".$disk_quota_limit_size_warning_noupload." GB.";
			if(!isset($disk_quota_notification_interval)){
				$send_email=true;
			}
			else{
				$last_sent=sql_value("select value from sysvars where name='last_sent_disk_quota_noupload'","");
				echo "Last Sent:".strtotime($last_sent)." - ".$last_sent."<br/>";
				echo "Now:".time()." - ".date("Y-m-d H:i:s")."<br/>";
				echo "Interval:".($disk_quota_notification_interval*60*60)."<br/>";
				if($last_sent=='' || (time()-strtotime($last_sent))>($disk_quota_notification_interval*60*60)){
					$send_email=true;
				}
			}
			if($send_email){
				# Send notifications
				$subject="Uploads Disabled - Free space is " . $disk_quota_limit_size_warning_noupload . "GB or less!";
				$body="Uploading will be disabled because free space is ".$disk_quota_limit_size_warning_noupload."GB or less!\n".$lang["diskusage"].": ".$used_percent."%\n".$lang["available"].": ".$avail_format."\n".$lang["used"].": ".$used_format."\n".$lang["free"].": ".$free_format;
				
				echo" Sending email...<br/>";
				
				send_mail($disk_quota_notification_email,$subject,$body);
				// update last sent
				sql_query("delete from sysvars where name='last_sent_disk_quota_noupload'");
				sql_query("insert into sysvars(name,value) values ('last_sent_disk_quota_noupload',now())");
			}
		}
		else{
			echo "Free space is greater than ".$disk_quota_limit_size_warning_noupload." GB.<br/>";
		}
	}
	hook("aftercheckdiskusage");
}
?>
