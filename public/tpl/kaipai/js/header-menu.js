$(document).ready( function() {
    $(".menu-button").click( function() {
        console.log('ddd')
        $(".top-menu").toggle();
        if ($(".top-menu").is(":visible")) {
            console.log('3333')
            $(".menu-button").removeClass("menu-open");
            $(".menu-button").addClass("menu-close");
            if (screen.width > 800) {
                $(".top-menu").css({ display: 'block !important' });
            }
        } else {
            console.log('444')
            $(".menu-button").removeClass("menu-close");
            $(".menu-button").addClass("menu-open");
            if (screen.width > 800) {
                $(".top-menu").css({ display: 'block !important' });
            }
        }
    });
});