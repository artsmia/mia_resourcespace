<?php
include dirname(__FILE__) . "/../../include/db.php";
include dirname(__FILE__) . "/../../include/general.php";
include dirname(__FILE__) . "/../../include/reporting_functions.php";
include dirname(__FILE__) . "/../../include/resource_functions.php";
include dirname(__FILE__) . "/../../include/mia_functions.php";

if(!isset($argv)){
  echo("ERROR: No Arguments where passed with TMS cron job - unable to resolve". date('d-m-Y h:i:s')."\n");
  exit();
}else{
  $attempts = $argv[1];
  $ref = $argv[2];
  $objid = $argv[3];
  unlink("/etc/cron.d/tms-$ref");
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "http://api.yoursite.org/objects/objectid");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 5);
  $request = curl_exec($curl);

  if($request == FALSE){
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($httpCode == 404) {
      echo("Object $objid not found \n");
       //romove the cron as it was a bad id
    }else{
       //Failed to connect reset the cron
       echo("Cron Failed - re-initializing $ref " . date('d-m-Y h:i:s')."\n");
       if($attempts <= 3){
         $attempts++;
         $file = fopen("/etc/cron.d/tms-$ref","w");
         fwrite($file, "#!/bin/sh \n */10 * * * * root /usr/bin/php -q -f /var/www/pages/tools/cron_tms.php $attempts $ref $objid >> /var/www/tmscron.log\n");
         fclose($file);
       }else{
         echo("Cron failed to resolve $ref in TMS with objectid $objid - Notified admin ".date('d-m-Y h:i:s')."\n");
//         file_put_contents("/var/www/cron_log_executable.php" , );
         send_mail($email_notify, "ResourceSpace - TMS FAIL", "Resource <a href='$baseurl/pages/view.php?ref=$ref'>$ref</a>failed to connect to TMS with Object Id: $objid. Please resolve.", $from="ResourceSpace");
       }
    }
    //echo json_encode($data);
    exit();
  }else{
    //Connection was success
    $update=array();
    $tms_matches=array();
    $request = json_decode($request);
    $getTMS=sql_query("select ref, tms_field from resource_type_field where tms_field != ''");
    for($tm = 0; $tm <count($getTMS); $tm++){
      $dbfield = $getTMS[$tm]['tms_field'];
      $tmsref = $getTMS[$tm]['ref'];
      if(array_key_exists($dbfield,$request) && $request->$dbfield !=''){
        //push matches to match agains existing
        $tms_matches[$tmsref]=$request->$dbfield;
      };
    };
    if(!empty($tms_matches)){
      $qerr=array();
      foreach($tms_matches as $key => $val){
        $exists = sql_query("SELECT resource FROM resource_data WHERE resource = $ref AND resource_type_field = $key");
        if($exists){
          $query = sql_query("UPDATE resource_data SET value = '$val' WHERE resource = $ref AND resource_type_field = $key");
          if($query === false){
            $qerr[]="UPDATE resource_data SET value = '$val' WHERE resource = $ref AND resource_type_field = $key";
          }
        }else{
          $query = sql_query("INSERT INTO resource_data VALUES ($ref,$key,'$val')");
          if($query === false){
            $qerr[]="INSERT INTO resource_data VALUES ($ref,$key,'$val')";
          }
        }
      }
    }
    if(empty($qerr)){
      $results=array();
      $results[] = get_resource_data($ref,false);
      $thumb_path = array("thumbnail"=>$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref,true,"thm",false,"jpg")));
      $newresults[] = array_merge($thumb_path,$results[0]);
      $results = $newresults;
      $results = mia_results($results);
      $resourcetype=get_resource_type_name($results[0]['resource_type']);
      $results=json_encode($results[0]);
      $query = push_RStoElastic($resourcetype,$ref,$results);
      if($query == false){
          //failed to connect to elastic search
          createcron($attempts=1,$ref);
          echo("Resolved TMS but failed to connect to Elastic Search. A Cron Job for Elastic Search has been instantiated and will try to resolve the issue" . date('d-m-Y h:i:s')."n");
      }else{
          echo("Art Object data was successfully resolved from TMS and added to Elastic Search ".date('d-m-Y h:i:s')."\n");
      }
    }else{
      echo("Database Error(s) - Failed to Resolve".date('d-m-Y h:i:s')."\n".json_encode($qerr)."\n");
     //handle this somehow
    }
  }
}
?>
