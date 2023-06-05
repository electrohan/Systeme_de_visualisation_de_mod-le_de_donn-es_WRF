<?php
$datas = json_decode(file_get_contents('http://localhost/Systeme_de_visualisation_de_modèle_de_données_WRF/API/api_rest.php')) ;

//$content=["para1","para2","para3","para4","para5","para6","para7"];
require_once("../Sodexam/index.php");
?>