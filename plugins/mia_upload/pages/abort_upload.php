<?php
if (isset($_GET['filename']) && $_GET['filename']!=''){
    $tempdir = "/var/www/filestore/tmp/uploads/";
    $filename = $_GET['filename'];
    $filepath = $tempdir.$filename;
    if(unlink($filepath)){
        $data['success']=true;
        $data['status']="success";
        $data['message'] = "Successfully removed $filename from the tmp directory";
        echo(json_encode($data));
    }
}else{
    die("No");
}
?>
