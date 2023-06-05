<?php 
    
    
    require_once("../API/api_tools.php");
    try{
        if(isset($_GET['categorie']) && $_GET['categorie'] === 'Pression'){
            getPressionParametre();
        }else if(isset($_GET['categorie']) && $_GET['categorie'] === 'Surface'){
            getSurfaceParametre();
        }
         else {
            throw new Exception("Veuillez sélectionner une catégorie.");
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
    
    
?>