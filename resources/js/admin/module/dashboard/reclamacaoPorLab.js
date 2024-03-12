document.addEventListener('DOMContentLoaded', function () {
    fetch('https://somosdevteam.com/SMI/api/v1/reclamacaoPorLab')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('myChart-ReclamacaoLaboratorio');
        const labels = data.map(item => item.numerolaboratorio);
        const dataPoints = data.map(item => item.total_reclamacoes);

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reclamações por Laboratório',
                    data: dataPoints,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => console.error('Erro ao buscar dados da API:', error));
});