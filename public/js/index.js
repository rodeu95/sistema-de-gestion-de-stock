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
});


window.onload = function() {
    const alertMessage = document.getElementById('alert-box');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.display = 'none';
        }, 5000); // Cambia 5000 por el tiempo en milisegundos que desees
    }
};