$(document).ready(function () {
    // Inicialize o Select2 com marcação habilitada
    $('#select-user-relatorioManutencao').select2({
        tags: true
    });
    
    // Adicione o evento change para o select de laboratório
    $('#laboratorio').on('change', function() {
        var selectedLaboratorio = $(this).val();

        if (selectedLaboratorio === "-1") {
            $('#computador').html("<option value='-1' selected>Todos os computadores</option>");
        } else if (selectedLaboratorio >= 1) {
            // /api/v1/computador/{codlaboratorio}
            // Carregue os computadores do laboratório selecionado
            fetch('http://localhost/ManutencaoLabs-Final/api/v1/computador/' + selectedLaboratorio)
            .then(response => response.json())
            .then(jsonResponse => {
                var selectComputador = $('#computador');
                selectComputador.prop('disabled', false);
                selectComputador.html("<option value='-2'>Todos os computadores desse laboratório</option>");
                jsonResponse.forEach(function (computador) {
                    selectComputador.append($('<option>', {
                        value: computador.codcomputador,
                        text: computador.patrimonio
                    }));
                });
            });
        } else if (selectedLaboratorio == "") {
            $('#computador').prop('disabled', false);
            $('#computador').html("<option value=''>Selecione um laboratório primeiro</option>");
        }
    });
});
