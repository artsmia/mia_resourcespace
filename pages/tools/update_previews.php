<?php
/**
 * Quick 'n' dirty script to update all preview images.
 * 
 * @package ResourceSpace
 * @subpackage Team_Admin
 */

# options:
# you can run the script straight, and it will try to create a preview from 1 to the ref of the current resource
# This might not be efficient if you don't have resources at all ref numbers.
# Alternatively, you can create a collection with the resources you'd like to preview, and pass col=[collectionid] (without brackets),
# This will recreate previews for all resources in that collection. 
# previewbased=true is an option that can help preserve alternative previews, 
# Recreating previews would normally use the original file and overwrite alternative previews that have been uploaded,
# but with previewbased=true, it will try to find a suitable large preview image to generate the smaller versions from.
# If you want to recreate preview for a single resource, you can pass ref=[ref]&only=true


include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php"; if (!checkperm("a")) {exit("Permission denied");}
include "../../include/image_processing.php";
include "../../include/resource_functions.php";
include_once "../../include/collections_functions.php";

/**
 * Update previews for given ref.
 * 
 * @param $ref Resource ref to update
 * @return bool True is resource exists
 */
function update_preview($ref){
    global $previewbased;
    $resourceinfo=sql_query("select * from resource where ref='$ref'");
    if (count($resourceinfo)>0 && !hook("replaceupdatepreview", '', array($ref, $resourceinfo[0]))){
    	if(!empty($resourceinfo[0]['file_path'])){$ingested=false;}
    	else{$ingested=true;}
        create_previews($ref, false,($previewbased?"jpg":$resourceinfo[0]["file_extension"]),false, $previewbased,-1,false,$ingested);
        hook("afterupdatepreview","",array($ref));
        return true;
    }
    return false;
}
$collectionid=getvalescaped("col", false);
$max=sql_value("select max(ref) value from resource",0);
$ref=getvalescaped("ref",false);
$previewbased=getvalescaped("previewbased",false);

if ($collectionid == false){
    if (!(is_numeric($ref) && $ref > 0)) $ref = 1;
    if (update_preview($ref)){
    	?>
    	<img src="<?php echo get_resource_path($ref,false,"pre",false)?>">
    	<?php
    	}
    else
    	{
    	echo "Skipping $ref";
    	}
    if ($ref<$max && getval("only","")=="")
    	{
    	?>
    	<meta http-equiv="refresh" content="0;url=<?php echo $baseurl?>/pages/tools/update_previews.php?ref=<?php echo $ref+1?>&previewbased=<?php echo $previewbased?>"/>
    	<?php
    	}
    else
    	{
    	?>
    	Done.	
    	<?php
    	}
}
else {
    $collection = get_collection_resources($collectionid);
    if (!is_array($collection)){
        echo "Collection id returned no resources.";
        die();
    }
    if (!(is_numeric($ref) && $ref > 0)){
        $ref = $collection[0];
        $key = 0;
    }
    else {
        $key = array_search($ref, $collection);
    }
    if (update_preview($ref)){
        ?>
        <img src="<?php echo get_resource_path($ref,false,"pre",false)?>">
        <?php 
    }
    if (isset($collection[$key+1])){
        $next_ref = $collection[$key+1];
        ?>
        <meta http-equiv="refresh" content="0;url=<?php echo $baseurl?>/pages/tools/update_previews.php?col=<?php echo $collectionid?>&ref=<?php echo $next_ref?>&previewbased=<?php echo $previewbased?>"/>
        <?php
    }
    else {
        ?>
        Done.
        <?php 
    }
    
}

	
