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

    $("#login-form").submit(function(e){
        e.preventDefault();
        submitLogin();
    });

    $.post({
        url: "../../php/autentication.php",
        datatype: "json",
        success: showLoginFormOrPersonalArea
    });
});

function showLoginFormOrPersonalArea(data){
    console.log(data);
    let jsonData = JSON.parse(data);
    if(jsonData.email === null){
        showLoginForm();
        /* $("#personal-area").hide(); */
    }else{
        showPersonalArea(jsonData.email);
        /* $("#login-div").hide();
        $("#user-menu span").text(jsonData.username); */
        //error feedback to add
    }
}

function submitLogin(){
    let email = $("#email").val();
    let password = $("#password").val();
    $.post({
        url: "../../php/login.php",
        data: {
            'email': email,
            'password': password
        },
        datatype: "json",
        success: processLoginResult
    });
}

function processLoginResult(loginResult){
    let loginResultJSON = null;
    try{
        loginResultJSON = JSON.parse(loginResult);
    }catch(e){
        //Then the result is not parsable. It should be a string containing the error message
        showLoginError(loginResult);
        return;
    }
    showPersonalArea(loginResultJSON.email);
    //qui va controllato il risultato del login. In caso di successo viene mostrata la barra personale. In caso di fallimento viene dato messaggio di errore login
}

function showLoginError(errorType){
    console.log("Login error: " + errorType);
    let textToShow;
    switch(errorType){
        case 'server down':
            textToShow = "Il server non è raggiungibile, prova più tardi";
            break;
        case 'user not found':
            textToShow = "L'email inserita non corrisponde a nessun account, riprova";
            break;
        case 'wrong password for user':
            textToShow = "Password errata";
            break;
        default:
            textToShow = "Si è presentato un errore inaspettato, prova più tardi"
            break;
    }
    $("#login-div p.error").text(textToShow);
    //in base al tipo di errore va dato il feedback opportuno all'utente. Leggiamo i nomi degli errori su sign-up.php
}

function showLoginForm(){
    $("#personal-area").hide();
    $("#login-div").show();
}

function showPersonalArea(email){
    $("#login-div").hide();
    $("#personal-area").show();
    $("#user-menu span").text(email);
}