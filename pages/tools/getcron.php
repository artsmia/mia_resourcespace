<?php
$path = "/var/tmp/";
$files = scandir($path);
if(!empty($files)){
  echo("Temporary directory was checked for new Cron Jobs : ".date('d-m-Y h:i:s')."\n");
  foreach($files as $k => $v){
    if(substr($v,0,8)=="elastic-" || substr($v,0,4)=="tms-" || substr($v,0,12)=="cron-preview" || substr($v,0,12)=="del-elastic-"){
      if(copy($path.$v,"/etc/cron.d/$v")){
        echo("$v - succesfully copied to Cron.d ".date('d-m-Y h:i:s')."\n");
        unlink($path.$v);
      }else{
        echo("$v - failed to copy to cron.d directory ".date('d-m-Y h:i:s')."\n");
      }
    }
  }
}else{
  echo("No New Crons");
}
?>
