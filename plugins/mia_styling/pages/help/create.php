<?php
include __DIR__ . "/../../../../include/db.php";
include __DIR__ . "/../../../../include/authenticate.php"; if (!checkperm('a')) {exit ($lang['error-permissiondenied']);}
include __DIR__ . "/../../../../include/general.php";
$ppages = sql_query("SELECT * FROM plugin_pages");
$edit = false;
if(isset($_GET['ref'])){
   $pref = $_GET['ref'];
   $edit = true;

}
if(isset($_POST) && $_POST !=""){
  $query=array();
  foreach($_POST as $key => $val){
    if($val != "" && $val != "NULL"){
        $query[]="page_".$key."='".htmlentities($val,ENT_QUOTES)."'";
    }
  }
  if(!empty($query)){
     if($edit==false){
     $result =  sql_query("INSERT INTO plugin_pages SET ".implode(",",$query));
     }else if($edit == true){
     $result = sql_query("UPDATE plugin_pages SET ".implode(",",$query)."WHERE page_id =".$pref);
     }
     if(!mysql_errno()){
       if($edit==true){
           echo("Successfully Updated Help Section");
       }else{
           echo("Successfully added page");
       }
     }else{
       die('Invalid query: ' . mysql_error());
     }
  }
}
if(isset($_GET['ref'])){
   $epage = sql_query("SELECT * FROM plugin_pages WHERE page_id =".$pref);
   $epage=$epage[0];
}
include __DIR__."/../../../../include/header.php";
include __DIR__."/nav.php";
?>
<div id="help-upload">
    <a id="help-upload-close">Close</a>
    <div id="existing-images">
        <h3>Help Section Image Library</h3>
        <ul></ul>
    </div>
    <form id="form-help-upload" method="POST" enctype="multipart/form-data">
        <h3>Add New Image</h3>
        <h5><em>Files < 10MB with extensions JPG, PNG and GIF are allowed.</em></h5>
         <input id="form-help-file" type="file"/>
         <input id="form-help-submit" type="submit" value="upload" disabled="disabled"/>
    </form>
</div>
<form class="clear" id="help-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; if(isset($_GET['ref'])){echo '?ref='.$_GET['ref'];}?>">
  <label for="title">Title:<br/>
    <input type="text" id="help-form-title" name="title" value="<?php if(isset($epage['page_title'])){echo $epage['page_title'];} ?>"/>
  </label><br/>
  <button id="btn-add-img">Add Image</button><br/>
  <label for="content">Content:
    <textarea name="content" id="content">
        <?php if(isset($epage['page_content'])){echo $epage['page_content'];}?>
    </textarea>
  </label>
  <?php if(count($ppages)>0){?>
  <label for="parent">Parent:
  <select name="parent" id="parent">
      <option value="NULL">--</option>
      <?php foreach($ppages as $k => $v){?>
      <option value="<?php echo $v['page_id'];?>" <?php if(isset($epage['page_parent']) && $epage['page_parent']== $v['page_id']){echo "selected";}?>><?php echo $v['page_title'];?></option>
      <?php }?>
  </select>
  </label>
  <?php }?>
  <label for="order">Order:
    <input type="text" name="order" value="<?php if(isset($epage['page_order'])){echo $epage['page_order'];}?>"/>
  </label><br/>

  <input type="submit" value="<?php if($edit == true){echo 'save';}else{echo 'create';}?>"/>
</form>
<script>
CKEDITOR.replace( 'content' );
var baseurl = "<?php echo $baseurl?>";

function GetImages(){
  jQuery.ajax({
     url: "<?php echo $baseurl?>/plugins/mia_styling/pages/help/getimages.php",
     type: "GET",
     success: function(data){
         data = JSON.parse(data);
         if(data.error==false){
         var display = "";
         jQuery.each(data.msg,function(k,v){
             display+="<li><img src='"+baseurl+"/plugins/mia_styling/gfx/help-files/"+v+"'/>"+
             "<h4>"+ v +"</h4>"+
             "<h5> Url: "+baseurl+"/plugins/mia_styling/gfx/help-files/"+v+"</h5><a onclick='DeleteHelpImg(\""+v+"\");'>Delete</a><div class='clear'></div></li>";
         });
         }else{
             display = data.msg;
         }
         jQuery("#existing-images ul").empty().append(display);
     },
     error: function(){
         jQuery("#existing-images").append("Sorry we are unable to retrieve images at this time.");
     }
  });
}

//Delete Image
function DeleteHelpImg(img){
   var conf = confirm("are you sure?");
   if(conf==false){
       return; 
   }else{
       jQuery.ajax({
           url: "<?php echo $baseurl?>/plugins/mia_styling/pages/help/deleteimage.php",
           type: "POST",
           data: {img},
           success: function(){
               GetImages();
           },
           error: function(){  
              alert("Sorry unable to delete image");
           }
       });
   }
}

var allowedext = ['jpg','jpeg','png','gif'];
jQuery("#form-help-file").on("change",function(){
    fchk=this.files[0];
    var msg = [];
    var extension = fchk.name.replace(/^.*\./, "").toLowerCase();
    if(jQuery.inArray(extension, allowedext)===-1){
        msg.push("Sorry only "+allowedext+" file extensions are allowed");
    }
    if(fchk.size > 1000000){
        msg.push("Max File Size is 10MB.")
    }
    if(msg.length === 0){
        jQuery("#form-help-submit").removeAttr("disabled").css({"background":"#0a9775","color":"#FFFFFF"});
    }else{
        alert(msg);
    }
    console.log(fchk);
});
//Add image button
jQuery("#btn-add-img").click(function(e){
  e.preventDefault();
  GetImages();
  jQuery("#help-upload").css("display","block");
});
//Upload Submit
 jQuery("#form-help-submit").click(function(e){
    e.preventDefault();
    var fd = new FormData();
    var file = jQuery("#form-help-file")[0].files[0];
    fd.append('file', file);
    jQuery.ajax({
        url: "<?php echo $baseurl?>/plugins/mia_styling/pages/help/upload.php",
        processData:false,
        data: fd,
        contentType: false,
        type: "POST",
        cache:false,
        success: function(data){
            alert(data);
            GetImages();
        },
        error: function(data){
            console.log("error");
        }
    });
 });
jQuery("#help-upload-close").click(function(e){
    jQuery("#help-upload").hide();
});
</script>
<?php
include __DIR__."/../../../../include/footer.php";
?>
