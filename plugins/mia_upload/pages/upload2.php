<?php
include __DIR__."/../../../include/db.php";
include __DIR__."/../../../include/general.php";
include __DIR__."/../../../include/resource_functions.php";
include __DIR__."/../../../include/image_processing.php";

header('Content-Type: application/json');

if (isset($_POST['file'])){
    $file = json_decode($_POST['file']);
    //Check to see wheather or not the resource trying to ingest already exists.
    $existing = sql_query("select * from resource where title ='" . escape_check($file)."' and archive = 0");

    if($existing){
        $data['error'] = true;
        $data['success'] = false;
        $data['textStatus'] = escape_check($file)." already exists as a resource <a href='/pages/view?ref=".$existing[0]['ref']."' target='_BLANK'>View Here</a>";
        echo(json_encode($data));
        exit();
    };

    //if user submitted a blank form?
    if ($file==''){
        $data['error'] = true;
        $data['success'] = false;
        $data['textStatus'] = 'Please Choose A Resource';
        echo(json_encode($data));
        exit();
    };

    $uploaddir = '/var/www/filestore/tmp/uploads/';
    $exiftool_fullpath = get_utility_path("exiftool");
    $command = $exiftool_fullpath . " -j --filename --exiftoolversion --filepermissions --NativeDigest --History --Directory " . escapeshellarg($uploaddir.$file)." 2>&1";
    $report_original = run_command($command);
    $extension = pathinfo($uploaddir.$file, PATHINFO_EXTENSION);
    $resource_type="";
    //get all of the available resource types and check the file extesion of uploaded file
    $resource_types=get_resource_types();
	//make sure file extension is all lowercase to ensure accurate match
    $file_extension = strtolower($extension);

    //for all of the resource types found
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
    //Could not determine resource type
	if($resource_type==""){
        //there wasn't a file extension
        if($file_extension == ""){
        $data['textStatus'] = 'Only resources with file extensions are allowed. Please check that the resource you are trying to upload has a proper extension.';
            //there wasn't a match in allowed extensions
        }else{
            $data['textStatus'] = 'Sorry .'.$file_extension.' is not a supported file type';
        }
        $data['error'] = true;
        $data['success'] = false;
        echo(json_encode($data));
        exit();
	};

    $type_name=get_resource_type_name($resource_type);

    // Get mapped exiftool fields from database
    $write_to = get_exiftool_fields($resource_type);
    // Returns an array of exiftool tags for the particular resource type.
    $decoded_arr=json_decode($report_original, true);
    $resultcount=count($write_to);
    $metaids = array();
    $metadata = array_change_key_case($decoded_arr[0], CASE_LOWER);
    for ($i=0; $i<$resultcount; $i++){
        $exiffields = strtolower($write_to[$i]['exiftool_field']);
	    if(array_key_exists($exiffields,$metadata)){
            $values = $metadata[$exiffields];
            if(is_array($values)){
                $values =  implode(',',$values);
            };
             $metaids[] = array("id"=>$write_to[$i]["ref"], "field"=>$write_to[$i]['exiftool_field'], "value"=>htmlspecialchars($values));
        };
    };
    $required = sql_query("select ref, title from resource_type_field where required = 1");
    if($required){
        $data['required']=$required;
    }
    //if Transmission Reference (TMS Object ID) is detected in Metadata
    if(array_key_exists("objectid",$metadata)){
        $objid = $metadata['objectid'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.yoursite.org/objects/objectid');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $request = curl_exec($curl);
        if($request == FALSE){
            $request = json_encode("Failed to connect to TMS");
            $data['tms_error']=$objid;
        }else{
            $request = json_decode($request);

            //get availabe tms mappings from database
            $getTMS=sql_query("select ref, tms_field from resource_type_field where tms_field != ''");
            for($tm = 0; $tm <count($getTMS); $tm++){
                $dbfield = $getTMS[$tm]['tms_field'];
                if(array_key_exists($dbfield,$request) && $request->$dbfield !=''){
                    $newray[] = array("ref"=>$getTMS[$tm]['ref'], "tmsfield"=>htmlspecialchars($dbfield), "tmsvalue"=>$request->$dbfield);
                };
            };
            $data['tms']=$newray;
        };
    };
    if($resource_type == 1){
        $tmpFile = "/filestore/tmp/uploads/".basename($file);
    }else{
        $tmpFile = $baseurl."/gfx/no_preview/extension/".$file_extension.".png";
    }
    $rs = array("resourceType"=>$resource_type, "resourceName"=>$type_name, "tmpFile"=>$tmpFile,"fileName"=>$file);
    $data['status']="success";
    $data['exif']=$metaids;
    $data['rs'] = $rs;
    echo json_encode($data);
}
?>

