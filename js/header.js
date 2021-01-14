$(function(){
    /**
     * mostro le opzioni personali quando l'utente passa sopra 
     * la barra col nome e icona utente 
    */
    $('#user-menu').mouseover(function(){
        $('.user-options').removeClass('hide');
    });
    
    /**
     * nascondo le opzioni personali quando l'utente esce dalla
     * la barra col nome e icona utente 
    */
    $('#personal-area').mouseleave(function() {
        $('#user-options-container div').addClass('hide');
        //IMPORTANTE DISABILITARE GLI A QUANDO VIENE INNESCATO QUESTO EVENTO
    });
    $.post({
        url: "../../php/autentication.php",
        datatype: "json",
        success: showLoginFormOrPersonalArea
    });
});

function showLoginFormOrPersonalArea(data){
    let jsonData = JSON.parse(data);
    if(jsonData.username === null){
        $("#personal-area").hide();
    }else{
        $("#login-div").hide();
        $("#user-menu span").text(jsonData.username);
        //error feedback to add
    }
}