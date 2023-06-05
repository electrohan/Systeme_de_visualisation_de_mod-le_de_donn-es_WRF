import { jsPDF } from "jspdf";
$(document).ready(function(){
    function generatePDF(){
        const doc = new jsPDF();

        doc.text("Hello world!", 10, 10);
        doc.save("a4.pdf");
    }
    $(".generateur").on("click", function(event) {
        event.preventDefault(); // empêche la page de se recharger
        console.log("bouton cliqué");
        generatePDF();
      });
});