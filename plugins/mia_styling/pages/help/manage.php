<?php
include __DIR__ . "/../../../../include/db.php";
include __DIR__ . "/../../../../include/authenticate.php"; if (!checkperm('a')) {exit ($lang['error-permissiondenied']);}
include __DIR__ . "/../../../../include/general.php";
include __DIR__ . "/../../../../include/header.php";
?>

<style type="text/css">
  table{width: 100%;}
  td, th{border: 1px solid !important; padding: 5px; margin: 0;}
  td a{display: block; margin: 0 5px; float: left;}
</style>

<?php include __DIR__ . "/nav.php"; ?>

<table>
  <thead>
    <th>Title</th>
    <th>Parent</th>
    <th>Actions</th>
  </thead>
  <tbody>
  <?php
  $ppages = sql_query("SELECT * FROM plugin_pages ORDER BY page_parent");
  if($ppages){
    $parent_name = array();
    foreach($ppages as $k => $v){
    $parent_name[$v['page_id']]=$v['page_title'];
    ?>
      <tr>
        <?php foreach($v as $kv => $vv){
          if($kv == "page_title"){?>
              <td><?php echo $vv ?></td>
            <?php
          }else if($kv == "page_parent"){
              if($vv != NULL && $vv != ""){?>
                  <td><?php echo $parent_name[$vv];?></td>
              <?php
              }else{?>
                  <td>--</td>
              <?php
              }
           }
        } ?>
        <td>
            <a id="<?php echo $v['page_id'];?>" href="<?php echo $baseurl?>/plugins/mia_styling/pages/help/create.php?ref=<?php echo $v['page_id'];?>">Edit</a>
            <a id="<?php echo $v['page_id'];?>" class="delete_page">Delete</a>
        </td>
      </tr>
      <?php
    }
  }
?>
  </tbody>
</table>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".delete_page").click(function(e){
            console.log("working");
            e.preventDefault();
            var pref = jQuery(this).prop("id");
            jQuery.ajax({
                url: "<?php echo $baseurl?>/plugins/mia_styling/pages/help/delete_page.php?ref="+pref,
                type: "POST",
                contentType: "JSON",
                cache: false,
                success: function(data){
                    alert(data);
                    location.reload();
                },
                error:function(){
                   alert(data);
                }
            })
        })
    })
</script>

<?php include __DIR__ . "/../../../../include/footer.php"; ?>
