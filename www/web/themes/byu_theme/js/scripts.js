jQuery( document ).ready(function( $ ) {

    if (document.querySelector("#toolbar-bar") != null) {
        $("body").addClass("admin-menu-bar");
    }

    var extendedToolbar = $("#toolbar-item-administration-tray");

    if (extendedToolbar.hasClass('is-active')) {
        $("body").addClass("admin-extended-menu-bar");
    }

    $('.toolbar-tab .toolbar-icon-menu').click(function() {
        // .is-active class removes after function runs, so if statements are reversed
        if (extendedToolbar.hasClass('is-active')) {
            $("body").removeClass("admin-extended-menu-bar");
        } else {
            $("body").addClass("admin-extended-menu-bar");
        }
    });
});
