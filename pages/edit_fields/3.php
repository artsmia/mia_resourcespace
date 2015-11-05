<?php /* -------- Drop down list ------------------ */ 
$auto_order_checkbox_case_insensitive = false;
# Translate all options
$options=trim_array(explode(",",$field["options"]));
$modified_options=hook("modify_field_options","",array($field));
if($modified_options!=""){$options=$modified_options;}
$adjusted_dropdownoptions=hook("adjustdropdownoptions","",array($field,$options));
if ($adjusted_dropdownoptions){$options=$adjusted_dropdownoptions;}

$option_trans=array();
for ($m=0;$m<count($options);$m++)
	{
	$option_trans[$options[$m]]=i18n_get_translated($options[$m]);
	}
if ($auto_order_checkbox && !hook("ajust_auto_order_checkbox","",array($field))) {
	if($auto_order_checkbox_case_insensitive){natcasesort($option_trans);}
	else{asort($option_trans);}	
}
$adjusted_dropdownoptiontrans=hook("adjustdropdownoptiontrans","edit",array($field,$option_trans));
if ($adjusted_dropdownoptiontrans){$option_trans=$adjusted_dropdownoptiontrans;}

if (substr($value,0,1) == ',') { $value = substr($value,1); }	// strip the leading comma if it exists
if(!checkperm("ro-".$field["ref"])){
?><select class="stdwidth" name="<?php echo $name?>" id="<?php echo $name?>" <?php echo $help_js; hook("additionaldropdownattributes","",array($field)); ?>
<?php if ($edit_autosave) {?>onChange="AutoSave('<?php echo $field["ref"] ?>');"<?php } ?>
>
<?php if (!hook("replacedropdowndefault","",array($field)))
	{ 
	if(empty($value)) {
		$value = (empty($options[0])) ? "" : $options[0];
	}
	?><option value=""></option><?php
	} ?>
<?php
foreach ($option_trans as $option=>$trans)
	{
	if (trim($option)!="")
		{
		?>
		<option value="<?php echo htmlspecialchars(trim($option))?>" <?php if (trim($option)==trim($value)) {?>selected<?php } ?>><?php echo htmlspecialchars(trim($trans))?></option>
		<?php
		}
	}
?></select><?php
}else{
    if($value != "" && $value != ","){
       echo($value);
       ?>
       <input type="hidden" name="field_<?php echo $field['ref']?>" value="<?php echo $value?>"/>
       <?php
    }else{
      echo("N/A");
    }
}?>
