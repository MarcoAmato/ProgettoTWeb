$(function(){
    console.log("nigga");
    $.post({
        url: "../../php/autentication.php",
        datatype: "json",
        success: showLoginFormOrPersonalArea
    });
});

function showLoginFormOrPersonalArea(data){
    let jsonData = JSON.parse(data);
    console.log(jsonData.username);
    if(jsonData.username === null){
        $("#personal-area").hide();
    }else{
        $("#login-div").hide();
        $("#user-menu span").text(jsonData.username);
        //error feedback to add
    }
}