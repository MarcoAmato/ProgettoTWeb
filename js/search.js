$(function(){
    let nome = getURLParameter("nome");
    let piattaforma = getURLParameter("piattaforma");

    if(piattaforma === "all"){
        $("#advertisements").append('<h1> ' + nome + ' - tutte le piattaforme </h1>');
    }else{
        $("#advertisements").append('<h1> ' + nome + ' - ' + piattaforma + ' </h1>');
    }

    $.post({
       url: "../../php/search.php",
       datatype: "json",
       data: {
           'nome': nome,
           'piattaforma': piattaforma
       },
       success: showSearch
    });
});

function showSearch(data){
    console.log(data);
    let jsonAnnunci = null;
    try{
        jsonAnnunci = JSON.parse(data);
    }catch(e){
        console.log(e);
        //dai errore per ricerca non possibile
        return;
    }
    
    console.log("jsonAnnunci: "+jsonAnnunci);
    for(annuncio of jsonAnnunci){ /**
     * <div class="advert">
            <img src="../../img/god_of_war.jpeg" alt="">
            <div>
                <h2>Dio</h2>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa optio mollitia earum? Hic magnam est sit suscipit accusamus, fugit minus.</p>
            </div>
            <img src="../../img/icons/empty_heart.png" alt="" class="heart">
        </div>
     */
        let divAdvert = '<div class="advert">';
            if(annuncio.path_immagine !== null){
                let imgAdvert = '<img src="../../img/advert-img/'+ annuncio.path_immagine + '" alt="'+ annuncio.titolo +'">';
                divAdvert += imgAdvert;
            }
            divAdvert += "<div>";
                divAdvert += '<h2>' + annuncio.titolo + '</h2>';
                divAdvert += '<p>' + annuncio.testo + '</p>';
            divAdvert += "</div>";
            divAdvert += '<img src="../../img/icons/empty_heart.png" alt="" class="heart">';
        divAdvert += '</div>';

        $("#advertisements").append(divAdvert);
    }
}