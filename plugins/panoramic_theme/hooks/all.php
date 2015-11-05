<?php
function HookPanoramic_themeAllHomepanelcontainerstart() {
	global $pagename,$baseurl;
	if($pagename=="home"){
		?><style>
		.HomePanelIN {
			background: rgba(0, 62, 81, 0.59);
		}
		#SearchBoxPanel,#HomeSiteText {
			background: rgba(0, 62, 81, 0.59);
			border: 1px solid #d0d0d0;
		}
		body, html {
		color: #383838;
		background: url('<?php echo $baseurl;?>/plugins/panoramic_theme/gfx/homeanim/2.jpg');
		}
		</style><?php
	}
}
