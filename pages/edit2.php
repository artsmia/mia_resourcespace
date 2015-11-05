<?php
include "../include/db.php";
include "../include/authenticate.php";
include "../include/general.php";
include "../include/resource_functions.php";
include "../include/collections_functions.php";
include "../include/search_functions.php";
include "../include/image_processing.php";
# Editing resource or collection of resources (multiple)?
$ref=getvalescaped("ref","",true);

# Fetch search details (for next/back browsing and forwarding of search params)
$archive=getvalescaped("archive",0,true);

$uploadparams="";
$uploadparams.="&relateto=" . urlencode(getval("relateto",""));
$uploadparams.="&redirecturl=" . urlencode(getval("redirecturl",""));

global $tabs_on_edit;
$tabs_on_edit=TRUE;
$collapsible_sections=true;
if($tabs_on_edit){$collapsible_sections=false;}

$errors=array(); # The results of the save operation (e.g. required field messages)

# Disable auto save for upload forms - it's not appropriate.
if ($ref<0) { $edit_autosave=false; }
$collection=getvalescaped("collection","",true);
$multiple=false;

# Fetch resource data.
$resource=get_resource_data($ref);

# Allow alternative configuration settings for this resource type.
resource_type_config_override($resource["resource_type"]);

# If upload template, check if the user has upload permission.
if ($ref<0 && !(checkperm("c") || checkperm("d"))){
    $error=$lang['error-permissiondenied'];
    error_alert($error);
    exit();
}

# Check edit permission.
if (!get_edit_access($ref,$resource["archive"],false,$resource)){
    # The user is not allowed to edit this resource or the resource doesn't exist.
    $error=$lang['error-permissiondenied'];
    error_alert($error);
    exit();
}

# Establish if this is a metadata template resource, so we can switch off certain unnecessary features
$is_template=(isset($metadata_template_resource_type) && $resource["resource_type"]==$metadata_template_resource_type);

hook("editbeforeheader");

# -----------------------------------
# 			PERFORM SAVE
# -----------------------------------

if ((getval("autosave","")!="") || (getval("tweak","")=="" && getval("submitted","")!="" && getval("resetform","")=="" && getval("copyfromsubmit","")=="")){
    if(($embedded_data_user_select && getval("exif_option","")=="custom") || isset($embedded_data_user_select_fields)){
        $exif_override=false;
	foreach($_POST as $postname=>$postvar){
	    if (strpos($postname,"exif_option_")!==false){
	        $uploadparams.="&" . urlencode($postname) . "=" . urlencode($postvar);
		$exif_override=true;
	    }
	}
	if($exif_override){
	    $uploadparams.="&exif_override=true";
	}
    }
    hook("editbeforesave");
    # save data
    if (!$multiple){
        # Upload template: Change resource type
        $resource_type=getvalescaped("resource_type","");
	if ($resource_type!="" && !checkperm("XU{$resource_type}")){
        // only if resource specified and user has permission for that resource type
	    update_resource_type($ref,$resource_type);
	    $resource=get_resource_data($ref,false); # Reload resource data.
	}
	$save_errors=save_resource_data($ref,$multiple);
	if($embedded_data_user_select){
	    $no_exif=getval("exif_option","");
	}else{
            $no_exif=getval("no_exif","");
	}
	$autorotate = getval("autorotate","");
	if ($upload_collection_name_required){
	    if (getvalescaped("entercolname","")=="" && getval("collection_add","")==-1){
                if (!is_array($save_errors)){$save_errors=array();}
		    $save_errors['collectionname']=$lang["requiredfield"];
		}
	    }
	    if (($save_errors===true || $is_template)&&(getval("tweak","")=="")){
	        if ((getval("uploader","")!="")&&(getval("uploader","")=="single")){
		    # Save button pressed? Move to next step.
		    if (getval("save","")!="") {
		    }
		}else{ // Hence fetching from ftp.
		    # Save button pressed? Move to next step.
		    if (getval("save","")!="") {redirect($baseurl_short."pages/team/team_batch.php?collection_add=" . getval("collection_add","")."&entercolname=".urlencode(getvalescaped("entercolname","")). "&resource_type=". urlencode($resource_type). "&no_exif=" . $no_exif . "&autorotate=" . urlencode($autorotate) . $uploadparams );}
		}
	    }elseif (getval("save","")!=""){
                $show_error=true;
            }
	}
    }

include "../include/header.php";
?>
<div id="progressBar"></div>
<?php hook("htmlbeforeform") ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.CollapsibleSectionHead').click(function(){
        cur=jQuery(this).next();
        cur_id=cur.attr("id");
        if (cur.is(':visible')){
            SetCookie(cur_id, "collapsed");
            jQuery(this).removeClass('expanded');
            jQuery(this).addClass('collapsed');
        }else{
            SetCookie(cur_id, "expanded")
            jQuery(this).addClass('expanded');
            jQuery(this).removeClass('collapsed');
        }
        cur.slideToggle();
        return false;
     }).each(function(){
        cur_id=jQuery(this).next().attr("id");
        if (getCookie(cur_id)=="collapsed"){
            jQuery(this).next().hide();
            jQuery(this).addClass('collapsed');
        }else{ jQuery(this).addClass('expanded')};
     });
<?php
    if($ctrls_to_save){ ?>
        jQuery(document).bind('keydown',function (e){
	    if (!(e.which == 115 && (e.ctrlKey || e.metaKey)) && !(e.which == 83 && (e.ctrlKey || e.metaKey)) && !(e.which == 19) ){
	        return true;
	    }else{
                e.preventDefault();
	        if(jQuery('#mainform')){
		    jQuery('.AutoSaveStatus').html('<?php echo $lang["saving"] ?>');
		    jQuery('.AutoSaveStatus').show();
		    jQuery.post(jQuery('#mainform').attr('action') + '&autosave=true',jQuery('#mainform').serialize(),
		    function(data){
		        if (data.trim()=="SAVED"){
			    jQuery('.AutoSaveStatus').html('<?php echo $lang["saved"] ?>');
			    jQuery('.AutoSaveStatus').fadeOut('slow');
			}else{
		            jQuery('.AutoSaveStatus').html('<?php echo $lang["save-error"] ?>' + data);
			}
                    }
	        }
	        return false;
            }
        });
<?php } ?>
});
<?php hook("editadditionaljs") ?>
function ShowHelp(field){
    // Show the help box if available.
    if (document.getElementById('help_' + field)){
        jQuery('#help_' + field).show("fast");
    }
}
function HideHelp(field){
    // Hide the help box if available.
    if (document.getElementById('help_' + field)){
        jQuery('#help_' + field).hide("blind");
    }
}
jQuery(document).ready(function() {
    jQuery('#collection_add').change(function (){
        if(jQuery('#collection_add').val()==-1){
            jQuery('#collectioninfo').fadeIn();
        }else{
            jQuery('#collectioninfo').fadeOut();
        }
    });
    jQuery('#collection_add').change();
});

</script>
<form method="post" style="display:none" action="<?php echo $baseurl_short?>pages/edit2.php?ref=<?php echo urlencode($ref)?>&amp;local=<?php echo urlencode(getvalescaped("local","")) ?>&amp;metadatatemplate=<?php echo getval("metadatatemplate","")  . $uploadparams?>" id="mainform">
    <div class="BasicsBox">
        <input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["next"]?>&nbsp;&nbsp;" class="defaultbutton" />
        <input type="hidden" name="submitted" value="true"/>

    <h1 id="mainTitle"> Batch Upload </h1>
    <h2 id="subTitle"> <span style="color:#FF0000">NOTICE</span>: Fields on this form will overwrite those values to all resources being uploaded.</h2>
    <p id="paragraph"></p>
<div class="QuestionSubmit">
<input name="resetform" type="submit" value="<?php /*echo $lang["clearbutton"]*/?> Select Different Resource" />&nbsp;
<div class="clearerleft"> </div>
</div>
<?php // Upload template: Show the required fields note at the top of the form.
  if (!$is_template) { ?><p class="greyText noPadding"><sup>*</sup> <?php echo $lang["requiredfield"]?></p><?php }
  ?>




<?php hook("editbefresmetadata"); ?>
<?php if (!hook("replaceedittype")) { ?>
<?php if (!$multiple){

# RESOURCE TYPE FIELD
?>
<h2 id="resource_title"></h2>
<div class="Question" id="question_resourcetype">
<label for="resourcetype" id="rsTypelabel"></label>
<input name="resource_type" type="hidden" id="rsType"/>
<input name="temp_file" type="hidden" id="rsTemp"/>
<input name ="file_name" type="hidden" class="rsFileName"/>
<input name ="field_8" type="hidden" class="rsFileName"/>

<?php
$types=get_resource_types();
?>
<div class="clearerleft"> </div>
</div>
<input type="button" style="display:none" id="show" value="Show Matched Metadata Fields" />
<?php } else {

# Multiple method of changing resource type.
 ?>
<h2 <?php echo ($collapsible_sections)?"class=\"CollapsibleSectionHead\"":""?>><?php echo $lang["resourcetype"] ?></h2>
<div <?php echo ($collapsible_sections)?"class=\"CollapsibleSection\"":""?> id="ResourceTypeSection<?php if ($ref==-1) echo "Upload"; ?>"><input name="editresourcetype" id="editresourcetype" type="checkbox" value="yes" onClick="var q=document.getElementById('editresourcetype_question');if (this.checked) {q.style.display='block';alert('<?php echo $lang["editallresourcetypewarning"] ?>');} else {q.style.display='none';}">&nbsp;<label for="editresourcetype"><?php echo $lang["resourcetype"] ?></label>
<div class="Question" style="display:none;" id="editresourcetype_question">
<label for="resourcetype"><?php echo $lang["resourcetype"]?></label>
<select name="resource_type" id="resourcetype" class="stdwidth">
<?php
$types=get_resource_types();
for ($n=0;$n<count($types);$n++)
	{
		if(in_array($types[$n]['ref'], $hide_resource_types)) { continue; }
	?><option value="<?php echo $types[$n]["ref"]?>" <?php if ($resource["resource_type"]==$types[$n]["ref"]) {?>selected<?php } ?>><?php echo htmlspecialchars($types[$n]["name"])?></option><?php
	}
?></select>
<div class="clearerleft"> </div>
</div>
<?php }
 } # end hook("replaceedittype")
$lastrt=-1;
if (isset($metadata_template_resource_type) && !$multiple && !checkperm("F*"))
	{
	# Show metadata templates here
	?>
	<div class="Question" id="question_metadatatemplate">
	<label for="metadatatemplate"><?php echo $lang["usemetadatatemplate"]?></label>
	<select name="metadatatemplate" class="medwidth">
	<option value=""><?php echo (getval("metadatatemplate","")=="")?$lang["select"]:$lang["undometadatatemplate"] ?></option>
	<?php
	$templates=get_metadata_templates();
	foreach ($templates as $template)
		{
		?>
		<option value="<?php echo $template["ref"] ?>"><?php echo htmlspecialchars($template["field$metadata_template_title_field"]) ?></option>
		<?php	
		}
	?>
	</select>
	<input type="submit" class="medcomplementwidth" name="copyfromsubmit" value="<?php echo $lang["action-select"]?>">
	</div><!-- end of question_metadatatemplate --> 
	<?php
	}

	if ($edit_upload_options_at_top){include '../include/edit_upload_options.php';}


$use=$ref;

# Resource aliasing.
# 'Copy from' or 'Metadata template' been supplied? Load data from this resource instead.
$originalref=$use;

if (getval("copyfrom","")!="")
	{
	# Copy from function
	$copyfrom=getvalescaped("copyfrom","");
	$copyfrom_access=get_resource_access($copyfrom);
	
	# Check access level
	if ($copyfrom_access!=2) # Do not allow confidential resources (or at least, confidential to that user) to be copied from
		{
		$use=$copyfrom;
		$original_fields=get_resource_field_data($ref,$multiple,true,-1,"",$tabs_on_edit);
		}
	}

//if (getval("metadatatemplate","")!="")
//	{
//	$use=getvalescaped("metadatatemplate","");
//	$original_fields=get_resource_field_data($ref,$multiple,true,-1,"",$tabs_on_edit);
//	}

# Load resource data
$fields=get_resource_field_data($use,$multiple,!hook("customgetresourceperms"),$originalref,"",$tabs_on_edit);

# if this is a metadata template, set the metadata template title field at the top
//if (isset($metadata_template_resource_type)&&(isset($metadata_template_title_field)) && $resource["resource_type"]==$metadata_template_resource_type){	# recreate fields array, first with metadata template field
//	$x=0;
//	for ($n=0;$n<count($fields);$n++){
//		if ($fields[$n]["resource_type"]==$metadata_template_resource_type){
//			$newfields[$x]=$fields[$n];
//			$x++;
//		}
//	}
//	# then add the others
//	for ($n=0;$n<count($fields);$n++){
//		if ($fields[$n]["resource_type"]!=$metadata_template_resource_type){
//			$newfields[$x]=$fields[$n];
//			$x++;
//		}
//	}
//	$fields=$newfields;
//}

$required_fields_exempt=array(); # new array to contain required fields that have not met the display condition

function is_field_displayed($field){
	global $ref, $resource;
	# Field is an archive only field
	return !(($resource["archive"]==0 && $field["resource_type"]==999)
	# Field has write access denied
		|| (checkperm("F*") && !checkperm("F-" . $field["ref"])
				&& !($ref < 0 && checkperm("P" . $field["ref"])))
		|| checkperm("F" . $field["ref"])
	# Upload only field
		|| ($ref < 0 && $field["hide_when_uploading"] && $field["required"]==0)
		|| hook('edithidefield', '', array('field' => $field))
		|| hook('edithidefield2', '', array('field' => $field)));
}

function check_display_condition($n, $field){
    global $fields, $scriptconditions, $required_fields_exempt;
    $displaycondition=true;
    $s=explode(";",$field["display_condition"]);
    $condref=0;
    foreach ($s as $condition){
     # Check each condition
        $displayconditioncheck=false;
        $s=explode("=",$condition);
        for ($cf=0;$cf<count($fields);$cf++){ # Check each field to see if needs to be checked
            if ($s[0]==$fields[$cf]["name"]){
            # this field needs to be checked
	        $scriptconditions[$condref]["field"] = $fields[$cf]["ref"];  # add new jQuery code to check value
		$scriptconditions[$condref]['type'] = $fields[$cf]['type'];
		$scriptconditions[$condref]['options'] = $fields[$cf]['options'];
		$checkvalues=$s[1];
		$validvalues=explode("|",strtoupper($checkvalues));
		$scriptconditions[$condref]["valid"]= "\"";
		$scriptconditions[$condref]["valid"].= implode("\",\"",$validvalues);
		$scriptconditions[$condref]["valid"].= "\"";
		$v=trim_array(explode(",",strtoupper($fields[$cf]["value"])));
	            foreach ($validvalues as $validvalue){
		        if (in_array($validvalue,$v)) {$displayconditioncheck=true;} # this is  a valid value
		    }
		    if (!$displayconditioncheck) {$displaycondition=false;$required_fields_exempt[]=$field["ref"];}
		        #add jQuery code to update on changes
		    if ($fields[$cf]["type"]==2){
		        # construct the value from the ticked boxes
			# Note: it seems wrong to start with a comma, but this ensures it is treated as a comma separated list by split_keywords(), so if just one item is selected it still does individual word adding, so 'South Asia' is split to 'South Asia','South','Asia'.
			$options=trim_array(explode(",",$fields[$cf]["options"]));

	                ?><script type="text/javascript">
			jQuery(document).ready(function() {<?php
			    for ($m=0;$m<count($options);$m++){
			        $checkname=$fields[$cf]["ref"] . "_" . md5($options[$m]);
				echo "
                                jQuery('input[name=\"" . $checkname . "\"]').change(function (){
				    checkDisplayCondition" . $field["ref"] . "();
				});";
			    }?>
			});
		        </script><?php
                     #add change event to each radio button
                     }else if($fields[$cf]['type'] == 12) {
                         $options = explode(',', $fields[$cf]['options']); ?>
                         <script type="text/javascript">
			 jQuery(document).ready(function() {<?php
			     foreach ($options as $option) {
			         $element_id = 'field_' . $fields[$cf]['ref'] . '_' . sha1($option);
				 $jquery = sprintf('jQuery("#%s").change(function() {
				     checkDisplayCondition%s();
				 });
                                ',
				$element_id,
				$field["ref"]);
				echo $jquery;
			      } ?>
                          });
			  </script><?php
		      }else{?>
		          <script type="text/javascript">
		          jQuery(document).ready(function() {
		              jQuery('#field_<?php echo $fields[$cf]["ref"];?>').change(function (){
			          checkDisplayCondition<?php echo $field["ref"];?>();
			      });
		          });
		          </script><?php
                      }
}
    } # see if next field needs to be checked
    $condref++;
} # check next condition 
?>
<script type="text/javascript">
function checkDisplayCondition<?php echo $field["ref"];?>(){
    <?php echo "field" . $field["ref"] . "status=jQuery('#question_" . $n . "').css('display');
    ";
    echo "newfield" . $field["ref"] . "status='none';
    ";
    echo "newfield" . $field["ref"] . "provisional=true;
    ";
    foreach ($scriptconditions as $scriptcondition){
        echo "newfield" . $field["ref"] . "provisionaltest=false;
	";
	echo "if (jQuery('#field_" . $scriptcondition["field"] . "').length!=0)
        {";
	    echo "
	    fieldcheck" . $scriptcondition["field"] . "=jQuery('#field_" . $scriptcondition["field"] . "').val().toUpperCase();
	    ";
	    echo "fieldvalues" . $scriptcondition["field"] . "=fieldcheck" . $scriptcondition["field"] . ".split(',');
	    //alert(fieldvalues" . $scriptcondition["field"] . ");
	}";
        echo "
	else{
            ";
            # Handle Radio Buttons type:
	    if($scriptcondition['type'] == 12) {
	        $scriptcondition["options"] = explode(',', $scriptcondition["options"]);
	        foreach ($scriptcondition["options"] as $key => $value) {
		    $scriptcondition["options"][$key] = sha1($value);
		}
		$scriptcondition["options"] = implode(',', $scriptcondition["options"]);?>

                var options_string = '<?php echo $scriptcondition["options"]; ?>';
		var field<?php echo $scriptcondition["field"]; ?>_options = options_string.split(',');
		var checked = null;
		for(var i=0; i < field<?php echo $scriptcondition["field"]; ?>_options.length; i++){
		    if(jQuery('#field_<?php echo $scriptcondition["field"]; ?>_' + field<?php echo $scriptcondition["field"]; ?>_options[i]).is(':checked')) {
		        checked = jQuery('#field_<?php echo $scriptcondition["field"]; ?>_' + field<?php echo $scriptcondition["field"]; ?>_options[i] + ':checked').val();
			checked = checked.toUpperCase();
		    }

		}
		fieldokvalues<?php echo $scriptcondition["field"]; ?> = [<?php echo $scriptcondition["valid"]; ?>];
		if(checked !== null && jQuery.inArray(checked, fieldokvalues<?php echo $scriptcondition["field"]; ?>) > -1) {
		    newfield<?php echo $field["ref"]; ?>provisionaltest = true;
		}<?php
	    }
            echo "fieldvalues" . $scriptcondition["field"] . "=new Array();
	    ";
	    echo "checkedvals" . $scriptcondition["field"] . "=jQuery('input[name^=" . $scriptcondition["field"] . "_]')
	    ";
	    echo "jQuery.each(checkedvals" . $scriptcondition["field"] . ",function(){
	        if (jQuery(this).is(':checked')){
		    checktext" . $scriptcondition["field"] . "=jQuery(this).parent().next().text().toUpperCase();
		    checktext" . $scriptcondition["field"] . " = jQuery.trim(checktext" . $scriptcondition["field"] . ");
		    fieldvalues" . $scriptcondition["field"] . ".push(checktext" . $scriptcondition["field"] . ");
		    //alert(fieldvalues" . $scriptcondition["field"] . ");
		}
		})
	    }";
	    echo "fieldokvalues" . $scriptcondition["field"] . "=new Array();
            ";
	    echo "fieldokvalues" . $scriptcondition["field"] . "=[" . $scriptcondition["valid"] . "];
	    ";
	    echo "jQuery.each(fieldvalues" . $scriptcondition["field"] . ",function(f,v){
	    //alert(\"checking value \" + fieldvalues" . $scriptcondition["field"] . " + \" against \" + fieldokvalues" . $scriptcondition["field"] . ");
	    //alert(jQuery.inArray(fieldvalues" . $scriptcondition["field"] . ",fieldokvalues" . $scriptcondition["field"] . "));
	    if ((jQuery.inArray(v,fieldokvalues" . $scriptcondition["field"] . "))>-1 || (fieldvalues" . $scriptcondition["field"] . " ==fieldokvalues" . $scriptcondition["field"] ." )){
	        newfield" . $field["ref"] . "provisionaltest=true;
	    }
	    });
            if (newfield" . $field["ref"] . "provisionaltest==false){
	         newfield" . $field["ref"] . "provisional=false;}";
	    }
	    echo "
	    exemptfieldsval=jQuery('#exemptfields').val();
	    exemptfieldsarr=exemptfieldsval.split(',');
	    if (newfield" . $field["ref"] . "provisional==true){
	        if (jQuery.inArray(" . $field["ref"] . ",exemptfieldsarr)){
	            exemptfieldsarr.splice(jQuery.inArray(" . $field["ref"] . ", exemptfieldsarr), 1 );
	        }
                newfield" . $field["ref"] . "status='block'
	    }else{
	        if ((jQuery.inArray(" . $field["ref"] . ",exemptfieldsarr))==-1){
	            exemptfieldsarr.push(" . $field["ref"] . ")
	        }
	    }
            jQuery('#exemptfields').val(exemptfieldsarr.join(","));
            ";
	    echo "if (newfield" . $field["ref"] . "status!=field" . $field["ref"] . "status){
                jQuery('#question_" . $n . "').slideToggle();
		if (jQuery('#question_" . $n . "').css('display')=='block'){
                    jQuery('#question_" . $n . "').css('border-top','');
                }else{
                    jQuery('#question_" . $n . "').css('border-top','none');
                }
	    }";
    ?>}
    </script>
    <?php return $displaycondition;
}

# Allows language alternatives to be entered for free text metadata fields.
function display_multilingual_text_field($n, $field, $translations){
global $language, $languages, $lang;?> <p><a href="#" class="OptionToggle" onClick="l=document.getElementById('LanguageEntry_<?php echo $n?>');if (l.style.display=='block') {l.style.display='none';this.innerHTML='<?php echo $lang["showtranslations"]?>';} else {l.style.display='block';this.innerHTML='<?php echo $lang["hidetranslations"]?>';} return false;"><?php echo $lang["showtranslations"]?></a></p>
<table class="OptionTable" style="display:none;" id="LanguageEntry_<?php echo $n?>"><?php reset($languages); 
foreach ($languages as $langkey => $langname){ if ($language!=$langkey){if (array_key_exists($langkey,$translations)) {$transval=$translations[$langkey];} 
else {$transval="";}?><tr><td nowrap valign="top"><?php echo htmlspecialchars($langname)?>&nbsp;&nbsp;</td><?php if ($field["type"]==0){?><td><input type="text" class="stdwidth" name="multilingual_<?php echo $n?>_<?php echo $langkey?>" value="<?php echo htmlspecialchars($transval)?>"></td>
<?php }else{ ?><td><textarea rows=6 cols=50 name="multilingual_<?php echo $n?>_<?php echo $langkey?>"><?php echo htmlspecialchars($transval)?></textarea></td>
<?php } ?></tr><?php }} ?></table><?php }

function display_field($n, $field, $newtab=false){
	global $use, $ref, $original_fields, $multilingual_text_fields, $multiple, $lastrt,$is_template, $language, $lang, $blank_edit_template, $edit_autosave, $errors, $tabs_on_edit,$collapsible_sections, $ctrls_to_save, $embedded_data_user_select, $embedded_data_user_select_fields;
	$name="field_" . $field["ref"];
	$value=$field["value"];
	$value=trim($value);

	if ($field["omit_when_copying"] && $use!=$ref){
		# Omit when copying - return this field back to the value it was originally, instead of using the current value which has been fetched from the new resource.
		reset($original_fields);
		foreach ($original_fields as $original_field){
			if ($original_field["ref"]==$field["ref"]) {$value=$original_field["value"];}
		}
	}
	$displaycondition=true;
	if ($field["display_condition"]!=""){
	    #Check if field has a display condition set
	    $displaycondition=check_display_condition($n,$field);
	}
	if ($multilingual_text_fields){
	    # Multilingual text fields - find all translations and display the translation for the current language.
	    $translations=i18n_get_translations($value);
	    if (array_key_exists($language,$translations)) {$value=$translations[$language];} else {$value="";}
	}
	if ($multiple) {$value="";} # Blank the value for multi-edits.
        if ($field["resource_type"]!=$lastrt && $lastrt!=-1 && $collapsible_sections){?>
            </div><h2 class="CollapsibleSectionHead" id="resource_type_properties">
            <?php echo htmlspecialchars(get_resource_type_name($field["resource_type"]))?> <?php echo $lang["properties"]?></h2><div class="CollapsibleSection" id="ResourceProperties<?php if ($ref==-1) echo "Upload"; ?>
            <?php echo $field["resource_type"]; ?>Section"><?php
        }

	$lastrt=$field["resource_type"];
	# Blank form if 'reset form' has been clicked.
	if (getval("resetform","")!="") {$value="";}

	# If config option $blank_edit_template is set, always show a blank form for user edit templates.
	if ($ref<0 && $blank_edit_template && getval("submitted","")=="") {$value="";}
	if ($multiple && !hook("replace_edit_all_checkbox","",array($field["ref"]))) { # Multiple items, a toggle checkbox appears which activates the question
	?><div class="edit_multi_checkbox"><input name="editthis_<?php echo htmlspecialchars($name)?>" id="editthis_<?php echo $n?>" type="checkbox" value="yes" onClick="var q=document.getElementById('question_<?php echo $n?>');var m=document.getElementById('modeselect_<?php echo $n?>');var f=document.getElementById('findreplace_<?php echo $n?>');if (this.checked) {q.style.display='block';m.style.display='block';} else {q.style.display='none';m.style.display='none';f.style.display='none';document.getElementById('modeselectinput_<?php echo $n?>').selectedIndex=0;}">&nbsp;<label for="editthis<?php echo $n?>"><?php echo htmlspecialchars($field["title"])?></label></div><!-- End of edit_multi_checkbox --><?php } ?>

	<div class="Question" id="question_<?php echo $n?>" <?php
	if ($multiple || !$displaycondition || $newtab)
		{?>style="border-top:none;<?php
		if ($multiple || !$displaycondition){?>display:none;<?php }
		}
		?>">
		<?php
			$labelname = $name;
			// Add _selector to label so it will keep working:
			if($field['type'] == 9) {
				$labelname .= '_selector';
			}

			// Add -d to label so it will keep working
			if($field['type'] == 4) {
				$labelname .= '-d';
			}
		?>
	<label for="<?php echo htmlspecialchars($labelname)?>" >
	<?php if (!$multiple) {?><?php echo htmlspecialchars($field["title"])?>
	<?php if (!$is_template && $field["required"]==1) { ?><sup>*</sup><?php } ?><?php } ?>
	</label>

	<?php
	# Define some Javascript for help actions (applies to all fields)
	$help_js="onBlur=\"HideHelp(" . $field["ref"] . ");return false;\" onFocus=\"ShowHelp(" . $field["ref"] . ");return false;\"";

	#hook to modify field type in special case. Returning zero (to get a standard text box) doesn't work, so return 1 for type 0, 2 for type 1, etc.
	$modified_field_type="";
	$modified_field_type=(hook("modifyfieldtype"));
	if ($modified_field_type){$field["type"]=$modified_field_type-1;}

	hook("addfieldextras");
	# ----------------------------  Show field -----------------------------------
	$type=$field["type"];
	if ($type=="") {$type=0;} # Default to text type.
	if (!hook("replacefield","",array($field["type"],$field["ref"],$n)))
		{
		global $auto_order_checkbox;
		include "edit_fields/" . $type . ".php";
		}
	# ----------------------------------------------------------------------------

	# Display any error messages from previous save
	if (array_key_exists($field["ref"],$errors))
		{
		?>
		<div class="FormError">!! <?php echo $errors[$field["ref"]]?> !!</div>
		<?php
		}

	if (trim($field["help_text"]!=""))
		{
		# Show inline help for this field.
		# For certain field types that have no obvious focus, the help always appears.
		?>
                <div class="FormHelp-selector" onmouseenter="ShowHelp(<?php echo $field["ref"] ?>); return false;" onmouseleave="HideHelp(<?php echo $field["ref"]?>)"><em>i</em></div>
		<div class="FormHelp" style="padding:0; display:none;" id="help_<?php echo $field["ref"]?>">
                <div class="FormHelpInner"><?php echo nl2br(trim(htmlspecialchars(i18n_get_translated($field["help_text"],false))))?></div></div>
		<?php
		}

	# If enabled, include code to produce extra fields to allow multilingual free text to be entered.
	if ($multilingual_text_fields && ($field["type"]==0 || $field["type"]==1 || $field["type"]==5))
		{
		display_multilingual_text_field($n, $field, $translations);
		}
		?>
	<div class="clearerleft"> </div>
	</div><!-- end of question_<?php echo $n?> div -->
	<?php
	hook('afterfielddisplay', '', array($n, $field));
	}
?>
</div>
<?php hook('editbeforesectionhead');

global $collapsible_sections;
if($collapsible_sections){?>
    <div id="CollapsibleSections"><?php
}
for ($n=0;$n<count($fields);$n++)
        {
        if (is_field_displayed($fields[$n]))
                {
                $display_any_fields=true;
                break;
                }
        }
$display_any_fields=false;
$fieldcount=0;
$tabname="";
$tabcount=0;
if ($display_any_fields){?>
 <h2  <?php if($collapsible_sections){echo'class="CollapsibleSectionHead"';}?> id="ResourceMetadataSectionHead"><?php echo $lang["resourcemetadata"]?></h2>
 <div <?php if($collapsible_sections){echo'class="CollapsibleSection"';}?> id="ResourceMetadataSection<?php if ($ref<0) echo "Upload"; ?>"><?php
}

if($tabs_on_edit){
  //  -----------------------------  Draw tabs ---------------------------
  $tabname="";
  $tabcount=0;
  if (count($fields)>0 && $fields[0]["tab_name"]!=""){
    $extra="";
    $tabname="";
    $tabcount=0;
    $tabtophtml="";
    for ($n=0;$n<count($fields);$n++){
      $value=$fields[$n]["value"];
      # draw new tab?
      if ($tabname!=$fields[$n]["tab_name"] && is_field_displayed($fields[$n])){
        if($tabcount==0){$tabtophtml.="<div class=\"BasicsBox\" id=\"BasicsBoxTabs\"><div class=\"TabBar\">";}
        $tabtophtml.="<div id=\"tabswitch" . $tabcount . "\" class=\"Tab";
        if($tabcount==0){$tabtophtml.=" TabSelected ";}
        $tabtophtml.="\"><a href=\"#\" onclick=\"SelectTab(" . $tabcount . ");return false;\">" .  i18n_get_translated($fields[$n]["tab_name"]) . "</a></div>";
        $tabcount++;
        $tabname=$fields[$n]["tab_name"];
      }
    }
    if ($tabcount>1){
      echo $tabtophtml;
      echo "</div><!-- end of TabBar -->";
    }
    if ($tabcount>1){?>
      <script type="text/javascript">
        function SelectTab(tab){
          // Deselect all tabs
          <?php for ($n=0;$n<$tabcount;$n++) { ?>
            jQuery("#tab<?php echo $n?>").hide("blind");
            document.getElementById("tabswitch<?php echo $n?>").className="Tab";
          <?php } ?>
          jQuery("#tab" + tab).show("blind");
          document.getElementById("tabswitch" + tab).className="Tab TabSelected";
        }
      </script>
      <?php
    }
  }
  if ($tabcount>1){?>
     <div id="tab0" class="TabbedPanel<?php if ($tabcount>0) { ?> StyledTabbedPanel<?php } ?>">
     <div class="clearerleft"> </div>
     <div class="TabPanelInner">
     <?php
  }
}
$tabname="";
$tabcount=0;

for ($n=0;$n<count($fields);$n++){
  # Should this field be displayed?
  if (is_field_displayed($fields[$n])){
    if(in_array($fields[$n]['resource_type'], $hide_resource_types)) { continue; }
    $newtab=false;
    if($n==0 && $tabs_on_edit){$newtab=true;}

    # draw new tab panel?
    if ($tabs_on_edit && ($tabname!=$fields[$n]["tab_name"]) && ($fieldcount>0)){
      $tabcount++;
      # Also display the custom formatted data $extra at the bottom of this tab panel.
      ?>
      <div class="clearerleft"> </div>
      <? echo $extra?>
      </div><!-- end of TabPanelInner -->
      </div><!-- end of TabbedPanel -->
      <div class="TabbedPanel StyledTabbedPanel" style="display:none;" id="tab<?php echo $tabcount?>">
        <div class="TabPanelInner"><?php
			$extra="";
			$newtab=true;
    }
    $tabname=$fields[$n]["tab_name"];
    $fieldcount++;
    display_field($n, $fields[$n], $newtab);
  }
}
if ($tabs_on_edit && $tabcount>0){?>
  <div class="clearerleft"> </div>
  </div><!-- end of TabPanelInner -->
  </div><!-- end of TabbedPanel -->
  </div><!-- end of Tabs BasicsBox -->
  <?php
}

# Add required_fields_exempt so it is submitted with POST
echo " <input type=hidden name=\"exemptfields\" id=\"exemptfields\" value=\"" . implode(",",$required_fields_exempt) . "\">";	

# Work out the correct archive status.
if ($ref<0){ # Upload template.
    $modified_defaultstatus = hook("modifydefaultstatusmode");
        if ($modified_defaultstatus) {$status = $modified_defaultstatus;}  # Set the modified default status - if set.
        elseif ($resource["archive"]!=2 && checkperm("e" . $resource["archive"])) {$status = $resource["archive"];} # Set status to the status stored in the user template - if the status is not Archived and if the user has the required permission.
        elseif (checkperm("c")) {$status = 0;} # Else, set status to Active - if the user has the required permission.
        elseif (checkperm("d")) {$status = -2;} # Else, set status to Pending Submission.   
    if ($show_status_and_access_on_upload==false){
        # Hide the dropdown, and set the default status.
        ?>
        <input type=hidden name="archive" id="archive" value="<?php echo htmlspecialchars($status)?>"><?php
    }
}
# Status / Access / Related Resources
if ($show_status_and_access_on_upload_perm &&!hook("editstatushide")){
    # Only display Status / Access / Related Resources if permissions match.
    hook("statreladdtopfields");
    # Access
    hook("beforeaccessselector");
    if (!hook("replaceaccessselector")){
        if ($ref<0 && $show_status_and_access_on_upload=== false && ($show_access_on_upload === false || ($show_access_on_upload === true && !$show_access_on_upload_perm))){
            # Upload template and the status and access fields are configured to be hidden on uploads.
            ?>
            <input type=hidden name="access" value="<?php echo htmlspecialchars($resource["access"])?>"><?php
        }else{
            if ($multiple) { ?><div><input name="editthis_access" id="editthis_access" value="yes" type="checkbox" onClick="var q=document.getElementById('question_access');if (q.style.display!='block') {q.style.display='block';} else {q.style.display='none';}">&nbsp;<label for="editthis<?php echo $n?>"><?php echo $lang["access"]?></label></div><?php } ?>
            <div class="Question" id="question_access" <?php if ($multiple) {?>style="display:none;"<?php } ?>>
            <label for="archive"><?php echo $lang["access"]?></label>
            <select class="stdwidth" name="access" id="access" onChange="var c=document.getElementById('custom_access');if (this.value==3) {c.style.display='block';} else {c.style.display='none';}<?php if ($edit_autosave) {?>AutoSave('Access');<?php } ?>"><?php
            for ($n=0;$n<=($custom_access?3:2);$n++){
                if ($n==2 && checkperm("v")){ ?>
                    <option value="<?php echo $n?>" <?php if ($resource["access"]==$n) { ?>selected<?php } ?>><?php echo $lang["access" . $n]?></option><?php
                }else if ($n!=2){ ?>
                    <option value="<?php echo $n?>" <?php if ($resource["access"]==$n) { ?>selected<?php } ?>><?php echo $lang["access" . $n]?></option><?php
                }
            } ?>
            </select>
            <div class="clearerleft"> </div>
            </div><?php
            }
        } /* end hook replaceaccessselector */
}
?>
  <!--</div>-->
	<?php if (!hook('replacesubmitbuttons')) { ?>
	    <div class="QuestionSubmit">
	        <input <?php if ($multiple) { ?>onclick="return confirm('<?php echo $lang["confirmeditall"]?>');"<?php } ?> id="savebtn" name="save" type="submit" value="Save Resource" /><br><br>
	        <div class="clearerleft"> </div>
	    </div>
	<?php } ?>

<?php
    if($collapsible_sections){
	?>
	</div><!-- end of collapsible section -->
	<?php
    }
    if($multiple){echo "</div>";} ?>
<div style="display:none" id="upload-log">
    <div id="upload-log-msg"></div>
    <div style="display:none; opacity:0;" id="success-actions"></div>
</div>

<div class="clearer"></div>
</form>
<?php
hook("autolivejs");
?>
<div class="clearer"></div>

<?php
include_once("../include/footer.php");
?>
