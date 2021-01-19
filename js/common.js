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
    var sPageURL = window.location.search.substring(1); //window.location.search returns url parameters including the first '?', so substring ignores it
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
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
        let piattaformaHTML = '<option value="'+ piattaforma.nome +'">'+ piattaforma.nome +'</option>';
        selectPiattaforme.append(piattaformaHTML);
    }
}