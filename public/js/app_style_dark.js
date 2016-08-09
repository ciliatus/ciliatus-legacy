function renderTemperatureSparklineById(id, height, width)
{
    return $(id).sparkline('html', {
        type: 'line',
        width: width,
        height: height,
        lineColor: '#CC5555',
        fillColor: false,
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        spotRadius: 0,
        chartRangeMin: 0,
        chartRangeMax: 40,
        disableHiddenCheck: true,
    });
}

function renderHumiditySparklineById(id, height, width)
{
    return $(id).sparkline('html', {
        type: 'line',
        width: width,
        height: height,
        lineColor: '#5555EE',
        fillColor: false,
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        spotRadius: 0,
        chartRangeMin: 0,
        chartRangeMax: 100,
        disableHiddenCheck: true,
    });
}



$(document).ready(function() {
    // Switchery
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A',
                secondaryColor: '#555555'
            });
        });
    }
});