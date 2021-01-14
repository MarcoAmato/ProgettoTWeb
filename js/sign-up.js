$(function(){
    $('input[type="password"]').keyup(checkPasswordMatch);
    $('input[type="date"]').change(checkBirth);
});

function checkPasswordMatch(){
    if($("#password-log-up").val() !== $("#passwordR").val()){
        $("#password-div p").text("Le due password non coincidono");
    }else{
        $("#password-div p").text("");
    }
}

function checkBirth(){
    let current_date = new Date();
    let date_18_years_ago = subtract_years(new Date(), 18);
    let date_110_years_ago = add_years(new Date(), 100);
    let birth = new Date($("#nascita").val());

    console.log(current_date);
    console.log(birth);
    console.log(current_date > birth);
    

    if(current_date < birth){
        $("#nascita-div p").text("Wow, vieni dal futuro");
        //data sbagliata perchè dopo oggi
    }else if(date_18_years_ago < birth){
        $("#nascita-div p").text("Devi avere almeno 18 anni");
        //user ha meno di 18 anni
    }else if(birth < date_110_years_ago){
        $("#nascita-div p").text("Wow, sembri molto vecchio");
        //user ha meno di 18 anni
    }else{
        $("#nascita-div p").text("");
    }
}

function add_years(date, num_years){
    return new Date(date.setFullYear(date.getFullYear() + num_years));
}

function subtract_years(date, num_years){
    return new Date(date.setFullYear(date.getFullYear() - num_years));
}