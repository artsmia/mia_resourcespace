<?php
include dirname(__FILE__) . "/../../../include/db.php";
include dirname(__FILE__) . "/../../../include/general.php";
include dirname(__FILE__) . "/../../../include/resource_functions.php";
include dirname(__FILE__) . "/../../../include/image_processing.php";
include_once(dirname(__FILE__) . "/../../elastic_search/include/elastic_functions.php");

if(!isset($argv)){
  echo("ERROR: No Arguments where passed with elastic search cron job \n");
}else{
  ini_set('max_execution_time', 1200);
  $resourcetype = $argv[1];
  $ref = trim($argv[2]);
  $extension = $argv[3];
  $attempts = $argv[4];
    if($resourcetype == 3 || $resourcetype == 4){
        $cp = create_previews($ref,$thumbonly=true,$extension);
        if($cp == true && $resourcetype == 3){
            $thumb_path = get_resource_path($ref,true,"thm",false,"jpg");
            $im_fullpath = get_utility_path("im-composite");
            $command = $im_fullpath . " -gravity center /var/www/gfx/video-overlay.png " . escapeshellarg($thumb_path) . " : " . escapeshellarg($thumb_path) . " 2>&1";
            $report_original = run_command($command);
        }
    }else{
        //create preview files in that directory
        $cp = create_previews_using_im($ref,false,$extension);
    }
    if ($cp == true){
        sql_query("UPDATE resource SET is_transcoding = 0 WHERE ref = $ref");

        //send result to elast search now that we have preview
        $results=array();
        $results[] = get_resource_data($ref,false);
        $thumb_path = array("thumbnail"=>$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref,true,"thm",false,"jpg")));
        $newresults[] = array_merge($thumb_path,$results[0]);
        $results=$newresults;
        $resourcetype=get_resource_type_name($results[0]['resource_type']);
        if($resourcetype == "Audio"){
            $prv_path = array("preview"=>$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref, true, "", false, "mp3")));
        }else if($resourcetype == "Video"){
            $prv_path = array("preview"=>$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref, true, "pre", false, "mp4")));
        }
        $newresults2[] = array_merge($prv_path,$results[0]);
        $results=$newresults2;
        $results = mia_results($results);
        for($r=0; $r<count($results); $r++){
            foreach($results[$r] as $k => $v){
                if($v == "" || $v=="NULL" || $v==","){
                    unset($results[$r][$k]);
                }
             }
        }
        $results=json_encode($results[0]);
        $query=push_RStoElastic($resourcetype,$ref,$results);
        if($query==false){
            $data['elastic']="Failed to connect to elastic search";
            $cronfile = fopen("/var/tmp/elastic-$ref","w");
            fwrite($cronfile, "#!/bin/sh \n*/1 * * * * root /usr/bin/php -q -f /var/www/plugins/elastic_search/pages/cron_elastic.php $ref >> /var/www/elasticcron.log\n");
            fclose($cronfile);
        }else{
            $data['elastic']=$query;
        }
    }else{
        error_log($attempts);
        //start a cron to resolve the preview images.
        if($attempts < 4){
            $attempts++;
            $prevcron = fopen("/var/tmp/cron-preview-$ref","w");
            fwrite($prevcron, "#!/bin/sh \n*/1 * * * * root /usr/bin/php -q -f /var/www/plugins/mia_upload/pages/background_previews.php $resourcetype $ref $extension $attempts >> /var/www/previews.log\n");
            fclose($prevcron);
        }else{
            //else send the email
            send_mail($email_notify, "Resourcespace - Failed Resource Previews", "Failed to create previews for resource <a href='$baseurl/pages/view.php?ref=$ref'>$ref</a> via cron. Please resolve", "ResourceSpace");
        }
    }
}


?>
