$(document).ready(function() {

    // Fonction qui récupère les données depuis l'API REST
    function getData(categorie) {
      $.ajax({
        url: "../API/api_rest.php?categorie=" + categorie,
        type: "GET",
        dataType: "json",
        success: function(data) {
          //console.log(data); // affiche les données dans la console pour le débogage
          var dataList = $("#data-list");
          dataList.empty(); // vide la liste des données
          for (var key in data) {
            //dataList.append("<li><button data-parametre='" + key + "'>" + key + "</button></li>");
            dataList.append("<li><a href='#' data-parametre='" + key + "'>" + key + "</a></li>");
            // ajoute chaque clé dans la liste
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown); // affiche une erreur dans la console pour le débogage
        }
      });
    }
    
    // Gestionnaire d'événements pour les boutons de catégorie
    $("#categories a").on("click", function(event) {
      event.preventDefault(); // empêche la page de se recharger
      var categorie = $(this).data("categorie");
      getData(categorie);
      getData(" rh");
    });
    $("#categories a[data-categorie='Pression']").click();
    });