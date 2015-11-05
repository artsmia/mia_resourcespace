<?php
# Popup category tree for use with the simple search.

include "../../include/db.php";
include "../../include/general.php";
include "../../include/authenticate.php";

$field=getvalescaped("field","");
$value=getvalescaped("value","");

# Set the expected options
$fdata=get_resource_type_field($field);
$name=$fdata["name"];
$options=$fdata["options"];

#echo $options;
?>

<p align="right"><a href="#" onClick="document.getElementById('cattree_<?php echo $name ?>').style.display='none';return false;"><?php echo $lang["close"] ?></a></p>
<?php


# Show the category tree
$category_tree_open=true;
$treeonly=true;
include dirname(__FILE__)."/../edit_fields/7.php";

?>
