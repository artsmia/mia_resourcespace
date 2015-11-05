<?php
function HookMia_stylingAllAdditionalheaderjs(){
?>
<script src="/lib/js/masonry.pkgd.min.js"></script>
<?php
}

function HookMia_stylingAllSearchbarbeforebuttons(){
//------------------------------------------------+
//    New Simple Search "Contributed By" field    |
//------------------------------------------------+
    //Get all the users
    $users = sql_query("SELECT fullname,username,ref FROM user ORDER BY fullname");

    //Check that they have a valid fullname set else use username else remove from list
    for($u=0; $u<count($users); $u++){
        if($users[$u]['fullname']==""){
            if($users[$u]['username']!=""){
                $users[$u]['fullname']=$users[$u]['username'];
            }else{
                unset($users[$u]);
            }
        }
    }
    //build the select list
    if($users){?>
       <div class="SearchItem">
           Contributed By:<br/>
           <select  id="contrib" class="SearchWidth" name="contrib">
               <option value=""></option>
               <?php
               for($u=0; $u<count($users); $u++){?>
                   <option  <?php if(isset($_COOKIE['contrib']) && $_COOKIE['contrib'] == $users[$u]['ref']){echo "selected";}?> value="!contributions<?php echo $users[$u]['ref'];?>"><?php echo $users[$u]['fullname'];?></option>
               <?php } ?>
           </select>
       </div>
     <?php
    }
//return true;
}
//-------------------------+
//    Active Navigation    |
//-------------------------+

function HookMia_stylingAllFooterbottom(){
  global $pagename, $ref;
  $active_page=$pagename;
  if($active_page=="edit"){
    if($ref>0){
     $active_page="";
    }
  }
//}
?>
<script>
rsReady = function(pagename){
    //--------------------------------+
    //    Simple Search Checkboxes    |
    //--------------------------------+
    jQuery(document).ajaxStop(function(){
           jQuery("#searchbarrt .tick").each(function(){
               var inp = jQuery(this).find(".tickbox");
               if(inp.prop("checked")==true){
                   jQuery(this).css("opacity","1");
               }else{
                   jQuery(this).css("opacity","0.1");
               }
           });
           //Set display on click
           jQuery("#searchbarrt .tick").click(function(){
               var selector = jQuery(this).find("input:checkbox");
               if(selector.prop("checked")==true){
                   jQuery(this).animate({"opacity":"1"},50);
               }else{
                   jQuery(this).animate({"opacity":"0.1"},50);
               }
           })
    });

    jQuery("#field_153").addClass("tms-input").after("<span class='tms-ico'>TMS</span>");

    jQuery(".ResourcePanelShell").css("display","none"); 
    jQuery(".RecordBox").css("display","none").fadeIn("slow");
    jQuery("#form-wrap").css("display","none").fadeIn("slow");
    jQuery(".ResourcePanelShell").each(function(i){
        jQuery(this).delay(i*50).fadeIn("slow");
    });

    //Help text description
    jQuery(".SearchItem").hover(function() {
        if(jQuery(this).attr("title") != undefined){
            jQuery(this).data("title", jQuery(this).attr("title")).removeAttr("title");
            jQuery(this).append("<div class='disp-txt'>" + jQuery(this).data("title")  + "</div>");
        }
    },function(){
        jQuery(this).attr("title",jQuery(this).data("title"));
        jQuery(".disp-txt").remove();
    });

    //------------------------+
    //    Global Functions    |
    //------------------------+
    function setActiveNav(pagename){
        var pgname = "<?php echo $active_page; ?>";
        console.log(pgname);
    //    if (pagename != "undefined" && pagename != ""){
    //        var pgname = pagename;
    //    }
        jQuery('body').attr('class','ui-layout-container '+ pgname);
        var pages = {
            "home":"Home",
            "themes":"Featuredcollections",
            "edit":"Upload",
            "search":"Recentlyadded",
            "help":"Help&advice",
            "team_home":"TeamCenter"
        };
        if (pages.hasOwnProperty(pgname)) {
            pgname = pages[pgname].replace(/ /g,'');
            jQuery("#HeaderNav2 ul > li").each(function(){
                jQuery(this).removeClass("active");
                txt = jQuery(this).text().trim().replace(/ /g,'');
                if(txt==pgname){
                    jQuery(this).addClass("active");
                }
            });
         }else{
           jQuery("#HeaderNav2 ul > li").removeClass("active");
         };
    }
    setActiveNav(pagename);

    jQuery(".removeFromCollection").click(function(){
        jQuery(this).parents(".ResourcePanelShell").remove();
    });
    jQuery(".addToCollection").click(function(){
        jQuery(this).effect("highlight");
        jQuery("#CollectionMinitems strong").effect("highlight");
    });
}//end AdditionalJs 
</script>
<?php
}
function HookMia_stylingAllSwfplayer(){
global $flashfile, $baseurl, $baseurl_short, $width, $height, $flashpath, $color, $bgcolor1, $bgcolor2, $pagename, $buttoncolor, $thumb;?>
    <video controls="true">
        <source src="<?php echo $baseurl . str_replace('/var/www/include/..',"",$flashfile); ?>">
        <!-- Fallback to flash if HTML5 not supportet -->
        <object type="application/x-shockwave-flash" data="<?php echo $baseurl_short?>lib/flashplayer/player_flv_maxi.swf?t=<?php echo time() ?>" width="<?php echo $width?>" height="<?php echo $height?>" class="Picture">
        <param name="allowFullScreen" value="true" />
        <param name="movie" value="<?php echo $baseurl_short?>lib/flashplayer/player_flv_maxi.swf" />
        <param name="FlashVars" value="flv=<?php echo $flashpath?>&amp;width=<?php echo $width?>&amp;height=<?php echo $height?>&amp;margin=0&amp;showvolume=1&amp;volume=200&amp;showtime=2&amp;autoload=1&amp;<?php if ($pagename!=="search"){?>showfullscreen=1<?php } ?>&amp;showstop=1&amp;buttoncolor=<?php echo $buttoncolor?>&playercolor=<?php echo $color?>&bgcolor=<?php echo $color?>&bgcolor1=<?php echo $bgcolor1?>&bgcolor2=<?php echo $bgcolor2?>&startimage=<?php echo $thumb?>&playeralpha=75&autoload=1&buffermessage=&buffershowbg=0" />
      </object>

    </video>
<?php
return true;
    }
function HookMia_stylingAllMp3player(){
global $mp3path, $baseurl, $baseurl_short, $pagename, $color, $buttoncolor, $bgcolor1, $bgcolor2;?>
<tr class="DownloadDBlend">
<td><h2> Preview </h2></td>
<td align="center" colspan="2"><center>
<audio controls>
  <source src="<?php echo str_replace('/var/www/include/..',"",$mp3path); ?>">
  <object type="application/x-shockwave-flash" data="<?php echo $baseurl_short?>lib/flashplayer/player_mp3_maxi.swf" width="200" height="20" <?php if ($pagename=="search"){?>style="margin:0px;margin-top:-20px;margin-left:auto;margin-right:auto;"<?php }?>>
  <param name="movie" value="<?php echo $baseurl_short?>lib/flashplayer/player_mp3_maxi.swf" />
  <param name="FlashVars" value="mp3=<?php echo $mp3path?>&width=200&buttoncolor=<?php echo $buttoncolor?>&playercolor=<?php echo $color?>&bgcolor=<?php echo $color?>&bgcolor1=<?php echo $bgcolor1?>&bgcolor2=<?php echo $bgcolor2?>&volume=100&showvolume=1" />
  </object>
</audio>
</center>
</td>
</tr>

<?php
    return true;
}

function HookMia_stylingAllSearchbarbottom(){
global $baseurl;
?>
    <script type="text/javascript">
        jQuery("#clearbutton").click(function(){
            jQuery("#contrib option:first").attr("selected","selected");
            jQuery.ajax({
                url:"<?php echo $baseurl?>/plugins/mia_styling/pages/kill_cookie.php",
                type: "POST",
                success: function(){
                   console.log("You Killed Cookie!!");
                },
                error: function(){
                   console.log("Its Alive");
                },
            });
        });
    </script>
<?php
    return;
}
function HookMia_stylingAllReplacefield($type,$ref,$n){
global $fields;
if(checkperm("ro-".$ref)){
  if(!empty($fields[$n]['value']) && trim($fields[$n]['value'])!="," && $fields[$n]['value'] != " "){
    $stat =  $fields[$n]['value']." - <em>Read Only</em>";
  }else{
    $stat = "N/A - <em>Read Only</em>";
  }
  echo $stat;
  echo "<script>jQuery(window).ready(function(){jQuery('#field_".$ref.",#field_".$ref."-d,#field_".$ref."-m,#field_".$ref."-h,#field_".$ref."-i,#field_".$ref."-y,#field_".$ref."_selector').hide()});</script>";
}
$helptext = '<div class="FormHelp-selector" onmouseleave="HideHelp('.$ref.')" onmouseenter="ShowHelp('.$ref.'); return false;"><em>i</em></div>';
echo ($helptext);
return false;
}
