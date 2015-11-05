<?php
include __DIR__."../../../../include/general.php";
include __DIR__."../../../../include/db.php";
if (!isset($_GET["name"])){
  exit("No Direct Script Access");
}else{
  $name = $_GET["name"];
  $query = sql_query("SELECT * FROM resource WHERE title = '".escape_check($name)."' AND archive = 0");
  if(empty($query)){
      $data["success"]=true;
  }else{
      $data["success"]=false;
      $data["msg"]="Sorry <a target='_BLANK' href='".$baseurl."/pages/view.php?ref=".$query[0]['ref']."'>".$name."</a> already exists as a resource. ";
  }
  echo(json_encode($data));
}
?>
