$(document).ready(function() {
    function generatePDF() {


      const doc = new jsPDF();
      var pageHeight = doc.internal.pageSize.height;
      var pageWidth = doc.internal.pageSize.width;

      // Récupérer le nom depuis la variable de session PHP
      var nom =previValue;
      // Ajouter le nom centré en haut
      var textWidth = doc.getTextWidth(nom);
      var textX = (pageWidth - textWidth) / 2;
      doc.text(nom, textX, 20);


      // Ajouter la date du jour en haut à droite
      var date = new Date().toLocaleDateString();
      var dateWidth = doc.getTextWidth(date);
      var dateX = pageWidth - dateWidth - 10;
      doc.text(date, dateX, 20, {
        align: "right"
      });

      var valeurTextarea = $("#analyse").val();
      doc.setFont("Helvetica", "normal");
      doc.setFontSize(12);
      var textDimensions = doc.getTextDimensions(valeurTextarea);
      var textHeight = textDimensions.h;

      var textY = (pageHeight - textHeight) / 2;

      var lignes = doc.splitTextToSize(valeurTextarea, 180);
      var lineHeight = doc.getLineHeight();
      var texteY = (lignes.length * lineHeight) / 2;

      doc.text(lignes, 10, 50, {
        align: "center"
      });
      //ajout du logo au document 
      var canvas = document.querySelector(".logo");
      var map = document.querySelector("#map");
      html2canvas(canvas).then((e) => {
        var DataImage = e.toDataURL("image/png");
        doc.addImage(DataImage, "PNG", 10, 15);
        
        html2canvas(map,{allowTaint: true,useCORS: true,proxy:"http://127.0.0.1/"}).then((em) => {
          var mapData = em.toDataURL("image/png");
          imageWidth=e.width;
          var imageX=(pageWidth - imageWidth) / 2;
          doc.addImage(mapData, "PNG", imageX, 80);
          doc.save("analyse.pdf");
        });
      });
    }
    $(".generateur").on("click", function(event) {
      event.preventDefault(); // empêche la page de se recharger
      console.log("bouton cliqué");
      //Appel de la fonction 
      generatePDF();
    });
  });