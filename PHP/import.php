<?php
//Connexion au serveur distant
$hostServer = '192.168.10.82';
$portServer = 22;
$usernameServer = 'nwp';
$passwordServer = 'nwp';

// Connexion SSH
$connexionServer = ssh2_connect($hostServer, $portServer);
if (!$connexionServer ) {
    die('Impossible de se connecter au serveur.');
}
// Authentification
if (!ssh2_auth_password($connexionServer , $usernameServer, $passwordServer)) {
    die('Impossible de s\'authentifier sur le serveur.');
}
$sftp = ssh2_sftp($connexionServer );

// Chemin du dossier WRF_OPERATIONAL
$remoteDir = '/home/nwp/WRF_OPERATIONAL/RUN_IMAGES/GFS';
$today = date('Ymd'); // Obtenez la date du jour en format '20230702'
$yesterday = date('Ymd', strtotime('-1 day', strtotime($today))); // Obtenez la date du jour précédent
$yesterdayFormatted = str_replace('-', '', $yesterday);
$folderName = $yesterdayFormatted;

$handle = opendir("ssh2.sftp://$sftp$remoteDir");
if (!$handle) {
    die('Impossible d\'ouvrir le dossier WRF_OPERATIONAL.');
}
while (($entry = readdir($handle)) !== false){
    if ($entry === '.' || $entry === '..') {
        continue; // Ignorer les entrées '.' et '..'
    }
    if ($entry === $folderName){
        // Obtenir les informations sur le dossier distant
        $stat = ssh2_sftp_stat($sftp, "$remoteDir/$entry");
        if ($stat['mode'] & 040000){
            $repertoireParent="ssh2.sftp://$sftp$remoteDir/$entry";
            $innerHandle = opendir("ssh2.sftp://$sftp$remoteDir/$entry");
            if (!$innerHandle) {
                die("Impossible d'ouvrir le dossier $entry.");
            }
        }
    }
}

//récupération des informations de connexion à la base de données
$host="localhost";
$port="5432";
$dbname="database_WRF";
$user="postgres";
$password="root";

$connexion="pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
$options=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
//essai de connexion à la base de données avec PDO

try{
    echo "on est dans le try"."<br>";
    $pdo=new PDO($connexion,$user,$password,$options);
    //$repertoireParent = "/var/www/html/Systeme_de_visualisation_de_modèle_de_données_WRF/DATASET/20230602";
    $datas=scandir($repertoireParent);
    
    foreach($datas as $repertoire){
        
        if ($repertoire != "." && $repertoire != ".." && is_dir($repertoireParent . '/' . $repertoire)){
            $cheminRepertoire = $repertoireParent . '/' . $repertoire;
            $fichiers = scandir($cheminRepertoire);
            foreach ($fichiers as $fichier) {
                if(substr($fichier, 0, 1)!="."){
                    $infos_fichier = pathinfo($fichier);
                    $chemin_fichier = $repertoireParent ."/" .$repertoire . "/" . $fichier;
                    $taille_donnees=filesize($chemin_fichier);
                    $nom_fichier = $infos_fichier['filename'];
                    $parties = explode("_", $nom_fichier);
                    $length=count($parties);
                    
                    
                    switch($length){
                        case 6:
                            $DateHeureDebutRound = $parties[3];
                            $parametre = $parties[0].$parties[1];
                            $DateHeureFinRound = $parties[5];
                            $alts="0";
                            break;
                        case 7:
                            $DateHeureDebutRound = $parties[4];
                            $parametre = $parties[0].$parties[1];
                            $alts=$parties[2];
                            $DateHeureFinRound = $parties[6];
                            //echo $DateHeureDebutRound  ." ". $DateHeureFinRound."<br>";
                            //echo $nom_fichier ."<br>";
                            //echo $parametre ."<br>";
                            break;
                        case 9:
                            $DateHeureDebutRound = $parties[6];
                            $parametre = $parties[0].$parties[1].$parties[2].$parties[3].$parties[4];
                            $DateHeureFinRound = $parties[8];
                            $alts="0";
                            break;
                        default:
                            echo"le fichiers n'est pas compris" . "<br>";
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
    
                    // algorithme de différenciation de différents paramètres de pression et de température 
                    
                    $caracteres=str_split($alts);
                    $altiude=" ";// l'altitude séparaé du nom du paramètre 
                    foreach($caracteres as $caratere){
                        if(ctype_digit($caratere)){
                            $altiude.=$caratere;
                        }
                    }

                    $alt=intval($altiude);
                    $categorie=" ";
                    
                        if($alt == 0){
                            $categorie="Surface";
                        }else{
                            $categorie="Pression";
                        }
                    
                    
                     // insertion des données dans la table DONNEES 
                    $requete_insertion1="INSERT INTO DONNEES (IntituleDonnees,TailleDonnees,chemindonnees) VALUES ('$nom_fichier','$taille_donnees','$chemin_fichier')";
                    $insertion1=$pdo->prepare($requete_insertion1);     
                    $insertion1->execute();
                    
                    $donnees_id = $pdo->lastInsertId();
    
                    
                    // mise en relation des données de la table DONNEES avec la table ROUND
    
                    $requete_insertion2="INSERT INTO ROUND (DateDebutRound, DateFinRound, donnees_id) VALUES ($date_heure_debut_timestamp, $date_heure_fin_timestamp, :donnees_id)";
                    $insertion2= $pdo->prepare($requete_insertion2);
                    $insertion2->bindParam(':donnees_id', $donnees_id);
                    $insertion2->execute();
    
    
                    //insertion des données dans la table CATEGORIE
                    $requete_insertion3="INSERT INTO CATEGORIE (type_parametre,donnees_id) VALUES (:type_parametre, :donnees_id)";
                    $insertion3=$pdo->prepare($requete_insertion3);
                    $insertion3->bindParam(':donnees_id', $donnees_id);
                    $insertion3->bindParam(':type_parametre', $categorie);
                    $insertion3->execute();
                    $categorie_id=$pdo->lastInsertId();
    
                    //insertion des informations obtenu dans la table parametre
    
                    $requete_insertion4="INSERT INTO PARAMETRES (nom_parametre,categorie_id,altitude) VALUES (:nom_parametre, :categorie_id,:altitude)";
                    $insertion4=$pdo->prepare($requete_insertion4);
                    $insertion4->bindParam(':categorie_id',$categorie_id);
                    $insertion4->bindParam(':nom_parametre',$parametre);
                    $insertion4->bindParam(':altitude',$alt);
                    $insertion4->execute();
    
                    echo $nom_fichier." ".$alt." ".$categorie."<br>";
                }
            }
            
        }
        
        
    }
}catch(PDOException $e){
    echo "erreur de connexion à la base de données" . $e->getMessage();
}