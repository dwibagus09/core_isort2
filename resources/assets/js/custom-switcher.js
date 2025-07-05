import { names } from './themeColors';

(function ($) {
    "use strict";

    // LIGHT THEME START
    $(document).on("click", '#myonoffswitch1', function () {
        if (this.checked) {
            $('body').addClass('light-mode');
            $('#myonoffswitch3').prop('checked', true);
            $('#myonoffswitch6').prop('checked', true);
            $('body').removeClass('dark-mode');

            $('body')?.removeClass('color-menu');
            $('body')?.removeClass('dark-menu');
            $('body')?.removeClass('color-header');
            $('body')?.removeClass('dark-header');

            // remove dark theme properties	
            localStorage.removeItem('volghdarkPrimary')

            // remove light theme properties
            localStorage.removeItem('volghprimaryColor')
            localStorage.removeItem('volghprimaryHoverColor')
            localStorage.removeItem('volghprimaryBorderColor')
            document.querySelector('html').style.removeProperty('--volgh-primary-rgb', localStorage.darkPrimary);
            document.querySelector('html').style.removeProperty('--volgh-primary-hover', localStorage.darkPrimary);
            document.querySelector('html').style.removeProperty('--volgh-primary-border', localStorage.darkPrimary);
            document.querySelector('html').style.removeProperty('--dark-primary', localStorage.darkPrimary);

            // removing dark theme properties
            localStorage.removeItem('volghdarkPrimary')
            localStorage.removeItem('volghtransparentBgColor');
            localStorage.removeItem('volghtransparentThemeColor');
            localStorage.removeItem('volghtransparentPrimary');
            localStorage.removeItem('volghdarkprimaryTransparent');


            $('#myonoffswitch1').prop('checked', true);
            $('#myonoffswitch2').prop('checked', false);
            $('#myonoffswitchTransparent').prop('checked', false);

            checkOptions();
            const root = document.querySelector(':root');
            root.style = "";
            names()
            localStorage.setItem('volghlightMode', true);
            localStorage.removeItem('volghdarkMode');
            localStorage.removeItem("volghdarkBody");
            localStorage.removeItem("volghdarkTheme");
        }
    });
    // LIGHT THEME END

    // DARK THEME START
    $(document).on("click", '#myonoffswitch2', function () {
        if (this.checked) {
            $('body').addClass('dark-mode');

            $('#myonoffswitch5').prop('checked', true);
            $('#myonoffswitch8').prop('checked', true);
            $('body').removeClass('light-mode');

            $('body')?.removeClass('color-menu');
            $('body')?.removeClass('light-menu');
            $('body')?.removeClass('color-header');
            $('body')?.removeClass('header-light');

            // remove light theme properties
            localStorage.removeItem('volghprimaryColor')
            localStorage.removeItem('volghprimaryHoverColor')
            localStorage.removeItem('volghprimaryBorderColor')
            localStorage.removeItem('volghdarkPrimary')
            document.querySelector('html').style.removeProperty('--volgh-primary-rgb');
            document.querySelector('html').style.removeProperty('--volgh-primary-hover');
            document.querySelector('html').style.removeProperty('--volgh-primary-border');

            // removing light theme data 
            localStorage.removeItem('volghprimaryColor')
            localStorage.removeItem('volghprimaryHoverColor')
            localStorage.removeItem('volghprimaryBorderColor')
            localStorage.removeItem('volghprimaryTransparent');

            $('#myonoffswitch1').prop('checked', false);
            $('#myonoffswitch2').prop('checked', true);
            $('#myonoffswitchTransparent').prop('checked', false);
            //
            checkOptions();

            localStorage.removeItem('volghtransparentBgColor');
            localStorage.removeItem('volghtransparentThemeColor');
            localStorage.removeItem('volghtransparentPrimary');
            document.querySelector('html').style.removeProperty("--volgh-dark-background");
            document.querySelector('html').style.removeProperty("--volgh-dark-theme");
            const root = document.querySelector(':root');
            root.style = "";
            localStorage.setItem('volghdarkMode', true);
            localStorage.removeItem('volghlightMode');

            localStorage.removeItem("volghdarkTheme");
            localStorage.removeItem("volghdarkBody");
            names()
        }
    });
    // DARK THEME END

    // LIGHT LEFTMENU START
    $(document).on("click", '#myonoffswitch3', function () {
        if (this.checked) {
            $('body').addClass('light-menu');
            $('body').removeClass('color-menu');
            $('body').removeClass('dark-menu');
            localStorage.setItem("volghlightmenu", true);
            localStorage.removeItem("volghdarkmenu");
            localStorage.removeItem("volghcolormenu");
        } else {
            $('body').removeClass('light-menu');
        }
    });
    // LIGHT LEFTMENU END

    // COLOR LEFTMENU START
    $(document).on("click", '#myonoffswitch4', function () {
        if (this.checked) {
            $('body').addClass('color-menu');
            $('body').removeClass('light-menu');
            $('body').removeClass('dark-menu');
            localStorage.setItem("volghcolormenu", true);
            localStorage.removeItem("volghdarkmenu");
            localStorage.removeItem("volghlightmenu");
        } else {
            $('body').removeClass('color-menu');
        }
    });
    // COLOR LEFTMENU END

    // DARK LEFTMENU START
    $(document).on("click", '#myonoffswitch5', function () {
        if (this.checked) {
            $('body').addClass('dark-menu');
            $('body').removeClass('color-menu');
            $('body').removeClass('light-menu');
            localStorage.setItem("volghdarkmenu", true);
            localStorage.removeItem("volghcolormenu");
            localStorage.removeItem("volghlightmenu");
        } else {
            $('body').removeClass('dark-menu');
        }
    });
    // DARK LEFTMENU END

    // LIGHT HEADER START
    $(document).on("click", '#myonoffswitch6', function () {
        if (this.checked) {
            $('body').addClass('header-light');
            $('body').removeClass('color-header');
            $('body').removeClass('dark-header');
            localStorage.setItem("volghlightheader", true);
            localStorage.removeItem("volghdarkheader");
            localStorage.removeItem("volghcolorheader");
        } else {
            $('body').removeClass('header-light');
        }
    });
    // LIGHT HEADER END

    // COLOR HEADER START
    $(document).on("click", '#myonoffswitch7', function () {
        if (this.checked) {
            $('body').addClass('color-header');
            $('body').removeClass('header-light');
            $('body').removeClass('dark-header');
            localStorage.setItem("volghcolorheader", true);
            localStorage.removeItem("volghdarkheader");
            localStorage.removeItem("volghlightheader");
        } else {
            $('body').removeClass('color-header');
        }
    });
    // COLOR HEADER END

    // DARK HEADER START
    $(document).on("click", '#myonoffswitch8', function () {
        if (this.checked) {
            $('body').addClass('dark-header');
            $('body').removeClass('color-header');
            $('body').removeClass('header-light');
            localStorage.setItem("volghdarkheader", true);
            localStorage.removeItem("volghcolorheader");
            localStorage.removeItem("volghlightheader");
        } else {
            $('body').removeClass('dark-header');
        }
    });
    // DARK HEADER END

    // FULL WIDTH LAYOUT START
    $(document).on("click", '#myonoffswitch9', function () {
        if (this.checked) {
            $('body').addClass('layout-fullwidth');
            $('body').removeClass('layout-boxed');
            localStorage.setItem("volghlayoutfullwidth", true);
            localStorage.removeItem("volghboxedwidth");
            checkHoriMenu();
        } else {
            $('body').removeClass('layout-fullwidth');
        }
    });
    // FULL WIDTH LAYOUT END

    // BOXED LAYOUT START
    $(document).on("click", '#myonoffswitch10', function () {
        if (this.checked) {
            $('body').addClass('layout-boxed');
            $('body').removeClass('layout-fullwidth');
            localStorage.setItem("volghboxedwidth", true);
            localStorage.removeItem("volghlayoutfullwidth");
            // localStorageBackup();
            checkHoriMenu();
        } else {
            $('body').removeClass('layout-boxed');
        }
    });
    // BOXED LAYOUT END

    // HEADER POSITION STYLES START
    $(document).on("click", '#myonoffswitch11', function () {
        if (this.checked) {
            $('body').addClass('fixed-layout');
            $('body').removeClass('scrollable-layout');
            localStorage.setItem("volghfixed", true);
            localStorage.removeItem("volghscrollable");
        } else {
            $('body').removeClass('fixed-layout');
        }
    });
    $(document).on("click", '#myonoffswitch12', function () {
        if (this.checked) {
            $('body').addClass('scrollable-layout');
            $('body').removeClass('fixed-layout');
            localStorage.setItem("volghscrollable", true);
            localStorage.removeItem("volghfixed");
            // localStorageBackup();
        } else {
            $('body').removeClass('scrollable-layout');
        }
    });
    // HEADER POSITION STYLES END

      // DEFAULT SIDEMENU START
      $(document).on("click", '#myonoffswitch13', function () {
        if (this.checked) {
            $('body').addClass('default-menu');
            $('body').removeClass('sidenav-toggled');
            hovermenu();
            $('body').removeClass('icontext-menu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('double-menu');
            $('body').removeClass('center-logo');
            $('body').removeClass('default-logo');
            localStorage.setItem("volghdefaultmenu", true);
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
        } else {
            $('body').removeClass('default-menu');
        }
    });
    // DEFAULT SIDEMENU END

    // ICON OVERLAY SIDEMENU START
    $(document).on("click", '#myonoffswitch15', function () {
        if (this.checked) {
            $('body').addClass('icon-overlay');
            hovermenu();
            iconoverlay();
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-menu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('double-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled-open');
            localStorage.setItem("volghsideiconmenu", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('icon-overlay');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // ICON OVERLAY SIDEMENU END

    // ICONTEXT SIDEMENU START
    $(document).on("click", '#myonoffswitch14', function () {
        if (this.checked) {
            $('body').addClass('icontext-menu');
            icontext();
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-menu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('double-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('sidenav-toggled-open');
            localStorage.setItem("volghicontextmenu", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // ICONTEXT SIDEMENU END

    // CLOSED SIDEMENU START
    $(document).on("click", '#myonoffswitch16', function () {
        if (this.checked) {
            $('body').addClass('closed-leftmenu');
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('default-menu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('double-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled-open');
            localStorage.setItem("volghclosedmenu", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();

        } else {
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('sidenav-toggled');
            $('body').addClass('default-menu');
        }
    });
    // CLOSED SIDEMENU END

    // HOVER SUBMENU START
    $(document).on("click", '#myonoffswitch17', function () {
        if (this.checked) {
            $('body').addClass('hover-submenu');
            hovermenu();
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-menu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('double-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled-open');
            $('.app-sidebar').removeClass('sidemenu-scroll');
            localStorage.setItem("volghhoversubmenu", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('hover-submenu');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // HOVER SUBMENU END
    
    //  Default Logo Start
    $(document).on("click", '#default-logo', function () {
        if (this.checked) {
            $('body').addClass('default-logo');
            $('body').removeClass('center-logo');
            localStorage.setItem("volghdefaultlogo", true);
            localStorage.removeItem("volghcenterlogo");
        }
    });
    //  Default Logo End

    //  Center Logo Start
    $(document).on("click", '#center-logo', function () {
        if (this.checked) {
            $('body').addClass('center-logo');
            $('body').removeClass('default-logo');
            localStorage.setItem("volghcenterlogo", true);
            localStorage.removeItem("volghdefaultlogo");
        }
    });
    //  Center Logo End

    // HOVER SUBMENU STYLE-1 START
    $(document).on("click", '#myonoffswitch18', function () {
        if (this.checked) {
            $('body').addClass('hover-submenu1');
            hovermenu();
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('default-menu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('double-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icontext-menu');
            // $('body').removeClass('sidenav-toggled-open');
            $('.app-sidebar').removeClass('sidemenu-scroll');
            localStorage.setItem("volghhoversubmenu1", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // HOVER SUBMENU STYLE-1 END

    // Double Menu START
    $(document).on("click", '#myonoffswitch19', function () {
        if (this.checked) {
            $('body').addClass('double-menu');
            doubleLayoutFn();
            // $('body').addClass('sidenav-toggled');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-menu');
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled-open');
            $('.app-sidebar').removeClass('sidemenu-scroll');
            localStorage.setItem("volghdoublemenu", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghdoublemenutabs");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('double-menu');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // Double Menu END

    // Double Menu with Tabs START
    $(document).on("click", '#myonoffswitch20', function () {
        if (this.checked) {
            $('body').addClass('double-menu-tabs');
            doubleLayoutFn();
            // $('body').addClass('sidenav-toggled');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-menu');
            $('body').removeClass('double-menu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icontext-menu');
            $('body').removeClass('sidenav-toggled-open');
            $('.app-sidebar').removeClass('sidemenu-scroll');
            localStorage.setItem("volghdoublemenutabs", true);
            localStorage.removeItem("volghdefaultmenu");
            localStorage.removeItem("volghicontextmenu");
            localStorage.removeItem("volghsideiconmenu");
            localStorage.removeItem("volghclosedmenu");
            localStorage.removeItem("volghhoversubmenu");
            localStorage.removeItem("volghdoublemenu");
            localStorage.removeItem("volghhoversubmenu1");
            localStorage.removeItem("volghcenterlogo");
            localStorage.removeItem("volghdefaultlogo");
            // localStorage.removeItem("volghdefaultlogo");
            // localStorageBackup();
        } else {
            $('body').removeClass('double-menu-tabs');
            $('body').removeClass('sidenav-toggled');
        }
    });
    // Double Menu with Tabs END


    /***************** Add Switcher Classes *********************/

        //---- DARK THEME START ----- //
            // $('body').addClass('dark-mode');
        //---- DARK THEME END ----- //

        //---- LIGHT LEFTMENU START ----- //
            // $('body').addClass('light-menu');
        //---- LIGHT LEFTMENU END ----- //

        //---- COLOR LEFTMENU START ----- //
            // $('body').addClass('color-menu');
        //---- COLOR LEFTMENU END ----- //

        //---- DARK LEFTMENU START  ----- //
            // $('body').addClass('dark-menu');
        //---- DARK LEFTMENU END ----- //

        //---- LIGHT HEADER START ----- //
            // $('body').addClass('header-light');
        //---- LIGHT HEADER END ----- //

        //---- COLOR HEADER START ----- //
            // $('body').addClass('color-header');
        //---- COLOR HEADER END ----- //

        //---- DARK HEADER START ----- //
            // $('body').addClass('dark-header');
        //---- DARK HEADER END ----- //

        //---- FULL WIDTH LAYOUT START ----- //
            // $('body').addClass('layout-fullwidth');
            // checkHoriMenu();
        //---- FULL WIDTH LAYOUT END ----- //

        //---- BOXED LAYOUT START ----- //
            // $('body').addClass('layout-boxed');
            // checkHoriMenu();
        //---- BOXED LAYOUT END ----- //

        //---- HEADER POSITION STYLES START ----- //
            // $('body').addClass('fixed-layout');
            // $('body').addClass('scrollable-layout');
        //---- HEADER POSITION STYLES END ----- //

        //---- DEFAULT SIDEMENU START ----- //
            // $('body').addClass('default-menu');
            // hovermenu();
        //---- DEFAULT SIDEMENU END ----- //

        //---- ICON OVERLAY SIDEMENU START ----- //
            // $('body').addClass('icon-overlay');
            // hovermenu();
            // iconoverlay();
        //---- ICON OVERLAY SIDEMENU END ----- //

        //---- ICONTEXT SIDEMENU START ----- //
            // $('body').addClass('icontext-menu');
            // icontext();
        //---- ICONTEXT SIDEMENU END ----- //

        //---- CLOSED SIDEMENU START ----- //
            // $('body').addClass('closed-leftmenu');
            // $('body').addClass('sidenav-toggled');
        //---- CLOSED SIDEMENU END ----- //

        //---- HOVER SUBMENU START ----- //
            // $('body').addClass('hover-submenu');
            // $('body').addClass('sidenav-toggled');
            // hovermenu();
        //---- HOVER SUBMENU END ----- //

        //---- HOVER SUBMENU STYLE-1 START ----- //
            // $('body').addClass('hover-submenu1');
            // $('body').addClass('sidenav-toggled');
            // hovermenu();
        //---- HOVER SUBMENU STYLE-1 END ----- //

        //---- DOUBLE-MENU START ----- //
            // $('body').addClass('double-menu');
        //---- DOUBLE-MENU END ----- //

        //---- DOUBLE-MENU-TABS START ----- //
            // $('body').addClass('double-menu-tabs');
        //---- DOUBLE-MENU-TABS END ----- //

         //---- HORIZONTAL CLICK MENU START ----- //
            // $('body').addClass('horizontal');
        //---- HORIZONTAL CLICK MENU END ----- //

        //---- HORIZONTAL HOVER MENU START ----- //
            // $('body').addClass('horizontal-hover');
        //---- HORIZONTAL HOVER MENU END ----- //

        //---- CENTER LOGO START ----- //
            // $('body').addClass('center-logo'); 
        //---- CENTER LOGO END ----- //

        //---- RTL START ----- //
            // $('body').addClass('rtl');
        //---- RTL END ----- //


    /***************** Add Switcher Classes *********************/


})(jQuery);

// RTL STYLE START
$(document).on("click", '#myonoffswitch24', function () {
    if (this.checked) {
        $('body').addClass('rtl');

        $('#slide-left').removeClass('d-none');
        $('#slide-right').removeClass('d-none');
        localStorage.setItem('volghrtl', true)
        localStorage.removeItem('volghltr')
        $("html[lang=en]").attr("dir", "rtl");
        $('body').removeClass('ltr');
        $("head link#style").attr("href", $(this));
        (document.getElementById("style").setAttribute("href", "http://127.0.0.1:8000/build/assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
        var carousel = $('.owl-carousel');
        $.each(carousel, function (index, element) {
            // element == this
            var carouselData = $(element).data('owl.carousel');
            carouselData.settings.rtl = true; //don't know if both are necessary
            carouselData.options.rtl = true;
            $(element).trigger('refresh.owl.carousel');
        });
    }
});
// RTL STYLE END

// LTR STYLE START
$(document).on("click", '#myonoffswitch23', function () {
    if (this.checked) {
        $('body').addClass('ltr');

        $('#slide-left').removeClass('d-none');
        $('#slide-right').removeClass('d-none');
        $("html[lang=en]").attr("dir", "ltr");
        $('body').removeClass('rtl');
        $("head link#style").attr("href", $(this));
        (document.getElementById("style").setAttribute("href", "http://127.0.0.1:8000/build/assets/plugins/bootstrap/css/bootstrap.min.css"));
        var carousel = $('.owl-carousel');
        $.each(carousel, function (index, element) {
            // element == this
            var carouselData = $(element).data('owl.carousel');
            carouselData.settings.rtl = false; //don't know if both are necessary
            carouselData.options.rtl = false;
            $(element).trigger('refresh.owl.carousel');
        });
        localStorage.setItem('volghltr', true)
        localStorage.removeItem('volghrtl')
    }
});
// LTR STYLE END

//VERTICAL
$(document).on("click", '#myonoffswitch34', function () {
    if (this.checked) {
        ActiveSubmenu();
        $('body').removeClass('horizontal');
        $('body').removeClass('horizontal-hover');
        $('body').removeClass('center-logo');
        $(".main-content").removeClass("hor-content");
        $(".main-content").addClass("app-content");
        $(".main-container").removeClass("container");
        $(".main-container").addClass("container-fluid");
        $(".app-header").removeClass("hor-header");
        $(".hor-header").addClass("app-header");
        $(".app-sidebar").removeClass("horizontal-main")
        $(".main-sidemenu").removeClass("container")
        $(".slide-menu").removeClass("ps")
        $(".slide-menu").removeClass("ps--active-y")
        $('#slide-left').removeClass('d-none');
        $('#slide-right').removeClass('d-none');
        $('body').addClass('sidebar-mini');
        localStorage.removeItem("volghhorizontal");
        localStorage.removeItem("volghcenterlogo");
        localStorage.removeItem("volghhorizontalHover");
        responsive();

        if (!(document.querySelector('.icontext-menu') !== null)) {
            hovermenu();
        }

    } else {
        $('body').removeClass('sidebar-mini');
        localStorage.setItem("volghsidebar-mini", "False");
    }
});

// HORIZONTAL
$(document).on("click", '#myonoffswitch35', function () {
    if (this.checked) {
        if (!document.querySelector('.login-img')) {
            ActiveSubmenu();
            checkHoriMenu();
            responsive(); 
            menuClick();
        }
        setTimeout(() => {
            if (window.innerWidth >= 992) {
                let li = document.querySelectorAll('.side-menu li')
                li.forEach((e, i) => {
                    e.classList.remove('is-expanded')
                })
                var animationSpeed = 300;
                // first level
                var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
                var ul = parent.find('ul[class^="slide-menu"]:visible').slideUp(animationSpeed);
                ul.removeClass('open');
                var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
                var ul1 = parent1.find('ul[class^="sub-slide-menu"]:visible').slideUp(animationSpeed);
                ul1.removeClass('open');
            }
        }, 200)
        $('body').addClass('horizontal');
        $(".main-content").addClass("hor-content");
        $(".main-content").removeClass("app-content");
        $(".main-container").addClass("container");
        $(".main-container").removeClass("container-fluid");
        $(".app-header").addClass("hor-header");
        $(".hor-header").removeClass("app-header");
        $(".app-sidebar").addClass("horizontal-main")
        $(".main-sidemenu").addClass("container")
        $('body').removeClass('sidebar-mini');
        $('body').removeClass('sidenav-toggled');
        $('body').removeClass('horizontal-hover');
        $('body').removeClass('default-menu');
        $('body').removeClass('icontext-menu');
        $('body').removeClass('icon-overlay');
        $('body').removeClass('closed-leftmenu');
        $('body').removeClass('double-menu');
        $('body').removeClass('double-menu-tabs');
        $('body').removeClass('hover-submenu');
        $('body').removeClass('hover-submenu1');
        // // To enable no-wrap horizontal style
        $('#slide-left').removeClass('d-none');
        $('#slide-right').removeClass('d-none');
        localStorage.setItem("volghhorizontal", "true");
        localStorage.removeItem("volghsidebarMini");
        localStorage.removeItem("volghhorizontalHover");
        document.querySelector('.horizontal .side-menu')?.classList.add('flex-nowrap')
        // To enable wrap horizontal style
        // $('#slide-left').addClass('d-none');
        // $('#slide-right').addClass('d-none');
        // document.querySelector('.horizontal .side-menu').style.flexWrap = 'wrap'
    }
});

// HORIZONTAL END

// HORIZONTAL HOVER
$(document).on("click", '#myonoffswitch111', function () {
    if (this.checked) {
        if (!document.querySelector('.login-img')) {
            checkHoriMenu();
            responsive();
        }
        setTimeout(() => {
            if (window.innerWidth >= 992) {
                let li = document.querySelectorAll('.side-menu li')
                li.forEach((e, i) => {
                    e.classList.remove('is-expanded')
                })
                var animationSpeed = 300;
                // first level
                var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
                var ul = parent.find('ul:visible').slideUp(animationSpeed);
                ul.removeClass('open');
                var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
                var ul1 = parent1.find('ul:visible').slideUp(animationSpeed);
                ul1.removeClass('open');
            }
        }, 200)
        $('body').addClass('horizontal-hover');
        $('body').addClass('horizontal');
        // $('#slide-left').addClass('d-none');
        // $('#slide-right').addClass('d-none');
        // document.querySelector('.horizontal .side-menu').style.flexWrap = 'wrap'
        $('#slide-left').addClass('d-none');
        $('#slide-right').addClass('d-none');
        document.querySelector('.horizontal .side-menu')?.classList.add('flex-nowrap')
        $(".main-content").addClass("hor-content");
        $(".main-content").removeClass("app-content");
        $(".main-container").addClass("container");
        $(".main-container").removeClass("container-fluid");
        $(".app-header").addClass("hor-header");
        $(".app-header").removeClass("app-header");
        $(".app-sidebar").addClass("horizontal-main")
        $(".main-sidemenu").addClass("container")
        $('body').removeClass('sidebar-mini');
        $('body').removeClass('sidenav-toggled');
        $('body').removeClass('default-menu');
        $('body').removeClass('icontext-menu');
        $('body').removeClass('icon-overlay');
        $('body').removeClass('closed-leftmenu');
        $('body').removeClass('double-menu');
        $('body').removeClass('double-menu-tabs');
        $('body').removeClass('hover-submenu');
        $('body').removeClass('hover-submenu1');
        localStorage.setItem("volghhorizontalHover", "true");
        localStorage.removeItem("volghsidebarMini");
        localStorage.removeItem("volghhorizontal");
    }
});

// HORIZONTAL HOVER END


    /***************** Light Mode *********************/
    function light() {
        "use strict";
        if (document.querySelector('body').classList.contains('light-mode')) {
            $('#myonoffswitch3').prop('checked', true);
            $('#myonoffswitch6').prop('checked', true);
        }
    }
    light();
    /***************** Light Mode *********************/
    let bodyhorizontal = $('body').hasClass('horizontal');
    if (bodyhorizontal) {
        if (!document.querySelector('.login-img')) {
            ActiveSubmenu();
            checkHoriMenu();
            responsive();
        }
        setTimeout(() => {
            if (window.innerWidth >= 992) {
                let li = document.querySelectorAll('.side-menu li')
                li.forEach((e, i) => {
                    e.classList.remove('is-expanded')
                })
                var animationSpeed = 300;
                // first level
                var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
                var ul = parent.find('ul[class^="slide-menu"]:visible').slideUp(animationSpeed);
                ul.removeClass('open');
                var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
                var ul1 = parent1.find('ul[class^="sub-slide-menu"]:visible').slideUp(animationSpeed);
                ul1.removeClass('open');
            }
        }, 200)
        $('body').addClass('horizontal');
        $(".main-content").addClass("hor-content");
        $(".main-content").removeClass("app-content");
        $(".main-container").addClass("container");
        $(".main-container").removeClass("container-fluid");
        $(".app-header").addClass("hor-header");
        $(".hor-header").removeClass("app-header");
        $(".app-sidebar").addClass("horizontal-main")
        $(".main-sidemenu").addClass("container")
        $('body').removeClass('sidebar-mini');
        $('body').removeClass('sidenav-toggled');
        $('body').removeClass('horizontal-hover');
        $('body').removeClass('default-menu');
        $('body').removeClass('icontext-menu');
        $('body').removeClass('icon-overlay');
        $('body').removeClass('closed-leftmenu');
        $('body').removeClass('double-menu');
        $('body').removeClass('double-menu-tabs');
        $('body').removeClass('hover-submenu');
        $('body').removeClass('hover-submenu1');
        // // To enable no-wrap horizontal style
        // $('#slide-left').removeClass('d-none');
        // $('#slide-right').removeClass('d-none');
        // localStorage.setItem("volghhorizontal", "true");
        // localStorage.removeItem("volghsidebarMini");
        // localStorage.removeItem("volghhorizontalHover");
        document.querySelector('.horizontal .side-menu')?.classList.add('flex-nowrap')
        // To enable wrap horizontal style
        // $('#slide-left').addClass('d-none');
        // $('#slide-right').addClass('d-none');
        // document.querySelector('.horizontal .side-menu').style.flexWrap = 'wrap'

    }


     /***************** Light Mode *********************/
    // function light() {
    //     "use strict";
    //     if (document.querySelector('body').classList.contains('light-mode')) {
    //         $('#myonoffswitch3').prop('checked', true);
    //         $('#myonoffswitch6').prop('checked', true);
    //     }
    // }
    // light();
     /***************** Light Mode *********************/

     /***************** Horizontal-Hover has Class *********************/
    let bodyhorizontalHover = $('body').hasClass('horizontal-hover');
    if (bodyhorizontalHover) {
        if (!document.querySelector('.login-img')) {
            checkHoriMenu();
            responsive();
        }
        setTimeout(() => {
            if (window.innerWidth >= 992) {
                let li = document.querySelectorAll('.side-menu li')
                li.forEach((e, i) => {
                    e.classList.remove('is-expanded')
                })
                var animationSpeed = 300;
                // first level
                var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
                var ul = parent.find('ul[class^="slide-menu"]:visible').slideUp(animationSpeed);
                ul.removeClass('open');
                var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
                var ul1 = parent1.find('ul[class^="sub-slide-menu"]:visible').slideUp(animationSpeed);
                ul1.removeClass('open');
            }
        }, 200)
        $('body').addClass('horizontal-hover');
        $('body').addClass('horizontal');
        // $('#slide-left').addClass('d-none');
        // $('#slide-right').addClass('d-none');
        // document.querySelector('.horizontal .side-menu').style.flexWrap = 'wrap'
        $('#slide-left').addClass('d-none');
        $('#slide-right').addClass('d-none');
        document.querySelector('.horizontal .side-menu')?.classList.add('flex-nowrap')
        $(".main-content").addClass("hor-content");
        $(".main-content").removeClass("app-content");
        $(".main-container").addClass("container");
        $(".main-container").removeClass("container-fluid");
        $(".app-header").addClass("hor-header");
        $(".app-header").removeClass("app-header");
        $(".app-sidebar").addClass("horizontal-main")
        $(".main-sidemenu").addClass("container")
        $('body').removeClass('sidebar-mini');
        $('body').removeClass('sidenav-toggled');
        $('body').removeClass('default-menu');
        $('body').removeClass('icontext-menu');
        $('body').removeClass('icon-overlay');
        $('body').removeClass('closed-leftmenu');
        $('body').removeClass('hover-submenu');
        $('body').removeClass('hover-submenu1');
        $('body').removeClass('double-menu');
        $('body').removeClass('double-menu-tabs');
        // localStorage.setItem("volghhorizontalHover", "true");
        // localStorage.removeItem("volghsidebarMini");
        // localStorage.removeItem("volghhorizontal");
    }
    /***************** Horizontal has Class *********************/

    /***************** RTL has Class *********************/
    let bodyRtl = $('body').hasClass('rtl');
    if (bodyRtl) {
        $('body').addClass('rtl');
        $('#slide-left').removeClass('d-none');
        $('#slide-right').removeClass('d-none');
        $("html[lang=en]").attr("dir", "rtl");
        $('body').removeClass('ltr');
        $("head link#style").attr("href", $(this));
        (document.getElementById("style").setAttribute("href", "http://127.0.0.1:8000/build/assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
        var carousel = $('.owl-carousel');
        $.each(carousel, function (index, element) {
            // element == this
            var carouselData = $(element).data('owl.carousel');
            carouselData.settings.rtl = true; //don't know if both are necessary
            carouselData.options.rtl = true;
            $(element).trigger('refresh.owl.carousel');
        });
    }
    /***************** RTL has Class *********************/

    /***************** CLOSEDMENU has Class *********************/
    let bodyclosed = $('body').hasClass('closed-leftmenu');
    if (bodyclosed) {
        $('body').addClass('closed-leftmenu');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            hovermenu();
        }
        $('body').addClass('sidenav-toggled');
    }
    /***************** CLOSEDMENU has Class *********************/

    /***************** ICONTEXT MENU has Class *********************/
    let bodyicontext = $('body').hasClass('icontext-menu');
    if (bodyicontext) {
        $('body').addClass('icontext-menu');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            icontext();
        }
        $('body').addClass('sidenav-toggled');
    }
    /***************** ICONTEXT MENU has Class *********************/

    /***************** ICONOVERLAY MENU has Class *********************/
    let bodyiconoverlay = $('body').hasClass('icon-overlay');
    if (bodyiconoverlay) {
        $('body').addClass('icon-overlay');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            hovermenu();
            iconoverlay();
        }
        $('body').addClass('sidenav-toggled');
    }
    /***************** ICONOVERLAY MENU has Class *********************/

    /***************** HOVER-SUBMENU has Class *********************/
    let bodyhover = $('body').hasClass('hover-submenu');
    if (bodyhover) {
        $('body').addClass('hover-submenu');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            hovermenu();
        }
        $('body').addClass('sidenav-toggled');
    }
    /***************** HOVER-SUBMENU has Class *********************/

    /***************** HOVER-SUBMENU 1 has Class *********************/
    let bodyhover1 = $('body').hasClass('hover-submenu1');
    if (bodyhover1) {
        $('body').addClass('hover-submenu1');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            hovermenu();
        }
        $('body').addClass('sidenav-toggled');
    }
    /***************** HOVER-SUBMENU 1 has Class *********************/

    /***************** Double-Menu has Class *********************/
    let bodydoublemenu = $('body').hasClass('double-menu');
    if (bodydoublemenu) {
        $('body').addClass('double-menu');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            doublemenu();
            $('body').removeClass('sidenav-toggled-open');
        }
    }
    /***************** Double-Menu has Class *********************/

    /***************** Double-Menu-Tabs has Class *********************/
    let bodydoublemenutabs = $('body').hasClass('double-menu-tabs');
    if (bodydoublemenutabs) {
        $('body').addClass('double-menu-tabs');
        if (!(document.querySelector('body').classList.contains('login-img'))) {
            doublemenu();
            $('body').removeClass('sidenav-toggled-open');
        }
    }
    /***************** Double-Menu-Tabs has Class *********************/

// CHECK OPTIONS
function checkOptions() {
    "use strict";
    // rtl
    if (document.querySelector('body').classList.contains('rtl')) {
        $('#myonoffswitch24').prop('checked', true);
    }
    // horizontal
    if (document.querySelector('body').classList.contains('horizontal')) {
        $('#myonoffswitch35').prop('checked', true);
    }
    // horizontal-hover
    if (document.querySelector('body').classList.contains('horizontal-hover')) {
        $('#myonoffswitch111').prop('checked', true);
    }

    // light header 
    if (document.querySelector('body').classList.contains('header-light')) {
        $('#myonoffswitch6').prop('checked', true);
    }
    // color header 
    if (document.querySelector('body').classList.contains('color-header')) {
        $('#myonoffswitch7').prop('checked', true);
    }
    // dark header 
    if (document.querySelector('body').classList.contains('dark-header')) {
        $('#myonoffswitch8').prop('checked', true);
    }

    // light menu
    if (document.querySelector('body').classList.contains('light-menu')) {
        $('#myonoffswitch3').prop('checked', true);
    }
    // color menu
    if (document.querySelector('body').classList.contains('color-menu')) {
        $('#myonoffswitch4').prop('checked', true);
    }
    // dark menu
    if (document.querySelector('body').classList.contains('dark-menu')) {
        $('#myonoffswitch5').prop('checked', true);
    }
    // dark-mode
    if (document.querySelector('body').classList.contains('dark-mode')) {
        $('#myonoffswitch2').prop('checked', true);
    }
    // icontext-menu
    if (document.querySelector('body').classList.contains('icontext-menu')) {
        $('#myonoffswitch14').prop('checked', true);
    }
    // icon-overlay
    if (document.querySelector('body').classList.contains('icon-overlay')) {
        $('#myonoffswitch15').prop('checked', true);
    }
    // closed-leftmenu
    if (document.querySelector('body').classList.contains('closed-leftmenu')) {
        $('#myonoffswitch16').prop('checked', true);
    }
    // closed-leftmenu
    if (document.querySelector('body').classList.contains('closed-leftmenu')) {
        $('#myonoffswitch16').prop('checked', true);
    }
    // hover-submenu
    if (document.querySelector('body').classList.contains('hover-submenu')) {
        $('#myonoffswitch17').prop('checked', true);
    }
    // hover-submenu1
    if (document.querySelector('body').classList.contains('hover-submenu1')) {
        $('#myonoffswitch18').prop('checked', true);
    }
    // hover-submenu1
    if (document.querySelector('body').classList.contains('double-menu')) {
        $('#myonoffswitch19').prop('checked', true);
    }
    // hover-submenu1
    if (document.querySelector('body').classList.contains('double-menu-tabs')) {
        $('#myonoffswitch20').prop('checked', true);
    }
    // layout-boxed
    if (document.querySelector('body').classList.contains('layout-boxed')) {
        $('#myonoffswitch10').prop('checked', true);
    }
    // scrollable-layout
    if (document.querySelector('body').classList.contains('scrollable-layout')) {
        $('#myonoffswitch12').prop('checked', true);    
    }
    // center-logo
    if (document.querySelector('body').classList.contains('center-logo')) {
        $('#center-logo').prop('checked', true);
    }
    // default-logo
    if (document.querySelector('body').classList.contains('default-logo')) {
        $('#default-logo').prop('checked', true);
    }
}
checkOptions();

// RESET SWITCHER TO DEFAULT
let reset = document.querySelector('#resetAll');
if (reset) {
    reset.addEventListener('click', () => {
        resetData();
    })
}

// RESET SWITCHER TO DEFAULT
function resetData() {
    "use strict";
	localStorage.clear();
    document.querySelector("html").style=""

    $('#myonoffswitch3').prop('checked', true);
    $('#myonoffswitch6').prop('checked', true);
    $('#myonoffswitch1').prop('checked', true);
    $('#myonoffswitch9').prop('checked', true);
    $('#myonoffswitch11').prop('checked', true);
    $('#myonoffswitch13').prop('checked', true);
    $('#myonoffswitch34').prop('checked', true);
    $('#myonoffswitch23').prop('checked', true);
    $('body')?.removeClass('dark-mode');
    $('body')?.removeClass('light-menu');
    $('body')?.removeClass('dark-menu');
    $('body')?.removeClass('color-menu');
    $('body')?.removeClass('header-light');
    $('body')?.removeClass('dark-header');
    $('body')?.removeClass('color-header');
    $('body')?.removeClass('layout-boxed');
    $('body')?.removeClass('icontext-menu');
    $('body')?.removeClass('icon-overlay');
    $('body')?.removeClass('closed-leftmenu');
    $('body')?.removeClass('hover-submenu');
    $('body')?.removeClass('double-menu');
    $('body')?.removeClass('double-menu-tabs');
    $('body')?.removeClass('hover-submenu1');
    $('body')?.removeClass('sidenav-toggled');
    $('body')?.removeClass('scrollable-layout');
    $('body')?.removeClass('rtl');
    $('body')?.addClass('ltr');
    names();

    document.querySelector('html').setAttribute("dir", "ltr");

    // resetting horizontal to vertical
    $('body').removeClass('horizontal');
    $('body').removeClass('horizontal-hover');
    $(".main-content").removeClass("hor-content");
    $(".main-content").addClass("app-content");
    $(".main-container").removeClass("container");
    $(".main-container").addClass("container-fluid");
    $(".app-header").removeClass("hor-header");
    $(".hor-header").addClass("app-header");
    $(".app-sidebar").removeClass("horizontal-main")
    $(".app-sidebar").removeClass("fixed-header")
    $(".main-sidemenu").removeClass("container")
    $(".slide-menu").removeClass("ps")
    $(".slide-menu").removeClass("ps--active-y")
    $('#slide-left').removeClass('d-none');
    $('#slide-right').removeClass('d-none');
    $('body').addClass('sidebar-mini');

    if (!document.querySelector('body').classList.contains('login-img')) {
        responsive();
        ActiveSubmenu();
    }

    $("head link#style").attr("href", $(this));
    (document.getElementById("style").setAttribute("href", "http://127.0.0.1:8000/build/assets/plugins/bootstrap/css/bootstrap.min.css"));
    localStorage.clear();
    if (!document.body.classList.contains('login-img')) {
        let mainHeader = document.querySelector('.app-header');
        mainHeader.style = "";
        let appSidebar = document.querySelector('.app-sidebar');
        appSidebar.style = "";

        //
        checkOptions();
        menuClick();
    }
}
