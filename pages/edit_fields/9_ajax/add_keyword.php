<?php
include dirname(__FILE__) . "/../../../include/db.php";
include dirname(__FILE__) . "/../../../include/general.php";
include dirname(__FILE__) . "/../../../include/authenticate.php";

$field=getvalescaped("field","");
$keyword=getvalescaped("keyword","");

if(!checkperm('bdk' . $field))
    {
    # Append the option and update the field
    sql_query("update resource_type_field set options=concat(ifnull(options,''), ', " . escape_check($keyword) . "') where ref='$field'");
    }

