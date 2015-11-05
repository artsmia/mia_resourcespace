<?php
function contacts($name,$post,$referer){
    //Check Database for match
    $contacts = sql_query("SELECT * FROM contacts WHERE name=\"".$name."\"");
    //if Match found
    if(!empty($contacts)){
        //The contact already exists
    }else{
        $inserts=array();
        $insertvalues=array();
        //Get contacts mapping
        switch($referer){
             case 80:
                 $cmap = array(
                     array("name", "field_80"),// Media Supplier Name | Media Source | Rights Owner
                     array("title","field_133"),
                     array("address","field_126"),
                     array("city","field_127"),
                     array("state","field_132"),
                     array("country","field_128"),
                     array("zipcode","field_131"),
                     array("email","field_129"),
                     array("URL","field_134")
                 );
             break;
             case 184:
                 $cmap= array(
                     array("name","field_184"),
                     array("URL","field_186"),
                     array("email","field_183"),
                 );
             break;
             case 171:
                 $cmap=array(
                     array("name","field_171"),
                     array("URL","field_171"),
                 );
             break;
             case 125:
                 $cmap=array(array("name","field_125"));
             break;
             case 178:
                 $cmap=array(array("name","field_178"));
             break;
             case 177:
                 $cmap=array(array("name","field_177"));
             break;
             case 180:
                 $cmap=array(array("name","field_180"));
             break;
         };
         //for all matched form fields that relate to contacts
         for ($c=0; $c<count($cmap); $c++){
                 if(array_key_exists($cmap[$c][1],$post)){
                     //map values to new array
                     $inserts[]=$cmap[$c][0];
                     $insertvalues[]="'".$post[$cmap[$c][1]]."'";
                  }
             }
              if(!empty($inserts) && !empty($insertvalues)){
                 //Add to contacts database
                 $query = sql_query("INSERT INTO contacts (".join(",",$inserts).") VALUES (".join(",",$insertvalues).")");
                  $datasave['contacts']="$name was added to your contacts";
             }else{
                 sql_query("INSERT INTO contacts name VALUES \"$name\"");
                 // $savedata['contacts']="Contact name added to contacts";
             }
         }
                //if Partial match / matches found
     }


function updateResourcesFromContacts($resourcestoupdate,$rs_fields,$resref, $oldvals,$oldvalmatch){
    $resourceupdates = array(); $resources=array(); $rf=array(); $rv=array(); $toindex=array();//empty array vars

    //filter for only resources where this name matches the resource_field_type
    $filteredray = array();
    for ($ru=0; $ru<count($resourcestoupdate); $ru++){
        if($resourcestoupdate[$ru]['resource_type_field']==$resref){
            $filteredray[] = $resourcestoupdate[$ru];
        };
    };
    //loop through the filtered resources
    for($r=0; $r<count($filteredray); $r++){
        //for all of the resources and foreach mapped resource field
        foreach($rs_fields as $k => $v){
            if($v !=""){
                // Check to see if resource data exists for that field and resource
                $exists = sql_query("SELECT * FROM resource_data WHERE resource ='".$filteredray[$r]['resource']."' AND resource_type_field='".$k."'");
                //if it doesn't exist and the value has changed
                if(empty($exists) && !in_array($v,$oldvals[0])){
                    //push the data that needs to be INSERTED
                    $resourceupdates[]="(".$filteredray[$r]['resource'].",".$k.",'".$v."')";
                    $oldkey = matcholdkey($k,$oldvalmatch);
                    $toindex[]=$k.",'".$v."',".$filteredray[$r]['resource'].",'".$oldvals[0][$oldkey]."'";
                    // else if the value does exist and the value has changed
                }else if(!in_array($v,$oldvals[0])){
                    //push the data that needs to be UPDATED
                    $resources[]=$filteredray[$r]['resource'];
                    $rf[]=$k;
                    $rv[]=$v;
                    $oldkey=matcholdkey($k,$oldvalmatch);
                    $toindex[]=$k.",'".$v."',".$filteredray[$r]['resource'].",'".$oldvals[0][$oldkey]."'";
                };
            };
        };
    };//end filtered loop

    $updateq = "";
    //if there is data to INSERT build the statement
    if(!empty($resourceupdates)){
        sql_query("INSERT INTO resource_data (resource, resource_type_field, value) VALUES ". join(",",$resourceupdates));
    }
    //if there is data to UPDATE build the statement
    if(!empty($resources)){
        $updateq = "UPDATE resource_data SET value = CASE resource_type_field";
        foreach($rs_fields as $k => $v){
            if($v !=""){
                $updateq .= " WHEN $k THEN '$v' \n";
            }
        };
        $updateq .= "ELSE value END ";
        $updateq .= "WHERE resource IN (" . join(",",$resources).")";
        sql_query($updateq);//update
    }
    //add remove and index keywords
    for($ti = 0; $ti<count($toindex);$ti++){
        $toind = explode(",", $toindex[$ti]);
        remove_keyword_mappings($toind[2],$toind[3],$toind[0]);
        add_keyword_mappings($toind[2],$toind[1],$toind[0]);
    }
    $results=array();
    for($fr=0; $fr<count($filteredray); $fr++){
        $ref=$filteredray[$fr]['resource'];
        $results[]=get_resource_data($ref);
    }
    //elastic search
    $results=mia_results($results);
    $resource_types=get_resource_types();
    $results=mia_elastic_encode($resource_types,$results,false);
    for($e=0; $e<count($results); $e++){
        $resourcetype=get_resource_type_name($results[$e]['resource_type']);
        $ref=$results[$e]['ref'];
        push_RStoElastic($resourcetype,$ref,json_encode($results[$e]));
    }
};//end function


?>
