document.addEventListener("DOMContentLoaded", function(event) {
   
  const showNavbar = (toggleId, navId, bodyId, headerId) =>{
    const toggle = document.getElementById(toggleId),
    nav = document.getElementById(navId),
    bodypd = document.getElementById(bodyId),
    headerpd = document.getElementById(headerId)
  
    // Validate that all variables exist
    if(toggle && nav && bodypd && headerpd){
      toggle.addEventListener('click', ()=>{
        // show navbar
        nav.classList.toggle('show2')
        // change icon
        toggle.classList.toggle('bx-x')
        // add padding to body
        bodypd.classList.toggle('body-pd')
        // add padding to header
        headerpd.classList.toggle('body-pd')
      })
    }
  }
  
  showNavbar('header-toggle','nav-bar','body-pd','header')
  
  /*===== LINK ACTIVE =====*/
  const linkColor = document.querySelectorAll('.nav_link')
  
  function colorLink(){
  if(linkColor){
  linkColor.forEach(l=> l.classList.remove('active'))
  this.classList.add('active')
  }
  }
  linkColor.forEach(l=> l.addEventListener('click', colorLink))
});

const logoutLink = document.getElementById('logoutLink');

// Adiciona um ouvinte de evento de clique ao link de logout
logoutLink.addEventListener('click', function(event) {
    // Previne o comportamento padrão do link (navegar para a URL)
    event.preventDefault();

    // Exibe o SweetAlert para confirmar o logout
    Swal.fire({
        title: "Tem certeza?",
        text: "Você sera desconectado!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, Sair!"
    }).then((result) => {
        // Se o usuário confirmar o logout
        if (result.isConfirmed) {
            // Redireciona para a rota de logout
            window.location.href = logoutLink.href; // A URL do link de logout
        }
    });
});

