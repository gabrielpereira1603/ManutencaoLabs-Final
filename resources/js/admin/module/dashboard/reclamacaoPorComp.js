document.addEventListener('DOMContentLoaded', function () {
    fetch('https://somosdevteam.com/SMI/api/v1/reclamacaoPorComp')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('myChart-ReclamacaoComponente');
        const labels = data.map(item => item.nome_componente);
        const dataPoints = data.map(item => item.total_reclamacoes);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reclamações por Componente',
                    data: dataPoints,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Cor de fundo das barras
                    borderColor: 'rgba(54, 162, 235, 1)', // Cor da borda das barras
                    borderWidth: 1,
                    borderRadius: 20 // Define o raio das bordas
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => console.error('Erro ao buscar dados da API:', error));
});
