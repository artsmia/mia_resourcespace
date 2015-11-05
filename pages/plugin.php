<?php
/***
 * plugin.php - Maps requests to plugin pages to requested plugin.
 * 
 * @package ResourceSpace
 * @subpackage Plugins
 *
 ***/

# Define this page as an acceptable entry point.
define('RESOURCESPACE', true);

include '../include/db.php';
include '../include/general.php';

$query = explode('&', $_SERVER['QUERY_STRING']);
$plugin_query = explode('/', $query[0]);

if (!is_plugin_activated(escape_check($plugin_query[0]))){
    die ('Plugin does not exist or is not enabled');
}
if (isset($plugin_query[1])){
    if(preg_match('/[\/]/', $plugin_query[1])) die ('Invalid plugin page.');
    $page_path = $baseurl_short."plugins/{$plugin_query[0]}/pages/{$plugin_query[1]}.php";
    if(file_exists($page_path)){
        include $page_path;
    }
    else {
        die ('Plugin page not found.');
    }
}
else if(file_exists("../plugins/{$plugin_query[0]}/pages/index.php")){
    include "../plugins/{$plugin_query[0]}/pages/index.php";
}
else {
    die ('Plugin page not found.');
}
