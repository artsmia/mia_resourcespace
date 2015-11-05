<?php

/* Note to tinkerers: To create your own custom authentication function, simply replace the function below
   with one of your own design. It needs to return false if the user is not authenticated,
   or an associative array if the user is ok. The array looks like so:
        Array
        (
           [username] => jdoe
           [displayname] => John Doe
           [group] => Marketing
           [email] => doe@acmewidget.com
        )

	The group returned here will be matched up to RS groups using the matching table configured by the user.
	If there is no match, the fallback user group will be used.
*/

function simpleldap_authenticate($username,$password){
	// given a username and password, return false if not authenticated, or 
	// associative array of displayname, username, e-mail, group if valid

	global $simpleldap;
	debug("LDAP - Connecting to LDAP server: " . $simpleldap['ldapserver'] . " on port " . $simpleldap['port']);
	$ds = ldap_connect( $simpleldap['ldapserver'],$simpleldap['port'] );
	
	debug("LDAP - Connected to LDAP server ");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

	//must always check that password length > 0
	if (!(strlen($password) > 0 && strlen($username) > 0)){
		return false;
		}
		
	$binddomains=explode(";",$simpleldap['domain']);
	foreach ($binddomains as $binddomain)
		{
		debug("LDAP - Attempting to bind to LDAP server as : " . $username . "@" .  $binddomain);
		$login = @ldap_bind( $ds, "$username@" . $binddomain, $password );
		if (!$login){continue;}else{$userdomain=$binddomain;break;}
		}
	if (!$login){debug("LDAP - failed to binding to LDAP server");	return false; }
	
	$email_attribute=$simpleldap['email_attribute'];
	$phone_attribute=$simpleldap['phone_attribute'];
	$ldapgroupfield=$simpleldap['ldapgroupfield'];
	$attributes = array("displayname",$ldapgroupfield,$email_attribute,$phone_attribute);
	$loginfield=$simpleldap['loginfield'];
	$filter = "(&(objectCategory=person)(" . $loginfield . "=" . $username . "))";
			
	$searchdns=explode(";",$simpleldap['basedn']);
	$dn=array();
	$ldapconnections=array();
	foreach($searchdns as $searchdn)
		{
		debug("LDAP - preparing search DN: " . $searchdn);
		$dn[]=$searchdn;
		}
	for($x=0;$x<count($dn);$x++)
		{
		//echo $ds;
		$ldapconnections[$x] = ldap_connect( $simpleldap['ldapserver'],$simpleldap['port'] );
		@ldap_bind($ldapconnections[$x], "$username@" . $userdomain, $password );
		}
	//exit();
	debug("LDAP - performing search: filter=" . $filter);
	$result = ldap_search($ldapconnections, $dn, $filter, $attributes);
	
	//exit(print_r($result));
	foreach ($result as $value) 
		{ 
		debug("LDAP - search returned value " . $value);
		if(ldap_count_entries($ds,$value)>0)
			{ 
			$search = $value; 
			break; 
			} 
		} 
	if (isset($search))
		{$entries = ldap_get_entries($ds, $search);}
	else
		{
		debug("LDAP - search returned no values");
		return false;
		}
		
		
	
	if($entries["count"] > 0){

		if (isset($entries[0]['displayname']) && count($entries[0]['displayname']) > 0){
			$displayname = $entries[0]['displayname'][0];
		} else {
			$displayname = '';
		}

		//$ldap_groupfield = $simpleldap[$ldapgroupfield];

		$department = '';

		if (isset($entries[0][$ldapgroupfield]) && count($entries[0][$ldapgroupfield]) > 0){
			$deptresult = sql_query('select ldapgroup, rsgroup from simpleldap_groupmap order by priority desc');
			foreach ($deptresult as $thedeptresult){
				$knowndept[$thedeptresult['ldapgroup']] = $thedeptresult['rsgroup'];
			}
			foreach ($entries[0][$ldapgroupfield] as $thedept){
				if (isset($knowndept[$thedept]) && $knowndept[$thedept] > 0){
					// if there are multiples, we will return one of the ones that has a match
					$department = $thedept;
					//exit if a match to honour priority
					break;
				} else {
					if (!is_numeric($thedept)){
					// ignore numbers; this is a kludgey way to deal with the fact
					// that some ldap servers seem to return a result count as the first value
					$thedept = escape_check($thedept);
					sql_query("replace into simpleldap_groupmap (ldapgroup, rsgroup) values (\"$thedept\",NULL)");
					} 
				}
			}
		}
		//Extract email info
		if ((isset($entries[0][$email_attribute])) && count($entries[0][$email_attribute]) > 0)
			{
			$email = $entries[0][$email_attribute][0];
			}
		else
			{
			$email = $username . '@' . $simpleldap['emailsuffix'];;
			}
			
		//Extract phone info
		if (isset($entries[0][$phone_attribute]) && count($entries[0][$phone_attribute]) > 0)
			{
			$phone = $entries[0][$phone_attribute][0];
			}
		else
			{
			$phone = 'Unknown';
			}
				
		
		$return['domain'] = $userdomain;
		$return['username'] = $username;
		$return['displayname'] = $displayname;
		$return['group'] = $department;
		$return['email'] = $email;
		$return['phone'] = $phone;
		return $return;

	}


	ldap_unbind($ds);



}
