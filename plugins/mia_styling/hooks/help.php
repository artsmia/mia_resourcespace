<?php
function HookMia_stylingHelpReplacehelp(){
  global $baseurl;
  $ppages = sql_query("SELECT * FROM plugin_pages");
  ?>
  <h1>Help &amp; Advice</h1>
  <p>Click on a topic below to begin learning.</p>
  <ul id="knowledge-links">
     <?php foreach($ppages as $key => $content){
         if($content['page_parent']==NULL){?>
             <li><a id="section_<?php echo $content['page_id'];?>"><?php echo $content['page_title'];?></a></li>
     <?php }
     }?>
     <div class="clear"></div>
  </ul>
  <div id="knowledge-subnav">
       <?php
       $subnav = array();
       foreach($ppages as $key => $val){
         if($val['page_parent']!=NULL){
             $subnav[$val['page_parent']][$val['page_id']]=$val['page_title'];
         }
       }
       foreach($subnav as $subkey => $subval){?>
       <ul style="display:none" class="subnav" id="sub_<?php echo $subkey ?>">
          <li><a id="subli_<?php echo $subkey?>">Overview</a></li>
          <?php
          foreach($subval as $sk => $sv){?>
              <li><a id="subli_<?php echo $sk;?>"><?php echo $sv;?></a></li>
<?php
          }?>
       </ul>
<?php
       }
?>
  </div>
<div class="help-section">
  <p>
    Here you can find some helpful tools and tips we have put together to help get the most out of Resourcespace.
    Feel free to visit the <a href="http://www.resourcespace.org/knowledge-base" target="_BLANK">ResourceSpace Knowledge Base</a> to dig in deeper to the system.
  </p>
</div>
  <?php foreach ($ppages as $k => $v){?>
             <div style="display:none;" id="main_<?php echo $v['page_id'];?>"><?php echo html_entity_decode($v['page_content']);?></div>
      <?php
  }?>

  <script type="text/javascript">
  jQuery(document).ready(function(){
      //main nav
      jQuery("#knowledge-links a").click(function(e){
          e.preventDefault();
          jQuery("#knowledge-links a").removeClass("active");
          jQuery(".subnav a").removeClass("active");
          jQuery(this).addClass("active");
          var page = jQuery(this).prop("id");
          page = page.substring(8);
          jQuery("#subli_"+page).addClass("active");
          var showhtml = jQuery("#main_"+page).html();
          jQuery(".subnav").css("display","none");
          jQuery("#sub_"+page).show();
          jQuery(".help-section").hide().empty().html(showhtml).show("clip");
      });
      jQuery(".subnav a").click(function(e){
          e.preventDefault();
          jQuery(".subnav a").removeClass("active");
          jQuery(this).addClass("active");
          var page = jQuery(this).prop("id");
          page = page.substring(6);
          console.log(page);
          var showhtml = jQuery("#main_"+page).html();
          jQuery(".help-section").hide().empty().html(showhtml).show("clip");
      })
   })
  </script>
<?php
return true;
}
?>
