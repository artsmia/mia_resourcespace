<?php
function HookGoogle_formsAllEditbeforesave(){

    global $ref;
    if(isset($_POST)){
        $coordstosave = array();
        $thequery=array();
        if(isset($_POST['coordslong'])){
            $coordstosave['geo_lat']=$_POST['coordslong'];
        }
        if(isset($_POST['coordslat'])){
            $coordstosave['geo_long']=$_POST['coordslat'];
        }
        if(!empty($coordstosave)){
            foreach($coordstosave as $coordkey => $coordval){
                $thequery[] = $coordkey."='".$coordval."'";
            }
        $coordquery = sql_query("UPDATE resource SET ".implode(", ",$thequery)." WHERE ref=".$ref);
        }
    }
    return;
}
?>
