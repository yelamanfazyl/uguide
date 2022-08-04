<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";

    $univers = R::findAll("univers");

    foreach($univers as $univer){
        $orgDate = $univer->application_due;
    
        $newDate = date("Y-m-d", strtotime($orgDate));
        
        $univer->application_due = $newDate;
        R::store($univer);
    }
?>