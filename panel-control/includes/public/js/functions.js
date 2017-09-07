/**
 * Created by mario.cuevas on 5/11/2016.
 */
$(document).ready(function (){
    var url = (location.href).split('/');
    console.log(url);
    var listActive = url[url.length - 1];
    console.log(listActive);
    $('li').removeClass('active');
    if (listActive != '') {
        console.log(BASE_ROOT + listActive);
        $(".nav-sidebar [href = '"+BASE_ROOT + listActive+"']").parent().addClass('active');
    }
});