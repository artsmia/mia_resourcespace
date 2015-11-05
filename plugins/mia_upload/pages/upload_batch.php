<?php
include __DIR__."/../../../include/db.php";
include __DIR__."/../../../include/authenticate.php";
include __DIR__."/../../../include/general.php";
include __DIR__."/../../../include/resource_functions.php";
include __DIR__."/../../../include/image_processing.php";
include __DIR__."/../../../include/mia_functions.php";
include __DIR__."/../../../plugins/contacts/include/contact_functions.php";

if (isset($_POST['file'])){
    ini_set('max_execution_time', 600);

    //If there is no file show status and exit
    if ($_POST['file']==''){
        $data['error'] = true;
        $data['success'] = false;
        $data['textStatus'] = 'Please add a Resource to load.';
        echo(json_encode($data));
        exit();
    };

    //set vars
    $file = json_decode($_POST['file']);
    $tempdir = "/var/www/filestore/tmp/uploads/";
    $filename = $file;
    $formdata=json_decode($_POST['form']);
    $formarray = array();
    $exifcompare = array();

    //check resource  exists
    $existing = sql_query("select * from resource where title ='" .escape_check($filename)."' and archive = 0");

    //and exit if it does with link to existing
    if($existing){
        $ref=$existing[0]['ref'];
        $data['error'] = true;
        $data['success'] = false;
        $data['textStatus'] = 'Resource '.$filename .'  already exists <a href="'.$baseurl.'/pages/view.php?ref='. $ref .'" style="text-decoration:underline;" target="_BLANK">View Existing Resource</a>';
        echo(json_encode($data));
        exit();
    };

    for($f=0; $f<count($formdata); $f++){
        $formarray[$formdata[$f]->name]=$formdata[$f]->value;
        $exifcompare[] = $formdata[$f]->name;
    };

    $_POST=$formarray;
    $path = $file;

    //get the basic info for the file
    $fileparts = pathinfo($tempdir.$path);
    $file_extension = strtolower($fileparts['extension']);
    $filesize = filesize($tempdir.$path);
    $resource_types=get_resource_types();

    for($i=0; $i<count($resource_types); $i++){
        //explode the allowed extensions into an array and remove any whitespaces
        $extension = preg_replace('/\s*/', '', $resource_types[$i]['allowed_extensions']);
        $resource_extensions=explode(',' , strtolower($extension));
        //check the array for the file extension
        if(in_array($file_extension,$resource_extensions)){
            //if the extension is found set the resource type for the found extension
            $resource_type=$resource_types[$i]['ref'];
        };
    };
    if($resource_type==""){
       if($file_extension == ""){
            $data['textStatus'] = 'Only resources with file extensions are allowed';
       }else{
            $data['textStatus'] = 'Sorry .'.$file_extension.' is not a supported file type';
       }
       $data['error'] = true;
       $data['success'] = false;
       echo(json_encode($data));
       exit();
    };

    //Run Exiftool
    $exiftool_fullpath = get_utility_path("exiftool");
    $command = $exiftool_fullpath . " -j --exiftoolversion --filepermissions --NativeDigest --History --Directory " . escapeshellarg($tempdir.$filename)." 2>&1";
    $report_original = run_command($command);
    $write_to = get_exiftool_fields($resource_type);
    $decoded_arr=json_decode($report_original, true);
    $resultcount=count($write_to);
    unset($metaids);
    $metaids = array();

    //if exif was successfull
    if(!empty($decoded_arr) || $decoded_arr != NULL){
        $metadata = array_change_key_case($decoded_arr[0], CASE_LOWER);
        for ($i=0; $i<$resultcount; $i++){
            $exiffields = strtolower($write_to[$i]['exiftool_field']);
            if(array_key_exists($exiffields,$metadata) && !in_array("field_".$write_to[$i]['ref'],$exifcompare)){
                $values = $metadata[$exiffields];
                if(is_array($values)){
                    $values = implode(',',$values);
                };
                $metaids["field_".$write_to[$i]['ref']] = htmlspecialchars($values);
            };
        };

        $_POST = array_merge($_POST, $metaids);
    }else{
        $data['error']=true;
        $data['success']=false;
        $data['textStatus']='Internal Error - Failed to extract metadata';
        echo(json_encode($data));
        exit();
    }

    //add the filename to the post variable
    $_POST['field_8']=$filename;

    //CREATE THE RESOURCE
    $ref = create_resource($resource_type,0,1);

    //if Object Id  (TMS Object ID) is detected in Metadata
    if(array_key_exists("objectid",$metadata)){
        $objid = $metadata['objectid'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.yoursite.org/objects/objectid');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $request = curl_exec($curl);
        if($request == FALSE){
            $request = "failed to connect to TMS";
            //Create a tms cron job to resolve;
            createCronTms(1, $ref, $objid);
        }else{
            $request = json_decode($request);

            //get availabe tms mappings from database
            $getTMS=sql_query("select ref, tms_field from resource_type_field where tms_field != ''");
            for($tm = 0; $tm <count($getTMS); $tm++){
                $dbfield = $getTMS[$tm]['tms_field'];
                if(array_key_exists($dbfield,$request) && $request->$dbfield !=''){
                    $newray["field_".$getTMS[$tm]['ref']] = $request->$dbfield;
                };
            };
            $_POST=array_merge($_POST,$newray);
        };
    };

    //create a new directory and file in relation to the newly created resource
    $rs_path = get_resource_path($ref,true,"",true, $file_extension);

    //if the file move was successfull
    if(copy($tempdir.$filename, $rs_path)){
        //remove tmp file
        unlink($tempdir.$filename);
        set_time_limit(600);
        // if contact name fields are detected in $_POST
        if(array_key_exists("field_80",$_POST)){$creator = $_POST['field_80'];contacts($creator,$_POST,80);};
        if(array_key_exists("field_184",$_POST)){$creator = $_POST['field_184'];contacts($creator,$_POST, 184);};
        if(array_key_exists("field_171",$_POST)){$creator = $_POST['field_171'];contacts($creator, $_POST, 171);};
        if(array_key_exists("field_125",$_POST)){$creator = $_POST['field_125'];contacts($creator, $_POST, 125);};
        if(array_key_exists("field_177",$_POST)){$creator = $_POST['field_177'];contacts($creator, $_POST, 177);};
        if(array_key_exists("field_178",$_POST)){$creator = $_POST['field_178'];contacts($creator, $_POST, 178);};
        if(array_key_exists("field_180",$_POST)){$creator = $_POST['field_180'];contacts($creator, $_POST, 180);};

        //Update image sizes in database
        get_original_imagesize($ref,$rs_path,$file_extension);
        // Update disk usage
        update_disk_usage($ref);

        //Bug Fix for Videos not storing width and height values in the resource_dimensions table
        if($resource_type==3){
            $exiftool_fullpath = get_utility_path("exiftool");
            $command = $exiftool_fullpath . " -j -sourceImageWidth -sourceImageHeight ".escapeshellarg($rs_path)." 2>&1";
            $dimensions = run_command($command);
            $result=json_decode($dimensions);

            //if extraction was success
            if(isset($result[0]->SourceImageWidth) && isset($result[0]->SourceImageHeight)){
                $width = $result[0]->SourceImageWidth;
                $height = $result[0]->SourceImageHeight;
                $resolution = $width*$height;
                if(isset($width) && isset($height)){
                    sql_query("UPDATE resource_dimensions SET width=$width,height=$height,resolution=$resolution WHERE resource = $ref");
                }
            }
        }
        hook("editbeforesave");
        //Save the data
        save_resource_data($ref,false);
        //Update creation times, extension and title
        sql_query("insert into resource_data (resource, resource_type_field, value) values ($ref,12,now()),($ref,148,now())");
        sql_query("update resource set file_extension = '" . $file_extension . "', field12 = now(), title ='".escape_check($filename)."'WHERE ref = '" . $ref . "'");
        $data['success']=true;
        $data['status']="success";
        $data['error']=false;
        $data['textStatus']= $file . " - Successfully Added";
        $data['ref']=$ref;
    }else{
        $data['error']=true;
        $data['textStatus']="could not move file to tmp";
    }
    if($resource_type==2){
        extract_text($ref,$file_extension);
    }
    if($resource_type != 2 && $resource_type != 3 && $resource_type != 4){
        //create preview files in that directory
        create_previews_using_im($ref,false,$file_extension);
        $nothumb = false;
    }else{
        $nothumb = true;
        sql_query("UPDATE resource SET is_transcoding = 1 WHERE ref = $ref");
        //Process previews in the backgrond and continue
        $attempts = 1;
        $command = "/usr/bin/php -q -f /var/www/plugins/mia_upload/pages/background_previews.php $resource_type $ref $file_extension $attempts";
        exec("$command > /dev/null &", $arrOutput);
    }
    savetoelastic($ref);
    echo(json_encode($data));
    ob_flush();
    flush();
}else{
    echo('Please Add a File');
};
?>
