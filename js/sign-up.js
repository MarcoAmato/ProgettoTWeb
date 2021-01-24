const errorsMap = new Map();
errorsMap.set("email_registered", "L'email ha già un account associato, inserire un'altra email");

$(function(){
    if(error = getURLParameter("error")){
        console.log(error);
        let errorElement = $('#sign-up-error');
        let defaultErrorText = "Si è verificato un errore, riprova più tardi";
        fillError(errorElement, errorsMap, error, defaultErrorText);
    }else if(success = getURLParameter("success") === "true"){
        $('#sign-up-form p.success').text("Account creato con successo");
    }

    $('input[type="password"]').keyup(check_password_match);
    $('input[type="date"]').change(check_birth);
});

function check_password_match(){
    if($("#password-log-up").val() !== $("#passwordR").val()){
        $("#password-div p").text("Le due password non coincidono");
        disable_signup();
    }else{
        $("#password-div p").text("");
        enable_signup();
    }
}

function check_birth(){
    let current_date = new Date();
    let date_18_years_ago = subtract_years(new Date(), 18);
    let date_110_years_ago = subtract_years(new Date(), 100);
    let birth = new Date($("#nascita").val());

    console.log(current_date);
    console.log(birth);
    console.log(date_110_years_ago);

    console.log(current_date > birth);
    

    if(current_date < birth){
        $("#nascita-div p").text("Wow, vieni dal futuro");
        disable_signup();
        //data sbagliata perchè dopo oggi
    }else if(date_18_years_ago < birth){
        $("#nascita-div p").text("Devi avere almeno 18 anni");
        disable_signup();
        //user ha meno di 18 anni
    }else if(birth < date_110_years_ago){
        $("#nascita-div p").text("Wow, sembri molto vecchio");
        disable_signup();
        //user ha meno di 18 anni
    }else{
        enable_signup();
        $("#nascita-div p").text("");
    }
}

function disable_signup(){
    $("#sign-up-form button").prop("disabled", true);
}

function enable_signup(){
    $("#sign-up-form button").prop("disabled", false);
}

function add_years(date, num_years){
    return new Date(date.setFullYear(date.getFullYear() + num_years));
}

function subtract_years(date, num_years){
    return new Date(date.setFullYear(date.getFullYear() - num_years));
}