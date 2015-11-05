<?php
  include_once(__DIR__."/../../../../include/config.php");
  header('Content-Type: application/json; charset=utf-8');
  if(!isset($_POST)){
     die("nice try");
  }
  else{
      $directory = __DIR__."/../../gfx/help-files/";
      $data = $_FILES['file'];
      $newname = preg_replace('/\s+/', '_', $data['name']);
      if(copy($data['tmp_name'],$directory.$newname)){
          echo json_encode("upload successfull. Here's your url:".$baseurl."/plugins/mia_styling/gfx/help-files/".$newname);
      }
      else{
         echo("Upload Fialed");
      }
  }
?>
