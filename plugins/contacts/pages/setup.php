<?php
include "../../../include/db.php";
include "../../../include/authenticate.php"; if (!checkperm("ct")) {exit ("Permission denied.");}
include "../../../include/general.php";
include "../../../include/resource_functions.php";
include __DIR__."/../config/config.php";
include __DIR__."/../include/contact_functions.php";

global $contact;
global $oldvals;
global $oldvalmatch;
global $cfields;
global $contact_fields;

if(!checkperm("ctE") && !isset($_GET['name'])){exit("You do not have permission to edit contacts.");}

if(isset($_GET['edit']) && $_GET['edit']!=""){
    $cref=$_GET['edit'];
    $contact = sql_query("SELECT * FROM contacts WHERE ref = $cref");
    $oldvals = $contact;
    $contact=$contact[0];
}

function matcholdkey($value, $oldvalmatch){
    foreach ($oldvalmatch as $ok => $ov){
        if (in_array($value, $ov)){
            return $ok;
        }
    }
}

$ref=getvalescaped("ref","");
$resource=getvalescaped("resource","");
$errors = array();

//SUBMIT
if(isset($_POST) && !empty($_POST)){

   //---------------------
   //    Error Handleing
   //---------------------
    //if name field was empty
    if(isset($_POST['contact_name']) && $_POST['contact_name']==""){
        $errors[] = 'We need a name for this contact <br/>';
    }
    //check email field for valid email
    if(isset($_POST['contact_email']) && $_POST['contact_email'] !="" && !filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)){
        $errors[] =  'not a valid email <br/>';
    }
    //check valid url
    if(isset($_POST['contact_url']) && $_POST['contact_url'] !="" && !filter_var($_POST['contact_url'], FILTER_VALIDATE_URL)){
        $errors[] = 'This is not a valid URL <br/>';
    }
    //check valid zip
    if(isset($_POST['contact_zipcode']) && trim($_POST['contact_zipcode']) !="" && !preg_match("/^[0-9]{4,9}$/", trim($_POST['contact_zipcode']))){
        $errors[] = 'Zipcode is not valid <br/>';
    }
    //check phone for format
    if(isset($_POST['contact_phone']) && $_POST['contact_phone'] !="" && !preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $_POST['contact_phone'])){
       $errors[]='Not a valid phone number <br/>';
    }
    //ECHO ERRORS
    if(!empty($errors)){
        echo("Erorrs:<br/>");
        for ($e=0; $e<count($errors); $e++){
            echo("<div style='color:#F00;'>".$errors[$e]."</div>");
        };

    }else{//if no errors

        $inserts=array();
        $values=array();
        $name = $_POST['contact_name'];

        //remove empty fields and format for insert
        foreach($_POST as $key => $val){
            if($val != ""){
                $inserts[]=str_replace("contact_", "", $key);
                $values[]="'".$val."'";
            };
            if($key == "contact_parent" && $val==""){
                $inserts[]=str_replace("contact_", "", $key);
                $values[]="NULL";
            }
        };
        //if there are values to insert
        if(!empty($inserts) && !empty($values)){

            //if this is a contact we are editing than we need to update
            if(isset($_GET['edit'])){
                $original_name=$contact['name'];

                $cref = $_GET['edit'];
                $update = array();
                $ru=array();
                foreach($_POST as $key => $val){
                    $update[]=str_replace("contact_","",$key)."="."'".$val."'";
                }
                //update the contacts database
                sql_query("UPDATE contacts SET ".join(",",$update)." WHERE ref = $cref");

                //Check if there are resources that need to be updated
                $resourcestoupdate = sql_query("SELECT DISTINCT resource, resource_type_field FROM resource_data WHERE value = \"$original_name\"");

                //If there are resources to update
                if(!empty($resourcestoupdate)){

                    //determine name fields to update resources by
                    $unique = array_unique(array_map(function ($i) { return $i['resource_type_field']; }, $resourcestoupdate));
                    foreach($unique as $k=>$v){
                    if(array_key_exists($v,$contact_fields)){
                        switch($v){
                            case 80:
                            $rs_fields=$cfields['creator'];
                            break;
                            case 184:
                            $rs_fields=$cfields['license'];
                            break;
                            case 171:
                            $rs_fields=$cfields['commons'];
                            break;
                            case  125:
                            $rs_fields=$cfields['media_cataloguer'];
                            break;
                            case 177:
                            $rs_fields=$cfields['media_source'];
                            break;
                            case 178:
                            $rs_fields=$cfields['rights_owner'];
                            break;
                            case 180:
                            $rs_fields=$cfields['media_supplier'];
                            break;
                        }
                        //call the update function
                        updateResourcesFromContacts($resourcestoupdate,$rs_fields,$v,$oldvals,$oldvalmatch);
                    };
                  }
               };
               //else this is a new contact and we need to insert
            }else{
                //check to see if this contact already exists
                $exists = sql_query("SELECT * FROM contacts WHERE name = '".$name."'");
                if($exists){
                    echo($_POST['contact_name']." already exists as a contact");
                }else{
                    sql_query("INSERT INTO contacts (".join(",",$inserts).") VALUES (".join(",",$values).")");
                    echo('Contact Added');
                    $name = $_POST['contact_name'];
                    if(isset($_GET['p'])){
                      $field = $_GET['p'];
                    }
                    $_POST = array();
                };
            }
            if(isset($_GET['name'])){
                echo "<script>
                window.opener.ctcallback('$field','$name')
                window.close();</script>";
            }else{
            header("Location: $baseurl/plugins/contacts/pages/manage.php");
            }
        }//end if inserts?
        else{
            echo('There is no contact info to save!');
        }
    };//end else no errors
}//end if SUBMIT
include "../../../include/header.php";
?>

<style type="text/css">
    #CentralSpace label{width:48%; display: block; float: left; margin: 1%;}
    #CentralSpace input{width:100%;margin: 10px 0; float: left;}
    #CentralSpace input[type="submit"]{width: 50%; display: block;  margin: auto; float: none !important; background: transparent; color: #298658; border:2px solid; padding: 6px 2px; border-radius: 10px;}
    input[type="submit"]:hover{cursor:pointer; background: #298658!important; color: #FFF !important;}
    #CentralSpace select{display: inline-block; width: 100%; padding: 5px 0}
    #contacts-form {width:50%; margin: auto;}
    #contacts-form fieldset{background: #FFFFFF;}
    #contacts-nav { list-style:none;}
    #contacts-nav li{display: inline-block; margin: 0 5px;}
    fieldset{padding:1%; margin-bottom: 20px;}
    fieldset fieldset{width:96%; margin: 10px 0; background: #FCFCFC}
    fieldset fieldset input{text-align:center}
    fieldset fieldset label{width:20% !important;margin:0 !important; text-align:center;}
    legend{width: 100%;}
    legend input{width:100% !important; text-align:left !important;}
</style>

<ul id = "contacts-nav">
    <li><a href="setup.php">Add New</a></li>
    <li><a href="manage.php">Manage Contacts</a></li>
</ul>


<form id="contacts-form" action="<?php echo $_SERVER['PHP_SELF'];
  if(isset($_GET['name'])){echo '?name='.$_GET['name'];}
  if(isset($_GET['edit'])){echo('?edit='.$_GET['edit']);}
  if(isset($_GET['p'])){echo('&p='.$_GET['p']);}
  ?>"
  method="POST">
<h2>Add a new contact.</h2>
<fieldset>
  <label for="contact_name">Name
  <input placeholder="Enter full name..." type="text" id="c-name" name="contact_name" value="<?php if(isset($_POST['contact_name'])){echo $_POST['contact_name'];}else if(isset($_GET['name'])){echo $_GET['name'];}else if(isset($contact['name'])){echo($contact['name']);};?>"/>
  </label>
  <label for="contact_title">Title
  <input placeholder="Enter contact's title..." type="text" id="c-title" name="contact_title" value="<?php if(isset($_POST['contact_title'])){echo $_POST['contact_title'];}else if(isset($contact['title'])){echo($contact['title']);}?>"/>
  </label>
  <fieldset>
<a href="#" onClick="clearLocation()" style="text-decoration:underline">Clear</a><br/>
    <input type="text" id="location-selector" placeholder="Enter full location and select from popup..."/>
  <label for="contact_address">Address
  <input readonly="readonly" type="text" id="c-address" name="contact_address" value="<?php if(isset($_POST['contact_address'])){echo $_POST['contact_address'];}else if(isset($contact['address'])){echo($contact['address']);} ?>"/>
  </label>
  <label for="contact_city">City
  <input readonly="readonly" type="text" id="c-city" name="contact_city" value="<?php if(isset($_POST['contact_city'])){echo $_POST['contact_city'];}else if(isset($contact['city'])){echo($contact{'city'});} ?>"/>
  </label>
  <label for="contact_state">State
  <input readonly="readonly" type="text" id="c-state" name="contact_state" value="<?php if(isset($_POST['contact_state'])){echo $_POST['contact_state'];}else if(isset($contact['state'])){echo($contact['state']);} ?>"/>
  </label>
  <label for="contact_country">Country
  <input readonly="readonly" type="text" id="c-country" name="contact_country" value="<?php if(isset($_POST['contact_country'])){echo $_POST['contact_country'];}else if(isset($contact['country'])){echo($contact['country']);} ?>"/>
  </label>
  <label for="contact_zipcode">Zip Code
  <input readonly="readonly" type="text" id="c-zipcode" name="contact_zipcode" value="<?php if(isset($_POST['contact_zipcode'])){echo $_POST['contact_zipcode'];}else if(isset($contact['zipcode'])){echo($contact['zipcode']);} ?>"/>
  </label>
</fieldset>
  <label for="contact_phone">Phone
  <input type="text" name="contact_phone" value="<?php if(isset($_POST['contact_phone'])){echo $_POST['contact_phone'];}else if(isset($contact['phone'])){echo($contact['phone']);} ?>">
  </label>
  <label for="contact_email">Email
  <input type="text" name="contact_email" value="<?php if(isset($_POST['contact_email'])){echo $_POST['contact_email'];}else if(isset($contact['email'])){echo($contact['email']);} ?>"/>
  </label>
  <label for="contact_url">URL
  <input type="text" name="contact_url" value="<?php if(isset($_POST['contact_url'])){echo $_POST['contact_url'];}else if(isset($contact['URL'])){echo($contact['URL']);} ?>"  placeholder="http://www.domain.xxx"/>
  </label>
  <label for="contact_constituent_id">Consituent ID
  <input type="text" name="contact_constituent_id" value="<?php if(isset($_POST['contact_constituent_id'])){echo $_POST['contact_constituent_id'];}else if(isset($contact['constituent_id'])){echo $contact['constituent_id'];}?>"/>
  </label>
  <label class="select" for="contact_type">Contact Type<br/>
  <select name="contact_type">
      <?php
      foreach($contact_categories as $key => $val){
      ?>
          <option value="<?php echo($key)?>" <?php if($key == $contact['type']){echo("selected");}?>><?php echo($val)?></option>
      <?php
      }
      ?>
  </select>
</label>
  <?php
  $parents = sql_query("SELECT * FROM contacts WHERE type = 1");
  if(!empty($parents)){
  ?>
  <label class="select" for="contact_parent">Parent<br/>
  <select name="contact_parent">
      <option value="">--</option>
      <?php
      for($p=0; $p<count($parents); $p++){
      ?>
          <option value="<?php echo($parents[$p]['ref'])?>" <?php if($contact['parent'] == $parents[$p]['ref']){echo("selected");};?>><?php echo($parents[$p]['name']);?></option>
      <?php
      }
      ?>
  </select>
</label>
  <?php }?>
</fieldset>
  <input type="submit" value="Save"/>
</form>
<script>
var clearLocation = function(){
    formfields = ["c-address","c-city","c-state","c-country","c-zipcode"];
    for(f=0; f<formfields.length; f++){
        jQuery("#"+formfields[f]).val("").effect("highlight");
    }
}

var placeSearch, autocomplete;
initialize();
var fields ={
  street_number: 'c-address',
  route: 'c-address',
  locality: 'c-city',
  country: 'c-country',
  administrative_area_level_1: 'c-state',
  postal_code: 'c-zipcode'
};
     
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',   
  locality: 'long_name',
  administrative_area_level_1: 'long_name',
  country: 'long_name',
  postal_code: 'short_name'
};
    
function initialize() {
             
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('location-selector')),
      { types: ['geocode'] });
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });
}

// [START region_fillform]
function fillInAddress() {

  var place = autocomplete.getPlace();
  //clear the values
  for (var component in fields) {
    document.getElementById(fields[component]).value = '';
  }
  //populate the values
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(fields[addressType]).value = val;
    }
  }
  for (var component in fields) {
   if(document.getElementById(fields[component]).value != ''){
     document.getElementById('location-selector').value = '';
   };
  }
}
// [END region_fillform]
</script>
<?php
include "../../../include/footer.php";
?>
