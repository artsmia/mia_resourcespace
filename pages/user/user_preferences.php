<?php

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";

$userpreferences_plugins= array();
$plugin_names=array();
$plugins_dir = dirname(__FILE__)."/../../plugins/";
foreach($active_plugins as $plugin)
	{
	$plugin = $plugin["name"];
	array_push($plugin_names,trim(mb_strtolower($plugin)));
	$plugin_yaml = get_plugin_yaml($plugins_dir.$plugin.'/'.$plugin.'.yaml', false);
	if(isset($plugin_yaml["userpreferencegroup"]))
		{
		$upg = trim(mb_strtolower($plugin_yaml["userpreferencegroup"]));
		$userpreferences_plugins[$upg][$plugin]=$plugin_yaml;
		}
	}

if(getvalescaped("quicksave",FALSE))
	{
	$ctheme = getvalescaped("colour_theme","");
	if($ctheme==""){exit("missing");}
	$ctheme = preg_replace("/^col-/","",trim(mb_strtolower($ctheme)));
	if($ctheme =="default")
		{
		if(empty($userpreferences))
			{
			// create a record
			sql_query("INSERT INTO user_preferences (user, parameter, `value`) VALUES (" . $userref . ", 'colour_theme', NULL);");
			rs_setcookie("colour_theme", "",100, "/", "", substr($baseurl,0,5)=="https", true);
			exit("1");
			}
		else
			{
			sql_query("UPDATE user_preferences SET `value` = NULL WHERE user = " . $userref . " AND parameter = 'colour_theme';");
			rs_setcookie("colour_theme", "",100, "/", "", substr($baseurl,0,5)=="https", true);
			exit("1");
			}
		}
	if(in_array("col-".$ctheme,$plugin_names))
		{
		// check that record exists for user
		if(empty($userpreferences))
			{
			// create a record
			sql_query("INSERT into user_preferences (user, parameter, `value`) VALUES (" . $userref . ", 'colour_theme', '" . escape_check(preg_replace('/^col-/', '', $ctheme)) . "');");
			rs_setcookie("colour_theme", escape_check(preg_replace("/^col-/","",$ctheme)),100, "/", "", substr($baseurl,0,5)=="https", true);
			exit("1");
			}
		else
			{
			sql_query("UPDATE user_preferences SET `value` = '" . escape_check(preg_replace('/^col-/', '', $ctheme)) . "' WHERE user = " . $userref . " AND parameter = 'colour_theme';");
			rs_setcookie("colour_theme", escape_check(preg_replace("/^col-/","",$ctheme)),100, "/", "", substr($baseurl,0,5)=="https", true);
			exit("1");
			}
		}

	exit("0");
	}

include "../../include/header.php";
?>
<div class="BasicsBox"> 
  	<h1><?php echo $lang["userpreferences"]?></h1>
  	<p><?php echo $lang["modifyuserpreferencesintro"]?></p>
  	
	<?php
	/* Display */
	$options_available = 0; # Increment this to prevent a "No options available" message

	/* User Colour Theme Selection */
	if((isset($userfixedtheme) && $userfixedtheme=="") && isset($userpreferences_plugins["colourtheme"]) && count($userpreferences_plugins["colourtheme"])>0)
		{ ?>
		<div class="Question">
			<label for="">
				<?php echo $lang["userpreferencecolourtheme"]; ?>
			</label>
			<script>
				function updateColourTheme(theme) {
					jQuery.post(
						window.location,
						{"colour_theme":theme,"quicksave":"true"},
						function(data){
							location.reload();
						});
				}
			</script>
			<?php
			# If there are multiple options provide a radio button selector
			if(count($userpreferences_plugins["colourtheme"])>1)
				{ ?>
				<table id="" class="radioOptionTable">
					<tbody>
						<tr>
						<!-- Default option -->
						<td valign="middle">
		                    <input 
		                    	type="radio" 
		                    	name="defaulttheme" 
		                    	value="default" 
		                    	onChange="updateColourTheme('default');"
		                    	<?php
		                    		if
		                    		(
		                    			(isset($userpreferences["colour_theme"]) && $userpreferences["colour_theme"]=="") 
		                    			|| 
		                    			(!isset($userpreferences["colour_theme"]) && $defaulttheme=="")
		                    		) { echo "checked";}
		                    	?>
		                    />
		                </td>
		                <td align="left" valign="middle">
		                    <label class="customFieldLabel" for="defaulttheme">
		                    	<?php echo $lang["default"];?>
		                    </label>
		                </td>
						<?php
						foreach($userpreferences_plugins["colourtheme"] as $colourtheme)
							{ ?>
							<td valign="middle">
			                    <input 
			                    	type="radio" 
			                    	name="defaulttheme" 
			                    	value="<?php echo preg_replace("/^col-/","",$colourtheme["name"]);?>" 
			                    	onChange="updateColourTheme('<?php echo preg_replace("/^col-/","",$colourtheme["name"]);?>');"
			                    	<?php
			                    		if
			                    		(
			                    			(isset($userpreferences["colour_theme"]) && "col-".$userpreferences["colour_theme"]==$colourtheme["name"]) 
			                    			|| 
			                    			(!isset($userpreferences["colour_theme"]) && $defaulttheme==$colourtheme["name"])
			                    		) { echo "checked";}
			                    	?>
			                    />
			                </td>
			                <td align="left" valign="middle">
			                    <label class="customFieldLabel" for="defaulttheme">
			                    	<?php echo $colourtheme["name"];?>
			                    </label>
			                </td>
			                <?php
							}
						?>
						</tr>
	            	</tbody>
			    </table>
	    		<?php
				}
			?>
			<div class="clearerleft"> </div>
		</div>
		<?php
		$options_available++;
		}
	/* End User Colour Theme Selection */

	/* Default display if there are no options available */
	if($options_available == 0)
		{ ?>
		<div class="FormError"><?php echo $lang["no-options-available"];?></div>
		<?php
		} 
	?>
</div>

<?php
include "../../include/footer.php";

