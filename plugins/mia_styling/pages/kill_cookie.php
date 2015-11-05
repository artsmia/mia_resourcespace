<?php
include_once("/var/www/include/general.php");
$killsearch = rs_setcookie("search","",1,'/pages/', '', false, false);
if($killsearch){
  rs_setcookie("restypes",'1,2,3,4','1','/','',false,false);
}else{
  echo("did not killsearch");
}
if(isset($_COOKIE['contrib'])){
    rs_setcookie("contrib",'',1,'/', '', false, false);
}
var_dump($_COOKIE);
?>
