<?php
#
# action_dates setup page
#

include '../../../include/db.php';
include '../../../include/general.php';
include '../../../include/authenticate.php'; if (!checkperm('a')) {exit ($lang['error-permissiondenied']);}

// Specify the name of this plugin and the heading to display for the page.
$plugin_name = 'action_dates';
$plugin_page_heading = $lang['action_dates_configuration'];

// Build the $page_def array of descriptions of each configuration variable the plugin uses.

$page_def[] = config_add_section_header($lang['action_dates_deletesettings']);
$page_def[] = config_add_single_ftype_select('action_dates_deletefield',$lang['action_dates_delete']);
$page_def[] = config_add_boolean_select('action_dates_reallydelete',$lang['action_dates_reallydelete']);

$page_def[] = config_add_section_header($lang['action_dates_restrictsettings']);

$page_def[] = config_add_text_input('action_dates_email_admin_days',$lang['action_dates_email_admin_days']);
$page_def[] = config_add_single_ftype_select('action_dates_restrictfield',$lang['action_dates_restrict']);



// Do the page generation ritual -- don't change this section.
$upload_status = config_gen_setup_post($page_def, $plugin_name);
include '../../../include/header.php';
config_gen_setup_html($page_def, $plugin_name, $upload_status, $plugin_page_heading);



include '../../../include/footer.php';
