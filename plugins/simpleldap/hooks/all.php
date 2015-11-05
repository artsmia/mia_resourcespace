<?php

include_once(dirname(__FILE__) . "/../include/simpleldap_functions.php");


function HookSimpleldapAllExternalauth($uname, $pword){
	global $simpleldap;
	global $username;
	global $password_hash, $email_attribute, $phone_attribute;
	
	// oops - the password is getting escaped earlier in the process, and we don't want that 
    // when it goes to the ldap server. So remove the slashes for this purpose.
    $pword = stripslashes($pword);
	
	$auth = false;

	if ($uname != "" && $pword != "") {
		$userinfo = simpleldap_authenticate($uname, $pword);
		//print_r($userinfo);
		if ($userinfo) { $auth = true; }
	} 


		
	if ($auth) {

		$usersuffix = $simpleldap['usersuffix'];
		$username=escape_check($uname . "." . $usersuffix);
		$password_hash= md5("RS".$username.$pword);
		$userid = sql_value("select ref value from user where username='".$uname.".".$usersuffix."'",0);
		$email=escape_check($userinfo["email"]);
		$phone=escape_check($userinfo["phone"]);
		$displayname=escape_check($userinfo['displayname']);
		debug ("LDAP - got user details email: " . $email . ", telephone: " . $phone);
		// figure out group
		$group = $simpleldap['fallbackusergroup'];
		$grouplist = sql_query("select * from simpleldap_groupmap");
		if (count($grouplist)>0){
			for ($i = 0; $i < count($grouplist); $i++){
				if (($userinfo['group'] == $grouplist[$i]['ldapgroup']) && is_numeric($grouplist[$i]['rsgroup'])){
					$group = $grouplist[$i]['rsgroup'];
				}
			}
		}


		if ($userid > 0){
			// user exists, so update info
			if($simpleldap['update_group'])
				{
				sql_query("update user set password = '$password_hash', usergroup = '$group', fullname='$displayname', email='$email', telephone='$phone' where ref = '$userid'");
				
				}
			else
				{
				sql_query("update user set password = '$password_hash', fullname='$displayname', email='$email', telephone='$phone' where ref = '$userid'");
				}
			return true;
		} else {
			// user authenticated, but does not exist, so create if necessary
			if ($simpleldap['createusers']){	
				// Create the user
				 $ref=new_user($username);
				 if (!$ref) { echo "returning false!"; exit; return false;} // this shouldn't ever happen
				 // Update with information from LDAP	
				
				sql_query("update user set password='$password_hash', fullname='$displayname',email='$email',telephone='$phone',usergroup='$group',comments='Auto create from SimpleLDAP.' where ref='$ref'");
				return true;
			} else {
				// user creation is disabled, so return false
				return false;
			}

		}
	

	} else {
		// user is not authorized
		return false;
	}


}
		
?>
