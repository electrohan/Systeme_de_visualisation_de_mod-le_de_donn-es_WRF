$(document).ready(function() {
  var currentIndex = -1;
  var selectedParametre = null;
  var totalImages = 0;
  var selectedAltitude = null; 
  var isFilterActive = false;

  function getData(parametre) {
    $.ajax({
      url: "../API/api_rest_image.php?parametre=" + parametre,
      type: "GET",
      dataType: "json",
      success: function(data) {
        //console.log(data); // Affiche les données dans la console pour le débogage
        totalImages = data.length;
        if (totalImages === 0) {
          console.log("Aucune image trouvée.");
          return;
        }

        if (currentIndex === -1 || currentIndex >= totalImages) {
          currentIndex = 0; // Réinitialisez l'index uniquement si nécessaire
        }
        selectedParametre = parametre;

        var filteredData = data;
        if (isFilterActive && selectedAltitude) {
          filteredData = data.filter(function(item) {
            return item.altitude === selectedAltitude;
          });
        }
        var imageUrl = filteredData[currentIndex].chemindonnees; // Récupérez le chemin de l'image à partir du JSON

        // Récupérez uniquement la partie spécifique de l'URL en supprimant le préfixe "/var/www/html/"
        var urlPartieSpecifique = imageUrl.replace("/var/www/html/", "");
        var baseUrl = "http://127.0.0.1/";
        //console.log(urlPartieSpecifique); // Affiche la partie spécifique de l'URL
        var urlComplete = baseUrl + urlPartieSpecifique;
        //console.log(urlComplete);
        
        // Sélectionnez l'élément <img> à l'intérieur de la classe 'cartes' et définissez son attribut 'src' avec la partie spécifique de l'URL
        $(".cartes img").attr("src", urlComplete);

        //console.log(currentIndex);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown); // Affiche une erreur dans la console pour le débogage
      }
    });
  }
 // Appel initial à la fonction getData avec le paramètre "rf"
  getData(" rh");
  // Gestionnaire d'événements pour les liens de paramètres
  $(".parametres").on("click", "#data-list a", function(event) {
    event.preventDefault();
    //console.log("Lien cliqué");
    var parametre = $(this).data("parametre");
    currentIndex = -1;
    selectedAltitude = null; 
    getData(parametre);
  });

  // Gestionnaire d'événements pour le bouton "Avance"
  $(".manip").on("click", ".next", function(event) {
    event.preventDefault();
    //console.log("Bouton Avance cliqué");
    if (selectedParametre === null) {
      console.log("Veuillez sélectionner un paramètre dans la liste avant d'utiliser le bouton Avance.");
      return;
    }
    currentIndex = (currentIndex + 1) % totalImages; // Utilisez l'opérateur modulo pour obtenir un index valide
    getData(selectedParametre);
  });
  // Gestionnaire d'événements pour le bouton "Avance Rapide"
$(".manip").on("click", ".fast-forward", function(event) {
  event.preventDefault();
  //console.log("Bouton Avance Rapide cliqué");
  if (selectedParametre === null) {
    console.log("Veuillez sélectionner un paramètre dans la liste avant d'utiliser le bouton Avance Rapide.");
    return;
  }
  var interval = 200; // Intervalle de temps entre chaque image en millisecondes
  var iterations = 30; // Nombre d'itérations d'avance rapide

  for (var i = 0; i < iterations; i++) {
    setTimeout(function() {
      currentIndex = (currentIndex + 1) % totalImages; // Utilisez l'opérateur modulo pour obtenir un index valide
      getData(selectedParametre);
    }, i * interval);
  }
});
  //Gestionnaire d'evenement pour les bouton altitude
  $(".altitude").on("click", "button", function(event) {
    event.preventDefault();
    selectedAltitude = parseInt($(this).val());
    currentIndex = -1;
    isFilterActive=true;
    //console.log("le bouton pur l'altitude a été appuyé");
    //console.log(selectedAltitude);
    getData(selectedParametre);
  });
});
