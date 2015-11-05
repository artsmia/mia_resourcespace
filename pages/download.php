<?php
ob_start(); // we will use output buffering to prevent any included files 
            // from outputting stray characters that will mess up the binary download
            // we will clear the buffer and start over right before we download the file
include_once dirname(__FILE__)."/../include/db.php";
include_once dirname(__FILE__)."/../include/general.php";
include_once dirname(__FILE__)."/../include/resource_functions.php";
include_once dirname(__FILE__)."/../include/search_functions.php";

ob_end_clean(); 

if($download_no_session_cache_limiter){session_cache_limiter(false);}

if(strlen(getvalescaped('direct',''))>0){$direct = true;} else { $direct = false;}

# if direct downloading without authentication is enabled, skip the authentication step entirely
if (!($direct_download_noauth && $direct)){
	# External access support (authenticate only if no key provided, or if invalid access key provided)
	$k=getvalescaped("k","");if (($k=="") || (!check_access_key(getvalescaped("ref","",true),$k))) {include dirname(__FILE__)."/../include/authenticate.php";}
}

$ref=getvalescaped("ref","",true);
$size=getvalescaped("size","");
$ext=getvalescaped("ext","");
if(!preg_match('/^[a-zA-Z0-9]+$/', $ext)){$ext="jpg";}

$alternative=getvalescaped("alternative",-1);
$page=getvalescaped("page",1);
$usage=getvalescaped("usage","-1");
$usagecomment=getvalescaped("usagecomment","");


$resource_data=get_resource_data($ref);

if ($direct_download_noauth && $direct){
	# if this is a direct download and direct downloads w/o authentication are enabled, allow regardless of permissions
	$allowed = true;
} else {
	# Permissions check
	$allowed=resource_download_allowed($ref,$size,$resource_data["resource_type"],$alternative);
}

if (!$allowed)
	{
		# This download is not allowed. How did the user get here?
		exit("Permission denied");
	}

# additional access check, as the resource download may be allowed, but access restriction should force watermark.	
$access=get_resource_access($ref);	
$use_watermark=check_use_watermark($ref);

# If no extension was provided, we fallback to JPG.
if ($ext=="") {$ext="jpg";}

$noattach=getval("noattach","");
$path=get_resource_path($ref,true,$size,false,$ext,-1,$page,$use_watermark && $alternative==-1,"",$alternative);

hook('modifydownloadpath');

if (!file_exists($path)) {$path=get_resource_path($ref,true,"",false,$ext,-1,$page,false,"",$alternative);}

if (!file_exists($path) && $noattach!="")
	{
	# Return icon for file (for previews)
	$info=get_resource_data($ref);
	$path="../gfx/" . get_nopreview_icon($info["resource_type"],$ext,"thm");
	}

# writing RS metadata to files: exiftool
if ($noattach=="" && $alternative==-1) # Only for downloads (not previews)
	{
	$tmpfile=write_metadata($path,$ref);
	if ($tmpfile!==false && file_exists($tmpfile)){$path=$tmpfile;}
	}

hook('modifydownloadfile');	
$filesize=filesize_unlimited($path);
header("Content-Length: " . $filesize);

# Log this activity (download only, not preview)
if ($noattach=="")
	{
	daily_stat("Resource download",$ref);
	resource_log($ref,'d',0,$usagecomment,"","",$usage,$size);
	
        hook('moredlactions');

	# update hit count if tracking downloads only
	if ($resource_hit_count_on_downloads) { 
		# greatest() is used so the value is taken from the hit_count column in the event that new_hit_count is zero to support installations that did not previously have a new_hit_count column (i.e. upgrade compatability).
		sql_query("update resource set new_hit_count=greatest(hit_count,new_hit_count)+1 where ref='$ref'");
	} 
	
	# We compute a file name for the download.
	$filename=$ref . $size . ($alternative>0?"_" . $alternative:"") . "." . $ext;
	
	if ($original_filenames_when_downloading)
		{
		# Use the original filename.
		if ($alternative>0)
			{
			# Fetch from the resource_alt_files alternatives table (this is an alternative file)
			$origfile=get_alternative_file($ref,$alternative);
			$origfile=get_data_by_field($ref,$filename_field)."-".$origfile["file_name"];
			}
		else
			{
				
			# Fetch from field data or standard table		

			$origfile=get_data_by_field($ref,$filename_field);	
				
			}
		if (strlen($origfile)>0)
			{
			# do an extra check to see if the original filename might have uppercase extension that can be preserved.	
			$pathparts=pathinfo($origfile);
			if (isset($pathparts['extension'])){
				if (strtolower($pathparts['extension'])==$ext){$ext=$pathparts['extension'];}	
			} 
			
			# Use the original filename if one has been set.
			# Strip any path information (e.g. if the staticsync.php is used).
			# append preview size to base name if not the original
			if ($size!=""){$filename=strip_extension(mb_basename($origfile))."-".$size.".".$ext;}
			else {$filename = strip_extension(mb_basename($origfile)).".".$ext;}

			if ($prefix_resource_id_to_filename) { $filename = $prefix_filename_string . $ref . "_" . $filename; }
			}
		}

	if ($download_filename_id_only){
		if(!hook('customdownloadidonly', '', array($ref, $ext, $alternative))) {
			$filename=$ref . "." . $ext;

			if($size != '' && $download_id_only_with_size) {
				$filename = $ref . '-' . $size . '.' . $ext;
			}

			if(isset($prefix_filename_string) && trim($prefix_filename_string) != '') {
				$filename = $prefix_filename_string . $filename;
			}

		}
	}
	
	if (isset($download_filename_field))
		{
		$newfilename=get_data_by_field($ref,$download_filename_field);
		if ($newfilename)
			{
			$filename = trim(nl2br(strip_tags($newfilename)));
			if($size != "")
				{
				$filename = substr($filename, 0, 200) . '-' . $size . '.' . $ext;
				}
			else
				{
				$filename = substr($filename, 0, 200) . '.' . $ext;
				}
			if($prefix_resource_id_to_filename)
				{
				$filename = $prefix_filename_string . $ref . '_' . $filename;
				}
			}
		}

	# Remove critical characters from filename
	$altfilename=hook("downloadfilenamealt");
	if(!($altfilename)) $filename = preg_replace('/:/', '_', $filename);
	else $filename=$altfilename;

    	hook("downloadfilename");

	if (!$direct)
		{
		# We use quotes around the filename to handle filenames with spaces.
		header(sprintf('Content-Disposition: attachment; filename="%s"', $filename));
		}
	}

# We assign a default mime-type, in case we can find the one associated to the file extension.
$mime="application/octet-stream";

if ($noattach=="")
	{
	$mime = get_mime_type($path);
	}
	
# We declare the downloaded content mime type.
header("Content-Type: $mime");

set_time_limit(0);

if (!hook("replacefileoutput"))
	{
	# New method
	$sent = 0;
	$handle = fopen($path, "r");

	// Now we need to loop through the file and echo out chunks of file data
	while($sent < $filesize)
		{
		echo fread($handle, $download_chunk_size);
		ob_flush();
		$sent += $download_chunk_size;
		}
	}

#Deleting Exiftool temp File:
if ($noattach=="" && $alternative==-1) # Only for downloads (not previews)
	{
	if (file_exists($tmpfile)){delete_exif_tmpfile($tmpfile);}
	}
hook('beforedownloadresourceexit');
exit();

