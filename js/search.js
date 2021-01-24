const searchErrorMap = new Map();
searchErrorMap.set("server_down", "Il server non è raggiungibile, riprovare più tardi");
searchErrorMap.set("query_failed", "Non è possibile visualizzare gli annunci, riprovare più tardi");

$(function () {
    let personal = getURLParameter("personal");
    let nome = getURLParameter("nome");
    let piattaforma = getURLParameter("piattaforma");

    if(personal === "true"){
        $.post({
            url: "../../php/autentication.php",
            datatype: "text",
            success: showPersonalSearch,
            error: function () {
                showSearch("server_unreachable");
            }
        });

        return;
    }

    if (!nome || !piattaforma) {
        showSearch("variables_not_set");
        return;
    }

    console.log(nome);
    console.log(piattaforma);

    if (piattaforma === "all") {
        $("#advertisements").append('<h1> ' + nome + ' - tutte le piattaforme </h1>');
    } else {
        $("#advertisements").append('<h1> ' + nome + ' - ' + piattaforma + ' </h1>');
    }

    $.post({
        url: "../../php/search.php",
        datatype: "json",
        data: {
            'nome': nome,
            'piattaforma': piattaforma
        },
        success: showSearch,
        error: function () {
            showSearch("server_unreachable");
        }
    });
});

function showPersonalSearch(autentication){
    if(autentication === null){
        window.location.replace("./index.shtml");
    }else{
        $.post({
            url: "../../php/search.php",
            datatype: "json",
            data:{
                'personal': 'true'
            },
            success: showSearch,
            error: function () {
                showSearch("server_unreachable");
            }
        });
    }
}

function showSearch(data) {
    $("#advertisements").html("");

    let jsonAnnunci = null;
    try {
        jsonAnnunci = JSON.parse(data);
    } catch (e) {
        console.log("error: " + data);
        let searchErrorElement = $("#search-error");
        let defaultErrorText = "Si è verificato un errore, riprova più tardi";
        fillError(searchErrorElement, searchErrorMap, data, defaultErrorText);
        return;
    }

    let idAnnunci = [];

    for (annuncio of jsonAnnunci) { /**
     * <div class="advert">
            <div class="advert-image">
                <img src="../../img/god_of_war.jpeg" alt="">
            </div>
            <div class="advert-text">
                <h2>Dio</h2>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa optio mollitia earum? Hic magnam est sit suscipit accusamus, fugit minus.</p>
            </div>
            <img src="../../img/icons/empty_heart.png" alt="" class="heart">
        </div>
     */
        let divAdvert = '<div class="advert">';
        if (annuncio.path_immagine !== null) {
            let imgAdvert = '<img src="../../img/advert-img/' + annuncio.path_immagine + '" alt="' + annuncio.titolo + '">';

            divAdvert += "<div class='advert-image'>";
            divAdvert += imgAdvert;
            divAdvert += "</div>";
        }
        divAdvert += "<div class='advert-text'>";
        divAdvert += '<h2>' + annuncio.titolo + '</h2>';
        divAdvert += '<p>' + annuncio.testo + '</p>';
        divAdvert += "</div>";
        divAdvert += '<img src="../../img/icons/empty_heart.png" alt="" class="heart">';
        divAdvert += '</div>';

        $("#advertisements").append(divAdvert);

        idAnnunci.push(annuncio.id);
    }

    /* loadPreferiti(idAnnunci) */
}

/* function loadPreferiti(idAnnunci){
    $.post({
        url: "../../php/getPreferiti.php",
        datatype: "json",
        data: {
            'id_annunci': idAnnunci
        },
        success: updateSearchPreferiti(data)
        },
        error: function () {
            showSearch("server_unreachable");
        }
    });
} */

function updateSearchPreferiti(id_preferiti){

}