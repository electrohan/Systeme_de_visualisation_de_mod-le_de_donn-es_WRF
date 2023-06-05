<?php
require('../PHP/outils.php');
init_php_session();
// récupération des informations de connexion à la base de données
$host="localhost";
$port="5432";
$dbname="Utilisateurs";
$user="postgres";
$password="root";

$connexion="pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
$options=array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION);

try
{
    $pdo=new PDO($connexion,$user,$password,$options);
    if(isset($_POST['valider'])){
        if($_POST['Matricule'] && !empty($_POST['code'])){
            $Mat_previ=$_POST['Matricule'];
            $code_previ=$_POST['code'];
            $requete_selection = "SELECT * FROM PREVISIONNISTES WHERE matricule_previ = '$Mat_previ'";
            $selection=$pdo->prepare($requete_selection);
            $selection->execute();
            $previ_data=$selection->fetch(PDO::FETCH_ASSOC);
            if(!empty($previ_data['matricule_previ'])){
                if($previ_data['code_previ']==$code_previ){
                    //creation de la variable
                    $_SESSION['previ']=$previ_data['matricule_previ'];
                    //redirection
                    header('Location: ../Sodexam/index.php');
                }else{
                    echo "toujours pas";
                }
            }else{
                echo "echec";
            }
        }else{
            echo "non";
        }
    }else
    {
        echo " je suis pas rentrer dans la boucle";
    }
} catch(PDOException $e){
    echo "erreur de connexion à la base de données" . $e->getMessage();
}