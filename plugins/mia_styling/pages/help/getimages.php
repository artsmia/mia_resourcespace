<?php
  $contents = __DIR__."/../../gfx/help-files/";
  $scanned_directory = array_diff(scandir($contents), array('..', '.'));
  if(!empty($scanned_directory)){
      $data['error']=false;
      $data['msg']=$scanned_directory;
  }else{
      $data['error']=true;
      $data['msg']="<em><b>No files in Library</b></em>";
  }
  echo json_encode($data);
?>
