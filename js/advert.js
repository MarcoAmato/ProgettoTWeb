const advertLoadingErrorMap = new Map();


$(function(){
    let id = getUrlParameter("id");

    $.post({
        url: "../../php/advert.php",
        datatype: "json",
        data: {
            'id': id,
        },
        success: showAdvert,
        error: function () {
            displayError(advertLoadingErrorMap, "server_unreachable", "Siamo spiacenti, l'annuncio non è disponibile");
        }
    });
});

function showAdvert(jsonAnnuncio){
    let annuncio;
    try {
        annuncio = JSON.parse(jsonAnnuncio);
    } catch (e) {
        console.log("error: " + jsonAnnuncio);
        displayError(advertLoadingErrorMap, jsonAnnuncio, "Siamo spiacenti, l'annuncio non è disponibile");
        return;
    }

    //componi html
}