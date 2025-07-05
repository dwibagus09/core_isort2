(function($) {
    "use strict";

    //colored tooltip
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-primary"]'), {
        template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    })
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-secondary"]'), {
        template: '<div class="tooltip tooltip-secondary" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    })
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-success"]'), {
        template: '<div class="tooltip tooltip-success" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    })
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-info"]'), {
        template: '<div class="tooltip tooltip-info" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    })

})(jQuery);

//popover
$(function () {
	'use strict'
	//Colored Head Popover
    var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="head-primary"]'), {
        template: '<div class="popover popover-head-primary" role="tooltip"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
    })
    var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="head-secondary"]'), {
        template: '<div class="popover popover-head-secondary" role="tooltip"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
    })

    //Full Colored Popover
    var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="primary"]'), {
        template: '<div class="popover popover-primary" role="tooltip"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
    })
    var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="secondary"]'), {
        template: '<div class="popover popover-secondary" role="tooltip"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
    })
});