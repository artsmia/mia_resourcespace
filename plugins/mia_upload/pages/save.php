<?php
include __DIR__."/../../../include/db.php";
include __DIR__."/../../../include/authenticate.php";
include __DIR__."/../../../include/general.php";
include __DIR__."/../../../include/resource_functions.php";
include __DIR__."/../../../include/image_processing.php";
include __DIR__."/../../../include/mia_functions.php";
include __DIR__."/../../../plugins/contacts/include/contact_functions.php";

$datasave = array();
$requiredrefs = array();
$postrefs = array();
$required = sql_query("select ref, title from resource_type_field where required = 1");
$filename =  $_POST['file_name'];

if (!empty($required)){
    //Trim field_ out of the keys and check to see if only an int is left.
    //if so store the intiger value to cross reference field types by ref in the database
    foreach  ($_POST as $field => $val){
        if (is_numeric(trim($field, "field_"))){
            $postrefs[] = trim($field, "field_");
        }else if(is_numeric(substr($field,0, 1))){
            //if first char is a number its a checkbox
            $strpos = strpos($field, "_");
            $postrefs[] = substr($field,0,$strpos);
        }
    };

    // use the returned results to check wheather any of them are required fields
    for($r=0; $r<count($required); $r++){
        if(!in_array($required[$r]['ref'],$postrefs)){
            $datasave['error'] = true;
            $datasave['success'] = false;
            $datasave['textStatus'] = $required[$r]['title'] . " is a required field \n";
        };
    };
    if(!empty($datasave)){
        echo(json_encode($datasave));
        exit();
    };
};

//setup directory to store log file
$log_dir = $_SERVER['DOCUMENT_ROOT'].'/filestore/tmp/upload_log/';
$status_log = $log_dir.$_GET['log'].".txt";
fopen($status_log, "w");

$resourcetype = $_POST['resource_type'];

//create a new resource
$ref = create_resource($resourcetype,0,1);
$current = json_encode('{"percent":10,"message":"Resource Created"}');file_put_contents($status_log, $current);
$path = "/var/www/filestore/tmp/uploads/".$_POST['file_name'];

//get the file extension for the file
$fileparts = pathinfo($path);
$extension = strtolower($fileparts['extension']);
$filesize = filesize($path);

//create a new directory and file in relation to the newly created resource
$rs_path = get_resource_path($ref,true,"",true, $extension);
$current = json_encode('{"percent":20,"message":"Storing file..."}');file_put_contents($status_log, $current);

//move the tmp file to the newly created directory
if(copy($path, $rs_path)){//if the file move was successfull

    //Remove the temp file
    unlink($path);
    $current = json_encode('{"percent":30,"message":"Creating directories..."}');file_put_contents($status_log, $current);

    // if contact name fields are detected in metadata
    if(array_key_exists("field_80",$_POST)){$creator = $_POST['field_80'];contacts($creator,$_POST,80);};
    if(array_key_exists("field_184",$_POST)){$creator = $_POST['field_184'];contacts($creator,$_POST, 184);};
    if(array_key_exists("field_171",$_POST)){$creator = $_POST['field_171'];contacts($creator, $_POST, 171);};
    if(array_key_exists("field_125",$_POST)){$creator = $_POST['field_125'];contacts($creator, $_POST, 125);};
    if(array_key_exists("field_177",$_POST)){$creator = $_POST['field_177'];contacts($creator, $_POST, 177);};
    if(array_key_exists("field_178",$_POST)){$creator = $_POST['field_178'];contacts($creator, $_POST, 178);};
    if(array_key_exists("field_180",$_POST)){$creator = $_POST['field_180'];contacts($creator, $_POST, 180);};

    $current = json_encode('{"percent":40,"message":"Saving metadata..."}');file_put_contents($status_log, $current);

    //Update Recource Dimensions
    get_original_imagesize($ref,$rs_path,$extension);
    update_disk_usage($ref);
    if($resourcetype==3){
        $exiftool_fullpath = get_utility_path("exiftool");
        $command = $exiftool_fullpath . " -j -sourceImageWidth -sourceImageHeight ".escapeshellarg($rs_path)." 2>&1";
        $dimensions = run_command($command);
        $result=json_decode($dimensions);
        if(isset($result[0]->SourceImageWidth) && isset($result[0]->SourceImageHeight)){
          $width = $result[0]->SourceImageWidth;
          $height = $result[0]->SourceImageHeight;
          $resolution = $width*$height;
          if(isset($width) && isset($height)){
             sql_query("UPDATE resource_dimensions SET width=$width,height=$height,resolution=$resolution WHERE resource = $ref");
          }
        }
    }
    $current = json_encode('{"percent":50,"message":"Processing disk usage..."}');file_put_contents($status_log, $current);

    $transcoding = false;
    $current = json_encode('{"percent":60,"message":"Creating previews..."}');file_put_contents($status_log, $current);
    if($resourcetype != 2 && $resourcetype != 3 && $resourcetype != 4){
    //create previews
        create_previews_using_im($ref,false,$extension);
    }else{
        sql_query("UPDATE resource SET is_transcoding = 1 WHERE ref = $ref");
        //Process previews in the backgrond and continue
        $attempts=1;
        $command = "/usr/bin/php -q -f /var/www/plugins/mia_upload/pages/background_previews.php $resourcetype $ref $extension $attempts";
        exec("$command > /dev/null &", $arrOutput);
        $current = json_encode('{"percent":60,"message":"Sending previews to background..."}');file_put_contents($status_log, $current);
        $transcoding = true;
    }
    if(isset($_POST['tms_error'])){
        $objid = $_POST['tms_error'];
        createCronTms(1, $ref, $objid);
    }
    $datasave['success']=true;
    $datasave['textStatus']="Your Resource Was Successfully Added";
    $datasave['ref']=$ref;
    hook("editbeforesave");

    //Save the data
    save_resource_data($ref,false);
    if($resourcetype==2){
        extract_text($ref,$extension);
        $current = json_encode('{"percent":70,"message":"Extracting text from document."}');file_put_contents($status_log, $current);
    }
    sql_query("insert into resource_data (resource, resource_type_field, value) values ($ref,12,now()),($ref,148,now())");
    sql_query("update resource set file_extension = '" . $extension . "', field12 = now(), title ='".escape_check($filename)."'WHERE ref = '" . $ref . "'");

    savetoelastic($ref);
    unlink($status_log);
    echo(json_encode($datasave));
}else{
    //error message
    echo json_encode('Unable create resource - something went wrong creating the directories');
}


?>
