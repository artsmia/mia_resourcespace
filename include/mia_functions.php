<?php

function createCronTms($attempts, $ref, $objid){
  $file = fopen("/var/tmp/tms-$ref","w");
  fwrite($file, "#!/bin/sh \n */1 * * * * root /usr/bin/php -q -f /var/www/pages/tools/cron_tms.php $attempts $ref $objid >> /var/www/tmscron.log\n");
  fclose($file);
}
/*function getContacts(){
   $contacts = sql_query("SELECT name FROM contacts ORDER BY name");
   $r=array();
   for($c = 0; $c<count($contacts); $c++){
       $r[]=$contacts[$c]['name'];
   }
   $contacts = $r;
   return $contacts;
}*/
?>
