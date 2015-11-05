<?php
include dirname(__FILE__)."/../../include/db.php";
include dirname(__FILE__)."/../../include/general.php";
include dirname(__FILE__)."/../../include/search_functions.php";
include dirname(__FILE__)."/../../include/resource_functions.php";
include dirname(__FILE__)."/../../include/collections_functions.php";
include dirname(__FILE__)."/../../include/image_processing.php";
$ref=getvalescaped("ref","",true);
if(isset($_COOKIE['user'])){
     header('location:'.$baseurl.'/pages/view.php?ref='.$ref);
}else{
    $api=true;
    include(dirname(__FILE__)."/../../include/authenticate.php");

# Update hit count
update_hitcount($ref);

# Load resource data
$resource=get_resource_data($ref);
if ($resource===false) {exit($lang['resourcenotfound']);}

# Allow alternative configuration settings for this resource type.
resource_type_config_override($resource["resource_type"]);

// get mp3 paths if necessary and set $use_mp3_player switch
if (!(isset($resource['is_transcoding']) && $resource['is_transcoding']==1) && (in_array($resource["file_extension"],$ffmpeg_audio_extensions) || $resource["file_extension"]=="mp3") && $mp3_player){
		$use_mp3_player=true;
	} 
	else {
		$use_mp3_player=false;
	}
if ($use_mp3_player){
	$mp3realpath=get_resource_path($ref,true,"",false,"mp3");
	if (file_exists($mp3realpath)){
		$mp3path=get_resource_path($ref,false,"",false,"mp3");
	}
}	

# Dev feature - regenerate exif data.
if (getval("regenexif","")!="")
	{
	extract_exif_comment($ref,$resource["file_extension"]);
	$resource=get_resource_data($ref,false);
	}

# Load access level
$access=get_resource_access($ref);
hook("beforepermissionscheck");
# check permissions (error message is not pretty but they shouldn't ever arrive at this page unless entering a URL manually)
if ($access==2) 
		{
		exit("This is a confidential resource.");
		}
		
hook("afterpermissionscheck");
		
# Establish if this is a metadata template resource, so we can switch off certain unnecessary features
$is_template=(isset($metawdata_template_resource_type) && $resource["resource_type"]==$metadata_template_resource_type);

$title_field=$view_title_field; 
# If this is a metadata template and we're using field data, change title_field to the metadata template title field
if (isset($metadata_template_resource_type) && ($resource["resource_type"]==$metadata_template_resource_type))
	{
	if (isset($metadata_template_title_field)){
		$title_field=$metadata_template_title_field;
		}
	else {$default_to_standard_title=true;}	
	}

if ($pending_review_visible_to_all && isset($userref) && $resource["created_by"]!=$userref && $resource["archive"]==-1 && !checkperm("e0"))
	{
	# When users can view resources in the 'User Contributed - Pending Review' state in the main search
	# via the $pending_review_visible_to_all option, set access to restricted.
	$access=1;
	}

# If requested, refresh the collection frame (for redirects from saves)
if (getval("refreshcollectionframe","")!="")
	{
	refresh_collection_frame();
	}

# Update the hitcounts for the search keywords (if search specified)
# (important we fetch directly from $_GET and not from a cookie
$usearch=@$_GET["search"];
if ((strpos($usearch,"!")===false) && ($usearch!="")) {update_resource_keyword_hitcount($ref,$usearch);}

# Log this activity
daily_stat("Resource view",$ref);
if ($log_resource_views) {resource_log($ref,'v',0);}

if ($direct_download && !$save_as){	
// check browser to see if forcing save_as 
if (!$direct_download_allow_opera  && strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"opera")!==false) {$save_as=true;}
if (!$direct_download_allow_ie7 && strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"msie 7.")!==false) {$save_as=true;}	
if (!$direct_download_allow_ie8 && strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),"msie 8.")!==false) {$save_as=true;}	
}

# Show the header/sidebar
include dirname(__FILE__)."/../../include/header.php";

if ($metadata_report && isset($exiftool_path))
	{
	?>
	<script src="<?php echo $baseurl_short?>lib/js/metadata_report.js" type="text/javascript"></script>
	<?php
	}

if ($direct_download && !$save_as){
?>
<iframe id="dlIFrm" frameborder=0 scrolling="auto" <?php if ($debug_direct_download){?>width="600" height="200" style="display:block;"<?php } else { ?>style="display:none"<?php } ?>> This browser can not use IFRAME. </iframe>
<?php }

if($resource_contact_link && $k=="")
		{?>
		<script>
		function showContactBox(){
				
				if(jQuery('#contactadminbox').length)
					{
					jQuery('#contactadminbox').slideDown();
					return false;
					}
				
				jQuery.ajax({
						type: "GET",
						url: baseurl_short+"pages/ajax/contactadmin.php?ref="+<?php echo $ref ?>+"&insert=true&ajax=true",
						success: function(html){
								jQuery('#RecordDownload li:last-child').after(html);
								document.getElementById('messagetext').focus();
								},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							alert('<?php echo $lang["error"] ?>\n' + textStatus);
							}
						});
				}				
		</script>
		<?php
		}
		
hook("pageevaluation");

# Load resource field data
$multi_fields = FALSE;
# Related resources with tabs need all fields (even the ones from other resource types):
if(isset($related_type_show_with_data)) {
	$multi_fields = TRUE;
}

$fields=get_resource_field_data($ref,$multi_fields,!hook("customgetresourceperms"),-1,$k!="",$use_order_by_tab_view);
// Get tab names and order from fields in order to know which one is the last tab
if(isset($related_type_show_with_data)) {
	foreach ($fields as $field) {
		$fields_tab_names[] = $field['tab_name'];
		$resources_per_tab_name[$field['tab_name']][] = $field['ref'];
	}
	$fields_tab_names = array_values(array_unique($fields_tab_names));
}

//Check if we want to use a specified field as a caption below the preview
if(isset($display_field_below_preview) && is_int($display_field_below_preview))
	{
	$df=0;
	foreach ($fields as $field)
		{
		if($field["fref"]==$display_field_below_preview)
			{
			$displaycondition=check_view_display_condition($fields,$df);
			if($displaycondition)
				{
				$previewcaption=$fields[$df];
				// Remove from the array so we don't display it twice
				unset($fields[$df]);
				//Reorder array 
				$fields=array_values($fields);				
				}
			}
		$df++;			
		}
	}

# Load edit access level (checking edit permissions - e0,e-1 etc. and also the group 'edit filter')
$edit_access=get_edit_access($ref,$resource["archive"],$fields,$resource);
if ($k!="") {$edit_access=0;}

function check_view_display_condition($fields,$n)	
	{
	#Check if field has a display condition set
	$displaycondition=true;
	if ($fields[$n]["display_condition"]!="")
		{
		//echo $fields[$n]["display_condition"] . "<br>";
		$fieldstocheck=array(); #' Set up array to use in jQuery script function
		$s=explode(";",$fields[$n]["display_condition"]);
		$condref=0;
		foreach ($s as $condition) # Check each condition
			{
			$displayconditioncheck=false;
			$s=explode("=",$condition);
			for ($cf=0;$cf<count($fields);$cf++) # Check each field to see if needs to be checked
				{
				if ($s[0]==$fields[$cf]["name"]) # this field needs to be checked
					{					
					$checkvalues=$s[1];
					$validvalues=explode("|",strtoupper($checkvalues));
					$v=trim_array(explode(",",strtoupper($fields[$cf]["value"])));
					foreach ($validvalues as $validvalue)
						{
						if (in_array($validvalue,$v)) {$displayconditioncheck=true;} # this is  a valid value						
						}
					if (!$displayconditioncheck) {$displaycondition=false;}					
					}
					
				} # see if next field needs to be checked
							
			$condref++;
			} # check next condition	
		
		}
	return $displaycondition;
	}
	
function display_field_data($field,$valueonly=false,$fixedwidth=452)
	{
	global $ref, $fieldcount, $tabcount, $show_expiry_warning, $access, $tabname, $search, $extra, $lang, $used_tab_names, $related_type_show_with_data, $show_default_related_resources, $fields_tab_names, $resources_per_tab_name;
	$value=$field["value"];

	$resource_type_tab_names = sql_array('SELECT tab_name as value FROM resource_type', '');
	$resource_type_tab_names = array_filter($resource_type_tab_names);
	
	$modified_field=hook("beforeviewdisplayfielddata_processing","",array($field));
	if($modified_field){
		$field=$modified_field;
	}
	
	# Handle expiry fields
	if (!$valueonly && $field["type"]==6 && $value!="" && $value<=date("Y-m-d H:i") && $show_expiry_warning) 
		{
		$extra.="<div class=\"RecordStory\"> <h1>" . $lang["warningexpired"] . "</h1><p>" . $lang["warningexpiredtext"] . "</p><p id=\"WarningOK\"><a href=\"#\" onClick=\"document.getElementById('RecordDownload').style.display='block';document.getElementById('WarningOK').style.display='none';\">" . $lang["warningexpiredok"] . "</a></p></div><style>#RecordDownload {display:none;}</style>";
		}
	
	if (($value!="") && ($value!=",") && ($field["display_field"]==1) && ($access==0 || ($access==1 && !$field["hide_when_restricted"])))
		{
		if (!$valueonly)
			{$title=htmlspecialchars(str_replace("Keywords - ","",$field["title"]));}
		else {$title="";}
		//if ($field["type"]==4 || $field["type"]==6) {$value=NiceDate($value,false,true);}

		# Value formatting
		if (($field["type"]==2) || ($field["type"]==7) || ($field["type"]==9))
			{$i18n_split_keywords =true;}
		else 	{$i18n_split_keywords =false;}
		$value=i18n_get_translated($value,$i18n_split_keywords );
		if (($field["type"]==2) || ($field["type"]==3) || ($field["type"]==7) || ($field["type"]==9)) {$value=TidyList($value);}
		
		// Don't display the comma for radio buttons:
		if($field['type'] == 12) {
			$value = str_replace(',', '', $value);
		}
		
		$value_unformatted=$value; # store unformatted value for replacement also

		if ($field["type"]!=8 || ($field["type"]==8 && $value == strip_tags($value))) # Do not convert HTML formatted fields (that are already HTML) to HTML. Added check for extracted fields set to ckeditor that have not yet been edited.
			{
			$value=nl2br(htmlspecialchars($value));
			}
		
		$modified_value = hook('display_field_modified_value', '', array($field));
		if($modified_value) {		
			$value = $modified_value['value'];
		}

		# draw new tab panel?
		if (!$valueonly && ($tabname!=$field["tab_name"]) && ($fieldcount>0))
			{

				$resource_type_tab_names = sql_array('SELECT tab_name as value FROM resource_type', '');
				$resource_type_tab_names = array_filter($resource_type_tab_names);

				# Display related resources on this tab, if set:
				if(isset($related_type_show_with_data)) {

					# NOTE: the resource type tab name and the current tab you are on need to be the same:
					if(in_array($tabname, $resource_type_tab_names)) {

						if(($key = array_search($tabname, $resource_type_tab_names)) !== false) {

							# Fields with display template should be rendered before the related resources list:
							echo $extra;
							$extra = '';
							
							include '../include/related_resources.php';
							unset($resource_type_tab_names[$key]);

							$show_default_related_resources = FALSE;
						
						}

					}

				}


			$tabcount++;
			# Also display the custom formatted data $extra at the bottom of this tab panel.
			?><div class="clearerleft"> </div><?php echo $extra; ?></div></div>
                        <div class="TabbedPanel StyledTabbedPanel" style="display:none;" id="tab<?php echo $tabcount?>"><div><?php
			$extra="";
			}
		$tabname=$field["tab_name"];
		$used_tab_names[] .= $tabname;
		$used_tab_names = array_unique($used_tab_names);
		$fieldcount++;
		if (!$valueonly && trim($field["display_template"])!="")
			{
			# Process the value using a plugin
			$plugin="../plugins/value_filter_" . $field["name"] . ".php";
			if ($field['value_filter']!=""){
				eval($field['value_filter']);
			}
			else if (file_exists($plugin)) {include $plugin;}
			else if ($field["type"]==4 || $field["type"]==6) { 
				$value=NiceDate($value,false,true);
			}
			
			# Highlight keywords
			$value=highlightkeywords($value,$search,$field["partial_index"],$field["name"],$field["keywords_index"]);

			# Use a display template to render this field
			$template=$field["display_template"];
			$template=str_replace("[title]",$title,$template);
			$template=str_replace("[value]",$value,$template);
			$template=str_replace("[value_unformatted]",$value_unformatted,$template);
			$template=str_replace("[ref]",$ref,$template);
			$extra.=$template;
			}
		else
			{
			#There is a value in this field, but we also need to check again for a current-language value after the i18n_get_translated() function was called, to avoid drawing empty fields
			if ($value!=""){
				# Draw this field normally.				
				
					# value filter plugin should be used regardless of whether a display template is used.
					$plugin="../plugins/value_filter_" . $field["name"] . ".php";
					if ($field['value_filter']!=""){
						eval($field['value_filter']);
					}
					else if (file_exists($plugin)) {include $plugin;}
					else if ($field["type"]==4 || $field["type"]==6) { 
						$value=NiceDate($value,false,true);
					}
				
				# Highlight keywords
				$value=highlightkeywords($value,$search,$field["partial_index"],$field["name"],$field["keywords_index"]);
				?><div <?php if (!$valueonly){echo "class=\"itemNarrow\""; } elseif (isset($fixedwidth)) {echo "style=\"width:" . $fixedwidth . "px\""; } ?>>
				<h3><?php echo $title?></h3><p><?php echo $value?></p></div><?php
				}
			}
		} else if(isset($related_type_show_with_data) && ($tabname!=$field['tab_name']) && in_array($field['tab_name'], $resource_type_tab_names) && !in_array($field['tab_name'], $used_tab_names)) {

			# Display related resources on this tab, if set:
			if(isset($related_type_show_with_data)) {

				# NOTE: the resource type tab name and the current tab you are on need to be the same:
				if(in_array($field['tab_name'], $resource_type_tab_names)) {

					if(($key = array_search($field['tab_name'], $resource_type_tab_names)) !== false) {

						# Fields with display template should be rendered before the related resources list:
						echo $extra;
						$extra = '';
						
						include '../include/related_resources.php';
						unset($resource_type_tab_names[$key]);

						$show_default_related_resources = FALSE;
					
					}
					
					$tabcount++;
					# Also display the custom formatted data $extra at the bottom of this tab panel. ?>
					<div class="clearerleft"></div>
					<?php echo $extra; ?>
					</div>
					</div>
					<div class="TabbedPanel StyledTabbedPanel" style="display:none;" id="tab<?php echo $tabcount?>"><div>
					<?php	
					$extra="";

					$tabname = $field['tab_name'];
					$used_tab_names[] = $tabname;
					$used_tab_names = array_unique($used_tab_names);
					$fieldcount++;

				}

			}

		} else if(isset($related_type_show_with_data) && $tabname == end($fields_tab_names) && $field['ref'] == end($resources_per_tab_name[$tabname])) {
			
			# NOTE: the resource type tab name and the current tab you are on need to be the same:
			if(in_array($field['tab_name'], $resource_type_tab_names)) {

				if(($key = array_search($field['tab_name'], $resource_type_tab_names)) !== FALSE) {

					# Fields with display template should be rendered before the related resources list:
					echo $extra;
					$extra = '';
					
					include '../include/related_resources.php';
					unset($resource_type_tab_names[$key]);

					$show_default_related_resources = FALSE;
				
				}
				
				$tabcount++;
				$tabname = $field['tab_name'];
				$used_tab_names[] = $tabname;
				$used_tab_names = array_unique($used_tab_names);
				$fieldcount++;

			}

		}
	
	}

// Add custom CSS for external users: 
if($k !='' && $custom_stylesheet_external_share) {
    $css_path = dirname(__FILE__) . '/..' . $custom_stylesheet_external_share_path;
    if(file_exists($css_path)) {
        echo '<link href="' . $baseurl . $custom_stylesheet_external_share_path . '" rel="stylesheet" type="text/css" media="screen,projection,print" />';
    }
}
if ($view_panels) {
?>
<script type="text/javascript">


jQuery(document).ready(function () {		
    
	
    
    jQuery("#Metadata").appendTo("#Panel1");
    jQuery("#Metadata").addClass("TabPanel");
    
	
	jQuery("#CommentsPanelHeaderRowTitle").children(".Title").attr("panel", "Comments").appendTo("#Titles1");
	jQuery("#CommentsPanelHeaderRowTitle").remove();
	jQuery("#CommentsPanelHeaderRowPolicyLink").css("width","300px").css("float","right");
	removePanel=jQuery("#Comments").parents(".RecordBox");
	jQuery("#Comments").appendTo("#Panel1").addClass("TabPanel").hide();
	removePanel.remove();

    jQuery("#RelatedResources").children().children(".Title").attr("panel", "RelatedResources").addClass("Selected").appendTo("#Titles2");
    removePanel=jQuery("#RelatedResources").parents(".RecordBox");
    jQuery("#RelatedResources").appendTo("#Panel2").addClass("TabPanel");
    removePanel.remove();
    

    jQuery("#SearchSimilar").children().children(".Title").attr("panel", "SearchSimilar").appendTo("#Titles2");
    removePanel=jQuery("#SearchSimilar").parents(".RecordBox");
    jQuery("#SearchSimilar").appendTo("#Panel2").addClass("TabPanel").hide();
    removePanel.remove();
    // if there are no related resources
    if (jQuery("#RelatedResources").length==0) {
        jQuery("#SearchSimilar").show();
        jQuery("div[panel='SearchSimilar']").addClass("Selected"); 
    }    
    
    // if there are no collections and themes
    if (jQuery("#resourcecollections").is(':empty')) {
       jQuery("div[panel='CollectionsThemes']").addClass("Selected"); 
       jQuery("#CollectionsThemes").show(); 
    }
    
    jQuery(".ViewPanelTitles").children(".Title").click(function(){
    // function to switch tab panels
        jQuery(this).parent().parent().children(".TabPanel").hide();
        jQuery(this).parent().children(".Title").removeClass("Selected");
        jQuery(this).addClass("Selected");
        jQuery("#"+jQuery(this).attr("panel")).css("position", "relative").css("left","0px");
        jQuery("#"+jQuery(this).attr("panel")).show();
        if (jQuery(this).attr("panel")=="Comments") {
        jQuery("#CommentsContainer").load(
        	"../pages/ajax/comments_handler.php?ref=<?php echo $ref;?>", 
        	function() {
        	if (jQuery.type(jQuery(window.location.hash)[0])!=="undefined")				
        		jQuery(window.location.hash)[0].scrollIntoView();
        	}						
        );	
        }
    });
    
   
});

</script>
<?php } ?>
<!--Panel for record and details-->
<div class="RecordBox">
<div class="RecordPanel">

<div class="RecordHeader">
<?php if (!hook("renderinnerresourceheader")) { ?>


<?php 
# Check if actually coming from a search, but not if a numeric search and config_search_for_number is set or if this is a direct request e.g. ?r=1234.
if (isset($_GET["search"]) && !($config_search_for_number && is_numeric($usearch))) { 
$counter = 0;
//$lastresource=get_max_resource_ref();
if(substr($_GET["search"],0,11)=="!collection"){
$frst = substr($_GET["search"],11);
$lastresource = sql_query("SELECT resource FROM collection_resource WHERE resource > 0 AND collection = $frst ORDER BY sortorder ASC LIMIT 1"); 
$lastresource = $lastresource[0]['resource'];
$getfirstresource=sql_query("SELECT resource FROM collection_resource WHERE resource > 0 AND collection = $frst ORDER BY sortorder DESC LIMIT 1");
$firstresource=$getfirstresource[0]['resource'];
}else{
$lastresource = sql_query("SELECT ref FROM resource WHERE ref > 0 AND archive = 0 ORDER BY ref DESC LIMIT 1");
$lastresource = $lastresource[0]['ref'];
$getfirstresource=sql_query("SELECT ref FROM resource WHERE ref > 0 AND archive = 0 ORDER BY ref ASC LIMIT 1");
$firstresource=$getfirstresource[0]['ref'];
}
?>
<div class="backtoresults">
<?php if($ref != $lastresource){
?>
<a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo urlencode($lastresource)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&amp;k=<?php echo urlencode($k) ?>&amp;go=previous&amp;curpos=<?php echo $curpos ?>&amp;<?php echo hook("nextpreviousextraurl") ?>"onClick="return CentralSpaceLoad(this);">&lt;&nbsp;First</a>
<a class="prevLink" href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&amp;k=<?php echo urlencode($k) ?>&amp;go=previous&amp;curpos=<?php echo $curpos ?>&amp;<?php echo hook("nextpreviousextraurl") ?>"onClick="return CentralSpaceLoad(this);">&lt;&nbsp;<?php echo $lang["previousresult"]?></a>
<?php
}
if (!hook("viewallresults")) {
?>
|
<a class="upLink" href="<?php echo $baseurl_short?>pages/search.php?search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&amp;go=up&amp;k=<?php echo urlencode($k)?>" onClick="return CentralSpaceLoad(this);"><?php echo $lang["viewallresults"]?></a>
<?php } ?>
|
<?php if ($ref != $firstresource){?>
<a class="nextLink" href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&amp;k=<?php echo urlencode($k)?>&amp;go=next&amp;curpos=<?php echo $curpos ?>"onClick="return CentralSpaceLoad(this);"><?php echo $lang["nextresult"]?>&nbsp;&gt;</a>
<a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo urlencode($firstresource)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&amp;k=<?php echo urlencode($k)?>&amp;go=next&amp;curpos=<?php echo $curpos ?>"onClick="return CentralSpaceLoad(this);">Last</a>

<?php }?>
</div>
<?php } ?>


<h1><?php hook("beforeviewtitle");?><?php
# Display title prefix based on workflow state.
if (!hook("replacetitleprefix","",array($resource["archive"]))) { switch ($resource["archive"])
	{
	case -2:
	?><span class="ResourcePendingSubmissionTitle"><?php echo $lang["status-2"]?>:</span>&nbsp;<?php
	break;
	case -1:
	?><span class="ResourcePendingReviewTitle"><?php echo $lang["status-1"]?>:</span>&nbsp;<?php
	break;
	case 1:
	?><span class="ArchiveResourceTitle"><?php echo $lang["status1"]?>:</span>&nbsp;<?php
	break;
	case 2:
	?><span class="ArchiveResourceTitle"><?php echo $lang["status2"]?>:</span>&nbsp;<?php
	break;
	case 3:
	?><span class="DeletedResourceTitle"><?php echo $lang["status3"]?>:</span>&nbsp;<?php
	break;
	} }
if (!hook("replaceviewtitle")){ echo htmlspecialchars(i18n_get_translated(get_data_by_field($resource['ref'],$title_field))); } /* end hook replaceviewtitle */  
?>&nbsp;</h1>
<?php } /* End of renderinnerresourceheader hook */ ?>
</div>

<?php if (!hook("replaceresourceistranscoding")){
	if (isset($resource['is_transcoding']) && $resource['is_transcoding']==1) { ?><div class="PageInformal"><?php echo $lang['resourceistranscoding']?></div><?php }
	} //end hook replaceresourceistrancoding ?>

<?php hook("renderbeforeresourceview"); 
$download_multisize=true;
?>

<div class="RecordResource">
<?php if (!hook("renderinnerresourceview")) { ?>
<?php if (!hook("replacerenderinnerresourcepreview")) { ?>
<?php if (!hook("renderinnerresourcepreview")) { ?>
<?php


$flvfile=get_resource_path($ref,true,"pre",false,$ffmpeg_preview_extension);
if (!file_exists($flvfile)) {$flvfile=get_resource_path($ref,true,"",false,$ffmpeg_preview_extension);}
if (file_exists("../players/type" . $resource["resource_type"] . ".php"))
	{
	include "../players/type" . $resource["resource_type"] . ".php";
	}
elseif (!(isset($resource['is_transcoding']) && $resource['is_transcoding']==1) && file_exists($flvfile) && (strpos(strtolower($flvfile),".".$ffmpeg_preview_extension)!==false))
	{
	# Include the Flash player if an FLV file exists for this resource.
	$download_multisize=false;
	?>
	<div id="previewimagewrapper">
	<?php 
    if(!hook("customflvplay"))
	    {
        include "flv_play.php";
	    }
	if(isset($previewcaption))
		{				
		display_field_data($previewcaption, true);
		}
	?></div><?php
	
	# If configured, and if the resource itself is not an FLV file (in which case the FLV can already be downloaded), then allow the FLV file to be downloaded.
	if ($flv_preview_downloadable && $resource["file_extension"]!="flv") {$flv_download=true;}
	}
elseif ($use_mp3_player && file_exists($mp3realpath) && hook("custommp3player")){}	
elseif ($resource['file_extension']=="swf" && $display_swf){
	$swffile=get_resource_path($ref,true,"",false,"swf");
	if (file_exists($swffile))
		{?>
		<div id="previewimagewrapper">
		<?php include "swf_play.php"; 
		if(isset($previewcaption))
			{
			echo "<div class=\"clearerleft\"> </div>";					
			display_field_data($previewcaption, true);
			}
		?>
		</div><?php
		}
	}
elseif ($resource["has_image"]==1)
	{
	$use_watermark=check_use_watermark();
	$imagepath=get_resource_path($ref,true,"pre",false,$resource["preview_extension"],-1,1,$use_watermark);
	if (!file_exists($imagepath))
		{
		$imageurl=get_resource_path($ref,false,"thm",false,$resource["preview_extension"],-1,1,$use_watermark);
		}
	else
		{
		$imageurl=get_resource_path($ref,false,"pre",false,$resource["preview_extension"],-1,1,$use_watermark);
		}
	
	?>
	<div id="previewimagewrapper"><a style="position:relative;" class="enterLink" id="previewimagelink" href="<?php echo $baseurl_short?>pages/preview.php?ref=<?php echo urlencode($ref)?>&amp;ext=<?php echo $resource["preview_extension"]?>&amp;k=<?php echo urlencode($k)?>&<?php echo hook("previewextraurl") ?>" title="<?php echo $lang["fullscreenpreview"]?>">
	<?php
	if (file_exists($imagepath))
		{ 
		?><img src="<?php echo $imageurl?>" alt="<?php echo $lang["fullscreenpreview"]?>" class="Picture" GALLERYIMG="no" id="previewimage" /><?php 
		} 
	?><?php hook("aftersearchimg","",array($ref))?></a><?php
	if(isset($previewcaption))
		{
		echo "<div class=\"clearerleft\"> </div>";	
		@list($pw) = @getimagesize($imagepath);
		display_field_data($previewcaption, true, $pw);
		}
	hook("previewextras");
	?></div><?php 
	if ($image_preview_zoom)
		{ 
		$previewurl=get_resource_path($ref,false,"scr",false,$resource["preview_extension"],-1,1,$use_watermark);		
		?>
		<script>
		jQuery(document).ready(function(){
			jQuery('#previewimage')
			        .wrap('<span style="display:inline-block"></span>')
			        .css('display', 'block')
			        .parent()
			        .zoom({url: '<?php echo $previewurl ?>' });
			});
		</script>
		<?php
		}
	}
else
	{
	?>
	<div id="previewimagewrapper">
	<img src="<?php echo $baseurl ?>/gfx/<?php echo get_nopreview_icon($resource["resource_type"],$resource["file_extension"],false)?>" alt="" class="Picture" style="border:none;" id="previewimage" />
	<?php
	if(isset($previewcaption))
		{	
		echo "<div class=\"clearerleft\"> </div>";	
		display_field_data($previewcaption, true);
		}
	hook("previewextras");
	?></div><?php	
	}

?>
<?php } /* End of renderinnerresourcepreview hook */ ?>
<?php } /* End of replacerenderinnerresourcepreview hook */ ?>
<?php



hook("renderbeforerecorddownload");

if ($download_summary) {include "../include/download_summary.php";}
?>
<?php if (!hook("renderresourcedownloadspace")) { ?>
<div class="RecordDownload" id="RecordDownload">
<div class="RecordDownloadSpace">
<?php if (!hook("renderinnerresourcedownloadspace")) { 
	hook("beforeresourcetoolsheader");
?>
<h2 id="resourcetools"><?php echo $lang["resourcetools"]?></h2>

<?php 

# DPI calculations
function compute_dpi($width, $height, &$dpi, &$dpi_unit, &$dpi_w, &$dpi_h)
	{
	global $lang, $imperial_measurements,$sizes,$n,$view_default_dpi;
	
	if (isset($sizes[$n]['resolution'])&& $sizes[$n]['resolution']!=0) { $dpi=$sizes[$n]['resolution']; }
	else if (!isset($dpi) || $dpi==0) { $dpi=$view_default_dpi; }

	if (((isset($sizes[$n]['unit']) && trim(strtolower($sizes[$n]['unit']))=="inches")) || $imperial_measurements)
		{
		# Imperial measurements
		$dpi_unit=$lang["inch-short"];
		$dpi_w=round($width/$dpi,1);
		$dpi_h=round($height/$dpi,1);
		}
	else
		{
		$dpi_unit=$lang["centimetre-short"];
		$dpi_w=round(($width/$dpi)*2.54,1);
		$dpi_h=round(($height/$dpi)*2.54,1);
		}
	}

# MP calculation
function compute_megapixel($width, $height)
	{
	return round(($width * $height) / 1000000, 2);
	}

function get_size_info($size, $originalSize = null)
{
	global $lang;
	global $ffmpeg_supported_extensions;
	
	$newWidth = intval($size['width']);
	$newHeight = intval($size['height']);

	if ($originalSize != null && $size !== $originalSize)
		{
		// Compute actual pixel size
		$imageWidth = $originalSize['width'];
		$imageHeight = $originalSize['height'];
		if ($imageWidth > $imageHeight)
			{
			// landscape
			$newWidth = $size['width'];
			$newHeight = round(($imageHeight * $newWidth + $imageWidth - 1) / $imageWidth);
			}
		else
			{
			// portrait or square
			$newHeight = $size['height'];
			$newWidth = round(($imageWidth * $newHeight + $imageHeight - 1) / $imageHeight);
			}
		}

	$output='<p>' . $newWidth . " x " . $newHeight . " " . $lang["pixels"];

	$mp=compute_megapixel($newWidth, $newHeight);
	if ($mp>=0)
		{
		$output.=" (" . $mp . " " . $lang["megapixel-short"] . ")";
		}

	$output.='</p>';

	if (!isset($size['extension']) || !in_array(strtolower($size['extension']), $ffmpeg_supported_extensions))
	    {
		if (!hook("replacedpi")){	
	    # Do DPI calculation only for non-videos
	    compute_dpi($newWidth, $newHeight, $dpi, $dpi_unit, $dpi_w, $dpi_h);
	    $output.= '<p>' . $dpi_w . " " . $dpi_unit . " x " . $dpi_h . " " . $dpi_unit . " " . $lang["at-resolution"]
		   . " " . $dpi ." " . $lang["ppi"] . '</p>';
		}
	    }

	return $output;
}

# Get display price for basket request modes
function get_display_price($ref, $size)
{
	global $pricing, $currency_symbol;

	$price_id=$size["id"];
	if ($price_id=="") { $price_id="hpr"; }

	$price=999; # If price cannot be found
	if (array_key_exists($price_id,$pricing)) { $price=$pricing[$price_id]; }

	# Pricing adjustment hook (for discounts or other price adjustments plugin).
	$priceadjust=hook("adjust_item_price","",array($price,$ref,$size["id"]));
	if ($priceadjust!==false) { $price=$priceadjust; }

	return $currency_symbol . " " . number_format($price,2);
}

function make_download_preview_link($ref, $size, $label)
	{
	global $direct_link_previews_filestore, $baseurl_short;

	if ($direct_link_previews_filestore)
		$direct_link="" . get_resource_path($ref,false,$size['id'],false,$size['extension']);
	else
		$direct_link=$baseurl_short."pages/download.php?direct=1&amp;ref=$ref&amp;size=" . $size['id'] . "&amp;ext=" . $size['extension'];

	return "<a href='$direct_link' target='dl_window_$ref'>$label</a>";
	}

function add_download_column($ref, $size_info, $downloadthissize)
	{
	global $save_as, $direct_download, $order_by, $lang, $baseurl_short, $baseurl, $k, $search, $request_adds_to_collection, $offset, $archive, $sort;
	if ($downloadthissize)
		{
		?><td class="DownloadButton"><?php
		if (!$direct_download || $save_as)
			{
			global $size_info_array;
			$size_info_array = $size_info;
			if(!hook("downloadbuttonreplace"))
				{
				?><a id="downloadlink" <?php
				if (!hook("downloadlink","",array("ref=" . $ref . "&k=" . $k . "&size=" . $size_info["id"]
						. "&ext=" . $size_info["extension"])))
					{
					?>href="<?php echo $baseurl ?>/pages/terms.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php
							echo urlencode($search) ?>&amp;k=<?php echo urlencode($k)?>&amp;url=<?php
							echo urlencode("pages/download_progress.php?ref=" . $ref . "&size=" . $size_info["id"]
									. "&ext=" . $size_info["extension"] . "&k=" . $k . "&search=" . urlencode($search)
									. "&offset=" . $offset . "&archive=" . $archive . "&sort=".$sort."&order_by="
									. urlencode($order_by))?>"<?php
					}
					?> onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["action-download"]?></a><?php
				}
			}
		else
			{
			?><a id="downloadlink" href="#" onclick="directDownload('<?php
					echo $baseurl_short?>pages/download_progress.php?ref=<?php echo urlencode($ref) ?>&size=<?php
					echo $size_info['id']?>&ext=<?php echo $size_info['extension']?>&k=<?php
					echo urlencode($k)?>')"><?php echo $lang["action-download"]?></a><?php
			}
			unset($size_info_array);
			?></td><?php
		}
	else if (checkperm("q"))
		{
		if (!hook("resourcerequest"))
			{
			?><td class="DownloadButton"><?php
			if ($request_adds_to_collection)
				{
				echo add_to_collection_link($ref,$search,"alert('" . $lang["requestaddedtocollection"] . "');",$size_info["id"]);
				}
			else
				{
				?><a href="<?php echo $baseurl_short?>pages/resource_request.php?ref=<?php echo urlencode($ref)?>&k=<?php echo getval("k","")?>" onClick="return CentralSpaceLoad(this,true);"><?php
				}
			echo $lang["action-request"]?></a></td><?php
		}
		}
	else
		{
		# No access to this size, and the request functionality has been disabled. Show just 'restricted'.
		?><td class="DownloadButton DownloadDisabled"><?php echo $lang["access1"]?></td><?php
		}
	}


# Look for a viewer to handle the right hand panel. If not, display the standard photo download / file download boxes.
if (file_exists("../viewers/type" . $resource["resource_type"] . ".php"))
	{
	include "../viewers/type" . $resource["resource_type"] . ".php";
	}
elseif (hook("replacedownloadoptions"))
	{
	}
elseif ($is_template)
	{
	
	}
else
	{ 
	?>
<table cellpadding="0" cellspacing="0">
<tr <?php hook("downloadtableheaderattributes")?>>
<?php
$table_headers_drawn=false;
$nodownloads=false;$counter=0;$fulldownload=false;
$showprice=$userrequestmode==2 || $userrequestmode==3;
hook("additionalresourcetools");
if ($resource["has_image"]==1 && $download_multisize)
	{
	# Restricted access? Show the request link.

	# List all sizes and allow the user to download them
	$sizes=get_image_sizes($ref,false,$resource["file_extension"]);
	for ($n=0;$n<count($sizes);$n++)
		{
		# Is this the original file? Set that the user can download the original file
		# so the request box does not appear.
		$fulldownload=false;
		if ($sizes[$n]["id"]=="") {$fulldownload=true;}
		
		$counter++;

		# Should we allow this download?
		# If the download is allowed, show a download button, otherwise show a request button.
		$downloadthissize=resource_download_allowed($ref,$sizes[$n]["id"],$resource["resource_type"]);

		$headline=$sizes[$n]['id']=='' ? str_replace_formatted_placeholder("%extension", $resource["file_extension"], $lang["originalfileoftype"])
				: $sizes[$n]["name"];
		$newHeadline=hook('replacesizelabel', '', array($ref, $resource, $sizes[$n]));
		if (!empty($newHeadline))
			$headline=$newHeadline;

		if ($direct_link_previews && $downloadthissize)
			$headline=make_download_preview_link($ref, $sizes[$n],$headline);
		if ($hide_restricted_download_sizes && !$downloadthissize && !checkperm("q"))
			continue;
		if(!hook("replacedownloadspacetableheaders")){
			if ($table_headers_drawn==false) { ?>
				<td><?php echo $lang["fileinformation"]?></td>
				<td><?php echo $lang["filesize"]?></td>
				<?php if ($showprice) { ?><td><?php echo $lang["price"] ?></td><?php } ?>
				<td class="textcenter"><?php echo $lang["options"]?></td>
				</tr>
 				<?php
				$table_headers_drawn=true;
			} 
		} # end hook("replacedownloadspacetableheaders")?>
		<tr class="DownloadDBlend" id="DownloadBox<?php echo $n?>">
		<td class="DownloadFileName"><h2><?php echo $headline?></h2><?php
		if (is_numeric($sizes[$n]["width"]))
			{
			echo get_size_info($sizes[$n]);
			}
		?></td><td class="DownloadFileSize"><?php echo $sizes[$n]["filesize"]?></td>

		<?php if ($showprice) {
			?><td><?php echo get_display_price($ref, $sizes[$n]) ?></td>
		<?php } ?>

		<?php

		add_download_column($ref, $sizes[$n], $downloadthissize);
		?>
		</tr>
		<?php
		if (!hook("previewlinkbar")){
			if ($downloadthissize && $sizes[$n]["allow_preview"]==1)
				{ 
				# Add an extra line for previewing
				?> 
				<tr class="DownloadDBlend"><td class="DownloadFileName"><h2><?php echo $lang["preview"]?></h2><p><?php echo $lang["fullscreenpreview"]?></p></td><td class="DownloadFileSize"><?php echo $sizes[$n]["filesize"]?></td>
				<?php if ($userrequestmode==2 || $userrequestmode==3) { ?><td></td><?php } # Blank spacer column if displaying a price above (basket mode).
				?>
				<td class="DownloadButton">
				<a class="enterLink" id="previewlink" href="<?php echo $baseurl_short?>pages/preview.php?ref=<?php echo urlencode($ref)?>&amp;ext=<?php echo $resource["file_extension"]?>&amp;k=<?php echo urlencode($k)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>&<?php echo hook("previewextraurl") ?>"><?php echo $lang["action-view"]?></a>
				</td>
				</tr>
				<?php
				} 
			}
		} /* end hook previewlinkbar */
	}
elseif (strlen($resource["file_extension"])>0 && !($access==1 && $restricted_full_download==false))
	{
	# Files without multiple download sizes (i.e. no alternative previews generated).
	$counter++;
	$path=get_resource_path($ref,true,"",false,$resource["file_extension"]);
	if (file_exists($path))
		{
		hook("beforesingledownloadsizeresult");
			if(!hook("origdownloadlink")):
		?>
		<tr class="DownloadDBlend">
		<td class="DownloadFileName"><h2><?php echo (isset($original_download_name)) ? str_replace_formatted_placeholder("%extension", $resource["file_extension"], $original_download_name, true) : str_replace_formatted_placeholder("%extension", $resource["file_extension"], $lang["originalfileoftype"]); ?></h2></td>
		<td class="DownloadFileSize"><?php echo formatfilesize(filesize_unlimited($path))?></td>
		<td class="DownloadButton">
		<?php if (!$direct_download || $save_as){ ?>
			<a <?php if (!hook("downloadlink","",array("ref=" . $ref . "&k=" . $k . "&ext=" . $resource["file_extension"] ))) { ?>href="<?php echo $baseurl_short?>pages/terms.php?ref=<?php echo urlencode($ref)?>&k=<?php echo urlencode($k)?>&search=<?php echo $search ?>&url=<?php echo urlencode("pages/download_progress.php?ref=" . $ref . "&ext=" . $resource["file_extension"] . "&k=" . $k . "&search=" . urlencode($search) . "&offset=" . $offset . "&archive=" . $archive . "&sort=".$sort."&order_by=" . urlencode($order_by))?>"<?php } ?> onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["action-download"] ?></a>
		<?php } else { ?>
			<a href="#" onclick="directDownload('<?php echo $baseurl_short?>pages/download_progress.php?ref=<?php echo urlencode($ref)?>&ext=<?php echo $resource['file_extension']?>&k=<?php echo urlencode($k)?>')"><?php echo $lang["action-download"]?></a>
		<?php } // end if direct_download ?>
		</td>
		</tr>
		<?php
			endif; # hook origdownloadlink
		}
	} 
else
	{
	$nodownloads=true;
	}
	
if (($nodownloads || $counter==0) && !checkperm("T" . $resource["resource_type"] . "_"))
	{
	hook("beforenodownloadresult");
	# No file. Link to request form.
	?>
	<tr class="DownloadDBlend">
	<td class="DownloadFileName"><h2><?php echo ($counter==0)?$lang["offlineresource"]:$lang["access1"]?></h2></td>
	<td class="DownloadFileSize"><?php echo $lang["notavailableshort"]?></td>

	<?php if (checkperm("q"))
		{
		?>
		<?php if(!hook("resourcerequest")){?>
		<td class="DownloadButton"><a href="<?php echo $baseurl_short?>pages/resource_request.php?ref=<?php echo urlencode($ref)?>&k=<?php echo urlencode($k) ?>"  onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["action-request"]?></a></td>
		<?php } ?>
		<?php
		}
	else
		{
		?>
		<td class="DownloadButton DownloadDisabled"><?php echo $lang["access1"]?></td>
		<?php
		}
	?>
	</tr>
	<?php
	}
	
if (isset($flv_download) && $flv_download)
	{
	# Allow the FLV preview to be downloaded. $flv_download is set when showing the FLV preview video above.
	?>
	<tr class="DownloadDBlend">
	<td class="DownloadFileName"><h2><?php echo (isset($ffmpeg_preview_download_name)) ? $ffmpeg_preview_download_name : str_replace_formatted_placeholder("%extension", $ffmpeg_preview_extension, $lang["cell-fileoftype"]); ?></h2></td>
	<td class="DownloadFileSize"><?php echo formatfilesize(filesize_unlimited($flvfile))?></td>
	<td class="DownloadButton">
	<?php if (!$direct_download || $save_as){?>
		<a href="<?php echo $baseurl_short?>pages/terms.php?ref=<?php echo urlencode($ref)?>&search=<?php echo $search ?>&k=<?php echo urlencode($k)?>&url=<?php echo urlencode("pages/download_progress.php?ref=" . $ref . "&ext=" . $ffmpeg_preview_extension . "&size=pre&k=" . $k . "&search=" . urlencode($search) . "&offset=" . $offset . "&archive=" . $archive . "&sort=".$sort."&order_by=" . urlencode($order_by))?>"  onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["action-download"] ?></a>
	<?php } else { ?>
		<a href="#" onclick="directDownload('<?php echo $baseurl_short?>pages/download_progress.php?ref=<?php echo urlencode($ref)?>&ext=<?php echo $ffmpeg_preview_extension?>&size=pre&k=<?php echo urlencode($k)?>')"><?php echo $lang["action-download"]?></a>
	<?php } // end if direct_download ?></td>
	</tr>
	<?php
	}

hook("additionalresourcetools2");
	
# Alternative files listing
$alt_access=hook("altfilesaccess");
if ($access==0) $alt_access=true; # open access (not restricted)
if ($alt_access) 
	{
	$alt_order_by="";$alt_sort="";
	if ($alt_types_organize){$alt_order_by="alt_type";$alt_sort="asc";} 
	$altfiles=get_alternative_files($ref,$alt_order_by,$alt_sort);
	hook("processaltfiles");
	$last_alt_type="-";
	for ($n=0;$n<count($altfiles);$n++)
		{
		$alt_type=$altfiles[$n]['alt_type'];
		if ($alt_types_organize){
			if ($alt_type!=$last_alt_type){
				$alt_type_header=$alt_type;
				if ($alt_type_header==""){$alt_type_header=$lang["alternativefiles"];}
				hook("viewbeforealtheader");
				?>
				<tr class="DownloadDBlend">
				<td colspan="3" id="altfileheader"><h2><?php echo $alt_type_header?></h2></td>
				</tr>
				<?php
			}
			$last_alt_type=$alt_type;
		}	
		else if ($n==0)
			{
			hook("viewbeforealtheader");
			?>
			<tr>
			<td colspan="3" id="altfileheader"><?php echo $lang["alternativefiles"]?></td>
			</tr>
			<?php
			}	
		$alt_thm="";$alt_pre="";
		if ($alternative_file_previews)
			{
			$alt_thm_file=get_resource_path($ref,true,"col",false,"jpg",-1,1,false,"",$altfiles[$n]["ref"]);
			if (file_exists($alt_thm_file))
				{
				# Get web path for thumb (pass creation date to help cache refresh)
				$alt_thm=get_resource_path($ref,false,"col",false,"jpg",-1,1,false,$altfiles[$n]["creation_date"],$altfiles[$n]["ref"]);
				}
			$alt_pre_file=get_resource_path($ref,true,"pre",false,"jpg",-1,1,false,"",$altfiles[$n]["ref"]);
			if (file_exists($alt_pre_file))
				{
				# Get web path for preview (pass creation date to help cache refresh)
				$alt_pre=get_resource_path($ref,false,"pre",false,"jpg",-1,1,false,$altfiles[$n]["creation_date"],$altfiles[$n]["ref"]);
				}
			}
		?>
		<tr class="DownloadDBlend" <?php if ($alt_pre!="" && $alternative_file_previews_mouseover) { ?>onMouseOver="orig_preview=jQuery('#previewimage').attr('src');orig_width=jQuery('#previewimage').width();jQuery('#previewimage').attr('src','<?php echo $alt_pre ?>');jQuery('#previewimage').width(orig_width);" onMouseOut="jQuery('#previewimage').attr('src',orig_preview);"<?php } ?>>
		<td class="DownloadFileName">
		<?php if(!hook("renderaltthumb")): ?>
		<?php if ($alt_thm!="") { ?><a href="<?php echo $baseurl_short?>pages/preview.php?ref=<?php echo urlencode($ref)?>&alternative=<?php echo $altfiles[$n]["ref"]?>&k=<?php echo urlencode($k)?>&search=<?php echo urlencode($search)?>&offset=<?php echo urlencode($offset)?>&order_by=<?php echo urlencode($order_by)?>&sort=<?php echo urlencode($sort)?>&archive=<?php echo urlencode($archive)?>&<?php echo hook("previewextraurl") ?>"><img src="<?php echo $alt_thm?>" class="AltThumb"></a><?php } ?>
		<?php endif; ?>
		<h2 class="breakall"><?php echo htmlspecialchars($altfiles[$n]["name"])?></h2>
		<p><?php echo htmlspecialchars($altfiles[$n]["description"])?></p>
		</td>
		<td class="DownloadFileSize"><?php echo formatfilesize($altfiles[$n]["file_size"])?></td>
		
		<?php if ($userrequestmode==2 || $userrequestmode==3) { ?><td></td><?php } # Blank spacer column if displaying a price above (basket mode).
		?>
		
		<?php if ($access==0){?>
		<td class="DownloadButton">
		<?php 		
		if (!$direct_download || $save_as)
			{
			if(!hook("downloadbuttonreplace"))
				{
				?><a <?php if (!hook("downloadlink","",array("ref=" . $ref . "&alternative=" . $altfiles[$n]["ref"] . "&k=" . $k . "&ext=" . $altfiles[$n]["file_extension"]))) { ?>href="<?php echo $baseurl_short?>pages/terms.php?ref=<?php echo urlencode($ref)?>&k=<?php echo urlencode($k)?>&search=<?php echo urlencode($search) ?>&url=<?php echo urlencode("pages/download_progress.php?ref=" . $ref . "&ext=" . $altfiles[$n]["file_extension"] . "&k=" . $k . "&alternative=" . $altfiles[$n]["ref"] . "&search=" . urlencode($search) . "&offset=" . $offset . "&archive=" . $archive . "&sort=".$sort."&order_by=" . urlencode($order_by))?>"<?php } ?> onClick="return CentralSpaceLoad(this,true);"><?php echo $lang["action-download"] ?></a><?php 
				}
			}
		else { ?>
			<a href="#" onclick="directDownload('<?php echo $baseurl_short?>pages/download_progress.php?ref=<?php echo urlencode($ref)?>&ext=<?php echo $altfiles[$n]["file_extension"]?>&k=<?php echo urlencode($k)?>&alternative=<?php echo $altfiles[$n]["ref"]?>')"><?php echo $lang["action-download"]?></a>
		<?php } // end if direct_download ?></td></td>
		<?php } else { ?>
		<td class="DownloadButton DownloadDisabled"><?php echo $lang["access1"]?></td>
		<?php } ?>
		</tr>
		<?php	
		}
        hook("morealtdownload");
	}
# --- end of alternative files listing

if ($use_mp3_player && file_exists($mp3realpath) && $access==0){
		include "mp3_play.php";
}

?>



</table>

<?php
hook("additionalresourcetools3");
 } 
if(!hook("replaceactionslistopen")){?>
<br />
<ul>
<?php
} # end hook("replaceactionslistopen")



# ----------------------------- Resource Actions -------------------------------------
hook ("resourceactions") ?>
<?php if ($k=="") { ?>
<?php if (!hook("replaceresourceactions")) {
	hook("resourceactionstitle");
	 if ($resource_contact_link)	{ ?>
	<li><a href="<?php echo $baseurl_short?>pages/ajax/contactadmin.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="showContactBox();return false;" >&gt; <?php echo $lang["contactadmin"]?></a></li>
	<?php }
	
	if ((!checkperm("b"))
	&&
	
	(!(($userrequestmode==2 || $userrequestmode==3) && $basket_stores_size))
	
	) { ?>
	<li><?php echo add_to_collection_link($ref,$search)?>&gt; <?php echo $lang["action-addtocollection"]?></a></li>
	<?php if ($search=="!collection" . $usercollection) { ?><li><?php echo remove_from_collection_link($ref,$search)?>&gt; <?php echo $lang["action-removefromcollection"]?></a></li><?php } ?>
	<?php } ?>
	
	
	<?php if ($allow_share && ($access==0 || ($access==1 && $restricted_share)) && !$hide_resource_share_link) { ?>
		<li><a href="<?php echo $baseurl_short?>pages/resource_share.php?ref=<?php echo urlencode($ref) ?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="return CentralSpaceLoad(this,true);" >&gt; <?php echo $lang["share"]?></a></li>
	<?php 
		hook('aftersharelink', '', array($ref, $search, $offset, $order_by, $sort, $archive));
	} ?>
	<?php if ($edit_access) { ?>
		<li><a href="<?php echo $baseurl_short?>pages/edit.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>"    onClick="return CentralSpaceLoad(this,true);">&gt; <?php echo $lang["action-edit"]?></a></li>
		<?php if ($metadata_download)	{ ?>
		<li><a href="<?php echo $baseurl_short?>pages/metadata_download.php?ref=<?php echo urlencode($ref)?>" onClick="return CentralSpaceLoad(this,true);" >&gt; <?php echo $lang["downloadmetadata"]?></a></li>
	<?php }?>	
	<?php if ((!checkperm("D") || hook('check_single_delete')) && !(isset($allow_resource_deletion) && !$allow_resource_deletion)){?><li><a href="<?php echo $baseurl_short?>pages/delete.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="return CentralSpaceLoad(this,true);">&gt; <?php if ($resource["archive"]==3){echo $lang["action-delete_permanently"];} else {echo $lang["action-delete"];}?></a><?php } ?></li>
	<?php if (!$disable_alternative_files && !checkperm('A')) { ?>
	<li><a href="<?php echo $baseurl_short?>pages/alternative_files.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["managealternativefiles"]?></a></li><?php } ?>

	<?php } ?>
	<?php if (checkperm("e" . $resource["archive"])) { ?><li><a href="<?php echo $baseurl_short?>pages/log.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;search_offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="return CentralSpaceLoad(this,true);">&gt; <?php echo $lang["log"]?></a></li><?php } ?>
	<?php if (checkperm("R") && $display_request_log_link) { ?><li><a href="<?php echo $baseurl_short?>pages/request_log.php?ref=<?php echo urlencode($ref)?>&amp;search=<?php echo urlencode($search)?>&amp;offset=<?php echo urlencode($offset)?>&amp;order_by=<?php echo urlencode($order_by)?>&amp;sort=<?php echo urlencode($sort)?>&amp;archive=<?php echo urlencode($archive)?>" onClick="return CentralSpaceLoad(this,true);">&gt; <?php echo $lang["requestlog"]?></a></li><?php } ?><?php
    } /* End replaceresourceactions */ 
hook("afterresourceactions");
hook("afterresourceactions2");
?>
<?php } /* End if ($k!="")*/ 
hook("resourceactions_anonymous");
?>
<?php } /* End of renderinnerresourcedownloadspace hook */ 
if(!hook('replaceactionslistclose')){
?>
</ul>
<?php } # end hook('replaceactionslistclose') ?>
<div class="clearerleft"> </div>

<?php
if (!hook("replaceuserratingsbox")){
# Include user rating box, if enabled and the user is not external.
if ($user_rating && $k=="") { include "../include/user_rating.php"; }
} /* end hook replaceuserratingsbox */


?>


</div>
<?php } /* End of renderresourcedownloadspace hook */ ?>
<?php } /* End of renderinnerresourceview hook */ ?>
</div>

<?php hook("renderbeforeresourcedetails"); ?>


<div id="Panel1" class="ViewPanel">
    <div id="Titles1" class="ViewPanelTitles">
        <div class="Title Selected" panel="Metadata"><?php if (!hook("customdetailstitle")) echo $lang["resourcedetails"]?></div>
    </div>
</div>
<div id="Metadata">
<?php
$extra="";

#  -----------------------------  Draw tabs ---------------------------
$tabname="";
$tabcount=0;
$used_tab_names = array();
$tmp = hook("tweakfielddisp", "", array($ref, $fields)); if($tmp) $fields = $tmp;
if (count($fields)>0 && $fields[0]["tab_name"]!=""){?>
    <div class="TabBar">
	<?php
	$extra="";
	$tabname="";
	$tabcount=0;
	$resource_type_tab_names = sql_array('SELECT tab_name as value FROM resource_type', '');
	$resource_type_tab_names = array_filter($resource_type_tab_names);

        //Sort function to fix multiple tabs bug
        function orderbytab($a,$b){
           return strcmp($b["tab_name"],$a["tab_name"]);
        };
        usort($fields,'orderbytab');
        $newval="";
        $finalray = array();
        //Re-format our marker fields
        for($f=0; $f<count($fields); $f++){
            if($fields[$f]['ref']==146){
                $framerate = $fields[$f]['value'];
            }else{
                $framerate = 24;
            }
//            var_dump($fields[$f]);
            if($fields[$f]["tooltip_text"]=="marker" && $fields[$f]['value']!=""){
                if($fields[$f]['ref']=="165" || $fields[$f]['ref']=="166"){
                    $toray=explode(",",$fields[$f]['value']);
                    $newray=array();
                    foreach($toray as $k => $v){
                        $v = ((int)$v / (int)str_replace(",", "",$framerate));
                        if($v > 60){
                            $v = $v / 60;
                            $remainder = $v/60 - floor($v/60);
                            $time = round($v)." Min ";
                            if($remainder > 0){
                               $remainder = round($remainder,2)*60;
                               $time.= round($remainder)." Sec";
                            }
                        }else{
                            $time = round($v,1)." Sec";
                        }
                        switch($fields[$f]['ref']){
                            case 166:
                                $pp = "duration ";
                            break;
                            case 165:
                                $pp = "Start @ ";
                            break;
                        }
                        $newray[]=$pp. $time;
                    }
                    $newray = implode(",",$newray);
                    $fields[$f]['value']=$newray;
                }
                $newval = explode(",",$fields[$f]['value']);
                foreach($newval as $key => $val){
                    if(array_key_exists($key,$finalray)){
                        $finalray[$key].=htmlspecialchars_decode($val)."\n";
                    }else{
                        $finalray[$key]=htmlspecialchars_decode($val)."\n";
                    }
                }
            }
        }
        $finalray = implode("\n",$finalray);
        $finaldisplay=array("value"=>$finalray,"name"=>"","title"=>"Markers","type"=>"","order_by"=>"130","keywords_index"=>"0","partial_index"=>"0","display_field"=>"1","use_for_similar"=>"1","display_template"=>"","tab_name"=>"Media Details","hide_when_restricted"=>"0","value_filter"=>"","exiftool_filter"=>"","display_condition"=>"","tooltip_text"=>"");
        array_push($fields,$finaldisplay);
	for ($n=0;$n<count($fields);$n++){
		$value=$fields[$n]["value"];
		# draw new tab?
		if (($tabname!=$fields[$n]["tab_name"]) && ($value!="") && ($value!=",") && ($fields[$n]["display_field"]==1)){
                    ?>
                    <div id="tabswitch<?php echo $tabcount?>" class="Tab<?php if ($tabcount==0) { ?> TabSelected<?php } ?>"><a href="#" onclick="SelectTab(<?php echo $tabcount?>);return false;"><?php echo i18n_get_translated($fields[$n]["tab_name"])?></a></div>
                    <?php
			$tabcount++;
			$tabname=$fields[$n]["tab_name"];
			$used_tab_names[] = $tabname;
               }

			// If any of the fields (with this tab name) are empty and the tab was not rendered before, draw it now
			if(isset($related_type_show_with_data) && ($tabname!=$fields[$n]['tab_name']) && in_array($fields[$n]['tab_name'], $resource_type_tab_names) && !in_array($fields[$n]['tab_name'], $used_tab_names)) { ?>

				<div id="tabswitch<?php echo $tabcount; ?>" class="Tab<?php if($tabcount == 0) { ?> TabSelected<?php } ?>">
					<a href="#" onclick="SelectTab(<?php echo $tabcount; ?>);return false;"><?php echo i18n_get_translated($fields[$n]["tab_name"])?></a>
				</div>

				<?php 
				$tabcount++;
				$tabname=$fields[$n]["tab_name"];
				$used_tab_names[] = $tabname;
			}

		}

		// This is being used to know which tab will be the last one
		if(isset($related_type_show_with_data)) {
			$fields_tab_names = array_values(array_intersect($fields_tab_names, $used_tab_names));
		}
	?>
	</div>
	<script type="text/javascript">
	function SelectTab(tab)
		{
		// Deselect all tabs
		<?php for ($n=0;$n<$tabcount;$n++) { ?>
//		document.getElementById("tab<?php echo $n?>").style.display="none";
                jQuery("#tab<?php echo $n?>").hide("blind");
		document.getElementById("tabswitch<?php echo $n?>").className="Tab";
		<?php } ?>
//		document.getElementById("tab" + tab).style.display="block";
                jQuery("#tab"+tab).show("blind");
		document.getElementById("tabswitch" + tab).className="Tab TabSelected";
		}
	</script>
	<?php
	}
?>

<div id="tab0" class="TabbedPanel<?php if ($tabcount>0) { ?> StyledTabbedPanel<?php } ?>">
<div class="clearerleft"> </div>
<div>
<?php 
#  ----------------------------- Draw standard fields ------------------------
?>
<?php if ($show_resourceid) { ?><div class="itemNarrow"><h3><?php echo $lang["resourceid"]?></h3><p><?php echo htmlspecialchars($ref)?></p></div><?php } ?>
<?php if ($show_access_field) { ?><div class="itemNarrow"><h3><?php echo $lang["access"]?></h3><p><?php echo @$lang["access" . $resource["access"]]?></p></div><?php } ?>
<?php if ($show_resource_type) { ?><div class="itemNarrow"><h3><?php echo $lang["resourcetype"]?></h3><p><?php echo  get_resource_type_name($resource["resource_type"])?></p></div><?php } ?>
<?php if ($show_hitcount){ ?><div class="itemNarrow"><h3><?php echo $resource_hit_count_on_downloads?$lang["downloads"]:$lang["hitcount"]?></h3><p><?php echo $resource["hit_count"]+$resource["new_hit_count"]?></p></div><?php } ?>
<?php hook("extrafields");?>
<?php
# contributed by field
if (!hook("replacecontributedbyfield")){
$udata=get_user($resource["created_by"]);
if ($udata!==false)
	{
	?>
<?php if ($show_contributed_by){?>	<div class="itemNarrow"><h3><?php echo $lang["contributedby"]?></h3><p><?php if (checkperm("u")) { ?><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/team/team_user_edit.php?ref=<?php echo $udata["ref"]?>"><?php } ?><?php echo highlightkeywords(htmlspecialchars($udata["fullname"]),$search)?><?php if (checkperm("u")) { ?></a><?php } ?></p></div><?php } ?>
	<?php
	}
} // end hook replacecontributedby

# Show field data
$tabname="";
$tabcount=0;
$fieldcount=0;
$extra="";
$used_tab_names = array();
$show_default_related_resources = TRUE;
for ($n=0;$n<count($fields);$n++)
	{
	
	$displaycondition=check_view_display_condition($fields,$n);	
	
	if ($displaycondition)
		{
		if (!hook("renderfield") && $fields[$n]["tooltip_text"]!="marker") 
			{
			display_field_data($fields[$n]);
			}
		}
	}
	
// Option to display related resources of specified types along with metadata
if ($enable_related_resources && $show_default_related_resources)
	{
	$relatedresources=do_search("!related" . $ref);
	#build array of related resources' types
	$related_restypes=array();
	for ($n=0;$n<count($relatedresources);$n++)
		{
		$related_restypes[]=$relatedresources[$n]['resource_type'];
		}
	#reduce extensions array to unique values
	$related_restypes=array_unique($related_restypes);
	
	$relatedtypes_shown=array();
	$related_resources_shown=0;
	if(isset($related_type_show_with_data))
		{
		
		# Render fields with display template before the list of related resources:
		echo $extra;
		
		foreach($related_type_show_with_data as $rtype)
			{
			// Is this a resource type that needs to be displayed?
			if (!in_array($rtype,$related_type_show_with_data) || (!in_array($rtype,$related_restypes) && !$related_type_upload_link))
				{
				continue;
				}
			$restypename=sql_value("select name as value from resource_type where ref = '$rtype'","");
			$restypename = lang_or_i18n_get_translated($restypename, "resourcetype-", "-2");		
			
			?>
			<div class="clearerleft"></div>
			<div class="item" id="RelatedResourceData">			
			<?php
			if(in_array($rtype,$related_restypes) || ($related_type_upload_link && $edit_access))
				{
				///only show the table if there are related resources of this type
				?>
				<div class="Listview ListviewTight" >
					<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
					<tbody>
					<tr class="ListviewTitleStyle">
					<td><h3><?php echo $restypename ?></h3></td>		
					<td><div class="ListTools"></div></td>                                    
					</tr>
					<?php
					foreach($relatedresources as $relatedresource)
						{
						if($relatedresource["resource_type"]==$rtype)
							{
							$relatedtitle=$relatedresource["field".$view_title_field];
												
							echo "<tr id=\"relatedresource" . $relatedresource["ref"] . "\" class=\"RelatedResourceRow\">";
							echo "<td class=\"link\"><a href=\"" . $baseurl_short . "pages/view.php?ref=" . $relatedresource["ref"] . "\">" . htmlspecialchars($relatedtitle) . "</a></td>";                                    
							echo "<td>";
							if($edit_access)
								{echo "<div class=\"ListTools\" ><a href=\"#\" onClick=\"if(confirm('" . $lang["related_resource_confirm_delete"] . "')){relateresources(" . $ref . "," . $relatedresource["ref"] . ",'remove');}return false;\" >&gt;&nbsp;" . $lang["action-remove"] . "</a></div>";
								}
							echo "</td>";	
							echo "</tr>";	
							$related_resources_shown++;
							}
						}
					
					if($related_type_upload_link && $edit_access)
						{
						echo "<tr><td></td><td><div class=\"ListTools\"><a href=\"" . $baseurl_short . "pages/edit.php?ref=-" . $userref . "&uploader=plupload&resource_type=" . $rtype ."&submitted=true&relateto=" . $ref . "&collection_add=&redirecturl=" . urlencode($baseurl . "/?r=" . $ref) . "\">&gt;&nbsp;" . $lang["upload"] . "</a></div></td>";
						}			
			
					?>
					</tbody>
					</table>
											 
				</div>
						
				<?php
				// We have displayed these, don't show them again later
				$relatedtypes_shown[]=$rtype;
				}
			?>
			</div><!-- End of RelatedResourceData -->
			<?php
			}
		}    
    }
    
?><?php hook("extrafields2");?>
<?php if(!$force_display_template_order_by){ ?> <div class="clearerleft"></div> <?php } ?>
<?php if(!isset($related_type_show_with_data)) { echo $extra; } ?>
<?php if($force_display_template_order_by){ ?> <div class="clearerleft"></div> <?php } ?>
</div>
</div>
<?php hook("renderafterresourcedetails"); ?>
<!-- end of tabbed panel-->
</div>
</div>
</div>
<div class="PanelShadow"></div>
</div>
<?php if ($view_panels) { ?>
<div class="RecordBox">
    <div class="RecordPanel">  
        <div id="Panel2" class="ViewPanel">
            <div id="Titles2" class="ViewPanelTitles"></div>
        </div>
    </div>
    <div class="PanelShadow"></div>
</div>

<div class="RecordBox">
    <div class="RecordPanel">  
        <div id="Panel3" class="ViewPanel">
            <div id="Titles3" class="ViewPanelTitles"></div>
        </div>
    </div>
    <div class="PanelShadow"></div>
</div>
<?php } ?>
<?php hook("custompanels"); //For custom panels immediately below resource display area ?>

<?php 
if (!$disable_geocoding) { 
  // only show this section if the resource is geocoded OR they have permission to do it themselves
  if ($edit_access||($resource["geo_lat"]!="" && $resource["geo_long"]!=""))
  		{
		include dirname(__FILE__)."/../../include/geocoding_view.php";
	  	} 
 	} 
?>

<?php 
	if ($comments_resource_enable && $k=="") include_once ("../include/comment_resources.php");
?>
	  	  
<?php hook("w2pspawn");?>

<?php 
// include collections listing
if ($view_resource_collections){ ?>
	<div id="resourcecollections"></div>
	<script type="text/javascript">
	jQuery("#resourcecollections").load('<?php echo $baseurl_short?>pages/resource_collection_list.php?ref=<?php echo urlencode($ref)?>&k=<?php echo urlencode($k)?>'
	<?php
	if ($view_panels) {
	?>
    	, function() {
    	
    	jQuery("#AssociatedCollections").children(".Title").attr("panel", "AssociatedCollections").addClass("Selected").appendTo("#Titles3");
    	removePanel=jQuery("#AssociatedCollections").parents(".RecordBox");
    	jQuery("#AssociatedCollections").appendTo("#Panel3").addClass("TabPanel");
    	removePanel.remove();
    	
    	jQuery("#CollectionsThemes").children().children(".Title").attr("panel", "CollectionsThemes").appendTo("#Titles3");
    	removePanel=jQuery("#CollectionsThemes").parents(".RecordBox");
    	jQuery("#CollectionsThemes").appendTo("#Panel3").addClass("TabPanel").hide();
    	removePanel.remove();
    	if (jQuery("#Titles2").children().length==0) jQuery("#Panel2").parent().parent().remove();
	if (jQuery("#Titles3").children().length==0) jQuery("#Panel3").parent().parent().remove();	
        jQuery(".ViewPanelTitles").children(".Title").click(function(){
        // function to switch tab panels
            jQuery(this).parent().parent().children(".TabPanel").hide();
            jQuery(this).parent().children(".Title").removeClass("Selected");
            jQuery(this).addClass("Selected");
            jQuery("#"+jQuery(this).attr("panel")).show();
        });
    	}
	<?php
	}
	?>); 
	</script>
	<?php }

// include optional ajax metadata report
if ($metadata_report && isset($exiftool_path) && $k==""){?>
        <div class="RecordBox">
        <div class="RecordPanel">  
        <div class="Title"><?php echo $lang['metadata-report']?></div>
        <div id="metadata_report"><a onclick="metadataReport(<?php echo htmlspecialchars($ref)?>);document.getElementById('metadata_report').innerHTML='<?php echo $lang['pleasewait']?>';return false;" class="itemNarrow" href="#">&gt; <?php echo $lang['viewreport'];?></a><br></div>
        </div>
        <div class="PanelShadow"></div>
        </div>

<?php } ?>

<?php hook("customrelations"); //For future template/spawned relations in Web to Print plugin ?>

<?php
# -------- Related Resources (must be able to search for this to work)
if (isset($relatedresources) && (count($relatedresources) > $related_resources_shown)&& checkperm("s") && ($k=="")) {
$result=$relatedresources;
if (count($result)>0) 
	{
	# -------- Related Resources by File Extension
	if($sort_relations_by_filetype){	
		#build array of related resources' file extensions
		for ($n=0;$n<count($result);$n++){
			$related_file_extension=$result[$n]["file_extension"];
			$related_file_extensions[]=$related_file_extension;
			}
		#reduce extensions array to unique values
		$related_file_extensions=array_unique($related_file_extensions);
		$count_extensions=0;
		foreach($related_file_extensions as $rext){
		?><!--Panel for related resources-->
		<div class="RecordBox">
		<div class="RecordPanel">  
         <div id="RelatedResources">
		<div class="RecordResouce">
		<div class="Title"><?php echo str_replace_formatted_placeholder("%extension", $rext, $lang["relatedresources-filename_extension"]); ?></div>
		<?php
		# loop and display the results by file extension
		for ($n=0;$n<count($result);$n++)			
			{
			if(in_array($result[$n]["resource_type"],$relatedtypes_shown))
				{
				// Don't show this type again.
				continue;
				}			
			if ($result[$n]["file_extension"]==$rext){
				$rref=$result[$n]["ref"];
				$title=$result[$n]["field".$view_title_field];
				$access=get_resource_access($rref);
				$use_watermark=check_use_watermark();
				# swap title fields if necessary

				if (isset($metadata_template_title_field) && isset($metadata_template_resource_type))
					{
					if ($result[$n]['resource_type']==$metadata_template_resource_type)
						{
						$title=$result[$n]["field".$metadata_template_title_field];
						}	
					}	
						
				?>
				
				<!--Resource Panel-->
				<div class="CollectionPanelShell">
				<table border="0" class="CollectionResourceAlign"><tr><td>
				<a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>&search=<?php echo urlencode("!related" . $ref)?>" onClick="return CentralSpaceLoad(this,true);"><?php if ($result[$n]["has_image"]==1) { ?><img border=0 src="<?php echo get_resource_path($rref,false,"col",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"])?>" class="CollectImageBorder"/><?php } else { ?><img border=0 src="../gfx/<?php echo get_nopreview_icon($result[$n]["resource_type"],$result[$n]["file_extension"],true)?>"/><?php } ?></a></td>
				</tr></table>
				<div class="CollectionPanelInfo"><a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>" onClick="return CentralSpaceLoad(this,true);"><?php echo tidy_trim(i18n_get_translated($title),15)?></a>&nbsp;</div>
				<?php hook("relatedresourceaddlink");?>
				</div>
				<?php		
				}
			}
		?>
		<div class="clearerleft"> </div>
		<?php $count_extensions++; if ($count_extensions==count($related_file_extensions)){?><a href="<?php echo $baseurl_short?>pages/search.php?search=<?php echo urlencode("!related" . $ref) ?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["clicktoviewasresultset"]?></a><?php }?>
		</div>
		</div>
		</div>
		<div class="PanelShadow"></div>
		</div><?php
		} #end of display loop by resource extension
	} #end of IF sorted relations
	
	elseif($sort_relations_by_restype){	
		$count_restypes=0;
		foreach($related_restypes as $rtype){
			if(in_array($rtype,$relatedtypes_shown))
				{
				// Don't show this type again.
				continue;
				}
		$restypename=sql_value("select name as value from resource_type where ref = '$rtype'","");
		$restypename = lang_or_i18n_get_translated($restypename, "resourcetype-", "-2");
		?><!--Panel for related resources-->
		<div class="RecordBox">
		<div class="RecordPanel">  
         <div id="RelatedResources">
		<div class="RecordResouce">
		<div class="Title"><?php echo str_replace_formatted_placeholder("%restype%", $restypename, $lang["relatedresources-restype"]); ?></div>
		<?php
		# loop and display the results by file extension
		for ($n=0;$n<count($result);$n++)			
			{	
			if ($result[$n]["resource_type"]==$rtype){
				$rref=$result[$n]["ref"];
				$title=$result[$n]["field".$view_title_field];
				$access=get_resource_access($rref);
				$use_watermark=check_use_watermark();
				# swap title fields if necessary

				if (isset($metadata_template_title_field) && isset($metadata_template_resource_type))
					{
					if ($result[$n]['resource_type']==$metadata_template_resource_type)
						{
						$title=$result[$n]["field".$metadata_template_title_field];
						}	
					}	
						
				?>
				
				<!--Resource Panel-->
				<div class="CollectionPanelShell">
				<table border="0" class="CollectionResourceAlign"><tr><td>
				<a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>&search=<?php echo urlencode("!related" . $ref)?>" onClick="return CentralSpaceLoad(this,true);"><?php if ($result[$n]["has_image"]==1) { ?><img border=0 src="<?php echo get_resource_path($rref,false,"col",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"])?>" class="CollectImageBorder"/><?php } else { ?><img border=0 src="../gfx/<?php echo get_nopreview_icon($result[$n]["resource_type"],$result[$n]["file_extension"],true)?>"/><?php } ?></a></td>
				</tr></table>
				<div class="CollectionPanelInfo"><a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>" onClick="return CentralSpaceLoad(this,true);"><?php echo tidy_trim(i18n_get_translated($title),15)?></a>&nbsp;</div>
				<?php hook("relatedresourceaddlink");?>
				</div>
				<?php		
				}
			}
		?>
		<div class="clearerleft"> </div>
		<?php $count_restypes++; if ($count_restypes==count($related_restypes)){?><a href="<?php echo $baseurl_short?>pages/search.php?search=<?php echo urlencode("!related" . $ref) ?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["clicktoviewasresultset"]?></a><?php }?>
		</div>
		</div>
		</div>
		<div class="PanelShadow"></div>
		</div><?php
		} #end of display loop by resource extension
	} #end of IF sorted relations	
	
	
	# -------- Related Resources (Default)
	else { 
		 ?><!--Panel for related resources-->
		<div class="RecordBox">
		<div class="RecordPanel">  
         <div id="RelatedResources">
		<div class="RecordResouce">
		<div class="Title"><?php echo $lang["relatedresources"]?></div>
		<?php
    	# loop and display the results
    	for ($n=0;$n<count($result);$n++)            
        	{
			if(in_array($result[$n]["resource_type"],$relatedtypes_shown))
				{
				// Don't show this type again.
				continue;
				}
        	$rref=$result[$n]["ref"];
			$title=$result[$n]["field".$view_title_field];
			$access=get_resource_access($rref);
			$use_watermark=check_use_watermark();
			# swap title fields if necessary

			if (isset($metadata_template_title_field) && isset($metadata_template_resource_type))
				{
				if ($result[$n]["resource_type"]==$metadata_template_resource_type)
					{
					$title=$result[$n]["field".$metadata_template_title_field];
					}	
				}	
	

			?>
        	<!--Resource Panel-->
        	<div class="CollectionPanelShell">
            <table border="0" class="CollectionResourceAlign"><tr><td>
            <a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>&search=<?php echo urlencode("!related" . $ref)?>" onClick="return CentralSpaceLoad(this,true);"><?php if ($result[$n]["has_image"]==1) { ?><img border=0 src="<?php echo get_resource_path($rref,false,"col",false,$result[$n]["preview_extension"],-1,1,$use_watermark,$result[$n]["file_modified"])?>" class="CollectImageBorder"/><?php } else { ?><img border=0 src="../gfx/<?php echo get_nopreview_icon($result[$n]["resource_type"],$result[$n]["file_extension"],true)?>"/><?php } ?></a></td>
            </tr></table>
            <div class="CollectionPanelInfo"><a href="<?php echo $baseurl_short?>pages/view.php?ref=<?php echo $rref?>" onClick="return CentralSpaceLoad(this,true);"><?php echo tidy_trim(i18n_get_translated($title),15)?></a>&nbsp;</div>
				<?php hook("relatedresourceaddlink");?>       
       </div>
        <?php        
        }
    ?>
    <div class="clearerleft"> </div>
        <a href="<?php echo $baseurl_short?>pages/search.php?search=<?php echo urlencode("!related" . $ref) ?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["clicktoviewasresultset"]?></a>

    </div>
    </div>
    </div>
    <div class="PanelShadow"></div>
    </div><?php
		}# end related resources display
	} 
	# -------- End Related Resources
	
	

if ($show_related_themes==true ){
# -------- Public Collections / Themes
$result=get_themes_by_resource($ref);
if (count($result)>0) 
	{
	?><!--Panel for related themes / collections -->
	<div class="RecordBox">
	<div class="RecordPanel">  
	<div id="CollectionsThemes">
	<div class="RecordResouce BasicsBox nopadding">
	<div class="Title"><?php echo $lang["collectionsthemes"]?></div>

	<?php
		# loop and display the results
		for ($n=0;$n<count($result);$n++)			
			{
			?>
			<a href="<?php echo $baseurl_short?>pages/search.php?search=!collection<?php echo $result[$n]["ref"]?>" onClick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo (strlen($result[$n]["theme"])>0)?htmlspecialchars(str_replace("*","",i18n_get_translated($result[$n]["theme"])) . " / "):$lang["public"] . " : "; ?><?php if (!$collection_public_hide_owner) {echo htmlspecialchars($result[$n]["fullname"] . " / ");} ?><?php echo i18n_get_collection_name($result[$n]); ?></a><br />
			<?php		
			}
		?>
	
	</div>
	</div>
	</div>
	<div class="PanelShadow"></div>
	</div><?php
	}} 
?>



<?php if ($enable_find_similar) { ?>
<!--Panel for search for similar resources-->
<div class="RecordBox">
<div class="RecordPanel"> 
<div id="SearchSimilar">

<div class="RecordResouce">
<div class="Title"><?php echo $lang["searchforsimilarresources"]?></div>
<?php if ($resource["has_image"]==1) { ?>

<!--
<p>Find resources with a <a href="search.php?search=<?php echo urlencode("!rgb:" . $resource["image_red"] . "," . $resource["image_green"] . "," . $resource["image_blue"])?>">similar colour theme</a>.</p>
<p>Find resources with a <a href="search.php?search=<?php echo urlencode("!colourkey" . $resource["colour_key"]) ?>">similar colour theme (2)</a>.</p>
-->

<?php } ?>
<script type="text/javascript">
function UpdateFSResultCount()
	{
	// set the target of the form to be the result count iframe and submit

	// some pages are erroneously calling this function because it exists in unexpected
	// places due to dynamic page loading. So only do it if it seems likely to work.
	if(jQuery('#findsimilar').length > 0)
		{
		document.getElementById("findsimilar").target="resultcount";
		document.getElementById("countonly").value="yes";
		document.getElementById("findsimilar").submit();
		document.getElementById("findsimilar").target="";
		document.getElementById("countonly").value="";
		}
	}
</script>

<form method="post" action="<?php echo $baseurl_short?>pages/find_similar.php" id="findsimilar">
<input type="hidden" name="resource_type" value="<?php echo $resource["resource_type"]?>">
<input type="hidden" name="countonly" id="countonly" value="">
<?php
$keywords=get_resource_top_keywords($ref,30);
$keywords = array_values(array_unique($keywords));
$searchwords=split_keywords($search);
for ($n=0;$n<count($keywords);$n++)
	{
	?>
	<div class="SearchSimilar"><input type=checkbox id="similar_search_<?php echo urlencode($keywords[$n])?>" name="keyword_<?php echo urlencode($keywords[$n])?>" value="yes"
	<?php if (in_array($keywords[$n],$searchwords)) {?>checked<?php } ?> onClick="UpdateFSResultCount();"><label for="similar_search_<?php echo urlencode($keywords[$n])?>">&nbsp;<?php echo htmlspecialchars($keywords[$n])?></label></div>
	<?php
	}
?>
<div class="clearerleft"> </div>
<br />
<input name="search" type="submit" value="&nbsp;&nbsp;<?php echo $lang["searchbutton"]?>&nbsp;&nbsp;" id="dosearch"/>
<iframe src="<?php echo $baseurl_short?>pages/blank.html" frameborder=0 scrolling=no width=1 height=1 style="visibility:hidden;" name="resultcount" id="resultcount"></iframe>
</form>
<div class="clearerleft"> </div>
</div>
</div>
</div>
<div class="PanelShadow"></div>
</div>
<?php } ?>

<?php hook("afterviewfindsimilar"); ?>

<?php } # end of block that requires search permissions


include dirname(__FILE__)."/../../include/footer.php";
}
?>
