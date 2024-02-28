function showAlert(messageType, messageText) {
    let icon, title;

    if (messageType === 'success') {
        icon = 'success';
        title = 'Success!';
    } else if (messageType === 'error') {
        icon = 'error';
        title = 'Error!';
    }

    Swal.fire({
        position: 'center',
        icon: icon,
        title: title,
        text: messageText,
        showConfirmButton: false,
        timer:  1500
    }).then(() => {
        // Clear the URL parameters
        const url = new URL(window.location.href);
        url.searchParams.delete(messageType);
        window.history.replaceState({}, document.title, url);
    });
}

// Example usage
const urlParams = new URLSearchParams(window.location.search);
const successParam = urlParams.get('success');
const errorParam = urlParams.get('error');

if (successParam === 'add') {
    showAlert('success', 'Usuário cadastrado com sucesso!');
}

if (errorParam === 'not') {
    showAlert('error', 'Usuário não foi cadastrado com sucesso!');
}

if (successParam === 'permissaoAdd') {
    showAlert('success', 'Permissão alterada com sucesso!');
}

if (errorParam === 'permissaoNot') {
    showAlert('error', 'Erro ao alterar a permissão!');
}

if (successParam === 'manutencaoAdd') {
    showAlert('success', 'Manutenção finalizada com sucesso!');
}

if (errorParam === 'manuntencaoNot') {
    showAlert('error', 'Erro ao finalizar a manutenção');
}

if (successParam === 'reclamacaoAdd') {
    showAlert('success', 'Reclamação finalizada com sucesso!');
}

if (errorParam === 'reclamacaoNot') {
    showAlert('error', 'Erro ao finalizar a Reclamação');
}