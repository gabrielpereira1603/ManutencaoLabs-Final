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
        timer: 1500
    }).then(() => {
        // Clear the URL parameters
        const url = new URL(window.location.href);
        url.searchParams.delete(messageType);
        window.history.replaceState({}, document.title, url);
    });
}

// Mapeamento de parâmetros de URL para tipos de mensagens e textos
const messageMap = {
    'add': { messageType: 'success', messageText: 'Usuário cadastrado com sucesso!' },
    'not': { messageType: 'error', messageText: 'Usuário não foi cadastrado com sucesso!' },
    'permissaoAdd': { messageType: 'success', messageText: 'Permissão alterada com sucesso!' },
    'permissaoNot': { messageType: 'error', messageText: 'Erro ao alterar a permissão!' },
    'manutencaoAdd': { messageType: 'success', messageText: 'Manutenção finalizada com sucesso!' },
    'manuntencaoNot': { messageType: 'error', messageText: 'Erro ao finalizar a manutenção' },
    'reclamacaoAdd': { messageType: 'success', messageText: 'Reclamação finalizada com sucesso!' },
    'reclamacaoNot': { messageType: 'error', messageText: 'Erro ao finalizar a Reclamação' },
    'apiError': { messageType: 'error', messageText: 'Perca da conexão com a API!' },
    'prenchaLogin': { messageType: 'error', messageText: 'Prencha o login para alterar as informações!'}, 
};

// Verifica os parâmetros de URL e exibe o alerta correspondente
const urlParams = new URLSearchParams(window.location.search);
for (let [key, value] of urlParams.entries()) {
    if (messageMap[value]) {
        showAlert(messageMap[value].messageType, messageMap[value].messageText);
    }
}
