$(function(){
    $.post({
        url: "../php/autentication.php",
        datatype: "json",
        success: showLoginFormOrPersonalArea
    });
});

function showLoginFormOrPersonalArea(json){
    if(json == null){
        $("#personal-area").hide();
    }else{
        $("#user-menu span").text(json);
    }
}