$(function(){
    let id = getUrlParameter("id");

    $.post({
        url: "../../php/advert.php",
        datatype: "json",
        data: {
            'id': id,
        },
        success: showSearch,
        error: function () {
            displayError("server_unreachable");
        }
    });
});

function showSearch(){

}