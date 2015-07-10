/**
 * Created by wap75 on 08/07/15.
 */



$(document).ready(function(){

    /* Confirmation de suppression */

    /*$(".btn-danger").click(function(event) {
        var stop = confirm("Etes-vous certain de le supprimer ?");

        if (stop == false) {
            event.preventDefault();
        }
    })*/

    $(".table").on("click", ".btn-danger", function(event) {
        var stop = confirm("Etes-vous certain de le supprimer ?");
        if (stop == false) {
            event.preventDefault();
        }
    });




});
