
const origem = document.getElementById("origem");
const destino = document.getElementById("destino");
const resumo = document.getElementById("resumoTrajeto");

function atualizarTrajeto() {
    const textoOrigem =
        origem.options[origem.selectedIndex]?.text;

    const textoDestino =
        destino.options[destino.selectedIndex]?.text;

    if (!origem.value || !destino.value) {
        resumo.textContent = "Selecione a origem e o destino.";
        return;
    }

    if (origem.value === destino.value) {
        resumo.textContent =
            "Atenção: origem e destino não podem ser iguais.";
        return;
    }

    resumo.textContent =
        "Origem: " + textoOrigem +
        " | Destino: " + textoDestino;
}

origem.addEventListener("change", atualizarTrajeto);
destino.addEventListener("change", atualizarTrajeto);

atualizarTrajeto();
