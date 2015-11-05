<?php
function isValidURL($url)
	{
	// check if the video exists
	global $ytid;
	// YouTube url?
	if (preg_match("/youtu.be\/[a-z1-9.-_]+/", $url))
		{
		preg_match("/youtu.be\/([a-z1-9.-_]+)/", $url, $matches);
		}
	  else if (preg_match("/youtube.com(.+)v=([^&]+)/", $url))
		{
		preg_match("/v=([^&]+)/", $url, $matches);
		}
	if (!empty($matches))
		{
		$ytid = $matches[1];
		if (!$fp = curl_init($url)) return false;
		return true;
		}
	}
function Hookyt2rsViewrenderinnerresourcepreview()
	{
	// Replace preview if it's a valid Youtube URL
	global $ref, $ffmpeg_preview_max_width, $ffmpeg_preview_max_height, $yt2rs_field_id, $ytid;
	$width = $ffmpeg_preview_max_width;
	$height = $ffmpeg_preview_max_height;
	$youtube_url = get_data_by_field($ref, $yt2rs_field_id);
	if ($youtube_url == "" || !isValidURL($youtube_url))
		{
		return false;
		}
	  else
		{
		$youtube_url_emb = "http://www.youtube.com/embed/" . "$ytid";
?>
	<div id="previewimagewrapper"><a style="position:relative;>
       <div class="Picture" id="videoContainer" style="width:<?php
		echo $width
?>px;height:<?php
		echo $height
?>px;">
           <iframe title="YouTube video player" class="youtube-player" type="text/html" width="<?php
		echo $width
?>" height="<?php
		echo $height
?>" src="<?php
		echo $youtube_url_emb; ?>" frameborder="0" frameborder="0" allowFullScreen></iframe>
      </div>
	</div>
<?php
		}
	return true;
	}
?>

<?php
function Hookyt2rsViewreplacedownloadoptions()
	{
	// Replace download options
	global $ref, $yt2rs_field_id, $baseurl_short, $lang;
	$youtube_url = get_data_by_field($ref, $yt2rs_field_id);
	if ($youtube_url !== "" && isValidURL($youtube_url))
		{ ?>
			<table cellpadding="0" cellspacing="0">
				<tr >
					<td>File Information</td>
					<td>File Size </td>
					<td>Options</td>
				</tr>
				<tr class="DownloadDBlend">
					<td><h2>Online Preview</h2><p>Youtube Video</p></td>
					<td>N/A</td>
					<td class="DownloadButton HorizontalWhiteNav"><a href="<?php
		echo $baseurl_short
?>pages/resource_request.php?ref=<?php
		echo urlencode($ref) ?>&k=<?php
		echo getval("k", "") ?>" onClick="return CentralSpaceLoad(this,true);">
				<?php
		echo $lang["action-request"] ?></td>
				</tr>
			</table>
<?php
		return true;
		}
	  else
		{
		return false;
		}
	}
?>

