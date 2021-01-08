$(function(){
    $('#user-menu').mouseover(function(){
        $('.user-options').removeClass('hide');
    });
    $('#personal-area').mouseleave(function() {
        $('.user-options').addClass('hide');
    });
})