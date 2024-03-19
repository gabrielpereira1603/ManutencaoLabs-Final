// Função para abrir o modal de edição de componentes
function openComponentesModal(codReclamacao, componentesSelecionados) {
    // Realiza o fetch para obter todos os componentes
    fetch('https://somosdevteam.com/SMI/api/v1/Componente')
        .then(response => response.json())
        .then(data => {
            // Limpa o container de checkboxes
            const container = document.getElementById('componentesCheckboxContainer');
            container.innerHTML = '';

            // Adiciona um checkbox para cada componente
            data.forEach(componente => {
                const checkboxDiv = document.createElement('div');
                checkboxDiv.classList.add('col-6', 'col-lg-4'); // Adiciona classes de grade do Bootstrap

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'componentes';
                checkbox.value = componente.codcomponente;
                checkbox.id = `componente_${componente.codcomponente}`;
                checkbox.className = 'form-check-input';

                const label = document.createElement('label');
                label.htmlFor = `componente_${componente.codcomponente}`;
                label.className = 'form-check-label';
                label.innerText = componente.nome_componente;

                checkboxDiv.appendChild(checkbox);
                checkboxDiv.appendChild(label);
                container.appendChild(checkboxDiv);

                // Verifica se o componente está na lista de componentes selecionados
                if (componentesSelecionados.includes(componente.codcomponente)) {
                    checkbox.checked = true;
                }
            });

            // Abre o modal de edição de componentes
            $('#editarComponentesModal').modal('show');
        })
        .catch(error => console.error('Erro ao buscar dados da API:', error));
}

// Adiciona um ouvinte de evento para o botão de editar em cada reclamação
$('.btn-editar').on('click', function () {
// Obtém o código da reclamação associada ao botão
const codReclamacao = $(this).closest('tr').find('td:first').text();

// Obtém os componentes selecionados pelo usuário na reclamação
fetch(`https://somosdevteam.com/SMI/api/v1/ComponenteReclamacao/${codReclamacao}`)
    .then(response => response.json())
    .then(data => {
        const componentesSelecionados = data.map(componente => componente.codcomponente);
        // Abre o modal de edição de componentes, passando os componentes selecionados
        openComponentesModal(codReclamacao, componentesSelecionados);
    })
    .catch(error => console.error('Erro ao buscar dados da API:', error));
});

// Adiciona um ouvinte de evento para o submit do formulário de edição
$('#formEditarLembrete').on('submit', function (event) {
    // Obtém os IDs dos componentes selecionados
    const componentesSelecionados = [];
    $('input[name="componentes"]:checked').each(function () {
        componentesSelecionados.push($(this).val());
    });

    // Atualiza o valor do input hidden com os IDs dos componentes selecionados
    $('#componentesSelecionados').val(componentesSelecionados.join(','));

    // Continua com o envio do formulário normalmente
    return true;
});

// Adiciona um ouvinte de evento para o botão "Cancelar"
$('.btn-cancelar').on('click', function () {
    $('#editarComponentesModal').modal('hide'); // Fecha o modal
});
