// document.getElementById("select-usuario").addEventListener("change", function () {
//     var selectedUsuario = this.value;
//     var selectedAcesso = document.getElementById("select-acesso").value;

//     if(selectedUsuario === "") {
//         document.getElementById("login-user").value = "";
//         document.getElementById("select-acesso").value = "";
//     }else {
//         fetch('?router=UsuarioController/getLogin&codUsuario=' + selectedUsuario)
//         .then(response => response.json())
//         .then(jsonResponse => {
//             // Verifica se o login está definido no objeto antes de atribuir ao campo
//             if (jsonResponse[0].login !== undefined) {
//                 document.getElementById("login-user").value = jsonResponse[0].login;
//             }
//         });
//     }
// });