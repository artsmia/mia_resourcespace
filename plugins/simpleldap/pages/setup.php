<?php
include "../../../include/db.php";
include "../../../include/authenticate.php"; if (!checkperm("u")) {exit ("Permission denied.");}
include "../../../include/general.php";

if (getval("submit","")!="")
	{

$simpleldap['fallbackusergroup'] = getvalescaped('fallbackusergroup','');
$simpleldap['domain'] = getvalescaped('domain','');
$simpleldap['emailsuffix'] = getvalescaped('emailsuffix','');
$simpleldap['ldapserver'] = getvalescaped('ldapserver','');
$simpleldap['port'] = getvalescaped('port','');;
$simpleldap['basedn']= getvalescaped('basedn','');
$simpleldap['loginfield'] = getvalescaped('loginfield','');
$simpleldap['usersuffix'] = getvalescaped('usersuffix','');
$simpleldap['createusers'] = getvalescaped('createusers','');
$simpleldap['ldapgroupfield'] = getvalescaped('ldapgroupfield','');
$simpleldap['email_attribute'] = getvalescaped('email_attribute','');
$simpleldap['phone_attribute'] = getvalescaped('phone_attribute','');
$simpleldap['update_group'] = getvalescaped('update_group','');

$ldapgroups = $_REQUEST['ldapgroup'];
$rsgroups = $_REQUEST['rsgroup'];
$priority = $_REQUEST['priority'];

if (count($ldapgroups) > 0){
	sql_query('delete from simpleldap_groupmap where rsgroup is not null');
}

for ($i=0; $i < count($ldapgroups); $i++){
	if ($ldapgroups[$i] <> '' && $rsgroups[$i] <> '' && is_numeric($rsgroups[$i])){
		$query = "replace into simpleldap_groupmap (ldapgroup,rsgroup,priority) values ('" . escape_check($ldapgroups[$i]) . "','" . $rsgroups[$i] . "' ,'" . $priority[$i] ."')";
		sql_query($query);		
	}
} 


	$config['simpleldap'] = $simpleldap;

	set_plugin_config("simpleldap",$config);
	redirect("pages/team/team_plugins.php");
	}



// retrieve list if groups for use in mapping dropdown
$rsgroups = sql_query('select ref, name from usergroup order by name asc');

include "../../../include/header.php";

// if some of the values aren't set yet, fudge them so we don't get an undefined error
// this may be important for updates to the plugin that introduce new variables
foreach (array('ldapserver','domain','port','basedn','loginfield','usersuffix','emailsuffix','fallbackusergroup','email_attribute','phone_attribute','update_group') as $thefield){
	if (!isset($simpleldap[$thefield])){
		$simpleldap[$thefield] = '';
	}
}



?>
<div class="BasicsBox"> 
  <h2>&nbsp;</h2>
  <h1>SimpleLDAP Configuration</h1>

<form id="form1" name="form1" method="post" action="">

<?php echo config_text_field("ldapserver",$lang['ldapserver'],$simpleldap['ldapserver'],60);?>
<?php echo config_text_field("domain",$lang['domain'],$simpleldap['domain'],60);?>
<?php echo config_text_field("emailsuffix",$lang['emailsuffix'],$simpleldap['emailsuffix'],60);?>
<?php echo config_text_field("email_attribute",$lang['email_attribute'],$simpleldap['email_attribute'],60);?>
<?php echo config_text_field("phone_attribute",$lang['phone_attribute'],$simpleldap['phone_attribute'],60);?>
<?php echo config_text_field("port",$lang['port'],$simpleldap['port'],5);?>
<?php echo config_text_field("basedn",$lang['basedn'],$simpleldap['basedn'],60);?>
<?php echo config_text_field("loginfield",$lang['loginfield'],$simpleldap['loginfield'],30);?>
<?php echo config_text_field("usersuffix",$lang['usersuffix'],$simpleldap['usersuffix'],30);?>
<?php echo config_text_field("ldapgroupfield",$lang['groupfield'],$simpleldap['ldapgroupfield'],30);?>
<?php echo config_boolean_field("createusers",$lang['createusers'],$simpleldap['createusers'],30);?>
<?php echo config_boolean_field("update_group",$lang['simpleldap_update_group'],$simpleldap['update_group'],30);?>


<div class="Question">
	<label for="fallbackusergroup"><?php echo $lang['fallbackusergroup']; ?></label>
	<select name='fallbackusergroup'><option value=''></option>
	<?php 	
		foreach ($rsgroups as $rsgroup){
			echo  "<option value='" . $rsgroup['ref'] . "'";
			if ($simpleldap['fallbackusergroup'] == $rsgroup['ref']){
				echo " selected";
			}
			echo ">". $rsgroup['name'] . "</option>\n";
		} 
 	?></select>
</div>
<div class="clearerleft"></div>



<div class="Question">
<h3><?php echo $lang['ldaprsgroupmapping']; ?></h3>
<table id='groupmaptable'>
<tr><th>
<strong><?php echo $lang['ldapvalue']; ?></strong>
</th><th>
<strong><?php echo $lang['rsgroup']; ?></strong>
</th><th>
<strong><?php echo $lang['simpleldappriority']; ?></strong>
</th>
</tr>

<?php
	$grouplist = sql_query('select ldapgroup,rsgroup, priority from simpleldap_groupmap order by priority desc');
	for($i = 0; $i < count($grouplist)+1; $i++){
		if ($i >= count($grouplist)){
			$thegroup = array();
			$thegroup['ldapgroup'] = '';
			$thegroup['rsgroup'] = '';
			$thegroup['priority'] = '';
			$rowid = 'groupmapmodel';
		} else {
			$thegroup = $grouplist[$i];
			$rowid = "row$i";
		}
?>
<tr id='<?php echo $rowid; ?>'>
   <td><input type='text' name='ldapgroup[]' value='<?php echo $thegroup['ldapgroup']; ?>' /></td>
   <td><select name='rsgroup[]'><option value=''></option>
	<?php 	
		foreach ($rsgroups as $rsgroup){
			echo  "<option value='" . $rsgroup['ref'] . "'";
			if ($thegroup['rsgroup'] == $rsgroup['ref']){
				echo " selected";
			}
			echo ">". $rsgroup['name'] . "</option>\n";
		} 
 	?></select>
    </td>
    <td><input type='text' name='priority[]' value='<?php echo $thegroup['priority']; ?>' /></td>
</tr>
<?php } ?>
</table>

<a onclick='addGroupMapRow()'><?php echo $lang['addrow']; ?></a>
</div>


<div class="Question">  
<label for="submit"></label> 
<input type="submit" name="submit" value="<?php echo $lang["save"]?>">   
</div><div class="clearerleft"></div>

</form>
</div>	

<script language="javascript">
        function addGroupMapRow() {
 
            var table = document.getElementById("groupmaptable");
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            row.innerHTML = document.getElementById("groupmapmodel").innerHTML;
        }
</script> 



<?php include "../../../include/footer.php";
