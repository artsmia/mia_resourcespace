<?php
include __DIR__."/../../../../include/config.php";
include __DIR__."/../../../../include/db.php";

if(isset($_POST) && isset($_POST['img']) && !empty($_POST['img'])){
$directory = "/var/www/plugins/mia_styling/gfx/help-files/";
$file = $directory.$_POST['img'];
     if(unlink($file)){
        $data['error']=false;
        $data['msg']="Successfully deleted image.";
     }else{
        $data['error']=true;
        $data['msg']="Failed to delete image.";
     }
     echo json_encode($data);
}else{
   echo "error";
}
?>
