function renderTemperatureSparklineById(id, height, width)
{
    return $(id).sparkline('html', {
        type: 'line',
        width: width,
        height: height,
        lineColor: '#BF0000',
        fillColor: '#FFAAAA',
        lineWidth: 1,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        spotRadius: 0,
        chartRangeMin: 0,
        chartRangeMax: 40
    });
}

function renderHumiditySparklineById(id, height, width)
{
    return $(id).sparkline('html', {
        type: 'line',
        width: width,
        height: height,
        lineColor: '#0000BF',
        fillColor: '#AAAAFF',
        lineWidth: 1,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        spotRadius: 0,
        chartRangeMin: 0,
        chartRangeMax: 100
    });
}