<?php
    function HookMia_stylingViewBeforeresourcetoolsheader(){
        global $fields;
        $flagged = false;
        for($f=0; $f<count($fields); $f++){
            if($fields[$f]['ref']=="195" && $fields[$f]['value']!="" && $fields[$f]['value']!=","){
               echo("<span class='flg-msg'>".ltrim($fields[$f]['value'],",")."</span>");
               $flagged = true;
            }
        }
        if($flagged == true){?>
             <style>
                .RecordDownload{
                   border-color: #F00 !important;
                }
             </style>
        <?php
        }
    }
?>
