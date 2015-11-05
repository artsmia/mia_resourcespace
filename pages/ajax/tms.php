<?php
if(isset($_GET['objectid'])){
    include "../../include/db.php";
    $objid = $_GET['objectid'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://api.yoursite.org/objects/id");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $request = curl_exec($curl);
    if($request == FALSE){
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if($httpCode == 404) {
        $data['textStatus']="We could not find a resource in tms with the object id of ". $_GET['objectid'];
        $data['error']=true;
        $data['error_type']="404";
      }else{
         $data['textStatus']="Sorry TMS seems to be down at the moment. Please try agian later.";
         $data['error']=true;
         $data['error_type']="FAIL";
         $data['tms_error']=$objid;
        }
        echo json_encode($data);
       exit();
    }else{
        $request = json_decode($request);

        //get availabe tms mappings from database
        $getTMS=sql_query("select ref, tms_field from resource_type_field where tms_field != ''");
        for($tm = 0; $tm <count($getTMS); $tm++){
            $dbfield = $getTMS[$tm]['tms_field'];
            if(array_key_exists($dbfield,$request) && $request->$dbfield !=''){
                $newray[] = '{"ref":'.$getTMS[$tm]['ref'].',"tmsfield":"'.htmlspecialchars($dbfield).'","tmsvalue":'.json_encode($request->$dbfield).'}'; 
            };
        };
    $data = $newray;
    };
echo(json_encode($data));
}
?>
