<?php
include "../../../include/db.php";
include "../../../include/authenticate.php"; 
if (!checkperm("r")) {exit ("Permission denied.");}
include "../../../include/general.php";
include "../../../include/resource_functions.php";
$ref=getvalescaped("ref","");
$resource=getvalescaped("resource","");
include "../../../include/header.php";
?>
<style type="text/css">
    #CentralSpace label{width: 100px; text-align: right; display: block; float: left; margin-right: 15px;}
    #CentralSpace input{margin-bottom: 10px;}
    #CentralSpaceContainer{padding:0 !important;}
    #contacts-nav { list-style:none;}
    #contacts-nav li{display: inline-block; margin: 0 5px;}
    #CentralSpace ul li > a {margin-right: 10px;}
    #contact_delete{color:#F00;}
    table{width: 100%; margin: 20px 0; border-collapse: collapse;}
//    td{width: 10%;}
    tbody{width: 100%;}
    th,td{border: 1px solid #CCC; padding: 10px;}
    th{text-align:center;}
    tr:nth-child(even){background: #FFFFFF;}
    tr:nth-child(odd){background:#EEEEEE;}
    tr{border-bottom:1px solid #CCCCCC}
    thead tr{background:#FFFFFF !important}
    #SearchBox{display:none;}
   
</style>
<ul id = "contacts-nav">
    <li><a href="setup.php">Add New</a></li>
    <li><a href="manage.php">Manage Contacts</a></li>
</ul>
<h2>Manage Contacts</h2>
<?php

if(isset($_POST['ref']) && !empty($_POST['ref'])){
    $ref = $_POST['ref'];
    //this is a little dangerous - should fix
    sql_query("DELETE FROM contacts WHERE ref= $ref");
    sql_query("UPDATE contacts SET parent = 0 WHERE parent = $ref");
};
$contacts = sql_query("SELECT * FROM contacts ORDER BY name");
if(!$contacts){
    echo("There currently are no contacts in the database");
}else{
    ?>
    <table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Title</th>
<!--        <th>City</th>
        <th>State</th>
        <th>Country</th>
        <th>Zip Code</th>-->
        <th>Phone</th>
        <th>Email</th>
<!--        <th>URL</th> -->
        <th>Type</th>
<!--        <th>Constituent</th>-->
        <th>Parent</th>
        <th>Address</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
$names=array();
for($c=0; $c<count($contacts); $c++){
  $names[$contacts[$c]['ref']]=$contacts[$c]['name'];
}
    for($c=0; $c<count($contacts); $c++){
       echo("<tr>");
       $ref = $contacts[$c]['ref'];
       $address_comp="";
       foreach($contacts[$c] as $key => $val){
           if($key == "address" || $key == "city" || $key == "state" || $key == "country" || $key == "zipcode"){
             $address_comp.=$val." ";
           }else if($key != "ref" && $key != "type" && $key !="parent" && $key !="URL" && $key !="constituent_id"){
               echo("<td>" . $val . "</td>");
           };
           if($key == "parent"){
               if($val != "" && $val != 0){
               //convert value to name
               echo("<td>".$names[$val]."</td>");
               }else{
               echo("<td>--</td>");
               }
           }
           if($key == "type"){
               //convert value to name
               if($val==0){echo("<td>Individual</td>");}else if($val==1){echo("<td>Institution</td>");};
           }
       };echo("<td>" . $address_comp . "</td>");
    ?>
           <td>
               <?php if(checkperm("ctE")){?>
               <a onclick='return CentralSpaceLoad(this,true);' class='contact_edit' href='setup.php?edit=<?php echo($ref); ?>'>Edit</a>
               <?php }?>
               <form class="contact edit" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                   <input name="ref" type="hidden" value="<?php echo ($ref);?>"/>
                 <?php if(checkperm("ctD")){ ?>
                     <input type="submit" onclick="return confirm('Are you sure you want to delete this contact?');" value="delete"/>
                 <?php }?>
               </form>
           </td>
       </tr>
    <?php
    };
    ?>
    <tbody>
    </table>
<?php
};
?>

<?php
include "../../../include/footer.php";
?>


