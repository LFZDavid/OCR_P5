$(document).ready(function () {

    //Bouton pour remonter en haut de page
    $('#scrolltop>a').on('click', function (e) {
        e.preventDefault();
        $('html').animate({ scrollTop: 0 }, 'slow');
    });

    $('.lost-pwd-form').hide(0);
    $('#lost-pwd-btn').on('click', function (e) {
        e.preventDefault();
        $('.lost-pwd-form').slideDown('slow');
    });

});