<?php 
    
    
    require_once("../API/api_tools.php");
    try{
        if(isset($_GET['parametre'])){
            getImage($_GET['parametre']);
        } else {
            throw new Exception("il n'y a pas de parametre selectionné.");
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
    
?>