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

    let htmlAnnuncio = 
    "<h1>"+annuncio.titolo+"</h1>";
    htmlAnnuncio += "<div id='img'>";
        htmlAnnuncio += "<img src='../../img/advert-img/'"+ annuncio.path_immagine +" alt='"+annuncio.titolo+"'>";
        htmlAnnuncio += "<p>"+annuncio.testo+"</p>";
        htmlAnnuncio += "<a href='mailto:"+annuncio.email_utente+"'>"+annuncio.email_utente+"</a>";
    htmlAnnuncio += "</div>";

    $("#main").append(htmlAnnuncio);
    //componi html
}