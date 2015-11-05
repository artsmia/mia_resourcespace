<?php
include "../../include/db.php";

//----------------------------------------------
//    Mapping for contacts to resource fields
//----------------------------------------------

if(isset($_GET['referer']) && $_GET['referer']!=""){
    $referer = $_GET['referer'];
    $fields="";
    $cfields=array(
        "license" => array(
        "field_184"=>"name",
        "field_186"=>"URL",
        "field_183"=>"email",
    ),
    "creator" => array(
        "field_80"=>"name",
        "field_134"=>"URL",
        "field_129"=>"email",
        "field_130"=>"phone",
        "field_133"=>"title",
        "field_126"=>"address",
        "field_127"=>"city",
        "field_132"=>"state",
        "field_128"=>"country",
        "field_131"=>"zipcode",
    ),
    "commons" => array(
        "field_171" => "name",
        "field_172" => "URL"
    ),
    "media" => array("field_125"=>"name"),
    "rights_source" => array("field_177"=>"name"),
    "rights_owner" => array("field_178"=>"name"),
    "rights_supplier" => array("field_180"=>"name"),
    );

    switch($referer){
        case "field_184":
        $fields = $cfields['license'];
        break;
        case "field_80":
        $fields = $cfields['creator'];
        break;
        case "field_171":
        $fields = $cfields['commons'];
        break;
        case "field_125":
        $fields = $cfields['media'];
        break;
        case "field_177":
        $fields=$cfields['rights_source'];
        break;
        case "field_178":
        $fields=$cfields['rights_owner'];
        break;
        case "field_180":
        $fields=$cfields['rights_supplier'];
        break;
    };
};

//AJAX for contacts
if(isset($_GET['contacts'])){
  if($_GET['contacts']!=""){
      $name = $_GET['contacts'];
      $contacts = sql_query("SELECT * FROM contacts WHERE name=\"".$name."\"");
      //if a contact is found
      if(!empty($contacts)){
        foreach($contacts[0] as $key => $val){
            $matchval = array_search($key,$fields);
            if($matchval !== false){
                $fields[$matchval]=$val;
            }
        }
        $datas['error']=false;
        $datas['success']=true;
        $datas['contacts']=$fields;
        echo(json_encode($datas));
      }else{
        $datas['success']=false;
        $datas['error']=true;
        $datas['fields']=$fields;
        $datas['name']=$_GET['contacts'];
        $datas['contacts']="We did not find a match in your contacts for ".$_GET['contacts'].".\n Would you like to create a new contact for ". $_GET['contacts']."?";
        echo(json_encode($datas));
      }
  }else{
    $datas['success']=false;
    $datas['error']=true;
    $datas['fields']=$fields;
    echo(json_encode($datas));
  }
}
if(isset($_GET['getcontacts'])){
   $contacts = sql_query("SELECT name FROM contacts ORDER BY name");
   $r=array();
   for($c = 0; $c<count($contacts); $c++){
       $r[]=$contacts[$c]['name'];
   }
   $contacts = $r;
   $data['results']=$contacts;
   $data['success']=true;
   echo json_encode($data);
}
?>
