// Verifica se há um parâmetro 'success' na URL
const urlParams = new URLSearchParams(window.location.search);
const successParam = urlParams.get('success');
const errorParam = urlParams.get('error');

// Se 'success' for igual a '1', exibe o alerta de sucesso e limpa o parâmetro da URL
if (successParam === '1') {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Reclamação realizada com sucesso!',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        // Limpa o parâmetro 'success' da URL
        const url = new URL(window.location.href);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);
    });
} if (errorParam == '1') {
    Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Reclamação não foi realizada com sucesso!',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        // Limpa o parâmetro 'success' da URL
        const url = new URL(window.location.href);
        url.searchParams.delete('error');
        window.history.replaceState({}, document.title, url);
    });
} else {
    
}