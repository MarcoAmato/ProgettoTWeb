const loginErrorMap = new Map();
loginErrorMap.set("server_down", "Il server non è raggiungibile, prova più tardi");
loginErrorMap.set("user_not_found", "L'email inserita non corrisponde a nessun account, riprova");
loginErrorMap.set("wrong_password", "Password errata");


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
    });


    /**
     * Il click sull'invio del form della mail fa partire una 
     * richiesta AJAX che risponde all'input inserito e, in 
     * caso di successo, mostra la barra personale, mentre nel
     * caso di errore mostra l'errore opportuno in rosso sotto
     * il form di login
     */
    $("#login-form").submit(function(e){
        e.preventDefault();
        submitLogin();
    });

    $("#logout").click(function(e){
        e.preventDefault();
        $.post({
            url: "../../php/logout.php",
            datatype: "text",
            success: function(logoutResult){
                if(logoutResult === "okay"){
                    showLoginForm();
                }
            }
        })
    });


    /**
     * Se l'utente è già loggato mostro la barra personale
     */
    $.post({
        url: "../../php/autentication.php",
        datatype: "text",
        success: showLoginFormOrPersonalArea
    });
});

function showLoginFormOrPersonalArea(response){
    if(response === null){
        showLoginForm();
        /* $("#personal-area").hide(); */
    }else{
        showPersonalArea(response);
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
    let errorElement = $("#login-div p.error");
    let defaultErrorText = "Si è verificato un errore, riprova più tardi";
    fillError(errorElement, loginErrorMap, errorType, defaultErrorText);
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