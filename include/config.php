<?php
###############################
## ResourceSpace
## Local Configuration Script
###############################

# All custom settings should be entered in this file.
# Options may be copied from config.default.php and configured here.

# MySQL database settings
$mysql_server = 'localhost';
$mysql_username = '';
$mysql_password = '';
$mysql_db = '';

$mysql_bin_path = '/usr/bin';

# Base URL of the installation
$baseurl = 'http://';

# Email settings
$email_from = '';
$email_notify = '';

$spider_password = '';
$scramble_key = '';

$api_scramble_key = '';

# Paths
$imagemagick_path = '/usr/bin';
$ghostscript_path = '/usr/bin';
$exiftool_path = '/usr/bin';
$antiword_path = '/usr/bin';
$pdftotext_path = '/usr/bin';
$ffmpeg_path = '/usr/bin';
$ffmpeg_preview_seconds=30;

$extracted_text_field=194;

$applicationname = 'ResourceSpace';
$ftp_server = 'my.ftp.server';
$ftp_username = 'my_username';
$ftp_password = 'my_password';
$ftp_defaultfolder = 'temp/';
$thumbs_display_fields = array(8,3);
$list_display_fields = array(8,3,12);
$sort_fields = array(12);
$imagemagick_colorspace = "sRGB";

$index_contributed_by=true;
$blank_edit_template=true;
$edit_autosave=false;
//Set download filename to filename field and turn prefixing the resource id to the filename off
$keyboard_navigation = false;
$download_filename_field = 8;
$original_filenames_when_downloading = true;
$prefix_resource_id_to_filename=false;
$view_title_field = 8;
$thumbs_display_fields = array(8,12);
$list_display_fields = array(8,12);
$infobox_fields=array(18,80,156,85,90,195);

$metadata_report=true;
$plugins=array();

$enable_remote_apis = true;
$api_scramble_key = "";

$ffmpeg_preview_extension="mp4";
$ffmpeg_preview_options="-vcodec libx264 -crf 23 -strict -2 -b:a 64k -ar 22050";
$ffmpeg_audio_extensions = array(
        'wav',
        'ogg',
        'aiff',
        'aif',
        'au',
        'cdda',
        'm4a',
        'wma',
        'mp2',
        'aac',
        'ra',
        'rm',
        'gsm'
        );
$ffmpeg_preview_force=true;
$global_cookies = true;
$tabs_on_edit=TRUE;
