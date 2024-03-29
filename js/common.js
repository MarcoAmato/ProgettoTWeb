$(function(){
    let selectPiattaforme = $("select#piattaforma");
    if(selectPiattaforme.length){ //We have a piattaforme select to fill
        $.post({
            url: "../../php/get-piattaforme.php",
            datatype: "json",
            success: function(data){
                fillSelectPiattaforme(selectPiattaforme, data);
            }
        });
    }
});

function getURLParameter(sParam)
{
    let sPageURL = window.location.search.substring(1); //window.location.search returns url parameters including the first '?', so substring ignores it
    let sURLVariables = sPageURL.split('&');
    for (let i = 0; i < sURLVariables.length; i++) 
    {
        let sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}

function fillSelectPiattaforme(selectPiattaforme, piattaformeJson){
    let piattaformeArray = null;
    try{
        piattaformeArray = JSON.parse(piattaformeJson);
    }catch(e){
        console.log("errore caricamento piattaforme: "+piattaformeJson);
        return;
    }
    
    for(piattaforma of piattaformeArray){
        let nome = (piattaforma.nome).replace(/_/g, " ");
        let piattaformaHTML = '<option value="'+ piattaforma.nome +'">'+ nome +'</option>';
        selectPiattaforme.append(piattaformaHTML);
    }
}

function fillError(errorElement, errorsNameTextMap, error, defaultErrorText){
    /**
     * Ricorda di commentare
     */
    for(const[key, value] of errorsNameTextMap.entries()){
        if(key === error){
            errorElement.text(value);
            return;
        }
    }
    errorElement.text(defaultErrorText);
}

function displayError(errorsNameTextMap, error, defaultErrorText){
    $("#main").empty();
    fillError($("#main"), errorsNameTextMap, error, defaultErrorText);
}