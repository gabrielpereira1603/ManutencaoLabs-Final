$(document).ready(function () {
    // Inicialize o Select2 com marcação habilitada
    $('#select-alterarUser').select2({
        tags: true
    });

    $('#select-alterarUser').on('select2:select', function (e) {
        var selectedUsuario = this.value;
     
        if(selectedUsuario === "") {
             document.getElementById("login").value = "";
             document.getElementById("email").value = "";
             document.getElementById("nome-input").value = "";
         } else {
            fetch('https://somosdevteam.com/SMI/api/v1/user/' + selectedUsuario)
            .then(response => response.json())
            .then(jsonResponse => {
                // console.log(jsonResponse)
                if (jsonResponse[0].login !== undefined) {
                    document.getElementById("login").value = jsonResponse[0].login;
                }
                if (jsonResponse[0].email_usuario !== undefined) {
                    document.getElementById("email").value = jsonResponse[0].email_usuario;
                }
                if (jsonResponse[0].nome_usuario !== undefined) {
                    document.getElementById("nome-input").value = jsonResponse[0].nome_usuario;
                }
            });
        }
    });
});
