$(document).ready(function () {
    // Inicialize o Select2 com marcação habilitada
    $('#select-usuario').select2({
        tags: true
    });

    // Adicione um ouvinte de evento para o evento "select2:select", que é acionado quando um item é selecionado no Select2
    $('#select-usuario').on('select2:select', function (e) {
       var selectedUsuario = this.value; // Obtém o ID do usuário selecionado

       if(selectedUsuario === "") {
            document.getElementById("login-user").value = "";
        }else {
            fetch('http://localhost/estudo-mvc/api/v1/user/' + selectedUsuario)
            .then(response => response.json())
            .then(jsonResponse => {
                // Verifica se o login está definido no objeto antes de atribuir ao campo
                if (jsonResponse[0].login !== undefined) {
                    document.getElementById("login-user").value = jsonResponse[0].login;
                }
            });
         }
    });
});
