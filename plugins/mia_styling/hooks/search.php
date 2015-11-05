<?php
function HookMia_stylingSearchMoresearchcriteria(){
    global $search;
//    if(isset($_POST['contrib'])){$search=$_POST['contrib'].",".$search;}
}
function HookMia_stylingSearchSearchaftersearchcookie(){
    global $search;
    if(isset($_POST['contrib']) && $_POST['contrib']!=""){
        $search=$_POST['contrib'].",".$search;
        setcookie("contrib",substr($_POST['contrib'],14),0,'/', '', false, false);
    }/*else{
      setcookie("contrib",'',1000,'', '', false, true);
      unset($_COOKIE['contrib']);
    }*/
}
?>
