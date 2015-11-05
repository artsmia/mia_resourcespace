<?php
$simpleldap['emailsuffix'] = 'mycompany.org';
$simpleldap['domain'] = 'mydomain.mycompany.org';
$simpleldap['ldapserver'] = 'pdc.mycompany.org';
$simpleldap['port'] = '389';
$simpleldap['basedn']= 'CN=users, DC=mydomain,DC=mycompany,DC=org';
$simpleldap['loginfield'] = 'uid';
$simpleldap['usersuffix'] = '.LDAP';
$simpleldap['createusers'] = true;
$simpleldap['ldapgroupfield'] = 'department';
$simpleldap['email_attribute'] = "userprincipalname";
$simpleldap['phone_attribute'] = "telephoneNumber";
$simpleldap['update_group'] = true;
?>
