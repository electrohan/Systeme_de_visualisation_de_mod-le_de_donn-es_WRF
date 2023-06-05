<?php
// récupération des informations de connexion à la base de données

$host="localhost";
$port="5432";
$dbname="database_WRF";
$user="postgres";
$password="root";

$connexion="pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
$options=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
//essai de connexion à la base de données avec PDO

try{
    $pdo=new PDO($connexion,$user,$password,$options);
    $repertoire = "/var/www/html/Systeme_de_visualisation_de_modèle_de_données_WRF/DATASET/2019051500_OP1";

    // Liste des fichiers du répertoire

    $fichiers = scandir($repertoire);

    // Affichage de la liste des fichiers

    foreach ($fichiers as $fichier) {

        // Exclusion des fichiers et répertoires cachés (qui commencent par un point)

        if (substr($fichier, 0, 1) != ".") {

            //Décomposition des éléments de chaque fichiers pour en sortir les métas données

            $infos_fichier = pathinfo($fichier);
            $chemin_fichier = $repertoire . "/" . $fichier;
            $taille_donnees=filesize($chemin_fichier);
            $nom_fichier = $infos_fichier['filename'];
            $parties = explode("_", $nom_fichier);
            if ($parties[2] === "to") {
                $DateHeureDebutRound = $parties[0];
                $parametre = $parties[1];
                $DateHeureFinRound = $parties[3];
            } else {
                $DateHeureDebutRound = $parties[0];
                $parametre = $parties[1];
                $DateHeureFinRound = $parties[2];
            }
            // mise en forme correct des elements récupérer    
            $date_debut = date('Y-m-d', strtotime(substr($DateHeureDebutRound, 0, 8)));
            $heure_debut = date('H:i', strtotime(substr($DateHeureDebutRound, 8, 2) . ':00:00'));
            $date_fin = date('Y-m-d', strtotime(substr($DateHeureFinRound, 0, 8)));
            $heure_fin = date('H:i', strtotime(substr($DateHeureFinRound, 8, 2) . ':00:00'));

            $date_heure_debut = $date_debut . ' ' . $heure_debut . ':00';
            $date_heure_fin = $date_fin . ' ' . $heure_fin . ':00';
            $date_heure_debut_timestamp = "to_timestamp('$date_heure_debut', 'YYYY-MM-DD HH24:MI:SS')";
            $date_heure_fin_timestamp = "to_timestamp('$date_heure_fin', 'YYYY-MM-DD HH24:MI:SS')";

            // insertion des données dans la table DONNEES 
            $requete_insertion1="INSERT INTO DONNEES (IntituleDonnees,TailleDonnees,chemindonnees) VALUES ('$nom_fichier','$taille_donnees','$chemin_fichier')";
            $insertion1=$pdo->prepare($requete_insertion1);     
            $insertion1->execute();
            $donnees_id = $pdo->lastInsertId();

            //Récuperation des métadonné a l'aide de session
            
            // mise en relation des données de la table DONNEES avec la table ROUND

            $requete_insertion2="INSERT INTO ROUND (DateDebutRound, DateFinRound, donnees_id) VALUES ($date_heure_debut_timestamp, $date_heure_fin_timestamp, :donnees_id)";
            $insertion2= $pdo->prepare($requete_insertion2);
            $insertion2->bindParam(':donnees_id', $donnees_id);
            $insertion2->execute();

            // algorithme de différenciation de différents paramètres de pression et de température 

            $caracteres=str_split($parametre);
            $categorie=" ";
            for($i=1; $i < count($caracteres); $i++){
                if(ctype_digit($caracteres[$i])){
                    $categorie="Pression";
                    break;
                }else{
                    $categorie="Surface";
                }
            };
            //insertion des données dans la table CATEGORIE
            $requete_insertion3="INSERT INTO CATEGORIE (type_parametre,donnees_id) VALUES (:type_parametre, :donnees_id)";
            $insertion3=$pdo->prepare($requete_insertion3);
            $insertion3->bindParam(':donnees_id', $donnees_id);
            $insertion3->bindParam(':type_parametre', $categorie);
            $insertion3->execute();
            $categorie_id=$pdo->lastInsertId();

            $new_name=" ";//le nom du parametre séparé de l'atitude
            $altiude=" ";// l'altitude séparaé du nom du paramètre 

            foreach($caracteres as $caratere){
                if(ctype_digit($caratere)){
                    $altiude.=$caratere;
                }elseif(is_string($caratere)){
                    $new_name.=$caratere;
                }
            }
                $alt=intval($altiude);
                //insertion des informations obtenu dans la table parametre

                $requete_insertion4="INSERT INTO PARAMETRES (nom_parametre,categorie_id,altitude) VALUES (:nom_parametre, :categorie_id,:altitude)";
                $insertion4=$pdo->prepare($requete_insertion4);
                $insertion4->bindParam(':categorie_id',$categorie_id);
                $insertion4->bindParam(':nom_parametre',$new_name);
                $insertion4->bindParam(':altitude',$alt);
                $insertion4->execute();

        }
    }
}catch(PDOException $e){
    echo "erreur de connexion à la base de données" . $e->getMessage();
}
?>