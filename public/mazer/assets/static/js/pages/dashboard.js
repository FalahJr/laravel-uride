var optionsProfileVisit = {
    annotations: {
        position: "back",
    },
    dataLabels: {
        enabled: false,
    },
    chart: {
        type: "bar",
        height: 300,
    },
    fill: {
        opacity: 1,
    },
    plotOptions: {},
    series: [
        {
            name: "sales",
            data: [9, 20, 30, 20, 10, 20, 30, 20, 10, 20, 30, 20],
        },
    ],
    colors: "#435ebe",
    xaxis: {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
    },
};
let optionsVisitorsProfile = {
    series: [70, 30],
    labels: ["Male", "Female"],
    colors: ["#435ebe", "#55c6e8"],
    chart: {
        type: "donut",
        width: "100%",
        height: "350px",
    },
    legend: {
        position: "bottom",
    },
    plotOptions: {
        pie: {
            donut: {
                size: "30%",
            },
        },
    },
};

var chartProfileVisit = new ApexCharts(
    document.querySelector("#chart-profile-visit"),
    optionsProfileVisit
);
var chartVisitorsProfile = new ApexCharts(
    document.getElementById("chart-visitors-profile"),
    optionsVisitorsProfile
);

chartProfileVisit.render();
chartVisitorsProfile.render();
