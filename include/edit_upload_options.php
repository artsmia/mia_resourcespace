<?php 
if(!hook("replaceuploadoptions")):	
if ($ref<0)
	{
	if($tabs_on_edit)
		{
		?></div><h1><?php echo $lang["upload-options"] ?></h1>
		<div id="UploadOptionsSection">
		<?php
		}
	else
		{		
		?></div><h2 class="CollapsibleSectionHead"><?php echo $lang["upload-options"] ?></h2>
		<div class="CollapsibleSection" id="UploadOptionsSection">
		<?php
		}
	if(!$embedded_data_user_select)
	    {
	    if ($metadata_read){?>
	    <div class="Question" id="question_noexif">
	    <label for="no_exif"><?php echo $lang["no_exif"]?></label><input type=checkbox id="no_exif" name="no_exif" value="yes" <?php if (getval("no_exif",($metadata_read_default)?"":"no")!="") { ?>checked<?php } ?>>
	    <div class="clearerleft"> </div>
	    </div>
	    <?php } elseif ($no_metadata_read_default) 
		    {?>
	    <input type=hidden id="no_exif" name="no_exif" value="">
	    <?php } 
	     else { ?>
	    <input type=hidden id="no_exif" name="no_exif" value="no">
	    <?php } 
	    }
	if($enable_related_resources && $relate_on_upload && $ref<0 && !$multiple) # When uploading
        {
        ?>
        <div class="Question" id="question_related">
        <label for="relateonupload"><?php echo $lang["relatedresources_onupload"]?></label>
        <input name="relateonupload" id="relateonupload" type="checkbox" value="1" style="margin-top:7px;" <?php if($relate_on_upload_default){echo " checked ";} ?>/> 
        <div class="clearerleft"> </div>
        </div><?php
        }
	if($camera_autorotation){ ?>
	<div class="Question" id="question_autorotate">
	<label for="autorotate"><?php echo $lang["autorotate"]?></label><input type=checkbox id="autorotate" name="autorotate" value="yes" <?php
	if ($camera_autorotation_checked) {echo ' checked';}?>>
	<div class="clearerleft"> </div>
	</div>
	<?php } // end if camera autorotation ?>

	<?php if (getval("single","")=="") { 

	# Add Resource Batch: specify default content - also ask which collection to add the resource to.
	if ($enable_add_collection_on_upload) 
		{
	    $collection_add=getvalescaped("collection_add","");
		?>
		<div class="Question" id="question_collectionadd">
		<label for="collection_add"><?php echo $lang["addtocollection"]?></label>
		<select name="collection_add" id="collection_add" class="stdwidth">
		
		<?php if ($upload_add_to_new_collection_opt && $collection_allow_creation) { ?><option value="-1" <?php if ($upload_add_to_new_collection){ ?>selected <?php }?>>(<?php echo $lang["createnewcollection"]?>)</option><?php } ?>
		<?php if ($upload_do_not_add_to_new_collection_opt && !hook("remove_do_not_add_to_collection")) { ?><option value="" <?php if (!$upload_add_to_new_collection || $do_not_add_to_new_collection_default){ ?>selected <?php }?>><?php echo $lang["batchdonotaddcollection"]?></option><?php } ?>
		
		<?php
		if ($upload_force_mycollection)
			{
			$list=get_user_collections($userref,"My Collection");}
		else
			{$list=get_user_collections($userref);}
		$currentfound=false;
		
	        // make sure it's possible to set the collection with collection_add (compact style "upload to this collection"
	        if ($collection_add!="" && getval("resetform","")=="" && (!isset($save_errors) || !$save_errors))
	               {
	               # Switch to the selected collection (existing or newly created) and refresh the frame.
	               set_user_collection($userref,$collection_add);
	               refresh_collection_frame($collection_add);
	               }
	               
	               
		for ($n=0;$n<count($list);$n++)
			{
			if ($collection_dropdown_user_access_mode){    
	                $colusername=$list[$n]['fullname'];
	                
	                # Work out the correct access mode to display
	                if (!hook('collectionaccessmode')) {
	                    if ($list[$n]["public"]==0){
	                        $accessmode= $lang["private"];
	                    }
	                    else{
	                        if (strlen($list[$n]["theme"])>0){
	                            $accessmode= $lang["theme"];
	                        }
	                    else{
	                            $accessmode= $lang["public"];
	                        }
	                    }
	                }
	            }	
				
			
			#remove smart collections as they cannot be uploaded to.
			if (!isset($list[$n]['savedsearch'])||(isset($list[$n]['savedsearch'])&&$list[$n]['savedsearch']==null)){
				#show only active collections if a start date is set for $active_collections 
				if (strtotime($list[$n]['created']) > ((isset($active_collections))?strtotime($active_collections):1))
					{ if ($list[$n]["ref"]==$usercollection) {$currentfound=true;} ?>
					<option value="<?php echo $list[$n]["ref"]?>" <?php if ($list[$n]['ref']==$collection_add) {?> 	selected<?php } ?>><?php echo i18n_get_collection_name($list[$n]) ?> <?php if ($collection_dropdown_user_access_mode){echo htmlspecialchars("(". $colusername."/".$accessmode.")"); } ?></option>
					<?php }
			
				}
			}
		if (!$currentfound && !$upload_force_mycollection)
			{
			# The user's current collection has not been found in their list of collections (perhaps they have selected a theme to edit). Display this as a separate item.
			$cc=get_collection($usercollection);
			if ($cc!==false)
				{$currentfound=true;
				?>
				<option value="<?php echo htmlspecialchars($usercollection) ?>" <?php if ($usercollection==$collection_add){?>selected <?php } ?>><?php echo i18n_get_collection_name($cc) ?></option>
				<?php
				}
			}
		?>
		</select>
	
		<div class="clearerleft"> </div>
		<div name="collectioninfo" id="collectioninfo" style="display:none;">
		<div name="collectionname" id="collectionname" <?php if ($upload_add_to_new_collection_opt){ ?> style="display:block;"<?php } else { ?> style="display:none;"<?php } ?>>
		<label for="entercolname"><?php echo $lang["collectionname"]?><?php if ($upload_collection_name_required){?><sup>*</sup><?php } ?></label>
		<input type=text id="entercolname" name="entercolname" class="stdwidth" value='<?php echo htmlentities(stripslashes(getval("entercolname","")), ENT_QUOTES);?>'> 
		
		</div>		
		
		<?php if ($enable_public_collection_on_upload && ($enable_public_collections || checkperm('h')) && !checkperm('b')) { ?>
		<label for="public"><?php echo $lang["access"]?></label>
		<select id="public" name="public" class="shrtwidth"  <?php
			if (checkperm('h')){ // if the user can add to a theme, include the code to toggle the theme selector
			?>onchange="if(jQuery(this).val()==1){jQuery('#themeselect').fadeIn();resetThemeLevels();} else {jQuery('#themeselect').fadeOut(); clearThemeLevels();}"<?php 
			}
		?>>
		<option value="0" selected><?php echo $lang["private"]?></option>
		<option value="1"><?php echo $lang["public"]?></option>
		</select>
	
		
		<?php 
		if (checkperm('h')){ 
		// if the user can add to a theme, include the theme selector
		?>
			<!-- select theme if collection is public -->
			<script type="text/javascript" src="../lib/js/update_theme_levels.js"></script>
			<input type="hidden" name="themestring" id="themestring" value="" />
			<div id='themeselect' class='themeselect' style="display:none">
				<?php 
					include_once("ajax/themelevel_add.php"); 
				?>
			</div>
			<!-- end select theme -->
			<?php 	
			} // end if checkperm h 
		} // end if public collections enabled
	?>
	</div> <!-- end collectioninfo -->
	</div> <!-- end question_collectionadd -->
	<?php
	} // end enable_add_collection_on_upload
	?>
	
	
		<?php
		}
	hook("extrauploadoptions");
	}
?>

<?php
endif; # hook replaceuploadoptions
