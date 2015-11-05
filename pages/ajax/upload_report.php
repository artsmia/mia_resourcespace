<?php
$filelog=$_GET['log'];
$data = file_get_contents("/var/www/filestore/tmp/upload_log/$filelog.txt"); //check from file where you output new message 
//or
  if($data  && $data!=''){
    echo $data; 
    }
?>
