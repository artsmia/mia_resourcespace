<?php
$contact_categories = array("individual","institution");
$license_fields=array("name","URL","email");
$creator_fields=array();

//----------------------------------------------
//    Mapping for contacts to resource fields
//----------------------------------------------

//map to associate field parents to their children when retrieving
$contact_fields=array(
    80=>array(126,133,127,132,128,131,130,129,134),
    184=>array(183,186),
    171=>array(),
    177=>array(),
    125=>array(),
    178=>array(),
    180=>array()
);

//map to temp store original values for each field type when updating
$oldvalmatch = array(
    "name"=>array(80,125,171,177,178,180,184),
    "title"=>array(133),
    "address"=>array(126),
    "city"=>array(127),
    "state"=>array(132),
    "country"=>array(128),
    "zipcode"=>array(131),
    "phone"=>array(130),
    "email"=>array(129,183,129),
    "URL"=>array(134,186,172)
);
//map field ids to contact form values
if(isset($_POST) && !empty($_POST) && isset($_POST['contact_name'])){
  $cfields=array(
    "license" => array(
        184=>$_POST['contact_name'],
        186=>$_POST['contact_url'],
        183=>$_POST['contact_email'],
    ),
    "creator" => array(
        80=>$_POST['contact_name'],
        134=>$_POST['contact_url'],
        129=>$_POST['contact_email'],
        130=>$_POST['contact_phone'],
        133=>$_POST['contact_title'],
        126=>$_POST['contact_address'],
        127=>$_POST['contact_city'],
        132=>$_POST['contact_state'],
        128=>$_POST['contact_country'],
        131=>$_POST['contact_zipcode'],
    ),
    "commons" => array(
        171 => $_POST['contact_name'],
        172 => $_POST['contact_url'],
    ),
    "media_cataloguer" => array(125=>$_POST['contact_name']),
    "media_source" => array(177=>$_POST['contact_name']),
    "rights_owner" => array(178=>$_POST['contact_name']),
    "media_supplier" => array(180=>$_POST['contact_name']),
  );
}
?>
