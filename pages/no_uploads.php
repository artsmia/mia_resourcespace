<?php

include "../include/db.php";
include "../include/general.php";
include "../include/authenticate.php"; 


include "../include/header.php";

?>
<div class="BasicsBox">
	<p><a href="<?php echo $baseurl?>/pages/<?php echo $default_home_page?>" onClick="return CentralSpaceLoad(this,true);">&lt;&nbsp;<?php echo $lang["home"]?></a></p>
	<h1><?php echo $lang['disk_size_no_upload_heading'] ?></h1>
	<p><?php echo $lang['disk_size_no_upload_explain'] ?></p>
</div>
<?php

include "../include/footer.php";
