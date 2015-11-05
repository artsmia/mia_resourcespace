<?php
include "../include/db.php";
include "../include/general.php";
$k=getvalescaped("k","");if (($k=="") || (!check_access_key(getvalescaped("ref",""),$k))) {include_once "../include/authenticate.php";}
include "../include/request_functions.php";
include "../include/resource_functions.php";
include_once "../include/collections_functions.php";

$ref=getvalescaped("ref","",true);
$error=false;
hook("addcustomrequestfields");

if ($k == "" && isset($anonymous_login) && $username == $anonymous_login){
	$user_is_anon = true;
} else {
	$user_is_anon = false;
}

if (getval("save","")!="")
	{
	if ($k!="" || $user_is_anon || $userrequestmode==0)
		{
		# Request mode 0 : Simply e-mail the request.
		if (($k!="" || $user_is_anon) && (getval("fullname","")=="" || getvalescaped("email","")==""))
			{
			$result=false; # Required fields not completed.
			}
		else
			{
                        $tmp = hook("emailresourcerequest"); if($tmp): $result = $tmp; else:
			$result=email_resource_request($ref,getvalescaped("request",""));
                        endif;
			}
		}
	else
		{
		# Request mode 1 : "Managed" mode via Manage Requests / Orders
                $tmp = hook("manresourcerequest"); if($tmp): $result = $tmp; else:
		$result=managed_collection_request($ref,getvalescaped("request",""),true);
                endif;
		}
	
	if ($result===false)
		{
		$error=$lang["requiredfields"];
		}
	else
		{
		redirect("pages/done.php?text=resource_request&resource=" . urlencode($ref) . "&k=" . urlencode($k));
		}
	}
include "../include/header.php";
?>

<div class="BasicsBox"> 
  <h1><?php echo $lang["requestresource"]?></h1>
  <p><?php echo text("introtext")?></p>
  
	<form method="post" action="<?php echo $baseurl_short?>pages/resource_request.php">  
	<input type="hidden" name="k" value="<?php echo htmlspecialchars($k); ?>">
	<input type="hidden" name="ref" value="<?php echo htmlspecialchars($ref)?>">
	
	<div class="Question">
	<label><?php echo $lang["resourceid"]?></label>
	<div class="Fixed"><?php echo htmlspecialchars($ref)?></div>
	<div class="clearerleft"> </div>
	</div>
	
	<?php if ($k!="" || $user_is_anon) { ?>
	<div class="Question">
	<label><?php echo $lang["fullname"]?> <sup>*</sup></label>
	<input type="hidden" name="fullname_label" value="<?php echo $lang["fullname"]?>">
	<input name="fullname" class="stdwidth" value="<?php echo htmlspecialchars(getvalescaped("fullname","")) ?>">
	<div class="clearerleft"> </div>
	</div>
	
	<div class="Question">
	<label><?php echo $lang["emailaddress"]?> <sup>*</sup></label>
	<input type="hidden" name="email_label" value="<?php echo $lang["emailaddress"]?>">
	<input name="email" class="stdwidth" value="<?php echo htmlspecialchars(getvalescaped("email","")) ?>">
	<div class="clearerleft"> </div>
	</div>

	<div class="Question">
	<label><?php echo $lang["contacttelephone"]?></label>
	<input type="hidden" name="contact_label" value="<?php echo $lang["contacttelephone"]?>">
	<input name="contact" class="stdwidth" value="<?php echo htmlspecialchars(getvalescaped("contact","")) ?>">
	<div class="clearerleft"> </div>
	</div>
	<?php } ?>

	<div class="Question">
	<label for="request"><?php echo $lang["requestreason"]?> <sup>*</sup></label>
	<textarea class="stdwidth" name="request" id="request" rows=5 cols=50><?php echo htmlspecialchars(getvalescaped("request","")) ?></textarea>
	<div class="clearerleft"> </div>
	</div>

<?php # Add custom fields 
if (isset($custom_request_fields))
	{
	$custom=explode(",",$custom_request_fields);
	$required=explode(",",$custom_request_required);
	
	for ($n=0;$n<count($custom);$n++)
		{
		$type=1;
		
		# Support different question types for the custom fields.
		if (isset($custom_request_types[$custom[$n]])) {$type=$custom_request_types[$custom[$n]];}
		
		if ($type==4)
			{
			# HTML type - just output the HTML.
			echo $custom_request_html[$custom[$n]];
			}
		else
			{
			?>
			<div class="Question">
			<label for="custom<?php echo $n?>"><?php echo htmlspecialchars(i18n_get_translated($custom[$n]))?>
			<?php if (in_array($custom[$n],$required)) { ?><sup>*</sup><?php } ?>
			</label>
			
			<?php if ($type==1) {  # Normal text box
			?>
			<input type=text name="custom<?php echo $n?>" id="custom<?php echo $n?>" class="stdwidth" value="<?php echo htmlspecialchars(getvalescaped("custom" . $n,""))?>">
			<?php } ?>

			<?php if ($type==2) { # Large text box 
			?>
			<textarea name="custom<?php echo $n?>" id="custom<?php echo $n?>" class="stdwidth" rows="5"><?php echo htmlspecialchars(getvalescaped("custom" . $n,""))?></textarea>
			<?php } ?>

			<?php if ($type==3) { # Drop down box
			?>
			<select name="custom<?php echo $n?>" id="custom<?php echo $n?>" class="stdwidth">
			<?php foreach ($custom_request_options[$custom[$n]] as $option)
				{
				$val=i18n_get_translated($option);
				?>
				<option <?php if (getval("custom" . $n,"")==$val) { ?>selected<?php } ?>><?php echo htmlspecialchars($val);?></option>
				<?php
				}
			?>
			</select>
			<?php } ?>
			
			<div class="clearerleft"> </div>
			</div>
			<?php
			}
		}
	}
?>

	<div class="QuestionSubmit">
	<?php if ($error) { ?><div class="FormError">!! <?php echo $error ?> !!</div><?php } ?>
	<label for="buttons"> </label>			
	<input name="cancel" type="button" value="&nbsp;&nbsp;<?php echo $lang["cancel"]?>&nbsp;&nbsp;" onclick="document.location='view.php?ref=<?php echo htmlspecialchars($ref)?>';"/>&nbsp;
	<input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["requestresource"]?>&nbsp;&nbsp;" />
	</div>
	</form>
	
</div>

<?php
include "../include/footer.php";
?>
