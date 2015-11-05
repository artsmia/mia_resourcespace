<?php
include dirname(__FILE__) . '/../../include/db.php';
include dirname(__FILE__) . '/../../include/general.php';
include dirname(__FILE__) . '/../../include/authenticate.php';
include dirname(__FILE__) . '/../../include/resource_functions.php';

$resource = getvalescaped('resource', '');
$user_ref = getvalescaped('user_ref', '');

$resource_data = get_resource_data($resource);

// User should have edit access to this resource!
if(!get_edit_access($resource, $resource_data['archive'], false, $resource_data)) {
	exit ('Permission denied.');
}

// Delete the record from the database
$query = sprintf('
		DELETE FROM resource_custom_access 
		      WHERE resource = "%s"
		        AND user = "%s";
	',
	$resource,
	$user_ref
);
sql_query($query);
