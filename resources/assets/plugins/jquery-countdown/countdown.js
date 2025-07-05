$(function() {
    var austDay = new Date("september 28, 2024");
    $('#launch_date').countdown({
        until: austDay,
        layout: '<ul class="countdown"><li><span class="number col-2 text-primary">{dn}<\/span><br/><span class="time col-2 text-primary fw-semibold">{dl}<\/span><\/li><li><span class="number col-2 text-primary">{hn}<\/span><br/><span class="time col-2 text-primary fw-semibold">{hl}<\/span><\/li><li><span class="number col-2 text-primary">{mn}<\/span><br/><span class="time col-2 text-primary fw-semibold">{ml}<\/span><\/li><li><span class="number col-2 text-primary">{sn}<\/span><br/><span class="time col-2 text-primary fw-semibold">{sl}<\/span><\/li><\/ul>'
    });
    $('#year').text(austDay.getFullYear());
});

