<?php
#
# Responsive Setup
#
// Do the include and authorization checking ritual -- don't change this section.
include '../../../include/db.php';
include '../../../include/authenticate.php'; if (!checkperm('a')) {exit ($lang['error-permissiondenied']);}
include '../../../include/general.php';

// Specify the name of this plugin and the heading to display for the page.
$plugin_name = 'panoramic_theme';
$plugin_page_heading = $lang["panoramic_configtitle"];

// Build the $page_def array of descriptions of each configuration variable the plugin uses.
$page_def[] = config_add_text_input('linkedheaderimgsrc',$lang['linkedheaderimgsrc']);

if(empty($_POST["linkedheaderimgsrc"]))
	{
	$_POST["linkedheaderimgsrc"]="";
	}
// Do the page generation ritual -- don't change this section.
$upload_status = config_gen_setup_post($page_def, $plugin_name);
include '../../../include/header.php';
//config_gen_setup_html($page_def, $plugin_name, $upload_status, $plugin_page_heading);
include '../../../include/footer.php';
