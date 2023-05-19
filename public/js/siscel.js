const elementoMensagem = document.getElementById('mensagem');
if (elementoMensagem) {
    const toastMensagem = new bootstrap.Toast(elementoMensagem);
    toastMensagem.show();
}

const form = document.getElementById('form');
const tabelaPessoas = new DataTable('#listagem', {
    "searching": false,
    "ordering": false,
    "lengthChange": false,
    "paging": true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
    },
});


const tabelaEleitores = new DataTable('#eleitores', {
    "searching": false,
    "ordering": false,
    "lengthChange": false,
    "paging": true,
    "language": {
        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
    },
});

function enviarLink(link) {
    // Enviar a solicitação AJAX
    fetch(link, {
        method: 'GET'
    })
        .then(response => {
            if (response.ok) {
                // Exibir a mensagem de sucesso
                const mensagem = 'Link enviado com sucesso.';
                const corpoRetorno = document.getElementById('corpo-retorno');
                if (corpoRetorno) {
                    corpoRetorno.innerText = mensagem;
                    const toastRetorno = new bootstrap.Toast(document.getElementById('retorno-email'));
                    toastRetorno.show();
                }
            } else {
                throw new Error('Erro ao enviar link.');
            }
        })
        .catch(error => {
            // Exibir a mensagem de erro
            const mensagem = error.message;
            const corpoRetorno = document.getElementById('corpo-retorno');
            if (corpoRetorno) {
                corpoRetorno.innerText = mensagem;
                const toastRetorno = new bootstrap.Toast(document.getElementById('retorno-email'));
                toastRetorno.show();
            }
        });
}


/*function enviarLink(link) {
    // Enviar a solicitação AJAX
    fetch(link, {
        method: 'GET'
    })
        .then(response => {
            if (response.ok) {
                // Exibir a mensagem de sucesso
                $('#corpo-retorno').innerHTML('Link enviado com sucesso.');
                $('#retorno-email').toast('show');
            } else {
                throw new Error('Erro ao enviar link.');
            }
        })
        .catch(error => {
            // Exibir a mensagem de erro
            $('#corpo-retorno').html(error.message);
            $('#retorno-email').toast('show');
        });
}*/



function selectLinhas(linhas) {
    url = "eleicao/eleitor/index.php?id={CODELEICAO}&l="  + linhas;
    window.location.href = url;
}

function TestaCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
    if (strCPF == "00000000000") return false;

    for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

    Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
}
//var strCPF = "12345678909";
//alert(TestaCPF(strCPF));

// document.addEventListener("DOMContentLoaded", () => {
//     const btnAbrirModal = document.querySelector("#btn-abrir-modal");
//     btnAbrirModal.addEventListener("click", () => {
//         const codConsulta = "codigo-da-consulta";
//         const codChapa = "codigo-da-chapa";
//         fetch(`/consulta/${codConsulta}/chapa/${codChapa}`)
//             .then((response) => response.json())
//             .then((data) => {
//                 const inputNumeroChapa = document.querySelector("#input-numero-chapa");
//                 const inputDescricaoChapa = document.querySelector("#input-descricao-chapa");
//                 const inputCodChapa = document.querySelector("#input-cod-chapa");
//                 inputNumeroChapa.value = data.numeroChapa;
//                 inputDescricaoChapa.value = data.descricaoChapa;
//                 inputCodChapa.value = data.codChapa;
//             })
//             .catch((error) => console.error(error));
//     });
// });