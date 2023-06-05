<?php 
    function getConnexion() { 
        $host="localhost";
        $port="5432";
        $dbname="database_WRF";
        $user="postgres";
        $password="root";
        
        $connexion="pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $options=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        $pdo=new PDO($connexion,$user,$password,$options);
        return $pdo;
    }
    function sendJSON($infos){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/Json");
        echo json_encode($infos,JSON_UNESCAPED_UNICODE);
    }
    
    function getPressionParametre(){
        $conn=getConnexion();

        $req="SELECT PARAMETRES.nom_parametre  FROM CATEGORIE INNER JOIN PARAMETRES 
        ON CATEGORIE.categorie_id = PARAMETRES.parametre_id
        INNER JOIN DONNEES ON DONNEES.donnees_id = CATEGORIE.donnees_id 
        WHERE CATEGORIE.type_parametre='Pression'";
        $stmt=$conn->prepare($req);
        //$stmt->bindParam(":type_parametre", $type , PDO::PARAM_STR);
        $stmt->execute();
        $paras=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $tableau_parmetres_vues=array();
        for($i=0; $i< count($paras) ; $i++){
            if(array_key_exists($paras[$i]["nom_parametre"],$tableau_parmetres_vues)){
                $tableau_parmetres_vues[$paras[$i]["nom_parametre"]]++;
            }else{
                $tableau_parmetres_vues[$paras[$i]["nom_parametre"]]=1;
            }
        }
        sendJSON($tableau_parmetres_vues);
    }

    function getSurfaceParametre(){
        $conn=getConnexion();

        $req="SELECT PARAMETRES.nom_parametre FROM CATEGORIE INNER JOIN PARAMETRES 
        ON CATEGORIE.categorie_id = PARAMETRES.parametre_id
        INNER JOIN DONNEES ON DONNEES.donnees_id = CATEGORIE.donnees_id 
        WHERE CATEGORIE.type_parametre='Surface'";
        $stmt=$conn->prepare($req);
        $stmt->execute();
        $paras=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $tableau_parmetres_vues=array();
        for($i=0; $i< count($paras) ; $i++){
            if(array_key_exists($paras[$i]["nom_parametre"],$tableau_parmetres_vues)){
                $tableau_parmetres_vues[$paras[$i]["nom_parametre"]]++;
            }else{
                $tableau_parmetres_vues[$paras[$i]["nom_parametre"]]=1;
            }
        }
        sendJSON($tableau_parmetres_vues);
    }
    function getRound(){}
    function getImage($parametre){
        $conn=getConnexion();
        $req="SELECT DONNEES.chemindonnees, PARAMETRES.altitude FROM CATEGORIE INNER JOIN PARAMETRES 
        ON CATEGORIE.categorie_id = PARAMETRES.parametre_id
        INNER JOIN DONNEES ON DONNEES.donnees_id = CATEGORIE.donnees_id 
        WHERE PARAMETRES.nom_parametre=:parametre";

        $stmt=$conn->prepare($req);
        $stmt->bindParam(':parametre', $parametre, PDO::PARAM_STR);
        $stmt->execute();
        $paras=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        sendJSON($paras);
    }
?>