function getChartColorsArray(e) {
    e = $(e).attr("data-colors");
    return (e = JSON.parse(e)).map(function (e) {
        e = e.replace(" ", "");
        if (-1 == e.indexOf("--"))
            return e;
        e = getComputedStyle(document.documentElement).getPropertyValue(e);
        return e || void 0
    })
}
const randomNumber = (maxNumber) => Math.floor(Math.random() * maxNumber);


var barColors = getChartColorsArray("#bar_chart")
    , options = {
        chart: {
            height: 300,
            type: "bar",
            toolbar: {
                show: !1
            }
        },
        plotOptions: {
            bar: {
                horizontal: !0
            }
        },
        dataLabels: {
            enabled: !1
        },
        series: [{
            data: [
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501),
                randomNumber(1501)
            ]
        }],
        colors: barColors,
        grid: {
            borderColor: "#f1f1f1"
        },
        xaxis: {
            categories: [
                "Suc.Cordoba",
                "Suc. Jujuy",
                "Suc. Ciudad Autonoma de Buenos Aires",
                "Suc. La Pampa",
                "Suc. Misiones",
                "Suc. Corrientes",
                "Suc. La Rioja",
                "Suc. San Juan",
                "Suc. Santa Cruz",
                "Suc. Tierra del Fuego"
            ]
        }
    };
(chart = new ApexCharts(document.querySelector("#bar_chart"), options)).render();

var pieColors = getChartColorsArray("#pie_chart")
  , options = {
    chart: {
        height: 300,
        type: "pie"
    },
    series: [
        randomNumber(100),
        randomNumber(100),
        randomNumber(100),
        randomNumber(100),
        randomNumber(100)
    ],
    labels: ["AURICULAR", "CABLE USB", "CARGADOR", "CORTADORA", "GLASS"],
    colors: pieColors,
    legend: {
        show: !0,
        position: "left",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: !1,
        fontSize: "14px",
        offsetX: 0
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: !1
            }
        }
    }]
};
(chart = new ApexCharts(document.querySelector("#pie_chart"),options)).render();
