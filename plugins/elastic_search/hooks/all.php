<?php
include_once(__DIR__ . "/../include/elastic_functions.php");

//-------------+
//    Hooks    |
//-------------+

//DELETE
function HookElastic_searchAllBeforedeleteresourcefromdb($ref){
global$baseurl;
      $query = sql_query("SELECT resource_type FROM resource WHERE ref = $ref");
      $resourcetype = get_resource_type_name($query[0]['resource_type']);
      $query = delete_RStoElastic($resourcetype,$ref);
      if($query == false){
        createcrondelete($attempts=1, $ref);
      }
return;
}

//SAVE - EDIT
function HookElastic_searchAllAftersaveresourcedata(){
    global $baseurl,$ref,$collection, $pagename;
    if($pagename != "edit2"){
      if($collection !=""){
        $query = sql_query("SELECT resource FROM collection_resource WHERE collection = $collection");
        foreach($query as $colk => $colv){
            savetoelastic($colv['resource']);
        }
      }else{
        savetoelastic($ref);
      }
    }
}

function savetoelastic($ref){
global $baseurl, $pagename;
if($pagename != "upload2"){
//error_log($ref);
   $results=array();
   $results[] = get_resource_data($ref,false);
   $resourcetype=get_resource_type_name($results[0]['resource_type']);
   if($resourcetype != "Audio"){
     $thethumb = $baseurl.str_replace("/var/www/include/..","",get_resource_path($ref,true,"thm",false,"jpg"));
     if(!file_exists($thethumb)){
       $thethumb = $results[0]["thumbnail"] = $baseurl."/gfx/no_preview/extension/".$results[0]['file_extension'].".png";
     }
     $results[0]["thumbnail"]=$thethumb;
   }else{
     $results[0]["thumbnail"] = $baseurl."/gfx/no_preview/extension/".$results[0]['file_extension'].".png";
   }
   if($resourcetype == "Audio"){
       $results[0]["preview"]=$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref, true, "", false, "mp3"));
   }else if($resourcetype == "Video"){
       $results[0]["preview"]=$baseurl.str_replace("/var/www/include/..","",get_resource_path($ref, true, "pre", false, "mp4"));
   }
   $results = mia_results($results);
   $results=json_encode($results[0]);
   $query = push_RStoElastic($resourcetype,$ref,$results);
   if($query == false){
       //failed to connect to elastic search
       createcron($attempts=1, $ref);
   }else{
//       var_dump($query);exit();
   }
   return;
}
}
