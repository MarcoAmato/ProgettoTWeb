const searchErrorMap = new Map();
searchErrorMap.set("server_down", "Il server non è raggiungibile, riprovare più tardi");
searchErrorMap.set("query_failed", "Non è possibile visualizzare gli annunci, riprovare più tardi");

const idAnnunci = [];

$(function () {
    let personal = getURLParameter("personal");
    let nome = getURLParameter("nome");
    let piattaforma = getURLParameter("piattaforma");

    if (personal === "true") {
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

function showPersonalSearch(autentication) {
    if (autentication === null) {
        window.location.replace("./index.shtml");
    } else {
        $.post({
            url: "../../php/search.php",
            datatype: "json",
            data: {
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

    let indexAnnuncio = 0;

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
        let divAdvert = '<div class="advert" id="annuncio' + indexAnnuncio + '">';
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
            divAdvert += '<img src="../../img/icons/empty_heart.png" alt="" id=heart'+ indexAnnuncio +' class="heart">';
        divAdvert += '</div>';

        $("#advertisements").append(divAdvert);

        idAnnunci.push(annuncio.id);
        indexAnnuncio++;
    }

    loadPreferiti()
}

 /**
  * Manda una richiesta al server per ottenere quali annunci tra quelli
  * corrispondenti alla ricerca sono già tra i preferiti
  * 
  * @param {Number[]} idAnnunci array di id degli annunci presenti nella ricerca.
 * In idAnnunci[i] ci sarà l'id corrispondente all'annuncio collocato
 * nell'advert con id "advert-i".
  */
function loadPreferiti() {
    $.post({
        url: "../../php/getPreferiti.php",
        datatype: "json",
        data: {
            'id_annunci': idAnnunci
        },
        success: updateSearchPreferiti,
        error: function () {
            showSearch("server_unreachable");
        }
    });
}

/**
 * 
 * @param {boolean[]} arrayPreferiti array di tipo boolean, contiene true
 * se l'annuncio è già tra i preferiti, false altrimenti. In caso di errori
 * lato server invece è una string contenente l'errore;
 */
function updateSearchPreferiti(arrayPreferiti) {

    let jsonPreferiti = null;
    try {
        jsonPreferiti = JSON.parse(arrayPreferiti);
    } catch (e) {
        if(arrayPreferiti === "access_denied"){
			//aggiungi al click di tutti i cuori un popup che invita a registrarsi
		}else{
			console.log(arrayPreferiti);
		}
		return;
    }

    let numAnnuncio = 0;

    for (isPreferito of jsonPreferiti) {
        if(isPreferito){
            $("#annuncio"+numAnnuncio+" .heart").attr("src","../../img/icons/full_heart.png");
            $("#annuncio"+numAnnuncio+" .heart").click(removePreferiti);
        }else{
            $("#annuncio"+numAnnuncio+" .heart").click(addPreferiti);
        }
        numAnnuncio++;
    }
}

function removePreferiti(){
    const heartElement = $(this);
    const id_clicked = $(this).attr("id");
    const indexAnnuncio = id_clicked.slice(-1);
    
    $.post({
        url: "../../php/preferiti-operations.php",
        data: {
            operation: "remove",
            id_annuncio: idAnnunci[indexAnnuncio]
        },
        datatype: "text",
        success: function(returnValue){
            if(returnValue !== "success"){
                showPreferitiError($(this), returnValue);
                return;
            }
            heartElement.click(addPreferiti);
            heartElement.attr("src","../../img/icons/empty_heart.png");
        },
        error: function(){
            showPreferitiError($(this),"page_not_found");
        }
    });
}

function addPreferiti(){
    const heartElement = $(this);
    const id_clicked = $(this).attr("id");
    const indexAnnuncio = id_clicked.slice(-1);
    
    $.post({
        url: "../../php/preferiti-operations.php",
        data: {
            operation: "add",
            id_annuncio: idAnnunci[indexAnnuncio]
        },
        datatype: "text",
        success: function(returnValue){
            if(returnValue !== "success"){
                showPreferitiError($(this), returnValue);
                return;
            }
            heartElement.click(removePreferiti);
            heartElement.attr("src","../../img/icons/full_heart.png");
        },
        error: function(){
            showPreferitiError($(this),"page_not_found");
        }
    });

    console.log("pino");
}

function showPreferitiError(heartElement, errorType){
    console.log(errorType);
}