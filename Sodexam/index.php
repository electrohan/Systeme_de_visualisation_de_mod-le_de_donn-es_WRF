<?php 
require('../PHP/outils.php');
init_php_session();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="../CSS/home.css" />

</head>

<body>
<?php if(is_logged()):?>
  <div class="my_page">
    <header>
      <div class="logo-container">
        <img class="logo" src="../images/images-removebg-preview.png" alt="logo de la SODEXAM" />
      </div>
      <div class="previsionniste">
        <h3><?php echo $_SESSION['previ'];?></h3>
      </div>
    </header>
    <div class="Ecran">
      <div class="barre_de_navigation">
        <hr />
        <div class="text">
          <h3>Centre de prévision météorologique</h3>
        </div>
          <ul class="menu">
            <li class="deroulant">
              <a href="#">Categorie</a>
              <ul class="sous" id="categories">
                <li><a href="#" data-categorie="Pression">Pression</a></li>
                <li><a href="#" data-categorie="Surface">Surface</a></li>
              </ul>
            </li>
          </ul>
        <div class="altitude view">
          <div class="colone1">
            <button value="200">200mb</button>
            <button value="500">500mb</button>
            <button value="600">600mb</button>
          </div>
          <div class="colone2">
            <button value="700">700mb</button>
            <button value="850">850mb</button>
            <button value="925">925mb</button>
          </div>
        </div>
        <form class="parametres">
        <ul id="data-list">
          
        </ul>
        </form>
      </div>
      <div class="affichage">
        <div class="cartes">
            <img src="" width="600px" height="500px" alt="">
            <div class="analyse">
              <select name="" id="">
                <option value="">les 72 dernières heures</option>
                <option value="">les 48 dernieres heures</option>
                <option value="">les 24 dernieres heures</option>
              </select>
              <div class="run esp">
                <button>Cycle 00</button>
                <button>Cycle 12</button>
              </div>
              <div class="observation esp">
                <textarea name="" id="" cols="30" rows="10"></textarea>
              </div>
              <div class="pdf esp">
                <button>télécharger</button>
              </div>
            </div>
        </div>
        <div class="timer">
          <div class="temps">
            <button>+3</button>
            <button>+6</button>
            <button>+9</button>
            <button>+12</button>
            <button>+15</button>
            <button>+18</button>
            <button>+21</button>
            <button>+24</button>
          </div>
          <div class="manip">
            <button>retour rapide</button>
            <button>retour</button>
            <button>stop</button>
            <button class="next">avance</button>
            <button class="fast-forward">avance rapide</button>
          </div>
        </div>
      </div>
    </div>
    <footer>
      <div class="info-sodexam">
        <h2>Qui sommes nous?</h2>
        <li><a href="https://www.sodexam.com/?page_id=2509">Mot du DG</a></li>
        <li><a href="https://www.sodexam.com/?page_id=63">Présentation générale de la SODEXAM</a></li>
        <li><a href="https://www.sodexam.com/?cat=9">Directions</a></li>
        <li><a href="https://www.sodexam.com/?p=412">Conseil</a></li>
        <li><a href="https://personnel.sodexam.com/">Espace privé</a></li>
      </div>
      <div class="partenaire-sodexam">
          <h2>Nos partenaires</h2>
          <li><a href="https://www.anac.ci/anac/web/">ANAC</a></li>
          <li><a href="https://www.asecna.aero/index.php/fr/">ASECNA</a></li>
          <li><a href="https://www.abidjan-aeroport.com/">AERIA</a></li>
          <li><a href="http://www.aircotedivoire.com/">Air Côte d’Ivoire</a></li>
      </div>
      <div class="vie-associative">
          <h2>Vie associative</h2>
          <li><a href="">SYNADEXAM</a></li>
          <li><a href="">MUDEXAM</a></li>
          <li><a href="">AFEXAM</a></li>
          <li><a href="">Vie interne</a></li>
      </div>
      <div class="sodexam-solution">
          <h2>Nos solutions</h2>
          <li><a href="">Visualisation WRF</a></li>
          <li><a href="http://37.187.119.198:3838/Babi_pluie/">Babipluie</a></li>
      </div>
    </footer>
  </div>

  <script src="../JAVASCRIPT/GetDataImage.js"></script>
  <script src="../JAVASCRIPT/GetDataCategorie.js"></script>
  <script src="../JAVASCRIPT/app.js"></script>
  <?php else:?>
        <?php header('Location: ../Sodexam/login.php');?>
  <?php endif?>
</body>

</html>