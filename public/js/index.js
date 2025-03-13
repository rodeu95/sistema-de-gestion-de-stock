document.addEventListener("DOMContentLoaded", function() {
    var chartElement = document.getElementById('ventasChart');

    if (chartElement) {  // Verifica si el elemento existe
        // Obtener los datos de los atributos data-*
        var labels = JSON.parse(chartElement.getAttribute('data-labels'));
        var data = JSON.parse(chartElement.getAttribute('data-data'));

        // Configurar y crear el gráfico
        var ctx = chartElement.getContext('2d');
        var ventasChart = new Chart(ctx, {
            type: 'bar', // O el tipo de gráfico que prefieras
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                animation: {
                    duration: 1500,  // Duración de la animación en milisegundos
                    easing: 'easeInOutBounce' // Efecto de animación
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        console.log('El elemento ventasChart no está presente en esta vista.');
    }

    var pieChart = document.getElementById('topProductosChart');

    if(pieChart){
        var labels = JSON.parse(pieChart.getAttribute('data-labels'));
        var data = JSON.parse(pieChart.getAttribute('data-data'));

        var ctx = pieChart.getContext('2d');
        const topProductosChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56'], // Colores para cada segmento
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }else{
        console.log('El elemento pieChart no está presente en esta vista.');
    }

});


window.onload = function() {
    const alertMessage = document.getElementById('alert-box');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.display = 'none';
        }, 5000); // Cambia 5000 por el tiempo en milisegundos que desees
    }
};