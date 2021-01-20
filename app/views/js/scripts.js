$(document).ready(function () {

    //Bouton pour remonter en haut de page
    $('#scrolltop>a').on('click', function (e) {
        e.preventDefault();
        $('html').animate({ scrollTop: 0 }, 'slow');
    });

});