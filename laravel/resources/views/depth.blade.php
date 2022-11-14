<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <style media="screen">
            #container {
                min-width: 310px;
                max-width: 1040px;
                height: 400px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div id="container"></div>
        <script type="text/javascript">
            Highcharts.chart('container', {
                chart: {
                    type: 'area',
                    zoomType: 'xy'
                },
                title: {
                    text: 'ETH-BTC Market Depth'
                },
                xAxis: {
                    minPadding: 0,
                    maxPadding: 0,
                    plotLines: [{
                        color: '#888',
                        value: {{ $actual_price }},
                        width: 1,
                        label: {
                            text: 'Actual price',
                            rotation: 90
                        }
                    }],
                    title: {
                        text: 'Price'
                    }
                },
                yAxis: [{
                    lineWidth: 1,
                    gridLineWidth: 1,
                    title: null,
                    tickWidth: 1,
                    tickLength: 5,
                    tickPosition: 'inside',
                    labels: {
                        align: 'left',
                        x: 8
                    }
                }, {
                    opposite: true,
                    linkedTo: 0,
                    lineWidth: 1,
                    gridLineWidth: 0,
                    title: null,
                    tickWidth: 1,
                    tickLength: 5,
                    tickPosition: 'inside',
                    labels: {
                        align: 'right',
                        x: -8
                    }
                }],
                legend: {
                    enabled: false
                },
                plotOptions: {
                    area: {
                        fillOpacity: 0.2,
                        lineWidth: 1,
                        step: 'center'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size=10px;">Price: {point.key}</span><br/>',
                    valueDecimals: 2
                },
                series: [
                    {
                        name: 'Bids',
                        data: [
                            @foreach ($bids as $bid)
                                [{{$bid[0]}}, {{$bid[1]}}],
                            @endforeach
                        ],
                        color: '#03a7a8'
                    },
                    {
                        name: 'Asks',
                        data: [
                            @foreach ($asks as $ask)
                                [{{$ask[0]}}, {{$ask[1]}}],
                            @endforeach
                        ],
                         color: '#fc5857'
                    }
                ]
            });
        </script>
    </body>
</html>
