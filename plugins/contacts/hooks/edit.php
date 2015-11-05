<?php
include __DIR__."/../config/config.php";
include __DIR__."/../include/contact_functions.php";
function HookContactsEditAutolivejs(){
  global $contact_fields;
?>
<style type="text/css">
  .Question{position:relative;}
  #box{background: rgba(255,255,255,0.8);border: 1px solid;padding: 0; width: 200px;position:absolute;z-index:10000;top:0;right:350px;max-height: 300px;overflow: auto;}
  #box li{padding:0;}
  #box li a{padding: 10px; display: block;}
  #box a:hover{cursor:pointer;}
  #box li{list-style:none;}
</style>

<script type="text/javascript">
//callback function to repopulate for fields after save
  var ctcallback = function(field,name){
    triggered = true;
    jQuery("#"+field).prop("value",name).trigger("focusout");
  }

jQuery(document).ready(function(){
  //disable return key from submitting form
  jQuery(document).keypress(function(e){
    var code = e.keyCode || e.which;
    if (code  == 13) {
      e.preventDefault();
      return false;
    }
  });
  var tohide = <?php echo json_encode($contact_fields); ?>;
  function showhideFields(){
      for (var k in tohide) {
         for(i=0; i<tohide[k].length; i++){
             if(jQuery('#field_'+k).val()==""){
                 jQuery("#field_"+tohide[k][i]).parents(".Question").hide("slow");
             };
         }
      }
  }
  var theid;
  var fieldstofocus=[];
  jQuery.each(tohide,function(k,v){
      fieldstofocus.push("#field_"+k);
  })
  var contactinputValue='';
  for(f=0; f<fieldstofocus.length; f++){
    jQuery(fieldstofocus[f]).addClass('contacts').prop("autocomplete","off");
  }
  //Make sure box is removed if already exists and append a new one to the current input
  jQuery('.contacts').focus(function(){
    contacts = ajax("ajax/mia_ajax.php?getcontacts=true",getcontactsCB);
    theid = jQuery(this).prop("id");
    jQuery('#box').remove();
    setTimeout(function(){
      jQuery("#"+theid).after("<div id='box' style='display:none'></div>");
    },100);
  });

  //Populate names as user types
  jQuery('.contacts').keyup(function(){
      theid = jQuery(this).prop("id");
      jQuery("#box").html("").show();
      var search_term  = jQuery(this).val();
      var search = new RegExp(search_term , "i");
      var arr = jQuery.grep(contacts, function (value){
        return search.test(value);
      });
      jQuery.each(arr , function(index, value){
        jQuery("#box").append('<li><a class="option" id="'+value+'">'+value+'</a></li>');
      });
      //if input is too far off from contacts in the array then ask if they would like to add this contact
      if(arr == ""){
        <?php if(checkperm("ct")){ ?>
          jQuery('#box').append('<a class="option-new" target="_BLANK">[+] Add <b>"'+search_term+'"</b> to contacts.</a>');
        <?php }else{?>
          jQuery('#box').append('<b>"'+search_term+'"</b> is not in our contacts.');
        <?php  }?>
      }
      jQuery(".option-new").click(function(){
        triggered = false;
        window.open("../plugins/contacts/pages/setup.php?name="+search_term+"&p="+theid , "_blank");
      });

      //if user clicks on a name update the input value
      jQuery('.option').click(function(){
        triggered = false;
        jQuery('#'+theid).prop('value',jQuery(this).prop('id'));
        jQuery("#"+theid).trigger("focusout");
        jQuery('#box').remove();
      });
      jQuery("#box").hover(function(){
        triggered = true;
        jQuery(document).bind("keypress.key9", function(e){
          var code = e.keyCode || e.which;
          if (code  == 9) {e.preventDefault();return false;}
        });
      },
      function(){
        triggered = false;
        jQuery(document).unbind('keypress.key9');
      });
  });//end keyup function

  var triggered =false;
  //when input loses focus make the ajax call
  jQuery('.contacts').focusout(function(){
     if(triggered == false){
       contactinputValue = jQuery("#"+theid).val();
       var referer = theid;
       if(contactinputValue!=""){
         ajax("ajax/mia_ajax.php?contacts="+contactinputValue+"&referer="+referer,contactsCB);
       }else{
         showhideFields();
       };
       jQuery('#box').remove();
     }
  });
  //------------------------
  //    Callback Functions
  //------------------------

  //get contacts callback
  var getcontactsCB = function (data){
    if(data['success']==true){
      contacts = data.results;
    }
  }

  //match contacts callback
  var contactsCB = function(datas){
    var theparent=jQuery('#'+theid).parent(".Question").prop("id");
    jQuery("#"+theparent+" .no-access").remove();
    if (datas['success'] == true){
      jQuery.each(datas.contacts, function(key, val) {
        if(jQuery('#'+key).length == 0 && val != ""){
          jQuery('#'+theparent).append("<div class='no-access'><label for='"+key+"'></label><input name='"+key+"' type='text' value='"+val+"'  readonly='readonly'/><br/></div>");
        }
        if(val == ""){
          jQuery('#'+key).parents(".Question").hide("slow");
        }else{
          jQuery('#'+key).parents(".Question").show("slow");
          if(jQuery('#'+key).prop("type")!="hidden"){
            jQuery('#'+key).val(val).effect("highlight");
          }else{
            jQuery('#'+key).val(val);
            jQuery('#'+key+'_selector').prop("value",val).effect("hightlight");
          }
        }
      })
    }else if(datas['error']==true){
      jQuery.each(datas.fields, function(k,v){
        jQuery('#'+k).prop("value","").effect('highlight');
        if(jQuery('#'+k).prop("type")=="hidden"){
          jQuery('#'+k+'_selector').prop("value","");
        }
      });
      showhideFields();
    }
  }//end callback function

  var ajax = function(URL, Success){
    jQuery.ajax({
      url: URL,
      type:"GET",
      data:"data",
      dataType:"JSON",
      processData:false,
      cache:false,
      success: Success,
      error: function(){
        console.log(error);
      },
    });
  }//end ajax

  for (var k in tohide) {
    for(i=0; i<tohide[k].length; i++){
      jQuery('#field_'+tohide[k][i]).addClass('media-readonly');
      jQuery("#field_"+tohide[k][i]).prop("readonly","readonly");
      if(jQuery('#field_'+k).val()==""){
        jQuery("#field_"+tohide[k][i]).parents(".Question").css("display","none");
      };
    }
  }
});
</script>
<?php
}
?>
