<?php
function HookTms_connectEdit2Autolivejs(){
  //set some server side variables for jovascript
  $artObjectFields = sql_query("select ref from resource_type_field where tms_field != ''");
  $artObject_array=array();
  $jao = json_encode($artObjectFields);
?>

<script type="text/javascript">
jQuery(document).ready(function(){
    var jao = <?php echo($jao)?>;
    jao_ray = new Array();
    for (j=0; j<jao.length; j++){
        jQuery("#field_"+jao[j].ref).prop("readonly","readonly");
        if(jQuery("#field_153").prop("value") == ""){
            jQuery("#field_"+jao[j].ref).parents(".Question").css("display","none");
        }
        jao_ray.push(JSON.parse(jao[j].ref));
    }
    var inputValue='';
    jQuery('#field_153').keyup(function() {
        inputValue = jQuery(this).val();
    }).keyup();
    jQuery('#field_153').focusout(function(){
        var URL = "/pages/ajax/tms.php?objectid="+inputValue;
        ajax(URL, successCB);
    });

    //------------------------
    //    Callback Functions
    //------------------------

    //TMS Callback
    var successCB = function(data){
    if(data.error!=true){
        var matched = new Array();
        //match Values and update form fields
        for(i=0; i<data.length; i++){
            datas=JSON.parse(data[i]);
            if(jQuery.inArray(datas.ref,jao_ray)!=-1){
                 jQuery("#field_"+datas.ref).parents(".Question").show("slow");
                 jQuery("#field_"+datas.ref).val(datas.tmsvalue).effect("highlight");
                 if(jQuery("#field_"+datas.ref).prop('type')=="hidden"){
                   jQuery("#field_"+datas.ref+"_selector").val(datas.tmsvalue).effect("highlight");
                 };
            matched.push(datas.ref);
           };
        };
        //Check values that where not matched and empty those fields
        for(jo=0;jo<jao_ray.length;jo++){
            if(jQuery.inArray(jao_ray[jo],matched)==-1){
               jQuery("#field_"+jao_ray[jo]).val('').effect("highlight");
               jQuery("#field_"+jao_ray[jo]).parents(".Question").hide("slow");
                if(jQuery("#field_"+jao_ray[jo]).prop('type')=="hidden"){
                    jQuery("#field_"+jao_ray[jo]+"_selector").val('');
                    jQuery("#field_"+jao_ray[jo]+"_selected").html('');
                };
            };
        };
     }else if(data.error == true && data.error_type == "404"){
         //if data object was not found
         alert(data.textStatus);
         var toclear=[153,91,92,93,94,95,96,97,98];
         for(i=0; i<toclear.length; i++){
           jQuery("#field_"+toclear[i]).val("").effect("highlight");
         }
     }else if(data.error == true && data.error_type == "FAIL"){
        //if connection failed or timeout
        alert(data.textStatus);
        jQuery("#field_153").parent(".Question").append("<input type='hidden' id='resolve_tms' name='resolve_tms' value='true'/>");
        var toclear=[153,91,92,93,94,95,96,97,98];
         for(i=0; i<toclear.length; i++){
           jQuery("#field_"+toclear[i]).val("").effect("highlight");
         }
     }

  };
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
  }
});
</script>
<?php
}
?>
