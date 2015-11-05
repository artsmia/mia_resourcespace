<?php
include __DIR__."/../config/config.php";
function mia_results($results){
    global $date_fields,$dont_pull;
    $getfields = sql_query("SELECT ref,title FROM resource_type_field");
    $fieldnames = array();
    for($g=0; $g<count($getfields); $g++){
       $fieldnames[$getfields[$g]['ref']]=$getfields[$g]['title'];
    }
    for($i = 0; $i < count($results); $i++) {
        $ref = $results[$i]['ref'];
        if(isset($results[$i]['ref'])){
            $query=sql_query("SELECT * FROM resource_data WHERE resource = $ref AND value != '' AND value!='NULL' AND value != ','");
            for($q=0; $q<count($query); $q++){
                if($query[$q]['value'] != "," && $query[$q]['value'] !="" && array_key_exists($query[$q]['resource_type_field'],$fieldnames)){
                    if(substr($query[$q]['value'],0,1)==","){
                        $results[$i][$fieldnames[$query[$q]['resource_type_field']]]=substr($query[$q]['value'],1);
                    }else{
                        $results[$i][$fieldnames[$query[$q]['resource_type_field']]]=$query[$q]['value'];
                    }
                }
            }

            $access = get_resource_access($results[$i]);
            $filepath = get_resource_path($results[$i]['ref'], TRUE, '', FALSE, $results[$i]['file_extension'], -1, 1, FALSE, '', -1);
            $original_link = get_resource_path($results[$i]['ref'], FALSE, '', FALSE, $results[$i]['file_extension'], -1, 1, FALSE, '', -1);
            if(file_exists($filepath)) {
                $results[$i]['original_link'] = $original_link;
            } else {
                $results[$i]['original_link'] = 'No original link available.';
            }
            // Get the size of the original file:
           /* $original_size = get_original_imagesize($results[$i]['ref'], $filepath, $results[$i]['file_extension']);
            $original_size = formatfilesize($original_size[0]);
            $original_size = str_replace('&nbsp;', ' ', $original_size);
            $results[$i]['original_size'] = $original_size;*/
            foreach($results[$i] as $k => $v){
                if($v == "" || $v ==","){
                    unset($results[$i][$k]);
                }
                if($k == "created_by"){
                    $user = get_user($v);
                    $results[$i][$k]=$user["fullname"];
                }
                if(in_array($k,$date_fields)){
                    $unix = strtotime($v);
                    $datetime = date('y-m-d',$unix);
                    $results[$i][$k] = $datetime;
                }
                if($k == "resource_type" && is_numeric($v)){
                   $results[$i][$k]=get_resource_type_name($v);
                }
                //need to convert type to string here
                if(in_array($k,$dont_pull)){
                   unset($results[$i][$k]);
                }
            }
       }
//  var_dump($results);exit();
  return $results;
  }
}
function mia_elastic_encode($resourcetypes,$results,$indexhead){
    //format for elastic search
    $keyedresults = array();
    $resourcenames = array();
    for($t=0; $t<count($resourcetypes); $t++){
       $resourcenames[$resourcetypes[$t]['ref']]=$resourcetypes[$t]['name'];
    };
    for($r=0; $r<count($results); $r++){
      if($indexhead != false){
        $keyedresults[]=array(
            "index"=>array(
                "_index"=>"resourcespace",
                "_type"=>$resourcenames[$results[$r]['resource_type']],
                "_id"=>$results[$r]['ref'],
            ),
        );
      }
        $keyedresults[]=$results[$r];
    }
    $results = $keyedresults;
    return $results;
}
function write_elastic_file($filepath,$filename,$objects){
        fopen("/var/www/full_dump.json", "w");
        for($i=0;$i<count($results);$i++){
             $object = json_encode($results[$i])."\n";
             file_put_contents("/var/www/full_dump.json", $object, FILE_APPEND);
        }
}
function push_RStoElastic($resourcetype,$ref,$values){
    global $elastic_full_path, $elastic_index, $elastic_prefix;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $elastic_full_path."/".$elastic_index."/$resourcetype/".$elastic_prefix."$ref/");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, html_entity_decode($values));
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($curl);
    if($response == false){
        return false;
    }else{
        return $response;
    }
}
  function delete_RStoElastic($resourcetype,$ref){
    global $elastic_full_path, $elastic_index, $elastic_prefix;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $elastic_full_path."/".$elastic_index."/$resourcetype/".$elastic_prefix."$ref/");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($curl);
    if($response == false){
        return false;
    }
    else{
        return $response;
    }
}
function createcron($attempts, $ref){
   $file = fopen("/var/tmp/elastic-$ref","w");
   fwrite($file, "#!/bin/sh \n*/1 * * * * root /usr/bin/php -q -f /var/www/plugins/elastic_search/pages/cron_elastic.php $attempts $ref >> /var/log/elasticcron.log\n");
   fclose($file);
}
function createcrondelete($attempts, $ref){
   $file = fopen("/var/tmp/del-elastic-$ref","w");
   fwrite($file, "#!/bin/sh \n*/1 * * * * root /usr/bin/php -q -f /var/www/plugins/elastic_search/pages/cron_elastic_delete.php $attempts $ref >> /var/log/elasticcron.log\n");
   fclose($file);
}
?>
