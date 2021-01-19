const errorsMap = new Map();
errorsMap.set("server_down", "Il server non è raggiungibile, riprovare più tardi");
errorsMap.set("fileUploadError", "Errore nel caricamento dell'immagine");
errorsMap.set("fileExtentionNotAllowed", "L' immagine non è in un formato corretto. I formati permessi sono: jpg, jpeg, png, gif");
errorsMap.set("query_failed", "Non è possibile inserire l'annuncio, riprovare più tardi");

$(function(){
    if(error = getURLParameter("error")){
        console.log(error);
        let errorElement = $('#new-advert-error');
        let defaultErrorText = "Si è verificato un errore, riprova più tardi";
        fillError(errorElement, errorsMap, error, defaultErrorText);
    }else if(success = getURLParameter("success") === "true"){
        $('p.success').text("Annuncio inserito con successo");
    }
});