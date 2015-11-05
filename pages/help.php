<?php
include "../include/db.php";
include "../include/general.php";
include "../include/authenticate.php";

$section=getvalescaped("section","");

include "../include/header.php";
?>

<div class="BasicsBox"> 

<?php if (!hook("replacehelp")){?>
<?php if ($section=="") { ?>
  <h1><?php echo $lang["helpandadvice"]?></h1>
  <p><?php echo text("introtext")?></p>
  
  <div class="VerticalNav">
  <ul>
  <?php
  $sections=get_section_list("help");
  for ($n=0;$n<count($sections);$n++)
  	{
  	?>
  	<li><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/help.php?section=<?php echo urlencode($sections[$n])?>"><?php echo htmlspecialchars($sections[$n])?></a></li>
  	<?php
  	}
  ?>
  </ul>
  </div>
  
<?php } else { ?>
  <h1><?php echo htmlspecialchars($section)?></h1>
  <p><?php echo text($section)?></p>
  <p><a onClick="return CentralSpaceLoad(this,true);" href="<?php echo $baseurl_short?>pages/help.php">&lt;&nbsp;<?php echo $lang["backtohelphome"]?></a></p>
<?php } ?>
<?php } // end hook replacehelp?>


</div>

<?php
include "../include/footer.php";
?>
