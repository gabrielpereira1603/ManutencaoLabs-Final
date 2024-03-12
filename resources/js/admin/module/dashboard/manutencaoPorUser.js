document.addEventListener('DOMContentLoaded', function () {
    fetch('https://somosdevteam.com/SMI/api/v1/manutencaoPorUser')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('myChart-ReclamacaoUsuario');
        const labels = data.map(item => item.nome_usuario);
        const dataPoints = data.map(item => item.total_manutencoes);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total de Manutenções por Usuário',
                    data: dataPoints,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Cor de fundo da área abaixo da linha
                    borderColor: 'rgba(255, 99, 132, 1)', // Cor da linha
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)', // Cor dos pontos
                    pointBorderColor: 'rgba(255, 99, 132, 1)', // Cor da borda dos pontos
                    pointRadius: 5, // Raio dos pontos
                    pointHoverRadius: 7 // Raio dos pontos ao passar o mouse
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false // Iniciar eixo y a partir de zero
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => console.error('Erro ao buscar dados da API:', error));
});
