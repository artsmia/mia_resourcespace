<?php

function HookMia_stylingThemesAdditionalsmartthemetool(){?>
<script type="text/javascript">
initThemes = function(){
    jQuery("#themeform .tools .ListTools, td > .ListTools").after("<button class='tools-btn'>tools</button>");
    jQuery("#themeform .tools-btn").click(function(e){
        e.preventDefault();
        jQuery(this).prev().slideToggle();
    });
    jQuery("#themeform .RecordBox").wrapAll("<div id='mason-grid' />");
    jQuery("#mason-grid").append("<div class='grid-sizer'></div>");
    var $container = jQuery('#mason-grid');
    $container.masonry({
        "itemSelector": "#themeform .RecordBox",
        "columnWidth": ".grid-sizer",
        "percentPosition": true,
    });
};
</script>
<?php
return true;
}

