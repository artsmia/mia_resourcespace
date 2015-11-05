<?php

function HookMontala_supportTeam_homeCustomteamfunction()
    {
    global $baseurl;
    ?>
    <script src="http://accounts.montala.com/service_check.php?baseurl=<?php echo urlencode($baseurl) ?>"></script>
    <?php
    }
