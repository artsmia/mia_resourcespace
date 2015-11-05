<?php
function HookNewsHomeSearchbarbottomtoolbar()
	{
	global $lang,$site_text,$baseurl;
	include_once dirname(__FILE__)."/../inc/news_functions.php";
	$recent = 3;
	$findtext = "";
	$news = get_news_headlines("",$recent,"");
	$results=count($news);
   	?>
	<div id="ssearchbox" class="HomePanelIN">
		<h1 style="color:#FFF;"><?php echo $lang['title']; ?></h1>
		<div id="NewsBodyDisplay">
         <?php
		if($results > 0)
			{
			for($n = 0; ($n < $results); $n++)
				{
				?>
				<p>&gt;<a href="<?php echo $baseurl; ?>/plugins/news/pages/news.php?ref=<?php echo $news[$n]['ref']; ?>"><?php echo $news[$n]['title']; ?></a></p>
				<?php
				}
			}
		else
			{
			echo $lang['news_nonewmessages'];
			}
			?>
		</div>
	</div>
	<?php
	}

function HookNewsHomeHomebeforepanels() 
	{
	if (getvalescaped("ajax",false))
  		{
		?>
		<script>ReloadSearchBar();</script>
		<?php
		}
	}
