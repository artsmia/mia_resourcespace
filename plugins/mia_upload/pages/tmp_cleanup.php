<?php
    $temp_dir = "/var/www/filestore/tmp/uploads/";
    $sd = array_diff(scandir($temp_dir), array('..', '.'));
    foreach($sd as $file){
        // if file was added to the directory more than 2 hours ago remove it
        if(filemtime($temp_dir.$file) < (time()-7200){
            unlink($temp_dir.$file);
        }
    }
    echo("You've got a clean tmp");
?>
