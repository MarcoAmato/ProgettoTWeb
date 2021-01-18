function getURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1); //window.location.search returns url parameters including the first '?', so substring ignores it
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}