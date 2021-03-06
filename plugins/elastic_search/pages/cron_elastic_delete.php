include dirname(__FILE__) . "/../../../include/db.php";
include dirname(__FILE__) . "/../../../include/general.php";
include dirname(__FILE__) . "/../../../include/reporting_functions.php";
include dirname(__FILE__) . "/../../../include/resource_functions.php";
include_once(dirname(__FILE__) . "/../include/elastic_functions.php");
if(!isset($argv)){
  echo("ERROR: No Arguments where passed with elastic search cron job \n");
}else{
  $attempts = $argv[1];
  $ref = trim($argv[2]);
  //get rid of the executed job we will reset it later if need be
  unlink("/etc/cron.d/elastic-$ref");

  $results = get_resource_data($ref,false);
  if(empty($results)){
    echo("ERROR: The cron that was executed references a resource ($ref) that no longer exist in the system \n");
    exit();
  }
  $resourcetype=get_resource_type_name($results[0]['resource_type']);
  $query = delete_RStoElastic($resourcetype,$ref);
  if($query == false){
    if($attempts <= 3){
     $attempts++;
      $file = fopen("/etc/cron.d/del-elastic-$ref","w");
      fwrite($file, "#!/bin/sh \n*/1 * * * * root /usr/bin/php -q -f /var/www/plugins/elastic_search/pages/cron_elastic_delete.php $attempts $ref >> /var/log/elasticcron.log\n");
      fclose($file);
      echo("\n Resource : $ref failed to resolve connecting to elastic search via cron - reinitializing on ".date('d-m-Y h:i:s')."\n");
    }else{
      send_mail($email_notify, "ResourceSpace - Elastic FAIL", "Resource <a href='$baseurl/pages/view.php?ref=$ref'>$ref</a> attempted to be removed but failed to connect to Elastic Search. Please resolve.", $from="ResourceSpace");
      echo("Resolve limit reached for $ref - notification sent to admin".date('d-m-Y h:i:s')."\n");
    }
  }else{
    echo("\n Resource : $ref was successfully removed from elastic search via cron on ".date('d-m-Y h:i:s')."\n");
  }
}
