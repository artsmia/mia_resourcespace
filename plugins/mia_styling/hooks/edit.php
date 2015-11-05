<?php
function HookMia_stylingEditReplacesaveerror(){
global $save_errors;
$display_msg="";
foreach ($save_errors as $save_error_field=>$save_error_message){
    $save_error_message = htmlspecialchars($save_error_message);
    $display_msg .= "$save_error_message\\n";
    $errids[]=$save_error_field;
}
if($display_msg != ""){
    $display_msg .= "\\n Please complete all required fields";
    $errids = json_encode($errids);
        ?>
            <script type="text/javascript">
            var errids = '<?php echo $errids ?>';
            errids = JSON.parse(errids);
            alert('<?php echo $display_msg ?>');
            jQuery.each(errids, function(k,v){
                console.log(v);
                jQuery("#field_"+v).addClass("sv_err");
            });
            </script>
<?php
            }
return true;
}
function HookMia_stylingEditReplacefield($a,$b,$c){
//var_dump($a);
//var_dump($b);
//var_dump($c);
global $name, $value, $help_js, $edit_autosave, $ref, $lang, $field, $auto_order_checkbox, $auto_order_checkbox_case_insensitive;
//include __DIR__."/../../../pages/edit_fields/" . $a . ".php";
//return $custo_fields;
}
?>
