<?php
include __DIR__ . "/../../../../include/db.php";
include __DIR__ . "/../../../../include/authenticate.php"; if (!checkperm('a')) {exit ($lang['error-permissiondenied']);}
include __DIR__ . "/../../../../include/general.php";

if(isset($_GET['ref']) && $_GET['ref']!=""){
    $pref = $_GET['ref'];
    $query = sql_query("DELETE FROM plugin_pages where page_id=".$pref);
    if(!mysql_errno()){
       $data="Resource Successfully Deleted";
    }else{
       $data="Error";
    }
    echo(json_encode($data));
}
?>
