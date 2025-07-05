

(function ($) {
    "use strict";

    // ______________ PAGE LOADING
    $(window).on("load", function (e) {
        $("#global-loader").fadeOut("slow");
    })


    // ______________Full screen
    $(document).on("click", ".fullscreen-button", function toggleFullScreen() {
        $('html').addClass('fullscreenie');
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            $('html').removeClass('fullscreenie');
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    })

    // ______________ BACK TO TOP BUTTON
    $(window).on("scroll", function (e) {
        if ($(this).scrollTop() > 0) {
            $('#back-to-top').fadeIn('slow');
        } else {
            $('#back-to-top').fadeOut('slow');
        }
    });
    $(document).on("click", "#back-to-top", function (e) {
        $("html, body").animate({
            scrollTop: 0
        }, 0);
        return false;
    });


    // ______________ COVER IMAGE
    $(".cover-image").each(function () {
        var attr = $(this).attr('data-bs-image-src');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).css('background', 'url(' + attr + ') center center');
        }
    });

    // ______________Live Toast
    var toastTrigger = document.getElementById('liveToastBtn')
    var toastLiveExample = document.getElementById('liveToast')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {
            var toast = new bootstrap.Toast(toastLiveExample)
            toast.show()
        })
    }

    //Increment & Decrement
    var quantitiy = 0;
    $('.quantity-right-plus').on('click', function (e) {
        e.preventDefault();
        var quantity = parseInt($('.quantity').val());
        $('.quantity').val(quantity + 1);

    });
    $('.quantity-left-minus').on('click', function (e) {
        e.preventDefault();
        var quantity = parseInt($('.quantity').val());
        if (quantity > 0) {
            $('.quantity').val(quantity - 1);
        }
    });

    // ______________Chart-circle
    if ($('.chart-circle').length) {
        $('.chart-circle').each(function () {
            let $this = $(this);
            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: 'rgba(119, 119, 142, 0.2)',
                lineCap: 'round'
            });
        });
    }

    // ______________ CARD
    const DIV_CARD = 'div.card';

    // ______________Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    // ______________Popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })

    // ______________ FUNCTION FOR REMOVE CARD
    $(document).on('click', '[data-bs-toggle="card-remove"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.remove();
        e.preventDefault();
        return false;
    });


    // ______________ FUNCTIONS FOR COLLAPSED CARD
    $(document).on('click', '[data-bs-toggle="card-collapse"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    // ______________ CARD FULL SCREEN
    $(document).on('click', '[data-bs-toggle="card-fullscreen"]', function (e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    let html = document.querySelector('html');
    // ______________ SWITCHER-toggle ______________//

    $('.layout-setting').on("click", function (e) {
        if (!(document.querySelector('body').classList.contains('dark-mode'))) {
            $('body').addClass('dark-mode');
            $('body').removeClass('light-mode');

            $('body')?.removeClass('color-menu');
            $('body')?.removeClass('light-menu');
            $('body')?.removeClass('color-header');
            $('body')?.removeClass('header-light');

            $('#myonoffswitch5').prop('checked', true);
            $('#myonoffswitch8').prop('checked', true);

            localStorage.setItem('volghdarkMode', true);
            localStorage.removeItem('volghlightMode');
            localStorage.removeItem('volghcolorheader');
            localStorage.removeItem('volghlightheader');
            localStorage.removeItem('volghdarkheader');
            localStorage.removeItem('volghdarkmenu');
            localStorage.removeItem('volghlightmenu');
            localStorage.removeItem('volghcolormenu');
            // localStorage.removeItem("volghdarkBody");
            // localStorage.removeItem("volghdarkTheme");
            $('#myonoffswitch2').prop('checked', true);
        }
        else {
            $('body').addClass('light-mode');
            $('body').removeClass('dark-mode');
            localStorage.removeItem('volghdarkMode');
            localStorage.setItem('volghlightMode', true);
            localStorage.removeItem("volghdarkBody");
            localStorage.removeItem('volghcolorheader');
            localStorage.removeItem('volghdarkheader');
            localStorage.removeItem('volghlightheader');
            localStorage.removeItem('volghdarkmenu');
            localStorage.removeItem('volghlightmenu');
            localStorage.removeItem('volghcolormenu');
            localStorage.removeItem("volghdarkTheme");
            $('#myonoffswitch3').prop('checked', true);
            $('#myonoffswitch6').prop('checked', true);
            $('#myonoffswitch1').prop('checked', true);
        }
    });

    // ACCORDION STYLE
    $(document).on("click", '[data-bs-toggle="collapse"]', function () {
        $(this).toggleClass('active').siblings().removeClass('active');
    });

    // EMAIL INBOX
    $(".clickable-row").on('click', function () {
        window.location = $(this).data("href");
    });
  
})(jQuery);

// OFF-CANVAS STYLE
$('.off-canvas').on('click', function () {
    $('body').addClass('overflow-y-scroll');
    $('body').addClass('pe-0');
});


