<?php

# This script is useful if you've added an exiftool field mapping and would like to update RS fields with the original file information 
# for all your resources.

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php"; if (!checkperm("a")) {exit("Permission denied");}
include "../../include/resource_functions.php";
include "../../include/image_processing.php";

set_time_limit(60*60*40);

# ex. pages/tools/update_exiftool_field.php?fieldrefs=75,3&blanks=true
$fieldrefs=getval("fieldrefs",0);
if ($fieldrefs==0){die ("Please add a list of refs to the fieldrefs url parameter, which are the ref numbers of the fields that you would like exiftool to extract from. <br /><br />For example: pages/tools/update_exiftool_field.php?fieldrefs=75,3");}

$exiftool_fullpath = get_utility_path("exiftool");
if ($exiftool_fullpath==false) {die ("Could not find Exiftool.");}

$blanks=getval("blanks","true"); // if new value is blank, it will replace the old value.
$fieldrefs=explode(",",$fieldrefs);


$collectionid=getvalescaped("col", false);

foreach ($fieldrefs as $fieldref){
	$fieldref_info= sql_query("select exiftool_field,exiftool_filter,title,resource_type,name from resource_type_field where ref='$fieldref'");
	if (!isset($fieldref_info[0])){die("field $fieldref doesn't exist");}
	$title=$fieldref_info[0]["title"];
	$name=$fieldref_info[0]["name"];
	$exiftool_filter=$fieldref_info[0]["exiftool_filter"];
	$exiftool_tag=$fieldref_info[0]["exiftool_field"];
	$field_resource_type=$fieldref_info[0]["resource_type"];

	if ($exiftool_tag==""){ die ("Please add an exiftool mapping to your $title Field");}


	echo "<b>Updating RS Field $fieldref - $title, with exiftool extraction of: $exiftool_tag</b><br><br>";

	$join="";
	$condition = "";
	$conditionand = "";
	if ($collectionid != false)
			{
			$join=" inner join collection_resource on collection_resource.resource=resource.ref "; 
			$condition = "where collection_resource.collection = '$collectionid' ";
			$conditionand = "and collection_resource.collection = '$collectionid' ";
			}
	
	if($field_resource_type==0){
		$rd=sql_query("select ref,file_extension from resource $join $condition order by ref");
	} else {
		$rd=sql_query("select ref,file_extension from resource $join where resource_type=$field_resource_type $conditionand order by ref");
	}	
    $exiftool_tags=explode(",",$exiftool_tag);
	for ($n=0;$n<count($rd);$n++)
		{		
		
		$ref=$rd[$n]['ref'];
		$extension=$rd[$n]['file_extension'];
	
		$image=get_resource_path($ref,true,"",false,$extension);
		if (file_exists($image)) {
		
		
		$resource=get_resource_data($ref);
		foreach ($exiftool_tags as  $exiftool_tag) 
    		{	
    		$command = $exiftool_fullpath . " -s -s -s -" . $exiftool_tag . " " . escapeshellarg($image);
    	    echo $command;
    		$value = iptc_return_utf8(trim(run_command($command)));
    	
    		$plugin="../../plugins/exiftool_filter_" . $name . ".php";
    		if ($exiftool_filter!=""){
    			eval($exiftool_filter);
    			}
    		if (file_exists($plugin)) {include $plugin;}
    	
    		if ($blanks=="true"){
    			update_field($ref,$fieldref,$value);
    			echo ("<br>Updated Resource $ref <br> -Exiftool found \"$value\" embedded in the -$exiftool_tag tag and applied it to ResourceSpace Field $fieldref<br><br>");
    			}
    		else {
    			if ($value!=""){
    				update_field($ref,$fieldref,$value);
    				echo ("<br>Updated Resource $ref <br> -Exiftool found \"$value\" embedded in the -$exiftool_tag tag and applied it to ResourceSpace Field $fieldref<br><br>");	
    			}
    		}
		}
	}		
}
}	
echo "...done.";


